<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';


if (!isset($_GET['token'])) {
    exit();
}

$pgToken = $_GET['token'];
$gameURL = $_SESSION[$pgToken];
if (strlen($gameURL) < 5) {
    exit();
}
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

<body class="body">
    <div class="container-fluid px-0 h-100 " id="PlayerWarper">
        <div class="row m-0 h-100">
            <div class="d-flex align-content-center flex-wrap text-center w-100" style="background: #000;">
                <iframe frameborder="no" scrolling="no" style="width: 100%;height: 100%;" allowfullscreen="true" src="<?php echo $gameURL ?>"></iframe>
            </div>
        </div>
    </div>
</body>

</html>