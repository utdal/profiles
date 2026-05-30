<div>
    <small class="form-text text-muted mb-4">Use this option to toggle the visibility of research work in the statistics section of your application
        <a role="button" tabindex="0" aria-label="visibility information" data-toggle="popover" data-trigger="hover focus" data-popover-content="#accepted-visibility-info">
            <i class="fas fa-question-circle"></i>
        </a>
    </small>

    <div id="accepted-visibility-info" style="display: none;">
        Display relevant researchers' names you've worked with or hide any once the collaboration has ended to show your availability.
    </div>

    <form wire:submit.prevent="saveDisplayPreferences">
        @foreach($accepted_stats as $profile_key => $accepted_record)
            <div class="row mb-2">
                <div class="col-sm-9">
                    <span class="{{ !$visibility_map[$profile_key] ? 'font-weight-lighter text-muted' : '' }}">
                        {{ $accepted_record['profile_name'] }}
                    </span>
                </div>
                <div class="col-sm-3">
                    <label class="switch small-switch pull-left">
                        <input
                            type="checkbox"
                            wire:model="visibility_map.{{ $profile_key }}"
                            id="accepted-by-visible-{{ $profile_key }}"
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        @endforeach
        <div class="row justify-content-end pr-4 pt-2">
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>