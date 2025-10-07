<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
    $('#dni').on('input', function() {
        var value = $(this).val();
        value = value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
        if (value.length > 0) {
            var formattedValue = '';
            // Formatea los primeros cuatro dígitos
            formattedValue += value.substr(0, 4);
            if (value.length > 4) {
                formattedValue += '-';
                // Formatea los siguientes cuatro dígitos
                formattedValue += value.substr(4, 4);
                if (value.length > 8) {
                    formattedValue += '-';
                    // Formatea los últimos cinco dígitos
                    formattedValue += value.substr(8, 5);
                }
            }
            $(this).val(formattedValue);
        }
    });
    $('#dniFormat').on('input', function() {
        var value = $(this).val();
        value = value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
        if (value.length > 0) {
            var formattedValue = '';
            // Formatea los primeros cuatro dígitos
            formattedValue += value.substr(0, 4);
            if (value.length > 4) {
                formattedValue += '-';
                // Formatea los siguientes cuatro dígitos
                formattedValue += value.substr(4, 4);
                if (value.length > 8) {
                    formattedValue += '-';
                    // Formatea los últimos cinco dígitos
                    formattedValue += value.substr(8, 5);
                }
            }
            $(this).val(formattedValue);
        }
    });

    $('#dniApi').on('input', function() {
        var value = $(this).val();
        value = value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
        if (value.length > 0) {
            var formattedValue = '';
            // Formatea los primeros cuatro dígitos
            formattedValue += value.substr(0, 4);
            if (value.length > 4) {
                formattedValue += '-';
                // Formatea los siguientes cuatro dígitos
                formattedValue += value.substr(4, 4);
                if (value.length > 8) {
                    formattedValue += '-';
                    // Formatea los últimos cinco dígitos
                    formattedValue += value.substr(8, 5);
                }
            }
            $(this).val(formattedValue);
        }
    });

    var mainurl = "{{ url('/') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // AJAX para cargar Municipios x Deptos
    $('#idDepto').change(function() {
        var id = $(this).val();

        if(id) {
            $.ajax({
                url: mainurl+'/obtenerMunicipiosPorDeptos/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#idMunicipio').empty();
                    $('#idMunicipio').append('<option value="">Seleccione un Municipio</option>');
                    $.each(data, function(index, opcion) {
                        $('#idMunicipio').append('<option value="'+ opcion.id +'">'+ opcion.nombremunicipio +'</option>');
                    });
                }
            });
        } else {
            $('#idMunicipio').empty();
            $('#idMunicipio').append('<option value="">Seleccione un Municipio</option>');
        }
    });


    //////////////////////////////////////////////
    $('#dni').on('input', function() {
        var numeroDNI = $(this).val();
        let query = numeroDNI.replace(/-/g, '');

        if (query.length > 12) {
            $.ajax({
                url: "{{ route('buscar') }}",
                method: 'GET',
                data: {
                    query: query
                },
                success: function(response) {
                    var Resultado = response[0];

                    if (Resultado) {
                        $('#errorDNI').css('display', 'none');
                        $('#btnDNI').css('display', 'block');
                        $('#name').val(Resultado.name);
                        $('#name').focus();

                    } else {
                        $('#btnDNI').css('display', 'none');
                        $('#errorDNI').css('display', 'block');
                        $('#errorDNI').val('No se encontraron resultados');
                    }
                }
            });
        }
    });


    //////////////////////////////////////////////
    $('#dniApi').on('input', function() {
        var numeroDNI = $(this).val();
        let query = numeroDNI.replace(/-/g, '');

        if (query.length > 12) {
            $.ajax({
                url: "{{ route('buscarDNIApi') }}",
                method: 'GET',
                data: {
                    query: query
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var Nombres = data.Qry_InscripcionNacimientoResult.Nombres;
                    var PrimerApellido = data.Qry_InscripcionNacimientoResult.PrimerApellido;
                    var SegundoApellido = data.Qry_InscripcionNacimientoResult.SegundoApellido;

                    var Resultado = response[0];

                    if (Resultado) {
                        $('#errorDNI').css('display', 'none');
                        $('#btnDNI').css('display', 'block');
                        $('#name').val(Nombres+" "+PrimerApellido+" "+SegundoApellido);
                        $('#name').focus();

                    } else {
                        $('#btnDNI').css('display', 'none');
                        $('#errorDNI').css('display', 'block');
                        $('#errorDNI').val('No se encontraron resultados');
                    }
                }
            });
        }
    });


});
</script>
