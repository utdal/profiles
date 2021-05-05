<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Tags\Tag;

class TagsModal extends Component
{
    /** @var Illuminate\Database\Eloquent\Model */
    public $model;

    public $model_slug;

    public $tags;

    public $selected_tags;

    public $tags_type;

    public function mount()
    {
        $this->model_slug = Str::slug($this->model->getRouteKey());
        $this->tags = $this->model->tags ?? collect();
        $this->tags = $this->tags->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        $this->selected_tags = $this->selected_tags ?? $this->tags;
        $this->tags_type = $this->tags_type ?? get_class($this->model);
    }

    public function toggleTag(Tag $tag)
    {
        if ($this->selected_tags->contains('id', $tag->id)) {
            $this->selected_tags = $this->selected_tags->keyBy('id')->forget($tag->id);
        } else {
            $this->selected_tags->push($tag);
        }

        $this->model->syncTagsWithType($this->selected_tags, $this->tags_type);
        $this->tags = $this->selected_tags->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function getPossibleTagsProperty()
    {
        // This is a computed property because Livewire doesn't
        // handle groupBy well in native properties.
        return Tag::where('type', $this->tags_type)
            ->orderBy('name->en')
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy(function ($tag, $key) {
                return strtoupper($tag->name[0]);
            })
            ->sortKeys();
    }

    public function render()
    {
        return view('livewire.tags-modal');
    }
}
