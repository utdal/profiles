<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Semester
{
    /** @var array Default semester seasons (keyed in order) */
    const DEFAULT_SEASONS = [
        'Spring',
        'Summer',
        'Fall',
    ];

    const DEFAULT_SEASON_DATES = [
        'Spring' => ['Jan 1', 'May 31'],
        'Summer' => ['Jun 1', 'Aug 31'],
        'Fall' => ['Sept 1', 'Dec 31'],
    ];

    const NAME_SEPARATOR = ' ';

    public static function seasons(): array
    {
        // @todo : allow this to be customized
        return static::DEFAULT_SEASONS;
    }

    public static function seasonDates(): array
    {
        return static::DEFAULT_SEASON_DATES;
    }

    public static function startOfSeason(string $season): string
    {
        return static::seasonDates()[$season][0];
    }

    public static function endOfSeason(string $season): string
    {
        return static::seasonDates()[$season][1];
    }

    public static function formatName(string $season, int $year): string
    {
        return $season . static::NAME_SEPARATOR . $year;
    }

    public static function current(): string
    {
        return static::formatName(static::currentSeason(), Carbon::now()->year);
    }

    public static function currentSeason(): string
    {
        foreach (static::seasons() as $season_name) {
            $season_start = Carbon::parse(static::startOfSeason($season_name))->startOfDay();
            $season_end = Carbon::parse(static::endOfSeason($season_name))->endOfDay();

            if (Carbon::now()->between($season_start, $season_end)) {
                return $season_name;
            }
        }

        return 'None';
    }

    /**
     * Get the names of the next X semesters
     *
     * @param int $count : number of future semesters to get
     * @param string $current_name : name of the semester to start from
     * @return string|array
     */
    public static function next(int $count = 1, string $current_name = '')
    {
        if ($count < 1) {
            return [];
        } elseif ($count > 1) {
            $names = [];

            for ($i = 0; $i < $count; $i++) {
                $current_name = $names[$i] = static::next(1, $current_name);
            }

            return $names;            
        }

        $seasons = static::seasons();
        $current_semester = static::parseName($current_name ?: static::current());
        $current_season_key = array_search($current_semester['season'], $seasons, true);

        if ($current_season_key !== false) {
            $next_season = $seasons[(int)$current_season_key + 1] ?? false;

            if ($next_season === false) { // next semester is next calendar year
                return static::formatName($seasons[0], $current_semester['year']->addYear()->year);
            }

            return static::formatName($next_season, $current_semester['year']->year);
        }

        return static::formatName('None', $current_semester['year']->year);
    }

    public static function currentAndNext(int $count = 1): array
    {
        $next = static::next($count);

        return array_merge([static::current()], is_array($next) ? $next : [$next]);
    }

    public static function parseName(string $name)
    {
        $pieces = explode(static::NAME_SEPARATOR, $name, 2);

        return [
            'season' => $pieces[0],
            'year' => Carbon::create($pieces[1]),
        ];
    }

    public static function date(string $name, bool $start_of_season = true)
    {
        $parsed = static::parseName($name);
        $month_and_day = $start_of_season ? static::startOfSeason($parsed['season']) : static::endOfSeason($parsed['season']);

        return Carbon::parse($month_and_day . ' ' . $parsed['year']->year);
    }
}
