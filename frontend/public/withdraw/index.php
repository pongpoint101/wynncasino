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
        <title>WithDraw - <?=$WebSites['title']?></title>

        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?> 
        <style type="text/css">
            .boxsub77body{
                background: none;
            }
            @media screen and (max-width: 660px) {
                .boxsub77{
                    margin:0 auto 0px auto;
                    border-bottom-right-radius: 40px;
                    border-bottom-left-radius: 40px;
                }
            }
        </style>
    </head>
    <body>

        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="mainheadbg">
                <div class="container p-0">
                    <div class="boxsub77 exboxsub77">
                        <div class="boxsub77head">
                            <span class="boxsub77headt1"><h1><i class="fas fa-hand-holding-usd"></i> ถอนเงิน <i class="fas fa-hand-holding-usd"></i></h1></span>  
                        </div> 
                        <div class="boxsub77body mb-0">
                            <div class="w90">
                                <div class="row"> 
                                    <div class="col-12">
                                      <div class="table-responsive">
                                       <table class="table table-borderless  withdraw_history_table">
                                        <tbody class="table_h">
                                            <tr class="text-center"> 
                                              <th scope="col"><h3>ยอดเงินคงเหลือ</h3></th> 
                                            </tr>
                                            <tr class="text-center"> 
                                                <th scope="col"><h3 class="boxfont2">{{data_global.main_wallet}}</h3></th> 
                                              </tr>
                                          </tbody> 
                                       </table>
                                     </div>

                                    </div>

                                </div>

                            </div>
                        </div>  
                        <div class="boxsub77body mb-4">
                            <div class="w90">
                                <div class="row"> 
                                    <div class="col-12">
                                        <form action="#" method="post" data-parsley-validate="">
                                            <input type="hidden" name="task" value="withdraw">
                                            <input type="hidden" id="wd-member-fullname" :value="fullName">
                                            <input type="hidden" id="wd-to" name="wd-to" :value="fullBank">
                                            <input type="hidden" id="wd_amount_text" name="wd_amount_text" v-model="data_global.withdraw_balance">
                                            <input type="hidden" id="min_wd" :value="data_global.min_wd">
                                            <input type="hidden" id="wd_amount" v-model="data_global.withdraw_balance">
                                            <div class="table-responsive">
                                                <table class="table withdraw_history_table"> 
                                                    <tbody>
                                                        <tr> 
                                                            <th scope="col">ชื่อบัญชี</th> 
                                                            <th scope="col">{{data_global.fname}} {{data_global.lname}}</th>  
                                                        </tr>
                                                        <tr> 
                                                            <th scope="col">โอนเข้าธนาคาร</th> 
                                                            <th scope="col">{{data_global.bank_name}} - {{data_global.bank_accountnumber}}</th>  
                                                        </tr>
                                                        <tr> 
                                                            <th scope="col">จำนวนเงินที่ถอนได้</th> 
                                                            <th scope="col">{{data_global.main_wallet}}</th> 
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                            </div>
                                            <div class="d-grid gap-2 pt-3 pb-3"> 
                                                <button type="button" id="wdAction" class="btn btn-round btn-warning btn-block" value="แจ้งถอนเงิน" v-on:click="wdAction()" :disabled="data_wd.issubmit"><i class="fas fa-hand-holding-usd"></i> แจ้งถอนเงิน</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

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
                $('#wdActionMobile').click(function(){
                    $('#wdAction').click();
                });
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