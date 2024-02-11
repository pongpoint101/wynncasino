<?php
require_once '../../bootstart.php';
require_once '../../../core/security.php';
require_once '../../../core/db2/db.php';
require_once '../lobby-function.php';

header("Content-type: application/json; charset=utf-8");

$site = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
  header("location: " . GetFullDomain() . '/login');
  exit(0);
}
$memberNo = $_SESSION['member_no'];
$memberPromo = $_SESSION['member_promo'];
$memberUserName = $_POST['username'];
$gameID = $_POST['game_code'];
$platformID = $_POST['platform_id'];

if (SkipSlot($memberPromo)) {
  return;
}
$db = new DB();
$qtConfig = getProviderConfig('qtp', 66, 1);

// file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' : ' . json_encode($qtConfig, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

$headers = [
  'exceptions'       => false,
  'verify'           => false,
  'headers' => [
    'Content-Type'   => 'application/json',
    'Authorization' => 'Bearer ' . $qtConfig['passkey']
  ]
];
$client = new GuzzleHttp\Client($headers);
$targetURL = $qtConfig['api_server'] . "/v1/auth/token?grant_type=password";
$targetURL .= "&response_type=token&username=" . $qtConfig['username'] . "&password=" . $qtConfig['password'];
$res = $client->request('GET', $targetURL);  //<<<<<<<<<<<<<<<
$res =  json_decode($res->getBody()->getContents());
$accessToken = $res->access_token;

// file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' accessToken : ' . $accessToken . PHP_EOL, FILE_APPEND);

// Get Launch Game URL
$device = 'desktop';
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  $device = 'mobile';
}
$sql = "SELECT lobby_url FROM lobby_control WHERE provider_id=?";
$res = $db->query($sql, $platformID)->fetchArray();
$returnURL = $site['host'] . $res['lobby_url'];

$headers['headers']['Authorization'] = 'Bearer ' . $accessToken;
$client = new GuzzleHttp\Client($headers);
$targetURL = $qtConfig['api_server'] . "/v1/games/$gameID/launch-url";
// $targetURL = $qtConfig['api_server'] . "/v1/games/lobby-url";
$arr['body'] = json_encode([
  'playerId' => $memberUserName,
  'displayName' => $memberUserName,
  'currency' => $qtConfig['currency'],
  'country' => $qtConfig['country'],
  'lang' => str_replace('-', '_', $qtConfig['language']),
  'mode' => 'real',
  'device' => $device,
  'returnUrl' => $returnURL,
  'walletSessionId' => $memberUserName,
  'betLimitCode' => $qtConfig['bet_limit'],
  // 'ipAddress' => getIP()
]);
// file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' target URL : ' . $targetURL . PHP_EOL, FILE_APPEND);
// file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' headers : ' . json_encode($headers, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
// file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' Payload : ' . $arr['body'] . PHP_EOL, FILE_APPEND);

try {
  $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
  $res =  json_decode($res->getBody()->getContents());
  // file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' res : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
} catch (Exception $e) {
  file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' req ERROR : ' . $arr['body'] . PHP_EOL, FILE_APPEND);
  file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' res ERROR : ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
  echo json_encode(['error' => 1, 'gameURL' => $e->getMessage()]);
  exit();
}

if (array_key_exists('url', $res)) {
  $errCode = 0;
  $uuID = genUUID();
  $gameURL = $res->url;
  $arrLaunch['url'] = $gameURL;
  $arrLaunch['dt'] = date('Y-m-d H:i:s');
  $_SESSION[$uuID] = json_encode($arrLaunch);
  $db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
  $gameURL = './launch?token=' . $uuID;
  // file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' gameURL : ' . $gameURL . PHP_EOL, FILE_APPEND);
} else {
  file_put_contents('qt-launch.log', date('Y-m-d H:i:s') . ' launch URL - Error : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
  $errCode = 1;
  $gameURL = 'error : ' . $res->code . PHP_EOL . 'message : ' . $res->message;
}
echo json_encode(['error' => $errCode, 'gameURL' => $gameURL]);
