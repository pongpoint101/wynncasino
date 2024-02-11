<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style>
			.btn-action {
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
						<h3 class="content-header-title mb-0 d-inline-block">แนะนำเพื่อน</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">จัดการรายการแนะนำเพื่อน
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
										<h4 class="card-title">จัดการรายการแนะนำเพื่อน</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<div class="table-responsive">
											<table class="table table-striped  ajax-sourced pg-data-table table-middle">
												<thead>
													<tr class="text-center">
														<th>ยูสเซอร์เนม</th>
														<th>ชื่อ-สกุล</th>
														<th>เพื่อนที่แนะนำ(1)</th>
														<!-- <th>เพื่อนที่แนะนำ(2)</th> -->
														<th>แก้ไข</th>
													</tr>
												</thead>
										    </table>
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
		<div class="modal fade text-left" id="modal-aff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="myModalLabel35">แก้ไขข้อมูลสมาชิก <font class="UserNameText"></font>
						</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form class="myway-form-aff" id="myway-form-aff" autocomplete="off" method="post" action>
						<input type="hidden" name="pg_ids">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="pg_l1">ผู้แนะนำ (ลำดับ1)</label>
										<input type="text" id="pg_l1" class="form-control pg_l1" name="pg_l1" autocomplete="off">
									</div>
								</div>
								<!-- <div class="col-md-6">
									<div class="form-group">
										<label for="pg_l2">ผู้แนะนำ (ลำดับ2)</label>
										<input type="text" id="pg_l2" class="form-control pg_l2" name="pg_l2" autocomplete="off">
									</div>
								</div> -->
							</div>
						</div>
						<div class="modal-footer">
							<span class="form-error-text" style="color: #ff776d !important;"></span>
							<input type="button" class="btn btn-primary submit-aff" value="บันทึก">
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
			$('.MenuAffiliateManage').addClass('active');

			datatablePGGmage =$('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('affiliate/list_manage_aff_json')?>",
				// "order": [[ 2, "asc" ]],
				"pageLength": 50,
				"ordering": false
			} );
			$('#modal-aff').on('hidden.bs.modal', function(event) {
				$('.UserNameText').html("");
				$('.pg_l1').val("");
				// $('.pg_l2').val("");
			});
			$('.submit-aff').click(function() {
				$('.myway-form-aff').submit();
			});
			var SubmitFormMember = $('.myway-form-aff');
			SubmitFormMember.ajaxForm({
				url: '<?php echo site_url("affiliate/add_edit_aff") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload(null, false);
						Myway_HideLoader();
						model_success_pggame();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text').html(result.ErrorText);
							swal("Error!", result.ErrorText, "error");
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});
		});
		function EditAff(ids) {
			Myway_ShowLoader();
			$('[name="pg_ids"]').val(ids);
            $('.form-error-text').html('');
			$.ajax({
				url: '<?php echo base_url("affiliate/list_json_show_edit_aff") ?>',
				type: 'post',
				data: {
					ids: ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0];
						$('.UserNameText').html(" - " + result.username);
						$('.pg_l1').val(result.group_af_l1);
						// $('.pg_l2').val(result.group_af_l2);
						$('#modal-aff').modal({
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
		</script>
	</body>
</html>