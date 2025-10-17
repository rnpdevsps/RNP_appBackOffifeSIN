<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;

class ArbolGenealogicoController extends Controller
{

    
    
    public function RevisionArbolxPadre($id = null)
    { 
        $xmlBody = '
            <Lst_RevisionArbolxPadre xmlns="http://servicios.rnp.hn/">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Lst_RevisionArbolxPadre>';

        $response = $this->makeSoapRequest('Lst_RevisionArbolxPadre', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        // Filtrar columnas no deseadas
        if (isset($response['RevArbolData'])) {
            foreach ($response['RevArbolData'] as &$item) {
                unset(
                    $item['OPTipoIdentidad'],
                    $item['OPNumeroIdentidad'],
                    $item['OPNombres'],
                    $item['OPPrimerApellido'],
                    $item['OMTipoIdentidad'],
                    $item['OMNumeroIdentidad'],
                    $item['OMNombres'],
                    $item['OMPrimerApellido'],
                    $item['TelefonoContacto'],
                    $item['CorreoContacto']
                );
            }
        }

        return ApiHelpers::success([$response], ['success' => ['Revision Arbol por Padre']]);
    }


    public function RevisionArbolxInscripcion($id = null)
    {
        
         $xmlBody = '
            <Qry_RevisionArbolxInscripcion xmlns="http://servicios.rnp.hn/">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Qry_RevisionArbolxInscripcion>';

        $response = $this->makeSoapRequest('Qry_RevisionArbolxInscripcion', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        // Filtrar columnas no deseadas
        if (isset($response['RevArbolData'])) {
            foreach ($response['RevArbolData'] as &$item) {
                unset(
                    $item['OPTipoIdentidad'],
                    $item['OPNumeroIdentidad'],
                    $item['OPNombres'],
                    $item['OPPrimerApellido'],
                    $item['OMTipoIdentidad'],
                    $item['OMNumeroIdentidad'],
                    $item['OMNombres'],
                    $item['OMPrimerApellido'],
                    $item['TelefonoContacto'],
                    $item['CorreoContacto']
                );
            }
        }

        return ApiHelpers::success([$response], ['success' => ['Revision Arbol por Inscripcion']]);
    }

    public function ActualizarRevisionArbolGen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'AprobadoXCiudadano' => 'required',
            'Comentarios' => 'required',
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }
        
        $xmlBody = '
            <Post_ActualizarRevisionArbolGen xmlns="http://servicios.rnp.hn/">
                <NumeroIdentidad>' . $request->NumeroIdentidad . '</NumeroIdentidad>
                <OrigenRevision>PEI</OrigenRevision>
                <AprobadoXCiudadano>' . $request->AprobadoXCiudadano . '</AprobadoXCiudadano>
                <Comentarios>' . $request->Comentarios . '</Comentarios>
                <CorreoElectronico>' . $request->CorreoElectronico . '</CorreoElectronico>
                <Telefono>' . $request->Telefono . '</Telefono>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Post_ActualizarRevisionArbolGen>';

        $response = $this->makeSoapRequest('Post_ActualizarRevisionArbolGen', $xmlBody, env('wsRNP_I'), "I");
        
    

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Actualizar Revision Arbol Genealogico']]);
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
