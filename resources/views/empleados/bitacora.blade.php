@php
    use App\Facades\UtilityFacades;
@endphp
<table id="comparecientesTable" class="table">
    <thead>
      <tr>
        <th>Observaciones</th>
        <th>Motivo</th>
        <th>Fecha Creación</th>
        <th>Generado por</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($Bitacora as $key => $data)
        <tr>
                <td>{{$data->observacion}}</td>
                <td>{{$data->estado_descripcion}}</td>
                <td>{{UtilityFacades::date_time_format($data->created_at)}}</td>
                <td>{{$data->creado_por}}</td>
            </tr>

        @endforeach
    </tbody>
</table>
{{
    $Bitacora->links();
 }}
