<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rcm;
use App\Models\EstadoInhabilitado;
use App\Models\BitacoraRcm;
use App\Models\Contrato;
use App\DataTables\RcmsDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;

class RcmsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-rcms|create-rcms|edit-rcms|delete-rcms', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-rcms', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-rcms', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-rcms', ['only' => ['destroy']]);
    }

    public function index(RcmsDataTable $dataTable)
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
        
        return $dataTable->render('rcms.index', compact('deptos','municipios'));
    }

    public function create()
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

        $clasificacion = DB::table("clasificacion")->orderBy('id', 'asc')->get();
        $clasificaciones = [];
        $clasificaciones[''] = __('Seleccione una Clasificación');
        foreach ($clasificacion as $value) {
            $clasificaciones[$value->id] = $value->name;
        }

        $view =  view('rcms.create', compact('deptos','municipios', 'clasificaciones'));
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'codigo'         => 'required|max:10|unique:rcms,codigo',
            'idDepto'         => 'required',
            'idMunicipio'         => 'required',
        ]);

        $input               = $request->all();
        $input['status']     = '1';
        $input['created_by'] = \Auth::user()->id;
        $input['created_at'] =  Carbon::now()->toDateTimeString();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $fileName  = $file->store('rcms');
            $input['foto'] = $fileName;
        }

        $rcm                       = Rcm::create($input);

        return redirect()->route('rcms.index')->with('success',  __('RCM creado con exito.'));
    }

    public function edit($id)
    {

        $rcm           = Rcm::find($id);
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

        $clasificacion = DB::table("clasificacion")->orderBy('id', 'asc')->get();
        $clasificaciones = [];
        $clasificaciones[''] = __('Seleccione una Clasificación');
        foreach ($clasificacion as $value) {
            $clasificaciones[$value->id] = $value->name;
        }

        $view           =   view('rcms.edit', compact('rcm','deptos', 'municipios', 'clasificaciones'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'codigo' => 'required|max:10|unique:rcms,codigo,' . $id,
            'iddepto' => 'required',
            'idmunicipio' => 'required',
        ]);

        $input               = $request->all();
        $input['status']     = '1';
        $input['updated_by'] = \Auth::user()->id;
        $input['updated_at'] =  Carbon::now()->toDateTimeString();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $fileName  = $file->store('rcms');
            $input['foto'] = $fileName;
        }

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
                    ->orderBy('created_at','desc')
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
            ->orderBy('contratos.created_at','desc')
            ->paginate(20);

        $IdRCM = $rcm->id;
        $view     =   view('rcms.contratos', compact('contratos', 'IdRCM'));
        if ($rcm->clasificacion == 1) {
            $out = 'RCM';
        } else if ($rcm->clasificacion == 0){
            $out = 'Ventanilla';
        } else if ($rcm->clasificacion == 2){
            $out = 'Kioscos';
        } else {
            $out = 'Bodega';
        }

        $titulo = 'Contratos '.$out.' - Código: '.$rcm->codigo;

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

        return redirect()->route('rcms.index')->with('success',  'RCM '.$rcm->codigo.' Activado con Exito.!');

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

        $view  = view('rcms.inactivar', compact('rcm','estados'));
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

            return redirect()->route('rcms.index')->with('success',  'RCM '.$rcm->codigo.' Inhabilitado con Exito.!');
        }

        return redirect()->back()->with('failed', __('No se Inactivo el RCM, intente de nuevo.'));

    }

}
