<!-- CSS y JS de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- Plugin Leaflet Control Geocoder -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
let map, marker;

// Inicializar mapa con coordenadas dadas
function initMap(lat, lon) {
    if (!map) {
        map = L.map('MapLocation').setView([lat, lon], 15);

        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Marcador draggable
        marker = L.marker([lat, lon], { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            $("#latitud").val(pos.lat);
            $("#longitud").val(pos.lng);
            getAddressFromCoordinates(pos.lat, pos.lng);
        });

        // Buscador siempre activo
        L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            const latlng = e.geocode.center;
            map.setView(latlng, 15);
            marker.setLatLng(latlng);
            $("#latitud").val(latlng.lat);
            $("#longitud").val(latlng.lng);
            $("#direccion").val(e.geocode.name);
        }).addTo(map);
    } else {
        // Si el mapa ya existe, solo mover marcador
        map.setView([lat, lon], 15);
        marker.setLatLng([lat, lon]);
    }

    getAddressFromCoordinates(lat, lon);
}

// Reverse geocoding para actualizar dirección
function getAddressFromCoordinates(lat, lon) {
    $.getJSON(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`, function(data) {
        if (data && data.display_name) {
            $("#direccion").val(data.display_name);
        }
    });
}

// Geolocalización inicial
function geoloc() {
    navigator.geolocation.getCurrentPosition(function(position) {
        const userlat = position.coords.latitude;
        const userlon = position.coords.longitude;

        $("#latitud").val(userlat);
        $("#longitud").val(userlon);

        initMap(userlat, userlon);
    }, function() {
        alert("Active su geolocalización o use el buscador.");
        // Si el usuario no permite geolocalización, iniciamos el mapa en coordenadas 0,0
        initMap(0, 0);
    });
}

// Detectar cambios manuales en latitud y longitud
$("#latitud, #longitud").on('change', function() {
    const lat = parseFloat($("#latitud").val());
    const lon = parseFloat($("#longitud").val());
    if (!isNaN(lat) && !isNaN(lon)) {
        initMap(lat, lon);
    }
});

$(document).ready(function() {
    geoloc();
});
</script>

