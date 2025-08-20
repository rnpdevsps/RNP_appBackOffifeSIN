@php
    $users = \Auth::user();
    $languages = Utility::languages();
    $profile = asset(Storage::url('avatar/'));
    $ShowLP =  App\Facades\UtilityFacades::getsettings('landing_page');

@endphp
<!DOCTYPE html>
<html>

<head>
    <title>@yield('title') | {{ Utility::getsettings('app_name') }}</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title"
    content="{{ !empty(Utility::getsettings('meta_title'))
        ? Utility::getsettings('meta_title') :  Utility::getsettings('app_name')  }}">
<meta name="keywords"
    content="{{ !empty(Utility::getsettings('meta_keywords'))
        ? Utility::getsettings('meta_keywords')
        : '' }}">
<meta name="description"
    content="{{ !empty(Utility::getsettings('meta_description'))
        ? Utility::getsettings('meta_description')
        : '' }}">
<meta name="meta_image_logo" property="og:image"
    content="{{ !empty(Utility::getsettings('meta_image_logo'))
        ? Storage::url(Utility::getsettings('meta_image_logo'))
        : Storage::url('seeder-image/meta-image-logo.jpg') }}">
    @if (Utility::getsettings('seo_setting') == 'on')
        {!! app('seotools')->generate() !!}
    @endif
    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ Utility::getsettings('favicon_logo') ? Storage::url('app-logo/app-favicon-logo.png') : '' }}"
        type="image/png">
    <!-- font css -->

</head>

<body class="light">
