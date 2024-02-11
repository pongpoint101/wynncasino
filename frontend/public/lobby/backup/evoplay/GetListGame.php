<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php'; 
$db = new DB(); 
require_once './api/evoplay-function.php'; 

$site = GetWebSites();  
$sql = "SELECT * FROM m_providers WHERE site_id=? AND operation_mode=? AND provider_code=?";
$res = $db->query($sql, 66, 1, "evp")->fetchArray();
$evo_config = json_decode($res['config_string'], true);

$api_url = $evo_config['api_url'] . '/Game/getList'; // https://api.8provider.com/Game/getList';
$system_id = $evo_config['system_id'];
$evo_key = $evo_config['key'];

$arr_signature = array();
$signature = getSignature($system_id, 1, $arr_signature, $evo_key);

$arr['form_params']['project'] = $system_id;
$arr['form_params']['version'] = 1;
$arr['form_params']['signature'] = $signature;
// $arr['form_params']['need_extra_data'] = 1;

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json'
  ]
]);

$res = $client->request('POST', $api_url, $arr);  //<<<<<<<<<<<<<<<

$res = json_decode($res->getBody()->getContents(), true);

if (count($res['data']) <= 0) {
  echo "Error : No list games (0)";
  exit();
}

echo "List games : " . count($res['data']);
$db->query("TRUNCATE `evp_list_games`");
foreach ($res['data'] as $key => $value) { 
  $sql = "INSERT INTO evp_list_games (game_id,type_id,game_sub_type,details)";
  $sql .= " VALUES (?,?,?,?)";
  $db->query(
    $sql,
    $key,
    $value['type_id'],
    $value['game_sub_type'],
    json_encode($value)
  );
}
