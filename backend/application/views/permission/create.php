<?php
//var_dump($rolesby_id);exit(); 
?> 
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="modal-form-proModalLabel">เพิ่มสิทธิ์ </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body"> 
<form class="form" onSubmit="return false" method="post"  id="form_add_role">  
    <div class="form-body">    
        <div class="form-group">
            <label for="name">ชื่อสิทธิ์</label>
            <input type="text" id="name" class="form-control" placeholder="ชื่อสิทธิ์" value="" name="name" required onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)" />
        </div> 
        <div class="form-group">
            <label for="display_name">ชื่อที่ใช้แสดงผลสิทธิ์</label>
            <input type="text" id="display_name" class="form-control" placeholder="ชื่อสิทธิ์" value="" name="display_name"  required />
        </div>
        <div class="form-group">
            <label for="description">คำอธิบายสิทธิ์</label>
            <input type="text" id="description" class="form-control" placeholder="ชื่อสิทธิ์" value="" name="description" />
        </div>

    <div class="form-actions">
        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal"><i class="ft-x"></i> ยกเลิก</button>
        <button type="submit" class="btn btn-primary btn-role-save"><i class="la la-check-square-o"></i> บันทึก</button>
    </div>
    </form> 
    </div> 
</div> 