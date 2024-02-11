<style>
 .footer__nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
.footer__nav-link {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    padding: 0.75rem;
    background-repeat: repeat-x;
    background-position: center center;
    background-size: auto 100%;
    color: #fff;
    font-size: 1.3rem;
}
.footer__nav-link--signup{
    background: rgb(156,48,142);
    background: linear-gradient(180deg, rgba(156,48,142,1) 0%, rgba(192,7,135,1) 65%, rgba(156,48,142,1) 100%);
}
.footer__nav a:hover { color: #c3b902; text-decoration: none;}
/* ================================================================================ */
.footer-navbar {
    display: block !important;
    overflow: hidden;
    background-color: #460000 ;
    position: fixed;
    bottom: 0;
    width: 100%;
    margin: 0 auto;
    height: 70px;
    z-index: 999;
}
.footer-navbar a {
    float: left;
    width: 20%;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding-top: 10px;
    padding-bottom: 10px;
    text-decoration: none;
    font-size: 13px;
}
.footer-navbar a:hover {
    background-color: #1091fc;
}

</style>

<footer class="footer">
    <!-- <nav class="footer__nav text-center" style="z-index: 100;">
        <a href="<?= $site['host'] ?>/home" id="dashboardMain" class="footer__nav-link footer__nav-link--signup" style="font-size: 30px;"><i class="fas fa-home"></i> หน้าหลัก</a>
        <a href="<?= $site['host'] ?>/lobby" id="gamesRoom" class="footer__nav-link footer__nav-link--signup" style="font-size: 30px;"><i class="fas fa-dice"></i> ห้องเกมส์</a>
    </nav> -->
    <div class="footer-navbar">
            <a href="<?=(isset($site['line_at_url'])&&@$site['line_at_url']!=''?@$site['line_at_url']:'#')?>" rel="noreferer nofollow" target="_blank">
            <img src="/assets/images/home/line-menu.png" alt="ติดต่อเรา" width="30px" height="30px">
            <p>ติดต่อเรา</p>
            </a>

            <a href="/deposit/">
            <img src="/assets/images/home/casino-menu.png" alt="ฝากถอน" width="30px" height="30px">
            <p>ฝาก-ถอน</p>
            </a>
            
            <a href="/lobby/">
            <img src="/assets/images/home/slot-menu.png" alt="เข้าเล่น" width="30px" height="30px">
            <p>เข้าเล่น</p>
            </a>
            
            <a href="/promotion/">
            <img src="/assets/images/home/bonus-menu.png" alt="โปรโมชั่น" width="30px" height="30px">
            <p>โปรโมชั่น</p>
            </a>
            
            <a href="/home/">
            <img src="/assets/images/home/member-menu.png" alt="สมาชิก" width="30px" height="30px">
            <p>สมาชิก</p>
            </a>
        </div>
</footer>
