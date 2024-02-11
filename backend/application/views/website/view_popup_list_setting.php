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
                                <li class="breadcrumb-item active">ประกาศ
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
                                              <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                     <h4 class="form-section"><i class="ft-airplay"></i> <?= $pg_title ?> <small style="color:red;">(จับเลื่อนตำแหน่งได้ เลขลำดับน้อยจะแสดงผลก่อน)</small></h4>
                                                    </div>
                                                       <div class="col-sm-6 ">
                                                        <div class="float-right"> 
                                                            <button onclick="updatePopUp()" type="button" class="btn btn-primary btnsave" disabled><i class="la la-check-square-o"></i> บันทึก</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                              </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-borderless mb-0" id="tblist">
                                                                        <!-- <thead>
                                                                            <tr>
                                                                                <th>รายการป๊อปอัพ</th>
                                                                                <th>รายละเอียด</th>
                                                                                <th>ลำดับแสดงผล</th>
                                                                                <th>จัดการ</th>
                                                                            </tr>
                                                                        </thead> -->
                                                                        <tbody> 
                                                                            <tr >
                                                                                <th style="vertical-align: bottom;border-bottom: 2px solid #515151;border-top: 1px solid #515151;">รายการป๊อปอัพ</th>
                                                                                <th style="vertical-align: bottom;border-bottom: 2px solid #515151;border-top: 1px solid #515151;">รายละเอียด</th>
                                                                                <th style="vertical-align: bottom;border-bottom: 2px solid #515151;border-top: 1px solid #515151;">ลำดับแสดงผล</th>
                                                                                <th style="vertical-align: bottom;border-bottom: 2px solid #515151;border-top: 1px solid #515151;">จัดการ</th>
                                                                            </tr> 
                                                                            <?php foreach ($listitem as $k => $v) {
                                                                                $tr_color = '';
                                                                                if ($v['status'] != 1) {
                                                                                    $tr_color = 'bg-danger bg-lighten-2 white';
                                                                                }
                                                                            ?>
                                                                                <tr class="trmorder morder_<?=$v['m_order']?> <?= $tr_color ?>" data-id="<?=$v['id']?>" data-morder="<?=$v['m_order']?>">
                                                                                    <td>
                                                                                        <div class="product-img d-flex align-items-center">
                                                                                            <?php
                                                                                            $img = 'https://dummyimage.com/540X540/e3e3e3';
                                                                                            if (!is_null($v['images']) && $v['images'] != '') {
                                                                                                $img = base_url() . 'images/web/' . $v['images'];
                                                                                            }
                                                                                            ?>
                                                                                            <img src="<?= $img ?>" height="300" width="300" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php
                                                                                        $popupstyle=[
                                                                                            '1'=>['name'=>'แสดงเฉพาะรูป'],
                                                                                            '2'=>['name'=>'แสดงเฉพาะข้อความ'],
                                                                                            '3'=>['name'=>'แสดงรูปภาพและข้อความ'],
                                                                                        ];
                                                                                        $name_popupstyle='';
                                                                                        foreach ($popupstyle as $key => $value) {
                                                                                            if($v['pop_style']==$key){$name_popupstyle=$value['name'];  break; }
                                                                                        }
                                                                                        ?>
                                                                                        <div class="product-color"><strong>วันที่เริ่มแสดงผล : </strong> <?= $v['pop_start'] ?></div>
                                                                                        <div class="product-color"><strong>วันสิ้นสุดการแสดงผล : </strong><?= $v['pop_expired'] ?></div>
                                                                                        <div class="product-color"><strong>รูปแบบป๊อปอัพ : </strong><?=$name_popupstyle?></div>
                                                                                        <div class="product-size"><strong>สถานะ : </strong> <?= ($v['status'] == 1 ? 'เปิด' : 'ปิด') ?></div>
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;">
                                                                                      <!-- <button type="button" class="btn btn-xs btn-success no-margin sort_morder_up_all" data-morder_id="<?=$v['id']?>">
                                                                                         <i class="ficon ft-chevrons-up"></i> 
                                                                                      </button> -->
                                                                                    <input type="number" class="inputmorder morder form-control" placeholder="ลำดับแสดงผล" id="morder_<?=$v['id']?>" name="morder_<?=$v['id']?>" value="<?=$v['m_order']?>"></td>
                                                                                    <td>
                                                                                        <div class="product-action">
                                                                                            <a href="<?= base_url() ?>website/popup?id=<?= $v['id'] ?>" target="_blank"><i class="ft-edit"></i></a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php  } ?>
                                                                        </tbody>
                                                                    </table>
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
    <script type="text/javascript">
        $(function() {
            $('.Menupopuplist').addClass('active');    
        }); 
    </script>
    <script src="<?= base_url('assets/jqueryui/jquery-ui.min.js')?>" type="text/javascript"></script> 
    <script type="text/javascript">
        $(function() {
            $('.inputmorder').keyup(function() {
                $(".btnsave").prop( "disabled",false);
            });
            $("#tblist").sortable({
            items: 'tr:not(tr:first-child)',
            cursor: 'move',
            axis: 'y',
            dropOnEmpty: false,
            start: function (e, ui) {
                ui.item.addClass("selected");
            },
            stop: function (e, ui) {
                ui.item.removeClass("selected");
                $(this).find("tr").each(function (index) {
                    if (index > 0) {  
                        $('input.morder ', this).val(index);//$(this).index()
                         $(this).attr('data-morder', index);
                        //  $(this).find("td").eq(2).html(index); 
                     }
                 }).promise().done(function(){  
                    $(".btnsave").prop( "disabled",false);
                    // Myway_ShowLoader();
                    // setTimeout(() => {
                    //     updatePopUp();
                    // }, 2000);
                });
              
            }
        });
    //     $(".sort_morder_up_all").on('click', function (e) {
    //         e.preventDefault();
    //         var morder_id = $(this).data('morder_id'); 
    //         if ($("tr.morder_" + morder_id + ":first").prev().attr('class') != undefined) {
    //             $("tr.morder_" + morder_id + ":parent").insertBefore($("tr.trmorder:first"));
    //             $("tr.morder_" + morder_id + ":parent").css({"background-color": "#d9edf7"});
    //             setTimeout(function () {
    //             $("tr.morder_" + morder_id + ":parent").css({"background-color": "#ffffff"});
    //             }, 500);
    //             update_number_rows();
    //         }
    //      });
    //      function update_number_rows() {
    //               var number;
    //               $("#tblist").find("tr").each(function (index, obj) {
    //                 if (index > 0) { 
    //                      number = index + 1; 
    //                     $('input.morder ', this).val(number);
    //                     $(this).attr('data-morder', number);
    //                 }
    //              }).promise().done(function(){ 

    //             }); 
    //   }

        });
        function updatePopUp(){
                 Myway_ShowLoader();
                 var listup=[];  
                $("#tblist").find("tr").each(function (index) {
                    if (index > 0) {
                    var popup_id=$(this).data('id');
                    // var popup_order=$(this).data('morder'); 
                    var popup_order=$(this).find(".inputmorder").val();
                    listup.push({'popup_id':popup_id,'popup_order':popup_order});
                    }
                }).promise().done(function(){  
                $.ajax({
                    url: '<?= base_url("website/save_popup?morder") ?>',
                    type: 'post',
                    data: { 
                        list: listup
                    },
                    dataType: 'json',
                    success: function(sr) {
                        $(".btnsave").prop( "disabled",true);
                        model_success_pggame();
                        Myway_HideLoader();
                    }

                  }); 

                }); 
          } 
    </script>
</body>

</html>