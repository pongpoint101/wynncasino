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

// $memberNo = $_SESSION['member_no'];
$memberNo = substr($_POST['username'], -7);
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
// $memberUsername = $site['site_id'] . '_' . $memberNo;
$memberUsername = $_POST['username'];

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
$fgConfig = $db->query($sql, 'fgs')->fetchArray();
$fgConfig = json_decode($fgConfig['config_string'], true);

$returnURL = $site['host'] . "/lobby/auto/fg?platform=$platformID&category=$productType";
$apiURL = $fgConfig['api_url'];
$createPlayerURL = $apiURL . '/v3/players';
$getLaunchGameURL = $apiURL . '/v3/launch_game';
$merchantName = $fgConfig['merchantname'];
$merchantCode = $fgConfig['merchantcode'];
$prefix = $fgConfig['id'] . '.';
$openID = '';

// Save latest game name
$sql = "SELECT gamecode,gamename FROM fg_list_games WHERE gamecode=?";
$res = $db->query($sql, $gameID)->fetchArray();
$sql = "INSERT INTO fg_latest_game (member_no,game_code,game_name) VALUES (?,?,?)";
$db->query($sql, $memberNo, $gameID, $res['gamename']);

// $postData['member_code'] = $prefix . $memberNo;
$arr['form_params'] = [
  'member_code' => $prefix . $memberNo
];

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Accept'         => 'application/json',
    'Content-Type'   => 'application/x-www-form-urlencoded',
    'merchantname'   => $merchantName,
    'merchantcode'   => $merchantCode
  ]
]);

$detectPlayerURL = $apiURL . '/v3/player_names/' . $prefix . $memberNo;
$res = $client->request('POST', $detectPlayerURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);
// file_put_contents('fg-authen.log', date('Y-m-d H:i:s') . ' (1) : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

if ($res['code'] == 0) {
  // The player exists in FG
  $openID = $res['data']['openid'];
} else {
  // the player doesn't exists in FG so createPlayer
  $resMember = $db->query("SELECT * FROM members WHERE id=$memberNo")->fetchArray();
  $arr['form_params'] = [
    'member_code' => $prefix . $memberNo,
    'password' => $resMember['passwordText']
  ];

  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
      'Accept'         => 'application/json',
      'Content-Type'   => 'application/x-www-form-urlencoded',
      'merchantname'   => $merchantName,
      'merchantcode'   => $merchantCode
    ]
  ]);

  $res = $client->request('POST', $createPlayerURL, $arr);  //<<<<<<<<<<<<<<<
  $res = json_decode($res->getBody()->getContents(), true);
  // file_put_contents('fg-authen.log', date('Y-m-d H:i:s') . ' (2) : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

  $openID = $res['data']['openid'];
}

$arr['form_params'] = [
  'openid' => $openID,
  'game_code' => $gameID,
  'game_type' => 'h5',
  'language' => 'en-us',
  'ip' => getIP(),
  'return_url' => $returnURL
];

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Accept'         => 'application/json',
    'Content-Type'   => 'application/x-www-form-urlencoded',
    'merchantname'   => $merchantName,
    'merchantcode'   => $merchantCode
  ]
]);

try {
  $res = $client->request('POST', $getLaunchGameURL, $arr);  //<<<<<<<<<<<<<<<
  $res = json_decode($res->getBody()->getContents(), true);
  // file_put_contents('fg-authen.log', date('Y-m-d H:i:s') . ' (3) : ' . json_encode($res, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

  $gameURL = $res['data']['game_url'] . '&token=' . $res['data']['token'];
} catch (Exception $e) {
  $gameURL = $e->getMessage();
  // file_put_contents('fg-authen.log', date('Y-m-d H:i:s') . ' (4) : ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
}

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
