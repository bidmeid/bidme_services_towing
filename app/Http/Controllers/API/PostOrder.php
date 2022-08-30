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
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class PostOrder extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'ruteId'  => 'nullable',
			'orderType'  => 'required',
			'asalPostcode'  => 'required',
			'tujuanPostcode'  => 'required',
			'kondisiKendaraanId'  => 'required',
			'jenisKendaraanId'  => 'required',
			'typeKendaraanId'  => 'required',
			'orderCost'  => 'required',
			'noTelp'  => 'required',
			'orderDate'  => 'required',
			'orderTime'  => 'required',
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
			'orderCost'  => $request->orderCost,
			'orderDate'  => $request->orderDate,
			'orderTime'  => $request->orderTime,
			'orderStatus'  => 'process',
		]);
		
		$message = $this->sendEmail($input->id);
		
		return $this->sendResponseCreate($input);
	}

	public function myOrder(Request $request){
		$validator = Validator::make($request->all(), [
			'orderStatus'  => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		if ($request->orderStatus == 'recent'){$orderStatus = 'IS NOT NULL'; }else{ $orderStatus = ' = '.$request->orderStatus; };
		
		$result = Tbl_order::where('customerId', Auth::user()->id)->whereRaw('orderStatus '. $orderStatus)->get();
	
		if((is_null($result)) OR ($result->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
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
		$result->mitra = Tbl_user_mitra::find($result->bid->mitraId);
		$result->biayaApp = 20000;
		
		return $this->sendResponseOk($result);

	}
	
	public function couponVoucher(Request $request){
		$validator = Validator::make($request->all(), [
			'kupon'  => 'required',
			 
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$result = ['status'  => 'valid', 'potongan'  => 20000];
	 
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
	
	private function created($uniqid) {
		$ticket = rand(100, 999).str_pad(substr($uniqid,3), 3, STR_PAD_LEFT);
		
		return $ticket;
	}
	
	private function sendEmail($orderId)
	{
		$result = Tbl_user_mitra::get();
		$order  = Tbl_order::find($orderId);
		
		foreach($result as $items){
		$details = [
			'title' => 'Order Towing',
			'name' => $items->name,
			'alamatAsal' => $order->alamatAsal,
			'alamatTujuan' => $items->alamatTujuan,
			 
			];
		
		 $catch = Mail::to($items->email)->send(new Email($details));
		}
		return true;

	}
}