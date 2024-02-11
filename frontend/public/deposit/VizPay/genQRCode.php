<?php
require_once '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once '../../../core/db2/db.php';
$WebSites = GetWebSites();
$sitetitle = 'QR Code';
include_once './config/Database.php';
include_once './models/Qr_Deposite_Log.php';
include_once './models/Vizpay.php';

$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
  ]
]);

if (isset($_POST['amount']) && isset($_SESSION['member_no'])) {
  $amount = $_POST['amount'];
  $site_id = strtoupper(WEBSITE);
  $memberNo = $_SESSION['member_no'];
  $memberUsername = $site_id . "_" . $memberNo;

  try {
    // Instantiate DB & connect
    // $database = new Database(DBHost, DBName, DBUser, DBPassword);
    // $db = $database->connect();
    // $qr_deposite_log = new Qr_Deposite_Log($db);

    $db2 = new DB();
    $resMember = $db2->query("SELECT * FROM members WHERE id=?", $memberNo)->fetchArray();
    $targetURL = 'http://paymenthub.apiplaza.local/VizPay/api/genQRCode';
    $arr['body'] = json_encode([
      'site_id' => $site_id,
      'member_no' => $memberUsername,
      'member_name' => $resMember['full_name'],
      'amount' => $amount,
      'bank_code' => $resMember['bank_code'],
      'bank_account' => $resMember['bank_accountnumber']
    ]);
    // file_put_contents('genQR.txt', 'body : ' . $arr['body'] . PHP_EOL, FILE_APPEND);

    $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
    $result =  json_decode($res->getBody()->getContents());
    // file_put_contents('genQR.txt', 'result : ' . json_encode($result) . PHP_EOL, FILE_APPEND);

    if ($result->error !== 0) {
      $qrURL = $result['result']['image'];
      echo json_encode([
        'code' => 0,
        'qr_url' => $qrURL,
        'bank_name' => $resMember['bank_name'],
        'bank_account' => $resMember['bank_accountnumber'],
        'date_time' => date('YmdHis'),
        'cust_name' => $resMember['full_name']
      ]);
    } else {
      echo json_encode([
        'code' => 404,
        'msg' => 'Can not generate QR code.',
      ]);
    }
  } catch (Exception $error) {
    // file_put_contents('genQR.txt', 'error : ' .  $error->getMessage() . PHP_EOL . PHP_EOL, FILE_APPEND);
    echo json_encode([
      'code' => 504,
      'msg' => $error->getMessage(),
    ]);
  }
  $db2->close();
}
