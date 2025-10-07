<span>

    @can('edit-listadoelectoral')
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-listadoelectoral"
            data-url="{{ route('listadoelectoral.edit', $listadoelectoral->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan
    @can('delete-listadoelectoral')
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['listadoelectoral.destroy', $listadoelectoral->id],
            'id' => 'delete-form-' . $listadoelectoral->id,
            'class' => 'd-inline',
        ]) !!}
        <a href="#" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $listadoelectoral->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! Form::close() !!}
    @endcan

</span>
