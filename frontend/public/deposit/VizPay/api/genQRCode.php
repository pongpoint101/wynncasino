<?php
require '../../../bootstart.php';
require_once ROOT . '../core/security.php';
require_once '../../../../core/db2/db.php';
$WebSites = GetWebSites();
$sitetitle = 'QR Code';
include_once '../config/Database.php';
include_once '../models/Qr_Deposite_Log.php';
include_once '../models/Vizpay.php';

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
  ]
]);

$siteID = strtoupper($WebSites['site_id']);
$targetURL = 'http://paymenthub.apiplaza.local/GetConfig?site=' . $siteID;
$res = $client->request('GET', $targetURL);  //<<<<<<<<<<<<<<<
$pmConfig =  json_decode($res->getBody()->getContents());
$config = [
  'api_key' => $pmConfig->api_key,
  'secret_key' => $pmConfig->secret_key,
  'version' => $pmConfig->api_version,
  'api_url' => $pmConfig->api_url,
  'callback_url' => $pmConfig->callback_url
];

if (isset($_POST['amount']) && isset($_SESSION['member_no'])) {
  $db = new DB();
  $sql = "SELECT * FROM members WHERE id=?";
  $res = $db->query($sql, $_SESSION['member_no'])->fetchArray();
  $bankCode = $res['bank_code'];

  $amount = $_POST['amount'];
  $member_no = $siteID . "_" . $_SESSION['member_no'];
  $ref_account_no = $_SESSION['bank_accountnumber'];
  $ref_bank_code = $bankCode;
  $ref_name = $_SESSION['member_name'];
  $trx_id = uniqid();
  $vizpay = new Vizpay($config);

  try {
    $targetURL = 'http://paymenthub.apiplaza.local/GenToken?site=' . $siteID;
    $res = $client->request('GET', $targetURL);  //<<<<<<<<<<<<<<<
    $response =  $res->getBody()->getContents();
    $token = json_decode($response, true);
    $callback_url = $config['callback_url'] . '?token=' . $token['token_string'];

    $db2 = new DB();
    $resMember = $db2->query("SELECT * FROM members WHERE id=?", $_SESSION['member_no'])->fetchArray();

    $post_data = [
      "order_id" => $trx_id,
      "user_id" =>  $member_no,
      "amount" => $amount,
      "ref_name" => $resMember['full_name'],
      "ref_account_no" => $resMember['bank_accountnumber'],
      "ref_bank_code" => $resMember['bank_code'],
      "callback_url" => $callback_url
    ];

    $post_data['signature'] = $vizpay->gen_signature($post_data);
    $client = new GuzzleHttp\Client([
      'exceptions'       => false,
      'verify'           => false,
      'headers'          => [
        'Content-Type'   => 'application/json',
        'Authorization' => 'Basic ' . base64_encode($pmConfig->api_key . ':')
      ],
    ]);
    $arr['body'] = json_encode($post_data);
    $targetURL = $pmConfig->api_url . $pmConfig->api_version . '/deposit/qrcode';
    $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
    $result =  json_decode($res->getBody()->getContents(), true);

    if ($result['error'] !== 0) {
      $sql = "INSERT INTO log_gen_qrcode_vizpay (memberNo,refID,amountReq,amountGen,";
      $sql .= "qrImage,create_time,expire_time,reqJSON,resJSON)";
      $sql .= " VALUES (?,?,?,?,?,?,?,?,?)";
      $db2->query(
        $sql,
        $_SESSION['member_no'],
        $result['result']['ref_id'],
        $amount,
        $result['result']['amount'],
        $result['result']['image'],
        date('Y-m-d H:i:s'),
        $result['result']['timeout']['days'] . ' ' . $result['result']['timeout']['time'],
        json_encode($post_data, JSON_UNESCAPED_UNICODE),
        json_encode($result, JSON_UNESCAPED_UNICODE)
      );

      $qr_url = $result['result']['image'];
      echo json_encode([
        'code' => 0,
        'qr_url' => $qr_url,
        'bank_name' => $_SESSION['bank_name'],
        'bank_account' => $_SESSION['bank_accountnumber'],
        'date_time' => date('YmdHis'),
        'cust_name' => $resMember['full_name']
      ]);
    } else {
      echo json_encode([
        'code' => 404,
        'msg' => 'Can not generate QR code.',
      ]);
      $sql = "INSERT INTO log_gen_qrcode_vizpay (memberNo,refID,amountReq,create_time,reqJSON,resJSON)";
      $sql .= " VALUES (?,?,?,?,?)";
      $db2->query(
        $sql,
        $_SESSION['member_no'],
        $result['result']['ref_id'],
        $amount,
        date('Y-m-d H:i:s'),
        json_encode($post_data, JSON_UNESCAPED_UNICODE),
        json_encode($result, JSON_UNESCAPED_UNICODE)
      );
    }
  } catch (Exception $error) {
    echo json_encode([
      'code' => 504,
      'msg' => $error->getMessage(),
    ]);
    file_put_contents("vizpay-genqrcode.log", date('Y-m-d H:i:s') . " (error 504): " . $error->getMessage() . PHP_EOL . 'authen : ' . $authen . PHP_EOL, FILE_APPEND);
  }
  $db2->close();
}
