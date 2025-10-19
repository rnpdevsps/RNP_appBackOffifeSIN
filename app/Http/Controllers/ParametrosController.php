<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ParametrosDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Models\Parametros;


class ParametrosController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-parametros|create-parametros|edit-parametros|delete-parametros', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-parametros', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-parametros', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-parametros', ['only' => ['destroy']]);
    }

    public function index(ParametrosDataTable $dataTable)
    {
        return $dataTable->render('parametros.index');
    }

    public function create()
    {
        $view =  view('parametros.create');
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'nombre'    => 'required',
            'valor'   => 'required',
        ]);

        $input  = $request->all();

        $parametro = Parametros::create($input);

        return redirect()->route('parametros.index')->with('success',  __('Parametro creado con exito.'));
    }

    public function edit($id)
    {
        $parametro           = Parametros::find($id);

        $view           =   view('parametros.edit', compact('parametro'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required',
            'valor'   => 'required',
        ]);

        $input = $request->all();

        $parametro = Parametros::find($id);
        $parametro->update($input);

        return redirect()->route('parametros.index')->with('success', __('Parametro actualizado con éxito.'));
    }


    public function destroy($id)
    {
        $parametro = Parametros::find($id);
        $parametro->delete();
        return redirect()->back()->with('success', __('Parametro eliminado con exito.'));
    }
}
