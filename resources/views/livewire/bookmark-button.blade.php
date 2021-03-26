<span>
    @if($user)
        @if($user->hasBookmarked($model))
            <button wire:click="unbookmark()" class="btn btn-primary btn-sm active" aria-pressed="true">
                <i class="fas fa-bookmark"></i> bookmarked!
            </button>
        @else
            <button wire:click="bookmark()" class="btn btn-primary btn-sm" aria-pressed="false">
                <i class="far fa-bookmark"></i> bookmark
            </button>
        @endif
    @endif
</span>
