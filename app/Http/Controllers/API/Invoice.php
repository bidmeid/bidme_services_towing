<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_invoice;
use App\Models\Tbl_order;
use App\Models\Tbl_bidding;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Invoice extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId' => 'required',
			'paymentMethod'  => 'required',

        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 202);       
        }
		
		$order = Tbl_order::where('customerId', Auth::user()->id)->find($request->orderId);
		if(empty($order)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
		$bid = Tbl_bidding::where('orderId', $order->id)->find($order->bidId);
		
		$billing = $bid->bidding - $this->couponVoucher($request->kupon);
		
		$ticketGen = $this->created(uniqid());
		
		if(!Tbl_invoice::where('noInvoice', '=', $invoiceGen)->exists()) {
			$invoice = $invoiceGen;
		} else {
			$invoice = $this->created();
		}
		
		$input['invoice'] = Tbl_invoice::create([
			'orderId' => $request->orderId,
			'biddingId' => $order->bidId,
			'noInvoice' => $invoice,
			'paymentMethod'  => $request->paymentMethod,
			'paymentStatus'  => 'pending',
			 
			'billing'  	=> $billing,
		]);
		$input['user'] = Auth::user();
		return $this->sendResponseCreate($input);
	}
	
	public function view($id) {

		$result['invoice']  = Tbl_invoice::find($id);
		 
		if(empty($result)){
			return $this->sendResponseError(null);
		}
		 
		return $this->sendResponseOk($result);
	}
	
	public function PaymentSuccess(request $request){
		
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			
        ]);

        if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$input = Tbl_invoice::where('id', $request->id)->update([
			'orderStatus'  => 'success',
		]);
		
		return $this->sendResponseCreate($input);
	}
	
	public function PaymentFailed(){
		 //
	}

	
	private function created($uniqid) {
		$ticket = rand(100, 999).str_pad(substr($uniqid,2), 2, STR_PAD_LEFT);
		
		return $ticket;
	}
	
	private function couponVoucher($kupon){
		 
		if($kupon == 'bidme22'){
		$result = 20000;
		}else{
		$result = 0;
		}
		return $result;

	}
}