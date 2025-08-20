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
      line-height: 1.6;
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
    <!-- Fondo -->
    <img src="{{ asset('/images/fondo_pdf.png') }}" style="width: 100%;">

    <!-- Foto de perfil -->
    <img src="data:image/png;base64,{{ $foto }}" class="fotoPerfil" 
        style="position: absolute; top: 180px; left: 249px; width: 269px; height: 280px;">

    <!-- Marca de agua encima -->
    <img src="{{ asset('/images/marca_agua.png') }}" class="marcaAgua"
        style="position: absolute; top: 180px; left: 249px; width: 269px; height: 280px; opacity: 0.3;">
</div>


    <div class="overlay-text">
      <div>
        <span class="value"  style="font-size: 34.3454px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ substr($datadirec->DNI, 0, 4) . '-' . substr($datadirec->DNI, 4, 4) . '-' . substr($datadirec->DNI, 8) }}</span>
        <br>
        <span class="label" style="font-size: 16.8666px; font-family: 'Montserrat', sans-serif; font-weight: 600;">IDENTIDAD</span>
      </div>

      <div class="salto"></div>
      <div>
      <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ $datadirec->Nombres }}</span><br>
      <span class="label">NOMBRE</span>      
      </div>

      <div class="salto"></div>
      <div>
        <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ $datadirec->MunicipioResidencia }}</span><br>
        <span class="label">MUNICIPIO</span>
      </div>

      <div class="salto"></div>
      <div>
        <span class="value"  style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ \Carbon\Carbon::parse($datadirec->FechaNacimiento)->format('d-m-Y') }}</span><br>
        <span class="label">FECHA DE NACIMIENTO</span>
      </div>

      <div class="salto"></div>
      <div>
        <span class="value" style="font-size: 21.9641px; font-family: 'Montserrat', sans-serif; font-weight: 800;">{{ \Carbon\Carbon::parse($datadirec->FechaExpiracionDNI)->format('d-m-Y') }}</span><br>
        <span class="label">FECHA DE EXPIRACIÓN</span>
      </div>

      <div class="salto"></div>
      <div style="font-size: 14.636px; font-family: 'Montserrat', sans-serif; font-weight: 600; color:#6C7276;">
        VALIDADO EL {{ \Carbon\Carbon::now()->format('d/m/Y') }} 
        A LAS {{ \Carbon\Carbon::now()->setTimezone('America/Tegucigalpa')->format('h:i A') }}
        @if ($datadirec->ErrData->TipoDeError == 'NIV')
            <div style="font-size: 13.636px; margin-top: -2px;">(NO ES EL ÚLTIMO DOCUMENTO VIGENTE)</div>
        @else
            <div style="font-size: 13.636px; margin-top: -2px;">(ES EL ÚLTIMO DOCUMENTO VIGENTE)</div>
        @endif
</div>

    </div>
  </div>
</body>
</html>