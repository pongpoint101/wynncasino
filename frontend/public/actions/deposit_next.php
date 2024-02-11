<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
header('Content-Type: application/json');
$member_no = $_SESSION['member_no'];
$sqlMember = "SELECT stm_ref_id,account_no,amount,confirmed_site,confirmed_member_no FROM log_deposit_multiple_vizpay 
     WHERE account_no=? AND bank_name=? AND (confirmed_site IS NULL OR confirmed_site = '') AND (confirmed_member_no IS NULL OR confirmed_member_no = '') "; // AND status='1'";
if (@$_GET['type'] == 'pull') {
  $arr_val = [];
  $rowvizpay = $conn->query($sqlMember, [$_SESSION['bank_accountnumber'], $_SESSION['bank_name']])->row_array();
  $arr_val['v_deposit_amount'] = isset($rowvizpay['amount']) ? $rowvizpay['amount'] : 0;
  echo json_encode($arr_val);
  exit();
}
if (isset($limiter)) {
  $limiterns = $limiter->hit('requestdeposit_next:' . $member_no, 1, 5); // เรียกได้ 1 ครั้งใน 5 วินาที
  if ($limiterns['overlimit']) {
    InSertLogSys(['member_no' => $member_no, 'ip' => getIP(), 'log_type' => 'deposit_next-maxlimit', 'txt_data' => date('Y-m-d') . ' ' . date('H:i:s')]);
    exit(0);
  }
}
if (!($_SERVER['REQUEST_METHOD'] === 'POST')) {
  exit();
}
$WebSites = GetWebSites();
try {
  $targetURL = $WebSites['vz_confirmdeposit'];
  $rowvizpay = $conn->query($sqlMember, [$_SESSION['bank_accountnumber'], $_SESSION['bank_name']])->row_array();
  if (!isset($rowvizpay['account_no'])) {
    throw new Exception("203");
  }
  $client = new GuzzleHttp\Client([
    'exceptions'       => true,
    'verify'           => false,
    'headers'          => [
      'Content-Type'   => 'application/json'
    ]
  ]);
  $req['stm_ref_id'] = $rowvizpay['stm_ref_id'];
  $req['site_id'] = $WebSites['site_id'];
  $req['member_no'] = $member_no;
  $arr['body'] = json_encode($req);
  $res = $client->request('POST', $targetURL, $arr);
  $res = json_decode($res->getBody()->getContents(), true);
  if (isset($res['success'])) {
    if ($res['success']) {
      $datares['msg_error'] = (@$res['success'] ? '200' : 500);
    } else {
      throw new Exception('404');
    }
  } else {
    throw new Exception('404');
  }
} catch (Exception $e) {
  $datares['msg_error'] = $e->getMessage();
  if (strpos($datares['msg_error'], 'success') && strpos($datares['msg_error'], '500 Internal Server Error')) {
    $datares['msg_error'] = '200';
  }
}
echo json_encode($datares);
// exit();
