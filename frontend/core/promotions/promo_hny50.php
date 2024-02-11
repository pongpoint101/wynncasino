<?php
$sql = "SELECT * FROM promo_hny50 WHERE member_no=? AND accept_date='" . date('Y-m-d') . "'";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() > 0) {
  $errCode = 402;
  $errMsg = "วันนี้สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
  exit();
} else {  
  $promo_cal_result=0;
  $turnover_multiplier =2;
  $expect_turnover = 0.00;
  $cal_percentage =1;
  $promo_max = 50;    // ยอดรับโบนัสสูงสุด 
  $remark = 'HNY50';
  $channel = 30;

  $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";
  $sql .= " AND amount>=100 AND trx_date='" . date('Y-m-d') . "'"; 
  $sql .= " ORDER BY trx_date DESC,trx_time DESC";
  $res = $conn->query($sql,[$member_no]);
  if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    exit(); 
  } else {
    $res = $res->row_array();
    if ($res['promo'] != (-1)) {
      $errCode = 402;
      $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
      exit();
    }

    $latest_deposit_amount = $res['amount'];  
    $promo_cal_result = $promo_max; 
    $expect_turnover = ($latest_deposit_amount + $promo_cal_result) * $turnover_multiplier;  // Turnover x 2

    $datapro =[ 
      'member_no' => $member_no, 
      'deposit_amount' => $latest_deposit_amount, 
      'promo_amount' =>$promo_cal_result,  
      'turnover_expect' => $expect_turnover, 
      'status' => 1, 
      'accept_date' => date('Y-m-d H:i:s') 
     ];  
     $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);
     $conn->insert('promo_hny50', $datapro); 
     //อัพเดทโปรโมชั่นอื่นๆ ให้สถานะเสร็จสมบูรณ์ ป้องกันการสับสน
     $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
     $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
  }
}

