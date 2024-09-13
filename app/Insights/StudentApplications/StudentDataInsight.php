<?php

namespace App\Insights\StudentApplications;

use App\Helpers\Semester;
use App\Insights\Insight;
use App\Insights\InsightDataBuilder;
use App\Student;
use App\StudentData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class StudentDataInsight extends Insight
{
    // public function getDataSet(string $criteria, ?string $type = null, ?string $status = 'submitted', ?string $stats_progress = null)
    public function getDataSet(?array $semesters_params = [], ?array $schools_params = [], ?string $type = null, ?string $status = 'submitted')
    {
        $insight_parameters[] = [
                'label' => 'Applications for Semester',
                'type' => 'bar',
                'backgroundColor' => 'rgba(15,64,97,255)',
                'borderColor' => 'rgba(15,64,97,255)',
                // 'data' => $this->getDataSemesterAndSchool($semesters_params, $schools_params, $type, $status),
                //'labels' => $this->getLabels($semester),
            ];

        return $this->getDataArray($insight_parameters);
    }

    //Query to retrieve student applications for given semesters and schools 
    public function appsForSemestersAndSchools($semesters_params, $schools_params)
    {
        return Student::query()
                ->submitted()
                    ->withWhereHas('research_profile', function($q) use ($semesters_params, $schools_params) {
                        $q->where(function($q) use ($semesters_params) {
                            foreach ($semesters_params as $semester) {
                                $q->orDataContains('semesters', $semester);
                            }
                        });
                        $q->where(function($q) use ($schools_params) {
                            foreach ($schools_params as $school) {
                                $q->orDataContains('schools', $school);
                            }
                        });
                    });
    }

    /**
    *  DOUGHNUT CHART #1 DATA - APPLICATIONS PERCENTAGE BY FILING STATUS FOR SEMESTERS AND SCHOOLS
    */

    public function getCachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);
        $fs = implode('-', $filing_status_params);
        
        return Cache::remember(
            "student-apps-for-semesters-schools-with-flstatus-{$sm}-{$sch}-{$fs}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)
                         ->withWhereHas('stats', function($q) use ($filing_status_params) {
                            $q->whereNotNull('data->status_history');
                            $q->where(function($q) use ($filing_status_params) {
                                foreach ($filing_status_params as $key => $filing_status) {
                                    $q = $key == 0 ? 
                                    $q->whereJsonContains('data->status_history', ['new_status' => $filing_status]) : 
                                    $q->orWhereJsonContains('data->status_history', ['new_status' => $filing_status]);
                                }
                            });
                    })->get()
        );
    }

    public function getCachedAppsForSemestersAndSchoolsWithoutFilingStatuses($semesters_params, $schools_params, $filing_status_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);
        
        return Cache::remember(
            "student-apps-for-semesters-schools-without-flstatus-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)
                         ->withWhereHas('stats', function($q) use ($filing_status_params) {
                            $q->whereNot(function($q) use ($filing_status_params) {
                                foreach ($filing_status_params as $key => $filing_status) {
                                    $q = $key == 0 ? 
                                    $q->whereJsonContains('data->status_history', ['new_status' => $filing_status]) : 
                                    $q->orWhereJsonContains('data->status_history', ['new_status' => $filing_status]);
                                }
                            });
                    })->get()
        );
    }

    public function groupAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params, $student_apps)
    {
        $semesters_params_start_end = $this->semestersParamsStartAndEnd($semesters_params);

        $filing_status_count = 0;

        foreach ($semesters_params_start_end as $semester_start_end) {
            $start_date = $semester_start_end['start'];
            $end_date = $semester_start_end['end'];
        
            $filing_status_count = $filing_status_count +
                $student_apps->where(function($app) use ($filing_status_params, $start_date, $end_date, &$filing_status_count) {
                    return collect($app->stats->data['status_history'])
                    ->groupBy('profile')
                    ->where(function($group) use ($app, $filing_status_params, $start_date, $end_date, &$filing_status_count) {
                        $last_update = $group->sortByDesc(function ($item) {
                            return Carbon::parse($item['updated_at']);
                        })->first();
                        $filing_date = Carbon::parse($last_update['updated_at']);
                        // if (in_array($last_update['new_status'], $filing_status_params) && $filing_date->between($start_date, $end_date)) {
                        //     // dump($app->stats->id);
                        //     $filing_status_count = $filing_status_count + 1;
                        // }
                        return (in_array($last_update['new_status'], $filing_status_params) && $filing_date->between($start_date, $end_date));
                    })->count();
                })->count();
                
        }
        return $filing_status_count;
    }

    public function getAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params)
    {
        $student_apps = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params);
        $filing_status_params_total = $this->groupAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params, $student_apps);

        $filing_status_params = ['maybe later', 'not interested'];
        $student_apps = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params);
        $others_total = $this->groupAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params, $student_apps);

        // $filing_status_params_total =  $grouped_student_apps_with_flstatus;
        // $others_total =  $grouped_student_apps_without_flstatus;
        // $filing_status_params_total =  array_sum($grouped_student_apps_with_flstatus);
        // $others_total =  array_sum($grouped_student_apps_without_flstatus);

        // $total = $filing_status_params_total + $others_total;

        // if ($total > 0) { // Avoid division by zero
        //     $result[0] = round(($filing_status_params_total / $total) * 100);
        //     $result[1] = round(($others_total / $total) * 100);
        // } else {
        //     $result[0] = 0;
        //     $result[1] = 0;
        // }
        return [$filing_status_params_total, $others_total];
    }

    /**
     *  DOUGHNUT CHART #2 DATA - APPLICATIONS COUNT VIEWED AND NOT FOR SEMESTERS AND SCHOOLS
     */

     public function getCachedAppsForSemestersAndSchoolsNotViewed($semesters_params, $schools_params)
     {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);
        
        return Cache::remember(
            "student-apps-for-semesters-schools-not-viewed-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)
                        ->where(function($query) {
                            $query->whereHas('stats', function($q) {
                                $q->whereNull('data->views');
                            });
                            $query->orDoesntHave('stats');
                        })->count()
        );
    }

    public function getCachedAppsForSemestersAndSchoolsViewed($semesters_params, $schools_params)
     {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-viewed-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)
                        ->where(function($query) {
                            $query->whereHas('stats', function($q) {
                                $q->whereNotNull('data->views');
                                $q->where('data->views', '>', 0);
                            });
                        })->count()
        );
    }

    public function getViewedAndNotViewedApps(array $semesters_params, array $schools_params,)
    {
        $result = [];

        $submitted_not_viewed = $this->getCachedAppsForSemestersAndSchoolsNotViewed($semesters_params, $schools_params);
        
        $submitted_and_viewed = $this->getCachedAppsForSemestersAndSchoolsViewed($semesters_params, $schools_params);

        // $total = $submitted_and_viewed + $submitted_not_viewed;

        // if ($total > 0) { // Avoid division by zero
        //     $result[0] = round(($submitted_and_viewed / $total) * 100);
        //     $result[1] = round(($submitted_not_viewed / $total) * 100);
        // } else {
        //     $result[0] = 0;
        //     $result[1] = 0;
        // }

        return [
                'labels' => [ 'Viewed', 'Not Viewed' ],
                'datasets' => [$submitted_and_viewed, $submitted_not_viewed],
            ];
    }

    /**
     *  BAR CHART #3 DATA - APPLICATIONS COUNT BY SEMESTER AND SCHOOL
     */

    public function getCachedAppsForSemestersAndSchools($semesters_params, $schools_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)->get()
        );
    }

    public function groupAppsBySemestersAndSchools(array $semesters_params, array $schools_params)
    {
        $student_applications = $this->getCachedAppsForSemestersAndSchools($semesters_params, $schools_params);

        return $student_applications->flatMap(function ($application) use ($semesters_params, $schools_params) {
            
            if (!empty($application->research_profile->data['semesters']) && !empty($application->research_profile->data['schools'])) {

                $semesters_values = array_intersect($application->research_profile->data['semesters'], $semesters_params);
                $schools_values = array_intersect($application->research_profile->data['schools'], $schools_params);

                    return array_map(function ($school_value) use ($application, $semesters_values) {
                        return array_map(function ($semester_value) use ($application, $school_value) {
                            return [
                                        'id' => $application->id,
                                        'semester' => $semester_value,
                                        'school' => $school_value,
                                    ];
                            }, $semesters_values);
                        }, $schools_values);
            } 
        })->flatten(1);
    }

    public function getAppsBySemestersAndSchools(array $semesters_params, array $schools_params, $type, $status)
    {
        $applications = $this->groupAppsBySemestersAndSchools($semesters_params, $schools_params);

        $counted_apps = $applications
                            ->groupBy(['semester', 'school'])
                            ->map(function ($semester_group) {
                                return $semester_group->map(function ($school_group) {
                                    return $school_group->count();
                                });
                            });
        $semesters_sort_closure = Semester::sortCollectionWithSemestersKeyChronologically();
        $all_semesters = $applications->pluck('semester')->unique()->sort()->values();
        // $all_semesters = $applications->pluck('semester')->unique()->sortBy($semesters_sort_closure)->values();
        $all_schools = $applications->pluck('school')->unique()->sort()->values();
                            
        $datasets = $all_schools->mapWithKeys(function ($school) use ($all_semesters) { // Initialize datasets for each school
            return [  $school => ['label' => $school, 'data' => array_fill(0, $all_semesters->count(), 0)]];
        })->toArray();

        // $sorted_apps = $counted_apps->sortKeysUsing(Semester::sortCollectionWithSemestersKeyChronologically());
        foreach ($counted_apps as $semester => $school_counts) {
            $semester_index = $all_semesters->search($semester);
            foreach ($school_counts as $school => $count) {
                $datasets[$school]['data'][$semester_index] = $count;
            }
        }

        return [
            'labels' => $all_semesters->toArray(),
            'datasets' => array_values($datasets),
        ];

    }

    /**
     *  BAR CHART #4 DATA - APPLICATIONS COUNT BY SEMESTER, SCHOOL AND FILING STATUS
     */

    public function getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-with-flstatus-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)->with('stats')->get()
        );
    }

    public function groupAppsBySemestersAndSchoolsWithFilingStatus(array $semesters_params, array $filing_status_params, array $schools_params)
    {
        $student_applications = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params);

        return $student_applications->flatMap(function ($application) use ($semesters_params, $filing_status_params) {
            
            if (!empty($application->research_profile->data['semesters']) && !empty($application->stats->data['status'])) {

                $semesters_values = array_intersect($application->research_profile->data['semesters'], $semesters_params);
                $status_values = array_intersect(array_keys($application->stats->data['status']), $filing_status_params);

                    return array_map(function ($status_value) use ($application, $semesters_values) {
                        return array_map(function ($semester_value) use ($application, $status_value) {
                            return [
                                        'id' => $application->id,
                                        'semester' => $semester_value,
                                        'status' => $status_value,
                                        'status_count' => $application->stats->data['status'][$status_value],
                                    ];
                            }, $semesters_values);
                        }, $status_values);
            } 
        })->flatten(1);
    }

    public function getAppsBySemestersAndSchoolsWithFilingStatus(array $semesters_params, array $filing_status_params, array $schools_params, $type, $status)
    {
        $applications = $this->groupAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $filing_status_params, $schools_params);

        $counted_apps = $applications
                            ->groupBy(['semester', 'status'])
                            ->map(function ($semester_group) {
                                return $semester_group->map(function ($status_group) {
                                    return $status_group->sum('status_count');
                                });
                            });

        $all_semesters = $applications->pluck('semester')->unique()->sort()->values();
        $all_filing_statuses = $applications->pluck('status')->unique()->sort()->values();
        
        $datasets = $all_filing_statuses->mapWithKeys(function ($filing_status) use ($all_semesters) { // Initialize datasets for each status
            return [$filing_status => ['label' => $filing_status, 'data' => array_fill(0, $all_semesters->count(), 0)]];
        })->toArray();

        foreach ($counted_apps as $semester => $filing_status_counts) {
            $semester_index = $all_semesters->search($semester);
            foreach ($filing_status_counts as $filing_status => $count) {
                $datasets[$filing_status]['data'][$semester_index] = $count;
            }
        }

        return [
            'labels' => $all_semesters->toArray(),
            'datasets' => array_values($datasets),
        ];
    }

    /** AUXILIARY METHODS */
    public static function getLabels(string $criteria) 
    {
        switch ($criteria) {
            case 'school':
                return StudentData::uniqueValuesFor('research_profile', 'schools')->sort()->values();
                break;
            case 'semester':
                return StudentData::uniqueValuesFor('research_profile', 'semesters')
                    ->sortBy(function($semester, $key) {
                        return Semester::date($semester)?->toDateString();
                    })
                    ->values();
                break;
            case 'faculty':
                return StudentData::uniqueValuesFor('research_profile', 'faculty')
                    ->sort()->values();
                break;
            default:
                $semesters = StudentData::uniqueValuesFor('research_profile', 'semesters')
                                ->map(fn($semester) => Semester::date($semester)?->toDateString());
                return Semester::sortSemestersChronologically($semesters);
                break;
        }
    }

    public function semestersParamsStartAndEnd($semesters_params, $weeks_before_start = 3, $weeks_before_end = 3) : array {
        $result = [];
    
        foreach ($semesters_params as $semester_params) {

            $semester = explode(' ', $semester_params);
            $start_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][0].' '.$semester[1])
                            ->subweeks($weeks_before_start)
                            ->format('Y-m-d');
            $end_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][1].' '.$semester[1])
                            ->subweeks($weeks_before_end)
                            ->format('Y-m-d');
            
            $result[] = [ 'start' => $start_date, 'end' => $end_date ];
        }

        return $result;
    }

}
