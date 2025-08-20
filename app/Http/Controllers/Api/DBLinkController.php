<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;

class DBLinkController extends Controller
{

    public function obtenerPaises(Request $request)
    { 
        $xmlBody = '
            <Lst_Obtener_Paises xmlns="http://servicios.rnp.hn/">
                <CodigoPais>' . $request->CodigoPais . '</CodigoPais>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Paises>';

        $response = $this->makeSoapRequest('Lst_Obtener_Paises', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Paises']]);
    }
    
    
    public function obtenerCiudadano(Request $request)
    { 
        
        $xmlBody = '
            <Lst_Obtener_Ciudadano xmlns="http://servicios.rnp.hn/">
                <DNICiudadano>' . $request->DNICiudadano . '</DNICiudadano>
                <DNIPadre>' . $request->DNIPadre . '</DNIPadre>
                <DNIMadre>' . $request->DNIMadre . '</DNIMadre>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Ciudadano>';

        $response = $this->makeSoapRequest('Lst_Obtener_Ciudadano', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Ciudadano']]);
    }
    
    public function obtenerDepartamentos(Request $request)
    { 
        
        $xmlBody = '
            <Lst_Obtener_Departamentos xmlns="http://servicios.rnp.hn/">
                <CodigoDepartamento>' . $request->CodigoDepartamento . '</CodigoDepartamento>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Departamentos>';

        $response = $this->makeSoapRequest('Lst_Obtener_Departamentos', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Departamento']]);
    }
    
    public function obtenerMunicipios(Request $request)
    { 
        $xmlBody = '
            <Lst_Obtener_Municipios xmlns="http://servicios.rnp.hn/">
                <CodigoDepartamento>' . $request->CodigoDepartamento . '</CodigoDepartamento>
                <CodigoMunicipio>' . $request->CodigoMunicipio . '</CodigoMunicipio>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Municipios>';

        $response = $this->makeSoapRequest('Lst_Obtener_Municipios', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Municipios']]);
    }
    
    public function obtenerBarrios(Request $request)
    { 
        $xmlBody = '
            <Lst_Obtener_Barrios xmlns="http://servicios.rnp.hn/">
                <CodigoDepartamento>' . $request->CodigoDepartamento . '</CodigoDepartamento>
                <CodigoMunicipio>' . $request->CodigoMunicipio . '</CodigoMunicipio>
                <CodigoCiudad>' . $request->CodigoCiudad . '</CodigoCiudad>
                <CodigoBarrio>' . $request->CodigoBarrio . '</CodigoBarrio>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Barrios>';

        $response = $this->makeSoapRequest('Lst_Obtener_Barrios', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Barrios']]);
    }
    
    
    public function obtenerGrupoEtnicos(Request $request)
    { 
        $xmlBody = '
            <Lst_Obtener_GrupoEtnico xmlns="http://servicios.rnp.hn/">
                <CodigoGrupoEtnico>' . $request->CodigoGrupoEtnico . '</CodigoGrupoEtnico>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_GrupoEtnico>';

        $response = $this->makeSoapRequest('Lst_Obtener_GrupoEtnico', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Grupo Etnicos']]);
    }
    
    
    public function obtenerParentesco(Request $request)
    { 
        $xmlBody = '
            <Lst_Obtener_Parentesco xmlns="http://servicios.rnp.hn/">
                <CodigoParentesco>' . $request->CodigoParentesco . '</CodigoParentesco>
                <MaxCount>' . $request->MaxCount . '</MaxCount>
                <SkipCount>' . $request->SkipCount . '</SkipCount>
                <Sorting>' . $request->Sorting . '</Sorting>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_Obtener_Parentesco>';

        $response = $this->makeSoapRequest('Lst_Obtener_Parentesco', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Parentesco']]);
    }
    
    
    
    
    


    /**
     * Función genérica para hacer solicitudes SOAP
     */
    private function makeSoapRequest($action, $xmlBody, $wsdl, $ws)
    {

        $fields = '
            <Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>' . $xmlBody . '</Body>
            </Envelope>';
            
            
       //die($fields);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $wsdl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "http://servicios.rnp.hn/' . $action . '"'
            ],
        ]);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return ['error' => "Error en la solicitud CURL: $error_msg"];
        }

        //die($response);
        curl_close($curl);
        return $this->parseSoapResponse($response, $action);
    }

    /**
     * Procesar respuesta SOAP
     */
    private function parseSoapResponse($response, $action)
    {
        try {
            $xml = simplexml_load_string($response);
            $namespaces = $xml->getNamespaces(true);
            $body = $xml->children($namespaces['soap'])->Body;
            $responseBody = $body->children($namespaces[''])->{$action . 'Response'};

            return $responseBody->{$action . 'Result'};
        } catch (Exception $e) {
            return ['error' => "Error procesando la respuesta XML: " . $e->getMessage()];
        }
    }





    private function realizarSolicitudSOAP($accion, $parametros)
    {
        $curl = curl_init();
        
        $soapEnvelope = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <' . $accion . ' xmlns="http://servicios.rnp.hn/">
                    ' . $parametros . '
                </' . $accion . '>
            </soap:Body>
        </soap:Envelope>';

        // https://soapservices.rnp.hn/API/WSkioskos.asmx
        // https://wstest.rnp.hn:1893/API/WSkioskos.asmx
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://129.146.180.156:82/api/WSAppsRNP.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$soapEnvelope,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: text/xml; charset=utf-8',
              'SOAPAction: http://servicios.rnp.hn/'. $accion
            ),
          ));
    
        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            die("Error en la solicitud CURL: $error_msg");
        }
        
        curl_close($curl);
    
        // Procesar la respuesta XML
        try {
            $xml = simplexml_load_string($response);
            $namespaces = $xml->getNamespaces(true);
            $body = $xml->children($namespaces['soap'])->Body;
            $responseBody = $body->children($namespaces[''])->{$accion . 'Response'};
        } catch (Exception $e) {
            die("Error procesando la respuesta XML: " . $e->getMessage());
        }
    
        return $responseBody->{$accion . 'Result'};
    }


}
