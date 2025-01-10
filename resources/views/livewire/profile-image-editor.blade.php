<div wire:ignore.self>
    <h5>2- Select Profile Image</h5>
    <form wire:submit.prevent="submit" enctype="multipart/form-data" method="GET">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p><strong>There are some errors. Please correct them and try again.</strong></p>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        <div class="d-flex align-items-center justify-content-center">
            <div class="flex-shrink-1">
                <img name="image" id="file-img" src="{{ $profile->imageUrl }}" wire:ignore/>
            </div>
            <div class="m-3 col-5">
                {!! Form::file('image', ['id' => 'file', 'name' => 'image', 'accept' => 'image/*', 'wire:model' => 'image', 'class' => 'd-none form-control']) !!}
                <label for="file" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                <small class="text-muted">This photo will appear on your profile page and as your application profile image - please use a high-quality image (300x300 pixels or larger).</small>
            </div>
        </div>

        <div class="control-group border-top-1 mt-4 p-4">
            <div class="row justify-content-center">
                <button type="button" class="btn btn-secondary col-3 mr-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary ml-3 col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#file">
                    <i class="fas fa-upload"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>