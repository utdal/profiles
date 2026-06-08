<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;

trait ReadsPatentSpreadsheets
{
    /**
     * Read a CSV or XLSX file and return rows keyed by header name.
     * Optionally targets a specific sheet by name; defaults to the active sheet.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function readSpreadsheetRows(string $path, ?string $sheetName = null): array
    {
        $spreadsheet = IOFactory::load($path);

        if ($sheetName) {
            $sheet = null;
            foreach ($spreadsheet->getAllSheets() as $s) {
                if (strcasecmp($s->getTitle(), $sheetName) === 0) {
                    $sheet = $s;
                    break;
                }
            }
            $sheet ??= $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->getActiveSheet();
        }

        $data = $sheet->toArray(null, true, true, false);

        if (count($data) < 2) {
            return [];
        }

        $headers = array_map(fn ($h) => trim((string) $h), array_shift($data));
        $rows = [];

        foreach ($data as $raw) {
            if (count(array_filter($raw, fn ($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }
            $assoc = [];
            foreach ($headers as $i => $header) {
                $assoc[$header] = $raw[$i] ?? null;
            }
            $rows[] = $assoc;
        }

        return $rows;
    }

    protected function parseDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        try {
            return \Carbon\Carbon::createFromFormat('n/j/y', trim($value))->format('Y-m-d');
        } catch (\Throwable) {
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }
    }
}