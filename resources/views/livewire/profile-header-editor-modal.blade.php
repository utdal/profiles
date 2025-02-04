<div id="profile_header_editor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 mb-0 ml-2">1- Select Profile Header Style</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container-md">
                    <div class="d-flex justify-content-around flex-wrap-sm-only">
                        <label class="card-radio mr-2">
                            <div class="form-check form-check-inline">
                                {!! Form::label("profile_enabled", "Avatar Layout (Default)", ['class' => 'form-check-label']) !!}
                                {!! Form::radio("fancy_header", '0', $fancy_header === '0', ['wire:model' => 'fancy_header', 'id' => "profile_enabled", 'class' => 'form-check-input'])!!}
                            </div>
                            <div class="d-flex">
                                <div>
                                    <img class="card-img-top" src="{{ 'img/default.png' }}" alt="{{ 'profile photo example' }}">
                                </div>
                            
                                <div class="col-6 align-content-center">
                                    @include('profiles/profile_card_example')
                                </div>
                            </div>
                        </label>
                 
                        <label class="card-radio">
                            <div class="form-check form-check-inline">
                                {!! Form::label("banner_enabled", "Cover Layout", ['class' => 'form-check-label']) !!}
                                {!! Form::radio("fancy_header", '1', $fancy_header === '1', ['wire:model' => 'fancy_header', 'id' => "banner_enabled", 'class' => 'form-check-input'])!!}
                            </div>
                            <div class="d-flex" style="background-image: url('img/cover.png'); height: 90%;">
                                <div class="col-6 align-content-center" style="background-color: #fff; margin: 0.75rem; padding: 0.75rem;">
                                    @include('profiles/profile_card_example')
                                </div>
                            </div>
                        </label>
                    </div>
                    @include('profiles.edit/profile_image', [
                                                    'image_url' => $profile->image_url, 
                                                    'trigger' => 'profile-header-editor-modal', 
                                                    'key' => "profile-img", 
                                                    'msg' => "This photo will appear on your profile page and as your application profile image - please use a high-quality image (300x300 pixels or larger)."
                                                ])

                    @include('profiles.edit/banner_image', [
                                                    'banner_url' => $profile->banner_url,
                                                    'trigger' => 'profile-header-editor-modal',
                                                    'key' => "banner-img",
                                                    'msg' => "This will use a full-width header style - please use a high-quality image (1280 × 720 pixels or larger)."
                                                ])
                </div>
            </div>
        </div>
    </div>
    <style>
        .card-radio {
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: border-color 0.3s, background-color 0.3s;
            max-width: 50%;
            flex: 0 0 50%;
        }
    
        .card-radio input {
            display: none;
        }
    
        /* Styling for selected card */
        .card-radio:has(input:checked) {
            border-color: #198754;
        }

        .border-top-1 {
            border-top: 1px solid #dee2e6;
        }
    </style>
</div>
