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
									<li class="breadcrumb-item active">รายงานดึงธนาคาร
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
										<h4 class="card-title">รายงานดึงธนาคาร</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">

											<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">วัน/เวลา</th>
														<th class="text-center">เลขบัญชี</th>
														<th class="text-center">ชื่อบัญชี</th>
														<th class="text-center">รายการทั้งหมด</th>
														<th class="text-center">รายการซ้ำ</th>
														<th class="text-center">รายการใหม่</th>
														<th class="text-center">สถานะ</th>
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
		var datatablePGGmage;
		$(function(){
			$('.MenuBankhistory').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('bank/list_bank_history_json?trx_date='.$PGDateNow)?>",
				"columns": [
				    { "width": "18%" },
				    null,
				    null,
				    null,
				    null,
				    null,
				    null
				],
				"order": [[ 0, "desc" ]],
		        "initComplete": function( settings, json ) {
		        	$('.dataTables_filter').prepend('<div style="display: inline-block;"><div class="form-group mr-1">' +
		        										'<div class="input-group">' +
		        											'<input type="text" value="<?=$PGDateNow?>" class="form-control search_date" placeholder="วันที่" autocomplete="off" />' + 
		        											'<div class="input-group-append">' + 
		        												'<span class="input-group-text">' + 
		        													'<span class="la la-calendar-o"></span>' +
		        												'</div>' +
		        											'</div>' +
		        										'</div>' +
		        									'</div></div>');
		        	$('.search_date').pickadate({
						selectMonths: true,
						selectYears: true,
						monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
						monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
						weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
						format: 'yyyy-mm-dd',
						formatSubmit: 'yyyy-mm-dd',
					    onClose: function(e) {
					        LoadDataDeposite($('.search_date').val());
					    }
					});
					
				}
			});

		});

		function LoadDataDeposite(inputDatePG){
			datatablePGGmage.ajax.url("<?php echo base_url('bank/list_bank_history_json?trx_date=')?>"+inputDatePG).load();
		}
		</script>
	</body>
</html>