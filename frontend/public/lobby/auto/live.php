<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

function getCategoryName($CateID)
{
    $CateName = '';
    switch ($CateID) {
        case 1:
            $CateName = 'Sportsbook';
            break;

        case 2:
            $CateName = 'Live Casino';
            break;

        case 3:
            $CateName = 'Slot';
            break;

        case 4:
            $CateName = 'Fishing';
            break;

        case 5:
            $CateName = 'Skill/Table/Arcade';
            break;

        case 6:
            $CateName = 'ESports';
            break;

        case 7:
            $CateName = 'Lotto';
            break;

        case 8:
            $CateName = 'Crypto';
            break;

        default:
            $CateName = 'N/A';
            break;
    }
    return $CateName;
}


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

if (isset($_GET['gc'])) {
    $product = $_GET['gc'];
    $platformID = $_GET['pid'];
    $lobbyURL = '/lobby/auto/live?gc=' . $product . '&pid=' . $platformID;
} elseif (isset($_GET['gn'])) {
    $product = $_GET['gn'];
    $platformID = $_GET['pid'];
    $lobbyURL = '/lobby/auto/live?gn=' . $product . '&pid=' . $platformID;
} else {
    exit();
}

if ($memberNo != 1000001) {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='" . $platformID . "' AND  lobby_url='" . $lobbyURL . "' AND status=1");
} else {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id='" . $platformID . "' AND  lobby_url='" . $lobbyURL . "'");
}
if ($res->numRows() <= 0) {
    echo "Error : 1007<BR>Message : Provider under maintenance<BR>";
    exit();
}

$res = $res->fetchArray();
$productType = $res['product_type'];
$provider_code = $res['provider_code'];
require ROOT . '/core/promotions/lobby-function.php';

if (isset($_GET['gn'])) {
    $lobbyURL = str_replace('live?gn=', '', $lobbyURL);
    header('refresh:0;url=' . $lobbyURL);
    exit();
}
$res = $db->query("SELECT * FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
    exit();
}

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$sql = "SELECT * FROM m_providers WHERE provider_code='auto'";
$ProviderConfig = $db->query($sql)->fetchArray();
$ProviderConfig = json_decode($ProviderConfig['config_string'], true);
$apiUrlCreateUser = $ProviderConfig['create_user_url'];
$apiUrlLogin = $ProviderConfig['login_url'];
$opToken = $ProviderConfig['operator_token'];
$secretKey = $ProviderConfig['secret_key'];

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
// $response = $client->request('POST', $apiUrlCreateUser, $arr);  //<<<<<<<<<<<<<<<
// $response = json_decode($response->getBody()->getContents(), true);

// Launch game
$arr['body'] = json_encode([
    'userName' => $memberUserName,
    'platformId' => (int)$platformID,
    'gameCode' => $product,
    'mobile' => false,
    'ip' => $clientIP,
    'lang' => 'th',
    'returnUrl' => $ProviderConfig['return_url'],
]);

$response = $client->request('POST', $apiUrlLogin, $arr);  //<<<<<<<<<<<<<<<
$response = json_decode($response->getBody()->getContents(), true);
if (array_key_exists('data', $response)) {
    if (!array_key_exists('gameUrl', $response['data'])) {
        echo 'Error : ' . $response['code'] . '<br>Message : ' . $response['msg'] . '<br>';
        // var_dump($response);
        exit();
    }
} else {
    echo 'Error : ' . $response['code'] . '<br>Message : ' . $response['msg'] . '<br>';
    // var_dump($response);
    exit();
}

$gameURL = $response['data']['gameUrl'];
// echo '<br>' . $gameURL;
header('refresh:0;url=' . $gameURL);
