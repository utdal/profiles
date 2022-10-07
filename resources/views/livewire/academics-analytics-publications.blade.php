<div class="modal-body">
    <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
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