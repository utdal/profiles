<div>
    {{-- Feedback form --}}
    @can('create', App\StudentFeedback::class)
        <div class="add-feedback mb-5">
            <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#student_{{ $student->id }}_feedback_form" aria-expanded="false" aria-controls="student_{{ $student->id }}_feedback_form">
                <i class="fas fa-comment-medical"></i> Add feedback <i class="fas fa-caret-down"></i>
            </button>
            <div id="student_{{ $student->id }}_feedback_form" class="collapse" wire:ignore.self>
                <div class="card">
                    <form class="card-body">
                        <div class="alert alert-primary">
                            <p class="mb-0">Please provide any feedback you might have for the student to help them improve their likelihood of finding a research group. Be specific, constructive, and kind. Your feedback will be shown anonymously on this page for the student to see.</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Select any that apply:</label>
                            @foreach ($reasons as $reason_key => $reason_label)
                                <div class="form-check">
                                    <input wire:model.defer="new_feedback.reasons.{{ $reason_key }}" type="checkbox" name="student_{{ $student->id }}_feedback[reasons][{{ $reason_key }}]" id="student_{{ $student->id }}_feedback[reasons][{{ $reason_key }}]">
                                    <label for="student_{{ $student->id }}_feedback[reasons][{{ $reason_key }}]" class="form-check-label">
                                        {{ $reason_label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="student_{{ $student->id }}_feedback[comment]" class="form-label">Other / Comments:</label>
                            <textarea wire:model.defer="new_feedback.comment" name="student_{{ $student->id }}_feedback[comment]" id="student_{{ $student->id }}_feedback[comment]" rows="10" class="form-control"></textarea>
                        </div>
                        <button wire:click="add()" type="button" class="btn btn-primary edit-button" data-toggle="collapse" data-target="#student_{{ $student->id }}_feedback_form" aria-expanded="false" aria-controls="student_{{ $student->id }}_feedback_form">
                            <i class="fas fa-comment-medical"></i> Submit my feedback
                        </button>
                        <button type="reset" class="btn btn-light edit-button" data-toggle="collapse" data-target="#student_{{ $student->id }}_feedback_form" aria-expanded="false" aria-controls="student_{{ $student->id }}_feedback_form">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Feedback list --}}
    @forelse($this->feedback as $feedback_item)
        <div class="card mb-3">
            <div class="card-header text-right">
                <em>{{ $feedback_item->created_at->toFormattedDateString() }}</em>
                @can('delete', $feedback_item)
                    <a onclick="confirm('Are you sure you want to remove this feedback?') || event.stopImmediatePropagation()" wire:click="destroy({{ $feedback_item->id }})" role="button" title="remove">
                        <i class="far fa-trash-alt"></i><span class="sr-only">Remove</span>
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <ul>
                    @foreach(($feedback_item->reasons ?? []) as $reason_key => $val)
                        <li>{{ $reasons[$reason_key] ?? '' }}</li>
                    @endforeach
                </ul>
                <p>{{ $feedback_item->comment }}</p>
            </div>
        </div>
    @empty
        <p>No feedback yet.</p>
    @endforelse
</div>
