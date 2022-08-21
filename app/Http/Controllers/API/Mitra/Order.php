<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use App\Models\Tbl_customer;
use App\Models\Tbl_rute;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Order extends Controller
{


	public function index(){
		
		$order = Tbl_order::where('orderStatus', 'proccess')->get();
		
		if((is_null($order)) OR ($order->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	     
		$result = array();
		foreach($order as $key=>$val){
			$result[$key] = $val;
			$result[$key]['customer'] = Tbl_customer::find($val->customerId);
			$result[$key]['rute'] = Tbl_rute::find($val->ruteId);
		};
		
		return $this->sendResponseOk($result);
	}


}