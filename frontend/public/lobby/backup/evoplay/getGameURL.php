<?php

require '../../bootstart.php'; 

require_once ROOT.'/core/security.php';

require_once ROOT.'/core/db2/db.php'; 

$db = new DB(); 

require_once './api/evoplay-function.php';



$site = GetWebSites(); 

$logged_in = FALSE;



if (!isset($_SESSION['member_no'])) {

  $logged_in = TRUE;

} elseif (empty($_SESSION['member_no'])) {

  $logged_in = TRUE;

} elseif (!isset($_SESSION['member_login'])) {

  $logged_in = TRUE;

} elseif (empty($_SESSION['member_login'])) {

  $logged_in = TRUE;

}



if ($logged_in) {

  session_destroy();

  header('refresh:0;url=../../');

  exit();

}



$member_no = $_SESSION['member_no'];

$member_login = $_SESSION['member_login'];

$game_id = $_POST['gameID'];



if (!isset($game_id)) {

  echo json_encode([

    'status' => 'failed',

    'data' => null,

  ]);

  exit();

}



$sql = "SELECT * FROM m_providers WHERE provider_code=?";

$evo_config = $db->query($sql, 'evp')->fetchArray();

$evo_config = json_decode($evo_config['config_string'], true);



$api_url = $evo_config['api_url'] . '/Game/getURL'; // https://api.8provider.com/Game/getURL';

$system_id = $evo_config['system_id'];

$evo_key = $evo_config['key'];

$lang = 'en';

$currency = 'THB';

$https = 1;



$game_details = $db->query("SELECT * FROM evp_list_games WHERE game_id=?", $game_id)->fetchArray();

$game_details = json_decode($game_details['details'], true);



$arr_signature[0] = $member_no . '-' . $member_login;

$arr_signature[1] = $game_details['absolute_name'];

$arr_signature[2] = $member_no . ':' . $site['host'] . ':' . $site['host'] . ':' . $lang . ':' . $https;

$arr_signature[3] = 1; // Denomination

$arr_signature[4] = $currency; // Currency

$arr_signature[5] = 1; // Return URL Info

$arr_signature[6] = 2; // Callback Version





$signature = getSignature($system_id, 1, $arr_signature, $evo_key);



// echo json_encode($signature);

// exit();



$mobile = 'false';

$detecMob = new Mobile_Detect();

if ($detecMob->isMobile()) {

  $mobile = 'true';

}



$arr['form_params']['project'] = $system_id;

$arr['form_params']['version'] = 1;

$arr['form_params']['signature'] = $signature;

$arr['form_params']['token'] = $member_no . '-' . $member_login;

$arr['form_params']['game'] = $game_details['absolute_name'];

$arr['form_params']['settings'] = array(

  'user_id' => $member_no,

  'exit_url' => $site['host'],

  'cash_url' => $site['host'],

  'language' => $lang,

  'https' => $https,

);

$arr['form_params']['denomination'] = 1;

$arr['form_params']['currency'] = $currency;

$arr['form_params']['return_url_info'] = 1;

$arr['form_params']['callback_version'] = 2;



$api_request = $api_url . '?';

$api_request_setting = '';

foreach ($arr['form_params'] as $key => $value) {

  if ($key == 'settings') {

    foreach ($value as $key1 => $value1) {

      $api_request_setting .= "settings[" . $key1 . "]=" . $value1 . "&";

    }

    $api_request .= $api_request_setting;

  } else {

    $api_request .= $key . '=' . $value . '&';

  }

}

$api_request = substr($api_request, 0, -1);



$client = new GuzzleHttp\Client([

  'exceptions'       => false,

  'verify'           => false,

  'headers'          => [

    'Content-Type'   => 'application/x-www-form-urlencoded'

  ]

]);



$res = $client->request('POST', $api_url, $arr);



$res = json_decode($res->getBody()->getContents(), true);



$status = $res['status'];

if ($status == 'ok') {

  hitsStatistic($game_id, $game_details['name']);

}

$res = $res['data'];

$res['api_request'] = $api_request;

$res['status'] = $status;



echo json_encode($res);

