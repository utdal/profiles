<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="profileSearch">Search</label>
            <input wire:model.debounce.250ms="search" type="text" id="profileSearch" class="form-control" placeholder="Search...">
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
                <option value="1" selected>Public</option>
                <option value="0" selected>Not Public</option>
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
    </div>

    <table class="table table-sm table-striped table-responsive-lg">
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'ID', 'field' => 'id'])
                @include('livewire.partials._th-sortable', ['title' => 'Full Name', 'field' => 'full_name'])
                @include('livewire.partials._th-sortable', ['title' => 'Slug', 'field' => 'slug'])
                <th>School</th>
                @include('livewire.partials._th-sortable', ['title' => 'Public', 'field' => 'public'])
                @include('livewire.partials._th-sortable', ['title' => 'Created', 'field' => 'created_at'])
                @include('livewire.partials._th-sortable', ['title' => 'Updated', 'field' => 'updated_at'])
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles as $profile)
            <tr>
                <td>{{ $profile->id }}</td>
                <td>{{ $profile->full_name }}</td>
                <td><a href="{{ $profile->url }}">{{ $profile->slug }}</a></td>
                <td>{{ optional($profile->user->school)->short_name }}</td>
                <td><span class="fas {{ $profile->public ? 'fa-eye' : 'fa-eye-slash text-muted' }}"></span></td>
                <td>{{ $profile->created_at->toFormattedDateString() }}</td>
                <td>{{ $profile->updated_at->toFormattedDateString() }}</td>
                <td class="text-center text-nowrap">
                    <a href="{{ $profile->url }}" title="View">
                        <i class="fas fa-fw fa-link"></i><span class="sr-only">View</span>
                    </a>
                    @can('update', $profile)
                    <a href="{{ route('profiles.edit', ['profile' => $profile, 'section' => 'information']) }}" target="_blank" title="Edit Information">
                        <i class="fas fa-fw fa-edit"></i><span class="sr-only">Edit</span>
                    </a>
                    @endcan
                    <span><livewire:bookmark-button :model="$profile" :mini="true" :wire:key="$profile->id"></span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $profiles->links() }}
        </div>
    </div>
</div>
