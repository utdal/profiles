<?php
namespace App\Macros;

use Illuminate\Support\Collection;

class CollectionMacros
{
    public static function register()
    {
        /**
         * Adds a macro to export the collection to a streamed CSV response.
         *
         * @param string|null $name The name of the CSV file to download. Defaults to 'export.csv'.
         * @return \Symfony\Component\HttpFoundation\StreamedResponse
         */
        Collection::macro('toCsv', function ($name = null) {

            /** @var \Illuminate\Support\Collection $this */
            $results = $this;

            return response()->streamDownload(function () use ($results) {
                if ($results->isEmpty()) return;

                $titles = implode(',', array_keys((array) $results->first()->getAttributes()));

                $values = $results->map(function ($result) {
                    return collect($result->getAttributes())->map(function ($value) {
                        if (is_null($value)) return '""';

                        $value = (string) $value;
                        $value = str_replace(["\r", "\n", "\t"], ' ', $value);
                        $value = preg_replace('/\s+/', ' ', $value);
                        $value = str_replace('"', '""', $value);

                        return "\"{$value}\"";
                    })->implode(',');
                });

                $values->prepend($titles);
                echo $values->implode("\n");
            }, $name ?? 'export.csv');
        });
    }
}