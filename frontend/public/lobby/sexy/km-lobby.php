<?php

// session_start();
// include(__DIR__ . '/../../checklogin.php');
require __DIR__ . '/../../checklogin.php';

$site = include(__DIR__ . '/config/site.php');
require_once '../../dbmodel.php';
require_once '../../function.php';
require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
require_once '../../vendor/autoload.php';
require_once './api/ae-function.php';
require_once '../lobby-function.php';

$memberNo = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];

if (SkipSlot($member_promo)) {
  return;
}

// $_SESSION['member_loing'] = '5eb2be1752496';
// $km_cfg = getAESiteCfg(66, 1); //(site_id,operation_mode) 1=Staging, 2=Production
$km_cfg = getProviderConfig('kmc', 66, 1);
$res = getAEAuth(1, $km_cfg);
if (!empty($res)) {
  if ($res['status'] == '0000' || $res['status'] == '1001') {
    $res = getAEAuth(4, $km_cfg);
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
