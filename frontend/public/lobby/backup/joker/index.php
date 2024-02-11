<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php'; 
$db = new DB(); 
$site =GetWebSites();   
require_once './api/joker-function.php'; 

$logged_in = FALSE;

if (!isset($_SESSION['member_no'])) {
  $logged_in = TRUE;
} elseif (empty($_SESSION['member_no'])) {
  $logged_in = TRUE; 
} elseif (!isset($_SESSION['member_login'])) {
  $logged_in = TRUE;
} elseif (empty($_SESSION['member_login'])) {
  $logged_in = TRUE; 
}

if ($logged_in) {
  session_destroy();
  header('refresh:0;url=' . $site['host']);
  exit();
}

$member_no = $_SESSION['member_no'];
$member_login =$_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];
$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$member_wallet = $db->query("SELECT main_wallet FROM member_wallet WHERE member_no=?", $member_no)->fetchArray();


$sql = "SELECT * FROM m_providers WHERE provider_code='jks'";
$jk_config = $db->query($sql)->fetchArray();
$jk_config = json_decode($jk_config['config_string'], true);
$redirect_url = $site['host'] . '/lobby/joker';


$mobile = 'false';
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  $mobile = 'true';
}

$res = $db->query("SELECT count(*) as count_all FROM jk_list_games")->fetchArray();
$countAll = $res['count_all'];
$res = $db->query("SELECT count(*) as count_all FROM jk_list_games WHERE game_type='Slot'")->fetchArray();
$countSlot = $res['count_all'];
$res = $db->query("SELECT count(*) as count_all FROM jk_list_games WHERE game_type='Fishing'")->fetchArray();
$countFishing = $res['count_all'];
$res = $db->query("SELECT count(*) as count_all FROM jk_list_games WHERE game_type='ECasino'")->fetchArray();
$countECasino = $res['count_all'];
$res = $db->query("SELECT count(*) as count_all FROM jk_list_games WHERE game_type='Bingo'")->fetchArray();
$countBingo = $res['count_all'];


$listGames = $db->query("SELECT * FROM jk_list_games")->fetchAll();
?>
<html lang="th">
<head>
  <title><?=$site['brand_name']?> - Joker</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/joker/assets/css/style.css' ?>">
  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/joker/assets/css/hover.css' ?>">
  <link rel="stylesheet" href="/lobby/assets/css/style.min.css">
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <style type="text/css">
    .bgimg {
      background-image: url('/lobby/joker/assets/img/s1.png');
    }
    .roundedcorners {
      border-radius: 15px;
      background-color: black;
    }
    /* Center the loader */
    #loader {
      position: absolute;
      left: 50%;
      top: 50%;
      z-index: 1;
      width: 150px;
      height: 150px;
      margin: -75px 0 0 -75px;
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }
    @-webkit-keyframes spin {
      0% {
        -webkit-transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
      }
    }
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    /* Add animation to "page content" */
    .animate-bottom {
      position: relative;
      -webkit-animation-name: animatebottom;
      -webkit-animation-duration: 1s;
      animation-name: animatebottom;
      animation-duration: 1s
    }
    @-webkit-keyframes animatebottom {
      from {
        bottom: -100px;
        opacity: 0
      }
      to {
        bottom: 0px;
        opacity: 1
      }
    }
    @keyframes animatebottom {
      from {
        bottom: -100px;
        opacity: 0
      }
      to {
        bottom: 0;
        opacity: 1
      }
    }
    #myDiv {
      display: none;
      text-align: center;
    }
  </style>
</head>
<body style="margin:0;">
  <header class="bgimg">
    <div class="container">
      <div class="logo text-center">
        <div class="logo-left">
        </div>
        <div class="logo-center">
          <a href="<?php echo $site['host'] ?>/lobby">
            <img src="<?php echo $site['host'] . '/assets/images/common/logo.png' ?>" style="width:190px;">
          </a>
        </div>
        <div class="logo-right roundedcorners">
          <a href="#" onclick="getMemberWallet(0)" style="text-decoration: none;">
            <p id="credit-text"><i class="las la-sync-alt"></i>เครดิต</p>
            <p id="credit-numb"><?php echo number_format($member_wallet['main_wallet'], 2) ?></p>
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </header>
  <div class="container">
    <a href="<?= $site['host'] ?>/lobby">
      <img src="/lobby/joker/assets/img/logo-joker.png" style="width: 30%;">
    </a>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="filters text-center" style="font-size: x-large;">
          <li id="all" class="active" data-filter="*">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/all-icon.png' ?>" style="width: 100px;"><BR><b>เกมส์ทั้งหมด</b> (<?php echo $countAll ?>)
            </a>
          </li>
          <li data-filter=".Slot">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/slot.png' ?>" style="width: 100px;"><BR><b>สล็อต</b> (<?php echo $countSlot ?>)
            </a>
          </li>
          <li data-filter=".Fishing">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/fish.png' ?>" style="width: 100px;"><BR><b>ยิงปลา</b> (<?php echo $countFishing ?>)
            </a>
          </li>
          <li data-filter=".ECasino">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/casino.png' ?>" style="width: 100px;"><BR><b>คาสิโน</b> (<?php echo $countECasino ?>)
            </a>
          </li>
          <li data-filter=".Bingo">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/bingo.png' ?>" style="width: 100px;"><BR><b>บิงโก</b> (<?php echo $countBingo ?>)
            </a>
          </li>
        </ul>
        <!-- <div id="loader" class="loader"></div> -->
        <div class="projects" style="position: relative; height: 8858.81px;">
          <div class="row">
            <?php
            foreach ($listGames as $key => $value) {
              echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item ' . $value['game_type'] . '" style="position: absolute; left: 0px; top: 0px;">';
              echo '<a class="game-item" href="' . $jk_config['forward_url'] . '/playGame?token=' . $member_login . '&appID=' . $jk_config['app_id'] . '&gameCode=' . $value['game_code'] . '&language=' . $jk_config['language'] . '&mobile=' . $mobile . '&redirectUrl=' . $redirect_url . '" target="_blank">';
              echo '<img src="' . $value['image1'] . '" alt="" class="img-fluid game-img">';
              echo '</a>';
              echo '<div class="middle">';
              echo '<a href="' . $jk_config['forward_url'] . '/playGame?token=' . $member_login . '&appID=' . $jk_config['app_id'] . '&gameCode=' . $value['game_code'] . '&language=' . $jk_config['language'] . '&mobile=' . $mobile . '&redirectUrl=' . $redirect_url . '" target="_blank" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
              echo '</div>';
              echo '<div class="game-title-d text-center">';
              echo '<a href="' . $jk_config['forward_url'] . '/playGame?token=' . $member_login . '&appID=' . $jk_config['app_id'] . '&gameCode=' . $value['game_code'] . '&language=' . $jk_config['language'] . '&mobile=' . $mobile . '&redirectUrl=' . $redirect_url . '" target="_blank" class="game-title">' . $value['game_name'] . '</a>';
              echo '</div>';
              echo '</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div> 
    <?php
    include_once '../footer.php'; 
    ?> 
  <a class="back-to-top" style="display: none;">
    <img src="<?php echo $site['host'] . '/lobby/joker/assets/img/scrolltop.png' ?>" alt="">
  </a>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo $site['host'] . '/lobby/joker/assets/js/isotope.min.js' ?>"></script>
  <script src="<?php echo $site['host'] . '/lobby/joker/assets/js/script.js' ?>"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <script type="text/javascript"> 
  function getMemberWallet(interval) { 
          $.ajax({
          url:'../../actions/Pullprofile.php',
          type: 'get',  
          contentType: "application/json",
          dataType: "json",
          cache: false,
          success: function(data){  
            document.getElementById("credit-numb").textContent =data.main_wallet;
          }, 
          error: function(){   }
          }); 
      if (interval == 0) {
        return;
      }
      setTimeout(function() {
        getMemberWallet();
      }, 5000);
    } 
    getMemberWallet(1);
    $('.container').imagesLoaded(function() {
      var $projects = $('.projects');
      $projects.isotope({
        itemSelector: '.item',
        layoutMode: 'fitRows'
      });
    });
    $(document).ready(function() {
      $(window).scroll(function() {
        var showAfter = 100;
        if ($(this).scrollTop() > showAfter) {
          $('.back-to-top').fadeIn();
        } else {
          $('.back-to-top').fadeOut();
        }
      });
      //Click event to scroll to top
      $('.back-to-top').click(function() {
        $('html, body').animate({
          scrollTop: 0
        }, 800);
        return false;
      });
    });

  </script>
</body>
</html>