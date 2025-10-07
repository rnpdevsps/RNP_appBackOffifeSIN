{!! html()->form('POST', route('rcms.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->open() !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@include('rcms.map')

<script>
    $(document).ready(function(){
    var mainurl = "{{ url('/') }}";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// AJAX para cargar Municipios x Deptos
$('#idDeptoModal').change(function() {
        var id = $(this).val();

        if(id) {
            $.ajax({
                url: mainurl+'/obtenerMunicipiosPorDeptos/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#idMunicipioModal').empty();
                    $('#idMunicipioModal').append('<option value="">Seleccione un Municipio</option>');
                    $.each(data, function(index, opcion) {
                        $('#idMunicipioModal').append('<option value="'+ opcion.id +'">'+ opcion.nombremunicipio +'</option>');
                    });
                }
            });
        } else {
            $('#idMunicipioModal').empty();
            $('#idMunicipioModal').append('<option value="">Seleccione un Municipio</option>');
        }
    });
});
</script>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group ">
                <div class="form-group">
                    {!! html()->label(__('Departamento'), 'idDepto')->class('col-form-label') !!}
                    {!! html()->select('idDepto', $deptos)->required()->class('form-select')->id('idDeptoModal') !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {!! html()->label(__('Municipio'), 'idMunicipio')->class('col-form-label') !!}
                {!! html()->select('idMunicipio', $municipios)->required()->class('form-select')->id('idMunicipioModal') !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                {!! html()->label(__('Código (Max. 10 caracteres)'), 'codigo')->class('col-form-label') !!}
                {!! html()->text('codigo')
                    ->class('form-control')
                    ->placeholder(__('050101'))
                    ->required()
                    ->attribute('maxlength', 10) !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {!! html()->label(__('Name'), 'name')->class('col-form-label') !!}
                {!! html()->text('name')->class('form-control')->placeholder(__('Enter name'))->required() !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                <div class="form-group">
                    {!! html()->label(__('Clasificación'), 'id_clasificacion')->class('col-form-label') !!}
                    {!! html()->select('id_clasificacion', $clasificaciones)->required()->class('form-select')->id('id_clasificacion') !!}
                </div>
            </div>
    	</div>

        <div class="col-md-6">
            <div class="form-group">
                {!! html()->label(__('Foto'), 'foto')->class('col-form-label') !!}
                {!! html()->file('foto')->class('form-control')->accept('.jpeg,.jpg,.png') !!}
                <small>{{ __('NOTE: Extensión: .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                {!! html()->label(__('Telefono'), 'telefono')->class('col-form-label') !!}
                {!! html()->text('telefono')->class('form-control')->placeholder(__('telefono'))!!}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div id="MapLocation" style="height: 210px; border: 1px solid #000; border-radius: 8px;"></div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    {!! html()->label(__('Latitud'), 'latitud')->class('col-form-label') !!}
                    {!! html()->text('latitud')->class('form-control')->id('latitud')->placeholder(__('Latitud'))->required() !!}
                </div>
                <div class="col-md-6">
                    {!! html()->label(__('Longitud'), 'longitud')->class('col-form-label') !!}
                    {!! html()->text('longitud')->class('form-control')->id('longitud')->placeholder(__('Longitud'))->required() !!}
                </div>
            </div>
            <div class="form-group mt-3">
                {!! html()->label(__('Dirección'), 'direccion')->class('form-label') !!}
                {!! html()->textarea('direccion', null)->class('form-control')->id('direccion')->placeholder(__('Ingrese la dirección'))->attribute('rows', 3)->required() !!}
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
