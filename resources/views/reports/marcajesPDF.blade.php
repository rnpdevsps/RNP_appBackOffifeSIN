<style>
        body {
            margin: 20px 20px 50px 20px; /* Ajusta el margen inferior para espacio del pie de página */
            font-family: Arial, sans-serif;
        }

        .page-break {
            page-break-after: always;
        }

        /* Estilos para el pie de página */
        .footer {
            position: fixed;
            bottom: -30px; /* Ubicación del pie de página */
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 12px;
            color: gray;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-right: 1px solid #2c2d2e
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #1f2122
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6
        }

        .table .table {
            background-color: #fff
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem
        }

        .table-bordered {
            border: 1px solid #1e2021
        }

        /* .table-bordered td,
        .table-bordered th {
            border: 1px solid #151718
        } */

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, .075)
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar
        }

        .table-responsive>.table-bordered {
            border: 0
        }

        .bg-primary {
            background-color: #898c8f !important
        }

        a.bg-primary:focus,
        a.bg-primary:hover,
        button.bg-primary:focus,
        button.bg-primary:hover {
            background-color: #0062cc !important
        }

        .text-center {
            text-align: center !important
        }
    </style>

<title>Reporte de Marcajes</title>

    <div class="order-comfirmation">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                            @php
                            use App\Facades\UtilityFacades;
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
                            
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td style="width: 50%; font-size: 14px;"><h3>DNI: {{ $dni }} <br>RCM: ({{ $codRCM }})  {{ $depto }} - {{ $muni }}</h3></td>
                                    <td style="width: 50%; font-size: 14px;"><h3>Nombre: {{ $name }} <br>Cargo: {{ $cargo }}</h3></td>
                                </tr>
                            </table>


                                <table class="table table-striped table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Hora Entrada</th>
                                            <th scope="col">Hora Salida</th>
                                            <th scope="col">Diferencia</th>
                                            <th scope="col">Alerta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        // Detectar el tipo de conexión activa
                                        $dbConnection = config('database.default');
                                    
                                        // Definir formato de hora según la base de datos
                                        $formatoHora = $dbConnection === 'oracle' ? 'Y-m-d H:i:s' : 'H:i:s';
    
                                        // Configuración del rango: Desde el inicio del mes hasta hoy
                                        $fechaInicio = Carbon::now()->startOfMonth(); // Inicio del mes actual
                                        $fechaFin = Carbon::now(); // Día actual

                                        // Generar las fechas dentro del rango
                                        $periodo = CarbonPeriod::create($fechai, $fechaf);

                                        // Total de minutos tarde acumulados
                                        $totMinutos = 0;

                                        // Convertir los marcajes a un formato indexado por fecha
                                        $marcajesPorFecha = collect($marcajesGroup)->keyBy(function($marcaje) {
                                            return Carbon::parse($marcaje->created_at)->toDateString();
                                        });
                                    @endphp

                                    @foreach ($periodo as $fecha)
                                        @php
                                            // Determinar marcaje del día
                                            $fechaString = $fecha->toDateString();
                                            $marcaje = $marcajesPorFecha->get($fechaString);

                                            // OMITIR fines de semana SOLO si no hay marcaje ese día
                                            if ($fecha->isWeekend() && !$marcaje) {
                                                continue;
                                            }
                                    
                                            if ($marcaje) {
                                                if ($marcaje->hora_salida == null ){
                                                    $salida = "00:00:00";
                                                } else {
                                                    $salida = $marcaje->hora_salida ? \Carbon\Carbon::createFromFormat($formatoHora, $marcaje->hora_salida) : null;
                                                }
                                    
                                                $horaEntrada = $marcaje->hora_entrada ? \Carbon\Carbon::createFromFormat($formatoHora, $marcaje->hora_entrada)->format('H:i:s') : null;
                                                $horaSalida = $salida;
                                    
                                                // Calcular minutos tarde
                                                $horaEsperadaEntrada = \Carbon\Carbon::createFromFormat('H:i:s', '08:00:00');
                                                
                                                if ($horaEntrada == null){
                                                    $horaEntrada = null;
                                                    $minutosTarde = 0;
                                                }else{
                                                    $horaEntrada = \Carbon\Carbon::createFromFormat('H:i:s', $horaEntrada);
                                    
                                                    $minutosTarde = $horaEntrada->greaterThan($horaEsperadaEntrada)
                                                        ? $horaEntrada->diffInMinutes($horaEsperadaEntrada)
                                                        : 0;
                                                }
                                    
                                                $totMinutos += $minutosTarde;
                                            } else {
                                                // Día sin registro (solo ocurre aquí cuando NO es fin de semana, porque los fines sin marca ya se omitieron)
                                                $horaEntrada = null;
                                                $horaSalida = null;
                                                $minutosTarde = 480;
                                    
                                                $totMinutos += $minutosTarde;
                                            }
                                    
                                            if ($horaSalida) {
                                                $horaSalida = Carbon::parse($horaSalida)->format('H:i:s');
                                            } else {
                                                $horaSalida = 'N / P';
                                            }
                                    
                                        @endphp
                                    
                                        <tr style="border: 1px solid #dddddd; font-size: 15px;">
                                            <td><p>{{ $fecha->format('Y-m-d') }}</p></td>
                                            <td><p>{{ $horaEntrada ? $horaEntrada->format('H:i:s') : 'N / P' }}</p></td>
                                            <td><p>{{ $horaSalida }}</p></td>
                                            <td><p>{{ $minutosTarde }}</p></td>
                                            @if ($minutosTarde > 0)
                                                <td><p><center><img src="{{ Storage::url('app-logo/error.png') }}" width="20px"></center></p></td>
                                            @else
                                                <td><p></p></td>
                                            @endif
                                        </tr>
                                    @endforeach




                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                </td>
                                                <td style="text-align: left; font-size: 14x; color: black;">
                                                    <p>Minutos Art. 11</p>
                                                </td>

                                                <td style="text-align: left; font-size: 14px; color: black;">
                                                    <p>30 Min.</p>
                                                </td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                </td>
                                                <td style="text-align: left; font-size: 14x; color: black;">
                                                    <p>Minutos Tarde</p>
                                                </td>

                                                <td style="text-align: left; font-size: 14px; color: black;">
                                                    <p>{{ $totMinutos }} Min.</p>
                                                </td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                </td>
                                                <td style="text-align: left; font-size: 14x; color: black;">
                                                    <p>Gran Total</p>
                                                </td>
                                                @php
                                                    $grantotal = $totMinutos-30;
                                                    if($grantotal < 0) $grantotal = 0;
                                                @endphp

                                                <td style="text-align: left; font-size: 14px; color: black;">
                                                    <p>{{ $grantotal }} Min.</p>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>

                                   

                                    </tbody>
                                </table>
                                <p>Total marcajes de {{ $name }}: {{ $marcajesGroup->count() }}</p>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
</body>

</html>
