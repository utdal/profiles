<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-2">
            <label for="profileSearch">Search</label>
            <input wire:model.debounce.250ms="search_filter" type="text" id="profileSearch" class="form-control" placeholder="Search...">
        </div>
        <div class="form-group col-lg-2">
            <label for="profileSchoolFilter">School</label>
            <select wire:model="schools_filter" id="profileSchoolFilter" class="form-control">
                <option value="" selected>All</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->short_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="publicFilter">Public</label>
            <select wire:model="public_filter" id="publicFilter" class="form-control">
                <option value="" selected>All</option>
                <option value="1">Public</option>
                <option value="0">Not Public</option>
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="typeFilter">Type</label>
            <select wire:model="type_filter" id="typeFilter" class="form-control">
                <option value="" selected>All</option>
                @foreach (App\Enums\ProfileType::cases() as $profile_type)
                    <option value="{{ $profile_type->value }}">{{ $profile_type->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-1">
            <label for="archivedFilter">Archived</label>
            <select wire:model="archived_filter" id="archivedFilter" class="form-control">
                <option value="0" selected>No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="form-group col-lg-1">
            <label for="perPage">Per Page</label>
            <select wire:model="per_page" id="perPage" class="form-control">
                <option value="10">10</option>
                <option value="25" selected>25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="form-group col-lg-2 text-center">
            <div class="mb-2">&nbsp;</div>
            <button type="button" class="btn btn-block btn-outline-primary" wire:click="resetFilters">
                Clear All Filters
            </button>
        </div>
    </div>

    @include('livewire.partials._applied-filters', ['filter_value_names' => ['schools_filter' => $schools->pluck('short_name', 'id')->all(), 'type_filter' => App\Enums\ProfileType::toArray()]])

    <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
        <caption class="sr-only">List of profiles</caption>
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'ID', 'field' => 'id'])
                @include('livewire.partials._th-sortable', ['title' => 'Full Name', 'field' => 'full_name'])
                @include('livewire.partials._th-sortable', ['title' => 'Slug', 'field' => 'slug'])
                <th>School</th>
                <th>Visibility</th>
                @include('livewire.partials._th-sortable', ['title' => 'Created', 'field' => 'created_at'])
                @include('livewire.partials._th-sortable', ['title' => 'Updated', 'field' => 'updated_at'])
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->profiles as $profile)
            <tr>
                <td>{{ $profile->id }}</td>
                <td>{{ $profile->full_name }}</td>
                @if($profile->trashed())
                    <td>{{ $profile->slug }}</td>
                @else
                    <td><a href="{{ $profile->url }}">{{ $profile->slug }}</a></td>
                @endif
                <td>{{ optional($profile->user->school)->short_name }}</td>
                <td class="text-center">
                    <span class="fas {{ $profile->public ? 'fa-eye' : 'fa-eye-slash text-muted' }}" title="{{ $profile->public ? 'Public' : 'Not public' }}"></span>
                    @if($profile->trashed())
                        <span class="fas fa-archive" title="Archived"></span>
                    @endif
                    @switch($profile->type)
                        @case(App\Enums\ProfileType::Unlisted)
                            <span class="fas fa-link text-muted" title="Unlisted"></span>
                            @break
                        @case(App\Enums\ProfileType::InMemoriam)
                            <span class="fas fa-monument" title="In Memoriam"></span>
                            @break
                    @endswitch
                </td>
                <td>{{ $profile->created_at->toFormattedDateString() }}</td>
                <td>{{ $profile->updated_at->toFormattedDateString() }}</td>
                <td>
                    <a href="{{ $profile->url }}" title="View">
                        <i class="fas fa-fw fa-link"></i><span class="sr-only">View</span>
                    </a>
                    @can('update', $profile)
                    <a href="{{ route('profiles.edit', ['profile' => $profile, 'section' => 'information']) }}" target="_blank" title="Edit Information">
                        <i class="fas fa-fw fa-edit"></i><span class="sr-only">Edit</span>
                    </a>
                    @endcan
                    <livewire:bookmark-button :model="$profile" :mini="true" :wire:key="$profile->id">
                    <span>
                    @if($profile->trashed())
                        @can('restore', $profile)
                            <a href="{{ route('profiles.confirm-restore', [ $profile ]) }}" title="Restore" role="button">
                                <i class="fas fa-trash-restore"></i><span class="sr-only">restore archived profile</span>
                            </a>
                        @endcan
                    @else
                        @can('delete', $profile)
                            <a href="{{ route('profiles.confirm-delete', [ $profile ]) }}" title="Archive" role="button">
                                <i class="fas fa-archive"></i><span class="sr-only">archive profile</span>
                            </a>
                        @endcan
                    @endif
                    </span>
                </td>
            </tr>
            @endforeach
            @include('livewire.partials._loading-indicator')
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $this->profiles->links() }}
        </div>
    </div>
</div>
