<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api as Controller;
use Illuminate\Http\Request;
use \App\Models\Tbl_user_driver as M_Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class Driver extends Controller
{


	public function index(Request $request){
		
		$render		= $request->input('render'); if ($render){$limit = 6; } else {$limit = 20;};	
		$order		= $request->input('order'); 
		$sort 		= $request->input('sort'); if ($sort == ''){$sort = 'ASC'; };
		$columns	= "id";

		$data 	= M_Users::
					->where('mitraId', Auth::user()->id)
					->orderBy($columns, $sort)
					->paginate($limit);

		if((!empty($data)) AND ($data->count() != 0)){
			$data->data = colection_pages::collection($data);
			$result = $data;
		}else{
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}
		return  $this->sendResponseOk($result);
	}
	
	public function updateDriver(request $request){
		
		$validator = Validator::make($request->all(), [
            'userId' => 'required',
            'name' => 'required',
            'alamat' => 'required',
			'no_telp' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = M_Users::where([
			['id', $request->userId]
		])->first();

		if ($request->password == "") {
			$realPassword = $result->password;
		} else {
			$realPassword = Hash::make($request->password);
		}

		$input = M_Users::where('id', $request->userId)->update([
			'nameDriver'   		=> $request->name,
			'alamatDriver'   		=> $request->alamat,
			'noTelpDriver'   		=> $request->no_telp,
			'password'   			=> $realPassword,
		]);

		if($input){
			return $this->sendResponseCreate($input);
		}
	}
	
	public function deleteDriver(){	
		$validator = Validator::make($request->all(), [
            'driverId' => 'required',            
		]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = M_Users::where('mitraId', Auth::user()->id)->find($request->driverId);
		
       
		$result->delete();
		return $this->sendResponseDelete(null);
		
	}

	
}