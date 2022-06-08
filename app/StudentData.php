<?php

namespace App;

use App\ProfileData;
use App\Setting;
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

    /** @var array Language choices */
    public static $languages = [
        'ar' => 'Arabic',
        'bn' => 'Bengali',
        'zh' => 'Chinese',
        'en' => 'English',
        'fr' => 'French',
        'de' => 'German',
        'hi' => 'Hindi',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'pt' => 'Portugese',
        'ru' => 'Russian',
        'es' => 'Spanish',
        'tl' => 'Tagalog',
        'vi' => 'Vietnamese',
        'other' => 'Other',
    ];

    /** @var array Language proficiency levels */
    public static $language_proficiencies = [
        'limited' => 'Limited Working',
        'basic' => 'Professional Working',
        'professional' => 'Full Professional',
        'native' => 'Native / Bilingual',
    ];

    /**
     * Student majors choices
     *
     * @return \Illuminate\Support\Collection
     */
    public static function majors()
    {
        $setting_majors = optional(Setting::whereName('student_majors')->first())->value;
        $majors = $setting_majors ? preg_split("/[\r\n]+/", $setting_majors) : [];

        return collect($majors)->combine($majors);
    }

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope for research profile
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeResearchProfile($query)
    {
        return $query->where('type', 'research_profile');
    }

    /**
     * Query scope for research profile
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeStats($query)
    {
        return $query->where('type', 'stats');
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