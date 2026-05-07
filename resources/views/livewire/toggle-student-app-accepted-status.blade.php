<div class="col col-12 mb-4">
    <span>Display previous accepted history</span>
    <label class="switch small-switch pull-left">

        <input type="hidden" name="student[accepted_by][visible]" value="{{ $visible ? '1' : '0' }}">

        <input type="checkbox"
               id="accepted-by-visible"
               name="student[accepted_by][visible]"
               data-toggle="show"
               data-toggle-target="#accepted_status_history"
                @if($visible)
                    checked
                @endif
               wire:model="visible">

        <span class="slider round"></span>
    </label>
</div>