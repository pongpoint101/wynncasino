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
        <title>Deposit - <?=$WebSites['title']?></title>
        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?> 
    </head>
    <body>

        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="w100" id="app" v-cloak>

            <div class="mainheadbg">
                <div class="container p-0">
                    <div class="boxsub77">
                        <?php include(ROOT.'/public/componentx/wallet_pc_v2.php') ?>
                        <div class="w90 pg-nav-tabs-style padding-top-top-mobile-menu">
                            <div class="row nav nav-tabs mt-4" role="tablist" v-if="!boxloading" style="justify-content: center;">
                                <div data-bs-toggle="tab" role="tab" aria-selected="true" v-for="item in account_transfer.bk_datalist" :id="'nav-'+item.bank_short+'-tab'" :aria-controls="'nav-'+item.bank_short" :data-bs-target="'#nav-'+item.bank_short" :class="'nav-link col-6 col-md-4 '+item.ClassActiveFirst">
                                    <div class="butboxbanner">
                                        <img v-bind:src="item.LogoBank" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w100 tab-content">
                            <div role="tabpanel"  v-for="item in account_transfer.bk_datalist" :id="'nav-'+item.bank_short" :aria-labelledby="'nav-'+item.bank_short+'-tab'" :class="'tab-pane fade show ss985 w90 '+item.ClassActiveFirst">
                                <div class="col-12 text-center">
                                    <p style="color: red;font-size: 16px;margin-top: 10px;margin-bottom: 0;">{{item.TextCondition_01}}<br/>{{item.TextCondition_02}}</p>
                                </div>
                                <div class="boxsub77body2 ss784">
                                    <div class="row m-0">
                                        <div class="col-12 col-md-6 ss785 boderright4">
                                            <div class="w1002 boxtextac"><span class="fs-5">ชื่อบัญชี</span></div>
                                            <div class="w1002 boxtextac"><span class="fs-4">{{item.account_name}}</span></div>
                                        </div>
                                        <div class="col-12 col-md-6 ss785">
                                            <div class="w1002 boxtextac"><span class="fs-5">เลขบัญชี</span></div>
                                            <div class="w1002 boxtextac"><span class="fs-4" :id="'bank_transfer_'+item.bank_short+'_copy'">{{item.bank_account}}</span></div>
                                        </div>
                                        <div class="col-12 text-center hide-Mobile-text">
                                            <p style="color: red;font-size: 16px;margin-top: 10px;margin-bottom: 0;">{{item.TextCondition_01}}<br/>{{item.TextCondition_02}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div :class="'ss753 mb-4 '+item.ClassBottonCopy">
                                    <a href="javascript:void(0)" :onclick="'copyDepositURL(\'bank_transfer_'+item.bank_short+'_copy\')'">
                                        <img src="<?=$tg_fn->tugame_url('theme_v1/img/icon2.png')?>" alt=""> คัดลอกเลขบัญชี
                                    </a>
                                    <br/><br/><br/>
                                </div>
                                <div class="butboxkasikorn2">
                                    <a href="<?=$tg_fn->tugame_url('history')?>">ดูประวัติการฝากเงิน</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bgpc">
                <div class="mainbodyfootbg">
                    <div class="container">
                        <div class="row g-3">
                            <?php
                            require_once ROOT.'/public/theme_v1/footer.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

          </div> 
       </div>
        <?php
        require_once ROOT.'/public/theme_v1/js_script.php';
        ?>
        <script type="text/javascript">
            $(function(){
               App.SetPageSection('depositSection');
               Swal.fire({ position: 'center', customClass: 'popupheight'
                ,html: '<img  src="<?=$tg_fn->tugame_url('assets/images/popup/wallet-edit.jpg')?>">'
                ,timer:20000
              });
            });
            function copyDepositURL(tgIDs) {
                var aux = document.createElement("input");
                aux.setAttribute("value", document.getElementById(tgIDs).innerHTML);
                document.body.appendChild(aux);
                aux.select();
                document.execCommand("copy");
                document.body.removeChild(aux);
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