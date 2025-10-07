@section('title')
    {{ __('Inicio') }}
@endsection
@section('auth-topbar')

@endsection

<!DOCTYPE html>
<html>

<head>
    <title>@yield('title') | {{ Utility::getsettings('app_name') }}</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (Utility::getsettings('seo_setting') == 'on')
        {!! app('seotools')->generate() !!}
    @endif
    <!-- Favicon icon -->
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap.min.css') }}">

    <link rel="icon"
        href="{{ Utility::getsettings('favicon_logo') ? Storage::url('app-logo/app-favicon-logo.png') : '' }}"
        type="image/png">
    <!-- font css -->

</head>

<body class="light">
    

<style>
        body {
            margin: 0; /* Elimina el margen predeterminado del navegador */
            height: 100vh; /* Asegura que ocupe toda la altura de la ventana */
            background-size: cover; /* Hace que la imagen cubra todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
        }

        header {
            width: 100%;
        }

        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
        }

        .footer {
            position: fixed; /* Fija la posición en la ventana */
            bottom: 0; /* Ubica el footer en la parte inferior */
            left: 0; /* Asegura que esté alineado al borde izquierdo */
            width: 100%; /* Ocupa todo el ancho de la ventana */
            height: 150px; /* Altura del footer */
            background: url("{{  Storage::url('app-logo/footer2.png') }}") no-repeat center center;
            background-size: cover; /* Ajusta la imagen para cubrir el espacio */
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            padding-bottom: 120px;
            width: 100%;
            max-width: 700px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
            z-index: 2;
            position: absolute;
            top: 15%;
        }

        h1 {
            font-size: 30px;
            color: #333;
            font-weight: bold;
        }

        .input-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .digit-box {
            width: 80px;
            height: 80px;
            font-size: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .digit-box:focus {
            border-color: #007bff;
            outline: none;
        }

        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            margin-left: 50px;
        }

        .textCod {
            width: 250px;
            height: 80px;
            font-size: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .buttonCod {
            font-size: 20px;
            padding: 15px;
            border: none;
            background-color: #0092bb;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .buttonCod:hover {
            background-color: #fbc405;
        }

        .buttonCod:active {
            background-color: #fbc405;
        }


        

        .keypad button {
            font-size: 20px;
            padding: 15px;
            border: none;
            background-color: #0092bb;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .keypad button:hover {
            background-color: #fbc405;
        }

        .keypad button:active {
            background-color: #fbc405;
        }

        .buttons {
            display: flex;
           /* justify-content: space-between;*/
        }

        .buttons img {
            font-size: 18px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            height: 270px;
            
            font-size: 30px;
            transition: background-color 0.2s;
        }

        .btn-entry {
            background-color: #28a745;
            color: #fff;
        }

        .btn-entry:active {
            background-color: #218838;
        }

        .btn-exit {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-exit:active {
            background-color: #c82333;
        }


        /* Loading Overlay */
        .loading-overlay, .procesando-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10;
            flex-direction: column;
        }

        .loading-overlay .spinner, .procesando-overlay .spinner  {
            position: absolute;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid #fff;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .loading-overlay .loading-text {
            font-size: 24px;
            color: #fff;
            font-weight: bold;
        }
        .loading-overlay .loading-text, .procesando-overlay .loading-text {
            font-size: 24px;
            color: #fff;
            font-weight: bold;
        }



        .fingerprint.scanning::after {
        content: '';
        position: absolute;
        width: 87px;
        height: 8px;
        margin-left: 73px;
        margin-top: 50px;
        background-image: linear-gradient(to bottom,
            rgba(170, 184, 190, 0),
            rgba(170, 184, 190, .8));
        animation: scanning 1.2s linear infinite;
        }

        @keyframes scanning {
            100% { transform: translate(0, 85px); }
        }

    </style>




<header>
    <img src="{{  Storage::url('app-logo/header_rnp2.png') }}" alt="Header Image">
</header>

<div id="marcaje">
    <div class="loading-overlay" id="loading-overlay">
        <div class="spinner"></div>
        <img src="{{  Storage::url('app-logo/huella.png') }}" width="100px">
        <div class="loading-text"><center>Posiciona tu dedo <br> sobre el <strong>lector de huella.</strong></center></div>
    </div>

    <div class="procesando-overlay" id="procesando-overlay">
        <div class="spinner"></div>
        <img src="{{  Storage::url('app-logo/huella.png') }}" width="100px">
        <div class="loading-text"><center>Por favor espere <br> <strong>procesando...</strong></center></div>
    </div>

    <div class="main-content">
        <div class="container">
            <img src="{{  Storage::url('app-logo/rnp.png') }}" width="150px">

            <h1>Marcaje de Empleado</h1>
            <div class="input-container">
                <input type="text" class="digit-box" id="digit-1" maxlength="1" readonly>
                <input type="text" class="digit-box" id="digit-2" maxlength="1" readonly>
                <input type="text" class="digit-box" id="digit-3" maxlength="1" readonly>
                <input type="text" class="digit-box" id="digit-4" maxlength="1" readonly>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="keypad">
                        <button onclick="addNumber(1)">1</button>
                        <button onclick="addNumber(2)">2</button>
                        <button onclick="addNumber(3)">3</button>
                        <button onclick="addNumber(4)">4</button>
                        <button onclick="addNumber(5)">5</button>
                        <button onclick="addNumber(6)">6</button>
                        <button onclick="addNumber(7)">7</button>
                        <button onclick="addNumber(8)">8</button>
                        <button onclick="addNumber(9)">9</button>
                        <button onclick="clearInput()" style="background-color: #FF8200;">C</button>
                        <button onclick="addNumber(0)">0</button>
                        <button onclick="backspace()" style="background-color: #FF8200;">←</button>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="buttons">
                        <div class="fingerprint scanning"></div>
                        <a href="#" onclick="markAttendance('Marcar')"> <img src="{{  Storage::url('app-logo/icon_marcaje.png') }}" alt=""> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div id="codigoRCM">
    <div class="main-content">
        <div class="container">
            <img src="{{  Storage::url('app-logo/rnp.png') }}" width="150px">

            <h1>C&oacute;digo RCM</h1>
            <form onsubmit="return false;">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <center><input type="text" class="form-control textCod" maxlength="6" id="codigo" name="codigo" required></center>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-secondary buttonCod" id="guardarCodigo" >Guardar Código</button> 
                        <button id="eliminarCodigo" class="d-none"  style="background-color: #FF8200;">Eliminar Código</button>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="footer"></div>


<script>

url = '{{ config('app.url') }}';
var appUrl = url.replace(/\/$/, '');


        document.addEventListener("DOMContentLoaded", function () {
            const codigoGuardado = localStorage.getItem("codigo");
            const marcaje = document.getElementById("marcaje");
            const codigoRCM = document.getElementById("codigoRCM");

            if (codigoGuardado) {
                marcaje.style.display = "block";
                codigoRCM.style.display = "none";

                //alert(`El código guardado es: ${codigoGuardado}`);
            } else {
                codigoRCM.style.display = "block";
                marcaje.style.display = "none";
                //alert("No hay ningún código guardado.");
            }

            document.getElementById("guardarCodigo").addEventListener("click", function () {
                const codigoInput = document.getElementById("codigo").value;
                const codigoGuardado = localStorage.getItem("codigo");



                if (!codigoInput.trim()) { // trim() elimina espacios en blanco al principio y al final
                    Swal.fire({
                        icon: "error",
                        title: "Por favor, ingrese un código RCM.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return; // Salir de la función si está vacío
                }


                if (codigoGuardado) {
                    marcaje.style.display = "block";
                    codigoRCM.style.display = "none";

                    alert(`El código ya existe: ${codigoGuardado}`);
                } else {
                    
                    localStorage.setItem("codigo", codigoInput);
                    Swal.fire({
                        icon: "success",
                        title: "Registro exisoto.",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    clearInput();

                    codigoRCM.style.display = "none";
                    marcaje.style.display = "block";



                    //alert("Código guardado permanentemente.");
                }
            });

            document.getElementById("eliminarCodigo").addEventListener("click", function () {
                localStorage.removeItem("codigo");
                alert("Código eliminado.");
            });
        });
    </script>


<script>
// Array to hold the digits
const digits = ["0", "0", "0", "0"];
var baseUrl = window.location.href;

const digitFields = [
    document.getElementById("digit-1"),
    document.getElementById("digit-2"),
    document.getElementById("digit-3"),
    document.getElementById("digit-4")
];

const loadingOverlay = document.getElementById("loading-overlay");
const procesandoOverlay = document.getElementById("procesando-overlay");

// Update the digit boxes
function updateDigitBoxes() {
    for (let i = 0; i < digits.length; i++) {
        digitFields[i].value = digits[i];
    }
}

// Add a number starting from the right
function addNumber(number) {
    if (digits.includes("0") || digits.join("") === "0000") {
        digits.push(number.toString());
        digits.shift(); // Remove the leftmost digit
        updateDigitBoxes();
    }
}

// Clear all digits
function clearInput() {
    digits.fill("0");
    updateDigitBoxes();
}

// Remove the rightmost digit
function backspace() {
    digits.pop(); // Remove the last digit
    digits.unshift("0"); // Add a zero at the start
    updateDigitBoxes();
}

// Mark attendance and show the result
function markAttendance(type) {
    const codigoRCM = localStorage.getItem("codigo");

    const code = digits.join("");
    if (code === "0000") {
        Swal.fire({
            icon: "error",
            title: "Por favor, ingrese un código válido.",
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }

    // Show loading overlay
    loadingOverlay.style.display = "flex";
    const resultado = digits.join("");        

    $.ajax({
        url: appUrl+'/buscarCodigo',
        type: 'POST',
        dataType: 'json',
        data: {
            codigo: resultado,
            codigoRCM: codigoRCM,
        },
        success: function (response) {
            setTimeout(() => {
                sendMessage();
            }, 2000);
        },
        error: function (xhr) {
            loadingOverlay.style.display = "none";
            clearInput();
            if (xhr.status === 404) {
                Swal.fire({
                    icon: "error",
                    title: "Empleado no existe o pertenece a otro RCM.",
                    showConfirmButton: false,
                    timer: 2100
                });
            } else {
                alert('Ocurrió un error al procesar la solicitud.');
            }
        }
    });
}

// Allow keyboard input
document.addEventListener("keydown", (event) => {
    if (event.key >= "0" && event.key <= "9") {
        addNumber(event.key);
    } else if (event.key === "Backspace") {
        backspace();
    }
});

// Initialize digit boxes on page load
updateDigitBoxes();


function sendMessage() {
    const socket = new WebSocket('ws://localhost:5000/validacion-huella/');

    const loadingOverlay = document.getElementById("loading-overlay");
    const procesandoOverlay = document.getElementById("procesando-overlay");

    socket.onopen = () => {
        console.log('Conexión WebSocket abierta');
        socket.send('Leer_Huella');
        console.log('Mensaje enviado');
    };

    socket.onmessage = (event) => {
        loadingOverlay.style.display = "none";
        procesandoOverlay.style.display = "flex";
        console.log('Mensaje recibido del servidor:', event.data);

        try {
            const data = JSON.parse(event.data);
        
            if (data.StatusCode == 500) {
                Swal.fire({
                    icon: "error",
                    title: data.Message,
                    showConfirmButton: false,
                    timer: 2100
                });
                loadingOverlay.style.display = "none";
                procesandoOverlay.style.display = "none";
                clearInput();
                return;
            }


            if (data.HuellaBase64) {
                const resultado = digits.join("");

            $.ajax({
                url: appUrl+'/registrarMarcaje',
                type: 'POST',
                dataType: 'json',
                data: {
                    codigo: resultado,
                    huella: data.HuellaBase64,
                },
                success: function (response) {
                    loadingOverlay.style.display = "none";
                    procesandoOverlay.style.display = "none";
                    clearInput();
                    Swal.fire({
                        icon: response.icon,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });        
                },
                error: function (xhr) {
                    loadingOverlay.style.display = "none";
                    procesandoOverlay.style.display = "none";
                    clearInput();
                    if (xhr.status === 404) {

                        Swal.fire({
                            icon: "error",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                
                    } else {
                        Swal.fire({
                        icon: "error",
                        title: "Ocurrió un error al procesar la solicitud.",
                        showConfirmButton: false,
                        timer: 2000
                        });

                    }
                }
            });

    
            } else {
            console.error('El mensaje no contiene HuellaBase64.');
            }
        } catch (error) {
            console.error('Error al parsear el mensaje:', error);
        }
      socket.close();
    };
  
    socket.onerror = (error) => {
      console.error('Error en la conexión WebSocket:', error);
    };
  
    socket.onclose = (event) => {


        if (!event.reason.trim()) { // trim() elimina espacios en blanco al principio y al final
            Swal.fire({
                icon: "error",
                title: "Revise que el dispositivo del mensaje este conectado.",
                showConfirmButton: false,
                timer: 2000
            });

            loadingOverlay.style.display = "none";
            procesandoOverlay.style.display = "none";
            return; // Salir de la función si está vacío
        }

      console.log('Conexión WebSocket cerrada:', event.reason);
    };
  }


if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(appUrl+'/service-worker.js')
        .then((registration) => {
            console.log('Service Worker registrado con éxito:', registration);
        })
        .catch((error) => {
            console.log('Error al registrar el Service Worker:', error);
        });
}


// Fetch the manifest.json file
var idDepto = $('#idDeptoModal').val()
url = '{{ config('app.url') }}';
var appUrl = url.replace(/\/$/, '');
var appUrl2 = '{{ config('app.url') }}'.replace(/\/$/, '');
file = appUrl + '/manifest.json';

fetch(file)
    .then(response => response.json())
    .then(data => {
        if (data.icons[0].sizes === '128x128') {
            data.icons[0].src = '{{ Utility::getpath("pwa_icon_128") ? Storage::url(Utility::getsettings("pwa_icon_128")) : "" }}';
        }
        if (data.icons[1].sizes === '144x144') {
            data.icons[1].src = '{{ Utility::getpath("pwa_icon_144") ? Storage::url(Utility::getsettings("pwa_icon_144")) : "" }}';
        }
        if (data.icons[2].sizes === '152x152') {
            data.icons[2].src = '{{ Utility::getpath("pwa_icon_152") ? Storage::url(Utility::getsettings("pwa_icon_152")) : "" }}';
        }
        if (data.icons[3].sizes === '192x192') {
            data.icons[3].src = '{{ Utility::getpath("pwa_icon_192") ? Storage::url(Utility::getsettings("pwa_icon_192")) : "" }}';
        }
        if (data.icons[4].sizes === '256x256') {
            data.icons[4].src = '{{ Utility::getpath("pwa_icon_256") ? Storage::url(Utility::getsettings("pwa_icon_256")) : "" }}';
        }
        if (data.icons[5].sizes === '512x512') {
            data.icons[5].src = '{{ Utility::getpath("pwa_icon_512") ? Storage::url(Utility::getsettings("pwa_icon_512")) : "" }}';
        }
        data.name = "{{ Utility::getsettings('app_name') }}";
        data.short_name = "{{ Utility::getsettings('app_name') }}";
        data.start_url = appUrl;

        const updatedManifest = JSON.stringify(data);
        const blob = new Blob([updatedManifest], {
            type: 'application/json'
        });
        const url = URL.createObjectURL(blob);
        document.querySelector('link[rel="manifest"]').href = url;
    })
    .catch(error => console.error('Error fetching manifest.json:', error));

var headerHright = $('header').outerHeight();
$('header').next('.home-banner-sec').css('padding-top', headerHright + 'px');
</script>

<!--scripts start here-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('vendor/landing-page2/js/jquery.min.js') }}"></script>

</body>
</html>