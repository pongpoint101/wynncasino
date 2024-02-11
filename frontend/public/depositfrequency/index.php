<?php

require '../bootstart.php'; 
require_once ROOT.'/core/security.php';
require ROOT.'/public/theme_v1/functionall.php';
require ROOT.'/core/promotions/pro_data.php';
$WebSites=GetWebSites();
if(@$_SESSION['member_no'] == NULL){
    header( "location: ".$tg_fn->tugame_url('login'));
    exit(0);
} 
$rewardpro =GetDatapro('deposit_freq');
$sql  = " CALL spc_deposit_frequency($member_no,500);";    
$datakey='depositfreq:'.$member_no; 
$res=GetDataSqlWhereAll($datakey,$sql,[],2*60);   

$countfreq=0;$checkfreq=0; 
for ($i=0; $i < 15; $i++) {  
    if(isset($res[$i])){
       $vv=$res[$i]; 
        if ($vv['daydiff']!=1) {
            $checkfreq=1;
        }
        if ($checkfreq==0) {
        $countfreq++;
       } 
     }
  }  
 foreach ($rewardpro as $k => $v) { 
          $vv=$rewardpro[$k];
           if ($vv['status']!=0) { 
            if ($countfreq>=$k) { 
               $vv['status']=2;  
             }
            $rewardpro[$k]=$vv;
        }  
  } 
?>

<!doctype html>

<html lang="th" style="scroll-behavior: smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ฝากประจำ - <?=$WebSites['title']?></title>
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
             .bg-color .card-body{padding: 0!important;} 
             .boxsub77body{padding-bottom: 0px;}
            }
            #nav-commission-mobile *,#nav-return-mobile *{
                color: #fff;
            }
            .bg-color{background-color: #fff;}
            .pro_btn_red{
                background: linear-gradient(0deg,#c50000 80%,#8c0000);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#259690",endColorstr="#259690",GradientType=0);
                background-attachment: fixed;
                background-position: top;
                background-size: 100% 100%;
            }
            .pro_btn_yellow{
                background: rgb(255,205,0);
                background: linear-gradient(180deg, rgba(255,205,0,1) 0%, rgba(253,254,3,1) 100%);
            }
            .pro_btn_green{
                background: rgb(25 239 1);
                background: linear-gradient(180deg, rgb(38 245 23) 0%, rgb(45 206 4) 100%);
                color: #FFF;
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
                                <!-- <span class="boxsub77headt1"><h3><i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i> ฝากประจำ <i class='fas fa-hand-holding-usd' style='font-size:1.8em;color:black;'></i></h3></span>  -->
                                <div class="row justify-content-center">
                                    <div class="col-12 text-center">
                                        <img src="../assets/images/promo/pro_frequency02.jpg" alt="" class="img-fluid img-banner-theme mb-3" />
                                    </div>
                                </div> 
                            </div> 
                            <div class="boxsub77body mb-4">
                                <div class="w90">
                                    <div class="row"> 
                                       <?php include 'data.php';?> 
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
                        <div class="boxsub77body mb-0">
                            <div class="w90">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">
                                            <div class="w1002">
                                                <!-- <span class="boxfont1" style="font-size: 1.8em; color: black;">ฝากประจำ</span> -->
                                                <div class="row justify-content-center">
                                                    <div class="col-12 text-center">
                                                        <img src="../assets/images/promo/pro_frequency02.jpg" alt="" class="img-fluid img-banner-theme mb-3" />
                                                    </div>
                                                </div> 
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="w100">
                            <div class="row"> 
                                     <?php include 'data.php';?> 
                            </div> 
                            <br/><br/><br/><br/>
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
                // App.SetPageSection('dwCommission');
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