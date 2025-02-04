<div id="profile-image-editor" @if($fancy_header) style="display:none" @endif>
    <h5>2- Select Profile Image</h5>
    <form wire:submit.prevent="submit" enctype="multipart/form-data" method="GET">
        @csrf

        <livewire:image-picker 
            :existing_image_url="$image_url" 
            :trigger="$trigger" 
            :custom_key="$key" 
            :custom_msg="$msg"
        >

        <div class="control-group border-top-1 mt-4 p-4">
            <div class="row justify-content-center">
                <button type="button" class="btn btn-secondary col-3 mr-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary ml-3 col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin">
                    <i class="fas fa-upload"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>