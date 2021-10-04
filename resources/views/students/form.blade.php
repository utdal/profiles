<div class="mb-3">
    {!! Form::label('full_name', 'Full name', ['class' => 'form-label']) !!}
    {!! Form::text('full_name', $student->full_name, ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile[intro]', 'Why are you interested in doing research?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Please be concise (500 words)</small>
    {!! Form::textarea('research_profile[intro]', $student->research_profile->intro ?? '', ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile[intro]', 'Which area of research are you most interested in, and why?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Please be concise (500 words)</small>
    {!! Form::textarea('research_profile[interest]', $student->research_profile->interest ?? '', ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    <label for="topics" class="form-label mr-3">Select a few research topics that interest you:</label>
    @if($editable)
        <a class="btn btn-success btn-sm" href="#" data-target="#{{ Illuminate\Support\Str::slug($student->getRouteKey()) }}_tags_editor" data-toggle="modal" role="button"><i class="fas fa-tags"></i> Select Tags&hellip;</a>
    @endif
    <div class="tags my-2">
        <livewire:tags-modal :model="$student">
    </div>
</div>

<div class="mb-3">
    <strong class="mr-5">Which semesters are you applying for?</strong>
    @php($semesters = $editable ? App\Helpers\Semester::currentAndNext(3) : $student->research_profile->semesters ?? [])
    @foreach($semesters as $i => $semester)
        <div class="form-check form-check-inline">
            {!! Form::checkbox("research_profile[semesters][]", $semester, in_array($semester, $student->research_profile->semesters ?? []), ['id' => "data_semester_$i", 'class' => 'form-check-input', 'data-toggle' => 'show', 'data-toggle-target' => "#semester_{$i}_subform"]) !!}
            {!! Form::label("data_semester_$i", $semester, ['class' => 'form-check-label']) !!}
        </div>
    @endforeach
    @foreach($semesters as $i => $semester)
        @php($semester_slug = Illuminate\Support\Str::slug($semester))
        <div class="subform my-3" id="semester_{{ $i }}_subform">
            <div class="mb-3">
                {!! Form::label("research_profile[availability][$semester_slug][hours]", "In $semester, how many hours are you willing to commit to research?", ['class' => 'form-label']) !!}
                {!! Form::text("research_profile[availability][$semester_slug][hours]", $student->research_profile->availability[$semester_slug]['hours'] ?? null, ['class' => 'form-control']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label("research_profile[availability][$semester_slug][hours_weekdays]", "In $semester, how many hours Monday to Friday between 9-5?", ['class' => 'form-label']) !!}
                {!! Form::text("research_profile[availability][$semester_slug][hours_weekdays]", $student->research_profile->availability[$semester_slug]['hours_weekdays'] ?? null, ['class' => 'form-control']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label("research_profile[availability][$semester_slug][hours_weekends]", "In $semester, how many hours on weeknights and/or weekends?", ['class' => 'form-label']) !!}
                {!! Form::text("research_profile[availability][$semester_slug][hours_weekends]", $student->research_profile->availability[$semester_slug]['hours_weekends'] ?? null, ['class' => 'form-control']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label("research_profile[availability][$semester_slug][hours_specific]", "For $semester, if you know your specific hours of availability, please list them here:", ['class' => 'form-label']) !!}
                {!! Form::textarea("research_profile[availability][$semester_slug][hours_specific]", $student->research_profile->availability[$semester_slug]['hours_specific'] ?? null, ['class' => 'form-control']) !!}
            </div>
        </div>
    @endforeach
</div>

<div class="mb-3">
    {!! Form::label('research_profile[schools][]', 'Any particular schools you would like to do research within?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Hold down control/command when clicking to select multiple.</small>
    {!! Form::select('research_profile[schools][]', $schools, $student->research_profile->schools ?? [], ['class' => 'form-control', 'multiple', 'size' => $schools->count()]); !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile_faculty[]', 'Any particular ' . $schools->keys()->implode(' / ') . ' faculty you would like to work with?', ['class' => 'form-label']) !!}
    <div class="profile-picker">
        @if($editable)
        <small class="form-text text-muted">Max 5. Start typing the name of a professor, and select from the list. You can also refer to <a href="{{ route('users.bookmarks.show', ['user' => auth()->user()]) }}" target="_blank">your bookmarks <i class="fas fa-external-link-alt"></i></a>.</small>
        <i class="fas fa-users" aria-hidden="true"></i> {!! Form::select('research_profile[faculty][]', array_combine($student->research_profile->faculty ?? [], $student->research_profile->faculty ?? []), $student->research_profile->faculty ?? [], ['id' => 'research_profile_faculty[]', 'multiple'] + ($schools->isNotEmpty() ? ['data-school' => $schools->keys()->implode(';')] : [])) !!}
        @else
            <i class="fas fa-users" aria-hidden="true"></i><span class="sr-only">Faculty:</span> 
            @foreach(($student->research_profile->faculty ?? []) as $faculty)
                <span class="badge badge-primary tags-badge">{{ $faculty }}</span>
            @endforeach
        @endif
    </div>
</div>

<div class="mb-3">
    {!! Form::label('research_profile[languages][]', 'Select your spoken languages:', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Hold down control/command when clicking to select multiple.</small>
    {!! Form::select('research_profile[languages][]', [
        'ar' => 'Arabic',
        'bn' => 'Bengali',
        'zh' => 'Chinese',
        'en' => 'English',
        'hi' => 'Hindi',
        'ja' => 'Japanese',
        'pt' => 'Portugese',
        'es' => 'Spanish',
        'ru' => 'Russian',
        'other' => 'Other',
        ], $student->research_profile->languages ?? [], ['class' => 'form-control', 'multiple', 'size' => 10]); !!}
</div>

<div class="row mb-4">
    <strong class="col-lg-9">Are you willing to complete your research hours at one of the research centers in Dallas (<a href="https://calliercenter.utdallas.edu/">Callier Center for Communication Disorders</a>, <a href="https://vitallongevity.utdallas.edu/">Center for Vital Longevity</a> or <a href="https://brainhealth.utdallas.edu">Center for Brain Health</a>)?</strong>
    <div class="col-lg-3">
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[travel]", '1', $student->research_profile->travel === '1', ['id' => "travel_yes", 'class' => 'form-check-input']) !!}
            {!! Form::label("travel_yes", "Yes", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[travel]", '0', $student->research_profile->travel === '0', ['id' => "travel_no", 'class' => 'form-check-input']) !!}
            {!! Form::label("travel_no", "No", ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>

<div class="row mb-4">
    <strong class="col-lg-9">Are you willing to take part in research that will require you to travel regularly to sites in the Dallas area, such as community centers, participants’ homes or area schools?</strong>
    <div class="col-lg-3">
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[travel_other]", '1', $student->research_profile->travel === '1', ['id' => "travel_other_yes", 'class' => 'form-check-input']) !!}
            {!! Form::label("travel_other_yes", "Yes", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[travel_other]", '0', $student->research_profile->travel === '0', ['id' => "travel_other_no", 'class' => 'form-check-input']) !!}
            {!! Form::label("travel_other_no", "No", ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>

<div class="row mb-4">
    <strong class="col-lg-4">Comfortable working with animals?</strong>
    <div class="col-lg-8">
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[animals]", '1', $student->research_profile->animals === '1', ['id' => "animals_yes", 'class' => 'form-check-input']) !!}
            {!! Form::label("animals_yes", "Yes", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[animals]", '0', $student->research_profile->animals === '0', ['id' => "animals_no", 'class' => 'form-check-input']) !!}
            {!! Form::label("animals_no", "No", ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>

<div class="row mb-4">
    <strong class="col-lg-4">Need research credit?</strong>
    <div class="col-lg-8">
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[credit]", '1', $student->research_profile->credit === '1', ['id' => "credit_yes", 'class' => 'form-check-input']) !!}
            {!! Form::label("credit_yes", "Yes, I need credit", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[credit]", '0', $student->research_profile->credit === '0', ['id' => "credit_no", 'class' => 'form-check-input']) !!}
            {!! Form::label("credit_no", "No, I just want to volunteer", ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    {!! Form::label('research_profile[graduation_date]', 'Expected graduation date:', ['class' => 'col-lg-4 col-form-label']) !!}
    <div class="col-lg-3 col-md-6">
        {!! Form::text('research_profile[graduation_date]', $student->research_profile->graduation_date ?? null, ['class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-min-view-mode' => '1', 'data-date-format' => 'MM yyyy', 'required']) !!}
    </div>
</div>

<div class="mb-3">
    {!! Form::label('research_profile[other_info]', 'Please feel free to add any other relevant information that you think may help us in making this decision', ['class' => 'form-label']) !!}
    {!! Form::textarea('research_profile[other_info]', $student->research_profile->other_info ?? '', ['class' => 'form-control']) !!}
</div>

@if($editable)
<button type="submit" class="btn btn-primary edit-button">Submit</button>
<a href="{{ $student->wasEverUpdated() ? $student->url : route('students.about') }}" class='btn btn-light edit-button'>Cancel</a>
@endif