<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_invoice;
use App\Models\Tbl_order;
use App\Models\Tbl_bidding;
use App\Models\Tbl_user_mitra;
use App\Models\Tbl_customer;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailMitra;

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
		
		$invoiceGen = $this->created(uniqid());
		
		if(!Tbl_invoice::where('noInvoice', '=', $invoiceGen)->exists()) {
			$invoice = $invoiceGen;
		} else {
			$invoice = $this->created();
		}
		
		$invoices = Tbl_invoice::where('orderId', $request->orderId)->first();
		if(!$invoices){
		$input['invoice'] = Tbl_invoice::create([
			'orderId' => $request->orderId,
			'biddingId' => $order->bidId,
			'mitraId' => $bid->mitraId,
			'noInvoice' => $invoice,
			'paymentMethod'  => $request->paymentMethod,
			'paymentStatus'  => 'pending',
			'billing'  	=> $billing,
		]);
		}else{
			$input['invoice'] = $invoices;
		}
		
		$orders = Tbl_order::where('id', $request->orderId)->update([
			'orderStatus'  => 'payment'
		]);
		
		Tbl_bidding::where('orderId', $request->orderId)->update([
			'bidStatus'  => 1
		]);
		
		Tbl_bidding::where('id', $request->biddingId)->update([
			'bidStatus'  => 2
		]);
		
		$message = $this->sendEmail($orders->id);
		
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
	
	private function sendEmail($orderId)
	{
		$order  = Tbl_order::find($orderId);
		$invoice  = Tbl_invoice::where("orderId", $orderId);
		$mitra = Tbl_user_mitra::find($invoice->mitraId);
		$customer = Tbl_customer::find($order->customerId);
		
		 
		$details = [
			'title' => 'Order masuk towing untuk '.$customer->name,
			'name' => $customer->name,
			'alamatAsal' => $order->alamatAsal,
			'alamatTujuan' => $order->alamatTujuan,
			'invoice' => $invoice->noinvoice,
			'payment' => "Sudah dibayar",
			'paymentDate' => $invoice->updated_at,
			 
			];
		
		$catch = Mail::to($mitra->email)->send(new EmailMitra($details));
		 
		return true;

	}
}