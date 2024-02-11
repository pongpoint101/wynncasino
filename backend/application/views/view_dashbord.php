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
				<div class="content-header row">
					<div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">DashBoard</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="javascript:void(0)">สรุปวันนี้ | อัพเดตล่าสุด: <?php echo $PGDateTimeNow ?></a>
									</li>
								</ol>
							</div>
						</div>
					</div>
					
					<div class="content-header-right col-md-4 col-12 text-right">
						<div class="form-group mr-1">
							<div class="input-group">
								<input type="text" value="<?=$PGDateNow?>" class="form-control search_date" placeholder="วันที่" autocomplete="off" /> 
								<div class="input-group-append"> 
									<span class="input-group-text"> 
									<span class="la la-calendar-o"></span>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="content-body">
					<div class="row">
						<div class="col-xl-3">
							<div class="card pull-up">
								<div class="card-content">
									<a href="<?=base_url('deposit')?>">
										<div class="card-body">
											<div class="media d-flex">
												<div class="media-body text-left">
													<h3 class="primary text_deposit_today_amount">...</h3>
													<h6>ยอดฝาก - วันนี้</h6>
												</div>
												<div>
													<i class="ft-download primary font-large-2 float-right"></i>
												</div>
											</div>
										</div>
									</a>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card pull-up">
								<div class="card-content">
									<a href="<?=base_url('withdraw')?>">
										<div class="card-body">
											<div class="media d-flex">
												<div class="media-body text-left">
													<h3 class="warning text_withdraw_today_amount">...</h3>
													<h6>ยอดถอน - วันนี้</h6>
												</div>
												<div>
													<i class="ft-upload warning font-large-2 float-right"></i>
												</div>
											</div>
										</div>
									</a>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card pull-up">
								<div class="card-content">
									<div class="card-body">
										<div class="media d-flex">
											<div class="media-body text-left">


												<h3 class="text_profit_today_02">...</h3>
												<h6>กำไร - วันนี้</h6>
											</div>
											<div>
												<i class="font-large-2 float-right icon_profit_pg"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card pull-up">
								<div class="card-content">
									<div class="card-body">
										<div class="media d-flex">
											<div class="media-body text-left">

												<h3 class="text_remain">...</h3>
												<h6>ยอดเครดิตคงค้าง - ทั้งหมด</h6>
											</div>
											<div>
												<i class="font-large-2 float-right ft-briefcase"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-xl-4 col-12">
							<div class="card">
								<div class="card-content">
									<div class="card-body">
										<div class="media d-flex">
											<div class="align-self-center">
												<i class="icon-user-follow font-large-2 float-left"></i>
											</div>
											<div class="media-body text-right">
												<h3 class="text_registered_today">...</h3>
												<span>สมัครใหม่(วันนี้)</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-12">
							<div class="card">
								<div class="card-content">
									<div class="card-body">
										<div class="media d-flex">
											<div class="align-self-center">
												<i class="icon-user-following font-large-2 float-left"></i>
											</div>

											<div class="media-body text-right">
												<h3 class="text_first_deposit_td">...</h3>
												<span>เติมเปิดยูส(วันนี้)</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-12">
							<div class="card">
								<div class="card-content">
									<div class="card-body">
										<div class="media d-flex">
											<div class="align-self-center">
												<i class="icon-users font-large-2 float-left"></i>
											</div>

											<div class="media-body text-right">
												<h3 class="text_member_count">...</h3>
												<span>สมาชิกในระบบ</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_deposit_pending_withdraw_pending">...</h2>
												<span>รอดำเนินการ(วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_deposit_today_count">...</h2>
												<span>รายการฝาก</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_withdraw_today_count">...</h2>
												<span>รายการถอน</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_aff_claimed_amount">...</h2>
												<span>แนะนำเพื่อน</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_mt_amount">...</h2>
												<span>เติมมือ SCB</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_topup_truewallet">...</h2>
												<span>เติมมือ TrueWallet</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_claim_comm_amount">...</h2>
												<span>ยอดรับค่าคอม</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_claim_return_loss_amount">...</h2>
												<span>ยอดรับคืนยอดเสีย</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_deposit_tw">...</h2>
												<span>ยอดฝาก TrueWallet</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_topup_kbank_amount">...</h2>
												<span>เติมมือ Kbank</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_sms_delayed">...</h2>
												<span>SMS Delayed</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_deposit_vizplay">...</h2>
												<span>ยอดฝาก VizPlay</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_bank_not_found">...</h2>
												<span>ไม่พบบัญชี</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text-success text_active_deposit">...</h2>
												<span>Active deposite user</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
 
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_add_credit_amount">...</h2>
												<span>เติมเงิน (วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_del_credit_amount">...</h2>
												<span>ลบเครดิต (วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_bonus_cardgame">...</h2>
												<span>เปิดไพ่</span>
											</div>
										</div>
										<div class="col-lg-3 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_bonus_luckywheel_amount">...</h2>
												<span>วงล้อ</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promofree50_deposit">...</h2>
												<span>Free 50 (วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_sum_profree50_total">...</h2>
												<span>Free 50 [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promofree60_deposit">...</h2>
												<span>Free 100 (วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_sum_profree60_total">...</h2>
												<span>Free 100 [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="textpromopmv50_accept">...</h2>
												<span>โปรลูกค้าเก่า [ยอดฝาก] [โบนัส] [จำนวน](วันนี้)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="textpromopmv50_summary">...</h2>
												<span>โปรลูกค้าเก่า [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div> 
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop125_accept">...</h2>
												<span>โปรฯสงกรานต์</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
											     <h2 class="promop125_summary">...</h2>
												 <span>โปรฯสงกรานต์ [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

				
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promop50p">...</h2>
												<span>โปรฯ 50% (P122) - Slot</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
											     <h2 class="text_P122">...</h2>
												 <span>โปรฯ 50% (P122) - Slot [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promop100p">...</h2>
												<span>โปรฯ 100% Off turn (Pro100%) - Slot</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_P100">...</h2>
												<span>โปรฯ 100% Off turn (Pro100%) - Slot [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">

									<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
											    <h2 class="text_deposit_promop20X100p">...</h2>
												<span>โปรฯ ฝาก 18 รับ 50(Bonus32)</span>
											</div>
										</div>
									
									   <div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
											    <h2 class="text_promop20X100p">...</h2>
												<span>โปรฯ ฝาก 18 รับ 50 (Bonus32) [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop101_accept">...</h2>
												<span>โปรฯ 20% (P101)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop101_summary">...</h2>
												<span>โปรฯ 20%  (P101) ทั้งวัน  [รวม] [ถอนจริง] [ส่วนต่าง] </span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop25_accept">...</h2>
												<span>โปรฯ รหัส ProU150</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
											     <h2 class="promop25_summary">...</h2>
												 <span>โปรฯ รหัส ProU150 [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop112_accept">...</h2>
												<span>โปรฯ ประจำเดือน (Pro112)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop112_summary">...</h2>
												<span>โปรฯ ประจำเดือน (Pro112) [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop34_accept">...</h2>
												<span>ฉลองวันหยุดยาว</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop34_summary">...</h2>
												<span>ฉลองวันหยุดยาว [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div> 

										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop37_accept">...</h2>
												<span>ฝากประจำ</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop37_summary">...</h2>
												<span>ฝากประจำ [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row"> 
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promop300p_desposit_withdraw_sum">...</h2>
												<span>โปรฯ 300 รับ 50 (P120) - Casino [ฝาก][ถอน]</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promop2p_desposit_withdraw_sum">...</h2>
												<span>โปรฯ 2% ทั้งวัน (P121) - Casino [ฝาก][ถอน]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop140_accept">...</h2>
												<span>โปรฯ ฝาก 25 รับ 100 (pro_sy1)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop140_summary">...</h2>
												<span>โปรฯ ฝาก 25 รับ 100 (pro_sy1)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
 
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row"> 
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop141_accept">...</h2>
												<span>โปรฯ ส่งท้ายปี (pro_sy141)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop141_summary">...</h2>
												<span>โปรฯ ส่งท้ายปี (pro_sy141)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop182_accept">...</h2>
												<span>โปรฯ ฝากเงิน 20 ขึ้นไป (P182)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop182_summary">...</h2>
												<span>โปรฯ ฝากเงิน 20 ขึ้นไป (P182) [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row"> 
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop147_accept">...</h2>
												<span>8.8 แจกสนั่น (P147)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop147_summary">...</h2>
												<span>8.8 แจกสนั่น (P147)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop144_accept">...</h2>
												<span>โปรฯ 5% โบนัสสูงสุด 8888 (pro_sy4)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop144_summary">...</h2>
												<span>โปรฯ 5% โบนัสสูงสุด 8888 (pro_sy4)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row"> 
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop142_accept">...</h2>
												<span>โปรลงทุนน้อยฯ (pro_sy3)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop142_summary">...</h2>
												<span>โปรลงทุนน้อยฯ (pro_sy3)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>
										<div class="col-lg-2 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="promop152_accept">...</h2>
												<span>กิจกรรม reward (bonus152)</span>
											</div>
										</div>
										<div class="col-lg-4 col-sm-12">
											<div class="card-body text-center">
												<h2 class="promop152_summary">...</h2>
												<span>กิจกรรม reward (bonus152)  [รวม] [ถอนจริง] [ส่วนต่าง]</span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-6 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_HappyTime200">...</h2>
												<span>Happy Time - 200 [12.00-13.00] [00.00-01.00]</span>
											</div>
										</div>
										<div class="col-lg-6 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_HappyTime400">...</h2>
												<span>Happy Time - 400 [12.00-13.00] [00.00-01.00]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-6 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_promoWB100_count">...</h2>
												<span>Welcome back</span>
											</div>
										</div>
										<div class="col-lg-6 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_promo_cc">...</h2>
												<span>ขาประจำ[100][120][150][200]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-6 col-sm-12 border-right-blue-grey border-right-lighten-5 border-right-lighten-3">
											<div class="card-body text-center">
												<h2 class="text_hny50">...</h2>
												<span>Happy New Year</span>
											</div>
										</div> 
										<div class="col-lg-6 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_frequency">...</h2>
												<span>ฝากประจำต่อเนื่อง [200][500][100][2000]</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="col-lg-12 col-sm-12">
											<div class="card-body text-center">
												<h2 class="text_overall">...</h2>
												<span>Deposit vs (All Promotion+AFF+ค่าคอม+คืนยอดเสีย+CardGame)</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		

		<?php echo $pg_footer?>
		<script type="text/javascript">
			var xhr =undefined; 
			$(function(){
				LoadDataReport($('.search_date').val());
				$('.MenuDashbordDay').addClass('active');
				$('.search_date').pickadate({
					selectMonths: true,
					selectYears: true,
					monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
					monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
					weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
					format: 'yyyy-mm-dd',
					formatSubmit: 'yyyy-mm-dd',
				    onClose: function(e) {
				        LoadDataReport($('.search_date').val());
				    }
				});
			});

			function LoadDataReport(inputDatePG){
				Myway_ShowLoader();
				if (xhr!=undefined) {xhr.abort(); }
				xhr =$.ajax({
			        url: '<?php echo base_url("dashbord/json_data")?>',
			        type: 'post',
			        data: {inputDatePG:inputDatePG},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {
			            	var result = sr.Result[0];

			            	$('.text_deposit_today_amount').html(result.deposit_today_amount);
			            	$('.text_withdraw_today_amount').html(result.withdraw_today_amount);

							$('.text_profit_today_02,.icon_profit_pg').removeClass('success');
							$('.text_profit_today_02,.icon_profit_pg').removeClass('danger');
							$('.icon_profit_pg').removeClass('ft-trending-up');
							$('.icon_profit_pg').removeClass('ft-trending-down');
							$('.text_profit_today_02,.icon_profit_pg').addClass(result.profit_color_text);
							$('.icon_profit_pg').addClass(result.profit_icon);
			            	$('.text_profit_today_02').html(result.profit_today_02);
			            	$('.text_remain').html(result.remain);
							$('.text_remain,.ft-briefcase').addClass('info');
			            	$('.text_registered_today').html(result.registered_today);
			            	$('.text_first_deposit_td').html(result.first_deposit_td);
			            	$('.text_member_count').html(result.member_count);
			            	$('.text_deposit_pending_withdraw_pending').html(result.deposit_pending_withdraw_pending);
			            	$('.text_deposit_today_count').html(result.deposit_today_count);
			            	$('.text_withdraw_today_count').html(result.withdraw_today_count);
			            	$('.text_aff_claimed_amount').html(result.aff_claimed_amount);
			            	$('.text_claim_comm_amount').html(result.claim_comm_amount);
			            	$('.text_claim_return_loss_amount').html(result.claim_return_loss_amount);
			            	$('.text_mt_amount').html(result.mt_amount);
			            	$('.text_topup_truewallet').html(result.topup_truewallet);
							$('.text_topup_kbank_amount').html(result.topup_kbank_amount); 
			            	$('.text_deposit_tw').html(result.deposit_tw);
			            	$('.text_add_credit_amount').html(result.add_credit_amount);
			            	$('.text_del_credit_amount').html(result.del_credit_amount);
			            	$('.text_bonus_cardgame').html(result.bonus_cardgame);
			            	$('.text_bonus_luckywheel_amount').html(result.bonus_luckywheel_amount);
			            	$('.text_sms_delayed').html(result.sms_delayed);
			            	$('.text_bank_not_found').html(result.bank_not_found);
			            	$('.text_active_deposit').html(result.active_deposit);
			            	$('.text_promop50p').html(result.promop50p);
			            	$('.text_promop103p').html(result.promop103p);
			            	$('.text_P122').html(result.P122);
			            	$('.text_P103').html(result.P103);
							$('.text_promop100p').html(result.promop100p);
			            	$('.text_P100').html(result.P100);
			            	$('.text_promop10p_sum').html(result.promop10p_sum);
			            	$('.text_HappyTime200').html(result.HappyTime200);
			            	$('.text_HappyTime400').html(result.HappyTime400);
			            	$('.text_promoWB100_count').html(result.promoWB100_count);
			            	$('.text_promo_cc').html(result.promo_cc);
			            	$('.text_overall').html(result.overall);
			            	$('.text_promop2p_desposit_withdraw_sum').html(result.promop2p_desposit_withdraw_sum);
							$('.text_promop300p_desposit_withdraw_sum').html(result.promop300p_desposit_withdraw_sum);
			            	$('.text_frequency').html(result.promo_frequency);
							$('.text_hny50').html(result.hny50);

			            	$('.text_promofree50_deposit').html(result.text_promofree50_deposit);
			            	$('.text_sum_profree50_total').html(result.text_sum_profree50_total);
			            	$('.text_promofree60_deposit').html(result.text_promofree60_deposit);
			            	$('.text_sum_profree60_total').html(result.text_sum_profree60_total);
							$('.text_promop20X100p').html(result.promop20X100p);
							$('.text_deposit_promop20X100p').html(result.deposit_promop20X100p);
							$('.textpromop29_accept').html(result.promop29_accept);
							$('.textpromop29_summary').html(result.promop29_summary);
							$('.textpromop79_accept').html(result.promop79_accept);
							$('.textpromop79_summary').html(result.promop79_summary);
							$('.textpromopmv50_accept').html(result.promopmv50_accept);
							$('.textpromopmv50_summary').html(result.promopmv50_summary);
							$('.promop122_accept').html(result.promop122_accept);
							$('.promop122_summary').html(result.promop122_summary);
							$('.promop125_accept').html(result.promop125_accept);
							$('.promop125_summary').html(result.promop125_summary);
							$('.promop34_accept').html(result.promop34_accept);
							$('.promop34_summary').html(result.promop34_summary);
							$('.promop37_accept').html(result.promop37_accept);
							$('.promop37_summary').html(result.promop37_summary);
							$('.promop25_accept').html(result.promop25_accept);
							$('.promop25_summary').html(result.promop25_summary);
							$('.promop112_accept').html(result.promop112_accept);
							$('.promop112_summary').html(result.promop112_summary);
							$('.text_deposit_vizplay').html(result.deposit_vizplay);
							$('.promop140_accept').html(result.promop140_accept);
							$('.promop140_summary').html(result.promop140_summary);
							$('.promop141_accept').html(result.promop141_accept);
							$('.promop141_summary').html(result.promop141_summary);
							$('.promop101_accept').html(result.promop101_accept);
							$('.promop101_summary').html(result.promop101_summary);
							$('.promop142_accept').html(result.promop142_accept);
							$('.promop142_summary').html(result.promop142_summary);
							$('.promop152_accept').html(result.promop152_accept);
							$('.promop152_summary').html(result.promop152_summary);
							$('.promop147_accept').html(result.promop147_accept);
							$('.promop147_summary').html(result.promop147_summary);
							$('.promop144_accept').html(result.promop144_accept);
							$('.promop144_summary').html(result.promop144_summary);
							$('.promop182_accept').html(result.promop182_accept);
							$('.promop182_summary').html(result.promop182_summary);
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