<?php 
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';   
require_once './api/ae-function.php'; 

$site =GetWebSites();     
$member_no = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];
$productType=1;
require ROOT.'/core/promotions/lobby-function.php';   

$ae_cfg = getProviderConfig('aec', 66, 1);
$res = getAEAuth(1, $ae_cfg);
if (!empty($res)) {
  if ($res['status'] == '0000' || $res['status'] == '1001') {
    $res = getAEAuth(3, $ae_cfg);
    if (!empty($res)) {
      if ($res['status'] == '0000') {
        $res2 = getAEAuth(2, $ae_cfg);
        echo "<script>window.open('" . $res['url'] . "','_self')</script>";
      } else {
        echo "<script>alert('Status : " . $res['status'] . "(" . $res['desc'] . "')</script>";
        echo "<script>window.close()</script>";
      }
    }
  } else {
    echo "<script>alert('Status : " . $res['status'] . "(" . $res['desc'] . "')</script>";
    echo "<script>window.close()</script>";
  }
}

