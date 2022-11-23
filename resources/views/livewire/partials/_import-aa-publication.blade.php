<div>
    @if($pub->imported == true)
        <label class="switch">
            <input type="checkbox" class="remove-publication" id="{{ $pub->id }}" value="1" checked>
            <span class="slider round"></span>
        </label>
    @else
        <label class="switch">
            <input type="checkbox" class="add-publication" id="{{ $pub->id }}" value="0">
            <span class="slider round"></span>
        </label>
    @endif

    @pushOnce('row-scripts')
    <script>

        $(document).ready( function(){

            //Add a Single Publication to the Editor
            $(document).on("click", "input.add-publication", function(e) {

                let publication = $(this).parents('td').data('publication');

                livewire.emit('addToEditor',  publication.id);

                render_row(e, publication);
            });

            //Remove a Single Publication From the Editor
            $(document).on("click", "input.remove-publication", function(e) {

                let publication = $(this).parents('td').data('publication');

                livewire.emit('removeFromEditor',  publication.id);

                let row_selector = $('div.record[data-custom-id='+publication.id+']');

                if ($(row_selector).length == 1) {
                    $(row_selector).remove();
                    //profiles.clear_row($('div.record[data-custom-id='+publication.id+'] a.trash'));
                }
            });

            //Add ALL the Publications to the Editor
            $(document).on("click", "button#addAll", (e) => {
                $(this).children('i').toggleClass("fa fa-spinner fa-pulse fa-lg");
            });

            livewire.on('JSAddAllToEditor', (publications) => {

                publications.forEach((publication) => {

                    let row_selector = $('div.record[data-custom-id='+publication.id+']');

                    if (row_selector.length == 0) {
                        $('div.sortable').append('<button type="button" id="all_rows_'+publication.id+'" data-toggle="add_row"></button>');
                        let temp_button = $('div.sortable button#all_rows_'+publication.id).first();
                        $(temp_button).on('click', publication, (e) => {
                            render_row(e, publication);
                        });
                        $(temp_button).click().remove();
                    }
                });
                $('div.sortable div.record[data-row-id="0"]').hide();
            });

            //Remove ALL the Publications From the Editor
            $(document).on("click", "button#removeAll", (e) => {

                $(this).children('i').toggleClass("fa fa-spinner fa-pulse fa-lg");

                $('div.record[data-custom-id]').remove();

                livewire.emit('removeAllFromEditor');

                e.target.dataset.insertType = 'prepend';

                $('div.sortable div.record[data-row-id="0"]').show();

            });

            //Render a new row and populate it with the publication data
            function render_row(e, publication){
                e.target.dataset.customId =  publication.id;
                e.target.dataset.insertType = 'append';

                let form = profiles.add_row(e);

                if ( $(form).data('custom-id') == publication.id ){

                    let elem_id = 'data['+$(form).data('row-id')+'][data]';

                    $(form).find('input[id="'+elem_id+'[doi]"]').val( publication.data['doi']);
                    $(form).find('input[id="'+elem_id+'[title]"]').val( publication.data['title']);
                    $(form).find('trix-editor[input="'+elem_id+'[title]"]').text( publication.data['title']);
                    $(form).find('input[id="'+elem_id+'[year]"]').val( publication.data['year']);
                    $(form).find('input[id="'+elem_id+'[url]"]').val( publication.data['url']);
                    $(form).find('input[id="'+elem_id+'[status]"]').val( publication.data['status']);
                    $(form).find('input[id="'+elem_id+'[type]"]').val( publication.type);
                }
            }
        });

    </script>
    @endPushOnce
</div>
