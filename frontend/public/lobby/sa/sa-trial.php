<?php

session_start();
require_once '../../dbmodel.php';
require_once '../../function.php';
require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
require_once '../../vendor/autoload.php';
require_once './api/sa-function.php';

// $sa_cfg = getSASiteCfg(66, 1); //(site_id,operation_mode) 1=Staging, 2=Production 3=Fun Mode
$sa_cfg = getProviderConfig('sac',66,1);
$res = getSAAuth(3,$sa_cfg);  // 1=RegUserInfo  2=LoginRequest

// var_dump($res);

switch ($res['ErrorMsgId']) {
  case 0:
    // Authen successful
    goLiveGameForFun($res['Token'], $sa_cfg);
    break;

  default:
    echo '<script>alert("Error : ("' . $res['ErrorMsgId'] . ') ' . $res['ErrorMsg'] . ')</script>';
    echo '<script>window.location.href = "/user/game"</script>';
    break;
}
