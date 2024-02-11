<?php
require '../bootstart.php';
date_default_timezone_set("Asia/Bangkok");
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: " . @$_SERVER['HTTP_ORIGIN'] . "");
if (GetFullDomain() != @$_SERVER['HTTP_ORIGIN']) {
  exit();
}
if(@$_POST['choose_bank']==2){
  $key = 'MB_databankverrity:tw' . $_POST['truewallet']; 
  $res = GetCachedata($key);
if (!$res) {
  $tw=GetTrueWallet();
  $tw_Url=$tw['twURL'];;
  $tw_Number=$tw['twNumber'];
  $tw_Name=$tw['twName']; 
  $targetURL =$tw_Url;  
  $datares=['code'=>404,'firstName'=>'','lastName'=>'','fullName'=>''];
   try {
      $client = new GuzzleHttp\Client([
      'exceptions'       => false,
      'http_errors' => false,
      'verify'           => false,
      'timeout' => 15, // Response timeout
      'connect_timeout' => 15, // Connection timeout
      'headers'          => [
        'Content-Type'   => 'application/json'
      ]
    ]);
    $req['wallet_acc'] = $tw_Number;
    $req['type'] = 'getname';
    $req['customer_phone'] =@$_POST['truewallet'];
    $arr['body'] = json_encode($req);
    $res = $client->request('POST', $targetURL, $arr); 
    if($res->getStatusCode()==200){
      $res = $res->getBody()->getContents(); 
      $res = (array)@json_decode($res); 
      $t=explode(' ',@$res['message']);
      $datares=['code'=>0,'firstName'=>@$t[0],'lastName'=>@$t[1],'fullName'=>@$res['message']];
      SetCachedata($key, json_encode($datares), 30 * 60);
      $res = json_encode($datares);
    }else{
      throw new Exception("Error 404 ");
    }
   } catch(Exception $e) {
    $datares['msg_error']=$e->getMessage();
    $res = json_encode($datares); 
   }  
} 
$_SESSION['verifybank'] = $res;
$_SESSION['bank_account'] = '';
$_SESSION['bank_code'] = '';

}else{
  $key = 'MB_databankverrity:' . $_POST['bank_code'] . $_POST['bank_acct']; 
  $res = GetCachedata($key);
if (!$res) {
  // $targetURL = "https://services.12pay.org/VerifyAccount?bankcode=" . $_POST['bank_code'] . '&bankacct=' . $_POST['bank_acct'];
  $targetURL = "http://services.12pay.local/VerifyAccount?bankcode=" . $_POST['bank_code'] . '&bankacct=' . $_POST['bank_acct'];
  $client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'http_errors' => false,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/json'
    ]
  ]);
  $res = $client->request('GET', $targetURL);
  $res = $res->getBody()->getContents();
  $res = (array)@json_decode($res);
  if (@$res['firstName'] != null && @$res['firstName'] != '') {
    // if(@$res['prefix']!=''&&$res['prefix']!=null){$res['firstName']=$res['prefix'].' '.$res['firstName'];}
    SetCachedata($key, json_encode($res), 30 * 60);
  }
  $res = json_encode($res);
}
$_SESSION['verifybank'] = $res;
$_SESSION['bank_account'] = $_POST['bank_acct'];
$_SESSION['bank_code'] = $_POST['bank_code'];
}
echo $res;
