<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Rcm;
use App\Models\Depto;
use App\Models\Municipio;

class RcmsController extends Controller
{
    public function obtenerListaRCM(Request $request)
    {
        $query = Rcm::query();

        // Filtro por id
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        // Filtro por estado
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por departamento
        if ($request->has('idDepto')) {
            $query->where('idDepto', $request->idDepto);
        }

        // Filtro por municipio
        if ($request->has('idMunicipio')) {
            $query->where('idMunicipio', $request->idMunicipio);
        }

        // Filtro por clasificación
        if ($request->has('id_clasificacion')) {
            $query->where('id_clasificacion', $request->id_clasificacion);
        }

        // Búsqueda por nombre o código
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('codigo', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Relaciones opcionales
        if ($request->has('with')) {
            $relations = explode(',', $request->with);
            $query->with($relations);
        }

        // Orden dinámico
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Paginación dinámica
        $perPage = $request->get('per_page', 15);

        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function obtenerDeptosRCM()
    {
        $deptos = Depto::select('id', 'codigodepto', 'nombredepto')->get();

        $data = ['deptos' => $deptos];
        $message = ['success' => [__('Departamentos')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerMunicipiosRCM($id = null)
    {
        $municipios = Municipio::select('id', 'codigodepto', 'codigomunicipio', 'nombremunicipio')->where('codigodepto', $id)->get();

        $data = ['municipios' => $municipios];
        $message =  ['success'=>[__('Municipios')]];
        return ApiHelpers::success($data,$message);
    }
}
