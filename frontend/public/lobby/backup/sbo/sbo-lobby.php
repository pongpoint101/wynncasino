<?php
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';  
header('Access-Control-Allow-Origin: *'); 

$site = GetWebSites();   
require_once './api/sbo-function.php'; 

$member_no = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];
$productType=3;
require ROOT.'/core/promotions/lobby-function.php';  

$member = getMembers($member_no);
$sboConfig = getProviderConfig('sbo', 66, 1);
$res = getSBOAuth(1, $sboConfig, $member);

if (!empty($res)) {
  if ($res['error']['id'] == 0 || $res['error']['id'] == 4103) {
    $res = getSBOAuth(2, $sboConfig, $member);
    if (!empty($res)) {
      if ($res['error']['id'] == 0) {
        echo "<script>window.open('" . $res['url'] . "&lang=th-th&oddstyle=MY&oddsmode=double','_self')</script>";
      } else {
        echo "<script>alert('Status : " . $res['error']['id'] . "(" . $res['error']['msg'] . "')</script>";
      }
    }
  } else {
    echo "<script>alert('Status : " . $res['error']['id'] . "(" . $res['error']['msg'] . "')</script>";
  }
}
