<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_invoice;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Invoice extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId' => 'required',
			'biddingId' => 'required',
			'driverId'  => 'required',
			'noInvoice'  => 'required',
			'noTnkbTowing'  => 'required',
			'paymentMethod'  => 'required',
			'billing'  => 'required',
			 
			
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		$ticketGen = $this->created(uniqid());
		
		if(!Tbl_invoice::where('noInvoice', '=', $invoiceGen)->exists()) {
			$invoice = $invoiceGen;
		} else {
			$invoice = $this->created();
		}
		
		$input = Tbl_invoice::create([
			'orderId' => $request->orderId,
			'biddingId' => $request->biddingId,
			'driverId' => $request->driverId,
			'noInvoice' => $invoice,
			'noTnkbTowing'  => $request->noTnkbTowing,
			'paymentMethod'  => $request->paymentMethod,
			'bankName' => $request->bankName, //BCA
			'accName' => $request->accName,  //Atas Nama
			'accNumber' => $request->accNumber, //Nomor Rekening
			'orderStatus'  => 'pending',
		]);
		
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
		
		$input = Tbl_invoice::where('id', $request->id)->update[
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
}