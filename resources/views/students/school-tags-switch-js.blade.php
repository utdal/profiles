@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        $("input[type=checkbox][id^=data_school]").on('change', function() {
            if ($(this).is(':checked')) {
                Livewire.emit('addTagType', "App\\Student\\"+$(this).val());
            } else {
                Livewire.emit('removeTagType', "App\\Student\\"+$(this).val());
            }
        })
    });
</script>
@endpush
