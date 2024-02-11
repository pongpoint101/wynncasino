<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();   
require_once './api/joker-function.php';

$sql = "SELECT * FROM m_providers WHERE site_id=? AND operation_mode=? AND provider_code=?";
$res = $db->query($sql, 66, 1, "jks")->fetchArray();
$joker_config = json_decode($res['config_string'], true);

$api_url = $joker_config['api_url'] . '/list-games'; // 'http://api688.net/seamless/list-games';
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

$arr['form_params'] = $postData;

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json'
  ]
]);

$res = $client->request('POST', $api_url, $arr);  //<<<<<<<<<<<<<<<

$res = json_decode($res->getBody()->getContents(), true);

if ($res['Error'] != 0) {
  echo "Error : " . $res['Description'];
  exit();
}

if (count($res['ListGames']) <= 0) {
  echo "Error : No list games (0)";
  exit();
}

echo "List games : " . count($res['ListGames']);
$db->query("DELETE FROM jk_list_games");
foreach ($res['ListGames'] as $key => $value) { 
  $sql = "INSERT INTO jk_list_games (game_type, game_code, game_name, game_alias, specials, supported_platforms, game_order, default_width, default_height, image1)";
  $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?)";
  $db->query(
    $sql,
    $value['GameType'],
    $value['GameCode'],
    $value['GameName'],
    $value['GameAlias'],
    $value['Specials'],
    $value['SupportedPlatForms'],
    $value['Order'],
    $value['DefaultWidth'],
    $value['DefaultHeight'],
    $value['Image1']
  );
} 
