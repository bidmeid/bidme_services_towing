<?php

namespace App\Http\Controllers\API\Mitra;
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
			'no_telp' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 202);       
		}
 

		$input = M_Users::where('id', Auth::user()->id)->update([
			'name'   		=> $request->name,
			'region'   		=> $request->region,
			'alamat'   		=> $request->alamat,
			'alamatUsaha'   => $request->alamatUsaha,
			'namaUsaha'   	=> $request->namaUsaha,
			'no_telp'   	=> $request->no_telp,
			'no_telp_2'   	=> $request->no_telp_2,
			'name_acc'   	=> $request->name_acc,
			'bank_acc'   	=> $request->bank_acc,
			'no_acc'   		=> $request->no_acc, 
		]);

		if($input){
			return $this->sendResponseCreate($input);
		}
	}
	
	public function update_password(request $request){
		
		$validator = Validator::make($request->all(), [
             
            'password' => 'required|confirmed',
           
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 202);       
		}
		
		$result = M_Users::where([['id', Auth::user()->id]])->first();
		if(empty($result)){
			$message 	= 'Your password is wrong';
			return $this->sendResponseError($message, '',202);
		}

		$input = M_Users::where('id', Auth::user()->id)->update([
			
			'password'   	=> $request->password,
		]);

		if($input){
			return $this->sendResponseCreate(null);
		}
	}
	
	public function setting_notifikasi(request $request){
		
		$validator = Validator::make($request->all(), [
             
            'region' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 202);       
		}
		
		$result = M_Users::where([['id', Auth::user()->id]])->first();
		if(empty($result)){
			$message 	= 'Your password is wrong';
			return $this->sendResponseError($message, '',202);
		}
		
		 
		if($result){
			return $this->sendResponseCreate(null);
		}
	}
	
	
	public function saveToken(Request $request)
    {
        
        $input = M_Users::where('id', $request->mitraId)->update(['device_token' => $request->token]);
        
		if($input){
			return $this->sendResponseCreate(null);
		}
    }

	
}