<?php  

if (($row['main_wallet']>1) ) {
    $errCode = 403;
    $errMsg = "สมาชิกไม่สามารถรับโปรฯ ได้ เนื่องจากมียอดเงินคงเหลือมากกว่า ( 1 บ.)";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

$sqlx="
SELECT sum(amount) AS total_deposit FROM log_deposit
WHERE member_no=? AND channel IN($allow_channel) AND status=1  
;";
$res = $conn->query($sqlx, [$member_no])->row_array();  
$total_deposit = @$res['total_deposit']; 
 if($total_deposit<100){
    $errCode = 402;
    $errMsg = "โปรนี้เฉพาะลูกค้าที่มีประวัติฝากเงิน 100 บาทขึ้นไป";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}   
$latest_deposit_amoun=0;
require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';  
$deposit_id=uniqid(); 