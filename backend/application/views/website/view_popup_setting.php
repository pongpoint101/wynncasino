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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
                                                <div class="popup-card">

                                                <div class="card">
                                                                    <div class="card-content">
                                                                       <div class="card-body">
 
                                                                            <form class="needs-validation" novalidate="">
                                                                                        <div class="row">
                                                                                        <div class="col-md-6 mb-1">
                                                                                                <label for="firstName">รูปแบบป๊อปอัพ</label> 
                                                                                                <select class="form-control" id="pop_style" name="pop_style" required> 
                                                                                                    <?php
                                                                                                    $popupstyle=[
                                                                                                        '1'=>['name'=>'แสดงเฉพาะรูป'],
                                                                                                        '2'=>['name'=>'แสดงเฉพาะข้อความ'],
                                                                                                        '3'=>['name'=>'แสดงรูปภาพและข้อความ'],
                                                                                                    ];
                                                                                                    foreach ($popupstyle as $key => $value) {
                                                                                                      ?>
                                                                                                       <option value="<?=$key?>" <?=($pop_style==$key)? 'selected':'' ?> ><?=$value['name']?></option>
                                                                                                      <?php
                                                                                                    }
                                                                                                    ?> 
                                                                                                </select>
                                                                                                <div class="invalid-feedback">
                                                                                                    Valid required.
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6 mb-1"></div>
                                                                                            <div class="col-md-6 mb-1">
                                                                                                <label for="firstName">วันที่เริ่มแสดงผล</label> 
                                                                                                <input type="datetime-local" class="form-control" id="pop_start" name="pop_start" value="<?=date('Y-m-d H:i', strtotime($pop_start))?>" required/>
                                                                                                <div class="invalid-feedback">
                                                                                                    Valid required.
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6 mb-2">
                                                                                                <label for="lastName">วันสิ้นสุดการแสดงผล</label>
                                                                                                <input type="datetime-local" class="form-control" id="pop_expired" name="pop_expired" value="<?=date('Y-m-d H:i', strtotime($pop_expired))?>" required/>
                                                                                                <div class="invalid-feedback">
                                                                                                    Valid required.
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-12 mb-2">
                                                                                                <label for="lastName">รูปภาพที่ต้องการแสดงผล <span class="text-danger">( ขนาดรูปมีผลต่อการแสดงผลหน้าเว็บ ขนาดแนะนำ 540X540 )</span></label> 
                                                                                                 <input type="file" class="form-control-file" id="popup_img" name="popup_img"> 
                                                                                            </div>
                                                                                            <div class="col-md-6 mb-1">
                                                                                                <div class="form-group">
                                                                                                    <label for="link_popup">ลิงค์ป๊อปอัพ</label>
                                                                                                    <input type="text" value="<?=$link_popup?>" id="link_popup" class="form-control link_popup" name="link_popup" placeholder="ตัวอย่าง https://www.w3schools.com" autocomplete="off" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-3 mb-1">
                                                                                                <div class="form-group">
                                                                                                    <label for="popup_link_name">ข้อความลิงค์ป๊อปอัพ</label>
                                                                                                    <input type="text" value="<?=$popup_link_name?>" id="popup_link_name" class="form-control popup_link_name" name="popup_link_name" placeholder="ตัวอย่าง ตกลง,คลิกที่นี่" autocomplete="off" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-3 mb-1">
                                                                                                <!-- <div class="form-group">
                                                                                                    <label for="open_style_popup">รูปแบบเปิดลิงค์ป๊อปอัพ</label>
                                                                                                    <input type="text" value="<?=$open_style_popup?>" id="open_style_popup" class="form-control open_style_popup" name="open_style_popup" placeholder="_blank,_self" autocomplete="off" />
                                                                                                </div> -->
                                                                                                  <div class="form-group">
                                                                                                            <label for="open_style_popup">รูปแบบเปิดลิงค์ป๊อปอัพ</label>
                                                                                                            <div class="input-group">
                                                                                                            <div class="d-inline-block custom-control custom-radio mr-1">
                                                                                                                    <input type="radio" <?=$open_style_popup=='close'?'checked':''?> name="open_style_popup" class="custom-control-input open_style_popup" value="close" id="open_style_popup3" />
                                                                                                                    <label class="custom-control-label" for="open_style_popup3">ปิดลิงค์ป๊อปอัพ</label>
                                                                                                                </div>
                                                                                                                <div class="d-inline-block custom-control custom-radio">
                                                                                                                    <input type="radio" <?=$open_style_popup=='_blank'?'checked':''?> name="open_style_popup" class="custom-control-input open_style_popup" value="_blank" id="open_style_popup1" />
                                                                                                                    <label class="custom-control-label" for="open_style_popup1">เปิดแท็บใหม่</label>
                                                                                                                </div>
                                                                                                                <div class="d-inline-block custom-control custom-radio">
                                                                                                                    <input type="radio" <?=$open_style_popup=='_self'?'checked':''?> name="open_style_popup" class="custom-control-input open_style_popup" value="_self" id="open_style_popup2" />
                                                                                                                    <label class="custom-control-label" for="open_style_popup2">เปิดแท็บเดิม</label>
                                                                                                                </div>

                                                                                                            </div>
                                                                                                    </div>

                                                                                            </div>
                                                                                            <div class="col-md-9 mb-5">
                                                                                                <label for="lastName">ข้อความที่ต้องการแสดงผล</label> 
                                                                                                  <!-- <textarea class="form-control" id="popup_message" name="popup_message" rows="3" placeholder="ข้อความที่ต้องการแสดงผล"><?=$message?></textarea> -->
                                                                                                  <div id="editor-container">
                                                                                                      <?=$message?>
                                                                                                    </div> 
                                                                                            </div>
                                                                                            

                                                                                            <div class="col-md-12 mb-2"></div>
                                                                                            <div class="col-md-8">
                                                                                                <label>
                                                                                                    <input type="radio" name="popup_opened" class="popup_opened" value="1" <?=($status==1)?'checked':''?> />
                                                                                                     เปิด
                                                                                                </label>
                                                                                                <label>
                                                                                                    <input type="radio" name="popup_opened" class="popup_opened" value="0" <?=($status==0)?'checked':''?>/>
                                                                                                   ปิด
                                                                                                </label> 
                                                                                            </div>

                                                                                            <div class="col-12">
                                                                                               <div class="card">
                                                                                                <div class="card-content">
                                                                                                    <div class="card-body">
                                                                                                        <label><span class="text-danger" id="form-error-text"></span></label> 
                                                                                                        <div class="text-center"> 
                                                                                                            <button type="button" class="btn btn-info mr-2" id="btn-save">บันทึก</button>
                                                                                                            <!-- <button type="cancel" class="btn btn-warning">ยกเลิก</button> -->
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                               </div> 
                                                                                            </div>

                                                                                        </div>  
                                                                               </form> 
                                                                        </div>
                                                                    </div>
                                                                </div>  
                                                      </div>
 
                                                </div>
 
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
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    
    <script type="text/javascript">
        $(function() {
            $('.Menupopuplist').addClass('active');
            chagetxt('');
            $('#pop_style').on('change', function() {
                chagetxt(this.value); 
            });
            function chagetxt(style=''){
                let pop_style='';
                $txt='รูปแบบป๊อปอัพ';
                if(style==''){
                    pop_style=$('#pop_style').val()*1;
                }
                switch (pop_style) {
                case 1: $txt='แสดงเฉพาะรูป';  break;
                case 2: $txt='แสดงเฉพาะข้อความ';  break;
                case 3: $txt='แสดงรูปภาพและข้อความ';  break;
               }
              
            } 

            var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'], 
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
           
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction
 
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }], 

            ['clean']                                         // remove formatting button
            ];
            var quill = new Quill('#editor-container', {
           modules: {
                    toolbar: toolbarOptions
               },
            placeholder: '...',
            theme: 'snow'  // or 'bubble'
            });
            $(document).on("click", "#btn-save", function() {
                    var file_data = $('#popup_img')[0].files[0]; 
                    var form_data = new FormData(); 
                    form_data.append('message', quill.root.innerHTML);
                    form_data.append('popup_img', (file_data!=undefined)?file_data:'');
                    form_data.append('pop_style', $('#pop_style').val()); 
                    form_data.append('pop_start', $('#pop_start').val());
                    form_data.append('pop_expired', $('#pop_expired').val()); 
                    form_data.append('link_popup', $('#link_popup').val()); 
                    form_data.append('open_style_popup', $('input[name="open_style_popup"]:checked').val()); 
                    form_data.append('status', $('input[name="popup_opened"]:checked').val()); 
                    form_data.append('popup_link_name', $('#popup_link_name').val()); 
                    form_data.append('id', '<?=@$_GET['id']?>'); 
                    $.ajax({
                        url: '<?php echo site_url("website/save_popup") ?>', 
                        method: "POST",
                        data: form_data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,   
                        beforeSend: function() {
						  Myway_ShowLoader();
					    },
                        success: function(res){
                              if(res.status=='success'){
                                swal("บันทึกข้อมูลเรียบร้อยแล้ว!"); 
                                Myway_HideLoader(); 
                               }else{
                                $('#form-error-text').text(res.message);
                            }  
                        }
                  });  
              }); 
 
        });
        
    </script>
</body>

</html>