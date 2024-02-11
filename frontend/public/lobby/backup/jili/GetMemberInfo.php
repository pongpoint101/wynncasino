<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site = GetWebSites();  
require_once './api/jili-function.php';

$arrConfig =getProviderConfig('jili', 66, 1);

date_default_timezone_set('Etc/GMT+4');
$agentKey = $arrConfig['agent_key'];
$agentId = $arrConfig['agent_id'];
$params = 'AgentId=' . $agentId;
$apiURL = $arrConfig['api_url'] . '/api1/GetMemberInfo';

$now = date("ymj");
$keyG = md5($now . $agentId . $agentKey);
$Key = RandomString(6) . md5($params . $keyG) . RandomString(6); // you could put random string to replace '000000'

echo '1. apiKey : ' . $agentKey . PHP_EOL . '<BR>';
echo '2. agentId : ' . $agentId . PHP_EOL . '<BR>';
echo '3. params : ' . $params . PHP_EOL . '<BR>';
echo '4. keyG=md5(' . $now . $agentId . $agentKey . ') : ' . $keyG . PHP_EOL . '<BR>';
echo '5. Key=rand(6)md5(' . $params . $keyG . ')rand(6) : ' . $Key . PHP_EOL . '<BR>';

echo '6. GetMemberInfo : ' . $arrConfig['api_url'] . '/api1/GetMemberInfo?AgentId=' . $params . '&Key=' . $Key;

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

$apiURL .= '?AgentId=' . $params . '&Key=' . $Key;
$res = $client->request('POST', $apiURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);

echo '<pre>';
print_r($res);
echo '</pre>';
