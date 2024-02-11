<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
$Website = GetWebSites(); 
date_default_timezone_set("Asia/Bangkok");

$db = new DB();
$site = GetWebSites();
$siteID = $site['site_id'];
$arr_return = array();

$task = $_POST['task'];
$member_no = $_SESSION['member_no'];
$member_login = $_SESSION['member_login'];
$data = array();
$data['errCode'] = 0;
$data['errMsg'] = "";
$data['uri_link'] = "";
$allow_channel='1,3,5';
if(@$site['truewallet_is_bonus']==2){
  $allow_channel='1,2,3,5';
}

// $sql = "SELECT * FROM log_deposit WHERE trx_date=? AND amount>=100 AND status=1 AND member_no=?";
// $sql .= " AND channel IN($allow_channel)";
// $res = $db->query($sql, date('Y-m-d'), $member_no);
// if ($res->numRows() <= 0) {
//   $data['errCode'] = 400;
//   $data['errMsg'] = "ต้องมีรายการฝาก 100 บ. ขึ้นไป อย่างน้อย 1 รายการวันเดียวกันที่เปิดไพ่ฯ";
// }

//Verify if user got card game bonus for today
$sql = "SELECT * FROM log_deposit WHERE trx_date=? AND remark='CardGame' AND member_no=?";
$res = $db->query($sql, date('Y-m-d'), $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 401;
  $data['errMsg'] = "เนื่องจากคุณได้ใช้สิทธิ์ของวันนี้ไปแล้ว A";
}

//Verify if user got card game bonus for today
$res = $db->query("SELECT * FROM bonus_cardgame WHERE DATE(update_date)=? AND member_no=?", date('Y-m-d'), $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 401;
  $data['errMsg'] = "เนื่องจากคุณได้ใช้สิทธิ์ของวันนี้ไปแล้ว B";
}

$res = $db->query("SELECT * FROM member_wallet WHERE member_no=? AND main_wallet > 10", $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 402;
  $data['errMsg'] = "เนื่องจากกระเป๋ามีเงินคงเหลือมากกว่า 10 บ.";
}

//Verify if completed 1000 users/day
$res = $db->query("SELECT COUNT(*) as count_rows FROM bonus_cardgame WHERE DATE(update_date)=?", date('Y-m-d'))->fetchArray();
if ($res['count_rows'] >= 1000) {
  $data['errCode'] = 405;
  $data['errMsg'] = "จำนวนผู้รับสิทธิ์สำหรับวันนี้ครบแล้ว (1,000 สิทธิ์) กรุณารอวันต่อไป";
  exit();
}

// Verify if customer taken any promotion
$res = $db->query("SELECT * FROM members WHERE id=?", $member_no)->fetchArray();
if ($res['member_promo'] > 0&&!in_array($res['member_promo'],[8,9])) {
  $data['errCode'] = 403;
  $data['errMsg'] = "สงวนสิทธิ์สำหรับสมาชิกที่ไม่รับโปรฯ เท่านั้น";
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

if (($sum_wd_amount >= $sum_deposit_amount) || ($sum_deposit_amount - $sum_wd_amount < 300)) {
  $data['errCode'] = 407;
  $data['errMsg'] = "ยอดเสียไม่ตรงตามเงื่อนไข (" . 300 . ") บาทขึ้นไป)";
  echo json_encode($data);
  exit();
}

if ($data['errCode'] != 0) { 
  $data['errMsg'] = ($data['errMsg']!="")?$data['errMsg']:"ไม่สามารถเปิดไพ่ลุ้นล้านได้ในขณะนี้";
  echo json_encode($data);
  exit();
}
$data['uri_link'] = "{$Website['random_card_url']}?site={$siteID}&user={$member_no}";
echo json_encode($data);
