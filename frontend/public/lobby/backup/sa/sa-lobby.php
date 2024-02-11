<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once './api/sa-function.php';

$member_no = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];
$productType = 1;
require ROOT . '/core/promotions/lobby-function.php';

$sa_cfg = getProviderConfig($type, 66, 1);
// $sa_cfg = getProviderConfig('sac', 66, 1);
$res = getSAAuth(1, $sa_cfg);  // 1=RegUserInfo  2=LoginRequest 3=LoginRequestForFun
$res = getSAAuth(2, $sa_cfg);   // 1=RegUserInfo  2=LoginRequest 3=LoginRequestForFun

switch (($res['ErrorMsgId'])) {
  case 0:
    // Authen successful
    // goLiveGame($res['Token'],$sa_cfg);
    $username = $_SESSION['member_login'];
    $qs  = $sa_cfg['sa_client_url'];
    $qs .= "?username=" . $username;
    $qs .= "&token="    . $res['Token'];
    $qs .= "&lobby="    . $sa_cfg['sa_lobby_code'];
    $qs .= "&lang="     . 'th';
    $detecMob = new Mobile_Detect();
    if ($detecMob->isMobile()) {
      $qs .= "&mobile=true";
    }
    header('Location:' . $qs);
    // echo '<script>window.open("' . $qs . '","_self")</script>';
    break;
  default:
    echo '<script>alert("Error : ("' . $res['ErrorMsgId'] . ') ' . $res['ErrorMsg'] . ')</script>';
    // echo '<script>window.location.href = "/user/game"</script>';
    echo "<script>window.close()</script>";
    break;
}
