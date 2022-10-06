<!-- Modal -->
<div class="modal fade" id="academics_analytics_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Academics Analytics Publications</h4>
            </div>

            <div class="modal-body">
                <table class="table table-sm table-striped table-live table-responsive-lg" aria-live="polite" wire:loading.attr="aria-busy">
                    <thead>
                    <tr>
                        <th>Import</th>
                        <th>Year</th>
                        <th>Title</th>
                    </tr>
                    </thead>
                    <livewire:academics-analytics-publications :profile="$profile">
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</div>

