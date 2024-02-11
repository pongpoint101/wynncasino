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

$deposit_type_fix='display:none;';$deposit_type_min_max='display:none;';$deposit_type_range='display:none;'; 
if(@$pro_data['row']->pro_deposit_type==1){$deposit_type_fix='';}
if(@$pro_data['row']->pro_deposit_type==2){$deposit_type_min_max='';}
if(@$pro_data['row']->pro_deposit_type==3){$deposit_type_range='';}

?> 
<style>  
 #datestart, #starttime,#dateend,#endtime { display: none; }
</style>
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="modal-form-proModalLabel">โปรโมชั่น <?=$pro_data['row']->pro_name?></h5>
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
            <label for="promotionname">ชื่อโปรโมชั่น</label>
            <input type="text" id="promotionname" class="form-control" placeholder="ชื่อโปรโมชั่น" value="<?=$pro_data['row']->pro_name?>" name="pro_name" />
        </div>
        <h4 class="form-section"><i class="la la-info"></i>โบนัส</h4>

        <div class="form-group">
            <label>คำนวนโบนัส</label>
            <div class="input-group">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" name="pro_bonus_type" class="custom-control-input" value="1" <?=(@$pro_data['row']->pro_bonus_type==1) ? 'checked' : '';?> id="pro_bonus_type_1" />
                    <label class="custom-control-label" for="pro_bonus_type_1">โบนัสเครดิต</label>
                </div>

                <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_bonus_type" class="custom-control-input" value="2" <?=(@$pro_data['row']->pro_bonus_type==2) ? 'checked' : '';?> id="pro_bonus_type_2" />
                    <label class="custom-control-label" for="pro_bonus_type_2">โบนัสจำนวนเปอร์เซ็นต์(%)</label>
                </div>   

            </div> 
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_bonus_amount" class="txt_pro_bonus_amount">ยอดรับโบนัส</label>
                    <input type="number" id="pro_bonus_amount" class="form-control" placeholder="ยอดรับโบนัส" value="<?=$pro_data['row']->pro_bonus_amount?>" name="pro_bonus_amount" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_bonus_max">รับโบนัสสูงสุด</label>
                    <input type="number" id="pro_bonus_max" class="form-control" placeholder="รับโบนัสสูงสุด" value="<?=$pro_data['row']->pro_bonus_max?>" name="pro_bonus_max" />
                </div>
            </div>
        </div>

        <div class="row">  
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_withdraw_max_amount">ถอนได้สูงสุด</label>
                    <input type="number" id="pro_withdraw_max_amount" class="form-control" placeholder="ถอนได้สูงสุด" value="<?=$pro_data['row']->pro_withdraw_max_amount?>" name="pro_withdraw_max_amount" />
                </div>
            </div>
        </div>
        <h4 class="form-section"><i class="la la-info"></i>เงื่อนไข</h4> 

        <?php
         $pro_repeat_c=''; 
         switch (@$pro_data['row']->pro_repeat*1) {
            case 1: $pro_repeat_c='รับได้ครั้งเดียว'; break;
            case 2: $pro_repeat_c='รับได้รายวัน'; break;
            case 3: $pro_repeat_c='รับได้ตลอด'; break;
            case 4: $pro_repeat_c='รับได้รายเดือน'; break;
            case 5: $pro_repeat_c='รับได้รายปี'; break;
            default: break;
         }
         ?>
        <div class="form-group">
            <label>รูปแบบรับโปร</label>
            <div class="input-group">
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" name="pro_repeat" class="custom-control-input pro_repeat" value="1" <?=(@$pro_data['row']->pro_repeat==1) ? 'checked' : '';?> id="pro_repeat_1" />
                    <label class="custom-control-label" for="pro_repeat_1">รับได้ครั้งเดียว</label>
                </div>

                <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_repeat" class="custom-control-input pro_repeat" value="2" <?=(@$pro_data['row']->pro_repeat==2) ? 'checked' : '';?> id="pro_repeat_2" />
                    <label class="custom-control-label" for="pro_repeat_2">รับได้รายวัน</label>
                </div>   

                <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_repeat" class="custom-control-input pro_repeat" value="4" <?=(@$pro_data['row']->pro_repeat==4) ? 'checked' : '';?> id="pro_repeat_4" />
                    <label class="custom-control-label" for="pro_repeat_4">รับได้รายเดือน</label>
                </div>
                <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_repeat" class="custom-control-input pro_repeat" value="5" <?=(@$pro_data['row']->pro_repeat==5) ? 'checked' : '';?> id="pro_repeat_5" />
                    <label class="custom-control-label" for="pro_repeat_5">รับได้รายปี</label>
                </div>  
                <!-- <div class="d-inline-block custom-control custom-radio">
                   <input type="radio" name="pro_repeat" class="custom-control-input pro_repeat" value="3" <?=(@$pro_data['row']->pro_repeat==3) ? 'checked' : '';?> id="pro_repeat_3" />
                    <label class="custom-control-label" for="pro_repeat_3">รับได้ตลอด</label>
                </div> -->

            </div> 
        </div>
        
        <div class="row box_pro_max_repeat"<?=(@$pro_data['row']->pro_repeat!=1?'':'style="display: none;"')?> >
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_max_repeat" class="txt_pro_max_repeat">จำนวนครั้งการรับโปร / <?=$pro_repeat_c?></label>
                    <input type="number" id="pro_max_repeat" class="form-control" data-placeholder="จำนวนครั้งการรับโปร/" placeholder="จำนวนครั้งการรับโปร/<?=$pro_repeat_c?>" value="<?=$pro_data['row']->pro_max_repeat?>" name="pro_max_repeat" />
                </div>
            </div> 
        </div>
        
        <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pro_deposit_start_date">เวลาเริ่มรับโปร</label> 
                        <input type="time"  class="form-control" placeholder="เวลาเริ่มรับโปร" value="<?=date("H:i:s", strtotime($pro_data['row']->pro_deposit_start_date))?>" name="pro_deposit_start_date" />
                    </div>
                  </div> 
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="pro_deposit_end_date">หมดเวลารับโปร</label>   
                        <input type="time"   class="form-control" placeholder="เวลาเริ่มรับโปร" value="<?=date("H:i:s", strtotime($pro_data['row']->pro_deposit_end_date))?>" name="pro_deposit_end_date" />
                    </div>
                  </div>  
        </div>
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
                    <label class="custom-control-label" for="pro_turnover_type_4">ทำเทิร์นจำนวนเท่า</label>
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

        <div class="form-group">
            <label>ยอดฝาก</label>
            <div class="input-group">
                <?php   foreach ($list_deposit_type as $k => $v) {   ?>
                <div class="d-inline-block custom-control custom-radio mr-1">
                    <input type="radio" name="pro_deposit_type" class="custom-control-input pro_deposit_type" value="<?=$v?>" <?=(@$pro_data['row']->pro_deposit_type==$v) ? 'checked' : '';?> id="pro_deposit_type_open<?=$v?>" />
                    <label class="custom-control-label" for="pro_deposit_type_open<?=$v?>"><?=$list_deposit_dis[$v]?></label>
                </div> 
                <?php } ?>
            </div>
        </div>
 
        <div class="row box_pro_deposit_type pro_deposit_type_fix" style="<?=$deposit_type_fix?>"> 
                  <div class="col-md-10">
                    <div class="form-group">
                        <label for="pro_deposit_fix">เฉพาะยอดฝาก</label>
                        <input type="tel"   class="form-control" placeholder="เฉพาะยอดฝาก" value="<?=$pro_data['row']->pro_deposit_fix?>" name="pro_deposit_fix" />
                    </div>
                  </div>  
        </div>

        <div class="row box_pro_deposit_type pro_deposit_type_min_max" style="<?=$deposit_type_min_max?>">
            <div class="col-md-6"> 
                <div class="form-group">
                    <label for="pro_deposit_start_amount">ยอดฝากขั้นต่ำ</label>
                    <input type="number" id="pro_deposit_start_amount" class="form-control" placeholder="ยอดฝากขั้นต่ำ" value="<?=$pro_data['row']->pro_deposit_start_amount?>" name="pro_deposit_start_amount" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pro_deposit_end_amount">ยอดฝากขั้นสูงสุด</label>
                    <input type="number" id="pro_deposit_end_amount" class="form-control" placeholder="ยอดฝากขั้นสูงสุด" value="<?=$pro_data['row']->pro_deposit_end_amount?>" name="pro_deposit_end_amount" />
                </div>
            </div>
        </div>
 
        <style>
        .tbmpro th,.tbmpro td{padding: .2em !important;}
        </style>
        <div class="table-responsive box_pro_deposit_type pro_deposit_type_range" style="<?=$deposit_type_range?>">
        <table class="table table-xs table-bordered mb-0 tbmpro">
            <thead>
                <tr>
                    <th style="padding:.5em;">ลำดับ</th>
                    <th width="120">ยอดฝากขั้นต่ำ</th>
                    <th width="120">ยอดฝากสูงสุด</th>
                    <th class="txt_turnover_amount"><?=$text_turnover_amount?></th>
                    <th width="80">โบนัส</th> 
                    <th width="120">โบนัสสูงสุด</th> 
                    <th width="120">ถอนสูงสุด</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $i=0;
                foreach ($deposit_range_list['result_array'] as $k => $v) {
                    $i++
                    ?>
                <tr>
                    <th scope="row"><?=$i?></th>
                    <td><input type="number" class="form-control" placeholder="ยอดฝากขั้นต่ำ" value="<?=$v['deposit_start_amount']?>" name="deposit_start_<?=$v['id']?>" /></td>
                    <td><input type="number" class="form-control" placeholder="ยอดฝากสูงสุด	" value="<?=$v['deposit_end_amount']?>" name="deposit_end_<?=$v['id']?>" /></td>
                    <td><input type="number" class="form-control txt_turnover_amount" placeholder="<?=$text_turnover_amount?>" value="<?=$v['turnover_amount']?>" name="turnover_<?=$v['id']?>" /></td>
                    <td><input type="number" class="form-control" placeholder="โบนัส" size="2" value="<?=$v['pro_bonus_amount']?>" name="pro_bonus_amount_<?=$v['id']?>" /></td> 
                    <td><input type="number" class="form-control" placeholder="โบนัสสูงสุด" value="<?=$v['bonus_max_amount']?>" name="bonus_max_<?=$v['id']?>" /></td> 
                    <td><input type="number" class="form-control" placeholder="ถอนสูงสุด" value="<?=$v['withdraw_max_amount']?>" name="withdraw_max_amount_<?=$v['id']?>" /></td>
                </tr>
                <?php
                 }
            ?>
            </tbody>
        </table>
    </div>
    <br> 
    
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