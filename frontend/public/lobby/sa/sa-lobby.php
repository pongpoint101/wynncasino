<?php
require_once '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
require_once './api/sa-function.php';
require_once '../lobby-function.php';
// require_once '../../../private/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

// require __DIR__ . '/../../checklogin.php';
// require_once '../../dbmodel.php';
// require_once '../../function.php';
// require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
// require_once '../../vendor/autoload.php';
// require_once './api/sa-function.php';
// require_once '../lobby-function.php';

$site = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
  header("location: " . GetFullDomain() . '/login');
  exit(0);
}
$memberNo = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];
$memberUserName = $site['site_id'] . $memberNo;

if (SkipSlot($member_promo)) {
  return;
}

if (isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = 'sac';
}

$sa_cfg = getProviderConfig($type, 66, 1);
// $sa_cfg = getProviderConfig('sac', 66, 1);
$res = getSAAuth(1, $sa_cfg, $memberUserName);  // 1=RegUserInfo  2=LoginRequest 3=LoginRequestForFun
$res = getSAAuth(2, $sa_cfg, $memberUserName);   // 1=RegUserInfo  2=LoginRequest 3=LoginRequestForFun

switch (($res['ErrorMsgId'])) {
  case 0:
    // Authen successful
    // goLiveGame($res['Token'],$sa_cfg);

    $username = $_SESSION['member_login'];
    $qs  = $sa_cfg['sa_client_url'];
    $qs .= "?username=" . $memberUserName;
    $qs .= "&token="    . $res['Token'];
    $qs .= "&lobby="    . $sa_cfg['sa_lobby_code'];
    $qs .= "&lang="     . 'th';

    $detecMob = new Mobile_Detect();
    if ($detecMob->isMobile()) {
      $qs .= "&mobile=true";
    }

    header('Location:' . $qs);

    break;

  default:
    // file_put_contents('sa-error.txt', $res['ErrorMsg'] . PHP_EOL, FILE_APPEND);
    echo '<script>alert("Error : ("' . $res['ErrorMsgId'] . ') ' . $res['ErrorMsg'] . ')</script>';
    echo "<script>window.close()</script>";
    break;
}
