<?php

function getAEAuth($option, $ae_cfg, $memberUserName, $extURL = '')
{
  $ae_cert = $ae_cfg['ae_cert'];
  $ae_agent_id = $ae_cfg['ae_agent_id'];
  // $user_id = $_SESSION['member_login'];
  $user_id = $memberUserName;
  $member_promo = $_SESSION['member_promo'];
  $currency_type = 'THB';
  $lang = 'th';
  $limit_id = $ae_cfg['ae_bet_limit'];
  $ae_bet_limit = '{"SEXYBCRT":{"LIVE":{"limitId":[' . $limit_id . ']}}}';
  $api_url = $ae_cfg['ae_api_url'];

  $detectMob = new Mobile_Detect();
  $mobile = 'false';
  if ($detectMob->isMobile()) {
    $mobile = 'true';
  }

  $res = null;
  $arr = array();

  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/x-www-form-urlencoded'
    ]
  ]);

  switch ($option) {
    case 1: // 1=CreateUser
      $arr['form_params'] = array(
        'cert'     => $ae_cert,
        'agentId'  => $ae_agent_id,
        'userId'   => $user_id,
        'currency' => $currency_type,
        'betLimit' => $ae_bet_limit,
        'isMobileLogin' => $mobile,
        'language' => $lang
      );
      $res = $client->request('POST', $api_url . '/wallet/createMember', $arr);
      // file_put_contents('ae-create_user.txt', json_encode($arr['form_params']));
      break;

    case 2: // UpdateBetLimit
      $arr['form_params'] = array(
        'cert'     => $ae_cert,
        'agentId'  => $ae_agent_id,
        'userId'   => $user_id,
        'betLimit' => $ae_bet_limit
      );
      $res = $client->request('POST', $api_url . '/wallet/updateBetLimit', $arr);
      break;

    case 3: // Call Sexy
      $arr['form_params']  = array(
        'agentId'  => $ae_agent_id,
        'cert'     => $ae_cert,
        'userId'   => $user_id,
        'gameCode'   => 'MX-LIVE-002',
        'hall' => "SEXY",
        'gameType'   => 'LIVE',
        'platform'   => 'SEXYBCRT',
        'isMobileLogin' => $mobile,
        'externalURL' => $extURL,
        'enableTable' => true,
        'gameForbidden' => '{"KINGMAKER":{"ALL":["ALL"]},"RT":{"SLOT":["ALL"]},"JILI":{"SLOT":["ALL"]}}',
        'language' => $lang
      );
      $res = $client->request('POST', $api_url . '/wallet/doLoginAndLaunchGame', $arr);
      break;
    default:
      # code...
      break;
  }

  if (+$option == 4) {  // KM
    $arr['form_params']  = array(
      'agentId'  => $ae_agent_id,
      'cert'     => $ae_cert,
      'userId'   => $user_id,
      'gameCode'   => '',
      'gameType'   => 'TABLE',
      'platform'   => 'KINGMAKER',
      'isMobileLogin' => $mobile,
      'gameForbidden' => '{"SEXYBCRT":{"ALL":["ALL"]},"RT":{"SLOT":["ALL"]},"JILI":{"SLOT":["ALL"]},"KINGMAKER":{"TABLE":["KM-TABLE-033"]}}',
      'language' => $lang
    );
    $res = $client->request('POST', $api_url . '/wallet/login', $arr);
  }

  if (+$option == 5) {  // Red Tiger
    $arr['form_params']  = array(
      'agentId'  => $ae_agent_id,
      'cert'     => $ae_cert,
      'userId'   => $user_id,
      'gameCode'   => '',
      'gameType'   => 'SLOT',
      'platform'   => 'RT',
      'isMobileLogin' => $mobile,
      'gameForbidden' => '{"SEXYBCRT":{"ALL":["ALL"]},"KINGMAKER":{"ALL":["ALL"]},"RT":{"TABLE":["ALL"]},"JILI":{"ALL":["ALL"]}}',
      'language' => $lang
    );
    // }
    $res = $client->request('POST', $api_url . '/wallet/login', $arr);
  }

  if (+$option == 6) {  // JILI
    $arr['form_params']  = array(
      'agentId'  => $ae_agent_id,
      'cert'     => $ae_cert,
      'userId'   => $user_id,
      'gameCode'   => '',
      'gameType'   => 'SLOT',
      'platform'   => 'JILI',
      'isMobileLogin' => $mobile,
      'gameForbidden' => '{"SEXYBCRT":{"LIVE":["ALL"]},"KINGMAKER":{"ALL":["ALL"]},"RT":{"ALL":["ALL"]},"JILI":{"TABLE":["ALL"]}}',
      'language' => $lang
    );
    // }
    $res = $client->request('POST', $api_url . '/wallet/login', $arr);
  }


  return json_decode($res->getBody()->getContents(), true);
}


// function updateBetLimit