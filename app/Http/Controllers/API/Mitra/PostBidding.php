<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_postCode;
use App\Models\Tbl_customer;
use App\Models\Tbl_kondisi_kendaraan;
use App\Models\Tbl_jenis_kendaraan;
use App\Models\Tbl_type_kendaraan;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PostBidding extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'bidding'  => 'required',
			'pickupTime'  => 'required',
			
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$check = Tbl_bidding::where('mitraId', Auth::user()->id)->where('orderId', $request->orderId)->first();
		$order =  Tbl_order::where('id', $request->input('orderId'))->first();
		
		if($this->checkingBid($order->orderDate, $order->orderTime) == false){
			
			Tbl_order::where('id', $request->orderId)->update([
			'orderStatus'  => 'failed'
			]);
		
			$message 	= 'Kami tidak dapat menemukan mitra towing untuk anda, silahkan lakukan order kembali';
			return $this->sendResponseError($message, '',203);
		}
		
		if(!empty($check)){
			$message 	= 'Anda telah melakukan biding untuk order ini';
			return $this->sendResponseError($message, null, 202);
		}
		
		$input = Tbl_bidding::create([
			'orderId' => $request->orderId,
			'mitraId' => Auth::user()->id,
			'bidding' => $request->bidding,
			'pickupTime'  => $request->pickupTime,
			'bidStatus'  => 0,
			
		]);
		
		Tbl_order::where('id', $request->orderId)->update([
			'bidId' => $input->id,

		]);
		
		return $this->sendResponseCreate($input);
	}

	public function myBidding(Request $request){
		$validator = Validator::make($request->all(), [
			'bidStatus'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		 
		$biding = Tbl_bidding::where('mitraId', Auth::user()->id)->where('bidStatus', $request->bidStatus)->orderBy('id', 'DESC')->get();
	    $result = array();
		foreach($biding as $key=>$val){
			$order			= Tbl_order::with('Tbl_customer')->find($val->orderId);
			
			$rute = Tbl_rute_pricelist::find($order->ruteId);
			
			$result[$key] 	= $val;
			$result[$key]['order'] = $order;
			if($rute){
			$result[$key]['regionAsal'] = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
			$result[$key]['regionTujuan'] = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}else{
				$result[$key]['rute'] = 'Tidak Ditemukan';
				$result[$key]['regionAsal'] = ['distric' => substr($order->alamatAsal,0, 16).'..'];
				$result[$key]['regionTujuan']= ['distric' => substr($order->alamatTujuan,0, 16).'..'];
				 
			}
			
		};
		
		if(empty($result)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	   
		
		return $this->sendResponseOk($result);

	}
	
	public function getBidById(Request $request){
		$validator = Validator::make($request->all(), [
			'bidId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		$result = Tbl_bidding::where('mitraId', Auth::user()->id)->find($request->bidId);
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$order = Tbl_order::with('Tbl_customer')->find($result->orderId);
			$result->order = $order;
			$result->bidtime = getDateFormat($result->created_at);
			$result->customer = Tbl_customer::find($order->customerId);
			$result->kondisiKendaraan = Tbl_kondisi_kendaraan::find($order->kondisiKendaraanId);
			$result->jenisKendaraan = Tbl_jenis_kendaraan::find($order->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($order->typeKendaraanId);
			
		
		return $this->sendResponseOk($result);

	}
	
	public function cancelBidding(Request $request){
		$validator = Validator::make($request->all(), [
			'bidId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$bid = Tbl_bidding::where('id', $request->bidId)->first();
		$order = Tbl_order::find($bid->orderId);
		
		if($order->orderStatus == 'process'){
			$bid->update([
			'bidStatus' => 2, //cancel 
			]);
		
			return $this->sendResponseCreate(null);
			
		}else{
			$message 	= 'Anda tidak dapat membatalkan penawaran untuk pesanan ini !';
			return $this->sendResponseError($message, '',203);
		}
		

	}
	
	public function getDateFormat($value){
		$date = Carbon::parse($value);
		return $date->format('Y-m-d H:i');
	}
	
	
}