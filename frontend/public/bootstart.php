<?php
defined('ROOT')  OR define('ROOT', realpath(__DIR__."/.."));   
require_once ROOT.'/core/app.php'; 
if (isset($_GET['aff']) && strlen($_GET['aff'] > 0)) { 
    setcookie("aff",$_GET['aff'],time()+31556926 ,'/'); 
} 
?>