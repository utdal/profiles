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
                <th>id</th>
                <th>Name</th>
                <th>Topic Interests</th>
                <th>Faculty Interest</th>
                <th>Schools</th>
                <th>Graduates</th>
                <th>Status</th>
                <th>Updated</th>
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
                <td>{{ $student->research_profile->graduation_date }}</td>
                <td>{{ $student->status }}</td>
                <td>{{ $student->updated_at->toFormattedDateString() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
