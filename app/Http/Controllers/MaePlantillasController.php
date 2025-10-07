<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\MaePlantillasDataTable;
use Spatie\MailTemplates\Models\MailTemplate;
use Illuminate\Support\Facades\Storage;
use App\Models\MaePlantilla;

class MaePlantillasController extends Controller
{
    public function index(MaePlantillasDataTable $dataTable)
    {
            return $dataTable->render('maeplantillas.index');
    }
    public function create()
    {
            return view('maeplantillas.create');
    }
    public function store(Request $request)
    {
            request()->validate([
                'name' => 'required',
                'content' => 'required',
                'created_by' => 'required',
            ]);

                MaePlantilla::create([
                    'name' => $request->name,
                    'content' => $request->content,
                    'created_by' => $request->created_by, // ID del usuario que crea la plantilla
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => now(),
                    'updated_by' => $request->created_by, // ID del usuario que actualiza la plantilla
                    'deleted_by' => null, // ID del usuario que elimina la plantilla, null si la plantilla no está eliminada
                    'status' => false, // Estado de la plantilla
                ]);
                return redirect()->route('maeplantillas.index')
                ->with('success', __('templete created successfully.'));
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-maeplantillas')) {
            $dataTable = MaePlantilla::find($id);
            return view('maeplantillas.edit', compact('dataTable'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-maeplantillas')) {
            request()->validate([
                'name' => 'required',
                'content' => 'required',
                'created_by' => 'required',
            ]);

            $dataTable = MaePlantilla::find($id);
            $dataTable->name = $request->name;
            $dataTable->content = $request->content;
            $dataTable->created_by = $request->created_by;
            $dataTable->updated_by = $request->created_by;
            $dataTable->updated_at = now();
            $dataTable->status = false;
            $dataTable->update();

            return redirect()->route('maeplantillas.index')->with('success', __('templete updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('edit-maeplantillas')) {
            $dataTable = MaePlantilla::find($id);
            $dataTable->status = true;
            $dataTable->updated_by = \Auth::user()->id;
            $dataTable->deleted_by = \Auth::user()->id;
            $dataTable->updated_at = now();
            $dataTable->deleted_at = now();
            $dataTable->update();

            return redirect()->route('maeplantillas.index')->with('success', __('template deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $fileName           = $request->upload->store('editor');
            $CKEditorFuncNum    = $request->input('CKEditorFuncNum');
            $url                = Storage::url($fileName);
            $msg                = 'Image uploaded successfully';
            $response           = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
