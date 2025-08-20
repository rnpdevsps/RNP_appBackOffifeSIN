<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;
use App\Models\Nnapren;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Auth;


class WSHuellaController extends Controller
{
    public $dni;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required',
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $user = Auth::user();
        $huellas = [];
        for ($i = 1; $i <= 10; $i++) {
            $key = 'finger_' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $huellas[$i] = $request->input($key, '');
        }

        return $this->webService($huellas, $request->dni , $request, false, $user);
    }


    private function webService($huellas, $dni, $request, $identify, $user)
    {
        try {
            $methodName = $identify ? 'mbssIdentify_wsq' : 'mbssVerify_wsq';
            $identity_number = $identify ? '' : '<identity_number>' . $dni . '</identity_number>';
            $this->dni = $dni;

            $xmlRequest = $this->buildXmlRequest($methodName, $identity_number, $huellas);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => env('wsRNPHuella'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 3000,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xmlRequest,
                CURLOPT_HTTPHEADER => ['Content-Type: text/xml; charset=utf-8'],
            ]);

            $result = curl_exec($curl);

            if(curl_errno($curl)) {
                // Handle the error
                echo 'Error:' . curl_error($curl);
            }
            
            curl_close($curl);

            if ($result === false) {
                throw new \Exception('Error al comunicarse con el servicio web.');
            }

            // Procesar la respuesta XML
            $response = $this->processWebServiceResponse($result, $methodName, $huellas, $request, $user);

            return response()->json($response);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function buildXmlRequest($methodName, $identity_number, $huellas)
    {
        $xmlRequest = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
            <' . $methodName . ' xmlns="http://honduras.rnp.gob/">
            <transaction_number>' . env('transaction_number') . '</transaction_number>
            <transaction_client>' . env('transaction_client') . '</transaction_client>
            ' . $identity_number;

        foreach ($huellas as $index => $huella) {
            $xmlRequest .= '<wsq_finger_' . str_pad($index, 2, '0', STR_PAD_LEFT) . '>' . $huella . '</wsq_finger_' . str_pad($index, 2, '0', STR_PAD_LEFT) . '>';
        }

        $xmlRequest .= '</' . $methodName . '>
        </soap:Body>
        </soap:Envelope>';

        return $xmlRequest;
    }


    private function processWebServiceResponse($result, $methodName, $huellas, $request, $user)
    {
        $xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        $body = $xml->children('soap', true)->Body->children('http://honduras.rnp.gob/');
        $mbssResult = $body->{$methodName . 'Response'}->{$methodName . 'Result'};

        $mbssResultArray = json_decode(json_encode((array)$mbssResult), true);

        $requestId = (string) $mbssResult->RequestId;
        $resultCode = (string) $mbssResult->ResultCode;
        $errorCode = (int) $mbssResult->ErrorCode;
        $Decision = ($methodName === "mbssIdentify_wsq") ? (string) $mbssResult->Decision : (string) $mbssResult->ResultCode;
        $candidateCount = (int) $mbssResult->CandidateCount;

        if ($Decision === "HIT") {
            $message = "Huella validada con éxito.";
        } else {
            $message = "Huella no válidada.";
        }

        return ApiHelpers::onlysuccess($message);

    }

}
