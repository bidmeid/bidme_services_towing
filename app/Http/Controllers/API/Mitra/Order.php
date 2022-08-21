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
		
		$result = Tbl_order::where('orderStatus', 'proccess')->get();
		
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	    $result->customer = Tbl_customer::find($result->customerId)
	    $result->rute = Tbl_rute::find($result->ruteId)
		
		return $this->sendResponseOk($result);
	}


}