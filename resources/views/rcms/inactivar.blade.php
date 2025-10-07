{!! Form::open(['route' => 'ChangeStatusRcm', 'method' => 'Post', 'data-validate','enctype' => 'multipart/form-data']) !!}

<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />

<script src="{{ asset('vendor/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    $(function() {
        $('input[name="set_end_date_time"]').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            showDropdowns: true,
            minYear: 2000,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });
    });
    $(document).on('click', "input[name$='set_end_date']", function() {
        if (this.checked) {
            $('#set_end_date').fadeIn(500);
            $("#set_end_date").removeClass('d-none');
            $("#set_end_date").addClass('d-block');
        } else {
            $('#set_end_date').fadeOut(500);
            $("#set_end_date").removeClass('d-block');
            $("#set_end_date").addClass('d-none');
        }
    });

</script>

<div class="modal-body">


<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::hidden('rcm_id', $rcm->id, ['class' => 'form-control']) !!}

            {{ Form::label('estadoinhabilitado_id', __('Motivo de Inactivación'), ['class' => 'col-form-label']) }}
            {!! Form::select('estadoinhabilitado_id', $estados, null, ['required' => 'required', 'class' => 'form-select', 'id' => 'estadoinhabilitado_id']) !!}
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            {{ Form::label('set_end_date', __('Set end date'), ['class' => 'col-form-label']) }}
            <label class="mt-2 form-switch float-end custom-switch-v1">
                <input type="hidden" name="set_end_date" value="0">
                <input type="checkbox" name="set_end_date" id="m_set_end_date" class="form-check-input input-primary" {{ 'unchecked' }} value="1">
            </label>
        </div>
        <div id="set_end_date" class="{{ 'd-none' }}">
            <div class="form-group">
                <input class="form-control" name="set_end_date_time" id="set_end_date_time">
            </div>
        </div>
    </div>

</div>

    <div class="row">

    	<div class="col-md-12">
            <div class="form-group">
                {{ Form::label('observaciones', __('Observaciones'), ['class' => 'form-label']) }} *
                {!! Form::textarea('observaciones', null, [
                    'class' => 'form-control ',
                    'placeholder' => __('Ingrese el motivo de Inactivación'),
                    'rows' => '4',
                    'required' => 'required',
                ]) !!}
            </div>
        </div>

    </div>



</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}
