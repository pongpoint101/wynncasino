<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');

$WebSites=GetWebSites();
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 
      <?php 
        if (isset($sitetitle)) {
            echo $sitetitle;
        } 
        echo ' - '.get_domain();
        echo ' '.$WebSites['title'];
        ?>
    </title>
    <?php
     require ROOT.'/core/headermatadata.php';
    ?>

    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/style.css?v=18" />
    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/font_style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Local CSS -->
    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/common_style.css?v=42">
    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/minimal.css">
    <!-- <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/navbar.css"> -->

    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/slick-slider.css" />
    <link rel="stylesheet" href="<?=GetFullDomain()?>/assets/css/siteground-optimizer.css?v=3" />
    <?php 
     if (isset($assets_head)) {    
        echo $assets_head;
     }
    ?>
    <style>
        [v-cloak] {display: none;}
    </style>
    <script>
       <?php
       	if(@$_SESSION['member_no']>0){
          ?>
          window.auth=true;
          <?php
        }else{
           ?>
          window.auth=false;
         <?php
        }
       ?>
    </script>
    

</head>
<body class="bg-black">
<?php
  if(isset($_SESSION['member_no'])){
    include_once ROOT_APP.'/componentx/tpl_headhome.php';
  }else{
    include_once ROOT_APP.'/componentx/tpl_headmain.php';  
  }
?>