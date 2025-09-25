<?php

namespace App\Http\Livewire;


use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class DownloadReadyWatcher extends Component
{
    use AuthorizesRequests;

    public bool $ready = false;
    public bool $polling = false;
    public $download = [];
    public $token;

    public function mount($token)
    {
        $this->token = (string) $token;
    }

    public function check()
    {
        if (!$this->polling || !$this->token) return;

        $user = auth()->user();

        if (!$user->id) return;

        $this->authorize('downloadPdf', $this->token);

        $this->download = Cache::get("pdf:ready:{$user->pea}:{$this->token}");

        if (!empty($this->download)) {
            $this->ready = true;
            $this->polling = false;
            $this->dispatchBrowserEvent('pdfDownloadReady');
            $this->emit('alert', 'Your PDF file is ready for download!');
        }
    }

    public function download()
    {
        $this->authorize('downloadPdf', $this->token);

        $path = $this->download['path'];
        $name = $this->download['filename'] ?? 'document.pdf';

        abort_unless(is_string($path), 403);
        abort_unless(Storage::exists($path), 404);

        $absolute = Storage::path($path);
        return response()->download($absolute, $name)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.download-ready-watcher');
    }
}
