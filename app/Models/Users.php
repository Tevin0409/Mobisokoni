<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Model
{
    use HasFactory;
    public $table = 'users';
    
      public static function send_push($token,$title,$message,$plateform)
    {
        $appName = env('NOTIFICATION_TITLE');
        $title =     $appName ."- Order ID: ". $title;
        if($plateform == 1)
        {
            $customData =  array("message" =>$message);
            $url = 'https://fcm.googleapis.com/fcm/send';
            $api_key = env('FCM_TOKEN');
       
            $body = $message;
            $notification = array('title' => $title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
            $fields = array('to' => $token, 'notification' => $notification,'priority'=>'high');
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // print_r(json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        }
        else
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $api_key = env('FCM_TOKEN');
            $msg = array ( 'title' => $title, 'body' => $message);
            $message = array(
                "message" => $title,
                "data" => $message,
            );
            $data = array('registration_ids' => array($token));
            $data['data'] = $message;
            $data['notification'] = $msg;
            $data['notification']['sound'] = "default";
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            //echo json_encode($data);
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            // print_r($result);
            return $result;
        }
    }
}
