<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Throwable;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $uuid = (string) Str::uuid();

        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            $this->registerLog($request, $apiKey, 'API Key no proporcionada', 'fail', 401, $uuid, $startTime, null);
            return response()->json(['error' => 'API Key no proporcionada'], 401);
        }

        $key = ApiKey::where('api_key', $apiKey)->where('status', 1)->first();
        if (!$key) {
            $this->registerLog($request, $apiKey, 'API Key inválida', 'fail', 401, $uuid, $startTime, null);
            return response()->json(['error' => 'API Key inválida'], 401);
        }

        // Verificar expiración
        $expiresAt = Carbon::parse($key->expires_at);
        $diasRestantes = Carbon::now()->diffInDays($expiresAt, false);
        if ($diasRestantes < 0) {
            $this->registerLog($request, $apiKey, 'API Key expirada', 'fail', 401, $uuid, $startTime, null);
            return response()->json(['error' => 'API Key expirada'], 401);
        }

        // Verificar permisos por ruta
        $routeName = $request->route()?->getName() ?? 'sin_nombre';
        $permissions = $key->permissions ?? [];
        if (!in_array($routeName, $permissions)) {
            $this->registerLog($request, $apiKey, "Permiso denegado para la ruta: {$routeName}", 'fail', 403, $uuid, $startTime, null);
            return response()->json(['error' => 'No tienes permiso para acceder a esta ruta.'], 403);
        }

        try {
            // Ejecutar la solicitud y capturar la respuesta
            $response = $next($request);
            $status = $response->getStatusCode();
            $message = $status === 200 ? 'Acceso autorizado' : "Respuesta con estado {$status}";

            $responseBody = $this->getResponseContent($response);

            // Guardar log exitoso con cuerpo de respuesta
            $this->registerLog($request, $apiKey, $message, 'success', $status, $uuid, $startTime, $responseBody);

            return $response;
        } catch (Throwable $e) {
            $this->registerLog($request, $apiKey, "Error: {$e->getMessage()}", 'error', 500, $uuid, $startTime, null);
            throw $e;
        }
    }

    private function registerLog(Request $request, $apiKey, $message, $status, $httpStatus, $uuid, $startTime, $responseBody = null)
    {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        ApiLog::create([
            'uuid' => $uuid,
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'user_agent' => $request->header('User-Agent'),
            'headers' => json_encode($request->headers->all(), JSON_UNESCAPED_UNICODE),
            'service_name' => $request->route()?->getName() ?? 'desconocido',
            'api_key' => $apiKey,
            'request_body' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            'response_body' => $responseBody,
            'message' => $message,
            'status' => $status,
            'http_status' => $httpStatus,
            'execution_time_ms' => $executionTime,
            'created_at' => \Carbon\Carbon::now('America/Tegucigalpa'),

        ]);
    }

    private function getResponseContent($response)
    {
        try {
            $content = $response->getContent();

            if (strlen($content) > 10000) {
                $content = $content;
            }

            return $content;
        } catch (Throwable $e) {
            return 'Error al obtener contenido de respuesta: ' . $e->getMessage();
        }
    }
}
