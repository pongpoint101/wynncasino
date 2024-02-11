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
						<h3 class="content-header-title mb-0 d-inline-block">แนะนำเพื่อน</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">โบนัส Affiliate
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
										<h4 class="card-title">ประวัติการรับโบนัส</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">

											<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>
													<tr>
														<th>USERNAME</th>
														<th>AMOUNT</th>
														<th>DATE / TIME</th>
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
			$('.MenuAffiliatelist').addClass('active');

			$('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('affiliate/list_log_aff_json')?>",
				"order": [[ 2, "desc" ]]
			} );
		});
		</script>
	</body>
</html>