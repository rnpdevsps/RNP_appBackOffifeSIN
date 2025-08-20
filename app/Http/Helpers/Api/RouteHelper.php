<?php

namespace App\Http\Helpers\Api;

use Illuminate\Support\Facades\Route;

class RouteHelper
{
    public static function getApiRouteNames()
    {
        $routes = Route::getRoutes();
        $apiRoutes = [];

        foreach ($routes as $route) {
            if (str_starts_with($route->uri(), 'api/') && $route->getName()) {
                $apiRoutes[] = $route->getName();
            }
        }

        return $apiRoutes;
    }
}
