<div id="profile_picture_editor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"><i class="fas fa-camera"></i> Update Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container-md">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data" method="GET">
                        @csrf
                        <img id="file-img" src="{{ $profile->imageUrl }}" wire:ignore/>
                        <br>
                        <br>
                        <div class="control-group">
                            <div class="controls">
                                {!! Form::file('image', ['id' => 'file', 'name' => 'image', 'required' => 'true', 'accept' => 'image/*', 'wire:model' => 'image', 'class' => 'd-none form-control']) !!}
                                @error('image') <span class="error">{{ $message }}</span> @enderror
                                <label for="file" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                                {!! Form::inlineErrors('image') !!}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#file">
                            <i class="fas fa-upload"></i> Save Image
                        </button>
                    </form>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>