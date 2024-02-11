<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
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

						<div class="col-md-6">
							<div class="card">

								<div class="card-content collapse show">
									<div class="card-body">

										<form onsubmit="return false;" class="myway-form-setting-website-01" id="myway-form-setting-website-01" autocomplete="off" method="post" action>
											<div class="form-body">
												<h4 class="form-section"><i class="ft-airplay"></i> ข้อมูลเว็บไซต์</h4>
												<div class="row">
                                                <?php if(in_array($create_user_by_admin,[1,2])){ ?>
												<div class="col-md-12">
													<div class="form-group">
														<label for="com_type">เพิ่มสมาชิกโดย Admin</label>
														<div class="input-group">
															<div class="d-inline-block custom-control custom-radio mr-1">
																<input type="radio" <?=$create_user_by_admin==1?'checked':''?> name="create_user_by_admin" class="custom-control-input create_user_by_admin" value="1"id="create_user_by_admin1" />
																<label class="custom-control-label" for="create_user_by_admin1">เปิด</label>
															</div>
															<div class="d-inline-block custom-control custom-radio">
																<input type="radio" <?=$create_user_by_admin==2?'checked':''?> name="create_user_by_admin" class="custom-control-input create_user_by_admin" value="2" id="create_user_by_admin2" />
																<label class="custom-control-label" for="create_user_by_admin2">ปิด</label>
															</div> 
														</div>
													 </div>
												</div> 
                                                    <?php } ?>
												<div class="col-md-12">
													<div class="form-group">
														<label for="com_type">เปิด-ปิดระบบ</label>
														<div class="input-group">
															<div class="d-inline-block custom-control custom-radio mr-1">
																<input type="radio" <?=$maintenance==1?'checked':''?> name="maintenance" class="custom-control-input maintenance" value="1"id="maintenance1" />
																<label class="custom-control-label" for="maintenance1">เปิดระบบทั้งหมด</label>
															</div>
															<div class="d-inline-block custom-control custom-radio">
																<input type="radio" <?=$maintenance==2?'checked':''?> name="maintenance" class="custom-control-input maintenance" value="2" id="maintenance2" />
																<label class="custom-control-label" for="maintenance2">ปิดรับสมัครสมาชิก</label>
															</div> 

															<div class="d-inline-block custom-control custom-radio">
																<input type="radio" <?=$maintenance==3?'checked':''?> name="maintenance" class="custom-control-input maintenance" value="3" id="maintenance3" />
																<label class="custom-control-label" for="maintenance3">ปิดถอนอนุมัติอัตโนมัติ</label>
															</div> 

															<div class="d-inline-block custom-control custom-radio">
																<input type="radio" <?=$maintenance==99?'checked':''?> name="maintenance" class="custom-control-input maintenance" value="99" id="maintenance99" />
																<label class="custom-control-label" for="maintenance99">ปิดระบบทั้งหมด</label>
															</div>
														</div>
													 </div>
													</div> 

													<div class="col-md-12">
													<div class="form-group">
														<label for="com_type">เปิด-ปิดรับสมัครสมาชิก</label>  
														 <div class="form-check form-check-inline">
																<input class="form-check-input" type="checkbox" id="truewallet_is_register" name="truewallet_is_register" <?=$truewallet_is_register==2?'checked':''?> value="2">
																<label class="form-check-label" for="truewallet_is_register">รับสมัครสมาชิกทางทรูมันนี่ วอลเล็ท</label>
														 </div> 
													 </div>
													</div> 

													<div class="col-md-12">
														<div class="form-group">
														<label for="com_type">รับโบนัสจากยอดฝาก True Wallet</label>
														<div class="input-group">
															
															<div class="d-inline-block custom-control custom-radio mr-1">
																<input type="radio" <?=$truewallet_is_bonus==1?'checked':''?> name="truewallet_is_bonus" class="custom-control-input truewallet_is_bonus" value="1"id="truewallet_is_bonus1" />
																<label class="custom-control-label" for="truewallet_is_bonus1">รับโบนัสจากยอดฝากไม่ได้</label>
															</div>
															<div class="d-inline-block custom-control custom-radio">
																<input type="radio" <?=$truewallet_is_bonus==2?'checked':''?> name="truewallet_is_bonus" class="custom-control-input truewallet_is_bonus" value="2" id="truewallet_is_bonus2" />
																<label class="custom-control-label" for="truewallet_is_bonus2">รับโบนัสจากยอดฝากได้</label>
															</div> 

														</div>
														</div>
													</div> 

													<div class="col-md-12">
														<div class="form-group">
															<label for="website_title">ชื่อเว็บไซต์</label>
															<input type="text" value="<?= $title ?>" id="website_title" class="form-control website_title" name="title" autocomplete="off">
														</div>
													</div>
											    
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="line_at_name">LINE @</label>
															<input type="text" value="<?= $line_at_name ?>" id="line_at_name" class="form-control line_at_name" name="line_at_name" autocomplete="off">
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label for="line_at_url">LINE URL</label>
															<input type="text" value="<?= $line_at_url ?>" id="line_at_url" class="form-control line_at_url" name="line_at_url" autocomplete="off">
														</div>
													</div>

													<div class="col-md-12">
														<div class="form-group">
															<label for="line_at_url">LINE QR CODE</label>
															<input id="line_img_qr" name="line_img_qr" type="file" accept="image/*" onchange="upload_imge()">
															<span class="file-custom"></span>
														</div>
													</div>

												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="text_marquee">รายละเอียดเว็บไซต์</label>
															<div class="position-relative has-icon-left">
																<textarea id="text_marquee" rows="5" class="form-control text_marquee" name="text_marquee" placeholder="หมายเหตุ..."><?= $text_marquee ?></textarea>
																<div class="form-control-position" style="top: 12px;">
																	<i class="ft-file"></i>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-actions right">
												<button type="button" class="btn btn-primary submit-setting-website-01">
													<i class="la la-check-square-o"></i> Save
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card">

								<div class="card-content collapse show">
									<div class="card-body">

										<form onsubmit="return false;" class="myway-form-setting-website-02" id="myway-form-setting-website-02" autocomplete="off" method="post" action>
											<div class="form-body">
												<h4 class="form-section"><i class="ft-trending-down"></i> ยอดขั้นต่ำ-สูงสุด</h4>
												<div class="row">
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_aff_claim">ยอดขั้นต่ำรับค่าแนะนำเพื่อน</label>
															<input type="number" value="<?= $min_aff_claim ?>" name="min_aff_claim" class="form-control min_aff_claim" id="min_aff_claim">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_comm_claim">ยอดขั้นต่ำค่าคอมมิชชั่น</label>
															<input type="number" value="<?= $min_comm_claim ?>" name="min_comm_claim" class="form-control min_comm_claim" id="min_comm_claim">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_cashback_claim">ยอดขั้นต่ำรับค่าคืนยอดเสีย</label>
															<input type="number" value="<?= $min_cashback_claim ?>" name="min_cashback_claim" class="form-control min_cashback_claim" id="min_cashback_claim">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_accept_promo">ยอดต่ำสุดที่ให้รับโปร</label>
															<input type="number" value="<?= $min_accept_promo ?>" name="min_accept_promo" class="form-control min_accept_promo" id="min_accept_promo">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_withdraw">ยอดถอนขั้นต่ำ</label>
															<input type="number" value="<?= $min_withdraw ?>" name="min_withdraw" class="form-control min_withdraw" id="min_withdraw">
														</fieldset>
													</div>

													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="max_withdraw">ถอนเงินได้สูงสุดต่อครั้ง</label>
															<input type="number" value="<?= $max_withdraw ?>" name="max_withdraw" class="form-control max_withdraw" id="max_withdraw">
														</fieldset>
													</div>
													
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="max_withdraw_perday">จำนวนถอนครั้งที่ถอนได้(ต่อวัน)</label>
															<input type="number" value="<?= $max_withdraw_perday ?>" name="max_withdraw_perday" class="form-control max_withdraw_perday" id="max_withdraw_perday">
														</fieldset>
													</div>

													
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_auto_deposit" >เงินฝากเข้าอัตโนมัติ/เงินคงเหลือขั้นต่ำ (Pending)</label>
															<input type="number" value="<?= $min_auto_deposit ?>" name="min_auto_deposit" class="form-control min_auto_deposit" id="min_auto_deposit"
															placeholder="ยอดเงินฝากจะเข้าอัตโนมัติถ้าเงินคงเหลือน้อยกว่าที่ตั้งค่าไว้"
															>
														</fieldset>
													</div>
													
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="max_auto_withdraw">ยอดถอนกึ่งอัตโนมัติสูงสุด (Semi Auto Withdraw)</label>
															<input type="number" value="<?= $max_auto_withdraw ?>" name="max_auto_withdraw" class="form-control max_auto_withdraw" id="max_auto_withdraw">
														</fieldset>
													</div>

													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="max_approve_auto_withdraw">ยอดถอนอัตโนมัติสูงสุด (Full Auto Withdraw) <span style="color:red;">(0 =ปิดถอนอนุมัติอัตโนมัติ)</span></label>
															<input type="number" value="<?= $max_approve_auto_withdraw ?>" name="max_approve_auto_withdraw" class="form-control max_approve_auto_withdraw" id="max_approve_auto_withdraw">
														</fieldset>
													</div> 

													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="min_lose_lucky_spin_reward">ยอดเสียขั้นต่ำสปินนำโชค</label>
															<input type="number" value="<?= $min_lose_lucky_spin_reward ?>" name="min_lose_lucky_spin_reward" class="form-control min_lose_lucky_spin_reward" id="min_lose_lucky_spin_reward">
														</fieldset>
													</div>

												</div>

											</div>

											<div class="form-actions right">
												<button type="button" class="btn btn-primary submit-setting-website-02">
													<i class="la la-check-square-o"></i> Save
												</button>
											</div>
										</form>

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
			$('.MenuWebsiteSetting').addClass('active');
			var SubmitFormSettingWebsite01 = $('.myway-form-setting-website-01');
			SubmitFormSettingWebsite01.ajaxForm({
				url: '<?php echo site_url("website/update_m_site_01") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					document.getElementById('line_img_qr').value = "";
					if (result.Message == true) {
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
				},error: function(er) {
					console.log(er);

				}
			});

			$('.submit-setting-website-01').click(function() {
				$('.myway-form-setting-website-01').submit();
			});
			var SubmitFormSettingWebsite02 = $('.myway-form-setting-website-02');
			SubmitFormSettingWebsite02.ajaxForm({
				url: '<?php echo site_url("website/update_m_site_02") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
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

			$('.submit-setting-website-02').click(function() {
				$('.myway-form-setting-website-02').submit();
			});
		});

		function upload_imge() {
			var data = new FormData();
			var files = $('input[name="line_img_qr"]')[0].files;
			data.append('image_field', files[0]);
			if (files.length > 0) {
				$.ajax({
					url: '<?= site_url("Website/UploadImage") ?>',
					type: 'post',
					data: data,
					contentType: false,
					processData: false,
					beforeSend: function() {
						Myway_ShowLoader();
					},
					success: function(response) {
						Myway_HideLoader();
					}
				});
			}
		}
	</script>
</body>

</html>