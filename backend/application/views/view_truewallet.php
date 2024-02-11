<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>

  		<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/tables/datatable/datatables.min.css')?>">
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">True Wallet</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">รายการ TRUE WALLET
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="content-header-right col-md-6 col-12 text-right">
						<button <?php echo $hide_btn ?> class="btn btn-danger round btn-glow px-2 Btn_AddTrueWalllet" type="button"><i class="la la-plus" style="vertical-align: bottom;"></i> เพิ่ม TrueWalllet</button>
					</div>
				</div>
				<div class="content-body">
					<section id="html">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">รายการ TRUE WALLET</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">


											<table class="table table-striped table-bordered ajax-sourced">
												<thead>
													<tr>
														<th>ID</th>
														<th>EMAIL</th>
														<th>PHONE</th>
														<th>NAME</th>
														<th>STATUS</th>
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
		<div class="modal fade text-left" id="modal-true-wallet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="myModalLabel35">เพิ่มพนักงาน</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form class="myway-form-true-wallet" id="myway-form-true-wallet" autocomplete="off" method="post" action>
						<input type="hidden" name="pg_ids">
						<div class="modal-body">
							<div class="form-group">
								<label for="wallet_phone">เบอร์โทรศัพท์</label>
								<input type="text" id="wallet_phone" class="form-control wallet_phone" name="wallet_phone" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="wallet_show">Email</label>
								<input type="text" id="wallet_show" class="form-control wallet_show" name="wallet_show" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="wallet_name">ชื่อ - นามสกุล</label>
								<input type="text" id="wallet_name" class="form-control wallet_name" name="wallet_name" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="wallet_pass">รหัสผ่าน</label>
								<input type="text" id="wallet_pass" class="form-control wallet_pass" name="wallet_pass" autocomplete="off">
							</div>
						</div>
						<div class="modal-footer">
							<span class="form-error-text" style="color: #ff776d !important;"></span>
							<input type="button" class="btn btn-primary submit-true-wallet" value="บันทึก">
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
				$('.MenuTrueWalletMain').addClass('active');
				datatablePGGmage = $('.ajax-sourced').DataTable( {
			        "ajax": "<?php echo base_url('truewallet/list_json')?>",
			        "initComplete": function( settings, json ) {
			        	pg_reload_Checked();
						
					},
					"ordering": false,
					"searching": false,
					"lengthChange": false,
					"paging": false
			    } );
			    $('.Btn_AddTrueWalllet').click(function(){
			    	$('#modal-true-wallet').modal({
			            show: true,
			            backdrop: 'static',
			            keyboard: false
			        });
			    });
				$('#modal-true-wallet').on('hidden.bs.modal', function (event) {
                    $('.pggame_username,.fname,.pg_password').val("");
                    $('.form-error-text').html("");
                    $('.pggame_username').removeAttr('readonly');
                });
                $('.submit-true-wallet').click(function(){
                	$('.myway-form-true-wallet').submit();
                });
                var SubmitFormMember = $('.myway-form-true-wallet');
                SubmitFormMember.ajaxForm({
                    url: '<?php echo site_url("truewallet/add_edit_truewallet")?>',
                    type: 'post',
                    dataType: 'json',
                    data: {},
                    beforeSubmit: function () {Myway_ShowLoader();},
                    success: function (result, statusText, xhr, form) {
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

                var SubmitFormDelete = $('.myway-form-delete');
                SubmitFormDelete.ajaxForm({
                    url: '<?php echo site_url("truewallet/delete_truewallet")?>',
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
			        url: '<?php echo base_url("truewallet/update_truewallet_status")?>',
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

			function EditTruewallet(ids){
				Myway_ShowLoader();
				$('[name="pg_ids"]').val(ids);

				$('.pggame_username').attr('readonly','readonly');
				$.ajax({
			        url: '<?php echo base_url("truewallet/list_json_id")?>',
			        type: 'post',
			        data: {ids:ids},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {
			            	var result = sr.Result[0];

			            	$('#wallet_phone').val(result.wallet_phone);
							$('#wallet_show').val(result.wallet_show);
							$('#wallet_pass').val(result.wallet_pass);
							$('#wallet_name').val(result.wallet_name);
			            	$('#modal-true-wallet').modal({
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
		</script>
	</body>
</html>