<?php

namespace App\Http\Controllers;

use App\DataTables\ComparecientesDataTable;
use App\Models\ComparecienteTramite;
use Illuminate\Http\Request;

class ComparecientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ComparecienteTramiteDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(ComparecientesDataTable $dataTable)
    {
        return $dataTable->render('comparecientes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('comparecientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'tramite_id' => 'required',
            'name' => 'required',
            'dni' => 'required',
            'created_by' => 'required',
        ]);
        $compareciente = new ComparecienteTramite();
        $compareciente->tramite_id = $request->tramite_id;
        $compareciente->name = $request->name;
        $compareciente->dni = $request->dni;
        $compareciente->estado_autorizacion = "Pendiente de autorización";
        $compareciente->created_by = $request->created_by;
        $compareciente->created_at = now();
        $compareciente->updated_at = now();
        $compareciente->deleted_at = now();
        $compareciente->updated_by = $request->created_by;
        $compareciente->deleted_by = null;

        $compareciente->save();
        return redirect()->route('comparecientes.index')
            ->with('success', __('Compareciente creado exitosamente.'));


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComparecienteTramite  $comparecienteTramite
     * @return \Illuminate\Http\Response
     */
    public function show(ComparecienteTramite $comparecienteTramite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComparecienteTramite  $comparecienteTramite
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $compareciente = ComparecienteTramite::find($id);
        return view('comparecientes.edit', compact('compareciente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComparecienteTramite  $comparecienteTramite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        request()->validate([
            'tramite_id' => 'required',
            'name' => 'required',
            'dni' => 'required',
            'created_by' => 'required',
        ]);
        $dataTable = ComparecienteTramite::find($id);
        $dataTable->tramite_id = $request->tramite_id;
        $dataTable->name = $request->name;
        $dataTable->dni = $request->dni;
        $dataTable->estado_autorizacion = $request->estado_autorizacion ?? $dataTable->estado_autorizacion;
        $dataTable->updated_at = now();
        $dataTable->updated_by = $request->created_by;
        $dataTable->update();
        return redirect()->route('comparecientes.index')
            ->with('success', 'Compareciente actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComparecienteTramite  $comparecienteTramite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // fisicaly delete compareciente
        $dataTable = ComparecienteTramite::find($id);
        $dataTable->delete();
        return redirect()->route('comparecientes.index')
            ->with('success', 'Compareciente eliminado correctamente');

    }
}
