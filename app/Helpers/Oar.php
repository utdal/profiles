<?php

namespace App\Helpers;

use Auth;
use Carbon\Carbon;

class Oar
{
    /**
     * Takes a value and returns a 'yes' or 'no' string based on that value's truthiness.
     *
     * @param  mixed $value
     * @return string
     */
    public static function yesNo($value)
    {
        if ($value === null) {
            return null;
        }
        if ($value === '-1') {
            return 'not applicable';
        }
        return ($value) ? 'Yes' : 'No';
    }

    /**
     * Produces a random string that is unique to a model column.
     * 
     * @param  int    $length         : desired string length
     * @param  string $model_name     : name of the model to check
     * @param  string $attribute_name : name of the column to check
     * @return string
     */
    public static function uniqueString($length, $model_name, $attribute_name)
    {
        $result = str_random($length);

        if ($model_name::where($attribute_name, '=', $result)->exists()) {
            return static::uniqueString($length, $model_name, $attribute_name);
        }

        return $result;
    }

    /**
     * Gets the end of the current semester.
     * 
     * @return Carbon
     */
    public static function currentSemesterEnds()
    {
        $current_month = idate("m");
        $semester_end_month = 12; // Fall semester ends in December
        if ($current_month < 6) {
            $semester_end_month = 5; // Spring semester ends in May
        } elseif ($current_month < 9) {
            $semester_end_month = 8; // Summer semester ends in August
        }

        return Carbon::createFromDate(null, $semester_end_month)->endOfMonth();
    }

    /**
     * Resolve the ID of the logged User.
     *
     * @return mixed|null
     */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }
}