<?php   
//สำหรับโปรยอดฝากล่าสุด
$deposit_list_id=[];
$sql_deposit = "SELECT * FROM log_deposit WHERE status=1 AND channel IN($allow_channel)  AND member_no=?";
$sql_deposit .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
//สำหรับโปรยอดฝากแรกของวัน 
 if(@$pro_deposit_first_day==2){
   $sql_deposit = "SELECT * FROM log_deposit WHERE status=1 AND channel IN($allow_channel) AND member_no=?";
   $sql_deposit .= " AND DATE_FORMAT(trx_date, '%Y-%m-%d') = CURDATE()";
   $sql_deposit .= " ORDER BY trx_date ASC,trx_time ASC LIMIT 1";  
  }else if(@$pro_deposit_first_day==3){
    $sql_deposit = "SELECT * FROM log_deposit WHERE status=1 AND channel IN($allow_channel)  AND member_no=? AND DATE_FORMAT(trx_date, '%Y-%m-%d') = CURDATE()";
    $sql_deposit .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
  }else if(@$pro_deposit_first_day==4){// ฝากครั้งแรก
    $sql_deposit = "SELECT * FROM log_deposit WHERE status=1 AND channel IN($allow_channel)  AND member_no=?";
    $sql_deposit .= " ORDER BY trx_date ASC,trx_time ASC LIMIT 1";
  }

 if($pro_deposit_type==1){//ยอดฝากฟิค
    $pro_deposit_fix_list = explode(',',$pro_deposit_fix);  
    $res = $conn->query($sql_deposit,[$member_no]);
    $local_res = $res;
    if ($local_res->num_rows() >= 1) {
        $local_res = $local_res->row_array();
        $latest_deposit_amount = $local_res['amount']; 
        $deposit_id=$local_res['id'];
        $promo_type=1;

        $validRanges = [];
        foreach ($pro_deposit_fix_list as $v) {
            $min = $v;
            $max = $v+0.99; 
            $validRanges[]=[$min,$max];
        }  
        $isValid = false;
        foreach ($validRanges as $k => $range) { 
            $min = $range[0];
            $max = $range[1]; 
            if ($latest_deposit_amount >= $min && $latest_deposit_amount <= $max) {
                $isValid = true;$promo_type=$k+1;
                break;
            }
         }

         if(!$isValid){
          $errCode = 402;
          $errMsg = "โปรฯ นี้สำหรับยอดฝาก {$pro_deposit_fix} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
          if(@$pro_deposit_first_day==2){
              $errMsg = "ยอดฝากแรกของวันไม่ใช่ {$pro_deposit_fix} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
          } 
          echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
         } 
        // if (!in_array($local_res['amount'],$pro_deposit_fix_list)) {
        //     $errCode = 402;
        //     $errMsg = "โปรฯ นี้สำหรับยอดฝาก {$pro_deposit_fix} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
        //     if(@$pro_deposit_first_day==2){
        //         $errMsg = "ยอดฝากแรกของวันไม่ใช่ {$pro_deposit_fix} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
        //     } 
        //     echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
        //   }else{
        //     $promo_type=array_search($local_res['amount'], $pro_deposit_fix_list)+1;
        //   }
     }
  }else if($pro_deposit_type==2){//ยอดฝากขั้นต่ำ,สูงสุด   
    $res = $conn->query($sql_deposit,[$member_no]);
    $local_res = $res;
    if ($local_res->num_rows() >= 1) {
        $local_res = $local_res->row_array();
        $latest_deposit_amount = $local_res['amount']; 
        $deposit_id=$local_res['id'];
        $promo_type=1;
        if($pro_deposit_end_amount==-1){$pro_deposit_end_amount=9999999;}

        $validRanges = [];$validRanges[]=[$pro_deposit_start_amount,$pro_deposit_end_amount+0.99]; 
        $isValid = false;
        foreach ($validRanges as $k => $range) { 
            $min = $range[0];
            $max = $range[1]; 
            if ($latest_deposit_amount >= $min && $latest_deposit_amount <= $max) {
                $isValid = true;$promo_type=$k+1;
                break;
            }
         }
         if(!$isValid){  
            $errCode = 402;
            $txtdeposit_first_day = "ยอดฝากของ"; 
            if(@$pro_deposit_first_day==2){ $txtdeposit_first_day = "ยอดฝากแรกของ"; }
            $errMsg = "$txtdeposit_first_day ลูกค้าต้องอยู่ในช่วง {$pro_deposit_start_amount}-{$pro_deposit_end_amount} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
            if($pro_deposit_end_amount==9999999){
              $errMsg = "$txtdeposit_first_day รับโปรฯนี้ใช้กับรายการฝากที่เริ่มต้น {$pro_deposit_start_amount} บาท. ขึ้นไป";
             }  
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
         }
      //   if(!($local_res['amount']>=$pro_deposit_start_amount&&$local_res['amount']<=$pro_deposit_end_amount)){  
      //     $errCode = 402;
      //     $txtdeposit_first_day = "ยอดฝากของ"; 
      //     if(@$pro_deposit_first_day==2){ $txtdeposit_first_day = "ยอดฝากแรกของ"; }
      //     $errMsg = "$txtdeposit_first_day ลูกค้าต้องอยู่ในช่วง {$pro_deposit_start_amount}-{$pro_deposit_end_amount} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
      //     if($pro_deposit_end_amount==9999999){
      //       $errMsg = "$txtdeposit_first_day รับโปรฯนี้ใช้กับรายการฝากที่เริ่มต้น {$pro_deposit_start_amount} บาท. ขึ้นไป";
      //     }  
      //    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
      // }
    }
  }else if($pro_deposit_type==3){//ยอดฝากขั้นบันได
    //สำหรับโปรยอดฝากแรกของวัน
    $res = $conn->query($sql_deposit,[$member_no]);
    $local_res = $res; 
    if ($local_res->num_rows() >= 1) {
        $local_res = $local_res->row_array();
        $latest_deposit_amount = $local_res['amount']; 
        $deposit_id=$local_res['id'];
        $promo_type=1;
        $sql = "SELECT * FROM pro_promotion_detail_more WHERE pro_id=? ORDER BY deposit_start_amount;"; // AND ? BETWEEN deposit_start_amount AND deposit_end_amount  
        $local_promo= $conn->query($sql,[$promo_id]); 
        if ($local_promo->num_rows() >= 1) {
          $local_promo = $local_promo->result_array();
          $x_allow_txt='';$kx=0;
          foreach ($local_promo as $k => $v) {
            if($v['deposit_end_amount']==0||$v['deposit_end_amount']==0){continue;}
            $deposit_end_amount=$v['deposit_end_amount'];
            if(($v['deposit_end_amount']*1)==-1){$deposit_end_amount=9999999;} 

            if($kx>0){$x_allow_txt.=' และ ';}
            if($v['deposit_start_amount']==$v['deposit_end_amount']){
               $x_allow_txt.=$v['deposit_start_amount']; 
               $errMsg = "สมาชิกยังไม่มีรายการฝากที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ $x_allow_txt บาท. เท่านั้น!";
              }else{
               $x_allow_txt.=$v['deposit_start_amount'].'-'.$v['deposit_end_amount']; 
               $errMsg = "สมาชิกยังไม่มีรายการฝากในช่วง $x_allow_txt บาท. เท่านั้น!";
              }  

              $validRanges = [];$validRanges[]=[$v['deposit_start_amount'],$v['deposit_end_amount']+0.99];  
              $isValid = false;
              foreach ($validRanges as $kk => $range) { 
                  $min = $range[0];
                  $max = $range[1]; 
                  if ($latest_deposit_amount >= $min && $latest_deposit_amount <= $max) {
                      $isValid = true;$promo_type=$kk+1;
                      break;
                  }
               }
               if ($isValid) { 
                   $data_local=$v;
               }
              // if (($latest_deposit_amount>= $v['deposit_start_amount'])&&($latest_deposit_amount<=$deposit_end_amount)) { 
              //   $data_local=$v;
              //  }
             $kx++;
          }
         if(isset($data_local['deposit_start_amount'])){
          $promo_max=$data_local['bonus_max_amount'];
          $pro_bonus_amount=$data_local['pro_bonus_amount'];
          $pro_turnover_amount=$data_local['turnover_amount']; 
          $pro_deposit_start_amount= $data_local['deposit_start_amount'];
          $pro_deposit_end_amount= $data_local['deposit_end_amount'];
          $pro_withdraw_max_amount= $data_local['withdraw_max_amount'];
          $pro_id_more=$data_local['id'];
         }else{
          $errCode = 402; 
          echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด      
           } 
        }else{
         $errCode = 402;
         $errMsg = "สมาชิกต้องมียอดฝาก ไม่สามารถทำรายการได้!";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด     
    }
   }
}else if($pro_deposit_type==4){
  $sql = "SELECT * FROM pro_promotion_topup_today WHERE pro_id=? ORDER BY morder;"; // AND ? BETWEEN deposit_start_amount AND deposit_end_amount  
  $local_promo= $conn->query($sql,[$promo_id]); 
  if ($local_promo->num_rows() >= 1) {
    $local_promo = $local_promo->result_array();
    $x_allow_txt='';$kx=1;$sql_x='';$pro_bonus_amount=0; 
      $sql="
      SELECT  *
        FROM (
            SELECT promo,id,member_no,
                  trx_date,
                  trx_time,
                  amount,
                  ROW_NUMBER() OVER (PARTITION BY member_no ORDER BY trx_date ASC, trx_time ASC) AS trx_order
          FROM log_deposit
          WHERE member_no=?  AND status=1 AND promo=-1 AND channel IN($allow_channel)
            AND trx_date = CURDATE()
        ) AS ranked_transactions 
        ORDER BY trx_order	ASC;
        ;";  
      $res= $conn->query($sql,[$member_no]); 
      $local_res=$res;
      if ($local_res->num_rows() >= 1) {
           $local_res = $local_res->result_array();  
           $check_deposit=false;$new_prodeposit=[];
           foreach ($local_promo as $k => $v) {
            if($v['morder']==0||$v['deposit_start_amount']==0||$v['deposit_end_amount']==0){continue;}
            $new_prodeposit[]=$v;$pro_bonus_amount+=$v['pro_bonus_amount'];
           }
          $pass_deposit=[];$indexpor=0;    
          foreach ($local_res as $k => $v) {
                    if(count($new_prodeposit)!=count($pass_deposit)){
                     $pass_count=count($pass_deposit);//var_dump($pass_count);
                     $row_prodeposit=$new_prodeposit[$pass_count];
                     $deposit_amountmin=(int)$row_prodeposit['deposit_start_amount'];
                     $deposit_amountmax=(int)$row_prodeposit['deposit_end_amount']; 
                     if($v['amount']>=$deposit_amountmin&&$v['amount']<=$deposit_amountmax){ 
                       $pass_deposit[]=$v;$deposit_list_id[]=$v['id'];
                     }else{
                       $pass_deposit=[];$deposit_list_id=[];
                   } 
                  }
             }   
             if(count($new_prodeposit)!=count($pass_deposit)||count($new_prodeposit)<=0||count($pass_deposit)<=0){
              $errCode = 402;
              $errMsg = "รายการฝากไม่ครบตามเงื่อนไข ไม่สามารถทำรายการได้ A !";
              echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
             } 
             $local_res=$local_res[0];  
        }else{
        $errCode = 402;
        $errMsg = "ไม่พบรายการฝาที่ตรงเงื่อนไข (H)";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด   
      }  
    }else{
      $errCode = 402;
      $errMsg = "ข้อมูลโปรไม่ถูกต้อง!";
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด     
  }
  $deposit_id=uniqid();
} 
if (gettype($local_res)=='array') { 
  if ($local_res['promo'] != (-1)) {
    $errCode = 402;
    $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
 } 
}
if(@$pro_deposit_first_day==4){
  $firstrow =$conn->query("SELECT 1 FROM log_deposit WHERE status=1 AND channel IN($allow_channel)  AND member_no=?", [$member_no]);  
  $numfirstrow=$firstrow->num_rows();
  if ($numfirstrow!=1&&$numfirstrow>0){
   $errCode = 402;
   $errMsg = "ต้องเป็นยอดฝากแรกของสมาชิกเท่านั้น";
   echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด   
 }
}

if(@$pro_deposit_first_day==2){
  $sqlxx = "SELECT * FROM log_deposit WHERE status=1 AND  member_no=? AND CAST(trx_date AS DATE)=?";  
  $sqlxx .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
  $dyxres = $conn->query($sqlxx,[$member_no,date('Y-m-d')]);
  $dyxres = $dyxres->row_array();
  if ($deposit_id != $dyxres['id']) {
      $errCode = 403;
      $errMsg = "โปรรายการฝากแรกวันต้องรับโบนัสทันทีเท่านั้น มียอดฝากถัดไปแล้วจะไม่สามารถรับได้คะ";
      echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
  }
}
if(@$checkrangetime==1){
  require ROOT.'/core/promotions/pro_v2/pro_checkrangetime.php';
} 

?>