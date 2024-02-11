<?php

session_start();
require_once '../../dbmodel.php';
require_once '../../function.php';
require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
require_once '../../vendor/autoload.php';
require_once './api/sbo-function.php';

$_SESSION['member_loing'] = '5f71e7ff61578';
$member = getMembers(100001);

$sboConfig = getProviderConfig('sbo', 66, 1);
$res = getSBOAuth(1, $sboConfig, $member);

// echo "<pre>";
// print_r($res);
// echo "</pre>";
// exit();
if (!empty($res)) {
  if ($res['error']['id'] == 0 || $res['error']['id'] == 4103) {
    $res = getSBOAuth(2, $sboConfig, $member);
    if (!empty($res)) {
      if ($res['error']['id'] == 0) {
        echo "<script>window.open('" . $res['url'] . "','_blank')</script>";
      } else {
        echo "<script>alert('Status : " . $res['status'] . "(" . $res['desc'] . "')</script>";
        // echo "<script>window.location.href = '/user/game'</script>";
        // echo "<script>window.close()</script>";
      }
    }
  } else {
    echo "<script>alert('Status : " . $res['status'] . "(" . $res['desc'] . "')</script>";
    // echo "<script>window.location.href = '/user/game'</script>";
    // echo "<script>window.close()</script>";
  }
}
