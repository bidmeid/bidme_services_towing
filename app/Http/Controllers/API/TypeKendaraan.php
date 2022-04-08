<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_type_kendaraan;

use Illuminate\Http\Request;

class TypeKendaraan extends Controller
{


	public function index(request $request){
		
		$merek 		= $request->input('merek'); if ($merek == ''){$merek = 'IS NOT NULL'; }else {$merek = '= '.$merek;};
        $result['TypeKendaraan']		= Tbl_type_kendaraan::whereRaw('merekKendaraanId '. $merek)->get();
		
								
		return $this->sendResponseOk($result);
	}

	

}