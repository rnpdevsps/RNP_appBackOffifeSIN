<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Api\Helpers;
use Illuminate\Http\Request;
use App\Models\Rcm;
use App\Models\Depto;
use App\Models\Municipio;
use App\Models\Clasificacion;
use Illuminate\Support\Facades\Storage;


class ApiRcmsController extends Controller
{
    public function obtenerListaRCM(Request $request)
    {
        $query = Rcm::select(
            'id',
            'codigo',
            'name',
            'id_clasificacion',
            'idDepto',
            'idMunicipio',
            'latitud',
            'longitud',
            'direccion',
            'telefono',
            'foto'
        )->whereNotNull('latitud');
    
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
        if ($request->has('idClasificacion')) {
            $query->where('id_clasificacion', $request->idClasificacion);
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
        $perPage = $request->get('per_page', 30);
    
        $data = $query->paginate($perPage);
        
        // Transformar foto -> URL completa del storage
        $data->getCollection()->transform(function ($item) {
            $item->foto = $item->foto ? \Storage::url($item->foto) : null;
            return $item;
        });
    
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
        return Helpers::success($data, $message);
    }

    public function obtenerMunicipiosRCM($id = null)
    {
        $municipios = Municipio::select('id', 'codigodepto', 'codigomunicipio', 'nombremunicipio')->where('codigodepto', $id)->get();

        $data = ['municipios' => $municipios];
        $message =  ['success'=>[__('Municipios')]];
        return ::success($data,$message);
    }

    public function obtenerClasificacionRCM()
    {
        $query = Clasificacion::select('id', 'name')->orderBy('id', 'asc')->get();

        return ::success(
            ['clasificaciones' => $query],
            ['success' => [__('Clasificaciones')]]
        );
    }

}
