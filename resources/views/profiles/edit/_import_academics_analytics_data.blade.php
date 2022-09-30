<div class="alert alert-warning">
    <p>We found some publications available for import from <strong>Academics Analytics. </strong><a href="#" data-target="#academicsAnalyticsData" data-toggle="modal"><i class="fas fa-book"></i> Click here to review your publications,</a> check the entries that you would like to import, then click on the "Import" button.</p>
</div>

    <!-- Modal -->
<div class="modal fade" id="academicsAnalyticsData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Academics Analytics Publications</h4>
      </div>
      <div class="modal-body">
        <table>
            @foreach ($publications as $publication)
                <tr>
                    <td> {{ $publication->data['year'] }}</td>
                    <td> {{ $publication->data['title'] }}</td>
                </tr>
            @endforeach
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>