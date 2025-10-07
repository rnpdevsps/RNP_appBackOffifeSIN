<?php

namespace App\Http\Controllers;

use App\DataTables\BitacoraNotarioDataTable;
use App\Models\BitacoraNotario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BitacoraNotarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index($id)
    {

        $Bitacora = DB::table('bitacora_notarios')
                    ->join('estado_inhabilitados', 'bitacora_notarios.estadoinhabilitado_id', '=', 'estado_inhabilitados.id')
                    ->join('users', 'bitacora_notarios.created_by', '=', 'users.id')
                    ->select('bitacora_notarios.*', 'estado_inhabilitados.descripcion', 'users.name')
                    ->orderBy('created_at','desc')
                    ->paginate(20);

        $view     =   view('BitacoraNotario.index', compact('Bitacora'));
        return ['html' => $view->render()];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BitacoraNotario $bitacoraNotario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BitacoraNotario $bitacoraNotario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BitacoraNotario $bitacoraNotario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BitacoraNotario $bitacoraNotario)
    {
        //
    }
}
