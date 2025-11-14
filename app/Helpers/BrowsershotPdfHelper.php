<?php

namespace App\Helpers;

use App\Helpers\Contracts\PdfGenerationHelperContract;
use App\PdfGenerationResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;

class BrowsershotPdfHelper implements PdfGenerationHelperContract
{
    public function generate(array $payload): PdfGenerationResult
    {
        $filename = $payload['filename'] ?? '';
        $data = $payload['data'] ?? '';
        $view = $payload['view'] ?? '';

        $folder_timestamp = $this->currentReportBucket();
        $dir = "tmp/reports/{$folder_timestamp}";

        $file_timestamp = Carbon::now()->addMinutes(30)->format('YmdHis');
        $filename = "{$filename}_{$file_timestamp}.pdf";

        $storage_path = "$dir/$filename";
        $absolute_path = Storage::path($storage_path); 

        Storage::makeDirectory($dir);

        $html = '';
        $html .= view($view, $data)->render();

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
        
        return new PdfGenerationResult(
            success: true,
            filename: $filename,
            path: $storage_path,
            job_id: Str::ulid(),
        );
    }


    public function currentReportBucket()
    {
        $t = Carbon::now()->copy()->second(0);

        $bucket_minute = (int) (floor($t->minute / 30) * 30);
        $t->minute($bucket_minute);

        return $t->format('Ymd_Hi');
    }

}
