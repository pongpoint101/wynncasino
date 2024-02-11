<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?>
<!-- ####################################################### -->
<div id="slot-games" class="tabcontent mx-auto"> 
  <div class="row">
    <?php 
      $old_channel=0;
     foreach ($promo_detail as $k => $v) { // var_dump($v);
          $pro_allow_playgame=@explode(',',$v['pro_allow_playgame']);
          if(!in_array('3',$pro_allow_playgame)||$v['pro_bonus_in_website']==3){continue;}
          if($v['pro_is_group']==2&&$old_channel==$v['channel']){continue;}
          if(in_array($v['channel'],[87,90,95])){continue;}//83,87,90,95
          $show_collapse='show';
        ?>
            
        <div class="col-md-6 col-12"> 
            <div class="card my-3 mainbox_pro">
               <?php if($v['pro_img1']!=''&&!is_null($v['pro_img1'])){
                $show_collapse='';
                ?>
                <div class="card-thumbnail">
                    <img src="<?=CDN_URI?>promotions/<?=$v['pro_img1']?>" class="img-fluid" alt="<?=htmlspecialchars($v['pro_name'])?>" data-bs-toggle="collapse" href="#collapse_id<?=$v['pro_id']?>" role="button" aria-expanded="true" aria-controls="collapse_id<?=$v['pro_id']?>" /> 
                </div>
                <div class="card-body p-1">
                <a class="btn btn-warning float-end" data-bs-toggle="collapse" href="#collapse_id<?=$v['pro_id']?>" role="button" aria-expanded="true" aria-controls="collapse_id<?=$v['pro_id']?>">อ่านเพิ่มเติม</a>
                </div>
                <?php } ?> 
                <div class="card-body collapse <?=$show_collapse?>"  id="collapse_id<?=$v['pro_id']?>">
                    <h3 class="card-title"><?=$v['pro_name']?> </h3>
                        <!-- <?php if(!in_array($v['channel'],[83,101])){?>
                       <table class="table table-sm table-dark table-hover">
                            <tbody>
                                <tr class="tr-bor">
                                    <td class="t-tt">ฝากขั้นต่ำ</td>
                                    <td class="t-r">1</td>
                                    <td class="t-c">บาท</td>
                                </tr>
                                <?php 
                                if($v['pro_bonus_max']==-1){$v['pro_bonus_max']='ไม่จำกัด';}
                                if($v['pro_turnover_type']==1){?>
                                <tr class="tr-bor">
                                    <td class="t-tt">ยอดวางเดิมพัน</td>
                                    <td class="t-r"><?=$v['pro_turnover_amount']?></td>
                                    <td class="t-c">ยอดเล่น</td>
                                </tr>
                                <?php } ?>
                                <?php if($v['pro_turnover_type']==2){?>
                                <tr class="tr-bor">
                                    <td class="t-tt">ยอดวางเดิมพัน</td>
                                    <td class="t-r"><?=$v['pro_turnover_amount']?></td>
                                    <td class="t-c">ยอดเล่นเท่า</td>
                                </tr>
                                <?php } ?>
                                <?php if($v['pro_turnover_type']==3){?>
                                <tr class="tr-bor">
                                    <td class="t-tt">ยอดวางเดิมพัน</td>
                                    <td class="t-r"><?=$v['pro_turnover_amount']?></td>
                                    <td class="t-c">ยอดเครดิต</td>
                                </tr>
                                <?php } ?>
                                <?php if($v['pro_turnover_type']==4){?>
                                <tr class="tr-bor">
                                    <td class="t-tt">ยอดวางเดิมพัน</td>
                                    <td class="t-r"><?=$v['pro_turnover_amount']?></td>
                                    <td class="t-c">เครดิตจำนวนเท่า</td>
                                </tr>
                                <?php } ?>
                                <tr class="tr-bor">
                                    <td class="t-tt">โบนัสสูงสุด</td>
                                    <td class="t-r"><?=$v['pro_bonus_max']?></td>
                                    <td class="t-c">บาท</td>
                                </tr>
                            </tbody>
                         </table>
                      <?php } ?> -->
                      
                    <p class="card-text"><?=$v['pro_description']?></p>
                    <?php if(isset($_SESSION['member_no'])&&$v['pro_bonus_in_website']==1){ ?>
                    <?php if(!in_array($v['channel'],[83])){ ?>
                    <a href="javascript:void(0)" class="btn btn-success position-relative start-50 translate-middle" v-on:click="get_bonus(<?=$v['pro_id']?>)">คลิกรับโปร</a>
                    <?php } }else if(isset($_SESSION['member_no'])&&($v['pro_bonus_in_website']==2||$v['pro_bonus_in_website']==4)){
                        ?>
                        <a href="<?php echo $WebSites['line_at_url'] ?>" target="_blank" class="mt-3 btn btn-success position-relative start-50 translate-middle" >ติดต่อ Admin </a>
                        <?php
                    } ?> 
                </div>
            </div>
         </div>
        <!-- <div class="promotion-card">
         <img src="<?=CDN_URI?>promotions/<?=$v['pro_img1']?>" alt="promotion">
          <div class="promotion-details">
            <div>
                <h5>
                    <?php 
                  if(in_array($v['channel'],[10,11,12])){
                   echo $v['pro_name_short'];
                  }else{
                   echo $v['pro_name']; 
                  }
                ?></h5> 
                <p class="morning-promotion"></p>
            </div>
            <div>
                <p class="night-promotion">   
                <?=$v['pro_description']?>
                </p> 
                <?php if(isset($_SESSION['member_no'])&&$v['pro_bonus_in_website']==1){ ?>
                <a href="javascript:void(0)" v-on:click="get_bonus(<?=$v['pro_id']?>)">คลิก</a>
                <?php }?>   
            </div>
          </div>
        </div> -->
        <?php
        $old_channel=$v['channel'];
     }
    ?>   
    <?php include('pro_all_use.php');?>
  </div>
</div>