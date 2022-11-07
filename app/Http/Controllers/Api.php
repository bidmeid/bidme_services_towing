<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Carbon\Carbon;

class Api extends Controller
{
    //
    public function sendResponseCustom($mssg, $result)
    {
        $response = [
            'success' => true,
            'message' => $mssg,
        ];
        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }

    public function sendResponseOk($result)
    {
        $response = [
            'success' => true,
            'message' => 'Your request has been found',
        ];
        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }

    public function sendResponseCreate($result)
    {
        $response = [
            'success' => true,
            'message' => 'Your request has been saved',
        ];
        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 201);
    }

    public function sendResponseUpdate($result)
    {
        $response = [
            'success' => true,
            'message' => 'Your request has been updated',
        ];
        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 201);
    }

    public function sendResponseDelete($result)
    {
        $response = [
            'success' => true,
            'message' => 'Your request has been deleted',
        ];
        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }

    public function sendResponseError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
	
	public function checkingBid($orderDate, $orderTime){
		
		$dateOrder = $orderDate;
        $timeOrder = $orderTime;
		
		$orderTime =  Carbon::parse($dateOrder.' '.$timeOrder);
		$now =  Carbon::now();
		
		$orderExpired = Carbon::parse($dateOrder.' '.$timeOrder)->addMinutes(25);
		
		$expireMin = $orderExpired->diff($orderTime)->format('%H:%I:%S');
		
		$diffInMinutes = $now->diffInMinutes($orderTime);
		
		if($diffInMinutes > 25){
			return false;
		}else{
			return true;
		}
		
	} 
	
	public function checkingExpired($orderDate, $orderTime){
		
		$dateOrder = $orderDate;
        $timeOrder = $orderTime;
		
		$orderTime =  Carbon::parse($dateOrder.' '.$timeOrder);
		$now =  Carbon::now();
		
		$orderExpired = Carbon::parse($dateOrder.' '.$timeOrder)->addMinutes(25);
		
		$expireMin = $orderExpired->diff($orderTime)->format('%H:%I:%S');
		
		$diffInMinutes = $now->diffInMinutes($orderTime);
		
		return $diffInMinutes;
		
	} 
}
