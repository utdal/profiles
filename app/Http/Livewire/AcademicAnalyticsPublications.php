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

    public bool $modalVisible = false;
    public Profile $profile;
    protected $listeners = ['AAPublicationsModalShown' => 'showModal'];

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
            fn() => $this->profile
                    ->getAcademicAnalyticsPublications()
                    ->sortByDesc('sort_order')
        );

        return $aa_publications->paginate($per_page);
    }

    public function render()
    {
        return view('livewire.academic-analytics-publications');
    }
}
