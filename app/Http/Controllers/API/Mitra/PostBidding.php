<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostBidding extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'bidding'  => 'required',
			'pickupTime'  => 'required',
			
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		
		$input = Tbl_bidding::create([
			'orderId' => $request->orderId,
			'mitraId' => Auth::user()->id,
			'bidding' => $request->bidding,
			'pickupTime'  => $request->pickupTime,
			'bidStatus'  => 'open',
			
		]);
		
		return $this->sendResponseCreate($input);
	}

	public function myBidding(Request $request){
		$validator = Validator::make($request->all(), [
			'bidStatus'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		if ($request->bidStatus == ''){$bidStatus = 'IS NOT NULL'; }else{ $bidStatus = ' = '.$request->bidStatus; };
		
		$result = Tbl_bidding::where('mitraId', Auth::user()->id)->whereRaw('bidStatus '. $bidStatus)->find();
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	   
		
		return $this->sendResponseOk($result);

	}
	
	public function cancelBidding(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_bidding::where('orderId', $request->orderId)->find();
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}else{
			$input = Tbl_bidding::where('orderId', $request->orderId)->update([
			'bidStatus' => 'cancel', 
			]);
		}
	   
		
		return $this->sendResponseOk($result);

	}
}