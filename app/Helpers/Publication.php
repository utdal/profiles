<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class Publication
{
    /** @var array Default citation formats (keyed in order) */
    const CITATION_FORMAT_AUTHOR_NAMES = [
        'APA' => ['last_name', 'comma', 'space', 'first_initial', 'space', 'middle_initial'],
        //'MLA' => ['last_name', 'comma', 'space', 'first_name'], // Example ONLY, for testing purposes
        //'Chicago' => ['first_name', 'space', 'last_name'], // Example ONLY, for testing purposes
    ];

    /** @var array */
    const REGEX_PATTERNS = [
        'APA' => "/^[A-Za-z][\s\w\p{L}\p{M}áéíóúüññÑ'-]+,\s[A-Z][\.\s\b][\s]?[A-Z]?[\.\s\b]?$/",
        'last_name_initials' => "/^[A-Za-z][\s\w\p{L}\p{M}áéíóúüññÑ'-]+,?\s[A-Z]{1,2}\b$/",
        'first_name_last_name' => "/^[A-Za-z][\s\w\p{L}\p{M}áéíóúüññÑ'-]+\s[A-Za-z][\s\w\p{L}\p{M}áéíóúüññÑ'-]+$/",
        // 'MLA' => "/^[\p{L}ñÑ'., -]+, [\p{Lu}ñÑ]\. ?[\p{Lu}ñÑ]?\.?$/", // Example ONLY, for testing purposes
        // 'Chicago' => "/^[\p{L}ñÑ'., -]+, [\p{Lu}ñÑ]\. ?[\p{Lu}ñÑ]?\.?$/", // Example ONLY, for testing purposes
    ];

    /** @var array */
    const SEPARATORS = [
        'comma' => ',',
        'ellipsis' => '...',
        'ampersand' => '&',
        'space' => ' ',
        'period' => '.',
    ];

    public static function citationFormats(): array
    {
        return static::CITATION_FORMAT_AUTHOR_NAMES;
    }

    public static function citationFormatRegex(): array
    {
        return static::REGEX_PATTERNS;
    }

    public static function matchesRegexPattern($citation_format, $formatted_author_name) : int
    {
        return preg_match(static::citationFormatRegex()[$citation_format], $formatted_author_name);
    }

    public static function firstName(array $author_name_array, $pattern = null) : string
    {
        if (!is_null($pattern) && $pattern === 'last_name_initials') {
            return $author_name_array[1];
        }
        return $author_name_array[0];
    }

    public static function lastName(array $author_name_array, $pattern = null) : string
    {
        if (!is_null($pattern) && $pattern === 'last_name_initials') {
            return $author_name_array[0];
        }

        if (count($author_name_array) == 3) {
            return $author_name_array[2];
        }
        return Arr::last($author_name_array);
    }

    public static function middleName(array $author_name_array, $pattern = null) : string 
    {
        if (!is_null($pattern) && $pattern === 'last_name_initials') {
            return strlen($author_name_array[1]) == 2 ? $author_name_array[1][1] : '';
        }

        if (count($author_name_array) == 3) {
            return $author_name_array[1];
        }
        return '';
    }

    public static function initial(string $name) : string 
    {
        return strlen($name) > 0 ? "{$name[0]}." : '';
    }

    /**
     * Return a string with the authors names in APA format
     * @param array $authors
     * @return string
     */
    public static function formatAuthorsApa(array $authors) : string
    {
        $authors = static::formatAuthorsNames($authors);

        $string_authors_names = "";
        $greater_than_20 = false;
        $authors_count = count($authors);

        if ($authors_count > 1) {
            $last = $authors[$authors_count - 1];

            if ($authors_count >= 20) {
                $greater_than_20 = true;
                array_splice($authors, 20);
            }
            else {
                array_splice($authors, $authors_count - 1);
            }

            foreach ($authors as $key => $author) {
                $string_authors_names = "{$string_authors_names} {$author['APA']}";

                if ($key < count($authors) - 1) {
                    $string_authors_names = $string_authors_names . static::SEPARATORS['comma'] . static::SEPARATORS['space'];
                } 
                else {
                    if ($greater_than_20) {
                        $string_authors_names = $string_authors_names . static::SEPARATORS['space'] . static::SEPARATORS['ellipsis'] . static::SEPARATORS['space'];
                    } 
                    else {
                        $string_authors_names = $string_authors_names . static::SEPARATORS['space'] . static::SEPARATORS['ampersand'] . static::SEPARATORS['space'];
                    }
                    $string_authors_names = "{$string_authors_names} {$last['APA']}";
                }
            }
        }
        else {
            $string_authors_names = $authors[0]['APA'];
        }

        return $string_authors_names;
    }

    /**
     * Return a string with the authors names in MLA format
     * @param array $authors
     */
    public static function formatAuthorsMla(array $authors)
    {
        
    }

    /**
     * Return a string with the authors names in Chicago format
     * @param array $authors
     */
    public static function formatAuthorsChicago(array $authors)
    {

    }

    /** 
     * Receive an array of author names, assuming each name is either already formatted or in the form of First Name Middle initial. Last Name
     * Return an array formatted author name for each citation format
     * 
     * @param $author_names
     * @return array
    */
    public static function formatAuthorsNames(array $author_names): array
    {
        /** @var array<array> */
        $formatted_author_names = [];
        
        foreach ($author_names as $author_name) {
            $raw_author_name = trim($author_name);
            
            foreach (array_keys(static::citationFormats()) as $key => $citation_format) {
                //If matches given citation format pattern
                if (static::matchesRegexPattern($citation_format, $raw_author_name)) {
                    $formatted_author_name[$citation_format] = ucwords($raw_author_name);
                } //If matches last name first initial middle initial pattern
                elseif (static::matchesRegexPattern('last_name_initials', $raw_author_name)) {
                    $formatted_author_name[$citation_format] = static::formatAuthorName($citation_format, $author_name, 'last_name_initials');
                }
                else { //If matches any other pattern, it will use the first name last name pattern by default to format the name
                    $formatted_author_name[$citation_format] = static::formatAuthorName($citation_format, $author_name);
                    
                }
            }

            $formatted_author_names[] = $formatted_author_name;
        }
        return $formatted_author_names;
    }

    /**
     * Format an author name according to a given citation format
     * 
     * @param $citation_format
     * @param $full_name
     * @return string
     */
    public static function formatAuthorName($citation_format, $full_name, $pattern = null) : string
    {
        $result = '';
        $format_components = static::citationFormats()[$citation_format];
        $full_name_array = explode(" ", $full_name);
        $first_name = static::firstName($full_name_array, $pattern);
        $middle_name = static::middleName($full_name_array, $pattern);
        $last_name = static::lastName($full_name_array, $pattern);
        $first_initial = static::initial($first_name);
        $middle_initial = static::initial($middle_name);
        $comma = static::SEPARATORS['comma'];
        $space = static::SEPARATORS['space'];

        foreach ($format_components as $key => $value) {
            $result = $result . array_values(compact($value))[0];
        }

        return trim($result);
    }

}
