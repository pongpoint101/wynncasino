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
						<h3 class="content-header-title mb-0 d-inline-block">Statement</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active"><a href="<?php echo base_url('bank')?>">รายการธนาคาร</a>
									</li>
									<li class="breadcrumb-item active"><?=$account_name?> (<?=$bank_account?>)
									</li>
								</ol>
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
												<h3 class="success"><?=$SumCashIN?></h3>
												<h6>รวมเงินเข้า</h6>
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
												<h3 class="danger"><?=$SumCashOUT?></h3>
												<h6>รวมเงินออก</h6>
											</div>
											<div>
												<i class="icon-rocket danger font-large-2 float-right"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<section id="html">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title success">รายการเดินบัญชี</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>
													<tr>
														<th class="text-center">วัน / เวลา</th>
														<th class="text-center">เข้าระบบ</th>
														<th class="text-center">ช่องทาง</th>
														<th class="text-center">ยอดเงิน</th>
														<th class="text-center">ข้อมูล</th>


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
				$('.MenuBanklist').addClass('active');
				$('.pg-data-table').DataTable( {
			        "ajax": "<?php echo base_url('bank/list_scb_auto_json?ref='.$bank_setting_id)?>",
			        "order": [[ 0, "desc" ]]
			    } );
			});
		</script>
	</body>
</html>