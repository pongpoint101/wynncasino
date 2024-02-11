<?php
require_once '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

$WebSites = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
  header("location: " . GetFullDomain() . '/login');
  exit(0);
}

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
$memberUserName = $site['site_id'] . $memberNo;

$db = new DB();

$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$res = $db->query($sql, 'hdg')->fetchArray();
$config = json_decode($res['config_string'], true);

$sql = "SELECT * FROM members WHERE username=?";
$res = $db->query($sql, $memberUserName)->fetchArray();
$req['username'] = $res['username'];
// $req['password'] = $res['passwordText'];
$req['agent'] = $config['agent'];

// Encryption
$password =  json_encode($req);
$iterations = 1000;
$secret = $config['secret_key'];
$hash = hash_pbkdf2("sha512", $password, $secret, $iterations, 64, true);
$xSignature =  base64_encode($hash);

$targetURL = $config['api_url'] . '/seamless/launch';
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'x-signature'    => $xSignature
  ]
]);

$arr['body'] = json_encode($req);
$res = $client->request('POST', $targetURL, $arr);
$resp = $res->getBody()->getContents();
$arr = json_decode($resp, true);
if ($arr['status']['code'] == 0) {
  header("Location: " . $arr['data']['url']);
} else {
  echo json_encode(['status' => ['code' => 999, 'message' => 'Service not available']]);
}
