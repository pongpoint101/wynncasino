<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
	<style>
		.dataTables_filter {
			display: none !important;
		}

		.div_setting_aff {
			padding: 10px;
			border: 1px solid #656ee8;
			margin: 2px;
			margin-bottom: 16px;
		}

		.opacity7 {
			opacity: .7;
		}
	</style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">ผู้ใช้งานระบบ</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active">Create User
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
									<h4 class="card-title">สมัครสมาชิก</h4>
								</div>
								<div class="card-content collpase show">
									<div class="card-body card-dashboard">
										<div class="container-fluid">

											<form  onSubmit="return false">
											<div class="row">
												<div class="col"> 
												  เลือก ช่องทางฝาก-ถอน
												</div> 
											</div>
											   <div class="row">
												<div class="col">
												  <label>
													 <input type="radio" name="choose_bank" value="1" checked />
														ธนาคาร
													</label>
													<label>
													 <input type="radio" name="choose_bank" value="2" />
													     ทรูมันนี่ วอลเล็ท
													</label>
												</div> 
											</div>
												<div class="row">
													<div class="col-sm-12 pb-2">
														<div class="form-group">
															<input type="tel" name="phone" ref="phone" placeholder="เบอร์โทรศัพท์*" maxlength="10" required="" class="form-control phone">
														</div>
													</div>
													<div class="col-sm-6 col-md-3 pb-2">
														<div class="form-group">
															<input type="tel" placeholder="เลขบัญชีธนาคาร*" required onkeyup="CheckAccount()"  name="bank_account" class="form-control bank_account">
														</div>
													</div>
													<div class="col-sm-6 col-md-3 pb-2 truewallet_account" style="display: none;">
														<div class="form-group">
															<input type="text" placeholder="วอลเล็ท ไอดี"  name="truewallet_account" class="form-control truewallet_account">
														</div>
													</div>
													<div class="col-sm-6 col-md-3 pb-2 mainbank_code">
														<div class="form-group">
															<select  required="" name="bank_code"  onchange="selectbank()" class="form-control bank_code">
																<option selected="selected" value="">เลือกธนาคาร*</option>
																<option value="014">ไทยพานิชย์</option>
																<option value="002">กรุงเทพ</option>
																<option value="004">กสิกรไทย</option>
																<option value="006">กรุงไทย</option>
																<option value="034">ธกส.</option>
																<option value="011">ทหารไทยธนชาต</option>
																<option value="070">ไอซีบีซี</option>
																<option value="071">ไทยเครดิต</option>
																<option value="017">ซิตี้แบงก์</option>
																<option value="020">สแตนดาร์ดชาร์เตอร์ด</option>
																<option value="022">ซีไอเอ็มบี</option>
																<option value="024">ยูโอบี</option>
																<option value="025">กรุงศรีฯ</option>
																<option value="030">ออมสิน</option>
																<option value="031">เอชเอสบีซี</option>
																<option value="033">ธอส.</option>
																<option value="073">แลนด์แอนด์เฮ้าส</option>
																<option value="067">ทิสโก้</option>
																<option value="069">เกียรตินาคิน</option>
																<option value="066">อิสลาม</option>
															</select>
														</div>
													</div>
													<div class="col-sm-6 col-md-3 pb-2">
														<div class="form-group">
															<input type="text" readonly class="form-control first_name" name="first_name" placeholder="ชื่อจริงในสมุดบัญชีธนาคาร*">
														</div>
													</div>
													<div class="col-sm-6 col-md-3 pb-2">
														<div class="form-group">
															<input type="text" readonly placeholder="นามสกุล*" name="last_name" class="form-control last_name">
														</div>
													</div>
													<div class="col-sm-6 pb-2">
														<div class="form-group">
															<input type="text" class="form-control password" placeholder="รหัสผ่าน*" minlength="4" required="" name="password" >
														</div>
													</div>
													<div class="col-sm-6 pb-2">
														<div class="form-group">
															<input type="text" class="form-control password_confirmation" placeholder="ยืนยันรหัสผ่าน*" minlength="4" required="" name="password_confirmation" >
														</div>
													</div>
													<div class="col-sm-12 pb-2">
														<div class="form-group">
														<button type="submit" class="form-control bg-warning pe-auto" onclick="register();" >สมัครสมาชิก</button>  
														</div>
													</div>
													
												</div>
											</form>

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
	
	<?php echo $pg_footer ?>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="text/javascript">
		var datatablePGGmage;
		$(function() {
			$(document).on("click","[name='choose_bank']",function() {
				 var _this=$(this);
				 var bank=_this.val()*1;
				 switch (bank) {
					case 1:
						$("[name='bank_account']").attr("placeholder", "เลขบัญชีธนาคาร*"); 
						$("[name='first_name']").attr("placeholder", "ชื่อจริงในสมุดบัญชีธนาคาร*");   
						$('.truewallet_account').hide();$('.mainbank_code').show();
					break;
					case 2:
						$("[name='bank_account']").attr("placeholder", "เบอร์ทรูมันนี่ วอลเล็ท*"); 
						$("[name='first_name']").attr("placeholder", "ชื่อจริงในทรูมันนี่ วอลเล็ท*"); 
						$('.mainbank_code').hide();$('.truewallet_account').show();
						CheckAccount();
						break;
					default: break;
				 } 
			});
			
		});
		function CheckAccount(){
			var choose_bank=$('input[name=choose_bank]:checked').val()*1;
			var regPattern5 = /^(0[1-9]{1})+([0-9]{8})+$/g;
			if (choose_bank==1) { return;} 
            if (!regPattern5.test($("[name='bank_account']").val())) {  return;}  
            verifyBankAccount();
		}
		function selectbank() {
			
			if ($('.bank_account').val().length <= 5) {
				Swal.fire(
					"ข้อมูลไม่สมบูรณ์",
					"กรุณากรอกเลขบัญชีธนาคาร",
					"error"
				);
				$( ".bank_account" ).focus();
				return;
			}
			if ($('.bank_code').val().length <= 0) {
				Swal.fire(
					"ข้อมูลไม่สมบูรณ์",
					"กรุณาระบุธนาคาร",
					"error"
				);
				$( ".bank_code" ).focus();
				return;
			}
			verifyBankAccount();
		}
		function verifyBankAccount() {
			var choose_bank=$('input[name=choose_bank]:checked').val()*1;
			var txt_choose_bank='ธนาคาร';
			if(choose_bank==2){txt_choose_bank='ทรูมันนี่ วอลเล็ท';} 
			Swal.fire({
				title: "กำลังค้นหาบัญชี"+txt_choose_bank,
				allowOutsideClick: false,
				timerProgressBar: true,
				showCancelButton: false,
				showConfirmButton: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});
			var bank_code = $('.bank_code').val();
			var bank_acct = $('.bank_account').val();
			var choose_bank=$('input[name=choose_bank]:checked').val()*1; 
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("member/VerifyBankAccount") ?>',
				dataType: "json",
				data: {
					choose_bank:choose_bank,
					bank_code: bank_code,
					bank_acct: bank_acct,
				},
				success: function(data) {
					var errorCode = data.code;
					Swal.close();
					if (errorCode == 0) {
						$('.first_name').val(data.firstName);
						$('.last_name').val(data.lastName);
					} else {
						$('.first_name').val("");
						$('.last_name').val("");
						Swal.fire(
                                "ไม่พบบัญชี"+txt_choose_bank,
                                "กรุณาใส่ข้อมูลให้ถูกต้อง",
                                "error"
                            );
					}
				},
				error: function(data) {
					Swal.close();
                        Swal.fire(
                            "Server error",
                            "กรุณาลองอีกครั้ง...",
                            "error"
                        );
				},
			});
		}
		function register() {
			   var choose_bank=$('input[name=choose_bank]:checked').val()*1;
			   var txt_choose_bank='ธนาคาร';
			   if(choose_bank==2){txt_choose_bank='ทรูมันนี่ วอลเล็ท';} 
                if (
                    $('.phone').val() == null ||
                    $('.phone').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรุณากรอก เบอร์โทรศัพท์",
						"error"
					);
					$( ".phone" ).focus();
                    
                    return;
                } else if (
                    $('.bank_account').val() == null ||
                    $('.bank_account').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรุณากรอก เลขบัญชี"+txt_choose_bank,
						"error"
					);
					$( ".bank_account" ).focus();
                    return;
                } else if (
					($('.bank_code').val() == null ||
                    $('.bank_code').val().length <= 0)&&choose_bank==1
                ) { return;
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรุณากรอก กรุณาระบุ"+txt_choose_bank,
						"error"
					);
					if (choose_bank==1) {
						$( ".bank_code" ).focus();
					}else{
						$( ".bank_account" ).focus();
					}
                    return;
                } else if (
					$('.first_name').val() == null ||
                    $('.first_name').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"ไม่พบข้อมูลชื่อในสมุดบัญชี"+txt_choose_bank,
						"error"
					);
					if (choose_bank==1) {
						$( ".bank_code" ).focus();
					}else{
						$( ".bank_account" ).focus();
					}
                    return;
                } else if (
					$('.last_name').val() == null ||
                    $('.last_name').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"ไม่พบข้อมูลนามสกุลในสมุดบัญชี"+txt_choose_bank,
						"error"
					);
					if (choose_bank==1) {
						$( ".bank_code" ).focus();
					}else{
						$( ".bank_account" ).focus();
					}
                    return;
                } else if (
					$('.password').val() == null ||
                    $('.password').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรุณากรอก รหัสผ่าน",
						"error"
					);
					$( ".password" ).focus();
                    return;
                } else if (
					$('.password_confirmation').val() == null ||
                    $('.password_confirmation').val().length <= 0
                ) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรุณากรอก ยืนยื่นรหัสผ่าน",
						"error"
					);
					$( ".password_confirmation" ).focus();
                    return;
                } else if ($('.password').val() !=  $('.password_confirmation').val()) {
					Swal.fire(
						"ข้อมูลไม่สมบูรณ์",
						"กรอก ยืนยื่นรหัสผ่านไม่ตรงกับรหัสผ่าน",
						"error"
					);
					$( ".password_confirmation" ).focus();
                    return;
                }
				var fd = new FormData();    
				fd.append( 'choose_bank', choose_bank);
				fd.append( 'truewallet_account', $('input[name=truewallet_account]').val());
				fd.append( 'phone', $('.phone').val() );
				fd.append( 'first_name', $('.first_name').val() );
				fd.append( 'last_name', $('.last_name').val() );
				fd.append( 'bank_account', $('.bank_account').val() );
				fd.append( 'bank_code', $('.bank_code').val() );
				fd.append( 'password', $('.password').val() );
				fd.append( 'password_confirmation', $('.password_confirmation').val() );
				fd.append( 'source', 'other' );
				

                ShowWaitting("กรุณารอสักครู่", "กำลังลงทะเบียน");
				$.ajax({
				url:  "<?php echo site_url("member/register") ?>",
				data: fd,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					var res = JSON.parse(data);
					if(res.status=='success'){
						Swal.fire({
						title: res.message,
						html:'สร้าง member: <span class="text-danger">'+res.data.username+'</span> สำเร็จ<br> username: <span class="text-danger">'+res.data.telephone+'</span>',
						showDenyButton: false,
						showCancelButton: false,
						confirmButtonText: 'Ok',
						}).then((result) => {
							if (result.isConfirmed) {
								location.reload();
							} 
						})
						
					}else{
						swal.fire('กรุณาตรวจสอบ',res.message,'error');
					}

				},
				error: function(er){
					swal.fire('มีบางอย่างผิดพลาด',er.statusText,'error');
				}
				});
               
            }
			function ShowWaitting(title, text) {
				this.alertwait = Swal.fire({
					title: title,
					text: text,
					timerProgressBar: true,
					showCancelButton: false,
					showConfirmButton: false,
					closeOnConfirm: false,
					allowOutsideClick: false,
					timer: 2000,
				});
			}

	</script>
</body>

</html>