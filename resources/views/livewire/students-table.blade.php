<div class="livewire-datatable">

    <div class="accordion filters mb-3" id="accordionFilters">
        <div class="card">
            <div class="card-header p-0" id="filterHeading">
                <h2 class="my-0">
                    <button
                        class="btn btn-link btn-block text-left px-3 py-2"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapseFilters"
                        aria-expanded="true"
                        aria-controls="collapseFilters"
                    >
                        <span class="fa-fw fas fa-filter mr-2" style="opacity:0.3"></span>
                        Filter by <span class="fas fa-fw fa-caret-down"></span>
                    </button>
                </h2>
            </div>
            <div
                id="collapseFilters"
                class="collapse"
                aria-labelledby="filterHeading"
                data-parent="#accordionFilters"
                wire:ignore.self
            >
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-lg-2">
                            <label for="studentNameSearch">Name</label>
                            <input wire:model.debounce.250ms="search_filter" type="text" id="studentNameSearch" class="form-control" placeholder="Search...">
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
                                @foreach($faculty as $faculty_id => $faculty_name)
                                <option value="{{ $faculty_id }}">{{ $faculty_name }}</option>
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
                            <label for="studentMajorSearch">Major</label>
                            <select wire:model="major_filter" id="studentMajorSearch" class="form-control">
                                <option value="" selected>All</option>
                                @foreach($majors as $major)
                                <option value="{{ $major }}">{{ $major }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentLanguageSearch">Language</label>
                            <select wire:model="language_filter" id="studentLanguageSearch" class="form-control">
                                <option value="" selected>All</option>
                                @foreach($languages as $language_code => $language)
                                <option value="{{ $language_code }}">{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentTravelSearch">Travel to Centers</label>
                            <select wire:model="travel_filter" id="studentTravelSearch" class="form-control">
                                <option value="" selected>All</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentTravelOtherSearch">Travel to Sites</label>
                            <select wire:model="travel_other_filter" id="studentTravelOtherSearch" class="form-control">
                                <option value="" selected>All</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentAnimalsSearch">Work with Animals</label>
                            <select wire:model="animals_filter" id="studentAnimalsSearch" class="form-control">
                                <option value="" selected>All</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentCreditSearch">Research Credit</label>
                            <select wire:model="credit_filter" id="studentCreditSearch" class="form-control">
                                <option value="" selected>All</option>
                                <option value="1">Credit</option>
                                <option value="0">Volunteer</option>
                                <option value="-1">No Preference</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="studentGraduatesSearch">Expected Graduation</label>
                            <select wire:model="graduation_filter" id="studentGraduatesSearch" class="form-control">
                                <option value="" selected>All</option>
                                @foreach($graduation_dates as $graduation_date)
                                <option value="{{ $graduation_date }}">{{ $graduation_date }}</option>
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
                        <div class="form-group col-lg-2">
                            <div class="mb-2">&nbsp;</div>
                            <button type="button" class="btn btn-block btn-outline-primary" wire:click="resetFilters">
                                Clear All Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.partials._applied-filters', ['filter_value_names' => ['credit_filter' => ['0' => 'Volunteer', '1' => 'Credit', '-1' => 'No preference']]])

    <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
        <caption class="sr-only">List of student research applications</caption>
        <thead>
            <tr>
                @include('livewire.partials._th-sortable', ['title' => 'ID', 'field' => 'id'])
                @include('livewire.partials._th-sortable', ['title' => 'Name', 'field' => 'full_name'])
                <th>Topic Interests</th>
                <th>Faculty Interest</th>
                <th>Schools</th>
                <th>Applying For</th>
                <th>Expected Graduation</th>
                @include('livewire.partials._th-sortable', ['title' => 'Status', 'field' => 'status'])
                <th class="pl-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td><a href="{{ route('students.show', ['student' => $student]) }}">{{ $student->full_name }}</a></td>
                <td>{{ $student->tags->implode('name', ', ') }}</td>
                <td>{{ $student->faculty->implode('full_name', ', ') }}</td>
                <td>{{ implode(', ', $student->research_profile->schools ?? []) }}</td>
                <td>{{ implode(', ', $student->research_profile->semesters ?? []) }}</td>
                <td>{{ $student->research_profile->graduation_date }}</td>
                <td>{{ $student->status }}</td>
                <td class="text-nowrap pl-4 pr-3">
                    <div>
                        <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank" title="View in new tab/window">
                            <i class="far fa-fw fa-window-restore"></i> View
                        </a>
                    </div>
                    <div>
                        <a href="mailto:{{ optional($student->user)->email }}" title="Email the student">
                            <i class="far fa-fw fa-envelope"></i> Email
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('students.show', ['student' => $student]) }}#student_feedback" target="_blank" title="Add or view feedback">
                            <i class="far fa-fw fa-comment"></i> Feedback
                        </a>
                    </div>
                    <livewire:bookmark-button :model="$student" :simple="true" :wire:key="$student->id">
                </td>
            </tr>
            @endforeach
            @include('livewire.partials._loading-indicator')
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-lg-10">
            {{ $this->students->links() }}
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
