<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_user_mitra;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_postCode;
use App\Models\Tbl_kondisi_kendaraan;
use App\Models\Tbl_jenis_kendaraan;
use App\Models\Tbl_type_kendaraan;
use App\Models\Tbl_invoice;
use App\Models\Tbl_tracking;
use App\Models\Tbl_feedback;
use App\Jobs\BroadcastOrder;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class PostOrder extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'ruteId'  => 'nullable',
			'orderType'  => 'nullable',
			'asalPostcode'  => 'nullable',
			'tujuanPostcode'  => 'nullable',
			'kondisiKendaraanId'  => 'required',
			'jenisKendaraanId'  => 'required',
			'typeKendaraanId'  => 'required',
			//'orderCost'  => 'required',
			'noTelp'  => 'required',
			//'orderDate'  => 'required',
			//'orderTime'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		$ticketGen = $this->created(uniqid());
		
		if(!Tbl_order::where('ticket', '=', $ticketGen)->exists()) {
			$ticket = $ticketGen;
		} else {
			$ticket = $this->created();
		}
		
		$rute = Tbl_rute_pricelist::find($request->ruteId);
		if(!empty($rute)){
			$orderCost = $rute->standarHarga;
		}else{
			$orderCost = 0;
		};
		if(!$request->orderDate){
            $orderDate = date('Y-m-d');       
		}else{
			$orderDate = $request->orderDate;
		}
		if(!$request->orderTime){
            $orderTime = date('H:i:s');       
        }else{
			$orderTime = $request->orderTime;
		}
		
		$input = Tbl_order::create([
			'ticket' => $ticket,
			'customerId' => Auth::user()->id,
			'ruteId' => $request->ruteId,
			'kondisiKendaraanId' => $request->kondisiKendaraanId,
			'jenisKendaraanId'  => $request->jenisKendaraanId,
			'typeKendaraanId'  => $request->typeKendaraanId,
			'orderType' => $request->orderType,
			'latLongAsal'  => $request->latLongAsal,
			'alamatAsal'  => $request->alamatAsal,
			'latLongTujuan'  => $request->latLongTujuan,
			'alamatTujuan'  => $request->alamatTujuan,
			'telp'  => $request->noTelp,
			//'orderCost'  => $orderCost,
			'orderDate'  => $orderDate,
			'orderTime'  => $orderTime,
			'orderStatus'  => 'process',
		]);
		
		$message = $this->sendEmail($input->id);
		$input->notif = $this->sendNotif($input->id);
		
		return $this->sendResponseCreate($input);
	}

	public function myOrder(Request $request){
		$validator = Validator::make($request->all(), [
			'orderStatus'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		if ($request->orderStatus == 'recent'){$orderStatus = 'IS NOT NULL'; }elseif ($request->orderStatus == 'process'){$orderStatus = '<> "failed" AND orderStatus <> "complete"'; }else{ $orderStatus = ' = "'.$request->orderStatus.'"'; };
		
		$order = Tbl_order::where('customerId', Auth::user()->id)->whereRaw('orderStatus '. $orderStatus)->orderBy('id', 'DESC')->get();
		
		$result = array();
		foreach($order as $key=>$val){
			$rute = Tbl_rute_pricelist::find($val->ruteId);
			
			$result[$key] = $val;
			
			
			$result[$key]['jenisKendaraan'] = Tbl_jenis_kendaraan::find($val->JenisKendaraanId);
			$result[$key]['typeKendaraan'] = Tbl_type_kendaraan::find($val->typeKendaraanId);	
			if($rute){		
				$result[$key]['standarHarga'] = $rute->standarHarga;
				$result[$key]['regionAsal'] = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
				$result[$key]['regionTujuan'] = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}else{
				$result[$key]['standarHarga'] = 'Tidak Tersedia';
				$result[$key]['regionAsal'] = ['distric' => substr($val->alamatAsal, 0, 16).'..'];
				$result[$key]['regionTujuan']= ['distric' => substr($val->alamatTujuan, 0, 16).'..'];
			}		
		};
	
		if(empty($result)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
	   
		
		return $this->sendResponseOk($result);

	}	
	
	public function getOrderById(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$invoice = Tbl_invoice::where('orderId', $request->orderId)->first();
		
		$result = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
		
		
		$result->invoice = '';
		if(empty($result)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
			$rute = Tbl_rute_pricelist::find($result->ruteId);
			
			$result->status = 'unpaid';
			$result->mitra = null;
			
			if($rute){
			$result->rute = $rute;
			$result->regionAsal = Tbl_postCode::where('postcode', $rute->asalPostcode)->first();
			$result->regionTujuan = Tbl_postCode::where('postcode', $rute->tujuanPostcode)->first();
			}else{
				$result->rute = 'Tidak Ditemukan';
				$result->regionAsal = ['distric' => substr($result->alamatAsal, 0, 16).'..'];
				$result->regionTujuan = ['distric' => substr($result->alamatTujuan, 0, 16).'..'];
			}	
			$result->kondisiKendaraan = Tbl_kondisi_kendaraan::find($result->kondisiKendaraanId);
			$result->jenisKendaraan = Tbl_jenis_kendaraan::find($result->JenisKendaraanId);
			$result->typeKendaraan = Tbl_type_kendaraan::find($result->typeKendaraanId);
			
		if((!empty($invoice)) OR ($invoice != null)){
			if($invoice->paymentStatus == 'settlement'){
			$result->status = 'paid';
			
			
			}
			$result->returns = $this->CheckPaymentStatus($invoice->noInvoice);
			 
			$result->invoice = $invoice;
			$result->paymentStatus = $invoice->paymentStatus;
			$result->mitra = Tbl_user_mitra::find($invoice->mitraId);
		}
		
		return $this->sendResponseOk($result);

	}
	
	public function checkOut(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'bidId'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}
		$result->bid = Tbl_bidding::where('orderId', $request->orderId)->find($request->bidId);
		if(empty($result->bid)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}
		/* $input = Tbl_order::where('id', $request->orderId)->update([
			'bidId' => $request->bidId, 
			]); */
		$result->mitra = Tbl_user_mitra::find($result->bid->mitraId);
		$result->biayaApp = $this->biayaApp();
		
		return $this->sendResponseOk($result);

	}
	
	public function couponVoucher(Request $request){
		$validator = Validator::make($request->all(), [
			'kupon'  => 'required',
			 
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		if($result->kupon == 'bidme22'){
		$result = ['status'  => 'valid', 'potongan'  => 20000];
		}else{
		$result = ['status'  => 'notvalid', 'potongan'  => 0];
		}
		return $this->sendResponseOk($result);

	}
	
	public function cancelOrder(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'reason'  => 'nullable',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_order::where('orderStatus', 'process')->find($request->orderId);
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}else{
			$input = Tbl_order::where('id', $request->orderId)->update([
			'orderStatus' => 'failed', 
			]);
		}
	   
		
		return $this->sendResponseOk($result);

	}
	
	public function finishOrder(Request $request){
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required'
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
		
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be done';
			return $this->sendResponseError($message, null, 202);
		}else{
			//$orders = Tbl_order::where('id', $request->orderId)->update([
			//'orderStatus'  => 'complete'
			//]);
			$tracking = Tbl_tracking::where('orderId', $request->orderId)->first();
			$tracking->update([
			'finishCust'  => '1'
			]);
			
			if($tracking->finishDriver == 1){
				$result->update([
				'orderStatus'  => 'complete'
				]);
			}
		}
	   
		
		return $this->sendResponseCreate(null);

	}
	
	public function reviewOrder(Request $request){
		
		$validator = Validator::make($request->all(), [
			'orderId'  => 'required',
			'rating'  => 'required'
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		if($request->rating !=0){
			
			$result = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
			
			if((is_null($result)) OR ($result->count() == 0)){
				$message 	= 'Your request couldn`t be done';
				return $this->sendResponseError($message, null, 202);
			}else{
				$mitraId = Tbl_invoice::select('mitraId')->where('orderId', $request->orderId)->first()->mitraId;
				
				$input = Tbl_feedback::create([
					'orderId' => $request->orderId,
					'userId' => Auth::user()->id,
					'mitraId' => $mitraId,
					'userName' => Auth::user()->name,
					'rating' => $request->rating,
					'review' => $request->review,
				 
				]);
			}
	    }
		
		return $this->sendResponseCreate(null);

	}
	
	private function created($uniqid) {
		$ticket = rand(100, 999).str_pad(substr($uniqid,3), 3, STR_PAD_LEFT);
		
		return $ticket;
	}
	
	private function sendEmail($orderId)
	{
		$result = Tbl_user_mitra::whereNotNull('device_token')->get();
		$order  = Tbl_order::find($orderId);
		
		foreach($result as $items){		
		
		$details = [
			'title' => 'Order Towing',
			'body' => 'Pemberitahuan Order Towing Tersedia Untuk Anda Bidding',
			'url' => 'http://mitra.bidme.id',
			'name' => $items->name,
			'alamatAsal' => $order->alamatAsal,
			'alamatTujuan' => $order->alamatTujuan,
			 
			];
		//$this->sendNotification($items, $details);
		 dispatch(new BroadcastOrder($details));	
		 //BroadcastOrder::dispatch($details);
		 //$catch = Mail::to($items->email)->queue(new Email($details));
		}
		return true;

	}
	
	private function sendNotif($orderId)
	{
		$users = Tbl_user_mitra::whereNotNull('device_token')->pluck('device_token')->all();
		$order  = Tbl_order::find($orderId);
		
		
		$details = [
			'title' => 'Order Towing',
			'body' => 'Pemberitahuan Order Towing Tersedia Untuk Anda Bidding',
			'url' => 'http://mitra.bidme.id',
			"icon" => 'https://mitra.bidme.id/backend/lc_icon.png',
			//'name' => $items->name,
			'alamatAsal' => $order->alamatAsal,
			'alamatTujuan' => $order->alamatTujuan,
			 
			];
			
		$return = $this->sendNotification($users, $details);
		
		return $return;

	}
	
	public function CheckPaymentStatus($noInvoice) {
		// Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
		 
		$status = \Midtrans\Transaction::status($noInvoice);
		//$statuss = '';
		$bank_acc = '';
		$va_number = '';
		$expiry_time = '';
		$payment_type = '';
		if($status != false){
			$invoice = Tbl_invoice::where('noInvoice', $noInvoice)->first();
			$return['check'] = true;
			if($status->transaction_status == $invoice->paymentStatus){
				
				$return['msg']  = 'nomor invoice di temukan';
			}else{
				
				$return['msg']  = 'status pembayaran berhasil diperbaharui';
				
			}
				//$statuss = $status->settlement_time;
				$bank_acc = $status->va_numbers[0]->bank;
				$va_number = $status->va_numbers[0]->va_number;
				$expiry_time = $status->expiry_time;
				$payment_type = $status->payment_type;
				
				$order = Tbl_invoice::where('noInvoice', $status->order_id)->first();
				
				$order->update(['paymentStatus' => $status->transaction_status, 
								'payment_type' => $payment_type,
								'bank_acc' => $bank_acc,
								'va_number' => $va_number,
								'expiry_time' => $expiry_time
								]);
								
				Tbl_order::where('id', $order->orderId)->update(['orderStatus'  => $status->transaction_status]);
				
			
			 
			return $status;
		    
		}else{
			 
			return false;
		}
		
	}
	
	
}