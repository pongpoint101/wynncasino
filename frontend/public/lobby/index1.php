<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

$WebSites = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
    header("location: " . GetFullDomain() . '/login');
    exit(0);
}

$sitetitle = 'ข้อมูลส่วนตัว';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/member_page1.css">';
$imageHost = 'https://services.12pay.org';
include_once ROOT_APP . '/componentx/header.php';
$db = new DB();
?>

<section class="info-section" id="app" v-cloak>
    <input type="hidden" id="wd_amount_text" name="wd_amount_text" v-model="data_global.withdraw_balance">
    <input type="hidden" id="min_wd" :value="data_global.min_wd">
    <input type="hidden" id="wd_amount" v-model="data_global.withdraw_balance">
    <div class="member-info mx-auto">
        <div class="accout-info">
            <p><span>รหัสสมาชิกของคุณ : </span> <span class="account-number">{{data_global.username}}</span> </p>
            <p> <span>สวัสดีคุณ</span> <span class="account-name">{{data_global.fname}} {{data_global.lname}}</span> </p>
        </div>
        <div class="wallet-info">
            <p>ยอดเครดิตของคุณ</p>
            <p><span class="credit-amount">{{data_global.main_wallet}}</span> <span>฿</span></p>
            <p> <span>ข้อมูลบัญชีของคุณ:</span> <span class="bank-acount-number">{{data_global.bank_accountnumber}}</span> <span class="bank-name"> {{data_global.bank_name}}</span></p>
        </div>
        <div class="acount-activities">
            <a href="javascript:void(0)" class="play-button">
                <img src="../assets/images/member_page1/Icon material-play-circle-outline.svg" alt="play">
                <p>เข้าเล่นเกม</p>
            </a>
            <div class="transaction-btns">
                <a href="<?= GetFullDomain() ?>/deposit" class="deposite-btn">ฝากเงิน</a>
                <a href="javascript:void(0)" v-on:click="wdAction()" :disabled="data_wd.issubmit" class="withdraw-btn">ถอนเงิน</a>
            </div>
            <a href="<?= GetFullDomain() ?>/logout.php" class="signout-btn">ออกจากระบบ</a>


        </div>
    </div>
</section>
<section class="game-section">
    <div class="tab mx-auto">
        <button class="tablinks" id="defaultOpen" onclick="openGames(event, 'sport-games')">
            <div>
                <h3>กีฬา</h3>
                <h4>SPORT GAME</h4>
            </div>
            <img src="../assets/images/home/sport-icon.png" alt="sport">
        </button>
        <button class="tablinks" onclick="openGames(event, 'slot-games')">
            <div>
                <h3>สล๊อต</h3>
                <h4>SLOT GAME</h4>
            </div>
            <img src="../assets/images/home/slot-icon.png" alt="slot game">
        </button>
        <button class="tablinks" onclick="openGames(event, 'casino-games')">
            <div>
                <h3>คาสิโน</h3>
                <h4>LIVE CASINO</h4>
            </div>
            <img src="../assets/images/home/casino-icon.png" alt="live casino">
        </button>
        <button class="tablinks" onclick="openGames(event, 'fishing-games')">
            <div>
                <h3>ตกปลา</h3>
                <h4>FISHING</h4>
            </div>
            <img src="../assets/images/home/fishing-icon.png" alt="fishing">
        </button>
    </div>

    <div id="slot-games" class="tabcontent mx-auto">
        <a href="javascript:void(0)" onclick="gotoLobby('auto-pgs')"><img src="<?php echo $imageHost ?>/gr/new/PG.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-pgs" href="/lobby/go?provider=auto-pgs" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-jks')"><img src="<?php echo $imageHost ?>/gr/new/JOKER-SLOT.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-jks" href="/lobby/go?provider=auto-jks" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-jls')"><img src="<?php echo $imageHost ?>/gr/new/JILI-SLOT.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-jls" href="/lobby/go?provider=auto-jls" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-sgs')"><img src="<?php echo $imageHost ?>/gr/new/SPADE-slot.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-sgs" href="/lobby/go?provider=auto-sgs" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-pps')"><img src="<?php echo $imageHost ?>/gr/new/pp-slot.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-pps" href="/lobby/go?provider=auto-pps" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-rc8')"><img src="<?php echo $imageHost ?>/gr/new/RICH88.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-rc8" href="/lobby/go?provider=auto-rc8" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-dgs')"><img src="<?php echo $imageHost ?>/gr/new/dragoon.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-dgs" href="/lobby/go?provider=auto-dgs" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-sps')"><img src="<?php echo $imageHost ?>/gr/new/SP-SLOT.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-sps" href="/lobby/go?provider=auto-sps" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-rts')"><img src="<?php echo $imageHost ?>/gr/new/RED.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-rts" href="/lobby/go?provider=auto-rts" style="display: none;"></a>
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-ygg')"><img src="<?php echo $imageHost ?>/gr/new/ygg.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-jdb')"><img src="<?php echo $imageHost ?>/gr/new/jdb.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-net')"><img src="<?php echo $imageHost ?>/gr/new/netent.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-hbn')"><img src="<?php echo $imageHost ?>/gr/new/habanero.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-lv2')"><img src="<?php echo $imageHost ?>/gr/new/live22.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-rlx')"><img src="<?php echo $imageHost ?>/gr/new/relax.jpg" alt="" style="width: 100%;"></a> -->
        <a href="javascript:void(0)" onclick="gotoLobby('auto-pgc')"><img src="<?php echo $imageHost ?>/gr/new/PushGaming.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-pgc" href="/lobby/go?provider=auto-pgc" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-qss')"><img src="<?php echo $imageHost ?>/gr/new/quickspin.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-qss" href="/lobby/go?provider=auto-qss" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-tks')"><img src="<?php echo $imageHost ?>/gr/new/ThunderKick.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-tks" href="/lobby/go?provider=auto-tks" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-klb')"><img src="<?php echo $imageHost ?>/gr/new/kalamba.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-klb" href="/lobby/go?provider=auto-klb" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-png')"><img src="<?php echo $imageHost ?>/gr/new/playngo.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-png" href="/lobby/go?provider=auto-png" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-ftm')"><img src="<?php echo $imageHost ?>/gr/new/fantasma.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-ftm" href="/lobby/go?provider=auto-ftm" style="display: none;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-nge')"><img src="<?php echo $imageHost ?>/gr/new/netgame.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-nge" href="/lobby/go?provider=auto-nge" style="display: none;"></a>
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-olp')"><img src="<?php echo $imageHost ?>/gr/new/onlyplay.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-wzd')"><img src="<?php echo $imageHost ?>/gr/new/wazdan.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-bgm')"><img src="<?php echo $imageHost ?>/gr/new/bgaming.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-fgs')"><img src="<?php echo $imageHost ?>/gr/new/FUN.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-isb')"><img src="<?php echo $imageHost ?>/gr/new/ISB.jpg" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('ambpg')"><img src="../assets/images/home/brand-icon/AMB-PG.png" alt="" style="width: 100%;"></a> -->
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('kas')"><img src="../assets/images/home/brand-icon/KA.png" alt="" style="width: 100%;"></a> -->
    </div>
    <div id="casino-games" class="tabcontent mx-auto">
        <?php
        $resLC = $db->query("SELECT * FROM lobby_control WHERE product_type=2 AND status=1 AND provider_id<>0 ORDER BY provider_ordering")->fetchAll();
        foreach ($resLC as $key => $value) {
            $imgProvider = $imageHost . $value['img_path'] . $value['img_logo'];
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="<?php echo $value['provider_name'] ?>" style="width: 100%;"></a>
            <a id="<?php echo  $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" target="_blank" style="display: none;"></a>
        <?php
        }
        ?>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-qmk')"><img src="<?php echo $imageHost ?>/gr/new/queenmaker.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-qmk" href="/lobby/go?provider=auto-qmk" style="display: none;"></a>
    </div>
    <div id="fishing-games" class="tabcontent mx-auto">
        <a href="javascript:void(0)" onclick="gotoLobby('auto-jkf')"><img src="<?php echo $imageHost ?>/gr/new/JOKER-FISH.jpg" alt="" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-spf')"><img src="<?php echo $imageHost ?>/gr/new/SP-FISH.jpg" alt="" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-sgf')"><img src="<?php echo $imageHost ?>/gr/new/spade-fish.jpg" alt="" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-dgf')"><img src="<?php echo $imageHost ?>/gr/new/dragoon.jpg" alt="" style="width: 100%;"></a>
        <a id="auto-jkf" href="/lobby/go?provider=auto-jkf" style="display: none;"></a>
        <a id="auto-spf" href="/lobby/go?provider=auto-spf" style="display: none;"></a>
        <a id="auto-sgf" href="/lobby/go?provider=auto-sgf" style="display: none;"></a>
        <a id="auto-dgf" href="/lobby/go?provider=auto-dgf" style="display: none;"></a>
    </div>
    <div id="sport-games" class="tabcontent mx-auto">
        <a href="javascript:void(0)" onclick="gotoLobby('auto-sbo')"><img src="<?php echo $imageHost ?>/gr/new/sbobet.jpg" alt="เข้าเล่น SBOBet" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-ibc')"><img src="<?php echo $imageHost ?>/gr/new/IBC.jpg" alt="เข้าเล่น IBCBet" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-bti')"><img src="<?php echo $imageHost ?>/gr/new/BTI.jpg" alt="เข้าเล่น BTI" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-wss')"><img src="<?php echo $imageHost ?>/gr/new/ws.jpg" alt="เข้าเล่น WS Sports" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-cmd')"><img src="<?php echo $imageHost ?>/gr/new/cmd368.jpg" alt="เข้าเล่น CMD368 Sports" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-ims')"><img src="<?php echo $imageHost ?>/gr/new/im-sports.jpg" alt="เข้าเล่น IM Sports" style="width: 100%;"></a>
        <a href="javascript:void(0)" onclick="gotoLobby('auto-tfg')"><img src="<?php echo $imageHost ?>/gr/new/tf.jpg" alt="เข้าเล่น TF Gaming" style="width: 100%;"></a>
        <a id="auto-sbo" href="/lobby/go?provider=auto-sbo" target="_blank" style="display: none;"></a>
        <a id="auto-ibc" href="/lobby/go?provider=auto-ibc" target="_blank" style="display: none;"></a>
        <a id="auto-bti" href="/lobby/go?provider=auto-bti" target="_blank" style="display: none;"></a>
        <a id="auto-wss" href="/lobby/go?provider=auto-wss" target="_blank" style="display: none;"></a>
        <a id="auto-cmd" href="/lobby/go?provider=auto-cmd" target="_blank" style="display: none;"></a>
        <a id="auto-ims" href="/lobby/go?provider=auto-ims" target="_blank" style="display: none;"></a>
        <a id="auto-tfg" href="/lobby/go?provider=auto-tfg" target="_blank" style="display: none;"></a>
        <!-- <a href="/lobby/checkPromo?provider=auto-sbo" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/sbobet.jpg" alt="เข้าเล่น SBOBet" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-ibc" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/IBC.jpg" alt="เข้าเล่น IBCBet" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-bti" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/BTI.jpg" alt="เข้าเล่น BTI" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-wss" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/ws.jpg" alt="เข้าเล่น WS Sports" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-cmd" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/cmd368.jpg" alt="เข้าเล่น CMD368 Sports" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-ims" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/im-sports.jpg" alt="เข้าเล่น IM Sports" style="width: 100%;"></a>
        <a href="/lobby/checkPromo?provider=auto-tfg" target="_blank"><img src="<?php echo $imageHost ?>/gr/new/tf.jpg" alt="เข้าเล่น TF Gaming" style="width: 100%;"></a> -->
    </div>

</section>
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/tap_panel_control.js"></script>
                    <script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>
<script type="text/javascript">
    $(function() {
        $(".play-button").click(function() {
            $('html, body').animate({
                scrollTop: $(".game-section").offset().top
            }, 1);
        });
    });

    function gotoLobby(v_provider) {
        var l_provider = v_provider;
        $.ajax({
            type: "POST",
            url: "checkProviderStatus.php",
            dataType: "json",
            data: {
                'provider': v_provider,
            },
        }).done(function(data) {
            dataLobbyURL = data.lobbyURL;
            dataLobbyType = data.lobbyType;
            if (data.status == 1) {
                $.ajax({
                    cache: false,
                    type: "GET",
                    url: "checkPromo.php?",
                    dataType: 'html',
                    data: {
                        'provider': v_provider,
                    },
                    success: function(data) {
                        // $('#xhtml').html(data);
                    },
                });
                // console.log('xxxxxxxxxxxxxxxxxxxxxxxxxxxx');
                document.getElementById(v_provider).click();

            } else {
                console.log('Provider maintenance');
                Swal.fire('ปิดปรับปรุง', 'ค่าย ' + data.providerName + ' ปิดปรับปรุง ขออภัยในความไม่สะดวกค่ะ', 'info');
            }
        });
    }
</script>