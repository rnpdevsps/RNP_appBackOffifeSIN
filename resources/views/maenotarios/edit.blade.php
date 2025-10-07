{!! Form::model($maenotario, [
    'route' => ['maenotarios.update', $maenotario->id],
    'method' => 'Put',
    'data-validate',
]) !!}
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

    });

</script>
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('dni', __('DNI'), ['class' => 'col-form-label']) }}
            {!! Form::text('dni', null, ['class' => 'form-control', 'id' => 'dni', 'maxlength' => '15', 'required', 'placeholder' => __('0501-0000-00000')]) !!}
            <p id="errorDNI" style="display: none;"><strong style="color: #d70b0b;">DNI ya existe.</strong></p>
            <div class="wizard-form-success"></div>
        </div>

        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('Enter name')]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">

            <div class="form-group">
                {{ Form::label('country_code', __('Country Code'), ['class' => 'd-block form-label']) }}
                <select id="country_code" name="country_code"class="form-control" data-trigger>
                    @foreach (\App\Core\Data::getCountriesList() as $key => $value)
                        <option data-kt-flag="{{ $value['flag'] }}"
                            {{ $value['phone_code'] == $maenotario->country_code ? 'selected' : '' }} value="{{ $key }}">
                            +{{ $value['phone_code'] }} {{ $value['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('phone', __('Phone Number'), ['class' => 'form-label']) }}
                {!! Form::number('phone', null, [
                    'autofocus' => '',
                    'required' => true,
                    'autocomplete' => 'off',
                    'placeholder' => 'Enter phone Number',
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>
    </div>





</div>
<div class="modal-footer" id="btnAddMAENotario">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}
