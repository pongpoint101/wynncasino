<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
	<style type="text/css">
		.daterangepicker .input-mini {
			background-color: #ffffff;
		}

		.daterangepicker .calendar th,
		.daterangepicker .calendar td {
			color: #666;
		}
	</style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">ประวัติการแก้ไขสายงาน Aff</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active">ประวัติการแก้ไขสายงาน Aff
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
									<h4 class="card-title">ประวัติการแก้ไขสายงาน Aff</h4>
								</div>
								<div class="card-content collpase show">
									<div class="card-body card-dashboard">
										<div class="content-header-right col-md-6 col-12 text-right mb-3">
											<div class='input-group'>
												<input type='text' class="form-control daterange" placeholder="วว/ดด/ปปปป - วว/ดด/ปปปป" />
												<div class="input-group-append">
													<span class="input-group-text">
														<span class="la la-calendar"></span>
													</span>
												</div>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table table-striped ajax-sourced pg-data-table table-middle">
												<thead>
													<tr>
														<th class="text-center">member</th>
														<th class="text-center">aff1</th>
														<th class="text-center">แก้ไขโดย</th>
														<th class="text-center">วันที่แก้ไข</th>
														<th class="text-center">ประเภท</th>
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


	<?php echo $pg_footer ?>
	<script type="text/javascript">
		$(function() {
			var datatablePGGmage;
			datatablePGGmage = $('.pg-data-table').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "<?php echo base_url('reports/admin_update_aff_team_json') ?>",
				"order": [
					[3, "desc"]
				]
			});
			$('.MenuTotalAdminUpAffTeam').addClass('active');
			$('.daterange').daterangepicker({
				"autoApply": true,
				"locale": {
					"format": "DD/MM/YYYY",
					"separator": " - ",
					"applyLabel": "Apply",
					"cancelLabel": "Clear",
					"fromLabel": "From",
					"toLabel": "To",
					"customRangeLabel": "Custom",
					"weekLabel": "W",
					"daysOfWeek": ["อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."],
					"monthNames": ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม'],
					"firstDay": 1
				},
				"opens": "center"
			}, function(start, end, label) {
				datatablePGGmage.ajax.url("<?php echo base_url('reports/admin_update_aff_json?date_begin=') ?>" + start.format('YYYY-MM-DD') + "&date_end=" + end.format('YYYY-MM-DD')).load();
			});
			$('.daterange').val("");

		});
	</script>
</body>

</html>