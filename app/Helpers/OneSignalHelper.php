<?php


namespace App\Helpers;


class OneSignalHelper
{
    function sendMessage($data,$filters){
        $content = array(
            "en" => 'English Message'
        );

        $fields = array(
            'app_id' => "83c1b15c-88b5-44f7-ad68-c6aaf593d5ac",
            'filters' => $filters,
            'data' => $data,
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic YTYyMjgxNzQtNzcxOC00NTQwLWFjMjMtY2E4MWUzZmE2NDEz'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}