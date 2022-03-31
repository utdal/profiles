<div class="row">
    <div class="col-md-3 mb-md-0 mb-3">
        <div
            class="nav flex-column nav-pills h-100 border-right bg-light"
            style="box-shadow: inset -7px 0 7px -7px rgba(160,160,160,0.2);"
            id="profileStudentTablist"
            role="tablist"
            aria-orientation="vertical"
        >
            @foreach(App\ProfileStudent::$statuses as $status => $status_name)
                <a
                    class="nav-link @if($status === '') active @endif"
                    id="tab_pill_{{ Str::slug($status) }}"
                    data-toggle="pill"
                    href="#tab_{{ Str::slug($status) }}"
                    role="tab"
                    aria-controls="tab_{{ Str::slug($status) }}"
                    aria-selected="true"
                    wire:ignore.self
                >
                    <span class="fa-fw mr-2 {{ App\ProfileStudent::$icons[$status] }}" style="opacity:0.3"></span>
                    {{ $status_name }}
                    <span class="badge">
                        ({{ $students->where('application.status', $status)->count() }})
                    </span>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content h-100" id="profileStudentTabContent">
            @foreach(App\ProfileStudent::$statuses as $status => $status_name)
                <div
                    class="tab-pane fade @if($status === '') show active @endif"
                    id="tab_{{ Str::slug($status) }}"
                    role="tabpanel"
                    aria-labelledby="tab_pill_{{ Str::slug($status) }}"
                    wire:ignore.self
                >
                    <ul class="list-group list-group-flush">
                        @forelse($students->where('application.status', $status) as $student)
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
                                        <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank" title="View in new tab/window">
                                            <i class="far fa-fw fa-lg fa-window-restore"></i><span class="sr-only">View</span>
                                        </a>
                                        <a href="{{ route('students.show', ['student' => $student]) }}#student_feedback" target="_blank" title="Add or view feedback">
                                            <i class="fas fa-fw fa-lg fa-comment"></i><span class="sr-only">Feedback</span>
                                        </a>
                                        <a href="mailto:{{ optional($student->user)->email }}" title="Email the student">
                                            <i class="fas fa-fw fa-lg fa-envelope"></i><span class="sr-only">Email</span>
                                        </a>
                                        <livewire:student-filer :profile="$profile" :student="$student" :wire:key="$student->slug . '_filer'">
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
</div>
