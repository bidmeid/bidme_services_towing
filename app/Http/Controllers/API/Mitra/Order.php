<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use App\Models\Tbl_customer;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_kondisi_kendaraan;
use App\Models\Tbl_jenis_kendaraan;
use App\Models\Tbl_type_kendaraan;
use App\Models\Tbl_postCode;
use App\Models\Tbl_invoice;
use App\Models\Tbl_tracking;
use App\Models\Tbl_bidding;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Order extends Controller
{


	public function index(){
		
		$now = date("Y-m-d");
		$order = Tbl_order::with('Tbl_customer')->whereRaw('orderDate >= '. $now)->where('orderStatus', 'process')->orderBy('id', 'DESC')->get();
		
		if((empty($order)) OR ($order->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	     
		$result = array();
		foreach($order as $key=>$val){
			$rute = Tbl_rute_pricelist::find($val->ruteId);
			$result[$key] = $val;
			
			//$result[$key]['customer'] = Tbl_customer::find($val->customerId);
			if($rute){
			$result[$key]['rute'] = $rute;
			$result[$key]['regionAsal'] = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
			$result[$key]['regionTujuan'] = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}else{
				$result[$key]['rute'] = 'Tidak Ditemukan';
				$result[$key]['regionAsal'] = ['distric' => substr($val->alamatAsal, 0, 16).'..'];
				$result[$key]['regionTujuan']= ['distric' => substr($val->alamatTujuan, 0, 16).'..'];
			}
			
			/* $result[$key]['kondisiKendaraan'] = Tbl_kondisi_kendaraan::find($val->kondisiKendaraanId);
			$result[$key]['jenisKendaraan'] = Tbl_jenis_kendaraan::find($val->JenisKendaraanId);
			$result[$key]['typeKendaraan'] = Tbl_type_kendaraan::find($val->typeKendaraanId); */
			if($this->checkingExpired($val->orderDate, $val->orderTime) >= 25){
				$exp = 0;
			}else{
				$exp = 25 - $this->checkingExpired($val->orderDate, $val->orderTime);
			}
			$result[$key]['expired'] = $exp. ' menit'; 
			
		};
		
		return $this->sendResponseOk($result);
	}
	
	public function myOrder(){
		
		$order = Tbl_invoice::select(
					'tbl_invoice.id as invoice_id',
					'tbl_invoice.noInvoice',
					'tbl_invoice.orderId',
					'tbl_invoice.mitraId',
					'tbl_invoice.biddingId',
					'tbl_invoice.driverId',
					'tbl_invoice.billing',
					'tbl_invoice.paymentStatus',
					//'tbl_invoice.paymentToMitra',

					'tbl_order.id',
					'tbl_order.ruteId',
					'tbl_order.customerId',
					'tbl_order.alamatAsal',
					'tbl_order.alamatTujuan',
					'tbl_order.orderDate',
					'tbl_order.orderTime',
					'tbl_order.orderStatus',
					'tbl_order.orderCost')	
					->join('tbl_order', 'tbl_invoice.orderId', '=', 'tbl_order.id')
					->where('tbl_invoice.mitraId', Auth::user()->id)
					->where('tbl_invoice.paymentStatus', 'settlement')
					->where('tbl_order.orderStatus', 'settlement')
					->orderBy('id', 'DESC')->get();
		
		if((empty($order)) OR ($order->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	     
		$result = array();
		foreach($order as $key=>$val){
			
			//$order = Tbl_order::with('Tbl_customer')->find($val->orderId);
			$rute = Tbl_rute_pricelist::findOrFail($val->ruteId);
			$result[$key] = $val;
			
			$result[$key]['customer'] = Tbl_customer::find($val->customerId);
			if($rute){
			$result[$key]['rute'] = $rute;
			$result[$key]['regionAsal'] = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
			$result[$key]['regionTujuan'] = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}else{
				$result[$key]['rute'] = 'Tidak Ditemukan';
				$result[$key]['regionAsal'] = ['distric' => substr($val->alamatAsal, 0, 16).'..'];
				$result[$key]['regionTujuan']= ['distric' => substr($val->alamatTujuan, 0, 16).'..'];
			}
			
			 
		};
		
		return $this->sendResponseOk($result);

	}
	
	public function getOrderForBidById(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		$result = Tbl_order::whereRaw('"orderStatus" <> "close"')->find($request->orderId);
		if($result->orderStatus != 'complete'){
			if($this->checkingBid($result->orderDate, $result->orderTime) == false){
				
				Tbl_order::where('id', $request->orderId)->update([
				'orderStatus'  => 'failed'
				]);
			
				$message 	= 'mohon maaf, order tersebut telah kadaluarsa';
				return $this->sendResponseError($message, '',203);
			}
		}
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$result->customer = Tbl_customer::find($result->customerId);
			$result->rute = Tbl_rute_pricelist::find($result->ruteId);
			$result->kondisiKendaraan = Tbl_kondisi_kendaraan::find($result->kondisiKendaraanId);
			$result->JenisKendaraan = Tbl_jenis_kendaraan::find($result->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($result->typeKendaraanId);
			$result->bidTotal = Tbl_bidding::where('orderId', $request->orderId)->count();
		
		return $this->sendResponseOk($result);

	}
	
	public function getOrderById(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_order::find($request->orderId);
		/* $result = Tbl_invoice::select(
						'tbl_invoice.id as invoice_id',
						'tbl_invoice.orderId',
						'tbl_invoice.mitraId',
						'tbl_order.*')	
					->join('tbl_order', 'tbl_invoice.orderId', '=', 'tbl_order.id')
					->where('tbl_invoice.mitraId', Auth::user()->id)
					->where('tbl_order.id', $request->orderId)
					->first(); */
					
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$result->customer = Tbl_customer::find($result->customerId);
			$result->rute = Tbl_rute_pricelist::find($result->ruteId);
			$result->kondisiKendaraan = Tbl_kondisi_kendaraan::find($result->kondisiKendaraanId);
			$result->JenisKendaraan = Tbl_jenis_kendaraan::find($result->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($result->typeKendaraanId);
			$result->bidTotal = Tbl_bidding::where('orderId', $request->orderId)->count();
		
		return $this->sendResponseOk($result);

	}
	
	public function getInvoiceById(Request $request){
		$validator = Validator::make($request->all(), [
			'invoice_id'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_invoice::select(
					'tbl_invoice.id as invoice_id',
					'tbl_invoice.noInvoice',
					'tbl_invoice.orderId',
					'tbl_invoice.mitraId',
					'tbl_invoice.biddingId',
					'tbl_invoice.driverId',
					'tbl_invoice.billing',
					'tbl_invoice.paymentMethod',
					'tbl_invoice.paymentStatus',
					'tbl_invoice.paymentDate',
					'tbl_invoice.paymentToMitra',

					'tbl_order.id',
					'tbl_order.ruteId',
					'tbl_order.customerId',
					'tbl_order.alamatAsal',
					'tbl_order.alamatTujuan',
					'tbl_order.kondisiKendaraanId',
					'tbl_order.JenisKendaraanId',
					'tbl_order.typeKendaraanId',
					'tbl_order.orderDate',
					'tbl_order.orderTime',
					'tbl_order.orderStatus',
					'tbl_order.orderCost')	
					->join('tbl_order', 'tbl_invoice.orderId', '=', 'tbl_order.id')
					->where('tbl_invoice.mitraId', Auth::user()->id)
					->whereRaw('"tbl_order.orderStatus" <> "process"')
					//->where('tbl_invoice.paymentStatus', 'settlement')
					//->where('tbl_order.orderStatus', 'settlement')
					->find($request->invoice_id);
					
		//$result = Tbl_order::whereRaw('"orderStatus" <> "process"')->find($request->orderId);
		/* if($result->orderStatus != 'complete'){
			if($this->checkingBid($result->orderDate, $result->orderTime) == false){
				
				Tbl_order::where('id', $request->orderId)->update([
				'orderStatus'  => 'failed'
				]);
			
				$message 	= 'mohon maaf, order tersebut telah kadaluarsa';
				return $this->sendResponseError($message, '',203);
			}
		} */
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$result->customer = Tbl_customer::find($result->customerId);
			$rute = Tbl_rute_pricelist::find($result->ruteId);
			
			$result->JenisKendaraan = Tbl_jenis_kendaraan::find($result->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($result->typeKendaraanId);
			$result->bidTotal = Tbl_bidding::where('orderId', $request->orderId)->count();
			
			if($rute){		
				$result->rute = Tbl_postCode::where('postcode', $rute->asalPostcode)->first()->distric.' --> '.Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first()->distric;
				 
			}else{
				$result->rute =  substr($result->alamatAsal, 0, 16).' --> '.substr($result->alamatTujuan, 0, 16);
				
			}	
		
		return $this->sendResponseOk($result);

	}
	
	public function postDriver(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'driverId'  => 'required',
			'noTnkbTowing'  => 'required',
			
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$check = Tbl_tracking::where('orderId', $request->orderId)->first();
		
		if(!empty($check)){
			$message 	= 'Anda telah memilih driver untuk order ini';
			return $this->sendResponseError($message, null, 202);
		}
		
		$input = Tbl_tracking::create([
			'orderId' => $request->orderId,
			'driverId' => $request->driverId,
			'note' => $request->note,
			'trackPoint' => 0,
			'status'  => 0,
			'msg'  => 'Driver sedang bersiap-siap untuk ke lokasi anda',
			
		]);
		
		Tbl_invoice::where('orderId', $request->orderId)->update([
			'driverId' => $input->driverId,
			'noTnkbTowing' => $request->noTnkbTowing,

		]);
		
		return $this->sendResponseCreate($input);
	}
	



}