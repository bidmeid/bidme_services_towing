<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_postCode;
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
		
		$check = Tbl_bidding::where('mitraId', Auth::user()->id)->where('orderId', $request->orderId)->first();
		
		if(!empty($check)){
			$message 	= 'Anda telah melakukan biding untuk order ini';
			return $this->sendResponseError($message, null, 202);
		}
		
		$input = Tbl_bidding::create([
			'orderId' => $request->orderId,
			'mitraId' => Auth::user()->id,
			'bidding' => $request->bidding,
			'pickupTime'  => $request->pickupTime,
			'bidStatus'  => 'open',
			
		]);
		
		$order = Tbl_order::where('id', $request->orderId)->update([
			'bidId' => $input->id,

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
		 
		$biding = Tbl_bidding::where('mitraId', Auth::user()->id)->where('bidStatus', $request->bidStatus)->get();
	    $result = array();
		foreach($biding as $key=>$val){
			$order			= Tbl_order::with('Tbl_customer')->find($val->orderId);
			
			$rute = Tbl_rute_pricelist::find($order->ruteId);
			
			$result[$key] 	= $val;
			$result[$key]['order'] = $order;
			if($rute){
			$result[$key]['regionAsal'] = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
			$result[$key]['regionTujuan'] = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}
			
		};
		
		if(empty($result)){
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