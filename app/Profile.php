<?php

namespace App;

use App\ProfileData;
use App\ProfileStudent;
use App\Providers\AcademicAnalyticsAPIServiceProvider;
use App\Providers\PublicationsApiServiceProvider;
use App\Student;
use App\User;
use Doctrine\DBAL\Types\IntegerType;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Profile extends Model implements HasMedia, Auditable
{
    use HasAudits;
    use HasFactory;
    use InteractsWithMedia;
    use HasTags;
    use SoftDeletes;

    /** @var string The database table used by the model. */
    protected $table = 'profiles';

    /** @var array Virtual attributes to include in array/json serialization */
    protected $appends = [
        'url',
        'name',
        'image_url',
        'api_url',
    ];

    /**
        * The attributes that should be cast to native types.
        *
        * @var array
        */
       protected $casts = [
           'public' => 'boolean',
       ];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
          'slug',
          'full_name',
          'first_name',
          'middle_name',
          'last_name',
          'active',
          'public'
      ];


    /////////////////////
    // General Methods //
    /////////////////////

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function ($query) {
            $query
              ->orderBy('last_name', 'asc')
              ->orderBy('first_name', 'asc');
        });
    }


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the relations that can be exposed via the API
     *
     * @return array
     */
    public static function apiRelations()
    {
        return [
            'information',
            'activities',
            'additionals',
            'affiliations',
            'appointments',
            'areas',
            'awards',
            'news',
            'preparation',
            'presentations',
            'projects',
            'publications',
            'support',
            'tags',
        ];
    }

    /**
     * Get the attributes that can be exposed via the API.
     *
     * @return array
     */
    public static function apiAttributes()
    {
        return [
            'id',
            'full_name',
            'first_name',
            'last_name',
            'slug',
            'public'
        ];
    }

    public function processImage($new_image, $collection = 'images'){
        if ($new_image) {
            $this->clearMediaCollection($collection);
            $this->addMedia($new_image)->toMediaCollection($collection);
            $message = 'Profile image has been updated.';
        } else {
            $message = 'Cannot update profile image.';
        }

        return $message;
    }

    /**
     * Checks if this profile has publications managed by ORCID
     *
     * @return bool
     */
    public function hasOrcidManagedPublications()
    {
        if ($this->relationLoaded('information')) {
            return (bool) ($this->information->first()->data['orc_id_managed'] ?? false);
        }

        return $this->information()->where('data->orc_id_managed', '1')->exists();
    }

    public function updateORCID()
    {
      $orc_id = $this->information()->get(array('data'))->toArray()[0]['data']['orc_id'];

      if(is_null($orc_id)){
        //can't update if we don't know your ID
        return false;
      }

      $orc_url = "https://pub.orcid.org/v2.0/" . $orc_id .  "/activities";

      $client = new Client();

      $res = $client->get($orc_url, [
        'headers' => [
          'Authorization' => 'Bearer ' . config('ORCID_TOKEN'),
          'Accept' => 'application/json'
        ],
        'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
      ]);

      //an error of some sort
      if($res->getStatusCode() != 200){
        return false;
      }

      $datum = json_decode($res->getBody()->getContents(), true);

      foreach($datum['works']['group'] as $record){
          $url = NULL;
          foreach($record['external-ids']['external-id'] as $ref){
            if($ref['external-id-type'] == "eid"){
              $url = "https://www.scopus.com/record/display.uri?origin=resultslist&eid=" . $ref['external-id-value'];
            }
            else if($ref['external-id-type'] == "doi"){
              $url = "http://doi.org/" . $ref['external-id-value'];
              $doi = $ref['external-id-value'];
            }
          }
          $new_record = ProfileData::firstOrCreate([
            'profile_id' => $this->id,
            'type' => 'publications',
            'data->title' => $record['work-summary'][0]['title']['title']['value'],
            'sort_order' => $record['work-summary'][0]['publication-date']['year']['value'] ?? null,
            'data->doi' => $doi,
          ],[
              'data' => [
                  'url' => $url,
                  'doi' => $doi,
                  'title' => $record['work-summary'][0]['title']['title']['value'],
                  'year' => $record['work-summary'][0]['publication-date']['year']['value'] ?? null,
                  'type' => ucwords(strtolower(str_replace('_', ' ', $record['work-summary'][0]['type']))),
                  'status' => 'Published'
              ],
          ]);
      }

      Cache::tags(['profile_data'])->flush();

      //ran through process successfully
      return true;
    }

    /**
     * Cache Academic Analytics publications for the current profile
     * @return Collection
     */
    public function cachedAAPublications()
    {
        $publications_provider = new PublicationsApiServiceProvider($this);
        $academic_analytics_publications = $publications_provider->getAcademicAnalyticsPublications();

        return Cache::remember(
            "profile{$this->id}-AA-pubs",
            15 * 60,
            fn() => $academic_analytics_publications
        );
    }

    /**
     *  Search for a publication by year and title within a given publications collection
     *  Return array with DOI and matching title
     * @return Array
     */
    public static function searchPublicationByTitleAndYear($title, $year, $publications)
    {
        $title = strip_tags(html_entity_decode($title));
        $publication_found = $aa_doi = $aa_title = null;
        $publication_found = $publications->filter(function ($item) use ($title, $year) {
            return (str_contains(strtolower($title), strtolower(strip_tags(html_entity_decode($item['data']['title'])))) && $year==$item['data']['year']);
            similar_text(strtolower($title), strtolower(strip_tags(html_entity_decode($item['data']['title']))), $percent);
            return (($percent > 80) && ($year==$item['data']['year']));
        });
        if ($publication_found->count() == 1) {
            $aa_doi = $publication_found->first()->doi;
            $aa_title = $publication_found->first()->title;
        }
        return [$aa_doi, $aa_title];
    }

    public function updateDatum($section, $request)
    {
      $sort_order = count($request->data ?? []) + 1;

      //iterate over each record
      foreach($request->data as $entry){
          $should_save = false;
          //do we have any data in the record and should we save it
          foreach($entry['data'] as $key => $value){
              if(!empty($value)){
                  $should_save = true;
                  break;
              }
          }

          //previously existing records
          if(isset($entry['id']) && !empty($entry['id']) && $entry['id'] > 0){
              $record = ProfileData::firstOrCreate([
                  'profile_id' => $this->id,
                  'id' => $entry['id'],
              ]);

              $record->public = $entry['public'] ?? true;
              if ($entry['data'] != $record->data) { // avoids extraneous save due to json reordering
                $record->data = $entry['data'];
              }
              $record->sort_order = $sort_order--;
              if ($record->type === 'information') {
                  $should_save = true;
              }
          }
          //new records
          else if($should_save){
              $record = ProfileData::create([
                  'profile_id' => $this->id,
                  'type' => $section,
                  'data' => $entry['data'],
                  'public' => $entry['public'] ?? true,
                  'sort_order' => $sort_order--
              ]);
          } else {
              $record = null;
          }

          if($should_save){
              $record->save();

              //this might need to be sequenced differently
              $new_file = $request->file("data.{$entry['id']}.image");
              //have a new file for this record
              if($new_file){
                  $record->clearMediaCollection('images');
                  $record->addMedia($new_file)->toMediaCollection('images');
              }
          } elseif ($record instanceof ProfileData && $record->type !== 'information') {
              $record->delete();
          }

      }

        // update overall profile visibility
        if ($section == 'information' && $request->hasAny(['public', 'full_name'])) {
            $this->update([
                'public' => $request->input('public') ?? $this->public,
                'full_name' => $request->input('full_name') ?? $this->full_name,
            ]);
        }

        Cache::tags(['profile_data'])->flush();
    }

    /**
     * Strips HTML tags from the specified data field.
     *
     * This is only for output purposes and does not save.
     *
     * @param array $data_names : the names of data properties to strip tags from
     * @param string $field : the field on each data property to strip tags from
     * @param boolean $loaded_only : only strip if the data relation has already been eager-loaded
     */
    public function stripTagsFromData($data_names, $field = 'description', $loaded_only = true)
    {
        foreach ($data_names as $data_name) {
            if ($loaded_only && !$this->relationLoaded($data_name)) {
                continue;
            }
            $this->$data_name->map(function($datum) use ($field) {
                $clean_data = $datum->data;
                $clean_data[$field] = strip_tags($datum->data[$field]);
                $datum->data = $clean_data;
            });
        }
    }

    /**
     * Eager load this Profile's API-accessible data.
     *
     * @return Profile
     */
    public function loadApiData()
    {
        return $this->load(['data' => function($query) {
            $query->addSelect(ProfileData::apiAttributes());
        }]);
    }

    /**
     * Registers media conversions.
     *
     * @param  Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->registerImageThumbnails($media, 'thumb', 150);
        $this->registerImageThumbnails($media, 'medium', 450);
        $this->registerImageThumbnails($media, 'large', 1800, 1200, '*');
    }

    /**
     * Registers image thumbnails.
     *
     * @param  Media|null $media
     * @param  string     $name       Name of the thumbnail
     * @param  int        $size       Max dimension in pixels
     * @param  string     $collection Name of the collection for the thumbnails
     * @return void
     */
    protected function registerImageThumbnails(Media $media = null, $name, $width, $height = null, $collection = 'images'): void
    {
        if(!$height) {
            $height = $width;
        }

        $this->addMediaConversion($name)
            ->width($width)
            ->height($height)
            ->crop(Manipulations::CROP_TOP, $width, $height)
            ->performOnCollections($collection);
    }

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope for public Profiles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('public', 1);
    }

    /**
     * Query scope for non-public Profiles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrivate($query)
    {
        return $query->where('public', 0);
    }

    /**
     * Query scope to eager load the Profiles along with their API-accessible data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array|string|null $sections  which data sections to load
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithApiData($query, $sections = null)
    {
        //add each meta-section to API eager load payload
        if ($sections === null) {
            $sections = self::apiRelations();
        } elseif (is_string($sections)) {
            $sections = explode(';', $sections);
        }

        return $query->with($sections);
    }

    /**
     * Query scope for Profiles that have data containing the provided string.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $search  what to search for
     * @param  string|null $type  restrict the search to data of the given type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeContaining($query, $search, $type = null)
    {
        return $query->whereHas('data', function ($data_query) use ($search, $type) {
            if ($type !== null) {
                $data_query->where('type', '=', $type);
            }
            $data_query->where(function($data_sub_query) use ($search) {
                $data_sub_query->where('data', 'LIKE', "%$search%");
                $data_sub_query->orWhere('data', 'LIKE', "%" . ucwords(strtolower($search)) . "%");
            });
        });
    }

    /**
     * Query scope for Profiles that have the given tag (case-insensitive)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTaggedWith($query, $tag, string $type = null)
    {
        $tag = strtolower($tag);
        $type = $type ?? static::class;

        return $query->whereHas('tags', function ($query) use ($tag, $type) {
            $query->where('type', $type);
            $query->whereRaw("LOWER(name->>'$.en') = ?", [$tag]);
        });
    }

    public function scopeWithName($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('full_name', 'LIKE', "%$search%");
            $q->orWhere('first_name', 'LIKE', "%$search%");
            $q->orWhere('last_name', 'LIKE', "%$search%");
            $q->orWhere('slug', 'LIKE', "%$search%");
        });
    }

    public function scopeFromSchool($query, $school)
    {
        return $query->whereHas('user', function($user_query) use ($school) {
            $user_query->withSchoolNamed($school);
        });
    }

    public function scopeFromSchoolId($query, $school_id)
    {
        return $query->whereHas('user', function($user_query) use ($school_id) {
            $user_query->withSchool($school_id);
        });
    }
    /**
     * Query scope for Profiles and eager load students whose application is pending review
     * for a given semester.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $semester
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEagerStudentsPendingReviewWithSemester($query, $semester)
    {
        return $query->with(['students' => function($eager_students) use ($semester) {
            $eager_students->submitted();
            $eager_students->withSemester($semester);
            $eager_students->WithStatusPendingReview();
        }]);
    }

    /**
     * Query scope for Profiles with students whose application is pending review
     * for a given semester.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $semester
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudentsPendingReviewWithSemester($query, $semester)
    {
        return $query->whereHas('students', function($query_students) use ($semester) {
            $query_students->submitted();
            $query_students->withSemester($semester);
            $query_students->WithStatusPendingReview();
        });
    }

    ///////////////////////////////////
    // Mutators & Virtual Attributes //
    ///////////////////////////////////


    /**
     * Get the full name. ($this->name)
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Get the full image URL. ($this->full_image_url)
     *
     * @return string
     */
    public function getFullImageUrlAttribute()
    {
        return url($this->getFirstMediaUrl('images') ?: '/img/default.png');
    }

    /**
     * Get the full image URL. ($this->large_image_url)
     *
     * @return string
     */
    public function getLargeImageUrlAttribute()
    {
        return url($this->getFirstMediaUrl('images', 'large') ?: '/img/default.png');
    }

    /**
     * Get the image URL. ($this->image_url)
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return url($this->getFirstMediaUrl('images', 'medium') ?: '/img/default.png');
    }

    /**
     * Get the image thumbnail URL. ($this->image_thumb_url)
     *
     * @return string
     */
    public function getImageThumbUrlAttribute()
    {
        return url($this->getFirstMediaUrl('images', 'thumb') ?: '/img/default.png');
    }

    /**
     * Get the banner image thumbnail. ($this->banner_url)
     *
     * @return string
     */
    public function getBannerUrlAttribute()
    {
        return url($this->getFirstMediaUrl('banners', 'large') ?: '/img/default.png');
    }

    /**
     * Get the profile URL. ($this->url)
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('profiles.show', ['profile' => $this->slug]);
    }

    /**
     * Get the profile API URL. ($this->api_url)
     *
     * @return string
     */
    public function getApiUrlAttribute()
    {
        return route('api.index', ['person' => $this->slug, 'with_data' => true]);
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * This belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * This has many data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data()
    {
        return $this->hasMany(ProfileData::class)
                    ->orderBy('data->year', 'desc')
                    ->orderBy('sort_order', 'desc');
    }

    /**
     * This has many students (many-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->using(ProfileStudent::class)
            ->withPivot('status')
            ->as('application')
            ->withTimestamps();
    }

    ///////////////////
    // Meta-Sections //
    ///////////////////

    /**
     * This has many information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function information()
    {
        return $this->data()->information();
    }

    /**
     * This has many preparations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function preparation()
    {
        return $this->data()->preparation();
    }

    /**
     * This has many awards.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function awards()
    {
        return $this->data()->awards();
    }

    /**
     * This has many areas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function areas()
    {
        return $this->data()->areas();
    }

    /**
     * This has many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function activities()
    {
        return $this->data()->activities();
    }

    /**
     * This has many news.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function news()
    {
        return $this->data()->news();
    }

    /**
     * This has many preparations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function appointments()
    {
        return $this->data()->appointments();
    }

    /**
     * This has many publications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function publications()
    {
        return $this->data()->publications();
    }

    /**
     * This has many affiliations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function affiliations()
    {
        return $this->data()->affiliations();
    }

    /**
     * This has many support.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function support()
    {
        return $this->data()->support();
    }

    /**
     * This has many projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function projects()
    {
        return $this->data()->projects();
    }

    /**
     * This has many additionals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function additionals()
    {
        return $this->data()->additionals();
    }

    /**
     * This has many presentations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function presentations()
    {
        return $this->data()->presentations();
    }

}
