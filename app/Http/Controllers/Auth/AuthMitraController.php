<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tbl_user_mitra as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\Api as Controller;

class AuthMitraController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'no_telp'   => 'required|min:11',
            'email'     => 'required|string|max:255|unique:tbl_user_mitra,email',
            'password'  => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());
        }
		if(isset($request->avatar)){
			$avatar = $request->avatar;
		}else{
			$avatar = NULL;
		};
        $user = User::create([
            'name'      => $request->name,
            'no_telp'   => $request->no_telp,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'avatar'  	=> $avatar
        ]);
		if(isset($request->provider_id)){
			$user->SosialAccountMitra()->create([
                'provider_id'   => $request->provider_id,
                'provider_name' => $request->provider_name,
            ]);
		}
		
        $data['access_token'] = $user->createToken('auth_token', ['mitra'])->plainTextToken;
        return $this->sendResponseCreate($data);
    }

    public function sigin(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);
        if (!Auth::guard('mitra')->attempt($credentials)) {
            return response()->json(['message' => 'Login Faileds!'], 401);
        }
        $user = User::where('email', $request->email)->first();
		if($user->banned == 0 ){
			return response()->json(['message' => 'Akun anda belum diaktifkan, hubungi kami di support@bidme.id terkait masalah ini.'], 203);
		}
        $token = $user->createToken('auth_token', ['mitra'])->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }
	
	public function forgot_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = sha1(rand());

        

        if (!empty($user)) {
            $data = [
				'email' => $user->email,
				'token' => $token,
				'to_url' => 'http://mitra.bidme.id/password-reset',
			];
			if($user->banned == 0 ){
				return response()->json(['message' => 'Akun anda belum diaktifkan, hubungi kami di support@bidme.id terkait masalah ini.'], 203);
			}
			if(Mail::to($user->email)->send(new ResetPasswordMail($data))){
			$user->update([
                'token_reset'    => $token
            ]);	
            return $this->sendResponseCustom('Kami telah mengirimkan link untuk reset password ke email Anda. Cek folder inbox atau spam untuk menemukannya.', false);
			
			}
        }
        return $this->sendResponseError('Upps. Email tidak di temukan!', null);
    }

    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required|confirmed|min:6',
            'token'     => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->token_reset !== $request->token) {
                return $this->sendResponseError('Upps token reset password salah!');
            } else {
                $user->update([
                    'password'  => Hash::make($request->password),
                    'token_reset'    => sha1(rand()),
                ]);
                return $this->sendResponseCustom('Password berhasil di ubah', true);
            }
        }
        return $this->sendResponseError('Upps. Email tidak di temukan!');
    }

    public function destroy(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
