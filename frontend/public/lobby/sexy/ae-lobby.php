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

if (SkipSlot($memberPromo)) {
  return;
}

if (isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = 'aec';
}

$ae_cfg = getProviderConfig($type, 66, 1);
// $ae_cfg = getProviderConfig('aec', 66, 1);
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
