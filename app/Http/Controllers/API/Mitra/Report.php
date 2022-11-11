<?php

namespace App\Http\Controllers\Api\Mitra;
use App\Http\Controllers\Api as Controller;
use Illuminate\Http\Request;
use App\Models\Tbl_invoice;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class Report extends Controller
{


	public function index(Request $request){
		
		$dateStart	= $request->input('dateStart');	
		if ($dateStart == ''){$dateStart = "2020-01-01"; };
		$dateEnd	= $request->input('dateEnd');
		if ($dateEnd == ''){$dateEnd = date("Y-m-d"); };		
		$paymentToMitra	= $request->input('paymentToMitra');
		if ($paymentToMitra == ''){$paymentToMitra = 'IS NOT NULL'; }else {$paymentToMitra = '= "'.$paymentToMitra.'"';};
		$orderStatus	= $request->input('orderStatus');
		if ($orderStatus == ''){$orderStatus = 'IS NOT NULL'; }else {$orderStatus = '= "'.$orderStatus.'"';};
		
		
		$order		= $request->input('order'); 
		$draw 		= $request->input('draw');
		$offset		= $request->input('start'); if ($offset == ''){$offset = 0; };
		$limit		= $request->input('length'); if ($limit == ''){$limit = 25; };
		$search		= $request->input('search')['value']; if ($search == ''){$search = ''; };		
		$order		= $request->input('order')[0]['column']; 
		$sort 		= $request->input('order')[0]['dir']; if ($sort == ''){$sort = 'DESC'; };
		$columns	= $request->input('columns')[$order]['data'];  if ($columns == ''){$columns = 'id'; };

		$datas 	= Tbl_invoice::select(
					'tbl_invoice.id as invoice_id',
					'tbl_invoice.orderId',
					'tbl_invoice.mitraId',
					'tbl_invoice.biddingId',
					'tbl_invoice.driverId',
					'tbl_invoice.paymentToMitra',

					'tbl_order.id',
					'tbl_order.ruteId',
					'tbl_order.customerId',
					'tbl_order.orderDate',
					'tbl_order.orderStatus',
					'tbl_order.orderCost')	
					->join('tbl_order', 'tbl_invoice.orderId', '=', 'tbl_order.id')
					->where('tbl_invoice.mitraId', Auth::user()->id)
					->whereBetween('tbl_order.orderDate', [$dateStart, $dateEnd])
					->whereRaw('tbl_order.orderStatus '.$orderStatus)
					->whereRaw('tbl_invoice.paymentToMitra '.$paymentToMitra);
					
		$data	= $datas->orderBy($columns, $sort)
					->offset($offset)
					->limit($limit)
					->get();
					
		$total  = $datas->count();
		
		$result['draw']           = $draw ;
		$result['recordsTotal']   = $total;
		$result['recordsFiltered']= $total;
		$result['data'] 		  = $data;
		
		if((empty($data)) AND ($total == 0)){
			
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}
		
		return  $this->sendResponseOk($result);
	}
	
}