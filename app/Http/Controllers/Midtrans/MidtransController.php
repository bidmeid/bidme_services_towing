<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use App\Models\Feature\Order;
use App\Services\Midtrans\CallbackService;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Tbl_invoice;

class MidtransController extends Controller
{
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

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
        return $order->update(['paymentStatus' => $json->transaction_status]);
        
    }
}
