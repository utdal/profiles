<div>
    <button type="button" class="btn btn-primary ml-1 mt-1 py-1" data-target="#academic_analytics_modal" data-toggle="modal" wire:ignore>
        <i class="fas fa-book"></i> Academic Analytics
    </button>
    <div class="modal fade" id="academic_analytics_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title m-0" id="myModalLabel">
                        Academic Analytics Publications
                        <span wire:loading.delay wire:loading.class="d-inline" role="presentation" class="loading-indicator mx-2" wire:key="aa-loading-indicator">
                            <i class="fas fa-sync fa-spin"></i>
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body" wire:loading.attr="aria-busy">
                    @if($this->modalVisible)
                    {{ count($this->imported_publications) }}
                    <table class="table table-sm table-borderless table-striped table-live table-responsive-lg" aria-live="polite">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Title</th>
                                <th>Import</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($this->publications as $pub)
                                <tr id="{{ $pub->id }}">
                                    <td> {{ $pub->year }}</td>
                                    <td> {{ $pub->title }}</td>
                                    <td>
                                        @include('livewire.partials._import-aa-publication', ['publication' => $pub])
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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
