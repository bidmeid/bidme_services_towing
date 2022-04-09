<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_kondisi_kendaraan;

use Illuminate\Http\Request;

class KondisiKendaraan extends Controller
{


	public function index(request $request){
		
		 
        $result['KondisiKendaraan']		= Tbl_kondisi_kendaraan::get();
		
								
		return $this->sendResponseOk($result);
	}

	

}