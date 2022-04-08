<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_merek_kendaraan;

use Illuminate\Http\Request;

class MerekKendaraan extends Controller
{


	public function index(){
		
		
        $result['MerekKendaraan']		= Tbl_merek_kendaraan::get();
		
								
		return $this->sendResponseOk($result);
	}

	

}