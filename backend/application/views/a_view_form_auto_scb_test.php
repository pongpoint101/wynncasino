<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-body">
					<section class="file-browser">
						<div class="row match-height">
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-block">
										<div class="card-body">
											<form class="myway-form-scb-auto" id="myway-form-scb-auto" autocomplete="off" method="post">
												<fieldset class="form-group">
													<div class="custom-file">
														<input type="file" name="filetest" class="custom-file-input" id="inputGroupFile01">
														<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
													</div>
												</fieldset>
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
		

		<?php echo $pg_footer?>
		<script type="text/javascript">
			$(function(){
				var SubmitFormSCBAuto = $('.myway-form-scb-auto');
                SubmitFormSCBAuto.ajaxForm({
                    url: '<?php echo site_url("autoscb/trx")?>',
                    type: 'post',
                    dataType: 'json',
    				contentType: "application/json; charset=utf-8",
                    data: {},
                    beforeSubmit: function () {
                    	// Myway_ShowLoader();
                    },
                    success: function (result, statusText, xhr, form) {
                        if (result.Message == true) {
							Myway_HideLoader();
							// model_success_pggame();
                        } else {
                            Myway_HideLoader();
                        }
                    }
                });
    //             $("#inputGroupFile01").on("change", function (e) {
    //             	$('.myway-form-scb-auto').submit();
				// });
			});
		</script>
	</body>
</html>