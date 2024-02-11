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
// $platformID = 21;
$gameCode = $_POST['game_code'];
$productType = $_POST['product_type'];
$memberUsername = $_POST['username'];
$product = $_POST['product'];
// $memberUsername = $site['site_id'] . '_' . $memberNo;

$res = $db->query("SELECT * FROM members WHERE id=?", $memberNo)->fetchArray();
if ($res['status'] != 1) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(A)']);
  exit();
}

$passwordText = $res['passwordText'];

// // $productType = 0;
if ($productType != '') {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 AND product_type=? ORDER BY product_type", $platformID, $productType);
} else {
  $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 ORDER BY product_type LIMIT 1", $platformID);
}

if ($res->numRows() <= 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(B)']);
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

// $res = $db->query("SELECT * FROM autosystem_game_list WHERE platformId=? AND gameCode=?", $platformID, $gameCode);
$res = $db->query("SELECT * FROM autosystem_game_list WHERE platformId=? AND gameCode=?", 21, $gameCode);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  // $res = $db->query("SELECT * FROM autosystem_gamelist WHERE platformId=? AND game_id=? AND isActive=0", $platformID, $res['id']);
  $res = $db->query("SELECT * FROM autosystem_gamelist WHERE platformId=? AND game_id=? AND isActive=0", 21, $res['id']);
  if ($res->numRows() > 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (A)']);
    exit();
  }
} else {
  echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (B)']);
  exit();
}

$mobile = false;
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  $mobile = true;
}

// Login
$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$providerConfig = $db->query($sql, 'pgv2')->fetchArray();
$providerConfig = json_decode($providerConfig['config_string'], true);
$strDecode = $providerConfig['username'] . ':' . $providerConfig['secret_key'] . ':' . $providerConfig['hash'];
// $strDecode = $providerConfig['username'] . ':' . $providerConfig['secret_key'];
$strEncode = base64_encode($strDecode);
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'Authorization' => 'Basic ' . $strEncode
  ]
]);
$arr['body'] = json_encode([
  'username' => strtolower($memberUsername),
  'productCode' => $product,
  'gameCode' => $gameCode,
  'language' => 'th',
  // 'isMobileLogin' => $mobile,
  'sessionToken' => strtolower($memberUsername),
  // 'betLimit' => [],
]);

$targetURL = $providerConfig['api_endpoint'] . '/PartnerAPI/v1/seamless/login';
$res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
$response =  $res->getBody()->getContents();
$arrRes = json_decode($response, true);
$errCode = $arrRes['code'];
if ($errCode == 0) {
  $gameURL = $arrRes['data']['url'];
  $uuID = genUUID();
  $arrLaunch['url'] = $gameURL;
  $arrLaunch['dt'] = date('Y-m-d H:i:s');
  $_SESSION[$uuID] = json_encode($arrLaunch);
  $db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
  $gameURL = './launch?token=' . $uuID;
} else {
  $gameURL = $arrRes['message'];
}
echo json_encode(['error' => $errCode, 'gameURL' => $gameURL]);
