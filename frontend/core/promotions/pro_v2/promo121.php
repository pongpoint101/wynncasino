<?php
require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
if ($res->num_rows() >= 1) { 
    $res = $res->row_array();
    if ($res['amount']< $pro_deposit_start_amount) {
        $errCode = 402;
        $errMsg = "รับโปรฯนี้ใช้กับรายการฝากที่เริ่มต้น {$pro_deposit_start_amount} บาท. ขึ้นไป";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    }   
    require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php'; 
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$deposit_id]);  
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
