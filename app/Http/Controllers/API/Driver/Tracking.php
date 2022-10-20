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
		$check = Tbl_tracking::where('driverId', Auth::user()->id)->where('orderId', $request->orderId)->first();
		if(empty($check)){
			$message 	= 'Your request couldn`t be done';
			return $this->sendResponseError($message, null, 202);
		};
		if($check->trackPoint == 1){
			$msg = 'Unit kendaraan sedang dalam pengiriman ke lokasi tujuan';
			 
		}else if($check->trackPoint == 2){
			$msg = 'Unit kendaraan anda telah sampai di lokasi tujuan';
		}else{
			$msg = 'Driver towing akan melakukan penjemputan ke lokasi anda';
		}
		
		$input = Tbl_tracking::where('driverId', Auth::user()->id)->where('orderId', $request->orderId)->update([
			'latitude'   		=> $request->latitude,
			'longtitude'   		=> $request->longtitude,
			'msg'   			=> $msg,
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
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 200);       
		}

		$result = Tbl_tracking::where('driverId', Auth::user()->id)->where('orderId', $request->orderId)->first();
		$order 	= Tbl_order::find($result->orderId);
		
		if($result->trackPoint == 0){
			$result->latLongTujuan = $order->latLongAsal;
			$result->latLongDriver = $result->latitude.','.$result->longtitude;
		}else{
			$result->latLongTujuan = $order->latLongTujuan;
			$result->latLongDriver = $result->latitude.','.$result->longtitude;	
		}
		
		if($result){
			return  $this->sendResponseOk($result);
		}
	}
	
	public function finishOrder(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'trackPoint'  => 'required'
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
		
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be done';
			return $this->sendResponseError($message, null, 202);
		}else{
			 
			Tbl_tracking::where('orderId', $request->orderId)->update([
			'status'  => 'close',
			'trackPoint'  => $request->trackPoint,
			'msg'  => $msg
			]);
		}
	   
		
		return $this->sendResponseCreate(null);

	}

}