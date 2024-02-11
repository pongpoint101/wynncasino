 <?php
 require '../bootstart.php'; 
 require_once ROOT.'/core/security.php';
 $member_no=$_SESSION['member_no'];
 if (isset($limiter)) {
  $limiterns = $limiter->hit('requestaffclaim:' .$member_no, 1, 5); // เรียกได้ 1 ครั้งใน 5 วินาที
  if ($limiterns['overlimit']) {
  InSertLogSys(['member_no'=>$member_no,'ip'=>getIP(),'log_type'=>'aff-maxlimit','txt_data'=>date('Y-m-d').' '.date('H:i:s')]);
      exit(0);
  }
}
 $Website = GetWebSites();   
 $row=getMemberWallet($member_no);  
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

  $amount = bcdiv(($income_level1_total + $income_level2_total)+($log_win_loss),1,2); 
 }else{
  $amount = bcdiv(($row['aff_wallet_l1'] + $row['aff_wallet_l2']),1,2); 
 }

 $channel=16;


 $msg='';$error=200;
 if($aff_type==2){
  $date_current = date('Y-m-d');
  $date_end = date("Y-m-d", strtotime("last day of previous month"));
  if($date_current!=$date_end){
    $msg="คุณสามารถโอนเงินเข้ากระเป๋าได้ทุกๆสิ้นเดือน";
    $error=300;
  }
 }

 if ($amount<$Website['min_aff_claim']) {
    $msg="ต้องมีจำนวนยอดแนะนำอย่างน้อย {$Website['min_aff_claim']} บาท.จึงจะโอนเข้ากระเป๋าหลักได้";
    $error=300;
 }
 $claimtype=1;
 require_once ROOT.'/core/checkClaim.php';   
 if($error==200&&$amount>0){
   try { 

    $conn->trans_start(); 
    if($aff_type==2){
    
      $conn->query("UPDATE member_wallet SET main_wallet=$amount WHERE member_no = ?", [$member_no]);
      $conn->query("UPDATE member_wallet SET aff_wallet_l1=0,aff_wallet_l2=0 WHERE member_no = ?", [$member_no]);

    }else{
      $conn->query("UPDATE member_wallet SET main_wallet=main_wallet+aff_wallet_l1+aff_wallet_l2 WHERE member_no = ?", [$member_no]);
      $conn->query("UPDATE member_wallet SET aff_wallet_l1=0,aff_wallet_l2=0 WHERE member_no = ?", [$member_no]);
    }
    
    $conn->trans_complete(); 
   } catch (\Throwable $ex) {//var_dump($ex->getMessage());

    $conn->trans_rollback(); 
    $msg="ไม่สามารถดำเนินการได้กรุณาลองอีกครั้ง";
    $error=500;
   }

  if ($error==200&&$conn->trans_status() === TRUE){
    $sql1="INSERT INTO log_deposit(member_no,amount,channel,trx_id,trx_date,trx_time,status,remark)";
    $sql1.=" VALUES(?,?,?,?,?,?,?,?)";
    $sql1_value=[$member_no,$amount,$channel,uniqid(),date('Y-m-d'), date('H:i:s'),1,'AFF'];
    $conn->query($sql1,$sql1_value);
    $sqlMember_s = "SELECT * FROM members WHERE id=?";  
    $resultMember_s = $conn->query($sqlMember_s,[$member_no])->row_array();

    $sql23="INSERT INTO log_claim_aff(member_no,member_username,amount,update_date)";
    $sql23.=" VALUES(?,?,?,?)";
    $sql23_value=[$member_no,$resultMember_s['username'],$amount,date('Y-m-d H:i:s')];

    $conn->query($sql23,$sql23_value); 
    $conn->query("UPDATE members SET member_promo=0,member_last_deposit=? WHERE id=?",[$channel,$member_no]);
    $conn->query("UPDATE aff_member_branch SET claim_at=? WHERE member_no=?",[$member_no,date('Y-m-d H:i:s')]);
    
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
   } 
 } 
 if ($error==200) {
      ?>
       <script>
         Swal.fire("โอนเครดิตแนะนำเข้ากระเป๋าหลัก", "โอนสำเร็จ", "success");
        </script>
      <?php
 }else{
    ?>
    <script>
      Swal.fire("โอนเครดิตแนะนำเข้ากระเป๋าหลัก","<?=$msg?>", "error");
     </script>
   <?php
 }
 ?>

 



 

