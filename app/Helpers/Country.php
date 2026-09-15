<?php

namespace App\Helpers;

/**
 * Patent jurisdictions used by the faculty patents system.
 *
 * Keyed by WIPO ST.3 code (aligned with ISO 3166-1 alpha-2 for countries,
 * plus 'EP' for the European Patent Office).
 */
final class Country
{
    /**
     * @var array<string, string>
     */
    private const LIST = [
        'AR' => 'Argentina',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'BD' => 'Bangladesh',
        'BE' => 'Belgium',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BR' => 'Brazil',
        'BG' => 'Bulgaria',
        'CA' => 'Canada',
        'CL' => 'Chile',
        'CN' => 'China',
        'CO' => 'Colombia',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CY' => 'Cyprus',
        'CZ' => 'Czechia',
        'DK' => 'Denmark',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'EE' => 'Estonia',
        'EP' => 'European',
        'FI' => 'Finland',
        'FR' => 'France',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GR' => 'Greece',
        'GT' => 'Guatemala',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KR' => 'Korea (Republic of)',
        'KW' => 'Kuwait',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MY' => 'Malaysia',
        'MT' => 'Malta',
        'MX' => 'Mexico',
        'MA' => 'Morocco',
        'NL' => 'Netherlands',
        'NZ' => 'New Zealand',
        'NG' => 'Nigeria',
        'NO' => 'Norway',
        'PK' => 'Pakistan',
        'PA' => 'Panama',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'SA' => 'Saudi Arabia',
        'RS' => 'Serbia',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'ZA' => 'South Africa',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'TW' => 'Taiwan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
    ];

    /**
     * Return all jurisdictions keyed by code.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return self::LIST;
    }

    /**
     * Return all jurisdiction codes.
     *
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_keys(self::LIST);
    }

    /**
     * Look up a jurisdiction name by code.
     */
    public static function name(string $code): ?string
    {
        return self::LIST[$code] ?? null;
    }

    /**
     * Check whether a code is a known jurisdiction.
     */
    public static function has(string $code): bool
    {
        return array_key_exists($code, self::LIST);
    }
}