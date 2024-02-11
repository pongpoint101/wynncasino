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

$res = $db->query("SELECT * FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง']);
  exit();
}

$encryption = urldecode($_GET['param']);

$arrParam = explode(',',  $encryption);
$userName = $arrParam[0];
$platformID = $arrParam[1];
$gameCode = $arrParam[2];

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
    echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง']);
    exit();
  }
} else {
  echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง']);
  exit();
}

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$platformIDConfig = $db->query($sql, 'auto')->fetchArray();
$platformIDConfig = json_decode($platformIDConfig['config_string'], true);
$apiUrlCreateUser = $platformIDConfig['create_user_url'];
$apiUrlLogin = $platformIDConfig['login_url'];
$opToken = $platformIDConfig['operator_token'];
$secretKey = $platformIDConfig['secret_key'];

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'operator-token' => $opToken,
    'secret-key'    => $secretKey
  ]
]);

// Create User before launch game
$clientIP = getIP();
if (strlen($clientIP) > 15) {
  $clientIP = '127.0.0.1';
}
$arr['body'] = json_encode([
  'userName' => $memberUserName,
  'name' => $memberUserName,
  'registeredIp' => $clientIP,
]);
$response = $client->request('POST', $apiUrlCreateUser, $arr);  //<<<<<<<<<<<<<<<
$response = json_decode($response->getBody()->getContents(), true);

// Launch game
$arr['body'] = json_encode([
  'userName' => $memberUserName,
  'platformId' => (int)$platformID,
  'gameCode' => $gameCode,
  'mobile' => false,
  'ip' => $clientIP,
  'returnUrl' => $platformIDConfig['return_url'],
]);

$response = $client->request('POST', $apiUrlLogin, $arr);  //<<<<<<<<<<<<<<<
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
  // if (strpos($gameURL, 'pgjksjk.com') !== false) {
  if ($platformID == 21) {
    $gameURL = str_replace('language=&', 'language=th&', $gameURL);
  }
}

// header('refresh:0;url=' . $gameURL);
// echo $gameURL;
$gameURL = str_replace("http:", "https:", $gameURL);
$db->query("INSERT INTO game_url_log (provider,game_code,game_url) VALUES (?,?,?)", $platformID, $gameCode, $gameURL);
$db->close();
if ($error == 1) {
  echo "<script>";
  echo "console.log('+" . $gameURL . "+');";
  echo "window.close();";
  echo "</script>";
  exit();
}
header('location:' . $gameURL);
