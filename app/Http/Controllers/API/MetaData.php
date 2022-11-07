<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bank;


use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class MetaData extends Controller
{

	
	public function index(){
		
	
			$message 	= 'halaman tidak ditemukan';
			return $this->sendResponseError($message, '',202);
	
	}
	
	public function bank(){
		
		$result['bank']		= Tbl_bank::get();
		
								
		return $this->sendResponseOk($result);
	}
	
}