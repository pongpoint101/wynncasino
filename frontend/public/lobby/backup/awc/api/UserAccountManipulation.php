<?php

session_start();

function RegUserInfo($api_url,$q,$s) {




  // $req_param = array();
  // $req_param['username'] = $_SESSION['username'];
  // $req_param['currency'] = 'THB';
  // $res = null;

  // $SecretKey = $sa_cfg['secret_key']; //SecretKey
  // $md5key = $sa_cfg['md5_key']; // MD5Key
  // $EncryptKey = $sa_cfg['encrypt_key'];
  // $Time = getdate('yyyyMMddHHmmss');
  // $init_amount = 10000.00;
  // $method_string = 'LoginRequestForFun';
  // $currency_type = 'THB';

  // // DES
  // $str = "method=" . $method_string . "&Key=" . $SecretKey . "&Time=" . $Time . "&Amount=" . $init_amount . "&CurrencyType=" . $currency_type;
  // $key = $EncryptKey; // EncryptKey
  // $crypt = new DES($key);
  // $mstr = $crypt->encrypt($str);
  // $urlemstr = urlencode($mstr);
  // echo "[ $str ] Encrypted: [ $mstr ] UrlEncoded encrypted string: [ $urlemstr ]";

  // // MD5
  // $str = "method=" . $method_string . "&Key=" . $SecretKey . "&Time=" . $Time . "&Amount=" . $init_amount . "&CurrencyType=" . $currency_type;
  // $PreMD5Str = $str . $md5key . $Time . $SecretKey;
  // $OutMD5 = md5($PreMD5Str);
  // echo "md5:[ $OutMD5 ]";

}

function VerifyUserName() {

}

function GetUserStatusDV() {

}

function QueryBetLimit() {
  
}

function SetBetLimit() {
  
}

function GetUserMaxBalance() {
  
}

function SetUserMaxBalance() {
  
}

function SetUserMaxWinning() {
  
}


?>