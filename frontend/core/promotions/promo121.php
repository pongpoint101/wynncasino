<?php
$cal_percentage =0.02;//2%
$promo_max = 1000;    // ยอดรับโบนัสสูงสุด
$expect_turnover = 0.00;
$turnover_multiplier = 5;
$remark = 'P121';
$channel = 121;
$sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";  
$sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() >= 1) { 
    $res = $res->row_array();
    if ($res['amount']<50) {
        $errCode = 402;
        $errMsg = "รับโปรฯนี้ใช้กับรายการฝากที่เริ่มต้น 50 บาท. ขึ้นไป";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    } 
    if ($res['promo'] != (-1)) {
        $errCode = 402;
        $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    }

    $latest_deposit_amount = $res['amount'];
    $promo_cal_result = round($latest_deposit_amount * $cal_percentage);  // ปัดทศนิยมเป็นจำนวนเต็ม
    $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]); 
    $expect_turnover = ($latest_deposit_amount + $promo_cal_result) * $turnover_multiplier;  // Turnover x 5 
    $data =[
        'member_no' => $member_no,
        'deposit_amount' => $latest_deposit_amount,
        'promo_amount' =>$promo_cal_result, 
        'turnover_expect' => $expect_turnover,
        'status' => 1,
        'accept_date' => date('Y-m-d H:i:s')
        ];  
    $conn->insert('promop121', $data); 
    //Update others promo to completed status preventing confusing
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
} else {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}  
