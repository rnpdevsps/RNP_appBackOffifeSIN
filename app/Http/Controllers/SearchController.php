<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaeNotario;
use DB;

class SearchController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('query');

        $resultados = MaeNotario::where('dni', 'LIKE', "%$query%")->where('status', 1)->get();

        if ($resultados->isEmpty()) {
            return response()->json(['mensaje' => 'No se encontraron resultados.']);
        } else {
            return response()->json($resultados);
        }
    }


    public function buscarDNIApi(Request $request)
    {
        $query = $request->input('query');

        // Crear instancia de SoapClient
        $client = new \SoapClient(env('wsRNP')."/WSInscripciones.asmx?WSDL");

        // Establecer los parámetros para Qry_InscripcionNacimiento
        $params = array(
            'NumeroIdentidad' => $query,
            'CodigoInstitucion' => env('CodigoInstitucion'),
            'CodigoSeguridad' => env('CodigoSeguridad'),
            'UsuarioInstitucion' => env('UsuarioInstitucion'),
        );

        // Pasa parametros al metodo Qry_InscripcionNacimiento para obtener resultados
        $result = $client->Qry_InscripcionNacimiento($params);

        // Convertir a formato json
        $json_result = json_encode($result);

        // Resultado
        echo $json_result;

    }

    public function obtenerListaElectoral($id)
    {
        $personal = DB::table('personal_votaciones')->where('periodo', $id)->get();
        return response()->json($personal);
    }


    public function obtenerMunicipiosPorDeptos($id)
    {
        $Municipios = DB::table('municipios')->where('codigodepto', $id)->get();
        return response()->json($Municipios);
    }

    public function obtenerRCMS($id1, $id2)
    {
        $rcms = DB::table('rcms')->where('idDepto', $id1)->where('idMunicipio', $id2)->get();
        return response()->json($rcms);
    }

    public function obtenerEmpleados($id1, $id2, $id3)
    {
        $empleados = DB::table('empleados')->where('idDepto', $id1)->where('idMunicipio', $id2)->where('rcm_id', $id3)->get();
        return response()->json($empleados);
    }

    public function obtenerStatusporLocal($id) 
    {
        $local = DB::table('tipo_inmuebles')->where('id', $id)->first();
        return response()->json($local);
    }

    public function obtenerAldeasPorMunicipios($id1, $id2)
    {
        $Aldeas = DB::table('tbl_dpge_a_aldea')->where('CODIGO_DEPARTAMENTO', $id1)->where('CODIGO_MUNICIPIO', $id2)->get();
        return response()->json($Aldeas);
    }
}


