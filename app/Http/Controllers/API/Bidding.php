<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_user_mitra;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Bidding extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderID' => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$order =  Tbl_order::where('id', $request->input('orderID'))->first();
		
		//$dt = new DateTime($order->);
		
        $bidding		= Tbl_bidding::whereRaw('orderID ='. $request->input('orderID'))->get();
		$result = array();
		foreach($bidding as $key=>$val){
			$result[$key] = $val;
			$result[$key]['mitra'] = Tbl_user_mitra::select('namaUsaha', 'alamatUsaha')->first($val->mitraID);
		};
								
		return $this->sendResponseOk($result);
	}

}