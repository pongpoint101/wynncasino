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
$pro_id=19;$td_txt='';$loadtabs='';
switch (strtolower($_GET['rewardtype'])) {
    case 'maxplayer':$pro_id=19;$td_txt='ยอดเล่น';$loadtabs=$_GET['rewardtype'];break; 
    case 'cashbackplayer':$pro_id=180;$td_txt='ยอดเสีย';$loadtabs=$_GET['rewardtype'];break; 
    case 'aff':$pro_id=179;$td_txt='ยอดแนะนำเพื่อน';$loadtabs=$_GET['rewardtype'];break; 
    case 'maxwinnerplayer':$pro_id=181;$td_txt='ยอดชนะ';
    // $previous_day=date('Y-m-d', strtotime(' -2 day'));
    $loadtabs=$_GET['rewardtype'];
    break; 
} 

$sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,pro_symbol 
,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
$sql.=" FROM pro_promotion_detail a 
INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
WHERE a.pro_id=? AND pro_status=1 ORDER BY rating;";
$datakey='data_daily_reward:list:'; 
$data_reward=GetDataSqlWhereAll($datakey,$sql,[$pro_id],5*60);
$pro_description='';$pro_channel='';
 if(isset($data_reward[0]['pro_description'])){
  $pro_description=$data_reward[0]['pro_description'];$pro_channel=$data_reward[0]['pro_id'];
 }
?> 
<style>
  .boxdateselect{margin-top: 10px;}
  .mdateselect{ border: 1px solid #707070;
    background-color: white;
    border-radius: 3px;
    line-height: 2;
    padding: 0 8px;
    }
</style> 
<section class="info-section" id="app" v-cloak>
    <div class="member-info mx-auto">
    <div class="boxdateselect"> 
       <label for="dateselect">วันที่</label>
       <input class="mdateselect" id="mdateselect" type="text" placeholder="เลือกวันที่..." size="12" value="<?=$previous_day?>">
    </div>    
    <div class="accout-info">
                    <h1>รางวัล<?=$td_txt?>สูงสุดประจำวันที่ <span id="txt_updatedate"><?=thai_date_fullmonth(strtotime($previous_day))?></span></h1>
    </div>
    <div class="table-container table-responsive">
        <img src = "../assets/images/common/loading.svg" v-if="boxloading" width="100" height="100" style="display: block;margin: 0 auto;"/> 
        <table class="table table-borderless mx-auto" v-if="!boxloading">
            <thead>
                <tr>
                    <th scope="col">ลำดับที่</th>
                    <th scope="col">รหัสสมาชิก</th>
                    <th scope="col"><?=$td_txt?></th>
                    <th scope="col">รางวัล</th>
                    <th scope="col">สถานะ</th>
                </tr>
            </thead>
            <tbody>
               
                <tr v-for="item in history_money.datalist">
                    <th scope="row">{{item.count_no}}</th>
                    <td>{{item.member_no}}</td>
                    <td>{{item.amount}}</td>
                    <td>{{item.rewardAmount}}</td>
                    <td>
                    <button type="button" class="btn btn-primary" v-if="item.member_allow!=0&&item.status==1" v-on:click="get_bonus(<?=$pro_channel;?>);">กดรับโบนัส</button>
                        <span  v-if="item.status==2">รับโบนัสแล้ว</span> 
                        <span  v-if="item.status==3">หมดอายุ</span> 
                        <span  v-if="item.member_allow==0&&item.status==1">รอรับรางวัล</span>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <div style="color: red;text-align:center;"> <?=$pro_description;?></div> 
    </div>    
 </div>
</section>

<?php

$assets_footer='<script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>
                <script src="'.GetFullDomain().'/assets/js/copy_link_btn_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script> 
<script type="text/javascript"> 
$(function(){
        <?php
            if($loadtabs!=''){
            ?>
                App.ReWardtabs('<?=$loadtabs?>');
            <?php  
            }
        ?> 
      Vue.nextTick().then(function () {
         var mdateselect=$(".mdateselect").flatpickr({
            "locale": "th",
            minDate: new Date().fp_incr(-365),
            maxDate: new Date().fp_incr(-1),
            onChange: function(selectedDates, dateStr, instance) { 
                const dateObject = new Date(dateStr);
                const formattedDate = `${dateObject.getDate()} ${getThaiMonth(dateObject.getMonth())} ${dateObject.getFullYear() + 543}`;
                $('#txt_updatedate').html(formattedDate);
                <?php
                 if($loadtabs!=''){
                    ?>
                     App.ReWardtabs('<?=$loadtabs?>');
                  <?php  
                 }
                ?>    
           }
        }); 
    });
});
</script>