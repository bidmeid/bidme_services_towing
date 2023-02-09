<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api as Controller;
use App\Models\Tbl_bidding;
use App\Models\Tbl_order;
use App\Models\Tbl_user_mitra;
use App\Models\Tbl_feedback;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Bidding extends Controller
{


	public function index(request $request){
		
		
		$validator = Validator::make($request->all(), [
			'orderId' => 'required',
        ]);
		
		if($validator->fails()){
            return $this->sendResponseError(json_encode($validator->errors()), $validator->errors());       
        }
		
		$order =  Tbl_order::where('id', $request->input('orderId'))->first();
		
		if($order->orderStatus == 'process'){ 
			if($this->checkingBid($order->orderDate, $order->orderTime) == false){
				
				
				
				Tbl_order::where('id', $request->orderId)
						->where('orderStatus', 'process')
						->update(['orderStatus'  => 'failed']);
			
				$message 	= 'Kami tidak dapat menemukan mitra towing untuk anda, silahkan lakukan order kembali';
				return $this->sendResponseError($message, '',203);
			}
		}
		
        $bidding = Tbl_bidding::whereRaw('orderId ='. $request->input('orderId'))
					->whereRaw('bidStatus <> 2')
					->get();
		 
		if((is_null($bidding)) OR ($bidding->count() == 0)){
			$message 	= 'Your request couldn`t be found';
			return $this->sendResponseError($message, '',202);
		}
		
		$result = array();
		foreach($bidding as $key=>$val){
			$result[$key] = $val;
			$result[$key]['url_img'] = url('rontend/img/towing-3.jpg');
			$result[$key]['mitra'] = Tbl_user_mitra::select('namaUsaha', 'alamatUsaha')->find($val->mitraId);
			$result[$key]['rating']	= $this->UserRating($mitraId);
		};
								
		return $this->sendResponseOk($result);
	}
	
	public function UserRating($mitraId){
		
		$average_rating = 0;
		$total_review = 0;
		$five_star_review = 0;
		$four_star_review = 0;
		$three_star_review = 0;
		$two_star_review = 0;
		$one_star_review = 0;
		$total_user_rating = 0;
		$review_content = array();
	
		$feedbacks = Tbl_feedback::where('mitraId', $mitraId)->get();
		
		foreach($feedbacks as $row)
		{
			$review_content[] = array(
			'userName'		=>	$row["userName"],
			'review'		=>	$row["review"],
			'rating'		=>	$row["rating"],
			 
			);

			if($row["rating"] == '5')
			{
				$five_star_review++;
			}

			if($row["rating"] == '4')
			{
				$four_star_review++;
			}

			if($row["rating"] == '3')
			{
				$three_star_review++;
			}

			if($row["rating"] == '2')
			{
				$two_star_review++;
			}

			if($row["rating"] == '1')
			{
				$one_star_review++;
			}

			$total_review++;

			$total_user_rating = $total_user_rating + $row["rating"];
		}
		
		$average_rating = $total_user_rating / $total_review;
		
		$output = array(
		'average_rating'	=>	number_format($average_rating, 1),
		'total_review'		=>	$total_review,
		'five_star_review'	=>	$five_star_review,
		'four_star_review'	=>	$four_star_review,
		'three_star_review'	=>	$three_star_review,
		'two_star_review'	=>	$two_star_review,
		'one_star_review'	=>	$one_star_review,
		'review_data'		=>	$review_content
		);
		
		return $output;
	}
	
	
}