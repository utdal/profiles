<div>
    <button wire:click="$set('modalVisible', true)" type="button" class="btn btn-primary ml-1 mt-1 py-1" data-target="#academic_analytics_modal" data-toggle="modal">
        <i class="fas fa-book"></i> Academic Analytics
    </button>
    @if($modalVisible)
        <div class="modal fade" id="academic_analytics_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" wire:ignore.self>
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Academic Analytics Publications</h4>
                    </div>

                    <div class="modal-body" wire:loading.attr="aria-busy">
                        <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite">
                            <thead>
                                <tr>
                                    <th>Import</th>
                                    <th>Year</th>
                                    <th>Title</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($publications as $pub)
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
                            {{ $publications->links() }}
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>