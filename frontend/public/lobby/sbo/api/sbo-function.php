<?php

function getSBOAuth($option, $sbo_cfg, $member, $siteInfo)
{
  $api_url = $sbo_cfg['sbo_api_url'];

  $sbo_cert = $sbo_cfg['sbo_cert'];
  $sbo_agent = $sbo_cfg['sbo_agent'];
  $sbo_group = $sbo_cfg['sbo_group'];
  $sbo_server = $sbo_cfg['sbo_server'];
  $sbo_agent_id = $sbo_cfg['sbo_agent_id'];
  $sbo_password = $sbo_cfg['sbo_bo_password'];
  $sbo_min_bet = $sbo_cfg['min_bet'];
  $sbo_max_bet = $sbo_cfg['max_bet'];
  $sbo_max_per_match = $sbo_cfg['max_per_match'];

  $user_id = $siteInfo['site_id'] . '_' . $member['id'] . '_' . $member['member_login'];

  $currency_type = 'THB';
  $lang = 'th';

  $res = null;
  $arr = array();

  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/json'
    ]
  ]);

  // Register Agent
  $arr['body'] = json_encode([
    "Username" => $sbo_agent,
    "Password" => $sbo_password,
    "Currency" => $currency_type,
    "Min" => +$sbo_min_bet,
    "Max" => +$sbo_max_bet,
    "MaxPerMatch"  => +$sbo_max_per_match,
    "CasinoTableLimit" => 1,
    "CompanyKey" => $sbo_cert,
    "ServerId" => $sbo_server,
  ]);
  $res = $client->request('POST', $api_url . '/web-root/restricted/agent/register-agent.aspx', $arr);

  if (+$option == 1) {  // 1=CreateUser 
    $arr['body'] = json_encode([
      "Username" => $user_id,
      "UserGroup" => $sbo_group,
      "Agent" => $sbo_agent,
      "CompanyKey" => $sbo_cert,
      "ServerId" => $sbo_server
    ]);
    $res = $client->request('POST', $api_url . '/web-root/restricted/player/register-player.aspx', $arr);
  }

  if (+$option == 2) {  // Login
    // Bet setting
    $arr['body'] = json_encode([
      'Username' => $user_id,
      'Min' => $sbo_cfg['min_bet'],
      'Max' => $sbo_cfg['max_bet'],
      'MaxPerMatch' => $sbo_cfg['max_per_match'],
      'CasinoTableLimit' => 2,
      'CompanyKey' => $sbo_cert,
      'ServerId' => $sbo_server
    ]);
    $res = $client->request('POST', $api_url . '/web-root/restricted/player/update-player-bet-settings.aspx', $arr);

    // Login
    $arr['body']  = json_encode(array(
      "Username" => $user_id,
      "Portfolio" => "SportsBook",
      "CompanyKey" => $sbo_cert,
      "ServerId" => $sbo_server
    ));
    $res = $client->request('POST', $api_url . '/web-root/restricted/player/login.aspx', $arr);
  }

  return json_decode($res->getBody()->getContents(), true);
}
