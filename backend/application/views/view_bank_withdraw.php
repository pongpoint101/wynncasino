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
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">ธนาคาร</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">ประวัติบัญชีถอน (150 รายการล่าสุด)
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body">
					<section id="html">

					<div class="row">
						<div class="col-xl-5">
							<div class="card pull-up">
								<div class="card-content"> 
										<div class="card-body">
											<div class="media d-flex">
												  
														<style>
														table.widthdrawtable {
														width: 100%;
														background-color: #ffffff;
														border-collapse: collapse;
														border-width: 2px;
														border-color: #ffcc00;
														border-style: solid;
														color: #000000;
														} 
														table.widthdrawtable td, table.widthdrawtable th {
														border-width: 2px;
														border-color: #ffcc00;
														border-style: solid;
														padding: 3px;
														} 
														</style> 
														<table class="widthdrawtable"> 
														<tbody>
															<tr>
															<td>ชื่อบัญชี:</td>
															<td><?=(isset($bank_withdraw['account_name'])?$bank_withdraw['account_name']:'ไม่พบข้อมูล')?></td> 
															</tr>
															<tr>
															<td>เลขบัญชี:</td>
															<td><?=(isset($bank_withdraw['bank_account'])?$bank_withdraw['bank_account']."({$bank_withdraw['short_name']})":'ไม่พบข้อมูล')?></td> 
															</tr>
															<tr>
															<td>ยอดเงินคงเหลือ:</td>
															<td><span class="text-success"><?=(isset($curl['availableBalance'])?number_format($curl['availableBalance'],2):'ไม่พบข้อมูล')?></span> <span class="text-info">@<?=$current_time?></span></td> 
															</tr>
														</tbody>
														</table> 


											</div>
										</div> 
								</div>
							</div>
						</div>
					</div>
					
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">ประวัติฝาก/รับเครดิต</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">

											<!-- <table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle"> -->
											<table class="table table-striped table-bordered ajax-sourced  table-middle">
												<thead>													
													<tr>
														<th class="text-center">วัน/เวลา</th>
														<th class="text-center">ประเภท</th>
														<th class="text-center">รายละเอียด</th>
														<th class="text-center">จำนวนเงิน</th>
														<th class="text-center">ธนาคาร</th>
													</tr>
												</thead>
												<tbody>	

													<?php 
													if($curl['transactions']>0){
														foreach ($curl['transactions'] as $key => $value) {
													?>												
													<tr>
														<td class="text-center"><?php echo $value['dateTime'] ?></td>
														<?php if($value['type']['description']=="ฝากเงิน" || $value['type']['description']=="รับโอน"){
															$color = 'text-success';
														}else{
															$color = 'text-danger';
														} ?>
														<td class="text-center <?php echo $color ?>"><?php echo $value['type']['description'] ?></td>
														<td class="text-center"><?php echo $value['txRemark'] ?></td>
														<td class="text-center"><?php echo number_format($value['amount'], 2, '.', '')  ?></td>
														<td class="text-center"><?php echo $value['remark']['bank'] ?></td>
													</tr>
													<?php }  ?>
													<?php }else{  ?>
														<tr>
															<td colspan="5" class="text-center">ไม่พบข้อมูล</td>
														</tr>
													<?php }  ?>
												</tbody>
										    </table>

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

			$('.MenuBankWithdraw').addClass('active');

		});

		
		</script>
	</body>
</html>