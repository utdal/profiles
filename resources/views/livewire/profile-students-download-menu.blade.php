<div>
    <form wire:submit.prevent="download">
        <div class="row">
            <div class="col-sm-4 d-flex flex-column justify-content-around">
                @if($filter_summary)
                    <label class="text-primary">Applications</label>
                @endif
                <label class="text-primary mt-4">Download as</label>
            </div>

            <div class="col-sm-8">
                @if($filter_summary)
                    <div class="d-flex flex-column">
                        <div class="form-check-inline">
                            {!! Form::radio("download_filtered", 'filtered', $application_scope === 'filtered', [
                                'id' => 'download_filtered',
                                'class' => 'form-check-input',
                                'wire:model' => 'application_scope',
                                'aria-label'=> "Download filtered student applications",
                                'disabled' => !isset($filter_summary),
                            ])!!}
                            {!! Form::label("download_filtered", $filter_summary ?? 'Apply a Filter', ['class' => 'form-check-label small text-muted', 'style' => 'font-weight: normal !important;']) !!}
                        </div>

                        <div class="form-check-inline">
                            {!! Form::radio("download_all", 'all', $application_scope === 'all', [
                                'id' => 'download_all',
                                'class' => 'form-check-input',
                                'wire:model' => 'application_scope',
                                'aria-label'=> "Download all student applications",
                            ])!!}
                            {!! Form::label("download_all", 'All', ['class' => 'form-check-label small text-muted', 'style' => 'font-weight: normal !important;']) !!}
                        </div>
                    </div>
                @endif

                <div class="d-flex mt-4 @if($filter_summary) justify-content-around flex-column @else flex-row @endif">
                    <div class="form-check-inline">
                        {!! Form::radio("download_as_pdf", 'pdf', $file_format === 'pdf', [
                            'id' => 'download_as_pdf',
                            'class' => 'form-check-input',
                            'wire:model' => 'file_format',
                            'aria-label'=> "PDF",
                        ])!!}
                        {!! Form::label("download_as_pdf", 'PDF', ['class' => 'form-check-label small text-muted', 'style' => 'font-weight: normal !important;']) !!}
                    </div>

                    <div class="form-check-inline">
                        {!! Form::radio("download_as_excel", 'excel', $file_format === 'excel', [
                            'id' => 'download_as_excel',
                            'class' => 'form-check-input',
                            'wire:model' => 'file_format',
                            'aria-label'=> "Excel",
                        ])!!}
                        {!! Form::label("download_as_excel", 'Excel', ['class' => 'form-check-label small text-muted', 'style' => 'font-weight: normal !important;']) !!}
                    </div>
                </div>
            </div>
        </div>
            

        <div class="text-right mt-2 mb-0" wire:ignore>
            <button type="submit" id="download_button" class="btn btn-primary btn-sm" >
                <span id="download_spinner" class="spinner-border spinner-border-sm text-light d-none" role="status" aria-hidden="true"></span>
                <span id="download_label">Download</span>
            </button>
            <p id="new_tab_msg" class="small text-muted mt-2 d-none" style="font-style: italic;">Your download will begin shortly in a new tab...</p>.</p>
        </div>

    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const spinner = document.getElementById('download_spinner');
            const label = document.getElementById('download_label');
            const button = document.getElementById('download_button');
            const new_tab_msg = document.getElementById('new_tab_msg');
            const form = button.closest('form');
            const originalLabel = label.textContent;

            form.addEventListener('submit', function () {
                if (button.disabled) return;

                let download_type = @this.file_format;

                if (download_type === 'pdf') {
                    new_tab_msg.classList.remove('d-none');
                }

                spinner.classList.remove('d-none');
                label.textContent = 'Downloading...';
                button.disabled = true;

            });

            function resetDownloadButtonOn(events) {
                events.split(",").forEach(event_name => {
                    window.addEventListener(event_name.trim(),  e => {
                        spinner.classList.add('d-none');
                        label.textContent = originalLabel;
                        button.disabled = false;
                        new_tab_msg.classList.add('d-none');
                    });
                });
            }

            resetDownloadButtonOn("initiatePdfDownload, initiateXlsxDownload, noStudentRecordsFound");

            window.addEventListener('initiatePdfDownload', event => {
                let url = event.detail.url;
                window.open(url);
            });

            window.addEventListener('noStudentRecordsFound', event => {
                Livewire.emit('alert', "No records available for the filters applied", 'danger');
            });
        });
    </script>

</div>