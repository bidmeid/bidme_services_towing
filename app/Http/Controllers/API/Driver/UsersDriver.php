<?php

namespace App\Http\Controllers\Api\Driver;
use App\Http\Controllers\Api as Controller;
use Illuminate\Http\Request;
use \App\Models\Tbl_user_driver as M_Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UsersDriver extends Controller
{

	public function updateAccount(request $request){
		
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
			'no_telp' => 'required',
            
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
			'nameDriver'   		=> $request->name,
			'alamatDriver'   		=> $request->alamat,
			'noTelpDriver'   		=> $request->no_telp,
			'password'   			=> $realPassword,
		]);

		if($input){
			return $this->sendResponseCreate($input);
		}
	}
	
	
	
}