<?php
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';     
require_once './api/ae-function.php'; 

$site =GetWebSites();  
$member_no = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$jl_cfg = getProviderConfig('jls', 66, 1);
$res = getAEAuth(1, $jl_cfg);
if (!empty($res)) {
  if ($res['status'] == '0000' || $res['status'] == '1001') {
    $res = getAEAuth(6, $jl_cfg);
    if (!empty($res)) {
      if ($res['status'] == '0000') {
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

