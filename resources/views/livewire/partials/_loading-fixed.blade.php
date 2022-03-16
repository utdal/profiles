<div
    wire:loading.delay
    wire:loading.class="position-fixed"
    role="presentation"
    style="top: 3.5em; right: 1em"
    @isset($loading_target)
        wire:target="{{ $loading_target }}"
    @endisset
>
    <div class="p-4">
        <h1 class="m-0"><i class="fas fa-spinner fa-spin"></i></h1>
    </div>
</div>