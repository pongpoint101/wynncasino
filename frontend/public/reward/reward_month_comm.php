<?php
require '../bootstart.php'; 
require_once ROOT.'/core/security.php'; 
$WebSites=GetWebSites();
if(@$_SESSION['member_no'] == NULL){
    header( "location: ".GetFullDomain().'/login'); 
    exit(0);
}

$sitetitle='รางวัลประจำวัน';
$assets_head='<link rel="stylesheet" href="'.GetFullDomain().'/assets/css/rewards_page.css">';
include_once ROOT_APP.'/componentx/header.php';

$currMonth = date('n'); 
$currYear = date('Y'); 
if ($currMonth<=1) {
$currMonth = 12; 
$currYear = date('Y')-1; 
}else{
$currMonth = date('n')-1;  
}
$pre_month=$currYear.'-'.$currMonth.'-01';
$previous_day=date('Y-m-d', strtotime(' -1 day'));

$sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,pro_symbol 
,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
$sql.=" FROM pro_promotion_detail a 
INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
WHERE a.pro_id=24 AND pro_status=1 ORDER BY rating;";
$datakey='data_mcomm_reward:list:'; 
$data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);
$pro_description='';$pro_channel='';
 if(isset($data_reward[0]['pro_description'])){
  $pro_description=$data_reward[0]['pro_description'];$pro_channel=$data_reward[0]['pro_id'];
 }

?> 
<section class="info-section" id="app" v-cloak>
    <div class="member-info mx-auto">
        <div class="accout-info">
                        <h1>รางวัลประจำเดือน(คอมมิชชั่น) เดือน<?=thai_date_fullmonth(strtotime($pre_month),true)?></h1>
        </div>
        <div class="table-container table-responsive">
            <table class="table table-borderless mx-auto" v-if="!boxloading">
                <thead>
                    <tr>
                        <th scope="col">ลำดับที่</th>
                        <th scope="col">รหัสสมาชิก</th>
                        <th scope="col">ยอดคอมมิชชั่น ที่ทำได้</th>
                        <th scope="col">รางวัล</th>
                    </tr>
                </thead>
                <tbody>
                     
                    <tr v-for="(item,index) in history_money.datalist">
                        <th scope="col">{{item.count_no}}</th>
                        <th scope="col">{{item.member_no}}</th>
                        <th scope="col">{{item.amount}} </th>
                        <th scope="col">{{item.rewardAmount}}</th> 
                   </tr>
                 
                </tbody>
            </table>
            <div style="color: red;text-align:center;"><?=$pro_description?></div>  
        </div>  
  </div>
</section> 
 <?php  
$assets_footer='<script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>
                <script src="'.GetFullDomain().'/assets/js/copy_link_btn_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?> 
<script type="text/javascript"> 
$(function(){
    <?php if($pro_channel!=''){ ?>
    App.ReWardtabs('com');
    <?php } ?>   
});
</script>