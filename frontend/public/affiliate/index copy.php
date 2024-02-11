<?php
require '../bootstart.php';  
$WebSites=GetWebSites();  
$com_l1=(isset($WebSites['aff_comm_level_1'])?$WebSites['aff_comm_level_1']*1:0);
?>
<!doctype html>
<html lang="th" style="scroll-behavior: smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>แนะนำเพื่อน - <?=$WebSites['title']?></title>

        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?>
        <style type="text/css">
            .boxsub77body{
                background: none;
            }
            @media screen and (max-width: 660px) {
                .boxsub77white{
                    margin: 85px auto 0px auto;
                    border-bottom-right-radius: 40px;
                    border-bottom-left-radius: 40px;
                }
                .exboxsub77{
                    padding: 0;
                    width: 100% !important;
                }
            }
            .exboxsub77{
                padding: 0; 
              }
            .boxsub77body{
                color: #000;
            }
        </style>
    </head>
    <body>

        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="pcboxx">
                <div class="mainheadbg">
                    <div class="container">
                        <div class="boxsub77white exboxsub77">
                            <div class="boxsub77head">
                                <span class="boxsub77headt1">ระบบแนะนำเพื่อน รับค่าคอม <?=$com_l1?>% ตลอดชีพ</span> 
                            </div> 
                            <div class="boxsub77body mb-4">
                                <div class="w90">
                                    <?php  if (isset($_SESSION['member_no'])) { ?>
                                    <div class="boxaff">
                                        <form ref="affiliateSection">
                                            <div class="row vertical-align">
                                                <div class="col-xs-12 col-sm-2 p-1">Link แนะนำเพื่อน</div>
                                                <div class="col-xs-12 col-sm-8 p-1">
                                                    <div class="form-group m-0">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm input100 inputboder_style" onclick="copyAffURL()" readonly="readonly" id="aff_url" :value="data_aff_com.aff_url" readonly style="font-size: x-large;color: #000;">
                                                            <div class="input-group-append">
                                                                <a href="javascript:void(0)" class="btn btn-danger text-white" onclick="copyAffURL()" style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;height: 100%;">Copy</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-xs-12 col-sm-2 p-1"></div>

                                                <div class="col-xs-12 col-sm-2 p-1">
                                                    <div class="p-1">
                                                        Link แนะนำเพื่อน<span class="d-block d-sm-none">(ลำดับที่ 1)</span>
                                                       <div class="d-none d-sm-block text-center">(ลำดับที่ 1)</div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-8 p-1">
                                                    <input type="text" readonly="readonly" class="form-control-sm input100 inputboder_style" id="num_af1" :value="data_aff_com.count_l1" readonly style="font-size:x-large;">
                                                </div>
                                                <div class="col-xs-12 col-sm-2 p-1"></div>

                                                <div class="col-xs-12 col-sm-2 p-1">
                                                    <div class="p-1">
                                                        Link แนะนำเพื่อน<span class="d-block d-sm-none">(ลำดับที่ 2)</span>
                                                        <div class="d-none d-sm-block text-center">(ลำดับที่ 2)</div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-8 p-1">
                                                    <input type="text" readonly="readonly" class="form-control-sm input100 inputboder_style" id="num_af2" :value="data_aff_com.count_l2" readonly style="font-size:x-large;">
                                                </div>
                                                <div class="col-xs-12 col-sm-2 p-1"></div>
                                                
                                                <div class="col-xs-12 col-sm-2 p-1">จำนวนเครดิตแนะนำ</div>
                                                <div class="col-xs-12 col-sm-8 p-1">
                                                    <div class="form-group m-0">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm input100 inputboder_style" readonly="readonly"  id="num_credit_af" v-model="data_aff_com.total_comm" readonly style="font-size: x-large;color: #000;">
                                                            <div class="input-group-append">
                                                                <a href="javascript:void(0)" class="btn btn-warning" style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;height: 100%;padding-top: 10px;padding-bottom: 10px;" v-on:click="claimAFF()">โอนเข้ากระเป๋าหลัก</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-5 col-md-4 p-1"></div>
                                                <div class="col-xs-12 col-sm-2 p-1"></div>
                                                <div class="col-xs-12 col-sm-10 p-1"> ** ต้องมียอดแนะนำเพื่อนสะสม {{data_aff_com.min_aff_claim}} บ. ขึ้นไป กดรับได้ทุกวัน **</div>   
                                            </div>
                                        </form>
                                    </div> 
                                     <?php }?>
                                </div>
                              <div class="boxsub1">
                                  <div class="alert" role="alert">
                                    สร้างรายได้ให้กับตัวท่านเองหลักแสนบาทต่อเดือน เพียงแนะนำ <?=strtoupper(get_domain())?>  ให้เพื่อนๆ รู้จัก หรือแชร์ลงสื่อ Social ต่างๆ 
                                    เมื่อมีคนกดสมัครเข้ามา คุณจะได้รับส่วนแบ่งจากยอดเดิมพันของสมาชิกที่สมัครผ่านลิงก์ของคุณ
                                    <div style=" color: red;">ตัวอย่างเช่น </div>
                                    <ul>
                                        <li style=" color: #ff3c4e;">เพื่อน 1 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?=(10000*$com_l1)/100?> บาท</li>
                                        <li style=" color: #ff3c4e;">เพื่อน 10 คน มียอดเล่นคนละ 10,000 บาท ท่านจะได้ <?=(100000*$com_l1)/100?> บาท</li>
                                        <li style=" color: #ff3c4e;">เพื่อน 100 คน มียอดเล่นคนละ 10,000 บาท ท่านจะได้ <?=number_format((1000000*$com_l1/100))?> บาท</li>
                                     </ul>
                                     สามารถทำรายได้มากว่า 100,000 บาทต่อเดือนได้ง่ายๆเลยที่เดียว 
                                    แจกจริง จ่ายเร็ว ตรวจสอบได้ทุกขั้นตอน ยิ่งแชร์มาก ยิ่งได้มาก ก๊อปลิงก์แล้วแชร์เลย !
                                  </div>
                              </div> 
                            </div>  
                            
                        </div>
                    </div>
                </div>

                <br/><br/><br/><br/><br/>

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
                <?php
                     if (isset($_SESSION['member_no'])) {
                         ?>
                           App.SetPageSection('affiliateSection');
                         <?php
                     }
                    ?> 
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