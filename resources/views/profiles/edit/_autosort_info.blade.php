<div class="alert alert-info">
    <ul class="fa-ul mb-0">
        <li>
            <span class="fa-li"><i class="fas fa-info-circle"></i></span> Entries will be auto-sorted by year
            (reverse chronological) when saved, but you may manually sort entries within a given year by dragging them
            with the <i class="fas fa-arrows-alt-v text-muted" aria-description="vertical arrows"></i> icon.
        </li>
        <li>
            <span class="fa-li"><i class="fas fa-info-circle"></i></span> To manually sort <em>all</em> entries, leave
            the <b>Year</b> field empty (and include it as a part of @if(isset($suggestion)) <b>{{ $suggestion }}</b> @else another field @endisset instead).
        </li>
        <li>
            <span class="fa-li"><i class="fas fa-info-circle"></i></span> To review your publications available for import from external sources:
            <livewire:publications-import-modal :profile="$profile">
        </li>
    </ul>
</div>
