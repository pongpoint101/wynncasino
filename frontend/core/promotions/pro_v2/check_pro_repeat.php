<?php
// เช็ครับได้กี่ครั้ง // 1. รับได้ครั้งเดียว 2. รับได้วันละกี่ครั้ง 3.รับได้ตลอด,4.รับได้เดือนละกี่ครั้ง 5.รับได้ปีละกี่ครั้ง 6.เครดิตฟรี
$sqlxrepeat = " AND pro_repeat=?";
switch ($pro_repeat) {
    case 1: 
    case 6:       
        $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=? AND pro_id=?"; 
        $repeat_total = $conn->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];   
        if($repeat_total>0){
            $errCode = 402;
            $errMsg = "สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
         }
    break; 
    case 2:  
        $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND DATE(create_at) = CURDATE() ";
        $repeat_total = $conn->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
         if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
            $errCode = 402;
            $errMsg = "วันนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
         }
    break; 
    case 3:    
         $proweeklyday=date("w", strtotime($pro_weekly_day));
         if($proweeklyday!=date("w", strtotime(date('Y-m-d H:i:s')))){ 
               $days = [
                  "mon" => "วันจันทร์",
                  "tue" => "วันอังคาร",
                  "wed" => "วันพุธ",
                  "thu" => "วันพฤหัสบดี",
                  "fri" => "วันศุกร์",
                  "sat" => "วันเสาร์",
                  "sun" => "วันอาทิตย์",
                 ]; 
             $txt_fullDay = $days[$pro_weekly_day];
             $errCode = 402;
             $errMsg = "สัปดาห์นี้สมาชิกรับโปรฯนี้ได้ใน $txt_fullDay นะค่ะ";
             echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด    
       } 
      $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND create_at>=CONCAT(DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE()) - 2 DAY),' 00:00:00') AND create_at<=DATE_SUB(CURRENT_TIMESTAMP(),INTERVAL DAYOFWEEK(CURRENT_TIMESTAMP())-8 DAY) ";
      $repeat_total = $conn->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];  
      if($repeat_total>0){
          $errCode = 402;
          $errMsg = "สัปดาห์นี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
          echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
       } 
  break;
    case 4: 
        $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND MONTH(create_at)=MONTH(CURRENT_DATE()) AND YEAR(create_at)=YEAR(CURRENT_DATE()) ";
        $repeat_total = $conn->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
         if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
            $errCode = 402;
            $errMsg = "เดือนนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
         } 
    break; 
    case 5: 
         $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND YEAR(create_at)=YEAR(CURRENT_DATE()) ";
         $repeat_total = $conn->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
         if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
            $errCode = 402;
            $errMsg = "ปีนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
         }
    break; 
    default:   break;
} 
?>