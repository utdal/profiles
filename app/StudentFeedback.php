<?php

namespace App;

use App\StudentData;
use App\User;
use App\Traits\HasJsonRelationships;

class StudentFeedback extends StudentData
{
    use HasJsonRelationships;

    public const REASONS = [
        'availability' => "Availability doesn't match my needs",
        'interests' => "Interests don't match my research",
        'qualifications' => "Qualifications or experience doesn't match my needs",
        'preferences' => "Preferences (travel, credit, and etc.) don't match my needs",
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
