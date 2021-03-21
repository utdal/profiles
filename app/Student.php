<?php

namespace App;

use App\StudentData;
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
        return $query->where('status', '=', 'drafted');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', '=', 'submitted');
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
     * Student has one research profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function research_profile()
    {
        return $this->hasOne(StudentData::class)->where('type', 'research_profile');
    }
}
