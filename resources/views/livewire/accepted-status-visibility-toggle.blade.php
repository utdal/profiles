<div class="row mb-2">
    <div class="col-sm-8">
        <span class="{{!$visible ? 'small font-weight-lighter text-muted' : ''}}">{{ $profile_name }}
    </div>

    @can('update', $student)
        <div class="col-sm-4">
            <button 
                wire:click="toggleVisibility" 
                wire:loading.attr="disabled"
                class="btn btn-sm btn-link p-0 text-uppercase text-muted border-0 shadow-none hover-darken"
                style="font-size: 0.75rem; text-decoration: underline; text-underline-offset: 4px;">
                
                <small title="{{ $visible ? 'Hide this research work experience once it has ended' : 'Make this research work visible' }}"
                        data-toggle="tooltip" 
                        wire:loading.remove>{{ $visible ? 'Hide' : 'Display' }}
                </small>
                <small wire:loading>
                    Updating...
                </small>
            </button>
        </div>
    @endcan
</div>