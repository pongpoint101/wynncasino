<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();  
require_once './api/ka-function.php'; 


$member_no = $_SESSION['member_no'];
$member_login =$_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$ka_config =getProviderConfig('kas', 66, 1);
$lobbyURL = $ka_config['lobby_url'] . '/?';
$arr['tp'] = 'square';
$arr['loc'] = 'th';
$arr['nw'] = 0;
$arr['p'] = $ka_config['partner_name'];
$arr['ak'] = $ka_config['access_key'];
$arr['u'] = $member_no;
$arr['cr'] = 'THB';
$arr['t'] = $member_login;
$arr['o'] =  $ka_config['site_name'];


$req_str = '';
foreach ($arr as $key => $value) {
  $req_str .= $key . '=' . $value . '&';
}
$req_str = substr($req_str, 0, -1);
header("Location:" . $lobbyURL . $req_str);

