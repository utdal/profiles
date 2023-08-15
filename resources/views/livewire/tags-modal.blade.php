<span>
    {{-- Tag List --}}
    <span id="{{ $model_slug }}_current_tags" class="tags">
        @foreach($tags as $tag)
            <a class="badge badge-primary tags-badge" href="{{ route('profiles.index', ['search' => $tag->name]) }}" target="_blank">
                {{ ucwords($tag->name) }}
            </a>
            <span class="{{ $tag->slug }}"></span> {{-- this is just here so Livewire respects whitespace when adding a tag --}}
        @endforeach
    </span>

    {{-- Tag Editor --}}
    <div id="{{ $model_slug }}_tags_editor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0"><i class="fas fa-tags"></i> Click on a tag to select or deselect it.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @forelse($this->possible_tags as $letter => $letter_tags)
                            <div class="row">
                                <div class="col-sm-2 col-lg-1">
                                    <h4>{{ $letter }}</h4>
                                </div>
                                <div class="col-sm py-sm-3">
                                    @foreach($letter_tags as $possible_tag)
                                        <button type="button" role="button" class="btn btn-outline-primary btn-sm rounded-pill my-1 @if($selected_tags->contains($possible_tag)) active @endif" wire:click="toggleTag({{ $possible_tag->id }})">
                                            {{ ucwords($possible_tag->name) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p>{{ $this->empty_message }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fas fa-check"></i> I'm done selecting tags</button>
                </div>

            </div>
        </div>
    </div>
</span>
