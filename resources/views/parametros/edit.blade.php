{!! html()->modelForm($parametro, 'PUT', route('parametros.update', $parametro->id))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-8">
            <div class="form-group ">
                {!! html()->label(__('Nombre'))->class('col-form-label') !!}
                {!! html()->text('nombre')->class('form-control')->placeholder(__('Enter name'))->required() !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {!! html()->label(__('Valor'))->class('col-form-label') !!}
                {!! html()->text('valor')->class('form-control')->placeholder(__('1.0'))->required() !!}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group ">
                {!! html()->label(__('Descripcion'))->class('col-form-label') !!}
                {!! html()->text('descripcion')->class('form-control')->placeholder(__('Enter description'))->required() !!}
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
{!! html()->closeModelForm() !!}
