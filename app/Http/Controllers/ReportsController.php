<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\Contrato;
use App\Models\DetalleContrato;
use App\Models\Rcm;
use App\Exports\SalesReport;
use Session;
use Validator;
use Auth;
use DB;
use PDF;



class ReportsController extends Controller
{

    public function rcmreport()
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

        $view =  view('rcms.reports', compact('deptos','municipios'));
        return ['html' => $view->render()];
    }



    public function report(Request $request)
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
        $clasificacion = $request->clasificacion;
        $idDepto = $request->idDepto;
        $idMunicipio = $request->idMunicipio;
        $fileType = $request->fileType;
        $visualizarPDF =  $request->visualizarPDF;

        if ($request->clasificacion == 0) {
            Session::put('clasificacion', 'Ventanilla');
            $tipo = 'Ventanilla';
        }
        if ($request->clasificacion == 1) {
            Session::put('clasificacion', 'RCM');
            $tipo = 'RCM';
        }
        if ($request->clasificacion == 2) {
            Session::put('clasificacion', 'Kioscos');
            $tipo = 'Kioscos';
        }
        if ($request->clasificacion == 3) {
            Session::put('clasificacion', 'Bodega');
            $tipo = 'Bodega';
        }
        if (!isset($request->clasificacion)) {
            Session::forget('clasificacion');
            $tipo = '';
        }


        Session::put('fromDate', Carbon::parse($fromDate));
        Session::put('toDate', Carbon::parse($toDate)->addDays(1));
        Session::put('idDepto', $idDepto);
        Session::put('idMunicipio', $idMunicipio);
        Session::put('fileType', $fileType);

        $data['contratos'] = [];
        $contratos = DB::table('rcms')
        ->select([
            'rcms.codigo',
            DB::raw("
                CASE
                    WHEN rcms.clasificacion = 0 THEN 'Ventanilla'
                    WHEN rcms.clasificacion = 1 THEN 'RCM'
                    WHEN rcms.clasificacion = 2 THEN 'Kioscos'
                    WHEN rcms.clasificacion = 3 THEN 'Bodega'
                    ELSE 'Desconocido'
                END AS clasificacion
            "),
            'contratos.propietario_inmueble', 'contratos.contacto_directo', 'contratos.celular',
            'detalle_contratos.fecha_final', 'detalle_contratos.valor_mensual',
            DB::raw("
                CASE
                    WHEN detalle_contratos.moneda = 1 THEN 'Lempiras'
                    WHEN detalle_contratos.moneda = 2 THEN 'Dolar'
                    ELSE 'Desconocido'
                END AS moneda
            "),
            'deptos.nombredepto as nombredepto',
            'municipios.nombremunicipio as nombremunicipio',
            'users.name as username'
        ])
        ->join('contratos', 'contratos.rcm_id', '=', 'rcms.id')
        ->join('detalle_contratos', 'detalle_contratos.contrato_id', '=', 'contratos.id')
        ->join('deptos', 'deptos.id', '=', 'rcms.idDepto')
        ->join('municipios', 'municipios.id', '=', 'rcms.idMunicipio')
        ->join('users', 'rcms.created_by', '=', 'users.id')
        ->orderBy('detalle_contratos.fecha_final', 'desc');

        $contratos->where('detalle_contratos.fecha_final', '>=', $request->from_date);
        $contratos->where('detalle_contratos.fecha_final', '<=', $request->to_date);
        $contratos->where('detalle_contratos.status', '=', 1);

        if (isset($request->clasificacion)) {
            $contratos->where('rcms.clasificacion', '=', $request->clasificacion);
        }
        if (!empty($request->idDepto)) {
            $contratos->where('rcms.idDepto', '=', $request->idDepto);
        }
        if (!empty($request->idMunicipio)) {
            $contratos->where('rcms.idMunicipio', '=', $request->idMunicipio);
        }

        Session::put('data_report', $contratos->get());
        $data['contratos'] = $contratos->get();
        $dataContratos = $contratos->get();

        $data['rango'] = 'del ' . Carbon::parse($fromDate)->format('d-m-Y') . ' al ' . Carbon::parse($toDate)->addDays(-1)->format('d-m-Y');

        if (empty($contratos->get()) || count($contratos->get()) == 0) {
            Session::flash('warning', 'No hay datos para exportar');
            return back();
        }

        if ($fileType == "pdf") {
            $pdf = PDF::loadView('reports.contratosPDF', $data);
            $pdf->setPaper('a3', 'landscape');
            if ($visualizarPDF == "si") {
                return $pdf->stream();
            } else {
                return $pdf->download('reporte_de_contratos.pdf');
            }
        }

        if ($fileType == "excel") {
            $contratos = Session::get('data_report');
            return Excel::download(new SalesReport($fromDate, $toDate, $dataContratos), 'reporte_de_ventas.xlsx');
        }

        if ($fileType == "csv") {
            $contratos = Session::get('data_report');
            return Excel::download(new SalesReport($fromDate, $toDate, $dataContratos), 'reporte_de_ventas.csv');
        }

    }

}
