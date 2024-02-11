<?php
//$pro_deposit_first_day=2;
require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php';   
if ($res->num_rows() >= 1) {
  require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';   
  $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$deposit_id]); 
  $data =[
    'member_no' => $member_no,
    'full_amount' =>0,
    'actual_withdraw' =>0,
    'win_expect' => $win_expect,
    'status' => 1,
    'arrival_date'=> date('Y-m-d H:s:i'),
    'update_by'=>'SYSTEM'
   ];  
  $conn->insert('promop52', $data); 
  //Update others promo to completed status preventing confusing
  $conn->query("UPDATE promofree50 SET status=2 WHERE member_no=? AND status!=2",[$member_no]);
  $conn->query("UPDATE promofree60 SET status=2 WHERE member_no=? AND status!=2",[$member_no]);
  $conn->query("UPDATE promop10p SET status=2 WHERE member_no=? AND status!=2",[$member_no]);
} else {
  $errCode = 401;
  $errMsg = "สมาชิกต้องมียอดฝาก จึงจะเลือกรับโปรฯ ได้ค่ะ";
  echojs("ผิดพลาด",$errMsg,1,"error");
}