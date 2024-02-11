<?php
require '../bootstart.php'; 
if(!isset($_GET['openExternalBrowser'])){
    $mob_redirect= new Mobile_Detect;
    if ($mob_redirect->isMobile()) {
        $redrurl=@explode("?", $_SERVER['REQUEST_URI'])[1];
        $redrurl=(!$redrurl?GetFullDomain().$_SERVER['REQUEST_URI'].'?openExternalBrowser=1':GetFullDomain().$_SERVER['REQUEST_URI'].'&openExternalBrowser=1');
        header( "location: $redrurl" );
        exit(0);
    } 
 }  
if (isset($_SESSION['member_no'])) {
    header('location:'.GetFullDomain().'/index.php');
    exit();
} 

if(@$_SESSION['member_no'] != NULL){
    header( "location: ".GetFullDomain().'/lobby');
    exit(0);
} 
$Website=GetWebSites();
$sitetitle='Register';
$assets_head='<link rel="stylesheet" href="'.GetFullDomain().'/assets/css/register_page.css?v=3">';
include_once ROOT_APP.'/componentx/header.php';
?> 
<style> .short-field,.long-field{margin-bottom: 1em;}</style>
    <section class="main-section" id="app" v-cloak>
        <div class="blue-box mx-auto">
            <h1>สมัครสมาชิก</h1>
            <p>ระบบมีการตรวจสอบบัญชีใช้งาน</p>
            <p>โปรดระบุข้อมูลที่เป็นจริง</p>
            <form action="" method="" onSubmit="return false">
                <span class=" mainstep step1" v-if="step==1">
                    
                <div class="mb-3">
                    <label for="phone" class="form-label text-light">เบอร์โทรศัพท์*</label>
                    <div class="long-field">
                        <div></div> 
                        <input type="tel" name="phone" ref="phone"  placeholder="เบอร์โทรศัพท์*" maxlength="10" required v-model="DataRegister.phone"> 
                    </div>  
                </div> 
                
                </span>
                <span class="mainstep step2" v-if="step==2">
                <div class="mb-3">
                        <label for="choose_bank" class="form-label text-light">เลือก ช่องทางฝาก-ถอน</label> <br> 
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="choose_bank" id="inlineRadio1" @change="select_bank_deposit()" v-model="DataRegister.choose_bank" v-bind:value="1" value="1">
                        <label class="form-check-label text-light" for="inlineRadio1">ธนาคาร</label>
                        </div>
                        <div class="form-check form-check-inline" v-if="DataRegister.truewallet_is_register==2">
                        <input class="form-check-input" type="radio" name="choose_bank" id="inlineRadio2" @change="select_bank_deposit()"  v-model="DataRegister.choose_bank" v-bind:value="2" value="2">
                        <label class="form-check-label text-light" for="inlineRadio2">ทรูมันนี่ วอลเล็ท</label>
                        </div> 
                   </div>  
                <div class="short-field" v-if="DataRegister.choose_bank==2&&DataRegister.truewallet_is_register==2"> 
                    <input type="tel"  v-model="DataRegister.truewallet" name="truewallet" ref="truewallet"  placeholder="เบอร์ทรูมันนี่ วอลเล็ท*" maxlength="10" v-on:keyup="selecttruewallet">
                    <input type="text" v-model="DataRegister.truewallet_account"  name="truewallet_account" ref="truewallet_account" placeholder="เบอร์ทรูมันนี่ วอลเล็ท ไอดี" >
                  </div>

                <div class="short-field" v-if="DataRegister.choose_bank==2&&DataRegister.truewallet_is_register==2"> 
                    <input type="text" readonly  v-model="DataRegister.first_name" name="first_name" placeholder="ชื่อจริงในทรูมันนี่ วอลเล็ท*">
                    <input type="text" readonly  placeholder="นามสกุลจริงในทรูมันนี่ วอลเล็ท*"  name="last_name" v-model="DataRegister.last_name">
                </div>

                <div class="short-field"  v-if="DataRegister.choose_bank==1">
                    <input type="tel" placeholder="เลขบัญชีธนาคาร*" required v-model="DataRegister.bank_account" name="bank_account" ref="bank_account">
                    <select name="bank-name" required="" name="bank_code" ref="bank_code" aria-label="Default select example" @blur="selectbank"   v-model="DataRegister.bank_code">
                        <option selected="selected" value="">เลือกธนาคาร*</option>
                        <option value="014">ไทยพานิชย์</option>
                        <option value="002">กรุงเทพ</option>
                        <option value="004">กสิกรไทย</option>
                        <option value="006">กรุงไทย</option>
                        <option value="034">ธกส.</option>
                        <option value="011">ทหารไทยธนชาต</option>
                        <option value="070">ไอซีบีซี</option>
                        <option value="071">ไทยเครดิต</option>
                        <option value="017">ซิตี้แบงก์</option>
                        <option value="020">สแตนดาร์ดชาร์เตอร์ด</option>
                        <option value="022">ซีไอเอ็มบี</option>
                        <option value="024">ยูโอบี</option>
                        <option value="025">กรุงศรีฯ</option>
                        <option value="030">ออมสิน</option>
                        <option value="031">เอชเอสบีซี</option>
                        <option value="033">ธอส.</option>
                        <option value="073">แลนด์แอนด์เฮ้าส</option>
                        <option value="067">ทิสโก้</option>
                        <option value="069">เกียรตินาคิน</option>
                        <option value="066">อิสลาม</option>
                    </select> 
                </div>
                <div class="short-field" v-if="DataRegister.choose_bank==1"> 
                    <input type="text" readonly  v-model="DataRegister.first_name" name="first_name" placeholder="ชื่อจริงในสมุดบัญชีธนาคาร*">
                    <input type="text" readonly  placeholder="นามสกุล*"  name="last_name" v-model="DataRegister.last_name">
                </div>

                <!-- <button  type="submit" @click="check_register(3);">ถัดไป</button>    -->
                </span>
                <span class="mainstep step3" v-if="step==3">
                <div class="long-field ">
                    <div></div> 
                    <input type="text" :disabled="!DataRegister.bankverify" placeholder="ไลน์ไอดี" name="line_id" ref="line_id"  v-model="DataRegister.line_id">
                </div>
                <div class="long-field">
                    <div></div> 
                    <input type="password" :disabled="!DataRegister.bankverify" placeholder="รหัสผ่าน*" minlength="4" required="" name="password" ref="password"  v-model="DataRegister.password">
                </div>
                <div class="long-field">
                    <div></div> 
                    <input type="password" :disabled="!DataRegister.bankverify" placeholder="ยืนยันรหัสผ่าน*" minlength="4" required="" name="password_confirmation" ref="password_confirmation"  v-model="DataRegister.password_confirmation">
                </div>
                <div class="long-field">
                    <div></div>
                    <select style="width: 100%;" :disabled="!DataRegister.bankverify" name="source" v-model="DataRegister.source">
                            <option selected="selected" value="">ช่องทางที่รู้จักเรา</option>
                            <option value="facebookv">Facebook</option>
                            <option value="tiktok">Tiktok </option>
                            <option value="google">Google </option> 
                            <option value="ads">ป้ายโฆษณา </option>
                            <option value="friend">เพื่อนแนะนำ </option>
                            <option value="y2kro">y2k-ro </option>
                            <option value="sms">ข้อความ SMS </option>
                            <option value="lineacc">ข้อความ Line </option>
                            <option value="lineacc">YouTube </option>
                            <option value="youyubeacc">อื่นๆ </option> 
                    </select>
                </div> 
                </span>
                <button type="submit" @click="register();"  v-if="step==3">สมัครสมาชิก</button>  
                <div class="col d-flex justify-content-center align-items-center" >
                    <button type="button" class="btn btn-outline-warning me-3" v-if="step!=1" @click="check_register('-')"><<ขั้นตอนก่อนหน้า</button>
                    <button type="button" class="btn btn-outline-info"  @click="check_register('+')" v-if="step!=3">ขั้นตอนถัดไป>></button> 
                </div>
            </form>
        </div>
    </section>
 <?php  
$assets_footer='<script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?>
<script>
   $(function(){
     App.DataRegister.truewallet_is_register='<?=$Website['truewallet_is_register']?>';
   });
</script>

