<div class="mb-3">
    {!! Form::label('full_name', 'Full name', ['class' => 'form-label']) !!}
    {!! Form::text('full_name', $student->full_name, ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile[major]', 'Major', ['class' => 'form-label']) !!}
    @if($majors->isNotEmpty())
        {!! Form::select('research_profile[major]', collect(['' => 'Select a major'])->merge($majors)->merge(['Other' => 'Other']), $student->research_profile->major ?? '', ['class' => 'form-control']); !!}
    @else
        {!! Form::text('research_profile[major]', $student->research_profile->major ?? '', ['class' => 'form-control', 'required']) !!}
    @endif
</div>

<div class="mb-3">
    <fieldset>
        <legend class="student-form-legend" id="school-selection">Which school(s) would you like to do research within?</legend>
        <small class="form-text text-muted mb-2">Selecting a school here allows this form to include any school-specific questions that professors might have for you.</small>
        @foreach($schools as $school_shortname => $school_displayname)
            <div class="form-check ml-3">
                {!! Form::checkbox(
                    "research_profile[schools][]",
                    "$school_shortname",
                    in_array($school_shortname, $student->research_profile->schools ?? []),
                    [
                        'id' => "data_school_$school_shortname",
                        'aria-describedby' => "school-selection",
                        'class' => 'form-check-input ml-n3',
                        'data-toggle' => 'show',
                        'data-toggle-target' => "#school_custom_questions_{$school_shortname}"
                    ]
                ) !!}
                {!! Form::label("data_school_$school_shortname", "$school_displayname ($school_shortname)", ['class' => 'form-check-label ml-1']) !!}
            </div>
        @endforeach
    </fieldset>
</div>

<div class="mb-3">
    {!! Form::label('research_profile[brief_intro]', 'In 20 words or less, why are you looking for a research opportunity?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Please be concise (20 words maximum)</small>
    {!! Form::textarea('research_profile[brief_intro]', $student->research_profile->brief_intro ?? '', ['class' => 'form-control', 'required', 'maxlength' => '280']) !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile[intro]', 'Please elaborate on your previous answer here. Why are you interested in doing research? How might this experience support your future goals?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Please be concise (4-6 sentences; 250 words maximum)</small>
    {!! Form::textarea('research_profile[intro]', $student->research_profile->intro ?? '', ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    {!! Form::label('research_profile[interest]', 'Which area of research interests you most and why?', ['class' => 'form-label']) !!}
    <small class="form-text text-muted">Please be concise (3-5 sentences; 200 words maximum)</small>
    {!! Form::textarea('research_profile[interest]', $student->research_profile->interest ?? '', ['class' => 'form-control', 'required']) !!}
</div>

<div class="mb-3">
    <strong >Select the research topics that most interest you:</strong>
    <small class="form-text text-muted mb-1">Give this some thought and be intentional. You may select 1-5 topics. The possible topics may change depending on your school selection above. </small>
    @if($editable)
        <a class="btn btn-success btn-sm" href="#" data-target="#{{ Illuminate\Support\Str::slug($student->getRouteKey()) }}_tags_editor" data-toggle="modal" role="button"><i class="fas fa-tags"></i> Select Tags&hellip;</a>
    @endif
    <div class="tags my-2">
        <livewire:tags-modal :model="$student" :tags_type="$student->tagTypes()" :empty_message="'You must select at least one school before selecting research topic interests.'">
    </div>
</div>

<div class="mb-3">
    {!! Form::label('research_profile_faculty[]', 'With which ' . $schools->keys()->implode(' / ') . ' faculty members would you most like to work?', ['class' => 'form-label', 'id' => 'profiles-picker-label']) !!}
    <div class="profile-picker">
        @if($editable)
            <small class="form-text text-muted">Required. Start typing the name of a professor, and select from the list. If you are not sure, please take some time to read the faculty profiles and learn about their research areas and current projects. You may click on the tags/research topics you selected above to view a list of faculty whose research interests are aligned with that topic or browse <a href="{{ route('tags.index') }}" target="_blank">all topics <i class="fas fa-external-link-alt"></i></a> or <a href="{{ route('profiles.index') }}" target="_blank">profiles <i class="fas fa-external-link-alt"></i></a>. You can also refer to <a href="{{ route('users.bookmarks.show', ['user' => auth()->user()]) }}" target="_blank">your bookmarks <i class="fas fa-external-link-alt"></i></a>.</small>
            <small class="form-text text-muted">There are typically more applicants for each lab than there are positions available and not every lab has open positions each semester, so please select at least 4 faculty with whom you would like to work. You may select a maximum of 8.</small>
            <i class="fas fa-users" aria-hidden="true"></i> 
            {!! Form::select('faculty[]', $student->faculty->pluck('full_name', 'id')->all(), $student->faculty->pluck('id')->all() ?? [], [
                'id' => 'research_profile_faculty[]', 
                'aria-labelledby' => 'profiles-picker-label',
                'multiple', 
                'required',
                ] + ($schools->isNotEmpty() ? ['data-school' => $schools->keys()->implode(';')] : [])) 
            !!}
        @else
            <i class="fas fa-users" aria-hidden="true"></i><span class="sr-only">Faculty:</span> 
            @foreach($student->faculty as $faculty)
                <span class="badge badge-primary tags-badge">{{ $faculty->full_name }}</span>
            @endforeach
        @endif
    </div>
</div>

<div class="mb-3">
    <strong class="mr-3">Which semesters are you applying for?</strong>
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
                {!! Form::label("research_profile[availability][$semester_slug][hours]", "In $semester, how many hours per week do you anticipate you will be able to dedicate to research?", ['class' => 'form-label']) !!}
                <small class="form-text text-muted">If you are planning to register for credit, the standard time commitment is 3 hours per week <strong>per</strong> credit hour.</small>
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

<div id="lang-proficiency-levels" style="display:none">
    <p><small><strong>Limited Working Proficiency:</strong> Someone at this level still needs help with more extensive conversations in the language. They can only operate independently in basic conversations.</small></p>
    <p><small><strong>Professional Working Proficiency:</strong> Someone at this level can speak at a normal speed in the language and has a fairly extensive vocabulary. They likely require help understanding subtle and nuanced phrasing.</small></p>
    <p><small><strong>Full Professional Proficiency:</strong> Someone at this level can have personal and technical conversations. People at this level may occasionally misspeak or make minor mistakes. Their vocabulary is extensive and they can carry on conversations with ease.</small></p>
    <p><small><strong>Native / Bilingual Proficiency:</strong> Someone at this level was either raised speaking the language as their native tongue or has been speaking it so long that they are completely fluent.</small></p>
</div>

<div class="mb-3">
    <div class="mb-3">
        <fieldset>
            <legend class="student-form-legend" id="language-selection">Select your spoken languages:</legend>
            @foreach($languages as $key => $value)
                <div class="form-check form-check-inline">
                    {!! Form::checkbox("research_profile[languages][]", $key, in_array($key, $student->research_profile->languages ?? []), ['id' => "data_language_$key", 'class' => 'form-check-input', 'aria=describedby' => 'language-selection', 'data-toggle' => 'show', 'data-toggle-target' => "#language_{$key}_subform"]) !!}
                    {!! Form::label("data_language_$key", $value, ['class' => 'form-check-label']) !!}
                </div>
            @endforeach
        </fieldset>
    </div>
    <div class="mb-4">
        <strong class="mr-5">Please indicate your proficiency level for each selected language</strong>
        <small class="form-text text-muted">Proficiency levels <a role="button" tabindex="0" aria-label="proficiency information" data-toggle="popover" data-trigger="focus" data-popover-content="#lang-proficiency-levels"><i class="fas fa-question-circle"></i></a></small>
        @foreach($languages as $key => $value)
            <div class="subform my-3" id="language_{{ $key }}_subform">
                <div class="row">
                    @if($key === 'other')
                        <div class="col-lg-2">
                            {!! Form::text("research_profile[language_other_name]", $student->research_profile->language_other_name, ['class' => 'form-control mb-0', 'placeholder' => 'Please specify...', 'aria-label' => 'Other language']) !!}
                        </div>
                    @else
                        <strong class="col-lg-2">{{ $value }}</strong>
                    @endif
                    <div class="col-lg-10">
                        <div class="form-check form-check-inline">
                            {!! Form::radio("research_profile[lang_proficiency][$key]", 'limited', (isset($student->research_profile->lang_proficiency[$key])) and ($student->research_profile->lang_proficiency[$key] == "limited") ? true : false, ['class' => 'form-check-input', 'id'=>$key.'_proficiency_limited']) !!}
                            {!! Form::label($key.'_proficiency_limited', "Limited Working", ['class' => 'form-check-label']) !!}
                        </div>
                        <div class="form-check form-check-inline">
                            {!! Form::radio("research_profile[lang_proficiency][$key]", 'basic', (isset($student->research_profile->lang_proficiency[$key])) and ($student->research_profile->lang_proficiency[$key] == "basic") ? true : false, ['class' => 'form-check-input', 'id'=>$key.'_proficiency_basic']) !!}
                            {!! Form::label($key.'_proficiency_basic', "Professional Working", ['class' => 'form-check-label']) !!}
                        </div>
                        <div class="form-check form-check-inline">
                            {!! Form::radio("research_profile[lang_proficiency][$key]", 'professional', (isset($student->research_profile->lang_proficiency[$key])) and ($student->research_profile->lang_proficiency[$key] == "professional") ? true : false, ['class' => 'form-check-input', 'id'=>$key.'_proficiency_professional']) !!}
                            {!! Form::label($key.'_proficiency_professional', "Full Professional", ['class' => 'form-check-label']) !!}
                        </div>
                        <div class="form-check form-check-inline">
                            {!! Form::radio("research_profile[lang_proficiency][$key]", 'native', (isset($student->research_profile->lang_proficiency[$key])) and ($student->research_profile->lang_proficiency[$key] == "native") ? true : false, ['class' => 'form-check-input', 'id'=>$key.'_proficiency_native']) !!}
                            {!! Form::label($key.'_proficiency_native', "Native / Bilingual", ['class' => 'form-check-label']) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach($custom_questions as $school_shortname => $school_custom_questions)
    <fieldset
        @class([
            'custom-questions-group mb-4',
            'subform' => $school_shortname !== 'All',
        ])
        id="school_custom_questions_{{ $school_shortname }}"
    >
        @if($school_shortname !== 'All')
            <legend>{{ $school_shortname }} research questions</legend>
        @endif
        @foreach($school_custom_questions as $question)
            <div class="row mb-4">
                @switch($question['type'])
                    @case('text')              
                        <div class="col">
                            {!! Form::label("research_profile[{$question['name']}]", $question['label'], ['class' => 'form-label']) !!}
                            {!! Form::text("research_profile[{$question['name']}]", $student->research_profile?->{$question['name']}, ['class' => 'form-control']) !!}
                        </div>
                        @break
                    @case('textarea')
                        <div class="col">
                            {!! Form::label("research_profile[{$question['name']}]", $question['label'], ['class' => 'form-label']) !!}
                            {!! Form::textarea("research_profile[{$question['name']}]", $student->research_profile?->{$question['name']}, ['class' => 'form-control']) !!}
                        </div>
                        @break
                    @case('yes_no')
                        <strong class="col-lg-9">{!! $question['label'] !!}</strong>
                        <div class="col-lg-3">
                            <div class="form-check form-check-inline">
                                {!! Form::radio("research_profile[{$question['name']}]", '1', $student->research_profile->{$question['name']} === '1', ['id' => "{$question['name']}_yes", 'class' => 'form-check-input']) !!}
                                {!! Form::label("{$question['name']}_yes", "Yes", ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio("research_profile[{$question['name']}]", '0', $student->research_profile->{$question['name']} === '0', ['id' => "{$question['name']}_no", 'class' => 'form-check-input']) !!}
                                {!! Form::label("{$question['name']}_no", "No", ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                        @break
                    @default

                @endswitch
            </div>
        @endforeach
    </fieldset>
@endforeach

<div class="row mb-4">
    <strong class="col-lg-4">Do you want to volunteer or enroll to earn research credit?</strong>
    <div class="col-lg-8">
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[credit]", '0', $student->research_profile->credit === '0', ['id' => "credit_no", 'class' => 'form-check-input']) !!}
            {!! Form::label("credit_no", "I would like to volunteer", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[credit]", '1', $student->research_profile->credit === '1', ['id' => "credit_yes", 'class' => 'form-check-input']) !!}
            {!! Form::label("credit_yes", "I would like to earn credit", ['class' => 'form-check-label']) !!}
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio("research_profile[credit]", '-1', $student->research_profile->credit === '-1', ['id' => "credit_na", 'class' => 'form-check-input']) !!}
            {!! Form::label("credit_na", "I have no preference", ['class' => 'form-check-label']) !!}
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