{!! html()->modelForm($user, 'PUT', route('users.update', $user->id))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Name'), 'name')->class('col-form-label') !!}
            {!! html()->text('name')->class('form-control')->placeholder(__('Enter name'))->required() !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Email'), 'email')->class('col-form-label') !!}
            {!! html()->text('email')->class('form-control')->placeholder(__('Enter email address'))->required() !!}
        </div>
    </div>
    <div class="form-group">
        {!! html()->label(__('Password'), 'password')->class('col-form-label') !!}
        {!! html()->password('password')->class('form-control')->placeholder(__('Enter password')) !!}
    </div>
    <div class="form-group">
        {!! html()->label(__('Confirm Password'), 'confirm-password')->class('col-form-label') !!}
        {!! html()->password('confirm-password')->class('form-control')->placeholder(__('Enter confirm password')) !!}
    </div>
    <div class="mb-3 form-group">
        {!! html()->label(__('Country Code'), 'country_code')->class('d-block form-label') !!}
        <select id="country_code" name="country_code"class="form-control" data-trigger>
            @foreach (\App\Core\Data::getCountriesList() as $key => $value)
                <option data-kt-flag="{{ $value['flag'] }}"
                    {{ $value['phone_code'] == $user->country_code ? 'selected' : '' }} value="{{ $key }}">
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
        {!! html()->select('roles', $roles, $userRole)->class('form-select')->id('roles')->required() !!}
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->closeModelForm() !!}
