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
                                                          <button  type="button" <?=$create_hide_btn?> class="btn btn-success btn-darken-3 btn_add_role">เพิ่มสิทธิ์</button>
														</div> 
                                                    </div>
													<div class="row match-height">
                                          <table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												  <thead>													
													<tr>
														<th class="text-center">ลำดับ</th>
														<th class="text-center">ชื่อสิทธิ์</th>  
														<th class="text-center">สถานะ</th>
                                                        <th class="text-center"></th>
													  </tr>
												     </thead>
                                                        <tbody> 
                                                            <?php
                                                            foreach ($roles as $k => $v) {
                                                            ?>
                                                            <tr >
                                                            <td><?=($k+1)?></td>
                                                            <td><?=$v->display_name?></td> 
                                                            <td><?=($v->status==1||99?'เปิด':'ปิด')?></td>
                                                            <td>
                                                                <button data-per_id='<?=$v->id?>' type="button" <?=$update_hide_btn?> class="btn btn-success btn-darken-3 btn_edit_per">ตั้งค่าสิทธิ์</button>
                                                                <button data-per_id='<?=$v->id?>'  <?=$delete_hide_btn?> data-display_name="<?=$v->display_name?>" type="button"  class="btn btn-danger btn-darken-3 btn_delete_per">ลบ</button>
                                                            </td>
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
            var modal_form_pro=$('#modal-form-pro'); 
            $('.MenuPermissions').addClass('active'); 

            $(document).on("click",".chk_all",function() {
                var check_all = $(this).data('group_name'); 
                if($(this).is(':checked')){
                    $('.'+check_all+'_chk_all').prop('checked', true);
                  }else{
                    $('.'+check_all+'_chk_all').prop('checked', false);
                } 
             });
             $()
              
            $('.btn_edit_per').click(function(){ 
                 
                var per_id = $(this).data('per_id');   
                $.ajax({
                    url: '<?=site_url("Permissions/update")?>',
                    type: 'get',
                    cache: false,
                    data: {per_id: per_id},
                    success: function(response){  
                    $('.modal-content').html(response); 
                      modal_form_pro.modal('show'); 
                    }
                });
                });
                

                $(document).on("click",".btn-save",function() {  
                var data =$('#form_per').serializeArray();  
                $.ajax({
                    url: '<?=site_url("Permissions/update")?>',
                    type: 'post',
                    cache: false, 
                    data: data,
                    beforeSend: function () {Myway_ShowLoader();},
                    success: function(response){  
                        if(response.status_code!=200){
                            swal("ไม่สามรถบันทึกข้อมูล!"); 
                          }else{
                            modal_form_pro.modal('hide');  
                            swal("บันทึกข้อมูลเรียบร้อยแล้ว!."); 
                         }  
                        Myway_HideLoader();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("ไม่สามรถบันทึกข้อมูล!"); 
                        Myway_HideLoader();
                    }
                  });
                });

                $('.btn_add_role').click(function(){      
                 $.ajax({
                     url: '<?=site_url("Permissions/create")?>',
                     type: 'get',
                     cache: false,
                     data: {},
                     success: function(response){  
                     $('.modal-content').html(response); 
                       modal_form_pro.modal('show'); 
                     }
                 });
              });

                $(document).on("click",".btn-role-save",function() {  
                var data =$('#form_add_role').serializeArray();  
                $.ajax({
                    url: '<?=site_url("Permissions/create")?>',
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
                $('.btn_delete_per').click(function(){     
                var result = confirm("ยืนยันการลบข้อมูลสิทธิ์ "+$(this).data('display_name')+" ?");
                if (!result) {return;} 
                var role_id = $(this).data('per_id');    
                 $.ajax({
                     url: '<?=site_url("Permissions/delete")?>',
                     type: 'post',
                     cache: false,
                     data: {role_id:role_id},
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
      
		</script>
	</body>
</html>