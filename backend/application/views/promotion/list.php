<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style> 
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new"> 
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active"><?=$card_title?>
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body">
					<section id="html">

						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title"><?=$card_title?></h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											    <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-sm-12 pb-2">
														   <form class="form-inline" method="get"> 
															<div class="form-group mx-sm-1">
																<label  class="sr-only">หมวดโปรโมชั่น</label>
																 <select class="form-control" id="pro_cat_id" name="pro_cat_id">
																    <option value='all'>หมวดโปรโมชั่น</option>
                                                                    <?php
                                                                    $channel_old='';
                                                                    foreach ($pro_category_list as $k => $v) {
                                                                        $channel=@$_GET['pro_cat_id'];
                                                                        ?>
                                                                        <option value='<?=$v['channel']?>' <?=(@$channel==$v['channel']) ? 'selected' : '';?> ><?=$v['pro_name']?></option>
                                                                        <?php
                                                                    }
                                                                    ?> 
																 </select>
															</div> 
															<button type="submit" class="btn btn-info" id="btn_search">ค้นหา</button>&nbsp;  
													       </form> 
														</div> 
                                                    </div>
													<div class="row match-height">
                      <div class="table-responsive">   
                        <table class="table w-100 table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">ชื่อโปรโมชั่น</th>
														<th class="text-center">ยอดรับโบนัส</th> 
														<th class="text-center">ยอดเทิร์น</th>
														<th class="text-center">ถอนได้สูงสุด</th> 
                            <th class="text-center">รหัสโปร</th> 
														<th class="text-center">สถานะ</th>
                            <th class="text-center"></th>
													</tr>
												</thead>
                        <tbody>
										   
                                                        <?php 
                                                        foreach ($pro_master_list as $k => $v) {
                                                            $color_card='';$btn_txt='เปิด';
                                                            $v_data=$v;
                                                            if($v['pro_status']!=1){$color_card='danger';$btn_txt='ปิด';}
                                                            $withdraw_max_amount=$v['pro_withdraw_max_amount'];
                                                            $bonus_max=$v['pro_bonus_max'];
                                                            $turnover_amount=$v['pro_turnover_amount'];
                                                            $pro_symbol=$v['pro_symbol'];
                                                            foreach ($pro_list as $v2) {
                                                                  if($v['pro_id']==$v2['pro_id']){
                                                                    $v_data=$v2;
                                                                    $withdraw_max_amount=$v_data['pro_withdraw_max_amount'];
                                                                    $bonus_max=$v_data['pro_bonus_max'];
                                                                    $turnover_amount=$v_data['pro_turnover_amount'];
                                                                    if($v2['pro_status']!=1){$color_card='bg-danger';$btn_txt='ปิด'; break; }else{$color_card='';$btn_txt='เปิด';} 
                                                                  }
                                                            } 
                                                            if($bonus_max==-1){$bonus_max='ไม่จำกัด';}
                                                            if($withdraw_max_amount==-1){$withdraw_max_amount='ไม่จำกัด';}
                                                            $turnover_type='';
                                                            if($v_data['pro_turnover_type']==1){$turnover_type='ยอดเล่น';}
                                                            else if($v_data['pro_turnover_type']==2){
                                                              $turnover_type='ยอดเล่นจำนวนเท่า';
                                                            }else if($v_data['pro_turnover_type']==3){
                                                              $turnover_type='ยอดเครดิต';
                                                            }else if($v_data['pro_turnover_type']==4){
                                                              $turnover_type='เครดิตจำนวนเท่า';
                                                            }
                                                           ?>
                                                            <!-- <div class="col-xl-3 col-md-6 col-sm-12">
                                                            <div class="card text-white bg-<?=$color_card?>">
                                                                <div class="card-content"> 
                                                                    <div class="card-body">
                                                                        <h4 class="card-title"><?=($v_data['pro_is_group']==1?$v_data['pro_name']:$v_data['pro_group_name'])?></h4>
                                                                        <p class="card-text"><?=$v_data['pro_description']?></p>
                                                                        <button data-pro_id='<?=$v_data['pro_id']?>' data-m_order='<?=$v_data['m_order']?>' type="button" class="btn btn-success btn-darken-3 btn_edit_pro">ตั้งค่าโปร(<?=$btn_txt?>)</button>
                                                                    </div>
                                                                </div>
                                                              </div>
                                                            </div> -->
                                                            <tr class="<?=$color_card?>">
                                                              <td><?=($v_data['pro_is_group']==1?$v_data['pro_name']:$v_data['pro_group_name'])?></td>
                                                              <td><?=$bonus_max?></td>
                                                              <td><?=$turnover_amount?>-(<?=$turnover_type?>)</td>
                                                              <td><?=$withdraw_max_amount?></td>
                                                              <td><?=$pro_symbol?></td> 
                                                              <td><?=$btn_txt?></td> 
                                                              <td><button data-pro_id='<?=$v_data['pro_id']?>' data-m_order='<?=$v_data['m_order']?>' type="button" <?php echo $hide_btn; ?> class="btn btn-success btn-darken-3 btn_edit_pro">ตั้งค่าโปร(<?=$btn_txt?>)</button></td>
                                                            </tr>
                                                           <?php
                                                        }
                                                        ?>
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

					</section>
				</div>
			</div>
		</div>
		 
        <div class="modal fade" id="modal-form-pro" tabindex="-1" aria-labelledby="modal-form-proModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"> </div>
            </div>
        </div> 

		<?php echo $pg_footer;  ?>
		<script type="text/javascript"> 
		  $(function(){
        var allow_empty=['description','promo_img1']; 
 
          $(document).on("click",".btn_delete_imagepro",function() {
              delete_imagePro();
           });

          $(document).on("click","#pro_bonus_type_1",function() {
            $('.txt_pro_bonus_amount').text('โบนัสเครดิต');      
            $('#pro_bonus_amount').attr("placeholder",'โบนัสเครดิต');
           });
          $(document).on("click","#pro_bonus_type_2",function() {
            $('.txt_pro_bonus_amount').text('โบนัสจำนวนเปอร์เซ็นต์(%)');      
            $('#pro_bonus_amount').attr("placeholder",'โบนัสจำนวนเปอร์เซ็นต์(%)');
          });
          $(document).on("click",".pro_repeat",function() {
            let pro_repeat=$(this).val()*1;
            $('#pro_max_repeat').val('');
            $('.box_pro_max_repeat').show();
            $('.box_pro_weekly_day').hide();  
            switch (pro_repeat) {
                case 1: 
                $('.box_pro_max_repeat,.box_pro_weekly_day').hide();  
                $('.txt_pro_max_repeat').text('จำนวนครั้งการรับโปร / รับได้ครั้งเดียว');
                $('#pro_max_repeat').attr("placeholder",'จำนวนครั้งการรับโปร / รับได้ครั้งเดียว'); break; 
                case 2: 
                $('.txt_pro_max_repeat').text('จำนวนครั้งการรับโปร / รับได้รายวัน');
                $('#pro_max_repeat').attr("placeholder",'จำนวนครั้งการรับโปร / รับได้รายวัน'); break; 
                case 3: 
                  $('.box_pro_max_repeat').hide();  
                  $('.box_pro_weekly_day').show();
                  $('#pro_max_repeat').val('1');
                // $('.txt_pro_max_repeat').text('จำนวนครั้งการรับโปร / รับได้ตลอด');
                // $('#pro_max_repeat').attr("placeholder",'จำนวนครั้งการรับโปร / รับได้ตลอด');
                 break; 
                case 4: 
                $('.txt_pro_max_repeat').text('จำนวนครั้งการรับโปร / รับได้รายเดือน');
                $('#pro_max_repeat').attr("placeholder",'จำนวนครั้งการรับโปร / รับได้รายเดือน'); break; 
                case 5: 
                $('.txt_pro_max_repeat').text('จำนวนครั้งการรับโปร / รับได้รายปี');
                $('#pro_max_repeat').attr("placeholder",'จำนวนครั้งการรับโปร / รับได้รายปี'); break;  
              default:  break;
            }
           
          });
          
          $(document).on("click",".pro_deposit_type",function() {
                 $('.box_pro_deposit_type').hide();
                 if($(this).val()==1){
                  allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount'];
                  $('.pro_deposit_type_fix,.boxpro_turnover_amount').show();
                 }else if($(this).val()==2){
                  allow_empty=['description','promo_img1','pro_deposit_fix'];
                  $('.pro_deposit_type_min_max,.boxpro_turnover_amount').show();
                 }else if($(this).val()==3){
                  allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount','pro_deposit_fix'];
                  $('.pro_deposit_type_range').show();
                  $('.boxpro_turnover_amount').hide();
               }else if($(this).val()==4){
                  allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount','pro_deposit_fix'];
                  $('.pro_deposit_type_topuptoday,.boxpro_turnover_amount').show(); 
               }
          });

          $(document).on("click","#pro_withdraw_type_1",function() {
            $('.txt_pro_withdraw_max_amount').text('ถอนสูงสุดระบุเครดิต');
            $('.pro_withdraw_max_amount').attr("placeholder",'ถอนสูงสุดระบุเครดิต'); 
          });
          $(document).on("click","#pro_withdraw_type_2",function() {
            $('.txt_pro_withdraw_max_amount').text('ถอนสูงสุดจำนวนเท่า');
            $('.pro_withdraw_max_amount').attr("placeholder",'ถอนสูงสุดจำนวนเท่า'); 
          });

          $(document).on("click","#pro_turnover_type_1",function() {
            $('.txt_turnover_amount').text('ทำยอดเล่นจำนวนเต็ม');
            $('.txt_turnover_amount').attr("placeholder",'ทำยอดเล่นจำนวนเต็ม'); 
          });
          $(document).on("click","#pro_turnover_type_2",function() {
            $('.txt_turnover_amount').text('ทำยอดเล่นกี่เท่า');
            $('.txt_turnover_amount').attr("placeholder",'ทำยอดเล่นกี่เท่า'); 
          });
          $(document).on("click","#pro_turnover_type_3",function() {
            $('.txt_turnover_amount').text('ทำเทิร์นยอดบวก');
            $('.txt_turnover_amount').attr("placeholder",'ทำเทิร์นยอดบวก'); 
          });
          $(document).on("click","#pro_turnover_type_4",function() {
            $('.txt_turnover_amount').text('ทำเทิร์นจำนวนเท่า');
            $('.txt_turnover_amount').attr("placeholder",'ทำเทิร์นจำนวนเท่า'); 
          });

            var modal_form_pro=$('#modal-form-pro');  
            $('.MenuPomotionMain,.MenuPomotionlist').addClass('active');    

            $('.btn_edit_pro').click(function(){ 
                var pro_id = $(this).data('pro_id');  
                var m_order = $(this).data('m_order'); 
                $.ajax({
                    url: '<?=site_url("promotion/loadfrompromotion")?>',
                    type: 'get',
                    cache: false,
                    data: {pro_id: pro_id,m_order:m_order},
                    success: function(response){  
                    $('.modal-content').html(response);
                    if (typeof reinitdate !== "undefined") {reinitdate(); }   
                    modal_form_pro.modal('show'); 
                    }
                });
                });
     
               $(document).on("click",".btn-save",function() {  
                var proturn=$("input[name='pro_deposit_type']:checked").val();
                if(proturn==1){
                  allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount']; 
                 }else if(proturn==2){
                  allow_empty=['description','promo_img1','pro_deposit_fix']; 
                 }else if(proturn==3){
                  allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount','pro_deposit_fix']; 
                  }else if(proturn==4){
                   allow_empty=['description','promo_img1','pro_deposit_start_amount','pro_deposit_end_amount','pro_deposit_fix']; 
                  }  
                  var proturn=$("input[name='pro_repeat']:checked").val();
                  if(proturn==1){  allow_empty.push('pro_max_repeat');  }  
                  if(!(proturn==3)){ allow_empty.push('pro_weekly_day'); }
                  console.log('allow_empty',allow_empty);
                var data =$('#form_promotion').serializeArray();   
                for (const v of data) { console.log('v',v);
                      if(allow_empty.indexOf(v.name)!=-1){continue; } 
                      if ($.trim(v.value)=='') {
                        swal("กรุณากรอกข้อมูลให้ครบ!");  return false;
                      }
                  } 
                $.ajax({
                    url: '<?=site_url("promotion/SaveData")?>',
                    type: 'post',
                    cache: false, 
                    data: data,
                    beforeSend: function () {Myway_ShowLoader();},
                    success: function(response){  
                        if(response.status_code!=200){
                            swal("ไม่สามรถบันทึกข้อมูล!"); 
                          }else{
                            modal_form_pro.modal('hide');  
                            swal("บันทึกข้อมูลเรียบร้อยแล้ว!.")
                            .then((value) => {
                             window.location.reload();
                            }); 
                         }  
                        Myway_HideLoader();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("ไม่สามรถบันทึกข้อมูล!"); 
                        Myway_HideLoader();
                    }
                  });
                });

		  }); 
    function delete_imagePro(){
      var proid=$('input[name="pro_id"]').val();
      if(proid<=0){return}
      $('.btn_delete_imagepro').prop('disabled', true);
      $.ajax({
          url:'<?=site_url("promotion/DeleteImagePro")?>', 
          type:'post',
          data:{pro_id:proid},  
          cache: false, 
          beforeSend: function () {Myway_ShowLoader();},
          success:function(response){  
            Myway_HideLoader(); 
            $('.btn_delete_imagepro').prop('disabled', false);
            $('input[name="promo_img1"]').val(''); 
            if(response.status_code!=200){
               swal("ไม่สามรถบันทึกข้อมูล!"); 
              }else{
               swal("บันทึกข้อมูลเรียบร้อยแล้ว!.");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            Myway_HideLoader(); 
            $('.btn_delete_imagepro').prop('disabled', false);
            swal("ไม่สามรถบันทึกข้อมูล!"); 
          }
      });
    }  
    function upload_imge(){
                var data = new FormData(); 
                var files = $('input[name="promo_img1"]')[0].files;
                data.append('pro_id', $('input[name="pro_id"]').val());
                data.append('image_field',files[0]);
                if(files.length > 0 ){
                  $.ajax({
                        url:'<?=site_url("promotion/UploadImage")?>', 
                        type:'post',
                        data:data, 
                        contentType: false,
                        processData: false,
                        beforeSend: function () {Myway_ShowLoader();},
                        success:function(response){  
                          Myway_HideLoader(); 
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                         Myway_HideLoader(); 
                       }
                    });
                }
           }
      
		</script>
	</body>
</html>