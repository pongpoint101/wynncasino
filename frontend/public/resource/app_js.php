<?php
defined('ROOTJS')  OR define('ROOTJS', realpath(__DIR__."/..")); 
require_once ROOTJS.'/bootstart.php';
?> 
<?php 
   switch (APP_ENV) {
         case 'production':
               ?> 
               <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.runtime.min.js" integrity="sha256-QSjTKFyl+0AHjs8OSm1238CRMQWixK8z7ymX/81u7i0=" crossorigin="anonymous"></script>   
               <?php
               break;  
        case 'test':
               ?> 
               <!-- Optional JavaScript -->
               <!-- jQuery first, then Popper.js, then Bootstrap JS -->
               <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.js" integrity="sha256-ufGElb3TnOtzl5E4c/qQnZFGP+FYEZj5kbSEdJNrw0A=" crossorigin="anonymous"></script> 
               <?php
               break; 
   }
?>
