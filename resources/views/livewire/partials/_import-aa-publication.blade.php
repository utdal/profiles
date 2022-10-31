<div>
    @if($pub->imported)
        <button id="{{ $pub->id }}" class="btn btn-primary btn-sm active remove-publication" aria-pressed="true" style="width: 100%">
            <i class="fas fa-times"></i> Remove
        </button>
    @else
        <button
            id="{{ $pub->id }}" class="btn btn-primary btn-sm add-publication" aria-pressed="false" style="width: 100%">
            <i class="fas fa-check"></i> Add
        </button>
    @endif

    @pushOnce('row-scripts')
    <script>
        $(document).ready( function(){

            $(document).on("click", "button.add-publication", function(e) {
                let publication_id = $(this).attr('id');
                let data_selector = $(this).parent().siblings('input#data_'+ publication_id);

                livewire.emit('addToEditor',  publication_id);

                e.target.dataset.customId =  publication_id;
                e.target.dataset.insertType = 'prepend';

                let form = profiles.add_row(e);

                if ( $(form).data('custom-id') ==  publication_id ){

                    let elem_id = 'data['+$(form).data('row-id')+'][data]';

                    $(form).find('input[id="'+elem_id+'[doi]"]').val( $(data_selector).data('doi'));
                    $(form).find('input[id="'+elem_id+'[title]"]').val( $(data_selector).data('title'));
                    $(form).find('trix-editor[input="'+elem_id+'[title]"]').text( $(data_selector).data('title'));
                    $(form).find('input[id="'+elem_id+'[year]"]').val( $(data_selector).data('year'));
                    $(form).find('input[id="'+elem_id+'[url]"]').val( $(data_selector).data('url'));
                    $(form).find('input[id="'+elem_id+'[status]"]').val( $(data_selector).data('status'));
                    $(form).find('input[id="'+elem_id+'[type]"]').val( $(data_selector).data('type'));
                }

            });
        });
    </script>
    @endPushOnce
</div>
