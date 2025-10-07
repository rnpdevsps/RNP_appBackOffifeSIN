<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Contratos</title>

    <style>
        /* .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        } */

        /* @media (min-width:576px) {
            .container {
                max-width: 540px
            }
        }

        @media (min-width:768px) {
            .container {
                max-width: 720px
            }
        }

        @media (min-width:992px) {
            .container {
                max-width: 960px
            }
        }

        @media (min-width:1200px) {
            .container {
                max-width: 1140px
            }
        }

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        }

        .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px
        }

        @media (min-width:992px) {
            .col-lg-6 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%
            }

            .col-lg-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }
        } */

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
</head>

<body>
    <div class="order-comfirmation">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">


                    <div class="row">
                        <div class="col-lg-3">
                        </div>

                        <div class="col-lg-6">
                            <center>
                                <h2 style="font-size 25px;">REGISTRO NACIONAL DE LAS PERSONAS</h2>
                                <h2 style="font-size 25px;">LISTADO DE ALQUILERES POR VENCER</h2>
                                <p style="margin-top:-15px;" style="font-size 20px;">REPORTE {{$rango}}</p>
                            </center>
                        </div>
                        <div class="col-lg-3">
                        </div>
                    </div>
                    <div class="confirmation-message bg-primary"
                        style="padding: 0px;margin-bottom: 20px; margin-top: 0px">
                        <h2 class="text-center"><strong style="color: white; ">{{ __('Contratos a vencer') }}</strong></h2>
                    </div>

                    <div class="row">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Nombre Inmueble</th>
                                    <th scope="col">Fecha Vencimiento</th>
                                    <th scope="col">Nombre Dueño</th>
                                    <th scope="col">Telefonos</th>
                                    <th scope="col">Valor Mensual</th>
                                    <th scope="col">Moneda</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contratos as $key => $contrato)

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
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
</body>

</html>
