<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tbl_user_driver as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api as Controller;

class AuthDriverController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mitraId'   => 'required',
            'name'      => 'required|string|max:255',
            'alamat'   	=> 'required',
            'no_telp'   => 'required',
            'email'     => 'required|string|max:255|unique:tbl_user_driver,email',
            'password'  => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());
        }

        $user = User::create([
            'mitraId'      => $request->mitraId,
            'nameDriver'      => $request->name,
            'alamatDriver'   => $request->alamat,
            'noTlpDriver'   => $request->no_telp,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        $data['access_token'] = $user->createToken('auth_token', ['driver'])->plainTextToken;
        return $this->sendResponseCreate($data);
    }

    public function sigin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|max:255',
            'password'  => 'required|min:6'
        ]);
		
		if ($validator->fails()) {
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());
        }
		
		if(is_numeric($request->email)){
			$credentials = [
            'noTlpDriver'     => $request->email,
            'password'  => $request->password
			];
			if (!Auth::guard('driver')->attempt($credentials)) {
            return response()->json(['message' => 'Login Faileds!'], 401);
			}
			$user = User::where('noTlpDriver', $request->email)->first();
		}else{
			$credentials = [
            'email'     => $request->email,
            'password'  => $request->password
			];
			if (!Auth::guard('driver')->attempt($credentials)) {
            return response()->json(['message' => 'Login Faileds!'], 401);
			}
			$user = User::where('email', $request->email)->first();
		}
		
        
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Hi ' . $user->name, 'Wellcome back', 'access_token' => $token, 'token_type' => 'Bearer']);
    }

	public function forgot_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = sha1(rand());

        

        if (!empty($user)) {
			$data = [
				'email' => $user->email,
				'token' => $token,
				'to_url' => 'http://services.bidme.id/driver/password-reset',
			];
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
