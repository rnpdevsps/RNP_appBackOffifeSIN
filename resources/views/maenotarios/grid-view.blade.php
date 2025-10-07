@extends('layouts.main')
@section('title', __('MAE Notarios'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('View') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active">{{ __('View') }}</li>
        </ul>

        <div class="float-end">
            <div class="d-flex align-items-center">
                <a href="{{ route('maenotariosgrid.view') }}" data-bs-toggle="tooltip" title="{{ __('List View') }}"
                    class="btn btn-sm btn-primary" data-bs-placement="bottom">
                    <i class="ti ti-list"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                @foreach ($maenotarios as $maenotario)
                    <div class="col-xl-3 col-lg-4 col-me-6 col-sm-6 col-12 d-flex">
                        <div class="text-center text-white card w-100 h-100">
                            <div class="pb-0 border-0 card-header">
                                <div class="d-flex align-items-center">
                                    <span class="p-2 px-3 rounded badge bg-primary">{{ $maenotario->status }}</span>
                                </div>
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('edit-user')
                                                <a class="dropdown-item" href="javascript:void(0);" id="edit-maenotario"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    data-bs-original-title="{{ __('Edit') }}"
                                                    data-url="{{ route('maenotarios.edit', $maenotario->id) }}"><i class="ti ti-edit"></i>
                                                    <span>{{ __('Edit') }}</span></a>
                                            @endcan



                                            @can('delete-user')
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['maenotarios.destroy', $maenotario->id],
                                                    'id' => 'delete-form-' . $maenotario->id,
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <a href="#" class="dropdown-item show_confirm"
                                                    id="delete-form-{{ $maenotario->id }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                                                        class="mr-0 ti ti-trash"></i><span>Delete</span></a>
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <img src="{{ Storage::exists(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : Auth::user()->avatar_image }}"
                                    alt="user-image" width="100px" class="rounded-circle">
                                <h4 class="mt-2 text-dark">{{ $maenotario->name }}</h4>
                                <small class="text-dark">{{ $maenotario->phone }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-xl-3 col-lg-4 col-me-6 col-sm-6 col-12 d-flex create-grid-user">
                    <a class="btn-addnew-project h-100 w-100 add_maenotario">
                        <div class="bg-primary add_maenotario proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                        <p class="text-center text-muted">{{ __('Click here to add new User') }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        $(function() {
            $('body').on('click', '.add_maenotario', function() {
                var modal = $('#common_modal');
                $.ajax({
                    type: "GET",
                    url: '{{ route('maenotarios.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Crear MAE Notario') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });

            $('body').on('click', '#edit-maenotario', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Editar MAE Notario') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
