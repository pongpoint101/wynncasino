<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>

  		<link rel="stylesheet" type="text/css" href="<?php echo base_url('app-assets/vendors/css/tables/datatable/datatables.min.css')?>">
		<style type="text/css">
			.width16per{width: 12%;}
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">ประวัติย้อนหลัง</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active"><a href="<?php echo base_url('member')?>">User</a>
									</li>
									<li class="breadcrumb-item active"><a href="<?php echo base_url('member/profile/'.$UserName)?>"><?=$UserName?></a>
									</li>
									<li class="breadcrumb-item active">History
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
										<h4 class="card-title success">ประวัติฝาก/รับเครดิต</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<table class="table table-striped table-bordered ajax-sourced pg-data-table-deposit table-middle">
												<thead>
													<tr>
														<th class="text-center">วัน / เวลา</th>
														<th class="text-center">ช่องทาง</th>
														<th class="text-center">จำนวนเงิน</th>
														<th class="text-center">อ้างอิง</th>
														<th class="text-center">สถานะ</th>
														<th class="text-center">หมายเหตุ</th>
													</tr>
												</thead>
										    </table>
										</div>
									</div>
								</div>
							</div>
				       </div>
					</section>
					<section id="html_02">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title danger">ประวัติถอนเครดิต</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<table class="table table-striped table-bordered ajax-sourced pg-data-table-withdraw table-middle">
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
				$('.MenuMemberMain').addClass('active');
				$('.pg-data-table-deposit').DataTable( {
			        "ajax": "<?php echo base_url('member/list_log_deposit_backup_json?ref='.$pg_ref)?>",
			        "order": [[ 0, "desc" ]]
			    } );
			    $('.pg-data-table-withdraw').DataTable( {
			        "ajax": "<?php echo base_url('member/list_log_withdraw_backup_json?ref='.$pg_ref)?>",
			        "order": [[ 0, "desc" ]]
			    } );
			});
		</script>
	</body>
</html>