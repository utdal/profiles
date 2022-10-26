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

    protected $listeners = ['AAPublicationsModalShown' => 'showModal', 'addToEditor', 'removeFromEditor'];

    public Profile $profile;

    public bool $modalVisible = false;

    public $imported_publications = [];

    public function showModal()
    {
        $this->modalVisible = true;
    }

    public function getPublicationsProperty()
    {
        $per_page = 10;

        $aa_publications = Cache::remember(
            "profile{$this->profile->id}-AA-pubs",
            15 * 60,
            fn() => $this->profile->getAcademicAnalyticsPublications()
        );

        $aa_publications->whereIn('id', $this->imported_publications)->transform(function ($elem, $key) {
            return $elem->imported = true;
        });

        return $aa_publications->sortByDesc('sort_order')->paginate($per_page);
    }

    public function addToEditor($publication_id)
    {
        array_push($this->imported_publications, $publication_id);
        $this->emit( 'alert', "Added to the Editor!", 'success');
    }

    public function render()
    {
        return view('livewire.academic-analytics-publications');
    }
}
