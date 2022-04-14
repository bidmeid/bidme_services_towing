<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_rute_pricelist;
use App\Models\Tbl_jenis_kendaraan;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestCost extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderType' => 'required',
			'asalPostcode'  => 'required',
			'tujuanPostcode'  => 'required',
			'jenisKendaraanId'  => 'required',
			'kondisiKendaraanId'  => 'required',
        ]);
		
		 
        $get = Tbl_rute_pricelist::where('asalPostcode', substr($request->asalPostcode, 0, 3))
				->where('tujuanPostcode', substr($request->tujuanPostcode, 0, 3))
				->first();
		if($get != null){
			$get->makeHidden(['created_at', 'updated_at']);
		 		
		$get->jenisKendaraanId = Tbl_jenis_kendaraan::select('jenisKendaraan')->find($get->jenisKendaraanId)->jenisKendaraan;
		$get->chargeHarga = 0;
		$get->totalHarga = $get->standarHarga + 0;
		
		$result['RequestCost']	= $get;
		return $this->sendResponseOk($result);
		}else{
		
		$aa['chargeHarga']	= 0;
		$aa['totalHarga']	= 0;
		$result['RequestCost']	= $aa;
		return $this->sendResponseCustom('daftar harga rute tidak ditemukan', $result);
		}
		
	}

	

}