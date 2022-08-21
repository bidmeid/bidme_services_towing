<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use App\Models\Tbl_customer;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_kondisi_kendaraan;
use App\Models\Tbl_jenis_kendaraan;
use App\Models\Tbl_type_kendaraan;
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
			$result[$key]['rute'] = Tbl_rute_pricelist::find($val->ruteId);
			$result[$key]['kondisiKendaraan'] = Tbl_kondisi_kendaraan::find($val->kondisiKendaraanId);
			$result[$key]['JenisKendaraan'] = Tbl_jenis_kendaraan::find($val->JenisKendaraanId);
			$result[$key]['typeKendaraan'] = Tbl_type_kendaraan::find($val->typeKendaraanId);
		};
		
		return $this->sendResponseOk($result);
	}
	
	public function getOrderById(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		$result = Tbl_order::where('orderStatus', 'proccess')->find($request->orderId);
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$result->customer = Tbl_customer::find($result->customerId);
			$result->rute = Tbl_rute_pricelist::find($result->ruteId);
			$result->kondisiKendaraan = Tbl_kondisi_kendaraan::find($result->kondisiKendaraanId);
			$result->JenisKendaraan = Tbl_jenis_kendaraan::find($result->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($result->typeKendaraanId);
		
		return $this->sendResponseOk($result);

	}


}