<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ContratosDataTable;
use Spatie\MailTemplates\Models\MailTemplate;
use Illuminate\Support\Facades\Storage;
use App\Models\Contrato;
use App\Models\DetalleContrato;
use App\Models\Rcm;
use App\Models\MaePlantilla;
use App\Models\BitacoraContratos;
use App\Models\TipoInmueble;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class ContratosController extends Controller
{
    public function index(ContratosDataTable $dataTable)
    {
        return redirect()->route('rcms.index');

        /*$trashContratos = Contrato::onlyTrashed()->count();
        $totContratos = Contrato::count();
        return $dataTable->render('contratos.index', compact('trashContratos', 'totContratos'));

            return $dataTable->render('contratos.index');*/
    }
    public function create()
    {
        $usuario = \Auth::user()->name;
        $fecha = Carbon::now()->format('d/m/Y');
        $status_contrato = "En Proceso";

        $depto = DB::table("deptos")->orderBy('codigodepto', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Departamento');
        foreach ($depto as $value) {
            $deptos[$value->codigodepto] = $value->nombredepto;
        }

        return view('contratos.create', compact('usuario','fecha','status_contrato','deptos'));
    }

    public function newContrato(Request $request)
    {

        $rcm =  Rcm::select(['rcms.*', 'deptos.nombredepto as nombredepto', 'municipios.codigodepto as codigodepto', 'municipios.nombremunicipio as nombremunicipio', 'municipios.codigomunicipio as codigomunicipio'])
            ->join('deptos', 'deptos.id', '=', 'rcms.idDepto')
            ->join('municipios', 'municipios.id', '=', 'rcms.idMunicipio')
            ->where('rcms.id', '=', $request->rcm_id)
            ->first();

        $usuario = \Auth::user()->name;
        $fecha = Carbon::now()->format('d/m/Y');
        $status_contrato = "En Proceso";

        $depto = DB::table("deptos")->orderBy('codigodepto', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Departamento');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        $municipios[''] = __('Seleccione un Municipio');
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        $inmueble = TipoInmueble::orderBy('id', 'asc')->where('status','!=',1)->get();
        $inmuebles = [];
        $inmuebles[''] = __('Seleccione Tipo Inmueble');
        foreach ($inmueble as $value) {
            $inmuebles[$value->id] = $value->descri;
        }

        /*$inmueble = TipoInmueble::orderBy('id', 'asc')->get();
        $inmuebles = [];
        foreach ($inmueble as $value) {
            $inmuebles[$value->id] = $value->descri;
        }*/

        return view('contratos.create', compact('inmuebles', 'usuario','fecha','status_contrato','deptos', 'municipios', 'rcm'));
    }

    public function plantilla($id)
    {
        $plantilla = MaePlantilla::find($id);

        if (!$plantilla) {
            return response()->json(['error' => 'Plantilla no encontrada'], 404);
        }

        $view = view('contratos.template', compact('plantilla'))->render();

        return response()->json(['html' => $view]);
    }


    public function updateTemplate(Request $request)
    {
        Session::put('template', $request->content);
        Session::put('template_id', $request->template_id);
        return response()->json(array('success'=> "Plantilla actualizado con éxito!"), 200);
    }


    public function store(Request $request)
    {
        
        $input = $request->all();
        $input['status'] = 1;
        $input['user_id']  = Auth::user()->id;
        $input['created_by']  = Auth::user()->id;
        $input['updated_by']  = Auth::user()->id;
        $input['created_at'] = now();
        $input['updated_at'] = now();

        $code = rand ( 100000 , 999999 );
        $input['code']  = $code;

        $Contrato = Contrato::create($input);
        $idContrato = Contrato::latest('id')->first()->id;

        if ($request->is_propio == 0 && !empty($request->anio_detalle)) {

            $detalleContrato = new DetalleContrato();

            $filename = '';
            if (request()->file('adjunto')) {
                $filename = "Contrato_".$idContrato."_".$request->file('adjunto')->getClientOriginalName();
                $request->file('adjunto')->storeAs('Contratos', $filename);
                $detalleContrato->adjunto = $filename;
            }
 
            if (!empty($request->costo_dicional)) {
                $costo_dicional = $request->costo_dicional;
            } else {
                $costo_dicional = 0;
            }

            $detalleContrato->contrato_id = $idContrato;
            $detalleContrato->anio = $request->anio_detalle;
            $detalleContrato->valor_metros2 = $request->valor_metros_detalle;
            $detalleContrato->fecha_inicio = $request->fecha_inicio;
            $detalleContrato->fecha_final = $request->fecha_final;
            $detalleContrato->status = 1;
            $detalleContrato->moneda = $request->moneda;
            $detalleContrato->valor_mensual = $request->valor_mensual;
            $detalleContrato->no_meses = $request->no_meses;
            $detalleContrato->valor_total = $request->valor_total;
            $detalleContrato->costo_dicional = $costo_dicional;
            $detalleContrato->observaciones_det = $request->observaciones_det;
            $detalleContrato->created_by =  Auth::user()->id;
            $detalleContrato->created_at = now();
            $detalleContrato->save();
        }

        return redirect()->route('rcms.index')->with('success',  __('Contrato creado con exito.'));
    }

    public function edit($id)
    {
        $usuario = \Auth::user()->name;
        $fecha = Carbon::now()->format('d/m/Y');

        $contrato = Contrato::find($id);
        $detalleContrato = DetalleContrato::where('contrato_id', $id)->orderBy('fecha_final', 'desc')->get();

        $depto = DB::table("deptos")->orderBy('codigodepto', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Departamento');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        $municipios[''] = __('Seleccione un Municipio');
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }

        $inmueble = TipoInmueble::orderBy('id', 'asc')->get();
        $inmuebles = [];
        foreach ($inmueble as $value) {
            $inmuebles[$value->id] = $value->descri;
        }

        return view('contratos.edit', compact('inmuebles', 'contrato','usuario','fecha', 'deptos', 'municipios','detalleContrato'));
    }

    public function editDetalle($id)
    {
        $detalleContrato = DetalleContrato::find($id);
        $fecha_inicio    = Carbon::parse($detalleContrato->fecha_inicio)->format('Y-m-d');
        $fecha_final     = Carbon::parse($detalleContrato->fecha_final)->format('Y-m-d');

        $view           =   view('contratos.editDetalle', compact('detalleContrato', 'fecha_inicio', 'fecha_final'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'propietario_inmueble' => 'required',
        ];

        $messages = [
            'propietario_inmueble.required' => 'Debe registrar el nombre del propietario.',
        ];

        $this->validate($request, $rules, $messages);

        if (!empty($request->metros2)) {
            $metros2 = $request->metros2;
        } else {
            $metros2 = 0;
        }

        $input             = $request->all();
        $input['updated_by']   =  Auth::user()->id;
        $input['updated_at']   = now();
        $input['updated_at']   = now();
        $input['metros2']   = $metros2;

        $contrato = Contrato::find($id);

        /*if ($request->status_contrato != $contrato->status_contrato) {
            BitacoraContratos::create([
                'rcm_id' => $rcm->id,
                'observaciones' => "Estado anterior: ".$contrato->status_contrato ."Nuevo estado: ".$request->status_contrato,
                'created_by' => \Auth::user()->id
            ]);
        }*/

        $contrato->update($input);

        if (!empty($request->anio_detalle)) {

            $detalleContrato = new DetalleContrato();

            $filename = '';
            if (request()->file('adjunto')) {
                $filename = "Contrato_".$id."_".$request->file('adjunto')->getClientOriginalName();
                $request->file('adjunto')->storeAs('Contratos', $filename);
                $detalleContrato->adjunto = $filename;
            }

            if (!empty($request->costo_dicional)) {
                $costo_dicional = $request->costo_dicional;
            } else {
                $costo_dicional = 0;
            }

            $detalleContrato->contrato_id = $id;
            $detalleContrato->anio = $request->anio_detalle;
            $detalleContrato->valor_metros2 = $request->valor_metros_detalle;
            $detalleContrato->fecha_inicio = $request->fecha_inicio;
            $detalleContrato->fecha_final = $request->fecha_final;
            $detalleContrato->status = 1;
            $detalleContrato->moneda = $request->moneda;
            $detalleContrato->valor_mensual = $request->valor_mensual;
            $detalleContrato->no_meses = $request->no_meses;
            $detalleContrato->valor_total = $request->valor_total;
            $detalleContrato->costo_dicional = $costo_dicional;
            $detalleContrato->observaciones_det = $request->observaciones_det;

            $detalleContrato->created_by =  Auth::user()->id;
            $detalleContrato->created_at = now();
            $detalleContrato->save();
        }

        return redirect()->route('rcms.index')->with('success', __('Contrato actualizado con exito.'));

    }

    public function updateDetalleContrato(Request $request)
    {

        $detalleContrato = DetalleContrato::find($request->id_detalle);

        $filename = '';
        if (request()->file('adjunto')) {
            $filename = "Contrato_".$detalleContrato->contrato_id."_".$request->file('adjunto')->getClientOriginalName();
            $request->file('adjunto')->storeAs('Contratos', $filename);
            $detalleContrato->adjunto   = $filename;
        }
        if (!empty($request->costo_dicional)) {
            $costo_dicional = $request->costo_dicional;
        } else {
            $costo_dicional = 0;
        }

        $detalleContrato->anio = $request->anio_detalle;
        $detalleContrato->valor_metros2 = $request->valor_metros_detalle;
        $detalleContrato->fecha_inicio = $request->fecha_inicio;
        $detalleContrato->fecha_final = $request->fecha_final;
        $detalleContrato->moneda = $request->moneda;
        $detalleContrato->valor_mensual = $request->valor_mensual;
        $detalleContrato->no_meses = $request->no_meses;
        $detalleContrato->valor_total = $request->valor_total;
        $detalleContrato->costo_dicional = $costo_dicional;
        $detalleContrato->observaciones_det = $request->observaciones_det;
        $detalleContrato->updated_by =  Auth::user()->id;
        $detalleContrato->updated_at = now();
        $detalleContrato->save();

        return response()->json(array('success'=> "Detalle del Contrato actualizado con éxito!"), 200);

    }


    public function destroy($id)
    {
        $dataTable = Contrato::find($id);
        $dataTable->status = true;
        $dataTable->updated_by = \Auth::user()->id;
        $dataTable->deleted_by = \Auth::user()->id;
        $dataTable->updated_at = now();
        $dataTable->deleted_at = now();
        $dataTable->update();

        return redirect()->route('contratos.index')->with('success', __('Contrato Eliminado con exito'));

    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $fileName           = $request->upload->store('editor');
            $CKEditorFuncNum    = $request->input('CKEditorFuncNum');
            $url                = Storage::url($fileName);
            $msg                = 'Image uploaded successfully';
            $response           = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }


    public function destroyMultiple(Request $request)
    {
        $form =  Contrato::whereIn('id', $request->ids)->delete();
        return response()->json(['msg' =>  'Contrato movido a la papelera.']);
    }

    public function restore($id)
    {
        $form = Contrato::where('id', $id)->restore();
        return redirect()->back()->with('success',  'Contrato restaurado con exito.');
    }

    public function restoreMultiple(Request $request)
    {
        $form = Contrato::whereIn('id', $request->ids)->restore();
        return response()->json(['msg' =>  'Contrato restaurado con exito.']);
    }

    public function forcedelete($id)
    {
        Contrato::where('id', $id)->forceDelete();
        return redirect()->route('contratos.index', 'view=trash')->with('success', __('Contrato eliminado con exito.'));
    }

    public function forcedeleteMultiple(Request $request)
    {
        $form = Contrato::whereIn('id', $request->ids)->forceDelete();

        return response()->json(['msg' =>  'Contrato eliminado con exito.']);
        if ($request->query->get('view')) {
            return route('contratos.index', 'view=trash');
        } else {
            return route('contratos.index');
        }
    }

    public function forcedeleteAll(Request $request)
    {
        $form = Contrato::onlyTrashed()->forceDelete();
        return response()->json(['msg' =>  'Papelera esta vacia.']);
    }

}
