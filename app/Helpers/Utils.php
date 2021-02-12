<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Utils
{
    /** @var string Delimiter used in obfuscation */
    public static $obfuscation_delimiter = '☄️';

    /**
     * Obfuscate an email address
     */
    public static function obfuscateEmailAddress(string $original_email_address): string
    {
        return static::encodeString($original_email_address, '@', static::$obfuscation_delimiter);
    }

    /**
     * Deobfuscate an email address
     */
    public static function deobfuscateEmailAddress(string $email_address): string
    {
        return static::encodeString($email_address, static::$obfuscation_delimiter, '@');
    }

    /**
     * Encodes/Decodes a two-part string, e.g. 'part1@part2'
     */
    protected static function encodeString(string $string, string $delimiter, string $glue): string
    {
        // Rot13-encode, split on delimiter, swap pieces, and join on glue
        [$first, $second] = explode($delimiter, str_rot13($string), 2);

        return "{$second}{$glue}{$first}";
    }
}