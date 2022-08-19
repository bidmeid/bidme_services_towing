<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api as Controller;
use Illuminate\Http\Request;
use \App\Models\Tbl_user_mitra as M_Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UsersMitra extends Controller
{


	public function update_account(request $request){
		
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'region' => 'required',
            'namaUsaha' => 'required',
            'alamatUsaha' => 'required',
            'alamat' => 'required',
			'no_tlp' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = M_Users::where([
			['id', Auth::user()->id]
		])->first();

		if ($request->password == "") {
			$realPassword = $result->password;
		} else {
			$realPassword = Hash::make($request->password);
		}

		$input = M_Users::where('id', Auth::user()->id)->update([
			'name'   		=> $request->name,
			'region'   		=> $request->region,
			'alamat'   		=> $request->alamat,
			'alamatUsaha'   => $request->alamatUsaha,
			'namaUsaha'   	=> $request->namaUsaha,
			'no_tlp'   		=> $request->noTlp,
			'password'   	=> $realPassword,
		]);

		if($input){
			return $this->sendResponseCreate($input);
		}
	}

	
}