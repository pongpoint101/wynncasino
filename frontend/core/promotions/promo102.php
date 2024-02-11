<?php
   $sql = "SELECT 1 FROM promop50p WHERE member_no=?";
   $res = $conn->query($sql,[$member_no]);
   if ($res->num_rows() > 0) {
       $errCode = 402;
       $errMsg = "สมาชิกเคยรับโปรฯ นี้ไปแล้วค่ะ";
       echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
   } else {
       $sql = "SELECT * FROM promop100p WHERE member_no=?";
       $res = $conn->query($sql,[$member_no]);
       if ($res->num_rows() > 0) {
           $errCode = 402;
           $errMsg = "สมาชิกเคยรับโปรฯ นี้ไปแล้วค่ะ";
           echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
       }
       $sql = "SELECT 1 FROM promop52 WHERE member_no=?";
       $res = $conn->query($sql,[$member_no]);
       if ($res->num_rows() > 0) {
         $errCode = 402;
         $errMsg = "สมาชิกเคยรับโปรฯ 20 ได้ 100 ไปแล้วจะรับรับโบนัส 50% ไม่ได้ค่ะ";
         echojs("ผิดพลาด",$errMsg,1,"error");
       }
     
       $cal_percentage = 0.5;
       $promo_max = 200;    // ยอดรับโบนัสสูงสุด
       $expect_turnover = 0.00;
       $remark = 'Pro50%';
       $channel = 24; 
       $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5)  AND member_no=?";
       $sql .= " ORDER BY trx_date DESC,trx_time DESC";
       $res = $conn->query($sql,[$member_no]);
       if ($res->num_rows() >= 1) {
           $res = $res->row_array();
           if ($res['promo'] != (-1)) {
               $errCode = 402;
               $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
               echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
           } 
           // if ($res['amount']!= 200) {
           //     $errCode = 402;
           //     $errMsg = "โปรฯ นี้สำหรับยอดฝาก 200 บาท รับ ฟรี 100 บาทเท่านั้นค่ะ";
           //     echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
           // }  
           $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);
           $latest_deposit_amount = $res['amount'];
           $promo_cal_result = round($latest_deposit_amount * $cal_percentage);  // ปัดทศนิยมเป็นจำนวนเต็ม
           $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;
           $win_expect = ($latest_deposit_amount + $promo_cal_result) * 2; 
           $expect_turnover =$win_expect;
           $data =[
               'member_no' => $member_no,
               'full_amount' =>0,
               'actual_withdraw' =>0,
               'win_expect' => $win_expect,
               'status' => 1,
               'arrival_date'=> date('Y-m-d H:s:i'),
               'update_by'=>'SYSTEM'
              ];  
           $conn->insert('promop50p', $data); 
           $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
           $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
         } else {
           $errCode = 401;
           $errMsg = "สมาชิกต้องมียอดฝาก จึงจะเลือกรับโปรฯ ได้ค่ะ";
           echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
       }
   } 