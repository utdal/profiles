<div class="delegations">

    {{-- Delegations List --}}
    @foreach ($delegations as $delegation)
        <div class="card mb-3" wire:key="delegation_{{ $delegation->id }}">
            <div class="card-header">
                <h5 class="d-inline">{{ $delegation->delegate->display_name }}</h5> 
                ({{ collect([$delegation->delegate->title, $delegation->delegate->name, $delegation->delegate->email])->filter()->implode(', ') }})
                <button wire:click="destroy({{ $delegation->id }})" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <strong>starting</strong> {{ optional($delegation->starting)->toFormattedDateString() ?? '' }},
                @if ($delegation->until)  
                <strong>until</strong> {{ optional($delegation->until)->toFormattedDateString() }},
                @endif
                <strong>{{ $delegation->gets_reminders ? 'with' : 'without' }} notifications</strong>
            </div>
            <div class="card-footer text-right">
                <small class="text-muted">delegation added on {{ optional($delegation->created_at)->toDayDateTimeString() }}</small>
            </div>
        </div>
    @endforeach

    {{-- Delegations form --}}
    <div class="add-delegation mt-5">
        @include('errors.list')
        <button
            type="button"
            class="btn btn-primary"
            data-toggle="collapse"
            data-target="#user_{{ $user->id }}_delegation_form"
            aria-expanded="false"
            aria-controls="user_{{ $user->id }}_delegation_form"
        >
            <i class="fas fa-user-plus"></i> Add a delegate <i class="fas fa-caret-down"></i>
        </button>
        <div id="user_{{ $user->id }}_delegation_form" class="collapse" wire:ignore.self>
            <div class="card">
                <form wire:submit.prevent="add()" class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="user_{{ $user->id }}_directorySearch" class="form-label">Name:</label>
                            <livewire:directory-search
                                :input_id='"user_{$user->id}_directorySearch"'
                                :input_name="'username'"
                                :aria_describedby='"user_{$user->id}_directorySearch_help"'
                                :required="true"
                            >
                            <small id="user_{{ $user->id }}_directorySearch_help" class="form-text text-muted">
                                Required. Start typing a name to search for a person. Then, select them from the list.
                            </small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="user_{{ $user->id }}_delegation[name]" class="form-label">Username:</label>
                            <input
                                type="text"
                                wire:model="new_delegation.name"
                                name="user_{{ $user->id }}_delegation[name]"
                                id="user_{{ $user->id }}_delegation[name]"
                                class="form-control mb-0"
                                aria-describedby="user_{{ $user->id }}_name_help"
                                required
                                readonly
                            >
                            <small id="user_{{ $user->id }}_name_help" class="form-text text-muted">
                                Required. Auto-filled when selecting a person from the list.
                            </small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="user_{{ $user->id }}_delegation[starting]" class="form-label">Starting:</label>
                            <input
                                type="text"
                                wire:model.defer="new_delegation.starting"
                                name="user_{{ $user->id }}_delegation[starting]"
                                id="user_{{ $user->id }}_delegation[starting]"
                                class="form-control mb-0"
                                aria-describedby="user_{{ $user->id }}_starting_help"
                                data-provide="datepicker"
                                data-date-format="MM d, yyyy"
                                data-date-autoclose="true"
                                required
                            >
                            <small id="user_{{ $user->id }}_starting_help" class="form-text text-muted">
                                Required. When should the delegation period begin? Default: today.
                            </small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="user_{{ $user->id }}_delegation[until]" class="form-label">Until:</label>
                            <input
                                type="text"
                                wire:model.defer="new_delegation.until"
                                name="user_{{ $user->id }}_delegation[until]"
                                id="user_{{ $user->id }}_delegation[until]"
                                class="form-control mb-0"
                                aria-describedby="user_{{ $user->id }}_until_help"
                                data-provide="datepicker"
                                data-date-format="MM d, yyyy"
                                data-date-autoclose="true"
                            >
                            <small id="user_{{ $user->id }}_until_help" class="form-text text-muted">
                                Optional. When should the delegation period end? Default: never ends.
                            </small>
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-check">
                            <label for="user_{{ $user->id }}_delegation[gets_reminders]" class="form-label clickable">Delegate receives user's notifications:</label>
                            <input
                                type="checkbox"
                                wire:model.defer="new_delegation.gets_reminders"
                                name="user_{{ $user->id }}_delegation[gets_reminders]"
                                id="user_{{ $user->id }}_delegation[gets_reminders]"
                                class="form-check-input clickable"
                            >
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary edit-button" data-toggle="collapse" data-target="#user_{{ $user->id }}_delegation_form" aria-expanded="false" aria-controls="user_{{ $user->id }}_delegation_form">
                        <i class="fas fa-user-plus"></i> Add
                    </button>
                    <button wire:click="resetNewDelegation()" type="button" class="btn btn-light edit-button" data-toggle="collapse" data-target="#user_{{ $user->id }}_delegation_form" aria-expanded="false" aria-controls="user_{{ $user->id }}_delegation_form">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('livewire.partials._loading-fixed')

    @push('scripts')
    <script>
        // make our datepicker play nice with Livewire
        $('.add-delegation [data-provide="datepicker"]').on('changeDate', (e) => {
            e.target.dispatchEvent(new Event('input'));
        });
        // manually handle HTML5 validation, so we can prevent collapse-on-submit
        // and validate the readonly name field
        const addDelegationForm = document.querySelector('.add-delegation form');
        const nameInput = document.getElementById('user_{{ $user->id }}_delegation[name]');
        addDelegationForm.noValidate = true;
        addDelegationForm.querySelector('button[type="submit"]').addEventListener('click', (event) => {
            nameInput.readOnly = false;
            nameInput.setCustomValidity('You must select a person from the Name search drop-down to fill this field.');
            if (!addDelegationForm.reportValidity()) {
                event.stopPropagation();
                event.preventDefault();
            }
            nameInput.readOnly = true;
        });
    </script>
    @endpush
</div>