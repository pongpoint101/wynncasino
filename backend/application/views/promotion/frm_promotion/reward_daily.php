<?php
$text_turnover_amount=''; 
if($pro_data['row']->pro_turnover_type==1){
    $text_turnover_amount='ทำยอดเล่นจำนวนเต็ม'; 
}else if($pro_data['row']->pro_turnover_type==2){
    $text_turnover_amount='ทำยอดเล่นกี่เท่า'; 
}else if($pro_data['row']->pro_turnover_type==3){
    $text_turnover_amount='ทำเทิร์นยอดบวก'; 
}else if($pro_data['row']->pro_turnover_type==4){
    $text_turnover_amount='ทำเทิร์นจำนวนเท่า'; 
} 
?>
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="modal-form-proModalLabel">รางวัล <?=$pro_data['row']->pro_name?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body"> 
<form class="form" onSubmit="return false" method="post" enctype="multipart/form-data" id="form_promotion">
    <input type="hidden" name="pro_id" id="pro_id" value="<?=$pro_id?>">
    <input type="hidden" name="m_order" id="m_order" value="<?=$m_order?>">
    <div class="form-body">    
        <div class="form-group">
            <label for="promotionname">ชื่อรางวัล</label>
            <input type="text" id="promotionname" class="form-control" placeholder="ชื่อรางวัล" value="<?=$pro_data['row']->pro_name?>" name="pro_name" />
        </div>
        <h4 class="form-section"><i class="la la-info"></i>เงื่อนไข</h4> 

        <!-- <div class="row">
            <div class="col-md-6"> 
                <div class="form-group">
                    <label for="pro_turnover_amount"><?=$text_turnover_amount?></label>
                    <input type="number" id="pro_turnover_amount" class="form-control" placeholder="<?=$text_turnover_amount?>" value="<?=$pro_data['row']->pro_turnover_amount?>" name="pro_turnover_amount" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_withdraw_max_amount">ถอนได้สูงสุด</label>
                    <input type="number" id="pro_withdraw_max_amount" class="form-control" placeholder="ถอนได้สูงสุด" value="<?=$pro_data['row']->pro_withdraw_max_amount?>" name="pro_withdraw_max_amount" />
                </div>
            </div>
        </div> -->

        <div class="row">  
            <div class="col-md-12">

             <div class="form-group">
              <label>คำนวนถอนสูงสุด</label>
               <div class="input-group">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" name="pro_withdraw_type" class="custom-control-input" value="1" <?=(@$pro_data['row']->pro_withdraw_type==1) ? 'checked' : '';?> id="pro_withdraw_type_1" />
                    <label class="custom-control-label" for="pro_withdraw_type_1">ถอนสูงสุดระบุเครดิต</label>
                </div> 

                <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_withdraw_type" class="custom-control-input" value="2" <?=(@$pro_data['row']->pro_withdraw_type==2) ? 'checked' : '';?> id="pro_withdraw_type_2" />
                    <label class="custom-control-label" for="pro_withdraw_type_2">ถอนสูงสุดจำนวนเท่า</label>
                </div>  
              </div> 
            </div>

           </div> 
        </div>
        <div class="row">  
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_withdraw_max_amount" class="txt_pro_withdraw_max_amount">ถอนได้สูงสุด</label>
                    <input type="number" id="pro_withdraw_max_amount" class="form-control pro_withdraw_max_amount" placeholder="ถอนได้สูงสุด" value="<?=$pro_data['row']->pro_withdraw_max_amount?>" name="pro_withdraw_max_amount" />
                </div>
            </div>
        </div>
        
        <div class="row">  
            <div class="col-md-12">
                <div class="form-group">
                <label>คำนวนเทิร์นโอเวอร์</label>
                <div class="input-group">
                    <div class="d-inline-block custom-control custom-radio mr-1">
                        <input type="radio" name="pro_turnover_type" class="custom-control-input" value="1" <?=(@$pro_data['row']->pro_turnover_type==1) ? 'checked' : '';?> id="pro_turnover_type_1" />
                        <label class="custom-control-label" for="pro_turnover_type_1">ทำยอดเล่นจำนวนเต็ม</label>
                    </div>
                    <div class="d-inline-block custom-control custom-radio">
                    <input type="radio" name="pro_turnover_type" class="custom-control-input" value="2" <?=(@$pro_data['row']->pro_turnover_type==2) ? 'checked' : '';?> id="pro_turnover_type_2" />
                        <label class="custom-control-label" for="pro_turnover_type_2">ทำยอดเล่นกี่เท่า </label>
                    </div>
    
                    <div class="d-inline-block custom-control custom-radio mr-1">
                        <input type="radio" name="pro_turnover_type" class="custom-control-input" value="3" <?=(@$pro_data['row']->pro_turnover_type==3) ? 'checked' : '';?> id="pro_turnover_type_3" />
                        <label class="custom-control-label" for="pro_turnover_type_3">ทำเทิร์นยอดบวก</label>
                    </div>
                    <div class="d-inline-block custom-control custom-radio">
                        <input type="radio" name="pro_turnover_type" class="custom-control-input" value="4" <?=(@$pro_data['row']->pro_turnover_type==4) ? 'checked' : '';?>  id="pro_turnover_type_4" />
                        <label class="custom-control-label" for="pro_turnover_type_4">ทำเทิร์นยอดจำนวนเท่า</label>
                    </div>

                 </div>
             </div>
            </div>
        </div>

        <div class="row boxpro_turnover_amount" style="<?=(@$pro_data['row']->pro_deposit_type==3 ?'display:none;':'')?>"> 
                  <div class="col-md-10">
                    <div class="form-group">
                        <label for="pro_deposit_fix" class="txt_turnover_amount"><?=$text_turnover_amount?></label>
                        <input type="number" id="pro_turnover_amount" class="form-control txt_turnover_amount" placeholder="<?=$text_turnover_amount?>" value="<?=$pro_data['row']->pro_turnover_amount?>" name="pro_turnover_amount" />
                    </div>
                  </div>  
        </div>
        <h4 class="form-section">
        <svg fill="#FFF" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 54.558 54.559" xml:space="preserve">
            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
            <g id="SVGRepo_iconCarrier"> <g> <g> <path d="M27.28,3.911c-8.024,0-14.553,6.528-14.553,14.552s6.528,14.553,14.553,14.553c8.024,0,14.552-6.529,14.552-14.553 S35.304,3.911,27.28,3.911z M27.28,31.016c-6.921,0-12.553-5.631-12.553-12.553c0-6.921,5.631-12.552,12.553-12.552 c6.921,0,12.552,5.631,12.552,12.552C39.832,25.384,34.201,31.016,27.28,31.016z"/> <path d="M27.28,7.704c-0.552,0-1,0.448-1,1c0,0.552,0.448,1,1,1c4.83,0,8.758,3.929,8.758,8.759c0,0.552,0.448,1,1,1s1-0.448,1-1 C38.038,12.53,33.212,7.704,27.28,7.704z"/> <path d="M45.743,18.463C45.743,8.282,37.46,0,27.28,0C17.1,0,8.816,8.282,8.816,18.463c0,5.947,2.847,11.471,7.647,14.946 l-5.877,15.06c-0.124,0.317-0.078,0.676,0.122,0.95c0.2,0.276,0.534,0.437,0.865,0.412l6.676-0.366l4.663,4.791 c0.19,0.196,0.45,0.303,0.717,0.303c0.066,0,0.132-0.006,0.199-0.02c0.333-0.066,0.609-0.3,0.733-0.615l2.719-6.968L30,53.924 c0.123,0.315,0.399,0.549,0.732,0.615c0.066,0.014,0.133,0.02,0.199,0.02c0.267,0,0.525-0.106,0.717-0.303l4.663-4.791 l6.676,0.366c0.022,0.001,0.045,0.003,0.065,0.001c0.549,0.008,1.01-0.443,1.01-1c0-0.197-0.057-0.381-0.156-0.537l-5.811-14.886 C42.896,29.934,45.743,24.41,45.743,18.463z M23.262,51.747l-3.897-4.004c-0.189-0.194-0.448-0.304-0.717-0.304 c-0.018,0-0.037,0-0.055,0.002l-5.579,0.306l5.163-13.228c0.019,0.011,0.039,0.02,0.058,0.029 c0.225,0.127,0.457,0.239,0.686,0.355c0.184,0.095,0.365,0.195,0.552,0.283c0.082,0.039,0.167,0.07,0.249,0.106 c1.544,0.698,3.171,1.181,4.85,1.429c0.008,0.002,0.016,0.004,0.024,0.004c0.365,0.053,0.734,0.09,1.104,0.121 c0.096,0.008,0.191,0.021,0.288,0.027c0.294,0.02,0.59,0.025,0.886,0.032c0.136,0.003,0.271,0.015,0.406,0.015 c0.041,0,0.082-0.006,0.123-0.006c0.513-0.005,1.027-0.027,1.545-0.077c0.039-0.003,0.077-0.003,0.115-0.007 c0.006,0,0.013,0,0.021-0.001l-2.735,7.004c0,0,0,0.001,0,0.002L23.262,51.747z M35.966,47.441 c-0.285-0.012-0.57,0.095-0.771,0.302l-3.896,4.004l-2.944-7.543l3.021-7.741c0.34-0.076,0.674-0.171,1.006-0.268 c0.08-0.021,0.159-0.038,0.237-0.062c0.513-0.154,1.017-0.334,1.513-0.533c0.139-0.056,0.272-0.119,0.409-0.176 c0.366-0.158,0.728-0.326,1.083-0.507c0.152-0.078,0.305-0.155,0.454-0.237c0.101-0.055,0.206-0.103,0.306-0.16l5.164,13.229 L35.966,47.441z M36.328,32.208c-1.798,1.187-3.775,1.996-5.881,2.406c-1.632,0.317-3.257,0.389-4.839,0.229 c-2.636-0.264-5.15-1.166-7.378-2.637c-4.643-3.062-7.415-8.201-7.415-13.746c0-9.078,7.385-16.463,16.463-16.463 s16.463,7.385,16.463,16.463C43.743,24.007,40.97,29.146,36.328,32.208z"/> </g> </g> </g>
        </svg>    
        รางวัล</h4>
        <div class="row"> 
                  <?php 
                  foreach ($reward_list['result_array'] as $k => $v) {
                         ?>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="pro_deposit_amount">รางวัลที่ <?=$v['rating']?></label>
                            <input type="hidden" value="<?=$pro_data['row']->pro_bonus_amount?>" name="pro_bonus_amount" /> 
                            <input type="hidden" value="<?=$pro_data['row']->pro_bonus_max?>" name="pro_bonus_max" />
                            <input type="hidden" value="<?=$pro_data['row']->pro_bonus_max?>" name="pro_bonus_max" />
                            <input type="hidden"  name="m_reward[]" value="1">
                            <input type="number" class="form-control" placeholder="รางวัลที่ <?=$v['rating']?>" value="<?=$v['amount']?>" name="m_reward_<?=$v['id']?>" />
                          </div>
                         </div> 
                         <?php
                  }
                  ?>
                    
        </div>

        <div class="form-group">
                        <label>รูปโปรโมชั่น</label>
                        <label id="promo_img1" class="file center-block"> 
                            <input id="promo_img1" name="promo_img1" type="file" accept="image/*" onchange="upload_imge()" style="width: 180px;">
                            <span class="file-custom"></span>
                            <button type="button" class="btn btn-danger btn-sm btn_delete_imagepro" title="ลบรูปภาพ"><i class="ft-x"></i> ลบรูปภาพ</button>
                        </label>
                        <div class="text-danger mb-1"> **ขนาดไฟล์ที่แนะนำ 450*450 pixel รองรับไฟล์ที่รูปภาพทุกชนิด **</ก>
        </div>

        <div class="form-group">
        <label for="formGroupplaygame">เกมส์ที่เล่นได้</label>  
        <?php 
        foreach ($game_type_list as $v) {
            $pro_allow_playgame=explode(',',$pro_data['row']->pro_allow_playgame);
            $box_checked='';
            if(in_array($v['id'],$pro_allow_playgame)){$box_checked='checked';} 
           ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="pro_allow_playgame[]" <?=$box_checked?> id="inlineCheckbox<?=$v['id']?>" value="<?=$v['id']?>">
                <label class="form-check-label" for="inlineCheckbox<?=$v['id']?>"><?=$v['name_th']?></label>
            </div>
           <?php
        }
        ?> 
       </div>

        <div class="form-group">
            <label>สถานะโปรโมชั่น</label>
            <div class="input-group">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" name="pro_status" class="custom-control-input" value="1" <?=(@$pro_data['row']->pro_status==1) ? 'checked' : '';?> id="pro_status_open" />
                    <label class="custom-control-label" for="pro_status_open">เปิด</label>
                </div>
                <div class="d-inline-block custom-control custom-radio">
                    <input type="radio" name="pro_status" class="custom-control-input" value="0" <?=(@$pro_data['row']->pro_status!=1) ? 'checked' : '';?>  id="pro_status_close" />
                    <label class="custom-control-label" for="pro_status_close">ปิด</label>
                </div>
            </div>
        </div> 
 
        <div class="form-group">
            <label for="pro_description">รายละเอียดโปรโมชั่น</label>
            <textarea id="pro_description" rows="5" class="form-control" name="pro_description" placeholder="รายละเอียดโปรโมชั่น"><?=$pro_data['row']->pro_description?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal"><i class="ft-x"></i> ยกเลิก</button>
        <button type="submit" class="btn btn-primary btn-save"><i class="la la-check-square-o"></i> บันทึก</button>
    </div>
    </form> 
    </div> 
</div> 