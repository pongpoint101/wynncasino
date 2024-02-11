<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
$WebSites = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
    header("location: " . GetFullDomain() . '/login');
    exit(0);
}

$sitetitle = 'ฝากเงิน';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/member_page3.css?v=4">';
include_once ROOT_APP . '/componentx/header.php';
?>
<section class="info-section" id="app" v-cloak>
    <input type="hidden" id="wd_amount_text" name="wd_amount_text" v-model="data_global.withdraw_balance">
    <input type="hidden" id="min_wd" :value="data_global.min_wd">
    <input type="hidden" id="wd_amount" v-model="data_global.withdraw_balance">

    <div class="member-info mx-auto">
        <div class="accout-info">
            <p><span>รหัสสมาชิกของคุณ:</span> <span class="account-number">{{data_global.username}}</span></p>
            <p><span>สวัสดีคุณ</span> <span class="account-name">{{data_global.fname}} {{data_global.lname}}</span></p>
        </div>

        <div class="transaction-group mx-auto">
            <div class="credit-info">
                <h1>ยอดเครดิตของคุณ</h1>
                <p><span class="credit-amount">{{data_global.main_wallet}}</span> <span>฿</span></p>
            </div>
            <div class="transaction-btns">
                <div><a href="" class="deposite-btn">ฝากเงิน</a>
                    <a href="javascript:void(0)" class="withdraw-btn" v-on:click="wdAction()" :disabled="data_wd.issubmit">ถอนเงิน</a>
                </div>
                <p><span>ข้อมูลบัญชีของคุณ:</span> <span class="bank-acount-number">{{data_global.bank_accountnumber}}</span> <span class="bank-name"> {{data_global.bank_name}}</span></p>
            </div>
        </div>
        <!-- =================================================================== -->
        <?php
        // $query = "SELECT COUNT(1) AS total FROM log_deposit WHERE member_no =? AND status=1 AND channel IN(1,2,3,5)";
        // $row = $conn->query($query, [$_SESSION['member_no']])->row_array();
        // if (@$row['total'] <= $WebSites['depositround']) {
        $showvizplay = true;
        if ((date("H:i") >= "22:50") || (date("H:i") <= "02:00")) {
            $showvizplay = false;
        }
        // $showvizplay = false;
        // file_put_contents('vp-test.log', date("H:i") . '(' . $showvizplay . ')' . PHP_EOL, FILE_APPEND);
        ?>
        <!-- <div class="bg-white w-50 px-5 py-3" style="display: none;"> -->
        <?php if ($showvizplay) { ?>
            <div class="bg-white w-50 px-5 py-3">
                <div class="mb-3">
                    <label for="amount" class="form-label">กรอกจำนวนเงิน(บาท)</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>

                <button onclick="requestQR()" class="btn btn-primary d-block mx-auto">รับ QR Code สำหรับฝากเงิน</button>
            </div>
        <?php
        }
        ?>
        <!-- ===================================================================== -->
        <?php
        // $showvizplay = true;
        // if ((date("H:i") >= "22:50" && date("H:i") <= "02:00")) {
        //     $showvizplay = false;
        // }
        // $showvizplay = false;
        // if (@$row['total'] > $WebSites['depositround']) { 
        ?>
        <?php if ($showvizplay) { ?>
            <div class="bank-icons" style="display: none;">
                <a href="javascript:void(0)" class="active" v-for="item in account_transfer.bk_datalist" v-on:click="switchelement=item.bank_code" :class="item.ClassActiveFirst">
                    <img v-bind:src="item.LogoBank" v-bind:alt="item.bank_short">
                </a>
            </div>
        <?php }
        // if ($showvizplay) { 
        ?>
        <div style="width: 90%;height: auto;max-width: 964px; min-height: 0px; background-color: black;border-radius: 20px;display: flex;align-items: center;padding:10px">
            <span style="font-size:-0.4em;font-weight: bold;color: yellow;text-decoration: underline;">ทุกครั้งที่ฝากเงินต้องสร้าง QR Code ใหม่ทุกครั้ง ห้ามใช้ QR Code เก่านะคะ เพื่อความรวดเร็วในการฝากค่ะ</span><br>
        </div>
        <div style="width: 90%;height: auto;max-width: 964px; min-height: 0px; background-color: black;border-radius: 20px;display: flex;align-items: center;padding:10px">
            <span style="font-size:-0.4em;font-weight: bold;color: red;text-decoration: underline;">ถ้าเป็นรายการฝากระหว่างช่วง 22.50 - 02.00 ทางเราไม่สามารถตรวจสอบให้ได้ ต้องรอข้อมูลจากทางธนาคารก่อน ดังนั้นถ้าเป็นยอดรายการระหว่างเวลานี้ ท่านสมาชิกต้องรอส่งสลิปให้แอดมินตรวจสอบช่วงเวลา 02.15 เป็นต้นไป</span><br>
        </div>
        <div style="width: 90%;height: auto;max-width: 964px; min-height: 0px; background-color: black;border-radius: 20px;display: flex;align-items: center;padding:10px">
            <span style="font-size:-0.4em;font-weight: bold;color: red;text-decoration: underline;">(ทางบริษัทได้พัฒนาระบบนี้ขึ้นมาเพื่อป้องกันบัญชีข้อมูลของท่านรั่วไหลไปถึงมิจฉาชีพ)</span><br>
        </div>
        <?php //} 
        ?>

        <template v-for="(item, index) in account_transfer.bk_datalist">
            <template v-if="switchelement==item.bank_code ||(index==0&&switchelement==1)">
                <div class="account-info" style="display: none;">
                    <div>
                        <p>ชื่อบัญชี</p>
                        <p>{{item.account_name}}</p>
                    </div>
                    <div>
                        <p>เลขบัญชี</p>
                        <span :id="'bank_transfer_'+item.bank_short+'_copy'" v-show="false">{{item.bank_account}}</span>
                        <p>{{item.bank_account}}({{item.bank_short}})</p>
                        <p style="color: #ffda1a; font-size: 16px; margin-top: 0; margin-bottom: 0px;text-align: center;padding-right: 8px;word-break: break-word;line-height: 1.4;" v-html="item.TextCondition_01"></p>
                        <p style="color: crimson; font-size: 16px; margin-top: 0; margin-bottom: 0px;padding-right: 8px;word-break: break-word;line-height: 1.4;" v-html="item.TextCondition_02"></p>
                        <p style="color: crimson; font-size: 16px; margin-top: 0; margin-bottom: 0px;" v-if="item.bank_code == '014'">ช่วงเวลา 1.00 - 1.30 อาจทำให้การฝากเงินล่าช้า เนื่องจากเป็นช่วงระยะเวลาที่ธนาคารปรับปรุงระบบประจำวัน</p>

                    </div>
                </div>
                <button class="copy-btn butboxkasikorn" :class="item.ClassBottonCopy" v-on:click="coppy_alert_top('คัดลอกเลขบัญชี','bank_transfer_'+item.bank_short+'_copy')" style="display: none;">
                    <img src="../assets/images/member_page3/Icon-copy.svg" alt="copy account number">
                    <p>คัดลอกเลขบัญชี</p>
                </button>
            </template>
        </template>

        <?php
        // } 
        ?>
        <a href="<?= GetFullDomain() ?>/history" class="history-link">ดูประวัติการฝากเงิน &gt;</a>
    </div>
</section>
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>
<style>
    body.swal2-shown>[aria-hidden="true"] {
        transition: 0.1s filter;
        filter: blur(10px);
    }
</style>
<script type="text/javascript">
    $(function() {
        App.SetPageSection('depositSection');

    });

    function requestQR() {
        var v_amount = document.getElementById("amount").value;
        if (v_amount <= '0') {
            alert('กรุณากรอกจำนวนมากกว่า 0');
            return;
        }
        $.ajax({
            type: 'POST',
            url: './VizPay/api/genQRCode.php',
            dataType: "json",
            data: {
                'amount': v_amount,
            }
        }).done(function(data) {
            if (data.code == 0) {
                let timerInterval

                Swal.fire({
                    title: '<p style="color: blue;">' + data.bank_name + ' : ' + data.bank_account + '</p>',
                    text: data.cust_name,
                    imageUrl: data.qr_url,
                    html: '<p class="mt-3" style="color: darkcyan;">ต้องโอนเงินจากบัญชีด้านบนนี้เท่านั้น</p>' + '<p style="color: red;">*** QR Code จะหมดอายุภายใน <strong></strong> วินาที ***</p>' +
                        '<p style="color: red;">*** กรุณาทำรายการในเวลาที่กำหนด ***</p>',
                    timer: 180000,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        const content = Swal.getHtmlContainer()
                        const $ = content.querySelector.bind(content)
                        timerInterval = setInterval(() => {
                            Swal.getHtmlContainer().querySelector('strong')
                                .textContent = (Swal.getTimerLeft() / 1000)
                                .toFixed(0)
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                })

            } else {
                Swal.fire({
                    title: "เกิดข้อผิดพลาด (" + data.code + ")",
                    icon: 'error',
                    text: "กรุณาลองใหม่อีกครั้ง"
                });

            }
        });
    }
</script>