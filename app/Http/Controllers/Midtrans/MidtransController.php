<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Api as Controller;

use Illuminate\Http\Request;

use App\Models\Tbl_invoice;
use App\Models\Tbl_order;


class MidtransController extends Controller
{


    // public function receive(CallbackService $callback)
    // {
    //     $callback->updateOrder();
    // }

    // public function success()
    // {
    //     return view('midtrans.success');
    // }

    public function payment_handler(Request $request)
    {
       $json = json_decode($request->getContent());
        // return response()->json([
        //     'message' => true,
        //     'data'     => $json
        // ]);
        
        $signature_key = hash('sha512', $json->order_id . $json->status_code . $json->gross_amount . env('MIDTRANS_SERVER_KEY'));

        // if ($signature_key != $json->signature_key) {
        //     return abort(404);
        // }
        
		
		$order = Tbl_invoice::where('noInvoice', $json->order_id)->first();
		
		
        $order->update(['paymentStatus' => $json->transaction_status, 'paymentDate' => $json->settlement_time]);
		Tbl_order::where('id', $order->orderId)->update(['orderStatus'  => $json->transaction_status]);
		
		return $this->sendResponseOk($json);
        
    }
}
