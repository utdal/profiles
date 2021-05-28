<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-4">
            <label for="userSearch">Search</label>
            <input wire:model.debounce.250ms="search" type="text" id="userSearch" class="form-control" placeholder="Search...">
        </div>
        <div class="form-group col-lg-2">
            <label for="userTitleSearch">Title</label>
            <select wire:model="title_filter" id="userTitleSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($titles as $title)
                <option value="{{ $title }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="userSchoolSearch">School</label>
            <select wire:model="schools_filter" id="userSchoolSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->short_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="userDepartmentSearch">Department</label>
            <select wire:model="department_filter" id="userDepartmentSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($departments as $department)
                <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
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
                @include('livewire.partials._th-sortable', ['title' => $settings['account_name'] ?? 'Username', 'field' => 'name'])
                @include('livewire.partials._th-sortable', ['title' => 'Profile', 'field' => 'pea'])
                @include('livewire.partials._th-sortable', ['title' => 'First', 'field' => 'firstname'])
                @include('livewire.partials._th-sortable', ['title' => 'Last', 'field' => 'lastname'])
                @include('livewire.partials._th-sortable', ['title' => 'Title', 'field' => 'title'])
                <th>School</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td><a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->id }}</a></td>
                <td>{{ $user->name }}</td>
                <td>
                    @if($user_profile = $user->profiles->first())
                        <a href="{{ route('profiles.show', ['profile' => $user_profile]) }}">{{ $user->pea }}</a>
                    @else
                        {{ $user->pea }}
                    @endif
                </td>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->title }}</td>
                <td>{{ $user->schools->implode('short_name', ', ') }}</td>
                <td>{{ $user->department }}</td>
                <td class="text-center text-nowrap">
                    <a href="{{ route('users.show', ['user' => $user]) }}" title="View">
                        <i class="fas fa-fw fa-link"></i><span class="sr-only">View</span>
                    </a>
                    <a href="{{ route('users.edit', ['user' => $user]) }}" target="_blank" title="Edit">
                        <i class="fas fa-fw fa-edit"></i><span class="sr-only">Edit</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $users->links() }}
        </div>
    </div>
</div>
