<form>
    <div class="checkbox-group">
        <input
            wire:model="not_accepting_students"
            type="checkbox"
            id="notAcceptingStudentsCheckbox"
        >
        <label class="form-check-label font-weight-bold text-primary clickable" for="notAcceptingStudentsCheckbox">
            <i class="fas fa-user-slash fa-fw"></i> I'm not accepting students
        </label>
    </div>
    @include('livewire.partials._loading-fixed', ['loading_target' => 'not_accepting_students'])
</form>