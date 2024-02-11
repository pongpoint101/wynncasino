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
$memberUsername = $site['site_id'] . '_' . $memberNo;

$platformID = $_POST['platform_id'];
$gameID = $_POST['game_id'];
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

$res = $db->query("SELECT * FROM game_hit_count WHERE provider=? AND game_code=?", $platformID, $gameID);
if ($res->numRows() > 0) {
  $db->query("UPDATE game_hit_count SET hit_count=hit_count+1 WHERE provider=? AND game_code=?", $platformID, $gameID);
} else {
  $db->query("INSERT INTO game_hit_count (provider,game_code,hit_count) VALUES (?,?,?)", $platformID, $gameID, 1);
}

$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$kaConfig = $db->query($sql, 'kas')->fetchArray();
$kaConfig = json_decode($kaConfig['config_string'], true);

$arrParam = [
  'g' => $gameID,
  'p' => $kaConfig['partner_name'],
  'u' => $memberUsername,
  't' => $memberUsername,
  'ak' => $kaConfig['access_key'],
  'cr' => 'THB',
  'loc' => 'th',
  'l' => $site['host'] . "/lobby/auto/ka?platform=$platformID&category=$productType",
  'o' =>  $kaConfig['site_name']
];
$api_request = '';
foreach ($arrParam as $key => $value) {
  $api_request .= $key . '=' . $value . '&';
}
$api_request = substr($api_request, 0, -1);
$gameURL = $kaConfig['game_launch_url'] . '?' . $api_request;

// // file_put_contents('ka-authen.log', date('Y-m-d H:i:s') . ' url : ' . $gameURL . PHP_EOL, FILE_APPEND);
// // file_put_contents('ka-authen.log', date('Y-m-d H:i:s') . ' body : ' . PHP_EOL . json_encode($arrParam, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

$errCode = 0;
$uuID = genUUID();
$arrLaunch['url'] = $gameURL;
$arrLaunch['dt'] = date('Y-m-d H:i:s');
$_SESSION[$uuID] = json_encode($arrLaunch);
$db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
$gameURL = './launch?token=' . $uuID;

echo json_encode(['error' => 0, 'gameURL' => $gameURL]);
