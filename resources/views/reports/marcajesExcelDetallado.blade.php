<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Facturas</title>
  <!-- El CSS aquí es solo para vista web; Excel tomará sobre todo lo inline y colgroup -->
  <style>
    table { font-family: Arial, sans-serif; border-collapse: collapse; width: 100%; table-layout: fixed; }
  </style>
</head>
<body>
@php
    use App\Facades\UtilityFacades;
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="container">
  <div class="row">
    <div class="col-3"></div>
    <div class="col-6">
      <center>
        <h2 style="font-size:25px;">REGISTRO NACIONAL DE LAS PERSONAS</h2>
        <h2 style="font-size:25px;">REPORTE DE MARCAJES</h2>
        <p style="margin-top:-15px;">{{$rango}}</p>
      </center>
    </div>
  </div>

  <div id="bot">
    <div id="table" style="border:4pt solid #000;padding:6px;">
      <center><h2 style="font-size:30px;"></h2></center>

      @php
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        // Agrupar por empleado (name)
        $groupedMarcajes = $marcajes->groupBy('name');

        // Determinar rango de fechas
        $fechaInicio = isset($fechai) && $fechai ? Carbon::parse($fechai) : Carbon::now()->startOfMonth();
        $fechaFin    = isset($fechaf) && $fechaf ? Carbon::parse($fechaf)   : Carbon::now();
      @endphp

      <!-- Tabla única (HTML compatible con Excel) -->
      <table
        border="1"
        cellpadding="2"
        cellspacing="0"
        style="width:100%;border-collapse:collapse;table-layout:fixed;mso-border-alt:solid windowtext 1pt;mso-table-lspace:0pt;mso-table-rspace:0pt;border-color:#000;"
      >
        <!-- Anchos por columna: ajusta si necesitas -->
        <colgroup>
          <col style="width:110px;mso-width-source:userset;">
          <col style="width:110px;mso-width-source:userset;">
          <col style="width:80px;mso-width-source:userset;">
          <col style="width:180px;mso-width-source:userset;">
          <col style="width:95px;mso-width-source:userset;">
          <col style="width:135px;mso-width-source:userset;">
          <col style="width:220px;mso-width-source:userset;">
          <col style="width:210px;mso-width-source:userset;">
          <col style="width:90px;mso-width-source:userset;">
          <col style="width:95px;mso-width-source:userset;">
          <col style="width:95px;mso-width-source:userset;">
          <col style="width:85px;mso-width-source:userset;">
        </colgroup>

        <thead>
          <!-- Encabezado naranja tipo Excel -->
          <tr bgcolor="#F79646" style="background:#F79646;mso-pattern:solid none;">
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Departamento</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Municipio</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Código RCM</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Nombre RCM</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Código Empleado</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">DNI</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Nombre</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Cargo</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Fecha</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Hora Entrada</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Hora Salida</th>
            <th style="border:1px solid #000;padding:3px 5px;line-height:1;color:#fff;font-weight:bold;text-align:center;">Diferencia</th>
          </tr>
        </thead>

        <tbody>
        @foreach ($groupedMarcajes as $name => $marcajesGroup)
          @php
            // Datos fijos por empleado
            $dni            = $marcajesGroup->first()->dni ?? 'N/A';
            $cargo          = $marcajesGroup->first()->cargo ?? 'N/A';
            $codRCM         = $marcajesGroup->first()->codrcm ?? 'N/A';
            $depto          = $marcajesGroup->first()->nombredepto ?? 'N/A';
            $muni           = $marcajesGroup->first()->nombremunicipio ?? 'N/A';
            $nombreRCM      = $marcajesGroup->first()->nombrercm
                                ?? $marcajesGroup->first()->rcm_nombre
                                ?? 'N/A';
            $codigoEmpleado = $marcajesGroup->first()->codigo
                                ?? $marcajesGroup->first()->empleado_codigo
                                ?? $marcajesGroup->first()->codempleado
                                ?? 'N/A';

            // Indexar por fecha
            $marcajesPorFecha = collect($marcajesGroup)->keyBy(function($m){
              return \Carbon\Carbon::parse($m->created_at)->toDateString();
            });

            $periodo = CarbonPeriod::create($fechaInicio, $fechaFin);
          @endphp

          @foreach ($periodo as $fecha)
            @php
              $fechaString = $fecha->toDateString();
              $marcaje = $marcajesPorFecha->get($fechaString);

              // NUEVA LÓGICA: si es fin de semana y no hay marcaje, no mostrar
              if ($fecha->isWeekend() && !$marcaje) { continue; }

              $horaEntradaTxt = 'N / P';
              $horaSalidaTxt  = 'N / P';
              $minutosTarde   = 0;

              $horaEsperadaEntrada = Carbon::createFromTimeString('08:00:00');

              if ($marcaje) {
                if (!empty($marcaje->hora_entrada)) {
                  try {
                    $entrada = Carbon::parse($marcaje->hora_entrada);
                    $horaEntradaTxt = $entrada->format('H:i:s');
                    if ($entrada->greaterThan($horaEsperadaEntrada)) {
                      $minutosTarde = $entrada->diffInMinutes($horaEsperadaEntrada);
                    }
                  } catch (\Throwable $e) { $horaEntradaTxt = 'N / P'; }
                }
                if (!empty($marcaje->hora_salida)) {
                  try {
                    $salida = Carbon::parse($marcaje->hora_salida);
                    $horaSalidaTxt = $salida->format('H:i:s');
                  } catch (\Throwable $e) { $horaSalidaTxt = 'N / P'; }
                }
              } else {
                // Solo llegamos aquí cuando NO es fin de semana (porque los fines sin marca ya se omitieron)
                $minutosTarde = 480;
              }
            @endphp

            <tr>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;">{{ $depto }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;">{{ $muni }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;text-align:center;">{{ $codRCM }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;">{{ $nombreRCM }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;text-align:center;">{{ $codigoEmpleado }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;white-space:nowrap;mso-number-format:'\@';">{{ $dni }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;">{{ $name }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;">{{ $cargo }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;white-space:nowrap;text-align:center;">{{ $fecha->format('Y-m-d') }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;white-space:nowrap;text-align:center;">{{ $horaEntradaTxt }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;white-space:nowrap;text-align:center;">{{ $horaSalidaTxt }}</td>
              <td style="border:1px solid #000;padding:3px 5px;line-height:1;white-space:nowrap;text-align:center;">{{ $minutosTarde }}</td>
            </tr>
          @endforeach
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
</div>
</body>
</html>
