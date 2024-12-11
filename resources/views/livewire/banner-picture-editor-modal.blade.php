<div id="banner_picture_editor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"><i class="fas fa-camera"></i> Use Fancy Header</h5>
            </div>

            <div class="modal-body">
                <div class="container-md">
                    <div class="alert alert-info">
                        <ul class="fa-ul mb-0">
                            <li>
                                <span class="fa-li"><i class="fas fa-info-circle"></i></span> Select a banner image to upload and preview.
                            </li>
                            <li>
                                <span class="fa-li"><i class="fas fa-info-circle"></i></span> Note: Clicking 'Save and Use' will replace your profile picture with a fancy header style, using the selected image as the background. Click 'Restore Header' to remove the fancy header and use your profile picture.
                            </li>
                        </ul>
                    </div>
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
                        <img id="banner-img" src="{{ $profile->banner_url }}" wire:ignore/>
                        <br>
                        <br>
                        <div class="controls">
 
                            {!! Form::file('banner_image', ['id' => 'banner', 'name' => 'banner_image', 'accept' => 'image/*', 'wire:model' => 'banner_image', 'class' => 'd-none form-control']) !!}
                            <label for="banner" class="btn btn-secondary btn-block"><i class="fas fa-plus"></i> Select Image</label>
                        </div>
                        <div>
                            <small class="text-muted">This will use a full-width header style - please make sure uploaded banner image is of sufficient quality!</small>
                        </div>

                        <fieldset class="form-group row py-2">
                            <div>
                                <div id="fancy_header_options">
                                    <div class="form-group">
                                        <input id="fancy_header_right" type="checkbox" wire:model="fancy_header_right">
                                        <label class="form-check-label" for="fancy_header_right">Align Header Right</label>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <div class="control-group">
                            <div class="row justify-content-between">
                                <button type="button" class="btn btn-secondary ml-3 col-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                                <a wire:click="$emit('removeFancyHeader')" class="btn btn-primary col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin">
                                    <i class="fas fa-undo"></i> Restore Header
                                </a>
                                <button type="submit" class="btn btn-primary mr-3 col-3" data-toggle="replace-icon" data-newicon="fas fa-sync fa-spin" data-inputrequired="#banner">
                                    <i class="fas fa-upload"></i> Save and Use
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>