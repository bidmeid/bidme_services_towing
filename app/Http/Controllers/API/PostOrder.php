<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_order;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;

class PostOrder extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'userToken' => 'required',
			'customerId' => 'required',
			'ruteId'  => 'required',
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
			'customerId' => 1,
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
			'orderStatus'  => 'proccess',
		]);
		
		return $this->sendResponseCreate($input);
	}

	
	private function created($uniqid) {
		$ticket = rand(100, 999).str_pad(substr($uniqid,3), 3, STR_PAD_LEFT);
		
		return $ticket;
	}
}