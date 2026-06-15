<?php

namespace App\Services;

class PatentFamilyGrouper
{
    private const COUNTRY_CODES = [
        'US', 'AU', 'CA', 'DE', 'EP', 'FR', 'GB', 'JP', 'KR', 'CN',
        'BE', 'CH', 'ES', 'IT', 'NL', 'MX', 'SG', 'IE', 'BR', 'DK',
        'IN', 'RU', 'TW', 'HK',
    ];

    private const PORTFOLIO_PATTERNS = [
        '/^(\d+MTI)\d+/',
    ];

    private const EUROPEAN_COUNTRIES = [
        'European', 'Belgium', 'Switzerland', 'Germany', 'Spain', 'France',
        'United Kingdom', 'Ireland', 'Italy', 'The Netherlands',
    ];

    public function familyPrefix(string $patent_internal_id): string
    {
        $normalized = strtoupper(str_replace('-', '', trim($patent_internal_id)));

        $pattern = '/^(.*?)(' . implode('|', self::COUNTRY_CODES) . ')/';
        if (preg_match($pattern, $normalized, $matches) && $matches[1] !== '') {
            $prefix = $matches[1];
        } else {
            $prefix = preg_replace('/(CIP|C|D)\d+$/', '', $normalized);
        }

        return preg_replace('/DES\d+$/', '', $prefix);
    }

    private function resolveGroupingKey(string $patent_internal_id): array
    {
        $normalized = strtoupper(str_replace('-', '', trim($patent_internal_id)));

        foreach (self::PORTFOLIO_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalized, $matches)) {
                return [$matches[1], true];
            }
        }

        return [$this->familyPrefix($patent_internal_id), false];
    }

    public function normalizeTitle(string $title): string
    {
        $t = strtolower(trim($title));
        $t = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        return trim($t);
    }

    public function groupStrict(array $rows): array
    {
        $co_inventor_index = $this->buildCoInventorIndex($rows);

        $groups = [];

        foreach ($rows as $row) {
            $net_id = trim((string) ($row['NetID'] ?? ''));
            $pid = (string) ($row['Patent Internal ID'] ?? '');
            $title = (string) ($row['Patent Title'] ?? '');

            if ($net_id === '' || $pid === '') {
                continue;
            }

            $normalized_title = $this->normalizeTitle($title);
            [$grouping_prefix, $include_country] = $this->resolveGroupingKey($pid);

            $country_part = '';

            if ($include_country) {
                $country = trim((string) ($row['Country'] ?? ''));
                if (in_array($country, self::EUROPEAN_COUNTRIES, true)) {
                    $country = 'EP';
                }
                $country_part = '||' . $country;
            }

            $key = $net_id . '||' . $normalized_title . '||' . $grouping_prefix . $country_part;

            if (! isset($groups[$key])) {
                $groups[$key] = [
                    'net_id' => $net_id,
                    'inventor' => (string) ($row['Inventor'] ?? ''),
                    'family_prefix' => $grouping_prefix,
                    'normalized_title' => $normalized_title,
                    'canonical_title' => trim($title),
                    'rows' => [],
                ];
            }

            $clean_title = preg_replace('/\s+/', ' ', trim($title));
            if (strlen($clean_title) > strlen($groups[$key]['canonical_title'])) {
                $groups[$key]['canonical_title'] = $clean_title;
            }

            $groups[$key]['rows'][] = $row;
        }

        $curated = [];
        foreach ($groups as $group) {
            $curated[] = $this->buildEntry($group, $co_inventor_index);
        }

        usort($curated, fn ($a, $b) =>
            [$a['inventor'], $a['canonical_title'], $a['family_prefix']]
            <=> [$b['inventor'], $b['canonical_title'], $b['family_prefix']]
        );

        return $curated;
    }

    private function buildCoInventorIndex(array $rows): array
    {
        $by_patent = [];

        foreach ($rows as $row) {
            $prefix = $this->familyPrefix((string) ($row['Patent Internal ID'] ?? ''));
            $country = trim((string) ($row['Country'] ?? ''));
            $patent_no = trim((string) ($row['Patent No.'] ?? ''));
            $inventor = trim((string) ($row['Inventor'] ?? ''));

            if ($patent_no === '' || $inventor === '') {
                continue;
            }

            $key = $prefix . '|' . $country . '|' . $patent_no;
            if (! isset($by_patent[$key])) {
                $by_patent[$key] = [];
            }
            if (! in_array($inventor, $by_patent[$key], true)) {
                $by_patent[$key][] = $inventor;
            }
        }

        return $by_patent;
    }

    private function formatInventorName(string $full_name): string
    {
        $parts = explode(',', $full_name, 2);
        if (count($parts) !== 2) {
            return trim($full_name);
        }

        $last = trim($parts[0]);
        $first = trim($parts[1]);

        if ($first === '') {
            return $last;
        }

        $initial = mb_strtoupper(mb_substr($first, 0, 1));
        return $initial . '. ' . $last;
    }

    private function buildEntry(array $group, array $co_inventor_index): array
    {
        $co_inventor_names = [];

        foreach ($group['rows'] as $r) {
            $c = trim((string) ($r['Country'] ?? ''));
            $p = trim((string) ($r['Patent No.'] ?? ''));

            $prefix = $this->familyPrefix((string) ($r['Patent Internal ID'] ?? ''));
            $co_key = $prefix . '|' . $c . '|' . $p;
            $this_inventor = trim((string) ($r['Inventor'] ?? ''));

            foreach ($co_inventor_index[$co_key] ?? [] as $name) {
                if ($name !== $this_inventor && ! in_array($name, $co_inventor_names, true)) {
                    $co_inventor_names[] = $name;
                }
            }
        }

        $co_inventors_formatted = implode(', ', array_map(
            fn ($name) => $this->formatInventorName($name),
            $co_inventor_names
        ));

        return [
            'net_id' => $group['net_id'],
            'inventor' => $group['inventor'],
            'canonical_title' => $this->toTitleCase($group['canonical_title']),
            'normalized_title' => $group['normalized_title'],
            'family_prefix' => $group['family_prefix'],
            'co_inventors' => $co_inventors_formatted,
            'rows' => $group['rows'],
        ];
    }

    private function toTitleCase(string $title): string
    {
        $words = preg_split('/(\s+)/', trim($title), -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        $word_index = 0;

        foreach ($words as $part) {
            if (preg_match('/^\s+$/', $part)) {
                $result .= $part;
                continue;
            }

            $lower = mb_strtolower($part);

            if ($word_index === 0 || mb_strlen($part) > 4) {
                $result .= mb_strtoupper(mb_substr($lower, 0, 1)) . mb_substr($lower, 1);
            } else {
                $result .= $lower;
            }

            $word_index++;
        }

        return $result;
    }
}