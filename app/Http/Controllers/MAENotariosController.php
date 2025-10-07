<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaeNotario;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\DataTables\MAENotariosDataTable;
use App\Facades\UtilityFacades;
use App\Mail\RegisterMail;
use App\Models\NotificationsSetting;
use App\Models\SocialLogin;
use App\Notifications\RegisterMail as NotificationsRegisterMail;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Facades\Mail;
use Lab404\Impersonate\Impersonate;
use Spatie\MailTemplates\Models\MailTemplate;

class MAENotariosController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-maenotarios|create-maenotarios|edit-maenotarios|delete-maenotarios', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-maenotarios', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-maenotarios', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-maenotarios', ['only' => ['destroy']]);
    }

    public function index(MAENotariosDataTable $dataTable)
    {
        if (\Auth::user()->users_grid_view == 1) {
            return redirect()->route('maenotariosgrid.view', 'view');
        }
        return $dataTable->render('maenotarios.index');
    }

    public function create()
    {
        $view =  view('maenotarios.create');
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'dni'    => 'required|unique:mae_notarios,dni',
            'name'   => 'required|max:50',
            'phone'  => 'required|unique:mae_notarios,phone',
        ]);
        $countries          = \App\Core\Data::getCountriesList();
        $country_code       = $countries[$request->country_code]['phone_code'];

        $input                      = $request->all();
        $dni_original = $input['dni'];
        $dni_modificado = str_replace("-", "", $dni_original);
        $input['dni']              =  $dni_modificado;
        $input['name']              = $input['name'];
        $input['country_code']      = $country_code;
        $input['phone']             = $input['phone'];
        $input['status']     = '1';
        $input['created_by']        = \Auth::user()->id;
        $maenotario                       = MaeNotario::create($input);

        return redirect()->route('maenotarios.index')
            ->with('success',  __('MAE Notario creado con exito.'));
    }

    public function edit($id)
    {
        $maenotario           = MaeNotario::find($id);
        $view           =   view('maenotarios.edit', compact('maenotario'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'dni'   => 'required',
            'name'  => 'required|max:50',
            'phone' => 'required|unique:mae_notarios,phone,' . $id,
        ]);
        $countries          = \App\Core\Data::getCountriesList();
        $country_code       = $countries[$request->country_code]['phone_code'];

        $input              = $request->all();
        $dni_original = $input['dni'];
        $dni_modificado = str_replace("-", "", $dni_original);
        $input['dni']              =  $dni_modificado;
        $input['name']              = $input['name'];
        $input['country_code']      = $country_code;
        $input['phone']             = $input['phone'];
        $maenotario                    = MaeNotario::find($id);
        $maenotario->update($input);
        return redirect()->route('maenotarios.index')->with('success',  __('MAE Notario actualizado con exito.'));
    }

    public function destroy($id)
    {
        if ($id != 1) {
            $maenotario  = MaeNotario::find($id);

            $maenotario->delete();
            return redirect()->back()->with('success', __('MAE Notario eliminado con exito.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function userStatus(Request $request, $id)
    {
        $maenotarios = MaeNotario::find($id);

        if ($maenotarios) {
            $DNI = $maenotarios->dni;
            $input = ($request->value == "true") ? 1 : 0;
            $maenotarios->status = $input;
            $maenotarios->save();

            // Actualizar el estado activo del usuario
            $user = User::where('dni', $DNI)->first();
            if ($user) {
                $user->active_status = $input;
                $user->save();
            }
        }

        return response()->json(['is_success' => true, 'message' => __('Se cambio el estado con exito.')]);
    }

    public function gridView($slug = '')
    {
        $user                   = \Auth::user();
        $user->users_grid_view  = ($slug) ? 1 : 0;
        $user->save();
        if ($user->users_grid_view == 0) {
            return redirect()->route('maenotarios.index');
        }
        $maenotarios = MaeNotario::get();
        return view('maenotarios.grid-view', compact('maenotarios'));
    }
}
