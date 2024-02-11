<?php
  $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND trx_date=SUBDATE(CURDATE(),0) AND member_no=?";
  $sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
  $res = $conn->query($sql,[$member_no]); 
  if ($res->num_rows()<=0) {
    $errCode = 402;
    $errMsg = "วันนี้สมาชิกยังไม่มีรายการฝาก 299,499,999,1,999 หรือรับโปรฯ อื่นไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error");
  }
 $res = $res->row_array();
 $org_trx_amount=$res['amount'];
 $depost_allow=[299,499,999,1999];
 $trx_amount=intval(floor ($org_trx_amount));   
 if ($res['promo'] != (-1)||!in_array($trx_amount,$depost_allow)) {
  $errCode = 402;
  $errMsg = "วันนี้สมาชิกยังไม่มีรายการฝาก 299,499,999,1,999 หรือรับโปรฯ อื่นไปแล้วค่ะ";
  echojs("ผิดพลาด",$errMsg,1,"error");
 }  

$sql="SELECT *
FROM (
  SELECT id,amount AS amount,trx_date,trx_time,member_no  
  ,lag(trx_date) over (partition by member_no order by trx_date ) as daydiffx      
  ,IFNULL(datediff(trx_date,lag(trx_date) over (partition by member_no order by trx_date )), 1) as daydiff
  FROM log_deposit  WHERE member_no=? AND trx_date>='2022-05-01' AND trx_date<='2022-05-10' 
  AND trx_date BETWEEN  SUBDATE(CURDATE(),10-1) AND SUBDATE(CURDATE(),0)
  AND amount>=? AND amount<=? AND status=1 AND (channel<=3 OR channel=5) AND promo IN(-1,37) 	
) AS t  
GROUP BY t.trx_date 
ORDER BY t.trx_date ASC,t.trx_time ASC LIMIT 10
;";
$res = $conn->query($sql,[$member_no,$trx_amount,$trx_amount+0.99]); 
if ($res->num_rows()<=0) {
  $errCode = 402;
    $errMsg = "วันนี้สมาชิกยังไม่มีรายการฝาก 299,499,999,1,999 หรือรับโปรฯ อื่นไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error");
}
$res=$res->result_array();
$datatfreq=[];$old_trx_date=null; 
		for ($i=0; $i < 10; $i++) {  
			if(isset($res[$i])){
			    $vv=$res[$i]; 
				  if($old_trx_date==$vv['trx_date']){continue;}
				  if ($vv['daydiff']>1) {
             $datatfreq=[];
             $datatfreq[]=$vv; 
			   	}else{
            $datatfreq[]=$vv; 
          }  
			   $old_trx_date=$vv['trx_date'];
			}
 } 
 $deposit_id=[];$pro_bonus_amount=0;
 for ($i=0; $i < sizeof($datatfreq); $i++) {  
     $vv=$datatfreq[$i]; 
     if($trx_amount==299){
        $pro_bonus_amount+=30;
      }else if($trx_amount==499){
        $pro_bonus_amount+=60;
      }else if($trx_amount==999){
        $pro_bonus_amount+=120;
      }else if($trx_amount==1999){
        $pro_bonus_amount+=250;
     }
     $deposit_id[]=$vv['id'];
 } 
$sql = "SELECT * FROM promop37p WHERE member_no=?";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() > 0) {
  $errCode = 402;
  $errMsg = "สมาชิกเคยรับโปรฯ นี้ไปแล้วค่ะ";
  echojs("ผิดพลาด",$errMsg,1,"error");
} else { 

  $cal_percentage = 1.0;
  $promo_max = 2500;    // ยอดรับโบนัสสูงสุด
  $expect_turnover = 0.00;
  $remark = 'P35';
  $channel = 37;
  
  $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";
  $sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
  $res = $conn->query($sql,[$member_no]);
  if ($res->num_rows() >= 1) {
    $res = $res->row_array();
    if ($res['promo'] != (-1)) {
      $errCode = 402;
      $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
      echojs("ผิดพลาด",$errMsg,1,"error");
    }  
    
    $conn->set('promo', $promo_id, FALSE);
    $conn->where_in('id', $deposit_id);
    $conn->update('log_deposit');
    $latest_deposit_amount = $main_wallet;
    $promo_cal_result = round($pro_bonus_amount);  // ปัดทศนิยมเป็นจำนวนเต็ม
    $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;

    $win_expect = ($latest_deposit_amount + $promo_cal_result) * 3; 
    $expect_turnover=$win_expect;
    $deposit_id=$res['id'];
    $trx_id=uniqid();
    $data =[
      'member_no' => $member_no,
      'full_amount' =>$pro_bonus_amount,
      'actual_withdraw' =>$expect_turnover,
      'win_expect' => $win_expect,
      'status' => 1,
      'arrival_date'=> date('Y-m-d H:s:i'),
      'update_by'=>'member'
     ]; 
    $conn->insert('promop37p', $data);  
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]); 
  } else {
    $errCode = 401;
    $errMsg = "สมาชิกต้องมียอดฝาก จึงจะเลือกรับโปรฯ ได้ค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error");
  }
} 