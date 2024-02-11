<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
$db = new DB(); 
$site =GetWebSites();  
require_once './api/ka-function.php';

$sql = "SELECT * FROM m_providers WHERE site_id=? AND operation_mode=? AND provider_code=?";
$res = $db->query($sql, 66, 1, "kas")->fetchArray();
$ka_config = json_decode($res['config_string'], true);

$apiURL = $ka_config['api_url'] . '/gameList'; // 'http://api688.net/seamless/gameList';
$partnerName = $ka_config['partner_name'];
$accessKey = $ka_config['access_key'];
$secretKey = $ka_config['secret_key'];
$lang = "th";
$randomID = mt_rand(1, 999999999);

$arrRequestBody['partnerName'] = $partnerName;
$arrRequestBody['accessKey'] = $accessKey;
$arrRequestBody['language'] = $lang;
$arrRequestBody['randomId'] = $randomID;

$hash = hash_hmac('SHA256', json_encode($arrRequestBody), $secretKey);
$apiURL .= '?hash=' . $hash;

$arrFormParams['json'] = $arrRequestBody;

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json'
  ]
]);

$res = $client->request('POST', $apiURL, $arrFormParams);  //<<<<<<<<<<<<<<<

$res = json_decode($res->getBody()->getContents(), true);

echo "API URL : " . $apiURL . '<BR>' . PHP_EOL;

echo "Parameters :";
print('<pre>');
print_r($arrRequestBody);
print('</pre>');

echo "JSON string :";
print('<pre>');
print_r(json_encode($arrRequestBody));
print('</pre>');

echo 'Hash : ' . $hash . '<BR>' . PHP_EOL;

print('<pre>');
print_r($res);
print('</pre>');

if ($res['status'] != 'ok') {
  echo "Error : No list games (0)";
  exit();
}

echo "List games : " . $res['numGames'];
$db->query("TRUNCATE `ka_list_games`");
foreach ($res['games'] as $key => $value) {
  print('<pre>');
  print_r($value);
  print('</pre>');
  $sql = "INSERT INTO ka_list_games (game_id,game_type,game_name,details,arrival_date)";
  $sql .= " VALUES (?,?,?,?,?)";
  $db->query(
    $sql,
    $value['gameId'],
    $value['gameType'],
    $value['gameName'],
    json_encode($value),
    date('Y-m-d H:i:s')
  );
}
