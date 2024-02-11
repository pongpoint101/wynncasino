<?php
require '../bootstart.php';  
require_once ROOT.'/core/security.php';
header('Content-Type: application/json');
$member_no=$_SESSION['member_no'];

$sql = "SELECT id,username,fname,lname,bank_name,bank_accountnumber,bank_code,member_promo, member_last_deposit,ignore_zero_turnover FROM members WHERE id=?"; // AND status='1'";
$arr_val = $conn->query($sql,[$member_no])->row_array();  
$member_wallet=getMemberWallet($member_no);
if ($member_wallet['main_wallet'] <= 10) {// ถ้ามียอดเงินเหลือน้อยกว่า 10 บาท ให้เช็คยอดค้าง ถ้ามีเติมเงินยอดที่ค้าง 
     //deposite_PaddingUpdate($member_no,$member_wallet['main_wallet']); 
     //$arr_val = $conn->query($sql,[$member_no])->row_array();
}
$Website = GetWebSites(); 
$pro=getMemberPro($member_no);
$turnovertotal=getTotalTurnover($member_no);

$arr_val['username'] = $arr_val['username'];
$arr_val['fname'] = $arr_val['fname'];
$arr_val['lname'] = $arr_val['lname']; 
$arr_val['withdraw_balance'] =number_format($member_wallet['main_wallet'],2,".",",");
$arr_val['main_wallet']=number_format($member_wallet['main_wallet'],2,".",",");
$arr_val['min_wd'] =$Website['min_withdraw']; 
$arr_val['max_withdraw'] =$Website['max_withdraw']; 
$arr_val['max_withdraw_perday'] =$Website['max_withdraw_perday'];
$arr_val['min_aff_claim'] =$Website['min_aff_claim']; 
$arr_val['min_comm_claim'] =$Website['min_comm_claim']; 
$arr_val['min_accept_promo'] =$Website['min_accept_promo'];  
$arr_val['bank_name'] = BankCode2Name($arr_val['bank_name']);
$arr_val['bank_code'] = $arr_val['bank_code'];
$arr_val['bank_accountnumber'] = $arr_val['bank_accountnumber'];
$arr_val['aff_wallet_l1'] = $member_wallet['aff_wallet_l1'];
$arr_val['aff_wallet_l2'] =  $member_wallet['aff_wallet_l2'];
$arr_val['cashback_wallet'] = 0;
$arr_val['comm_claimed'] = 0;
$arr_val['commission_wallet'] = 0;
$arr_val['turnover'] = 0;
$arr_val['turnover_expect'] =(@$pro['turnover_expect'])>0?$pro['turnover_expect']:0;
$arr_val['turnover_now'] = $turnovertotal; 
$arr_val['deposit'] = 0;
$arr_val['withdraw'] =0; 
$arr_val['promo_id'] = $arr_val['member_promo'];;
$arr_val['member_last_deposit'] =$arr_val['member_last_deposit'];; 
$arr_val['ignore_zero_turnover'] =$arr_val['ignore_zero_turnover'];; 
echo json_encode($arr_val);
exit();