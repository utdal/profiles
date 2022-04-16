<div class="dropdown student-filer">
    <button
        class="btn dropdown-toggle py-0 pl-0 text-primary"
        type="button"
        id="studentFilerMenuButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-controls="studentFilerMenu"
        aria-expanded="false"
    >
        <i class="fas fa-fw fa-folder-open"></i> Move to&hellip;
    </button>
    <div id="studentFilerMenu" class="dropdown-menu" role="menu" aria-labelledby="studentFilerMenuButton">
        @foreach($statuses as $status_value => $status_name)
            <button
                class="dropdown-item student-filer-status @if($status_value == $status) active bg-primary text-white @endif"
                {{-- href="#" --}}
                role="menuitem"
                wire:key="{{ $profile->slug }}_{{ $student->slug }}_status_{{ $status_value }}"
                wire:click="updateStatus('{{ $status_value }}', '{{ $status_name }}')"
                @if($status_value == $status)
                aria-current="true"
                style="pointer-events: none;"
                tabindex="-1"
                disabled
                @endif
            >
                <span class="fa-fw {{ $status_icons[$status_value] }}" style="opacity:0.3;"></span>
                {{ $status_name }}
            </button>
        @endforeach
    </div>
    @include('livewire.partials._loading-fixed', ['loading_target' => 'updateStatus'])
</div>