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
            

        <div class="text-right mt-2 mb-0" wire:ignore.self>
            <button type="submit" id="download_button" class="btn btn-primary btn-sm" >
                <span id="download_spinner" class="spinner-border spinner-border-sm text-light d-none" role="status" aria-hidden="true"></span>
                <span id="download_label">Download</span>
            </button>
        </div>

    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const spinner = document.getElementById('download_spinner');
            const label = document.getElementById('download_label');
            const button = document.getElementById('download_button');
            const form = button.closest('form');

            form.addEventListener('submit', function () {
                spinner.classList.remove('d-none');
                label.textContent = 'Downloading...';
                button.disabled = true;
            });

            window.addEventListener('downloadStarted', function () {
                console.log('Livewire event received, delaying reset...');
                    spinner.classList.add('d-none');
                    label.textContent = 'Download';
                    button.disabled = false;
            });
        });
    </script>

</div>