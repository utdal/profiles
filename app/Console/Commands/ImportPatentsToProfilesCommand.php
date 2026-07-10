<?php

namespace App\Console\Commands;

use App\Profile;
use App\ProfileData;
use App\Services\PatentFamilyGrouper;
use App\Traits\ReadsPatentSpreadsheets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportPatentsToProfilesCommand extends Command
{
    use ReadsPatentSpreadsheets;

    protected $signature = 'patents:import
                            {input : Path to the raw patents CSV or XLSX file}
                            {--dry-run : Show what would happen without persisting}';

    protected $description = 'Read a raw patents file, group into curated families, and bulk-insert into profile_data with type=patents. Wipes existing patents rows for the affected profiles before inserting.';

    public function handle(PatentFamilyGrouper $grouper): int
    {
        $input_path = $this->resolvePath($this->argument('input'));
        $dry_run = (bool) $this->option('dry-run');

        if (! is_file($input_path)) {
            $this->error("Input file not found: {$input_path}");
            return self::FAILURE;
        }

        $this->info("Reading: {$input_path}");
        $rows = $this->readSpreadsheetRows($input_path);
        $this->info('Rows read: ' . count($rows));

        if (empty($rows)) {
            $this->error('No rows to process.');
            return self::FAILURE;
        }

        $curated = $grouper->groupStrict($rows);
        $this->info('Curated groups: ' . count($curated));

        $net_ids_in_csv = array_values(array_unique(array_filter(array_map(
            fn ($r) => trim((string) ($r['NetID'] ?? '')),
            $rows
        ))));
        $this->info('Unique NetIDs in CSV: ' . count($net_ids_in_csv));

        $profiles = Profile::with('user')
            ->withWhereHas('user', fn ($q) => $q->whereIn('name', $net_ids_in_csv))
            ->get()
            ->keyBy(fn ($p) => $p->user->name);

        $this->info('Profiles matched: ' . $profiles->count());

        $unmatched = array_diff($net_ids_in_csv, $profiles->keys()->all());
        if (! empty($unmatched)) {
            $this->warn('NetIDs in CSV with no matching profile: ' . count($unmatched));
            foreach ($unmatched as $netId) {
                $this->warn("  - {$netId}");
                Log::warning("[patents:import] No profile for NetID '{$netId}'");
            }
        }

        $curated_by_net_id = [];
        foreach ($curated as $entry) {
            $curated_by_net_id[$entry['net_id']][] = $entry;
        }

        $stats = ['profiles_processed' => 0, 'rows_wiped' => 0, 'rows_inserted' => 0];

        if ($dry_run) {
            $this->warn('Dry run — no changes persisted.');
        }

        foreach ($profiles as $netId => $profile) {
            $entries = $curated_by_net_id[$netId] ?? [];

            if (empty($entries)) {
                continue;
            }

            $stats['profiles_processed']++;
            $this->line("Profile #{$profile->id} ({$netId}): " . count($entries) . ' familie(s)');

            if ($dry_run) {
                $stats['rows_inserted'] += count($entries);
                continue;
            }

            DB::transaction(function () use ($profile, $entries, &$stats) {
                $wiped = ProfileData::where('profile_id', $profile->id)
                    ->where('type', 'patents')
                    ->delete();
                $stats['rows_wiped'] += $wiped;

                foreach ($entries as $entry) {
                    ProfileData::create([
                        'profile_id' => $profile->id,
                        'type' => 'patents',
                        'data' => $this->buildJsonPayload($entry),
                    ]);
                    $stats['rows_inserted']++;
                }
            });
        }

        $this->info('---');
        $this->info('Profiles processed: ' . $stats['profiles_processed']);
        $this->info('Existing patents rows ' . ($dry_run ? 'would be ' : '') . 'wiped: ' . $stats['rows_wiped']);
        $this->info('profile_data rows ' . ($dry_run ? 'would be ' : '') . 'inserted: ' . $stats['rows_inserted']);

        return self::SUCCESS;
    }

    private function buildJsonPayload(array $entry): array
    {
        $members = [];
        $i = 1;
        foreach ($entry['rows'] as $r) {
            $patent_no = trim((string) ($r['Patent No.'] ?? '')) ?: null;

            $members['patent_' . $i] = [
                'patent_internal_id' => trim((string) ($r['Patent Internal ID'] ?? '')),
                'patent_title' => trim((string) ($r['Patent Title'] ?? '')),
                'jurisdiction' => trim((string) ($r['Code'] ?? '')) ?: null,
                'patent_no' => $patent_no,
                'status' => 'published',
                'filed_date' => null,
                'published_date' => $this->parseDate((string) ($r['Filed Date'] ?? '')),
            ];
            $i++;
        }

        return [
            'title' => $entry['canonical_title'],
            'co_inventors' => $entry['co_inventors'],
            'family_prefix' => $entry['family_prefix'],
            'members' => $members,
        ];
    }

    private function resolvePath(string $path): string
    {
        if (str_starts_with($path, '/') || preg_match('/^[A-Z]:\\\\/', $path)) {
            return $path;
        }
        return base_path($path);
    }
}