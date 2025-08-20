<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;
use App\Models\Nnapren;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class WSController extends Controller
{
    public function obtenerPaises($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.REF_COUNTRY@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE CODE_COUNTRY = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_COUNTRY@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['paises' => $result];
        $message =  ['success'=>[__('Paises')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerDepartamentos($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.REF_DEPARTMENT@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE DEPARTMENT_CODE  = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_DEPARTMENT@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['departamentos' => $result];
        $message =  ['success'=>[__('Departamento')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerMunicipios($id = null, $id2 = null)
    {
        if (!empty($id)) {
            if (!empty($id2)) {
                $paises = "SELECT * FROM ENROLLMENT.REF_MUNICIPALITY@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE DEPARTMENT_CODE  = " . $id. " AND MUNICIPALITY_CODE  = " . $id2;
            } else {
                $paises = "SELECT * FROM ENROLLMENT.REF_MUNICIPALITY@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE DEPARTMENT_CODE  = " . $id;
            }
            
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_MUNICIPALITY@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['municipios' => $result];
        $message =  ['success'=>[__('Municipios')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerBarrios($id = null, $id2 = null, $id3 = null, $id4 = null)
    {
        if (!empty($id)) {
            if (!empty($id2) && empty($id3)) {
                $query = "SELECT * FROM ENROLLMENT.REF_BARRIO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2";
                $bindings = ['id' => $id, 'id2' => $id2];
            } elseif (!empty($id2) && !empty($id3) && empty($id4)) {
                $query = "SELECT * FROM ENROLLMENT.REF_BARRIO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2 
                          AND CITY_CODE = :id3";
                $bindings = ['id' => $id, 'id2' => $id2, 'id3' => $id3];
            } elseif (!empty($id2) && !empty($id3) && !empty($id4)) {
                $query = "SELECT * FROM ENROLLMENT.REF_BARRIO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM 
                          WHERE DEPARTMENT_CODE = :id 
                          AND MUNICIPALITY_CODE = :id2 
                          AND CITY_CODE = :id3 
                          AND suburb_code = :id4";
                $bindings = ['id' => $id, 'id2' => $id2, 'id3' => $id3, 'id4' => $id4];
            } else {
                $query = "SELECT * FROM ENROLLMENT.REF_BARRIO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM 
                WHERE DEPARTMENT_CODE = :id";
                $bindings = ['id' => $id];
            }
        } else {
            $query = "SELECT * FROM ENROLLMENT.REF_BARRIO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
            $bindings = [];
        }
    
        $result = \DB::select($query, $bindings);
        
        $data = ['barrios' => $result];
        $message =  ['success'=>[__('Barrios')]];
        return ApiHelpers::success($data, $message);
    }


    public function obtenerGrupoEtnicos($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.REF_GRUPO_ETNICO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE GRUPO_ETNICO_CODE  = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_GRUPO_ETNICO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['grupoetnicos' => $result];
        $message =  ['success'=>[__('Grupo Etnicos')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerParentesco($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.REF_PARENTESCO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE PARENTESCO_CODE  = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_PARENTESCO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['parentesco' => $result];
        $message =  ['success'=>[__('Parentesco')]];
        return ApiHelpers::success($data, $message);
    }

    public function obtenerGenero($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.REF_PARENTESCO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE PARENTESCO_CODE  = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.REF_PARENTESCO@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['parentesco' => $result];
        $message =  ['success'=>[__('Parentesco')]];
        return ApiHelpers::success($data, $message);
    }


    public function obtenerCiudadano($id = null)
    {
        if (!empty($id)) {
            $paises = "SELECT * FROM ENROLLMENT.CIUDADANOS@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM WHERE IDENTITY_NUMBER  = " . $id;
        } else {
            $paises = "SELECT * FROM ENROLLMENT.CIUDADANOS@ENROL.NETNLUALB.VCNZVJWP.ORACLEVCN.COM";
        }
        
        $result = \DB::select($paises);
        $data = ['ciudadano' => $result];
        $message =  ['success'=>[__('Ciudadano')]];
        return ApiHelpers::success($data, $message);
    }


    public function PreRegistroNNA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'DNI_NNA' => 'required'
        ]);

        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return ApiHelpers::validation($error);
        }

        $nnapren = Nnapren::where('DNI_NNA',$request->DNI_NNA)->first();
        if($nnapren){
            $error = ['error'=>[__("Ya existe una solicitud de pre-enrolamiento para este DNI.")]];
            return ApiHelpers::validation($error);
        }

        $input = $request->all();
        $input['FchDeclaPreRegistroNNA']           =  Carbon::now()->toDateTimeString();
        $input['EstadoDeclaPreRegistroNNA']        = "P";
        $input['FechaProcesaDeclaPreRegistroNNA']  = Carbon::now()->toDateTimeString();

        $datos = Nnapren::create($input);

        // Construir la respuesta con solo el ID y otro campo
        $data = [
            'PreRegistroNNA' => [
                'id' => $datos->id, // ID del registro
                'dni_nna' => $datos->DNI_NNA, // Otro campo específico
            ]
        ];
        $message =  ['success'=>[__('Registro Exitoso')]];
        return ApiHelpers::success($data, $message);

    }


    public function obtenerPreRegistroNNA($id = null)
    {

        if(empty($id)){
            $error = ['error'=>[__('Ingregar el DNI a consultar.')]];
            return ApiHelpers::validation($error);
        }

        $nnapren = Nnapren::where('DNI_NNA',$id)->first();

        
        $data = ['PreRegistroNNA' => $nnapren];
        $message =  ['success'=>[__('PreRegistroNNA')]];
        return ApiHelpers::success($data, $message);
    }



    public function obtenerCertificadoNacimiento($id = null)
    {
        $xmlBody = '
            <qry_CertificadoNacimiento xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </qry_CertificadoNacimiento>';

        $response = $this->makeSoapRequest('qry_CertificadoNacimiento', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Certificado Nacimiento']]);
    }



    
    public function obtenerArbolGenealogico($id = null)
    {
        $xmlBody = '
            <lst_ArbolGenealogico xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </lst_ArbolGenealogico>';

        $response = $this->makeSoapRequest('lst_ArbolGenealogico', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([$response], ['success' => ['Arbol Genealogico']]);
    }


    public function obtenerInscripcionNacimiento($id = null)
    {
        $xmlBody = '
            <Qry_InscripcionNacimiento xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Qry_InscripcionNacimiento>';

        $response = $this->makeSoapRequest('Qry_InscripcionNacimiento', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        $fechan = $response->FechaDeNacimiento ?? null;
        $edad = $fechan ? Carbon::parse($fechan)->age : null;

        return ApiHelpers::success([
            'InscripcionNacimiento' => $response,
            'Edad' => $edad,
            'FechaServidor' => Carbon::now()->format('d-m-Y')
        ], ['success' => ['Inscripción de Nacimiento']]);
    }

    public function obtenerValidacionDNI($id = null)
    {
        $xmlBodyDirec = '
            <Qry_DireccionInscrito xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Qry_DireccionInscrito>';

        $datadirec = $this->makeSoapRequest('Qry_DireccionInscrito', $xmlBodyDirec, env('wsRNP_I'), "I");

        if (isset($datadirec['error'])) {
            return response()->json(['error' => $datadirec['error']], 500);
        }

        $xmlBodyFoto = '
            <qry_FotoInscrito xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </qry_FotoInscrito>';

        $dataFoto = $this->makeSoapRequest('qry_FotoInscrito', $xmlBodyFoto, env('wsRNP_I'), "I");

        $fechan = $datadirec->Inscripcion->FechaDeNacimiento ?? null;
        $foto = $dataFoto->Foto ?? null;
        $edad = $fechan ? Carbon::parse($fechan)->age : null;

        $html = view('carnet', compact('datadirec', 'foto'))->render();
        $base64Html = base64_encode($html);
        //die($base64Html);

        $pdf = Pdf::loadHTML($html);
        $pdfContent = $pdf->output();
        $base64Pdf = base64_encode($pdfContent);
        //die($base64Pdf);

        //return  view('carnet', compact('datadirec', 'foto', ));

        $data = [$base64Pdf];
        $message = ['success' => [__('Validacion DNI')]];
        return ApiHelpers::success($data, $message);
    }



    public function obtenerIdentidadxCodigoBarras(Request $request)
    {
        $validator = Validator::make($request->all(), ['LinkCB' => 'required']);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $xmlBody = '
            <Qry_IdentidadxCodigoBarras xmlns="https://servicios.rnp.hn">
                <CodigoBarras>' . $request->LinkCB . '</CodigoBarras>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Qry_IdentidadxCodigoBarras>';

        $response = $this->makeSoapRequest('Qry_IdentidadxCodigoBarras', $xmlBody, env('wsRNP_I'), "I" );

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([
            'IdentidadxCodigoBarras' => $response,
            'FechaServidor' => Carbon::now()->format('d-m-Y')
        ], ['success' => ['Identidad por Código de Barras']]);
    }

    public function obtenerComparaFotoInscrito(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NumeroIdentidad' => 'required',
            'imgFoto' => 'required'
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $xmlBody = '
            <qry_ComparaFotoInscrito xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $request->NumeroIdentidad . '</NumeroIdentidad>
                <imgFoto>' . $request->imgFoto . '</imgFoto>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </qry_ComparaFotoInscrito>';

        $response = $this->makeSoapRequest('qry_ComparaFotoInscrito', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([
            'ComparaFotoInscrito' => $response,
            'FechaServidor' => Carbon::now()->format('d-m-Y')
        ], ['success' => ['Comparación de Foto de Inscrito']]);
    }

    public function obtenerInfoCompletaInscripcion($id = null)
    {
        $xmlBody = '
            <Qry_InfCompletaInscripcion xmlns="https://servicios.rnp.hn">
                <NumeroIdentidad>' . $id . '</NumeroIdentidad>
                <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
            </Qry_InfCompletaInscripcion>';

        $response = $this->makeSoapRequest('Qry_InfCompletaInscripcion', $xmlBody, env('wsRNP_I'), "I");

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return ApiHelpers::success([
            'InfoCompletaInscripcion' => $response,
            'FechaServidor' => Carbon::now()->format('d-m-Y')
        ], ['success' => ['Información Completa de Inscripción']]);
    }



    

    // PARA KIOSKO    
    public function DefuncionesKiosko($id = null)
    {
        $parametros = "
            <NumeroIdentidad>".$id."</NumeroIdentidad>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_Defunciones', $parametros)];
        $message = ['success' => [__('Defunciones')]];
        return ApiHelpers::success($data, $message);
    }
    
    public function MatrimoniosKiosko($id = null)
    {
        $parametros = "
            <NumeroIdentidad>".$id."</NumeroIdentidad>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_Matrimonios', $parametros)];
        $message = ['success' => [__('Matrimonios')]];
        return ApiHelpers::success($data, $message);
    }


    public function ArbolGenealogicoKiosko($id = null)
    {
        $parametros = "
            <identidad>".$id."</identidad>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
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
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
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
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_ComparaHuellaInscrito', $parametros)];
        $message = ['success' => [__('Compara Huella Inscrito')]];
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
            <identidadConsultante>".$request->identidadConsultante."</identidadConsultante>
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('Qry_CertificadoMatrimonio', $parametros)];
        $message = ['success' => [__('Certificado Matrimonio')]];
        return ApiHelpers::success($data, $message);
    }


    public function ListaKioskos()
    {
        $parametros = "
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_Kioscos', $parametros)];
        $message = ['success' => [__('Lista de Kioskos')]];
        return ApiHelpers::success($data, $message);
    }


    public function LugaresdeEntregaDNIKiosko($id = null)
    {
        $parametros = "
            <CodigoKiosco>".$id."</CodigoKiosco>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('lst_LugaresEntregaDNI', $parametros)];
        $message = ['success' => [__('Lugares  de Entrega DNI')]];
        return ApiHelpers::success($data, $message);
    }

    public function RecuperarUltimoDNIKiosko($id = null)
    {
        $parametros = "
            <identidad>".$id."</identidad>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('RecuperarUltimoDNI', $parametros)];
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
    CURLOPT_URL => 'https://soapservices.rnp.hn/API/WSkioskos.asmx',
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
            <identidad>0501199503745</identidad>
            <EsTerceraEdad>false</EsTerceraEdad>
            <TipoCertificacion>1</TipoCertificacion>
            <MontoTotal>200</MontoTotal>
            <CodigoInstitucion>APP$1N2.0</CodigoInstitucion>
            <CodigoSeguridad>EC76FBF321184A7FA6C29EE73427C31E</CodigoSeguridad>
            <UsuarioInstitucion>REPODNI</UsuarioInstitucion>
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

// Convertir la respuesta XML en un objeto
$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json, true);

// Extraer el contenido de "crearReciboTGR1Result"
$body = $array['soap:Body'] ?? [];
$responseData = $body['crearReciboTGR1Response']['crearReciboTGR1Result'] ?? [];

return response()->json($responseData);

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
            <identidad>".$request->numeroIdentidad."</identidad>
            <NumeroRecibo>".$request->numeroRecibo."</NumeroRecibo>
            <CodigoInstitucion>" . env('CodigoInstitucion_Kiosko') . "</CodigoInstitucion>
            <CodigoSeguridad>" . env('CodigoSeguridad_Kiosko') . "</CodigoSeguridad>
            <UsuarioInstitucion>" . env('UsuarioInstitucion_Kiosko') . "</UsuarioInstitucion>
        ";
        
        $data = [$this->realizarSolicitudSOAP('RecuperarReciboTGR1', $parametros)];
        $message = ['success' => [__('Recuperar DNI')]];
        return ApiHelpers::success($data, $message);
    }








    /**
     * Función genérica para hacer solicitudes SOAP
     */
    private function makeSoapRequest($action, $xmlBody, $wsdl, $ws)
    {
        if ($ws == 'I') {
            $fields = '<?xml version="1.0" encoding="utf-8"?>
                <Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
                    <Body>' . $xmlBody . '</Body>
                </Envelope>';
        } else {
            $fields = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>' . $xmlBody . '</soap:Body>
                </soap:Envelope>';
        }
        
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
                'SOAPAction: "https://servicios.rnp.hn/' . $action . '"'
            ],
        ]);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return ['error' => "Error en la solicitud CURL: $error_msg"];
        }

        die($response);
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
            CURLOPT_URL => 'https://soapservices.rnp.hn/API/WSkioskos.asmx',
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
