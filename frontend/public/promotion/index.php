<?php
require '../bootstart.php';
$WebSites = GetWebSites();

$sitetitle = 'โปรโมชั่น';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/promotion_page.css?v=4">';
include_once ROOT_APP . '/componentx/header.php';

$sql = "SELECT * FROM pro_promotion_detail WHERE pro_status=1 AND  pro_id NOT IN(23,24,26) AND pro_bonus_in_website!=3 group by channel  ORDER BY m_order;";
$datakey = 'promoall:list:';
$promo_detail = GetDataSqlWhereAll($datakey, $sql, [], 5 * 60);
?>
<style>
    /* .mainbox_pro img {
    width: 240px;
    height: 240px;
    display: block;
    object-fit: cover;
    margin-right: 10px; 
}*/
</style>
<section class="info-section" id="app" v-cloak>
    <div class="member-info mx-auto">
        <div class="tab mx-auto">
            <button class="tablinks" id="defaultOpen" onclick="openGames(event, 'slot-games')">
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
            <button class="tablinks" onclick="openGames(event, 'sport-games')">
                <div>
                    <h3>กีฬา</h3>
                    <h4>SPORT GAME</h4>
                </div>
                <img src="../assets/images/home/sport-icon.png" alt="sport">
            </button>
            <button class="tablinks" onclick="openGames(event, 'fishing-games')">
                <div>
                    <h3>ตกปลา</h3>
                    <h4>FISHING</h4>
                </div>
                <img src="../assets/images/home/fishing-icon.png" alt="fishing">
            </button>
        </div>
        <?php include('pro_casino.php'); ?>
        <?php include('pro_slote.php'); ?>
        <?php include('pro_fishing.php'); ?>
        <?php include('pro_sportbook.php'); ?>
    </div>
</section>
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>
                <script src="' . GetFullDomain() . '/assets/js/tap_panel_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>