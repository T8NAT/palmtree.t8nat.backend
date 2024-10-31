<?php namespace App\Services;

use Illuminate\Support\Facades\Http;
class SmsService {
    static $endpoint = "https://api.twilio.com/2010-04-01/Accounts/";
    public static function send_sms($to = null,$body){
        $to = self::validate_phone_number($to);
        if($to == null) return __('validation.PhoneNumber_not_validate');
        $response = Http::withOptions([
            'verify' => false
        ])->withBasicAuth(
            env('TWILIO_ACCOUNT_SID'),env('TWILIO_TOKEN')
        )->asForm()->post(self::$endpoint.env('TWILIO_ACCOUNT_SID').'/Messages.json',[
            'To'   => strval("+966".$to),
            'From' => env('TWILIO_SENDER'),
            'Body' => $body
        ]);

        $result = $response->json();
        if($result['status'] == 'queued'):
            $result['status'] = 'pending';
        endif;

        return $result;
    }

    public static function validate_phone_number($phone){
        return self::filter_validate_mobile($phone);
    }

    public static function filter_validate_mobile($phone){
        if(!$phone) return null;
        $check_if_country_key = substr($phone,0,3);
        if($check_if_country_key != '966'):
            return $phone;
        endif;
        return substr($phone,3);
    }
}