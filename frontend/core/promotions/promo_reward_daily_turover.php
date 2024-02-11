<?php
$sql = "SELECT 1 FROM log_deposit WHERE  member_no=? AND promo=51 AND trx_date=SUBDATE(CURDATE(),0);";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() > 0) {
  $errCode = 402;
  $errMsg = "วันนี้สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
  exit();
} else {  
  $promo_cal_result=0;
  $turnover_multiplier =5;
  $expect_turnover = 0.00;
  $cal_percentage =1;
  $promo_max = 1000;    // ยอดรับโบนัสสูงสุด 
  $remark = 'DAILY_TO_RD';
  $channel = 51; 
  $sql = "SELECT * FROM pro_reward_daily_turover WHERE member_no=?  AND  trx_date = SUBDATE(CURDATE(),1) AND status=1 LIMIT 1;"; 
  $res = $conn->query($sql,[$member_no]);
  if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    exit(); 
  } else {
    $res = $res->row_array();
    if ($res['status'] != (1)) {
      $errCode = 402;
      $errMsg = "รายการนี้ถูกใช้ไปแล้วค่ะ";
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
      exit();
    } 
    $latest_deposit_amount = $res['amount']; 
    $promo_cal_result = $latest_deposit_amount;   
    $expect_turnover = ($main_wallet+$promo_cal_result) * $turnover_multiplier;  // Turnover x 5 

    $conn->query('UPDATE pro_reward_daily_turover SET status=3,turnover_expect=? WHERE status!=3 AND id=?', [$expect_turnover,$res['id']]);     
  
    //อัพเดทโปรโมชั่นอื่นๆ ให้สถานะเสร็จสมบูรณ์ ป้องกันการสับสน 
    // $conn->query("UPDATE promo_hny50 SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
  	// $conn->query("UPDATE promop50p SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
		// $conn->query("UPDATE promo_happy_time SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promo_cc200 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promop120 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promop121 SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
    // $conn->query("UPDATE promop10p SET status=2 WHERE status=1 AND member_no=?",[$member_no]);  

		$conn->query("UPDATE promofree50 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		$conn->query("UPDATE promofree60 SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
	
  }
}

