<?php
//var_dump($rolesby_id);exit(); 
?>

<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="modal-form-proModalLabel">กำหนดสิทธิ์ <?=$rolesby_id->display_name?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body"> 
<form class="form" onSubmit="return false" method="post" enctype="multipart/form-data" id="form_per">
    <input type="hidden" name="role_id" id="role_id" value="<?=$rolesby_id->id?>"> 
    <div class="form-body">    
        <div class="form-group">
            <label for="display_name">ชื่อสิทธิ์</label>
            <input type="text" id="display_name" class="form-control" placeholder="ชื่อสิทธิ์" value="<?=$rolesby_id->display_name?>" name="display_name" />
        </div> 

        <div class="row">
            <div class="col-md-12 p-2">
            <div class="row match-height"> 
                <table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
                        <thead>													
                        <tr>
                            <th class="text-center text-white ">ระบบ</th>
                            <th class="text-center text-white ">จัดการ</th>   
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($permissions as $k => $v) {
                                    $create_is_opened=false;//var_dump($permissions);
                                    $update_is_opened=false;$delete_is_opened=false;
                                    ?>
                                    <tr class="header" >
                                        <td colspan="2"><label><input type="checkbox" class="chkrow chk_all"  data-group_name="<?=$v['group_name']?>" value="1"></label> <label><?=$v['group_name']?></label></td>
                                    </tr>
                                    <?php
                                    foreach ($permissions_all as $kk => $vv) {
                                           $view_is_opened=false;
                                           if($v['group_name']!=$vv->group_name){continue;}
                                           foreach ($permissionsby_id as $kkk => $vvv) {
                                            if($vv->group_name!=$vvv['group_name']){continue;}
                                            if($vv->group_name==$vvv['group_name']&&$vv->id==$vvv['permission_id']){
                                                $view_is_opened=true;  
                                             }
                                           }
                                       ?>
                                        <tr >
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="<?=$v['group_name']?>_chk_all" name="permission_<?=$vv->id?>" value="<?=$vv->id?>" <?=($view_is_opened?'checked':'')?> ></label></td>
                                            <td><label> <?=$vv->display_name?></label></td>    
                                        </tr>
                                       <?php
                                    } 
                                  //  var_dump($v);exit();
                                ?> 
                                <?php
                                }
                                ?>  
                            </tbody>
                        </table>      
				 </div>
            </div> 
        </div>
 

    <div class="form-actions">
        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal"><i class="ft-x"></i> ยกเลิก</button>
        <button type="submit" class="btn btn-primary btn-save"><i class="la la-check-square-o"></i> บันทึก</button>
    </div>
    </form> 
    </div> 
</div> 