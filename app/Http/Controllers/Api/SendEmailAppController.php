<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Facades\UtilityFacades;
use App\Http\Helpers\Api\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Validator;
use App\Models\Nnapren;
use App\Mail\SendEmailApp;
use Illuminate\Support\Facades\Mail;

class SendEmailAppController extends Controller
{
    protected $property1;

    public function __construct($property1 = null)
    {
        $this->property1 = $property1;
    }


    public function sendmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'subject' => 'required',
            'clientEmail' => 'required',
        ]);
        if ($validator->fails()) {
            return ApiHelpers::validation(['error' => $validator->errors()->all()]);
        }

        $emails = explode(',', UtilityFacades::getsettings('email'));
        $ccemails = explode(',', UtilityFacades::getsettings('ccemail'));
        $bccemails = explode(',', UtilityFacades::getsettings('bccemail'));

        $subject = $request->subject;
        $body = $request->body;
        $base64Image = $request->has('image') && !empty($request->image) ? $request->image : null;

        if ($ccemails && $bccemails) {
            try {
                Mail::to($emails)
                    ->cc($ccemails)
                    ->bcc($bccemails)
                    ->send(new SendEmailApp($subject, $body, $base64Image));
            } catch (\Exception $e) {
            }
        } else if ($ccemails) {
            try {
                Mail::to($emails)
                    ->cc($ccemails)
                    ->send(new SendEmailApp($subject, $body, $base64Image));
            } catch (\Exception $e) {
            }
        } else if ($bccemails) {
            try {
                Mail::to($emails)
                    ->bcc($bccemails)
                    ->send(new SendEmailApp($subject, $body, $base64Image));
            } catch (\Exception $e) {
            }
        } else {
            try {
                Mail::to($emails)->send(new SendEmailApp($subject, $body, $base64Image));
            } catch (\Exception $e) {
            }
        }

        // Send email to the client
        Mail::to($request->clientEmail)->send(new SendEmailApp($subject, $body, $base64Image));
        
        return ApiHelpers::onlysuccess('Correo enviado con exito');
    }
}
