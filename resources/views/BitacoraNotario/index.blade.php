<table id="comparecientesTable" class="table">
    <thead>
      <tr>
        <th>Observaciones</th>
        <th>Motivo Inactivación</th>
        <th>Fch. Creación</th>
        <th>Generado por</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($Bitacora as $key => $data)
        <tr>
                <td>{{$data->observaciones}}</td>
                <td>{{$data->descripcion}}</td>
                <td>{{$data->created_at}}</td>
                <td>{{$data->name}}</td>
                <td>
                        @if (isset($data->adjunto) && $data->adjunto !== '')
                            <a href="{{ Storage::url('ArchivosInhabilitados/'.$data->adjunto) }}" target="_blank"><i class="ti ti-download"></i>Ver Adjunto</a>
                        @else
                            <span>(Sin Adjunto)</span>
                        @endif

                </td>
            </tr>

        @endforeach
    </tbody>
</table>
{{
    $Bitacora->links();
 }}
