<?php
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';  
require_once './api/pg-function.php'; 
$site =GetWebSites(); 

$protocal = 'http://';
if (!empty($_SERVER['HTTPS'])) {
  $protocal = 'https://';
}

$pg_cfg = getProviderConfig('pgs', 66, 1);

$api_url = $pg_cfg['api_domain'] . '/Game/v2/Get?trace_id=' . GUID();

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/x-www-form-urlencoded'
  ]
]);

$arr['form_params'] = array(
  'secret_key'      => $pg_cfg['secret_key'],
  'operator_token'  => $pg_cfg['operator_token'],
  'currency'        => 'THB',
  // 'status'          => 1,
);

$res = $client->request('POST', $api_url, $arr);  //<<<<<<<<<<<<<<<
$dataEng = json_decode($res->getBody()->getContents(), true);

$arr['form_params'] = array(
  'secret_key'      => $pg_cfg['secret_key'],
  'operator_token'  => $pg_cfg['operator_token'],
  'currency'        => 'THB',
  'status'          => 1,
  'language'        => 'th-th'
);
$res = $client->request('POST', $api_url, $arr);  //<<<<<<<<<<<<<<<
$dataTha = json_decode($res->getBody()->getContents(), true);
$dataTha = $dataTha['data'];

if (count($dataEng) > 0) {
  $i = 0;
  foreach ($dataEng['data'] as $key => $value) {
    $arrData[$i]['gameId'] = $value['gameId'];
    $arrData[$i]['gameCode'] = $value['gameCode'];
    $arrData[$i]['gameNameEng'] = $value['gameName'];
    $ThaiName = $value['gameName'];
    for ($j = 0; $j < count($dataTha); $j++) {
      if ($dataTha[$j]['gameId'] == $value['gameId']) {
        $ThaiName = $dataTha[$j]['gameName'];
      }
    }
    $arrData[$i]['gameNameTha'] = $ThaiName; 
    $data = array(
      'id' => $arrData[$i]['gameId'],
      'game_code' => $arrData[$i]['gameCode'],
      'game_name_eng' => $arrData[$i]['gameNameEng'],
      'game_name_tha' => $arrData[$i]['gameNameTha'],
      'status' =>$value['status']
     ); 
     $conn->insert('pg_list_games', $data);

    $i++;
  }
  echo "<pre>";
  print_r($arrData);
  echo "</pre>";
}
