<?php

namespace App\Insights\StudentApplications;

use App\Helpers\Semester;
use App\Insights\Insight;
use App\Insights\InsightDataBuilder;
use App\Student;
use App\StudentData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class StudentDataInsight extends Insight
{
    /**
     * DOUGHNUT CHART #1 DATA - APPLICATIONS COUNT BY FILING STATUS FOR SEMESTERS AND SCHOOLS
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @param array $filing_status_category_1 Filing statuses for the first category. Example: ['accepted', 'follow up'].
     * @param array $filing_status_category_2 Filing statuses for the first category. Example: ['not interested', 'maybe later'].
     * @return array
    */
    public function getAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_category_1, $filing_status_category_2)
    {
        $filing_status_category_1_total = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_category_1)->count();
        $filing_status_category_2_total = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_category_2)->count();

        return [$filing_status_category_1_total, $filing_status_category_2_total];
    }

    /**
     * Auxiliary caching and grouping methods for Chart #1 (Doughnut) and Chart #4 (Bar).
     */

    /**
     * Retrieve and cache a collection of student applications whose last filing status matches 
     * the provided filing status parameters for the specified semesters and schools.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @param array $filing_status_params Filing status filter for the last status of the applications. Example: ['accepted', 'follow up'].
     * @return \Illuminate\Support\Collection
    */
    public function getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);
        $fls = implode('-', $filing_status_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-with-flstatus-{$sm}-{$sch}-{$fls}",
            15 * 60,
            fn() => $this->groupAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params)
        );
    }

    /**
     * Return a collection of student applications whose last filing status matches 
     * the provided filing status parameters for the specified semesters and schools.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @param array $filing_status_params Filing status filter for the last status of the applications. Example: ['accepted', 'follow up'].
     * @return \Illuminate\Support\Collection
    */
    public function groupAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params)
    {
        $student_applications = $this->getCachedAppsForSemestersAndSchoolsWithStats($semesters_params, $schools_params);
        $semesters_params_start_end = $this->semestersParamsStartAndEnd($semesters_params);
        $results = [];
        
        $student_applications->each(function ($application) use ($semesters_params_start_end, $filing_status_params, &$results) {
            if (!empty($application->research_profile->data['semesters']) && !empty($application->stats->data['status_history'])) {
                foreach ($semesters_params_start_end as $semester => $semester_start_end) {
                    $start_date = $semester_start_end['start'];
                    $end_date = $semester_start_end['end'];
                    foreach ($filing_status_params as $filing_status) {
                        collect($application->stats->data['status_history'])
                        ->groupBy('profile')
                        ->each(function($group) use ($start_date, $end_date, $filing_status, $semester, $application, &$results) {
                            $last_update = $group->sortByDesc(function ($item) {
                                return Carbon::parse($item['updated_at']);
                            })->first();
                            $filing_date = Carbon::parse($last_update['updated_at']);
                            if ($last_update['new_status'] === $filing_status && $filing_date->between($start_date, $end_date)) {
                                $results[] = [
                                    'id' => $application->stats->id,
                                    'semester' => $semester,
                                    'filing_status' => $filing_status,
                                ];
                            }
                        });
                    }
                }
            }
        });
        return collect($results);
    }

    /**
     * Retrieve and cache a collection of student applications for given semesters and schools with stats.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
    */
    public function getCachedAppsForSemestersAndSchoolsWithStats($semesters_params, $schools_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-with-stats-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)->with('stats')->get()
        );
    }

    /**
     * Return query builder to retrieve students records with applications ('research_profile') for given semesters and schools.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return Illuminate\Database\Eloquent\Builder
    */
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
     * DOUGHNUT CHART #2 DATA - APPLICATIONS COUNT VIEWED AND NOT VIEWED FOR SEMESTERS AND SCHOOLS
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return array
    */
    public function getViewedAndNotViewedApps($semesters_params, $schools_params,)
    {
        $submitted_and_viewed = $this->getCachedAppsForSemestersAndSchoolsViewed($semesters_params, $schools_params)->count();
        $submitted_not_viewed = $this->getCachedAppsForSemestersAndSchoolsNotViewed($semesters_params, $schools_params)->count();

        return [
                'labels' => [ 'Viewed', 'Not Viewed' ],
                'datasets' => [$submitted_and_viewed, $submitted_not_viewed],
            ];
    }
    /**
     * Retrieve and cache a collection of student applications for given semesters and schools that have been viewed.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return Illuminate\Database\Eloquent\Builder
    */
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
                       })
       );
    }
    
    /**
     * Retrieve and cache a collection of student applications for given semesters and schools that have not been viewed.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return Illuminate\Database\Eloquent\Builder
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
                        })
        );
    }

    /**
     * BAR CHART #3 DATA - APPLICATIONS COUNT BY SEMESTER AND SCHOOL
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return array
     */
    public function getAppsBySemestersAndSchools($semesters_params, $schools_params)
    {
        $applications = $this->transformAppsBySemestersAndSchools($semesters_params, $schools_params);

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
     * Auxiliary method for Chart #3 to transform student applications by semesters and schools.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
    */
    public function transformAppsBySemestersAndSchools(array $semesters_params, array $schools_params)
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

    /**
     * Retrieve and cache a collection of student applications for given semesters and schools.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
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

    /**
     *  BAR CHART #4 DATA - APPLICATIONS COUNT BY SEMESTER, SCHOOL AND FILING STATUS
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @param array $filing_status_params Filing status filter for the last status of the applications. Example: ['accepted', 'follow up'].
    */
    public function getAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params)
    {
        $applications = $this->getCachedAppsForSemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params);

        $counted_apps = $applications
                            ->groupBy(['semester', 'filing_status'])
                            ->map(function ($semester_group) {
                                return $semester_group->map(function ($status_group) {
                                    return $status_group->count();
                                });
                            });

        $all_semesters = $applications->pluck('semester')->unique()->sort()->values();
        $all_filing_statuses = $applications->pluck('filing_status')->unique()->sort()->values();
        
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

    public function semestersParamsStartAndEnd($semesters_params, $weeks_before_start = 4, $weeks_before_end = 4) : array {
        $result = [];
    
        foreach ($semesters_params as $semester_params) {

            $semester = explode(' ', $semester_params);
            $start_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][0].' '.$semester[1])
                            ->subweeks($weeks_before_start)
                            ->format('Y-m-d');
            $end_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][1].' '.$semester[1])
                            ->subweeks($weeks_before_end)
                            ->format('Y-m-d');
            
            $result[$semester_params] = [ 'start' => $start_date, 'end' => $end_date ];
        }

        return $result;
    }

}
