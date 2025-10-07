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
                        'route' => ['newcontrato'],
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
                <th>Propietario</th>
                <th>Estado</th>
                <th>Fecha Vencimiento</th>
                <th>Creado por</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($contratos as $key => $data)
                    <tr>
                        <td>{{$data->propietario_inmueble}}</td>
                        <td>
                            @if ($data->is_propio)
                            <span class="p-2 px-3 badge rounded-pill bg-warning">Local Propio</span>
                            @else
                                @if (empty($data->DetContrato->fecha_final))
                                    <span class="p-2 px-3 badge rounded-pill bg-danger">En Proceso</span>
                                @else
                                    @if ($data->DetContrato->fecha_final < now())
                                        <span class="p-2 px-3 badge rounded-pill bg-danger">Vencido</span>
                                    @else
                                        <span class="p-2 px-3 badge rounded-pill bg-success">Vigente</span>
                                    @endif
                                @endif
                            
                                
                            @endif
                        </td>
                        @if ($data->is_propio)
                        <td> -------------------- </td>
                        @else
                        <td>
                            @if (empty($data->DetContrato->fecha_final))
                                    -----
                                @else
                                    {{UtilityFacades::date_format($data->DetContrato->fecha_final)}}
                                @endif
                                
                            
                            </td>
                        @endif

                        <td>{{$data->name}}</td>
                        <td>
                            @if (!$data->is_propio)
                                @if (isset($data->DetContrato->adjunto) && $data->DetContrato->adjunto !== '')
                                    <a href="{{ Storage::url('Contratos/'.$data->DetContrato->adjunto) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Editar') }}" target="_blank">
                                        <i class="ti ti-download"></i>Ver Adjunto
                                    </a>
                                @else
                                    <span>(Sin Adjunto)</span>
                                @endif
                            @endif

                            <a href="{{ route('contratos.edit', $data->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Editar') }}"><i
                                    class="ti ti-edit"></i>
                            </a>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        {{
            $contratos->links();
         }}
    </div>

</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
    </div>
</div>

