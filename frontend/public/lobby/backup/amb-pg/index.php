<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';    
require_once ROOT.'/core/db2/dbmodel.php';  
require_once './api/ambp-function.php';

$cfg = getProviderConfig('ambpg', 66, 1);

$member_no = 100001;
$member_promo = 0;

$member_no = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];

$agent = $cfg['agent'];
$password = $cfg['password'];
$key = $cfg['key'];
$api_url = $cfg['api_url'];
$iterations = 1000;
$game_launch_url = $api_url . '/seamless/launch';
$create_member_url = $api_url . '/seamless/create';

// $arrJSON['username'] = strval($member_no);
$arrJSON['username'] = WEBSITE. $member_no;
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
$res = $client->request('POST', $create_member_url, $arr);
$res = json_decode($res->getBody()->getContents(), true);
if ($res['status']['code'] != 0) {
  // header('refresh:0;url=' . $site['host']);
  // var_dump($res);
  if ($res['status']['code'] != 902) {
    exit();
  }
}

// Call /seamless/launch
$res = $client->request('POST', $game_launch_url, $arr);
$res = json_decode($res->getBody()->getContents(), true);
if ($res['status']['code'] != 0) {
  echo $res['status']['message'];
  exit();
}

header("Location:" . $res['data']['url']);
