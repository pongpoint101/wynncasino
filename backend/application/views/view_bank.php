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
						<h3 class="content-header-title mb-0 d-inline-block">ธนาคาร</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">รายการธนาคาร

									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="content-header-right col-md-6 col-12 text-right">
						<button <?php echo $hide_btn ?> class="btn btn-danger round btn-glow px-2 Btn_AddTrueWalllet" type="button"><i class="la la-plus" style="vertical-align: bottom;"></i> เพิ่ม ธนาคาร</button>
					</div>
				</div>
				<div class="content-body">
					<section id="html">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">รายการธนาคาร</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">


											<table class="table table-striped table-bordered ajax-sourced">
												<thead>
													<tr>
														<th>ID</th>
		                                                <th>Bank</th>
		                                                <th>Name</th>
		                                                <th>Acct. No.</th>
		                                                <th>ประเภท</th>
		                                                <th>Statement</th>
		                                                <th>แสดงหน้าเว็บ</th>
		                                                <th>ดึงยอด</th>
														<th>ACTION</th>
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
		<div class="modal fade text-left" id="modal-bank-setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="myModalLabel35">เพิ่ม/แก้ไข ธนาคาร</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form class="myway-form-m-bank-setting" id="myway-form-m-bank-setting" autocomplete="off" method="post" action>
						<input type="hidden" name="pg_ids">

						<div class="modal-body">
							<div class="form-group">
								<label for="pg_lname">ธนาคาร</label>
								<select id="bank_code" name="bank_code" class="form-control border-primary bank_code">
									<option value="0">เลือกธนาคาร</option>
									<?php
									if($RSBank['num_rows'] > 0){
										foreach ($RSBank['result_array'] as $item) {
									?>
									<option value="<?php echo $item['bank_code'];?>"><?php echo $item['bank_short'];?> - <?php echo $item['short_name'];?></option>
									<?php
										}
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="bank_account">หมายเลขบัญชี</label>
								<input type="text" id="bank_account" class="form-control bank_account" name="bank_account" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="account_name">ชื่อบัญชี</label>
								<input type="text" id="account_name" class="form-control account_name" name="account_name" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="m_bank_setting_type">ประเภท</label>
								<select id="m_bank_setting_type" name="m_bank_setting_type" class="form-control border-primary m_bank_setting_type">
									<option value="1">ฝาก</option>
									<option value="2">ถอน</option>
								</select>
							</div>
							<div class="form-group boxusername">
								<label for="m_bank_setting_username">Username</label>
								<input type="text" id="m_bank_setting_username" class="form-control m_bank_setting_username" name="m_bank_setting_username" autocomplete="off">
							</div>
							<div class="form-group boxpassword">
								<label for="m_bank_setting_password">Password</label>
								<input type="text" id="m_bank_setting_password" class="form-control m_bank_setting_password" name="m_bank_setting_password" autocomplete="off">
							</div>
						</div>
						<div class="modal-footer">
							<span class="form-error-text" style="color: #ff776d !important;"></span>
							<input type="button" class="btn btn-primary submit-m-bank-setting" value="บันทึก">
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
			$('.MenuBanklist').addClass('active');
				datatablePGGmage = $('.ajax-sourced').DataTable( {
			        "ajax": "<?php echo base_url('bank/list_json')?>",
			        "initComplete": function( settings, json ) {
			        	pg_reload_Checked();
			        	pg_trx_reload_Checked();
						
					},
					"columns": [
					    null,
					    null,
					    null,
					    null,
					    null,
					    null,
					    null,
					    null,
					    { "width": "20%" }
					],
					"ordering": false,
					"searching": false,
					"lengthChange": false,
					"paging": false
			    } );
			    $('.Btn_AddTrueWalllet').click(function(){
					showaddData();
			    	$('#modal-bank-setting').modal({
			            show: true,
			            backdrop: 'static',
			            keyboard: false
			        });
			    });
				$('#modal-bank-setting').on('hidden.bs.modal', function (event) {
                    $('#bank_code').val(0);
					$('#bank_account').val("");
					$('#account_name').val("");
					$('#m_bank_setting_type').val(1);
					$('#m_bank_setting_username').val("");
					$('#m_bank_setting_password').val("");
                });
                $('.submit-m-bank-setting').click(function(){
                	$('.myway-form-m-bank-setting').submit();
                });
                var SubmitFormMBankSetting = $('.myway-form-m-bank-setting');
                SubmitFormMBankSetting.ajaxForm({
                    url: '<?php echo site_url("bank/add_edit_m_bank_setting")?>',
                    type: 'post',
                    dataType: 'json',
                    data: {},
                    beforeSubmit: function () {Myway_ShowLoader();},
                    success: function (result, statusText, xhr, form) {
                        if (result.Message == true) {
							datatablePGGmage.ajax.reload();
							pg_reload_Checked();
							pg_trx_reload_Checked();
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

                var SubmitFormDelete = $('.myway-form-delete');
                SubmitFormDelete.ajaxForm({
                    url: '<?php echo site_url("bank/delete_bank_setting")?>',
                    type: 'post',
                    dataType: 'json',
                    data: {},
                    beforeSubmit: function () {Myway_ShowLoader();},
                    success: function (result, statusText, xhr, form) {
                        if (result.Message == true) {
							datatablePGGmage.ajax.reload();
							pg_reload_Checked();
							pg_trx_reload_Checked();
							Myway_HideLoader();$('.modal').modal('hide');
                        } else {
                            Myway_HideLoader();$('.modal').modal('hide');
                        }
                    }
                });
			});
			function showaddData(){
               $('.boxusername,.boxpassword').show();
			}
			function hideddData(){
               $('.boxusername,.boxpassword').hide();
			}
			function ChangeStatusPGGamge(ids,pg_status){
				Myway_ShowLoader();
				$.ajax({
			        url: '<?php echo base_url("bank/update_bank_setting_status")?>',
			        type: 'post',
			        data: {ids:ids,pg_status:pg_status},
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

			function ChangeTRXPGGamge(ids,pg_status){
				Myway_ShowLoader();
				$.ajax({
			        url: '<?php echo base_url("bank/update_bank_setting_trx")?>',
			        type: 'post',
			        data: {ids:ids,pg_status:pg_status},
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

			function EditBankSetting(ids){
				Myway_ShowLoader();
				hideddData();
				$('[name="pg_ids"]').val(ids);

				$('.pggame_username').attr('readonly','readonly');
				$.ajax({
			        url: '<?php echo base_url("bank/list_json_id")?>',
			        type: 'post',
			        data: {ids:ids},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {
			            	var result = sr.Result[0];
							
			            	$('#bank_code').val(result.bank_code);
							$('#bank_account').val(result.bank_account);
							$('#account_name').val(result.account_name);
							$('#m_bank_setting_type').val(result.type);
							$('#m_bank_setting_username').val(result.username);
							$('#m_bank_setting_password').val(result.password);
			            	$('#modal-bank-setting').modal({
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
							ChangeStatusPGGamge(e.target.attributes[2].value,state)
						}
					});
			    },500);
			}
			function pg_trx_reload_Checked(){
				setTimeout(function(){
			   		$(".switch_get_trx_pggamge").bootstrapSwitch({
						onSwitchChange: function(e, state) {
							ChangeTRXPGGamge(e.target.attributes[2].value,state)
						}
					});
			    },500);
			}
		</script>
	</body>
</html>