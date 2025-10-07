<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Marcaje;
use App\Models\Rcm;
use App\Models\EstadoInhabilitado;
use App\Models\BitacoraEmpleado;
use App\Models\Contrato;
use App\DataTables\EmpleadosDataTable;
use App\Facades\UtilityFacades;
use App\Mail\HitReportMail;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;


class EmpleadosController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-empleados|create-empleados|edit-empleados|delete-empleados', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-empleados', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-empleados', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-empleados', ['only' => ['destroy']]);
    }

    public function index(EmpleadosDataTable $dataTable)
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
        
        return $dataTable->render('empleados.index', compact('deptos','municipios'));
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
            $rcms[$value->id] = $value->codigo.' - '.$value->name;
        }

        $view =  view('empleados.create', compact('deptos','municipios', 'rcms'));
        return ['html' => $view->render()];
    }

    public function sendHitEmail(Request $request)
    {
        
    }

    public function enviarCorreo2()
{
    
}

    public function enviarCorreo(Request $request)
    {
        $user = \Auth::user()->name;
        $fecha = Carbon::now()->toDateTimeString();
        $destinatarios = ['jose.brid@rnp.hn', 'vanessa.alberto@rnp.hn'];
        //vanessa.alberto@rnp.hn
        $asunto = 'Excepción de Huella - Marcaje de Empleado';        
        $nombre = $request->input('name');
        $accion = $request->input('accion');
        $rcm = $request->input('rcm');

        $msg = "Empleado ".$accion." con exito";

        $mensaje = "Se informa que el usuario ".$user." ha marcado al empleado ".$nombre." del RCM ".$rcm." con una excepción de huella para el marcaje de asistencia del empleado.\n\n\nSaludos";

        Mail::raw($mensaje, function ($message) use ($destinatarios, $asunto) {
            $message->to($destinatarios)
                    ->subject($asunto);
        });

        return redirect()->route('empleados.index')->with('success',  $msg);
    }


    public function store(Request $request)
    {
        request()->validate([
            'dni'         => 'required|unique:empleados,dni',
            'codigo'         => 'required'
        ]);

        $rcm = Rcm::where('id', $request->rcm_id)->first();
        $existeCodigoRCM = Empleado::where('codigo', $request->codigo)->where('rcm_id', $rcm->id)->exists();

        if ($existeCodigoRCM) {
            return redirect()->back()->with('failed', __('Ya existe Codigo de empleado para el RCM seleccionado.'));
        }

        // Si marcajeh viene activado, se asigna 1 para marcar con Huella, de lo contrario 0
        $marcajeh = ($request->marcajeh == 'on') ? 1 : 0;
        $input               = $request->all();
        $input['marcajeh']   = $marcajeh;
        $input['status']     = '1';
        $input['created_by'] = \Auth::user()->id;
        $input['created_at'] =  Carbon::now()->toDateTimeString();

        $emp  = Empleado::create($input);

        if (app()->environment('production') && \Auth::user()->can('manage-marcarsinhuella') && $marcajeh == 0)  { 
            return redirect()->route('enviarCorreo', ['name' => $emp->name,'accion' => "creado", 'rcm' => $rcm->name]);
        }

        return redirect()->route('empleados.index')->with('success',  __('Empleado creado con exito.'));
    }

    public function edit($id)
    {
 
        $empleado           = Empleado::find($id);
        $depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $depto = DB::table("deptos")->orderBy('id', 'asc')->get();
        $deptos = [];
        $deptos[''] = __('Seleccione un Depto');
        foreach ($depto as $value) {
            $deptos[$value->id] = $value->nombredepto;
        }

        $municipio = DB::table("municipios")->orderBy('id', 'asc')->get();
        $municipios = [];
        foreach ($municipio as $value) {
            $municipios[$value->id] = $value->nombremunicipio;
        }


        $rcm = DB::table("rcms")->orderBy('id', 'asc')->get();
        $rcms = [];
        $rcms[''] = __('Seleccione un RCM');
        foreach ($rcm as $value) {
            $rcms[$value->id] = $value->codigo.' - '.$value->name;
        }


        $view           =   view('empleados.edit', compact('empleado','deptos', 'municipios', 'rcms'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'dni'         => 'required|unique:empleados,dni,' . $id,
            'codigo'      => 'required|unique:empleados,codigo,' . $id,
        ]);

        $input               = $request->all();

        if (\Auth::user()->can('manage-marcarsinhuella')) {
            $marcajeh = ($request->marcajeh == 'on') ? 1 : 0;
            $input['marcajeh']   = $marcajeh;
        }        
        $input['status']     = '1';
        $input['updated_by'] = \Auth::user()->id;
        $input['updated_at'] =  Carbon::now()->toDateTimeString();

        $empleado                 = Empleado::find($id);
        $empleado->update($input);

        $rcm = Rcm::where('id', $empleado->rcm_id)->first();

        if (app()->environment('production') && \Auth::user()->can('manage-marcarsinhuella') && $marcajeh == 0)  {
            return redirect()->route('enviarCorreo', ['name' => $empleado->name,'accion' => "actualizado", 'rcm' => $rcm->name]);
        }

        return redirect()->route('empleados.index')->with('success',  __('Empleado actualizado con exito.'));
    }

 

    public function bitacora($id)
    {
        $Bitacora = DB::table('bitacora_empleados')
        ->join('estado_inhabilitados', 'bitacora_empleados.estadoinhabilitado_id', '=', 'estado_inhabilitados.id')
        ->join('users', 'bitacora_empleados.created_by', '=', 'users.id')
        ->select(
            'bitacora_empleados.id',
            'bitacora_empleados.empleado_id',
            'bitacora_empleados.estadoinhabilitado_id',
            'bitacora_empleados.created_by',
            'bitacora_empleados.created_at',
            'bitacora_empleados.updated_at',
            'bitacora_empleados.observaciones as observacion',
            'estado_inhabilitados.descripcion as estado_descripcion',
            'users.name as creado_por'
        )
        ->where('bitacora_empleados.empleado_id', $id)
        ->orderBy('bitacora_empleados.created_at', 'desc')
        ->paginate(20);


        $view     =   view('empleados.bitacora', compact('Bitacora'));
        return ['html' => $view->render()];
    }

    public function empleados($id)
    {
        $rcm           = Rcm::find($id);
        $empleados = Empleado::join('users', 'empleados.created_by', '=', 'users.id')
            ->select('empleados.*',  'users.name as username')
            ->where('empleados.rcm_id', $id)
            ->orderBy('empleados.created_at','desc')
            ->paginate(20);

        $IdRCM = $rcm->id;
        $view     =   view('empleados.empleados', compact('empleados', 'IdRCM'));
        if ($rcm->clasificacion == 1) {
            $out = 'RCM';
        } else if ($rcm->clasificacion == 0){
            $out = 'Ventanilla';
        } else if ($rcm->clasificacion == 2){
            $out = 'Kioscos';
        } else {
            $out = 'Bodega';
        }

        $titulo = 'Empleados '.$out.' - Código: '.$rcm->codigo;

        return ['html' => $view->render(), 'titulo' => $titulo];
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete-empleados')) {
            $existeMarcaje = Marcaje::where('empleado_id', $id)->exists();
            if ($existeMarcaje) {
                return redirect()->back()->with('failed', __('No se puede eliminar el empleado porque tiene registros asociados en Marcaje'));
            }

            $existeBitacora = BitacoraEmpleado::where('empleado_id', $id)->exists();
            if ($existeBitacora) {
                return redirect()->back()->with('failed', __('No se puede eliminar el empleado porque tiene registros asociados en Bitacora'));
            }
            
            // Si no existen registros asociados, elimina el empleado
            $empleado           = Empleado::find($id);
            $empleado->delete();
            return redirect()->back()->with('success', __('Empleado eliminado con exito.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function empleadoStatus(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if ($empleado) {
            $empleado->status = 1;
            $empleado->save();
        }

        $estado = EstadoInhabilitado::find(1);

        BitacoraEmpleado::create([
            'empleado_id' => $empleado->id,
            'estadoinhabilitado_id' => 1,
            'observaciones' => $estado->descripcion,
            'created_by' => \Auth::user()->id
        ]);

        return redirect()->route('empleados.index')->with('success',  'Empleado '.$empleado->name.' Activado con Exito.!');

    }

    public function inactivarEmpleado($id)
    {
        $empleado  = Empleado::find($id);
        $estado           = EstadoInhabilitado::where('id', '!=', 1)->orderBy('id', 'ASC')->get();
        $estados          = [];
        $estados['']      = __('Seleccione un motivo');
        foreach ($estado as $value) {
            $estados[$value->id] = $value->descripcion;
        }

        $view  = view('empleados.inactivar', compact('empleado','estados'));
        return ['html' => $view->render()];
    }

    public function ChangeStatusEmpleado(Request $request)
    {

        $empleado  = Empleado::find($request->empleado_id);

        if ($empleado) {
            $empleado->status = 0;
            $empleado->save();

            BitacoraEmpleado::create([
                'empleado_id' => $request->empleado_id,
                'estadoinhabilitado_id' => $request->estadoinhabilitado_id,
                'observaciones' => $request->observaciones,
                'created_by' => \Auth::user()->id
            ]);

            return redirect()->route('empleados.index')->with('success',  'Empleado '.$empleado->name.' Inhabilitado con Exito.!');
        }

        return redirect()->back()->with('failed', __('No se Inactivo el Empleado, intente de nuevo.'));

    }

}
