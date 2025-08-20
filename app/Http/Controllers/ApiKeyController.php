<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ApiKeyDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Models\ApiKey;
use App\Http\Helpers\Api\RouteHelper;


class ApiKeyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-apikey|create-apikey|edit-apikey|delete-apikey', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-apikey', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-apikey', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-apikey', ['only' => ['destroy']]);
    }

    public function index(ApiKeyDataTable $dataTable)
    {
        return $dataTable->render('apikey.index');
    }

    public function create()
    {
        $key = Str::random(50);
        $permisosDisponibles = RouteHelper::getApiRouteNames();

        $view =  view('apikey.create', compact('key', 'permisosDisponibles'));
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'api_key'    => 'required',
            'app_name'   => 'required',
        ]);

        $input  = $request->all();
        $expira = is_numeric($input['expira']) ? intval($input['expira']) : 0;
        $input['expires_at']  =  Carbon::now()->addDays($expira);

        // Asegúrate de que siempre haya un array, aunque no se marquen permisos
        $input['permissions'] = $request->has('permissions') ? $request->input('permissions') : [];

        $apikey = ApiKey::create($input);

        return redirect()->route('apikey.index')->with('success',  __('ApiKey creado con exito.'));
    }

    public function edit($id)
    {
        $apikey           = ApiKey::find($id);

        $expiresAt = Carbon::parse($apikey->expires_at); // Convertir la fecha a una instancia de Carbon
        $diasRestantes = Carbon::now()->diffInDays($expiresAt, false); // Calcular la diferencia en días

        $diasRestantes = round($diasRestantes); // Redondea al entero más cercano

        // Mostrar los días restantes
        if ($diasRestantes >= 0) {
            $diasDisponible = "Quedan $diasRestantes días.";
            $dias = $diasRestantes;
        } else {
            $diasDisponible = "La fecha ya expiró hace " . abs($diasRestantes) . " días.";
            $dias = 0;
        }

        $permisosDisponibles = RouteHelper::getApiRouteNames();

        $view           =   view('apikey.edit', compact('apikey', 'diasDisponible', 'dias', 'permisosDisponibles'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'api_key'    => 'required',
        'app_name'   => 'required',
    ]);

    $input = $request->all();
    $expira = is_numeric($input['expira']) ? intval($input['expira']) : 0;
    $input['expires_at'] = Carbon::now()->addDays($expira);

    // Asegúrate de que siempre haya un array, aunque no se marquen permisos
    $input['permissions'] = $request->has('permissions') ? $request->input('permissions') : [];

    $apikey = ApiKey::find($id);
    $apikey->update($input);

    return redirect()->route('apikey.index')->with('success', __('ApiKey actualizado con éxito.'));
}


    public function destroy($id)
    {
        $apikey           = ApiKey::find($id);
        $apikey->delete();
        return redirect()->back()->with('success', __('ApiKey eliminado con exito.'));
    }


    public function apikeyStatus(Request $request, $id)
    {
        $apikey = ApiKey::find($id);
        $input = ($request->value == "true") ? 1 : 0;
        if ($apikey) {
            $apikey->status = $input;
            $apikey->save();
        }
        return response()->json(['is_success' => true, 'message' => __('El estado del ApiKey se actualizo con exito.')]);
    }
}
