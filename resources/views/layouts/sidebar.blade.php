@php
    $user        = \Auth::user();
    $currantLang = $user->currentLanguage();
    $languages   = Utility::languages();
    $role_id     = $user->roles->first()->id;
    $user_id     = $user->id;
@endphp
<nav class="dash-sidebar light-sidebar {{ $user->transprent_layout == 1 ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('home') }}" class="text-center b-brand">
                <!-- ========   change your logo hear   ============ -->
                @if ($user->dark_layout == 1)
                    <img src="{{ Utility::getsettings('app_logo') ? Storage::url('app-logo/app-logo.png') : Storage::url('app-logo/78x78.png') }}"
                        class="app-logo" />
                @else
                    <img src="{{ Utility::getsettings('app_dark_logo') ? Storage::url('app-logo/app-dark-logo.png') : Storage::url('app-logo/78x78.png') }}"
                        class="app-logo" />
                @endif
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar d-block">
                <li class="dash-item dash-hasmenu {{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-home"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Dashboard') }}</span></a>
                </li>

                @canany(['manage-user', 'manage-role']) 
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-layout-2"></i></span><span
                                class="dash-mtext">{{ __('User Management') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-user')
                                <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                </li>
                            @endcan
                            @can('manage-role')
                                <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['manage-blog', 'manage-category'])
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('blogs*') || request()->is('blog-category*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon">
                                <i class="ti ti-forms"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Blog') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i>
                            </span>
                        </a>
                        <ul class="dash-submenu">
                            @can('manage-blog')
                                <li class="dash-item {{ request()->is('blogs*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('blogs.index') }}">{{ __('Blogs') }}</a>
                                </li>
                            @endcan
                            @can('manage-category')
                                <li class="dash-item {{ request()->is('blog-category*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('blog-category.index') }}">{{ __('Categories') }}</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

         
                @canany(['manage-chat'])
                    @if (setting('pusher_status') == '1')
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('chat*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-table"></i></span><span
                                    class="dash-mtext">{{ __('Support') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-chat')
                                    <li class="dash-item">
                                        <a class="dash-link" href="{{ route('chats') }}">{{ __('Chats') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                @endcanany
                @canany(['manage-mailtemplate', 'manage-sms-template', 'manage-language', 'manage-setting'])
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('mailtemplate*') || request()->is('sms-template*') || request()->is('manage-language*') || request()->is('create-language*') || request()->is('settings*') ? 'active dash-trigger' : 'collapsed' }} || {{ request()->is('create-language*') || request()->is('settings*') ? 'active' : '' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-apps"></i></span><span
                                class="dash-mtext">{{ __('Account Setting') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-apikey')
                                <li class="dash-item {{ request()->is('apikey*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('apikey.index') }}">{{ __('Apikey') }}</a>
                                </li>
                            @endcan
                             @can('manage-module')
                                <li class="dash-item {{ request()->is('module*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('module.index') }}">{{ __('Modulos') }}</a>
                                </li>
                            @endcan
                            @can('manage-mailtemplate')
                                <li class="dash-item {{ request()->is('mailtemplate*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('mailtemplate.index') }}">{{ __('Email Templates') }}</a>
                                </li>
                            @endcan
                            @can('manage-sms-template')
                                <li class="dash-item {{ request()->is('sms-template*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('sms-template.index') }}">{{ __('Sms Templates') }}</a>
                                </li>
                            @endcan
                            @can('manage-language')
                                <li
                                    class="dash-item {{ request()->is('manage-language*') || request()->is('create-language*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Languages') }}</a>
                                </li>
                            @endcan
                            @can('manage-setting')
                                <li class="dash-item {{ request()->is('settings*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['manage-landing-page', 'manage-faqs', 'manage-testimonial', 'manage-page-setting'])
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('landingpage-setting*') || request()->is('faqs*') || request()->is('page-setting*') || request()->is('testimonials*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-table"></i></span><span
                                class="dash-mtext">{{ __('Frontend Setting') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-landing-page')
                                <li class="dash-item {{ request()->is('landingpage-setting*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('landing-page.setting') }}">{{ __('Landing Page') }}</a>
                                </li>
                            @endcan
                            @can('manage-testimonial')
                                <li class="dash-item {{ request()->is('testimonials*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('testimonial.index') }}">{{ __('Testimonials') }}</a>
                                </li>
                            @endcan
                            @can('manage-faqs')
                                <li class="dash-item {{ request()->is('faqs*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('faqs.index') }}">{{ __('FAQs') }}</a>
                                </li>
                            @endcan
                            @can('manage-page-setting')
                                <li class="dash-item {{ request()->is('page-setting*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('page-setting.index') }}">{{ __('Page Settings') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @if (Auth::user()->type == 'Admin')
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('telescope*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-device-desktop-analytics"></i></span><span
                                class="dash-mtext">{{ __('System Analytics') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            <li class="dash-item {{ request()->is('telescope*') ? 'active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('telescope') }}">{{ __('Telescope Dashboard') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
