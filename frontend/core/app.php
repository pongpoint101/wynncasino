<?php 
 if (session_status() == PHP_SESSION_NONE) {
   session_start();
  }   
 date_default_timezone_set("Asia/Bangkok"); 

 defined('ROOT')  OR define('ROOT',  realpath(__DIR__.DIRECTORY_SEPARATOR."..")); 
 defined('ROOT_APP')  OR define('ROOT_APP',  ROOT.DIRECTORY_SEPARATOR.'public'); 

 require_once ROOT.'/private/vendor/autoload.php';  
 require_once ROOT.'/core/constants.php';  
 require_once ROOT.'/core/function.php';
 require_once ROOT . '/core/fn_http.php'; 
 require_once ROOT . '/core/cache.php';
 require_once ROOT . '/core/RateLimiter.php';
  if(APP_ENV=='test'){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
   }else{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    $limiter = new RateLimiter($cache, 'M_', true);
    $limiterns = $limiter->hit('requestmax:' . getIP(true), 40, 10); // เรียกได้ 40 ครั้งใน 10 วินาที
    if ($limiterns['overlimit']) {exit(0);} 

  }
 require_once ROOT.'/core/db.php';
 require_once ROOT . '/core/db_fn.php'; 

defined('CDN_URI') OR define('CDN_URI','http://'.SOURCE_CDN.'.'.get_domain().'/');

?>