<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="d-flex align-items-center justify-content-center">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p><strong>There are some errors. Please correct them and try again.</strong></p>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @else
                <div class="flex-shrink-1">
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="">
                    @else
                        <img src="{{ $existing_image_url }}" alt="">
                    @endif
                </div>
            @endif

            <div class="m-3 col-5">
                <label for="image-{{$custom_key}}" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                <input type="file" wire:model="image" id="image-{{$custom_key}}" accept = "image/*" style="display: none;">
                <small class="text-muted">{{ $custom_msg }}</small>
            </div>
        </div>
        
        @if(isset($partial_view))
            @include($partial_view)
        @endif

        <div class="flex-row control-group border-top-1 mt-4 p-4">
            <div class="row justify-content-center">
                <button type="button" class="btn btn-secondary mr-3 col-3" data-dismiss="modal" aria-label="Close">Cancel</button>

                <button type="submit" class="btn btn-primary ml-3 col-3" wire:target="save" wire:loading.attr="disabled">
                    <span wire:target="save" wire:loading.remove><i class="fas fa-upload"></i></span>
                    <span wire:target="save" wire:loading><i class="fas fa-sync fa-spin"></i></span> Save
                </button>
            </div>
        </div>
    </form>
</div>