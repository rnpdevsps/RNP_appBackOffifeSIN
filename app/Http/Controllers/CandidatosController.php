<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatos;
use App\Models\PersonalVotaciones;
use App\DataTables\CandidatosDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;

class CandidatosController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-candidatos|create-candidatos|edit-candidatos|delete-candidatos', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-candidatos', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-candidatos', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-candidatos', ['only' => ['destroy']]);
    }

    public function index(CandidatosDataTable $dataTable)
    {
        return $dataTable->render('candidatos.index');
    }

    public function listaCandidatos()
    {
        $candidatos = Candidatos::with('personal')
            ->where('status', 1)
            ->where('periodo', '2025')
            ->get()
            ->sortBy(function ($candidato) {
                return $candidato->personal->nombre ?? '';
            })
            ->values(); // reindexa la colección
    
        return response()->json($candidatos);
    }

    
    


    public function create()
    {
        $PersonalVotacion = PersonalVotaciones::get();
        $PersonalVotaciones = [];
        $PersonalVotaciones[''] = __('Seleccione un Personal');
        foreach ($PersonalVotacion as $value) {
            $PersonalVotaciones[$value->id] = $value->nombre;
        }
        $view =  view('candidatos.create', compact('PersonalVotaciones'));
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'periodo'          => 'required|max:4',
            'personal_id'         => 'required',
            'foto'         => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max
        ]);

        $existeCandidato = Candidatos::where('personal_id', $request->personal_id)->where('periodo', $request->periodo)->exists();

        if ($existeCandidato) {
            return redirect()->back()->with('failed', 'Ya existe candidado para el periodo '.$request->periodo);
        }

        $input                      = $request->all();
        $input['status']     = '1';
        $input['created_by']        = \Auth::user()->id;
        $input['created_at'] = Carbon::now()->toDateTimeString();

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $fileName  = $file->store('candidatos');
            $input['foto'] = $fileName;
        }


        $candidato                       = Candidatos::create($input);

        return redirect()->route('candidatos.index')->with('success',  __('Candidato creado con exito'));
    }

    public function edit($id)
    {

        $candidato           = Candidatos::find($id);
        $PersonalVotacion = PersonalVotaciones::get();
        $PersonalVotaciones = [];
        $PersonalVotaciones[''] = __('Seleccione un Personal');
        foreach ($PersonalVotacion as $value) {
            $PersonalVotaciones[$value->id] = $value->nombre;
        }

        $view           =   view('candidatos.edit', compact('candidato', 'PersonalVotaciones'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'periodo' => 'required|max:4',
            'personal_id' => 'required',
        ]);

        $input              = $request->all();

        $input['status']     = '1';
        $input['updated_by']        = \Auth::user()->id;
        $input['updated_at'] = Carbon::now()->toDateTimeString();
        $candidato                    = Candidatos::find($id);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName  =  $file->store('candidatos');
            $input['foto']   = $fileName;
        }
        
        $candidato->update($input);

        return redirect()->route('candidatos.index')->with('success',  __('Candidato actualizado con exito'));
    }

    public function destroy($id)
    {
        $candidato           = Candidatos::find($id);
        $candidato->delete();
        return redirect()->back()->with('success', __('Candidato eliminado con exito'));
    }
}
