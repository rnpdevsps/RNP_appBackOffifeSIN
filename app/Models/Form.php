<?php

namespace App\Models;

use App\Facades\UtilityFacades;
use App\Mail\FormSubmitEmail;
use BulkGate\Message\Connection;
use BulkGate\Sms\Message;
use BulkGate\Sms\Sender;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput;
use HubSpot\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use mediaburst\ClockworkSMS\Clockwork;
use Omniphx\Forrest\Providers\Laravel\Facades\Forrest;
use Pipedrive\Api\ActivitiesApi;
use Pipedrive\Api\PersonsApi;
use Pipedrive\Configuration;
use Twilio\Rest\Client;
use Vonage\Client as VonageClient;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class Form extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'title', 'json', 'logo', 'success_msg', 'thanks_msg', 'email', 'amount', 'currency_symbol', 'currency_name', 'theme', 'theme_color', 'color_flag', 'theme_background_image', 'category_id',
        'payment_status', 'payment_type', 'bccemail', 'ccemail', 'allow_comments', 'allow_share_section', 'assign_type', 'created_by', 'set_end_date', 'set_end_date_time', 'limit',
        'limit_status', 'form_fill_edit_lock', 'feedback_enabled', 'enable_session_timer', 'form_status'
    ];

    public function getFormArray()
    {
        return json_decode($this->json);
    }

    public function Roles()
    {
        return $this->belongsToMany('Spatie\Permission\Models\Role', 'user_forms', 'form_id', 'role_id');
    }

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function assignFormRoles($roleIds)
    {
        $roles = $this->Roles->pluck('name', 'id')->toArray();
        if ($roleIds) {
            foreach ($roleIds as $id) {
                if (!array_key_exists($id, $roles)) {
                    UserForm::create(['form_id' => $this->id, 'role_id' => $id]);
                } else {
                    unset($roles[$id]);
                }
            }
        }
        if ($roles) {
            foreach ($roles as $id => $name) {
                UserForm::where(['form_id' => $this->id, 'role_id' => $id])->delete();
            }
        }
    }

    public function commmant()
    {
        return $this->hasMany(FormComments::class, 'form_id', 'id');
    }

    //assign form user
    public function assignedusers()
    {
        return $this->belongsToMany(User::class, 'assign_forms_users', 'form_id', 'user_id');
    }

    public function assignUser($usersIds)
    {
        $formUsers = $this->assignedusers->pluck('name', 'id')->toArray();
        if ($usersIds) {
            foreach ($usersIds as $id) {
                if (!array_key_exists($id, $formUsers)) {
                    AssignFormsUsers::create(['form_id' => $this->id, 'user_id' => $id]);
                } else {
                    unset($formUsers[$id]);
                }
            }
        }
        if ($formUsers) {
            foreach ($formUsers as $id => $name) {
                AssignFormsUsers::where(['form_id' => $this->id, 'user_id' => $id])->delete();
            }
        }
    }

    //assign form roles
    public function assignedroles()
    {
        return $this->belongsToMany('Spatie\Permission\Models\Role', 'assign_forms_roles', 'form_id', 'role_id');
    }

    public function assignRole($usersIds)
    {
        $formRoles = $this->assignedroles->pluck('name', 'id')->toArray();
        if ($usersIds) {
            foreach ($usersIds as $id) {
                if (!array_key_exists($id, $formRoles)) {
                    AssignFormsRoles::create(['form_id' => $this->id, 'role_id' => $id]);
                } else {
                    unset($formRoles[$id]);
                }
            }
        }
        if ($formRoles) {
            foreach ($formRoles as $id => $name) {
                AssignFormsRoles::where(['form_id' => $this->id, 'role_id' => $id])->delete();
            }
        }
    }

    // scope to get all forms
    public function scopeGetAllForms($query)
    {
        return $query->where('created_by', \Auth::user()->id)
        ->orWhere('created_by', \Auth::user()->created_by);
    }

    //scope to get all assign form and created form
    public function scopeGetAllAssignForm($query, $userRole)
    {
        return $query->where(function ($query)   use ($userRole) {
            $query->whereIn('id', function ($query) use ($userRole) {
                $query->select('form_id')->from('assign_forms_roles')->where('role_id', $userRole);
            })->orWhereIn('id', function ($query) {
                $query->select('form_id')->from('assign_forms_users')->where('user_id', \Auth::user()->id);
            })->orWhere('assign_type', 'public');
        });
    }

    public static function integrationFormData($form, $formValue)
    {

        $appName = UtilityFacades::getsettings('app_name');
        $formTitle = $form->title;
        //slack integration
        $formslacksetting = FormIntegrationSetting::where('key', 'slack_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formslacksetting) {
            if ($formslacksetting->json) {
                $slackFieldJsons = json_decode($formslacksetting->field_json, true);
                $slackJsons = json_decode($formslacksetting->json, true);

                foreach ($slackJsons as $slackJsonkey => $slackJson) {
                    if ($slackJson['slack_webhook_url']) {
                        $slackdata = [];
                        $slackdata['blocks'] = [];
                        $slackdata['blocks'][] = [
                            'type' => 'header',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => "[$appName]\n\n$formTitle",
                                'emoji' => true
                            ]
                        ];

                        $formValueJsons = json_decode($formValue->json);

                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValueItem) { // Changed variable name to avoid conflict
                                foreach ($slackFieldJsons as $slackFieldkey => $slackFieldJson) {
                                    if ($slackFieldkey == $slackJsonkey) {
                                        $slackarr = explode(',', $slackFieldJson);
                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $slackarr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $slackdata['blocks'][] = [
                                                            'type' => 'section',
                                                            'fields' => [
                                                                [
                                                                    'type' => 'mrkdwn',
                                                                    'text' => "*$formValueItem->label:*"
                                                                ],
                                                                [
                                                                    'type' => 'mrkdwn',
                                                                    'text' => $Value->label
                                                                ]
                                                            ]
                                                        ];
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' && $formValueItem->type != 'file' && $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'SignaturePad' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $slackarr)) {
                                                $val = (property_exists($formValueItem, 'value')) ? $formValueItem->value : null;
                                                $slackdata['blocks'][] = [
                                                    'type' => 'section',
                                                    'fields' => [
                                                        [
                                                            'type' => 'mrkdwn',
                                                            'text' => "*$formValueItem->label:*"
                                                        ],
                                                        [
                                                            'type' => 'mrkdwn',
                                                            'text' => $val
                                                        ]
                                                    ]
                                                ];
                                            }
                                        } elseif ($formValueItem->type == 'file' || $formValueItem->type == 'SignaturePad') {
                                            if (in_array($formValueItem->name, $slackarr)) {
                                                if (property_exists($formValueItem, 'multiple') && $formValueItem->multiple) {
                                                    if (property_exists($formValueItem, 'value')) {
                                                        $files = $formValueItem->value;
                                                        $accessoryImages = [];
                                                        foreach ($files as $file) {
                                                            $accessoryImages[] = [
                                                                'type' => 'image',
                                                                'image_url' => asset(Storage::url($file)),
                                                                'alt_text' => $formValueItem->name
                                                            ];
                                                        }
                                                        foreach ($accessoryImages as $image) {
                                                            $slackdata['blocks'][] = [
                                                                'type' => 'section',
                                                                'text' => [
                                                                    'type' => 'mrkdwn',
                                                                    'text' => "*$formValueItem->label:*"
                                                                ],
                                                                'accessory' => $image
                                                            ];
                                                        }
                                                    }
                                                } else {
                                                    if (property_exists($formValueItem, 'value')) {
                                                        if (is_array($formValueItem->value)) {
                                                            $files = $formValueItem->value;
                                                            $accessoryImages = [];
                                                            foreach ($files as $file) {
                                                                $accessoryImages[] = [
                                                                    'type' => 'image',
                                                                    'image_url' => asset(Storage::url($file)),
                                                                    'alt_text' => $formValueItem->name
                                                                ];
                                                            }
                                                            foreach ($accessoryImages as $image) {
                                                                $slackdata['blocks'][] = [
                                                                    'type' => 'section',
                                                                    'text' => [
                                                                        'type' => 'mrkdwn',
                                                                        'text' => "*$formValueItem->label:*"
                                                                    ],
                                                                    'accessory' => $image
                                                                ];
                                                            }
                                                        } else {
                                                            $slackdata['blocks'][] = [
                                                                'type' => 'section',
                                                                'text' => [
                                                                    'type' => 'mrkdwn',
                                                                    'text' => "*$formValueItem->label:*"
                                                                ],
                                                                'accessory' => [
                                                                    'type' => 'image',
                                                                    'image_url' => asset(Storage::url($formValueItem->value)),
                                                                    'alt_text' => $formValueItem->name
                                                                ]
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $response = Http::post($slackJson['slack_webhook_url'], [
                            'text' => "[$appName]",
                            'blocks' => $slackdata['blocks']
                        ]);
                    }
                }
            }
        }

        //telegram integration
        $formtelegramsetting = FormIntegrationSetting::where('key', 'telegram_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formtelegramsetting) {
            if ($formtelegramsetting->json) {
                $telegramFieldJsons = json_decode($formtelegramsetting->field_json, true);
                $telegramJsons = json_decode($formtelegramsetting->json, true);

                foreach ($telegramJsons as $telegramJsonkey => $telegramJson) {
                    if ($telegramJson['telegram_access_token'] && $telegramJson['telegram_chat_id']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $teltable .= "<b>Field Label</b> | <b>Value</b>\n";
                        $teltable .= "| --- | --- |\n";

                        $formValueJsons = json_decode($formValue->json);

                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValueItem) { // Changed variable name to avoid conflict
                                foreach ($telegramFieldJsons as $telegramFieldkey => $telegramFieldJson) {
                                    if ($telegramFieldkey == $telegramJsonkey) {
                                        $telegramarr = explode(',', $telegramFieldJson);
                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $telegramarr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "| {$formValueItem->label} | {$Value->label} |\n";
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' && $formValueItem->type != 'file' && $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'SignaturePad' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $telegramarr)) {
                                                $val = (property_exists($formValueItem, 'value')) ? $formValueItem->value : null;
                                                $teltable .= "| {$formValueItem->label} | {$val} |\n";
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        try {
                            $telegramMessage = "<pre>{$teltable}</pre>";
                            $telegrambot = $telegramJson['telegram_access_token'];
                            $telegramchatid = $telegramJson['telegram_chat_id'];
                            $response = Http::post("https://api.telegram.org/bot{$telegrambot}/sendMessage", [
                                'chat_id' => $telegramchatid,
                                'text' => $telegramMessage,
                                'parse_mode' => 'HTML',
                            ]);
                            if ($response->failed()) {
                                // Handle failure if necessary
                            }
                            $responseData = $response->json();
                        } catch (\Exception $e) {
                            // Handle exceptions if necessary
                        }
                    }
                }
            }
        }


        //mailgun integration
        $formmailgunsetting = FormIntegrationSetting::where('key', 'mailgun_integration')->where('form_id', $form->id)->where('status', 1)->first();
        $formVale = [];

        if ($formmailgunsetting) {
            if ($formmailgunsetting->json) {
                $mailgunFieldJsons = json_decode($formmailgunsetting->field_json, true);
                $mailgunJsons = json_decode($formmailgunsetting->json, true);

                foreach ($mailgunJsons as $mailgunJsonkey => $mailgunJson) {
                    if ($mailgunJson['mailgun_email'] && $mailgunJson['mailgun_domain'] && $mailgunJson['mailgun_secret']) {
                        $formValueJsons = json_decode($formValue->json);

                        foreach ($formValueJsons as $formValueJsonkgun => $formValueJson) {
                            foreach ($formValueJson as $formValueJsonk1gun => $formValueItem) { // Changed variable name to avoid conflict
                                foreach ($mailgunFieldJsons as $mailgunFieldkey => $mailgunFieldJson) {
                                    if ($mailgunFieldkey == $mailgunJsonkey) {
                                        $mailgunarr = explode(',', $mailgunFieldJson);

                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $mailgunarr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        // Storing the selected values for later use
                                                        $formVale[$formValueJsonkgun][$formValueJsonk1gun] = $formValueItem;
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' && $formValueItem->type != 'file' && $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'SignaturePad' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $mailgunarr)) {
                                                // Storing the value for later use
                                                $formVale[$formValueJsonkgun][$formValueJsonk1gun] = $formValueItem;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        config([
                            'mail.default'              => 'mailgun',
                            'services.mailgun.domain'   => $mailgunJson['mailgun_domain'],
                            'services.mailgun.secret'   => $mailgunJson['mailgun_secret'],
                            'mail.from.address'         => 'hello@example.com',
                            'mail.from.name'            => $formTitle,
                        ]);

                        try {
                            // Pass both $formValue and $formVale to the email template
                            Mail::to($mailgunJson['mailgun_email'])->send(new FormSubmitEmail($formValue, $formVale));
                        } catch (\Exception $e) {
                            // Handle exceptions if necessary
                        }
                    }
                }
            }
        }

        // bulkgate integration
        $formbulkgatesetting = FormIntegrationSetting::where('key', 'bulkgate_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formbulkgatesetting) {
            if ($formbulkgatesetting->json) {
                $bulkgateFieldJsons = json_decode($formbulkgatesetting->field_json, true);
                $bulkgateJsons = json_decode($formbulkgatesetting->json, true);

                foreach ($bulkgateJsons as $bulkgateJsonkey => $bulkgateJson) {
                    if ($bulkgateJson['bulkgate_number'] && $bulkgateJson['bulkgate_token'] && $bulkgateJson['bulkgate_app_id']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";

                        $formValueJsons = json_decode($formValue->json);

                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValueItem) { // Changed variable name to avoid conflict
                                foreach ($bulkgateFieldJsons as $bulkgateFieldkey => $bulkgateFieldJson) {
                                    if ($bulkgateFieldkey == $bulkgateJsonkey) {
                                        $bulkgatearr = explode(',', $bulkgateFieldJson);

                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $bulkgatearr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . str_pad($formValueItem->label, 20, " ") . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' && $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $bulkgatearr)) {
                                                $val = (property_exists($formValueItem, 'value')) ? $formValueItem->value : null;
                                                $teltable .= "\n" . str_pad($formValueItem->label, 20, " ") . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $connection = new Connection($bulkgateJson['bulkgate_app_id'], $bulkgateJson['bulkgate_token']);
                        $sender = new Sender($connection);
                        $message = new Message($bulkgateJson['bulkgate_number'], $teltable);
                        $sender->send($message);
                    }
                }
            }
        }

        // nexmo integration
        $formnexmosetting = FormIntegrationSetting::where('key', 'nexmo_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formnexmosetting) {
            if ($formnexmosetting->json) {
                $nexmoFieldJsons = json_decode($formnexmosetting->field_json, true);
                $nexmoJsons = json_decode($formnexmosetting->json, true);
                foreach ($nexmoJsons as $nexmoJsonkey => $nexmoJson) {
                    if ($nexmoJson['nexmo_number'] && $nexmoJson['nexmo_key'] && $nexmoJson['nexmo_secret']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValueItem) { // Changed variable name
                                foreach ($nexmoFieldJsons as $nexmoFieldkey => $nexmoFieldJson) {
                                    if ($nexmoFieldkey == $nexmoJsonkey) {
                                        $nexmoarr = explode(',', $nexmoFieldJson);
                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $nexmoarr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formValueItem->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' &&  $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $nexmoarr)) {
                                                $val = (property_exists($formValueItem, 'value')) ? $formValueItem->value : null;
                                                $teltable .= "\n" . $formValueItem->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $response = Http::asForm()->post('https://rest.nexmo.com/sms/json/', [
                            'api_key' => $nexmoJson['nexmo_key'],
                            'api_secret' => $nexmoJson['nexmo_secret'],
                            'from' => $appName,
                            'text' => $teltable,
                            'to' => $nexmoJson['nexmo_number']
                        ]);
                    }
                }
            }
        }

        // fast2sms integration
        $formfast2smssetting = FormIntegrationSetting::where('key', 'fast2sms_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formfast2smssetting) {
            if ($formfast2smssetting->json) {
                $fast2smsFieldJsons = json_decode($formfast2smssetting->field_json, true);
                $fast2smsJsons = json_decode($formfast2smssetting->json, true);
                foreach ($fast2smsJsons as $fast2smsJsonkey => $fast2smsJson) {
                    if ($fast2smsJson['fast2sms_number'] && $fast2smsJson['fast2sms_api_key']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValueItem) { // Changed variable name
                                foreach ($fast2smsFieldJsons as $fast2smsFieldkey => $fast2smsFieldJson) {
                                    if ($fast2smsFieldkey == $fast2smsJsonkey) {
                                        $fast2smsarr = explode(',', $fast2smsFieldJson);
                                        if ($formValueItem->type == 'checkbox-group' || $formValueItem->type == 'radio-group' || $formValueItem->type == 'select') {
                                            if (in_array($formValueItem->name, $fast2smsarr)) {
                                                foreach ($formValueItem->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formValueItem->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValueItem->type != 'button' &&  $formValueItem->type != 'header' && $formValueItem->type != 'hidden' && $formValueItem->type != 'paragraph' && $formValueItem->type != 'video' && $formValueItem->type != 'selfie' && $formValueItem->type != 'break' && $formValueItem->type != 'location') {
                                            if (in_array($formValueItem->name, $fast2smsarr)) {
                                                $val = (property_exists($formValueItem, 'value')) ? $formValueItem->value : null;
                                                $teltable .= "\n" . $formValueItem->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $fields = array(
                            "message" => $teltable,
                            "language" => "english",
                            "route" => "q",
                            "numbers" => $fast2smsJson['fast2sms_number'],
                        );

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($fields),
                            CURLOPT_HTTPHEADER => array(
                                "authorization: " . $fast2smsJson['fast2sms_api_key'],
                                "accept: */*",
                                "cache-control: no-cache",
                                "content-type: application/json"
                            ),
                        ));

                        $response = curl_exec($curl);
                    }
                }
            }
        }

        // vonage integration
        $formvonagesetting = FormIntegrationSetting::where('key', 'vonage_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formvonagesetting) {
            if ($formvonagesetting->json) {
                $vonageFieldJsons = json_decode($formvonagesetting->field_json, true);
                $vonageJsons = json_decode($formvonagesetting->json, true);
                foreach ($vonageJsons as $vonageJsonkey => $vonageJson) {
                    if ($vonageJson['vonage_number'] && $vonageJson['vonage_key'] && $vonageJson['vonage_secret']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValue) {
                                foreach ($vonageFieldJsons as $vonageFieldkey => $vonageFieldJson) {
                                    if ($vonageFieldkey == $vonageJsonkey) {
                                        $vonagearr = explode(',', $vonageFieldJson);
                                        if ($formValue->type == 'checkbox-group' || $formValue->type == 'radio-group' || $formValue->type == 'select') {
                                            if (in_array($formValue->name, $vonagearr)) {
                                                foreach ($formValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formValue->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValue->type != 'button' &&  $formValue->type != 'header' && $formValue->type != 'hidden' && $formValue->type != 'paragraph' && $formValue->type != 'video' && $formValue->type != 'selfie' && $formValue->type != 'break' && $formValue->type != 'location') {
                                            if (in_array($formValue->name, $vonagearr)) {
                                                $val = (property_exists($formValue, 'value')) ? $formValue->value : null;
                                                $teltable .= "\n" . $formValue->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $basic  = new  Basic($vonageJson['vonage_key'], $vonageJson['vonage_secret']);
                        $client = new VonageClient($basic);
                        $response = $client->sms()->send(
                            new SMS($vonageJson['vonage_number'], "Vonage APIs", $teltable)
                        );
                    }
                }
            }
        }

        //sendgrid integration
        $formsendgridsetting = FormIntegrationSetting::where('key', 'sendgrid_integration')->where('form_id', $form->id)->where('status', 1)->first();
        $formValues = [];
        if ($formsendgridsetting && $formsendgridsetting->json) {
            // Decode JSON settings
            $sendgridFieldJsons = json_decode($formsendgridsetting->field_json, true);
            $sendgridJsons = json_decode($formsendgridsetting->json, true);

            foreach ($sendgridJsons as $sendgridJsonkey => $sendgridJson) {
                if ($sendgridJson['sendgrid_email'] && $sendgridJson['sendgrid_host'] && $sendgridJson['sendgrid_port'] && $sendgridJson['sendgrid_username'] && $sendgridJson['sendgrid_password'] && $sendgridJson['sendgrid_encryption'] && $sendgridJson['sendgrid_from_address'] && $sendgridJson['sendgrid_from_name']) {
                    // Initialize form values array
                    $formValues = [];

                    // Iterate through form value JSONs
                    $formValueJsons = json_decode($formValue->json);
                    foreach ($formValueJsons as $formValueJsonkgrid => $formValueJson) {
                        foreach ($formValueJson as $formValueJsonk1grid => $formValue) {
                            foreach ($sendgridFieldJsons as $sendgridFieldkey => $sendgridFieldJson) {
                                // Check if the form field matches the sendgrid field
                                if ($sendgridFieldkey == $sendgridJsonkey) {
                                    $sendgridarr = explode(',', $sendgridFieldJson);
                                    if ($formValue->type == 'checkbox-group' || $formValue->type == 'radio-group' || $formValue->type == 'select') {
                                        // If the form field is a group, iterate through values
                                        if (in_array($formValue->name, $sendgridarr)) {
                                            foreach ($formValue->values as $Value) {
                                                if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                    $formValues[$formValueJsonkgrid][$formValueJsonk1grid] = $formValue;
                                                }
                                            }
                                        }
                                    } elseif ($formValue->type != 'button' && $formValue->type != 'file'  && $formValue->type != 'header' && $formValue->type != 'hidden' && $formValue->type != 'paragraph' && $formValue->type != 'SignaturePad' && $formValue->type != 'video' && $formValue->type != 'selfie' && $formValue->type != 'break' && $formValue->type != 'location') {
                                        // If the form field is not a button or specific types, add to form values
                                        if (in_array($formValue->name, $sendgridarr)) {
                                            $formValues[$formValueJsonkgrid][$formValueJsonk1grid] = $formValue;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Configure mail settings
                    config([
                        'mail.default'                 => 'sendgrid',
                        'mail.mailers.smtp.host'       => $sendgridJson['sendgrid_host'],
                        'mail.mailers.smtp.port'       => $sendgridJson['sendgrid_port'],
                        'mail.mailers.smtp.encryption' => $sendgridJson['sendgrid_encryption'],
                        'mail.mailers.smtp.username'   => $sendgridJson['sendgrid_username'],
                        'services.sendgrid.api_key'    => $sendgridJson['sendgrid_password'],
                        'mail.from.address'            => $sendgridJson['sendgrid_from_address'],
                        'mail.from.name'               => $sendgridJson['sendgrid_from_name'],
                    ]);

                    // Send email
                    try {
                        Mail::to($sendgridJson['sendgrid_email'])->send(new FormSubmitEmail($formValue, $formValues));
                    } catch (\Exception $e) {
                        // Handle mail sending errors
                    }
                }
            }
        }

        // twilio integration
        $formtwiliosetting = FormIntegrationSetting::where('key', 'twilio_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formtwiliosetting) {
            if ($formtwiliosetting->json) {
                $twilioFieldJsons = json_decode($formtwiliosetting->field_json, true);
                $twilioJsons = json_decode($formtwiliosetting->json, true);
                foreach ($twilioJsons as $twilioJsonkey => $twilioJson) {
                    if ($twilioJson['twilio_mobile_number'] && $twilioJson['twilio_sid'] && $twilioJson['twilio_auth_token'] && $twilioJson['twilio_number']) {
                        $teltable = '';
                        $teltable .= "\n[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValue) {
                                foreach ($twilioFieldJsons as $twilioFieldkey => $twilioFieldJson) {
                                    if ($twilioFieldkey == $twilioJsonkey) {
                                        $twilioarr = explode(',', $twilioFieldJson);
                                        if ($formValue->type == 'checkbox-group' || $formValue->type == 'radio-group' || $formValue->type == 'select') {
                                            if (in_array($formValue->name, $twilioarr)) {
                                                foreach ($formValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . str_pad($formValue->label, 20, " ") . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValue->type != 'button' &&  $formValue->type != 'header' && $formValue->type != 'hidden' && $formValue->type != 'paragraph' && $formValue->type != 'video' && $formValue->type != 'selfie' && $formValue->type != 'break' && $formValue->type != 'location') {
                                            if (in_array($formValue->name, $twilioarr)) {
                                                $val = (property_exists($formValue, 'value')) ? $formValue->value : null;
                                                $teltable .= "\n" . str_pad($formValue->label, 20, " ") . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $client = new Client($twilioJson['twilio_sid'], $twilioJson['twilio_auth_token']);
                            $client->messages->create(
                                '+' . $twilioJson['twilio_mobile_number'],
                                [
                                    'from' => $twilioJson['twilio_number'],
                                    'body' => $teltable
                                ]
                            );
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }

        // textlocal integration
        $formtextlocalsetting = FormIntegrationSetting::where('key', 'textlocal_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formtextlocalsetting) {
            if ($formtextlocalsetting->json) {
                $textlocalFieldJsons = json_decode($formtextlocalsetting->field_json, true);
                $textlocalJsons = json_decode($formtextlocalsetting->json, true);
                foreach ($textlocalJsons as $textlocalJsonkey => $textlocalJson) {
                    if ($textlocalJson['textlocal_number'] && $textlocalJson['textlocal_api_key']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formValue) {
                                foreach ($textlocalFieldJsons as $textlocalFieldkey => $textlocalFieldJson) {
                                    if ($textlocalFieldkey == $textlocalJsonkey) {
                                        $textlocalarr = explode(',', $textlocalFieldJson);
                                        if ($formValue->type == 'checkbox-group' || $formValue->type == 'radio-group' || $formValue->type == 'select') {
                                            if (in_array($formValue->name, $textlocalarr)) {
                                                foreach ($formValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . str_pad($formValue->label, 20, " ") . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formValue->type != 'button' &&  $formValue->type != 'header' && $formValue->type != 'hidden' && $formValue->type != 'paragraph' && $formValue->type != 'video' && $formValue->type != 'selfie' && $formValue->type != 'break' && $formValue->type != 'location') {
                                            if (in_array($formValue->name, $textlocalarr)) {
                                                $val = (property_exists($formValue, 'value')) ? $formValue->value : null;
                                                $teltable .= "\n" . str_pad($formValue->label, 20, " ") . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $response = Http::asForm()->post('https://api.textlocal.in/send/', [
                                'form_params' => [
                                    'apikey' => $textlocalJson['textlocal_api_key'],
                                    'sender' => 'Prime Builder',
                                    'numbers' => $textlocalJson['textlocal_number'],
                                    'message' => $teltable,
                                ],
                            ]);
                            $responseData = $response->json();
                        } catch (\Exception $e) {
                        }
                    }
                }
            }
        }

        // messente integration
        $formmessentesetting = FormIntegrationSetting::where('key', 'messente_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formmessentesetting) {
            if ($formmessentesetting->json) {
                $messenteFieldJsons = json_decode($formmessentesetting->field_json, true);
                $messenteJsons = json_decode($formmessentesetting->json, true);
                foreach ($messenteJsons as $messenteJsonkey => $messenteJson) {
                    if ($messenteJson['messente_number'] && $messenteJson['messente_api_username'] && $messenteJson['messente_api_password'] && $messenteJson['messente_sender']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formItemKey => $formItemValue) {
                                foreach ($messenteFieldJsons as $messenteFieldkey => $messenteFieldJson) {
                                    if ($messenteFieldkey == $messenteJsonkey) {
                                        $messentearr = explode(',', $messenteFieldJson);
                                        if ($formItemValue->type == 'checkbox-group' || $formItemValue->type == 'radio-group' || $formItemValue->type == 'select') {
                                            if (in_array($formItemValue->name, $messentearr)) {
                                                foreach ($formItemValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formItemValue->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formItemValue->type != 'button' &&  $formItemValue->type != 'header' && $formItemValue->type != 'hidden' && $formItemValue->type != 'paragraph' && $formItemValue->type != 'video' && $formItemValue->type != 'selfie' && $formItemValue->type != 'break' && $formItemValue->type != 'location') {
                                            if (in_array($formItemValue->name, $messentearr)) {
                                                $val = (property_exists($formItemValue, 'value')) ? $formItemValue->value : null;
                                                $teltable .= "\n" . $formItemValue->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $messagePayload = [
                                'to' => '+' . $messenteJson['messente_number'],
                                'messages' => [
                                    [
                                        'channel' => 'sms',
                                        'sender' => $messenteJson['messente_sender'],
                                        'text' => $teltable,
                                    ],
                                ],
                            ];
                            $response = Http::withBasicAuth($messenteJson['messente_api_username'], $messenteJson['messente_api_password'])
                                ->withHeaders(['Content-Type' => 'application/json'])
                                ->post('https://api.messente.com/v1/omnimessage', $messagePayload);

                            $responseData = $response->json();
                        } catch (\Exception $e) {
                            // Handle exceptions if needed
                        }
                    }
                }
            }
        }

        // smsgateway integration
        $formsmsgatewaysetting = FormIntegrationSetting::where('key', 'smsgateway_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formsmsgatewaysetting) {
            if ($formsmsgatewaysetting->json) {
                $smsgatewayFieldJsons = json_decode($formsmsgatewaysetting->field_json, true);
                $smsgatewayJsons = json_decode($formsmsgatewaysetting->json, true);
                foreach ($smsgatewayJsons as $smsgatewayJsonkey => $smsgatewayJson) {
                    if ($smsgatewayJson['smsgateway_number'] && $smsgatewayJson['smsgateway_api_key'] && $smsgatewayJson['smsgateway_user_id'] && $smsgatewayJson['smsgateway_user_password'] && $smsgatewayJson['smsgateway_sender_id']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formItemKey => $formItemValue) {
                                foreach ($smsgatewayFieldJsons as $smsgatewayFieldkey => $smsgatewayFieldJson) {
                                    if ($smsgatewayFieldkey == $smsgatewayJsonkey) {
                                        $smsgatewayarr = explode(',', $smsgatewayFieldJson);
                                        if ($formItemValue->type == 'checkbox-group' || $formItemValue->type == 'radio-group' || $formItemValue->type == 'select') {
                                            if (in_array($formItemValue->name, $smsgatewayarr)) {
                                                foreach ($formItemValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formItemValue->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formItemValue->type != 'button' &&  $formItemValue->type != 'header' && $formItemValue->type != 'hidden' && $formItemValue->type != 'paragraph' && $formItemValue->type != 'video' && $formItemValue->type != 'selfie' && $formItemValue->type != 'break' && $formItemValue->type != 'location') {
                                            if (in_array($formItemValue->name, $smsgatewayarr)) {
                                                $val = (property_exists($formItemValue, 'value')) ? $formItemValue->value : null;
                                                $teltable .= "\n" . $formItemValue->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $response = Http::withHeaders([
                                'apikey' => $smsgatewayJson['smsgateway_api_key'],
                                'cache-control' => 'no-cache',
                                'content-type' => 'application/x-www-form-urlencoded',
                            ])->post('https://www.smsgateway.center/SMSApi/rest/send', [
                                'userId' => $smsgatewayJson['smsgateway_user_id'],
                                'password' => $smsgatewayJson['smsgateway_user_password'],
                                'senderId' => $smsgatewayJson['smsgateway_sender_id'],
                                'sendMethod' => 'simpleMsg',
                                'msgType' => 'text',
                                'mobile' => $smsgatewayJson['smsgateway_number'],
                                'msg' => $teltable,
                                'duplicateCheck' => 'true',
                                'format' => 'json',
                            ]);
                        } catch (\Exception $e) {
                            // Handle exceptions if needed
                        }
                    }
                }
            }
        }


        // clicktell integration
        $formclicktellsetting = FormIntegrationSetting::where('key', 'clicktell_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formclicktellsetting) {
            if ($formclicktellsetting->json) {
                $clicktellFieldJsons = json_decode($formclicktellsetting->field_json, true);
                $clicktellJsons = json_decode($formclicktellsetting->json, true);
                foreach ($clicktellJsons as $clicktellJsonkey => $clicktellJson) {
                    if ($clicktellJson['clicktell_number'] && $clicktellJson['clicktell_api_key']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formItemKey => $formItemValue) {
                                foreach ($clicktellFieldJsons as $clicktellFieldkey => $clicktellFieldJson) {
                                    if ($clicktellFieldkey == $clicktellJsonkey) {
                                        $clicktellarr = explode(',', $clicktellFieldJson);
                                        if ($formItemValue->type == 'checkbox-group' || $formItemValue->type == 'radio-group' || $formItemValue->type == 'select') {
                                            if (in_array($formItemValue->name, $clicktellarr)) {
                                                foreach ($formItemValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formItemValue->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formItemValue->type != 'button' &&  $formItemValue->type != 'header' && $formItemValue->type != 'hidden' && $formItemValue->type != 'paragraph' && $formItemValue->type != 'video' && $formItemValue->type != 'selfie' && $formItemValue->type != 'break' && $formItemValue->type != 'location') {
                                            if (in_array($formItemValue->name, $clicktellarr)) {
                                                $val = (property_exists($formItemValue, 'value')) ? $formItemValue->value : null;
                                                $teltable .= "\n" . $formItemValue->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $headers = [
                                "Content-Type" => "application/json",
                                "Accept" => "application/json",
                                "Authorization" => $clicktellJson['clicktell_api_key'],
                            ];
                            $data = [
                                "messages" => [
                                    [
                                        "channel" => "sms",
                                        "to" => $clicktellJson['clicktell_number'],
                                        "content" => $teltable,
                                    ],
                                ],
                            ];
                            $response = Http::withHeaders($headers)->post('https://platform.clickatell.com/v1/message', $data);
                        } catch (\Exception $e) {
                            // Handle exceptions if needed
                        }
                    }
                }
            }
        }

        // clockwork integration
        $formclockworksetting = FormIntegrationSetting::where('key', 'clockwork_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($formclockworksetting) {
            if ($formclockworksetting->json) {
                $clockworkFieldJsons = json_decode($formclockworksetting->field_json, true);
                $clockworkJsons = json_decode($formclockworksetting->json, true);
                foreach ($clockworkJsons as $clockworkJsonkey => $clockworkJson) {
                    if ($clockworkJson['clockwork_number'] && $clockworkJson['clockwork_api_token']) {
                        $teltable = '';
                        $teltable .= "[$appName]\n\n$formTitle\n\n";
                        $formValueJsons = json_decode($formValue->json);
                        foreach ($formValueJsons as $formValueJson) {
                            foreach ($formValueJson as $formItemKey => $formItemValue) {
                                foreach ($clockworkFieldJsons as $clockworkFieldkey => $clockworkFieldJson) {
                                    if ($clockworkFieldkey == $clockworkJsonkey) {
                                        $clockworkarr = explode(',', $clockworkFieldJson);
                                        if ($formItemValue->type == 'checkbox-group' || $formItemValue->type == 'radio-group' || $formItemValue->type == 'select') {
                                            if (in_array($formItemValue->name, $clockworkarr)) {
                                                foreach ($formItemValue->values as $Value) {
                                                    if (property_exists($Value, 'selected') && $Value->selected == 1) {
                                                        $teltable .= "\n" . $formItemValue->label . ": " . $Value->label;
                                                    }
                                                }
                                            }
                                        } elseif ($formItemValue->type != 'button' &&  $formItemValue->type != 'header' && $formItemValue->type != 'hidden' && $formItemValue->type != 'paragraph' && $formItemValue->type != 'video' && $formItemValue->type != 'selfie' && $formItemValue->type != 'break' && $formItemValue->type != 'location') {
                                            if (in_array($formItemValue->name, $clockworkarr)) {
                                                $val = (property_exists($formItemValue, 'value')) ? $formItemValue->value : null;
                                                $teltable .= "\n" . $formItemValue->label . ": " . $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        try {
                            $clockwork = new Clockwork($clockworkJson['clockwork_api_token']);

                            $result = $clockwork->send([
                                'to' => $clockworkJson['clockwork_number'],
                                'message' => $teltable
                            ]);
                        } catch (\Exception $e) {
                            // Handle exceptions if needed
                        }
                    }
                }
            }
        }


        // salesforce integartion
        $salesforcesetting = FormIntegrationSetting::where('key', 'salesforce_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($salesforcesetting) {
            if ($salesforcesetting->json) {

                $formValueJsons = json_decode($formValue->json);

                $sfData = [];
                foreach ($formValueJsons[0] as $item) {
                    switch ($item->name) {
                        case 'firstName':
                            $sfData['FirstName'] = $item->value;
                            break;
                        case 'lastName':
                            $sfData['LastName'] = $item->value;
                            break;
                        case 'Status':
                            foreach ($item->values as $value) {
                                if (isset($value->selected) && $value->selected == 1) {
                                    $sfData['Status'] = $value->value;
                                    break;
                                }
                            }
                            break;
                        case 'Company':
                            $sfData['Company'] = $item->value;
                            break;
                        default:
                            break;
                    }
                }

                if (isset($sfData)) {
                    Forrest::sobjects('Lead', [
                        'method' => 'post',
                        'body' => $sfData,
                    ]);
                } else {
                    return response()->json(['failed' => false, 'message' => __('Please Enter Valid Details')], 200);
                }
            }
        }

        // hubspot integartion
        $hubspotsetting = FormIntegrationSetting::where('key', 'hubspot_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($hubspotsetting) {
            if ($hubspotsetting->json) {
                $client             = new GuzzleHttpClient();
                $contactInput       = new SimplePublicObjectInput();
                $formValueJsons     = json_decode($formValue->json);

                $properties = [];
                foreach ($formValueJsons[0] as $item) {
                    switch ($item->name) {
                        case 'lastname':
                            $properties['lastname'] = $item->value;
                            break;
                        case 'firstname':
                            $properties['firstname'] = $item->value;
                            break;
                        case 'email':
                            $properties['email'] = $item->value;
                            break;
                        default:
                            break;
                    }
                }

                $contactInput->setProperties($properties);

                request()->validate([
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'email' => 'required|email',
                ]);
                $hubspotjson = json_decode($hubspotsetting->json);

                foreach ($hubspotjson as $key => $value) {
                    $hubspot = Factory::createWithAccessToken($value->hubspot_access_token, $client);
                    if (!empty($contactInput['properties']['firstname']) && !empty($contactInput['properties']['email'])) {
                        $hubspot->crm()->contacts()->basicApi()->create($contactInput);
                    } else {
                        return response()->json(['error' => false, 'message' => __('Hubspot contact not created')], 500);
                    }
                }
            }
        }

        // pipedrive setting
        $pipedrivesetting = FormIntegrationSetting::where('key', 'pipedrive_integration')->where('form_id', $form->id)->where('status', 1)->first();
        if ($pipedrivesetting) {
            if ($pipedrivesetting->json) {

                $pipedriverjson         = json_decode($pipedrivesetting->json);
                $formValueJsons         = json_decode($formValue->json);

                foreach ($pipedriverjson as $pipedriverKey => $pipedriverValue) {
                    $person = '';
                    $activity = '';
                    foreach ($pipedriverjson[$pipedriverKey]->pipedrive_type as $type) {
                        if ($type == 'person') {
                            $person = $type;
                        }
                        if ($type == 'activity') {
                            $activity = $type;
                        }
                    }

                    $config                 = new Configuration();
                    $config->setApiKey('api_token', $pipedriverjson[$pipedriverKey]->pipedrive_api_token);

                    if ($person == 'person') {
                        $apiInstance = new PersonsApi(new GuzzleHttpClient(['base_uri' => 'https://api.pipedrive.com']), $config);
                        try {
                            $personData = [];
                            foreach ($formValueJsons[0] as $item) {
                                switch ($item->name) {
                                    case 'name':
                                        $personData['name'] = $item->value;
                                        break;
                                    case 'phone':
                                        $personData['phone'] = $item->value;
                                        break;
                                    case 'email':
                                        $personData['email'] = $item->value;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            $result = $apiInstance->addPerson($personData);
                        } catch (Exception $e) {
                        }
                    }

                    if ($activity == 'activity') {
                        $apiInstance = new ActivitiesApi(new GuzzleHttpClient(['base_uri' => 'https://api.pipedrive.com']), $config);
                        try {
                            $activityData = [];
                            foreach ($formValueJsons[0] as $item) {
                                switch ($item->name) {
                                    case 'email':
                                        $activityData['subject'] = $item->value;
                                        break;
                                    case 'due_date':
                                        $activityData['due_date'] = $item->value;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            $result = $apiInstance->addActivity($activityData);
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }
    }
}
