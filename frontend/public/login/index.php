<?php
require '../bootstart.php'; 
if(!isset($_GET['openExternalBrowser'])){
    $mob_redirect= new Mobile_Detect;
    if ($mob_redirect->isMobile()) {
        $redrurl=@explode("?", $_SERVER['REQUEST_URI'])[1];
        $redrurl=(!$redrurl?GetFullDomain().$_SERVER['REQUEST_URI'].'?openExternalBrowser=1':GetFullDomain().$_SERVER['REQUEST_URI'].'&openExternalBrowser=1');
        header( "location: $redrurl" );
        exit(0);
    } 
 }  
if (isset($_SESSION['member_no'])) {
    header('location:'.GetFullDomain().'/index.php');
    exit();
} 
if(@$_SESSION['member_no'] != NULL){
    header( "location: ".GetFullDomain().'/home');
    exit(0);
} 
$sitetitle='เข้าสู่ระบบ';
$assets_head='<link rel="stylesheet" href="'.GetFullDomain().'/assets/css/register_page.css?v=3">';
include_once ROOT_APP.'/componentx/header.php';
?> 
    <section class="main-section" id="app" v-cloak>
        <div class="blue-box mx-auto">
            <h1>เข้าสู่ระบบ</h1> 
            <form id="login_form" method="POST" autocomplete="on"  @submit="checkForm"> 
                <div class="long-field"> 
                   <div></div> 
                    <input type="tel" name="phoneLogin" placeholder="เบอร์โทรศัพท์*" v-model="loginDetails.username" v-on:keyup="keyLogin" autocomplete="username">
                </div> 
                <div class="long-field"> 
                    <div></div> 
                    <input name="passwordLogin" type="password" placeholder="รหัสผ่าน*" v-model="loginDetails.password" v-on:keyup="keyLogin" autocomplete="current-password">
                </div>
                <button type="submit" ref="btn_doLogin" :disabled="loginDetails.issubmit" >เข้าสู่ระบบ</button>   
            </form>
        </div>
    </section>
 <?php  
$assets_footer='<script src="'.GetFullDomain().'/assets/js/navbar_control.js"></script>';

include_once ROOT_APP.'/componentx/footer.php';
?>