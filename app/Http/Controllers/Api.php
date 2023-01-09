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
	
	public function biayaApp()
    {
        
        return 20000;
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
	
	public function sendNotification($users, $details)
    {
        //firebaseToken berisi seluruh user yang memiliki device_token. jadi notifnya akan dikirmkan ke semua user
        //jika kalian ingin mengirim notif ke user tertentu batasi query dibawah ini, bisa berdasarkan id atau kondisi tertentu
	
        //$firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $firebaseToken = $users->device_token;

        $SERVER_API_KEY = 'AAAAWRP69yA:APA91bEe2uoSYF_w2i3e1aUf0cb30HlQerVyBsd41hNkLaMwchoK9Rlx2e_N5Af347zwmRBCqlrzqsVNHtdbUZVm5_HxSdyuIqgq5sc1HCBZRShtgvnx81SHqBDgpmj_vKyTAwObkOku';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $details['title'],
                "body" => $details['body'],
                "url" => $details['url'],
                "icon" => 'https://mitra.bidme.id/backend/lc_icon.png',
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $response;
       
    }
}
