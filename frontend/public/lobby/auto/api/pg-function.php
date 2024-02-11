<?php

function KickJokerPlayer($v_member_login)
{
  global $mysqli;

  date_default_timezone_set("Asia/Bangkok");
  $sql = "SELECT * FROM m_providers WHERE provider_code='jks'";
  $res = $mysqli->query($sql)->fetch_assoc();
  $jkConfig = json_decode($res['config_string'], true);
  $apiURL = $jkConfig['api_url'] . '/sign-out';
  $appID = $jkConfig['app_id'];
  $secretKey = $jkConfig['secret'];
  $userName = $v_member_login;
  $date = new DateTime();
  $timeStamp = $date->getTimestamp();

  $array = array(
    "Timestamp" => $timeStamp,
    "AppID" => $appID,
    "Username" => $userName
  );

  $array = array_filter($array);
  $array = array_change_key_case($array, CASE_LOWER);
  ksort($array);

  $rawData = '';
  foreach ($array as $Key => $Value)
    $rawData .=  $Key . '=' . $Value . '&';

  $rawData = substr($rawData, 0, -1);
  $rawData .= $secretKey;
  $hash = md5($rawData);
  // var_dump('Hash : ' . $hash);
  // exit();

  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/json'
    ]
  ]);

  $arr = array('AppID' => $appID, 'Username' => $userName, 'Hash' => $hash, 'Timestamp' => $timeStamp);
  $arrBody['body'] = json_encode($arr);
  $res = $client->request('POST', $apiURL, $arrBody);  //<<<<<<<<<<<<<<<
  $res = json_decode($res->getBody()->getContents(), true);
  // var_dump($res);
}
