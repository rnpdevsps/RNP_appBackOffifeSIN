<span>

    @if ($empleado->status == 1)
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="inactivar-empleado"
        data-url="{{ route('empleados.inactivar', $empleado->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Inactivar Empleado') }}"><i class="ti ti-user"></i></a>

    @else
        <a class="btn btn-warning btn-sm" href="{{ route('empleado.status', $empleado->id) }}" data-bs-toggle="tooltip"
            data-bs-placement="bottom" data-bs-original-title="Activar Empleado">
            <i class="ti ti-user"></i></a>
    @endif


    @can('edit-empleados')
        <a class="btn btn-warning btn-sm" href="javascript:void(0);" id="edit-empleado"
            data-url="{{ route('empleados.edit', $empleado->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan

    <a class="btn btn-secondary btn-sm" href="javascript:void(0);" id="bitacora-empleado"
        data-url="{{ route('empleado.bitacora', $empleado->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Bitacora del Empleado') }}"><i class="ti ti-new-section"></i>
    </a>

    @can('delete-empleados')
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['empleados.destroy', $empleado->id],
            'id' => 'delete-form-' . $empleado->id,
            'class' => 'd-inline',
        ]) !!}
        <a href="#" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $empleado->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! Form::close() !!}
    @endcan



</span>
