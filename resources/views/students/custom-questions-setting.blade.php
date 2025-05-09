<div
    @class([
        'form-row p-2',
        'student-questions-template' => $template ?? false,
    ])
    data-row-id="{{ $index }}"
    @if($template ?? false)
        style="display:none"
    @endif
>
    <div class="form-group col-2">
        <label for="setting[student_questions][{{ $index }}][name]">Name</label>
        <input
            type="text"
            class="form-control"
            name="setting[student_questions][{{ $index }}][name]"
            id="setting[student_questions][{{ $index }}][name]"
            value="{{ $question['name'] ?? '' }}"
            required
            pattern="[a-z][\w]*" {{-- snake-case --}}
        >
    </div>
    <div class="form-group col-6">
        <label for="setting[student_questions][{{ $index }}][label]">Prompt</label>
        <input
            type="text"
            class="form-control"
            name="setting[student_questions][{{ $index }}][label]"
            id="setting[student_questions][{{ $index }}][label]"
            value="{{ $question['label'] ?? '' }}"
            required
        >
    </div>
    <div class="form-group col">
        <label for="setting[student_questions][{{ $index }}][type]">Response Type</label>
        {!! Form::select(
            "setting[student_questions][$index][type]",
            ['text' => 'Text', 'textarea' => 'Paragraph', 'yes_no' => 'Yes/No'],
            $question['type'] ?? null,
            ['class' => 'form-control', 'required' => 'required']);
        !!}
    </div>
    <div class="form-group col-2">
        <label for="setting[student_questions][{{ $index }}][school]">Show for School</label>
        {!! Form::select(
            "setting[student_questions][$index][school]",
            App\School::pluck('short_name', 'short_name')->prepend('all', 'All'),
            $question['school'] ?? null,
            ['class' => 'form-control', 'required' => 'required']);
        !!}
    </div>
    <div class="actions d-flex position-relative">
        <a class="handle" title="Drag to reorder"><i class="fas fa-arrows-alt-v"></i></a>
        <a class="trash" title="Remove question" data-remove="true" data-on-remove="reindex,reset-next-row-id"><i class="fas fa-times"></i></a>
    </div>
</div>