<?php

require '../../bootstart.php'; 

require_once ROOT.'/core/security.php';

require_once ROOT.'/core/db2/db.php';

$db = new DB(); 

$site =GetWebSites();  

require_once './api/ka-function.php';



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



$member_no = $_SESSION['member_no'];

$member_login = $_SESSION['member_login'];

$id = $_POST['id'];



if (!isset($id)) {

  echo json_encode([

    'status' => 'failed',

    'data' => null,

  ]);

  exit();

}



$sql = "SELECT * FROM m_providers WHERE provider_code=?";

$ka_config = $db->query($sql, 'kas')->fetchArray();

$ka_config = json_decode($ka_config['config_string'], true);



$game_details = $db->query("SELECT * FROM ka_list_games WHERE id=? AND status=1", $_POST['id'])->fetchArray();

$game_details = json_decode($game_details['details'], true);



$gameLaunchURL = $ka_config['game_launch_url'] . '?';

$arrParam['g'] = $game_details['gameId'];

$arrParam['p'] = $ka_config['partner_name'];

$arrParam['u'] = $member_no;

$arrParam['t'] = $member_login;

$arrParam['ak'] = $ka_config['access_key'];

$arrParam['cr'] = 'THB';

$arrParam['loc'] = 'th';

$arrParam['l'] = $site['host'] . '/lobby/kagaming/kafishing';

$arrParam['o'] =  $ka_config['site_name'];


$api_request = '';

foreach ($arrParam as $key => $value) {

  $api_request .= $key . '=' . $value . '&';

}



$api_request = substr($api_request, 0, -1);



hitsStatistic($game_details['gameId'], $game_details['gameName']);

$res['link'] = $gameLaunchURL . $api_request;

$res['status'] = 'ok';



echo json_encode($res);

