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
        break;
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

$sql = "SELECT * FROM pro_promotion_detail WHERE pro_group_id=? AND pro_status=1 ORDER BY pro_deposit_start_amount;";  
$datakey='promo:local:'.$pro_group_id; 
$promo_local_detail=GetDataSqlWhereAll($datakey,$sql,[$pro_group_id],5*60);

$sql  = "SELECT * FROM log_deposit WHERE member_no=?";
$sql .= " AND status=1 AND channel IN($allow_channel) AND amount=? AND YEAR(trx_date)='" . date('Y') . "' AND MONTH(trx_date)='" . date('m') . "'";
$sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
$res = $conn->query($sql,[$member_no,$promo_detail['pro_last_deposit_amount']]);
if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ (ยอดฝาก {$promo_detail['pro_last_deposit_amount']} บ. เท่านั้น)";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} else {
    $res = $res->row_array(); 
    if ($res['promo'] != (-1)) {
        $errCode = 402;
        $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    $trx_id = $res['trx_id']; 
}
$sql  = "SELECT SUM(amount) AS sum_amount FROM log_deposit WHERE member_no=?";
$sql .= " AND status=1 AND channel IN($allow_channel) AND YEAR(trx_date)=" . $yearVar . " AND MONTH(trx_date)=" . $monthVar; 
$res = $conn->query($sql,[$member_no])->row_array();
$sum_amount = (is_null($res['sum_amount'])) ? 0 : $res['sum_amount'];
if ($sum_amount < $promo_detail['pro_deposit_start_amount']) {
    $sql  = "SELECT SUM(amount) AS sum_amount FROM log_deposit_backup WHERE member_no=?";
    $sql .= " AND status=1 AND channel IN($allow_channel) AND YEAR(trx_date)=" . $yearVar . " AND MONTH(trx_date)=" . $monthVar;
    $res2 = $conn->query($sql,[$member_no])->row_array(); 
    $sum_amount2 = (is_null($res2['sum_amount'])) ? 0 : $res2['sum_amount'];
    if ($sum_amount + $sum_amount2 < $promo_detail['pro_deposit_start_amount']) {
        $errCode = 402;
        $errMsg = "ท่านสมาชิกมียอดสะสมเดือนที่ผ่านมาน้อยกว่า ".number_format($promo_detail['pro_deposit_start_amount'])." บ. ค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
}
  
$allowGetPromo = 0;
foreach ($promo_local_detail as $k => $v) {  
    if ($sum_amount >= $v['pro_deposit_start_amount']&&$sum_amount <=$v['pro_deposit_end_amount']||$sum_amount >= $v['pro_deposit_start_amount']&&$v['pro_deposit_end_amount']=-1) {  
        $bonus_amount=$v['pro_bonus_amount'];  
        $promo_max =$v['pro_bonus_max'];    // ยอดรับโบนัสสูงสุด 
        $remark = $v['pro_symbol']; 
        $pro_turnover_amount=$v['pro_turnover_amount'];    
        $pro_id=$v['pro_id']; 
        $pro_group_id=$v['pro_group_id'];  
        $pro_name=$v['pro_name'];
        $pro_cat_id=$v['pro_cat_id'];
        $pro_cat_name=$v['pro_cat_name']; 
        $pro_deposit_expire=$v['pro_deposit_expire']; 
        $channel =$v['channel'];

        $promo_cal_result = $bonus_amount;  
        $allowGetPromo = 1; 
      }
 }
 if ($allowGetPromo == 0) {
    $errCode = 402;
    $errMsg = "ท่านสมาชิกมียอดสะสมเดือนที่ผ่านมาไม่ตรงตามเงื่อนไข!";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}   

require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';   
$data =[
    'member_no' => $member_no,
    'deposit_amount' => $promo_cal_result,
    'amount' => $promo_cal_result, // amount = promo_amount
    'turnover_expect' => $win_expect,
    'status' => 1,
    'promo_date' =>date('Y-m-d') 
   ];  
$conn->insert('promo_cc200', $data);

$conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
$conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
