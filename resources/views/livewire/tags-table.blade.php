<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-8">
            <label for="tagNameSearch">Tag Name</label>
            <input wire:model.debounce.250ms="search" type="text" id="studentNameSearch" class="form-control" placeholder="Search...">
        </div>
        <div class="form-group col-lg-2">
            <label for="tagTypeFilter">Tag Type</label>
            <select wire:model="tag_type_filter" id="tagTypeFilter" class="form-control">
                <option value="" selected>All</option>
                @foreach($tag_types as $tag_type)
                <option value="{{ $tag_type }}">{{ $tag_type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="perPage">Per Page</label>
            <select wire:model="per_page" id="perPage" class="form-control">
                <option value="10">10</option>
                <option value="25" selected>25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
        <caption class="sr-only">List of tags</caption>
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'ID', 'field' => 'id'])
                @include('livewire.partials._th-sortable', ['title' => 'Name', 'field' => 'name->en'])
                @include('livewire.partials._th-sortable', ['title' => 'Slug', 'field' => 'slug->en'])
                @include('livewire.partials._th-sortable', ['title' => 'Type', 'field' => 'type'])
                @include('livewire.partials._th-sortable', ['title' => 'Created', 'field' => 'created_at'])
                @include('livewire.partials._th-sortable', ['title' => 'Updated', 'field' => 'updated_at'])
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->id }}</td>
                <td><a href="{{ route('profiles.index', ['search' => $tag->name]) }}">{{ $tag->name }}</a></td>
                <td>{{ $tag->slug }}</td>
                <td>{{ $tag->type }}</td>
                <td>{{ $tag->created_at->toFormattedDateString() }}</td>
                <td>{{ $tag->updated_at->toFormattedDateString() }}</td>
                <td>
                    <a onclick="confirm('Are you sure you want to remove the {{ $tag->slug }} tag?') || event.stopImmediatePropagation()" wire:click="destroy({{ $tag->id }})" role="button" title="delete">
                        <i class="far fa-trash-alt"></i><span class="sr-only">Trash</span>
                    </a>
                </td>
            </tr>
            @endforeach
            @include('livewire.partials._loading-indicator')
        </tbody>
    </table>

    {{ $tags->links() }}

    @can('create', Spatie\Tags\Tag::class)
        <a href="{{ route('tags.create') }}" class="btn btn-primary" role="button"><i class="fa fa-plus"></i> Add Tags</a>
    @endcan
</div>
