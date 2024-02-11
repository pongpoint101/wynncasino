<?php
require_once '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
require_once '../lobby-function.php';

header("Content-type: application/json; charset=utf-8");
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json'
  ]
]);

$site = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
  header("location: " . GetFullDomain() . '/login');
  exit(0);
}
$memberNo = $_SESSION['member_no'];
$memberPromo = $_SESSION['member_promo'];
$memberUserName = $site['site_id'] . $memberNo;

if (SkipSlot($memberPromo)) {
  return;
}

$db = new DB();

$platformId = 129;
$categoryID = 2;

$res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND product_type=? AND status=1", $platformId, $categoryID);
if ($res->numRows() <= 0 || $platformId <= 0) {
  echo "Error : 1007<BR>Message : Provider under maintenance<BR>";
  header('refresh:3;url=/lobby');
  exit();
}

$dgConfig = getProviderConfig('dgc', 66, 1);
$apiURLList = explode(',', $dgConfig['api_server']);
$apiURL = $apiURLList[rand(0, (count($apiURLList) - 1))];
$randStr = RandomString(16);
$agentAccount = $dgConfig['agent_account'];
$agentName = $dgConfig['agent_name'];
$apiKey = $dgConfig['api_key'];
$md5Str = md5($agentAccount . $apiKey . $randStr);
$minBet = $dgConfig['min_bet'];
$maxBet = $dgConfig['max_bet'];
$limitGroup = $dgConfig['limit_group'];
$currency = $dgConfig['currency'];
$winLimit = $dgConfig['win_limit_per_day'];

$sql = "SELECT * FROM members WHERE id=$memberNo";
$res = $db->query($sql);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  $password = md5($res['passwordText']);
} else {
  // file_put_contents('dg.log', date('Y-m-d H:i:s') . ' : User does not exists' . PHP_EOL, FILE_APPEND);
  echo "Error : 1007<BR>Message : User does not exists<BR>";
  header('refresh:3;url=/lobby');
  exit();
}

// Register New member
$apiURL_SignUp = $apiURL . "/user/signup/$agentAccount";
$arr['body'] = json_encode([
  'token' => $md5Str,
  'random' => $randStr,
  'data' => $limitGroup,
  'member' => [
    'username' => $site['site_id'] . $memberNo,
    'password' => $password,
    'currencyName' => $currency,
    'winLimit' => $winLimit
  ]
]);

$res = $client->request('POST', $apiURL_SignUp, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents());
if ($res->codeId != 0 && $res->codeId != 116) {
  // file_put_contents('dg.log', date('Y-m-d H:i:s') . ' : ' . json_encode($res) . PHP_EOL, FILE_APPEND);
  echo "Error : $res->codeId<BR>Message : Provider under maintenance<BR>";
  echo 'Message : ' . json_encode($res) . '<br>';
  exit();
}


// Modify Member Info
$apiURL_MemberLogin = $apiURL . "/user/update/$agentAccount";
$arr['body'] = json_encode([
  'token' => $md5Str,
  'random' => $randStr,
  'member' => [
    'username' => $site['site_id'] . $memberNo,
    'password' => $password,
    'winLimit' => (float)$dgConfig['win_limit_per_day'],
    'status' => 1
  ]
]);
$res = $client->request('POST', $apiURL_MemberLogin, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents());
// file_put_contents('dg-modify-member-info.log', date('Y-m-d H:i:s') . ' : ' . json_encode($arr['body'], JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
// file_put_contents('dg-modify-member-info.log', date('Y-m-d H:i:s') . ' : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

// Member login
$apiURL_MemberLogin = $apiURL . "/user/login/$agentAccount";
$arr['body'] = json_encode([
  'token' => $md5Str,
  'random' => $randStr,
  'lang' => 'th',
  'domains' => 1,
  'member' => [
    'username' => $site['site_id'] . $memberNo,
    'password' => $password
  ]
]);

$res = $client->request('POST', $apiURL_MemberLogin, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents());
if ($res->codeId == 0) {
  $gameURL = str_replace('"', '', explode(',', $res->list[0])[0]) . $res->token;
  // // file_put_contents('dg-gameURL.log', date('Y-m-d H:i:s') . ' : ' . $gameURL . PHP_EOL, FILE_APPEND);
  header('Location:' . $gameURL);
} else {
  // file_put_contents('dg-error.log', json_encode($res) . PHP_EOL, FILE_APPEND);
  echo "Error : 1009<BR>Message : Provider under maintenance<BR>";
  echo 'Message : ' . json_encode($res) . '<br>';
  header('refresh:3;url=/lobby');
}
