{!! html()->form('POST', route('users.store'))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group ">
            {!! html()->label(__('Name'), 'name')->class('col-form-label') !!}
            {!! html()->text('name')->class('form-control')->placeholder(__('Enter name'))->required() !!}
        </div>
        <div class="form-group ">
            {!! html()->label(__('Email'), 'email')->class('col-form-label') !!}
            {!! html()->text('email')->class('form-control')->placeholder(__('Enter email address'))->required() !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Password'), 'password')->class('col-form-label') !!}
            {!! html()->password('password')->class('form-control')->placeholder(__('Enter password'))->required() !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Confirm Password'), 'confirm-password')->class('col-form-label') !!}
            {!! html()->password('confirm-password')->class('form-control')->placeholder(__('Enter confirm password'))->required() !!}

        </div>
        <div class="mb-3 form-group">
            {!! html()->label(__('Country Code'), 'country_code')->class('d-block form-label') !!}
            <select id="country_code" name="country_code"class="form-select" data-trigger>
                @foreach (\App\Core\Data::getCountriesList() as $key => $value)
                    <option data-kt-flag="{{ $value['flag'] }}" value="{{ $key }}">
                        +{{ $value['phone_code'] }} {{ $value['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 form-group">
            {!! html()->label(__('Phone Number'), 'phone')->class('form-label') !!}
            {!! html()->number('phone')->class('form-control')->placeholder(__('Enter phone Number'))->autofocus()->required()->attribute('autocomplete', 'off') !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Role'), 'roles')->class('col-form-label') !!}
            {!! html()->select('roles', $roles)->class('form-select')->id('roles') !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}

    </div>
</div>
{!! html()->form()->close() !!}
