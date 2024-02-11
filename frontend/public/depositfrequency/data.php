<?php 
defined('ROOT') OR exit('No direct script access allowed'); 
?>
<div class="col-12">

<div class="row justify-content-center" style="margin: 0px auto;">
      <?php  
      $el_btnpro='';
      for ($i=1; $i <=5 ; $i++) { 
         $el_btnpro='';  
         $stylallowpro='';$reward_txt="วันที่ $i";  
         if ($rewardpro[$i]['status']!=0) {
            $v=$rewardpro[$i]; 
            $stylallowpro='pro_btn_red'; 
            $reward_txt='รับ '.number_format($v['reward'],0); 
            $el_btnpro="v-on:click=\"showalert('รายการฝากยังไม่ครบ','ขาดฝากประจำอีก ".($i-$countfreq)." วัน','error',function(){ }); \" role='button' tabindex='0'";   
            if ($v['status']==2) {
                $stylallowpro='pro_btn_green'; 
                $el_btnpro="v-on:click='deposit_frequency($i)' role='button' tabindex='0'";      
            }
         }else{
            if ($countfreq>=$i) {
                $stylallowpro='pro_btn_yellow';   
            }   
         }  
       ?>
     <div class="col m-1 p-0">  
         <div class="bg-color shadow border-1 text-center py-2 <?=$stylallowpro?>" <?=$el_btnpro?> style="border-radius: 4px;">
             <div class="card-body p-2">
                 <h5 class="mb-0 font-weight-normal text-color text-color">
                     <?=$i?>
                 </h5> 
                 <p class="m-0 text-color small">
                     <?=$reward_txt?>
                 </p> 
             </div>
         </div>
     </div>
     <?php
     }
    ?> 
 </div>
 <div class="row justify-content-center" style="margin: 0px auto;">
 <?php  
      for ($i=6; $i <=10 ; $i++) { 
         $el_btnpro=''; 
         $stylallowpro='';$reward_txt="วันที่ $i"; 
         if ($rewardpro[$i]['status']!=0) {
            $v=$rewardpro[$i]; 
            $stylallowpro='pro_btn_red'; 
            $reward_txt='รับ '.number_format($v['reward'],0); 
            $el_btnpro="v-on:click=\"showalert('รายการฝากยังไม่ครบ','ขาดฝากประจำอีก ".($i-$countfreq)." วัน','error',function(){ }); \" role='button' tabindex='0'";   
            if ($v['status']==2) {
                $stylallowpro='pro_btn_green';
                $el_btnpro="v-on:click='deposit_frequency($i)' role='button' tabindex='0'";      
            }
         }
       ?>
     <div class="col m-1 p-0"> 
         <div class="bg-color shadow border-1 text-center py-2 <?=$stylallowpro?>" <?=$el_btnpro?> style="border-radius: 4px;">
             <div class="card-body p-2">
                 <h5 class="mb-0 font-weight-normal text-color text-color">
                     <?=$i?>
                 </h5>
                 <p class="m-0 text-color small">
                 <?=$reward_txt?>
                 </p>
             </div>
         </div>
     </div>
     <?php
     }
    ?> 
 </div>
 <div class="row justify-content-center" style="margin: 0px auto;">
 <?php  
      for ($i=11; $i <=15 ; $i++) { 
        $el_btnpro='';   
        $stylallowpro='';$reward_txt="วันที่ $i"; 
         if ($rewardpro[$i]['status']!=0) {
            $v=$rewardpro[$i]; 
            $stylallowpro='pro_btn_red';  
            $reward_txt='รับ '.number_format($v['reward'],0); 
            $el_btnpro="v-on:click=\"showalert('รายการฝากยังไม่ครบ','ขาดฝากประจำอีก ".($i-$countfreq)." วัน','error',function(){ }); \" role='button' tabindex='0'";   
            if ($v['status']==2) {
                $stylallowpro='pro_btn_green';     
                $el_btnpro="v-on:click='deposit_frequency($i)' role='button' tabindex='0'";       
            }
         }
       ?>
     <div class="col m-1 p-0"> 
         <div class="bg-color shadow border-1 text-center py-2 <?=$stylallowpro?>" <?=$el_btnpro?> style="border-radius: 4px;">
             <div class="card-body p-2">
                 <h5 class="mb-0 font-weight-normal text-color text-color">
                     <?=$i?>
                 </h5>
                 <p class="m-0 text-color small">
                  <?=$reward_txt?>
                 </p>
             </div>
         </div>
     </div>
     <?php
     }
    ?> 
 </div>  
</div> 
<div class="col-12">
    <div class="p-2" style="color:red;background-color: white;border-radius: 5px;"> 
    <h3>เงื่อนไขกิจกรรม:</h3> 
    - ฝาก 500 ชึ้นไปครบ 3 วันรับ 200<br> 
    - ฝาก 500 ชึ้นไปครบ 7 วันรับ 500<br> 
    - ฝาก 500 ชึ้นไปครบ 10 วันรับ 1,000<br> 
    - ฝาก 500 ชึ้นไปครบ 15 วันรับ 2,000<br> 
    - เฉพาะยอดฝาก 500 ขึ้นไปที่ไม่รับโบนัสเท่านั้น<br> 
    - ทำยอด 1 เท่าถอนได้เลย ไม่มีอั้น<br> 
    - เกมส์สล็อตเท่านั้น<br>  
    </div>
</div>