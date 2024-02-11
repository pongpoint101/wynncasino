<?php

// use function GuzzleHttp\Psr7\build_query;

session_start();
require_once '../../dbmodel.php';
require_once '../../function.php';
require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
require_once '../../vendor/autoload.php';
require_once './api/sa-function.php';


$arr = array();

for ($i=0; $i < 10; $i++) {
  $arr[$i]['user'] = 'user' . $i;
  $arr[$i]['password'] = 'password' . $i;
}

echo '<pre>';
var_dump($arr);
echo '</pre>'



// Note: MCRYPT_RIJNDAEL_128 is compatible with AES (all key sizes)

// echo DESEncrypt('g9G16nTs','method=RegUserInfo&Key=2D53BC29E1B84D55AE105416F54381AA&Time=&CurrencyType=THB&Username=5eb2be1752496');

// echo BuildMD5('method=RegUserInfo&Key=2D53BC29E1B84D55AE105416F54381AA&Time=&CurrencyType=THB&Username=5eb2be1752496', 'GgaIMaiNNtg',);
// // use GuzzleHttp\Client;
// $dt = new DateTime("NOW");
// echo $dt->format("YmdHis");

// $res = getSAAuth(1);
// // var_dump($res);
// echo '../vendor/autoload.php';
// $sa_cfg = getSASiteCfg(100, 1); //(site_id,operation_mode) 1=Staging, 2=Production

// $sa_cfg = array('xxx' => 'xxx' , 'yyy' => 'yyy' );
// testTest($sa_cfg);


// $client = new Client();
// $res = $client->request('POST', 'http://ep.myserver.local/staging/sa/GetUserBalance?username=5eb2be1752496&currency=THB');
// echo $res->getStatusCode();
// // "200"
// echo $res->getHeader('content-type')[0];
// // 'application/json; charset=utf8'
// echo $res->getBody();
// {"type":"User"...'

// Send an asynchronous request.
// $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
// $promise = $client->sendAsync($request)->then(function ($response) {
//     echo 'I completed! ' . $response->getBody();
// });
// $promise->wait();

?>