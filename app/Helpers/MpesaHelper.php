<?php


namespace App\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MpesaHelper
{

    private $short_code=633590;
    private $passkey='de7b459bda15459ca60511171b2b1bd2ea54d0cf0c2de7423cbdc9fcb9fb7741';
    private $key = "fYpvGmQ67rliegU9BcjRAIIqZU3jgMRZ";
    private $secret = "9rC9MQXERx7oPFXS";

    private $_prod_urls=[
        'oauth'=>'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
        'stk_push'=>'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
        'B2B'=>'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest',
        'balance'=>'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query'
    ];

    private $initiator = "zuru";
    private $password = "6122Z6122";

    public function oAuth()
    {
        $credentials = base64_encode($this->key . ':' . $this->secret);
        $headers = [
            'Authorization'=>'Basic ' . $credentials
        ];
        $client = new Client();
        try{
            $response = $client->get($this->_prod_urls['oauth'], [
                'headers' => $headers,
            ]);
            $res=$response->getBody()->getContents();

        }catch (RequestException  $e) {
            $res=$e->getMessage();
        }
        return $res;
    }

    public function getToken(){

        if (Cache::has('mpesa_token')) {
            return Cache::get('mpesa_token');
        }else{
            $array=json_decode($this->oAuth());
            $token=$array->access_token;
            Cache::put('mpesa_token', $token, 3500); // 10 Minutes
            return $token;
        }

    }

    public function preFormatNumber($number){

        /*
         * perform phone number modifications depending on user or your database
         */
        /*
         * check if the number is 07...
         * check if the number is 254...
         * check if the number is +254...
         * check if the number is 7...
         */
        if(preg_match("/^(\+2547)\d{8}$/",$number)){
            return ltrim($number,"+");
        }elseif (preg_match("/^(2547)\d{8}$/",$number)){
            return $number;
        }elseif (preg_match("/^7\d{8}$/",$number)){
            return "254" . $number;
        }elseif (preg_match("/^07\d{8}$/",$number)){
            return "254" .  ltrim($number,"0");
        }

        throw new MpesaException("Invalid phone number");

    }


    public function performStkPush($number,$amt,$ref){

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization'=>'Bearer ' . $this->getToken()
        ];
        $time=Carbon::now()->format('YmdHis');
        $password=base64_encode($this->short_code . $this->passkey . $time);
        $body=[
            'BusinessShortCode'=>$this->short_code,
            'Password'=>$password,
            'Timestamp'=>$time,
            "TransactionType"=>"CustomerPayBillOnline",
            "Amount"=> $amt,
            "PartyA"=> $this->preFormatNumber($number),
            "PartyB"=> $this->short_code,
            "PhoneNumber"=> $this->preFormatNumber($number),
            "CallBackURL"=> "https://lipia.co.ke/stk_result",
            "AccountReference"=> $ref,
            "TransactionDesc"=> "desc"
        ];
        $client = new Client(['headers' =>$headers]);
        try{
            $response = $client->post($this->_prod_urls['stk_push'], ['body' => json_encode($body)]);
            $res=$response->getBody()->getContents();
        }catch (RequestException  $e) {
            $res=$e->getMessage();
        }
        return $res;

    }





}