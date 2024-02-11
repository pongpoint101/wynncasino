<?php
require '../bootstart.php';
require_once ROOT . '/core/db2/db.php';
date_default_timezone_set("Asia/Bangkok");
$db = new DB();

$site = GetWebSites();
$arr_return = array();

// $task = $_POST['task'];
$member_no = $_GET['member'] * 1;
$data = array();
$data['errCode'] = 0;
$data['errMsg'] = "";
$data['level'] = "";
$curr_date = date('Y-m-d');
$allow_channel='1,3,5';
if(@$site['truewallet_is_bonus']==2){
  $allow_channel='1,2,3,5';
}

//Verify if user got luckywheel game bonus for today
$sql = "SELECT * FROM log_deposit WHERE trx_date=? AND channel=9 AND member_no=?"; //remark='LuckyWheel' AND member_no=?";
$res = $db->query($sql, $curr_date, $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 401;
}

//Verify if user got luckywheel game bonus for today
$res = $db->query("SELECT * FROM bonus_luckywheel WHERE DATE(update_date)=? AND member_no=?", $curr_date, $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 401;
}

$res = $db->query("SELECT * FROM member_wallet WHERE member_no=? AND main_wallet > 10", $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 402;
}

// Verify if customer taken any promotion
$res = $db->query("SELECT * FROM members WHERE id=?", $member_no);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  if ($res['member_promo'] > 0&&!in_array($res['member_promo'],[8,9])) {
    $data['errCode'] = 403;
  }
} else {
  $data['errCode'] = 400;
}


// if ($member_no == 100001) {
//   $data['errCode'] = 0;
//   $data['errMsg'] = "";
//   $data['level'] = '5,50';
//   echo json_encode($data);
//   exit();  
// }

if ($data['errCode'] != 0) {
  $data['errMsg'] = "ไม่สามารถหมุนวงล้อมหาโชคได้";
  echo json_encode($data);
  exit();
}

$sql = "SELECT SUM(amount) as sum_deposit_amount FROM log_deposit WHERE trx_date=? AND member_no=?";
$sql .= " AND channel IN($allow_channel)";
$res = $db->query($sql, date('Y-m-d',strtotime("yesterday")), $member_no)->fetchArray();
$sum_deposit_amount = $res['sum_deposit_amount'];

$sql = "SELECT SUM(amount_actual) as sum_wd_amount FROM log_withdraw WHERE trx_date=? AND member_no=?";
$sql .= " AND status=1";
$res = $db->query($sql, date('Y-m-d',strtotime("yesterday")), $member_no);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  $sum_wd_amount = $res['sum_wd_amount'];
}

if (!isset($sum_wd_amount)) {
  $sum_wd_amount = 0;
}

if (($sum_wd_amount >= $sum_deposit_amount) || ($sum_deposit_amount - $sum_wd_amount < $site['min_lose_lucky_spin_reward'])) {
  $data['errCode'] = 400;
  $data['errMsg'] = "ยอดเสียไม่ตรงตามเงื่อนไข (" . $site['min_lose_lucky_spin_reward'] . ") ขึ้นไป)";
  echo json_encode($data);
  exit();
}

$win_lose = $sum_deposit_amount - $sum_wd_amount;
if ($win_lose >= 1000 && $win_lose <= 1999) {
  $data['level'] = '5,50';
}
if ($win_lose >= 2000 && $win_lose <= 2999) {
  $data['level'] = '5,50';
}
if ($win_lose >= 3000 && $win_lose <= 3999) {
  $data['level'] = '5,50,100,150';
}
if ($win_lose >= 4000 && $win_lose <= 4999) {
  $data['level'] = '5,100,150,200';
}
if ($win_lose >= 5000 && $win_lose <= 7999) {
  $data['level'] = '5,150,200';
}
if ($win_lose >= 8000 && $win_lose <= 9999) {
  $data['level'] = '5,100,150,200';
}
if ($win_lose >= 10000 && $win_lose <= 49999) {
  $data['level'] = '5,150,200,500';
}
if ($win_lose >= 50000 && $win_lose <= 99999) {
  $data['level'] = '5,150,500,1000';
}
if ($win_lose >= 100000) {
  $data['level'] = '5,500,1000';
}

$_SESSION['luckywheel_level'] = $data['level'];

echo json_encode($data);
