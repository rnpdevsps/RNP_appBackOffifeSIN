<?php

namespace App\Http\Middleware;

use App\Models\FormIntegrationSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormIntegration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $formId = $request->formId ? $request->formId : session('formId');
        $FormIntegrationSetting = FormIntegrationSetting::where('form_id', $formId)->where('key', 'salesforce_integration')->first();
        $credentials = json_decode($FormIntegrationSetting->json);
        config([
            'forrest.credentials.consumerKey'        => $credentials[0]->sf_consumer_key,
            'forrest.credentials.consumerSecret'     => $credentials[0]->sf_consumer_secret,
            'forrest.credentials.callbackURI'        => $credentials[0]->sf_callback_uri,
            'forrest.credentials.formId'             => $formId,
        ]);

        return $next($request);
    }
}
