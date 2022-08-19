<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_user_mitra;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Bidding extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId' => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$order =  Tbl_order::where('id', $request->input('orderId'))->first();
		
		//$dt = new DateTime($order->);
		
        $bidding		= Tbl_bidding::whereRaw('orderId ='. $request->input('orderId'))->get();
		
		if((is_null($bidding)) OR ($bidding->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendError($message, 204);
		}
		
		$result = array();
		foreach($bidding as $key=>$val){
			$result[$key] = $val;
			$result[$key]['mitra'] = Tbl_user_mitra::select('namaUsaha', 'alamatUsaha')->first($val->mitraID);
		};
								
		return $this->sendResponseOk($result);
	}

}