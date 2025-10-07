



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
                    <h2 style="font-size 25px;">LISTADO DE ALQUILERES POR VENCER</h2>
                    <p style="margin-top:-15px;">REPORTE {{$rango}}</p>

                </center>
            </div>


        </div>

            <div id="bot">
					<div id="table">
                    <center><h2 style="font-size 30px;"></h2></center>
                    <table>
							<tr style="border: 1px solid #dddddd;padding: 10px;">
								<td>Codigo</td>
								<td>Departamento</td>
                                <td>Municipio</td>
                                <td>Tipo</td>
                                <td>Nombre Inmueble</td>
                                <td>Fecha Vencimiento</td>
                                <td>Nombre Dueño</td>
                                <td>Telefonos</td>
                                <td>Valor Mensual</td>
                                <td>Moneda</td>
							</tr>

                            @foreach ($contratos as $contrato)
							<tr style="border: 1px solid #dddddd;padding: 1px; font-size: 15px;">
								<td><p>{{$contrato->codigo}}</p></td>
                                <td><p>{{$contrato->nombredepto}}</p></td>
                                <td><p>{{$contrato->nombremunicipio}}</p></td>
                                <td><p>{{$contrato->clasificacion}}</p></td>
                                <td><p>{{$contrato->propietario_inmueble}}</p></td>
                                <td><p>{{$contrato->fecha_final}}</p></td>
                                <td><p>{{$contrato->contacto_directo}}</p></td>
                                <td><p>{{$contrato->celular}}</p></td>
                                <td><p>{{$contrato->valor_mensual}}</p></td>
                                <td><p>{{$contrato->moneda}}</p></td>
							</tr>


                            @endforeach

						</table>
					</div>

				</div>
    </div>
</body>

</html>
