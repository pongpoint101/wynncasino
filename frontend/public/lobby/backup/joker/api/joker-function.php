<?php

function GetListGame()
{
  global $db;

  $sql = "SELECT * FROM m_providers WHERE site_id=? AND operation_mode=? AND provider_code=?";
  $res = $db->query($sql, 66, 1, "jks")->fetchArray();
  $joker_config = json_decode($res['config_string'], true);

  $url = $joker_config['api_url'] . '/list-games'; // 'http://api688.net/seamless/list-games';
  $appID = $joker_config['app_id']; // 'Your AppID';
  $secretKey = $joker_config['secret'];   // 'Your SecretKey';

  $seconds = round((microtime(true) * 1000));

  $array = array(
    "Timestamp" => $seconds,
    "AppID" => $appID,
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

  $postData = $array;
  $postData['hash'] = $hash;


  //Encode the array into JSON.
  $jsonDataEncoded = json_encode($postData);

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

  $data = curl_exec($ch);
  curl_close($ch);

  // return $data;
  // var_dump($data);
  echo $data;
}
