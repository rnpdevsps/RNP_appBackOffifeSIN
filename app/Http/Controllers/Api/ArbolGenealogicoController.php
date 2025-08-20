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
        $parametros = "
            <NumeroIdentidad>".$id."</NumeroIdentidad>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_RevisionArbolxPadre', $parametros)];
        $message = ['success' => [__('Revision Arbol por Padre')]];
        return ApiHelpers::success($data, $message);
    }


    public function RevisionArbolxPadreRESP($id = null)
    {
        $xmlBody = '
            <lst_RevisionArbolxPadre xmlns="http://tempuri.org/">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </lst_RevisionArbolxPadre>';

        $response = $this->makeSoapRequest('lst_RevisionArbolxPadre', $xmlBody, env('wsRNP_A')); 

        return ApiHelpers::success([$response], ['success' => ['Revision Arbol por Padre']]);

        $dataArray = [];
        $revData = $response->RevArbolData;
        $dataArray['RevArbolData'] = [
            'NumeroIdentidad'       => (string) $revData->NumeroIdentidad,
            'Nombres'               => (string) $revData->Nombres,
            'PrimerApellido'        => (string) $revData->PrimerApellido,
            'FechaNacimiento'       => (string) $revData->FechaNacimiento,

            'RPResultadoRevision'       => (string) $revData->RPResultadoRevision,
            'RPNumeroIdentidad'     => (string) $revData->RPNumeroIdentidad,
            'RPNombres'             => (string) $revData->RPNombres,
            'RPPrimerApellido'      => (string) $revData->RPPrimerApellido,
            'RPSegundoApellido'     => (string) $revData->RPSegundoApellido,
            'RPFechaNacimiento'       => (string) $revData->RPFechaNacimiento,
            'RPDescrDepartamento'     => (string) $revData->RPDescrDepartamento,
            'RPDescrMunicipio'             => (string) $revData->RPDescrMunicipio,
            'RMResultadoRevision'      => (string) $revData->RMResultadoRevision,
            'RMNumeroIdentidad'     => (string) $revData->RMNumeroIdentidad,
            'RMNombres'     => (string) $revData->RMNombres,
            'RMPrimerApellido'     => (string) $revData->RMPrimerApellido,
            'RMSegundoApellido'     => (string) $revData->RMSegundoApellido,
            'RMFechaNacimiento'     => (string) $revData->RMFechaNacimiento,
            'RMDescrDepartamento'     => (string) $revData->RMDescrDepartamento,
            'RMDescrMunicipio'     => (string) $revData->RMDescrMunicipio,

            'OrigenRevision'        => is_object($revData->OrigenRevision) ? '' : (string) $revData->OrigenRevision,
            'RevisadoXCiudadano'    => (string) $revData->RevisadoXCiudadano,
            'FechaRevision'         => is_object($revData->FechaRevision) ? '' : (string) $revData->FechaRevision,
            'AprobadoXCiudadano'    => (string) $revData->AprobadoXCiudadano,
            'ComentariosCiudadano'  => is_object($revData->ComentariosCiudadano) ? '' : (string) $revData->ComentariosCiudadano,
            'ProcesadoXRNP'         => (string) $revData->ProcesadoXRNP,
            'DetalleError' => [
                'TipoDeError'       => (string) $revData->DetalleError->TipoDeError ?? '',
                'DescripcionError'  => (string) $revData->DetalleError->DescripcionError ?? '',
            ],
        ];


        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$dataArray], ['success' => ['Revision Arbol por Padre']]);
    }

    public function RevisionArbolxInscripcion($id = null)
    {
        $xmlBody = '
            <qry_RevisionArbolxInscripcion xmlns="http://tempuri.org/">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </qry_RevisionArbolxInscripcion>';

        $response = $this->makeSoapRequest('qry_RevisionArbolxInscripcion', $xmlBody, env('wsRNP_A'));


        $dataArray = [];
        $revData = $response;
        $dataArray = [
            'NumeroIdentidad'       => (string) $revData->NumeroIdentidad,
            'Nombres'               => (string) $revData->Nombres,
            'PrimerApellido'        => (string) $revData->PrimerApellido,
            'FechaNacimiento'       => (string) $revData->FechaNacimiento,

            'RPResultadoRevision'       => (string) $revData->RPResultadoRevision,
            'RPNumeroIdentidad'     => (string) $revData->RPNumeroIdentidad,
            'RPNombres'             => (string) $revData->RPNombres,
            'RPPrimerApellido'      => (string) $revData->RPPrimerApellido,
            'RPSegundoApellido'     => (string) $revData->RPSegundoApellido,
            'RPFechaNacimiento'       => (string) $revData->RPFechaNacimiento,
            'RPDescrDepartamento'     => (string) $revData->RPDescrDepartamento,
            'RPDescrMunicipio'             => (string) $revData->RPDescrMunicipio,
            'RMResultadoRevision'      => (string) $revData->RMResultadoRevision,
            'RMNumeroIdentidad'     => (string) $revData->RMNumeroIdentidad,
            'RMNombres'     => (string) $revData->RMNombres,
            'RMPrimerApellido'     => (string) $revData->RMPrimerApellido,
            'RMSegundoApellido'     => (string) $revData->RMSegundoApellido,
            'RMFechaNacimiento'     => (string) $revData->RMFechaNacimiento,
            'RMDescrDepartamento'     => (string) $revData->RMDescrDepartamento,
            'RMDescrMunicipio'     => (string) $revData->RMDescrMunicipio,

            'OrigenRevision'        => is_object($revData->OrigenRevision) ? '' : (string) $revData->OrigenRevision,
            'RevisadoXCiudadano'    => (string) $revData->RevisadoXCiudadano,
            'FechaRevision'         => is_object($revData->FechaRevision) ? '' : (string) $revData->FechaRevision,
            'AprobadoXCiudadano'    => (string) $revData->AprobadoXCiudadano,
            'ComentariosCiudadano'  => is_object($revData->ComentariosCiudadano) ? '' : (string) $revData->ComentariosCiudadano,
            'ProcesadoXRNP'         => (string) $revData->ProcesadoXRNP,
            'DetalleError' => is_object($revData->DetalleError) ? '' : (string) $revData->DetalleError,
        ];

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$dataArray], ['success' => ['Revision Arbol por Inscripcion']]);
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
            <post_ActualizarRevisionArbolGen xmlns="http://tempuri.org/">
                <NumeroIdentidad>' . $request->NumeroIdentidad . '</NumeroIdentidad>
                <OrigenRevision>PEI</OrigenRevision>
                <AprobadoXCiudadano>' . $request->AprobadoXCiudadano . '</AprobadoXCiudadano>
                <Comentarios>' . $request->Comentarios . '</Comentarios>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </post_ActualizarRevisionArbolGen>';

        $response = $this->makeSoapRequest('post_ActualizarRevisionArbolGen', $xmlBody, env('wsRNP_A'));

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Actualizar Revision Arbol Genealogico']]);
    }

    /**
     * Función genérica para hacer solicitudes SOAP
     */
    private function makeSoapRequest($action, $xmlBody, $wsdl)
    {
        $fields = '<?xml version="1.0" encoding="utf-8"?>
            <Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
                <Body>' . $xmlBody . '</Body>
            </Envelope>';
        
        
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
                'Content-Type: text/xml; charset=utf-8'
            ],
        ]);

        $response = curl_exec($curl);
        //die($response); retornar el XML
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return ['error' => "Error en la solicitud CURL: $error_msg"];
        }

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
