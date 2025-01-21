<div wire:ignore.self>
    <h5>2- Select Cover Image</h5>
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
            <div @if(!$banner_image_exists) style="display:none" @endif>
                <img id="banner-img" src="{{ $profile->banner_url }}" wire:ignore/>
            </div>
            <div @if($banner_image_exists) class="col-4" @endif>
                {!! Form::file('banner_image', ['id' => 'banner', 'name' => 'banner_image', 'accept' => 'image/*', 'wire:model' => 'banner_image', 'class' => 'd-none form-control']) !!}
                <label for="banner" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                <small class="text-muted">This will use a full-width header style - please use a high-quality image (1280 × 720 pixels or larger).</small>
            </div>
        </div>

        <fieldset class="form-group row py-2" @if(!$banner_image_exists) style="display:none" @endif>
            <div>
                <div id="fancy_header_options">
                    <div class="form-group">
                        <input id="fancy_header_right" type="checkbox" wire:model="fancy_header_right">
                        <label class="form-check-label" for="fancy_header_right">Align Header Right</label>
                    </div>

                </div>
            </div>
        </fieldset>

        <div class="control-group  border-top-1 mt-4 p-4">
            <div class="row justify-content-center">
                <button type="button" class="btn btn-secondary mr-3 col-3" data-dismiss="modal" aria-label="Close">Cancel</button>

                <button type="submit" class="btn btn-primary ml-3 col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#cover">
                    <i class="fas fa-upload"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>