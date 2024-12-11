<div id="profile_picture_editor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"><i class="fas fa-camera"></i> Update Profile Picture</h5>
            </div>

            <div class="modal-body">
                <div class="container-md">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data" method="GET">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <p><strong>There are some errors. Please correct them and try again.</strong></p>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif<img name="image" id="file-img" src="{{ $profile->imageUrl }}" wire:ignore/>
                        <br>
                        <br>
                        <div class="control-group">
 
                            {!! Form::file('image', ['id' => 'file', 'name' => 'image', 'accept' => 'image/*', 'wire:model' => 'image', 'class' => 'd-none form-control']) !!}
                            <label for="file" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                        </div>

                        <div class="control-group">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary col-md-5" data-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" class="btn btn-primary ml-md-auto col-md-5" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#file">
                                    <i class="fas fa-upload"></i> Save Image
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>