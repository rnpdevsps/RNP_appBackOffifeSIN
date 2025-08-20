{!! html()->modelForm($dashboard, 'PUT', route('update.dashboard', $dashboard->id))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group ">
            {!! html()->label(__('Title'), 'title')->class('form-label') !!}
            {!! html()->text('title')->class('form-control')->id('password')->required()->placeholder(__('Enter title')) !!}
        </div>
        <div class="form-group ">
            {!! html()->label(__('Size'), 'size')->class('form-label') !!}
            {!! html()->select('size', config('static.widget_size'), round($dashboard->size))->class('form-select')->attribute('data-trigger')->required() !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Type'), 'type')->class('form-label') !!}
            {!! html()->text('type', $dashboard->type)->class('form-control')->required()->attribute('readonly', 'readonly') !!}
        </div>
        <div id="form" class="{{ $dashboard->type == 'form' ? 'd-block' : 'd-none' }}">
            <div class="form-group ">
                {!! html()->label(__('Form Title'), 'form_title')->class('form-label') !!}
                {!! html()->select('form_title', $forms, $dashboard->form_id)->class('form-select')->attribute('data-trigger')->id('form_title') !!}
            </div>
            <div class="form-group ">
                {!! html()->label(__('Field Name'), 'field_name')->class('form-label') !!}
                <div class="field_name">
                    {!! html()->select('field_name', $label, $dashboard->field_name)->class('form-control')->attribute('data-trigger') !!}
                    <div class="invalid-feedback">
                        {{ __('Sel Inter is required') }}
                    </div>
                </div>
            </div>
        </div>
        <div id="poll" class="{{ $dashboard->type == 'poll' ? 'd-block' : 'd-none' }}">
            <div class="form-group">
                {!! html()->label(__('Poll Title'), 'poll_title')->class('form-label') !!}
                {!! html()->select('poll_title', $polls, $dashboard->poll_id)->class('form-select')->attribute('data-trigger')->id('poll_title') !!}
            </div>
        </div>
        <div class="form-group ">
            {!! html()->label(__('Chart Type'), 'chart_type')->class('form-label') !!}
            {!! html()->select('chart_type', config('static.chart_type'), $dashboard->chart_type)->class('form-select')->attribute('data-trigger')->required() !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->closeModelForm() !!}
<script>
    $("#type").change(function() {
        var test = $(this).val();
        if (test == 'form') {
            $("#form").fadeIn(500);
            $("#form").removeClass('d-none');
            $('#poll').fadeOut(500);
            $("#poll").addClass('d-none');
        } else {
            $("#form").fadeOut(500);
            $("#poll").fadeIn(500);
            $("#poll").removeClass('d-none');
            $("#form").addClass('d-none');
        }
    });
</script>
