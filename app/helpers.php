<?php
use Illuminate\Support\Facades\Http;
    function getUser($userId) {
        $url = env('SERVICE_USER_URL').'users/'.$userId;
        try {
            $resp = Http::timeout(10)->get($url);
            $data = $resp->json();
            $data['http_code'] = $resp->getStatusCode();
            return $data;
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'http_code' => 500,
                'message' => 'service user unavailable',
            ];
        }
       
    }

    function getUserByIds($userIds = []) 
    {
        $url = env('SERVICE_USER_URL').'users/';
      
        try {
            if(count($userIds) === 0) {
                return [
                    'status' => 'success',
                    'http_code' => 200,
                    'data' => []
                ];
            } else {
                $resp = Http::timeout(10)->get($url, ['userids[]' => $userIds]);
                $data = $resp->json();
                $data['http_code'] = $resp->getStatusCode();
               
                return $data;
                
            } 
            
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'http_code' => 500,
                'message' => 'service user unavailable',
            ];
          
        }
    }

    function postOrder($params)
    {
        $url = env('SERVICE_ORDER_PAYMENT_URL').'api/orders/';
        try {
            $response = Http::post($url, $params);
            $data = $response->json();
            $data['http_code'] = $response->getStatusCode();
            return $data;
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'http_code' => 500,
                'message' => 'service Order Payment unavailable',
            ];
        }
    }
    
?>