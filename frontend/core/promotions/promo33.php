<?php
$sql = "SELECT 1 FROM promo_casino_100p WHERE member_no=?";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() > 0) {
    $errCode = 402;
    $errMsg = "สมาชิกเคยรับโบนัสนี้ไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
} else { 
    $cal_percentage = 1.0;
    $promo_max = 100;    // ยอดรับโบนัสสูงสุด
    $expect_turnover = 0.00;
    $turnover_multiplier = 12;
    $remark = 'CASINO100';
    $channel = 19;
    $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";
    $sql .= " AND amount=100";
    $sql .= " ORDER BY trx_date DESC,trx_time DESC";
    $res = $conn->query($sql,[$member_no]);
    if ($res->num_rows() >= 1) {
        //     $errCode = 402;
        //     $errMsg = "โปรฯ นี้สำหรับสมาชิกใหม่ฝากครั้งแรกเท่านั้นค่ะ";
        //     echo json_encode(['code' => $errCode, 'msg' => $errMsg]);
        //     exit();
        // } elseif ($res->num_rows == 1) {
        $res = $res->row_array();
        if ($res['promo'] != (-1)) {
            $errCode = 402;
            $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
        }

        $latest_deposit_amount = $res['amount'];
        $promo_cal_result = round($latest_deposit_amount * $cal_percentage);  // ปัดทศนิยมเป็นจำนวนเต็ม
        $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;
        $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);
        $expect_turnover = ($latest_deposit_amount + $promo_cal_result) * $turnover_multiplier;  // Turnover x 12 

        $data =[
            'member_no' => $member_no,
            'deposit_amount' => $latest_deposit_amount,
            'promo_amount' =>$promo_cal_result,
            'turnover_expect' => $expect_turnover,
            'accept_date' => date('Y-m-d H:i:s')
           ]; 
        $conn->insert('promo_casino_100p', $data); 
    } else {
        $errCode = 402;
        $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
}
//Update others promo to completed status preventing confusing
$conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
$conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);

?>