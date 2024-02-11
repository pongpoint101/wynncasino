<?php
$sql = "SELECT 1 FROM promop100p WHERE member_no=?";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() > 0) {
  $errCode = 402;
  $errMsg = "สมาชิกเคยรับโปรฯ 100% ไปแล้วค่ะ";
  echojs("ผิดพลาด",$errMsg,1,"error");
} else {

  $sql = "SELECT 1 FROM promop50p WHERE member_no=?";
  $res = $conn->query($sql,[$member_no]);
  if ($res->num_rows() > 0) {
    $errCode = 402;
    $errMsg = "สมาชิกเคยรับโปรฯ 50% ไปแล้วจะรับรับโบนัสฝาก 20 ได้ 100 ไม่ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error");
  }
  $sql = "SELECT 1 FROM promop52 WHERE member_no=?";
  $res = $conn->query($sql,[$member_no]);var_dump($res->num_rows());
  if ($res->num_rows() > 0) {
    $errCode = 402;
    $errMsg = "สมาชิกเคยรับโปรฯ 20 ได้ 100 ไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error");
  }

  $cal_percentage = 1;
  $promo_max = 80;    // ยอดรับโบนัสสูงสุด
  $expect_turnover =10;
  $remark = 'P52';
  $channel = 52;
 
  $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";
  $sql .= " ORDER BY trx_date DESC,trx_time DESC";
  $res = $conn->query($sql,[$member_no]);

  if ($res->num_rows() >= 1) {
    $res = $res->row_array();
    if ($res['promo'] != (-1)) {
      $errCode = 402;
      $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
      echojs("ผิดพลาด",$errMsg,1,"error");
    } 
    if ($res['amount']!= 20) {
      $errCode = 500;
      $errMsg = "สมาชิกยังไม่มีรายการฝากที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ (ยอดฝาก 20 เท่านั้น)";
      echojs("ผิดพลาด",$errMsg,1,"error"); 
    }
    
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);
    $latest_deposit_amount = $res['amount'];
    $promo_cal_result =$promo_max;

    $win_expect = ($latest_deposit_amount + $promo_cal_result) * $expect_turnover; 
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
} 