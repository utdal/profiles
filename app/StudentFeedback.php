<?php

namespace App;

use App\StudentData;
use App\User;
use App\Traits\HasJsonRelationships;

class StudentFeedback extends StudentData
{
    use HasJsonRelationships;

    public const REASONS = [
        'availability' => "Availability doesn't match current lab needs",
        'interests' => "Research interests are incompatible or not fully aligned with current projects",
        'qualifications' => "Qualifications or experience doesn't match current lab needs",
        'preferences' => "Preferences (travel, credit, and etc.) don't match current lab needs",
        'no_openings' => "I am not accepting students at this time and/or all lab positions have been filled",
    ];

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::creating(function($student_data) {
            $student_data->type = 'feedback';
        });
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
        return $this->belongsTo(User::class, 'data->submitted_by');
    }
}
