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
    <label for="topics" class="form-label">Select a few research topics that interest you:</label>
    <div class="tags">
        <livewire:tags-modal :model="$student"> @if($editable)<a class="btn btn-primary btn-sm badge" href="#" data-target="#{{ Illuminate\Support\Str::slug($student->getRouteKey()) }}_tags_editor" data-toggle="modal" role="button"><i class="fas fa-edit"></i> Edit</a>@endif
    </div>
</div>

<div class="mb-3">
    <strong class="mr-5">Days Available:</strong>
    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
    <div class="form-check form-check-inline">
        {!! Form::checkbox("research_profile[availability][]", $day, in_array($day, $student->research_profile->availability ?? []), ['id' => "data_availability_$day", 'class' => 'form-check-input']) !!}
        {!! Form::label("data_availability_$day", $day, ['class' => 'form-check-label']) !!}
    </div>
    @endforeach
</div>

<div class="mb-3">
    <strong class="mr-5">Are you willing to start in the middle of the semester?</strong>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[mid_semester]", '1', $student->research_profile->mid_semester === '1', ['id' => "mid_semester_yes", 'class' => 'form-check-input']) !!}
        {!! Form::label("mid_semester_yes", "Yes", ['class' => 'form-check-label']) !!}
    </div>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[mid_semester]", '0', $student->research_profile->mid_semester === '0', ['id' => "mid_semester_no", 'class' => 'form-check-input']) !!}
        {!! Form::label("mid_semester_no", "No", ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="mb-3">
    <strong class="mr-5">Are you willing to start in a future semester?</strong>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[future_semester]", '1', $student->research_profile->future_semester === '1', ['id' => "future_semester_yes", 'class' => 'form-check-input']) !!}
        {!! Form::label("future_semester_yes", "Yes", ['class' => 'form-check-label']) !!}
    </div>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[future_semester]", '0', $student->research_profile->future_semester === '0', ['id' => "future_semester_no", 'class' => 'form-check-input']) !!}
        {!! Form::label("future_semester_no", "No", ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="mb-3">
    {!! Form::label('research_profile[schools][]', 'Any particular schools you would like to do research within?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Hold down control/command when clicking to select multiple.</small>
    {!! Form::select('research_profile[schools][]', App\School::pluck('display_name', 'short_name'), $student->research_profile->schools ?? [], ['class' => 'form-control', 'multiple', 'size' => App\School::count()]); !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile_faculty[]', 'Any particular faculty you would like to work with?', ['class' => 'form-label']) !!}
    <div class="profile-picker">
        @if($editable)
        <small class="form-text text-muted">Max 5. Start typing a name and select from the list. You can also refer to <a href="{{ route('users.bookmarks.show', ['user' => auth()->user()]) }}" target="_blank">your bookmarks <i class="fas fa-external-link-alt"></i></a>.</small>
        <i class="fas fa-users" aria-hidden="true"></i> {!! Form::select('research_profile[faculty][]', array_combine($student->research_profile->faculty ?? [], $student->research_profile->faculty ?? []), $student->research_profile->faculty ?? [], ['id' => 'research_profile_faculty[]', 'multiple']) !!}
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

<div class="mb-3">
    <strong class="mr-5">Are you willing to travel?</strong>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[travel]", '1', $student->research_profile->travel === '1', ['id' => "travel_yes", 'class' => 'form-check-input']) !!}
        {!! Form::label("travel_yes", "Yes", ['class' => 'form-check-label']) !!}
    </div>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[travel]", '0', $student->research_profile->travel === '0', ['id' => "travel_no", 'class' => 'form-check-input']) !!}
        {!! Form::label("travel_no", "No", ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="mb-3">
    <strong class="mr-5">Comfortable working with animals?</strong>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[animals]", '1', $student->research_profile->animals === '1', ['id' => "animals_yes", 'class' => 'form-check-input']) !!}
        {!! Form::label("animals_yes", "Yes", ['class' => 'form-check-label']) !!}
    </div>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[animals]", '0', $student->research_profile->animals === '0', ['id' => "animals_no", 'class' => 'form-check-input']) !!}
        {!! Form::label("animals_no", "No", ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="mb-3">
    <strong class="mr-5">Need research credit?</strong>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[credit]", '1', $student->research_profile->credit === '1', ['id' => "credit_yes", 'class' => 'form-check-input']) !!}
        {!! Form::label("credit_yes", "Yes, I need credit", ['class' => 'form-check-label']) !!}
    </div>
    <div class="form-check form-check-inline">
        {!! Form::radio("research_profile[credit]", '0', $student->research_profile->credit === '0', ['id' => "credit_no", 'class' => 'form-check-input']) !!}
        {!! Form::label("credit_no", "No, I just want to volunteer", ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="form-group row mb-3">
    {!! Form::label('research_profile[graduation_date]', 'Expected graduation date:', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-3 col-sm-4">
        {!! Form::text('research_profile[graduation_date]', $student->research_profile->graduation_date ?? null, ['class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-min-view-mode' => '1', 'data-date-format' => 'MM yyyy', 'required']) !!}
    </div>
</div>

@if($editable)
<button type="submit" class="btn btn-primary edit-button">Submit</button>
<a href="{{ $student->url }}" class='btn btn-light edit-button'>Cancel</a>
@endif