@can('edit-estadoInhabilitado')
    <a class="btn btn-primary btn-sm edit-estadoInhabilitado" href="javascript:void(0);" id="edit-estadoInhabilitado"
    data-url="estadoinhabilitado/{{ $estadoinhabilitado->id }}/edit" data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-estadoInhabilitado')
    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['estadoinhabilitado.destroy', $estadoinhabilitado->id],
        'style' => 'display:inline',
        'id' => 'delete-form-' . $estadoinhabilitado->id,
    ]) !!}
    <button type="button" class="btn btn-danger btn-sm show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Delete') }}" id="delete-form-{{ $estadoinhabilitado->id }}"><i
            class="ti ti-trash"></i></button>
    {!! Form::close() !!}
@endcan