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

class ApiLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-apilog|create-apilog|edit-apilog|delete-apilog', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-apilog', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-apilog', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-apilog', ['only' => ['destroy']]);
    }

    public function index(ApiLogDataTable $dataTable)
    {
        return $dataTable->render('apilog.index');
    }

   

    public function destroy($id)
    {
        $ApiLog           = ApiLog::find($id);
        $ApiLog->delete();
        return redirect()->back()->with('success', __('ApiLog eliminado con exito.'));
    }

}
