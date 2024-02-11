<?php
require '../bootstart.php'; 
require_once ROOT.'/core/security.php'; 
$WebSites=GetWebSites();
if(@$_SESSION['member_no'] == NULL){
    header( "location: ".GetFullDomain().'/login'); 
    exit(0);
}

$sitetitle='คอมมิชชั่น/คืนยอดเสีย';
$assets_head='<link rel="stylesheet" href="'.GetFullDomain().'/assets/css/commision_page.css?v=33">';
include_once ROOT_APP.'/componentx/header.php';
?> 
    <section class="info-section" id="app" v-cloak>
        <h1>คืนยอดเสีย</h1>
        <div class="btn-container mx-auto">
            <!-- <div> -->
                <!-- <p><span id="commision-amount">00000000.00</span><span>฿</span></p> -->
                <!-- <button id="commision-btn" v-on:click="switchelement=1;Changecommtabs('commission')">คอมมิชชั่น</button> -->
            <!-- </div> -->
            <div>
                <!-- <p><span id="lost-amount">00000000.00</span><span>฿</span></p> -->
                <button id="lost-btn button" v-on:click="switchelement=2;Changecommtabs('return_loss')">คืนยอดเสีย</button>
            </div>
        </div>

        <div class="tabcontent member-info mx-auto">

            <div class="table-container table-responsive">
                <table class="table table-borderless mx-auto" v-if="!boxloading">
                    <thead>
                        <tr>
                            <th scope="col">วันที่</th>
                            <th scope="col">คอมมิชชั่น</th>
                            <th class="text-center" scope="col">สถานะ</th>
                            <th scope="col">หมายเหตุ</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in history_comm.datalist">
                            <th scope="row">{{item.date_receive}}</th>
                            <td>{{item.amount}}</td>
                            <td v-if="item.status!=0" v-html="item.statustxt"></td>
                            <td class="text-center"  v-else-if="switchelement==1" v-html="item.statustxt" @click="claimCommAndReturn(1)"></td>
                            <td class="text-center" v-else-if="switchelement==2" v-html="item.statustxt" @click="claimCommAndReturn(2)"></td>
                            <td>{{item.remark}}</td> 
                        </tr>

                    </tbody>
                </table> 

                <hr />
                <div style="color: red;text-align: center;">
                *** ยอดเสียที่เกิดขึ้นในวันนี้จะสามารถรับได้ในวันพรุ่งนี้ *** <br>
                *** ระบบจะตัดคำนวณยอดเสียทุกวัน เวลา 00.00 น. ***
                </div>
            </div>

        </div>

    </section>
    <?php  
$assets_footer='<script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?>  
<script type="text/javascript">
    $(function(){
        App.switchelement=2;
        App.Changecommtabs('return_loss');
    });
</script>