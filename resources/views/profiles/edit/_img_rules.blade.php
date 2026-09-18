@php
$img_request = new \App\Http\Requests\ProfileImageRequest();
@endphp
<div id="img-rules" style="display:none">
    <p class="m-1"><small>Supported file types: {{ $img_request->supportedMimeString() }}.</small></p>
    <p class="m-1"><small>Maximum file size: {{ $img_request->maxFilesize() }} MB.</small></p>
    <p class="m-1"><small>Maximum file name length: {{ $img_request->maxFilenameLength() }} characters.</small></p>
</div>