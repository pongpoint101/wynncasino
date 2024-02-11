<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
	<style type="text/css">
		.paddingboxpg {
			padding: 6px 9px !important;
		}
		span.text-right.block { display: flex;align-items: center;justify-content: center;}
		.table tbody th, .table tbody td{padding: 3px;}
	</style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">ถอนเงิน</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active">รอทำรายการ
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
									<h4 class="card-title">รอทำรายการ</h4>
								</div>
								<div class="card-content collpase show">
									<div class="card-body card-dashboard">
									  <div class="table-responsive">
										<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
											<thead>
												<tr>
													<th class="text-center align-middle">Date - Time</th>
													<th class="text-center align-middle">USERNAME</th>
													<th class="text-center align-middle">NAME</th>
													<th class="text-center align-middle">BANK</th>
													<th class="text-center align-middle">BALANCE</th>
													<th class="text-center align-middle">STATUS</th>
													<th class="text-center align-middle">PROMOTION</th>
													<th>[<label class="text-secondary">VZ - Vizplay(ออโต้)</label>] [<label class="text-warning">TW - TrueWallet(ออโต้)</label>] <br> [<label class="text-primary">A - ธนาคาร(ออโต้)</label>] <label class="text-success"> [M - ถอนมือ]</label> [<label class="text-info">C - คืนเครดิต</label>] [<label class="text-danger">R - ยกเลิก</label>]</th>
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

	<div class="modal animated pulse text-left" id="modal-withdraw-truewallet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel38" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
				<div class="modal-body mt-2 text-center">
					<i class="la font-large-2 warning rounded-circle border p-2 width-100 height-100 border-warning">TW</i>
					<h3 class="text-center mt-2 text-warning">ทำรายการถอนออโต้ ทรูมันนี่ วอลเล็ท ?</h3>
					<h6 class="grey-blue text-center block PGGameUsernameText"></h6>
					<h6 class="grey-blue text-center block PGGameAmountText"></h6>
					 <div class="table-responsive">
					 <table class="table table-bordered table-sm table-success text-primary" id="tw_list">
						<thead  >
							<tr> 
							<th scope="col">เลือกบัญชีถอน</th>
							<th scope="col">บัญชี ทรูมันนี่</th>  
							</tr>
						</thead>
						<tbody> </tbody> </table>
					 </div>
					 <br><textarea rows="2" class="form-control remark_detail_truewallet" name="remark_detail" placeholder="ใส่หมายเหตุ..."></textarea>
				</div>

				<div class="modal-footer border-0 text-center justify-content-center">
					<input type="button" style="border-radius: 0.7rem;" class="btn btn-danger width-100 submit_form_withdraw_truewallet" value="ยืนยัน">
					<input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-dark width-100" data-dismiss="modal" value="ยกเลิก">
				</div>
			</div>
		</div>
	</div>

	<div class="modal animated pulse text-left" id="modal-success-withdraw_vizplay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel39" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
				<div class="modal-body mt-2 text-center">
					<i class="la font-large-3 secondary rounded-circle border p-1 width-100 height-100 border-secondary">VZ</i>
					<h3 class="text-center mt-2 text-secondary" >ทำรายการถอนออโต้ VizPlay ?</h3>
					<h6 class="grey-blue text-center block PGGameUsernameText"></h6>
					<h6 class="grey-blue text-center block PGGameAmountText"></h6>
					<br><textarea rows="2" class="form-control remark_detail_vizplay" name="remark_detail" placeholder="ใส่หมายเหตุ..."></textarea>
				</div>
				<div class="modal-footer border-0 text-center justify-content-center">
					<input type="button" style="border-radius: 0.7rem;" class="btn btn-danger width-100 submit-form-success-withdraw_vizplay" value="ยืนยัน">
					<input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-dark width-100" data-dismiss="modal" value="ยกเลิก">
				</div>
			</div>
		</div>
	</div>

	<div class="modal animated pulse text-left" id="modal-success-withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel37" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
				<div class="modal-body mt-2 text-center">
					<i class="la font-large-3 primary rounded-circle border p-2 width-100 height-100 border-primary">A</i>
					<h3 class="text-center mt-2 text-primary" >ทำรายการถอนออโต้ ?</h3>
					<h6 class="grey-blue text-center block PGGameUsernameText"></h6>
					<h6 class="grey-blue text-center block PGGameAmountText"></h6>
					<br><textarea rows="2" class="form-control remark_detail_auto" name="remark_detail" placeholder="ใส่หมายเหตุ..."></textarea>
				</div>
				<div class="modal-footer border-0 text-center justify-content-center">
					<input type="button" style="border-radius: 0.7rem;" class="btn btn-danger width-100 submit-form-success-withdraw" value="ยืนยัน">
					<input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-dark width-100" data-dismiss="modal" value="ยกเลิก">
				</div>
			</div>
		</div>
	</div>
	<div class="modal animated pulse text-left" id="modal-manual-withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel61" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
				<div class="modal-body mt-2 text-center">
					<i class="la font-large-3 success rounded-circle border p-2 width-100 height-100 border-success">M</i>
					<h3 class="text-center mt-2" style="color:#776c95;">ทำรายการถอนมือ ?</h3>
					<h6 class="grey-blue text-center block manual_PGGameUsernameText"></h6>
					<h6 class="grey-blue text-center block manual_PGGameNameText"></h6>
					<h6 class="grey-blue text-center block manual_PGGameBankText"></h6>
					<h6 class="grey-blue text-center block manual_PGGameAmountText"></h6>
					<input type="file" name="slip" accept="image/*" class="form-control slip" onchange="upload_imge()" style="background-color: beige;">
					<input type="hidden" class="pg_w_ids_slip" name="pg_w_ids_slip">
					<br><textarea rows="2" class="form-control remark_detail_manual" name="remark_detail" placeholder="ใส่หมายเหตุ..."></textarea>
				</div>
				<div class="modal-footer border-0 text-center justify-content-center">
					<input type="button" style="border-radius: 0.7rem;" class="btn btn-danger width-100 submit-form-manual-withdraw" value="ยืนยัน">
					<input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-dark width-100" data-dismiss="modal" value="ยกเลิก">
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade text-left" id="modal-refund-credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel91" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel35"><i class="la white rounded-circle bg-info paddingboxpg">C</i> ใส่เหตุผลคืนเครดิต</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<form class="withdraw-form-refund-credit" id="withdraw-form-refund-credit" autocomplete="off" method="post" action>
					<div class="modal-body">
						<input type="hidden" class="refund_pg_w_ids" name="pg_w_ids">
						<input type="hidden" class="refund_pg_m_ids" name="pg_m_ids">
						<div class="row mt-2">
							<div class="col-md-12">
								<div class="form-group">
									<div class="position-relative has-icon-left">
										<textarea rows="5" class="form-control pg_remark" name="remark" placeholder="ใส่เหตุผลคืนเครดิต..."></textarea>
										<div class="form-control-position" style="top: 12px;">
											<i class="ft-file"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<span class="form-error-text-del-credit" style="color: #ff776d !important;"></span>
						<input type="button" class="btn btn-danger submit-refund-credit" value="ทำรายการ">
						<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade text-left" id="modal-cancle-credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel48" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel35"><i class="la white rounded-circle bg-danger paddingboxpg">R</i> ใส่เหตุผลยกเลิก</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<form class="withdraw-form-cancle-credit" id="withdraw-form-cancle-credit" autocomplete="off" method="post" action>
					<div class="modal-body">
						<input type="hidden" class="cancle_pg_w_ids" name="pg_w_ids">
						<input type="hidden" class="cancle_pg_m_ids" name="pg_m_ids">
						<div class="row mt-2">
							<div class="col-md-12">
								<div class="form-group">
									<div class="position-relative has-icon-left">
										<textarea rows="5" class="form-control pg_remark" name="remark" placeholder="ใส่เหตุผลยกเลิก..."></textarea>
										<div class="form-control-position" style="top: 12px;">
											<i class="ft-file"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<span class="form-error-text-del-credit" style="color: #ff776d !important;"></span>
						<input type="button" class="btn btn-danger submit-cancle-credit" value="ทำรายการ">
						<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
					</div>
				</form>
			</div>
		</div>
	</div>
	<form onsubmit="return false;" class="withdraw-form-success-withdraw" method="post" action>
	    <input type="hidden" class="success_pg_tw_id" name="tw_id">
		<input type="hidden" class="success_pg_wd_type" name="wd_type">
		<input type="hidden" class="success_pg_w_ids" name="pg_w_ids">
		<input type="hidden" class="success_pg_m_ids" name="pg_m_ids">
		<input type="hidden" class="success_promo_ids" name="promo_ids">
		<input type="hidden" class="remark_detail" name="remark_detail">
	</form>
	<form onsubmit="return false;" class="withdraw-form-manual-withdraw" method="post" action>
		<input type="hidden" class="manual_pg_w_ids" name="pg_w_ids">
		<input type="hidden" class="manual_pg_m_ids" name="pg_m_ids">
		<input type="hidden" class="manual_promo_ids" name="promo_ids">
		<input type="hidden" class="remark_detail" name="remark_detail">
	</form>
	<?php echo $pg_footer ?>
	<script type="text/javascript">
		var datatablePGGmage;
		$(function() {
			$('.MenuWithdrawpending').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable({
				"ajax": "<?php echo base_url('withdraw/list_withdraw_padding_json') ?>",
				"columns": [
					null,
					null,
					null,
					null,
					null,
					null, 
					null,
					null,
				],
				"order": [
					[0, "desc"]
				],
				"ordering": false,
				"searching": false,
				"lengthChange": false,
				"paging": false,

				// "processing": true,
				// "serverSide": true
			});
			datatablePGGmage.on('xhr.dt', function ( e, settings, json, xhr ) {
                soundplay_withdraw_padding(json);    
            });
			setInterval(function() { 
				$.ajax({
					url: '<?php echo base_url("withdraw/check_have_session") ?>',
					type: 'post', 
					success: function(res) { 
						if(res!=null || res!=undefined){
							datatablePGGmage.ajax.reload();
						} 
					} 
				});

				
			}, 10000);
			$('#modal-success-withdraw').on('hidden.bs.modal', function(event) {
				$('.success_pg_w_ids,.success_pg_m_ids,.success_promo_ids,.success_pg_wd_type,.success_pg_tw_id,.remark_detail').val("");
				$('.PGGameUsernameText,.PGGameAmountText').html("");
			});
			$('#modal-manual-withdraw').on('hidden.bs.modal', function(event) {
				$('.manual_pg_w_ids,.manual_pg_m_ids,.manual_promo_ids,.pg_w_ids_slip,.remark_detail').val("");
				$('.manual_PGGameUsernameText,.manual_PGGameAmountText,.manual_PGGameNameText,.manual_PGGameBankText').html("");
			});
			$('#modal-refund-credit').on('hidden.bs.modal', function(event) {
				$('.refund_pg_w_ids,.refund_pg_m_ids,.pg_remark').val("");
			});
			$('#modal-cancle-credit').on('hidden.bs.modal', function(event) {
				$('cancle_pg_w_ids,cancle_pg_m_ids,.pg_remark').val("");
			});

			$('.submit-form-success-withdraw').click(function() {
				$('.remark_detail').val($('.remark_detail_auto').val());
				$('.withdraw-form-success-withdraw').submit();
			});
			$('.submit_form_withdraw_truewallet').click(function() {
				$('.remark_detail').val($('.remark_detail_truewallet').val());
				$('.withdraw-form-success-withdraw').submit();
			});
			$('.submit-form-success-withdraw_vizplay').click(function() {
				$('.remark_detail').val($('.remark_detail_vizplay').val());
				$('.withdraw-form-success-withdraw').submit();
			}); 

			var SubmitFormWithdrawSuccess = $('.withdraw-form-success-withdraw');
			SubmitFormWithdrawSuccess.ajaxForm({
				url: '<?php echo site_url("withdraw/withdraw_auto") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					$('.modal').modal('hide');
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload();
						Myway_HideLoader();
						var swal_wrapper = document.createElement('div');
						swal_wrapper.innerHTML = result.ErrorText; 
						swal({
						title: 'success', 
						content: swal_wrapper,
						icon: "success" 
						});
					} else {
						// if (result.boolError == 1) {
						// 	$('.form-error-text').html(result.ErrorText);
						// } else {
						$('.modal').modal('hide');
						// }
						Myway_HideLoader();
						var swal_wrapper = document.createElement('div');
						swal_wrapper.innerHTML = result.ErrorText; 
						swal({
						title: 'Error', 
						content: swal_wrapper,
						icon: "error" 
						});
					}
				}
			});
			$('.submit-form-manual-withdraw').click(function() {
				$('.remark_detail').val($('.remark_detail_manual').val());
				$('.withdraw-form-manual-withdraw').submit();
			});
			var SubmitFormWithdrawManual = $('.withdraw-form-manual-withdraw');
			SubmitFormWithdrawManual.ajaxForm({
				url: '<?php echo site_url("withdraw/withdraw_manual") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload();
						Myway_HideLoader();
						model_success_pggame();
						$('#modal-manual-withdraw').modal('hide');
					} else {
						// if (result.boolError == 1) {
						// 	$('.form-error-text').html(result.ErrorText);
						// } else {
						$('.modal').modal('hide');
						// }
						Myway_HideLoader();
						swal("Error!", result.ErrorText, "error");
					}
				}
			});
			$('.submit-refund-credit').click(function() {
				$('.withdraw-form-refund-credit').submit();
			});
			var SubmitFormWithdrawRefund = $('.withdraw-form-refund-credit');
			SubmitFormWithdrawRefund.ajaxForm({
				url: '<?php echo site_url("withdraw/refund_credit") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload();
						Myway_HideLoader();
						model_success_pggame();
					} else {
						// if (result.boolError == 1) {
						// 	$('.form-error-text').html(result.ErrorText);
						// } else {
						$('.modal').modal('hide');
						// }
						Myway_HideLoader();
						swal("Error!", result.ErrorText, "error");
					}
				}
			});
			$('.submit-cancle-credit').click(function() {
				$('.withdraw-form-cancle-credit').submit();
			});
			var SubmitFormWithdrawCancle = $('.withdraw-form-cancle-credit');
			SubmitFormWithdrawCancle.ajaxForm({
				url: '<?php echo site_url("withdraw/cancle_credit") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload();
						Myway_HideLoader();
						model_success_pggame();
					} else {
						// if (result.boolError == 1) {
						// 	$('.form-error-text').html(result.ErrorText);
						// } else {
						$('.modal').modal('hide');
						// }
						Myway_HideLoader();
						swal("Error!", result.ErrorText, "error");
					}
				}
			});
		});
		function upload_imge() {
			var data = new FormData();
			var files = $('input[name="slip"]')[0].files;
			data.append('image_field', files[0]);
			data.append('pg_w_ids_slip', $('.pg_w_ids_slip').val());

			if (files.length > 0) {
				$.ajax({
					url: '<?= site_url("Withdraw/UploadImage") ?>',
					type: 'post',
					data: data,
					contentType: false,
					processData: false,
					beforeSend: function() {
						Myway_ShowLoader();
					},
					success: function(response) {
						Myway_HideLoader();
					},
					error: function (x, status, error) {
						console.log('x ',x);
					}
				});
			}
		}

		function cancel_withdraw(pg_w_ids, pg_m_ids, promo_ids) {
			//modal-success-withdraw
			$('.cancle_pg_w_ids').val(pg_w_ids);
			$('.cancle_pg_m_ids').val(pg_m_ids);
			$('#modal-cancle-credit').modal({
				show: true,
				backdrop: 'static',
				keyboard: false
			});
		}

		function success_withdraw(pg_w_ids, pg_m_ids, promo_ids) {
			$('#remark_detail_truewallet').val('');
			$('#remark_detail_auto').val('');
			$('#remark_detail_vizplay').val('');
			$('#remark_detail_manual').val('');
			Myway_ShowLoader();
			$.ajax({
				url: '<?php echo base_url("withdraw/list_withdraw_id_json") ?>',
				type: 'post',
				data: {
					ids: pg_w_ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0];

						$('.success_pg_wd_type').val(1);
						$('.success_pg_w_ids').val(pg_w_ids);
						$('.success_pg_m_ids').val(pg_m_ids);
						$('.success_promo_ids').val(promo_ids);

						$('.PGGameUsernameText').html(result.username);
						$('.PGGameAmountText').html(result.amount);


						$('#modal-success-withdraw').modal({
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

		function success_withdraw_manual(pg_w_ids, pg_m_ids, promo_ids) {
			$('#remark_detail_truewallet').val('');
			$('#remark_detail_auto').val('');
			$('#remark_detail_vizplay').val('');
			$('#remark_detail_manual').val('');
			Myway_ShowLoader();
			$.ajax({
				url: '<?php echo base_url("withdraw/list_withdraw_id_json") ?>',
				type: 'post',
				data: {
					ids: pg_w_ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0];
						$('.pg_w_ids_slip').val(pg_w_ids);
						$('.manual_pg_w_ids').val(pg_w_ids);
						$('.manual_pg_m_ids').val(pg_m_ids);
						$('.manual_promo_ids').val(promo_ids);

						$('.manual_PGGameUsernameText').html(result.username);
						$('.manual_PGGameAmountText').html(result.amount);
						$('.manual_PGGameNameText').html(result.name);
						$('.manual_PGGameBankText').html(result.bank);

						$('#modal-manual-withdraw').modal({
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

		function refund_credit(pg_w_ids, pg_m_ids, promo_ids) {
			$('.refund_pg_w_ids').val(pg_w_ids);
			$('.refund_pg_m_ids').val(pg_m_ids);
			$('#modal-refund-credit').modal({
				show: true,
				backdrop: 'static',
				keyboard: false
			});
		}
		function truewallet_withdraw(pg_w_ids, pg_m_ids, promo_ids){
			$('#remark_detail_truewallet').val('');
			$('#remark_detail_auto').val('');
			$('#remark_detail_vizplay').val('');
			$('#remark_detail_manual').val('');
			Myway_ShowLoader();
			$.ajax({
				url: '<?php echo base_url("withdraw/list_withdraw_id_json") ?>',
				type: 'post',
				data: {
					ids: pg_w_ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0];
						$('.success_pg_tw_id').val($("input[name='twselect']:checked").val());
						$('.success_pg_wd_type').val(2);
						$('.success_pg_w_ids').val(pg_w_ids);
						$('.success_pg_m_ids').val(pg_m_ids);
						$('.success_promo_ids').val(promo_ids);

						$('.PGGameUsernameText').html(result.username);
						$('.PGGameAmountText').html(result.amount);
						var tmp='';
						if(typeof result['tw']==='object'){
							$.each(result['tw'], function( index, value ) {
								var t_checked='';
								if(index==0){t_checked='checked';}
							    tmp+=`<tr>  
								<td style="padding: 5px;">
								  <div class="form-check">
									<input class="form-check-input" type="radio" style="cursor: pointer;" name="twselect" value="${value.id}" ${t_checked}>
									</div>
								</td> 
								<td style="padding: 5px;">${value.twName}(${value.twNumber})</td>
								</tr> `;
						    });
							$("#tw_list tbody").empty().append(tmp);
						} 
						$('#modal-withdraw-truewallet').modal({
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

		function vizplay_withdraw(pg_w_ids, pg_m_ids, promo_ids){
			$('#remark_detail_truewallet').val('');
			$('#remark_detail_auto').val('');
			$('#remark_detail_vizplay').val('');
			$('#remark_detail_manual').val('');
			Myway_ShowLoader();
			$.ajax({
				url: '<?php echo base_url("withdraw/list_withdraw_id_json") ?>',
				type: 'post',
				data: {
					ids: pg_w_ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0]; 
						$('.success_pg_wd_type').val(3);
						$('.success_pg_w_ids').val(pg_w_ids);
						$('.success_pg_m_ids').val(pg_m_ids);
						$('.success_promo_ids').val(promo_ids);

						$('.PGGameUsernameText').html(result.username);
						$('.PGGameAmountText').html(result.amount); 
						$('#modal-success-withdraw_vizplay').modal({
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
		//pg_m_ids
		// success_withdraw
		// success_withdraw_manual
		// refund_credit
	</script>
</body>

</html>