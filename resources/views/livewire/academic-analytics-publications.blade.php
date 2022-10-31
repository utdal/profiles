<div>
    <button type="button" class="btn btn-primary ml-1 mt-1 py-1" data-target="#academic_analytics_modal" data-toggle="modal" wire:ignore>
        <i class="fas fa-book"></i> Academic Analytics
    </button>
    <div class="modal fade" id="academic_analytics_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title m-0" id="myModalLabel">
                        Import Academic Analytics Publications
                        <span wire:loading.delay wire:loading.class="d-inline" role="presentation" class="loading-indicator mx-2" wire:key="aa-loading-indicator">
                            <i class="fas fa-sync fa-spin"></i>
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="alert alert-info mx-3 mt-2">
                    <ul class="fa-ul mb-0">
                        <li>
                            <span class="fa-li"><i class="fas fa-info-circle"></i></span> Select the publications you would like to review and save in your profile.
                        </li>
                    </ul>
                </div>

                <div class="modal-body" wire:loading.attr="aria-busy">
                    @if($this->modalVisible)
                    <table class="table table-sm table-borderless table-striped table-live table-responsive-lg" aria-live="polite">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Title</th>
                                <th style="text-align: center !important">Import</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($this->publications as $pub)
                                <tr>
                                    <td style="vertical-align: middle !important"> {{ $pub->year }}</td>
                                    <td style="width:85%"> {{ $pub->title }} </td>
                                    <td style="vertical-align: middle !important">
                                        <input type="hidden" id="data_{{ $pub->id }}"
                                            data-title="{{ $pub->title }}"
                                            data-year="{{ $pub->year }}"
                                            data-url="{{ $pub->url }}"
                                            data-type="{{ $pub->type }}"
                                            data-doi="{{ $pub->doi }}"
                                        >
                                        @include('livewire.partials._import-aa-publication')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="paginator">
                        {{ $this->publications->links() }}
                    </div>
                    @endif
                </div>
                <div class="level mt-2 mb-4 ml-2">
                        <div class="col col-lg-10 col-12">
                            <small>You have selected {{count($this->imported_publications)}} publications. When you finish your selection, close this modal to return to you editor to review and save your changes.</small>
                        </div>
                        <div class="col col-lg-2 col-12">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Return to Editor</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        if (typeof Livewire === 'object') {
            $('#academic_analytics_modal').on('show.bs.modal', () => Livewire.emit('AAPublicationsModalShown'));
        }
    </script>
    @endpush
    @stack('row-scripts')
</div>
