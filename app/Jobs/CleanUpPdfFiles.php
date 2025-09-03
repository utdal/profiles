<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CleanUpPdfFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $root;
    public string $cutoff;

    /**
     * Create a new job instance.
     */
    public function __construct(?array $options)
    {
        $this->root = $options['root'] ?? 'tmp/reports';
        $this->cutoff = $options['cutoff'] ?? now()->subHours(1);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach (Storage::directories($this->root) as $dir) {
            $stamp = basename($dir); //"20250826_1430"

            $bucketTime = Carbon::createFromFormat('Ymd_Hi', $stamp)->second(0);

            if ($bucketTime->lt($this->cutoff)) {
                Storage::deleteDirectory($dir);
            }
        }
    }
}
