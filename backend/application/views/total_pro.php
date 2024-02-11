<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style>
	     table.dataTable td, table.dataTable th{padding: 8px; white-space: nowrap;}
		.row_admin_deposit1{color: #000000;background-color:#cad5ff !important;}
		.row_admin_deposit2{color: #707185;background-color:#380e0e !important;}
		.row_admin_deposit3{color: #252525;background-color:#fbcece !important;}
		.row_admin_deposit4{color: #000;background-color:#bbf0b0 !important;}
		.h-42{height: 42.6px!important;max-width: 241.6px;}
		
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">สรุป</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">สรุปแยกตามโปรโมชั่น
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
										<h4 class="card-title">สรุปแยกตามโปรโมชั่น</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<div class="table-responsive">
											<table class="table table-striped ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">รหัสลูกค้า</th>
														<th class="text-center">ยอดฝาก</th>
														<th class="text-center">จำนวนรายการฝาก</th>
														<th class="text-center">ยอดถอน</th>
														<th class="text-center">จำนวนรายการถอน</th>
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
			$('.MenuTotalPro').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('reports/list_pro_cate_json?trx_date=&trx_date2=&pro=')?>",
				autoWidth: false,
				dom: 'Bfrtip',
				buttons: [
					{
					extend: 'excel',
					text: 'Export excel',
					className: 'exportExcel',
					filename: 'ประวัติการฝากเงิน-<?=$PGDateNow?>',
					exportOptions: {
						columns:[0,1,2,3,4],
						modifier: {
						page: 'all'
						}
					  }
					} 
				],
				"columns": [
				    { "width": "15%" },
					{ "width": "18%" },
					{ "width": "18%" },
					{ "width": "18%" },
					{ "width": "5%" }
				] 
				,
				"order": [[ 0, "desc" ]],
		        "initComplete": function( settings, json ) {
    
		        	$('.dataTables_filter').prepend('<div class="mt-1 mb-1" style="display: inline-block;"><select class="form-control h-42"  id="pro_id"></select></div> <div class="mt-1 mb-1" style="display: inline-block;"><div class="form-group mr-1">' +
		        										'<div class="input-group">' +
		        											'<input type="text" value="" class="form-control search_date" placeholder="วันที่เริ่มต้น" autocomplete="off" />' + 
		        											'<div class="input-group-append">' + 
		        												'<span class="input-group-text">' + 
		        													'<span class="la la-calendar-o"></span>' +
		        												'</div>' +
		        											'</div>' +
		        										'</div>' +
		        									'</div></div><div class="mt-1 mb-1" style="display: inline-block;"><div class="form-group mr-1">' +
		        										'<div class="input-group">' +
		        											'<input type="text" value="" class="form-control search_date2" placeholder="วันที่สิ้นสุด" autocomplete="off" />' + 
		        											'<div class="input-group-append">' + 
		        												'<span class="input-group-text">' + 
		        													'<span class="la la-calendar-o"></span>' +
		        												'</div>' +
		        											'</div>' +
		        										'</div>' +
		        									'</div></div>');

					$.ajax({
						type: "GET",
						url: "<?php echo base_url('reports/get_pro_list')?>",
						dataType:'JSON', 
						success: function(response){
							promotion = response.data;
							$('#pro_id').append('<option value="">โปรโมชั่น</option>');
							for (var key in promotion) {
								var obj = promotion[key];
								
								$('#pro_id').append('<option value="' + obj.pro_id + '">' + obj.pro_name + '</option>');
								
							}
						}
					});
					
					// Filter results on select change
					$('#pro_id').on('change', function () {
						LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val());
					});
		        	$('.search_date').pickadate({
						selectMonths: true,
						selectYears: true,
						monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
						monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
						weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
						format: 'yyyy-mm-dd',
						formatSubmit: 'yyyy-mm-dd',
					    onClose: function(e) {
					        LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val());
					    }
					});
					$('.search_date2').pickadate({
						selectMonths: true,
						selectYears: true,
						monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
						monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
						weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
						format: 'yyyy-mm-dd',
						formatSubmit: 'yyyy-mm-dd',
					    onClose: function(e) {
					        LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val());
					    }
					});
					
				},
				"rowCallback": function (row, data, index) {  
					    if ((data[9] !='')) { $(row).addClass(data[9]); } 
				  }
			});

		});

		function LoadDataDeposite(inputDatePG,inputDatePG2,pro){
			datatablePGGmage.ajax.url("<?php echo base_url('reports/list_pro_cate_json?trx_date=')?>"+inputDatePG+"&trx_date2="+inputDatePG2+"&pro="+pro).load();
		}
		
		</script>
	</body>
</html>