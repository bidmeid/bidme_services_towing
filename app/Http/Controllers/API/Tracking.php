<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_user_driver;
use App\Models\Tbl_order;
use App\Models\Tbl_user_mitra;

use App\Models\Tbl_tracking;
use App\Models\Tbl_invoice;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class Tracking extends Controller
{

	
	public function index(Request $request){
		
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		
		
		$order = Tbl_order::where('customerId', Auth::user()->id)->whereRaw('orderStatus <> "done"')->find($request->orderId);
		if($order){
		$result = Tbl_tracking::where('orderId', $order->id)->first();
		
		$invoice = Tbl_invoice::where('orderId', $order->id)->first();
		
		
		
		if(empty($result)){
			$message 	= 'Tracking kendaraan anda belum tersedia';
			return $this->sendResponseError($message, '',202);
		}
		
			 
			$result->latLongAsal = $order->latLongAsal;
			$result->latLongTujuan = $order->latLongTujuan;
			$result->latLongDriver = $result->latitude.','.$result->longtitude;
			
			$result->driver = Tbl_user_driver::find($result->driverId);
			$result->mitra = Tbl_user_mitra::select('id', 'namaUsaha')->find($invoice->mitraId);
			
		
		return $this->sendResponseOk($result);
		}else{
			$message 	= 'Order tidak ditemukan';
			return $this->sendResponseError($message, '',202);
		}
	}
	
}