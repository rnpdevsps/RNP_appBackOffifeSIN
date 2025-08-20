<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;
use App\Models\Nnapren;

class WSKioskosController extends Controller
{
    // PARA KIOSKO    
    public function DefuncionesKiosko($id = null)
    {
        $parametros = "
            <NumeroIdentidad>".$id."</NumeroIdentidad>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Lst_Defunciones', $parametros)];
        $message = ['success' => [__('Defunciones')]];
        return ApiHelpers::success($data, $message);
    }
    
    public function MatrimoniosKiosko($id = null)
    {
        $parametros = "
            <NumeroIdentidad>".$id."</NumeroIdentidad>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Lst_Matrimonios', $parametros)];
        $message = ['success' => [__('Matrimonios')]];
        return ApiHelpers::success($data, $message);
    }


    public function ArbolGenealogicoKiosko($id = null)
    {
        $parametros = "
            <identidad>".$id."</identidad>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ArbolGenealogico', $parametros)];
        $message = ['success' => [__('Arbol Genealogico')]];
        return ApiHelpers::success($data, $message);
    }

    public function ComparaFotoInscritoKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'imgFoto' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->NumeroIdentidad."</NumeroIdentidad>
            <imgFoto>".$request->imgFoto."</imgFoto>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ComparaFotoInscrito', $parametros)];
        $message = ['success' => [__('Compara Foto Inscrito')]];
        return ApiHelpers::success($data, $message);
    }

    public function ComparaHuellaInscritoKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'imgHuella' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->numeroIdentidad."</NumeroIdentidad>
            <imgHuella>".$request->imgHuella."</imgHuella>
            <Digito>0</Digito>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ComparaHuellaInscrito', $parametros)];
        $message = ['success' => [__('Compara Huella Inscrito')]];
        return ApiHelpers::success($data, $message);
    }


    public function ListaKioskos()
    {
        $parametros = "
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_Kioscos', $parametros)];
        $message = ['success' => [__('Lista de Kioskos')]];
        return ApiHelpers::success($data, $message);
    }


    public function LugaresdeEntregaDNIKiosko($id = null)
    {
        $parametros = "
            <CodigoKiosco>".$id."</CodigoKiosco>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Lst_LugaresEntregaDNI', $parametros)];
        $message = ['success' => [__('Lugares  de Entrega DNI')]];
        return ApiHelpers::success($data, $message);
    }

    public function RecuperarUltimoDNIKiosko($id = null)
    {
        $parametros = "
            <identidad>".$id."</identidad>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_RecuperarUltimoDNI', $parametros)];
        $message = ['success' => [__('Ultimo DNI')]];
        return ApiHelpers::success($data, $message);
    }

    public function crearReciboTGRKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numeroIdentidad' => 'required',
            'esTerceraEdad' => 'required',
            'tipoCertificacion' => 'required',
            'montoTotal' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }


        $curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => env('wsRNP_Kiosko'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <crearReciboTGR1 xmlns="http://servicios.rnp.hn/">
            <identidad>'.$request->numeroIdentidad.'</identidad>
            <EsTerceraEdad>'.$request->esTerceraEdad.'</EsTerceraEdad>
            <TipoCertificacion>'.$request->tipoCertificacion.'</TipoCertificacion>
            <MontoTotal>'.$request->montoTotal.'</MontoTotal>
            <CodigoInstitucion>'.env('CodigoInstitucion').'</CodigoInstitucion>
            <CodigoSeguridad>'.env('CodigoSeguridad').'</CodigoSeguridad>
            <UsuarioInstitucion>'.env('UsuarioInstitucion').'</UsuarioInstitucion>
        </crearReciboTGR1>
    </soap:Body>
</soap:Envelope>',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: http://servicios.rnp.hn/crearReciboTGR1'
    ),
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    curl_close($curl);
    return response()->json(['error' => "Error en la solicitud CURL: $error_msg"], 500);
}

curl_close($curl);

// Cargar el XML con manejo de espacios de nombres
$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$namespaces = $xml->getNamespaces(true);

// Acceder a Body sin necesidad de namespace
$body = $xml->children($namespaces['soap'])->Body;

// Acceder a la respuesta usando el namespace correcto
$responseData = $body->children($namespaces[''])->crearReciboTGR1Response->crearReciboTGR1Result;

// Convertir a JSON
$json = json_encode($responseData);
$array = json_decode($json, true);

$data = ['recibo' => $array];
        $message =  ['success'=>[__('Crear Recibo')]];
        return ApiHelpers::success($data, $message);



    }

    public function RecuperarReciboTGR1Kiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numeroIdentidad' => 'required',
            'numeroRecibo' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_RecuperarReciboTGR1', $parametros)];
        $message = ['success' => [__('Recuperar Recibo TGR')]];
        return ApiHelpers::success($data, $message);
    }


    public function CertificadoMatrimonioKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numeroIdentidad' => 'required',
            'identidadConsultante' => 'required',
            'numeroRecibo' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->numeroIdentidad."</NumeroIdentidad>
            <IdentidadConsultante>".$request->identidadConsultante."</IdentidadConsultante>
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_CertificadoMatrimonio', $parametros)];
        $message = ['success' => [__('Certificado Matrimonio')]];
        return ApiHelpers::success($data, $message);
    }



    public function CertificadoDefuncionKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numeroIdentidad' => 'required',
            'identidadConsultante' => 'required',
            'numeroRecibo' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->numeroIdentidad."</NumeroIdentidad>
            <IdentidadConsultante>".$request->identidadConsultante."</IdentidadConsultante>
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_CertificadoDefuncion', $parametros)];
        $message = ['success' => [__('Certificado Defuncion')]];
        return ApiHelpers::success($data, $message);
    }


    public function ValidarReciboTGR1ConOrigenKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numeroRecibo' => 'required',
            'numeroIdentidad' => 'required',
            'tipoCertificacion' => 'required',
            'codigoDepartamentoEmision' => 'required',
            'codigoMunicipioEmision' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <NumeroIdentidad>".$request->numeroIdentidad."</NumeroIdentidad>
            <TipoCertificado>".$request->tipoCertificacion."</TipoCertificado>
            <CodigoDepartamentoEmision>".$request->codigoDepartamentoEmision."</CodigoDepartamentoEmision>
            <CodigoMunicipioEmision>".$request->codigoMunicipioEmision."</CodigoMunicipioEmision>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('ValidarReciboTGR1ConOrigen', $parametros)];
        $message = ['success' => [__('Validar Recibo TGR con Origen')]];
        return ApiHelpers::success($data, $message);
    }



    public function ComprobanteReposicionDNIKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'NumeroSeguimiento' => 'required',
            'NumeroRecibo' => 'required',
            'CodigoUbicacionEntrega' => 'required',
            'NumeroReferencia' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->NumeroIdentidad."</NumeroIdentidad>
            <NumeroSeguimiento>".$request->NumeroSeguimiento."</NumeroSeguimiento>
            <NumeroRecibo>".$request->NumeroRecibo."</NumeroRecibo>
            <CodigoUbicacionEntrega>".$request->CodigoUbicacionEntrega."</CodigoUbicacionEntrega>
            <NumeroReferencia>".$request->NumeroReferencia."</NumeroReferencia>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ComprobanteReposicionDNI', $parametros)];
        $message = ['success' => [__('Comprobante Reposicion DNI')]];
        return ApiHelpers::success($data, $message);
    }


    public function ReimpresionReposicionDNIKiosko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'CodigoGUID' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $parametros = "
            <NumeroIdentidad>".$request->NumeroIdentidad."</NumeroIdentidad>
            <CodigoGUID>".$request->CodigoGUID."</CodigoGUID>
            <CodigoInstitucion>" . env('CodigoInstitucion') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ReimpresionReposicionDNI', $parametros)];
        $message = ['success' => [__('Reimpresion Reposicion DNI')]];
        return ApiHelpers::success($data, $message);
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
