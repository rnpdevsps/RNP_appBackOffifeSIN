{!! Form::open([
    'route' => ['updatetemplate'],
    'method' => 'POST',
    'data-validate',
    'id' => 'form_dataC',
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data',
]) !!}

<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('content');
    CKEDITOR.config.height='400px';
</script>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! Form::hidden('template_id', $plantilla->id) !!}
            {!! Form::textarea('content', ( $plantilla->id == Session::get('template_id')) ? Session::get('template') : $plantilla->content, ['id' => 'content', 'class' => 'form-control', 'rows' => 6]) !!}
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <a href="#" class="btn btn-lg btn-primary rigth" id="submitDataC"  >
            <span class="btn-inner--icon"><i class="ti ti-send"></i> Guardar</span>
        </a>
    </div>
</div>
{!! Form::close() !!}
