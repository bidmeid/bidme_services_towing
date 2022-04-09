<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_jenis_kendaraan;

use Illuminate\Http\Request;

class JenisKendaraan extends Controller
{


	public function index(){
		
		
        $result['JenisKendaraan']		= Tbl_jenis_kendaraan::get();
		
								
		return $this->sendResponseOk($result);
	}

	

}