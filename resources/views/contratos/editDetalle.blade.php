{!! Form::open([
    'route' => ['updateDetalleContrato'],
    'method' => 'POST',
    'data-validate',
    'id' => 'form_dataC',
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data',
]) !!}

<script>

    function calcula_valores(val) {

        let metros2 = parseFloat($('#metros2').val());
        if (isNaN(metros2)) {
            metros2 = 0;
        }

        if (val == 1) {
            let valorm = parseFloat($('#valor_mensualModal').val());
            if (isNaN(valorm)) {
                valorm = 0;
            }

            $('#valor_metros_detalleModal').val(valorm/metros2);

        } else {
            let valor_metros_detalle = parseFloat($('#valor_metros_detalleModal').val());
            if (isNaN(valor_metros_detalle)) {
                valor_metros_detalle = 0;
            }

            $('#valor_mensualModal').val(metros2*valor_metros_detalle);
        }

        let valor_mensual = parseFloat($('#valor_mensualModal').val());




        const fecha_inicio = document.getElementById("fecha_inicioModal").value;
        const fecha_final = document.getElementById("fecha_finalModal").value;

        const fechaInicio = new Date(fecha_inicio);
        const fechaFinal = new Date(fecha_final);

        let yearDiff = fechaFinal.getFullYear() - fechaInicio.getFullYear();
        let monthDiff = fechaFinal.getMonth() - fechaInicio.getMonth();
        let dayDiff = fechaFinal.getDate() - fechaInicio.getDate();

        // Calcular el total de meses sin considerar los días aún
        let totalMonths = yearDiff * 12 + monthDiff;

        // Si hay una diferencia en días, incrementar el total de meses en 1
        if (dayDiff > 0) {
            totalMonths += 1;
        }

        // Asegurarse de que totalMonths no sea negativo si la fecha final es antes de la fecha de inicio
        totalMonths = totalMonths < 0 ? 0 : totalMonths;


        $('#no_mesesModal').val(totalMonths);
        if (isNaN(valor_mensual)) {
            valor_mensual = 0;
        }

        let valor_total = valor_mensual*totalMonths;
        $('#valor_totalModal').val(valor_total);

        console.log("Total de meses:", totalMonths);

    }

</script>






<div class="modal-body">
    {!! Form::hidden('id_detalle', $detalleContrato->id, ['class' => 'form-control']) !!}

    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('anio_detalle', __('Año'), ['class' => 'form-label']) }}
                {!! Form::text('anio_detalle', $detalleContrato->anio, [
                    'class' => 'form-control',
                    'required',
                    'id' => 'anio_detalle',
                    'placeholder' => __('2024'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>


        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('fecha_inicio', __('Fecha Inicio'), ['class' => 'form-label']) }}
                {!! Form::date('fecha_inicio', $fecha_inicio, [
                    'class' => 'form-control',
                    'required',
                    'id' => 'fecha_inicioModal',
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('fecha_final', __('Fecha Final'), ['class' => 'form-label']) }}
                {!! Form::date('fecha_final', $fecha_final, [
                    'class' => 'form-control',
                    'required',
                    'id' => 'fecha_finalModal',
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('valor_metros_detalle', __('Valor Metros2'), ['class' => 'form-label']) }}
                {!! Form::text('valor_metros_detalle', $detalleContrato->valor_metros2, [
                    'class' => 'form-control',
                    'required',
                    'id' => 'valor_metros_detalleModal',
                    'onkeyup' => "calcula_valores(0);",
                    'placeholder' => __('25000'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('valor_mensual', __('Valor Mensual'), ['class' => 'form-label']) }}
                {!! Form::text('valor_mensual', $detalleContrato->valor_mensual, [
                    'class' => 'form-control',
                    'required',
                    'id' => 'valor_mensualModal',
                    'onkeyup' => "calcula_valores(1);",
                    'placeholder' => __('10000'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                {{ Form::label('no_meses', __('No. Meses'), ['class' => 'form-label']) }}
                {!! Form::text('no_meses', $detalleContrato->no_meses, [
                    'class' => 'form-control',
                    'readonly',
                    'id' => 'no_mesesModal',
                    'placeholder' => __('12'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                {{ Form::label('valor_total', __('Total'), ['class' => 'form-label']) }}
                {!! Form::text('valor_total', $detalleContrato->valor_total, [
                    'class' => 'form-control',
                    'readonly',
                    'id' => 'valor_totalModal',
                    'placeholder' => __('25000'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group ">
                {{ Form::label('moneda', __('Moneda'), ['class' => 'form-label']) }}
                <div class="input-group">
                    <select name="moneda" class="form-select">
                        <option value="1" @if ($detalleContrato->moneda == 1) selected @endif>Lempiras</option>
                        <option value="2" @if ($detalleContrato->moneda == 2) selected @endif>Dolar</option>
                    </select>
                </div>
                <span class="error-message"></span>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                {{ Form::label('costo_dicional', __('Costo Adicional'), ['class' => 'form-label']) }}
                {!! Form::text('costo_dicional', $detalleContrato->costo_dicional, [
                    'class' => 'form-control',
                    'id' => 'costo_dicional',
                    'placeholder' => __('500'),
                ]) !!}
                <span class="error-message"></span>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('observaciones_det', __('Observaciones'), ['class' => 'form-label']) }}
                {!! Form::textarea('observaciones_det', $detalleContrato->observaciones_det, ['id' => 'observaciones_det', 'placeholder' => 'Escribe un comentario...', 'class' => 'form-control', 'rows' => 3]) !!}
                <span class="error-message"></span>
            </div>
        </div>
    </div>


    <div class="col-md-12">

        @if (isset($detalleContrato->adjunto) && $detalleContrato->adjunto !== '')
            <a href="{{ Storage::url('Contratos/'.$detalleContrato->adjunto) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
                data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Ver Adjunto') }}" target="_blank">
                <i class="ti ti-download"></i>Ver Adjunto
            </a>
        @endif

        <div class="form-group">
            {{ Form::label('adjunto', __('Adjuntar Documento'), ['class' => 'form-label']) }} *
            <small class="text-end d-flex mt-2">{{ __('Adjunte un archivo PDF') }}</small>
            {!! Form::file('adjunto', ['class' => 'form-control', 'id' => 'adjunto',]) !!}
        </div>
    </div>


</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <a class="btn btn-lg btn-primary rigth" id="submitDataC"  >
            <span class="btn-inner--icon" style="color: white;"><i class="ti ti-send"></i> Guardar</span>
        </a>
    </div>
</div>
{!! Form::close() !!}



