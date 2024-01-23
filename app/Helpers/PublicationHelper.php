<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Publication
{
    /** @var array Default semester seasons (keyed in order) */
    const CITATION_FORMATS = [
        'APA',
    ];

    public static function formatAuthors(array $author_names): array
    {
        $formatted_author_names = [];
        
        foreach ($author_names as $author_name) {
            $middle_initial = null;
            $formatted_author_name = trim($author_name);

            if (!preg_match(static::CITATION_FORMATS['APA'], $formatted_author_name)) { // Add method to access regex constant

                $author_name_array = explode(" ", $author_name);
                $last_name = Arr::last($author_name_array);
                $first_name = $author_name_array[0];

                if (count($author_name_array) == 3) {
                    $middle_initial = " {$author_name_array[1][0]}.";
                }

                $author_name_values = [
                    'apa_format' => ucwords("{$last_name}, {$first_name[0]}.{$middle_initial}") ,
                    'first_name' => $first_name,
                    'middle_initial' => $middle_initial,
                    'last_name' => $last_name
                ];
            }
            else {
                $author_name_values = ['apa_format' => ucwords($formatted_author_name)];
            }
            $formatted_author_names[] = $author_name_values;
        }
        return $formatted_author_names;
    }

        /**
     * Get the citation for a given publication in APA format
     * @param array
     * @return string
     */
    public static function formatAuthorsApa(array $authors)
    {
            $formatted_authors = "";
            $greater20 = false;
            $authors_count = count($authors);

            if ($authors_count > 1) {

                $last = $authors[$authors_count - 1];

                if ($authors_count >= 20) {
                    $greater20 = true;
                    array_splice($authors, 20);
                }
                else {
                    array_splice($authors, $authors_count - 1);
                }

                foreach ($authors as $key => $author) {
                    
                    $formatted_authors = $formatted_authors . $author['apa_format'];

                    if ($key < count($authors) - 1) {
                        $formatted_authors = $formatted_authors . ", ";
                    } else {
                        if ($greater20) {
                            $formatted_authors = $formatted_authors . " ... ";
                        } else {
                            $formatted_authors = $formatted_authors . " & ";
                        }

                        $formatted_authors = "{$formatted_authors} {$last['apa_format']}";
                    }
                }
            }
            else {
                $formatted_authors = $authors[0]['apa_format'];
            }
        return $formatted_authors;
    }
}
