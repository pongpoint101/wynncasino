<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<title>เข้าสู่ระบบ</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700" rel="stylesheet">
	<link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/vendors.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/forms/icheck/icheck.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/forms/icheck/custom.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/app.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/menu/menu-types/horizontal-menu.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/colors/palette-gradient.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/pages/login-register.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/plugins/loaders/loaders.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/colors/palette-loader.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
</head>

<body class="horizontal-layout horizontal-menu horizontal-menu-padding 1-column   menu-expanded blank-page blank-page" data-open="click" data-menu="horizontal-menu" data-col="1-column">

	<div class="app-content container center-layout mt-2">
		<div class="content-wrapper">
			<div class="content-header row">
			</div>
			<div class="content-body">
				<section class="flexbox-container">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="col-md-4 col-10 box-shadow-2 p-0">
							<div class="card border-grey border-lighten-3 m-0">
								<div class="card-header border-0 pb-0">
									<div class="card-title text-center">
										<div class="p-1">
											<h3 class="brand-text pg-color-gray"><i class="la la-chrome pg-color-gray icon-main-pg" style="font-size: 2.4rem;vertical-align: sub;"></i> <?=SITE_NAME_ID?></h3>
										</div>
									</div>
									<h6 class="card-subtitle line-on-side text-muted text-center font-small-3">
										<span style="background-color: #232f3e;">Login with Admin</span>
									</h6>
								</div>
								<div class="card-content">
									<div class="card-body">
										<form onsubmit="return false;" class="form-horizontal form-simple pggame-Form-Login" action="" novalidate method="post" autocomplete="on">
											<fieldset class="form-group position-relative has-icon-left mb-0">
												<input type="text" class="form-control form-control-lg input-lg login-username" name="PGUserName" id="user-name" placeholder="Your Username" required autocomplete="username">
												<div class="form-control-position">
													<i class="ft-user"></i>
												</div>
											</fieldset>
											<fieldset class="form-group position-relative has-icon-left">
												<input type="password" class="form-control form-control-lg input-lg login-password" name="PGPassword" id="user-password" placeholder="Enter Password" required autocomplete="new-password">
												<div class="form-control-position">
													<i class="la la-key"></i>
												</div>
											</fieldset>
                                             <?php 
											 if(!($publickeycaptcha==''||is_null($publickeycaptcha))){
											 ?>
											<div class="form-group row">
												<div class="col-md-6 col-12 text-center text-md-left">
													<fieldset> 
													   <div class="g-recaptcha" data-callback="makeaction" data-sitekey="<?=$publickeycaptcha?>"></div>
													   <br>	
													   <input name="MywayRemember" type="checkbox" value="1" id="remember-me" class="chk-remember">
														<label for="remember-me"> Remember Me</label>
													</fieldset>
												</div> 
											</div>
                                             <?php } ?>
											<div class="form-group row ErrorLoginMyway" style="margin-bottom: 6px;display:none;">
												<span class="col-12 text-center text-sm-left" style="color: red;text-align: center;">
												</span>
											</div>
											<button type="submit" id="submit" <?=($publickeycaptcha==''||is_null($publickeycaptcha) ? '':'disabled')?>  class="btn btn-info btn-lg btn-block submitformlogin"><i class="ft-unlock"></i> Login</button>
										</form>
									</div>
								</div>
								<!-- <div class="card-footer">
								<div class="">
								<p class="float-sm-left text-center m-0"><a href="recover-password.html" class="card-link">Recover password</a></p>
								<p class="float-sm-right text-center m-0">New to Moden Admin? <a href="register-simple.html" class="card-link">Sign Up</a></p>
								</div>
								</div> -->
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<script src="<?php echo base_url('app-assets/vendors/js/vendors.min.js') ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('app-assets/vendors/js/ui/jquery.sticky.js') ?>"></script>
	<script src="<?php echo base_url('app-assets/vendors/js/forms/icheck/icheck.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('app-assets/js/core/app-menu.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('app-assets/js/core/app.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('app-assets/js/scripts/forms/form-login-register.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('js/jquery.form.min.js') ?>" type="text/javascript"></script>
	<script src='https://www.google.com/recaptcha/api.js?hl=th' async defer></script>
	<script type="text/javascript">
		function makeaction(){
          document.getElementById('submit').disabled = false;  
        }
		$(function() {
			setTimeout(function() {
				$('.login-username,.login-password').val("");
			}, 500);
			var SunnyFormLogin = $('.pggame-Form-Login');
			SunnyFormLogin.ajaxForm({
				url: '<?php echo site_url("login/system") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						$('.ErrorLoginMyway span').html("");
						$('.ErrorLoginMyway').hide();
						window.location.href = "<?php echo base_url('home/index') ?>";
						//Myway_HideLoader();
					} else {

						$('.ErrorLoginMyway span').html(result.ErrorText);

						$('.ErrorLoginMyway').show();
						Myway_HideLoader();
					}
				}
			});
			$('#user-name,#user-password').on('keypress', function(e) {
				if (e.which == 13) {
					submitCheckForm();
				}
			});
			$('.submitformlogin').click(function() {
				submitCheckForm();
			});
		});

		function submitCheckForm() {
			var UserName = $('#user-name');
			var UserPass = $('#user-password');
			var bool = true;
			var focus;
			if ($.trim(UserPass.val()) == "") {
				bool = false;
				focus = UserPass;
				UserPass.addClass('is-invalid');
			} else {
				UserPass.removeClass('is-invalid');
			}
			if ($.trim(UserName.val()) == "") {
				bool = false;
				focus = UserName;
				UserName.addClass('is-invalid');
			} else {
				UserName.removeClass('is-invalid');
			}
			if (bool == true) {
				$('.pggame-Form-Login').submit();
			} else {
				focus.focus();
			}
		}

		function Myway_ShowLoader() {
			var LoadingBody = $('body');
			var containLoading = $('<div class="fs_loading" style="width: 100%;height: 100%;position: fixed;top: 0;left: 0;right: 0;bottom: 0;z-index: 1151;"></div>');
			var backgroundLoading = $('<div style="width: 100%;height: 100%;background-color: #fff;opacity: 0.9;"></div>');
			containLoading.append(backgroundLoading);
			var divImageLoading = $('<div style="position: fixed;left: 50%;top: 50%;transform: translate(-50%, -50%);text-align: center;"></div>');
			var imageLoading = $('<div class="loader-wrapper">' +
				'<div class="loader-container">' +
				'<div class="folding-cube loader-blue-grey">' +
				'<div class="cube1 cube"></div>' +
				'<div class="cube2 cube"></div>' +
				'<div class="cube4 cube"></div>' +
				'<div class="cube3 cube"></div>' +
				'</div>' +
				'</div>' +
				'</div>');
			divImageLoading.append(imageLoading);
			containLoading.append(divImageLoading);
			LoadingBody.append(containLoading);
		}

		function Myway_HideLoader() {
			$('.fs_loading').remove();
		}
	</script>
</body>

</html>