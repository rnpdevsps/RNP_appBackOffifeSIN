{!! html()->modelForm($apikey, 'PUT', route('apikey.update', $apikey->id))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-8">
            <div class="form-group ">
                {!! html()->label(__('Nombre App'))->class('col-form-label') !!}
                {!! html()->text('app_name')->class('form-control')->placeholder(__('Enter name'))->required() !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {!! html()->label($diasDisponible)->class('col-form-label') !!}
                {!! html()->number('expira', $dias)->class('form-control')->placeholder(__('365'))->required() !!}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group ">
                {!! html()->label(__('ApiKey'))->class('col-form-label') !!}
                {!! html()->text('api_key')->class('form-control')->attribute('readonly')->placeholder(__('apikey'))->required() !!}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {!! html()->label(__('Asignar Permisos'))->class('col-form-label d-block') !!}

                @php
                    $permisosAsignados = $apikey->permissions ?? [];
                    $chunks = array_chunk($permisosDisponibles, ceil(count($permisosDisponibles) / 3));
                @endphp

                <div class="row">
                    @foreach($chunks as $col)
                        <div class="col-md-4">
                            @foreach($col as $permiso)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permiso }}" 
                                        class="form-check-input" id="permiso_{{ md5($permiso) }}"
                                        {{ in_array($permiso, $permisosAsignados) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permiso_{{ md5($permiso) }}">
                                        {{ $permiso }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
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
