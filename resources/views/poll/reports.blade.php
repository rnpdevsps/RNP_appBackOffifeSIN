{!! Form::open([
    'route' => 'reportElectoral',
    'method' => 'Post',
    'data-validate',
    'target' => '_blank'
]) !!}

<div class="modal-body">
    <div class="row">
        {!! Form::hidden('idVotacion', $poll->id, ['class' => 'form-control','required',]) !!}

        <div class="col-lg-7" id="archivoPDF">
            <div class="form-group">
                {{ Form::label('typePDF', __('Tipo de Reporte:'), ['class' => 'form-label']) }}
                <br>

                {!! Form::radio('typePDF', 'puesto', true, [
                    'class' => 'btn-check',
                    'id' => 'puesto',
                ]) !!}
                {{ Form::label('puesto', __('Cargo'), ['class' => 'btn btn-outline-primary']) }}


                {!! Form::radio('typePDF', 'candidatos', false, [
                    'class' => 'btn-check',
                    'id' => 'candidatos',
                ]) !!}
                {{ Form::label('candidatos', __('candidatos'), ['class' => 'btn btn-outline-primary']) }}
            </div>
        </div>

        <div class="col-lg-7 d-none">

            <div class="form-group">
                {{ Form::label('fileType', __('Formato a Exportar:'), ['class' => 'col-form-label']) }}
                <br>

                {!! Form::radio('fileType', 'pdf', true, [
                    'class' => 'btn-check',
                    'id' => 'pdf',
                    'onclick' => "mostrarPDF(1);",
                ]) !!}
                {{ Form::label('pdf', __('PDF'), ['class' => 'btn btn-outline-primary']) }}

            </div>
        </div>
        <div class="col-lg-5" id="archivoPDF">
            <div class="form-group">
                {{ Form::label('fileType', __('Visualizar PDF:'), ['class' => 'form-label']) }}
                <br>

                {!! Form::radio('visualizarPDF', 'si', true, [
                    'class' => 'btn-check',
                    'id' => 'si',
                ]) !!}
                {{ Form::label('si', __('SI'), ['class' => 'btn btn-outline-warning']) }}


                {!! Form::radio('visualizarPDF', 'no', false, [
                    'class' => 'btn-check',
                    'id' => 'no',
                ]) !!}
                {{ Form::label('no', __('NO'), ['class' => 'btn btn-outline-warning']) }}
            </div>
        </div>

    </div>


</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Generar Reporte'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}