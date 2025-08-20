<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public $countries;

    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth', 'web']);
        $path = public_path() . "/assets/json/typehead/countries.json";
        $countriesJson = File::get($path);
        $this->countries = json_decode($countriesJson, true);
    }

    public function index()
    {
        if (!setting('2fa')) {
            $user       = auth()->user();
            $role       = $user->roles->first();
            $countries  = $this->countries;
            $google2faUrl  = "";
            foreach ($countries as  $countrie) {
                $allcountries[$countrie] = $countrie;
            }
            return view('profile.index', [
                'user' => $user,
                'role' => $role,
                'countries' => $allcountries,
                'google2fa_url' => $google2faUrl,
            ]);
        }
        return $this->activeTwoFactor();
    }

    private function activeTwoFactor()
    {
        $user           = Auth::user();
        $google2faUrl  = "";
        $secretKey     = "";
        if ($user->loginSecurity()->exists()) {
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2faUrl = $google2fa->getQRCodeInline(
                @setting('app_name'),
                $user->name,
                $user->loginSecurity->google2fa_secret
            );
            $secretKey = $user->loginSecurity->google2fa_secret;
        }
        $user       = auth()->user();
        $role       = $user->roles->first();
        $countries  = $this->countries;
        foreach ($countries as  $countrie) {
            $allcountries[$countrie] = $countrie;
        }

        $data = array(
            'user'          => $user,
            'secret'        => $secretKey,
            'google2fa_url' => $google2faUrl,
            'countries'     => $countries
        );
        return view('profile.index', [
            'user'          => $user,
            'role'          => $role,
            'secret'        => $secretKey,
            'google2fa_url' => $google2faUrl,
            'countries'     => $allcountries
        ]);
    }

    public function BasicInfoUpdate(Request $request)
    {
        $user = User::find(auth()->id());
        request()->validate([
            'fullname' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/|max:191',
            'address' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/|max:191',
            'country' => 'required|string|max:191',
            'phone' => 'required|max:191',
        ]);
        $user->name     = $request->fullname;
        $user->address  = $request->address;
        $user->country  = $request->country;
        $user->phone    = $request->phone;
        if ($request->hasFile('avatarCrop')) {
            $avatar = $request->file('avatarCrop');
            $imageName = time() . '.' . 'png';
            $imagePath = "avatar/" . $imageName;
            Storage::disk()->put($imagePath, file_get_contents($avatar));
            $user->avatar = $imagePath;
        }
        $user->save();
        return redirect()->back()->with('success',  __('Account details updated successfully.'));
    }

    public function updateAvatar(Request $request)
    {
        $disk = Storage::disk();
        $user = Auth::user();
        request()->validate([
            'avatar'    => 'required',
        ]);
        $image     = $request->avatar;
        $image     = str_replace('data:image/png;base64,', '', $image);
        $image     = str_replace(' ', '+', $image);
        $imagename = time() . '.' . 'png';
        $imagepath = "avatar/" . $imagename;
        $disk->put($imagepath, base64_decode($image));
        $user->avatar = $imagepath;
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => __('Avatar Updated Successfully.')
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => __('Failed to update avatar.')
        ]);
    }

    public function LoginDetails(Request $request)
    {
        $user = User::find(auth()->id());
        request()->validate([
            'email'                 => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password'              => 'required|min:5|max:191|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        $user->email = $request->email;
        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->back()->with('success',  __('Login details updated successfully.'));
    }

    public function profileStatus()
    {
        $user = User::find(Auth::user()->id);
        $user->active_status = 0;
        $user->save();
        auth()->logout();
        return redirect()->route('home');
    }

    public function verify()
    {
        return redirect(URL()->previous());
    }
}
