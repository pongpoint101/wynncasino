<?php  
if (@$claimtype<=0||!isset($_SESSION['member_no'])) {
  $errCode = 402;
  $errMsg ='ข้อมูลไม่ถูกต้อง';
  echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
 }
 
$resWallet = $conn->query("SELECT main_wallet FROM member_wallet WHERE member_no=?",[$member_no])->row_array();
if ($resWallet['main_wallet'] > 10) {
	$errCode = 402;
    $errMsg = 'ท่านสมาชิกมีเงินคงเหลือในกระเป๋าหลักมากกว่า 10 บ. ไม่สามารถรับค่า' . (($claimtype == 1) ? 'แนะนำเพื่อน' : 'คืนยอดเสีย') . 'ได้ค่ะ';
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}  
if($claimtype==2||$claimtype==3){
  $sql = "SELECT 1 FROM log_claim_commission WHERE DATE(update_date)=CURRENT_DATE() AND member_no=?";
  $res = $conn->query($sql, [$member_no]);
  $check_com=$res->num_rows();
  $sql = "SELECT 1 FROM log_claim_cashback WHERE DATE(update_date)=CURRENT_DATE() AND member_no=?";
  $res = $conn->query($sql, [$member_no]);
  $check_caskback=$res->num_rows();
}
$min_claim=1;
switch ($claimtype) {
  case 1:  // AFF
    $sql = "SELECT 1 FROM log_claim_aff WHERE DATE(update_date)=CURRENT_DATE() AND member_no=?";
    $res = $conn->query($sql, [$member_no]);
    if ($res->num_rows() > 0) { 
      $errCode = 402;
      $errMsg ='ท่านสมาชิกใช้สิทธิ์กดรับค่าแนะนำเพื่อนของวันนี้ไปแล้วค่ะ';
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    } 
    break; 
  case 2:  // Commission 
     $min_claim=$Website['min_comm_claim']; 
    if ($check_com > 0||$check_caskback>0) {
      $errCode  = 402;
      $errMsg ='ท่านสมาชิกใช้สิทธิ์กดรับคอมมิชชั่นของวันนี้ไปแล้วค่ะ';
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    if (@$dataResult['amount']< $min_claim) {
      $errCode  = 402;
      $errMsg ='ค่าคอมฯ น้อยกว่า '.number_format($min_claim).' บ. ไม่สามารถรับได้ค่ะ';
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    } 
    break; 
  case 3: // Cashback 
    if ($check_com > 0||$check_caskback>0) {
      $errCode  = 402;
      $errMsg ='ท่านสมาชิกใช้สิทธิ์กดรับคืนยอดเสียของวันนี้ไปแล้วค่ะ';
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    } 
    $min_claim=$Website['min_cashback_claim']; 
    if (@$dataResult['amount']< $min_claim) { 
      $errCode  = 402;
      $errMsg ='ยอดเสีย น้อยกว่า '.number_format($min_claim).' บ. ไม่สามารถรับได้ค่ะ';
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    break; 
  default:  break;
} 