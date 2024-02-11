<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
$db = new DB();
$site = GetWebSites();

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
$platformId = filter_input(INPUT_GET, 'platform', FILTER_VALIDATE_INT);
if (isset($_GET['category'])) {
    $categoryID = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
} else {
    $categoryID = 3;
}
$res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND product_type=? AND status=1", $platformId, $categoryID);
if ($res->numRows() <= 0 || $platformId <= 0) {
    echo "Error : 1007<BR>Message : Game under maintenance<BR>";
    header('refresh:3;url=/lobby');
    exit();
}


$res_lobby = $res->fetchArray();

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
$memberUserName = $_SESSION['member_username'];

$res = $db->query("SELECT 1 FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
    return;
}
$productType = $res_lobby['product_type'];
$provider_code = $resLobby['provider_code'];
require ROOT . '/core/promotions/lobby-function.php';

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$sql = "SELECT * FROM m_providers WHERE provider_code='auto'";
$ProviderConfig = $db->query($sql)->fetchArray();
$ProviderConfig = json_decode($ProviderConfig['config_string'], true);
$apiUrlGetGame = $ProviderConfig['get_game_url'] . $res_lobby['provider_id']; // PG Platform ID
$apiUrlCreateUser = $ProviderConfig['create_user_url'];
$apiUrlLogin = $ProviderConfig['login_url'];
$opToken = $ProviderConfig['operator_token'];
$secretKey = $ProviderConfig['secret_key'];
$client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
        'Content-Type'   => 'application/json',
        'operator-token' => $opToken,
        'secret-key'    => $secretKey
    ]
]);

// Get game list
$response = $client->request('GET', $apiUrlGetGame);  //<<<<<<<<<<<<<<<
$response = json_decode($response->getBody()->getContents(), true);
$i = 0;

$sql = "SELECT * FROM autosystem_gamelist";
$datakey = 'autosystem_gamelist';
$res = GetDataSqlWhereAll($datakey, $sql, [], 1 * 60);

foreach ($response['data'] as $key => $value) {
    $game_avalable = true;
    if ($value['gameCategory'] != $categoryID) {
        continue;
    }

    foreach ($res as $k => $v) {
        if ($value['gameId'] == $v['game_id'] && @$v['isActive'] == 1) {
            $game_avalable = false;
        }
    }
    if ($game_avalable) {
        continue;
    }

    $ImgServer = '';
    if ($res_lobby['img_path'] != '' && !is_null($res_lobby['img_path'])) {
        $ImgServer = $ProviderConfig['image_server'] . '/' . $res_lobby['img_path'] . '/' . $value['gameCode'] . '.png';
    } else if (isset($value['bannerUrl'])) {
        $ImgServer = $value['bannerUrl'];
    }
    if ($ImgServer == '') {
        continue;
    }
    $GameList[$i]['gameId'] = $value['gameId'];
    $GameList[$i]['gameName'] = $value['gameName'];
    $GameList[$i]['gameCode'] = $value['gameCode'];
    $GameList[$i]['gameIcon'] = $ImgServer;
    $i++;
}

$dt = array();
$dt = array_column($GameList, 'gameCode');
array_multisort($dt, SORT_ASC, $GameList);

?>
<html lang="th">

<head>
    <title><?= $site['brand_name'] ?> - <?= $res_lobby['provider_name'] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/auto/assets/css/style.css' ?>">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/auto/assets/css/hover.css' ?>">
    <link rel="stylesheet" href="/lobby/assets/css/style.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <style type="text/css">
        .bgimg {
            background-image: url('/lobby/auto/assets/img/s1.png');
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
                    <a href="/home">
                        <img src="../../assets/images/common/logo.png" style="width:190px;">
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
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
    </style>
    <div class="container">
        <a href="/lobby">
            <img src="https://services.12pay.org/<?= $res_lobby['img_logo_path'] ?>/<?= $res_lobby['img_logo'] ?>" style="width: 50%;">
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="projects" style="position: relative; height: 8858.81px;">
                    <div class="row">
                        <?php
                        if (@sizeof($GameList) > 0) {
                            foreach ($GameList as $key => $value) {
                                echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item" style="position: absolute; left: 0px; top: 0px;">';
                                echo '<a onclick="LaunchGame(\'' . $memberNo . '\',\'' . $value['gameCode'] . '\')" class="game-item" style="cursor: pointer;">';
                                echo '<img src="' . $value['gameIcon'] . '" alt="" class="img-fluid game-img" style="width: 92%">';
                                echo '</a>';
                                echo '<div class="middle">';
                                echo '<a onclick="LaunchGame(\'' . $memberNo . '\',\'' . $value['gameCode'] . '\')" style="cursor: pointer;" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
                                echo '</div>';
                                echo '<div class="game-title-d text-center">';
                                echo '<a onclick="LaunchGame(\'' . $memberNo . '\',\'' . $value['gameCode'] . '\')" style="cursor: pointer;" class="game-title">' . $value['gameName'] .  '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<center><h3>ไม่พบข้อมูลเกมส์</h3></center>';
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
    <script src="<?php echo $site['host'] . '/lobby/auto/assets/js/isotope.min.js' ?>"></script>
    <script src="<?php echo $site['host'] . '/lobby/auto/assets/js/script.js' ?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        function LaunchGame(member_no, game_code) {
            $.ajax({
                type: "POST",
                url: "/lobby/auto/launch.php",
                dataType: "json",
                data: {
                    'provider': 'pgs',
                    'member_no': member_no,
                    'game_code': game_code,
                }
            }).done(function(data) {
                if (data.error == 0) {
                    window.open(data.gameURL, '_blank');
                } else {
                    Swal.fire("เกิดข้อผิดพลาด", data.gameURL, "error");
                }
            });
        }

        function getMemberWallet(interval) {
            $.ajax({
                url: '../../actions/Pullprofile.php',
                type: 'get',
                contentType: "application/json",
                dataType: "json",
                cache: false,
                success: function(data) {
                    document.getElementById("credit-numb").textContent = data.main_wallet;
                },
                error: function() {}
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
            // console.log("Images Loaded");
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