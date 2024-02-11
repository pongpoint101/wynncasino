<?php

function getSAAuth($option, $sa_cfg, $memberUserName)
{

  $SecretKey = $sa_cfg['sa_secret_key']; //SecretKey
  $md5key = $sa_cfg['sa_md5_key']; // MD5Key
  $EncryptKey = $sa_cfg['sa_encrypt_key'];
  $api_url = $sa_cfg['sa_api_url'];
  $Time = getDateTime('YmdHis');
  $currency_type = 'THB';
  $lang = 'th';
  $init_amount = 10000.00;
  $query_str = "";

  $username = $memberUserName;
  // $memmber_no = $_SESSION['member_no'];


  switch ($option) {
    case 1: // RegUserInfo
      $query_str = "method=RegUserInfo&Key=" . $SecretKey . "&Time=" . $Time  . "&CurrencyType=" . $currency_type . "&Username=" . $username;
      break;

    case 2: // LoginRequest
      $query_str = "method=LoginRequest&Key=" . $SecretKey . "&Time=" . $Time  . "&CurrencyType=" . $currency_type . "&Username=" . $username . "&Lang=" . $lang;
      break;

    case 3: // LoginRequestForFun
      $query_str = "method=LoginRequestForFun&Key=" . $SecretKey . "&Time=" . $Time  . "&CurrencyType=" . $currency_type . "&Amount=" . $init_amount . "&Lang=" . $lang;
      break;

    default:
      # code...
      break;
  }

  // ************* DES *************
  $q = DESEncrypt($EncryptKey, $query_str);              //<<<<<<<<<  Q
  // ************* MD5 *************
  $s = BuildMD5($query_str, $md5key, $Time, $SecretKey);   //<<<<<<<<<<  S

  $res = null;

  try {
    $client = new GuzzleHttp\Client([
      'exceptions'       => false,
      'verify'           => false,
      'headers'         => [
        'Content-Type'   => 'application/x-www-form-urlencoded'
      ]
    ]);

    $res = $client->request('POST', $api_url . "?q=" . $q . "&s=" . $s);
    // var_dump($res->getBody()->getContents());

    $res = json_decode(json_encode(simplexml_load_string($res->getBody()->getContents())), true);
  } catch (Exception $e) {
    if (+$option == 1) {
      $res['Token'] = '';
      $res['ErrorMsgId'] = 129;
      $res['ErrorMsg'] = $e->getMessage;
    }
    if (+$option == 2) {
      $res['Username'] = $username;
      $res['ErrorMsgId'] = 133;
      $res['ErrorMsg'] = $e->getMessage;
    }
  }
  return $res;
}


function goLiveGame($token, $sa_cfg)
{
  // $sa_cfg = getSASiteCfg(100, 1); //(site_id,operation_mode) 1=Staging, 2=Production

  $username = $_SESSION['member_login'];
  $qs  = $sa_cfg['sa_client_url'];
  $qs .= "?username=" . $username;
  $qs .= "&token="    . $token;
  $qs .= "&lobby="    . $sa_cfg['sa_lobby_code'];
  $qs .= "&lang="     . 'th';

  $detecMob = new Mobile_Detect();
  if ($detecMob->isMobile()) {
    $qs .= "&mobile=true";
  }
  // var_dump($detecMob->isMobile());
  echo '<script>window.open("' . $qs . '","_self")</script>';
}

function goLiveGameForFun($token, $sa_cfg)
{
  // $sa_cfg = getSASiteCfg(100, 1); //(site_id,operation_mode) 1=Staging, 2=Production
  $qs  = $sa_cfg['sa_client_url'];
  $qs .= "?token="    . $token;
  if (isMobile() == 1) {
    $qs .= "&mobile=true";
  }

  echo "<script>window.open('" . $qs . "')</script>";
}
