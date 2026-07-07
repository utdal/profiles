<?php

namespace App;

use App\Enums\ProfileType;
use App\ProfileData;
use App\ProfileStudent;
use App\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @method public()
 * @method private()
 * @method withApiData(array|string|null $sections)
 * @method containing(string $search, string $type = null)
 * @method taggedWith(string $tag, string $type = null)
 * @method withName(string $search)
 * @method fromSchool(string $school)
 * @method fromSchoolId(int $id)
 * @method eagerStudentsPendingReviewWithSemester(string $semester)
 * @method studentsPendingReviewWithSemester(string $semester)
 */
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
        'type' => ProfileType::class,
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
        'public',
        'type',
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

    /**
     * Updates the current user's publications by importing data from the ORCID API.
     *
     * This method fetches ORCID works, checks for duplicates using various identifiers (DOI, EID),
     * and imports new publications or updates existing ones accordingly. 
     *
     * @return array
     */
    public function updateORCID()
    {
        // Initializing counters
        $updated = $created = 0;
        $exact_id_match = $contained_id_url_match = $exact_title_match = $contained_title_match = 0;
        $no_url_count = $similar_title_count = 0;

        $orcid_works = $this->fetchOrcidWorks()['group'];
        $orcid_works_count = count($orcid_works);

        /** @var \Illuminate\Database\Eloquent\Collection */
        $current_publications = $this->publications()->get();

        Log::info("STARTING ORCID update for {$this->full_name} ‼️‼️");
        
        foreach ($orcid_works as $record) {
            $existing_pub = null;
            $similar_title_found = collect();

            $work_summary = self::getBestWorkSummary($record['work-summary']);

            $doi_record = self::getIdentifier($work_summary, 'doi');
            $eid_record = self::getIdentifier($work_summary, 'eid');

            $title = $work_summary['title']['title']['value'] ?? null;
            $year = $work_summary['publication-date']['year']['value'] ?? null;
            $month = $work_summary['publication-date']['month']['value'] ?? null;
            $day = $work_summary['publication-date']['day']['value'] ?? null;

            if ($current_publications->isNotEmpty()) {
                
                $no_url_count = $current_publications->filter(function ($pub) {
                                    return empty(data_get($pub, 'data.url'));
                                })->count();

                // Searching by title and date to save the count of similar matches
                $results_by_title_and_date = self::searchPublicationByTitleAndDate($title, $month, $day, $year, $current_publications);
                
                if (isset($doi_record['id'])) { // Start searching by DOI
                    $existing_pub = self::searchPublicationByPubIdentifier($doi_record['id'], 'doi', $current_publications, $exact_id_match, $contained_id_url_match); // Search by DOI in both, id and url
                }

                if (!$existing_pub && isset($eid_record['id'])) { // If not publciation was found, then search by EID
                    $existing_pub = self::searchPublicationByPubIdentifier($eid_record['id'], 'eid', $current_publications, $exact_id_match, $contained_id_url_match); // Search by EID in both, id and url
                }

                if ($existing_pub) {
                    $similar_title_found = $results_by_title_and_date['exact_title_matching']
                                            ->merge($results_by_title_and_date['contained_title_matching'])
                                            ->merge($results_by_title_and_date['similar_title_matching'])
                                            ->reject(function($item) use ($existing_pub) {
                                                return $item->id === $existing_pub->id;
                                            });
                } 
                else {
                    if ($results_by_title_and_date['exact_title_matching']->isNotEmpty()) {
                        
                        $existing_pub = $results_by_title_and_date['exact_title_matching']->first();
                        $exact_title_match++;
                        
                        Log::info($results_by_title_and_date['exact_title_matching']->first()->message);

                        $similar_title_found = $results_by_title_and_date['contained_title_matching']
                                                ->merge($results_by_title_and_date['similar_title_matching']);
                    }
                    elseif ($results_by_title_and_date['contained_title_matching']->isNotEmpty()) {
                        $existing_pub = $results_by_title_and_date['contained_title_matching']->first();
                        $contained_title_match++;

                        Log::info($results_by_title_and_date['contained_title_matching']->first()->message);

                        $similar_title_found = $results_by_title_and_date['similar_title_matching'];
                    }
                }
                
                $similar_title_count += $similar_title_found->count();
            }

            if (!isset($doi_record['id']) && !isset($eid_record['id'])) {
                $additional_identifier = self::getIdentifier($work_summary);
            }

            $identifiers = array_filter(
                                [$doi_record, $eid_record, $additional_identifier ?? null],
                                function ($record) {
                                    if (!is_array($record)) return false;
                                    return isset($record['id']) && isset($record['id_type']);
                                }
                            );

            $data = [
                        'title' => $work_summary['title']['title']['value'],
                        'year' => $work_summary['publication-date']['year']['value'] ?? null,
                        'publication_date' => compact('year', 'month', 'day'),
                        'type' => ucwords(strtolower(str_replace('_', ' ', $work_summary['type']))),
                        'status' => 'Published',
                        'put-code' => $work_summary['put-code'],
                        'identifiers' => $identifiers,
                        'source' => 'orcid',
                        'source_id' => $work_summary['source']['source-client-id']['uri'] ?? null,
                        'source_path' => $work_summary['source']['source-client-id']['path'] ?? null,
                        'published_in' => $work_summary['journal-title']['value'] ?? null,
                        'orginal_source' => $work_summary['source']['source-name']['value'] ?? null,
                    ];

            //If the publication date is null then use the existing pub year to calculate the sort order
            $sort_order = self::getSortOrder(...array_values($data['publication_date'] ?? ['year' => $existing_pub->year]));

            $this->updateOrInsertPublication($data, $sort_order, $existing_pub, $created, $updated);
        }

        Log::info("Existing publications: {$current_publications->count()}, ORCID API publications: {$orcid_works_count}");

        foreach (compact('updated', 'created', 'exact_id_match', 'contained_id_url_match', 'exact_title_match', 'contained_title_match', 'similar_title_count') as $key => $value) {
            $key = strtoupper($key);
            Log::info("Total {$key} publications: {$value} ");
        }

        Log::info("ORCID update for {$this->full_name} completed ✅");

        Cache::tags(['profile-{$this->id}-current_publications'])->flush();
        Cache::tags(['profile_data'])->flush();

        return array_merge(['completed' => true], 
                            compact(
                                'orcid_works_count',
                                'created',
                                'updated',
                                'exact_id_match',
                                'contained_id_url_match',
                                'exact_title_match',
                                'contained_title_match',
                                'no_url_count',
                                'similar_title_count')
                            );
    }

    /**
     * Fetches the list of works (publications) from the ORCID API for the current user.
     * Returns false if the ORCID ID is missing or the API request fails, the method returns false.
     * Otherwise, returns an associative array of works data.
     * 
     * @return array|false
     */
    protected function fetchOrcidWorks()
    {
        $orc_id = $this->information()->get(array('data'))->toArray()[0]['data']['orc_id'];

        if (!$orc_id) {
            return false;
        }

        $orc_url = "https://pub.orcid.org/v3.0/$orc_id/works";

        $client = new Client();

        $response = $client->get($orc_url, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . config('ORCID_TOKEN'),
                                    'Accept' => 'application/json'
                                ],
                                'http_errors' => false,
                            ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
    
    /**
     * Returns the best work summary from an array of ORCID work summaries.
     * If there's only one summary, it is returned. Otherwise, the summaries are sorted
     * by `display-index` in descending order, and the first summary is returned.
     *
     * @param array $work_summaries
     * @return array|null
     */
    protected static function getBestWorkSummary($work_summaries)
    {
        if (count($work_summaries) === 1) {
            return $work_summaries[0];
        }

        $sorted = collect($work_summaries)
                    ->sortByDesc('display-index')
                    ->values();

        return $sorted->first();
    }

    /**
     * If a specific type key is provided, it looks for an external ID of that type with a relationship of 'self'. 
     * If no type key is given, it returns the first external ID with a 'self' relationship for a work summary.
     * @param array $work_summary
     * @param string|null $type_key
     * @return array{
     *     id: string|null,
     *     id_type: string|null,
     *     id_url: string|null
     * }
     */
    protected static function getIdentifier($work_summary, $type_key = null)
    {
        $id_record = collect($work_summary['external-ids']['external-id'] ?? null)->first(function($ext_id) use ($type_key) {
            if ($type_key) {
                return $ext_id['external-id-type'] === $type_key && $ext_id['external-id-relationship'] === 'self';
            }
            return $ext_id['external-id-relationship'] === 'self';
        });

        $id = $id_record['external-id-normalized']['value'] ?? null;
        $id_url = $id_record['external-id-url']['value'] ?? null;
        $id_type = $type_key ?? ($id_record['external-id-type'] ?? 'unknown');

        return compact('id', 'id_type', 'id_url');
    }

    /**
     * Search publication by exact identifier, if not found, then it searches for the identifier within the URL.
     * 
     * @param string $id
     * @param string $id_type
     * @param \Illuminate\Database\Eloquent\Collection $publications
     * @return \App\ProfileData|null Returns the matched publication or null if none found.
     */
    protected static function searchPublicationByPubIdentifier($id, $id_type, $publications, &$exact_id_match, &$contained_id_url_match)
    {
        $id = strtolower($id);

        return $publications->first(function ($publication) use ($id, $id_type, &$exact_id_match, &$contained_id_url_match) {
            $pub_id = strtolower($publication->data['id'] ?? '');
            $pub_url = strtolower($publication->data['url'] ?? '');

            if ($pub_id && $pub_id === $id) {
                $exact_id_match++;
                Log::info("Publication matched: exact ID match for {$id_type}, {$pub_id}");
                return true;
            }
            
            if ($pub_url && str_contains($pub_url, $id)) {
                $contained_id_url_match++;
                Log::info("Publication matched: ID found in URL, for {$id} in {$pub_url} for {$publication->id}");
                return true;
            }

            return false;
        });
    }

    /**
     * Searches for a publication by matching title and publication date.
     * It checks for exact, contained, or similar title matches. Also collects similar title matches (if year matches) in a separate group for reference.
     *
     * @param string $title
     * @param string|null $month
     * @param string|null $day
     * @param string|null $year
     * @param \Illuminate\Database\Eloquent\Collection $existing_publications
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function searchPublicationByTitleAndDate(string $title, ?string $month, ?string $day, ?string $year, $existing_publications)
    {
        /** @var \Illuminate\Support\Collection */
        $results = [
                    'exact_title_matching' => collect(),
                    'contained_title_matching' => collect(),
                    'similar_title_matching' => collect(),
                    ];

        foreach ($existing_publications as $existing_pub) {
            $data = $existing_pub->data;
            $existing_title = strtolower($data['title'] ?? '');

            $pub_date = $data['publication_date'] ?? [];

            $pub_day = $pub_date['day'] ?? null;
            $pub_month = $pub_date['month'] ?? null;
            $pub_year = $pub_date['year'] ?? null;

            $pub_year = $pub_year ?: $data['year'];

            if ($pub_year !== $year) {
                continue; // Continue loop if year doesn't match
            }

            $title_match_type = self::getTitleMatchType(strtolower($title), $existing_title);

            if (!$title_match_type) {
                continue; // Continue loop if title doesn't match
            }

            if ($month && $day && $pub_month === $month && $pub_day === $day) {
                $existing_pub->message = "Matching publication found {$existing_pub->id} by $title_match_type title and full pubblication date: {$title}, {$year}, {$month}, {$day}";
            }

            if ($month && $pub_month === $month) {
                $existing_pub->message = "Matching publication {$existing_pub->id} found by $title_match_type title and month: {$title}, {$month}";
            }

            $existing_pub->message = "Matching publication {$existing_pub->id} found by $title_match_type title and year: {$title}, {$year}";

            $results["{$title_match_type}_title_matching"]->push($existing_pub);
        }

        if ($results['exact_title_matching']->isEmpty() && $results['contained_title_matching']->isEmpty()) {
            Log::warning("No matching publication found for: {$title}, {$year}, {$month}, {$day}");
        }
        
        return $results;
    }

    /**
     * Determines the match type between two publication titles.
     *
     * @param string $new_title
     * @param string $existing_title
     * @return string|false Returns the match type ('exact', 'contained', 'similar') or false if no match.
     */
    protected static function getTitleMatchType(string $new_title, string $existing_title)
    {
        if ($new_title === $existing_title) {
            return 'exact';
        }

        if (str_contains($new_title, $existing_title) || str_contains($existing_title, $new_title)) {
            return 'contained';
        }

        similar_text($new_title, $existing_title, $percent);

        return ($percent >= 93 && $percent <= 97) ? 'similar' : false;
    }

    /**
     * Calculates an integer value used to sort publications in reverse chronological order.
     * It converts a given date (year, month, day) into a numeric value such that more recent dates have lower values.
     *
     * @param int|string $year
     * @param int|string|null $month
     * @param int|string|null $day
     * @return int An integer representing the reverse chronological sort order.
     */
    protected static function getSortOrder($year, $month = null, $day = null) 
    {
        $year = (int) $year;
        $month = $month !== null ? str_pad((string) $month, 2, '0', STR_PAD_LEFT) : '00';
        $day = $day !== null ? str_pad((string) $day, 2, '0', STR_PAD_LEFT) : '00';

        $rev_year = 9999 - $year;

        if ($month === '00') {
            return (int) sprintf('%04d0000', $rev_year);
        }

        $rev_month = 12 - (int) $month;

        if ($day === '00') {
            return (int) sprintf('%04d%02d00', $rev_year, $rev_month);
        }

        $rev_day = 31 - (int) $day;

        return (int) sprintf('%04d%02d%02d', $rev_year, $rev_month, $rev_day);
    }

    /**
     * Updates an existing publication or inserts a new one.
     * 
     * @param array $data
     * @param \App\ProfileData|null $existing_pub
     * @param int &$created
     * @param int &$updated
     * @return bool
     */
    protected function updateOrInsertPublication($data, $sort_order, $existing_pub, &$created, &$updated)
    {
        if ($existing_pub) {
            unset($existing_pub->message);
            $data = array_merge($data, ['url' => $existing_pub->data['url'] ?? null ]); 
            
            $existing_pub->update([
                'data' => $data,
                'sort_order' => $sort_order,
            ]);

            $updated++;
            Log::info("Updated best match publication for id: {$existing_pub->id}");
        } 
        else {
            $existing_pub = $this->publications()->create([
                'data' => $data,
                'sort_order' => $sort_order,
                'type' => 'publications',
            ]);

            $created++;
            Log::info("Created new publication (no best match found) for title: {$data['title']}");
        }

        return true;
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

        // update overall profile record
        if ($section == 'information' && $request->hasAny(['public', 'type', 'full_name'])) {
            $this->update([
                'public' => $request->input('public') ?? $this->public,
                'type' => match ($request->input('type')) {
                    '0' => ProfileType::Default,
                    '1' => ProfileType::Unlisted,
                    '2' => ProfileType::InMemoriam,
                    default => $this->type,
                },
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

    public function isType(ProfileType $type): bool
    {
        return $this->type === $type;
    }

    public function isDefault(): bool
    {
        return $this->isType(ProfileType::Default);
    }

    public function isUnlisted(): bool
    {
        return $this->isType(ProfileType::Unlisted);
    }

    public function isInMemoriam(): bool
    {
        return $this->isType(ProfileType::InMemoriam);
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
     * Query scope for Profiles of a particular type
     */
    public function scopeOfType(Builder $query, ProfileType $type): void
    {
        $query->where('type', $type->value);
    }

    /**
     * Query scope for Profiles excluding a particular type
     */
    public function scopeExcludingType(Builder $query, ProfileType $type): void
    {
        $query->whereNot('type', $type->value);
    }

    /**
     * Query scope for Profiles of default/normal type
     */
    public function scopeDefault(Builder $query): void
    {
        $query->ofType(ProfileType::Default);
    }

    /**
     * Query scope for unlisted Profiles
     */
    public function scopeUnlisted(Builder $query): void
    {
        $query->ofType(ProfileType::Unlisted);
    }

    /**
     * Query scope for unlisted Profiles
     */
    public function scopeInMemoriam(Builder $query): void
    {
        $query->ofType(ProfileType::InMemoriam);
    }

    /**
     * Query scope for excluding unlisted Profiles
     */
    public function scopeExcludingUnlisted(Builder $query): void
    {
        $query->excludingType(ProfileType::Unlisted);
        $query->excludingType(ProfileType::InMemoriam);
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
    /**
     * Query scope for Profiles that are accepting undergrad students,
     * i.e. not marked as "Not accepting undergrad students" nor "Not accepting students".
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAcceptingUndergradStudents($query) {
        return $query
            ->whereDoesntHave("information", function ($q) {
                $q->whereJsonContains("data->not_accepting_students", "1");
            })
            ->whereDoesntHave("information", function ($q) {
                $q->whereJsonContains("data->show_not_accepting_students", "1")
                  ->whereJsonContains("data->not_accepting_students", "0")
                  ->whereJsonContains("data->not_accepting_grad_students", "0");
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
