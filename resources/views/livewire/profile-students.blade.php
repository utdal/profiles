<div class="row">
    <div class="col-md-3 mb-md-0 mb-3">
        <div
            class="nav flex-column nav-pills h-100 border-right bg-light"
            style="box-shadow: inset -7px 0 7px -7px rgba(160,160,160,0.2);"
            id="profileStudentTablist"
            role="tablist"
            aria-orientation="vertical"
        >
            @foreach($statuses as $status => $status_name)
                <a
                    class="nav-link @if($loop->first) active @endif"
                    id="tab_pill_{{ Str::slug($status) }}"
                    data-toggle="pill"
                    href="#tab_{{ Str::slug($status) }}"
                    role="tab"
                    aria-controls="tab_{{ Str::slug($status) }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    wire:ignore.self
                >
                    <span class="fa-fw mr-2 {{ $status_icons[$status] }}" style="opacity:0.3"></span>
                    {{ $status_name }}
                    <span class="badge">
                        ({{ $this->students->where('application.status', $status)->count() }})
                    </span>
                </a>
            @endforeach
            <div class="accordion filters mt-5" id="accordionFilters">
                <div class="card bg-transparent border-right-0 border-left-0">
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
                            <div class="form-group">
                                <label for="studentNameSearch">Name</label>
                                <input wire:model.debounce.250ms="search_filter" type="text" id="studentNameSearch" class="form-control" placeholder="Search...">
                            </div>
                            <div class="form-group">
                                <label for="studentSemesterSearch">Semester</label>
                                <select wire:model="semester_filter" id="studentSemesterSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentTagSearch">Topic Interests</label>
                                <select wire:model="tag_filter" id="studentTagSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($tags as $tag)
                                    <option value="{{ $tag->slug }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentSchoolSearch">School</label>
                                <select wire:model="schools_filter" id="studentSchoolSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school }}">{{ $school }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentMajorSearch">Major</label>
                                <select wire:model="major_filter" id="studentMajorSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($majors as $major)
                                    <option value="{{ $major }}">{{ $major }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentLanguageSearch">Language</label>
                                <select wire:model="language_filter" id="studentLanguageSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($languages as $language_code => $language)
                                    <option value="{{ $language_code }}">{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentTravelSearch">Travel to Research Centers</label>
                                <select wire:model="travel_filter" id="studentTravelSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentTravelOtherSearch">Travel to Sites</label>
                                <select wire:model="travel_other_filter" id="studentTravelOtherSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentAnimalsSearch">Work with Animals</label>
                                <select wire:model="animals_filter" id="studentAnimalsSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentCreditSearch">Research Credit</label>
                                <select wire:model="credit_filter" id="studentCreditSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="studentGraduatesSearch">Graduates</label>
                                <select wire:model="graduation_filter" id="studentGraduatesSearch" class="form-control">
                                    <option value="" selected>All</option>
                                    @foreach($graduation_dates as $graduation_date)
                                    <option value="{{ $graduation_date }}">{{ $graduation_date }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" wire:click="resetFilters">Clear All Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="applied_filters">
            @foreach($filter_names as $filter_name)
                @if($this->$filter_name !== '')
                    <span wire:key="filter_badge_{{ $filter_name }}" class="badge badge-primary mr-1 mb-3">
                        {{ Str::before($filter_name, '_filter') }}: 
                        {{ ['0' => 'No', '1' => 'Yes']["{$this->$filter_name}"] ?? $this->$filter_name }}
                        <button
                            wire:click="resetFilter('{{ $filter_name }}')"
                            type="button"
                            class="close float-none ml-2"
                            style="font-size: 1rem;"
                            aria-label="Clear Filter"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </span>
                @endif
            @endforeach
        </div>
        <div class="tab-content h-100" id="profileStudentTabContent">
            @foreach($statuses as $status => $status_name)
                <div
                    class="tab-pane fade @if($status === '') show active @endif"
                    id="tab_{{ Str::slug($status) }}"
                    role="tabpanel"
                    aria-labelledby="tab_pill_{{ Str::slug($status) }}"
                    wire:ignore.self
                >
                    <ul class="list-group list-group-flush">
                        @forelse($this->students->where('application.status', $status) as $student)
                            @if ($loop->first)
                                <li class="list-group-item bg-light py-1">
                                    <div class="row align-items-center">
                                        <div class="col-lg-3">
                                            <small class="text-muted font-weight-bold">Name</small>
                                        </div>
                                        <div class="col-lg-2">
                                            <small class="text-muted font-weight-bold">Applying For</small>
                                        </div>
                                        <div class="col-lg-2">
                                            <small class="text-muted font-weight-bold">Major</small>
                                        </div>
                                        <div class="col-lg-2">
                                            <small class="text-muted font-weight-bold">Graduates</small>
                                        </div>
                                        <div class="col-lg-3">
                                            <small class="text-muted font-weight-bold">Actions</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li wire:key="ps_{{ $student->slug }}" class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank">{{ $student->full_name }}</a>
                                    </div>

                                    <div class="col-lg-2">
                                        {{ implode(', ', $student->research_profile->semesters ?? []) }}
                                    </div>
                                    <div class="col-lg-2">
                                        {{ $student->research_profile->major }}
                                    </div>
                                    <div class="col-lg-2">
                                        {{ $student->research_profile->graduation_date }}
                                    </div>
                                    <div class="col-lg-3">
                                        <div>
                                            <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank" title="View in new tab/window">
                                                <i class="far fa-fw fa-window-restore"></i> View
                                            </a>
                                        </div>
                                        <div>
                                            <a href="mailto:{{ optional($student->user)->email }}" title="Email the student">
                                                <i class="fas fa-fw fa-envelope"></i> Email
                                            </a>
                                        </div>
                                        <div>
                                            <livewire:student-filer :profile="$profile" :student="$student" :status="$student->application->status" :wire:key="$student->slug . '_filer'">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                None.
                                @if($status === '')
                                Looking for more students? <a href="{{ route('students.index') }}">Check out the full list</a>.
                                @endif
                            </li>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    @include('livewire.partials._loading-fixed', ['loading_target' => 'resetFilter, resetFilters, animals_filter, credit_filter, graduation_filter, language_filter, search_filter, schools_filter, semester_filter, travel_filter, travel_other_filter, tag_filter'])
</div>
