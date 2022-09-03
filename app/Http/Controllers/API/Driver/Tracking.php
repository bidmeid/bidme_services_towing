<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use App\Models\Tbl_tracking;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Tracking extends Controller
{


	public function index(){
		
		$result = Tbl_tracking::where('driverId', Auth::user()->id)->where('status', 'open')->first();
		
		if(empty($result)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message,null, 202);
		}
		
		$result->order = Tbl_order::find($result->orderId);
		
		return $this->sendResponseOk($result);
	}

	public function postLatLng(request $request){
		
		$validator = Validator::make($request->all(), [
            'orderId' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}

		$input = Tbl_tracking::where('driverId', Auth::user()->id)->where('orderId', $request->orderId)->update([
			'latitude'   		=> $request->latitude,
			'longtitude'   		=> $request->longtitude,
			//'status'   			=> 'running',
		]);

		if($input){
			return $this->sendResponseCreate($input);
		}
	}
	
	public function lastLatLng(request $request){
		
		$validator = Validator::make($request->all(), [
            'orderId' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}

		$result = Tbl_tracking::where('driverId', Auth::user()->id)->where('orderId', $request->orderId)->first();

		if($result){
			return  $this->sendResponseOk($result);
		}
	}

}