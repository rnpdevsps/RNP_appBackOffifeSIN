@php
    use App\Facades\UtilityFacades;
@endphp

<div class="modal-body">
    <div class="row">
    	<div class="col-lg-6"></div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="float-end d-flex align-items-center">
                <div class="d-flex align-items-center">
                    {!! Form::open([
                        'route' => ['newemployee'],
                        'method' => 'post',
                        'class' => 'd-inline-block',
                    ]) !!}
                    {{ Form::hidden('rcm_id', $IdRCM) }}
                    {{ Form::submit('+ Nuevo', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                </div>
            </div>
    	</div>
    </div>


    <div class="row">
        <table class="table mt-4">
            <thead>
              <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Creado por</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $key => $data)
                    <tr>
                        <td>{{$data->dni}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->createdBy->name}}</td>
                        <td>
                            <a href="{{ route('empleados.edit', $data->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Editar') }}"><i
                                    class="ti ti-edit"></i>
                            </a>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        {{
            $empleados->links();
         }}
    </div>

</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
    </div>
</div>

