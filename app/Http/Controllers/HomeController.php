<?php

namespace App\Http\Controllers;

use App\DataTables\DashboardWidgetDataTable;
use App\Facades\UtilityFacades;
use App\Models\Announcement;
use App\Models\Blog;
use App\Models\DashboardWidget;
use App\Models\Faq;
use App\Models\FooterSetting;
use App\Models\Form;
use App\Models\FormValue;
use App\Models\Poll;
use App\Models\Role;
use App\Models\Testimonial;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', '2fa']);
    }

    public function landingPage()
    {
        \DB::disconnect('oracle');

        return redirect()->route('login');
        
    }


    public function changeLang($lang = '')
    {
        if ($lang == '') {
            $lang = UtilityFacades::getActiveLanguage();
        }
        Cookie::queue('lang', $lang, 120);
        return redirect()->back()->with('success', __('Language successfully changed.'));
    }


    public function index()
    {
        $usr = \Auth::user();
        $userId = $usr->id;
        $roles = Role::where('name', $usr->type)->first();
        $roleId = $usr->roles->first()->id;
        if ($usr->type == 'Admin') {
            $user = User::where('type', '!=',  'Admin')->count();
        } else {
            $user = User::where('created_by', Auth::user()->id)->count();
        }

        return  view('dashboard/home', compact('user'));
        
    }


    public function indexDashboard(DashboardWidgetDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-dashboardwidget')) {
            return $dataTable->render('dashboard.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function changeThememode(Request $request)
    {
        $user = \Auth::user();
        if ($user->dark_layout == 1) {
            $user->dark_layout = 0;
        } else {
            $user->dark_layout = 1;
        }
        $user->save();
        return response()->json(['mode' => $user->dark_layout]);
    }

    public function createDashboard()
    {
        if (\Auth::user()->can('create-dashboardwidget')) {
            if (Auth::user()->type == 'Admin') {
                $form = form::all();
            } else {
                $form = form::select('id', 'title')->where('created_by', Auth::user()->id)->get();
            }
            $poll = Poll::all();
            $p = [];
            $p[''] = __('Please select type');
            if (count($form) > 0) {
                $p['form'] = "Form";
            }
            if (count($poll) > 0) {
                $p['poll'] = "Poll";
            }
            $forms = [];
            $forms[''] = __('No select title');
            foreach ($form as $val) {
                $forms[$val->id] = $val->title;
            }
            $polls = [];
            $polls[''] = __('No select title');
            foreach ($poll as $value) {
                $polls[$value->id] = $value->title;
            }
            return view('dashboard.create', compact('forms', 'polls', 'p'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function storeDashboard(Request $request)
    {
        if (\Auth::user()->can('create-dashboardwidget') && Auth::user()->type == 'Admin') {
            $validator = \Validator::make($request->all(), [
                'title'      => 'required|max:191',
                'size'       => 'required',
                'type'       => 'required',
                'chart_type' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $wid                = DashboardWidget::orderBy('id', 'DESC')->first();
            $widget             = new DashboardWidget();
            $widget->title      = $request->title;
            $widget->size       = $request->size;
            $widget->type       = $request->type;
            $widget->chart_type = $request->chart_type;
            $widget->created_by = Auth::user()->id;
            $widget->position   = (!empty($wid) ? ($wid->position + 1) : 0);

            if ($request->type == 'form') {
                $widget->form_id = $request->form_title;
                $widget->field_name = $request->field_name;
            } else {
                $widget->poll_id = $request->poll_title;
            }
            $widget->save();
            return redirect()->route('index.dashboard')
                ->with('success', __('Dashboard created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function editDashboard($id)
    {
        if (\Auth::user()->can('edit-dashboardwidget')) {
            $dashboard = DashboardWidget::find($id);
            $form = form::all();
            $polls = [];
            $forms = [];
            $poll = Poll::all();
            $label = [];
            if ($dashboard->type == 'form') {
                foreach ($form as $val) {
                    $forms[$val->id] = $val->title;
                }
                $formTitle =  form::find($dashboard->form_id);
                $home = json_decode($formTitle->json);
                foreach ($home as $hom) {
                    foreach ($hom as $key => $var) {
                        if ($var->type == 'select' || $var->type == 'radio-group' || $var->type == 'date' || $var->type == 'checkbox-group' || $var->type == 'starRating') {
                            $label[$var->name] = $var->label;
                        }
                    }
                }
            } else {
                foreach ($poll as $val) {
                    $polls[$val->id] = $val->title;
                }
            }
            return view('dashboard.edit', compact('dashboard', 'polls', 'label', 'forms'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function updateDashboard(Request $request, $id)
    {
        if (\Auth::user()->can('edit-dashboardwidget') && Auth::user()->type == 'Admin') {
            $validator = \Validator::make($request->all(), [
                'title'       => 'required|max:191',
                'size'        => 'required',
                'type'        => 'required',
                'chart_type'  => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $dashboard                  = DashboardWidget::find($id);
            $dashboard->title           = $request->title;
            $dashboard->size            = $request->size;
            $dashboard->type            = $request->type;
            if ($request->type == 'form') {
                $dashboard->form_id     = $request->form_title;
                $dashboard->field_name  = $request->field_name;
            } else {
                $dashboard->poll_id     = $request->poll_title;
            }
            $dashboard->chart_type      = $request->chart_type;
            $dashboard->update();
            return redirect()->route('index.dashboard')->with('success', __('Dashboard updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function deleteDashboard($id)
    {
        if (\Auth::user()->can('delete-dashboardwidget') && Auth::user()->type == 'Admin') {
            $dashboard = DashboardWidget::find($id);
            $dashboard->delete();
            return redirect()->route('index.dashboard')
                ->with('success', __('Dashboard deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function WidgetChnages(Request $request)
    {
        $widget = $request->widget;
        $form = form::find($widget);
        $home = json_decode($form->json);
        $label = [];
        if (isset($home)) {
            foreach ($home as $hom) {
                foreach ($hom as $key => $var) {
                    if ($var->type == 'select' || $var->type == 'radio-group' || $var->type == 'date' || $var->type == 'checkbox-group' || $var->type == 'starRating') {
                        $label[$key] = $var;
                    }
                }
            }
        }
        return response()->json($label, 200);
    }

    public function updatePosition(Request $request)
    {
        if (\Auth::user()->can('manage-dashboardwidget')) {
            $widgets = $request->all();
            foreach ($widgets['position'] as $key => $item) {
                $dash = DashboardWidget::where('id', '=', $item)->first();
                $dash->position = $key;
                $dash->save();
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function readNotification()
    {
        auth()->user()->notifications->markAsRead();
        return response()->json(['is_success' => true], 200);
    }

    public function userFormQrcode($id)
    {
        $hashids = new Hashids('', 20);
        $decodedId = $hashids->decodeHex($id);
        $forms = Form::where('created_by', $decodedId)->get();
        if ($forms) {
            return view('dashboard.users-forms', compact('forms'));
        } else {
            abort(404);
        }
    }
}
