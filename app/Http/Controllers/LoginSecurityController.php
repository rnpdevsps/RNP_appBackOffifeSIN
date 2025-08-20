<?php

namespace App\Http\Controllers;

use App\Models\LoginSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Countries\Package\Countries;

class LoginSecurityController extends Controller
{
    protected $countries;

    public function __construct()
    {
        $this->middleware(['auth']);
        $path = public_path() . "/assets/json/typehead/countries.json";
        $countriesJson = File::get($path);
        $this->countries = json_decode($countriesJson, true);
    }

    public function show2faForm(Request $request)
    {
        $user = Auth::user();
        $google2faUrl = "";
        $secretKey = "";
        if ($user->loginSecurity()->exists()) {
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2faUrl = $google2fa->getQRCodeInline(
                @setting('app_name'),
                $user->name,
                $user->loginSecurity->google2fa_secret
            );
            $secretKey = $user->loginSecurity->google2fa_secret;
        }
        $user = auth()->user();
        $role = $user->roles->first();
        $countries = $this->countries;
        $data = array(
            'user' => $user,
            'secret' => $secretKey,
            'google2fa_url' => $google2faUrl,
            'countries' => $countries
        );
        return view('profile.index', [
            'user' => $user,
            'role' => $role,
            'secret' => $secretKey,
            'google2fa_url' => $google2faUrl,
            'countries' => $countries
        ]);
    }

    public function generate2faSecret(Request $request)
    {
        $user = Auth::user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $loginSecurity = LoginSecurity::firstOrNew(array('user_id' => $user->id));
        $loginSecurity->user_id = $user->id;
        $loginSecurity->google2fa_enable = 0;
        $loginSecurity->google2fa_secret = $google2fa->generateSecretKey();
        $loginSecurity->save();
        return redirect()->route('profile.index')->with('success', __('Secret key is generated successfully.'));
    }

    public function enable2fa(Request $request)
    {
        $user = Auth::user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $secret = $request->input('secret');
        $valid = $google2fa->verifyKey($user->loginSecurity->google2fa_secret, $secret);
        if ($valid) {
            $user->loginSecurity->google2fa_enable = 1;
            $user->loginSecurity->save();
            return redirect('2fa')->with('success', __('2fa is enabled successfully.'));
        } else {
            return redirect('2fa')->with('failed', __('Invalid verification code, please try again.'));
        }
    }

    public function disable2fa(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return redirect()->back()->with("failed", __('Your password does not matches with your account password. please try again.'));
        }
        request()->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->loginSecurity->google2fa_enable = 0;
        $user->loginSecurity->save();
        return redirect('/2fa')->with('success', __('2fa is disabled.'));
    }
}
