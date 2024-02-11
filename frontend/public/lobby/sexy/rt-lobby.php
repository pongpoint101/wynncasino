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

if (SkipCasino($member_promo)) {
  return;
}

$rt_cfg = getProviderConfig('rts', 66, 1);
$res = getAEAuth(1, $rt_cfg);
if (!empty($res)) {
  if ($res['status'] == '0000' || $res['status'] == '1001') {
    $res = getAEAuth(5, $rt_cfg);
    if (!empty($res)) {
      if ($res['status'] == '0000') {
        // echo 'asdfasdfasfsdfasdfads';
        // var_dump($res);
        // exit();
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
