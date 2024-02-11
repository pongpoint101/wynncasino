<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

$db = new DB();
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
  header('refresh:0;url=' . $site['host']);
  exit();
}

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];

$platformID = $_POST['platform_id'];
$gameCode = $_POST['game_code'];
$productType = $_POST['product_type'];
$memberUsername = $_POST['username'];
$token = $_POST['username'];
$memberNo = substr($memberUsername, -7);
$qmSelect = $_POST['qmselect'];

$res = $db->query("SELECT * FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(1)']);
  exit();
}

// // $productType = 0;
if ($productType != '') {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='{$platformID}' AND status=1 AND product_type={$productType} ORDER BY product_type");
} else {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='{$platformID}' AND status=1 ORDER BY product_type LIMIT 1");
}
if ($res->numRows() <= 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(2)']);
  exit();
} else {
  $res = $res->fetchArray();
  if (($res['product_type'] == 4) && $memberPromo > 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'โปรฯ ของท่านไม่สามารถเข้าเล่นได้(3)']);
    exit();
  }
  $lobbyURL = $res['lobby_url'];
}

$res = $db->query("SELECT * FROM game_hit_count WHERE provider=? AND game_code=?", $platformID, $gameCode);
if ($res->numRows() > 0) {
  $db->query("UPDATE game_hit_count SET hit_count=hit_count+1 WHERE provider=? AND game_code=?", $platformID, $gameCode);
} else {
  $db->query("INSERT INTO game_hit_count (provider,game_code,hit_count) VALUES (?,?,?)", $platformID, $gameCode, 1);
}

// $res = $db->query("SELECT * FROM autosystem_game_list WHERE platformId=? AND gameCode=?", 56, $gameCode);
// if ($res->numRows() > 0) {
//   $res = $res->fetchArray();
//   $res = $db->query("SELECT * FROM autosystem_gamelist WHERE platformId=? AND game_id=? AND isActive=0", 56, $res['id']);
//   if ($res->numRows() > 0) {
//     echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (A)']);
//     exit();
//   }
// } else {
//   echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (B)']);
//   exit();
// }

$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$qmConfig = $db->query($sql, strtolower($qmSelect))->fetchArray();
$qmConfig = json_decode($qmConfig['config_string'], true);
// file_put_contents('authen-rt.log', 'RT Config : ' . json_encode($qmConfig) . PHP_EOL, FILE_APPEND);

$mobile = 0;
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  $mobile = 1;
}
// Player Authorization
$headers = [
  'exceptions'       => false,
  'verify'           => false,
  'headers' => [
    'Content-Type'   => 'application/json',
    'Accept'   => 'application/json',
    'X-QM-ClientId' => $qmConfig['client_id'],
    'X-QM-ClientSecret' => $qmConfig['client_secret']
  ]
];
$client = new GuzzleHttp\Client($headers);
$arr['body'] = json_encode([
  'ipaddress' => getIP(),
  'username' => $memberUsername,
  'userid' => $memberUsername,
  'lang' => $qmConfig['language'],
  'cur' => $qmConfig['currency'],
  'betlimitid' => $qmConfig['bet_limit'],
  'istestplayer' => false,
  'platformtype' => $mobile
]);

$targetURL = $qmConfig['api_domain'] . "/api/player/authorize";
$isErr = 0;
try {
  $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
  $response = json_decode($res->getBody()->getContents(), true);
} catch (Exception $e) {
  $isErr = 1;
  $return = json_encode(['error' => $isErr, 'gameURL' => $e->getMessage()]);
  echo $return;
  exit();
}
if ($isErr == 0) {
  if (array_key_exists('err', $response)) {
    $isErr = 2;
    $return = json_encode(['error' => $isErr, 'gameURL' => $response['errdesc']]);
    echo $return;
    exit();
  }
}

$authToken = $response['authtoken'];
$uuID = genUUID();
$qmSelect = ($qmSelect == 'KM') ? 'KMQM' : 'RT';
$queryParam = "gpcode=$qmSelect&gcode=$gameCode&token=$authToken&lang=" . $qmConfig['language'];
$gameURL = $qmConfig['game_domain'] . "/gamelauncher?" . $queryParam;
$arrLaunch['url'] = $gameURL;
$arrLaunch['dt'] = date('Y-m-d H:i:s');
$_SESSION[$uuID] = json_encode($arrLaunch);
$db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
// $gameURL = './launch?token=' . $uuID;
// file_put_contents('authen-rt.log', 'URL : ' . $gameURL . PHP_EOL, FILE_APPEND);
$return = json_encode(['error' => 0, 'gameURL' => $gameURL]);
echo $return;
