<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php echo $pg_header ?>
    <title><?php echo $pg_title ?></title>
    <style>
        @media only screen and (max-width: 500px) {
            .div-add {
                height: 318px !important;
            }
        }

        .div-del {
            cursor: pointer;
            font-size: 22px;
            right: 15px;
            padding: 0px 9px;
            position: absolute;
            text-align: right;
            background: #ff000091;

        }

        .div-del:hover {
            background: #ff0000d1;
        }

        .div-add {
            width: 100%;
            height: 250px;
            background: #dfdfe3;
            cursor: pointer;
            font-size: 80px;
        }

        .image-display {
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            text-align: right;
            text-align: center;
        }

        .image-display img {
            height: 100%;
        }

        .div-add:hover,
        .div-add:focus,
        .div-add:active {
            background: #dfdfe3;
            color: #515a6c;
        }

        .image-upload div {
            width: 100%;
            /* height: 218px; */
            background: #dfdfe3;
            cursor: pointer;
        }

        .image-upload>input {
            display: none;
        }

        .image-upload div h2 {
            padding: 75px 0;
            text-align: center;
            font-size: 50px;
            font-weight: 100;
            color: #59595c;
        }

        .image-upload div svg {
            color: #c9c9c9;
            font-size: 30px;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <?php echo $pg_menu ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">ตั้งค่า</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item active">เว็บไซต์
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row match-height">

                        <div class="col-xs-12 col-md-12">
                            <div class="card">

                                <div class="card-content collapse show">
                                    <div class="card-body">

                                            <div class="form-body">
                                                <h4 class="form-section"><i class="ft-airplay"></i> <?= $pg_title ?></h4>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?php echo base_url() ?>" id="base_url">
                                                            <h4 >Desktop <span class="text-danger">( ขนาดรูปมีผลต่อการแสดงผลหน้าเว็บ ขนาดแนะนำ 1920x808 )</span></h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php 
                                                        foreach ($pic['result_array'] as $key => $value) { ?>
                                                            <div class="col-md-6 mb-2 div-pic-<?php echo $value['id'] ?>">
                                                                <span class="div-del" onclick="clickDel(<?php echo $value['id'] ?>)">x</span>
                                                                <div class="image-upload" data-toggle="modal" data-target="#picModal">
                                                                
                                                                    <div onclick="clickModal(<?php echo $value['id'] ?>)">
                                                                        <img src="<?php echo  base_url().'/images/banner/'.$value['name'] ?>" class="w-100">
                                                                        <!-- <h2>
                                                                            <font-awesome-icon :icon="['fa', 'pen-to-square']" class="icon alt" />
                                                                        </h2> -->
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        <?php
                                                        }?>
                                                    
                                                    
                                                    <div class="col-md-6">
                                                        <div class="image-upload" data-toggle="modal" data-target="#picModal">
                                                            <div style="border: 1px solid #f00">
                                                                <!-- <h2>+</h2> -->
                                                                <img src="https://dummyimage.com/1920x808/e3e3e3/fff&text=Add" class="w-100">
                                                            </div>
                                                            <input id="file-input" type="file" />
                                                        </div>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-labelledby="picModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="picModalLabel">เพิ่มภาพ</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="array" class="col-form-label">image:</label>
                                                                                    <input type="hidden" id="id" value="">
                                                                                    <input type="file" class="form-control" name="file" id="file" accept="image/*">
                                                                                </div>
                                                                            
                                                                            <div class="col-md-12 pl-0 pb-1">
                                                                                <span class="form-error-text text-danger"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary btn-save">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?php echo base_url() ?>" id="base_url">
                                                            <h4 >Mobile <span class="text-danger">( ขนาดรูปมีผลต่อการแสดงผลหน้าเว็บ ขนาดแนะนำ 390×536 )</span></h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php 
                                                        foreach ($pic_mb['result_array'] as $key => $value) { ?>
                                                            <div class="col-md-3 mb-2 div-mb-pic-<?php echo $value['id'] ?>">
                                                                <span class="div-del" onclick="clickDelMobile(<?php echo $value['id'] ?>)">x</span>
                                                                <div class="image-upload" data-toggle="modal" data-target="#picModal-m">
                                                                
                                                                    <div onclick="clickModalMobile(<?php echo $value['id'] ?>)">
                                                                        <img src="<?php echo  base_url().'/images/banner/'.$value['name'] ?>" class="w-100">
                                                                        <!-- <h2>
                                                                            <font-awesome-icon :icon="['fa', 'pen-to-square']" class="icon alt" />
                                                                        </h2> -->
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        <?php
                                                        }?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="image-upload" data-toggle="modal" data-target="#picModal-m">
                                                            <div style="border: 1px solid #f00">
                                                                <!-- <h2>+</h2> -->
                                                                <img src="https://dummyimage.com/390x536/e3e3e3/fff&text=Add" class="w-100">
                                                            </div>
                                                            <input id="file-input" type="file" />
                                                        </div>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="picModal-m" tabindex="-1" role="dialog" aria-labelledby="picModalMobileLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="picModalMobileLabel">เพิ่มภาพ</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="array" class="col-form-label">image:</label>
                                                                                    <input type="hidden" id="id-m" value="">
                                                                                    <input type="file" class="form-control" name="file-m" id="file-m" accept="image/*">
                                                                                </div>
                                                                            
                                                                            <div class="col-md-12 pl-0 pb-1">
                                                                                <span class="form-error-text text-danger"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary btn-save-m">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions right">
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </section>
            </div>

        </div>
    </div>


    <?php echo $pg_footer ?>
    <script type="text/javascript">
        $(function() {
            $('.MenuHome').addClass('active');
            $(document).on("click", ".btn-save", function() {
                var file_data = $('#file')[0].files[0];
                var form_data = new FormData();
                console.log(file_data);
                form_data.append('file', file_data);
                form_data.append('id', $('#id').val());
                if (file_data) {
                    $.ajax({
                        url: '<?php echo site_url("website/add_pic_pc") ?>', 
                        method: "POST",
                        data: form_data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,   
                        success: function(res){
                            if(res.status=='success'){
                                var base = $("#base_url").val();
                                if(res.action=='edit'){
                                    $(".div-pic-"+res.id+" .image-upload img").attr('src',base+'/images/banner/'+res.res);
                                }else if(res.action=='add'){
                                    location.reload();
                                }
                                Myway_HideLoader();
                                $('.modal').modal('hide');
                            }else{
                                $('.form-error-text').text(res.message);
                            }
                            
                        }
                    });
                }else{
                    $('.form-error-text').text('กรุณาเลือกไฟล์');
                }

            });
            $(document).on("click", ".btn-save-m", function() {
                var file_data = $('#file-m')[0].files[0];
                var form_data = new FormData();
                console.log(file_data);
                form_data.append('file', file_data);
                form_data.append('id', $('#id-m').val());
                if (file_data) {
                    $.ajax({
                        url: '<?php echo site_url("website/add_pic_mb") ?>', 
                        method: "POST",
                        data: form_data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,   
                        success: function(res){
                            if(res.status=='success'){
                                var base = $("#base_url").val();
                                if(res.action=='edit'){
                                    $(".div-mb-pic-"+res.id+" .image-upload img").attr('src',base+'/images/banner/'+res.res);
                                }else if(res.action=='add'){
                                    location.reload();
                                }
                                Myway_HideLoader();
                                $('.modal').modal('hide');
                            }else{
                                $('.form-error-text').text(res.message);
                            }
                            
                        }
                    });
                }else{
                    $('.form-error-text').text('กรุณาเลือกไฟล์');
                }

            });

            $('#picModal,#picModal-m').on('hidden.bs.modal', function (e) {
                $('.form-error-text').text('');
                $(this)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
            })


        });
        function clickModal(id){
            $('#id').val(id);
            $('#picModalLabel').text('แก้ไขภาพ');
        }
        function clickDel(id){
            swal({
            title: "คุณต้องการลบรูปนี้ใช่ไหม?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["ยกเลิก", "ใช่, ลบ"],
            
            })
            .then((willDelete) => {
                if (willDelete) {
                    var form_data = new FormData();
                    form_data.append('id', id);
                    $.ajax({
                        url: '<?php echo site_url("website/delete_pic_pc") ?>', 
                        method: "POST",
                        data: form_data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,   
                        success: function(res){
                            console.log(res);
                            if(res.status=='success'){
                                swal("สำเร็จ!", "ลบรูปเรียบร้อยแล้ว", "success")
                                .then((value) => {
                                    Myway_HideLoader();
                                    $(".div-pic-"+res.id).remove();
                                });

                            }else{
                                swal("มีบางอย่างผิดพลาด!", "กรุณาลองใหม่อีกครั้ง", "error");
                            }
                            
                        }
                    });
                    
                }
            });
        }
        function clickModalMobile(id){
            $('#id-m').val(id);
            $('#picModalMobileLabel').text('แก้ไขภาพ');
        }
        function clickDelMobile(id){
            swal({
            title: "คุณต้องการลบรูปนี้ใช่ไหม?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["ยกเลิก", "ใช่, ลบ"],
            
            })
            .then((willDelete) => {
                if (willDelete) {
                    var form_data = new FormData();
                    form_data.append('id', id);
                    $.ajax({
                        url: '<?php echo site_url("website/delete_pic_mb") ?>', 
                        method: "POST",
                        data: form_data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,   
                        success: function(res){
                            console.log(res);
                            if(res.status=='success'){
                                swal("สำเร็จ!", "ลบรูปเรียบร้อยแล้ว", "success")
                                .then((value) => {
                                    Myway_HideLoader();
                                    $(".div-mb-pic-"+res.id).remove();
                                });

                            }else{
                                swal("มีบางอย่างผิดพลาด!", "กรุณาลองใหม่อีกครั้ง", "error");
                            }
                            
                        }
                    });
                    
                }
            });
        }
    </script>
</body>

</html>