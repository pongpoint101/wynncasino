<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';
// header("Content-type: application/json; charset=utf-8");

function getCategoryName($CateID)
{
    $CateName = '';
    switch ($CateID) {
        case 1:
            $CateName = 'Sportsbook';
            break;

        case 2:
            $CateName = 'Live Casino';
            break;

        case 3:
            $CateName = 'Slot';
            break;

        case 4:
            $CateName = 'Fishing';
            break;

        case 5:
            $CateName = 'Skill/Table/Arcade';
            break;

        case 6:
            $CateName = 'ESports';
            break;

        case 7:
            $CateName = 'Lotto';
            break;

        case 8:
            $CateName = 'Crypto';
            break;

        default:
            $CateName = 'N/A';
            break;
    }
    return $CateName;
}

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
    echo "Error : 1007<BR>Message : Provider under maintenance<BR>";
    header('refresh:3;url=/lobby');
    exit();
}

$resLobby = $res->fetchArray();
$lobbyURL = $resLobby['lobby_url'];

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
$memberUsername = $site['site_id'] . $memberNo;

$res = $db->query("SELECT 1 FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
    return;
}
$productType = $resLobby['product_type'];
$provider_code = $resLobby['provider_code'];
require ROOT . '/core/promotions/lobby-function.php';

$mobile = 0;
$detecMob = new Mobile_Detect();
if ($detecMob->isMobile()) {
    $mobile = 1;
}

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$qmConfig = getProviderConfig('rt', 66, 1);

$headers = [
    'exceptions'       => false,
    'verify'           => false,
    'headers' => [
        'Content-Type'   => 'application/json',
        'X-QM-ClientId' => $qmConfig['client_id'],
        'X-QM-ClientSecret' => $qmConfig['client_secret'],
    ]
];

$client = new GuzzleHttp\Client($headers);
// Kick exist player's session
$targetURL = $qmConfig['api_domain'] . "/api/player/deauthorize";
$arr['body'] = json_encode([
    'userid' => $memberUsername
]);
try {
    $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
} catch (Exception $e) {
    file_put_contents('rt-index.log', date('Y-m-d H:i:s') . " Error : " . $e->getMessage() . PHP_EOL, FILE_APPEND);
}

// Get game list
$targetURL = $qmConfig['api_domain'] . "/api/games?lang=th-TH&platformtype=$mobile";
$res = $client->request('GET', $targetURL);  //<<<<<<<<<<<<<<<
$response = json_decode($res->getBody()->getContents(), true);
// echo 'count : ' . count($response['games']);
// echo "<pre>";
// print_r($response);
// echo "</pre>";
// exit();

$gameList['games'] = array();
for ($i = 0; $i < count($response['games']); $i++) {
    $prepareArray = false;
    if ($response['games'][$i]['isactive'] != 1 || $response['games'][$i]['type'] != 0) {
        continue;
    }
    $sql = "SELECT game_id,isActive FROM qmrt_game_list ";
    $sql .= "WHERE game_id=?";
    $res = $db->query($sql, $response['games'][$i]['externalid']);
    if ($res->numRows() > 0) {
        $res = $res->fetchArray();
        if ($res['isActive'] == 1) {
            $prepareArray = true;
        }
    } else {
        $sql = "INSERT INTO qmrt_game_list (game_id,game_code,game_name,game_json) ";
        $sql .= "VALUES (?,?,?,?)";
        // echo $sql . '<br>';
        try {
            $db->query(
                $sql,
                $response['games'][$i]['externalid'],
                $response['games'][$i]['code'],
                $response['games'][$i]['name'],
                json_encode($response['games'][$i])
            );
        } catch (Exception $e) {
            // Error
        }
        $prepareArray = true;
    }
    if ($prepareArray) {
        $response['games'][$i]['iconurl'] = $site['services_url'] . "/qm/rt/" . $response['games'][$i]['code'] . '.jpg';
        $response['games'][$i]['iconurl_local'] = "http://services.12pay.local/qm/rt/" . $response['games'][$i]['code'] . '.jpg';
        $response['games'][$i]['username'] = $memberUsername;
        array_push($gameList['games'], $response['games'][$i]);
    }
}
// echo 'count : ' . count($gameList['games']);
// echo "<pre>";
// print_r($gameList);
// echo "</pre>";
// exit();
?>

<html lang="th">

<head>
    <title><?= $site['brand_name'] ?> - <?= $resLobby['provider_name'] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/auto/assets/css/style.css' ?>">
    <link rel="stylesheet" href="<?php echo $site['host'] . '/lobby/auto/assets/css/hover.css' ?>">
    <link rel="stylesheet" href="/lobby/assets/css/style.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" /> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <img src="<?= $site['services_url'] . '/' . $resLobby['img_logo_path'] . '/' . $resLobby['img_logo'] ?>" style="width: 50%;">
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="projects" style="position: relative; height: 8858.81px;">
                    <div class="row">
                        <?php
                        if (count($gameList['games']) > 0) {
                            foreach ($gameList['games'] as $key => $value) {
                                switch (true) {
                                    case $value['iconurl'] == '':
                                    case $value['type'] != 0:
                                    case $value['providercode'] != 'RT':
                                        $isRT = false;
                                        break;
                                    default:
                                        $isRT = true;
                                        break;
                                }
                                if (!$isRT) {
                                    continue;
                                }
                                $sParam = "'" . $value['username'] . "','" . $value['code'] . "','RT'";
                                echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item" style="position: absolute; left: 0px; top: 0px;">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" class="game-item" style="cursor: pointer;">';
                                echo '<img src="' . $value['iconurl'] . '" alt="" class="img-fluid game-img" style="width: 92%" loading="lazy">';
                                echo '</a>';
                                echo '<div class="middle">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" style="cursor: pointer;" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
                                echo '</div>';
                                echo '<div class="game-title-d text-center">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" style="cursor: pointer;color: #ffc107;" class="game-title">' . $value['name'] .  '</a>';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
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

        function SelectGame(username, game_code, qmselect) {
            let winOpen = window;
            $.ajax({
                type: "POST",
                url: "/lobby/auto/authen-qm.php",
                dataType: "json",
                data: {
                    'platform_id': <?= $platformId ?>,
                    'game_code': game_code,
                    'qmselect': qmselect,
                    'product_type': <?= $_GET['category'] ?>,
                    'username': username
                }
            }).done(function(data) {
                // alert(data.gameURL);
                if (data.error == 0) {
                    var newWin = winOpen.open(data.gameURL);
                    if (!newWin || newWin.closed || typeof newWin.closed == 'undefined') {
                        //POPUP BLOCKED
                        winOpen.location = data.gameURL;
                    }
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