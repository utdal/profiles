<?php

namespace App;

use App\Profile;
use App\ProfileStudent;
use App\StudentData;
use App\StudentFeedback;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Tags\HasTags;

class Student extends Model implements Auditable
{
    use HasAudits, HasTags;

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

        if ($accepted) {
            $stats->insertData([
                'accepted_by' => [
                    "profile_{$profile->id}" => [
                        'profile' => $profile->id,
                        'profile_name' => $profile->full_name,
                    ],
                ],
                'accepted_on' => now()->toDateTimeString(),
            ]);
        } else {
            $stats->removeData("accepted_by.profile_{$profile->id}");
            if (isset($stats->accepted_by) && empty($stats->accepted_by)) {
                $stats->removeData('accepted_by');
            }
        }
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
        return $query->Where('profile_student.status', '=', 'not interested');
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
