@extends('layouts.main')
@section('title', __('Edit Email Template'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Email Template') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('mailtemplate.index'))->text(__('Email Templates')) !!} </li>
            <li class="breadcrumb-item active">{{ __('Edit Email Template') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-6 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <h5>{{ __('Edit Email Template') }}</h5>
                </div>
                {!! html()->modelForm($mailtemplate, 'PUT', route('mailtemplate.update', $mailtemplate->id))->attribute('data-validate')->open() !!}
                <div class="card-body">
                    <div class="row">
                        <div class="mx-auto col-lg-12 col-12">
                            <div class="form-group">
                                {!! html()->label(__('Variables ;'), 'variables', ['class' => 'form-label fw-bolder text-dark fs-6']) !!}
                                @foreach ($mailtemplate->variables as $variables)
                                    <span class="fw-bolder text-dark fs-6">{{ <?php echo $variables; ?> }}</span>
                                @endforeach
                            </div>
                            <div class="form-group">
                                {!! html()->label(__('Mailable'), 'mailable', ['class' => 'form-label']) !!}
                                {!! html()->text('mailable', $mailtemplate->mailable)->placeholder('App\Mail\TestMail')->class('form-control')->attributes(['readonly' => 'readonly']) !!} </div>
                            <div class="form-group">
                                {!! html()->label(__('Subject'), 'subject', ['class' => 'form-label']) !!}
                                {!! html()->text('subject', $mailtemplate->subject)->placeholder('readonly')->class('form-control') !!}
                            </div>
                            <div class="form-group">
                                {!! html()->label(__('Html Template'), 'html_template', ['class' => 'form-label']) !!}
                                {!! html()->textarea('html_template', $mailtemplate->html_template)->placeholder('')->class('form-control') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        {!! html()->a(route('mailtemplate.index'), __('Cancel'))->class('btn btn-secondary') !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->closeModelForm() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('html_template', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
