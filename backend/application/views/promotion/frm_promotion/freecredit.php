<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="modal-form-proModalLabel">โปรโมชั่น <?=$pro_data['row']->name?></h5>
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
            <input type="text" id="promotionname" class="form-control" placeholder="ชื่อโปรโมชั่น" value="<?=$pro_data['row']->name?>" name="name" />
        </div>
        <h4 class="form-section"><i class="la la-info"></i>เงื่อนไข</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bonus_amount">ยอดรับโบนัส</label>
                    <input type="number" id="bonus_amount" class="form-control" placeholder="ยอดรับโบนัส" value="<?=$pro_data['row']->bonus_amount?>" name="bonus_amount" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bonus_max">รับโบนัสสูงสุด</label>
                    <input type="number" id="bonus_max" class="form-control" placeholder="รับโบนัสสูงสุด" value="<?=$pro_data['row']->bonus_max?>" name="bonus_max" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="turnover_amount">ทำยอดบวก</label>
                    <input type="number" id="turnover_amount" class="form-control" placeholder="ทำยอดบวก" value="<?=$pro_data['row']->turnover_amount?>" name="turnover_amount" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="withdraw_max_amount">ถอนได้สูงสุด</label>
                    <input type="number" id="withdraw_max_amount" class="form-control" placeholder="ถอนได้สูงสุด" value="<?=$pro_data['row']->withdraw_max_amount?>" name="withdraw_max_amount" />
                </div>
            </div>
        </div>
        <div class="form-group">
                        <label>รูปโปรโมชั่น</label>
                        <label id="promo_img1" class="file center-block"> 
                            <input id="promo_img1" name="promo_img1" type="file" accept="image/*" onchange="upload_imge()">
                            <span class="file-custom"></span>
                        </label>
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
                <input class="form-check-input" type="checkbox" name="allow_playgame[]" <?=$box_checked?> id="inlineCheckbox<?=$v['id']?>" value="<?=$v['id']?>">
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
                    <input type="radio" name="status" class="custom-control-input" value="1" <?=(@$pro_data['row']->status==1) ? 'checked' : '';?> id="pro_status_open" />
                    <label class="custom-control-label" for="pro_status_open">เปิด</label>
                </div>
                <div class="d-inline-block custom-control custom-radio">
                    <input type="radio" name="status" class="custom-control-input" value="0" <?=(@$pro_data['row']->status!=1) ? 'checked' : '';?>  id="pro_status_close" />
                    <label class="custom-control-label" for="pro_status_close">ปิด</label>
                </div>
            </div>
        </div> 
 
        <div class="form-group">
            <label for="description">รายละเอียดโปรโมชั่น</label>
            <textarea id="description" rows="5" class="form-control" name="description" placeholder="รายละเอียดโปรโมชั่น"><?=$pro_data['row']->description?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal"><i class="ft-x"></i> ยกเลิก</button>
        <button type="submit" class="btn btn-primary btn-save"><i class="la la-check-square-o"></i> บันทึก</button>
    </div>
    </form> 
    </div> 
</div> 
  