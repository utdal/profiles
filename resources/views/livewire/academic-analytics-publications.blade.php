<div>
    <button type="button" class="btn btn-primary ml-1 mt-1 py-1" data-target="#academic_analytics_modal" data-toggle="modal" wire:ignore>
        <i class="fas fa-book"></i> Import Publications
    </button>
    <div class="modal fade" id="academic_analytics_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-0" id="myModalLabel">
                        Import Publications
                        <span wire:loading.delay wire:loading.class="d-inline" role="presentation" class="loading-indicator mx-2" wire:key="aa-loading-indicator">
                            <i class="fas fa-sync fa-spin"></i>
                        </span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="alert alert-info mx-3 mt-2">
                    <ul class="fa-ul mb-0">
                        <li>
                            <span class="fa-li"><i class="fas fa-info-circle"></i></span> Select any publications you would like to import. Then, return to the editor to review and save your changes.
                        </li>
                    </ul>
                </div>

                <div class="modal-body" wire:loading.attr="aria-busy">
                    @if($this->modalVisible)
                        <div class="col col-lg-12 col-12 text-right">

                        </div>
                        <table class="table table-sm table-borderless table-striped table-live table-responsive-lg" aria-live="polite">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Title</th>
                                    <th>
                                        @if(!$this->allChecked)
                                            <button id="addAll" type="button" class="btn btn-link" wire:click="addAllToEditor"><i class="fa fa-toggle-off fa-lg" aria-hidden="true" aria-label="Select all"></i></button>
                                        @else
                                            <button id="removeAll" type="button" class="btn btn-link" aria-label="Remove all"><i class="fa fa-toggle-on fa-lg" aria-hidden="true"></i></button>
                                        @endif
                                        Select
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($this->publications as $pub)
                                    <tr>
                                        <td style="vertical-align: middle !important"> {{ $pub->year }}</td>
                                        <td style="width:85%"> {{ $pub->title }} </td>
                                        <td style="vertical-align: middle !important"
                                            data-publication="{{ $pub }}"
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
                    <div class="col col-lg-10 col-12 text-right">
                        <small>You have selected <b>{{ count($this->importedPublications) }} out of {{ $this->allPublicationsCount }}</b> publications.</small>
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
