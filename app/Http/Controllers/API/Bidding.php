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
use Carbon\Carbon;

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
		
		 
		if($this->checkingBid($order->orderDate, $order->orderTime) == false){
			
			Tbl_order::where('id', $request->orderId)->update([
			'orderStatus'  => 'failed'
			]);
		
			$message 	= 'Kami tidak dapat menemukan mitra towing untuk anda, silahkan lakukan order kembali';
			return $this->sendResponseError($message, '',203);
		}
		
        $bidding		= Tbl_bidding::whereRaw('orderId ='. $request->input('orderId'))->get();
		 
		if((is_null($bidding)) OR ($bidding->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
		
		$result = array();
		foreach($bidding as $key=>$val){
			$result[$key] = $val;
			$result[$key]['url_img'] = url('rontend/img/towing-3.jpg');
			$result[$key]['mitra'] = Tbl_user_mitra::select('namaUsaha', 'alamatUsaha')->find($val->mitraId);
		};
								
		return $this->sendResponseOk($result);
	}
	
	private function checkingBid($orderDate, $orderTime){
		
		$dateOrder = $orderDate;
        $timeOrder = $orderTime;
		
		$orderTime =  Carbon::parse($dateOrder.' '.$timeOrder);
		$now =  Carbon::now();
		
		$orderExpired = Carbon::parse($dateOrder.' '.$timeOrder)->addMinutes(5);
		
		$expireMin = $orderExpired->diff($orderTime)->format('%H:%I:%S');
		
		$diffInMinutes = $now->diffInMinutes($orderTime);
		
		if($diffInMinutes > 5){
			return false;
		}else{
			return true;
		}
		
	} 

}