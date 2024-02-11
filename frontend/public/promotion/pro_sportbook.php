<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?> 
 <div id="sport-games" class="tabcontent mx-auto">
   <div class="row">
   <?php
   $old_pro_group_id=0;
     foreach ($promo_detail as $k => $v) {
          $pro_allow_playgame=@explode(',',$v['pro_allow_playgame']);
          if(!in_array('1',$pro_allow_playgame)||$v['pro_bonus_in_website']==3){continue;}
          if($v['pro_is_group']==2&&$old_pro_group_id==$v['pro_group_id']){continue;}
          $show_collapse='show';
        ?>
        <div class="col-md-6 col-12"> 
                        <div class="card my-3 mainbox_pro">
                         <?php if($v['pro_img1']!=''&&!is_null($v['pro_img1'])){
                             $show_collapse='';
                            ?>
                            <div class="card-thumbnail">
                                <img src="<?=CDN_URI?>promotions/<?=$v['pro_img1']?>" class="img-fluid" alt="<?=$v['pro_name']?>" data-bs-toggle="collapse" href="#collapse_id<?=$v['pro_id']?>" role="button" aria-expanded="false" aria-controls="collapse_id<?=$v['pro_id']?>"/> 
                            </div> 
                            <div class="card-body p-1">
                            <a class="btn btn-warning float-end" data-bs-toggle="collapse" href="#collapse_id<?=$v['pro_id']?>" role="button" aria-expanded="false" aria-controls="collapse_id<?=$v['pro_id']?>">อ่านเพิ่มเติม</a>
                            </div>
                            <?php } ?> 
                            <div class="card-body collapse <?=$show_collapse?>"  id="collapse_id<?=$v['pro_id']?>">
                                <h3 class="card-title"><?=$v['pro_name']?></h3>
                                <p class="card-text"><?=$v['pro_description']?></p>
                                <?php if(isset($_SESSION['member_no'])&&$v['pro_bonus_in_website']==1){ ?>
                                <a href="javascript:void(0)" class="btn btn-success position-relative start-50 translate-middle" v-on:click="get_bonus(<?=$v['pro_id']?>)">คลิกรับโปร</a> 
                                <?php }else if(isset($_SESSION['member_no'])&&($v['pro_bonus_in_website']==2||$v['pro_bonus_in_website']==4)){
                                    ?>
                                        <a href="<?php echo $WebSites['line_at_url'] ?>" target="_blank" class="mt-3 btn btn-success position-relative start-50 translate-middle" >ติดต่อ Admin </a>
                                    <?php
                                } ?>
                            </div>
                        </div>
        </div>
        <?php
        $old_pro_group_id=$v['pro_group_id'];
     }
    ?>        
 
 <?php include('pro_all_use.php');?>
 </div>
</div>