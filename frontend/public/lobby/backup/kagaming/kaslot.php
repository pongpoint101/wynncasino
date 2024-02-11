<?php

require '../../bootstart.php'; 

require_once ROOT.'/core/security.php';

require_once ROOT.'/core/db2/db.php';

$db = new DB(); 

$site =GetWebSites();  

require_once './api/ka-function.php';



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



$member_promo = $_SESSION['member_promo'];

if ($member_promo == 31 || $member_promo == 32 || $member_promo == 33) {

  return;

}



$member_no = $_SESSION['member_no'];

$member_login =$_SESSION['member_login'];

$member_wallet = $db->query("SELECT main_wallet FROM member_wallet WHERE member_no=?", $member_no)->fetchArray();



$sql = "SELECT * FROM m_providers WHERE provider_code='kas'";

$evo_config = $db->query($sql)->fetchArray();

$evo_config = json_decode($evo_config['config_string'], true);

$lang = 'th';

$icon_type = 'square';



$api_url = $evo_config['api_url'];



$redirect_url = $site['host'] . '/lobby/kagaming';



$mobile = 'false';

$detecMob = new Mobile_Detect();

if ($detecMob->isMobile()) {

  $mobile = 'true';

}



$res = $db->query("SELECT count(*) as count_all FROM ka_list_games WHERE status=1")->fetchArray();

$countAll = $res['count_all'];

$res = $db->query("SELECT count(*) as count_all FROM ka_list_games WHERE game_type='slots' AND status=1")->fetchArray(); // Slot

$countSlot = $res['count_all'];

$res = $db->query("SELECT count(*) as count_all FROM ka_list_games WHERE game_type='fish' AND status=1")->fetchArray();  // Fishing

$countFish = $res['count_all'];





$res = $db->query("SELECT count(*) as count_all FROM ka_list_games WHERE game_type in ('table','other','vpoker') AND status=1")->fetchArray(); // Casino

$countECasino = $res['count_all'];



$listGames = $db->query("SELECT * FROM ka_list_games WHERE status=1")->fetchAll();



?>



<html lang="th">



<head>

  <title>KA Gaming <?=$site['brand_name']?></title>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/kagaming/assets/css/style.css' ?>">

  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/kagaming/assets/css/hover.css' ?>">

  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

  <style type="text/css">

    .bgimg {

      background-image: url('/lobby/kagaming/assets/img/banner-head.png');

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

  <!-- <div id="loader"></div> -->



  <header class="bgimg">

    <div class="container">

      <div class="logo text-center">

        <div class="logo-left">

          <!-- <a href="<?php echo $site['host'] ?>"><i class="las la-home la-3x"></i>

            <img src="/lobby/kagaming/assets/img/logo-white.png" style="width: 110%;">

          </a> -->

        </div>

        <div class="logo-center">

          <a href="<?php echo $site['host'] ?>">

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

    <a href="<?php echo $site['host'] ?>"><i class="las la-home la-3x"></i>

      <!-- <img src="/lobby/kagaming/assets/img/logo-white.png" style="width: 30%;"> -->

    </a>

  </div>



  <div class="container">

    <div class="row">

      <div class="col-lg-12">

        <ul class="filters text-center">

          <li id="all" class="active" data-filter="*">

            <a href="#!">

              <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/all-icon.png' ?>" style="width: 100px;"><BR><b>เกมส์ทั้งหมด</b> (<?php echo $countAll ?>)

            </a>

          </li>

          <li data-filter=".Slot">

            <a href="#!">

              <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/slot-icon-365.png' ?>" style="width: 100px;"><BR><b>สล็อต</b> (<?php echo $countSlot ?>)

            </a>

          </li>

          <li data-filter=".Fish">

            <a href="#!">

              <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/fish.png' ?>" style="width: 100px;"><BR><b>ยิงปลา</b> (<?php echo $countFish ?>)

            </a>

          </li>

          <li data-filter=".ECasino">

            <a href="#!">

              <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/poker-icon.png' ?>" style="width: 100px;"><BR><b>คาสิโน</b> (<?php echo $countECasino ?>)

            </a>

          </li>

          <!-- <li data-filter=".Bingo">

            <a href="#!">

              <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/bingo.png' ?>" style="width: 100px;"><BR><b>บิงโก</b> (<?php echo $countBingo ?>)

            </a>

          </li> -->

        </ul>

        <!-- <div id="loader" class="loader"></div> -->

        <div class="projects" style="position: relative; height: 8858.81px;">

          <div class="row">

            <?php

            foreach ($listGames as $key => $value) {

              if ($value['game_type'] == 'slots') {

                $game_type = "Slot";

              }

              if ($value['game_type'] == 'fish') {

                $game_type = "Fish";

              }

              if (($value['game_type'] == 'table') || ($value['game_type'] == 'other') || ($value['game_type'] == 'vpoker')) {

                $game_type = "ECasino";

              }

              // $game_details = json_decode($value['details'], true);

              // $game_image = str_replace(':', '', $game_details['name']);

              // $game_image = '/lobby/kagaming/assets/img/games/' . str_replace(' ', '_', $game_image) . '_Thumbnail_160x160.png';

              $game_image = 'https://rmpiconcdn.kaga88.com/kaga/gameIcon?game=' . $value['game_id'] . '&lang=' . $lang . '&type=' . $icon_type;

              echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item ' . $game_type . '" style="position: absolute; left: 0px; top: 0px;">';

              echo '<a class="game-item" onclick="getGameURL(' . $value['id'] . ')" target="_self">';

              echo '<img src="' . $game_image . '" alt="" class="img-fluid game-img">';

              echo '</a>';

              echo '<div class="middle">';

              echo '<a onclick="getGameURL(' . $value['id'] . ')" target="_self" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';

              echo '</div>';

              echo '<div>';

              echo '<a onclick="getGameURLTrial(' . $value['id'] . ')" target="_self" class="play-joker"><b>ทดลองเล่น</b></a>';

              echo '</div>';

              echo '<div class="game-title-d text-center">';

              echo '<a onclick="getGameURL(' . $value['id'] . ')" target="_self" class="game-title" style="color: gold;">' . $value['game_name'] . '</a>';

              echo '</div>';

              echo '</div>';

            }



            ?>

          </div>



        </div>

      </div>

    </div>

  </div>

  <footer>

    <div class="top-line-footer"></div>

    <div class="container">

      <div class="row">

        <div class="col-md-2">

          <div class="logo-footer">

            <!-- <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/logo-white.png' ?>" class="w-100"> -->

            <p>Copyright©2020 <?=$site['brand_name']?></p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <a class="back-to-top" style="display: none;">
    <img src="<?php echo $site['host'] . '/lobby/kagaming/assets/img/scrolltop.png' ?>" alt="">
  </a>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo $site['host'] . '/lobby/kagaming/assets/js/isotope.min.js' ?>"></script>
  <script src="<?php echo $site['host'] . '/lobby/kagaming/assets/js/script.js' ?>"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <script type="text/javascript">
    function getGameURL(id) {
      $.ajax({
        type: "POST",
        url: "getGameURL.php",
        dataType: "json",
        data: {
          'id': id,
        },
      }).done(function(data) {

        if (data.status == "ok") {
          window.open(data.link, "_self");
        }
      });
    }
    function getGameURLTrial(id) {
      $.ajax({
        type: "POST",
        url: "getGameURL.php",
        dataType: "json",
        data: {
          'id': id,
        },
      }).done(function(data) { 
        if (data.status == "ok") {
          window.open(data.link + "&m=1", "_self");
        }
      });
    }

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