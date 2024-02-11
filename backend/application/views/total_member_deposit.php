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
		.row_admin_deposit4{color: #000;background-color:#0a8a31 !important;}
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
									<li class="breadcrumb-item active">สรุปการฝากของลูกค้า
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
										<h4 class="card-title">สรุปการฝากของลูกค้า</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<div class="table-responsive">
											<table class="table table-striped ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">ยูสเซอร์เนม</th>
														<th class="text-center">เบอร์โทรศัพท์</th>
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
			$('.MenuTotalMemberDeposit').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('reports/list_deposit_member_cate_json?trx_date=&trx_date2=&pro=1&pro_time=')?>",
				autoWidth: false,
				dom: 'Bfrtip',
				"pageLength": 50,
				buttons: [
					{
					extend: 'excel',
					text: 'Export excel',
					className: 'exportExcel',
					filename: 'ประวัติการฝากเงินของลูกค้า-<?=$PGDateNow?>',
					exportOptions: {
						columns:[0,1],
						modifier: {
						page: 'all'
						}
					  }
					} 
				],
				"columns": [
				    { "width": "15%" },
					{ "width": "18%" }
				] 
				,
				"order": [[ 0, "desc" ]],
		        "initComplete": function( settings, json ) {
    
		        	$('.dataTables_filter').prepend('<div><div class="mt-1 mb-1" style="display: inline-block;"><select class="form-control h-42"  id="pro_id"></select></div><div class="mt-1 mb-1 ml-1" style="display: inline-block;"><select class="form-control h-42 d-none"  id="pro_time"></select></div> <div class="mt-1 mb-1" style="display: inline-block;"><div class="form-group mr-1 div_search_date">' +
		        										'<div class="input-group">' +
		        											'<input type="text" value="" class="form-control search_date" placeholder="วันที่เริ่มต้น" autocomplete="off" />' + 
		        											'<div class="input-group-append">' + 
		        												'<span class="input-group-text">' + 
		        													'<span class="la la-calendar-o"></span>' +
		        												'</div>' +
		        											'</div>' +
		        										'</div>' +
		        									'</div><div class="mt-1 mb-1" style="display: inline-block;"><div class="form-group mr-1 div_search_date2">' +
		        										'<div class="input-group">' +
		        											'<input type="text" value="" class="form-control search_date2" placeholder="วันที่สิ้นสุด" autocomplete="off" />' + 
		        											'<div class="input-group-append">' + 
		        												'<span class="input-group-text">' + 
		        													'<span class="la la-calendar-o"></span>' +
		        												'</div>' +
		        											'</div>' +
		        										'</div>' +
		        									'</div></div></div>');
					// $('#pro_id').append('<option value="">ประเภทลูกค้า</option>');
					$('#pro_id').append('<option value="1">' + 'ลูกค้าที่เคยฝากเงิน</option>');
					$('#pro_id').append('<option value="2">' + 'ลูกค้าที่ไม่เคยฝากเงิน</option>');
					$('#pro_id').append('<option value="3">' + 'ลูกค้าที่รับฟรีแล้วไม่เคยฝากเงิน</option>');
					$('#pro_id').append('<option value="4">' + 'ลูกค้าทั้งหมด</option>');
					
					// Filter results on select change
					$('#pro_id').on('change', function () {
						if(this.value==2){
							$('#pro_time').removeClass("d-none");
							$('.div_search_date').addClass("d-none");
							$('.div_search_date2').addClass("d-none");
							$('.search_date').val('');
							$('.search_date2').val('');
						}else if(this.value==4){
							$('#pro_time').addClass("d-none");
							$('.div_search_date').addClass("d-none");
							$('.div_search_date2').addClass("d-none");
							$('.search_date').val('');
							$('.search_date2').val('');
						}else{
							$('#pro_time').addClass("d-none");
							$('.div_search_date').removeClass("d-none");
							$('.div_search_date2').removeClass("d-none");
							$('#pro_time').val('');
						}
						LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val(),$('#pro_time').val());
					});
					$('#pro_time').append('<option value="">ไม่เคยฝากเงินเลย</option>');
					$('#pro_time').append('<option value="1">' + 'ไม่ได้ฝากเงินนาน7วัน</option>');
					$('#pro_time').append('<option value="2">' + 'ไม่ได้ฝากเงินนาน15วัน</option>');
					$('#pro_time').append('<option value="3">' + 'ไม่ได้ฝากเงินนาน30วัน</option>');
					$('#pro_time').append('<option value="4">' + 'ไม่ได้ฝากเงินนานมากกว่า30วัน</option>');
					$('#pro_time').on('change', function () {
						LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val(),$('#pro_time').val());
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
					        LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val(),$('#pro_time').val());
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
					        LoadDataDeposite($('.search_date').val(),$('.search_date2').val(),$('#pro_id').val(),$('#pro_time').val());
					    }
					});
					
				},
				"rowCallback": function (row, data, index) {  
					    if ((data[9] !='')) { $(row).addClass(data[9]); } 
				  }
			});

		});

		function LoadDataDeposite(inputDatePG,inputDatePG2,pro,pro_time){
			datatablePGGmage.ajax.url("<?php echo base_url('reports/list_deposit_member_cate_json?trx_date=')?>"+inputDatePG+"&trx_date2="+inputDatePG2+"&pro="+pro+"&pro_time="+pro_time).load();
		}
		
		</script>
	</body>
</html>