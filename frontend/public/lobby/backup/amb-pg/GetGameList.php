<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';    
require_once ROOT.'/core/db2/dbmodel.php';  
require_once './api/ambp-function.php';
 
$cfg = getProviderConfig('ambpg', 66, 1);
$member_no = $_SESSION['member_no']; 
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';   

$agent = $cfg['agent'];
$password = $cfg['password'];
$key = $cfg['key'];
$api_url = $cfg['api_url'];
// $secret = $cfg['secret'];
$iterations = 1000;
$get_games_url = $api_url . '/seamless/games';
$create_member_url = $api_url . '/seamless/create';

$arrJSON['username'] = strval($member_no);
$arrJSON['password'] = $password;
$arrJSON['agent'] = $agent;
$arrJSON = json_encode($arrJSON);
$x_amb_signature = base64_encode(hash_pbkdf2('sha512', $arrJSON, $key, $iterations, 64, true));

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'x-amb-signature' => $x_amb_signature
  ]
]);
$arr['body'] = $arrJSON; // <<<<<<<<<<<<<<<<<<<<<<<<<

// Call /seamless/create
// $res = $client->request('POST', $create_member_url, $arr);
// $res = json_decode($res->getBody()->getContents(), true);
// if (($res['status']['code'] != 0) && ($res['status']['code'] != 902)) {
//   exit();
// }

// Call /seamless/games
$res = $client->request('POST', $get_games_url, $arr);
$res = json_decode($res->getBody()->getContents(), true);
if ($res['status']['code'] != 0) {
  var_dump($res);
  exit();
}

echo json_encode($res);
// header("Location:" . $res['data']['url']);
