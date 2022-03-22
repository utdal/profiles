<?php

namespace App;

use Illuminate\Support\Arr;
use App\ProfileData;
use App\Student;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

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

    /* Add another query scope for the where semester
    * array_key_exists(string|int $key, array $array) to ask if it's set
    * use the current semester helper in case the parameter is null
    */
    public static function student_data_count_by_faculty($semester)
    { 
        $student_data_count = array(); 
        $apps = StudentData::whereNotNull('data->faculty')->where('data->semesters', 'like', "%{$semester}%")->get();

        foreach ($apps as $app) {
            foreach ($app->data['faculty'] as $faculty) {
                $id = Profile::firstWhere('full_name', 'like', "%{$faculty}%")->user->id;
                if (array_key_exists($faculty, $student_data_count)) {
                    $student_data_count[$id] = ++$student_data_count[$faculty];
                }
                else {
                    $student_data_count[$id] = 1;
                }
            }
        }
        
        return $student_data_count;
    }

}
