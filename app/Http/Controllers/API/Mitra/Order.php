<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostOrder extends Controller
{


	public function index(){
		
		$result = Tbl_order::where('orderStatus', 'proccess')->get();
		
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',204);
		}
	   
		
		return $this->sendResponseOk($result);
	}


}