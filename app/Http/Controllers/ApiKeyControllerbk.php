<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\ApiKey;

class ApiKeyController extends Controller
{

    public function generateApiKey(Request $request)
{
    // Generar una nueva API key
    $apiKey = ApiKey::create([
        'api_key' => Str::random(60),
        'app_name' => $request->input('app_name', 'Nombre de la App'),
        'expires_at' => now()->addDays(365),
    ]);

    return response()->json([
        'message' => 'API Key generada con éxito',
        'api_key' => $apiKey->api_key,
    ]);
}
}

