<?php

namespace App;

use App\ProfileData;
use App\Student;
use Illuminate\Database\Eloquent\Model;

class StudentData extends ProfileData
{
    /** @var string The database table used by the model */
    protected $table = 'student_data';

    /** @var array attribute casting map */
    protected $casts = [
        'data' => 'array'
    ];

    /** @var array mass-assignment fillable attributes */
    protected $fillable = [
        'student_id',
        'type',
        'data',
        'sort_order',
    ];

    public const FEEDBACK_REASONS = [
        'availability' => "Availability doesn't match my needs",
        'interests' => "Interests don't match my research",
        'qualifications' => "Qualifications or experience doesn't match my needs",
        'preferences' => "Preferences (travel, credit, and etc.) don't match my needs",
    ];

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope for research profile
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeResearchProfile($query)
    {
        return $query->where('type', 'research_profile');
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * This belongs to one student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
