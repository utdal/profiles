<?php

namespace App\Http\Livewire;


use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DownloadReadyWatcher extends Component
{
    public bool $ready = false;
    public bool $polling = false;
    public $download = null;

    public $token;

    // protected $listeners = ['resetWatcher'];

    public function mount($token)
    {
        $this->token = (string) $token;
    }

    // public function updatedToken($value)
    // {
    //     if ($value) $this->resetWatcher($value);
    // }

    // public function resetWatcher($token)
    // {
    //     $this->token = $token;
    //     $this->polling = true;
    //     $this->reset(['ready', 'download']);
    // }

    public function check()
    {
        if (!$this->polling || !$this->token) return;

        $user = auth()->user();

        if (!$user->id) return;

        $key = "pdf:ready:{$user->name}:{$this->token}";

        $payload = Cache::pull($key);

        if (!empty($payload)) {
            $this->download = $payload;
            $this->ready = true;
            $this->polling = false;
            $this->dispatchBrowserEvent('pdfDownloadReady');
            $this->emit('alert', 'Your PDF file is ready for download!');
        }
    }

    public function render()
    {
        return view('livewire.download-ready-watcher');
    }
}
