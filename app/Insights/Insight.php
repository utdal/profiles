<?php

namespace App\Insights;

abstract class Insight 
{
    abstract public function getDataSet(?array $semester = [], ?array $school = [], ?string $type = null, ?string $status = 'submitted');

    abstract public static function getLabels(string $filter);

    public static function getDataArray(array $charts_options)
    {
        $data = [];

        foreach ($charts_options as $chart_options) {
            $data['datasets'][]= 
                [
                    'label' => $chart_options['label'],
                    'type' => $chart_options['type'],
                    'backgroundColor' => $chart_options['backgroundColor'],
                    'borderColor' => $chart_options['borderColor'],
                    'data' => $chart_options['data'],
                ];
            }
            $data['labels'] = $chart_options['labels'];

        return $data;
    }

}
