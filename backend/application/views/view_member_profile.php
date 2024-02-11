<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/tables/datatable/datatables.min.css') ?>">
	<style type="text/css">
		.width24per {
			width: 24%;
			white-space: nowrap;
		}
	</style>
	<style>
	

		.mh400 {
			min-height: 400px;
		}

		.fz20 {
			font-size: 20px;
		}

		.fz20 i {
			font-size: 60px !important;
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

		.tab-pane.fade {
			display: none;
			min-height: 400px;
		}

		.tab-pane.fade.active.show {
			display: block;
			transition: visibility 0s, opacity 0.5s linear;
		}

		.text-short {
			overflow: hidden;
			display: -webkit-box;
			-webkit-line-clamp: 1;
			line-clamp: 2;
			-webkit-box-orient: vertical;
		}
	</style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content info_section">
		<div class="content-wrapper">
			<div class="content-header row align-items-center mb-1">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">ข้อมูลสมาชิก</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active"><a href="<?php echo base_url('member') ?>">User</a>
								</li>
								<li class="breadcrumb-item active"><?= $UserName ?> (<?= $member_no ?>)
								</li>
							</ol>
						</div>
					</div>
				</div>
				<div class="content-header-right col-md-4 col-12 d-flex">
					<fieldset>
						<div class="float-right">
						<?php $chk = '';
						if($status==1){ $chk = 'checked'; } 
						?>
							<input type="checkbox" class="switch_status_pggamge" ref-id="<?php echo $pg_ref; ?>" data-on-color="success" data-off-color="danger"<?php echo $chk; ?> />
						</div>
					</fieldset>
					<!-- <button onclick="AddCreditMember()" class="btn btn-primary btn-sm mt005px mt-0 ml-1"><i class="ft-credit-card"></i> เติมเครดิต</button> -->
				</div>
				<div class="content-header-right col-md-2 col-12">
					
					<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<button class="btn btn-primary dropdown-toggle dropdown-menu-right box-shadow-2 px-2" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> Action</button>
						<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
							<a target="_blank" class="dropdown-item" href="<?php echo base_url('member/profile/' . $UserName . '/history') ?>">ดูประวัติย้อนหลัง</a>
							<button class="dropdown-item" onclick="EditMember()" >แก้ไขข้อมูลลูกค้า</button>
						</div>
					</div>
				</div>
				
			</div>
			<div class="content-body">
				<div class="row">
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= $main_wallet ?></h3>
											<h6>ยอดเงินคงเหลือ</h6>
										</div>
										<div>
											<i class="la la-money success font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= number_format($turnover_now, 2) ?></h3>
											<h6>เทิร์นปัจจุบัน</h6>
										</div>
										<div>
											<i class="icon-rocket info font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= number_format($turnover_collect, 2) ?></h3>
											<h6>เทิร์นสะสม</h6>
										</div>
										<div>
											<i class="icon-social-dropbox warning font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= number_format($sum_deposit, 2) ?></h3>
											<h6>ยอดฝากทั้งหมด</h6>
										</div>
										<div>
											<i class="la la-download font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= number_format($sum_withdraw, 2) ?></h3>
											<h6>ยอดถอนทั้งหมด</h6>
										</div>
										<div>
											<i class="la la-upload danger font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="card pull-up">
							<div class="card-content">
								<div class="card-body">
									<div class="media d-flex">
										<div class="media-body text-left">
											<h3 class="primary"><?= number_format($win_loss, 2) ?></h3>
											<h6>Win/Loss</h6>
										</div>
										<div>
											<i class="la la-exchange pink font-large-2 float-right"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ใหม่ -->
				<section class="mh400">
					<div class="row">
						<div class="col-12">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="pills-tab1" data-toggle="pill" href="#pills-1" role="tab" aria-controls="pills-1" aria-selected="true">รายละเอียด</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab2" data-toggle="pill" href="#pills-2" role="tab" aria-controls="pills-2" aria-selected="false">รายการฝาก/รับเครดิต</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab3" data-toggle="pill" href="#pills-3" role="tab" aria-controls="pills-3" aria-selected="false">รายการถอนเครดิต</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab7" data-toggle="pill" href="#pills-7" role="tab" aria-controls="pills-7" aria-selected="false">รายการฝาก/ถอนรายวัน</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab4" data-toggle="pill" href="#pills-4" role="tab" aria-controls="pills-4" aria-selected="false">รายการเข้าเกมส์</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab5" data-toggle="pill" href="#pills-5" role="tab" aria-controls="pills-5" aria-selected="false">โปรโมชั่น</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="pills-tab6" data-toggle="pill" href="#pills-6" role="tab" aria-controls="pills-6" aria-selected="false">ชวนเพื่อน</a>
								</li>

							</ul>
							<div class="tab-content" id="pills-tabContent"></div>
							<div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-tab1">

								<div class="row">
									<div class="col-lg-6">
										<div class="card">
											<div class="card-body">
												<table class="table mb-2" id="table-tab">
													<tbody>
														<tr class="border-none fz20">
															<th colspan="2" class="text-center">
																<i class="la la-user"></i> <br><?= $InfoMember->fname . " " . $InfoMember->lname ?>
															</th>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">เบอร์โทร :</th>
															<td class="border-top-0"><?= $InfoMember->telephone ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">LINE ID :</th>
															<td class="border-top-0"><?= $InfoMember->line_id ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">เลขบัญชี :</th>
															<td class="border-top-0"><?= $InfoMember->bank_name ?><br> <?= $InfoMember->bank_accountnumber ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">รหัสอ้างอิง :</th>
															<td class="border-top-0"><?= $InfoMember->member_login ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">truewallet account :</th>
															<td class="border-top-0"><?= $InfoMember->truewallet_account ?><?= ($InfoMember->truewallet_phone != null && $InfoMember->truewallet_phone != '' ? "({$InfoMember->truewallet_phone})" : '') ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">บัญชีถอนเงินหลัก</th>
															<td class="border-top-0"><?= ($InfoMember->choose_bank == 1 ? "ธนาคาร({$InfoMember->bank_accountnumber})" : ($InfoMember->choose_bank == 2 ? "TrueWallet({$InfoMember->truewallet_phone})" : 'ไม่ได้ระบุบัญชีถอนเงินหลัก')) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">ช่องทางที่รู้จักเรา :</th>
															<td class="border-top-0"><?= $this->configdata->toString_source($InfoMember->source_ref) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">วันที่สมัคร :</th>
															<td class="border-top-0"><?= $InfoMember->create_at ?></td>
														</tr>

													</tbody>
												</table>
											</div>
										</div>
										<div class="card">
											<div class="card-header">
												รหัส User Web
											</div>
											<div class="card-body">
												<table class="table mb-2" id="table-tab">
													<tbody>
														<tr>
															<th scope="row" class="border-top-0 width24per">ยูสเซอร์เนม :</th>
															<td class="border-top-0"><?= $InfoMember->username ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">รหัสผ่าน :</th>
															<td class="border-top-0"><?= $InfoMember->passwordText ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">ลิงค์เข้าสู่ระบบอัตโนมัติ :</th>
															<td class="border-top-0"><a href="<?= $linkloginmem ?>" >
																	<p class="text-short"><?= $linkloginmem ?></p>
																</a></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">ผู้แนะนำ : </th>
															<td class="border-top-0">(1) <?=($InfoMember->group_af_l1!=''&&$InfoMember->group_af_l1!=null)?$InfoMember->group_af_l1:'-' ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">พาร์ทเนอร์ : </th>
															<td class="border-top-0"> <?=($InfoMember->partner!=''&&$InfoMember->partner!=null)?$InfoMember->partner:'-' ?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-lg-6">

										<div class="card">
											<div class="card-header">
												กระเป๋าเงิน
											</div>
											<div class="card-body">
												<table class="table mb-2" id="table-tab">
													<tbody>
														<tr>
															<th scope="row" class="border-top-0 width24per">ยอดเงินคงเหลือ :</th>
															<td class="border-top-0"><?= $main_wallet ?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="card">
											<div class="card-header">
												สรุปฝากถอน
											</div>
											<div class="card-body">
												<table class="table mb-2" id="table-tab">
													<tbody>
														<tr>
															<th scope="row" class="border-top-0 width24per">รวมยอดฝาก :</th>
															<td class="border-top-0"><?= number_format($sum_deposit, 2) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">รวมยอดถอน :</th>
															<td class="border-top-0"><?= number_format($sum_withdraw, 2) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">ลูกค้ากำไร :</th>
															<td class="border-top-0"><?= ($sum_withdraw)> 0 ? number_format(($sum_withdraw - $sum_deposit), 2) : 0.00 ?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="card">
											<div class="card-header">
												turn / win loss
											</div>
											<div class="card-body">
												<table class="table mb-2" id="table-tab">
													<tbody>
														<tr>
															<th scope="row" class="border-top-0 width24per">เทิร์นปัจจุบัน :</th>
															<td class="border-top-0"><?= number_format($turnover_now, 2) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">เทิร์นสะสม :</th>
															<td class="border-top-0"><?= number_format($turnover_collect, 2) ?></td>
														</tr>
														<tr>
															<th scope="row" class="border-top-0 width24per">Win/Loss :</th>
															<td class="border-top-0"><?= number_format($win_loss, 2) ?></td>
														</tr>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>
							<div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-tab2">
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart1" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend1" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily1">ค้นหา</button>
													</form> 
												</div>
												<div class="table-responsive">
													<table class="table table-striped w-100  ajax-sourced pg-data-table-deposit table-middle">
														<thead>
															<tr>
																<th class="text-center">วัน/เวลา ฝากธนาคาร</th>
																<th class="text-center">วัน/เวลา เข้าระบบ</th>
																<th class="text-center">ช่องทาง</th>
																<th class="text-center">จำนวนเงิน</th>
																<th class="text-center">อ้างอิง</th>
																<th class="text-center">สถานะ</th>
																<th class="text-center">หมายเหตุ</th>
															</tr>
														</thead>
														<tfoot>
															<tr>
															
																<th class="text-right"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-3" role="tabpanel" aria-labelledby="pills-tab3">
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart2" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend2" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily2">ค้นหา</button>
													</form> 
												</div>
												<table class="table table-striped w-100  ajax-sourced pg-data-table-withdraw table-middle">
													<thead>
														<tr>
															<th class="text-center">วัน/เวลา ขอถอน</th>
															<th class="text-center">วัน/เวลา ทำรายการ</th>
															<th class="text-center">ขอถอน</th>
															<th class="text-center">ถอนจริง</th>
															<th class="text-center">ช่องทาง</th>
															<th class="text-center">สถานะ</th>
															<th class="text-center">หมายเหตุ</th>
														</tr>
													</thead>
													<tfoot>
														<tr>
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-4" role="tabpanel" aria-labelledby="pills-tab4">
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="table-responsive">
													<table class="table table-striped w-100  ajax-sourced pg-data-table-game table-middle">
														<thead>
															<tr>
																<th class="text-center">วัน/เวลา</th>
																<th class="text-center">ค่าย</th>
																<th class="text-center">ยอดเงินเข้าเกมส์</th>
															</tr>
														</thead>
														<tfoot>
															<tr>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-5" role="tabpanel" aria-labelledby="pills-tab5">
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart_pro" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend_pro" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily_pro">ค้นหา</button>
													</form> 
												</div>
												<div class="table-responsive">
													<table class="table table-striped w-100  ajax-sourced pg-data-table-pro table-middle">
														<thead>
															<tr>

																<th class="text-center">วัน/เวลา เข้าระบบ</th>
																<th class="text-center">ช่องทาง</th>
																<th class="text-center">จำนวนเงิน</th>
																<th class="text-center">อ้างอิง</th>
																<th class="text-center">สถานะ</th>
																<th class="text-center">หมายเหตุ</th>
															</tr>
														</thead>
														<tfoot>
															<tr>
																<th class="text-center"></th>
																<th class="text-center"></th>
																<th class="text-center"></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-6" role="tabpanel" aria-labelledby="pills-tab6">
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart_aff1" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend_aff1" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily_aff1">ค้นหา</button>
													</form> 
												</div>
												<h4 class="card-title success">AFFILIATE LEVEL 1</h4>
												<table class="table table-striped w-100  ajax-sourced pg-data-table-af-l1 table-middle">
													<thead>
														<tr>
															<th class="text-center">USERNAME</th>
															<th class="text-center">วันที่สมัคร</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart_aff2" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend_aff2" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily_aff2">ค้นหา</button>
													</form> 
												</div>
												<h4 class="card-title success">AFFILIATE LEVEL 2</h4>
												<table class="table table-striped w-100  ajax-sourced pg-data-table-af-l2 table-middle">
													<thead>
														<tr>
															<th class="text-center">USERNAME</th>
															<th class="text-center">วันที่สมัคร</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="tab-pane fade" id="pills-7" role="tabpanel" aria-labelledby="pills-tab7">
								
								<div class="row">
									<div class="col">
										<div class="card">
											<div class="card-body">
												<div class="row mb-2">
													<form class="form-inline" onSubmit="return false">  
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timestart" class="d_w_daily_timestart form-control" placeholder="จากวันที่" autocomplete="off" >
														</div>
														<div class="form-group mx-sm-1">
															<label  class="sr-only">วันที่</label> 
															<input type="datetime-local" value="" id="d_w_daily_timeend" class="d_w_daily_timeend form-control" placeholder="ถึงวันที่" autocomplete="off" >
														</div>
														<button type="submit" class="btn btn-primary" id="btn_search_d_w_daily">ค้นหา</button>
													</form> 
												</div>

												<table class="table table-striped w-100  ajax-sourced data_table_deposit_withdraw_daily table-middle">
													<thead>
														
														<tr>
															<th class="text-center">วัน</th>
															<th class="text-center">ฝาก</th>
															<th class="text-center">ถอน</th>
															<th class="text-center">รับโบนัส</th>
															<th class="text-center">กำไร</th>
															<th class="text-center">เทิร์นโอเวอร์</th>
														</tr>
													</thead>
													<tfoot>
														<tr>
														<th class="text-center"></th>
														<th class="text-center"></th>
														<th class="text-center"></th>
														<th class="text-center"></th>
														<th class="text-center"></th>
															<th class="text-center"></th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- ใหม่ -->

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
					<input type="hidden" name="pg_ids" value="<?= $pg_ref ?>">
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
											<option value="73">VizPay</option>
											
											<?php
											foreach ($pro_list_all['result_array'] as $k => $v) {
												if (in_array($v['pro_id'], [13, 14])) {
													continue;
												}
											?>
												<option value="<?= $v['pro_id'] ?>"><?= $v['pro_name'] ?></option>
											<?php
											}
											?>
											
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
											<option value="1">Free 200</option>
											<option value="2">Free 50</option>
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
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="text/javascript">
		var state_submit=false;
		$(function() {
			var datatable_dw_daily=undefined;
			$(".switch_status_pggamge").bootstrapSwitch({
					onSwitchChange: function(e, state) {
						ChangeStatusPGGamge(e.target.attributes[2].value, state)
					}
				});
			$(document).on("click","#btn_search_d_w_daily",function() {
					datatable_dw_daily.ajax.reload();     
			});
			$(document).on("click","#btn_search_d_w_daily1",function() {
				datatable_deposit.ajax.reload();     
			});
			$(document).on("click","#btn_search_d_w_daily2",function() {
				datatable_withdraw.ajax.reload();     
			});
			$(document).on("click","#btn_search_d_w_daily_pro",function() {
				datatable_pro.ajax.reload();     
			});
			$(document).on("click","#btn_search_d_w_daily_aff1",function() {
				datatable_aff1.ajax.reload();     
			});
			$(document).on("click","#btn_search_d_w_daily_aff2",function() {
				datatable_aff2.ajax.reload();     
			});
			
			// $(".displayProfileMember a").click(function() {
			// 	var GotoSeection = $(this).attr("href");
			// 	var GotoClass = GotoSeection.slice(1);
			// 	$('html, body').animate({
			// 		scrollTop: $('.' + GotoClass + "_section").offset().top - 80
			// 	}, 1000);
			// });

			// $('.displayProfileMember').removeClass('d-none');
			// $('.MenuMemberMain').addClass('active');

			datatable_pro=$('.pg-data-table-pro').DataTable({
				autoWidth: false,
				"ajax": {
						"url": "<?php echo base_url('member/list_pro?ref=' . $pg_ref) ?>",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart_pro').val();
							d.timeend =$('#d_w_daily_timeend_pro').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				],footerCallback: function (row, data, start, end, display) {
					sum_footer_pro();
				} 
			});
			datatable_aff1=$('.pg-data-table-af-l1').DataTable({
				autoWidth: false,
				"ajax": {
						"url": "<?php echo base_url('member/list_af_l1?ref=' . $pg_ref) ?>",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart_aff1').val();
							d.timeend =$('#d_w_daily_timeend_aff1').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				]
			});
			datatable_aff2=$('.pg-data-table-af-l2').DataTable({
				autoWidth: false,
				"ajax": {
						"url": "<?php echo base_url('member/list_af_l2?ref=' . $pg_ref) ?>",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart_aff2').val();
							d.timeend =$('#d_w_daily_timeend_aff2').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				]
			});
			datatable_deposit= $('.pg-data-table-deposit').DataTable({
				"ajax": {
						"url": "<?php echo base_url('member/list_log_deposit_json?ref=' . $pg_ref) ?>",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart1').val();
							d.timeend =$('#d_w_daily_timeend1').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				]
				,footerCallback: function (row, data, start, end, display) {
					sum_footer_deposit();
				} 
			});
			
			datatable_withdraw =$('.pg-data-table-withdraw').DataTable({ 
				processing: true, 
				"ajax": {
						"url": "<?php echo base_url('member/list_log_withdraw_json?ref=' . $pg_ref) ?>",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart2').val();
							d.timeend =$('#d_w_daily_timeend2').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				],footerCallback: function (row, data, start, end, display) {
					sum_footer_withdraw();
				} 
				
			});
			datatable_dw_daily=$('.data_table_deposit_withdraw_daily').DataTable({ 
				processing: true, 
				"ajax": {
						"url": "<?php echo base_url('member/list_log_withdraw_json?ref=' . $pg_ref) ?>&reporttype=sumdaily",
						"type": "GET",
						"data": function(d){ 
							d.timestart =$('#d_w_daily_timestart').val();
							d.timeend =$('#d_w_daily_timeend').val(); 
						}
						,complete: function (data) { },
				 },
				"order": [
					[0, "desc"]
				]
				,footerCallback: function (row, data, start, end, display) {
					sum_deposit_withdraw_daily_to_footer();
				} 
			});
			$('.pg-data-table-game').DataTable({
				autoWidth: false,
				"ajax": "<?php echo base_url('member/list_log_game_json?ref=' . $pg_ref) ?>",
				"order": [
					[0, "desc"]
				],
				"columns": [{
						"width": "20%"
					},
					null,
					null
				],footerCallback: function (row, data, start, end, display) {
					sum_game_to_footer();
				} 
			});
			$('.submit-member').click(function() {
				$('.myway-form-member').submit();
			});
			$('#modal-member').on('hidden.bs.modal', function(event) {
				$('.UserNameText').html("");
				$('.pg_telephone,.pg_fname,.pg_lname,.pg_line_id,.setting_bank_id,[name="pg_ids"],#pg_member_password').val("");
				$('.bank_code').val(0).trigger('change');
				$('.pg_bank_accountnumber').val(0);
			});
			$('#modal-credit-member').on('hidden.bs.modal', function(event) {
				$('.selectdate_truewallet,.starttime_truewallet,#truewallet_amount,#from_acc_truewallet,.pg_del_amount,#pg_amount,.pg_remark,#scb_amount,.pg_telephone,.pg_fname,.pg_lname,.pg_line_id,.setting_bank_id,[name="pg_ids"],[name="selectdate"],[name="starttime"],#from_acc').val("");
				$('.bank_code').val(0).trigger('change');
				$('.scb_bank_id,.remark-option-credit-free').val(0);
				$('.remark-option').val(-1);

			});
			$("#pg_amount").keyup(function (e) { 
				if($('#remark-option').val()==97 || $('#remark-option').val()==98){
					$(this).val('100');
				}
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
						Myway_HideLoader();
						model_success_pggame();
						window.location.reload();
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
				// $('.myway-form-credit-munual').submit();
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
			var SubmitFormCreditMunual = $('.myway-form-credit-munual');
			SubmitFormCreditMunual.ajaxForm({
				url: '<?php echo site_url("member/add_credit_manual") ?>',
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
						window.location.reload();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text-credit-munual').html(result.ErrorText);
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});
			$('.submit-credit-truewallet').click(function() {
				$('.myway-form-credit-truewallet').submit();
			});
			var SubmitFormCreditTrueWallet = $('.myway-form-credit-truewallet');
			SubmitFormCreditTrueWallet.ajaxForm({
				url: '<?php echo site_url("member/add_credit_truewallet") ?>',
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
						window.location.reload();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text-free-credit').html(result.ErrorText);
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});

			$('.submit-credit-scb').click(function() {
				$('.myway-form-credit-scb').submit();
			});
			var SubmitFormCreditSCB = $('.myway-form-credit-scb');
			SubmitFormCreditSCB.ajaxForm({
				url: '<?php echo site_url("member/add_credit_scb") ?>',
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
						window.location.reload();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text-scb').html(result.ErrorText);
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});
			$('.submit-credit-delete').click(function() {
				$('.myway-form-credit-delete').submit();
			});

			var SubmitFormCreditDel = $('.myway-form-credit-delete');
			SubmitFormCreditDel.ajaxForm({
				url: '<?php echo site_url("member/del_credit") ?>',
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
						window.location.reload();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text-del-credit').html(result.ErrorText);
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});
			$('.submit-credit-free').click(function() {
				$('.myway-form-credit-free').submit();
			});

			var SubmitFormCreditFREE = $('.myway-form-credit-free');
			SubmitFormCreditFREE.ajaxForm({
				url: '<?php echo site_url("member/free_credit") ?>',
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
						window.location.reload();
					} else {
						if (result.boolError == 1) {
							$('.form-error-text-free-credit').html(result.ErrorText);
						} else {
							$('.modal').modal('hide');
						}
						Myway_HideLoader();
					}
				}
			});
		});

		function EditMember() {
			Myway_ShowLoader();

			$('.pggame_username').attr('readonly', 'readonly');
			$.ajax({
				url: '<?php echo base_url("member/list_json_id") ?>',
				type: 'post',
				data: {
					ids: '<?= $pg_ref ?>'
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
						$('input[name=pg_aff]').prop('checked', false);
						$("input[name=pg_aff][value=" + result.aff_type + "]").prop('checked', true);
						$('input[name=pg_setting]').prop('checked', false);
						$("input[name=pg_setting][value=" + result.aff_percent_use_default + "]").prop('checked', true);
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
						if (result.aff_percent_use_default == 1 || result.aff_percent_use_default == null || result.aff_percent_use_default == 0) {
							$('.div_setting_aff').addClass('opacity7');
							$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', true); 
						} else {
							$('.div_setting_aff').removeClass('opacity7');
							$("#pg_aff_percent1,#pg_aff_percent2").prop('disabled', false); 
						}
						$('.pg_aff_percent1').val(result.aff_percent1);
						$('.pg_aff_percent2').val(result.aff_percent2);
						$('input[name=lock_Turnover]').prop('checked', false);
						$("input[name=lock_Turnover][value=" + result.ignore_zero_turnover + "]").prop('checked', true);
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

		$('#autologin').click(function() {

			$.ajax({
				url: '<?php echo DOMAIN_FRONTEND.'/actions/login.php' ?>',
				type: 'post',
				data: {
					token: '<?= $linkloginmem ?>'
				},
				success: function(response) {
					$("#xhtml").html(response);
				}
			});
		});


		function AddCreditMember() {
			$('.CreditMemberText').html("- " + "<?= $UserName ?>");
			$('#content_tab46').html("");
			$('input[name="slip"]').val("");
			$('.credit_pg_ids').val("<?= $pg_ref ?>");
			$('#modal-credit-member').modal({
				show: true,
				backdrop: 'static',
				keyboard: false
			});
			
		}
		function showpro(_event) {
			if (_event.value == '97' || _event.value== '98') {
				$("#pg_amount").val(100);
			}
			if (['23', '24'].indexOf(_event.value) == -1) {
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
		function numberWithCommas(x) { return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");} 
		var removebracketVal = function(input){ input=''+input;return input.replace(/\s*\(.*?\)\s*/g, '');}
	    var removetaghtml = function(input){ input=''+input;return input.replace( /(<([^>]+)>)/ig, '');} 
		var intVal = function (i) {i=''+i;
				if (typeof i=='string') {  i=i.replace( /<.*?>/g, '');i = i.replace(/[^0-9.+-]/g, ""); } 
				i=i.replace(/\,/g,'');
				return typeof i === 'string' ?i.replace(/[\$,]/g, '')*1 :typeof i === 'number' ? i : 0;};  
	    var formatcontent = function(input){ return intVal(removebracketVal(removetaghtml(input)));}
		function sum_deposit_withdraw_daily_to_footer(){  
			var api=$('.data_table_deposit_withdraw_daily').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 

			 var betpageTotal = api.column(1,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var bettotal = api.column(1).data().reduce( function (a, b) {return formatcontent(a) + formatcontent(b);}, 0 ); 
			 
			 var withdraw_betpageTotal = api.column(2,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var withdraw_bettotal = api.column(2).data().reduce( function (a, b) {return formatcontent(a) + formatcontent(b);}, 0 ); 

			 var bonus_betpageTotal = api.column(3,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var bonus_bettotal = api.column(3).data().reduce( function (a, b) {return formatcontent(a) + formatcontent(b);}, 0 ); 

			 var profit_betpageTotal = api.column(4,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var profit_bettotal = api.column(4).data().reduce( function (a, b) {return formatcontent(a) + formatcontent(b);}, 0 ); 

			 var turnover_betpageTotal = api.column(5,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var turnover_bettotal = api.column(5).data().reduce( function (a, b) {return formatcontent(a) + formatcontent(b);}, 0 );  
			// //  ----------------------------------------------------------------------------------------------------------------------
			var bettotal_string = `รวมปัจจุบัน: <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(betpageTotal).toFixed(2))} บาท</b><br>`;  
			  if(betpageTotal!=bettotal){
			    bettotal_string += `ทั้งหมด: <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(bettotal).toFixed(2))} บาท</b>`;   
			   }
			 var withdraw_bettotal_string = `รวมปัจจุบัน: <b class='Block' style='color:#dc35d1'>${numberWithCommas(Number.parseFloat(withdraw_betpageTotal).toFixed(2))} บาท</b><br>`;  
			  if(withdraw_betpageTotal!=withdraw_bettotal){
			    withdraw_bettotal_string += `ทั้งหมด: <b class='Block' style='color:#dc35d1'>${numberWithCommas(Number.parseFloat(withdraw_bettotal).toFixed(2))} บาท</b>`;   
			   }
			  var bonus_total_string = `รวมปัจจุบัน: <b class='Block text-warning'>${numberWithCommas(Number.parseFloat(bonus_betpageTotal).toFixed(2))} บาท</b><br>`;    
			  if(bonus_bettotal!=bonus_betpageTotal){
			    bonus_total_string += `ทั้งหมด: <b class='Block text-warning'>${numberWithCommas(Number.parseFloat(bonus_bettotal).toFixed(2))} บาท</b>`;   
			   }
		     
		    var profit=(bettotal)-(withdraw_bettotal);
            var profit_txt='0.00';var profit_stype='text-danger';
			if(profit<0){profit_stype='text-danger';
				profit_txt=`<span class='${profit_stype}'>${numberWithCommas(Number.parseFloat(profit).toFixed(2))}</span>`;
			}else if(profit>0){profit_stype='text-success';
				profit_txt=`<span class='${profit_stype}'>${numberWithCommas(Number.parseFloat(profit).toFixed(2))}</span>`;
			}
		    var profit_bettotal_string = `รวมปัจจุบัน: <b class='Block ${profit_stype}'>${profit_txt} บาท</b><br>`;  
			  if(withdraw_betpageTotal!=withdraw_bettotal){
			    profit_bettotal_string += `ทั้งหมด: <b class='Block ${profit_stype}'>${profit_txt} บาท</b>`;   
			   }
			  var turnover_bettotal_string = `รวมปัจจุบัน: <b class='Block text-info'>${numberWithCommas(Number.parseFloat(turnover_betpageTotal).toFixed(2))} บาท</b><br>`;  
			  if(withdraw_betpageTotal!=withdraw_bettotal){
			    turnover_bettotal_string += `ทั้งหมด: <b class='Block text-info'>${numberWithCommas(Number.parseFloat(turnover_bettotal).toFixed(2))} บาท</b>`;   
			   }    
			$(api.column(1).footer()).html(bettotal_string); 
			$(api.column(2).footer()).html(withdraw_bettotal_string); 
			$(api.column(3).footer()).html(bonus_total_string); 
			$(api.column(4).footer()).html(profit_bettotal_string);  
			$(api.column(5).footer()).html(turnover_bettotal_string); 
		}	
		function sum_footer_deposit(){  
			var api=$('.pg-data-table-deposit').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 

			 var amount = api.column(3,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			  
			// //  ----------------------------------------------------------------------------------------------------------------------
			var amount_string = ` <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(amount).toFixed(2))}</b><br>`;  
		
			$(api.column(3).footer()).html(amount_string); 
		}
		function sum_footer_withdraw(){  
			var api=$('.pg-data-table-withdraw').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 

			 var amount = api.column(2,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			 var amount_actual = api.column(3,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			  
			// //  ----------------------------------------------------------------------------------------------------------------------
			var amount_string = ` <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(amount).toFixed(2))}</b><br>`;  
			var amount_actual_string = ` <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(amount).toFixed(2))}</b><br>`;  
		
			$(api.column(2).footer()).html(amount_string); 
			$(api.column(3).footer()).html(amount_actual_string); 
		} 	
		function sum_game_to_footer(){  
			var api=$('.pg-data-table-game').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 

			 var amount = api.column(2,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			  
			// //  ----------------------------------------------------------------------------------------------------------------------
			var amount_string = ` <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(amount).toFixed(2))}</b><br>`;  
		
			$(api.column(1).footer()).html(`รวม`); 
			$(api.column(2).footer()).html(amount_string); 
		} 	
		function sum_footer_pro(){  
			var api=$('.pg-data-table-pro').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 

			 var amount = api.column(2,{ page: 'current'} ).data().reduce( function (a, b) { return formatcontent(a) + formatcontent(b);},0);
			  
			// //  ----------------------------------------------------------------------------------------------------------------------
			var amount_string = ` <b class='Block text-primary'>${numberWithCommas(Number.parseFloat(amount).toFixed(2))}</b><br>`;  
		
			$(api.column(2).footer()).html(amount_string); 
		} 	
		
	</script>
	<span id="xhtml" style="display: none;"></span>
</body>

</html>