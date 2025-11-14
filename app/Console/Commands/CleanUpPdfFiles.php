<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanUpPdfFiles extends Command
{
    protected $signature = 'files:clean-up-pdfs
                            {--root=tmp/reports : Root directory where timestamped folders live}
                            {--hours=2 : Delete folders older than this many hours}
                            {--disk= : Filesystem disk to use (defaults to FILESYSTEM_DISK)}
                            {--dry : Show what would be deleted, but don\'t delete}';

    protected $description = 'Delete temporary PDF folders (Ymd_Hi format) older than the specified number of hours. Default: 2 hours.';

    public function handle(): int
    {
        $disk_name = $this->option('disk') ?: config('filesystems.default');
        $disk     = Storage::disk($disk_name);

        $root     = rtrim($this->option('root') ?? 'tmp/reports', '/');
        $hours_opt = (string) ($this->option('hours') ?? '2');

        
        if (!ctype_digit($hours_opt)) { // validate hours
            $this->error("Invalid --hours value: {$hours_opt}. Use a non-negative integer.");
            return self::FAILURE;
        }
        $hours = (int) $hours_opt;

        $now    = now()->second(0);
        $cutoff = $now->copy()->subHours($hours);

        if (!$disk->exists($root)) {
            $this->info("Root '{$root}' does not exist on disk '{$disk_name}'. Nothing to do.");
            return self::SUCCESS;
        }

        // Only consider folders named Ymd_Hi (e.g., 20250826_1430)
        $dirs = collect($disk->directories($root))
                ->filter(fn ($path) => preg_match('~^' . preg_quote($root, '~') . '/\d{8}_\d{4}$~', $path))
                ->sort()
                ->values();

        if ($dirs->isEmpty()) {
            $this->info("No timestamped folders found under '{$root}'.");
            return self::SUCCESS;
        }

        $dry = (bool) $this->option('dry');
        $deleted = 0; $skipped = 0;

        foreach ($dirs as $dir) {
            $stamp = basename($dir);

            // Parse folder timestamp; skip if malformed
            $bucket_time = Carbon::createFromFormat('Ymd_Hi', $stamp, $now->timezone)->second(0);
            if ($bucket_time === false) {
                $this->warn("Skipping malformed folder name: {$dir}");
                $skipped++;
                continue;
            }

            if ($bucket_time->lt($cutoff)) {
                if ($dry) {
                    $this->line("[DRY] Would delete: {$dir} ({$bucket_time->toDateTimeString()})");
                } else {
                    $disk->deleteDirectory($dir);
                    $this->line("Deleted: {$dir} ({$bucket_time->toDateTimeString()})");
                }
                $deleted++;
            } else {
                $skipped++;
            }
        }

        $summary = $dry ? 'would delete' : 'deleted';
        $this->info("Cleanup complete: {$summary} {$deleted}, kept {$skipped}. Disk='{$disk_name}', Root='{$root}', Cutoff='{$cutoff->toDateTimeString()}'.");

        return self::SUCCESS;
    }
}