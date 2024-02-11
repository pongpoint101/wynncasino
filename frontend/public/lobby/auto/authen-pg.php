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
$memberUserName = $site['site_id'] . $memberNo;

$platformID = $_POST['platform_id'];
$gameCode = $_POST['game_code'];
$productType = $_POST['product_type'];
$ops = $_POST['username'];

$res = $db->query("SELECT * FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง']);
  exit();
}

// // $productType = 0;
if ($productType != '') {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='" . $platformID . "' AND status=1 AND product_type=" . $productType . " ORDER BY product_type");
} else {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='" . $platformID . "' AND status=1 ORDER BY product_type LIMIT 1");
}
if ($res->numRows() <= 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง']);
  exit();
} else {
  $res = $res->fetchArray();
  if (($res['product_type'] == 4) && $memberPromo > 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'โปรฯ ของท่านไม่สามารถเข้าเล่นได้']);
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

$res = $db->query("SELECT * FROM autosystem_game_list WHERE platformId=? AND gameCode=?", $platformID, $gameCode);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  $res = $db->query("SELECT * FROM autosystem_gamelist WHERE platformId=? AND game_id=? AND isActive=0", $platformID, $res['id']);
  if ($res->numRows() > 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (A)']);
    exit();
  }
} else {
  echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (B)']);
  exit();
}

$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$PGConfig = $db->query($sql, 'pgs')->fetchArray();
$PGConfig = json_decode($PGConfig['config_string'], true);
// $gameURL = $PGConfig['launch_url'];
// $gameURL = str_replace('{game_code}', $gameCode, $gameURL);
// $gameURL = str_replace('{0}', 'th', $gameURL);   // language
// $gameURL = str_replace('{1}', '1', $gameURL);     // bet_type
// $gameURL = str_replace('{2}', $PGConfig['operator_token'], $gameURL);
// $gameURL = str_replace('{3}', $ops, $gameURL);
// $gameURL .= '&lt=1';

// GetLaunchURLHTML
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/x-www-form-urlencoded',
    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    'Pragma' => 'no-cache'
  ]
]);

$traceID = genUUID();
$url = str_replace('/external', '', $PGConfig['api_domain']);
$url .= '/external-game-launcher/api/v1/GetLaunchURLHTML?trace_id=' . urlencode($traceID);
$extra = urlencode("btt=1&l=th&ops=$ops");
$path = "/$gameCode/index.html";
$arr = [
  'operator_token'  => $PGConfig['operator_token'],
  'path'   => $path,
  'extra_args' => $extra,
  'url_type' => 'game-entry',
  'client_ip' => getIP()
];
$rawData = '';
foreach ($arr as $Key => $Value) {
  $rawData .=  $Key . '=' . $Value . '&';
}
$rawData = substr($rawData, 0, -1);
$arrBody['body'] = $rawData;

try {
  $res = $client->request('POST', $url, $arrBody);  //<<<<<<<<<<<<<<<
  $data = $res->getBody()->getContents();
  if (!file_exists(__DIR__ . '/pg-launch')) {
    mkdir(__DIR__ . '/pg-launch', 0777, true);
  }
  $fileName = $traceID . '.html';
  file_put_contents('./pg-launch/' . $fileName, $data);
  $gameURL = 'https://' . $site['brand_name_url'] . '/lobby/auto/pg-launch/' . $fileName;
  $errCode = 0;
  $arrLaunch['url'] = $gameURL;
  $arrLaunch['dt'] = date('Y-m-d H:i:s');
  $_SESSION[$traceID] = json_encode($arrLaunch);
  $db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $traceID, $gameURL);
  $gameURL = './launch?token=' . $traceID;

  echo json_encode(['error' => 0, 'gameURL' =>  $gameURL]);
} catch (Exception $e) {
  file_put_contents('pg-url.log', 'Error : ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
  echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (C)']);
}
