<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();   
require_once './api/fg-function.php';

$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$res = $db->query($sql, "fgs")->fetchArray();
$fgs_config = json_decode($res['config_string'], true);

$apiURL = $fgs_config['api_url'];
$getGameListURL = $apiURL . '/v3/games/game_type/h5/language/en-us/';
$merchantName = $fgs_config['merchantname'];
$merchantCode = $fgs_config['merchantcode'];
$postData['terminal'] = 'h5';
$postData['lang'] = 'en-us';

$arr['form_params'] = $postData;

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Accept'         => 'application/json',
    'merchantname'   => $merchantName,
    'merchantcode'   => $merchantCode
  ]
]);

$res = $client->request('POST', $getGameListURL, $arr);  //<<<<<<<<<<<<<<<

$res = json_decode($res->getBody()->getContents(), true);

if ($res['code'] != 0) { 
  // echo "Message : " . $res['message'];
  exit();
}

if (count($res['data']) <= 0) {
  echo "Error : No list games (0)";
  exit();
}

echo "List games : " . count($res['data']);
$db->query("DELETE FROM fg_list_games");
foreach ($res['data'] as $key => $value) { 
  $sql = "INSERT INTO fg_list_games (app_special_img, big_img, categoryid, game_url, gamecode, gt, img, ishot, isnew, isrecommend, name, server_ip, server_port, service_id, small_img, sort)";
  $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $db->query(
    $sql,
    $value['app_special_img'],
    $value['big_img'],
    $value['categoryid'],
    $value['game_url'],
    $value['gamecode'],
    $value['gt'],
    $value['img'],
    $value['ishot'],
    $value['isnew'],
    $value['isrecommend'],
    $value['name'],
    $value['server_ip'],
    $value['server_port'],
    $value['service_id'],
    $value['small_img'],
    $value['sort']
  );
} 