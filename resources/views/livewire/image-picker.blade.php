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
        <input type="file" wire:model="image" name="image-{{$custom_key}}" id="image-{{$custom_key}}" accept = "image/*" style="display: none;">
        <small class="text-muted">{{ $custom_msg }}</small>
    </div>
    
</div>