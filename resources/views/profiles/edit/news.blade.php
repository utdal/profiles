@extends('profiles.edit.layout', ['files' => true])

@section('section_name', 'News')

@section('form')
    @foreach ($data as $news)
        <div class="row record form-group my-4 py-4 level lower-border" data-row-id="{{ $news->id }}">
			@include('profiles.edit._actions')
            <div class="col col-lg-6 col-12">
                <input type="hidden" name="data[{{ $news->id }}][id]" value="{{ $news->id }}">
                <label for="data[{{ $news->id }}][data][title]">Title</label>
                <input type="text" class="form-control" id="data[{{ $news->id }}][data][title]"
                    name="data[{{ $news->id }}][data][title]" value="{{ $news->title }}">
            </div>
            <div class="col col-lg-5 col-12">
                <label for="data[{{ $news->id }}][data][url]">URL</label>
                <input type="url" class="form-control" id="data[{{ $news->id }}][data][url]"
                    name="data[{{ $news->id }}][data][url]" value="{{ $news->url }}">
            </div>
            <div class="col col-lg-1 col-12 pb-4">
                <label for="data[{{ $news->id }}][public]">Public</label>
                <label class="switch" title="Publicly Visible">
                    <input type="hidden" name="data[{{ $news->id }}][public]" id="data[{{ $news->id }}][public]"
                        value="0">
                    <input type="checkbox" name="data[{{ $news->id }}][public]" id="data[{{ $news->id }}][public]"
                        value="1" @if ($news->public) checked @endif>
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="col col-lg-12 col-12">
                <label for="data[{{ $news->id }}][data][description]">Description</label>
                <input id="data[{{ $news->id }}][data][description]" type="hidden" class="clearable"
                    name="data[{{ $news->id }}][data][description]" value="{{ $news->description }}">
                <trix-editor aria-label="Description" input="data[{{ $news->id }}][data][description]"></trix-editor>
            </div>
            <div class="col col-lg-4 col-12">
                <label for="data[{{ $news->id }}][image]-img">Image</label>
                <img class="uploaded-image w-100 d-flex" id="data[{{ $news->id }}][image]-img"
                    src="@if ($news->imageUrl != asset('/img/default.png')) {{ $news->imageUrl }} @endif">
                <div class="custom-file form-control">
                    <input type="file" id="data[{{ $news->id }}][image]" name="data[{{ $news->id }}][image]"
                        accept="image/*" class="custom-file-input clickable">
                    <label id="label-{{ $news->id }}" for="data[{{ $news->id }}][image]"
                        class="custom-file-label">
                        {{ $news->image->file_name ?? 'Select an image' }}
                    </label>
                </div>
                @foreach ($errors->get("data.{$news->id}.image") as $image_error)
                    @include('alert', ['message' => $image_error, 'type' => 'danger'])
                    <p class="d-block invalid-feedback"><i class="fas fa-asterisk"></i> {!! $image_error !!}</p>
                @endforeach
            </div>
            <div class="col-lg-8 col col-12">
                <label for="data[{{ $news->id }}][data][image_alt]">Image Description (Alt)</label>
                <input type="text" class="form-control" id="data[{{ $news->id }}][data][image_alt]"
                    name="data[{{ $news->id }}][data][image_alt]" value="{{ $news->image_alt }}">
            </div>
        </div>
    @endforeach
@endsection
