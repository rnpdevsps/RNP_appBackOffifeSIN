<?php

namespace App\Http\Controllers;

use App\DataTables\KnowledgeBaseDataTable;
use App\Models\DocumentGenrator;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class KnowledgeBaseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-knowledge|create-knowledge|edit-knowledge|delete-knowledge', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-knowledge', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-knowledge', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-knowledge', ['only' => ['destroy']]);
    }

    public function index(KnowledgeBaseDataTable $dataTable)
    {
        return $dataTable->render('knowledge-base.index');
    }

    public function create()
    {
        $knowledgesCategories  = KnowledgeCategory::pluck('name', 'id');
        $documents             = DocumentGenrator::pluck('title', 'id');
        $view = view('knowledge-base.create', compact('knowledgesCategories', 'documents'));
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'title'          => 'required|max:191',
            'category'       => 'required||exists:knowledge_categories,id',
            'document'       => 'required|exists:document_genrators,id',
            'description'    => 'required|string',
        ]);

        $knowledgeBase              = new KnowledgeBase();
        $knowledgeBase->title       = $request->title;
        $knowledgeBase->category_id = $request->category;
        $knowledgeBase->document_id = $request->document;
        $knowledgeBase->description = $request->description;
        $knowledgeBase->created_by  = auth()->user()->id;
        $knowledgeBase->save();
        return redirect()->route('knowledges.index')->with('success', __('Knowledge created successfully.'));
    }

    public function edit($id)
    {
        $knowledgeBase         = KnowledgeBase::find($id);
        $knowledgesCategories  = KnowledgeCategory::pluck('name', 'id');
        $documents             = DocumentGenrator::pluck('title', 'id');
        $view = View::make('knowledge-base.edit', compact('knowledgeBase','knowledgesCategories', 'documents'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'title'          => 'required|max:191' . $id,
            'category'       => 'required||exists:knowledge_categories,id',
            'document'       => 'required|exists:document_genrators,id',
            'description'    => 'required|string',
        ]);
        $knowledgeBase = KnowledgeBase::find($id);
        $knowledgeBase->title       = $request->title;
        $knowledgeBase->category_id = $request->category;
        $knowledgeBase->document_id = $request->document;
        $knowledgeBase->description = $request->description;
        $knowledgeBase->save();
        return redirect()->route('knowledges.index')->with('success', __('Knowledge updated successfully.'));
    }

    public function destroy($id)
    {
        $knowledgeBase = KnowledgeBase::find($id);
        $knowledgeBase->delete();
        return redirect()->route('knowledges.index')->with('success', __('Knowledge deleted successfully.'));
    }
}
