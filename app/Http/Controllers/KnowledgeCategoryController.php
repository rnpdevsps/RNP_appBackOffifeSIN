<?php

namespace App\Http\Controllers;

use App\DataTables\KnowledgeCategoryDataTable;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class KnowledgeCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manage-knowledge-category|create-knowledge-category|edit-knowledge-category|delete-knowledge-category', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-knowledge-category', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-knowledge-category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-knowledge-category', ['only' => ['destroy']]);
    }

    public function index(KnowledgeCategoryDataTable $dataTable)
    {
        return $dataTable->render('knowledge-category.index');
    }

    public function create()
    {
        $view = view('knowledge-category.create');
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'name'       => 'required|unique:knowledge_categories|max:191',
        ]);
        KnowledgeCategory::create([
            'name' => $request->input('name'),
            'created_by' => auth()->user()->id,
    ]);
        return redirect()->route('knowledge-category.index')->with('success', __('Knowledge Category created successfully.'));
    }

    public function edit($id)
    {
        $knowledgeCategory = KnowledgeCategory::find($id);
        $view = View::make('knowledge-category.edit', compact('knowledgeCategory'));
        return ['html' => $view->render()];
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'name'  => 'required|max:191|unique:knowledge_categories,name,' . $id,
        ]);
        $knowledgeCategory = KnowledgeCategory::find($id);
        $knowledgeCategory->name = $request->input('name');
        $knowledgeCategory->save();
        return redirect()->route('knowledge-category.index')->with('success', __('Knowledge Category updated successfully.'));
    }

    public function destroy($id)
    {
        $knowledgeCategory = KnowledgeCategory::find($id);
        $knowledgeCategory->delete();
        return redirect()->route('knowledge-category.index')->with('success', __('Knowledge Category deleted successfully.'));
    }
}
