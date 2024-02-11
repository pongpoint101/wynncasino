<?php
 require '../bootstart.php'; 
 require_once ROOT.'/core/security.php';
 $member_no=$_SESSION['member_no'];

 $key='MB:AFF:'.$_SESSION['member_no'];
 $sql = "SELECT id,af_code,turnover_all,commission_wallet,commission_money_all,aff_type FROM members WHERE id=?"; // AND status='1'";
 $row_member =GetDataSqlWhereOne($key,$sql,[$_SESSION['member_no']],5*60);
 $aff_type =$row_member['aff_type'];
 if($aff_type==2){
  $WebSites = GetWebSites();
  $income_level1 = GetWinlossLevel1($_SESSION['member_no'],0);
  $tot1 = isset($income_level1['total']['total']) ? $income_level1['total']['total'] : 0;
  $income_level1_total = ($tot1 * $WebSites['aff_rate1']) / 100;
  
  $income_level2 = GetWinlossLevel2($_SESSION['member_no'],0);
  $tot2 = isset($income_level2['total']['total']) ? $income_level2['total']['total'] : 0;
  $income_level2_total = ($tot2 * $WebSites['aff_rate2']) / 100;
  $log_win_loss = GetLogWinloss($_SESSION['member_no']);
  $amount =($income_level1_total + $income_level2_total)+($log_win_loss); 
 }
 
 $msg='';$error=200;
 $sql_chk_date = "SELECT 1 FROM log_aff_win_loss WHERE DATE(date)=CURRENT_DATE() AND member_no=?";
 $res_chk_date = $conn->query($sql_chk_date, [$member_no]);
if ($res_chk_date->num_rows() > 0) { 
$error=300;
$msg ='ท่านสมาชิกใช้สิทธิ์กดรับค่าแนะนำเพื่อนของวันนี้ไปแล้วค่ะ';
} 
$date_current = date('Y-m-d');
$date_last= date('Y-m-t');
if($date_current!=$date_last){

    $error=300;
    $msg ='กดรับค่าแนะนำเพื่อนได้เฉพาะวันสุดท้ายของเดือนเท่านั้น';
}

 if($error==200&&$amount>0){
   try { 

    $conn->trans_start(); 
    if($aff_type==2){
        $cal = ($income_level1_total + $income_level2_total)+$log_win_loss;
      
      $sql1="INSERT INTO log_aff_win_loss(member_no,total_l1,total_l2,cal,date)";
      $sql1.=" VALUES(?,?,?,?,?)";
      $sql1_value=[$member_no,$income_level1_total,$income_level2_total,$cal,date('Y-m-d')];
      $conn->query($sql1,$sql1_value);
    }
    
    $conn->trans_complete(); 
   } catch (\Throwable $ex) {var_dump($ex->getMessage());

    // $conn->trans_rollback(); 
    $msg="ไม่สามารถดำเนินการได้กรุณาลองอีกครั้ง";
    $error=500;
   }

 
 } 
 return $msg ;
 
 ?>

 



 

