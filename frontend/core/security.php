<?php
$logged_in = false; 
$member_no='';
$USER_STATUS=2;// lock
$uuid_lastaccess='';
$site = GetWebSites();
if (isset($_SESSION['member_no'])) {
    $row_auth=Auth_check($_SESSION['member_no']);
    if (isset($row_auth['status'])) {  
        $USER_STATUS= $row_auth['status']; 
        $uuid_lastaccess=$row_auth['uuid_lastaccess'];
     }
    if (isset($row_auth['ip_address'])) { 
        $_SESSION['ip_live'] = $row_auth['ip_address'];  
        $_SESSION['USER_AGENT_live']=$row_auth['user_agent'];
    } 
} 
if (!isset($_SESSION['member_no'])||$USER_STATUS!=1||$uuid_lastaccess!=$_SESSION['uuid_lastaccess']) {
    $logged_in = true; 
} elseif (empty($_SESSION['member_no'])||$USER_STATUS!=1) {
    $logged_in = true;
} 
$member_no=@$_SESSION['member_no']; 
$_SESSION['USER_STATUS']=$USER_STATUS; 

if ($logged_in||(isset($_SESSION['member_no'])&&(@$_SESSION['ip_live']!=@$_SESSION['ip']))) { 
    $is_promoclaim = isset($_POST['is_promoclaim']) ? $_POST['is_promoclaim'] : 0; 
    if($is_promoclaim == 1){
        echojs("ผิดพลาด","กรุณา Login หรือสมัครสมาชิก",1,"error");
    } else {
        header('location:'.GetFullDomain().'/logout.php');
    }
    exit(); 
}  
function logged_in(){
    global $logged_in;
    return $logged_in;
} 
?>