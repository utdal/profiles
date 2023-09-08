<form>
    <div class="form-check form-check-inline align-items-baseline m-0">
        <input
            wire:model="not_accepting_students"
            type="checkbox"
            id="notAcceptingStudentsCheckbox"
            class="form-check-input"
        >
        <label class="form-check-label font-weight-bold text-primary clickable" for="notAcceptingStudentsCheckbox">
            <i class="fas fa-user-slash fa-fw"></i> I'm not accepting undergraduate students
        </label>
    </div>
    @include('livewire.partials._loading-fixed', ['loading_target' => 'not_accepting_students'])
</form>