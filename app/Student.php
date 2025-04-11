<?php

namespace App;

use App\Profile;
use App\ProfileStudent;
use App\Setting;
use App\StudentData;
use App\StudentFeedback;
use App\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Student extends Model implements Auditable
{
    use HasAudits;
    use HasFactory;
    use HasTags;

    /** @var string The database table used by the model */
    protected $table = 'students';

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
        'type',
        'status',
    ];

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
     * Whether this record was ever updated after creation
     *
     * @return bool
     */
    public function wasEverUpdated(): bool
    {
        return $this->updated_at->greaterThan($this->created_at);
    }

    /**
     * Get or create the stats record
     *
     * @return StudentData
     */
    public function firstStats()
    {
        return $this->stats()->firstOrCreate(['type' => 'stats'], ['data' => []]);
    }

    /**
     * Updates the Student Application status stats
     *
     * @param string|null $old_status
     * @param string|null $new_status
     * @param Profile $profile
     * @return void
     */
    public function updateStatusStats($old_status, $new_status, Profile $profile): void
    {
        $stats = $this->firstStats();

        if ($new_status) {
            $stats->incrementDatum("status.$new_status");
        }

        if ($old_status) {
            $stats->decrementDatum("status.$old_status", 1, false);
        }

        $stats->insertData(['status_history' => [[
            'old_status' => $old_status,
            'new_status' => $new_status,
            'profile' => $profile->id,
            'profile_name' => $profile->full_name,
            'updated_at' => now()->toDateTimeString(),
        ]]]);
    }

    public function updateAcceptedStats(Profile $profile, $accepted = true)
    {
        $stats = $this->firstStats();
        $accepted_key = "profile_{$profile->id}";

        if ($accepted) {
            if (!isset($stats->accepted_by[$accepted_key])) {
                $stats->insertData([
                    'accepted_by' => [
                        $accepted_key => [
                            'profile' => $profile->id,
                            'profile_name' => $profile->full_name,
                        ],
                    ],
                    'accepted_on' => now()->toDateTimeString(),
                ]);
            }
        } else {
            $stats->removeData("accepted_by.{$accepted_key}");
            if (isset($stats->accepted_by) && empty($stats->accepted_by)) {
                $stats->removeData('accepted_by');
            }
        }
    }

    public static function exportStudentApps(EloquentCollection $students)
    {
        $students->map(function ($student) {
            
            $st = clone $student;

            $st->email = $st->user->email;
            $st->link = "https://profiles.utdallas.edu/students/{$st->slug}";
            $st->brief_intro = $st->research_profile->brief_intro;
            $st->intro = $st->research_profile->intro;
            $st->interest = $st->research_profile->interest;
            $st->major = $st->research_profile->major;
            $st->schools = $st->research_profile->schools;
            $st->languages = $st->research_profile->languages;
            $st->languages_other = $st->research_profile->language_other_name;
            $st->languages_proficiency = $st->research_profile->lang_proficiency;
            $st->semesters = $st->research_profile->semesters;
            $st->availability = $st->research_profile->availability;
            $st->earn_credit = $st->research_profile->credit;
            $st->graduation_date = $st->research_profile->graduation_date;
            $st->bbs_travel_centers = $st->research_profile->travel;
            $st->bbs_travel_other = $st->research_profile->travel_other;
            $st->bbs_comfortable_animals = $st->research_profile->animals;
            $st->other_info = $st->research_profile->other_info;

            $st->earn_credit = match ($st->earn_credit) {
                '1' => 'credit',
                '0' => 'volunteer',
                '-1' => 'no preference',
                default => $st->earn_credit,
            };

            foreach ([
                'bbs_travel_centers',
                'bbs_travel_other',
                'bbs_comfortable_animals',
            ] as $yes_no_field) {
                $st->$yes_no_field = match ($st->$yes_no_field) {
                    '1' => 'yes',
                    '0' => 'no',
                    default => $st->$yes_no_field,
                };
            }

            $st->topics = $st->tags->pluck('name')->implode(", ");

            foreach($st->getAttributes() as $attr => $value) {
                if (is_array($value)) {
                    if (array_is_list($value)) {
                        $st->$attr = implode(", ", $value);
                    } else {
                        $json = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                        $json = str_replace([',', ':'], [', ', ': '], $json);
                        $json = str_replace(["\r", "\n"], ' ', $json);
                        $st->$attr = $json;
                    }
                }
                if ($value === null) {
                    $st->$attr = '';
                }
            }

            unset($st->id, $st->user_id, $st->type, $st->application, $st->tags, $st->research_profile, $st->user);

            return $st;
        });

        return $students;
    }

    /**
     * Increments the Student Application view count
     *
     * @return void
     */
    public function incrementViews()
    {
        $this->firstStats()->incrementDatum('views');
    }

    /**
     * Updates the Student Application last viewed stat
     *
     * @param string $datetime
     * @return void
     */
    public function updateLastViewed($datetime = '')
    {
        $this->firstStats()->updateData([
            'last_viewed' => $datetime ?: now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the list of schools participating in student research
     */
    public static function participatingSchools(): Collection|EloquentCollection
    {
        $names = json_decode(Setting::whereName('student_participating_schools')->first()?->value ?? "[]");

        return empty($names) ? collect([]) : School::withNames($names)->pluck('display_name', 'short_name');
    }

    /**
     * Get the list of possible tag types
     */
    public static function possibleTagTypes(): Collection
    {
        return static::participatingSchools()
            ->map(fn($display_name, $short_name) => "App\\Student\\{$short_name}");
    }

    /**
     * Get the list of possible tags
     */
    public static function possibleTags(): EloquentCollection
    {
        return Tag::whereIn('type', static::possibleTagTypes())
            ->orderBy('name->en')
            ->get();
    }

    /**
     * Get the list of tag types for this student application
     * based on its selected schools
     */
    public function tagTypes(): array
    {
        return collect($this->research_profile->schools ?? [])
            ->map(fn($shortname) => "App\\Student\\{$shortname}")
            ->all();
    }

    ////////////////////////////////////
    // Mutators and Virtual Attributes//
    ////////////////////////////////////

    /**
     * Get the student profile URL. ($this->url)
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('students.show', ['student' => $this]);
    }

    //////////////////
    // Query Scopes //
    //////////////////

    public function scopeDrafted($query)
    {
        return $this->scopeWithStatus($query, 'drafted');
    }

    public function scopeSubmitted($query)
    {
        return $this->scopeWithStatus($query, 'submitted');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('full_name', 'LIKE', "%$search%");
        }

        return $query;
    }

    public function scopeWithStatus($query, $status)
    {
        if ($status) {
            $query->where('students.status', '=', $status);
        }

        return $query;
    }

    public function scopeWithTag($query, $tag)
    {
        if ($tag) {
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('slug->en', '=', $tag);
                $q->orWhere('name->en', '=', $tag);
            });
        }

        return $query;
    }

    public function scopeWithFaculty($query, $faculty)
    {
        if ($faculty) {
            $query->whereHas('faculty', function ($q) use ($faculty) {
                $q->where('profiles.id', '=', $faculty);
            });
        }

        return $query;
    }
    
    public function scopeWithSchool($query, $school)
    {
        return $this->scopeDataContains($query, 'schools', $school);
    }

    public function scopeWithSemester($query, $semester)
    {
        return $this->scopeDataContains($query, 'semesters', $semester);
    }

    public function scopeWithLanguage($query, $language)
    {
        return $this->scopeDataContains($query, 'languages', $language);
    }

    public function scopeWithMajor($query, $major)
    {
        return $this->scopeDataContains($query, 'major', $major);
    }

    public function scopeWillTravel($query, $travel)
    {
        return $this->scopeDataEquals($query, 'travel', $travel);
    }

    public function scopeWillTravelOther($query, $travel)
    {
        return $this->scopeDataEquals($query, 'travel_other', $travel);
    }

    public function scopeWillWorkWithAnimals($query, $animals)
    {
        return $this->scopeDataEquals($query, 'animals', $animals);
    }

    public function scopeNeedsResearchCredit($query, $credit)
    {
        return $this->scopeDataEquals($query, 'credit', $credit);
    }

    public function scopeGraduatesOn($query, $graduation_date)
    {
        return $this->scopeDataEquals($query, 'graduation_date', $graduation_date);
    }

    public function scopeDataContains($query, $key, $value)
    {
        if ($value !== '') {
            $query->whereHas('research_profile', function ($q) use ($key, $value) {
                $q->whereJsonContains("data->{$key}", $value);
            });
        }

        return $query;
    }

    public function scopeDataEquals($query, $key, $value)
    {
        if ($value !== '') {
            $query->whereHas('research_profile', function ($q) use ($key, $value) {
                $q->where("data->{$key}", "=", $value);
            });
        }

        return $query;
    }

    public function scopeEverUpdated($query)
    {
        return $query->whereColumn('updated_at', '>', 'created_at');
    }

    /**
     * Query scope for Students whose application status is 'maybe later' or Null. To be used through the Profile relation.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatusPendingReview($query) 
    {
        return $query->whereNull('profile_student.status')
                     ->orWhere('profile_student.status', '=', 'maybe later');
    }

    /**
     * Query scope for Students whose application status is 'not interested'. To be used through the Profile relation.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatusNotInterested($query)
    {
        return $query->where('profile_student.status', '=', 'not interested');
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * Student belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Student has many data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data()
    {
        return $this->hasMany(StudentData::class);
    }

    /**
     * Student has many feedback.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback()
    {
        return $this->hasMany(StudentFeedback::class)->where('type', 'feedback');
    }

    /**
     * Student has one research profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function research_profile()
    {
        return $this->hasOne(StudentData::class)->where('type', 'research_profile');
    }

    /**
     * Student has one research profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stats()
    {
        return $this->hasOne(StudentData::class)->where('type', 'stats');
    }

    /**
     * Student has many faculty profiles (many-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function faculty()
    {
        return $this->belongsToMany(Profile::class)
            ->using(ProfileStudent::class)
            ->withPivot('status')
            ->as('applicaton')
            ->withTimestamps();
    }
}
