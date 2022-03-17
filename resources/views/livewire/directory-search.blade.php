<div class="directory-search input-group dropdown">
    <input
        id="{{ $input_id }}"
        wire:model.debounce.250ms="query"
        wire:keydown.escape="resetSearch"
        type="text"
        class="form-control position-relative mb-0 dropdown-toggle"
        data-toggle="dropdown"
        data-flip="false"
        placeholder="search for a person..."
        autocomplete="off"
        @if($aria_describedby) aria-describedby="{{ $aria_describedby }}" @endif
        @if($required) required @endif
    >
    <input wire:model="selected_username" type="hidden" name="{{ $input_name }}">

    <div class="dropdown-menu shadow border-primary w-100">
        <button
            wire:loading.delay.short
            wire:loading.class="dropdown-item disabled"
            wire:target="query"
        >
            <i class="fas fa-sync fa-spin"></i> Searching
        </button>
        @forelse($people as $index => $person)
            <button
                class="dropdown-item"
                type="button"
                wire:key="{{ $person[$username_attribute] }}"
                wire:click="selectPerson({{ $index }})"
                wire:keydown.enter="selectPerson({{ $index }})"
            >
                {{ $person[$displayname_attribute] ?? 'Name' }}, 
                {{ $person[$title_attribute] ?? '' }} 
                ({{ $person[$username_attribute] ?? 'username' }})
            </button>
        @empty
            <button class="dropdown-item disabled">No Results</button>
        @endforelse
    </div>

    @push('scripts')
    <script>
        if (typeof Livewire === 'object') {
            // Make this work better w/default Bootstrap dropdown events
            Livewire.on('profiles.directorySearch.query.updated', () => {
                $('.directory-search input').click();
            });
        }
    </script>
    @endpush
</div>
