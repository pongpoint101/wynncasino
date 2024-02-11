<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
	<style>
		.dataTables_filter {
			display: none !important;
		}
		.div_setting_aff,.div_setting_cashback{
			padding: 10px;
			border: 1px solid #656ee8;
			margin: 2px;
			margin-bottom: 16px;
		}
		.opacity7{
			opacity: .7;
		}
		.btn-action{
			padding:0.5rem 0.5rem;
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
								<li class="breadcrumb-item active">User
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
									<div class="row">
										<div class="col-sm-6">
											<h4 class="card-title">รายการผู้ใช้งานระบบ</h4>
										<!-- create_user_by_admin -->
										</div>
										<?php if($create_user_by_admin==1){ ?>
										<div class="col-sm-6 ">
											<div class="float-right"><a href="<?php echo base_url('member/create') ?>" class="btn btn-primary">เพิ่มสมาชิก</a></div>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="card-content collpase show">
									<div class="card-body card-dashboard">
										<div class="container-fluid">
											<div class="row">
												<div class="col-sm-12 pb-2">
													<form class="form-inline" onSubmit="return false">
														<div class="form-group">
															<label class="sr-only">ค้นหาข้อมูลลูกค้า:</label>
															<input type="text" value="" id="search_input" class="form-control" placeholder="ค้นหาข้อมูลลูกค้า" autocomplete="off">
														</div>
														<div class="form-group mx-sm-1">
															<label class="sr-only">เลือกปรเภทข้อมูลลูกค้า</label>
															<select class="form-control" id="search_category">
																<option value='1'>เบอร์โทร</option>
																<option value='2'>USERNAME</option>
																<option value='3'>ชื่อ-สกุล</option>
																<option value='4'>หมายเลขบัญชี</option>
																<option value='5'>Account TrueWallet</option>
															</select>
															&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary" id="btn_search">ค้นหา</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table w-100 table-striped table-bordered pg-data-table">
												<thead>
													<tr>
														<th>USERNAME</th>
														<th>เบอร์โทร</th>
														<th>หมายเลขบัญชี</th>
														<th>ชื่อ</th>
														<th>สกุล</th>
														<th>ยอดเงิน</th>
														<th>STATUS</th>
														<th>Action</th>
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
	<div class="modal fade text-left" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel35">แก้ไขข้อมูลสมาชิก <font class="UserNameText"></font>
					</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form class="myway-form-member" id="myway-form-member" autocomplete="off" method="post" action>
					<input type="hidden" name="pg_ids">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_telephone">เบอร์โทรศัพท์</label>
									<input type="text" id="pg_telephone" class="form-control pg_telephone" name="telephone" autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_truewallet_account">TrueWallet Account ID</label>
									<input type="text" id="pg_truewallet_account" class="form-control pg_truewallet_account" name="truewallet_account" autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_truewallet_phone">เบอร์ TrueWallet</label>
									<input type="text" id="pg_truewallet_phone" class="form-control pg_truewallet_phone" name="pg_truewallet_phone" autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="choose_bank">เลือกบัญชีถอนเงินหลัก</label>
									<select id="choose_bank" name="choose_bank" class="form-control border-primary choose_bank"> 
										 <option value="0" selected>บัญชีถอนเงินหลัก</option>
									     <option value="1">ธนาคารที่สมัคร</option>
										 <option value="2">TrueWallet ที่สมัคร</option>
										 <option value="3">ถอนด้วย VizPlay</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_fname">ชื่อ</label>
									<input type="text" id="pg_fname" class="form-control pg_fname" name="fname" readonly autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">นามสกุล</label>
									<input type="text" id="pg_lname" class="form-control pg_lname" name="lname" readonly autocomplete="off">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_bank_accountnumber">หมายเลขบัญชี</label>
									<input type="text" id="pg_bank_accountnumber" class="form-control pg_bank_accountnumber" name="bank_accountnumber" autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">ธนาคาร</label>
									<select id="bank_code" name="bank_code" class="form-control border-primary bank_code">
										<option value="0">เลือกธนาคาร</option>
										<?php
										if ($RSBank['num_rows'] > 0) {
											foreach ($RSBank['result_array'] as $item) {
										?>
												<option value="<?php echo $item['bank_code']; ?>"><?php echo $item['bank_short']; ?> - <?php echo $item['short_name']; ?></option>
										<?php
											}
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_line_id">Line ID</label>
									<input type="text" id="pg_line_id" class="form-control pg_line_id" name="line_id" autocomplete="off">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">จับคู่ธนาคาร</label>
									<select id="setting_bank_id" name="setting_bank_id" class="form-control border-primary setting_bank_id">
										<option value="0">เลือกจับคู่ธนาคาร</option>
										<?php
										if ($RSBankSetting['num_rows'] > 0) {
											foreach ($RSBankSetting['result_array'] as $item) {
										?>
												<option value="<?php echo $item['id']; ?>"><?php echo $item['bank_short']; ?> - <?php echo $item['bank_account']; ?> - <?php echo $item['account_name']; ?></option>
										<?php
											}
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<!-- ล็อค Turnover -->
						 
						<!-- ล็อค Turnover -->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">ตั้งค่าการคำนวณ aff</label><br>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_setting1" type="radio" name="pg_setting" id="pg_setting1" value="1">
										<label class="form-check-label" for="pg_setting1">default</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_setting2" type="radio" name="pg_setting" id="pg_setting2" value="2">
										<label class="form-check-label" for="pg_setting2">custom</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">ประเภทสมาชิก</label><br>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_type1" type="radio" name="pg_type" id="pg_type1" value="1">
										<label class="form-check-label" for="pg_type1">ปกติ</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_type2" type="radio" name="pg_type" id="pg_type2" value="2">
										<label class="form-check-label" for="pg_type2">เฝ้าระวัง</label>
									</div>
								</div>
							</div>
						</div>
						<div class="row div_setting_aff">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pg_lname">การคำนวณ aff</label><br>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_aff1" type="radio" name="pg_aff" id="pg_aff1" value="1">
										<label class="form-check-label" for="pg_aff1">turnover</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input pg_aff2" type="radio" name="pg_aff" id="pg_aff2" value="2">
										<label class="form-check-label" for="pg_aff2">win/loss</label>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="pg_aff_percent1">% aff level1</label>
									<input type="number" id="pg_aff_percent1" class="form-control pg_aff_percent1" name="aff_percent1" autocomplete="off">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="pg_aff_percent2">% aff level2</label>
									<input type="number" id="pg_aff_percent2" class="form-control pg_aff_percent2" name="aff_percent2" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="pg_lname">ปลดล็อค Turnover</label><br>
									<div class="form-check form-check-inline">
										<input class="form-check-input lock_Turnover" type="radio" name="lock_Turnover" id="lock_Turnover1" value="0">
										<label class="form-check-label" for="lock_Turnover1">ล็อค Turnover</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input lock_Turnover" type="radio" name="lock_Turnover" id="lock_Turnover2" value="1">
										<label class="form-check-label" for="lock_Turnover2">ไม่ล็อค Turnover</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="pg_telephone">เปลี่ยนรหัสผ่าน</label>
							<input type="text" id="pg_member_password" class="form-control pg_member_password" name="password" autocomplete="off">
						</div>
					</div>
					<div class="modal-footer">
						<span class="form-error-text" style="color: #ff776d !important;"></span>
						<input type="button" class="btn btn-primary submit-member" value="บันทึก">
						<input type="button" class="btn btn-outline-light close-create-title" data-dismiss="modal" value="ยกเลิก">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade text-left" id="modal-credit-member" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3521" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content modal-body-credit-member">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel3521">เติมเครดิต <font class="CreditMemberText"></font>
					</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<ul class="nav nav-tabs nav-linetriangle no-hover-bg">
					<li class="nav-item">
						<a class="nav-link active" id="base-tab41" data-toggle="tab" aria-controls="tab41" href="#tab41" aria-expanded="true">เติมมือ</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab45" data-toggle="tab" aria-controls="tab45" href="#tab45" aria-expanded="false">TrueWallet</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab42" data-toggle="tab" aria-controls="tab42" href="#tab42" aria-expanded="false">SCB</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab57" data-toggle="tab" aria-controls="tab57" href="#tab57" aria-expanded="false">KBank</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab58" data-toggle="tab" aria-controls="tab58" href="#tab58" aria-expanded="false">Vizplay</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab43" data-toggle="tab" aria-controls="tab43" href="#tab43" aria-expanded="false">ลบเครดิต</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="base-tab44" data-toggle="tab" aria-controls="tab44" href="#tab44" aria-expanded="false">เครดิตฟรี</a>
					</li>
				</ul>
				<div class="tab-content px-1 pt-1">
					<div role="tabpanel" class="tab-pane active" id="tab41" aria-expanded="true" aria-labelledby="base-tab41">
						<h4 class="mt-1">เติมเครดิต</h4>
						<form class="myway-form-credit-munual" id="myway-form-credit-munual" enctype="multipart/form-data" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<input type="hidden" name="subpro_id" class="subpro_id">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input type="number" id="pg_amount" placeholder="ยอดเงิน" class="form-control pg_amount" name="amount" autocomplete="off">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="remark-option" name="remark-option" class="form-control border-primary remark-option" onchange="showpro(this);">
											<option value="-1">เลือกหมายเหตุ</option>
											<option value="5">ไม่พบบัญชี-SCB-Bank</option>
											<option value="7">ไม่พบบัญชี-KBank-Bank</option>
											<option value="6">ไม่พบบัญชี-SCB-SMS</option>
											<option value="1">ไม่พบบัญชี-KBank-SMS</option>
											<option value="4">SMS Delayed</option>
											<option value="2">ยอดข้าม</option>
											<!-- <option value="20">Happy New Year ฝาก 100 รับเพิ่ม 50</option>
												<option value="21">Welcome back ฝาก 100 รับ 50</option>
                                                <option value="22">Merry Christmas 25%</option>  
												<option value="83">ฝาก 500 / 3 วัน / รับ 200 (FQ200)</option>
                                                <option value="87">ฝาก 500 / 7 วัน / รับ 500 (FQ500)</option>
                                                <option value="90">ฝาก 500 / 10 วัน / รับ 1,000 (FQ1000)</option>
                                                <option value="95">ฝาก 500 / 15 วัน / รับ 2,000 (FQ2000)</option> -->

											<!-- <option value="20">แตก 5,000 รับเพิ่ม 200</option>
                                                <option value="21">แตก 10,000 รับเพิ่ม 500</option>
                                                <option value="22">แตก 30,000 รับเพิ่ม 1,500</option>
                                                <option value="23">แตก 50,000 รับเพิ่ม 3,000</option> -->
											<?php
											foreach ($pro_list_all['result_array'] as $k => $v) {
												if (in_array($v['pro_id'], [13, 14])) {
													continue;
												}
											?>
												<option value="<?= $v['pro_id'] ?>"><?= $v['pro_name'] ?><?=(in_array($v['pro_id'],[151,157]))?' (ระบุยอดเทิร์น)':''?></option>
											<?php
											}
											?>
											<!-- <option value="61">รางวัลประจำเดือน - AFF - อันดับ 1 - 3,000</option>
												<option value="62">รางวัลประจำเดือน - AFF - อันดับ 2 - 2,000</option>
												<option value="63">รางวัลประจำเดือน - AFF - อันดับ 3 - 1,500</option>
												<option value="64">รางวัลประจำเดือน - AFF - อันดับ 4 ถึง 10 - 1,000</option>
												<option value="65">รางวัลประจำเดือน - AFF - อันดับ 11 ถึง 20 - 500</option>
												<option value="66">รางวัลประจำเดือน - AFF - อันดับ 21 ถึง 30 - 250</option>
												<option value="67">รางวัลประจำเดือน - Comm - อันดับ 1 - 5,000</option>
												<option value="68">รางวัลประจำเดือน - Comm - อันดับ 2 - 3,000</option>
												<option value="69">รางวัลประจำเดือน - Comm - อันดับ 3 - 2,000</option>
												<option value="70">รางวัลประจำเดือน - Comm - อันดับ 4 ถึง 10 - 1,000</option>
												<option value="71">รางวัลประจำเดือน - Comm - อันดับ 11 ถึง 20 - 500</option>
												<option value="72">รางวัลประจำเดือน - Comm - อันดับ 21 ถึง 30 - 250</option> -->
											<option value="0">อื่น ๆ</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="col-md-6">
									  <div class="form-group"> 
									    <label class="form-label">สลิปโอนเงิน</label>
									    <input type="file" name="slip" id="slip_general" accept="image/*" class="form-control slip">
									  </div>
								</div> 
							<div class="col-md-6">
									  <div class="form-group" style="display: none;" id="turnover_amount"> 
									    <label class="form-label">เทิร์นโอเวอร์</label>
										<input type="number"  placeholder="ใส่ยอดเทิร์นโอเวอร์หรือไม่ใส่ก็ได้" class="form-control turnover_amount" name="turnover_amount" autocomplete="off">
									  </div>
							 </div> 
								<div class="col-md-12">
									<div id="content_tab46"></div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="pg_remark">หมายเหตุ</label>
										<div class="position-relative has-icon-left">
											<textarea id="pg_remark" rows="5" class="form-control pg_remark" name="remark" placeholder="หมายเหตุ..."></textarea>
											<div class="form-control-position" style="top: 12px;">
												<i class="ft-file"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-credit-munual" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-munual" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab45" aria-labelledby="base-tab45">
						<h4 class="mt-1">เติมเครดิต TrueWallet</h4>
						<form class="form myway-form-credit-truewallet" id="myway-form-credit-truewallet" enctype="multipart/form-data" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-6">

									<div class="form-group">
										<div class="input-group">
											<input type='text' name="selectdate_truewallet" class="form-control selectdate_truewallet" placeholder="วันที่" autocomplete="off" />
											<div class="input-group-append">
												<span class="input-group-text">
													<span class="la la-calendar-o"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="position-relative has-icon-left">
											<input type="time" id="timesheetinput6" class="form-control starttime_truewallet" name="starttime_truewallet">
											<div class="form-control-position" style="top: 15px;">
												<i class="ft-clock"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">เลขที่รายการ/เบอร์โทรศัพท์</label>
										<input type="text" class="form-control" id="from_acc_truewallet" name="from_acc_truewallet" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">ยอดเงิน</label>
										<input type="number" class="form-control" id="truewallet_amount" name="amount" autocomplete="off" required="" step="0.01">
									</div>
								</div>
								<div class="col-md-6">
									  <div class="form-group"> 
									    <label class="form-label">สลิปโอนเงิน</label>
									    <input type="file" name="slip" id="slip_truewallet" accept="image/*" class="form-control slip">
									  </div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-truewallet" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-truewallet" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab42" aria-labelledby="base-tab42">
						<h4 class="mt-1">เติมเครดิต SCB</h4>
						<form class="form myway-form-credit-scb" id="myway-form-credit-scb" enctype="multipart/form-data" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-6">

									<div class="form-group">
										<div class="input-group">
											<input type='text' name="selectdate" class="form-control selectdate" placeholder="วันที่" autocomplete="off" />
											<div class="input-group-append">
												<span class="input-group-text">
													<span class="la la-calendar-o"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="position-relative has-icon-left">
											<input type="time" id="timesheetinput5" class="form-control" name="starttime">
											<div class="form-control-position" style="top: 15px;">
												<i class="ft-clock"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">จากเลข บช.</label>
										<input type="text" class="form-control" id="from_acc" name="from_acc" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">ยอดเงิน</label>
										<input type="number" class="form-control" id="kbank_amount" name="amount" autocomplete="off" required="" step="0.01">
									</div>
								</div>
							</div>
							<div class="row">
							   <div class="col-md-6">
									  <div class="form-group"> 
									    <label class="form-label">สลิปโอนเงิน</label>
									    <input type="file" name="slip" id="slip_scb" accept="image/*" class="form-control slip">
									  </div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="pg_lname">เข้า บช.</label>
										<select name="scb_bank_id" class="form-control border-primary scb_bank_id">
											<option value="0">เลือก</option>
											<?php
											if ($RSBankSettingS['num_rows'] > 0) {
												foreach ($RSBankSettingS['result_array'] as $item) {
											?>
													<option value="<?php echo $item['bank_account']; ?>"><?php echo $item['bank_short']; ?> - <?php echo $item['bank_account']; ?> - <?php echo $item['account_name']; ?></option>
											<?php
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-scb" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-scb" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab57" aria-labelledby="base-tab57">
						<h4 class="mt-1">เติมเครดิต KBANK</h4>
						<form class="form myway-form-credit-kbank" id="myway-form-credit-kbank" enctype="multipart/form-data" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-6">

									<div class="form-group">
										<div class="input-group">
											<input type='text' name="selectdate" class="form-control selectdate" placeholder="วันที่" autocomplete="off" />
											<div class="input-group-append">
												<span class="input-group-text">
													<span class="la la-calendar-o"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="position-relative has-icon-left">
											<input type="time" id="timesheetinput5" class="form-control" name="starttime">
											<div class="form-control-position" style="top: 15px;">
												<i class="ft-clock"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">จากเลข บช.</label>
										<input type="text" class="form-control" id="from_acc_kbank" name="from_acc" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">ยอดเงิน</label>
										<input type="number" class="form-control" id="scb_amount" name="amount" autocomplete="off" required="" step="0.01">
									</div>
								</div>
							</div>
							<div class="row">
							   <div class="col-md-6">
									  <div class="form-group">
									    <label class="form-label">สลิปโอนเงิน</label> 
									    <input type="file" name="slip" id="slip_kbank" accept="image/*" class="form-control slip">
									  </div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="pg_lname">เข้า บช.</label>
										<select name="kbank_bank_id" class="form-control border-primary kbank_bank_id">
											<option value="0">เลือก</option>
											<?php
											if ($RSKbank['num_rows'] > 0) {
												foreach ($RSKbank['result_array'] as $item) {
											?>
													<option value="<?php echo $item['bank_account']; ?>"><?php echo $item['bank_short']; ?> - <?php echo $item['bank_account']; ?> - <?php echo $item['account_name']; ?></option>
											<?php
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-kbank" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-kbank" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab58" aria-labelledby="base-tab58">
						<h4 class="mt-1">เติมเครดิต Vizplay</h4>
						<form class="form myway-form-credit-vizplay" id="myway-form-credit-vizplay" enctype="multipart/form-data" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-6">

									<div class="form-group">
										<div class="input-group">
											<input type='text' name="selectdate" class="form-control selectdate" placeholder="วันที่" autocomplete="off" />
											<div class="input-group-append">
												<span class="input-group-text">
													<span class="la la-calendar-o"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="position-relative has-icon-left">
											<input type="time" id="timesheetinput6" class="form-control" name="starttime">
											<div class="form-control-position" style="top: 15px;">
												<i class="ft-clock"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">จากเลข บช.</label>
										<input type="text" class="form-control" id="from_acc_vizplay" name="from_acc" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">ยอดเงิน</label>
										<input type="number" class="form-control" id="vizplay_amount" name="amount" autocomplete="off" required="" step="0.01">
									</div>
								</div>
							</div>
							<div class="row">
							   <div class="col-md-6">
									  <div class="form-group">
									    <label class="form-label">สลิปโอนเงิน</label> 
									    <input type="file" name="slip" id="slip_bank" accept="image/*" class="form-control slip">
									  </div>
								</div> 
							</div>
							<div class="modal-footer">
								<span class="form-error-text-vizplay" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-vizplay" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab43" aria-labelledby="base-tab43">
						<h4 class="mt-1">ลบเครดิต</h4>
						<form class="myway-form-credit-delete" id="myway-form-credit-delete" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label">ยอดเงิน</label>
										<input type="number" class="form-control pg_del_amount" name="amount" autocomplete="off">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="pg_remark">หมายเหตุ</label>
										<div class="position-relative has-icon-left">
											<textarea rows="5" class="form-control pg_remark" name="remark" placeholder="หมายเหตุ..."></textarea>
											<div class="form-control-position" style="top: 12px;">
												<i class="ft-file"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-del-credit" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-delete" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>

					<div class="tab-pane" id="tab44" aria-labelledby="base-tab44">
						<h4 class="mt-1">เปิดเครดิตฟรี</h4>
						<form class="myway-form-credit-free" id="myway-form-credit-free" autocomplete="off" method="post" action>
							<input type="hidden" name="pg_ids" class="credit_pg_ids">
							<div class="row mt-2">
								<div class="col-md-12">
									<div class="form-group">
										<select name="remark-option" class="form-control border-primary remark-option-credit-free">
											<option value="0">เลือกหมายเหตุ</option>
											<option value="1">Free 50</option>
											<option value="2">Free 100</option>
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<span class="form-error-text-free-credit" style="color: #ff776d !important;"></span>
								<input type="button" class="btn btn-danger submit-credit-free" value="ทำรายการ">
								<input type="button" class="btn btn-outline-light" data-dismiss="modal" value="ยกเลิก">
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
	<?php echo $pg_footer ?>
	<script type="text/javascript">
		var datatablePGGmage;
		var state_submit=false;
		$(function() {
			$('input[type=radio][name=pg_setting]').change(function() {
				if (this.value == 1) {
					$('.div_setting_aff').addClass('opacity7');
					$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', true); 
				}else{
					$('.div_setting_aff').removeClass('opacity7');
					$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', false); 
				}
			});
			$('input[type=radio][name=cashback_setting]').change(function() {
				if (this.value == 1) {
					$('.div_setting_cashback').addClass('opacity7');
					$("#cashbacksetting").prop('disabled', true); 
				}else{
					$('.div_setting_cashback').removeClass('opacity7');
					$("#cashbacksetting").prop('disabled', false).focus();
				}
			});
			
			$(document).on("click", ".btn_get_bonus", function() {
				var _this = this;
				$.when(
					$('.btn_get_bonus').removeClass('btn-success').addClass('btn-info').promise(),
					$('.btn_get_bonus.btn-success').removeClass('btn-info').promise(),
				).done(function() {
					$(_this).removeClass('btn-info').addClass('btn-success');
				});
				$('[name="subpro_id"]').val($(this).data('subpro_id'));
				$('[name="amount"]').val($(this).data('amount') * 1);

				var data = {
					pg_ids: $('#myway-form-add-pro .credit_pg_ids').val(),
					pro_id: $(this).data('pro_id'),
					subpro_id: $(this).data('subpro_id')
				};

			});

			$('.MenuMemberMain').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?= base_url('member/list_json') ?>",
					"type": "GET",
					"data": function(d) {
						d.search_input = $('#search_input').val();
						d.search_category = $('#search_category').val();
					}
				},
				"createdRow": (row, data, dataIndex, cells) => {
					if(data[8]=='เฝ้าระวัง'){
						$(row).addClass('bg-warning');
					}
					// $(row).addClass('selected');
				},
				"columns": [
					null,
					null,
					null,
					null,
					null,
					{
						"sClass": "text-center"
					},
					{
						"width": "18%"
					},
					{
						"width": "30%"
					}
				],
				"initComplete": function(settings, json) {
					pg_reload_Checked();

				}
			});
			$(document).on("click", "#btn_search", function() {
				if ($('#search_input').val() == '') {
					alert('กรุณากรอกข้อมูลลูกค้าที่ต้องการค้นหา!');
					$('#search_input').focus();
					return false;
				}
				ReloadloadData();
			});

			$('.selectdate,.selectdate_truewallet').pickadate({
				selectMonths: true,
				selectYears: true,
				monthsFull: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม'],
				monthsShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
				weekdaysShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
				format: 'yyyy-mm-dd',
				formatSubmit: 'yyyy-mm-dd'
			});
			$('.pg-data-table thead').on('click', 'th', function() {
				pg_reload_Checked();
			});
			$('.pg-data-table').on('length.dt', function(e, settings, len) {
				pg_reload_Checked();
			});
			$('.pg-data-table').on('draw.dt', function(e, ctx, id, data) {
				pg_reload_Checked();
			});
			$('.pg-data-table').on('page.dt', function() {
				pg_reload_Checked();
			});
			$('#bank_code').select2({
				dropdownParent: $('#modal-member')
			});
			$('#modal-member').on('hidden.bs.modal', function(event) {
				$('.UserNameText').html("");
				$('.pg_telephone,.pg_fname,.pg_lname,.pg_line_id,.setting_bank_id,[name="pg_ids"],#pg_member_password,.pg_aff,.pg_aff_percent1,.pg_aff_percent2,.pg_setting,.pg_type,.pg_com').val("");
				$('.bank_code').val(0).trigger('change');
				$('.pg_bank_accountnumber').val(0);
			});
			$('#modal-credit-member').on('hidden.bs.modal', function(event) {
				$('.selectdate_truewallet,.starttime_truewallet,#truewallet_amount,#from_acc_truewallet,.pg_del_amount,#pg_amount,.pg_remark,#scb_amount,.pg_telephone,.pg_fname,.pg_lname,.pg_line_id,.setting_bank_id,[name="pg_ids"],[name="selectdate"],[name="starttime"],#from_acc').val("");
				$('.bank_code').val(0).trigger('change');
				$('.scb_bank_id,.remark-option-credit-free').val(0);
				$('.remark-option').val(-1);

			});
			$('.submit-member').click(function() {
				if($('#pg_truewallet_phone').val()!=''&&$('#pg_bank_accountnumber').val()!=''&&$('#choose_bank').val()==0){
					swal("Error!", 'กรุณาเลือก เลือกบัญชีถอนเงินหลัก', "error");return;
				}
				$('.myway-form-member').submit();
			});
			var SubmitFormMember = $('.myway-form-member');
			SubmitFormMember.ajaxForm({
				url: '<?php echo site_url("member/add_edit_member") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
						datatablePGGmage.ajax.reload(null, false);
						pg_reload_Checked();
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


			$('.submit-credit-munual').click(function() {
				var Remarkoption = $('#remark-option');
				var Pg_amount = $('#pg_amount');
				if (Pg_amount.val() <= 0) {
					swal("Error!", 'กรุณาเลือก ระบุจำนวนเครดิต', "error");
					Pg_amount.focus();
					return false
				}
				if (Remarkoption.val() == -1) {
					swal("Error!", 'กรุณาเลือก เลือกหมายเหตุ', "error");
					Remarkoption.focus();
					return false
				} 
				SubmitForm('#myway-form-credit-munual'
				,'<?=site_url("promotion/add_credit_manual") ?>'
				,'.form-error-text-credit-munual');
			 }); 

			$('.submit-credit-scb').click(function() {
				SubmitForm('#myway-form-credit-scb'
				,'<?=site_url("member/add_credit_scb") ?>'
				,'.form-error-text-scb');
			}); 

			$('.submit-credit-kbank').click(function() { 
				SubmitForm('#myway-form-credit-kbank'
				,'<?=site_url("member/add_credit_kbank") ?>'
				,'.form-error-text-kbank');
			}); 
			$('.submit-credit-vizplay').click(function() { 
				SubmitForm('#myway-form-credit-vizplay'
				,'<?=site_url("member/add_credit_vizplay") ?>'
				,'.form-error-text-vizplay');
			}); 
			$('.submit-credit-delete').click(function() { 
				SubmitForm('#myway-form-credit-delete'
				,'<?=site_url("member/del_credit") ?>'
				,'.form-error-text-del-credit');
			});  
			$('.submit-credit-free').click(function() { 
				SubmitForm('#myway-form-credit-free'
				,'<?=site_url("member/free_credit") ?>'
				,'.form-error-text-free-credit');
			}); 
			$('.submit-credit-truewallet').click(function() { 
				SubmitForm('#myway-form-credit-truewallet'
				,'<?=site_url("member/add_credit_truewallet") ?>'
				,'.form-error-text-truewallet');
			}); 

		});

		function showpro(_event) {
			$('#turnover_amount').hide();
			if (['23', '24'].indexOf(_event.value) == -1) {
                if(['151', '157'].indexOf(_event.value) != -1){$('#turnover_amount').show();}
				$("#content_tab46").html('');
				return true
			}
			$.ajax({
				type: 'GET',
				cache: false,
				url: "<?= base_url('promotion/promotionList') ?>",
				data: {
					pro_id: _event.value
				},
				dataType: "json",
				beforeSend: function() {
					Myway_ShowLoader();
				},
				success: function(data) {
					$("#content_tab46").html(data.data);
					Myway_HideLoader();
				},
				error: function(xhr) { // if error occured
					Myway_ShowLoader();
				}
			});
		}

		function ReloadloadData() {
			datatablePGGmage.ajax.reload();
		}

		function ChangeStatusPGGamge(ids, pg_status) {
			Myway_ShowLoader();
			$.ajax({
				url: '<?php echo base_url("member/update_member_status") ?>',
				type: 'post',
				data: {
					ids: ids,
					pg_status: pg_status
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						Myway_HideLoader();
					} else {
						Myway_HideLoader();
					}
				}

			});
		}

		function EditMember(ids) {
			Myway_ShowLoader();
			$('#turnover_amount').hide();
			$('[name="pg_ids"]').val(ids);
            $('.form-error-text').html('');
			$('.pggame_username').attr('readonly', 'readonly');
			$.ajax({
				url: '<?php echo base_url("member/list_json_id") ?>',
				type: 'post',
				data: {
					ids: ids
				},
				dataType: 'json',
				success: function(sr) {
					if (sr.Message == true) {
						var result = sr.Result[0];


						$('.UserNameText').html(" - " + result.username);
						$('.pg_telephone').val(result.telephone);
						$('.pg_truewallet_account').val(result.truewallet_account);
						$('.pg_fname').val(result.fname);
						$('.pg_lname').val(result.lname);
						$('.bank_code').val(result.bank_code).trigger('change');
						$('.pg_bank_accountnumber').val(result.bank_accountnumber);
						$('.pg_line_id').val(result.line_id);
						$('.setting_bank_id').val(result.setting_bank_id);
						$('input[name=pg_aff]').prop('checked',false);
						$("input[name=pg_aff][value=" + result.aff_type + "]").prop('checked',true); 
						$('input[name=pg_setting]').prop('checked',false);
						$("input[name=pg_setting][value=" + result.aff_percent_use_default + "]").prop('checked',true);
						$('input[name=pg_type]').prop('checked',false);
						$("input[name=pg_com][value=" + result.com_use_default + "]").prop('checked',true); 
						$("input[name=pg_type][value=" + result.type_member + "]").prop('checked',true);
						$('input[name=cashback_setting]').prop('checked',false);
						$('input[name=pg_truewallet_phone]').val(result.truewallet_phone);
						$('select[name=choose_bank]').val(result.choose_bank);
						if(result.cashback_rate==-1){
							$("input[name=cashback_setting][value=1]").prop('checked',true);  
							$("#cashbacksetting").prop('disabled', true); 
							$('.div_setting_cashback').addClass('opacity7');
						}else{
							$('input[name=cashbacksetting]').val(result.cashback_rate);
							$("#cashbacksetting").prop('disabled', false); 
							$("input[name=cashback_setting][value=2]").prop('checked',true);  	
							$('.div_setting_cashback').removeClass('opacity7');
						} 
						if(result.aff_percent_use_default==1 || result.aff_percent_use_default==null || result.aff_percent_use_default==0){
							$('.div_setting_aff').addClass('opacity7');
							$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', true); 
						}else{
							$('.div_setting_aff').removeClass('opacity7');
							$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', false); 
						}
						$('.pg_aff_percent1').val(result.aff_percent1);
						$('.pg_aff_percent2').val(result.aff_percent2);
						$('input[name=lock_Turnover]').prop('checked',false);
						$("input[name=lock_Turnover][value=" + result.ignore_zero_turnover + "]").prop('checked',true);
						$('#modal-member').modal({
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

		function pg_reload_Checked() {
			setTimeout(function() {
				$(".switch_status_pggamge").bootstrapSwitch({
					onSwitchChange: function(e, state) {
						ChangeStatusPGGamge(e.target.attributes[2].value, state)
					}
				});
			}, 500);
		}

		function AddCreditMember(ref_id, MemberName) {
			$('.CreditMemberText').html("- " + MemberName);
			$('#content_tab46').html("");
			$('input[name="slip"]').val("");
			$('.credit_pg_ids').val(ref_id);
			$('#turnover_amount').hide();
			$('#modal-credit-member').modal({
				show: true,
				backdrop: 'static',
				keyboard: false
			});
		}
		function SubmitForm(file_id,myurl,error_el) {
			if (state_submit) { return} 
			state_submit=true;
			var formData = new FormData($(file_id)[0]);  
				$.ajax({
					url: myurl,
					type: 'post',
					dataType: 'json',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						Myway_ShowLoader();
					},
					success: function(result, statusText, xhr, form) {
						state_submit=false;
						if (result.Message == true) {
						datatablePGGmage.ajax.reload(null, false);
						pg_reload_Checked();
						Myway_HideLoader();
						model_success_pggame();
						} else {
							if (result.boolError == 1) {
								swal("Error!", result.ErrorText, "error");
								$(error_el).html(result.ErrorText);
							} else {
								$('.modal').modal('hide');
							}
							Myway_HideLoader();
						}
					} 
				}); 
		}

	</script>
</body>

</html>