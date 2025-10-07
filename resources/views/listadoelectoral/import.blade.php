{!! Form::open([
    'route' => 'listadoelectoral.storeListadoElectoral',
    'method' => 'Post',
    'data-validate',
    'enctype' => 'multipart/form-data',
]) !!}

<div class="modal-body">
    <div class="row">
    	<div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('periodo', __('Periodo'), ['class' => 'col-form-label']) }}
                {!! Form::select('periodo', array_combine(range(2025, 2035), range(2025, 2035)), null, [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => 'Seleccione un periodo'
                ]) !!}
            </div>
        </div>


        <div class="col-lg-6"><br><br>
            <a target="_blank" href="{{  Storage::url('Plantilla_Listado_Electoral.xlsx') }}" class="btn btn-success" download>
                <i class="fas fa-file-download"></i> Descargar Plantilla
            </a>
    	</div>

   


        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('excel', __('Archivo Excel'), ['class' => 'col-form-label']) }} *
                {!! Form::file('excel', [
                    'class' => 'form-control',
                    'required' => 'required',
                    'accept' => '.xlsx,.xls' // ✅ Restringe el tipo de archivo
                ]) !!}
                <small class="form-text text-muted">
                    {{ __('Extensión: .xlsx, .xls (Máx: 5 MB)') }}
                </small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="float-end" >
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}

@push('script')
    


@endpush