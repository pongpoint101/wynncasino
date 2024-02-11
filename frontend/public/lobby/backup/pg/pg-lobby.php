<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php'; 
require_once './api/pg-function.php'; 

$member_no = $_SESSION['member_no'];
$member_login =$_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$pg_cfg = getProviderConfig('pgs', 66, 1);
$api_url = $pg_cfg['api_domain'] . '/v1/Login/LoginGame';
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/x-www-form-urlencoded'
  ]
]);

$arr['form_params'] = array(
  'secret_key'     => $pg_cfg['secret_key'],
  'operator_token'  => $pg_cfg['operator_token'],
  'player_session'   => $_SESSION['member_login'],
  'operator_player_session' => $_SESSION['member_login'],
  'player_name' => $_SESSION['member_no'],
  'currency' => 'THB',
  'reminder_time' => round(microtime(true) * 1000),
  'nickname' =>$_SESSION['username']
);
$res = $client->request('POST', $api_url, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  // Mobile
  // https://m.pg-redirect.net/{game_code}/index.html?language={0}&bet_type={1}&operator_token={2}&operator_player_session={3}
  $launch_url = $pg_cfg['launch_url'];
  $launch_url = str_replace('{game_code}', 'lobby', $launch_url);
  $launch_url = str_replace('{0}', 'th', $launch_url);   // language
  $launch_url = str_replace('{1}', '1', $launch_url);     // bet_type
  $launch_url = str_replace('{2}', $pg_cfg['operator_token'], $launch_url);
  $launch_url = str_replace('{3}', $_SESSION['member_login'], $launch_url);
  // $launch_url = str_replace('{3}', $res['data']['player_session'], $launch_url);
  $launch_url .= '&lt=1';
  // var_dump($launch_url);
  header("Location:" . $launch_url);
} else {
  // Desktop
  // https://public.pg-redirect.net/web-lobby/{panel_type}/?operator_token={1}&operator_player_session={2}&language={3}
  $web_lobby_url = $pg_cfg['web_lobby_url'];
  $web_lobby_url = str_replace('{panel_type}', 'games', $web_lobby_url);
  $web_lobby_url = str_replace('{1}', $pg_cfg['operator_token'], $web_lobby_url);
  $web_lobby_url = str_replace('{2}', $_SESSION['member_login'], $web_lobby_url);
  // $web_lobby_url = str_replace('{2}', $res['data']['player_session'], $web_lobby_url);
  $web_lobby_url = str_replace('{3}', 'th', $web_lobby_url);
  $web_lobby_url .= '&bet_type=1&lt=1';
  // $web_lobby_url = str_replace('&operator_player_session=&', '&operator_player_session=' . $_SESSION['member_login'] . '&', $web_lobby_url);
  // var_dump($web_lobby_url);
  header("Location:" . $web_lobby_url);
}

