<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>RNP PDF</title>
  <style>
    body {
      margin: 0;
      padding: 0;
    }
    .pdf-container {
      position: relative;
      height: 990px; width: 765px;
      font-family: Arial, sans-serif;
    }
    .background {
      width: 100%;
      height: 100%;
    }
    .overlay-text {
      position: absolute;
      top: 480px; /* Ajusta según sea necesario */
      left: 50px;
      right: 50px;
      text-align: center;
      font-size: 18px;
      line-height: 1.8;
    }
    .label {
      color: #009CBD;
      font-size: 16.8666px; 
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
    }
    .value {
      color: #000;
      font-weight: bold;
    }

    .salto {
      height: 15px;
    }
    .brsalto {
      height: 2px;
    }

    @font-face {
        font-family: 'Montserrat';
        src: url('https://cdn.jsdelivr.net/npm/@fontsource/montserrat@latest/900.css') format('truetype');
        font-weight: 900;
    }

    @font-face {
        font-family: 'Montserrat';
        src: url('https://cdn.jsdelivr.net/npm/@fontsource/montserrat@latest/600.css');
        font-weight: 600;
    }

        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap');
        .nombre {
            position: absolute;
            left: inherit;
            right: inherit;
            margin-left: -20px;
            top: 600.489px;
            font-size: 21.96px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            white-space: nowrap;
            text-align: center;
        }

  </style>
</head>
<body >
  <div class="pdf-container">
    <div class="canvasLayer" style="position: relative;">
        <img src="{{ asset('/images/fondo_pdf.png') }}" style="width: 100%;">
        <img src="data:image/png;base64,{{ $foto }}" class="fotoPerfil" 
            style="position: absolute; top: 180px; left: 249px; width: 269px; height: 280px;"">
    </div>

    <div class="overlay-text">
      <div>
        <span class="value"  style="font-size: 34.3454px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ substr($datadirec->Inscripcion->NumInscripcion, 0, 4) . '-' . substr($datadirec->Inscripcion->NumInscripcion, 4, 4) . '-' . substr($datadirec->Inscripcion->NumInscripcion, 8) }}</span>
        <br>
        <span class="label" style="font-size: 16.8666px; font-family: 'Montserrat', sans-serif; font-weight: 600;">IDENTIDAD</span>
      </div>

      <div class="salto"></div>
      <div>
      <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ $datadirec->Inscripcion->Nombres }} {{ $datadirec->Inscripcion->PrimerApellido }} {{ $datadirec->Inscripcion->SegundoApellido }}</span><br>
      <span class="label">NOMBRE</span>      
      </div>

      <div class="salto"></div>
      <div>
        <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ $datadirec->Municipio }}</span><br>
        <span class="label">MUNICIPIO</span>
      </div>

      <div class="salto"></div>
      <div>
        <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ \Carbon\Carbon::parse($datadirec->Inscripcion->FechaDeNacimiento)->format('d-m-Y') }}</span><br>
        <span class="label">FECHA DE NACIMIENTO</span>
      </div>

      <div class="salto"></div>
      <div>
        <span class="value" style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">16-11-2031</span><br>
        <span class="label">FECHA DE EXPIRACIÓN</span>
      </div>

      <div class="salto"></div>
      <div><span class="label" style="font-size: 14.636px; font-family: 'Montserrat', sans-serif; font-weight: 600; color:#6C7276">VALIDADO EL {{ \Carbon\Carbon::now()->format('d/m/Y') }} A LAS {{ \Carbon\Carbon::now()->setTimezone('America/Tegucigalpa')->format('h:i A') }}</span></div>
    </div>
  </div>
</body>
</html>