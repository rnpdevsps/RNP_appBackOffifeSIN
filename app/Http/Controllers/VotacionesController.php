<?php

namespace App\Http\Controllers;

use Symfony\Component\DomCrawler\Crawler;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Rcm;
use App\Models\Empleado;
use App\Models\Marcaje;
use App\Models\EstadoInhabilitado;
use App\Models\BitacoraRcm;
use App\Models\Contrato;
use App\DataTables\MarcajesDataTable;
use App\Facades\UtilityFacades;
use App\Exports\SalesReport;
use Validator;
use Session;
use Carbon\Carbon;
use DB;
use Hash;
use PDF;
use App\Models\PersonalVotaciones;
use App\Models\MultipleChoice;
use App\Models\Poll;
use App\Models\Candidatos;

use GuzzleHttp\Client;


class VotacionesController extends Controller
{
    function __construct()
    {

    }

    public function buscarDNI(Request $request)
    {
        $empleado = PersonalVotaciones::where('dni', $request->codigo)->first();

        if (!$empleado) {
            return response()->json([
                'status' => false,
                'message' => 'DNI no esta autorizado para votar.',
                'icon' => "success",
            ], 200);
        }

        $voto = MultipleChoice::where('dni', $request->codigo)->first();

        if ($voto) {
            return response()->json([
                'status' => false,
                'message' => 'DNI ya realizo la votación para la encuesta actual.',
                'icon' => "success",
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => $empleado->nombre,
            'flag' => $empleado->flag,
            'icon' => "success",
        ], 200);


    }
    
    public function registrarVotoSinHuella(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Continuar.',
            'icon' => "success",
        ], 200);
    }



    public function store(Request $request)
    {
        $huella = $request->huella;
        $dni = $request->dni;

        //die($dni);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('wsRNP') . "/WSInscripciones.asmx",
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
        <Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
            <Body>
                <qry_ComparaHuellaInscrito xmlns="https://servicios.rnp.hn">
                    <NumeroIdentidad>' . $dni . '</NumeroIdentidad>
                    <imgHuella>' . $huella . '</imgHuella>
                    <Digito>0</Digito>
                    <CodigoInstitucion>' . env('CodigoInstitucion') . '</CodigoInstitucion>
                    <CodigoSeguridad>' . env('CodigoSeguridad') . '</CodigoSeguridad>
                    <UsuarioInstitucion>' . env('UsuarioInstitucion') . '</UsuarioInstitucion>
                </qry_ComparaHuellaInscrito>
            </Body>
        </Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "https://servicios.rnp.hn/qry_ComparaHuellaInscrito"'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            die("Error en la solicitud CURL: $error_msg");
        }

        curl_close($curl);

        //die( $response);

        // Procesar la respuesta XML
        try {
            $xml = simplexml_load_string($response);
            $namespaces = $xml->getNamespaces(true);
            $body = $xml->children($namespaces['soap'])->Body;
            $responseBody = $body->children($namespaces[''])->qry_ComparaHuellaInscritoResponse;
            $result = (string) $responseBody->qry_ComparaHuellaInscritoResult;
            $respuesta = $result;
        } catch (Exception $e) {
            die("Error procesando la respuesta XML: " . $e->getMessage());
        }

        if ($respuesta == "SI") {
                return response()->json([
                    'status' => true,
                    'message' => 'Continuar.',
                    'icon' => "success",
                ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Intente marcar de nuevo.',
                'icon' => "error",
            ], 200);
        }


    }


    public function reporteElectoral($id)
    {
        $poll = Poll::find($id);
        $titulo = "Reporte Electoral <br>".$poll->title;
        $view =   view('poll.reports', compact('poll'));
        return ['html' => $view->render(),'titulo' => $titulo];
    }


    // Reporte Electoral
    public function reportElectoral(Request $request)
{
    $typePDF = $request->typePDF;
    $fileType = $request->fileType;
    $visualizarPDF =  $request->visualizarPDF;

    $pollId = $request->idVotacion;
    $poll = Poll::findOrFail($pollId);

    $puestos = collect(json_decode($poll->multiple_answer_options)->multiple_answer_options ?? [])
        ->pluck('answer_options');

    $candidatos = Candidatos::where('periodo', $poll->periodo)->get();

    if ($typePDF === 'candidatos') {
        // === Mismo comportamiento actual ===
        $candidatosConVotos = [];

        foreach ($candidatos as $candidato) {
            $votosRaw = MultipleChoice::where('candidato_id', $candidato->id)
                ->selectRaw('vote, COUNT(*) as total')
                ->groupBy('vote')
                ->pluck('total', 'vote');

            $votos = [];
            foreach ($puestos as $puesto) {
                $votos[$puesto] = $votosRaw[$puesto] ?? 0;
            }

            $candidatosConVotos[] = [
                'nombre' => optional($candidato->personalVotaciones)->nombre ?? 'N/A',
                'dni' => optional($candidato->personalVotaciones)->dni ?? 'N/A',
                'votos' => $votos,
            ];
        }

        if (empty($candidatosConVotos)) {
            Session::flash('warning', 'No hay datos para exportar');
            return back();
        }

        $headerHTML = view('reports.headerVPDF', ['poll' => $poll])->render();

        $pdf = PDF::loadView('reports.votos-candidatos-pdf', [
            'poll' => $poll,
            'candidatosConVotos' => $candidatosConVotos
        ])
        ->setOption('margin-top', 40)
        ->setOption('margin-bottom', 15)
        ->setOption('header-html', $headerHTML)
        ->setOption('footer-center', 'Página [page] de [topage]')
        ->setOption('footer-font-size', 8);

        return ($visualizarPDF == "si") ? $pdf->inline('Reporte_Electoral.pdf') : $pdf->download('Reporte_Electoral.pdf');

    } elseif ($typePDF === 'puesto') {
        // === Nuevo comportamiento para resumen por cargo ===
        $resumen = [];

        foreach ($puestos as $puesto) {
            $mayor = MultipleChoice::where('vote', $puesto)
                ->selectRaw('candidato_id, COUNT(*) as total')
                ->groupBy('candidato_id')
                ->orderByDesc('total')
                ->first();

            if ($mayor) {
                $candidato = Candidatos::find($mayor->candidato_id);
                $nombre = optional($candidato->personalVotaciones)->nombre ?? 'N/A';
                $dni = optional($candidato->personalVotaciones)->dni ?? 'N/A';

                $resumen[] = [
                    'puesto' => $puesto,
                    'nombre' => $nombre,
                    'dni' => $dni,
                    'total' => $mayor->total
                ];
            } else {
                $resumen[] = [
                    'puesto' => $puesto,
                    'nombre' => 'Ninguno',
                    'dni' => '-',
                    'total' => 0
                ];
            }
        }

        $headerHTML = view('reports.headerVPDF', ['poll' => $poll])->render();

        $pdf = PDF::loadView('reports.votos-por-puesto-pdf', [
            'poll' => $poll,
            'resumen' => $resumen
        ])
        ->setOption('margin-top', 40)
        ->setOption('margin-bottom', 15)
        ->setOption('header-html', $headerHTML)
        ->setOption('footer-center', 'Página [page] de [topage]')
        ->setOption('footer-font-size', 8);

        return ($visualizarPDF == "si") ? $pdf->inline('Resumen_Puestos.pdf') : $pdf->download('Resumen_Puestos.pdf');
    }

    return back()->with('error', 'Tipo de reporte no válido.');
}


}
