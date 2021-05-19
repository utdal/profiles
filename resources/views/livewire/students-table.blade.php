<div class="livewire-datatable">

    <div class="form-row">
        <div class="form-group col-lg-2">
            <label for="studentNameSearch">Name</label>
            <input wire:model.debounce.250ms="search" type="text" id="studentNameSearch" class="form-control" placeholder="Search...">
        </div>
        <div class="form-group col-lg-2">
            <label for="studentTagSearch">Topic Interests</label>
            <select wire:model="tag_filter" id="studentTagSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($tags as $tag)
                <option value="{{ $tag->slug }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="studentFacultySearch">Faculty Interest</label>
            <select wire:model="faculty_filter" id="studentFacultySearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($faculty as $fac)
                <option value="{{ $fac }}">{{ $fac }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="studentSchoolSearch">School</label>
            <select wire:model="schools_filter" id="studentSchoolSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($schools as $school)
                <option value="{{ $school }}">{{ $school }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="studentSemesterSearch">Semester</label>
            <select wire:model="semester_filter" id="studentSemesterSearch" class="form-control">
                <option value="" selected>All</option>
                @foreach($semesters as $semester)
                <option value="{{ $semester }}">{{ $semester }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="studentStatus">Status</label>
            <select wire:model="status_filter" id="studentStatus" class="form-control">
                <option value="" selected>All</option>
                <option value="submitted">submitted</option>
                <option value="drafted">drafted</option>
            </select>
        </div>
    </div>

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'ID', 'field' => 'id'])
                @include('livewire.partials._th-sortable', ['title' => 'Name', 'field' => 'full_name'])
                <th>Topic Interests</th>
                <th>Faculty Interest</th>
                <th>Schools</th>
                <th>Applying For</th>
                <th>Graduates</th>
                @include('livewire.partials._th-sortable', ['title' => 'Status', 'field' => 'status'])
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td><a href="{{ route('students.show', ['student' => $student]) }}">{{ $student->full_name }}</a></td>
                <td>{{ $student->tags->implode('name', ', ') }}</td>
                <td>{{ implode(', ', $student->research_profile->faculty ?? []) }}</td>
                <td>{{ implode(', ', $student->research_profile->schools ?? []) }}</td>
                <td>{{ implode(', ', $student->research_profile->semesters ?? []) }}</td>
                <td>{{ $student->research_profile->graduation_date }}</td>
                <td>{{ $student->status }}</td>
                <td class="text-center">
                    <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank" title="View in new tab/window">
                        <i class="far fa-fw fa-window-restore"></i><span class="sr-only">View</span>
                    </a>
                    <a href="{{ route('students.show', ['student' => $student]) }}#student_feedback" target="_blank" title="Add or view feedback">
                        <i class="fas fa-fw fa-comment"></i><span class="sr-only">Feedback</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $students->links() }}
        </div>
        <div class="col-lg-2">
            <select wire:model="per_page" id="perPage" class="form-control form-control-sm">
                <option value="10">10 per page</option>
                <option value="25" selected>25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>
</div>
