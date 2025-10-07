@extends('layouts.main')
@section('title', __('Contratos'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Contratos') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active">{{ __('Contratos') }} </li>
        </ul>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-bottom justify-content-between">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <ul class="nav nav-pills mb-2" id="pills-tab" role="tablist">
                            @php
                                $view = request()->query->get('view');
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link   {{ $view != 'trash' ? 'active' : '' }}"
                                    href="{{ route('contratos.index') }}">{{ __('Todos') }} <span
                                        class="badge ms-1 {{ isset($view) ? 'bg-primary text-light' : 'bg-light text-primary' }}">{{ isset($totContratos) ? $totContratos : 0 }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $view == 'trash' ? 'active' : '' }}"
                                    href="{{ route('contratos.index', 'view=trash') }}">{{ __('Borrados') }}
                                    <span
                                        class="badge ms-1 {{ isset($view) ? 'bg-light text-primary' : 'bg-primary text-light' }}">{{ isset($trashContratos) ? $trashContratos : 0 }}</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">

                    </div>

                    <div class="col-lg-3 col-md-3 text-end">
                        @if ($view !== null && $view == 'trash')
                            <a class="deleteAll btn btn-danger btn-lg text-white" tabindex="0" aria-controls="user-table"
                                type="button"><span><i class="fa fa-trash me-1 text-md"></i>{{ __('Vaciar Papelera') }}</span></a>
                        @endif
                    </div>
                </div>

            </div>

            <div class="card-body table-border-style">
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
    @include('layouts.includes.datatable-css')
@endpush
@push('script')
    @include('layouts.includes.datatable-js')
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

    {{ $dataTable->scripts() }}

    <script>

        $(document).ready(function() {

            var multipleCancelButton = new Choices(
                '#choices-multiple-remove-button', {
                    removeItemButton: true,
                }
            );
            document.addEventListener('DOMContentLoaded', function() {
                var genericExamples = document.querySelectorAll('[data-trigger]');
                for (i = 0; i < genericExamples.length; ++i) {
                    var element = genericExamples[i];
                    new Choices(element, {
                        placeholderValue: 'This is a placeholder set in the config',
                        searchPlaceholderValue: 'This is a search placeholder',
                    });
                }
            });


            $(document).on('change', 'input[name="checkbox-all"]', function() {
                var isChecked = $(this).prop('checked');
                $('.selected-checkbox').prop('checked', isChecked).trigger('change');
            });

            $(document).on('click', '.deleteAll', function(e) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "If you delete this form, all the submitted forms will also be deleted. Do you want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('form.force.delete.all') }}',
                            data: {
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                show_toastr('Success!', response.msg,
                                    'success');
                                window.location.reload();
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush


