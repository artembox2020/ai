<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;

class Telegram extends BaseObject
{
    public static $token = /*"6533662233:AAHbg_MmUrQhqFYn8F4dEvTWo6ouz01bkvI";*/"5963564090:AAG4bPPH30w3spB4KON05Tb-Jmz0a6KK8Zs";

    public static function getMe($token)
    {
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.telegram.org/bot{$token}/getMe",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "User-Agent: Telegram Bot SDK - (https://github.com/irazasyed/telegram-bot-sdk)",
    "accept: application/json"
  ],
]);

$response = curl_exec($curl);
//$err = curl_error($curl);

curl_close($curl);

return $response;
    }

    public static function sendMessage($msg, $chatId)
    {
    $token = self::$token;
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.telegram.org/bot{$token}/sendMessage",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"chat_id\":\"". $chatId . "\", \"parse_mode\":\"". "HTML" . "\", \"text\":\"" . str_replace('"', "'", $msg) . "\",\"disable_web_page_preview\":false,\"disable_notification\":false,\"reply_to_message_id\":null}",
  CURLOPT_HTTPHEADER => [
    "User-Agent: Telegram Bot SDK - (https://github.com/irazasyed/telegram-bot-sdk)",
    "accept: application/json",
    "content-type: application/json"
  ],
]);

    $response = curl_exec($curl);

    return $response;
    }
}
