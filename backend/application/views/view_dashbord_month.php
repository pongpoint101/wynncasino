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
					<div class="content-header-left col-md-7 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">DashBord</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="javascript:void(0)">สรุปรายเดือน</a>
									</li>
								</ol>
							</div>
						</div>
					</div>


					<div class="content-header-right col-md-2 col-12 text-right">
						<fieldset class="form-group position-relative">
							<select class="form-control" id="PGselectMonth">
								<?=$select_option_month?>
							</select>
						</fieldset>
					</div>
					
					<div class="content-header-right col-md-2 col-12 text-right">
						<fieldset class="form-group position-relative">
							<select class="form-control" id="PGselectYear">
								<?=$select_option_year?>
							</select>
						</fieldset>
					</div>

				</div>

				<div class="content-body">

					<section id="html">

						<div class="row">
							<div class="col-xl-3">
								<div class="card pull-up">
									<div class="card-content">
										<a href="<?=base_url('deposit')?>">
											<div class="card-body">
												<div class="media d-flex">
													<div class="media-body text-left">
														<h3 class="primary text_deposit_today_amount"><?=number_format($deposit_total_all,2)?></h3>
														<h6>ยอดฝาก</h6>
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
														<h3 class="warning text_withdraw_today_amount"><?=number_format($withdraw_total_all,2)?></h3>
														<h6>ยอดถอน</h6>
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


													<h3 class="text_profit_today_02 <?=($profit_percent<=0)? 'text-danger':'text-success' ?>"><?=number_format($profit_total_all,2)?> (<?=number_format($profit_percent,2)?>%)</h3>
													<h6>กำไร</h6>
												</div>
												<div>
													<i class="ft-trending-up success font-large-2 float-right icon_profit_pg"></i>
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

												<h3 class="info text_remain"><?=number_format($remain,2)?></h3>
												<h6>ยอดเครดิตคงค้าง - ทั้งหมด</h6>
											</div>
											<div>
												<i class="info font-large-2 float-right ft-briefcase"></i>
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
									<div class="card-header">
										<h4 class="card-title">สรุปยอดรายเดือน</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
										  <div class="table-responsive">
											<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">DATE</th>
														<th class="text-right info">DEPOSIT</th>
														<th class="text-right danger">WITHDRAW</th>
														<th class="text-right success">PROFIT</th>
														<th class="text-right success">MARGIN (%)</th>
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
		<?php echo $pg_footer?>
		<script type="text/javascript">
			var datatablePGGmage;
			$(function(){
				$('.MenuDashbordMonth').addClass('active');

				datatablePGGmage = $('.pg-data-table').DataTable( {
					"ajax": "<?php echo base_url('dashbord/json_month_data?pg_year='.$YearNows.'&pg_month='.$MonthNows)?>",
					"ordering": false,
					"searching": false,
					"lengthChange": false,
					"processing": true,
	                "serverSide": true,
					"paging": false
				});

				$('#PGselectMonth').change(function(){
					ReloadLoadDataReport();
				});
				$('#PGselectYear').change(function(){
					ReloadLoadDataReport();
				});
			});
			function ReloadLoadDataReport(){
				Myway_ShowLoader();
				var PGselectMonth = $('#PGselectMonth').val();
				var PGselectYear = $('#PGselectYear').val();
				json_month_all_data(PGselectYear,PGselectMonth);
				datatablePGGmage.ajax.url("<?php echo base_url('dashbord/json_month_data?pg_year=')?>"+PGselectYear+"&pg_month="+PGselectMonth).load(Myway_HideLoader);
			}
			function json_month_all_data(pg_year,pg_month){
				$.ajax({
			        url: '<?php echo base_url("dashbord/json_month_all_data")?>',
			        type: 'post',
			        data: {pg_year:pg_year,pg_month:pg_month},
			        dataType: 'json',
			        success: function (sr) {
			            if (sr.Message == true) {
			            	var result = sr.Result[0];

			            	$('.text_remain').html(result.remain);
			            	$('.text_deposit_today_amount').html(result.deposit_total_all);
			            	$('.text_withdraw_today_amount').html(result.withdraw_total_all);
			            	$('.text_profit_today_02').html(result.profit_total_all);
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