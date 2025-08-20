<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Models\ApiKey;
use Carbon\Carbon;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key no proporcionada'], 401);
        }

        $key = ApiKey::where('api_key', $apiKey)->where('status', 1)->first();
        if (!$key) {
            return response()->json(['error' => 'API Key inválida'], 401);
        }

        $expiresAt = Carbon::parse($key->expires_at); // Convertir la fecha a una instancia de Carbon
        $diasRestantes = Carbon::now()->diffInDays($expiresAt, false); // Calcular la diferencia en días
        $diasRestantes = round($diasRestantes); // Redondea al entero más cercano


        // Verifica si la ruta actual está permitida
        $routeName = $request->route()->getName();
        $permissions = $key->permissions ?? [];

        if (!in_array($routeName, $permissions)) {
            // Registrar intento no autorizado por falta de permisos
            ApiLog::create([
                'ip_address' => $request->ip(),
                'endpoint' => $request->path(),
                'user_agent' => $request->header('User-Agent'),
                'api_key' => $apiKey,
                'request_data' => json_encode($request->all()),
                'message' => 'Permiso denegado para la ruta: ' . $routeName,
                'status' => 'fail',
            ]);
        
            return response()->json(['error' => 'No tienes permiso para acceder la peticion.'], 403);
        }
        // Fin verificar las rutas

        // Mostrar los días restantes
        if ($diasRestantes < 0) {
            ApiLog::create([
                'ip_address' => $request->ip(),
                'endpoint' => $request->path(),
                'user_agent' => $request->header('User-Agent'),
                'api_key' => $apiKey,
                'request_data' => json_encode($request->all()),
                'status' => 'fail',
                'message' => 'API Key expirada',
            ]);
            return response()->json(['error' => 'API Key expirada'], 401);
        }


        if ($apiKey != $key->api_key) {
            // Registrar intento fallido en la tabla
            ApiLog::create([
                'ip_address' => $request->ip(),
                'endpoint' => $request->path(),
                'user_agent' => $request->header('User-Agent'),
                'api_key' => $apiKey,
                'request_data' => json_encode($request->all()),
                'message' => 'No Autorizado',
                'status' => 'fail',
            ]);

            return response()->json(['error' => 'No Autorizado'], 401);
        }

        // Registrar acceso exitoso en la tabla
        ApiLog::create([
            'ip_address' => $request->ip(),
            'endpoint' => $request->path(),
            'user_agent' => $request->header('User-Agent'),
            'api_key' => $apiKey,
            'request_data' => json_encode($request->all()),
            'message' => 'Acceso Autorizado',
            'status' => 'success',
        ]);

        return $next($request);
    }
}
