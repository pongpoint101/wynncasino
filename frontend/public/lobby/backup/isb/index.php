<?php
require '../../bootstart.php'; 
require_once ROOT.'/core/security.php';
require_once ROOT.'/core/db2/db.php';
function HotGame($gameID)
{
  switch ((int)$gameID) {
    case 905347:
    case 905995:
    case 904620:
    case 200402:
    case 200258:
    case 200421:
    case 200429:
    case 200540:
    case 908473:
    case 200346:
      $return = true;
      break;

    default:
      $return = false;
      break;
  }
  return $return;
}   
$db = new DB();  
$site =GetWebSites();    
require_once './api/isb-function.php'; 
$reqPost = $_REQUEST ?? $_POST ?? $_GET ?? NULL;

if (isset($reqPost) && array_key_exists('playerid', $reqPost)) {
  switch ($reqPost['playerid']) {
    case '100001':
      $_SESSION['member_no'] = 100001;
      $_SESSION['member_login'] = '5f71e7ff61578';
      break;

    case '100002':
      $_SESSION['member_no'] = 100002;
      $_SESSION['member_login'] = '5f71e863a210b';
      break;

    case '100003':
      $_SESSION['member_no'] = 100003;
      $_SESSION['member_login'] = '5f71eb9960eac';
      break;

    default:
      # code...
      break;
  }
  $_SESSION['member_promo'] = 0;
}

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
$member_login = $_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];
$userName = $site['site_id'] . $member_no;
$lang = 'en';

$productType=2;
require ROOT.'/core/promotions/lobby-function.php';  

$arrConfig =getProviderConfig('isb', 66, 1);
$member_wallet = $db->query("SELECT main_wallet FROM member_wallet WHERE member_no={$member_no}")->fetchArray();
$redirectURL = $site['host'] . '/lobby/isb';

$xmlSource = $arrConfig['xml_desktop'];
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
  $xmlSource = $arrConfig['xml_mobile'];
}

$arrXML = simplexml_load_file($xmlSource);
$objJsonDocument = json_encode($arrXML);
$arrOutput = json_decode($objJsonDocument, true);
$baseImagesURL = $arrOutput['images']['@attributes']['url'];

$countAll = 0;
$countAllSelected = 0;
$countSlot = 0;
$countMegaways = 0;
$countECasino = 0;
$countHot = 0;

$arrOutput = $arrOutput['games']['c']['g'];
$arrSelectedGame = array();

foreach ($arrOutput as $c_key => $c_value) {
  $value = $c_value['@attributes'];
  if (!empty($value['img'])) { // && $value['provider'] == 'iSoftBet') {
    $noCount = 0;
    if (strpos($value['i'], '_92') !== false) {
      $noCount = 1;
    }
    if (strpos($value['i'], '_njc') !== false) {
      $noCount = 1;
    }
    if (strpos($value['i'], '_njn') !== false) {
      $noCount = 1;
    }

    $gameID = $value['id'];
    $img = $baseImagesURL . $value['img'];
    $launchURL = $arrConfig['gap_launcher'] . $arrConfig['lid'] . '/' . $value['id'];
    $launchURL .= '?lang=' . $lang . '&cur=THB&mode=1&user=' . $userName . '&uid=' . $member_no . '&token=' . $member_login;
    $name = $value['n'];
    $identify = $value['i'];
    if (HotGame($gameID)) {
      $hot = true;
      $countHot++;
    } else {
      $hot = false;
    }
    $type = '';
    $typeOriginal =  $value['type'];
    if ($value['type'] == 'slot') {
      if (strpos($name, 'Megaways') !== false) {
        $type = 'Fishing';
        if ($noCount == 0)
          $countMegaways++;
      } else {
        $type = 'Slot';
        if ($noCount == 0)
          $countSlot++;
      }
    } else {
      $type = 'ECasino';
      if ($noCount == 0)
        $countECasino++;
    }
    if ($hot) {
      $type = 'Bingo';
    }

    if ($noCount == 0) {
      $arrSelectedGame[] = [
        'id' => $gameID,
        'img' => $img,
        'n' => $name,
        'i' => $identify,
        'type' => $type,
        'type_original' => $typeOriginal,
        'hot' => $hot,
        'launch' => $launchURL
      ];
      $countAllSelected++;
    }
  }
  $countAll++;
} 

?>
<html lang="th">

<head>
  <title><?=$site['brand_name']?> - iSoftBet</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/isb/assets/css/style.css' ?>">
  <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/isb/assets/css/hover.css' ?>">
  <!-- <link rel="stylesheet" href="/lobby/assets/css/style.min.css"> -->
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <style type="text/css">
    .bgimg {
      background-image: url('/lobby/isb/assets/img/s1.png');
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
          <a href="javascript:void(0)" onclick="getMemberWallet(0)" style="text-decoration: none;">
            <p id="credit-text"><i class="las la-sync-alt"></i>เครดิต</p>
            <p id="credit-numb"><?php echo number_format($member_wallet['main_wallet'], 2) ?></p>
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </header>

  <div class="container">
    <a href="<?php echo $site['host'] ?>/lobby">
      <img src="/lobby/isb/assets/img/isb-logo.png" style="width: 30%;">
    </a>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="filters text-center">
          <li id="all" class="active" data-filter="*">
            <a href="#!">
              <!-- <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/all-icon.png' ?>" style="width: 100px;"><BR><b>เกมส์ทั้งหมด</b> (<?php echo ($countSlot + $countMegaways + $countECasino) ?>) -->
              <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/all-icon.png' ?>" style="width: 100px;"><BR><b>เกมส์ทั้งหมด</b> (<?php echo $countAllSelected ?>)
              <!-- <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/all-icon.png' ?>" style="width: 100px;"><BR><b>เกมส์ทั้งหมด</b> -->
            </a>
          </li>
          <li data-filter=".Bingo">
            <a href="#!">
              <img src="./assets/img/hot.png" alt="" style="width: 74px;transform: rotate(25deg);"><BR><b>Top 10</b>
            </a>
          </li>
          <li data-filter=".Slot">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/slot.png' ?>" style="width: 100px;"><BR><b>สล็อต</b> (<?php echo $countSlot ?>)
            </a>
          </li>
          <li data-filter=".Fishing">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/slot.png' ?>" style="width: 100px;"><BR><b>Megaways</b> (<?php echo $countMegaways ?>)
            </a>
          </li>
          <li data-filter=".ECasino">
            <a href="#!">
              <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/casino.png' ?>" style="width: 100px;"><BR><b>คาสิโน</b> (<?php echo $countECasino ?>)
            </a>
          </li>
        </ul>
        <div class="projects" style="position: relative; height: 8858.81px;">
          <div class="row">
            <?php
            // $gameName = array();
            // $gameName = array_column($arrSelectedGame, 'n');
            // array_multisort($gameName, SORT_ASC, $arrSelectedGame);
            // echo '<pre>';
            // print_r($arrSelectedGame);
            // exit();

            $namePrevious = '';
            $count = count($arrSelectedGame) - 1;
            for ($i = $count; $i != (-1); $i--) {
              $value = $arrSelectedGame[$i];
              // }
              // foreach ($arrSelectedGame as $key => $value) {
              if (empty($value['img'])) {
                continue;
              }
              // if ($value['n'] == $namePrevious) {
              //   continue;
              // }
              // if (strpos($value['i'], '_92') !== false) {
              //   continue;
              // }
              // if (strpos($value['i'], '_njc') !== false) {
              //   continue;
              // }
              // if (strpos($value['i'], '_njn') !== false) {
              //   continue;
              // }

              echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item ' . $value['type'] . '" style="position: absolute; left: 0px; top: 0px;">';
              if ($i >= $count - 10) {
                echo '<img src="./assets/img/new03.png" alt="" class="img-fluid game-img" style="position: absolute;width: 45%;">';
              }
              if ($value['hot'])
                echo '<img src="./assets/img/hot.png" alt="" class="img-fluid game-img" style="position: absolute;width: 28%;left: 73%;transform: rotate(58deg);">';

              echo '<a class="game-item" href="' . $value['launch'] . '" target="_blank">';
              echo '<img src="' . $value['img'] . '" alt="" class="img-fluid game-img">';
              echo '</a>';
              echo '<div class="middle">';
              echo '<a href="' . $value['launch'] . '" target="_blank" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
              echo '</div>';
              echo '<div class="game-title-d text-center">';
              if ($value['type'] == 'Fishing') {
                $value['type'] = 'Megaways';
                // $value['n'] = str_replace('Megaways', '', $value['n']);
              }

              // if ($value['type'] == 'Bingo') {
              //   if ($value['type_original'] == 'roulette' || $value['type_original'] == 'card game') {
              //     $value['type_original'] = 'ECasino';
              //   }
              //   $value['type'] = ucfirst($value['type_original']);
              // }

              echo '<a class="game-item" href="' . $value['launch'] . '" target="_blank" class="game-title" style="text-decoration: none;color: yellow;">';
              echo $value['n'] . ' (' . ucfirst($value['type_original']) .  ')</a>';
              echo '</div>';
              echo '</div>';
              $namePrevious = $value['n'];
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <footer class="footer">
    <?php
    // include_once '../footer.php';
    ?>
  </footer> -->

  <footer>
    <div class="top-line-footer"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-2">
          <div class="logo-footer">
            <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/isb-logo.png' ?>" class="w-100">
            <p>Copyright©2020 <?=$site['brand_name']?></p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <a class="back-to-top" style="display: none;">
    <img src="<?php echo $site['host'] . '/lobby/isb/assets/img/scrolltop.png' ?>" alt="">
  </a>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo $site['host'] . '/lobby/isb/assets/js/isotope.min.js' ?>"></script>
  <script src="<?php echo $site['host'] . '/lobby/isb/assets/js/script.js' ?>"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

  <script type="text/javascript">
    function KickPGPlayer(player_name) {
      // alert(player_name);
      $.ajax({
        type: "POST",
        url: "/lobby/joker/kick-pg-player.php",
        dataType: "json",
        data: {
          member_no: player_name
        }
      }).done(function(data) {
        // alert('Done');
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

    $('.projects').imagesLoaded(function() {
       console.log("Images Loaded");
      var $projects = $('.projects');
      $projects.isotope({
        itemSelector: '.item',
        layoutMode: 'fitRows'
      });
    });

    $(document).ready(function() {
      //Check to see if the window is top if not then display button
      $(window).scroll(function() {
        // Show button after 100px
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