
<?php
class Model_bus_promo extends MY_Model { 
    private $res=['vstatus'=>false,'errCode'=>0,'errMsg'=>'','deposit_id'=>'','promo_type'=>'','win_expect'=>0,'promo_amount'=>0,'data_all'=>[]];
    private $allow_channel='1,3,5';
    public function Promo_allow_TrueWallet(){
       $this->allow_channel='1,3,5';
       $webconfig=$this->Model_affiliate->list_m_site(); 
       if($webconfig['row']->truewallet_is_bonus==2){
         $this->allow_channel='1,2,3,5';
       }
       return $this->allow_channel;
    }
    public function Valid_limit_per_user($member_no,$datapro=[]){  
        if(@$datapro['row']->pro_max_repeat_user==-1){ $this->res['vstatus']=true; return $this->res; }   
        $this->db->where('member_no', $member_no);  
        $this->db->where('pro_id', $datapro['row']->pro_id);   
        $numrows=$this->db->get('pro_logs_promotion')->num_rows();// var_dump($numrows<($datapro['row']->pro_max_repeat_user));exit();
        if (($numrows+1)>$datapro['row']->pro_max_repeat_user) {$this->res['vstatus']=false; return $this->res; ; }
        else if($numrows<($datapro['row']->pro_max_repeat_user)){$this->res['vstatus']=true; return $this->res; ;}
        $this->res['vstatus']=false; return $this->res;  
      } 
      public function Valid_deposit($member_no,$datapro=[],$pro_deposit_first_day=''){
         $allow_channel=$this->Promo_allow_TrueWallet();
         $this->res['vstatus']=false;
         $cal_percentage = 0.00;                      // % คำนวณโบนัส
         $cal_bonus_percentage = 0.00;                      // % คำนวณโบนัส
         $promo_max = 0.00;
         $latest_deposit_amount = 0.00;               // ยอดรับโบนัสสูงสุด
         $expect_turnover = 10000000;
         $trx_id = uniqid();
         $promo_type='';
         $pro_id_more=null;
         $deposit_id='';

         $pro_id=$datapro['row']->pro_id;
         $pro_name=$datapro['row']->pro_name; 
         $pro_cat_id=$datapro['row']->pro_cat_id;
         $pro_cat_name=$datapro['row']->pro_cat_name;
         $pro_group_id=$datapro['row']->pro_group_id;
         $pro_repeat=$datapro['row']->pro_repeat;
         $remark=$datapro['row']->pro_symbol;
         $promo_max=$datapro['row']->pro_bonus_max;
         $pro_bonus_type=$datapro['row']->pro_bonus_type;
         $pro_bonus_amount=$datapro['row']->pro_bonus_amount;
         $pro_deposit_type=$datapro['row']->pro_deposit_type;
         $pro_deposit_fix=$datapro['row']->pro_deposit_fix;
         $pro_deposit_end_amount=$datapro['row']->pro_deposit_end_amount;
         $pro_deposit_start_amount=$datapro['row']->pro_deposit_start_amount;
         $pro_turnover_type =$datapro['row']->pro_turnover_type;
         $pro_turnover_amount = $datapro['row']->pro_turnover_amount;
         $pro_last_deposit_amount = $datapro['row']->pro_last_deposit_amount;
         $pro_withdraw_max_amount=$datapro['row']->pro_withdraw_max_amount;
         $pro_withdraw_type=$datapro['row']->pro_withdraw_type;
         if($pro_bonus_type==2){$cal_bonus_percentage = ($pro_bonus_amount / 100);}
         if ($pro_turnover_type == 2||$pro_turnover_type == 4) { //1 turnoverจากยอดเล่นจำนวนเต็ม ,2 turnover จากยอดเล่นจำนวนเท่า ,3 ยอดเครดิตจำนวนเต็ม,4 เครดิตจำนวนเท่า
             $cal_percentage = ($pro_turnover_amount / 100);
         }

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
           } 
          
          if($pro_deposit_type==1){//ยอดฝากฟิค
             $pro_deposit_fix_list = explode(',',$pro_deposit_fix);  
             $res = $this->db->query($sql_deposit,[$member_no]);
             $local_res = $res;
             if ($local_res->num_rows() >= 1) {
                 $local_res = $local_res->row_array();
                 $latest_deposit_amount = $local_res['amount']; 
                 $deposit_id=$local_res['id'];
                 $promo_type=1;
                 if(@$pro_deposit_fix!=0){
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
                   $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  // ยินดีด้วยค่ะ ผิดพลาด   
                  } 
               }
              }
           }else if($pro_deposit_type==2){//ยอดฝากขั้นต่ำ,สูงสุด   
             $res = $this->db->query($sql_deposit,[$member_no]);
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
                      $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  // ยินดีด้วยค่ะ ผิดพลาด  
                  } 
             }
           }else if($pro_deposit_type==3){//ยอดฝากขั้นบันได
             //สำหรับโปรยอดฝากแรกของวัน
             $res = $this->db->query($sql_deposit,[$member_no]);
             $local_res = $res; 
             if ($local_res->num_rows() >= 1) {
                 $local_res = $local_res->row_array();
                 $latest_deposit_amount = $local_res['amount']; 
                 $deposit_id=$local_res['id'];
                 $promo_type=1;
                 $sql = "SELECT * FROM pro_promotion_detail_more WHERE pro_id=? ORDER BY deposit_start_amount;"; // AND ? BETWEEN deposit_start_amount AND deposit_end_amount  
                 $local_promo= $this->db->query($sql,[$pro_id]); 
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
                   $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  // ยินดีด้วยค่ะ ผิดพลาด   
                    } 
                 }else{
                  $errCode = 402;
                  $errMsg = "สมาชิกต้องมียอดฝาก ไม่สามารถทำรายการได้!";
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  // ยินดีด้วยค่ะ ผิดพลาด    
               }
            }
         } 
         if (gettype($local_res)=='array') { 
           if ($local_res['promo'] != (-1)&&!($pro_last_deposit_amount==-1)) {
             $errCode = 402;
             $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
             $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  // ยินดีด้วยค่ะ ผิดพลาด
          } 
         }
         if ($res->num_rows() <=0&&!($pro_last_deposit_amount==-1)) {$errCode = 402;$errMsg = "ไม่พบรายการฝากที่ตรงเงื่อนไข!";$this->res['vstatus']=false; $this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res; } 
         $this->Check_pro_repeat($member_no,$datapro);
         if(!$this->res['vstatus']){return $this->res;}
         //คำนวนโบนัส   
        if($pro_bonus_type==1){//  โบนัสเครดิต
         $promo_cal_result = $pro_bonus_amount;   
         }else if($pro_bonus_type==2){//โบนัสจำนวนเปอร์เซ็นต์(%)  
         $promo_cal_result = round($latest_deposit_amount *$cal_bonus_percentage);  // ปัดทศนิยมเป็นจำนวนเต็ม
         if($promo_max!=-1){
            $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;
         } 
         if($pro_deposit_type==3){$promo_cal_result= $promo_max;}
      } 
      //คำนวนเทิร์นโอเวอร์
      $wallet=$this->Model_member->SearchMemberWalletBymember_no($member_no);
         $main_wallet=$wallet['row']->main_wallet;
      if($pro_turnover_type==1){ //ทำยอดเล่นจำนวนเต็ม
         $expect_turnover = $pro_turnover_amount;   
      }else if($pro_turnover_type==2){//ทำยอดเล่นกี่เท่า
         $expect_turnover = (($main_wallet)+$promo_cal_result) * (($pro_turnover_amount==1)?2:$pro_turnover_amount); 
      }else if($pro_turnover_type==3){//ทำเทิร์นยอดบวก
         $expect_turnover = $pro_turnover_amount;   
      }else if($pro_turnover_type==4){//ทำเทิร์นจำนวนเท่า
         $expect_turnover = (($main_wallet)+$promo_cal_result) * (($pro_turnover_amount==1)?2:$pro_turnover_amount); 
      }       
        $pro_withdraw_accept=-1;   
       if ($pro_withdraw_max_amount != -1&&$pro_withdraw_type== 2) { 
         $pro_withdraw_accept = (($main_wallet)+$promo_cal_result) * (($pro_withdraw_max_amount==1)?2:$pro_withdraw_max_amount);  
      }
          $data = [
         'member_no' => $member_no,
         'trx_id' => $trx_id,
         'deposit_id' => $deposit_id,
         'pro_id' => $pro_id,
         'pro_id_more'=>$pro_id_more,
         'pro_group_id' => $pro_group_id,
         'pro_symbol' => $remark,
         'pro_name' => $pro_name,
         'pro_cat_id' => $pro_cat_id,
         'pro_cat_name' => $pro_cat_name,
         'turnover_expect' => $expect_turnover,
         'pro_withdraw_accept'=>$pro_withdraw_accept,
         'promo_amount' => $promo_cal_result,
         'create_by' => $this->encryption->decrypt($this->session->userID),
         'pro_repeat'=>$pro_repeat,
         'deposit_amount' => $latest_deposit_amount,
         'status' => 0
           ];//var_dump($data);exit();
         $this->res['data_all']=$data; 
         
         $this->db->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
         $this->db->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
         $this->db->query("UPDATE promop10p SET status=2 WHERE status!=2 AND member_no=?",[$member_no]); 
         $this->res['deposit_id']=$deposit_id;
         $this->res['promo_amount']=$promo_cal_result;
         $this->res['win_expect']=$expect_turnover;
         $this->res['vstatus']=true; return $this->res;
      } 
      public function Deposit_last($member_no,$numday){ // ฝากล่าสุด   
         $this->db->where('member_no', $member_no);   
         $this->db->where("DATE(trx_date) != CURDATE()", "", false);  
         $this->db->where("CAST(create_date AS DATE) > CAST((NOW() - interval $numday DAY) AS DATE)", "", false);  
         return $this->db->get('log_deposit')->num_rows();
     } 
      public function Deposit_Count($member_no){    
         $this->db->where('member_no', $member_no);   
         $this->db->where_in('channel',[1,2,3,4]);  
         return $this->db->get('log_deposit')->num_rows();
     } 
     public function Check_pro_repeat($member_no,$datapro=[]){  
      // เช็ครับได้กี่ครั้ง // 1. รับได้ครั้งเดียว 2. รับได้วันละกี่ครั้ง 3.รับได้ตลอด,4.รับได้เดือนละกี่ครั้ง 5.รับได้ปีละกี่ครั้ง 6.เครดิตฟรี
      $pro_repeat=$datapro['row']->pro_repeat;
      $pro_id=$datapro['row']->pro_id; 
      $pro_max_repeat=$datapro['row']->pro_max_repeat;  
    
      $sqlxrepeat = " AND pro_repeat=?";
      switch ($pro_repeat) {
         case 1: 
         case 6:       
            $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=? AND pro_id=?"; 
            $repeat_total = $this->db->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];   
            if($repeat_total>0){
                  $errCode = 402;
                  $errMsg = "สมาชิกรับโปรฯ นี้ไปแล้วค่ะ";   
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  
               }
         break; 
         case 2:  
            $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND DATE(create_at) = CURDATE() ";
            $repeat_total = $this->db->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
               if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
                  $errCode = 402;
                  $errMsg = "วันนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  
               }
         break; 
         case 4: 
            $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND MONTH(create_at)=MONTH(CURRENT_DATE()) AND YEAR(create_at)=YEAR(CURRENT_DATE()) ";
            $repeat_total = $this->db->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
               if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
                  $errCode = 402;
                  $errMsg = "เดือนนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  
               } 
         break; 
         case 5: 
               $sqlrepeat = "SELECT COUNT(1) AS count_total,pro_name  FROM pro_logs_promotion WHERE  member_no=?  AND pro_id=? AND YEAR(create_at)=YEAR(CURRENT_DATE()) ";
               $repeat_total = $this->db->query($sqlrepeat.$sqlxrepeat, [$member_no,$pro_id,$pro_repeat])->row_array()['count_total'];
               if(($repeat_total+1)>$pro_max_repeat ||$pro_max_repeat<=0){
                  $errCode = 402;
                  $errMsg = "ปีนี้สมาชิกรับโปรฯ นี้ครบแล้วค่ะ";
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  
               }
         break; 
         default:   break;
       }
     
       if($datapro['row']->pro_group_to_1pro!=''&&$datapro['row']->pro_group_to_1pro!=null){
         $this->Check_produpicat($member_no,$sqlrepeat,$datapro['row']->pro_group_to_1pro); 
         if(!$this->res['vstatus']){return $this->res;}
       } 
       $this->res['vstatus']=true; return $this->res;
    } 
    public function Check_produpicat($member_no,$sqlrepeat,$pro_group_to_1pro){ // ฝากล่าสุด   
      $progroup_1allow=(($pro_group_to_1pro!=''&&$pro_group_to_1pro!=null)?explode(',',$pro_group_to_1pro):[]);
      if(sizeof($progroup_1allow)>0){
            foreach ($progroup_1allow as $k => $pro_allow_id) {  
            $rowdata = $this->db->query($sqlrepeat,[$member_no,$pro_allow_id])->row_array(); 
            if ($rowdata['count_total']> 0) { 
                  $errCode = 402;
                  $errMsg = "สมาชิกเคยรับ {$rowdata['pro_name']} ไปแล้ว<br>จะรับโปรฯ นี้ไม่ได้ค่ะ";
                  $this->res['vstatus']=false;$this->res['errCode']=$errCode;$this->res['errMsg']=$errMsg;return $this->res;  
                  break; 
               }
           } 
       } 
       $this->res['vstatus']=true; return $this->res;
  } 
}
?>