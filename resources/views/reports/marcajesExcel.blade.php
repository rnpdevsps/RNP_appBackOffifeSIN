<!DOCTYPE html>
<html lang="es" >
<head>
    <meta charset="UTF-8">
    <title>Reporte de Facturas</title>
    <!-- CSS only -->
    <style>
        table{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        tr:nth-child(even){
            background-color: #dddddd;
        }

    </style>
</head>
<body>
@php
    use App\Facades\UtilityFacades;
    use Illuminate\Support\Facades\Storage;

@endphp
<div class="container">

        <div class="row">
            <div class="col-3">
            </div>

            <div class="col-6">
                <center>
                    <h2 style="font-size 25px;">REGISTRO NACIONAL DE LAS PERSONAS</h2>
                    <h2 style="font-size 25px;">REPORTE DE MARCAJES</h2>
                    <p style="margin-top:-15px;">{{$rango}}</p>

                </center>
            </div>


        </div>

            <div id="bot">
					<div id="table">
                    <center><h2 style="font-size 30px;"></h2></center>
                    
                        @php
                            use Carbon\Carbon;
                            use Carbon\CarbonPeriod;

                            $groupedMarcajes = $marcajes->groupBy('name');
                            $granTotal = 0;
                        @endphp

                        @foreach ($groupedMarcajes as $name => $marcajesGroup)
                            @php
                                $granTotal = $granTotal + 1;
                                $dni = $marcajesGroup->first()->dni ?? 'N/A';
                                $cargo = $marcajesGroup->first()->cargo ?? 'N/A';
                                $codRCM = $marcajesGroup->first()->codrcm ?? 'N/A';
                                $depto = $marcajesGroup->first()->nombredepto ?? 'N/A';
                                $muni = $marcajesGroup->first()->nombremunicipio ?? 'N/A';
                                $totMinutos = 0;
                            @endphp
                            
                            <table>
                                <tr>
                                    <td><strong>DNI: </strong> {{ $dni }}<br>
                                        <strong>RCM: </strong> ({{ $codRCM }}) {{ $depto }} - {{ $muni }}
                                    </td>
                                    <td><strong>Nombre: </strong> {{ $name }}<br>
                                        <strong>Cargo: </strong> {{ $cargo }}
                                    </td>
                                </tr>
                            </table>

                            
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora Entrada</th>
                                        <th>Hora Salida</th>
                                        <th>Diferencia (minutos)</th>
                                        <th>Alerta</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        // Detectar el tipo de conexión activa
                                        $dbConnection = config('database.default');
                                    
                                        // Definir formato de hora según la base de datos
                                        $formatoHora = $dbConnection === 'oracle' ? 'Y-m-d H:i:s' : 'H:i:s';
                                        
                                        $fechaInicio = Carbon::now()->startOfMonth();
                                        $fechaFin = Carbon::now();
                                        $periodo = CarbonPeriod::create($fechai, $fechaf);
                            
                                        $totMinutos = 0;
                            
                                        $marcajesPorFecha = collect($marcajesGroup)->keyBy(function($marcaje) {
                                            return Carbon::parse($marcaje->created_at)->toDateString();
                                        });
                                    @endphp

                                    @foreach ($periodo as $fecha)
                                        @php
                                            // Buscar primero si existe marcaje en la fecha
                                            $fechaString = $fecha->toDateString();
                                            $marcaje = $marcajesPorFecha->get($fechaString);

                                            // NUEVO: si es fin de semana y no hay marcaje, omitir
                                            if ($fecha->isWeekend() && !$marcaje) continue;
                            
                                            if ($marcaje) {
                                                $salida = $marcaje->hora_salida 
                                                    ? \Carbon\Carbon::createFromFormat($formatoHora, $marcaje->hora_salida) 
                                                    : null;
                            
                                                $horaEntrada = $marcaje->hora_entrada 
                                                    ? \Carbon\Carbon::createFromFormat($formatoHora, $marcaje->hora_entrada)->format('H:i:s') 
                                                    : null;
                            
                                                $horaSalida = $salida ? Carbon::parse($salida)->format('H:i:s') : 'N / P';
                            
                                                $horaEsperadaEntrada = \Carbon\Carbon::createFromFormat('H:i:s', '08:00:00');
                            
                                                if ($horaEntrada) {
                                                    $entradaCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $horaEntrada);
                                                    $minutosTarde = $entradaCarbon->greaterThan($horaEsperadaEntrada)
                                                        ? $entradaCarbon->diffInMinutes($horaEsperadaEntrada)
                                                        : 0;
                                                } else {
                                                    $minutosTarde = 0;
                                                }
                            
                                                $totMinutos += $minutosTarde;
                                            } else {
                                                // Solo entra aquí cuando NO es fin de semana (los fines sin marca se omitieron arriba)
                                                $horaEntrada = null;
                                                $horaSalida = null;
                                                $minutosTarde = 480;
                                                $totMinutos += $minutosTarde;
                                            }
                                        @endphp
                                    
                                        <tr>
                                            <td>{{ $fecha->format('Y-m-d') }}</td>
                                            <td>{{ $horaEntrada ?? 'N / P' }}</td>
                                            <td>{{ $horaSalida ?? 'N / P' }}</td>
                                            <td>{{ $minutosTarde }}</td>
                                            <td>{{ $minutosTarde > 0 ? '⚠️️️' : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="3">Minutos Art. 11</td>
                                        <td>30 Min.</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Minutos Tarde</td>
                                        <td>{{ $totMinutos }} Min.</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Gran Total</td>
                                        @php
                                            $grantotal = $totMinutos - 30;
                                            if($grantotal < 0) $grantotal = 0;
                                        @endphp
                                        <td>{{ $grantotal }} Min.</td>
                                        <td></td>
                                    </tr>
                                </tfoot>

                                </table>
                                <p>Total marcajes de {{ $name }}: {{ $marcajesGroup->count() }}</p>
                        @endforeach
					</div>
				</div>
    </div>
</body>

</html>
