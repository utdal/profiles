<div class="flash-container">
    <div class="flash-message alert-{{ $type ?? 'success' }} alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&nbsp;&times;</span></button>
        {{ $message }}
    </div>
</div>