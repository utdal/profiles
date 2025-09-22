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
    public $download_route_name;
    public $description;
    public $model;
    public $ability;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $view, $download_route_name, $filename_prefix, $description, $token, $data = [], $model, $ability)
    {
        $this->user = $user;
        $this->view = $view;
        $this->download_route_name = $download_route_name;
        $this->filename_prefix = $filename_prefix;
        $this->token = $token;
        $this->data = $data;
        $this->description = $description;
        $this->model = $model;
        $this->ability = $ability;
    }

    /**
     * Execute the job.
     */
    public function handle(PdfGenerationService $service): void
    {
        $result = $service->generatePdf($this->data, $this->view, $this->filename_prefix);

        $download_url = URL::temporarySignedRoute(
                    $this->download_route_name,
                    now()->addMinutes(30),
                    ['path' => $result->path, 'filename' => $result->filename, 'user' => $this->user, 'model' => $this->model, 'ability' => $this->ability]
                );

        $download_info = ['download_url' => $download_url, 'filename' => $result->filename, 'user' => $this->user, 'description' => $this->description];

        Cache::put("pdf:ready:{$this->user->pea}:{$this->token}", $download_info, now()->addMinutes(30));
    }

}
