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
			'bidId' => 'required',
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
		$bid = Tbl_bidding::find($request->bidId);
		
		$bidMitra = $bid->bidding;
		$biayaApp = $this->biayaApp();
		$billing = $bidMitra + $biayaApp;
		
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
			'biddingId' => $request->bidId,
			'mitraId' => $bid->mitraId,
			'noInvoice' => $invoice,
			'paymentMethod'  => $request->paymentMethod,
			'paymentStatus'  => 'waiting',
			'billing'  	=> $billing,
		]);
		
		Tbl_order::where('id', $request->orderId)->update(['orderCost' => $billing, 'orderStatus' => 'pending']);
		
		}else{
			$input['invoice'] = Tbl_invoice::where('id', $invoices->id)->update([
			'orderId' => $request->orderId,
			'biddingId' => $request->bidId,
			'mitraId' => $bid->mitraId,
			
			'paymentMethod'  => $request->paymentMethod,
			//'paymentStatus'  => 'pending',
			'billing'  	=> $billing,
		]);
			$input['invoice'] = $invoices;
		}
		
		/* $orders = Tbl_order::where('id', $request->orderId)->update([
			'orderStatus'  => 'payment'
		]);
		 */
		Tbl_bidding::where('orderId', $request->orderId)->update([
			'bidStatus'  => 2 // tidk terpilih
		]); 
		
		Tbl_bidding::where('id', $request->bidId)->update([
			'bidStatus'  => 1 //terpilih
		]);
		
		$message = $this->sendEmail($request->orderId);
		
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
	
	public function paymentStatus(request $request) {
		$validator = Validator::make($request->all(), [
			'order_id' => 'required',
			'transaction_status'  => 'required',

        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors(), 202);       
        }
		
		$result = Tbl_invoice::where('noInvoice', $request->order_id)->update([
			'paymentStatus'  => $request->transaction_status
		]);
		Tbl_order::where('id', $result->orderId)->update(['orderStatus'  => $request->transaction_status]);
		 
		if(empty($result)){
			return $this->sendResponseError(null);
		}
		 
		return $this->sendResponseCreate(null);
	}
	
	
	private function created($uniqid) {
		$ticket = rand(10, 99).str_pad(substr($uniqid,4), 4, STR_PAD_LEFT);
		$ticket = 'BID-'.strtoupper($ticket);
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
		$invoice  = Tbl_invoice::where("orderId", $orderId)->first();
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
	
	public function snaptoken(request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderId' => 'required',


        ]);

        if ($validator->fails()) {
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors()); 
        }
		
		$invoices = Tbl_invoice::where('orderId', $request->orderId)->first();
		
		if(empty($invoices)){
			return $this->sendResponseError(null);
		}
		
		$order  = Tbl_order::find($request->orderId);
		$customer = Tbl_customer::find($order->customerId);
		
		// Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
		
        \Midtrans\Config::$appendNotifUrl = url('api/payment-handler');
		
		$params = array(
            'transaction_details' => array(
                'order_id' => $invoices->noInvoice,
                'gross_amount' => $invoices->billing,
                'date' => $invoices->created_at,
            ),
            "item_details" =>  array(
                [
                    "id" => $invoices->orderId,
                    "price" => $invoices->billing,
                    "quantity" => 1,
                    "name" => $invoices->noInvoice
                ]
            ),
            'customer_details'  => array(
                'first_name'    =>  $customer->name,
                'email'         => $customer->email,
                'phone'         => $customer->no_telp,
            ),
        );
		$return = $this->CheckPaymentStatus($invoices->noInvoice);
		
		if($return['check'] == true){
			return $this->sendResponseOk($return);
		};
		
		try {

            $snapToken = \Midtrans\Snap::getSnapToken($params);
			
        } catch (Exception $e) {

            throw new Exception("API Request Error unable to json_decode API response");
        }
		
		$input['item'] = $params;
		$input['snapToken'] = $snapToken;
		
		return $this->sendResponseOk($input);
		
	}
	
	public function CheckPaymentStatus($noInvoice) {
		
		$status = \Midtrans\Transaction::status($noInvoice);
		if($status != false){
			$invoice = Tbl_invoice::where('noInvoice', $noInvoice)->first();
			$return['check'] = true;
			if($status->transaction_status == $invoice->paymentStatus){
				
				$return['msg']  = 'nomor invoice di temukan';
			}else{
				
				$return['msg']  = 'status pembayaran berhasil diperbaharui';
				 
			}
				$order = Tbl_invoice::where('noInvoice', $status->order_id)->first();
				
				$order->update(['paymentStatus' => $status->transaction_status, 'paymentDate' => $status->settlement_time]);
				Tbl_order::where('id', $order->orderId)->update(['orderStatus'  => $status->transaction_status]);
			
			$return['data']  = $status;
			$return['order']  = $order;
			return $return;
		    
		}else{
			$return['check'] = false;
			$return['msg']  = 'nomor invoice tidak di temukan';
			return $return;
		}
		
	}
}