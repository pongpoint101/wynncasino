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
        <title>Commission - <?=$WebSites['title']?></title>
        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?> 
        <style type="text/css">
            .mbbox .cnew00{display: block;}
            .mbbox table tr th {font-size: 10px;}
            .exboxsub77{
                padding: 0 0 10px 0;
            }
            .boxsub77body{
                background: none;
            }
            @media screen and (max-width: 660px) {
                .boxsub77{
                    margin: 85px auto 0px auto;
                    border-bottom-right-radius: 40px;
                    border-bottom-left-radius: 40px;
                }
            }
            #nav-commission-mobile *,#nav-return-mobile *{
                color: #fff;
            }
        </style>
    </head>
    <body>

        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="pcbox">
                <div class="mainheadbg">
                    <div class="container">
                        <div class="boxsub77 exboxsub77">
                            <div class="boxsub77head">
                                <span class="boxsub77headt1"><h3><i class="fa fa-history"></i> คอมมิชชั่น/คืนยอดเสีย <i class="fa fa-history"></i></h3></span> 
                            </div>
                            <div class="bodyx111 mb-1 bgnone">
                                <div class="row g-3">
                                    <div class="w100">
                                        <div class="cnew00 mb-3 pg-nav-tabs-model-style">
                                            <div class="row g-0 flexhcenter nav nav-tabs" role="tablist">
                                                <div class="nav-link active col-3" id="nav-commission-tab" data-bs-toggle="tab" data-bs-target="#nav-commission" role="tab" aria-controls="nav-deposit" aria-selected="true">
                                                    <a href="javascript:void(0);" v-on:click="Changecommtabs('commission')">
                                                        <i class='fas fa-comment-dollar' style='font-size:1.8em;color:black;'></i><br />
                                                        คอมมิชชั่น
                                                    </a>
                                                </div>
                                                <div class="nav-link col-3" id="nav-return-tab" data-bs-toggle="tab" data-bs-target="#nav-return" role="tab" aria-controls="nav-withdraw" aria-selected="true">
                                                    <a href="javascript:void(0);" v-on:click="Changecommtabs('return_loss')">
                                                        <i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i><br /> 
                                                        คืนยอดเสีย
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
                                            <div class="tab-pane fade show active table-responsive" id="nav-commission" role="tabpanel" aria-labelledby="nav-commission-tab" :selected="true">
                                                <table class="table withdraw_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr> 
                                                            <th scope="col">วันที่</th>
                                                            <th scope="col">คอมมิชชั่น</th>
                                                            <th scope="col" class="text-center">สถานะ</th>
                                                            <th scope="col">หมายเหตุ</th> 
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="item in history_comm.datalist"> 
                                                            <th scope="col">{{item.date_receive}}</th>
                                                            <th scope="col">{{item.amount}}</th>
                                                            <th v-if="item.status==1" v-html="item.statustxt" scope="col"></th> 
                                                            <th v-else scope="col" class="text-center" v-html="item.statustxt" @click="claimCommAndReturn(1)"></th>
                                                            <th scope="col">{{item.remark}}</th> 
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade table-responsive" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab">
                                                <table class="table withdraw_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr> 
                                                            <th scope="col">วันที่</th>
                                                            <th scope="col">คืนยอดเสีย</th>
                                                            <th scope="col" class="text-center">สถานะ</th>
                                                            <th scope="col">หมายเหตุ</th> 
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="item in history_comm.datalist"> 
                                                            <th scope="col">{{item.date_receive}}</th>
                                                            <th scope="col">{{item.amount}}</th> 
                                                            <th  v-if="item.status==1" scope="col" v-html="item.statustxt"></th> 
                                                            <th  v-else scope="col" class="text-center" v-html="item.statustxt" @click="claimCommAndReturn(2)"></th>
                                                            <th scope="col">{{item.remark}}</th> 
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div style="color: red;text-align: center;">
                                            *** สมาชิกสามารถรับได้อย่างใดอย่างหนึ่งเท่านั้น ***<br>
                                            *** คอมมิชชั่นหรือยอดเสียที่เกิดขึ้นในวันนี้ จะสามารถรับได้ในวันพรุ่งนี้ ***
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
                                            <div class="w1002"><span class="boxfont1">คอมมิชชั่น/คืนยอดเสีย</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cnew00 mb-3 pg-nav-tabs-model-style">
                            <div class="row g-2 flexhcenter nav nav-tabs" role="tablist">
                                <div class="nav-link active col-3 p-0" id="nav-commission-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-commission-mobile" role="tab" aria-controls="nav-commission-mobile" aria-selected="true" style="width: auto;">
                                    <a href="javascript:void(0);" style="padding: 0 20px;" v-on:click="Changecommtabs('commission')">
                                        <i class='fas fa-comment-dollar' style='font-size:1.8em;color:black;'></i><br />
                                        คอมมิชชั่น
                                    </a>
                                </div>
                                <div class="nav-link col-3 p-0" id="nav-return-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-return-mobile" role="tab" aria-controls="nav-return-mobile" aria-selected="true" style="width: auto;">
                                    <a href="javascript:void(0);" style="padding: 0 20px;" v-on:click="Changecommtabs('return_loss')">
                                        <i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i><br /> 
                                        คืนยอดเสีย
                                    </a>
                                </div> 
                            </div>
                        </div>

                        <div class="w100 tab-content name">
                            <div class="tab-pane fade show active table-responsive" id="nav-commission-mobile" role="tabpanel" aria-labelledby="nav-commission-mobile-tab" :selected="true">
                                <table class="table withdraw_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr> 
                                            <th scope="col">วันที่</th>
                                            <th scope="col">คอมมิชชั่น</th>
                                            <th scope="col" class="text-center">สถานะ</th>
                                            <th scope="col">หมายเหตุ</th> 
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="item in history_comm.datalist"> 
                                            <th scope="col">{{item.date_receive}}</th>
                                            <th scope="col">{{item.amount}}</th>
                                            <th  v-if="item.status==1" v-html="item.statustxt" scope="col"></th> 
                                            <th  v-else scope="col" class="text-center" v-html="item.statustxt" @click="claimCommAndReturn(1)"></th>
                                            <th scope="col">{{item.remark}}</th> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade table-responsive" id="nav-return-mobile" role="tabpanel" aria-labelledby="nav-return-mobile-tab">
                                <table class="table withdraw_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr> 
                                            <th scope="col">วันที่</th>
                                            <th scope="col">คืนยอดเสีย</th>
                                            <th scope="col" class="text-center">สถานะ</th>
                                            <th scope="col">หมายเหตุ</th> 
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="item in history_comm.datalist"> 
                                            <th scope="col">{{item.date_receive}}</th>
                                            <th scope="col">{{item.amount}}</th>
                                            <th  v-if="item.status==1" v-html="item.statustxt" scope="col"></th> 
                                            <th  v-else scope="col" class="text-center" v-html="item.statustxt" @click="claimCommAndReturn(2)"></th>
                                            <th scope="col">{{item.remark}}</th> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div style="color: red;text-align: center;">
                            *** สมาชิกสามารถรับได้อย่างใดอย่างหนึ่งเท่านั้น ***<br>
                            *** คอมมิชชั่นหรือยอดเสียที่เกิดขึ้นในวันนี้ จะสามารถรับได้ในวันพรุ่งนี้ ***
                            </div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="bgpc">
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
                App.SetPageSection('dwCommission');
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