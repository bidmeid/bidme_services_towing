<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use App\Models\Tbl_invoice;
use App\Models\Tbl_bidding;
use \App\Models\Tbl_unit_towing;
use \App\Models\Tbl_user_driver;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraNotif extends Controller
{


	public function index(){
		
		$now = date("Y-m-d");
		$result['total_order_today']		= Tbl_order::whereRaw('orderDate >= '. $now)->count();
		$result['total_bidding_aktif']		= Tbl_bidding::where('mitraId', Auth::user()->id)->where('bidStatus', 'open')->count();
		$result['total_order_berlangsung']	=  Tbl_invoice::where('mitraId', Auth::user()->id)->where('paymentStatus', 'settlement')->count();
		$result['total_driver']				=  Tbl_unit_towing::where('mitraId', Auth::user()->id)->count();
		$result['total_towing']				=  Tbl_user_driver::where('mitra_id', Auth::user()->id)->count();
		
								
		return $this->sendResponseOk($result);
	}

	

}