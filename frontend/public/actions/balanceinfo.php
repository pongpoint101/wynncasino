<?php

require '../bootstart.php'; 

require_once ROOT.'/core/security.php';

header('Content-Type: application/json');

$key='MB:AFF:'.$_SESSION['member_no'];

$Website = GetWebSites();

$count_level_1 = 0;

$count_level_2 = 0;

$last_refresh='';

$sql = "SELECT id,af_code,turnover_all,commission_wallet,commission_money_all,aff_type FROM members WHERE id=?"; // AND status='1'";

$row =GetDataSqlWhereOne($key,$sql,[$_SESSION['member_no']],5*60);

$memberwallet=getMemberWallet($_SESSION['member_no']);

$sql = "SELECT group_af_l1,group_af_l2 FROM members WHERE group_af_l1 is not null and group_af_l1=? ";

$aff_branch_level_1 = $conn->query($sql,[$_SESSION['member_no']])->result_array();

foreach ($aff_branch_level_1 as $rows_l1) { 
    $count_level_1++;  
}
$sql = "SELECT group_af_l1,group_af_l2 FROM members WHERE group_af_l2 is not null and group_af_l2=?"; 
$aff_branch_level_2 = $conn->query($sql,[$_SESSION['member_no']])->result_array();
//echo $conn->last_query();
foreach ($aff_branch_level_2 as $rows_l2) {  
   $count_level_2++; 
}

$data['aff_turnover_l1']= isset($memberwallet) ? number_format($memberwallet['aff_wallet_l1'],2,".",",") : 0;

$data['aff_turnover_l2']= isset($memberwallet) ? number_format($memberwallet['aff_wallet_l2'],2,".",",") : 0;

$data['aff_url'] = GetFullDomain().'/register?aff='.$row['af_code']; 

$data['aff_type'] = $row['aff_type']; 

$data['count_l1'] = $count_level_1;

$data['count_l2'] = $count_level_2;

$data['turnover_all']= number_format($row['turnover_all'],2,".",",");

$data['commission_wallet']= isset($memberwallet) ? number_format($memberwallet['commission_wallet'],2,".",","):0;

$data['commission_money_all']= number_format($row['commission_money_all'],2,".",",");

$data['total_comm'] = isset($memberwallet) ? number_format($memberwallet['aff_wallet_l1']+$memberwallet['aff_wallet_l2'],2,".",",") : 0;

$data['min_aff_claim'] = $Website['min_aff_claim'];

echo json_encode($data);

exit(); 

function decodeMemberAFGroup($group){

    try {

       $group = json_decode($group, true);

        return (int) @count($group);

    } catch (\Throwable $th) {

        //throw $th;

    }

    return 0;

}

?>

