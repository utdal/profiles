@foreach($errors->get($field_name) as $field_error)
    <p class="d-block invalid-feedback"><i class="fas fa-asterisk"></i> {!! $field_error !!}</p>
@endforeach