<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
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
$data['level'] = "";
$data['uri_link'] = "";
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
  $data['errMsg'] = "เนื่องจากคุณได้ใช้สิทธิ์ของวันนี้ไปแล้ว";
}

//Verify if user got luckywheel game bonus for today
$res = $db->query("SELECT * FROM bonus_luckywheel WHERE DATE(update_date)=? AND member_no=?", $curr_date, $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 401;
  $data['errMsg'] = "เนื่องจากคุณได้ใช้สิทธิ์ของวันนี้ไปแล้ว";
}

$res = $db->query("SELECT * FROM member_wallet WHERE member_no=? AND main_wallet > 10", $member_no);
if ($res->numRows() > 0) {
  $data['errCode'] = 402;
  $data['errMsg'] = "เนื่องจากกระเป๋ามีเงินคงเหลือมากกว่า 10 บ.";
}


// Verify if customer taken any promotion
$res = $db->query("SELECT * FROM members WHERE id=?", $member_no)->fetchArray();
if ($res['member_promo'] > 0&&!in_array($res['member_promo'],[8,9])) {
  $data['errCode'] = 403;
  $data['errMsg'] = "สงวนสิทธิ์สำหรับสมาชิกที่ไม่รับโปรฯ เท่านั้น";
}
 
if ($data['errCode'] != 0) {
  $data['errMsg'] = ($data['errMsg']!="")?$data['errMsg']:"ไม่สามารถหมุนวงล้อมหาโชคได้";
  echo json_encode($data);
  exit();
}

$sql = "SELECT SUM(amount) as sum_deposit_amount FROM log_deposit WHERE trx_date=? AND member_no=?";
$sql .= " AND channel IN($allow_channel)";
$res = $db->query($sql, date('Y-m-d',strtotime("yesterday")), $member_no)->fetchArray();
$sum_deposit_amount = $res['sum_deposit_amount'];

$sql = "SELECT SUM(amount_actual) as sum_wd_amount FROM log_withdraw WHERE trx_date=? AND member_no=?";
$sql .= " AND status=1";
$res = $db->query($sql,date('Y-m-d',strtotime("yesterday")), $member_no);
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

// $res = $db->query("SELECT * FROM m_site")->fetchArray();
$data['uri_link'] = $site['lucky_spin_url'] . "?site={$siteID}&user={$member_no}&min_loss={$site['min_lose_lucky_spin_reward']}";
echo json_encode($data);
