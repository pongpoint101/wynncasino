<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

$WebSites = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
    header("location: " . GetFullDomain() . '/login');
    exit(0);
}
$memberNo = $_SESSION['member_no'];
$sitetitle = 'ข้อมูลส่วนตัว';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/member_page1.css?v=' . filemtime("../assets/css/member_page1.css") . '">';
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
        <div class="row justify-content-center div-lob">
            <a href="<?= GetFullDomain() ?>/deposit" class="deposite-btn">ฝากเงิน</a>
            <a href="javascript:void(0)" v-on:click="wdAction()" :disabled="data_wd.issubmit" class="withdraw-btn">ถอนเงิน</a>
        </div>
        <div class="acount-activities">
            <a href="<?= GetFullDomain() ?>/home" class="text-decoration-none">
                <div class="btn-gotohome big-small-anim" style="margin-top: 0px;width:150px;min-width:200px;padding:5px;border-radius:2rem;border: 2px solid #00ff47;">กลับหน้าสมาชิก</div>
            </a>
            <a href="<?= GetFullDomain() ?>/logout.php" class="signout-btn">ออกจากระบบ</a>
        </div>
    </div>
</section>
<section class="game-section">
    <div class="tab mx-auto">
        <div class="row w-100 bd-lob">
            <div class="col-custom">
                <button class="tablinks" id="defaultOpen" onclick="openGames(event, 'sport-games')">
                    <img src="../assets/images/member_page1/game-banners/football.png" alt="sport">
                    <div>
                        <h3>เดิมพันกีฬา</h3>
                    </div>
                </button>
            </div>
            <div class="col-custom">
                <button class="tablinks" onclick="openGames(event, 'casino-games')">
                    <img src="../assets/images/member_page1/game-banners/playing-cards.png" alt="live casino">
                    <div>
                        <h3>คาสิโน</h3>
                    </div>
                </button>
            </div>
            <div class="col-custom">
                <button class="tablinks" onclick="openGames(event, 'slot-games')">
                    <img src="../assets/images/member_page1/game-banners/slot-machine.png" alt="slot game">
                    <div>
                        <h3>สล๊อตออนไลน์</h3>
                    </div>
                </button>

            </div>
            <div class="col-custom">
                <button class="tablinks" onclick="openGames(event, 'fishing-games')">
                    <img src="../assets/images/member_page1/game-banners/fish.png" alt="fishing">
                    <div>
                        <h3>เกมส์ยิงปลา</h3>
                    </div>
                </button>
            </div>

            <div class="col-custom">
                <button class="tablinks" onclick="openGames(event, 'lotto-games')">
                    <img src="../assets/images/member_page1/game-banners/lotto.png" alt="lotto">
                    <div>
                        <h3>หวย</h3>
                    </div>
                </button>
            </div>
        </div>

    </div>

    <div id="slot-games" class="tabcontent mx-auto">
        <?php
        $resSlot = $db->query("SELECT * FROM lobby_control WHERE product_type=3 AND status=1 AND provider_id<>0  ORDER BY provider_ordering")->fetchAll();
        // AND provider_code LIKE 'auto-%'
        foreach ($resSlot as $key => $value) {
            // if ($value['provider_code'] == 'pgsl' && $memberNo!=1000001) {
            //     continue;
            // }
            //     $isAllow = false;
            //     switch ($memberNo) {
            //         case '1000001':
            //         case '1000005':
            //             $isAllow = true;
            //             break;

            //         default:
            //             $isAllow = false;
            //             break;
            //     }
            //     if (!$isAllow) {
            //         continue;
            //     }
            // }

            $imgProvider = $imageHost . $value['img_logo_path'] . $value['img_logo'];
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="" style="width: 100%;"></a>
            <a id="<?php echo $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" style="display: none;"></a>
        <?php
        }
        ?>

    </div>
    <div id="casino-games" class="tabcontent mx-auto">
        <?php
        $resLC = $db->query("SELECT * FROM lobby_control WHERE product_type=2 AND status=1 AND provider_id<>0 ORDER BY provider_ordering")->fetchAll();
        foreach ($resLC as $key => $value) {
            if ($value['status'] != 1) {
                continue;
            }
            if ($value['provider_code'] == 'auto-qmk') {
                $imgProvider = $imageHost . $value['img_logo_path'] . $value['img_logo'];
            } else {
                $imgProvider = $imageHost . $value['img_path'] . $value['img_logo'];
            }
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="<?php echo $value['provider_name'] ?>" style="width: 100%;"></a>
            <a id="<?php echo  $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" style="display: none;"></a>
        <?php
        }
        ?>
    </div>
    <div id="fishing-games" class="tabcontent mx-auto">
        <?php
        $resFishing = $db->query("SELECT * FROM lobby_control WHERE product_type=4 AND status=1 AND provider_id<>0 ORDER BY provider_ordering")->fetchAll();
        foreach ($resFishing as $key => $value) {
            if ($value['status'] != 1) {
                continue;
            }
            $imgProvider = $imageHost . $value['img_logo_path'] . $value['img_logo'];
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="<?php echo $value['provider_name'] ?>" style="width: 100%;"></a>
            <a id="<?php echo  $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" style="display: none;"></a>
        <?php
        }
        ?>
    </div>
    <div id="sport-games" class="tabcontent mx-auto">
        <?php
        $resSport = $db->query("SELECT * FROM lobby_control WHERE product_type=1 AND status=1 AND provider_id<>0 ORDER BY provider_ordering")->fetchAll();
        foreach ($resSport as $key => $value) {
            if ($value['status'] != 1) {
                continue;
            }
            $imgProvider = $imageHost . $value['img_logo_path'] . $value['img_logo'];
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="<?php echo $value['provider_name'] ?>" style="width: 100%;"></a>
            <a id="<?php echo  $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" style="display: none;"></a>
        <?php
        }
        ?>
        <!-- <a href="javascript:void(0)" onclick="gotoLobby('auto-tfg')"><img src="<?php echo $imageHost ?>/gr/new/tf.jpg" alt="เข้าเล่น TF Gaming" style="width: 100%;"></a>
        <a id="auto-tfg" href="/lobby/go?provider=auto-tfg" style="display: none;"></a> -->
    </div>
    <div id="lotto-games" class="tabcontent mx-auto">
    <?php
        $resLotto = $db->query("SELECT * FROM lobby_control WHERE product_type=7 AND status=1 AND provider_id<>0 ORDER BY provider_ordering")->fetchAll();
        foreach ($resLotto as $key => $value) {
            if ($value['status'] != 1) {
                continue;
            }
            $imgProvider = $imageHost . $value['img_logo_path'] . $value['img_logo'];
        ?>
            <a href="javascript:void(0)" onclick="gotoLobby('<?php echo $value['provider_code'] ?>')"><img src="<?php echo $imgProvider ?>" alt="<?php echo $value['provider_name'] ?>" style="width: 100%;"></a>
            <a id="<?php echo  $value['provider_code'] ?>" href="/lobby/go?provider=<?php echo $value['provider_code'] ?>" style="display: none;"></a>
        <?php
        }
        ?>
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

    function checkBrowser() {

        // Get the user-agent string
        let userAgentString =
            navigator.userAgent;

        // Detect Chrome
        let chromeAgent =
            userAgentString.indexOf("Chrome") > -1;

        // Detect Internet Explorer
        let IExplorerAgent =
            userAgentString.indexOf("MSIE") > -1 ||
            userAgentString.indexOf("rv:") > -1;

        // Detect Firefox
        let firefoxAgent =
            userAgentString.indexOf("Firefox") > -1;

        // Detect Safari
        let safariAgent =
            userAgentString.indexOf("Safari") > -1;

        // Discard Safari since it also matches Chrome
        if ((chromeAgent) && (safariAgent))
            safariAgent = false;

        // Detect Opera
        let operaAgent =
            userAgentString.indexOf("OP") > -1;

        // Discard Chrome since it also matches Opera     
        if ((chromeAgent) && (operaAgent))
            chromeAgent = false;

        return safariAgent;
    }

    function gotoLobby(v_provider) {
        $.ajax({
            cache: false,
            type: "GET",
            url: "checkPromo.php?",
            dataType: 'html',
            data: {
                'provider': v_provider,
            },
            success: function(data) {
                $('#xhtml').html(data);
            },
        });
    }
</script>