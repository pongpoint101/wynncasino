<?php
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';  

$site =GetWebSites();   

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

$type = 1;
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
$memberNo = $_SESSION['member_no'];
$member_login = $_SESSION['member_login'];
$member_promo = $_SESSION['member_promo'];
 
$res =$conn->query("SELECT * FROM members WHERE status!=1 AND id=?",[$memberNo]); 
if ($res->num_rows() > 0) {
    return;
}

$productType=2;
require ROOT.'/core/promotions/lobby-function.php';   


$memberWallet = $conn->query("SELECT * FROM member_wallet WHERE member_no=?",[$memberNo])->row_array();

$sql = "SELECT * FROM m_providers WHERE provider_code='pgs'";
$PGConfig = $conn->query($sql)->row_array();
if($PGConfig['status']!=1){exit();}
$PGConfig = json_decode($PGConfig['config_string'], true);
$RedirectURL = $site['host'] . '/lobby/pg';
$LoginGameURL = $PGConfig['api_domain'] . '/v1/Login/LoginGame';

$client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
        'Content-Type'   => 'application/x-www-form-urlencoded'
    ]
]);

$arr['form_params'] = array(
    'secret_key'     => $PGConfig['secret_key'],
    'operator_token'  => $PGConfig['operator_token'],
    'player_session'   => $_SESSION['member_login'],
    'operator_player_session' => $_SESSION['member_login'],
    'player_name' => $_SESSION['member_no'],
    'currency' => 'THB',
    'reminder_time' => round(microtime(true) * 1000),
    'nickname' =>$_SESSION['username']
);

$res = $client->request('POST', $LoginGameURL, $arr);  //<<<<<<<<<<<<<<<
$res = json_decode($res->getBody()->getContents(), true);

$mobile = 'false';
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
    $mobile = 'true';
}

$listGames = $conn->query("SELECT * FROM pg_list_games wHERE status=1 ORDER BY hit_count DESC, game_name_eng")->result_array();

?>
<html lang="th">

<head>
    <title><?=$site['brand_name']?> - PG Soft</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/pg/assets/css/style.css' ?>">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/pg/assets/css/hover.css' ?>">
    <link rel="stylesheet" href="/lobby/assets/css/style.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <style type="text/css">
        .bgimg {
            background-image: url('/lobby/pg/assets/img/s1.png');
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
                    <a href="/"> 
                        <img src="<?=$site['host'].'/assets/images/common/logo.png' ?>" style="width:190px;">
                    </a>
                </div>
                <div class="logo-right roundedcorners">
                    <a href="#" onclick="getMemberWallet(0)" style="text-decoration: none;">
                        <p id="credit-text"><i class="las la-sync-alt"></i>เครดิต</p>
                        <p id="credit-numb"><?php echo number_format($memberWallet['main_wallet'], 2) ?></p>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </header>

    <div class="container"> 
        <a href="<?= $site['host'] ?>/lobby">
            <img src="https://services.12pay.org/pg-icon/logo/PGSOFT_Logo_Secondary_Reserved.png" style="width: 30%;">
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="projects" style="position: relative; height: 8858.81px;">
                    <div class="row">
                        <?php
                        foreach ($listGames as $key => $value) {
                            $imgURL = "https://services.12pay.org/pg-icon/" . $value['id'] . "R.png";
                            $launch_url = $PGConfig['launch_url'];
                            $launch_url = str_replace('{game_code}', $value['id'], $launch_url);
                            $launch_url = str_replace('{0}', 'th', $launch_url);   // language
                            $launch_url = str_replace('{1}', '1', $launch_url);     // bet_type
                            $launch_url = str_replace('{2}', $PGConfig['operator_token'], $launch_url);
                            if ($type == 1) {
                                $launch_url = str_replace('{3}', $member_login, $launch_url);
                            } else { 
                                $launch_url = str_replace('{3}', $member_login, $launch_url);
                            }
                            $launch_url .= '&lt=1';
                            echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item" style="position: absolute; left: 0px; top: 0px;">';
                            echo '<a onclick="HitCount(' . $value['id'] . ')" class="game-item" href="' . $launch_url . '" target="_blank">';
                            echo '<img src="' . $imgURL . '" alt="" class="img-fluid game-img" style="width: 92%">';
                            echo '</a>';
                            echo '<div class="middle">';
                            echo '<a onclick="HitCount(' . $value['id'] . ')" href="' . $launch_url . '" target="_blank" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
                            echo '</div>';
                            echo '<div class="game-title-d text-center">';
                            echo '<a onclick="HitCount(' . $value['id'] . ')" href="' . $launch_url . '" target="_blank" class="game-title">' . $value['game_name_eng'] .  '</a>'; // '<BR>' . $value['game_name_eng'] .
                            echo '</div>';
                            echo '</div>';
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <BR><BR><BR>
    <footer class="footer">
        <?php
        include_once '../footer.php';
        ?>
    </footer> 
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script src="<?=$site['host'] . '/lobby/pg/assets/js/isotope.min.js' ?>"></script>
    <script src="<?=$site['host'] . '/lobby/pg/assets/js/script.js' ?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script> 

    <script type="text/javascript">
        function HitCount(game_id) { 
            $.ajax({
                type: "POST",
                url: "/lobby/pg/hit-count.php",
                dataType: "json",
                data: {
                    'game_id': game_id
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

        $('.container').imagesLoaded(function() { 
            var $projects = $('.projects');
            $projects.isotope({
                itemSelector: '.item',
                layoutMode: 'fitRows'
            });
        });

        $(document).ready(function() { 
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