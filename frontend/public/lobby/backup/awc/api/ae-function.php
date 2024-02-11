<?php



function getAEAuth($option, $ae_cfg)

{

  $ae_cert = $ae_cfg['ae_cert'];

  $ae_agent_id = $ae_cfg['ae_agent_id'];

  $user_id =$_SESSION['member_login'];

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



  if (+$option == 1) {  // 1=CreateUser 

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

  }



  if (+$option == 2) {  // 2=UpdateBetLimit

    // $url = '/wallet/updateBetLimit';

    $arr['form_params'] = array(

      'cert'     => $ae_cert,

      'agentId'  => $ae_agent_id,

      'userId'   => $user_id,

      'betLimit' => $ae_bet_limit

    );

    $res = $client->request('POST', $api_url . '/wallet/updateBetLimit', $arr);

  }



  // $gameForbidden = '';

  // if ($member_promo == 31 || $member_promo == 32 || $member_promo == 33) {

  //   $gameForbidden = '{"RT":{"ALL":["ALL"]},"JILI":{"ALL":["ALL"]}}';

  // }



  // $freeCredit = 0;

  // if ($member_promo == 1 || $member_promo == 16) {

  // }



  if (+$option == 3) {  // Sexy

    $arr['form_params']  = array(

      'agentId'  => $ae_agent_id,

      'cert'     => $ae_cert,

      'userId'   => $user_id,

      'gameCode'   => '',

      'gameType'   => 'LIVE',

      'platform'   => 'SEXYBCRT',

      'isMobileLogin' => $mobile,

      // 'gameForbidden' => $gameForbidden,

      'gameForbidden' => '{"KINGMAKER":{"ALL":["ALL"]},"RT":{"SLOT":["ALL"]},"JILI":{"SLOT":["ALL"]}}',

      'language' => $lang



    );

    $res = $client->request('POST', $api_url . '/wallet/login', $arr);

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

      'gameForbidden' => '{"SEXYBCRT":{"ALL":["ALL"]},"RT":{"SLOT":["ALL"]},"JILI":{"SLOT":["ALL"]}}',

      // 'gameForbidden' => $gameForbidden,

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