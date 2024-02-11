<?php
 if (session_status() == PHP_SESSION_NONE) {
     session_start();
  }   
 session_destroy(); 
 header("location:https://{$_SERVER['HTTP_HOST']}/index.php");
?>