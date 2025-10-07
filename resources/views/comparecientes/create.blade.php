@extends('layouts.main')
@section('title', __('Agregar compareciente'))
@section('content')
    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-6 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <h5>{{ __('Agregar compareciente') }}</h5>
                </div>
                {!! Form::open(['route' => 'comparecientes.store', 'method' => 'Post', 'data-validate' ]) !!}
                <div class="card-body">
                    <div class="row">
                        <div class="mx-auto col-lg-12 col-12">
                            <div class="form-group">
                                {{ Form::label('tramite_id', __('Tramite ID'), ['class' => 'form-label']) }}
                                {!! Form::text('tramite_id', null, ['placeholder' => 'Tramite ID', 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                {!! Form::text('name', null, ['placeholder' => 'Nombre de la plantilla', 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('dni', __('DNI'), ['class' => 'form-label']) }}
                                {!! Form::text('dni', null, ['placeholder' => 'DNI', 'class' => 'form-control', 'required']) !!}
                            </div>
                            {!! Form::hidden('created_by', Auth::id()) !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! Html::link(route('comparecientes.index'), __('Cancel'), ['class'=>'btn btn-secondary']) !!}
                        {{ Form::button(__('Save'),['type' => 'submit','class' => 'btn btn-primary']) }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
