<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php'; 
require_once './api/pg-function.php';

// $pg_cfg = getPGSiteCfg(66, 1); //(site_id,operation_mode) 1=Staging, 2=Production 3=Fun Mode
$pg_cfg = getProviderConfig('pgs',66,1);
// $res = getSAAuth(1, $sa_cfg);  // 1=RegUserInfo  2=LoginRequest 3=LoginRequestForFun
// $res = getSAAuth(2, $sa_cfg); //require_once 'function.php';
$trial_url = $pg_cfg['web_lobby_url'];
$trial_url = str_replace('{panel_type}', 'games', $trial_url);
$trial_url = str_replace('{1}',$pg_cfg['operator_token'],$trial_url);
$trial_url = str_replace('{2}', 'demo-'. urlencode(uniqid()), $trial_url);
$trial_url = str_replace('{3}', 'th', $trial_url);
 
header("Location:". $trial_url); 

?> 