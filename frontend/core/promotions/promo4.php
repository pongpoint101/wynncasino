<?php 
$current_datetime = date_create(date('Y-m-d H:i:s'));
// $time_1_start = date_create(date('Y-m-d') . ' 07:00:00');
// $time_1_end   = date_create(date('Y-m-d') . ' 08:00:00');
$time_1_start = date_create(date('Y-m-d') . ' 10:00:00');
$time_1_end   = date_create(date('Y-m-d') . ' 12:00:00');
$time_2_start = date_create(date('Y-m-d') . ' 20:00:00');
$time_2_end   = date_create(date('Y-m-d') . ' 22:00:00');

$allowGetPromo = 0;
$remark = 'HappyTime-01';
$expect_turnover = 0.00;
if (($current_datetime >= $time_1_start) && ($current_datetime <= $time_1_end)) {
    $allowGetPromo = 1;
    $channel = 13;
} elseif (($current_datetime >= $time_2_start) && ($current_datetime <= $time_2_end)) {
    $allowGetPromo = 2;
    $channel = 14;
    $remark = 'HappyTime-02';
}

if ($allowGetPromo == 0) {
    $errCode = 402;
    $errMsg = "โปรฯ นี้ สามารถรับได้เฉพาะช่วงเวลา 10.00-12.00 น. และ 20.00-22.00 น. เท่านั้นค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

$sql  = "SELECT * FROM log_deposit WHERE member_no=?";
$sql .= " AND status=1 AND (channel<=3 OR channel=5) ";
$sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
$res =$conn->query($sql,[$member_no]);
if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} else {
    $res = $res->row_array(); 
    if ($res['promo'] != (-1)) {
        $errCode = 402;
        $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
     if ($res['amount'] == 400) {
        $win_expect = 1000;
        $promo_cal_result = 100;
        $withdraw_accept_amount=5000;
    } elseif ($res['amount'] == 200) {
        $win_expect = 500;
        $promo_cal_result = 50;
        $withdraw_accept_amount=2500;
    } else {
        $errCode = 402;
        $errMsg = "สมาชิกยังไม่มีรายการฝากที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ (ยอดฝาก 200 หรือ 400 เท่านั้น)";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    $allowGetPromo = 0;
    $res['update_date'] = date_create($res['update_date']);
    $status = 1;
    if (($res['update_date'] >= $time_1_start) && ($res['update_date'] <= $time_1_end)) {
        $allowGetPromo = 1;
        $channel = 13;
    } elseif (($res['update_date'] >= $time_2_start) && ($res['update_date'] <= $time_2_end)) {
        $allowGetPromo = 2;
        $channel = 14;
        $status = 2;
    }
    if ($allowGetPromo == 0) {
        $errCode = 402;
        $errMsg = "โปรฯ นี้ ใช้สำหรับยอดฝากในช่วงเวลา 10.00-12.00 น. และ 20.00-22.00 น. เท่านั้นค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    $trx_id = $res['trx_id']; 
    $data =[
        'member_no' => $member_no,
        'trx_id' => $trx_id,
        'promo_date' =>date('Y-m-d'), 
        'win_expect' => $win_expect,
        'status' => $status
       ]; 
      $turnover_multiplier=2;    
      $expect_turnover = ($main_wallet + $promo_cal_result) * $turnover_multiplier;  // Turnover x 5  
      $pro_withdraw_accept=$withdraw_accept_amount;
      $pro_withdraw_max_amount=$withdraw_accept_amount; 
      $deposit_id=$trx_id; 
 
      try {
        $conn->insert('promo_happy_time', $data); 
        if ($conn->affected_rows() <= 0) {
           $errCode = 402;
           $errMsg = "ท่านสมาชิกใช้สิทธิ์สำหรับรับโปรฯ ในช่วงเวลานี้ไปแล้วค่ะ";
           echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
         }
      } catch (\Throwable $th) {
        $errCode = 402;
        $errMsg = "ท่านสมาชิกใช้สิทธิ์สำหรับรับโปรฯ ในช่วงเวลานี้ไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }  
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);
}
//Update others promo to completed status 
$conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
$conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
