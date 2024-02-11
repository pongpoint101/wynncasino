<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
$WebSites = GetWebSites();
if (@$_SESSION['member_no'] == NULL) {
    header("location: " . GetFullDomain() . '/login');
    exit(0);
}

$sitetitle = 'ข้อมูลส่วนตัว';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/member_page2.css?v=' . filemtime("../assets/css/member_page2.css") . '">';
include_once ROOT_APP . '/componentx/header.php';

$sql = "SELECT * FROM pro_promotion_detail WHERE  pro_id IN(19,179,180,181) group by channel  ORDER BY m_order;";
$datakey = 'promoall:uilist:';
$promo_detail = GetDataSqlWhereAll($datakey, $sql, [], 5 * 60);
$uihome['index19']=false;$uihome['index179']=false;$uihome['index180']=false;$uihome['index181']=false;
foreach ($promo_detail as $k => $v) {
     switch ($v['pro_id']) {
        case 19: if($v['pro_status']==1){$uihome["index{$v['pro_id']}"]=true;}  break;
        case 179: if($v['pro_status']==1){$uihome["index{$v['pro_id']}"]=true;}  break;
        case 180: if($v['pro_status']==1){$uihome["index{$v['pro_id']}"]=true;}  break;
        case 181: if($v['pro_status']==1){$uihome["index{$v['pro_id']}"]=true;}  break;
     }
}
?>
<section class="main-section" id="app" v-cloak>
    <input type="hidden" id="wd_amount_text" name="wd_amount_text" v-model="data_global.withdraw_balance">
    <input type="hidden" id="min_wd" :value="data_global.min_wd">
    <input type="hidden" id="wd_amount" v-model="data_global.withdraw_balance">

    <div class="member-center mx-auto">
        <p><span>รหัสสมาชิกของคุณ </span><span class="account-number">{{data_global.username}}</span></p>
        <div class="wallet-info">
            <h1>ยอดเครดิตของคุณ</h1>
            <div class="bank-info">
                <p><span class="credit-amount">{{data_global.main_wallet}}</span> <span>฿</span></p>
                <p> <span>ข้อมูลบัญชีของคุณ:</span> <span class="bank-acount-number"> {{data_global.bank_accountnumber}}</span> <span class="bank-name"> {{data_global.bank_name}}</span></p>
            </div>
            <div class="transaction-btns"><a href="<?= GetFullDomain() ?>/deposit" class="deposite-btn member_deposit">ฝากเงิน</a> <a href="javascript:void(0)" class="withdraw-btn" v-on:click="wdAction()" :disabled="data_wd.issubmit">ถอนเงิน</a></div> <!-- <a href="https://wynncasino888.co/lobby" class="play-btn"><img src="../assets/images/member_page2/Icon ionic-md-play.png" alt="play"> <p>เข้าเล่นเกมส์</p></a> -->
        </div>
        <a href="<?= GetFullDomain() ?>/lobby">
            <div class="btn-gotolobby big-small-anim"><img src="../assets/images/member_page2/Icon ionic-md-play.png" alt="play">เข้าเล่นเกม คลิ๊ก !</div>
        </a>

        <div class="member-menu">
            <a href="<?= GetFullDomain() ?>/deposit" class="menu-card member_deposit"><img src="../assets/images/member_page2/menu-icons/ฝากเงิน.png" alt="ฝากเงิน">
                <p>ฝากเงิน</p>
            </a> <a href="javascript:void(0)" v-on:click="wdAction()" :disabled="data_wd.issubmit" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/ถอนเงิน.png" alt="ถอนเงิน">
                <p>ถอนเงิน</p>
            </a> <a href="<?= GetFullDomain() ?>/history" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/ประวัติ.png" alt="ประวัติ">
                <p>ประวัติ</p>
            </a> <a href="<?= GetFullDomain() ?>/commission" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/รับค่าคอมฯ.png" alt="รับยอดเสีย">
                <p>รับยอดเสีย</p>
            </a>
            <?php if($uihome['index19']){ ?>
            <a href="<?= GetFullDomain() ?>/reward/?type=daily&rewardtype=maxplayer" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/รับค่าคอมฯ.png" alt="รางวัลยอดเล่น">
                <p>รางวัลยอดเล่น</p>
            </a>
            <?php } 
            if($uihome['index181']){
            ?>
             <a href="<?= GetFullDomain() ?>/reward/?type=daily&rewardtype=maxwinnerplayer" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/รับค่าคอมฯ.png" alt="รางวัลยอดชนะ">
                <p>รางวัลยอดชนะ</p>
            </a>
            <?php
            }
           if($uihome['index180']){ 
            ?>
            <a href="<?= GetFullDomain() ?>/reward/?type=daily&rewardtype=cashbackplayer" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/รับค่าคอมฯ.png" alt="รางวัลยอดเสีย">
                <p>รางวัลยอดเสีย</p>
            </a>
            <?php }  
           if($uihome['index179']){  
            ?> 
            <a href="<?= GetFullDomain() ?>/reward/?type=daily&rewardtype=aff" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/รับค่าคอมฯ.png" alt="รางวัลยอดแนะนำเพื่อน">
                <p>รางวัลยอดแนะนำเพื่อน</p>
            </a>
            <?php } 
            ?>  
            <a href="javascript:void(0)" v-on:click="luckywheelBonus()" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/ลัคกี้สปินลุ้นทองคำ.png" alt="ลัคกี้สปิน ลุ้นทองคำ">
                <p>ลัคกี้สปิน </p>
            </a> <a href="javascript:void(0)" v-on:click="cardGameBonus()" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/เปิดไพ่.png" alt="เปิดไพ่ ลุ้นรางวัล">
                <p>เปิดไพ่</p>
            </a> <a href="<?= GetFullDomain() ?>/affiliate" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/แนะนำเพื่อนรับค่าคอมฯ.png" alt="แนะนำเพื่อน รับค่าคอมฯ">
                <p>แนะนำเพื่อน</p>
            </a>

            <a href="<?= GetFullDomain() ?>/logout.php" class="menu-card">
                <img src="../assets/images/member_page2/menu-icons/ออกจากระบบ.png?z1" alt="ออกจากระบบ">
                <p>ออกจากระบบ</p>
            </a>
        </div>
    </div>
</section>
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>
<script type="text/javascript">
    $(function() {
        $(document).on('click', '.play-btn', function() {
            if (App.data_global.main_wallet < 1) {
                Swal.fire('กรุณาเติมเงินค่ะ', 'ยอดเครดิตมีอยู่ ' + App.data_global.main_wallet + ' บาท มีไม่เพียงพอเล่นเกมส์ค่ะ!', 'info');
                return false
            }
            window.location = '<?= GetFullDomain() ?>/lobby';
        });

    });
</script>