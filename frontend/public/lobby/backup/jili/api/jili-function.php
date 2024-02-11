<?php 
function GenerateKey()
{
  global $db;

  $arrConfig = getProviderConfig('jili', 66, 1);
  $agentKey = $arrConfig['agent_key'];
  $agentID = $arrConfig['agent_id'];

  date_default_timezone_set("America/Manaus");
  // $dd = date_create('2021-04-01');
  // $dateUTCMinus4 = date_format($dd, 'ymj'); // date('ymd');
  // $dateUTCMinus4 = date("ymj H:i:s A");
  $dateUTCMinus4 = date('ymj');
  date_default_timezone_set("Asia/Bangkok");
  return md5($dateUTCMinus4 . $agentID . $agentKey);
  // return $dateUTCMinus4 . ' ' . $agentID . ' ' . $agentKey;

  // return md5('19011' . 'Chaiyo_1' .  'be8e08f95357d215921f91c6a533f74d3194de52');
}

function GenerateKeyForLogin($account, $gameID)
{
  global $db;

  $randTxt1 = RandomString(6);
  $randTxt2 = RandomString(6);

  $keyG = GenerateKey();

  $arrConfig =getProviderConfig('jili', 66, 1);
  $arrReturn['keyG'] = $keyG;
  $arrReturn['agent_key'] = $arrConfig['agent_key'];
  $arrReturn['agent_id'] = $arrConfig['agent_id'];
  $arrReturn['language'] = 'th-TH';
  $arrReturn['token'] = 'ThisIsToken';
  $arrReturn['account'] = $account;
  $arrReturn['game_id'] = $gameID;
  $arrReturn['random_text1'] = $randTxt1;
  $arrReturn['random_text2'] = $randTxt2;
  $arrReturn['query_string'] = 'Token=' . $arrReturn['token'] . '&Account=' . $account . '&GameId=' . $gameID . '&Lang=' . $arrReturn['language'] . '&AgentId=' . $arrReturn['agent_id'];
  $arrReturn['md5_string'] = md5($arrReturn['query_string'] . $keyG);
  $arrReturn['key_login'] = $randTxt1 . $arrReturn['md5_string'] . $randTxt2;
  $arrReturn['login_url'] = $arrReturn['query_string'] . '&Key=' . $arrReturn['key_login'];
  return $arrReturn;
}

function RandomString($strLength)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  $input_length = strlen($permitted_chars);
  $random_string = '';
  for ($i = 0; $i < $strLength; $i++) {
    $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
    $random_string .= $random_character;
  }

  return $random_string;
}

function GetGameList()
{
  global $db;

  $arrConfig =getProviderConfig('jili', 66, 1);

  date_default_timezone_set('Etc/GMT+4');
  $agentKey = $arrConfig['agent_key'];
  // // $agentKey = '38367db12903817653f90a2706f547f38207d2e7';
  // $agentKey = 'fc38fdfd04fd0e5b0fc4b12c8b03c65bdca6f2a1';
  $agentId = $arrConfig['agent_id'];
  $params = 'AgentId=' . $agentId;
  $apiURL = $arrConfig['api_url'] . '/api1/GetGameList';
  // // $apiURL = 'https://uat-wb-api.jlfafafa2.com/api1/GetGameList';
  // $apiURL = 'https://wb-api.jlfafafa2.com/api1/GetGameList';

  $now = date("ymj");
  $keyG = md5($now . $agentId . $agentKey);
  $Key = RandomString(6) . md5($params . $keyG) . RandomString(6); // you could put random string to replace '000000'
  // $Key = RandomString(6) . md5($params . $keyG) . RandomString(6); // you could put random string to replace '000000'

  $arr['form_params'] = array(
    'AgentId' => $agentId,
    'Key' => $Key
  );

  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/x-www-form-urlencoded'
    ]
  ]);

  $apiURL .= '?AgentId=' . $agentId . '&Key=' . $Key;
  $res = $client->request('POST', $apiURL, $arr);  //<<<<<<<<<<<<<<<
  $res = json_decode($res->getBody()->getContents(), true);

  return $res;
  // return $apiURL;
}
