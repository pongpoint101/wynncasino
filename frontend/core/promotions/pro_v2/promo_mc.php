<?php  
$startDatedt = strtotime($pro_start);
$endDatedt = strtotime($pro_end);
$usrDatedt = strtotime(date('Y-m-d'));
$pro_deposit_first_day=3;
if(!($usrDatedt >= $startDatedt && $usrDatedt <= $endDatedt)){
  $errCode = 402;
  $errMsg = "หมดเขตรับโปรฯ นี้ไปแล้วค่ะ";   
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
} 
$sql = "SELECT 1 FROM promo_mc25 WHERE member_no=? AND  YEAR(accept_date )=? "; 
$res = $conn->query($sql,[$member_no,date("Y")]);
if ($res->num_rows()> 0) {
$errCode = 402;
$errMsg = "สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
} else {  
require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
if ($res->num_rows() >= 1) { 
    require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$deposit_id]);  
    $data =[
      'member_no' => $member_no,   
      'turnover_expect' => $expect_turnover,  
      'promo_date' => date('Y-m-d') 
    ];  
    $conn->insert('promo_mc25', $data); 
    //Update others promo to completed status preventing confusing 
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
} else {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
} 

