<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ApiLogDataTable;
use App\Facades\UtilityFacades;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Models\ApiLog;
use App\Http\Helpers\Api\RouteHelper;

class ApiLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-apilog|create-apilog|edit-apilog|delete-apilog', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-apilog', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-apilog', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-apilog', ['only' => ['destroy']]);
    }

    /*public function index(ApiLogDataTable $dataTable)
    {
        return $dataTable->render('apilog.index');
    }*/

    public function index(Request $request)
    {
        $permisosDisponibles = RouteHelper::getApiRouteNames();
    
        $logs = ApiLog::query()
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->when($request->filled('method'), fn($q) => $q->where('method', $request->method))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('service_name'), fn($q) => $q->where('service_name', $request->service_name))
            ->when($request->filled('http_status'), function ($q) use ($request) {
                if ($request->http_status == '2') $q->whereBetween('http_status', [200, 299]);
                if ($request->http_status == '4') $q->whereBetween('http_status', [400, 499]);
                if ($request->http_status == '5') $q->whereBetween('http_status', [500, 599]);
            })
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $term = strtolower(trim($request->buscar));
                $q->where(function ($sub) use ($term) {
                    $sub->whereRaw('LOWER(request_body) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(response_body) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(endpoint) LIKE ?', ["%{$term}%"]);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        return view('apilog.index', compact('logs', 'permisosDisponibles'));
    }

    
    public function getRequestBody($uuid)
    {
        $log = ApiLog::where('uuid', $uuid)->firstOrFail();
    
        return response()->json([
            'request_body' => $log->request_body,
        ]);
    }
    
    public function getResponseBody($uuid)
    {
        $log = ApiLog::where('uuid', $uuid)->firstOrFail();
    
        return response()->json([
            'response_body' => $log->response_body,
        ]);
    }




    public function fetch(Request $request)
    {
        $query = ApiLog::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('endpoint', 'like', '%' . $request->search . '%')
                    ->orWhere('api_key', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        $logs = $query->orderByDesc('created_at')->limit(50)->get();

        return response()->json($logs);
    }


    public function destroy($id)
    {
        $ApiLog           = ApiLog::find($id);
        $ApiLog->delete();
        return redirect()->back()->with('success', __('ApiLog eliminado con exito.'));
    }

}
