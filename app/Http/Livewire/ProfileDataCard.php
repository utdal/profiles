<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\ProfileSectionType;

class ProfileDataCard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $profile;
    public $data_type;
    public $editable;
    public $paginated = true;
    public $per_page;
    public $public_filtered = false;

    public function mount(Profile $profile, $editable, $data_type, $paginated = true, $public_filtered = false)
    {
        $section = ProfileSectionType::tryFrom($data_type);

        if (!$section) {
            abort(403, 'Invalid profile section type');
        }

        $this->profile = $profile;
        $this->editable = $editable;
        $this->data_type = $data_type;
        $this->paginated = $paginated;
        $this->public_filtered = $public_filtered;
    }

    public function updatingDataType($value)
    {
        $section = ProfileSectionType::tryFrom($value);

        if (!$section) {
            abort(403, 'Invalid profile section type');
        }
        $this->emit('alert', $this->data_type);
    }

    public function data()
    {
        $section = ProfileSectionType::tryFrom($this->data_type);
        
        $data_query = $this->profile->{$section->value}();

        if ($this->public_filtered) {
            $data_query = $data_query->public();
        }

        if ($this->paginated) {
            return $data_query->paginate(
                $section->perPage() ?? ProfileSectionType::Default->perPage(),
                ['*'],
                $this->data_type
            );
        }

        return $data_query->get();
    }

    public function render()
    {
        $data = $this->data();

        if ($data->isEmpty() && !$this->editable) {
            return '';
        }

        return view("livewire.profile-data-cards/{$this->data_type}", [
            'data' => $data,
            'editable' => $this->editable,
            'profile' => $this->profile,
            'paginated' => $this->paginated,
        ]);
    }
}