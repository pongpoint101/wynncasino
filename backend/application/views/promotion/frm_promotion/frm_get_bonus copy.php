<h4 class="mt-1">เครดิตโปรโมชั่น</h4>
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="form-group">
                <select name="remark-option" class="form-control border-primary" id="selected_pro" onchange="showpro();">
                    <?php 
                      foreach ($pro_list as $k => $v) {
                          if ($v['pro_bonus_in_website']!=2) { continue;}
                      ?>
                       <option value="<?=($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])?>"><?=($v['pro_is_group']==1?$v['pro_name']:$v['pro_group_name'])?></option>
                      <?php
                      }
                    ?> 
                </select>
            </div>
        </div>
    </div>
    <div class="row">  
<?php
 
  foreach ($pro_list as $k => $v) {
       if ($v['pro_bonus_in_website']!=2) { continue;}
       $subdata=[];
       if (($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])==19) {$subdata=$reward_daily; }
       if (($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])==23) {$subdata=$reward_m_aff; }
       if (($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])==24) {$subdata=$reward_m_comm; }
       if($v['pro_is_group']==1||in_array($v['pro_group_id'],[112])){
      ?>
      <div class="col-xl-4 col-md-6 col-12 prolist <?=($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])?>">
        <div class="card profile-card-with-cover">
            <div class="card-content card-deck text-center">
                <div class="card box-shadow">
                    <div class="card-header pb-0">
                        <h5 class="font-weight-bold"><?=($v['pro_is_group']==1?$v['pro_name']:$v['pro_group_name'])?></h5>
                    </div>
                    <div class="card-body">  
                        <p class="card-text"><?=$v['pro_description']?></p>  
                        <button data-pro_id='<?=$v['pro_id']?>' data-subpro_id='<?=$v['pro_id']?>' type="button" class="btn btn-lg btn-block btn-info btn_get_bonus">กดรับเครดิต</button>
                    </div>
                </div>
            </div>
        </div>
        </div> 
      <?php 
     }
      if(@sizeof($subdata['result_array'])>0){
       foreach ($subdata['result_array'] as $k2 => $v2) { 
        ?>
        <div class="col-xl-4 col-md-6 col-12 prolist <?=($v['pro_is_group']==1?$v['pro_id']:$v['pro_group_id'])?>">
          <div class="card profile-card-with-cover">
              <div class="card-content card-deck text-center">
                  <div class="card box-shadow">
                      <div class="card-header pb-0">
                          <h5 class="font-weight-bold"><?=($v['pro_is_group']==1?$v['pro_name']:$v['pro_group_name'])?> ลำดับที่ <?=$v2['rating']?> - <?=$v2['amount']?></h5>
                      </div>
                      <div class="card-body">  
                          <!-- <p class="card-text"><?=$v['pro_description']?></p>   -->
                          <button data-pro_id='<?=$v['pro_id']?>' data-subpro_id='<?=$v2['id']?>' type="button" class="btn btn-lg btn-block btn-info btn_get_bonus" >กดรับเครดิต</button>
                      </div>
                  </div>
              </div>
          </div>
          </div> 
        <?php    
       }
    }
  } 
?>

</div>
<script>
    showpro();
    function showpro(){
        $.when( 
            $(".prolist").hide().promise()  
        ).done(function() {
            $(".prolist."+$('#selected_pro').val()).show();
            // console.log('all done');
        });
    }
 
</script>