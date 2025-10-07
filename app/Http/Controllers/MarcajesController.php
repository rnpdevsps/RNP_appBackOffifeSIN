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

use GuzzleHttp\Client;


class MarcajesController extends Controller
{
    function __construct() {}

    public function index(MarcajesDataTable $dataTable)
    {
        $depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Depto');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        $municipios[''] = __('Seleccione un Municipio');
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        return $dataTable->render('marcajes.index', compact('deptos', 'municipios'));
    }

    public function create()
    {

        // Definir la fecha a actualizar en formato correcto
        /*$fechaActualizar = Carbon::createFromFormat('d/m/Y', '20/02/2025')->toDateString();

// Buscar todos los registros del 21/02/2025 con hora_salida vacía o NULL
$marcajesVacios = Marcaje::whereDate('created_at', $fechaActualizar)
                         ->where(function ($query) {
                             $query->whereNull('hora_salida')->orWhere('hora_salida', '');
                         })
                         ->get();

foreach ($marcajesVacios as $marcaje) {
    // Generar una hora aleatoria entre 16:00:00 y 16:30:00
    $horaAleatoria = Carbon::today()->setHour(16)->setMinute(rand(0, 30))->setSecond(0);

    // Actualizar el registro con la nueva hora_salida
    $marcaje->hora_salida = $horaAleatoria;
    $marcaje->updated_at = now();
    $marcaje->save();
}*/



        /*// Definir fechas correctamente en formato YYYY-MM-DD
$fechaAyer = Carbon::createFromFormat('d/m/Y', '18/02/2025')->toDateString();
$fechaHoy = Carbon::createFromFormat('d/m/Y', '21/02/2025')->toDateString();

// Obtiene todos los registros de ayer
$marcajesAyer = Marcaje::whereDate('created_at', $fechaAyer)->get();

foreach ($marcajesAyer as $marcajeAyer) {
    // Obtener la hora_salida de ayer y sumarle 5 minutos
    $nuevaHoraSalida = Carbon::parse($marcajeAyer->hora_salida)->addMinutes(10);

    // Buscar el registro de hoy del mismo empleado
    $marcajeHoy = Marcaje::where('empleado_id', $marcajeAyer->empleado_id)
                         ->whereDate('created_at', $fechaHoy)
                         ->first();

    if ($marcajeHoy) {
        // Actualizar la hora_salida de hoy con la nueva hora
        $marcajeHoy->hora_salida = $nuevaHoraSalida;
        $marcajeHoy->updated_at = now();
        $marcajeHoy->save();
    }
}*/


        /*$depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Depto');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        $municipios[''] = __('Seleccione un Municipio');
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        $view =  view('rcms.create', compact('deptos','municipios'));
        return ['html' => $view->render()];*/
    }


    public function buscarCodigo(Request $request)
    {
        $empleado = Empleado::where('codigo', $request->codigo)
            ->whereHas('Rcm', function ($query) use ($request) {
                $query->where('codigo', $request->codigoRCM);
            })
            ->first();

        if (!$empleado) {
            return response()->json(['message' => 'Empleado no existe o pertenece a otro RCM.'], 404);
        }

        return response()->json(['message' => 'CONFIRMANDO HUELLA', 'marcajeh' => $empleado->marcajeh], 200);
    }

    public function registrarMarcajeSinHuella(Request $request)
    {
        date_default_timezone_set('America/Tegucigalpa');

        $codigo = $request->codigo;
        $fechaActual = Carbon::now()->format('Y-m-d');
        $horaActual = Carbon::now()->format('H:i:s');

        if ($request->fechaHora) {
            $FechahoraActual = Carbon::createFromFormat('d/m/Y H:i:s', $request->fechaHora)->format('Y-m-d H:i:s');
        } else {
            $FechahoraActual = Carbon::now()->format('Y-m-d H:i:s');
        }

        // Extraer la hora
        $horaMarcaje = Carbon::parse($FechahoraActual)->format('h:i:s A');

        // Convierte la hora a segundos para comparación
        $horaActualEnSegundos = Carbon::parse($horaActual)->diffInSeconds(Carbon::today());
        // Hora de referencia: 16:00:00 en segundos
        $horaReferenciaEnSegundos = Carbon::parse('15:00:00')->diffInSeconds(Carbon::today());

        $empleado = Empleado::where('codigo', $codigo)->first();

        if (!$empleado) {
            return response()->json(['message' => 'Empleado no existe.'], 404);
        }

        $marcaje = Marcaje::where('empleado_id', $empleado->id)
            ->whereDate('created_at', $fechaActual)
            ->first();

        if ($marcaje) {
            $marcaje->hora_salida = $FechahoraActual;
            $marcaje->updated_at = $FechahoraActual;
            $marcaje->save();

            return response()->json([
                'message' => "Hora de salida registrada correctamente.",
                'hora' => $horaMarcaje,
                'icon' => "success",
            ], 200);
        } else {
            // Si no existe, registrar una nueva hora_entrada
            $nuevoMarcaje = Marcaje::create([
                'empleado_id' => $empleado->id,
                'rcm_id' => $empleado->rcm_id,
                'hora_entrada' => $FechahoraActual,
                'hora_salida' => null,
                'created_at' => $FechahoraActual,
                'updated_at' => $FechahoraActual,
            ]);

            return response()->json([
                'message' => "Hora de entrada registrada correctamente.",
                'hora' => $horaMarcaje,
                'data' => $nuevoMarcaje,
                'icon' => "success",
            ], 200);
        }
    }


    public function store(Request $request)
    {

        date_default_timezone_set('America/Tegucigalpa');

        $codigo = $request->codigo;
        $huella = $request->huella;
        $fechaActual = Carbon::now()->format('Y-m-d');
        $horaActual = Carbon::now()->format('H:i:s');

        $FechahoraActual = Carbon::now()->format('Y-m-d H:i:s');

        // Extraer la hora
        $horaMarcaje = Carbon::parse($FechahoraActual)->format('h:i:s A');

        // Convierte la hora a segundos para comparación
        $horaActualEnSegundos = Carbon::parse($horaActual)->diffInSeconds(Carbon::today());
        // Hora de referencia: 16:00:00 en segundos
        $horaReferenciaEnSegundos = Carbon::parse('15:00:00')->diffInSeconds(Carbon::today());

        $empleado = Empleado::where('codigo', $codigo)->first();

        if (!$empleado) {
            return response()->json(['message' => 'Empleado no existe.'], 404);
        }

        if ($request->app) {
            $respuesta == "SI";
        } else {
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
                        <NumeroIdentidad>' . $empleado->dni . '</NumeroIdentidad>
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
        }



        if ($respuesta == "SI") {
            $marcaje = Marcaje::where('empleado_id', $empleado->id)
                ->whereDate('created_at', $fechaActual)
                ->first();

            if ($marcaje) {
                // Si existe, actualizar la hora_salida
                //if ($horaActualEnSegundos >= $horaReferenciaEnSegundos) {
                $marcaje->hora_salida = $FechahoraActual;
                $marcaje->updated_at = $FechahoraActual;
                $marcaje->save();

                return response()->json([
                    'message' => "Hora de salida registrada correctamente.",
                    'hora' => $horaMarcaje,
                    'icon' => "success",
                ], 200);
                /*} else {
                    return response()->json([
                        'message' => 'Debe esperar a las 04:00 PM para marcar salida.',
                        'icon' => "error",
                    ], 200);
                }*/
            } else {

                if ($horaActualEnSegundos >= $horaReferenciaEnSegundos) {
                    // Si no existe, registrar una nueva hora_salida si es mayor o igual a las 3pm
                    $nuevoMarcaje = Marcaje::create([
                        'empleado_id' => $empleado->id,
                        'rcm_id' => $empleado->rcm_id,
                        'hora_entrada' => null,
                        'hora_salida' => $FechahoraActual,
                        'created_at' => $FechahoraActual,
                        'updated_at' => $FechahoraActual,
                    ]);

                    $mensaje = "Hora de salida registrada correctamente.";
                } else {
                    // Si no existe, registrar una nueva hora_entrada
                    $nuevoMarcaje = Marcaje::create([
                        'empleado_id' => $empleado->id,
                        'rcm_id' => $empleado->rcm_id,
                        'hora_entrada' => $FechahoraActual,
                        'hora_salida' => null,
                        'created_at' => $FechahoraActual,
                        'updated_at' => $FechahoraActual,
                    ]);

                    $mensaje = "Hora de entrada registrada correctamente.";
                }

                return response()->json([
                    'message' => $mensaje,
                    'hora' => $horaMarcaje,
                    'data' => $nuevoMarcaje,
                    'icon' => "success",
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Intente marcar de nuevo.',
                'icon' => "error",
            ], 200);
        }
    }

    public function marcajesreport()
    {
        $depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Depto');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        $municipios[''] = __('Seleccione un Municipio');
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        $rcm = DB::table("rcms")->orderBy('id', 'asc')->get();
        $rcms = [];
        $rcms[''] = __('Seleccione un RCM');
        foreach ($rcm as $value) {
            $rcms[$value->id] = $value->codigo;
        }

        $empleado = DB::table("empleados")->orderBy('id', 'asc')->get();
        $empleados = [];
        $empleados[''] = __('Seleccione un Empleado');
        foreach ($empleado as $value) {
            $empleados[$value->id] = $value->name;
        }

        $view =  view('marcajes.reports', compact('deptos', 'municipios', 'rcms', 'empleados'));
        return ['html' => $view->render()];
    }




    // Reporte de Marcajes
    public function reportMarcajes(Request $request)
    {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $request->session()->flash('warning', " ¡Desde la fecha y hasta la fecha requerido!");
            return back();
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $idDepto = $request->idDepto;
        $idMunicipio = $request->idMunicipio;
        $rcm_id = $request->rcm_id;
        $empleado_id = $request->empleado_id;
        $fileType = $request->fileType;
        $visualizarPDF =  $request->visualizarPDF;

        Session::put('fromDate', Carbon::parse($fromDate));
        Session::put('toDate', Carbon::parse($toDate)->addDays(1));
        Session::put('idDepto', $idDepto);
        Session::put('idMunicipio', $idMunicipio);
        Session::put('rcm_id', $rcm_id);
        Session::put('empleado_id', $empleado_id);
        Session::put('fileType', $fileType);

        $data['marcajes'] = [];
        $marcajes = DB::table('empleados')
            ->select([
                'empleados.dni',
                'empleados.name',
                'empleados.status',
                'empleados.cargo',
                'empleados.codigo',
                'marcajes.empleado_id',
                'marcajes.hora_entrada',
                'marcajes.hora_salida',
                'marcajes.created_at',
                'rcms.codigo as codrcm',
                'rcms.name as nombrercm',
                'deptos.nombredepto as nombredepto',
                'municipios.nombremunicipio as nombremunicipio'
            ])
            ->join('marcajes', 'marcajes.empleado_id', '=', 'empleados.id')
            ->join('deptos', 'deptos.id', '=', 'empleados.idDepto')
            ->join('municipios', 'municipios.id', '=', 'empleados.idMunicipio')
            ->join('rcms', 'rcms.id', '=', 'empleados.rcm_id')
            ->orderBy('marcajes.created_at', 'desc');

        $marcajes->whereDate('marcajes.created_at', '>=', Carbon::parse($fromDate));
        $marcajes->whereDate('marcajes.created_at', '<=', Carbon::parse($toDate)->addDays(1));
        $marcajes->where('empleados.status', '=', 1);

        if (!empty($request->empleado_id)) {
            $marcajes->where('marcajes.empleado_id', '=', $request->empleado_id);
        }
        if (!empty($request->rcm_id)) {
            $marcajes->where('marcajes.rcm_id', '=', $request->rcm_id);
        }
        if (!empty($request->idDepto)) {
            $marcajes->where('empleados.idDepto', '=', $request->idDepto);
        }
        if (!empty($request->idMunicipio)) {
            $marcajes->where('empleados.idMunicipio', '=', $request->idMunicipio);
        }

        // Ejecutamos una sola vez la consulta y reusamos el resultado
        $dataMarcajes = $marcajes->get();

        Session::put('data_report', $dataMarcajes);
        $data['marcajes'] = $dataMarcajes;
        $data['fechai'] = $request->from_date;
        $data['fechaf'] = $request->to_date;

        $data['rango'] = 'DEL ' . Carbon::parse($fromDate)->format('d-m-Y') . ' AL ' . Carbon::parse($toDate)->format('d-m-Y');
        $rango = 'DEL ' . Carbon::parse($fromDate)->format('d-m-Y') . ' AL ' . Carbon::parse($toDate)->format('d-m-Y');

        $headerHTML = view('reports.headerPDF', ['rango' => $rango])->render();

        if ($dataMarcajes->isEmpty()) {
            Session::flash('warning', 'No hay datos para exportar');
            return back();
        }

        if ($fileType == "pdf") {
            $pdf = PDF::loadView('reports.marcajesPDF', array_merge($data, ['rango' => $rango]))
                ->setOption('margin-top', 50)
                ->setOption('margin-bottom', 15)
                ->setOption('header-html', $headerHTML)
                ->setOption('footer-center', 'Página [page] de [topage]')
                ->setOption('footer-font-size', 8);

            return ($visualizarPDF == "si") ? $pdf->inline('Reporte_de_marcajes.pdf') : $pdf->download('Reporte_de_marcajes.pdf');
        }

        if ($fileType == "excel") {
            // Vista normal (detallado = false)
            return Excel::download(
                new SalesReport($fromDate, $toDate, $dataMarcajes, false),
                'reporte_de_marcajes.xlsx'
            );
        }

        if ($fileType == "excel_detallado") {
            // Vista detallada (detallado = true)  <-- CAMBIO CLAVE
            return Excel::download(
                new SalesReport($fromDate, $toDate, $dataMarcajes, true),
                'reporte_de_marcajes_detallado.xlsx'
            );
        }

        if ($fileType == "csv") {
            return Excel::download(
                new SalesReport($fromDate, $toDate, $dataMarcajes, false),
                'reporte_de_marcajes.csv'
            );
        }
    }


    public function edit($id)
    {

        $rcm           = Rcm::find($id);
        $depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        $view           =   view('rcms.edit', compact('rcm', 'deptos', 'municipios'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'codigo' => 'required|max:10|unique:rcms,codigo,' . $id,
            'idDepto' => 'required',
            'idMunicipio' => 'required',
        ]);

        $input               = $request->all();
        $input['status']     = '1';
        $input['updated_by'] = \Auth::user()->id;
        $input['updated_at'] =  Carbon::now()->toDateTimeString();

        $rcm                    = Rcm::find($id);
        $rcm->update($input);

        return redirect()->route('rcms.index')->with('success',  __('RCM actualizado con exito.'));
    }



    public function bitacora($id)
    {
        $Bitacora = DB::table('bitacora_rcms')
            ->join('estado_inhabilitados', 'bitacora_rcms.estadoinhabilitado_id', '=', 'estado_inhabilitados.id')
            ->join('users', 'bitacora_rcms.created_by', '=', 'users.id')
            ->select('bitacora_rcms.*', 'estado_inhabilitados.descripcion', 'users.name')
            ->where('bitacora_rcms.rcm_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $view     =   view('rcms.bitacora', compact('Bitacora'));
        return ['html' => $view->render()];
    }

    public function contratos($id)
    {
        $rcm           = Rcm::find($id);

        /*$contratos = DB::table('contratos')
                    ->join('users', 'contratos.created_by', '=', 'users.id')
                    ->rightJoin('detalle_contratos', 'contratos.id', '=', 'detalle_contratos.contrato_id')
                    ->select('contratos.*',  'users.name', 'detalle_contratos.adjunto as adjunto', 'detalle_contratos.fecha_final')
                    ->where('contratos.rcm_id', $id)
                    ->orderBy('detalle_contratos.created_at','desc')
                    ->paginate(20);*/

        $contratos = Contrato::join('users', 'contratos.created_by', '=', 'users.id')
            ->select('contratos.*',  'users.name')
            ->where('contratos.rcm_id', $id)
            ->orderBy('contratos.created_at', 'desc')
            ->paginate(20);

        $IdRCM = $rcm->id;
        $view     =   view('rcms.contratos', compact('contratos', 'IdRCM'));
        if ($rcm->clasificacion == 1) {
            $out = 'RCM';
        } else if ($rcm->clasificacion == 0) {
            $out = 'Ventanilla';
        } else if ($rcm->clasificacion == 2) {
            $out = 'Kioscos';
        } else {
            $out = 'Bodega';
        }

        $titulo = 'Contratos ' . $out . ' - Código: ' . $rcm->codigo;

        return ['html' => $view->render(), 'titulo' => $titulo];
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete-rcms')) {
            $rcm           = Rcm::find($id);
            $rcm->delete();
            return redirect()->back()->with('success', __('RCM eliminado con exito.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function rcmStatus(Request $request, $id)
    {
        $rcm = Rcm::find($id);

        if ($rcm) {
            $rcm->status = 1;
            $rcm->save();
        }

        $estado = EstadoInhabilitado::find(1);

        BitacoraRcm::create([
            'rcm_id' => $rcm->id,
            'estadoinhabilitado_id' => 1,
            'observaciones' => $estado->descripcion,
            'created_by' => \Auth::user()->id
        ]);

        return redirect()->route('rcms.index')->with('success',  'RCM ' . $rcm->codigo . ' Activado con Exito.!');
    }

    public function inactivarRcm($id)
    {
        $rcm  = Rcm::find($id);
        $estado           = EstadoInhabilitado::where('id', '!=', 1)->orderBy('id', 'ASC')->get();
        $estados          = [];
        $estados['']      = __('Seleccione un motivo');
        foreach ($estado as $value) {
            $estados[$value->id] = $value->descripcion;
        }

        $view  = view('rcms.inactivar', compact('rcm', 'estados'));
        return ['html' => $view->render()];
    }

    public function ChangeStatusRcm(Request $request)
    {
        if (isset($request->set_end_date) && $request->set_end_date == 'on') {
            request()->validate([
                'set_end_date' => 'required',
                'set_end_date_time' => 'required'
            ]);
        }

        if (isset($request->set_end_date) && $request->set_end_date == 1) {
            if (isset($request->set_end_date_time)) {
                $setEndDateTime = Carbon::parse($request->set_end_date_time)->toDateTimeString();
            } else {
                $setEndDateTime = null;
            }
        } else {
            $setEndDateTime = null;
        }

        $rcm  = Rcm::find($request->rcm_id);

        if ($rcm) {
            $rcm->status = 0;
            $rcm->date_end_inactive = $setEndDateTime;
            $rcm->save();

            BitacoraRcm::create([
                'rcm_id' => $request->rcm_id,
                'estadoinhabilitado_id' => $request->estadoinhabilitado_id,
                'observaciones' => $request->observaciones,
                'created_by' => \Auth::user()->id
            ]);

            return redirect()->route('rcms.index')->with('success',  'RCM ' . $rcm->codigo . ' Inhabilitado con Exito.!');
        }

        return redirect()->back()->with('failed', __('No se Inactivo el RCM, intente de nuevo.'));
    }
}
