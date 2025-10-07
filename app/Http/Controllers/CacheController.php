<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Session;

class CacheController extends Controller
{
    public function clear() {
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('route:clear');
      Artisan::call('view:clear');

      Session::flash('success', 'Cache, Rutas, Vistas, Ajustes se borro, cache con éxito!');
      return back();
    }
}
