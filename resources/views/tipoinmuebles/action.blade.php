@can('edit-tipoinmuebles')
    <a class="btn btn-primary btn-sm edit-tipoinmueble" href="javascript:void(0);" id="edit-tipoinmueble"
    data-url="tipoinmuebles/{{ $tipoinmueble->id }}/edit" data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-tipoinmuebles')
    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['tipoinmuebles.destroy', $tipoinmueble->id],
        'style' => 'display:inline',
        'id' => 'delete-form-' . $tipoinmueble->id,
    ]) !!}
    <button type="button" class="btn btn-danger btn-sm show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Delete') }}" id="delete-form-{{ $tipoinmueble->id }}"><i
            class="ti ti-trash"></i></button>
    {!! Form::close() !!}
@endcan
