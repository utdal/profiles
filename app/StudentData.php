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

    /**
     * Get the student applications for a specific semester and returns an array with the total number of applications per faculty.
     *
     * @return array
     */
    public static function applications_count_by_faculty($semester)
    { 
        $apps_count = array(); 
        $apps = StudentData::with('student.faculty')->where('data->semesters', 'like', "%{$semester}%")->get();

        foreach ($apps as $app) {
            foreach ($app->student->faculty as $faculty) {

                $user = ['full_name' => $faculty->user->display_name, 'email' => $faculty->user->email ];
                $id = $faculty->user->id;

                if (Arr::exists($apps_count, $id)) {
                    $count = ++$apps_count[$id]['count'];
                }
                else {
                    $count = 1;
                    $apps_count = Arr::add($apps_count, $id, ['user' => $user] );

                    foreach ($faculty->user->currentReminderDelegates as $current_delegate) {
                        $delegate_user = ['full_name' => $current_delegate->display_name, 
                                            'email' => $current_delegate->email, 
                                            'delegator' => $faculty->user->display_name ];

                        $apps_count[$id]['delegates'][$current_delegate->id] = $delegate_user;
                    }

                }

                $apps_count[$id]['count'] = $count; 
                
            }
        }
        
        return $apps_count;
    }

}
