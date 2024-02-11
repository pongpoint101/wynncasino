<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

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

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];
// $memberUserName = $_SESSION['member_username'];

$res = $db->query("SELECT 1 FROM members WHERE status<>1 AND id=" . $memberNo);
if ($res->numRows() > 0) {
    return;
}
$productType = $resLobby['product_type'];
$provider_code = $resLobby['provider_code'];
require ROOT . '/core/promotions/lobby-function.php';

$memberWallet = $db->query("SELECT * FROM member_wallet WHERE member_no=?", $memberNo)->fetchArray();
$sql = "SELECT * FROM m_providers WHERE provider_code='auto'";
$ProviderConfig = $db->query($sql)->fetchArray();
$ProviderConfig = json_decode($ProviderConfig['config_string'], true);
$apiUrlGetGame = $ProviderConfig['get_game_url'] . $resLobby['provider_id']; //  Platform ID
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
$listnameshortshow=[];
foreach ($response['data'] as $key => $value) {
    $gameAvailable = true;
    if ($value['gameCategory'] != $categoryID) {
        continue;
    }

    foreach ($res as $k => $v) {
        if ($value['gameId'] == $v['game_id'] && @$v['isActive'] == 1) {
            $gameAvailable = false;
        }
    }
    if ($gameAvailable) {
        continue;
    }

    $ImgServer = '';
    if ($resLobby['img_path'] != '' && !is_null($resLobby['img_path'])) {
        if ($platformId == 21) {
            $ImgServer = $ProviderConfig['image_server'] . '/' . $resLobby['img_path'] . '/' . $value['gameCode'] . 'R.png';
        } else {
            $ImgServer = $ProviderConfig['image_server'] . '/' . $resLobby['img_path'] . '/' . $value['gameCode'] . '.png';
        }
    } else if (isset($value['bannerUrl'])) {
        $ImgServer = $value['bannerUrl'];
    }
    if ($ImgServer == '') {
        continue;
    }

    $bolFlag = false;
    if ($platformId == 56) {
        switch ($value['gameId']) {
            case 2001:
            case 1992:
            case 1997:
            case 2019:
            case 2028:
                $bolFlag = true;
                break;

            default:
                # code...
                break;
        }
        if ($bolFlag) {
            continue;
        }
    }
    $GameList[$i]['gameId'] = $value['gameId'];
    if (!array_key_exists('gameName', $value) && array_key_exists('name', $value)) {
        $value['gameName'] = $value['name'];
    }
    $GameList[$i]['gameName'] = $value['gameName'];
    $GameList[$i]['gameCode'] = $value['gameCode'];
    $GameList[$i]['gameIcon'] = $ImgServer;
    if(sizeof($listnameshortshow)<=250){
       $listnameshortshow[$platformId.'index'.$value['gameCode']]=strip_tags($GameList[$i]['gameName']);
    }
    if ($db->query("SELECT * FROM autosystem_platform_details WHERE platform_id=? AND game_id=? AND game_code=?", $platformId, $value['gameId'], $value['gameCode'])->numRows() <= 0) {
        $resLobbyCtrl = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND product_type=?", $platformId, $categoryID);
        if ($resLobbyCtrl->numRows() > 0) {
            $resLobbyCtrl = $resLobbyCtrl->fetchArray();
            $sql = "INSERT INTO autosystem_platform_details (platform_id,platform_name,game_id,game_code,game_name,game_category,game_category_name) VALUES (?,?,?,?,?,?,?)";
            $db->query($sql, $platformId, $resLobbyCtrl['provider_name'], $value['gameId'], $value['gameCode'], $value['gameName'], $resLobbyCtrl['product_type'], getCategoryName($resLobbyCtrl['product_type']));
        }
    }
    $i++;
}

$dt = array();
$dt = array_column($GameList, 'gameCode');
array_multisort($dt, SORT_DESC, $GameList);

// Create User before launch game
$clientIP = getIP();
if (strlen($clientIP) > 15) {
    $clientIP = '127.0.0.1';
}
$arr['body'] = json_encode([
    'userName' => $site['site_id'] . $memberNo,
    'name' => $site['site_id'] . $memberNo,
    'registeredIp' => $clientIP,
]);
// $response = $client->request('POST', $apiUrlCreateUser, $arr);  //<<<<<<<<<<<<<<<
// $response = json_decode($response->getBody()->getContents(), true);

?>
<html lang="th">

<head>
    <title><?= $site['brand_name'] ?> - <?= $resLobby['provider_name'] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=GetFullDomain(). '/lobby/auto/assets/css/style.css' ?>">
    <link rel="stylesheet" href="<?=GetFullDomain(). '/lobby/auto/assets/css/hover.css' ?>">
    <link rel="stylesheet" href="<?=GetFullDomain()?>/lobby/assets/css/style.min.css?v=<?=filemtime("../assets/css/style.min.css")?>">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
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
    <div class="container">
         <div class="row mb-2 align-items-end">  
                       <?php 
                       $showtext='ค้นหาชื่อเกมส์';
                       if(@sizeof($listnameshortshow)>0){
                        $randomeshowgame=array_intersect_key($listnameshortshow, array_flip(array_rand($listnameshortshow, 3)));
                        $showtext='';
                        if(isset($_COOKIE['1gameselect'.$platformId])){
                            $showtext.='เช่น '.$_COOKIE['1gameselect'.$platformId];
                        }
                        if(isset($_COOKIE['2gameselect'.$platformId])){
                            if($showtext==''){
                                $showtext.='เช่น '.$_COOKIE['2gameselect'.$platformId]; 
                            }else{
                                $showtext.=','.$_COOKIE['2gameselect'.$platformId];
                            }
                         }
                        if($showtext==''){
                            $showtext='เช่น '.implode(" , ",$randomeshowgame);
                        }else{
                            $showtext.=','.implode(" , ",$randomeshowgame);
                        } 
                       }
                       ?>
                      <div class="col-sm-12 col-lg-3">
                        <a href="/lobby">
                            <img src="https://services.12pay.org/<?= $resLobby['img_logo_path'] ?>/<?= $resLobby['img_logo'] ?>" style="width: 100%;">
                        </a> 
                      </div>
                      <div class="col-lg-7 mx-auto"> 
                        
                        <div id="autocomplete">
                         <div class="input-group border rounded-pill p-1"> 
                             <input type="text" placeholder="<?=$showtext?>" aria-describedby="button-addon3" autofocus class="form-control bg-none border-0  input_searchgames autocomplete-input"   />
                             <ul class="autocomplete-result-list"></ul>    
                             <div class="input-group-append border-0">
                                <button id="button-addon3" type="button" class="btn btn-link text-success"><i class="fa fa-search"></i></button>
                             </div> 
                         </div>
                        </div>

                      </div>
          </div>  
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="projects" style="position: relative; height: 8858.81px;">
                    <div class="row">
                        <?php
                        if (@sizeof($GameList) > 0) {
                            foreach ($GameList as $key => $value) {

                                // Encryption
                                $sParam = "'" . $platformId . "','" . $value['gameCode'] . "'";

                                echo '<div class="col-4 col-sm-4 col-md-3 col-lg-3 item" style="position: absolute; left: 0px; top: 0px;">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" class="game-item active" style="cursor: pointer;">'; 
                                echo '<div class="loader"></div> <img  src="' . $value['gameIcon'] . '" alt="" class="img-fluid game-img" style="width: 92%">'; 
                                echo '</a>';
                                echo '<div class="middle">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" style="cursor: pointer;" class="play-joker"><i class="lar la-play-circle"></i><b>เล่นเกมส์</b></a>';
                                echo '</div>';
                                echo '<div class="game-title-d text-center">';
                                echo '<a onclick="SelectGame(' . $sParam . ')" style="cursor: pointer;" class="game-title">' . $value['gameName'] .  '</a>';
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
    <script src="<?=GetFullDomain(). '/lobby/auto/assets/js/isotope.min.js' ?>"></script>
    <script src="<?=GetFullDomain(). '/lobby/auto/assets/js/script.js' ?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@trevoreyre/autocomplete-js"></script>

    <script type="text/javascript">
         var basegameshowlist=<?=(@sizeof($listnameshortshow)>0?json_encode($listnameshortshow):[])?>;
         var placeholder_earchgames=''; 
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

        function SelectGame(platform_id, game_code) {
            let winOpen = window; 
            var gamename=basegameshowlist[platform_id+'index'+game_code];
            var lastkey1="1gameselect"+platform_id; var lastkey2="2gameselect"+platform_id;
            if (getCookie(lastkey1) == ""&&getCookie(lastkey2) == "") { 
                setCookie(lastkey1,gamename,86400000000000000000); 
                $('.input_searchgames').attr("placeholder",'เช่น '+getCookie(lastkey1)+','+placeholder_earchgames);
            }else if(getCookie(lastkey1) != ""&&getCookie(lastkey2) == ""){
                setCookie(lastkey2,getCookie(lastkey1),86400000000000000000);
                setCookie(lastkey1,gamename,86400000000000000000); 
                $('.input_searchgames').attr("placeholder",'เช่น '+getCookie(lastkey1)+','+getCookie(lastkey2)+','+placeholder_earchgames);
            }else if(getCookie(lastkey1) != ""&&getCookie(lastkey2) != ""){
                setCookie(lastkey2,getCookie(lastkey1),86400000000000000000);
                setCookie(lastkey1,gamename,86400000000000000000); 
                $('.input_searchgames').attr("placeholder",'เช่น '+getCookie(lastkey1)+','+getCookie(lastkey2)+','+placeholder_earchgames);
            }  
            $.ajax({
                type: "POST",
                url: "/lobby/auto/authen.php",
                dataType: "json",
                data: {
                    'platform_id': platform_id,
                    'game_code': game_code,
                    'product_type': <?php echo $_GET['category'] ?>,
                }
            }).done(function(data) {
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

        // $('.container').imagesLoaded({background: true},function() {
        //     // console.log("Images Loaded");
        //     var $projects = $('.projects');
        //     $projects.isotope({
        //         itemSelector: '.item',
        //         layoutMode: 'fitRows'
        //     });
        // }); 
       $(function(){  
            window['gameshowlist'] =Object.values(basegameshowlist);
            placeholder_earchgames=$('.input_searchgames').attr('placeholder').replace('เช่น ',''); 
            new Autocomplete('#autocomplete', {
            search: input => {
            if (input.length < 1) { return [] }
            return gameshowlist.filter(country => {
            return country.toLowerCase()
                .startsWith(input.toLowerCase());
            });
            }
            ,getResultValue: result => result
            ,onSubmit: result => { 
                $qsRegexisotope = new RegExp(result, 'gi');
                $gridlayout.isotope();
                }
        });

        var $grid = $('.projects').isotope({
          itemSelector: '.game-item',
          layoutMode: 'fitRows'
        });
        // layout Isotope after each image loads
        $grid.imagesLoaded().progress( function(imgLoad, image) {
        //     console.log('imgLoad',imgLoad);console.log('image',image);
        //    console.log('image.img.parentNode.className',image.img.parentNode.className);
           image.img.parentNode.className='game-item';
          $grid.isotope('layout');
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