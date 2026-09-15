<?php

namespace App\Traits;

use Carbon\Carbon;
use RuntimeException;

trait ReadsPatentSpreadsheets
{
    /**
     * Read a CSV file and return rows keyed by header name.
     *
     * Expects: UTF-8 encoded CSV with a header row.
     * Excel "CSV UTF-8" export works; "CSV (Comma delimited)" may need manual
     * encoding conversion if your data has non-ASCII characters.
     */
    protected function readSpreadsheetRows(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("File not found: {$path}");
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException("Could not open file: {$path}");
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            throw new RuntimeException("Empty file or invalid CSV: {$path}");
        }

        // Strip UTF-8 BOM from first header (Excel "CSV UTF-8" adds one)
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $headers = array_map('trim', $headers);

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            // Skip blank rows (Excel sometimes adds trailing empty rows)
            $non_empty = array_filter($row, fn ($v) => $v !== null && trim((string) $v) !== '');
            if (empty($non_empty)) {
                continue;
            }

            // Pad/truncate row to match header count
            $row = array_pad(array_slice($row, 0, count($headers)), count($headers), '');

            // Trim each value
            $row = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);

            $rows[] = array_combine($headers, $row);
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Parse a date string into ISO format (YYYY-MM-DD).
     * Returns null for unparseable or empty input.
     */
    protected function parseDate(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}