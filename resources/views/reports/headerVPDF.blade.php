<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha Inspeccion</title>

</head>

<body>
    <div class="container">

<style>
    #background-div {
    width: 100%;
    background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/header.png'))) }}");
     background-size: 100% auto; /* Ajusta la imagen al ancho del div */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat;
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

<div class="row" id="background-div">
    <div class="col-lg-12" >
        <center>
            <h2 style="font-size 35px;"><br>REGISTRO NACIONAL DE LAS PERSONAS <br>{{ $poll->title }}</h2>


        </center>
    </div>

</div>
<br>