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

						<div class="col-xs-12 col-md-6">
							<div class="card">

								<div class="card-content collapse show">
									<div class="card-body">

										<form onsubmit="return false;" class="myway-form-setting-cashback-01" id="myway-form-setting-cashback-01" autocomplete="off" method="post" action>
											<div class="form-body">
												<h4 class="form-section"><i class="ft-airplay"></i> <?=$pg_title?></h4>
												<div class="row"> 
                                                    <div class="col-md-12"> 
															<div class="form-group">
                                                                <label for="cashback_rate">โบนัสคืนยอดเสีย(%)</label>
                                                                <input type="number" value="<?= $cashback_rate ?>" id="cashback_rate" class="form-control cashback_rate" name="cashback_rate" autocomplete="off">
                                                              </div> 
													 </div>
                                                     <div class="col-md-12"> 
															<div class="form-group">
                                                                <label for="cashback_max_payout">โบนัสคืนยอดเสียสูงสุด (บาท)</label>
                                                                <input type="number" value="<?= $cashback_max_payout ?>" id="cashback_max_payout" class="form-control cashback_max_payout" name="cashback_max_payout" autocomplete="off">
                                                              </div> 
													 </div>
												</div>  
											</div>
											<div class="form-actions right">
                                                <span class="form-error-text" style="color: #ff776d !important;"></span>
												<button type="button" class="btn btn-primary submit-setting-website-01">
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
			$('.MenucashbackSetting').addClass('active');  
			var SubmitFormSettingWebsite01 = $('.myway-form-setting-cashback-01');
			SubmitFormSettingWebsite01.ajaxForm({
				url: '<?php echo site_url("website/update_Cashback") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) { console.log('result',result); console.log('result',typeof result);
					if (result.Message == true) {
						Myway_HideLoader();
						model_success_pggame();
                        $('.form-error-text').html('');
					  } else {
						$('.form-error-text').html(result.ErrorText);
						Myway_HideLoader();
					}
				}
			});

			$('.submit-setting-website-01').click(function() {
				$('.myway-form-setting-cashback-01').submit();
			});
             
		});
 
	</script>
</body> 
</html>