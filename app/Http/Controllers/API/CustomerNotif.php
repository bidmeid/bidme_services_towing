<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;

use Illuminate\Http\Request;

class CustomerNotif extends Controller
{


	public function index(){
		
		$result['riwayat_order']		= Tbl_order::where('customerId', Auth::user()->id)->count();
		$result['order_berlangsung']		= Tbl_order::where('customerId', Auth::user()->id)->where('orderStatus', 'process')->count();
		
								
		return $this->sendResponseOk($result);
	}

	

}