<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>

  		<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/tables/datatable/datatables.min.css')?>">
		<style>
			.btn-icon{
				padding: 0.5rem 0.5rem;
			}
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">ผู้ดูแลระบบ</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">Admin
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="content-header-right col-md-6 col-12 text-right">
						<button <?php echo $hide_btn ?> class="btn btn-danger round btn-glow px-2 Btn_AddMember" type="button"><i class="la la-plus" style="vertical-align: bottom;"></i> เพิ่มพนักงาน</button>
					</div>
				</div>
				<div class="content-body">
					<section id="html">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">รายการพนักงาน</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">


											<table class="table table-striped table-bordered ajax-sourced">
												<thead>
													<tr>
														<th>USERNAME</th>
														<th>NAME</th> 
														<th>ทำรายการฝากได้สูงสุด/วัน</th>
														<th>IP</th>
														<th>STATUS</th>
														<th>Action</th>
													</tr>
												</thead>
										    </table>
										</div>
									</div>
								</div>
							</div>
				       </div>
					</section>
				</div>
			</div>
		</div>
		<div class="modal fade text-left" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="myModalLabel35">เพิ่มพนักงาน</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form class="myway-form-member" id="myway-form-member" autocomplete="off" method="post" action>
						<input type="hidden" name="pg_ids">
						<div class="modal-body">
							<div class="form-group">
								<label for="fname">Name</label>
								<input type="text" id="fname" class="form-control fname" name="fname" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="pggame_username">Username</label>
								<input type="text" id="pggame_username" class="form-control pggame_username" name="username" autocomplete="nope">
							</div>
							<div class="form-group">
								<label for="pg_password">Password</label>
								<input type="text" id="pg_password" class="form-control pg_password" name="password" autocomplete="nope">
							</div>
							<div class="form-group" style="display: none;" id="deposit_edit_allow">
								<label for="depositlimit">ทำรายการฝากได้สูงสุด/วัน</label>
								<input type="number" id="depositlimit" class="form-control depositlimit" name="depositlimit" autocomplete="off"  placeholder="ทำรายการฝากได้สูงสุด/วัน">
							</div>
							<div class="form-group" id="boxpg_level">
								<label for="pg_level">สิทธิ์การใช้งาน</label>
								<select id="pg_level" class="form-control pg_level" name="level">
									<?php 
									foreach ($role_all as $k => $v) {
									    ?>
										<option value="<?=$v->id?>"><?=$v->display_name?></option>
										<?php
									} 
									?> 
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<span class="form-error-text" style="color: #ff776d !important;"></span>
							<input type="button" class="btn btn-primary submit-member" value="บันทึก">
							<input type="button" class="btn btn-outline-light close-create-title" data-dismiss="modal" value="ยกเลิก">
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php echo $pg_footer?>
		<script type="text/javascript">
			var datatablePGGmage;
			$(function(){
				$('.MenuAdminMain').addClass('active');
				datatablePGGmage = $('.ajax-sourced').DataTable( {
			        "ajax": "<?php echo base_url('admin/list_json')?>",
			        "columns": [
					    null,
					    null,
						null,
						null,
					    { "width": "10%" },
					    { "width": "13%" }
					],
			        "initComplete": function( settings, json ) {
			        	pg_reload_Checked();
						
					}
			    } );
				$('.ajax-sourced thead').on('click', 'th', function () {
					pg_reload_Checked();
				});
				$('.ajax-sourced').on( 'length.dt', function ( e, settings, len ) {
				    pg_reload_Checked();
				});
				$('.ajax-sourced').on( 'search.dt', function () {
				    pg_reload_Checked();
				});
			    $('.ajax-sourced').on( 'page.dt', function () {
			    	pg_reload_Checked();
			    	
				} );
			    setTimeout(function(){
			   		$('.pggame_username,.fname,.pg_password').val("");
			    },500);
			    $('.Btn_AddMember').click(function(){
					$('#myway-form-member input:not([type=button])').val('');
					$('#myway-form-member').attr( 'url','<?=site_url("admin/add_new_member")?>');
					$('#myModalLabel35').html('เพิ่มพนักงาน');
					$('#boxpg_level').show();
			    	$('#modal-member').modal({
			            show: true,
			            backdrop: 'static',
			            keyboard: false
			        });
			    });
				$('#modal-member').on('hidden.bs.modal', function (event) {
                    $('.pggame_username,.fname,.pg_password').val("");
                    $('.form-error-text').html("");
                    $('.pggame_username').removeAttr('readonly');
                }); 
                $('.submit-member').click(function(e){  
                	$.ajax({
						type: 'POST',
						url: $('#myway-form-member').attr('url'), 
						dataType: "json",
						data: $('#myway-form-member').serialize(),
						beforeSend: function() { Myway_ShowLoader();},
						success: function(result, statusText, xhr, form) {
							if (result.Message == true) {
							datatablePGGmage.ajax.reload();
							pg_reload_Checked();
							Myway_HideLoader();
								model_success_pggame();
							} else {
								if (result.boolError == 1) {
									$('.form-error-text').html(result.ErrorText);
								} else {
									$('.modal').modal('hide');
								}
								Myway_HideLoader();
							}
						} 
					});
					
                }); 

                var SubmitFormDelete = $('.myway-form-delete');
                SubmitFormDelete.ajaxForm({
                    url: '<?php echo site_url("admin/delete")?>',
                    type: 'post',
                    dataType: 'json',
                    data: {},
                    beforeSubmit: function () {Myway_ShowLoader();},
                    success: function (result, statusText, xhr, form) {
                        if (result.Message == true) {
							datatablePGGmage.ajax.reload();
							pg_reload_Checked();
							Myway_HideLoader();$('.modal').modal('hide');
                        } else {
                            Myway_HideLoader();$('.modal').modal('hide');
                        }
                    }
                });
			});
			function ChangeStatusPGGamge(ids,pg_status){
				Myway_ShowLoader();
				$.ajax({
			        url: '<?php echo base_url("admin/update")?>',
			        type: 'post',
			        data: {pg_ids:ids,pg_status:pg_status},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {

			            	Myway_HideLoader();
			            } else {
			            	Myway_HideLoader();
			            }
			        }

			    });
			}

			function EditEmployee(ids){
				Myway_ShowLoader();
				$('[name="pg_ids"]').val(ids);
                $('#myModalLabel35').html('แก้ไขพนักงาน'); 
				$('#myway-form-member').attr( 'url','<?=site_url("admin/add_edit_member")?>');
				$('#boxpg_level').hide();
				$('#deposit_edit_allow').hide();
				$('.pggame_username').attr('readonly','readonly');
				$.ajax({
			        url: '<?php echo base_url("admin/list_json_id")?>',
			        type: 'post',
			        data: {ids:ids},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {
			            	var result = sr.Result[0];
			            	$('#pggame_username').val(result.username);
							$('#fname').val(result.fname);
							$('#pg_password').val(result.phash);
							$('#pg_level').val(result.level);
							$('#depositlimit').val(result.depositlimit);
							if(result.deposit_edit_allow){
								$('#deposit_edit_allow').show();
							} 
			            	$('#modal-member').modal({
					            show: true,
					            backdrop: 'static',
					            keyboard: false
					        });
			            	Myway_HideLoader();
			            } else {
			            	Myway_HideLoader();
			            }
			        }

			    });
				
			}
			function pg_reload_Checked(){
				setTimeout(function(){
			   		$(".switch_status_pggamge").bootstrapSwitch({
						onSwitchChange: function(e, state) {
							ChangeStatusPGGamge(e.target.attributes[2].value,state);
						}
					});
			    },500);
			}
		</script>
	</body>
</html>