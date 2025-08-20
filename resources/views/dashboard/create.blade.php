{!! html()->form('POST', route('store.dashboard'))->attribute('data-validate', 'true')->attribute('novalidate', 'novalidate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! Html::label('title', __('Title'))->class('form-label') !!}
            {!! Html::text('title')->class('form-control')->id('password')->required()->placeholder(__('Enter title')) !!}
        </div>
        <div class="form-group">
            {!! Html::label('size', __('Size'))->class('form-label') !!}
            {!! html()->select('size', config('static.widget_size'))->class('form-select')->required()->attribute('data-trigger', true) !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Type'), 'type')->class('form-label') !!}
            {!! html()->select('type', $p)->class('form-select text-upper')->required()->attribute('data-trigger', true) !!}
            <div class="error-message" id="bouncer-error_type"></div>
        </div>
        <div id="form" class="d-none">
            <div class="form-group">
                {!! html()->label(__('Form Title'), 'form_title')->class('form-label') !!}
                {!! html()->select('form_title', $forms)->class('form-select')->id('form_title')->attribute('data-trigger', true) !!}
            </div>
            <div class="form-group">
                {{ html()->label(__('Field Name'), 'field_name')->class('form-label') }}
                <div class="field_name">
                    {!! html()->select('field_name', [], null)->class('form-control')->attribute('data-trigger') !!}
                </div>
            </div>
        </div>
        <div id="poll" class="d-none">
            <div class="form-group">
                {!! html()->label(__('Poll Title'), 'poll_title')->class('form-label') !!}
                {!! html()->select('poll_title', $polls)->class('form-select')->id('poll_title')->attribute('data-trigger', true) !!}
            </div>
        </div>
        <div class="form-group">
            {!! html()->label(__('Chart Type'), 'chart_type')->class('form-label') !!}
            {!! html()->select('chart_type', config('static.chart_type'))->class('form-select text-upper')->required()->attribute('data-trigger', true) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
<script>
    $("#type").change(function() {
        $('#poll').hide();
        $('#form').hide();
        var test = $(this).val();
        if (test == 'form') {
            $('#poll').hide();
            $('#form').show();
            $("#form").fadeIn(500);
            $("#form").removeClass('d-none');
            $('#poll').fadeOut(500);
        } else {
            $('#form').hide();
            $('#poll').show();
            $("#form").fadeOut(500);
            $("#poll").fadeIn(500);
            $("#poll").removeClass('d-none');
        }
    });
</script>
