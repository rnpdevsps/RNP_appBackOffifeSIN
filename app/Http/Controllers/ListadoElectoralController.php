<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalVotaciones;
use App\DataTables\ListadoElectoralDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ListadoElectoralController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-listadoelectoral|create-listadoelectoral|edit-listadoelectoral|delete-listadoelectoral', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-listadoelectoral', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-listadoelectoral', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-listadoelectoral', ['only' => ['destroy']]);
    }

    public function index(ListadoElectoralDataTable $dataTable)
    {
        return $dataTable->render('listadoelectoral.index');
    }


    public function create()
    {
        $view =  view('listadoelectoral.create');
        return ['html' => $view->render()];
    }

    public function import()
    {
        $view =  view('listadoelectoral.import');
        return ['html' => $view->render()];
    }

    public function storeListadoElectoral(Request $request)
    {
        $request->validate([
            'periodo' => 'required|max:4',
            'excel' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB
        ]);

        $periodo = $request->input('periodo');

        $rows = Excel::toCollection(null, $request->file('excel'))->first();

        if (!$rows || $rows->isEmpty()) {
            return back()->with('error', 'El archivo está vacío o mal formateado.');
        }

        $headers = $rows->first()->toArray();
        $dataRows = $rows->slice(1);

        $inserted = 0;
        foreach ($dataRows as $row) {
            $rowData = [];

            foreach ($headers as $index => $columnName) {
                $key = \Illuminate\Support\Str::slug(trim($columnName), '_');
                $rowData[$key] = $row[$index] ?? null;
            }

            $rowData['periodo'] = $periodo;
            $rowData['created_at'] = Carbon::now()->toDateTimeString();
            $rowData['created_by'] = \Auth::user()->id;

            if (!empty($rowData['dni'])) {
                DB::table('personal_votaciones')
                    ->where('dni', $rowData['dni'])
                    ->where('periodo', $periodo)
                    ->delete();
            }

            DB::table('personal_votaciones')->insert($rowData);
            $inserted++;
        }

        return back()->with('success', "$inserted registros insertados correctamente con periodo $periodo.");
    }

    public function store(Request $request)
    {
        request()->validate([
            'periodo'          => 'required|max:4'
        ]);

        $cadena = $request->dni;
        $dni_sin_guion = str_replace("-", "", $cadena);

        $existePersonal = PersonalVotaciones::where('dni', $dni_sin_guion)->where('periodo', $request->periodo)->exists();

        if ($existePersonal) {
            return redirect()->back()->with('failed', 'Ya existe Personal para el periodo '.$request->periodo);
        }

        $input               = $request->all();   
        $flag = ($request->flag == 'on') ? 1 : 0;
        $input['dni']              =  $dni_sin_guion;
        $input['status']     = '1';
        $input['flag']     = $flag;
        $input['created_by'] = \Auth::user()->id;
        $input['created_at'] = Carbon::now()->toDateTimeString();
        $personal            = PersonalVotaciones::create($input);

        return redirect()->route('listadoelectoral.index')->with('success',  __('Personal creado con exito'));
    }

    public function edit($id)
    {
        $PersonalVotaciones           = PersonalVotaciones::find($id);
        $view           =   view('listadoelectoral.edit', compact('PersonalVotaciones'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'periodo' => 'required|max:4'
        ]);

        $input              = $request->all();
        $flag = ($request->flag == 'on') ? 1 : 0;
        $cadena = $input['dni'];
        $dni_sin_guion = str_replace("-", "", $cadena);
        $input['dni']              =  $dni_sin_guion;
        $input['status']     = '1';
        $input['flag']     = $flag;
        $input['updated_by']        = \Auth::user()->id;
        $input['updated_at'] = Carbon::now()->toDateTimeString();
        $PersonalVotaciones                    = PersonalVotaciones::find($id);
        
        $PersonalVotaciones->update($input);

        return redirect()->route('listadoelectoral.index')->with('success',  __('Personal actualizado con exito'));
    }

    public function destroy($id)
    {
        $personal           = PersonalVotaciones::find($id);
        $personal->delete();
        return redirect()->back()->with('success', __('Personal eliminado con exito'));
    }
}
