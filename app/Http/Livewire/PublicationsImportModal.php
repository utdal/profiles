<?php

namespace App\Http\Livewire;

use App\Profile;
use App\Providers\AAPublicationsApiServiceProvider;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\App;

class PublicationsImportModal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['AAPublicationsModalShown' => 'showModal', 'addToEditor', 'removeFromEditor', 'addAllToEditor', 'removeAllFromEditor'];
    public Profile $profile;
    public bool $modalVisible = false;
    public bool $allChecked = false;
    public $importedPublications = [];
    public $perPage = 10;
    public $allPublicationsCount;

    public function showModal()
    {
        $this->modalVisible = true;
    }

    public function addToEditor($publicationId)
    {
        array_push($this->importedPublications, $publicationId);
        $this->emit( 'alert', "Added to the Editor!", 'success');
    }

    public function removeFromEditor($publicationId)
    {
        if (($key = array_search($publicationId, $this->importedPublications)) !== false) {
            unset($this->importedPublications[$key]);
        }
        $this->emit('alert', "Removed from the Editor!", 'success');
    }

    public function addAllToEditor()
    {
        $pubs_to_import = App::call([$this, 'getNewPublications'])->whereIn('imported', false);
        $this->importedPublications = $pubs_to_import->pluck('id')->all();
        $this->allChecked = true;
        $this->emit('JSAddAllToEditor', $pubs_to_import);
    }

    public function removeAllFromEditor()
    {
        $this->reset('importedPublications');
        $this->reset('allChecked');
    }

    public function getNewPublications(AAPublicationsApiServiceProvider $pubServiceProvider)
    {
        $aaPublications = $pubServiceProvider->getCachedPublications($this->profile->id, $this->profile->academic_analytics_id);

        return $aaPublications
            ->whereNotIn('doi', $this->profile->publications->pluck('data.doi')->filter()->values());
    }

    public function getPublicationsProperty()
    {
        $aaPublications = App::call([$this, 'getNewPublications']);

        $this->allPublicationsCount = count($aaPublications);

        $aaPublications
            ->each(function($elem, $key) {
                $elem->imported = (in_array($elem->id, $this->importedPublications));
            });

        return $aaPublications->sortByDesc('sort_order')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.publications-import-modal');
    }
}
