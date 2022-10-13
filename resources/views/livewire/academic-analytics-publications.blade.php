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
                    <table class="table table-sm table-borderless table-striped table-live table-responsive-lg" aria-live="polite">
                        <thead>
                            <tr>
                                <th>Import</th>
                                <th>Year</th>
                                <th>Title</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($this->publications as $pub)
                                <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                                
                                <input type="hidden" name="id" value="{{ $pub['id'] }}">
                                <input type="hidden" name="sort_order" value="{{ $pub['sort_order'] }}">

                                <input type="hidden" name="data['title']" value="{{ $pub['data']['title'] }}">
                                <input type="hidden" name="data['year']" value="{{ $pub['data']['year'] }}">
                                <input type="hidden" name="data['url']" value="{{ $pub['data']['url'] }}">
                                <input type="hidden" name="data['group']" value=" ">
                                <input type="hidden" name="data['type']" value="{{ $pub['data']['type'] }}">
                                <input type="hidden" name="data['status']" value="">
                                <tr>
                                    <td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></td>
                                    <td> {{ $pub['data']['year'] }}</td>
                                    <td> {{ $pub['data']['title'] }}</td>
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
</div>