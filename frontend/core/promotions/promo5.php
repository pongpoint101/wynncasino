<?php
$dateNow = date_create(date('Y-m-d'));
$dateLaunch = date_create('2021-02-01');
if ($dateNow < $dateLaunch) {
    // echo json_encode(['code' => 402, 'msg' => 'โปรฯ นี้เริ่มใช้วันที่ 01/02/2021 เป็นต้นไป']); // exit();
}
$monthVar = date('m');
$yearVar = date('Y');
switch ($monthVar) {
    case '01':
        $monthVar = '12';
        $yearVar = (int) $yearVar - 1;
        break;
    case '02':
        $monthVar = '01';
        break;
    case '03':
        $monthVar = '02';
        break;
    case '04':
        $monthVar = '03';
        break;
    case '05':
        $monthVar = '04';
        break;
    case '06':
        $monthVar = '05';
        break;
    case '07':
        $monthVar = '06';
        break;
    case '08':
        $monthVar = '07';
    case '09':
        $monthVar = '08';
        break;
    case '10':
        $monthVar = '09';
        break;
    case '11':
        $monthVar = '10';
        break;
    case '12':
        $monthVar = '11';
        break;
}
$sql = "SELECT * FROM promo_cc200 WHERE member_no=? AND YEAR(promo_date)='" . date('Y') . "' AND MONTH(promo_date)='" . date('m') . "'";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() != 0) {
    $errCode = 402;
    $errMsg = "ท่านสมาชิกรับโบนัสสำหรับเดือนนี้ไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
$sql  = "SELECT * FROM log_deposit WHERE member_no=?";
$sql .= " AND status=1 AND (channel<=3 OR channel=5) AND amount=100 AND YEAR(trx_date)='" . date('Y') . "' AND MONTH(trx_date)='" . date('m') . "'";
$sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ (ยอดฝาก 100 บ. เท่านั้น)";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} else {
    $res = $res->row_array(); 
    if ($res['promo'] != (-1)) {
        $errCode = 402;
        $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
}
$sql  = "SELECT SUM(amount) AS sum_amount FROM log_deposit WHERE member_no=?";
$sql .= " AND status=1 AND channel<=3 AND YEAR(trx_date)=" . $yearVar . " AND MONTH(trx_date)=" . $monthVar;
// $sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
$res = $conn->query($sql,[$member_no])->row_array();
$sum_amount = (is_null($res['sum_amount'])) ? 0 : $res['sum_amount'];
if ($sum_amount < 5000) {
    $sql  = "SELECT SUM(amount) AS sum_amount FROM log_deposit_backup WHERE member_no=?";
    $sql .= " AND status=1 AND channel<=3 AND YEAR(trx_date)=" . $yearVar . " AND MONTH(trx_date)=" . $monthVar;
    $res2 = $conn->query($sql,[$member_no])->row_array(); 
    $sum_amount2 = (is_null($res2['sum_amount'])) ? 0 : $res2['sum_amount'];
    if ($sum_amount + $sum_amount2 < 5000) {
        $errCode = 402;
        $errMsg = "ท่านสมาชิกมียอดสะสมเดือนที่ผ่านมาน้อยกว่า 5,000 บ. ค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
}

$promo_cal_result = 0;
$expect_turnover = 0.00;
$channel = 16;
$trx_id = $res['trx_id'];

if ($sum_amount >= 5000 && $sum_amount <= 9999) {
    $promo_cal_result = 100;
    $remark = 'CC100';
    $promo_id = 21;
}
if ($sum_amount >= 10000 && $sum_amount <= 19999) {
    $promo_cal_result = 120;
    $remark = 'CC120';
    $promo_id = 22;
}
if ($sum_amount >= 20000 && $sum_amount <= 49999) {
    $promo_cal_result = 150;
    $remark = 'CC150';
    $promo_id = 23;
}
if ($sum_amount >= 50000) {
    $promo_cal_result = 200;
    $remark = 'CC200';
    $promo_id = 24;
}
$win_expect = (100 + $promo_cal_result) * 2; 
$data =[
    'member_no' => $member_no,
    'deposit_amount' => 100,
    'amount' => $promo_cal_result, // amount = promo_amount
    'turnover_expect' => $win_expect,
    'status' => 1,
    'promo_date' =>date('Y-m-d') 
   ]; 
$conn->insert('promo_cc200', $data);

$conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
$conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
