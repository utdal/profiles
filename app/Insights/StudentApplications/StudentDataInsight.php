<?php

namespace App\Insights\StudentApplications;

use App\Helpers\Semester;
use App\Student;
use App\StudentData;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;


class StudentDataInsight
{
    /**
     * DOUGHNUT CHART #1 DATA - APPLICATIONS COUNT BY FILING STATUS FOR SEMESTERS AND SCHOOLS
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @param array $filing_status_category_1 Filing statuses for the first category. Example: ['accepted', 'follow up'].
     * @param array $filing_status_category_2 Filing statuses for the first category. Example: ['not interested', 'maybe later'].
     * @return array
    */
    public function appsCountForTwoCategoriesOfFilingStatus($semesters_params, $schools_params, $filing_status_category_1, $filing_status_category_2, $weeks_before_semester_start, $weeks_before_semester_end)
    {
        $filing_status_category_1_total = $this->cachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_category_1, $weeks_before_semester_start, $weeks_before_semester_end)->count();
        $filing_status_category_2_total = $this->cachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_category_2, $weeks_before_semester_start, $weeks_before_semester_end)->count();

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
    public function cachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params, $weeks_before_semester_start, $weeks_before_semester_end)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);
        $fls = implode('-', $filing_status_params);
        $wbs = $weeks_before_semester_start;
        $wbe = $weeks_before_semester_end;

        return Cache::remember(
            "student-apps-for-semesters-schools-with-flstatus-{$sm}-{$sch}-{$fls}-{$wbs}-{$wbe}",
            15 * 60,
            fn() => $this->groupAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params, $weeks_before_semester_start, $weeks_before_semester_end)
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
    public function groupAppsBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params, $weeks_before_semester_start, $weeks_before_semester_end)
    {
        $students = $this->cachedAppsForSemestersAndSchoolsWithStatsWithUser($semesters_params, $schools_params);
        $semesters_params_start_end = $this->semestersParamsStartAndEnd($semesters_params, $weeks_before_semester_start, $weeks_before_semester_end);
        $results = [];
        
        foreach ($semesters_params_start_end as $semester => $semester_start_end) { // Looping through semesters
            foreach ($schools_params as $school) { // Looping through schools
                $start_date = $semester_start_end['start'];
                $end_date = $semester_start_end['end'];
                foreach ($filing_status_params as $filing_status) { // Looping through filing statuses
            
                    $students_filtered = $this->filterStudentsBySemesterAndSchool($students, $semester, $school);
                    $students_filtered->each(function ($student) use ($start_date, $end_date, $filing_status, $semester, &$results) {
                        // For each student, find the non empty status history in the stats
                        if (!empty($student->stats->data['status_history'])) { // Each status history equals a filing record for a student app - 
                            // Each status history represents an action of filing the app for a specific status by a faculty member
                            $matching_updates = collect($student->stats->data['status_history']) //For each status history, group by profile
                                                ->groupBy('profile') //Filter the st-hist for which the last update matches the filing status param and was updated in the given timeframe
                                                ->filter(function($group_by_profile) use ($start_date, $end_date, $filing_status) {
                                                    $last_update_by_profile = $this->lastUpdateByProfile($group_by_profile);
                                                    $filing_date = Carbon::parse($last_update_by_profile['updated_at']);
                                                    return $last_update_by_profile['new_status'] === $filing_status && $filing_date->between($start_date, $end_date);
                                                });
                                                // There could be multiple matches. For instance, two faculty members file the app as follow up in the same timeframe
                                                foreach ($matching_updates as $m) { 
                                                    $results[] = $this->lastUpdateBetweenRangeByProfileWithStatus($m->first(), $semester, $student);
                                                }
                        }
                    });
                }
            }
        }
        return collect($results);
    }

    /**
     * Retrieve and cache a collection of student applications for given semesters and schools with stats.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
    */
    public function cachedAppsForSemestersAndSchoolsWithStatsWithUser($semesters_params, $schools_params)
    {
        $sm = implode('-', $semesters_params);
        $sch = implode('-', $schools_params);

        return Cache::remember(
            "student-apps-for-semesters-schools-with-stats-{$sm}-{$sch}",
            15 * 60,
            fn() => $this->appsForSemestersAndSchools($semesters_params, $schools_params)
                         ->with('stats')
                         ->with('user')
                         ->get()
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
        if (empty($semesters_params) || empty($schools_params)) {
            return Student::query()->whereRaw('1 = 0');
        }

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
    public function appsCountViewedAndNotViewed($semesters_params, $schools_params)
    {
        $submitted_and_viewed = $this->cachedViewedAppsForSemestersAndSchools($semesters_params, $schools_params)->count();
        $submitted_not_viewed = $this->cachedNotViewedAppsForSemestersAndSchools($semesters_params, $schools_params)->count();

        return [
                'labels' => [ 'Viewed', 'Not Viewed' ],
                'datasets' => [$submitted_and_viewed, $submitted_not_viewed],
            ];
    }
    /**
     * Retrieve and cache a collection of student applications for given semesters and schools that have been viewed.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
    */
    public function cachedViewedAppsForSemestersAndSchools($semesters_params, $schools_params)
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
                       })->get()
       );
    }
    
    /**
     * Retrieve and cache a collection of student applications for given semesters and schools that have not been viewed.
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return \Illuminate\Support\Collection
    */
    public function cachedNotViewedAppsForSemestersAndSchools($semesters_params, $schools_params)
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
                        })->get()
        );
    }

    /**
     * BAR CHART #3 DATA - APPLICATIONS COUNT BY SEMESTER AND SCHOOL
     * @param array $semesters_params Semesters filter. Example: ["Summer 2023", "Fall 2023"].
     * @param array $schools_params Schools filter. Example: ["BBS", "NSM"].
     * @return array
     */
    public function appsCountBySemestersAndSchools($semesters_params, $schools_params)
    {
        $applications = $this->groupAppsBySemestersAndSchools($semesters_params, $schools_params);
        $semesters_sort_closure = Semester::sortCollectionWithSemestersKeyChronologically();

        $counted_apps = $applications
                            ->groupBy(['semester', 'school'])
                            ->sortKeysUsing($semesters_sort_closure)
                            ->map(function ($semester_group) {
                                return $semester_group->map(function ($school_group) {
                                    return $school_group->count();
                                });
                            });

        $all_semesters = $counted_apps->keys();
        $all_schools = $applications->pluck('school')->unique()->sort()->values();
                            
        $datasets = $all_schools->mapWithKeys(function ($school) use ($all_semesters) { // Initialize datasets for each school
            return [  $school => ['label' => $school, 'data' => array_fill(0, $all_semesters->count(), 0)]];
        })->toArray();

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
    public function groupAppsBySemestersAndSchools(array $semesters_params, array $schools_params)
    {
        $students = $this->getCachedAppsForSemestersAndSchools($semesters_params, $schools_params);
        $results = [];

        foreach ($semesters_params as $semester) { // Looping through semesters
            foreach ($schools_params as $school) { // Looping through schools

                $students_filtered = $this->filterStudentsBySemesterAndSchool($students, $semester, $school);
                $students_filtered->each(function ($student) use ($semester, $school, &$results) {
                    // For each student, find the non empty status history in the stats
                    $results[] =[
                        'id' => $student->research_profile->id,
                        'semester' => $semester,
                        'school' => $school,
                    ];
                });
            }
        }
        return collect($results);
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
    public function appsCountBySemestersAndSchoolsWithFilingStatus($semesters_params, $schools_params, $filing_status_params, $weeks_before_semester_start, $weeks_before_semester_end)
    {
        $applications = $this->cachedAppsForSemestersAndSchoolsWithFilingStatuses($semesters_params, $schools_params, $filing_status_params, $weeks_before_semester_start, $weeks_before_semester_end);
        $semesters_sort_closure = Semester::sortCollectionWithSemestersKeyChronologically();
        $counted_apps = $applications
                            ->groupBy(['semester', 'filing_status'])
                            ->sortKeysUsing($semesters_sort_closure)
                            ->map(function ($semester_group) {
                                return $semester_group->map(function ($status_group) {
                                    return $status_group->count();
                                });
                            });

        $all_semesters = $counted_apps->keys();
        $all_filing_statuses = $applications->pluck('filing_status')->unique()->sort()->values();
        
        $datasets = $all_filing_statuses->mapWithKeys(function ($filing_status) use ($all_semesters) { // Initialize datasets for each status
            return [$filing_status => ['label' => ucfirst($filing_status), 'data' => array_fill(0, $all_semesters->count(), 0)]];
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

    public function semestersParamsStartAndEnd($semesters_params, $weeks_before_start, $weeks_before_end) : array {
        $result = [];
        
        foreach ($semesters_params as $semester_params) {

            $semester = explode(' ', $semester_params);
            $start_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][0].' '.$semester[1])
                            ->subweeks((int) $weeks_before_start)
                            ->format('Y-m-d');
            $end_date = Carbon::createFromFormat('M j Y', Semester::seasonDates()[$semester[0]][1].' '.$semester[1])
                            ->subweeks((int) $weeks_before_end)
                            ->format('Y-m-d');
            
            $result[$semester_params] = [ 'start' => $start_date, 'end' => $end_date ];
        }

        return $result;
    }

    public static function convertParameterstoTitle($semesters_params, $schools_params)
    {
        $semesters = implode(' | ', $semesters_params);
        $schools = implode(' | ', $schools_params);
        return [$semesters, $schools];
    }

    public function filterStudentsBySemesterAndSchool($students, $semester, $school): Collection {
        return $students->filter(function($student) use ($semester, $school) {
            return in_array($semester, $student->research_profile->data['semesters']) && in_array($school, $student->research_profile->data['schools']);
        });
    }

    public function lastUpdateBetweenRangeByProfileWithStatus($matching_update, $semester, $student) {

            return [
                'id' => $student->stats->id,
                'semester' => $semester,
                'school' => $student->research_profile->data['schools'],
                'filing_status' => ucfirst($matching_update['new_status']),
                'updated_at' => $matching_update['updated_at'],
                'profile' => $matching_update['profile'],
                'display_name' => $student->user->display_name,
                'netID' => $student->user->name,
            ];

    }

    public function lastUpdateByProfile($group_by_profile) {
        return $group_by_profile->sortByDesc(function ($item) {
            return Carbon::parse($item['updated_at']);
        })->first();
    }
}
