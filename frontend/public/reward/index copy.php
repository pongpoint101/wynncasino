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
        <title>รายการรางวัล - <?=$WebSites['title']?></title> 
        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';

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
        ?> 
        <style type="text/css">
            .boxsub77body{
                background: none;
            }
            .withdraw_history_table {color: #000;}
            .mbbox .cnew00{display: block;}
            .mbbox table tr th {font-size: 10px;}
            .exboxsub77{
                padding: 0 0 10px 0;
            }
            @media screen and (max-width: 660px) {
                .boxsub77white{
                    margin: 85px auto 0px auto;
                    border-bottom-right-radius: 40px;
                    border-bottom-left-radius: 40px;
                }
            }
            #deposit_m_history_table *,#withdraw_m_history_table *,#reward_daily_m_history_table *{
                color: #fff;
            }
            .nav button[aria-selected="true"]{ background-color: #ffc107;border-color: #ffc107;color: #FFF !important;}
            .nav button[aria-selected="true"] i{ color: #FFF !important;}
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
                        <div class="boxsub77white exboxsub77" style="min-height: 500px;"> 
                            <div class="bodyx111 mb-1 bgnone">
                                <div class="row g-3">
                                    <div class="w100">
      
                                            <div class="menubot mb-3"> 
 
                                            <div class="row g-0  nav nav-tabs" role="tablist">  
                                                <div class="col-lg-4 col-md-4 col-4 mb-2 nav-link active"  data-bs-toggle="tab" data-bs-target="#nav-reward_daily" role="tab" aria-controls="nav-reward_daily" aria-selected="true"> 
                                                    <a href="javascript:void(0);" v-on:click="ReWardtabs('rewarddaily')" style="border-right:0;"> 
                                                    <i class='fal fa-trophy'  style='font-size:1.8em;color:gold'></i> <br />  
                                                    รางวัลประจำวัน <span class="menubotfontsize2" style='font-size:.7em;'>(ยอดการเล่น 5 อันดับสูงสุด)</span></a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-4 mb-2 nav-link" data-bs-toggle="tab" data-bs-target="#nav-deposit" role="tab" aria-controls="nav-deposit" aria-selected="true">
                                                   <a href="javascript:void(0);" v-on:click="ReWardtabs('aff')" style="border-right:0;"> 
                                                    <i class='fas fa-trophy-alt'  style='font-size:1.8em;color:gold'></i> <br />  
                                                    รางวัลประจำเดือน <span class="menubotfontsize2" style='font-size:.7em;'>(ค่าคอมแนะนำเพื่อนสูงสุด)</span></a>
                                                </div> 

                                                <div class="col-lg-4 col-md-4 col-4 mb-2 nav-link" id="nav-withdraw-tab" data-bs-toggle="tab" data-bs-target="#nav-withdraw" role="tab" aria-controls="nav-withdraw" aria-selected="true">
                                                   <a href="javascript:void(0);" v-on:click="ReWardtabs('com')" style="border-right:0;"> 
                                                    <i class='fas fa-trophy'  style='font-size:1.8em;color:gold'></i> <br />  
                                                    รางวัลประจำเดือน <span class="menubotfontsize2" style='font-size:.7em;'>(ยอดเงินคินสูงสุด)</span></a>
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

                                          <div class="tab-pane fade show active table-responsive" id="nav-reward_daily" role="tabpanel"  :selected="true">
                                               <H5> รางวัลประจำวันที่ <?=thai_date_fullmonth(strtotime($previous_day))?></H5>
                                               <table class="table withdraw_history_table" id="reward_daily_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr>
                                                            <th scope="col">ลำดับที่</th>
                                                            <th scope="col">รหัสสมาชิก</th> 
                                                            <th scope="col">ยอดเล่น</th>
                                                            <th scope="col">รางวัล</th> 
                                                            <th scope="col">สถานะ</th> 
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="item in history_money.datalist">
                                                            <th scope="col">{{item.count_no}}</th>
                                                            <th scope="col">{{item.member_no}}</th>
                                                            <th scope="col">{{item.amount}} </th>
                                                            <th scope="col">{{item.rewardAmount}}</th> 
                                                            <th scope="col">
                                                                <button type="button" class="btn btn-primary" v-if="item.member_allow!=0&&item.status==1" v-on:click="get_bonus(51);">กดรับโบนัส</button>
                                                                <span  v-if="item.status==3">รับโบนัสแล้ว</span>
                                                            </th> 
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                    <div>*** ยอดการเล่น 5 อันดับสูงสุด *** </div>
                                                    <div>*** อันดับ 1 รับ 1,000 ***</div>
                                                    <div>*** อันดับ 2 รับ 500 ***</div>
                                                    <div>*** อันดับ 3 รับ 300 ***</div>
                                                    <div>*** อันดับ 4,5 รับ 100 ***</div>
                                                    <div> *** ทำเทิร์น 5 เท่า ถอนไม่อั้น ***</div>
                                                    <div>*** ยอดโบนัสที่นำไปเล่นจะไม่นำไปคิดคอมมิชชั่น ***</div> 
                                            </div> 

                                            <div class="tab-pane fade active table-responsive" id="nav-deposit" role="tabpanel"  :selected="true">
                                               <H5>แนะนำเพื่อนสุงสุด เดือน<?=thai_date_fullmonth(strtotime($pre_month),true)?></H5>
                                               <table class="table withdraw_history_table" id="deposit_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr>
                                                            <th scope="col">ลำดับที่</th>
                                                            <th scope="col">รหัสสมาชิก</th> 
                                                            <th scope="col">ยอดแนะนำฯ ที่ทำได้</th>
                                                            <th scope="col">รางวัล</th> 
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="item in history_money.datalist">
                                                            <th scope="col">{{item.count_no}}</th>
                                                            <th scope="col">{{item.member_no}}</th>
                                                            <th scope="col">{{item.amount}} </th>
                                                            <th scope="col">{{item.rewardAmount}}</th> 
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                ***ทีมงานจะติดต่อลูกค้าและส่งของรางวัลให้ผู้โชคดีไม่เกินวันที่ 5 ของเดือน***
                                            </div>
                                            <div class="tab-pane fade table-responsive" id="nav-withdraw" role="tabpanel" aria-labelledby="nav-withdraw-tab">
                                                <H5>ค่าคอมฯ สุงสุด เดือน<?=thai_date_fullmonth(strtotime($pre_month),true)?></H5>
                                                <table class="table withdraw_history_table" id="withdraw_history_table" v-if="!boxloading">
                                                    <tbody class="table_h">
                                                        <tr>
                                                        <th scope="col">ลำดับที่</th>
                                                            <th scope="col">รหัสสมาชิก</th> 
                                                            <th scope="col">ยอดคอมมิชชั่น ที่ทำได้</th>
                                                            <th scope="col">รางวัล</th> 
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="(item,index) in history_money.datalist">
                                                            <th scope="col">{{item.count_no}}</th>
                                                            <th scope="col">{{item.member_no}}</th>
                                                            <th scope="col">{{item.amount}} </th>
                                                            <th scope="col">{{item.rewardAmount}}</th> 
                                                        </tr>

                                                    </tbody>
                                                </table>
                                                ***ทีมงานจะติดต่อลูกค้าและส่งของรางวัลให้ผู้โชคดีไม่เกินวันที่ 5 ของเดือน***
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
                         
                      <div class="d-grid gap-2 nav mt-4 mb-2 pe-1 ps-1" role="tablist">
                            <button v-on:click="ReWardtabs('rewarddaily')" class="nav-link active btn btn-outline-warning" id="nav-reward_daily-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-reward_daily-mobile" type="button" role="tab" aria-controls="nav-reward_daily-mobile" aria-selected="true">
                                <i class='fal fa-trophy'  style='font-size:1.8em;color:gold'></i><br />รางวัลประจำวัน <span class="menubotfontsize2" style='font-size:.7em;'>(ยอดการเล่น 5 อันดับสูงสุด)</span>
                            </button>
                            <button v-on:click="ReWardtabs('aff')" class="nav-link btn btn-outline-warning" id="nav-rewardaff-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-rewardaff-mobile" type="button" role="tab" aria-controls="nav-rewardaff-mobile" aria-selected="false">
                                <i class='fas fa-trophy-alt'  style='font-size:1.8em;color:gold'></i><br />รางวัลประจำเดือน <span class="menubotfontsize2" style='font-size:.7em;'>(ค่าคอมแนะนำเพื่อนสูงสุด)</span>
                            </button>
                            <button v-on:click="ReWardtabs('com')" class="nav-link btn btn-outline-warning" id="nav-rewardcom-mobile-tab" data-bs-toggle="tab" data-bs-target="#nav-rewardcom-mobile" type="button" role="tab" aria-controls="nav-rewardcom-mobile" aria-selected="false">
                            <i class='fas fa-trophy'  style='font-size:1.8em;color:gold'></i><br />รางวัลประจำเดือน <span class="menubotfontsize2" style='font-size:.7em;'>(ยอดเงินคินสูงสุด)</span>
                            </button>
                        </div>

                        <div class="w100 tab-content name"> 
                         <div class="tab-pane fade show active" id="nav-reward_daily-mobile" role="tabpanel" aria-labelledby="nav-reward_daily-mobile-tab">
                              <H5> รางวัลประจำวันที่ <?=thai_date_fullmonth(strtotime($previous_day))?></H5>
                                <table class="table withdraw_history_table" id="reward_daily_m_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr>
                                            <th scope="col">ลำดับที่</th>
                                            <th scope="col">รหัสสมาชิก</th>
                                            <th scope="col">ยอดเล่น</th>
                                            <th scope="col">รางวัล</th> 
                                            <th scope="col">สถานะ</th> 
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="item in history_money.datalist">
                                            <th scope="col">{{item.count_no}}</th>
                                            <th scope="col">{{item.member_no}}</th>
                                            <th scope="col">{{item.amount}} </th>
                                            <th scope="col">{{item.rewardAmount}}</th> 
                                            <th scope="col">
                                              <button type="button" class="btn btn-primary" v-if="item.member_allow!=0&&item.status==1" v-on:click="get_bonus(51)">กดรับโบนัส</button>
                                              <span  v-if="item.status==3">รับโบนัสแล้ว</span>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                    <div>*** ยอดการเล่น 5 อันดับสูงสุด *** </div>
                                    <div>*** อันดับ 1 รับ 1,000 ***</div>
                                    <div>*** อันดับ 2 รับ 500 ***</div>
                                    <div>*** อันดับ 3 รับ 300 ***</div>
                                    <div>*** อันดับ 4,5 รับ 100 ***</div>
                                    <div> *** ทำเทิร์น 5 เท่า ถอนไม่อั้น ***</div>
                                    <div>*** ยอดโบนัสที่นำไปเล่นจะไม่นำไปคิดคอมมิชชั่น ***</div> 
                            </div>

                            <div class="tab-pane fade" id="nav-rewardaff-mobile" role="tabpanel" aria-labelledby="nav-rewardaff-mobile-tab">
                              <H5>แนะนำเพื่อน เดือน<?=thai_date_fullmonth(strtotime($pre_month),true)?></H5> 
                                <table class="table withdraw_history_table" id="deposit_m_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr>
                                            <th scope="col">ลำดับที่</th>
                                            <th scope="col">รหัสสมาชิก</th>
                                            <th scope="col">ยอดเล่น</th>
                                            <th scope="col">รางวัล</th> 
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="item in history_money.datalist">
                                            <th scope="col">{{item.count_no}}</th>
                                            <th scope="col">{{item.member_no}}</th>
                                            <th scope="col">{{item.amount}} </th>
                                            <th scope="col">{{item.rewardAmount}}</th> 
                                        </tr>
                                    </tbody>
                                </table>
                                ***ทีมงานจะติดต่อลูกค้าและส่งของรางวัลให้ผู้โชคดีไม่เกินวันที่ 5 ของเดือน***
                            </div>
                            <div class="tab-pane fade" id="nav-rewardcom-mobile" role="tabpanel" aria-labelledby="nav-rewardcom-mobile-tab">
                               <H5>ค่าคอมฯ สุงสุด เดือน<?=thai_date_fullmonth(strtotime($pre_month),true)?></H5>
                               <table class="table withdraw_history_table" id="withdraw_m_history_table" v-if="!boxloading">
                                    <tbody class="table_h">
                                        <tr>
                                            <th scope="col">ลำดับที่</th>
                                            <th scope="col">รหัสสมาชิก</th>
                                            <th scope="col">ยอดเล่น</th>
                                            <th scope="col">รางวัล</th> 
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="(item,index) in history_money.datalist">
                                            <th scope="col">{{item.count_no}}</th>
                                            <th scope="col">{{item.member_no}}</th>
                                            <th scope="col">{{item.amount}} </th>
                                            <th scope="col">{{item.rewardAmount}}</th> 
                                        </tr>

                                    </tbody>
                                </table>
                                ***ทีมงานจะติดต่อลูกค้าและส่งของรางวัลให้ผู้โชคดีไม่เกินวันที่ 5 ของเดือน***
                            </div>
                            <br/><br/><br/><br/>
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
                App.ReWardtabs('rewarddaily');
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