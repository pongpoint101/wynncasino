<?php
 require '../bootstart.php'; 
 require_once ROOT.'/core/security.php';
 $member_no=$_SESSION['member_no'];
 if (isset($limiter)) {
    $limiterns = $limiter->hit('requestcaskbackclaim:' .$member_no, 1, 5); // เรียกได้ 1 ครั้งใน 5 วินาที
    if ($limiterns['overlimit']) {
		InSertLogSys(['member_no'=>$member_no,'ip'=>getIP(),'log_type'=>'com-maxlimit','txt_data'=>date('Y-m-d').' '.date('H:i:s')]);
        exit(0);
    }
}
 $Website = GetWebSites(); 
$comm_type = @$_POST['comm_type']; // 1 = รับค่าคอม // 2 = คืนยอดเสีย
 
$msg='';
$IsBoolean = true;
$dataResult = "";

$channel = 0; 
 
$TextCommType = "";
$DataBaseComm = "";
$remark='';
if($comm_type == 1){
	$msql="SELECT id,member_no,amount,status FROM member_a_commission WHERE member_no=? AND status = 0";
	$TextCommType = "คอมมิชชั่น";
	$remark = "COMM";
	$channel = 18;$claimtype=2;
} else if($comm_type == 2){
	$msql="SELECT id,member_no,amount,status FROM member_a_return_loss WHERE member_no=? AND status = 0"; 
	$TextCommType = "คืนยอดเสีย";
	$remark = "CASHBACK";
	$channel = 17;$claimtype=3;
} 
if (in_array($claimtype,[2,3])) {
	$dataResult = $conn->query($msql,[$member_no])->row_array();
}else{
 exit();
}
require_once ROOT.'/core/checkClaim.php'; 

if($dataResult != ""){
	if($dataResult['amount'] > 0){
	 try { 
	    $conn->trans_start(); 
	    $dataResult = $conn->query($msql,[$member_no])->row_array(); 
         if($dataResult['amount'] <= 0){InSertLogSys(['member_no'=>$member_no,'log_type'=>'com','txt_data'=>date('Y-m-d').' '.date('H:i:s')]);exit();}
		 if($comm_type == 1){
			$conn->query("UPDATE member_a_commission SET status=1 WHERE member_no = ? AND id = ?", [$member_no,$dataResult['id']]);
			$conn->query("UPDATE member_a_return_loss SET status=2 WHERE member_no = ? AND status = 0", [$member_no]);
		 }	else if($comm_type == 2){
			$conn->query("UPDATE member_a_return_loss SET status=1 WHERE member_no = ? AND id = ?", [$member_no,$dataResult['id']]);
			$conn->query("UPDATE member_a_commission SET status=2 WHERE member_no = ? AND status = 0", [$member_no]);
		} 
		$main_wallet = $conn->query("SELECT main_wallet FROM member_wallet WHERE member_no=? ",[$member_no])->row_array()['main_wallet'];
		$conn->query("UPDATE member_wallet SET main_wallet=main_wallet+".$dataResult['amount']." WHERE member_no = ?", [$member_no]); 
		$conn->query("UPDATE members SET member_promo=0,ignore_zero_turnover=0,member_last_deposit =? WHERE id = ?", [$channel,$member_no]);
		$sql = "UPDATE member_turnover_product SET current_turnover=0,update_date=? WHERE member_no=? AND current_turnover!=0";
	    $conn->query($sql, [date("Y-m-d H:i:s"),$member_no]);
		// clear pro all 
		// $conn->query("UPDATE promop50p SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
		// $conn->query("UPDATE promo_happy_time SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promo_cc200 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promop120 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promop121 SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
		// $conn->query("UPDATE promofree50 SET status=2 WHERE status=1 AND member_no=?",[$member_no]);
		// $conn->query("UPDATE promofree60 SET status=2 WHERE status=1 AND member_no=?",[$member_no]); 
		// $conn->query("UPDATE promop10p SET status=2 WHERE status=1 AND member_no=?",[$member_no]);  
		//$conn->trans_complete();   
			$sql1="INSERT INTO log_deposit(member_no,amount,channel,trx_id,trx_date,trx_time,status,remark)"; 
		    $sql1.=" VALUES(?,?,?,?,?,?,?,?)";

		    $sql1_value=[$member_no,$dataResult['amount'],$channel,uniqid(),date('Y-m-d'), date('H:i:s'),1,$remark];
		    $conn->query($sql1,$sql1_value); 
			$conn->trans_complete();  
	  } catch (\Throwable $th) {
		$conn->trans_rollback(); 
	 }	
	?>
		<script>
			Swal.fire("โอน<?=$TextCommType?>เข้ากระเป๋าหลัก", "โอนสำเร็จ", "success");
		</script>
	<?php
	$turnover_expect=($main_wallet+$dataResult['amount'])*5;
	switch ($claimtype) {
		case 2:
			$turnover_expect=($main_wallet+$dataResult['amount'])*2;
			$data = array(
				'member_no' => $member_no,
				'turnover_expect'=>$turnover_expect,
				'amount' =>$dataResult['amount'] 
				); 
			$conn->insert('log_claim_commission', $data); 
			break;
			case 3:
				$data = array(
				'member_no' => $member_no,
				'turnover_expect'=>$turnover_expect,
				'amount' =>$dataResult['amount'] 
				); 
				$conn->insert('log_claim_cashback', $data); 
			break; 
		default: 	break;
	}
	} else {
	?>
		<script>
			Swal.fire("โอน<?=$TextCommType?>เข้ากระเป๋าหลัก","ไม่สามารถทำรายการได้", "error");
		</script>
	<?php
	}
} else {
	?>
		<script>
			Swal.fire("โอน<?=$TextCommType?>เข้ากระเป๋าหลัก","ไม่สามารถทำรายการได้", "error");
		</script>
	<?php
}
?>