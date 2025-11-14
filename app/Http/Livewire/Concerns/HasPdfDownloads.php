<?php

namespace App\Http\Livewire\Concerns;

use App\Jobs\ProcessPdfJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait HasPdfDownloads
{
    public function initiatePdfDownload($view, $data, $filename, $file_description = '')
    {
        if (!$data) { return false; }

        $user = auth()->user();
        $token = (string) Str::ulid();

        $this->cachePdfToken($user, $token);

        $download_request_url = URL::temporarySignedRoute('pdf.requestDownload', now()->addMinutes(10), ['user' => $user, 'token' => $token]);

        ProcessPdfJob::dispatch($user, $view, $filename, $file_description, $token, $data);
        
        $this->dispatchBrowserEvent('initiatePdfDownload', ['download_request_url' => $download_request_url]);
    }

    protected function cachePdfToken($user, $token) {
        $user_tokens = Cache::get("pdf:tokens:{$user->pea}", collect());
        $user_tokens->push($token);

        Cache::put("pdf:tokens:{$user->pea}", $user_tokens, now()->addMinutes(30));
    }

}
