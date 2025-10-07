<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ContratosDataTable;
use Spatie\MailTemplates\Models\MailTemplate;
use Illuminate\Support\Facades\Storage;
use App\Models\Contrato;
use App\Models\MaePlantilla;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\Validator;

class QuioscosController extends Controller
{
    public function index(ContratosDataTable $dataTable)
    {
        $trashContratos = Contrato::onlyTrashed()->count();
        $totContratos = Contrato::count();
        return $dataTable->render('quioscos.index', compact('trashContratos', 'totContratos'));

            return $dataTable->render('quioscos.index');
    }
    public function create()
    {
        $usuario = \Auth::user()->name;
        $fecha = Carbon::now()->format('d/m/Y');
        $status_contrato = "En Proceso";

        return view('quioscos.create', compact('usuario','fecha','status_contrato'));
    }

    public function plantilla($id)
    {
        $plantilla  = MaePlantilla::find($id);
        $view  = view('quioscos.template', compact('plantilla'));
        return ['html' => $view->render()];
    }

    public function updateTemplate(Request $request)
    {
        Session::put('template', $request->content);
        Session::put('template_id', $request->template_id);
        return response()->json(array('success'=> "Plantilla actualizado con éxito!"), 200);
    }


    public function store(Request $request)
    {
        $rules = [
            'codigo_muni' => 'required',
            'propietario_inmueble' => 'required',
        ];

        $messages = [
            'codigo_muni.required' => 'Debe registrar el codigo del municipio.',
            'propietario_inmueble.required' => 'Debe registrar el nombre del propietario.',
        ];

        $this->validate($request, $rules, $messages);

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

        return redirect()->route('quioscos.index')->with('success',  __('Contrato creado con exito.'));

    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-contratos')) {
            $usuario = \Auth::user()->name;
            $fecha = Carbon::now()->format('d/m/Y');

            $contrato = Contrato::find($id);

            return view('quioscos.edit', compact('contrato','usuario','fecha'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-contratos')) {


            $rules = [
                'codigo_muni' => 'required',
                'propietario_inmueble' => 'required',
            ];

            $messages = [
                'codigo_muni.required' => 'Debe registrar el codigo del municipio.',
                'propietario_inmueble.required' => 'Debe registrar el nombre del propietario.',
            ];

            $this->validate($request, $rules, $messages);

            $input             = $request->all();
            $input['updated_by']   =  Auth::user()->id;
            $input['updated_at']   = now();
            $contrato = Contrato::find($id);
            $contrato->update($input);

            return redirect()->route('quioscos.index')->with('success', __('Contrato actualizado con exito.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete-contratos')) {
            $dataTable = Contrato::find($id);
            $dataTable->status = true;
            $dataTable->updated_by = \Auth::user()->id;
            $dataTable->deleted_by = \Auth::user()->id;
            $dataTable->updated_at = now();
            $dataTable->deleted_at = now();
            $dataTable->update();

            return redirect()->route('quioscos.index')->with('success', __('Contrato Eliminado con exito'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
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
        if (\Auth::user()->can('delete-contratos')) {
            $form =  Contrato::whereIn('id', $request->ids)->delete();
            return response()->json(['msg' =>  'Contrato movido a la papelera.']);
        } else {
            return redirect()->back()->with('failed', __('Permission Denied.'));
        }
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
        return redirect()->route('quioscos.index', 'view=trash')->with('success', __('Contrato eliminado con exito.'));
    }

    public function forcedeleteMultiple(Request $request)
    {
        $form = Contrato::whereIn('id', $request->ids)->forceDelete();

        return response()->json(['msg' =>  'Contrato eliminado con exito.']);
        if ($request->query->get('view')) {
            return route('quioscos.index', 'view=trash');
        } else {
            return route('quioscos.index');
        }
    }

    public function forcedeleteAll(Request $request)
    {
        $form = Contrato::onlyTrashed()->forceDelete();
        return response()->json(['msg' =>  'Papelera esta vacia.']);
    }

}
