<div>
    @if($publication->imported)
        <button class="btn btn-primary btn-sm active remove-publication" aria-pressed="true">
            <i class="fas fa-bookmark"></i> Remove from Editor
        </button>
    @else
        <button
            class="btn btn-primary btn-sm add-publication" aria-pressed="false"
            data-publication-id="{{ $publication->id }}"
            data-title="{{ $publication->title }}"
            data-year="{{ $publication->year }}"
            data-url="{{ $publication->url }}"
            data-type="{{ $publication->type }}"
            data-doi="{{ $publication->doi }}"
            >
            <i class="far fa-bookmark"></i> Add to Editor
        </button>
    @endif

    @pushOnce('row-scripts')
    <script>
        $(document).ready( function(){

            $(document).on("click", "button.add-publication", function(e) {

                let publication_id = $(this).data('publication-id');

                livewire.emit('addToEditor',  publication_id);

                e.target.dataset.customId =  publication_id;
                e.target.dataset.insertType = 'prepend';

                let form = profiles.add_row(e);

                if ( $(form).data('custom-id') ==  publication_id ){

                    let elem_id = 'data['+$(form).data('row-id')+'][data]';

                    $(form).find('input[id="'+elem_id+'[doi]"]').val( $(this).data('doi'));
                    $(form).find('input[id="'+elem_id+'[title]"]').val( $(this).data('title'));
                    $(form).find('trix-editor[input="'+elem_id+'[title]"]').text( $(this).data('title'));
                    $(form).find('input[id="'+elem_id+'[year]"]').val( $(this).data('year'));
                    $(form).find('input[id="'+elem_id+'[url]"]').val( $(this).data('url'));
                    $(form).find('input[id="'+elem_id+'[status]"]').val( $(this).data('status'));
                    $(form).find('input[id="'+elem_id+'[type]"]').val( $(this).data('type'));
                }

            });

        });
    </script>
    @endPushOnce
</div>
