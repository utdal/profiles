<form class="form-inline d-inline-flex">
    <label for="{{ $profile->slug }}_{{ $student->slug }}_status" class="sr-only">
        Change student application status
    </label>
    <select
        wire:model="status"
        wire:key="{{ $profile->slug }}_{{ $student->slug }}_status"
        name="{{ $profile->slug }}_{{ $student->slug }}_status"
        id="{{ $profile->slug }}_{{ $student->slug }}_status"
        class="custom-select custom-select-sm rounded-pill"
    >
        @foreach (App\ProfileStudent::$statuses as $status_value => $status_name)
            <option
                value="{{ $status_value }}"
                wire:key="{{ $profile->slug }}_{{ $student->slug }}_status_{{ $status_value }}"
            >
                {{ $status_name }}
            </option>
        @endforeach
    </select>

    @include('livewire.partials._loading-fixed', ['loading_target' => 'status'])
</form>
