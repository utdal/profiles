<span>
    @if($user)
        @if($user->hasBookmarked($model))
            @if($simple || $mini)
                <a wire:click.prevent="unbookmark()" href="#" role="button" aria-pressed="true" title="toggle bookmark">
                    <i class="fas fa-fw mr-1 fa-bookmark"></i><span @if($mini)class="sr-only"@endif>bookmarked!</span>
                </a>
            @else
                <button wire:click="unbookmark()" class="btn btn-primary btn-sm active" aria-pressed="true">
                    <i class="fas fa-fw mr-1 fa-bookmark"></i>bookmarked!
                </button>
            @endif
        @else
            @if($simple || $mini)
                <a wire:click.prevent="bookmark()" href="#" role="button" aria-pressed="false" title="toggle bookmark">
                    <i class="far fa-fw mr-1 fa-bookmark"></i><span @if($mini)class="sr-only"@endif>bookmark</span>
                </a>
            @else
                <button wire:click="bookmark()" class="btn btn-primary btn-sm" aria-pressed="false">
                    <i class="far fa-fw mr-1 fa-bookmark"></i>bookmark
                </button>
            @endif
        @endif
    @endif
</span>
