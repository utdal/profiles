<?php

namespace App;

use App\StudentData;
use App\StudentFeedback;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Tags\HasTags;

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
            $query->where('status', '=', $status);
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
            $query->whereHas('research_profile', function ($q) use ($faculty) {
                $q->whereJsonContains('data->faculty', $faculty);
            });
        }

        return $query;
    }
    
    public function scopeWithSchool($query, $school)
    {
        if ($school) {
            $query->whereHas('research_profile', function ($q) use ($school) {
                $q->whereJsonContains('data->schools', $school);
            });
        }

        return $query;
    }

    public function scopeWithSemester($query, $semester)
    {
        if ($semester) {
            $query->whereHas('research_profile', function ($q) use ($semester) {
                $q->whereJsonContains('data->semesters', $semester);
            });
        }

        return $query;
    }

    public function scopeEverUpdated($query)
    {
        return $query->whereColumn('updated_at', '>', 'created_at');
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
}
