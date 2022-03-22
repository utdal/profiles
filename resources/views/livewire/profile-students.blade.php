<div class="accordion" id="profileStudentsAccordion">
    @foreach(App\ProfileStudent::$statuses as $status => $status_name)
        <div class="card" wire:key="accordion_{{ Str::slug($status) }}">
            <div class="card-header" id="accordion_{{ Str::slug($status) }}">
                <h2 class="my-0 fa-2x">
                    <button
                        class="btn btn-link btn-block text-left"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapse_{{ Str::slug($status) }}"
                        aria-expanded="{{ $status === '' ? 'true' : 'false' }}"
                        aria-controls="collapse_{{ Str::slug($status) }}"
                    >
                        <span class="fa-fw fa-lg mr-2 {{ App\ProfileStudent::$icons[$status] }}" style="opacity:0.3"></span>
                        {{ $status_name }}
                        <span class="badge">
                            ({{ $students->where('application.status', $status)->count() }})
                        </span>
                    </button>
                </h2>
            </div>
            <div
                id="collapse_{{ Str::slug($status) }}"
                class="collapse @if($status === '') show @endif"
                aria-labelledby="accordion_{{ Str::slug($status) }}"
                data-parent="#profileStudentsAccordion"
                wire:ignore.self
            >
                <ul class="list-group list-group-flush">
                    @forelse($students->where('application.status', $status) as $student)
                        @if ($loop->first)
                            <li class="list-group-item bg-light py-1">
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <small class="text-muted font-weight-bold">Name</small>
                                    </div>
                                    <div class="col-sm-2">
                                        <small class="text-muted font-weight-bold">Applying For</small>
                                    </div>
                                    <div class="col-sm-2">
                                        <small class="text-muted font-weight-bold">Major</small>
                                    </div>
                                    <div class="col-sm-2">
                                        <small class="text-muted font-weight-bold">Graduates</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <small class="text-muted font-weight-bold">Actions</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                        <li wire:key="ps_{{ $student->slug }}" class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-sm-3">
                                    <a href="{{ route('students.show', ['student' => $student]) }}" target="_blank">{{ $student->full_name }}</a>
                                </div>

                                <div class="col-sm-2">
                                    {{ implode(', ', $student->research_profile->semesters ?? []) }}
                                </div>
                                <div class="col-sm-2">
                                    TODO
                                </div>
                                <div class="col-sm-2">
                                    {{ $student->research_profile->graduation_date }}
                                </div>
                                <div class="col-sm-3">
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
        </div>
    @endforeach
</div>
