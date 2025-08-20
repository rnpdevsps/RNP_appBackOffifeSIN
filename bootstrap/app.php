<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ApiKey'                => \App\Http\Middleware\ValidateApiKey::class,
            'auth'                => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic'          => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings'            => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers'       => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'                 => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'               => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed'              => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'            => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'            => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role'                => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'          => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'  => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            '2fa'                 => \App\Http\Middleware\LoginSecurityMiddleware::class,
            // new '2fa' => \PragmaRX\Google2FALaravel\Middleware::class,
            'xss'                 => \App\Http\Middleware\XSS::class,
            'cors'                => \App\Http\Middleware\Cors::class,
            'Setting'             => \App\Http\Middleware\Setting::class,
            'verified_phone'      => \App\Http\Middleware\EnsurePhoneIsVerified::class,
            'Upload'              => \App\Http\Middleware\Upload::class,
            'password_protection' => \App\Http\Middleware\PasswordProtection::class,
            'formIntegration'     => \App\Http\Middleware\FormIntegration::class
        ]);
        $middleware->validateCsrfTokens(except: [
            '/fillcallback*',
            '/paytm-callback*',
            'forms/fill/*',
            '/payumoney/fill/prepare*',
            'payumoney-fill-payment/*',
            'feedback*',
            'timer/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
