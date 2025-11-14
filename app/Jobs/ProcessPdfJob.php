<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\PdfReady;
use App\Services\PdfGenerationService;
use App\Student;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class ProcessPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $view;
    public $filename_prefix;
    public $data;
    public $token;
    public $user;
    public $description;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $view, $filename_prefix, $description, $token, $data = [])
    {
        $this->user = $user;
        $this->view = $view;
        $this->filename_prefix = $filename_prefix;
        $this->token = $token;
        $this->data = $data;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle(PdfGenerationService $service): void
    {
        $result = $service->generatePdf($this->data, $this->view, $this->filename_prefix);

        $download_info = [ 
                            'path' => $result->path,
                            'filename' => $result->filename,
                            'user_id' => $this->user->id,
                            'description' => $this->description,
                        ];
        
        Cache::put("pdf:ready:{$this->user->pea}:{$this->token}", $download_info, now()->addMinutes(30));
    }

}
