<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\HasFilters;
use App\Http\Livewire\Concerns\HasPagination;
use App\Http\Livewire\Concerns\HasSorting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\Tags\Tag;

class TagsTable extends Component
{
    use AuthorizesRequests;
    use HasFilters;
    use HasPagination;
    use HasSorting;

    public $search_filter = '';

    public $tag_type_filter = '';

    public function mount()
    {
        $this->per_page = 10;
    }

    public function getTagsProperty()
    {
        return Tag::query()
            ->when($this->tag_type_filter, function ($q) {
                $q->where('type', $this->tag_type_filter);
            })
            ->when($this->search_filter, function ($q) {
                $q->containing($this->search_filter);
            })
            ->orderBy($this->sort_field, $this->sort_descending ? 'desc' : 'asc')
            ->paginate($this->per_page);
    }

    public function updating($name)
    {
        $this->resetPageOnChange($name);
    }

    public function updated($name, $value)
    {
        $this->emitFilterUpdatedEvent($name, $value);
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag_name = $tag->name;

        $tag->delete();

        $this->emit('alert', "Deleted tag $tag_name", 'success');
    }

    public function render()
    {
        return view('livewire.tags-table', [
            'filter_names' => $this->availableFilters(),
            'tag_types' => Tag::groupBy('type')->pluck('type'),
        ]);
    }
}
