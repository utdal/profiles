<?php

namespace App\Insights\StudentApplications;

use App\Helpers\Semester;

class ProfileStudentInsight
{
    protected static $model;

    public static function getDataArray(array $chart_options)
    {
        $data = [];

        $data['dataset'] = [
            [
                'label' => $chart_options['label'],
                'backgroundColor' => $chart_options['backgroundColor'],
                'borderColor' => $chart_options['borderColor'],
                'data' => $chart_options['data'],
            ]
        ];

        $data['labels'] = $chart_options['labels'];

        return $data;
    }


    
    public function semesterToDate($semester)
    {
        return Semester::date($semester)?->toDateString();
    }

}
