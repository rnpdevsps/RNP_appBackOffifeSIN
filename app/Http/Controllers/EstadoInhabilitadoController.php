<?php

namespace App\Http\Controllers;

use App\DataTables\EstadoInhabilitadoDataTable;
use Illuminate\Http\Request;
use App\Models\EstadoInhabilitado;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
class EstadoInhabilitadoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-estadoInhabilitado', ['only' => ['index']]);
        $this->middleware('permission:create-estadoInhabilitado', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-estadoInhabilitado', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-estadoInhabilitado', ['only' => ['destroy']]);

    }

    public function index(EstadoInhabilitadoDataTable $dataTable)
    {
        return $dataTable->render('estadoinhabilitado.index');

    }

    public function create()
    {


            $view =  view('estadoinhabilitado.create');
        return ['html' => $view->render()];

    }

    public function store(Request $request)
    {
        /*$request->validate([
            'descripcion' => 'required|unique:estadoInhabilitado'
        ]);*/

        $input                      = $request->all();
        $input['created_by']        = \Auth::user()->id;
        $input['status']            = 0;
        $input['updated_by']        = \Auth::user()->id;
        EstadoInhabilitado::create($input);

        return redirect()->route('estadoinhabilitado.index')
            ->with('success', __('Estado de Inhabilitación creado con éxito.'));

    }

    public function edit($id)
    {
        $estado = EstadoInhabilitado::findOrFail($id);
        $view               = View::make('estadoinhabilitado.edit', compact('estado'));
        return ['html' => $view->render()];

    }

    public function update(Request $request, $id)
    {
            /*$request->validate([
                'descripcion' => 'required|unique:estadoInhabilitado,descripcion,' . $id
            ]);*/

            $estado = EstadoInhabilitado::findOrFail($id);
            $input                      = $request->all();
            $input['update_at']            = Carbon::now();
            $input['updated_by']        = \Auth::user()->id;

            $estado->update($input);
            return redirect()->route('estadoinhabilitado.index')
                ->with('success', __('Estado de Inhabilitación actualizado con éxito.'));

    }

    public function destroy($id)
    {
            $estado = EstadoInhabilitado::find($id);
            $estado->status = 1;
            $estado->deleted_at = Carbon::now();
            $estado->deleted_by = \Auth::user()->id;
            $estado->save();
            return redirect()->route('estadoinhabilitado.index')
                ->with('success', __('Estado de Inhabilitación eliminado con éxito.'));

    }
}
