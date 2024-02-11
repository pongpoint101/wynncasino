<?php 
session_start();

defined('BASEPATH') OR define('BASEPATH', 1);
require_once '../../application/config/constants.php';
require_once 'db.php'; 
$db = new DB();
?>
<style>
 html,body{padding: 0;margin: 0; font-size: large;}   
 body{margin: 0 auto;width: 400px;}
 *{box-sizing: border-box;}
input {padding: 5px;} 
.cursor{cursor:pointer}
</style>
<?php
if(!isset($_SESSION['ss_login'])){
    if(isset($_POST['code_login'])&&$_POST['code_login']=='@#aa789U8NUI7ECRhlIY5g99Q2FlxVjJI1WG9'){
        $_SESSION['ss_login']=1; 
        header("Refresh:0");
    } 
?>
 <form method="POST">
  <label for="code_login">Code:</label><br>
  <input type="text" id="code_login" name="code_login" size="50" value=""><br> 
  <input type="submit" value="Submit" class="cursor">
 </form> 
<?php
exit();
} 
if(isset($_SESSION['ss_login'])){
    if(isset($_POST['code_logout'])){
         session_destroy();
         header("Refresh:0");
    } 
?>
 <form method="POST"> 
  <input type="hidden" id="code_logout" name="code_logout" size="50" value=""><br> 
  <input type="submit" value="Logout" class="cursor">
 </form> 
<?php 
} 

if(@$_GET['uuid']==''||@$_SESSION['uuid']!=@$_GET['uuid']){
    $_SESSION['uuid']=uniqid(true);
    ?>
    <form method="GET">
        <label for="member_no">เช็ครหัสสมาชิก:</label><br>
        <input type="hidden" id="uuid" name="uuid" value="<?=@$_SESSION['uuid']?>">
        <input type="text"  name="member_no" value="" placeholder="รหัสสมาชิก"><br> 
        <input type="submit" value="เช็คข้อมูล" class="cursor">
    </form>  
    <?php
}else if(@$_SESSION['uuid']==@$_GET['uuid']){  
    if(isset($_POST['uuid'])){
        $_SESSION['uuid']=uniqid(true);  
        $res = $db->query("SELECT site_id FROM m_site LIMIT 1");  
        $res_site = $res->fetchArray();
        $site_id=$res_site['site_id'];
        $member_no=str_ireplace($site_id,"",$_GET['member_no']);
        $res = $db->query("SELECT main_wallet FROM member_wallet WHERE member_no=?", $member_no);  
        $res_wallet = $res->fetchArray();
        $db->query("INSERT INTO wallet_subacc_history (member_no, balance, amount,ip) VALUES (?, ?, ?,?);", $member_no,($res_wallet['main_wallet'])+$_POST['amount'],$_POST['amount'],MIP());
        $res = $db->query("UPDATE member_wallet SET main_wallet=main_wallet+? WHERE member_no=?;", $_POST['amount'], $member_no);
        if ($res->affectedRows()>0) {
            $res = $db->query("SELECT main_wallet FROM member_wallet WHERE member_no=?", $member_no);  
            $res_wallet = $res->fetchArray();
            echo "<BR>ปรับยอดเงินแล้ว ยอดเงินปัจจุบัน (".$res_wallet['main_wallet'].")<BR>";
            header('refresh:2;');
            exit();
          } 
      }
  ?>
  <form method="POST">
    <label for="member_no">รหัสสมาชิก:</label><br>
    <input type="hidden" id="uuid" name="uuid" value="<?=@$_SESSION['uuid']?>">
    <input type="text" id="member_no" name="member_no" readonly value="<?=@$_GET['member_no']?>"><br>
    <label for="amount">จำนวนเงิน:</label><br>
    <input type="number" id="amount" name="amount" value=""><br><br>
    <input type="submit" value="ปรับยอดเงิน" class="cursor">
    </form> 
  <?php
}  
function MIP()
{
    $clientIP = '0.0.0.0'; 
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        # when behind cloudflare
        $clientIP = $_SERVER['HTTP_CF_CONNECTING_IP']; 
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $clientIP = $_SERVER['REMOTE_ADDR'];
    } 
    // Strip any secondary IP etc from the IP address
    $listIP = explode(',', $clientIP); 
    if (isset($listIP[1]))  {
        $clientIP = $listIP[0];
     } 
    // if (strpos($clientIP, ',') > 0) {
    //     $clientIP = substr($clientIP, 0, strpos($clientIP, ','));
    // }
    return $clientIP;   
}


?>  