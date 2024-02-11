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

$res = $db->query("SELECT * FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง']);
  exit();
}

// $productType = 0;
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

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$platformConfig = $db->query($sql, 'auto')->fetchArray();
$platformConfig = json_decode($platformConfig['config_string'], true);
$apiUrlCreateUser = $platformConfig['create_user_url'];
$apiUrlLogin = $platformConfig['login_url'];
$opToken = $platformConfig['operator_token'];
$secretKey = $platformConfig['secret_key'];

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'operator-token' => $opToken,
    'secret-key'    => $secretKey
  ]
]);

// Launch game
$clientIP = getIP();
if (strlen($clientIP) > 15) {
  $clientIP = '127.0.0.1';
}
$arr['body'] = json_encode([
  'userName' => $memberUserName,
  'platformId' => (int)$platformID,
  'gameCode' => $gameCode,
  'mobile' => false,
  'ip' => $clientIP,
  'lang' => 'th',
  'returnUrl' => $platformConfig['return_url'],
]);

$response = $client->request('POST', $apiUrlLogin, $arr);
$response = json_decode($response->getBody()->getContents(), true);

$error = 0;
$gameURL = '';
if (array_key_exists('data', $response)) {
  if (!array_key_exists('gameUrl', $response['data'])) {
    $error = 1;
  }
} else {
  $error = 1;
  $gameURL = $response['code'] . ' : ' . $response['msg'];
}
if ($error == 0) {
  $gameURL = $response['data']['gameUrl'];
  if ($platformID == 21) {
    $gameURL = str_replace('language=&', 'language=th&', $gameURL);
  }
  $uuID = genUUID();
  $arrLaunch['url'] = $gameURL;
  $arrLaunch['dt'] = date('Y-m-d H:i:s');
  $_SESSION[$uuID] = json_encode($arrLaunch);
  $db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
  $gameURL = './launch?token=' . $uuID;
}
echo json_encode(['error' => $error, 'gameURL' => $gameURL]);
