<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_merek_kendaraan;

use Illuminate\Http\Request;

class MerekKendaraan extends Controller
{


	public function index(request $request){
		
		$jenis 		= $request->input('jenis'); if ($jenis == ''){$jenis = 'IS NOT NULL'; }else {$jenis = '= '.$jenis;};
        $result['MerekKendaraan']		= Tbl_merek_kendaraan::whereRaw('jenisKendaraanId '. $jenis)->get();
		
								
		return $this->sendResponseOk($result);
	}

	

}