<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\PdfReady;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;

class ProcessPdfDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $view;
    public $filename;
    public $data;
    public $user;
    public $profile;
    public $route_name;
    public $description;
    public $token;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Profile $profile, $view = 'students.download', $route_name, $filename, $description, $token, $data = [])
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->view = $view;
        $this->route_name = $route_name;
        $this->filename = $filename;
        $this->data = $data;
        $this->description = $description;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = '';
        $html .= view($this->view, $this->data)->render();

        $folder_timestamp = $this->currentReportBucket();
        $dir = "tmp/reports/{$folder_timestamp}";

        $file_timestamp = Carbon::now()->addMinutes(30)->format('YmdHis');
        $filename = "{$this->filename}_{$file_timestamp}.pdf";

        $storage_path = "$dir/$filename";
        $absolute_path = Storage::path($storage_path); 

        Storage::makeDirectory($dir);

        $content = Browsershot::html($html)
                        ->waitUntilNetworkIdle()
                        ->ignoreHttpsErrors()
                        ->margins(30, 15, 30, 15);

        if (config('pdf.node')) {
            $content = $content->setNodeBinary(config('pdf.node'));
        }

        if (config('pdf.npm')) {
            $content = $content->setNpmBinary(config('pdf.npm'));
        }

        if (config('pdf.modules')) {
            $content = $content->setIncludePath(config('pdf.modules'));
        }

        if (config('pdf.chrome')) {
            $content = $content->setChromePath(config('pdf.chrome'));
        }

        if (config('pdf.chrome_arguments')) {
            $content = $content->addChromiumArguments(config('pdf.chrome_arguments'));
        }

        $content->timeout(60)
                ->save($absolute_path);

        PdfReady::dispatch($this->user, $this->profile, $this->route_name, $storage_path, $filename, $this->description, $this->token);
    }

    function currentReportBucket()
    {
        $t = Carbon::now()->copy()->second(0);

        $bucket_minute = (int) (floor($t->minute / 30) * 30);
        $t->minute($bucket_minute);

        return $t->format('Ymd_Hi');
    }

}
