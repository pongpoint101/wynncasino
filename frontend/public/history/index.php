<?php
require '../bootstart.php'; 
require_once ROOT.'/core/security.php'; 
$WebSites=GetWebSites();
if(@$_SESSION['member_no'] == NULL){
    header( "location: ".GetFullDomain().'/login'); 
    exit(0);
}

$sitetitle='ประวัติฝาก/ถอน';
$assets_head='<link rel="stylesheet" href="'.GetFullDomain().'/assets/css/transactionhistory.css">';
include_once ROOT_APP.'/componentx/header.php';
?> 
    <section class="info-section" id="app" v-cloak>
        <div class="tab mx-auto">
            <button class="tablinks" id="defaultOpen" onclick="openGames(event, 'deposite-history')" v-on:click="Changedwtabs('deposit')">
                <img src="../assets/images/transaction-history/deposit_icon.svg" alt="deposite history">
                <div>
                    <h3>ประวัติ</h3>
                    <h4>การฝากเงิน</h4>
                </div>
              
            </button>
            <button class="tablinks" onclick="openGames(event, 'withdraw-history')" v-on:click="Changedwtabs('withdraw')">
                <img src="../assets/images/transaction-history/withdraw_icon.svg" alt="withdraw history">
                <div>
                    <h3>ประวัติ</h3>
                    <h4>การถอนเงิน</h4>
                </div>
                
            </button>
        </div>
        <div id="deposite-history" class="tabcontent member-info mx-auto">
            <div class="accout-info">
                <h1>ประวัติ การฝากเงิน</h1>
            </div>
            <div class="table-container table-responsive">
                <table class="table table-borderless mx-auto" v-if="!boxloading">
                    <thead>
                        <tr>
                            <th scope="col">หมายเลขธุรกรรม</th>
                            <th scope="col">วันที่</th>
                            <th scope="col">ชื่อผู้ใช้</th>
                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                            <th scope="col">จำนวนเงิน</th>
                            <th scope="col">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in history_money.datalist">
                            <th scope="row">{{item.trx_id}}</th>
                            <td>{{item.create_date}}</td>
                            <td>{{data_global.fname}} {{data_global.lname}}</td>
                            <td>{{item.bank_name_to}}<br>{{item.bank_number_to}}</td>
                            <td>{{item.amount}}</td>
                            <td>{{item.status}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
        <div id="withdraw-history" class="tabcontent member-info mx-auto">
            <div class="accout-info">
                <h1>ประวัติ การถอนเงิน</h1>
            </div>
            <div class="table-container table-responsive">
                <table class="table table-borderless mx-auto" v-if="!boxloading">
                    <thead>
                        <tr>
                            <th scope="col">หมายเลขธุรกรรม</th>
                            <th scope="col">วันที่</th>
                            <th scope="col">ชื่อผู้ใช้</th>
                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                            <th scope="col">จำนวนเงิน</th>
                            <th scope="col">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in history_money.datalist">
                            <th scope="row">{{item.trx_id}}</th>
                            <td>{{item.create_date}}</td>
                            <td>{{data_global.fname}} {{data_global.lname}}</td>
                            <td>{{item.bank_name_to}}<br>{{item.bank_number_to}}</td>
                            <td>{{item.amount}}</td>
                            <td>{{item.status}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <?php  
$assets_footer='<script src="'.GetFullDomain().'/assets/js/tap_panel_control.js"></script>
                <script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?>  
<script type="text/javascript">
$(function(){
    App.SetPageSection('dwHistorySection');
}); 
</script>