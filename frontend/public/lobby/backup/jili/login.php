<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();  
require_once './api/jili-function.php'; 

$reqData = $_REQUEST ?? $_POST ?? $_GET ?? NULL;
if (!isset($reqData['GameId'])) {
  return;
}

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
  header('refresh:0;url=' . $site['host']);
  exit();
}

$member_no = $_SESSION['member_no'];
$member_login = $_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];

$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$arrConfig =getProviderConfig('jili', 66, 1);
date_default_timezone_set('Etc/GMT+4');
$agentKey = $arrConfig['agent_key'];
$agentId = $arrConfig['agent_id'];
$params = 'Token=' . $member_login . '&GameId=' . $reqData['GameId'] . '&Lang=th-TH&AgentId=' . $agentId;
// $params = 'Token=' . $member_login . '&GameId=80&Lang=th-TH&AgentId=' . $agentId;
$apiURL = $arrConfig['api_url'] . '/singleWallet/LoginWithoutRedirect?' . $params;

$now = date("ymj");
$keyG = md5($now . $agentId . $agentKey);
$Key = RandomString(6) . md5($params . $keyG) . RandomString(6);
 
$arr['form_params'] = array(
  'AgentId' => $agentId,
  'Key' => $Key
);

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/x-www-form-urlencoded'
  ]
]);


$apiURL .= '&Key=' . $Key;
$res = $client->request('POST', $apiURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);
if (is_null($res)) {
  header('refresh:0;url=' . $site['host'] . '/lobby/jili/?type=1');
} elseif (array_key_exists('Data', $res) == false) {
  echo '<script>alert("' . $res['Message'] . '")</script>';
  header('refresh:0;url=' . $site['host'] . '/lobby/jili/?type=1');
  // exit();
} else {
  header('Location:' . $res['Data']);
}
