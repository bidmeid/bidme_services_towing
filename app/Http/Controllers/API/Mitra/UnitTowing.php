<?php

namespace App\Http\Controllers\Api\Mitra;
use App\Http\Controllers\Api as Controller;
use Illuminate\Http\Request;
use \App\Models\Tbl_unit_towing;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UnitTowing extends Controller
{


	public function index(Request $request){
		
		$render		= $request->input('render'); if ($render){$limit = 6; } else {$limit = 5;};	
		$order		= $request->input('order'); 
		$sort 		= $request->input('sort'); if ($sort == ''){$sort = 'ASC'; };
		$columns	= "id";

		$data 	= Tbl_unit_towing::where('mitra_id', Auth::user()->id)
					->orderBy($columns, $sort)
					->paginate($limit);

		if((!empty($data)) AND ($data->count() != 0)){
// 			$data->data = colection_pages::collection($data);
			$result = $data;
		}else{
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}
		return  $this->sendResponseOk($result);
	}
	
	public function createTowing(request $request){
		
		$validator = Validator::make($request->all(), [
            'mitraId' => 'required',
            'jenisTowing' => 'required',
            'noTnkbTowing' => 'required',
			'lokasiUnit' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
	
			$input = Tbl_unit_towing::create([
			'mitra_id'   			=> $request->mitraId,
			'jenisTowing'   			=> $request->jenisTowing,
			'noTnkbTowing'   		=> $request->noTnkbTowing,
			'lokasiUnit'   		=> $request->lokasiUnit,
			]);

			 
			return $this->sendResponseCreate($input);
		
		
	}
	
	public function updateTowing(request $request){
		
		$validator = Validator::make($request->all(), [
            'towingId' => 'required',
            'jenisTowing' => 'required',
            'noTnkbTowing' => 'required',
			'lokasiUnit' => 'required',
            
		]);
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = Tbl_unit_towing::find($request->towingId);

		if((!empty($result)) AND ($result->count() != 0)){
			$input = Tbl_unit_towing::where('id', $request->towingId)->update([
			'jenisTowing'   			=> $request->jenisTowing,
			'noTnkbTowing'   		=> $request->noTnkbTowing,
			'lokasiUnit'   		=> $request->lokasiUnit,
			]);

			 
			return $this->sendResponseCreate($input);
			 
		}else{
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, null, 202);
		}

		
	}
	
	public function getTowingById(Request $request){	
		$validator = Validator::make($request->all(), [
            'towingId' => 'required',            
		]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = Tbl_unit_towing::where('mitra_id', Auth::user()->id)->find($request->towingId);
		
       if(empty($result)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
		
		return $this->sendResponseOk($result);
		
	}
	
	public function deleteTowing(Request $request){	
		$validator = Validator::make($request->all(), [
            'towingId' => 'required',            
		]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
		}
		
		$result = Tbl_unit_towing::where('mitra_id', Auth::user()->id)->find($request->towingId);
		
       
		$result->delete();
		return $this->sendResponseDelete(null);
		
	}

	
}