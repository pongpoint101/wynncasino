<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,800;1,900&display=swap" rel="stylesheet"> -->
<link href="<?=base_url('app-assets/fonts/line-awesome/css/line-awesome.min.css')?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/fonts/simple-line-icons/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/vendors.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/extensions/sweetalert.css');?>">
 <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/pickers/daterange/daterangepicker.css')?>">
 <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/pickers/pickadate/pickadate.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/tables/datatable/datatables.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/app.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/menu/menu-types/vertical-menu-modern.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/colors/palette-gradient.css')?>">
 <link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/plugins/pickers/daterange/daterange.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/forms/toggle/switchery.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/charts/jquery-jvectormap-2.0.3.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/charts/morris.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/plugins/forms/switch.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/colors/palette-switch.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/plugins/animate/animate.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/plugins/loaders/loaders.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/css/core/colors/palette-loader.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/forms/selects/select2.min.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css')?>">
<style type="text/css">
	.ActiveFsMenu{background: #23262f;}
	.pg-color-gray{color:#ced4da !important;}
	.icon-main-pg{font-size: 30px !important;vertical-align: middle;}
	.select2-container{width: 100% !important;}
	.mt005px{margin-top: 5px;}
	.modal-body-credit-member .nav.nav-tabs .nav-item .nav-link.active{
		background-color: #fff0;
	}
	.modal-body-credit-member .nav.nav-tabs.nav-linetriangle .nav-item a.nav-link.active:after {
	    border-top-color: #293544;
	}
	.picker__day {
	    color: #000;
	}
	.iconfontsize12px{font-size: 12px !important;}
	.btnmr5px{margin-right: 5px;}
	[type="number"]::-webkit-inner-spin-button{display: none !important;}
	input[type="date" i]::-webkit-calendar-picker-indicator,input[type="time" i]::-webkit-calendar-picker-indicator{filter: invert(1);}
</style>

<script type="text/javascript">
	function Myway_ShowLoader(){
		var LoadingBody = $('body');
		var containLoading = $('<div class="fs_loading" style="width: 100%;height: 100%;position: fixed;top: 0;left: 0;right: 0;bottom: 0;z-index: 1151;"></div>');
		var backgroundLoading = $('<div style="width: 100%;height: 100%;background-color: #fff;opacity: 0.9;"></div>');
		containLoading.append(backgroundLoading);
		var divImageLoading = $('<div style="position: fixed;left: 50%;top: 50%;transform: translate(-50%, -50%);text-align: center;"></div>');
		var imageLoading = $('<div class="loader-wrapper">' +
								 '<div class="loader-container">'+
									 '<div class="folding-cube loader-blue-grey">'+
										 '<div class="cube1 cube"></div>'+
										 '<div class="cube2 cube"></div>'+
										 '<div class="cube4 cube"></div>'+
										 '<div class="cube3 cube"></div>'+
									 '</div>'+
								 '</div>'+
							 '</div>');
		divImageLoading.append(imageLoading);
		containLoading.append(divImageLoading);
		LoadingBody.append(containLoading);
	}
	function Myway_HideLoader(){
		$('.fs_loading').remove();
	}
   function clodesysteme(){
	if(confirm('ยืนยันปิดระบบการใช้งานระบบทั้งหมด!')) {
		window.location.href = "<?=base_url("admin/clodesysteme") ?>"; 
	  }
   }
</script>