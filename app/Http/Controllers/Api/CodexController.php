<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;

class CodexController extends Controller
{

    public function ObtenerExpedientes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idExpediente' => 'required',
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $idExpediente = $request->idExpediente; 

        $curl = curl_init();
        
        $xml = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <soap12:Envelope xmlns:soap12="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/">
          <soap12:Body>
            <tem:ObtenerExpedientes>
              <tem:idExpediente>{$idExpediente}</tem:idExpediente>
            </tem:ObtenerExpedientes>
          </soap12:Body>
        </soap12:Envelope>
        XML;

        curl_setopt_array($curl, array(
        CURLOPT_URL => env('wsRNP_C'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $xml,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "http://tempuri.org/ObtenerExpedientes"'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            echo json_encode(['error' => 'Error al realizar la solicitud SOAP']);
            exit;
        }

        $xmlObject = simplexml_load_string($response);
        $namespaces = $xmlObject->getNamespaces(true);
        $body = $xmlObject->children($namespaces['soap'])->Body;
        $result = $body->children($namespaces[''])->ObtenerExpedientesResponse->ObtenerExpedientesResult;

        $expedientesArray = json_decode(json_encode($result), true);
        return ApiHelpers::success([$expedientesArray], ['success' => ['Expedientes']]);
    }

}
