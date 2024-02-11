<?php

require '../bootstart.php'; 
require_once ROOT.'/core/security.php';
require ROOT.'/public/theme_v1/functionall.php';
$WebSites=GetWebSites();
if(@$_SESSION['member_no'] == NULL){
    header( "location: ".$tg_fn->tugame_url('login'));
    exit(0);
}

?>

<!doctype html>

<html lang="th" style="scroll-behavior: smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>History - <?=$WebSites['title']?></title>

        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?> 
        <style type="text/css">
            .boxsub77body{
                background: none;
            }
            .mbbox .cnew00{display: block;}
            .mbbox table tr th {font-size: 10px;}
            .exboxsub77{
                padding: 0 0 10px 0;
            }
            @media screen and (max-width: 660px) {
                .boxsub77{
                    margin: 85px auto 0px auto;
                    border-bottom-right-radius: 40px;
                    border-bottom-left-radius: 40px;
                }
            }
            #deposit_m_history_table *,#withdraw_m_history_table *{
                color: #fff;
            }
        </style>
    </head>
    <body>

        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="pcbox mb-5">
                <div class="mainheadbg">
                    <div class="container">
                        <div class="boxsub77 exboxsub77" style="min-height: 500px;">
                            <div class="boxsub77head">
                                <span class="boxsub77headt1">ประวัติการเงิน</span> 
                            </div>
                            <div class="bodyx111 mb-1 bgnone">
                                <div class="row g-3">
                                    <div class="w100">
                                        <div class="cnew00 mb-3 pg-nav-tabs-model-style">
                                            <div class="row g-0 flexhcenter nav nav-tabs" role="tablist">
                                                <div class="nav-link active col-3" id="nav-deposit-tab" data-bs-toggle="tab" data-bs-target="#nav-deposit" role="tab" aria-controls="nav-deposit" aria-selected="true">
                                                    <a href="javascript:void(0);" v-on:click="Changedwtabs('deposit')" >
                                                        <i class='fas fa-comment-dollar' style='font-size:1.8em;color:black;'></i><br />
                                                        ฝากเงิน
                                                    </a>
                                                </div>
                                                <div class="nav-link col-3" id="nav-withdraw-tab" data-bs-toggle="tab" data-bs-target="#nav-withdraw" role="tab" aria-controls="nav-withdraw" aria-selected="true">
                                                    <a href="javascript:void(0);" v-on:click="Changedwtabs('withdraw')">
                                                        <i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i><br /> 
                                                        ถอนเงิน
                                                    </a>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boxsub77body mb-4">
                                <div class="w90">
                                    <div class="row"> 
                                        <div class="col-12 tab-content">
                                            <div class="tab-pane fade show active table-responsive" id="nav-deposit" role="tabpanel" aria-labelledby="nav-deposit-tab" :selected="true">
                                                <table class="table withdraw_history_table" id="deposit_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr>
                                                            <th scope="col">หมายเลขธุรกรรม</th>
                                                            <th scope="col">วันที่</th>
                                                            <th scope="col">ชื่อผู้ใช้</th>
                                                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                                                            <th scope="col">จำนวนเงิน</th>
                                                            <th scope="col">สถานะ</th>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="item in history_money.datalist">
                                                            <th scope="col">{{item.trx_id}}</th>
                                                            <th scope="col">{{item.create_date}}</th>
                                                            <th scope="col">{{data_global.fname}} {{data_global.lname}}</th>
                                                            <th scope="col">{{item.bank_name_to}}<br>{{item.bank_number_to}}</th>
                                                            <th scope="col">{{item.amount}}</th>
                                                            <th scope="col">{{item.status}}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade table-responsive" id="nav-withdraw" role="tabpanel" aria-labelledby="nav-withdraw-tab">
                                                <table class="table withdraw_history_table" id="withdraw_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr>
                                                            <th scope="col">หมายเลขธุรกรรม</th>
                                                            <th scope="col">วันที่</th>
                                                            <th scope="col">ชื่อผู้ใช้</th>
                                                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                                                            <th scope="col">จำนวนเงิน</th>
                                                            <th scope="col">สถานะ</th>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="(item,index) in history_money.datalist">
                                                            <th scope="col">{{item.trx_id}}</th>
                                                            <th scope="col">{{item.create_date}}</th>
                                                            <th scope="col">{{data_global.fname}} {{data_global.lname}}</th>
                                                            <th scope="col">{{item.name_in_bank}}</th>
                                                            <th scope="col">{{item.bank_name_to}}<br>{{item.bank_number_to}}</th>
                                                            <th scope="col">{{item.amount}}</th>
                                                            <th scope="col">{{item.status}}</th>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>  
                            
                        </div>
                    </div>
                </div>

            </div>


            <div class="mbbox">
                <div class="container">
                    <div class="row mb-4">
                        <div class="boxsub77body mb-3">
                            <div class="w90">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">
                                            <div class="w1002"><span class="boxfont1">ประวัติการเงิน</span></div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="cnew00 mb-3 pg-nav-tabs-model-style">
                            <div class="row g-2 flexhcenter nav nav-tabs" role="tablist">
                                <div class="nav-link active col-3 p-0" id="nav-deposit-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-deposit-mobile" role="tab" aria-controls="nav-deposit-mobile" aria-selected="true" style="width: auto;">
                                    <a href="javascript:void(0);" v-on:click="Changedwtabs('deposit')" style="padding: 0 20px;">
                                        <i class='fas fa-comment-dollar' style='font-size:1.8em;color:black;'></i><br />
                                        ฝากเงิน
                                    </a>
                                </div>
                                <div class="nav-link col-3 p-0" id="nav-withdraw-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-withdraw-mobile" role="tab" aria-controls="nav-withdraw-mobile" aria-selected="true" style="width: auto;">
                                    <a href="javascript:void(0);" v-on:click="Changedwtabs('withdraw')" style="padding: 0 20px;">
                                        <i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i><br /> 
                                        ถอนเงิน
                                    </a>
                                </div> 
                            </div>
                        </div>

                        <div class="w100 tab-content name">
                            <div class="tab-pane fade show active" id="nav-deposit-mobile" role="tabpanel" aria-labelledby="nav-deposit-mobile-tab" :selected="true">
                                <table class="table withdraw_history_table" id="deposit_m_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr>
                                            <th scope="col">หมายเลขธุรกรรม</th>
                                            <th scope="col">วันที่</th>
                                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                                            <th scope="col">จำนวนเงิน</th>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="item in history_money.datalist">
                                            <th scope="col">{{item.trx_id}}</th>
                                            <th scope="col">{{item.create_date}}</th>
                                            <th scope="col">{{item.bank_name_to}}<br>{{item.bank_number_to}}</th>
                                            <th scope="col">{{item.amount}}<br>({{item.status}})</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-withdraw-mobile" role="tabpanel" aria-labelledby="nav-withdraw-mobile-tab">
                                <table class="table withdraw_history_table" id="withdraw_m_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr>
                                            <th scope="col">หมายเลขธุรกรรม</th>
                                            <th scope="col">วันที่</th>
                                            <th scope="col">ชื่อผู้ใช้</th>
                                            <th scope="col">โอนเข้าธนาคาร/หมายเหตุ</th>
                                            <th scope="col">จำนวนเงิน</th>
                                            <th scope="col">สถานะ</th>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="(item,index) in history_money.datalist">
                                            <th scope="col">{{item.trx_id}}</th>
                                            <th scope="col">{{item.create_date}}</th>
                                            <th scope="col">{{item.name_in_bank}}</th>
                                            <th scope="col">{{item.bank_name_to}}<br>{{item.bank_number_to}}</th>
                                            <th scope="col">{{item.amount}}<br>({{item.status}})</th>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        


                    </div>
                </div>
            </div>

            <div class="bgpc mt-5">
                <div class="mainbodyfootbg">
                    <div class="container">
                        <div class="row g-3 cnew00margin">
                            <?php
                            require_once ROOT.'/public/theme_v1/footer.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            require_once ROOT.'/public/theme_v1/model_html.php';
            ?>

        </div>
        <?php
        require_once ROOT.'/public/theme_v1/js_script.php';
        ?>
        <script type="text/javascript">
            $(function(){
               App.SetPageSection('dwHistorySection');
            });
            function copyAffURL() {
                var copyText = document.getElementById("aff_url");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                Swal.fire({
                    icon: "success",
                    title: "คัดลอกแล้ว",
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        </script>
    </body>
</html>