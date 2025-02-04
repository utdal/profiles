<div id="banner-image-editor" @if(!$fancy_header) style="display:none" @endif>
    <h5>2- Select Cover Image</h5>
    <form wire:submit.prevent="submit" enctype="multipart/form-data" method="GET">
        
        <livewire:image-picker :existing_image_url="$banner_url" :trigger="$trigger" :custom_key="$key" :custom_msg="$msg">
        
        <fieldset class="form-group row py-2" @if(!$banner_image_exists) style="display:none" @endif>
            <div id="fancy_header_options">
                <div class="form-group">
                    <input id="fancy_header_right" type="checkbox" wire:model="fancy_header_right">
                    <label class="form-check-label" for="fancy_header_right">Align Header Right</label>
                </div>

            </div>
        </fieldset>

        <div class="control-group  border-top-1 mt-4 p-4">
            <div class="row justify-content-center">
                <button type="button" class="btn btn-secondary mr-3 col-3" data-dismiss="modal" aria-label="Close">Cancel</button>

                <button type="submit" class="btn btn-primary ml-3 col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin">
                    <i class="fas fa-upload"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>