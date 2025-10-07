@php
    use App\Facades\UtilityFacades;
@endphp
<table id="comparecientesTable" class="table">
    <thead>
      <tr>
        <th>Observaciones</th>
        <th>Motivo Inactivación</th>
        <th>Fecha Creación</th>
        <th>Generado por</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($Bitacora as $key => $data)
        <tr>
                <td>{{$data->observaciones}}</td>
                <td>{{$data->descripcion}}</td>
                <td>{{UtilityFacades::date_time_format($data->created_at)}}</td>
                <td>{{$data->name}}</td>
            </tr>

        @endforeach
    </tbody>
</table>
{{
    $Bitacora->links();
 }}
