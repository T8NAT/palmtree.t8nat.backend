<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Notifications\Notification;
class SendFireBaseNotification {

    public static function send_firebase_notifiy($fcm_tokens = [],$notification = [],$data = []){
        $body["registration_ids"] =  $fcm_tokens;
        $body["notification"] =  [
            "title" => $notification['title'] ?: 'Your Title Notification',
            "body"  => $notification['body']  ?: 'Your Body  Notification',
            "sound" => "notification_sound.wav"
        ];

        if(count($data) > 0):
            $body["data"] =  $data;
        endif;

        $response     = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Authorization' => 'key=AAAANS3VdFA:APA91bEEO_6eLd4vGM3QFaUkqUPov_yQ1HgysYwSra0WAu2Dl0lW3EKLnIuCj2wLKKcQBZ7tQyK2FKpqjDlNgZL0eRREm8-homsrQsmZoWEHdYsP2A27s0EbiwtY7Pk9sm9yLU_R3uH5',
            'Content-Type'  => 'application/json'
        ])->post("https://fcm.googleapis.com/fcm/send",$body);

        return $response;
    }
}