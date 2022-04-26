<span class="custom-control custom-switch d-inline-block">
    <input
        wire:model="not_accepting_students"
        type="checkbox"
        class="custom-control-input"
        id="notAcceptingStudentsSwitch"
    >
    <label class="custom-control-label font-weight-bold text-primary clickable" for="notAcceptingStudentsSwitch">
        <i class="fas fa-user-slash fa-fw"></i> I'm not accepting students
    </label>
    @include('livewire.partials._loading-fixed', ['loading_target' => 'not_accepting_students'])
</span>
