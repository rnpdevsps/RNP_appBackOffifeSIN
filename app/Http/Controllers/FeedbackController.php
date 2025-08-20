<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FormValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create(Request $request)
    {
        $formId       = $request->formId;
        $formValueId  = $request->formValueId;
        $currentUrl   = $request->currentUrl;
        $view         = view('feedback.feedback', compact('formId', 'formValueId', 'currentUrl'));
        return ['html' => $view->render()];
    }

    public function store(Request $request)
    {
        request()->validate([
            'name'        => 'required|String|max:191',
            'desc'        => 'required|string',
            'rating'      => 'required|numeric|min:0.5|max:5',
            'formId'      => 'required|integer|exists:forms,id',
            'formValueId' => 'required|integer|exists:form_values,id'
        ]);
        $exists = Feedback::where('form_id', $request->formId)
            ->where('form_value_id', $request->formValueId)
            ->exists();
        if (!$exists) {
            $feedback                = new Feedback();
            $feedback->name          = $request->name;
            $feedback->description   = $request->desc;
            $feedback->rating        = $request->rating;
            $feedback->form_id       = $request->formId;
            $feedback->form_value_id = $request->formValueId;
            $feedback->save();
            return response()->json(['success' => true, 'message' => 'Your feedback has been submitted.']);
        }
    }

    public function show(string $id)
    {
        if (Auth::user()->can('show-feedback')) {
            try {
                $feedback  = Feedback::find($id);
                $formValue = FormValue::find($feedback->form_value_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('errors', $th->getMessage());
            }
            $view = view('feedback.view', compact('formValue', 'feedback'));
            return ['html'=>$view->render()];
        } else {
            return redirect()->back()->with('errors', __('Permission denied.'));
        }
    }

    public function destroy(string $id)
    {
        if (Auth::user()->can('delete-feedback')) {
            $feedback = Feedback::find($id);
            $feedback->delete();
            return redirect()->back()->with('success', 'Feedback Delete successfully...');
        } else {
            return redirect()->back()->with('errors', __('Permission denied.'));
        }
    }
}
