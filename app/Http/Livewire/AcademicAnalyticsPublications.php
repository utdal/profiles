<?php

namespace App\Http\Livewire;

use App\Profile;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicAnalyticsPublications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['AAPublicationsModalShown' => 'showModal', 'addToEditor', 'removeFromEditor', 'addAllToEditor', 'removeAllFromEditor'];

    public Profile $profile;

    public bool $modalVisible = false;

    public $importedPublications = [];

    public $perPage = 10;

    public bool $allChecked = false;

    public bool $transform = true;

    public $allPublicationsCount;

    public function showModal()
    {
        $this->modalVisible = true;
    }

    public function addToEditor($publicationId)
    {
        array_push($this->importedPublications, $publicationId);
        $this->reset('transform');
        $this->emit( 'alert', "Added to the Editor!", 'success');
    }

    public function removeFromEditor($publicationId)
    {
        if (($key = array_search($publicationId, $this->importedPublications)) !== false) {
            unset($this->importedPublications[$key]);
        }
        $this->reset('transform');
        $this->emit('alert', "Removed from the Editor!", 'success');
    }

    public function addAllToEditor()
    {
        $pubs_to_import = $this->getNewAAPublications()->whereIn('imported', false);
        $this->importedPublications = $pubs_to_import->pluck('id')->all();
        $this->allChecked = true;
        $this->reset('transform');
        $this->emit('JSAddAllToEditor', $pubs_to_import);
    }

    public function removeAllFromEditor()
    {
        $this->reset('importedPublications');
        $this->reset('allChecked');
        $this->transform = false;
    }

    public function cachedAAPublications()
    {
        return Cache::remember(
            "profile{$this->profile->id}-AA-pubs",
            15 * 60,
            fn() => $this->profile->getAcademicAnalyticsPublications()
        );
    }

    public function getNewAAPublications()
    {
        return $this->cachedAAPublications()
            ->whereNotIn('doi', $this->profile->publications->pluck('data.doi')->filter()->values());
    }

    public function getPublicationsProperty()
    {
        $aaPublications = $this->getNewAAPublications();

        $this->allPublicationsCount = count($aaPublications);

        if ($this->transform) {
            $aaPublications
                ->whereIn('id', $this->importedPublications)
                ->transform(fn($elem, $key) => $elem->imported = true);
        }

        return $aaPublications->sortByDesc('sort_order')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.academic-analytics-publications');
    }
}
