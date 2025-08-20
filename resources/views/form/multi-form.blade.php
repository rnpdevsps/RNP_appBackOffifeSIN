@php
    use App\Facades\UtilityFacades;
    use App\Models\Role;
    use App\Models\AssignFormsRoles;
    use App\Models\AssignFormsUsers;
@endphp
@php
    $hashids = new Hashids('', 20);
    $id = $hashids->encodeHex($form->id);
    $user = \Auth::user();
    $data = null;
    $type = '';
    if ($user) {
        $roleId = Role::where('name', $user->getRoleNames())->first();
        $type = $user->type;
        if ($form->assign_type == 'role') {
            $data = AssignFormsRoles::where('form_id', $form->id)
                ->where('role_id', $roleId->id)
                ->first();
        } elseif ($form->assign_type == 'user') {
            $data = AssignFormsUsers::where('form_id', $form->id)
                ->where('user_id', $user->id)
                ->first();
        } elseif ($form->assign_type == 'public') {
            $data = 'public';
        }
    }
@endphp
@if ($data !== null || $type == 'Admin' || $type == 'public' || \Auth::user()->can('access-all-form'))
    <div class="section-body">
        <div class="mx-0 mt-5 row">
            <div class="mx-auto col-md-7">
                @if (!empty($form->logo))
                    <div class="mb-2 text-center gallery gallery-md">
                        <img id="app-dark-logo" class="float-none gallery-item"
                            src="{{ isset($form->logo) ? Storage::url($form->logo) : Storage::url('/not-exists-data-images/78x78.png') }}">
                    </div>
                @endif
                @if ($form->enable_session_timer == 1)
                    <div class="my-2 session-timer">
                        <div class="time-unit bg-primary">
                            <span class="hours">00</span>
                            <div class="label">{{ __('Hours') }}</div>
                        </div>
                        <div class="time-unit bg-primary">
                            <span class="minutes">00</span>
                            <div class="label">{{ __('Minutes') }}</div>
                        </div>
                        <div class="time-unit bg-primary">
                            <span class="seconds">00</span>
                            <div class="label">{{ __('Seconds') }}</div>
                        </div>
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-center w-100">{{ $form->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center gallery" id="success_loader">
                                <img src="{{ asset('assets/images/success.gif') }}" />
                                <br>
                                <br>
                                <h2 class="w-100 ">{{ session()->get('success') }}</h2>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        @php
                            $formRules = App\Models\formRule::where('form_id', $form->id)->get();
                        @endphp
                        <div class="card-header">
                            <h5 class="text-center w-100">{{ $form->title }}</h5>
                        </div>
                        <div class="card-body form-card-body">
                            <form action="{{ route('forms.fill.store', $form->id) }}" method="POST"
                                enctype="multipart/form-data" id="fill-form">
                                @method('PUT')
                                @csrf
                                @if (isset($array))
                                    @foreach ($array as $keys => $rows)
                                        <div class="tab">
                                            <div class="row">
                                                @foreach ($rows as $row_key => $row)
                                                    @php
                                                        if (isset($row->column)) {
                                                            if ($row->column == 1) {
                                                                $col = 'col-12 step-' . $keys;
                                                            } elseif ($row->column == 2) {
                                                                $col = 'col-6 step-' . $keys;
                                                            } elseif ($row->column == 3) {
                                                                $col = 'col-4 step-' . $keys;
                                                            }
                                                        } else {
                                                            $col = 'col-12 step-' . $keys;
                                                        }
                                                    @endphp
                                                    @if ($row->type == 'checkbox-group')
                                                        <div class="form-group {{ $col }}"
                                                            data-name="{{ $row->name }}">
                                                            <label for="{{ $row->name }}"
                                                                class="d-block form-label">{{ $row->label }}
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                @if (isset($row->description))
                                                                    <span type="button" class="tooltip-element"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ $row->description }}">
                                                                        ?
                                                                    </span>
                                                                @endif
                                                            </label>
                                                            @foreach ($row->values as $key => $options)
                                                                @php
                                                                    $attr = [
                                                                        'class' => 'form-check-input',
                                                                        'id' => $row->name . '_' . $key,
                                                                    ];
                                                                    $attr['name'] = $row->name . '[]';
                                                                    if ($row->required) {
                                                                        $attr['required'] = 'required';
                                                                        $attr['class'] = $attr['class'] . ' required';
                                                                    }
                                                                    if ($row->inline) {
                                                                        $class = 'form-check form-check-inline col-4 ';
                                                                        if ($row->required) {
                                                                            $attr['class'] =
                                                                                'form-check-input required';
                                                                        } else {
                                                                            $attr['class'] = 'form-check-input';
                                                                        }
                                                                        $l_class = 'form-check-label mb-0 ml-1';
                                                                    } else {
                                                                        $class = 'form-check';
                                                                        if ($row->required) {
                                                                            $attr['class'] =
                                                                                'form-check-input required';
                                                                        } else {
                                                                            $attr['class'] = 'form-check-input';
                                                                        }
                                                                        $l_class = 'form-check-label';
                                                                    }
                                                                @endphp
                                                                <div class="{{ $class }}">
                                                                    {{ html()->checkbox($row->name, isset($options->selected) && $options->selected == 1 ? true : false, $options->value)->attributes($attr) }}
                                                                    <label class="{{ $l_class }}"
                                                                        for="{{ $row->name . '_' . $key }}">{{ $options->label }}</label>
                                                                </div>
                                                            @endforeach
                                                            @if ($row->required)
                                                                <div class=" error-message required-checkbox"></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'file')
                                                        @php
                                                            $attr = [];
                                                            $attr['class'] = 'form-control upload';
                                                            if ($row->multiple) {
                                                                $maxupload = 10;
                                                                $attr['multiple'] = 'true';
                                                                if ($row->subtype != 'fineuploader') {
                                                                    $attr['name'] = $row->name . '[]';
                                                                }
                                                            }
                                                            if (
                                                                $row->required &&
                                                                (!isset($row->value) || empty($row->value))
                                                            ) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            if ($row->subtype == 'fineuploader') {
                                                                $attr['class'] = $attr['class'] . ' ' . $row->name;
                                                            }
                                                        @endphp
                                                        <div class="form-group {{ $col }}"
                                                            data-name="{{ $row->name }}">
                                                            <label class="form-label"
                                                                for="{{ $row->name }}">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            @if ($row->subtype == 'fineuploader')
                                                                <div class="dropzone" id="{{ $row->name }}"
                                                                    data-extention="{{ $row->file_extention }}">
                                                                </div>
                                                                @include('form.js.dropzone')
                                                                {!! html()->hidden($row->name, null)->attributes($attr) !!}
                                                            @else
                                                                {!! html()->file($row->name)->attributes($attr) !!}
                                                            @endif
                                                            @if ($row->required)
                                                                <div class="error-message required-file"></div>
                                                            @endif
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <p>
                                                                    @if (property_exists($row, 'value'))
                                                                        @if ($row->value)
                                                                            @php
                                                                                $allowedExtensions = ['pdf','pdfa','fdf','xdp','xfa','pdx','pdp','pdfxml','pdxox','xlsx','csv','xlsm','xltx','xlsb','xltm','xlw',];
                                                                            @endphp
                                                                            @if ($row->multiple)
                                                                                <div class="row" id="attachments-{{ $row->name }}">
                                                                                    @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                                        @foreach ($row->value as $img)
                                                                                            @php
                                                                                                $fileName = basename($img);
                                                                                                $fileExtension = pathinfo($img, PATHINFO_EXTENSION);
                                                                                                $isImage = !in_array($fileExtension, $allowedExtensions);
                                                                                                $iconClass = in_array($fileExtension, ['pdf', 'pdfa', 'fdf', 'xdp', 'xfa', 'pdx', 'pdp', 'pdfxml', 'pdxox']) ? 'fa-file-pdf text-danger' :
                                                                                                            (in_array($fileExtension, ['xls', 'xlsx', 'csv', 'xlsm', 'xltx', 'xlsb', 'xltm', 'xlw']) ? 'fa-file-excel text-success' : 'fa-file-alt');
                                                                                            @endphp
                                                                                            <div class="col-6 position-relative">
                                                                                                @if ($isImage)
                                                                                                    <img src="{{ Storage::exists($img) ? asset('storage/app/' . $img) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                                        class="mb-2 img-thumbnail" style="width: 100%; height: auto;">
                                                                                                @else
                                                                                                    <div class="mb-2 text-center file-placeholder" style="border: 1px solid #ddd; padding: 20px;">
                                                                                                        <i class="fas {{ $iconClass }} fa-3x"></i>
                                                                                                        <p class="mt-2" style="font-size: 0.9em;">{{ Str::limit($fileName, 20) }}</p>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <a href="javascript:void(0)" id="deleteAttachment" data-id="{{ Crypt::encryptString($img) }}"
                                                                                                class="delete-icon position-absolute" style="top: -8px; right: 5px;">
                                                                                                    <i class="fas fa-times-circle fa-lg text-danger"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @else
                                                                                        @foreach ($row->value as $img)
                                                                                            <div class="col-6">
                                                                                                @php
                                                                                                    $fileName = explode('/', $img);
                                                                                                    $fileName = end($fileName);
                                                                                                @endphp
                                                                                                @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                    <a class="my-2 btn btn-info"
                                                                                                        href="{{ Storage::url($img) }}"
                                                                                                        type="image"
                                                                                                        download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                                @else
                                                                                                    <img src="{{ Storage::url($img) }}"
                                                                                                        class="mb-2 img-responsive img-thumbnail">
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            @else
                                                                                <div class="row">
                                                                                    <div class="col-6">
                                                                                        @if ($row->subtype == 'fineuploader')
                                                                                            @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                                                @if ($row->value[0])
                                                                                                    @foreach ($row->value as $img)
                                                                                                        @php
                                                                                                            $fileName = explode('/',$img);
                                                                                                            $fileName = end($fileName);
                                                                                                        @endphp
                                                                                                        @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                            <a class="my-2 btn btn-info"
                                                                                                                href="{{ asset('storage/app/' . $img) }}"
                                                                                                                type="image"
                                                                                                                download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                                        @else
                                                                                                            <img src="{{ Storage::exists($img) ? asset('storage/app/' . $img) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                                                class="mb-2 img-responsive img-thumbnail">
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            @else
                                                                                                @if ($row->value[0])
                                                                                                    @foreach ($row->value as $img)
                                                                                                        @php
                                                                                                            $fileName = explode('/',$img);
                                                                                                            $fileName = end($fileName);
                                                                                                        @endphp
                                                                                                        @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                            <a class="my-2 btn btn-info"
                                                                                                                href="{{ Storage::url($img) }}"
                                                                                                                type="image"
                                                                                                                download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                                        @else
                                                                                                            <img src="{{ Storage::url($img) }}"
                                                                                                                class="mb-2 img-responsive img-thumbnail">
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            @endif
                                                                                        @else
                                                                                            @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                                                @if (in_array(pathinfo($row->value, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                    @php
                                                                                                        $fileName = explode('/',$row->value);
                                                                                                        $fileName = end($fileName);
                                                                                                    @endphp
                                                                                                    <a class="my-2 btn btn-info"
                                                                                                        href="{{ asset('storage/app/' . $row->value) }}"
                                                                                                        type="image"
                                                                                                        download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                                @else
                                                                                                    <img src="{{ Storage::exists($row->value) ? asset('storage/app/' . $row->value) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                                        class="mb-2 img-responsive img-thumbnailss">
                                                                                                @endif
                                                                                            @else
                                                                                                @if (in_array(pathinfo($row->value, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                    @php
                                                                                                        $fileName = explode(
                                                                                                            '/',
                                                                                                            $row->value,
                                                                                                        );
                                                                                                        $fileName = end($fileName);
                                                                                                    @endphp
                                                                                                    <a class="my-2 btn btn-info"
                                                                                                        href="{{ Storage::url($row->value) }}"
                                                                                                        type="image"
                                                                                                        download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                                @else
                                                                                                    <img src="{{ Storage::url($row->value) }}"
                                                                                                        class="mb-2 img-responsive img-thumbnailss">
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif($row->type == 'header')
                                                        @php
                                                            $class = '';
                                                            if (isset($row->className)) {
                                                                $class = $class . ' ' . $row->className;
                                                            }
                                                        @endphp
                                                        <div class="{{ $col }}">
                                                            <{{ $row->subtype }} class="{{ $class }}">
                                                                {{ html_entity_decode($row->label) }}
                                                                </{{ $row->subtype }}>
                                                        </div>
                                                    @elseif($row->type == 'paragraph')
                                                        @php
                                                            $class = '';
                                                            if (isset($row->className)) {
                                                                $class = $class . ' ' . $row->className;
                                                            }
                                                        @endphp
                                                        <div class="{{ $col }}">
                                                            <{{ $row->subtype }} class="{{ $class }}">
                                                                {{ html_entity_decode($row->label) }}
                                                                </{{ $row->subtype }}>
                                                        </div>
                                                    @elseif($row->type == 'radio-group')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            <label for="{{ $row->name }}"
                                                                class="d-block form-label">{{ $row->label }}
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                @if (isset($row->description))
                                                                    <span type="button" class="tooltip-element"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ $row->description }}">
                                                                        ?
                                                                    </span>
                                                                @endif
                                                            </label>
                                                            @foreach ($row->values as $key => $options)
                                                                @php
                                                                    if ($row->required) {
                                                                        $attr['required'] = 'required';
                                                                        $attr = [
                                                                            'class' => 'form-check-input required',
                                                                            'required' => 'required',
                                                                            'id' => $row->name . '_' . $key,
                                                                        ];
                                                                    } else {
                                                                        $attr = [
                                                                            'class' => 'form-check-input',
                                                                            'id' => $row->name . '_' . $key,
                                                                        ];
                                                                    }
                                                                    if ($row->inline) {
                                                                        $class = 'form-check form-check-inline ';
                                                                        if ($row->required) {
                                                                            $attr['class'] =
                                                                                'form-check-input required';
                                                                        } else {
                                                                            $attr['class'] = 'form-check-input';
                                                                        }
                                                                        $l_class = 'form-check-label mb-0 ml-1';
                                                                    } else {
                                                                        $class = 'form-check';
                                                                        if ($row->required) {
                                                                            $attr['class'] =
                                                                                'form-check-input required';
                                                                        } else {
                                                                            $attr['class'] = 'form-check-input';
                                                                        }
                                                                        $l_class = 'form-check-label';
                                                                    }
                                                                @endphp
                                                                <div class=" {{ $class }}">
                                                                    {!! html()->radio($row->name, isset($options->selected) && $options->selected ? true : false, $options->value)->attributes($attr) !!}
                                                                    <label class="{{ $l_class }}"
                                                                        for="{{ $row->name . '_' . $key }}">{{ $options->label }}</label>
                                                                </div>
                                                            @endforeach
                                                            @if ($row->required)
                                                                <div class="error-message required-radio "></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'select')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $attr = [
                                                                    'class' => 'form-select w-100',
                                                                    'id' => 'sschoices-multiple-remove-button',
                                                                    'data-trigger',
                                                                ];
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                                if (isset($row->multiple) && !empty($row->multiple)) {
                                                                    $attr['multiple'] = 'true';
                                                                    $attr['name'] = $row->name . '[]';
                                                                }
                                                                if (
                                                                    isset($row->className) &&
                                                                    $row->className == 'calculate'
                                                                ) {
                                                                    $attr['class'] =
                                                                        $attr['class'] . ' ' . $row->className;
                                                                }
                                                                if ($row->label == 'Registration') {
                                                                    $attr['class'] = $attr['class'] . ' registration';
                                                                }
                                                                if (
                                                                    isset($row->is_parent) &&
                                                                    $row->is_parent == 'true'
                                                                ) {
                                                                    $attr['class'] = $attr['class'] . ' parent';
                                                                    $attr['data-number-of-control'] = isset(
                                                                        $row->number_of_control,
                                                                    )
                                                                        ? $row->number_of_control
                                                                        : 1;
                                                                }
                                                                $values = [];
                                                                $selected = [];
                                                                foreach ($row->values as $options) {
                                                                    $values[$options->value] = $options->label;
                                                                    if (
                                                                        isset($options->selected) &&
                                                                        $options->selected
                                                                    ) {
                                                                        $selected[] = $options->value;
                                                                    }
                                                                }
                                                            @endphp
                                                            @if (isset($row->is_parent) && $row->is_parent == 'true')
                                                                <label
                                                                    for="{{ $row->name }}">{{ $row->label }}</label>
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                <div class="input-group">
                                                                    {!! html()->select($row->name, $values, $selected)->attributes($attr) !!}
                                                                </div>
                                                            @else
                                                                {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                @if (isset($row->description))
                                                                    <span type="button" class="tooltip-element"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ $row->description }}">?</span>
                                                                @endif
                                                                {!! html()->select($row->name, $values, $selected)->attributes($attr) !!}
                                                            @endif
                                                            @if ($row->label == 'Registration')
                                                                <span class="text-warning registration-message"></span>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'autocomplete')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            {{-- @include('form.js.autocomplete') --}}
                                                            @php
                                                                $attr = [
                                                                    'class' => 'form-select w-100',
                                                                    'id' => 'sschoices-multiple-remove-button',
                                                                    'data-trigger',
                                                                ];
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                                if (isset($row->multiple) && !empty($row->multiple)) {
                                                                    $attr['multiple'] = 'true';
                                                                    $attr['name'] = $row->name . '[]';
                                                                }
                                                                if (
                                                                    isset($row->className) &&
                                                                    $row->className == 'calculate'
                                                                ) {
                                                                    $attr['class'] =
                                                                        $attr['class'] . ' ' . $row->className;
                                                                }
                                                                if ($row->label == 'Registration') {
                                                                    $attr['class'] = $attr['class'] . ' registration';
                                                                }
                                                                if (
                                                                    isset($row->is_parent) &&
                                                                    $row->is_parent == 'true'
                                                                ) {
                                                                    $attr['class'] = $attr['class'] . ' parent';
                                                                    $attr['data-number-of-control'] = isset(
                                                                        $row->number_of_control,
                                                                    )
                                                                        ? $row->number_of_control
                                                                        : 1;
                                                                }
                                                                $values = [];
                                                                $selected = [];
                                                            @endphp
                                                            <div class="form-group">
                                                                <label for="autocompleteInputZero"
                                                                    class="form-label">{{ $row->label }}</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="{{ $row->label }}"
                                                                    list="list-timezone" name="autocomplete"
                                                                    id="input-datalist">
                                                                <datalist id="list-timezone">
                                                                    @foreach ($row->values as $options)
                                                                        @if (is_object($options) && property_exists($options, 'value'))
                                                                            <option value="{{ $options->value }}">
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </datalist>
                                                            </div>
                                                        </div>
                                                    @elseif($row->type == 'date')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $attr = ['class' => 'form-control'];
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                            @endphp
                                                            <label for="{{ $row->name }}" class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            {!! html()->date($row->name, $row->value ?? null)->attributes($attr) !!}
                                                            @if ($row->required)
                                                                <div class="error-message required-date"></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'hidden')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            <input type="hidden" name="{{ $row->name }}" value="{{ isset($row->value) ? $row->value : '' }}">
                                                        </div>
                                                    @elseif($row->type == 'number')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $row_class = isset($row->className)
                                                                    ? $row->className
                                                                    : '';
                                                                $attr = ['class' => 'number ' . $row_class];
                                                                if (isset($row->min)) {
                                                                    $attr['min'] = $row->min;
                                                                }
                                                                if (isset($row->max)) {
                                                                    $attr['max'] = $row->max;
                                                                }
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required ';
                                                                }
                                                            @endphp
                                                            <label for="{{ $row->name }}" class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            {!! html()->input('number', $row->name, $row->value ?? null)->attributes($attr) !!}
                                                            @if ($row->required)
                                                                <div class="error-message required-number"></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'textarea')
                                                        <div class="form-group {{ $col }} "
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $attr = ['class' => 'form-control text-area-height'];
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                                if (isset($row->rows)) {
                                                                    $attr['rows'] = $row->rows;
                                                                } else {
                                                                    $attr['rows'] = '3';
                                                                }
                                                                if (isset($row->placeholder)) {
                                                                    $attr['placeholder'] = $row->placeholder;
                                                                }
                                                                if ($row->subtype == 'ckeditor') {
                                                                    $attr['class'] = $attr['class'] . ' ck_editor';
                                                                }
                                                            @endphp
                                                            <label for="{{ $row->name }}" class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            {!! html()->textarea($row->name, $row->value ?? null)->attributes($attr) !!}
                                                            @if ($row->required)
                                                                <div class="error-message required-textarea"></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'button')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @if (isset($row->value) && !empty($row->value))
                                                                <a href="{{ $row->value }}" target="_new"
                                                                    class="{{ $row->className }}">{{ __($row->label) }}</a>
                                                            @else
                                                                <button name="{{ $row->name }}" type="{{ $row->subtype }}" class="{{ $row->className }}" id="{{ $row->name }}">{{ __($row->label) }}</button>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'text')
                                                        @php
                                                            $class = '';
                                                            if ($row->subtype == 'text' || $row->subtype == 'email') {
                                                                $class = 'form-group-text';
                                                            }
                                                        @endphp
                                                        <div class="form-group {{ $class }} {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $attr = ['class' => 'form-control ' . $row->subtype];
                                                                if ($row->required) {
                                                                    $attr['required'] = 'required';
                                                                    $attr['class'] = $attr['class'] . ' required';
                                                                }
                                                                if (isset($row->maxlength)) {
                                                                    $attr['max'] = $row->maxlength;
                                                                }
                                                                if (isset($row->placeholder)) {
                                                                    $attr['placeholder'] = $row->placeholder;
                                                                }
                                                                $value = isset($row->value) ? $row->value : '';
                                                                if ($row->subtype == 'datetime-local') {
                                                                    $row->subtype = 'datetime-local';
                                                                    $attr['class'] = $attr['class'] . ' date_time';
                                                                }
                                                            @endphp
                                                            <label for="{{ $row->name }}"
                                                                class="form-label">{{ $row->label }}
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                            </label>
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            {!! html()->input($row->subtype, $row->name, $value)->attributes(array_merge($attr, ['data-input' => $row->name])) !!}
                                                            @if ($row->required)
                                                                <div class="error-message required-text"></div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'starRating')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @php
                                                                $value = isset($row->value) ? $row->value : 0;
                                                                $num_of_star = isset($row->number_of_star)
                                                                    ? $row->number_of_star
                                                                    : 5;
                                                            @endphp
                                                            <label for="{{ $row->name }}" class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            @if (isset($row->description))
                                                                <span type="button" class="tooltip-element"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $row->description }}">
                                                                    ?
                                                                </span>
                                                            @endif
                                                            <div id="{{ $row->name }}" class="starRating"
                                                                data-value="{{ $value }}"
                                                                data-num_of_star="{{ $num_of_star }}">
                                                            </div>
                                                            <input type="hidden" name="{{ $row->name }}"
                                                                value="{{ $value }}" class="calculate"
                                                                data-star="{{ $num_of_star }}">
                                                        </div>
                                                    @elseif($row->type == 'SignaturePad')
                                                        @php
                                                            $attr = ['class' => $row->name];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            $value = isset($row->value) ? $row->value : null;
                                                        @endphp
                                                        <div class="row form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @include('form.js.signature')
                                                            <div class="col-12">
                                                                <label for="{{ $row->name }}"
                                                                    class="form-label">{{ $row->label }}</label>
                                                                @if ($row->required)
                                                                    <span
                                                                        class="text-danger align-items-center">*</span>
                                                                @endif
                                                                @if (isset($row->description))
                                                                    <span type="button" class="tooltip-element"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="{{ $row->description }}">
                                                                        ?
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-lg-6 col-md-12 col-12">
                                                                <div class="signature-pad-body">
                                                                    <canvas class="signaturePad form-control"
                                                                        id="{{ $row->name }}"></canvas>
                                                                    <div class="sign-error"></div>
                                                                    {!! html()->hidden($row->name, $value)->attributes($attr) !!}
                                                                    <div class="buttons signature_buttons">
                                                                        <button id="save{{ $row->name }}"
                                                                            type="button" data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            data-bs-original-title="{{ __('Save') }}"
                                                                            class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                                                        <button id="clear{{ $row->name }}"
                                                                            type="button" data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            data-bs-original-title="{{ __('Clear') }}"
                                                                            class="btn btn-danger btn-sm">{{ __('Clear') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if (@$row->value != '')
                                                                <div class="col-lg-6 col-md-12 col-12">
                                                                    <img src="{{ Storage::url($row->value) }}"
                                                                        width="80%" class="border" alt="">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @elseif($row->type == 'break')
                                                        <hr class="hr_border">
                                                    @elseif($row->type == 'location')
                                                        <div class="form-group {{ $col }}"
                                                            data-name={{ $row->name }}>
                                                            @include('form.js.map')
                                                            <input id="pac-input" class="controls" type="text"
                                                                name="location" placeholder="Search Box" />
                                                            <div id="map"></div>
                                                        </div>
                                                    @elseif($row->type == 'video')
                                                        @php
                                                            $attr = ['class' => 'multi-media'];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            $value = isset($row->value) ? $row->value : null;
                                                        @endphp
                                                        <div class="form-group video-stream {{ $col }}"
                                                            data-name={{ $row->name }}>

                                                            <label for="{{ $row->name }}"
                                                                class="form-label">{{ $row->label }}</label>
                                                            @if ($row->required)
                                                                <span class="text-danger align-items-center">*</span>
                                                            @endif
                                                            <div class="d-flex justify-content-start">
                                                                <button type="button" class="btn btn-primary"
                                                                    id="videostream">
                                                                    <i class="ti ti-camera"></i>
                                                                    <span>{{ __('Record Video') }}</span>
                                                                </button>
                                                            </div>
                                                            @if ($row->required)
                                                                <div class="error-message required-text"></div>
                                                            @endif
                                                            <div class="cam-buttons d-none">
                                                                <video autoplay controls
                                                                    class="p-2 web-cam-container d-none"
                                                                    style="width:100%; height:80%;">
                                                                    {{ __("Your browser doesn't support the video tag") }}
                                                                </video>
                                                                <div class="py-4">
                                                                    <div class="field-required">
                                                                        <div
                                                                            class="mb-2 btn btn-lg btn-primary float-end">
                                                                            <div id="timer">
                                                                                <span id="hours">00:</span>
                                                                                <span id="mins">00:</span>
                                                                                <span id="seconds">00</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id='gUMArea' class="video_cam">
                                                                        <div class="web_cam_video">
                                                                            <input type="hidden"
                                                                                class="{{ implode(' ', $attr) }}"
                                                                                name="{{ $row->name }}" checked
                                                                                value="{{ $value }}"
                                                                                id="mediaVideo">
                                                                        </div>
                                                                    </div>
                                                                    <div id='btns'>
                                                                        <div id="controls">
                                                                            <button class="btn btn-primary start"
                                                                                id='start' type="button">
                                                                                <i class="ti ti-video"></i>
                                                                                <span>{{ __('Start') }}</span>
                                                                            </button>
                                                                            <button class="btn btn-danger stop"
                                                                                id='stop' type="button">
                                                                                <i class="ti ti-player-stop"></i>
                                                                                <span>{{ __('Stop') }}</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($row->type == 'selfie')
                                                        @php
                                                            $attr = ['class' => $row->name];
                                                            if ($row->required) {
                                                                $attr['required'] = 'required';
                                                                $attr['class'] = $attr['class'] . ' required';
                                                            }
                                                            $cameraId = $row->name;
                                                        @endphp
                                                        <div class="row {{ $col }} selfie_screen"
                                                            data-name={{ $row->name }}>
                                                            @include('form.js.selfie')
                                                            <div class="col-12 col-sm-6 selfie_photo">
                                                                <div class="form-group">
                                                                    <label for="{{ $row->name }}"
                                                                        class="form-label">{{ $row->label }}</label>
                                                                    @if ($row->required)
                                                                        <span
                                                                            class="text-danger align-items-center">*</span>
                                                                    @endif
                                                                    <div id="{{ $cameraId }}"
                                                                        class="camera_screen"></div>
                                                                    <br />
                                                                    <button type="button"
                                                                        class="btn btn-default btn-primary"
                                                                        onClick="takeSnapshot('{{ $cameraId }}')">
                                                                        <i class="ti ti-camera"></i>
                                                                        {{ __('Take Selfie') }}
                                                                    </button>
                                                                    <input type="hidden" name="{{ $row->name }}"
                                                                        class="image-tag  {{ implode(' ', $attr) }}">
                                                                </div>

                                                            </div>
                                                            <div class="mt-4 col-12 col-sm-6">
                                                                <div class="selfie_result {{ $cameraId }} ms-1"
                                                                    style="width: 263px">
                                                                    {{ __('Your captured image will appear here...') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="row">
                                    <div class="col cap">
                                        @if (UtilityFacades::getsettings('captcha_enable') == 'on')
                                            @if (UtilityFacades::getsettings('captcha') == 'hcaptcha')
                                                {!! HCaptcha::renderJs() !!}
                                                <small
                                                    class="text-danger font-weight-bold">{{ __('Note :- reCAPTCHA Is required') }}</small>
                                                <div class="g-hcaptcha"
                                                    data-sitekey="{{ UtilityFacades::getsettings('hcaptcha_sitekey') }}">
                                                </div>
                                                {!! HCaptcha::display() !!}
                                                @error('g-hcaptcha-response')
                                                    <span class="text-danger text-bold">{{ $message }}</span>
                                                @enderror
                                            @endif
                                            @if (UtilityFacades::getsettings('captcha') == 'recaptcha')
                                                {!! NoCaptcha::renderJs() !!}
                                                <small
                                                    class="text-danger font-weight-bold">{{ __('Note :- reCAPTCHA Is required') }}</small>
                                                <div class="g-recaptcha"
                                                    data-sitekey="{{ UtilityFacades::getsettings('captcha_sitekey') }}">
                                                </div>
                                                {!! NoCaptcha::display() !!}
                                            @endif
                                        @endif

                                        <div class="pb-0 mt-3 form-actions">
                                            <input type="hidden" name="form_value_id"
                                                value="{{ isset($formValue) ? $formValue->id : '' }}"
                                                id="form_value_id">
                                        </div>
                                    </div>
                                </div>

                                @if (!isset($formValue) && $form->payment_status == 1)
                                    @if (!isset($formValue) && $form->payment_type == 'stripe')
                                        <div class="strip">
                                            <strong class="d-block">{{ __('Payment') }}
                                                ({{ $form->currency_symbol }}{{ $form->amount }})</strong>
                                            <div id="card-element" class="form-control">
                                            </div>
                                            <span id="card-errors" class="payment-errors"></span>
                                            <br>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'razorpay')
                                        <div class="razorpay">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id">
                                            <h5>{{ __('Payable Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'paypal')
                                        <div class="paypal">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id">
                                            <h5>{{ __('Payable Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                            <div id="paypal-button-container"></div>
                                            <span id="paypal-errors" class="payment-errors"></span>
                                            <br>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'paytm')
                                        <div class="paytm">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'flutterwave')
                                        <div class="flutterwave">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'paystack')
                                        <div class="paystack">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'coingate')
                                        <div class="coingate">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'mercado')
                                        <div class="mercado">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'payumoney')
                                        <div class="payumoney">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif(!isset($formValue) && $form->payment_type == 'mollie')
                                        <div class="mollie">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id" value="">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>
                                        </div>
                                    @elseif (!isset($formValue) && $form->payment_type == 'offlinepayment')
                                        <div class="offlinepayment">
                                            <p>{{ __('Make Payment') }}</p>
                                            <input type="hidden" name="payment_id" id="payment_id">
                                            <h5>{{ __('Payble Amount') }} : {{ $form->currency_symbol }}
                                                {{ $form->amount }}</h5>

                                            <div class="form-group">
                                                <label for="payment_details" class="form-label">{{ __('Payment Details') }}</label>
                                                <P>{{ UtilityFacades::getsettings('offline_payment_details') }}</P>
                                            </div>
                                            <div class="form-group">
                                                <label for="transfer_slip" class="form-label">{{ __('Upload Payment Slip') }}</label>
                                                <span>{{ __('( jpg, png, pdf )') }}</span>
                                                <input type="file" name="transfer_slip" class="form-control required" required>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <input type="hidden" name="ip_data" id="ip_data" value="">
                                <div class="over-auto">
                                    <div class="float-right">
                                        {!! html()->button(__('Previous'))->type('button')->class('btn btn-default')->id('prevBtn')->attribute('onclick', 'nextPrev(-1)') !!}
                                        {!! html()->button(__('Next'))->type('button')->class('btn btn-primary')->id('nextBtn')->attribute('onclick', 'nextPrev(1)') !!}
                                    </div>
                                </div>
                                <div class="extra_style">
                                    @if (isset($array))
                                        @foreach ($array as $keys => $rows)
                                            <span class="step"></span>
                                        @endforeach
                                    @endif
                                </div>
                            </form>

                            {!! html()->form('post', route('coingateprepare'))->id('coingate_payment_frms')->open() !!}
                            {!! html()->hidden('cg_currency')->id('cg_currency') !!}
                            {!! html()->hidden('cg_amount')->id('cg_amount') !!}
                            {!! html()->hidden('cg_form_id')->id('cg_form_id') !!}
                            {!! html()->hidden('cg_submit_type')->id('cg_submit_type') !!}
                            {!! html()->form()->close() !!}

                            {!! html()->form('post', route('payumoneyfillprepare'))->id('payumoney_payment_frms')->attribute('name', 'payuForm')->open() !!}
                            {!! html()->hidden('payumoney_currency')->id('payumoney_currency') !!}
                            <input type="hidden" name="payumoney_currency" id="payumoney_currency" value="">
                            <input type="hidden" name="payumoney_amount" id="payumoney_amount" value="">
                            <input type="hidden" name="payumoney_form_id" id="payumoney_form_id" value="">
                            <input type="hidden" name="payumoney_created_by" id="payumoney_created_by"
                                value="">
                            <input type="hidden" name="payumoney_submit_type" id="payumoney_submit_type"
                                value="">
                            {!! html()->form()->close() !!}

                            {!! html()->form('post', route('molliefillprepare'))->id('mollie_payment_frms')->attribute('name', 'mollieForm')->open() !!}
                            <input type="hidden" name="mollie_currency" id="mollie_currency">
                            <input type="hidden" name="mollie_amount" id="mollie_amount">
                            <input type="hidden" name="mollie_form_id" id="mollie_form_id">
                            <input type="hidden" name="mollie_created_by" id="mollie_created_by">
                            <input type="hidden" name="mollie_submit_type" id="mollie_submit_type">
                            {!! html()->form()->close() !!}

                            {!! html()->form('post', route('mercadofillprepare'))->id('mercado_payment_frms') !!} {!! html()->hidden('mercado_amount')->id('mercado_amount')->open() !!}
                            <input type="hidden" name="mercado_amount" id="mercado_amount">
                            <input type="hidden" name="mercado_form_id" id="mercado_form_id">
                            <input type="hidden" name="mercado_created_by" id="mercado_created_by">
                            <input type="hidden" name="mercado_submit_type" id="mercado_submit_type">
                            {!! html()->form()->close() !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if ($form->allow_share_section == 1)
            <div class="row">
                @include('form.js.share-section')
                <div class="mx-auto col-xl-7 order-xl-1">
                    <div class="card">
                        <div class="card-header">
                            <h5> <i class="me-2" data-feather="share-2"></i>{{ __('Share') }}</h5>
                        </div>
                        <div class="card-body ">
                            <div class="m-auto form-group col-md-6">
                                <p>{{ __('Use this link to share the poll with your participants.') }}</p>
                                <div class="input-group">
                                    <input type="text" value="{{ route('forms.survey', $id) }}"
                                        class="form-control js-content" id="pc-clipboard-1"
                                        placeholder="Type some value to copy">
                                    <a href="javascript:void(0)" class="btn btn-primary js-copy" data-clipboard="true"
                                        data-clipboard-target="#pc-clipboard-1"> {{ __('Copy') }}
                                    </a>
                                </div>
                                <div class="mt-3 social-links-share">
                                    <a href="https://api.whatsapp.com/send?text={{ route('forms.survey', $id) }}"
                                        title="Whatsapp" class="social-links-share-main">
                                        <i class="ti ti-brand-whatsapp"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text={{ route('forms.survey', $id) }}"
                                        title="Twitter" class="social-links-share-main">
                                        <i class="ti ti-brand-twitter"></i>
                                    </a>
                                    <a href="https://www.facebook.com/share.php?u={{ route('forms.survey', $id) }}"
                                        title="Facebook" class="social-links-share-main">
                                        <i class="ti ti-brand-facebook"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ route('forms.survey', $id) }}"
                                        title="Linkedin" class="social-links-share-main">
                                        <i class="ti ti-brand-linkedin"></i>
                                    </a>
                                    <a href="javascript:void(1);" class="social-links-share-main"
                                        title="Show QR Code" data-action="{{ route('forms.survey.qr', $id) }}"
                                        id="share-qr-image">
                                        <i class="ti ti-qrcode"></i>
                                    </a>
                                    <a href="javascript:void(0)" title="Embed" class="social-links-share-main"
                                        onclick="copyToClipboard('#embed-form-{{ $form->id }}')"
                                        id="embed-form-{{ $form->id }}"
                                        data-url='<iframe src="{{ route('forms.survey', $id) }}" scrolling="auto" align="bottom" style="height:100vh;" width="100%"></iframe>'>
                                        <i class="ti ti-code"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($form->allow_comments == 1)
            <div class="row">
                <div class="mx-auto col-xl-7 order-xl-1">
                    <div class="card">
                        <div class="card-header">
                            <h5> <i class="me-2" data-feather="message-circle"></i>{{ __('Comments') }}</h5>
                        </div>
                        {!! html()->form('POST', route('form.comment.store'))->open() !!}
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Enter your name">
                            </div>
                            <div class="form-group">
                                <textarea name="comment" class="form-control" rows="3" required placeholder="Add a comment"></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">
                        <div class="card-footer">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Add a comment') }}</button>
                            </div>
                            {!! html()->form()->close() !!}
                            @foreach ($form->commmant as $value)
                                <div class="comments-item">
                                    <div class="comment-user-icon">
                                        <img src="{{ asset('assets/images/comment.png') }}">
                                    </div>
                                    <span class="text-left comment-info">
                                        <h6>{{ $value->name }}</h6>
                                        <span class="d-block"><small>{{ $value->comment }}</small></span>
                                        <h6 class="d-block">
                                            <small>({{ $value->created_at->diffForHumans() }})</small>
                                            <a href="#reply-comment"
                                                class="text-dark reply-comment-{{ $value->id }}"
                                                id="comment-reply" data-bs-toggle="collapse"
                                                data-id="{{ $value->id }}" title="{{ __('Reply') }}">
                                                {{ __('Reply') }}</i></a>
                                            @if (Auth::user())
                                                {!! html()->form('DELETE', route('form.comment.destroy', $value->id))->id('delete-form-' . $value->id)->class('d-inline')->open() !!}
                                                <a href="javascript:void(0)" class="text-dark show_confirm" title="Delete"
                                                    id="delete-form-{{ $value->id }}">{{ __('Delete') }}</a>
                                                {!! html()->form()->close() !!}
                                            @endif
                                        </h6>
                                        <li class="list-inline-item"> </li>
                                        @foreach ($value->replyby as $reply_value)
                                            <div class="comment-replies">
                                                <div class="comment-user-icon">
                                                    <img src="{{ asset('assets/images/comment.png') }}">
                                                </div>
                                                <div class="comment-replies-content">
                                                    <h6>{{ $reply_value->name }}</h6>
                                                    <span
                                                        class="d-block"><small>{{ $reply_value->reply }}</small></span>
                                                    <h6 class="d-block">
                                                        <small>({{ $reply_value->created_at->diffForHumans() }})</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </span>
                                </div>
                                {!! html()->form('POST', route('form.comment.reply.store'))->id('reply-form-' . $value->id)->class('data-validate')->open() !!}
                                <div class="row commant" id="reply-comment-{{ $value->id }}">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" required
                                            placeholder="{{ __('Enter your name') }}">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="reply" class="form-control" rows="2" required placeholder="{{ __('Add a comment') }}"></textarea>
                                    </div>
                                    <input type="hidden" id="form_id" name="form_id"
                                        value="{{ $form->id }}">
                                    <input type="hidden" id="comment_id" name="comment_id"
                                        value="{{ $value->id }}">
                                    <div class="card-footer">
                                        <div class="text-end">
                                            <button type="submit"
                                                class="btn btn-primary">{{ __('Add a comment') }}</button>
                                        </div>
                                    </div>
                                </div>
                                {!! html()->form()->close() !!}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif

@if ($form->conditional_rule == '1')
    @include('form.js.conditional-rule')
@endif
@if ($form->enable_session_timer == '1')
    @include('form.js.session-timer')
@endif
@push('script')
    @include('form.js.video')
    <script src="{{ asset('vendor/location-get/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('vendor/location-get/utils.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $.get("https://ipinfo.io", function(data) {
                    $('#ip_data').val(JSON.stringify(data));
                }, "jsonp");
            }, 2000);
        });
    </script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/clipboard.min.js') }}"></script>
    <script>
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
        });
    </script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).attr('data-url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully.') }}', 'success');
        }

        $(document).ready(function() {
            let area = document.createElement('textarea');
            document.body.appendChild(area);
            area.style.display = "none";
            let content = document.querySelectorAll('.js-content');
            let copy = document.querySelectorAll('.js-copy');
            for (let i = 0; i < copy.length; i++) {
                copy[i].addEventListener('click', function() {
                    area.style.display = "block";
                    area.value = content[i].innerText;
                    area.select();
                    document.execCommand('copy');
                    area.style.display = "none";
                    this.innerHTML = 'Copied ';
                    setTimeout(() => this.innerHTML = "Copy", 2000);
                });
            }
        });

        $(document).on('click', '#deleteAttachment', function(e) {
            e.preventDefault();
            var imgId = $(this).data('id');
            var formValueId = '{{ $formValue->id ?? '' }}';
            var attachmentsContainer = $(this).closest('.row');

            $.ajax({
                url: '{{ route('attachments.delete', ['imgId', 'formValueId']) }}'.replace('imgId', imgId).replace('formValueId', formValueId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.is_success) {
                        attachmentsContainer.load(document.URL + " #" + attachmentsContainer.attr('id'));
                        show_toastr('Great', response.message, 'success');
                    } else {
                        show_toastr('Error!', response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    show_toastr('Error!', 'An error occurred while deleting the attachment', 'danger');
                }
            });
        });
    </script>
@endpush
