<?php

namespace App\Http\Controllers;

use App\DataTables\TipoInmuebleDataTable;
use Illuminate\Http\Request;
use App\Models\TipoInmueble;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class TipoInmueblesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-tipoinmuebles', ['only' => ['index']]);
        $this->middleware('permission:create-tipoinmuebles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-tipoinmuebles', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-tipoinmuebles', ['only' => ['destroy']]);

    }

    public function index(TipoInmuebleDataTable $dataTable)
    {
        return $dataTable->render('tipoinmuebles.index');

    }

    public function create()
    {

        $view =  view('tipoinmuebles.create');
        return ['html' => $view->render()];

    }

    public function store(Request $request)
    {

        $input                      = $request->all();
        $input['created_by']        = \Auth::user()->id;
        $input['status']            = 0;
        $input['updated_by']        = \Auth::user()->id;
        TipoInmueble::create($input);

        return redirect()->route('tipoinmuebles.index')->with('success', __('Tipo de Inmueble creado con éxito.'));

    }

    public function edit($id)
    {
        $estado = TipoInmueble::findOrFail($id);
        $view   = View::make('tipoinmuebles.edit', compact('estado'));
        return ['html' => $view->render()];

    }

    public function update(Request $request, $id)
    {
        $estado = TipoInmueble::findOrFail($id);
        $input                      = $request->all();
        $input['update_at']            = Carbon::now();
        $input['updated_by']        = \Auth::user()->id;

        $estado->update($input);
        return redirect()->route('tipoinmuebles.index')->with('success', __('Tipo de Inmueble actualizado con éxito.'));

    }

    public function destroy($id)
    {
            $estado = TipoInmueble::find($id);
            $estado->status = 1;
            $estado->deleted_at = Carbon::now();
            $estado->deleted_by = \Auth::user()->id;
            $estado->save();
            return redirect()->route('tipoinmuebles.index')->with('success', __('Tipo de Inmueble eliminado con éxito.'));

    }
}
