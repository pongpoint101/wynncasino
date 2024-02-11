<?php
require_once '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
include_once './api/ae-function.php';
require_once '../lobby-function.php';

$site = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
  header("location: " . GetFullDomain() . '/login');
  exit(0);
}

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
// $memberUserName = $site['site_id'] . '_' . $memberNo;
$memberUserName = $site['site_id'] . $memberNo;
// $_SESSION['member_login'] = $memberUserName;

if (SkipSlot($member_promo)) {
  return;
}

$ae_cfg = getProviderConfig('awc', 66, 1);
// // file_put_contents('awc-ae-lobby.txt', json_encode($ae_cfg));
$res = getAEAuth(1, $ae_cfg, $memberUserName); // Create user
if (!empty($res)) {
  if ($res['status'] == '0000' || $res['status'] == '1001') {
    $res = getAEAuth(3, $ae_cfg, $memberUserName, $site['host'] . '/lobby'); // Call sexy lobby
    if (!empty($res)) {
      if ($res['status'] == '0000') {
        $res2 = getAEAuth(2, $ae_cfg, $memberUserName); // update bet limit
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
