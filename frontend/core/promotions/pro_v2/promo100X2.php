<?php  
require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
if ($res->num_rows() >= 1) { 
    require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$deposit_id]);   
    //Update others promo to completed status preventing confusing 
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
} else {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
