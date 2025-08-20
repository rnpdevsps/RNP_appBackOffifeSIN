<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;

class DBLinkController extends Controller
{
    public function obtenerPaises($id = null)
    {
        if (!empty($id)) {
            $paises = "Select * from enrollment.REF_COUNTRY@enroll WHERE CODE_COUNTRY = " . $id;
        } else {
            $paises = "Select * from enrollment.REF_COUNTRY@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['paises' => $result];
        $message =  ['success'=>[__('Paises')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerDepartamentos($id = null)
    {
        if (!empty($id)) {
            $paises = "Select * from enrollment.REF_DEPARTMENT@enroll WHERE DEPARTMENT_CODE  = " . $id;
        } else {
            $paises = "Select * from enrollment.REF_DEPARTMENT@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['departamentos' => $result];
        $message =  ['success'=>[__('Departamento')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerMunicipios($id = null, $id2 = null)
    {
        if (!empty($id)) {
            if (!empty($id2)) {
                $paises = "Select * from enrollment.REF_MUNICIPALITY@enroll WHERE DEPARTMENT_CODE  = " . $id. " AND MUNICIPALITY_CODE  = " . $id2;
            } else {
                $paises = "Select * from enrollment.REF_MUNICIPALITY@enroll WHERE DEPARTMENT_CODE  = " . $id;
            }
            
        } else {
            $paises = "Select * from enrollment.REF_MUNICIPALITY@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['municipios' => $result];
        $message =  ['success'=>[__('Municipios')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerBarrios($id = null, $id2 = null, $id3 = null, $id4 = null)
    {
        if (!empty($id)) {
            if (!empty($id2) && empty($id3)) {
                $query = "Select * from enrollment.REF_BARRIO@enroll 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2";
                $bindings = ['id' => $id, 'id2' => $id2];
            } elseif (!empty($id2) && !empty($id3) && empty($id4)) {
                $query = "Select * from enrollment.REF_BARRIO@enroll 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2 
                          AND CITY_CODE = :id3";
                $bindings = ['id' => $id, 'id2' => $id2, 'id3' => $id3];
            } elseif (!empty($id2) && !empty($id3) && !empty($id4)) {
                $query = "Select * from enrollment.REF_BARRIO@enroll 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2 
                          AND CITY_CODE = :id3 
                          AND suburb_code = :id4";
                $bindings = ['id' => $id, 'id2' => $id2, 'id3' => $id3, 'id4' => $id4];
            } else {
                $query = "Select * from enrollment.REF_BARRIO@enroll 
                WHERE DEPARTMENT_CODE = :id";
                $bindings = ['id' => $id];
            }
        } else {
            $query = "Select * from enrollment.REF_BARRIO@enroll";
            $bindings = [];
        }
    
        $result = \DB::connection('oracle3')->select($query, $bindings);
        
        $data = ['barrios' => $result];
        $message =  ['success'=>[__('Barrios')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerGrupoEtnicos($id = null)
    {
        if (!empty($id)) {
            $paises = "Select * from enrollment.REF_GRUPO_ETNICO@enroll WHERE GRUPO_ETNICO_CODE  = " . $id;
        } else {
            $paises = "Select * from enrollment.REF_GRUPO_ETNICO@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['grupoetnicos' => $result];
        $message =  ['success'=>[__('Grupo Etnicos')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerParentesco($id = null)
    {
        if (!empty($id)) {
            $paises = "Select * from enrollment.REF_PARENTESCO@enroll WHERE PARENTESCO_CODE  = " . $id;
        } else {
            $paises = "Select * from enrollment.REF_PARENTESCO@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['parentesco' => $result];
        $message =  ['success'=>[__('Parentesco')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerGenero($id = null)
    {
        if (!empty($id)) {
            $paises = "Select * from enrollment.REF_PARENTESCO@enroll WHERE PARENTESCO_CODE  = " . $id;
        } else {
            $paises = "Select * from enrollment.REF_PARENTESCO@enroll";
        }
        
        $result = \DB::connection('oracle3')->select($paises);
        $data = ['parentesco' => $result];
        $message =  ['success'=>[__('Parentesco')]];
        return ApiHelpers::success($data, $message);
    }


    public function obtenerCiudadano(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dniPrincipal' => 'required',
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        if (empty($request->dniPadre) && empty($request->dniMadre)) {
          $buscar = [$request->dniPrincipal];
        }
        if (!empty($request->dniPadre) && empty($request->dniMadre)) {
          $buscar = [$request->dniPrincipal, $request->dniPadre];
        }
        if (empty($request->dniPadre) && !empty($request->dniMadre)) {
          $buscar = [$request->dniPrincipal, $request->dniMadre];
        }
        if (!empty($request->dniPadre) && !empty($request->dniMadre)) {
          $buscar = [$request->dniPrincipal, $request->dniPadre, $request->dniMadre];
        }

        $placeholders = implode(',', array_fill(0, count($buscar), '?'));

        if (!empty($request->dniPrincipal)) {
            $sql = "SELECT * FROM enrollment.CIUDADANOS@enroll WHERE IDENTITY_NUMBER IN ($placeholders)";
        } else {
            $sql = "Select * from enrollment.CIUDADANOS@enroll";
        }
        
        //$result = \DB::connection('oracle3')->select($paises);
        $result = \DB::connection('oracle3')->select($sql, $buscar);
        $data = ['ciudadano' => $result];
        $message =  ['success'=>[__('Ciudadano')]];
        return ApiHelpers::success($data, $message);
    }

}
