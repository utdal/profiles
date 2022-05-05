<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="userSearch">Search</label>
            <input wire:model.debounce.250ms="search_filter" type="text" id="userSearch" class="form-control" placeholder="Search...">
        </div>
        <div class="form-group col-lg-2">
            <label for="delegationNotifyFilter">Notify</label>
            <select wire:model="notify_filter" id="delegationNotifyFilter" class="form-control">
                <option value="" selected>All</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="perPage">Per Page</label>
            <select wire:model="per_page" id="perPage" class="form-control">
                <option value="10">10 per page</option>
                <option value="25" selected>25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
        <div class="form-group col-lg-2 text-center">
            <div class="mb-2">&nbsp;</div>
            <button type="button" class="btn btn-block btn-outline-primary" wire:click="resetFilters">
                Clear All Filters
            </button>
        </div>
    </div>

    @include('livewire.partials._applied-filters')

    <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
        <caption class="sr-only">List of user delegations</caption>
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'Delegator', 'field' => 'delegator.display_name'])
                @include('livewire.partials._th-sortable', ['title' => 'Delegate', 'field' => 'delegate.display_name'])
                @include('livewire.partials._th-sortable', ['title' => 'Starting', 'field' => 'starting'])
                @include('livewire.partials._th-sortable', ['title' => 'Until', 'field' => 'until'])
                @include('livewire.partials._th-sortable', ['title' => 'Notify', 'field' => 'gets_reminders'])
                @include('livewire.partials._th-sortable', ['title' => 'Created', 'field' => 'created_at'])
                @include('livewire.partials._th-sortable', ['title' => 'Updated', 'field' => 'updated_at'])
            </tr>
        </thead>
        <tbody>
            @foreach ($this->delegations as $delegation)
            <tr>
                <td><a href="{{ route('users.delegations.show', ['user' => $delegation->delegator]) }}">{{ $delegation->delegator->display_name }}</a></td>
                <td><a href="{{ route('users.delegations.show', ['user' => $delegation->delegate]) }}">{{ $delegation->delegate->display_name }}</a></td>
                <td>{{ optional($delegation->starting)->toFormattedDateString() ?? '∞' }}</td>
                <td>{{ optional($delegation->until)->toFormattedDateString() ?? '∞' }}</td>
                <td>{{ $delegation->gets_reminders ? 'yes' : 'no' }}</td>
                <td>{{ $delegation->created_at->toDatetimeString() }}</td>
                <td>{{ $delegation->updated_at->toDatetimeString() }}</td>
            </tr>
            @endforeach
            @include('livewire.partials._loading-indicator')
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $this->delegations->links() }}
        </div>
    </div>
</div>
