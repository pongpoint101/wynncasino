<?php 
$row = getMemberWallet($member_no);
$main_wallet = $row['main_wallet'];
if($row['main_wallet'] > $min_autodeposit){ 
  $errCode = 402;
  $errMsg = "ท่านสมาชิกมีเงินคงเหลือในกระเป๋าหลักมากกว่า $min_autodeposit บ."; 
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
$sql = "SELECT 1 FROM log_deposit WHERE  member_no=? AND promo=19 AND trx_date=SUBDATE(CURDATE(),0);";
$res = $conn->query($sql,[$member_no]);

if ($res->num_rows() > 0) {
  $errCode = 402;
  $errMsg = "วันนี้สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
  exit();
} else {   
  $sql = "SELECT * FROM rewardmaxplayer_daily WHERE member_no=?  AND  trx_date = SUBDATE(CURDATE(),1) AND status=1 ORDER BY id DESC LIMIT 1;"; 
  $res = $conn->query($sql,[$member_no]);
  if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มียอดโบนัสที่รับได้ตอนนี้!";
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
    require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';
    $trx_id=uniqid();
    $deposit_id=-1;
    $reward_id=$res['id'];   
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

