<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();   
require_once './api/fg-function.php'; 


$reqDataArray = $_REQUEST ?? $_POST ?? $_GET ?? NULL;
if (is_null($reqDataArray)) {
  echo json_encode(['errorCode' => 403, 'errorMsg' => 'Invalid parameter(s)']);
  exit();
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
$member_promo = $_SESSION['member_promo'];
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$sql = "SELECT * FROM m_providers WHERE provider_code='fgs'";
$fgs_config = $db->query($sql)->fetchArray();
$fgs_config = json_decode($fgs_config['config_string'], true);

$redirectURL = $site['host'] . '/lobby/fg';
$apiURL = $fgs_config['api_url'];
$createPlayerURL = $apiURL . '/v3/players';
$getLaunchGameURL = $apiURL . '/v3/launch_game';
$merchantName = $fgs_config['merchantname'];
$merchantCode = $fgs_config['merchantcode'];
$prefix = $fgs_config['id'] . '.';
$openID = '';

// Detect the player already exists and get openId
$postData['member_code'] = $prefix . $member_no;
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

$detectPlayerURL = $apiURL . '/v3/player_names/' . $prefix . $member_no;
$res = $client->request('POST', $detectPlayerURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);

if ($res['code'] == 0) {
  // The player exists in FG
  $openID = $res['data']['openid']; 
} else {
  
  // the player doesn't exists in FG so createPlayer
  $resMember = getMemberInfo($member_no);
  // echo $prefix .$member_no;
  // exit();
  $postData = array();

  $postData['member_code'] = $prefix . $member_no;
  $postData['password'] = $resMember['passwordText'];
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

  $res = $client->request('POST', $createPlayerURL, $arr);  //<<<<<<<<<<<<<<<
  $res = json_decode($res->getBody()->getContents(), true);
  $openID = $res['data']['openid']; 
} 
// Ready to lauching the game
$postData = array();
$postData['openid'] = $openID;
$postData['game_code'] = $reqDataArray['gamecode'];
$postData['game_type'] = 'h5';
$postData['language'] = 'en-us';
$postData['ip'] = getIPAddress();
$postData['return_url'] = $redirectURL;

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
// var_dump($postData);
// exit();

$res = $client->request('POST', $getLaunchGameURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true); 
header('Location:' . $res['data']['game_url'] . '&token=' . $res['data']['token']);

 