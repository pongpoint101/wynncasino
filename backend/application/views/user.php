<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
	<style>
		table.dataTable td,
		table.dataTable th {
			padding: 8px;
			white-space: nowrap;
		}

		.row_admin_deposit1 {
			color: #000000;
			background-color: #cad5ff !important;
		}

		.row_admin_deposit2 {
			color: #707185;
			background-color: #380e0e !important;
		}

		.row_admin_deposit3 {
			color: #252525;
			background-color: #fbcece !important;
		}

		.row_admin_deposit4 {
			color: #000;
			background-color: #bbf0b0 !important;
		}

		.h-42 {
			height: 42.6px !important;
			max-width: 241.6px;
		}
	</style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">สรุป</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active">สรุปแยกตามลูกค้า
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
									<h4 class="card-title">สรุปแยกตามลูกค้า</h4>
								</div>
								<div class="card-content collpase show">
									<div class="card-body card-dashboard">
										<div class="table-responsive mb-5">
											<table class="table table-striped pg-data-table-level ajax-sourced table-middle">
												<thead>
													<tr>
														<th class="text-center">ยอดฝาก</th>
														<th class="text-center">จำนวนลูกค้า</th>
														<th class="text-center">ยอดรวม</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($lavel as $i => $item) { ?>
														<tr>
															<td><?php echo $item['level'] ?></td>
															<td><?php echo $item['mem_count'] ?></td>
															<td><?php echo $item['total'] ?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="table-responsive">
											<table class="table table-striped ajax-sourced pg-data-table table-middle">
												<thead>
													<tr>
														<th class="text-center">ลำดับ</th>
														<th class="text-center">รหัสลูกค้า</th>
														<th class="text-center">ชื่อ-นามสกุล</th>
														<th class="text-center">วันที่ฝากครั้งแรก</th>
														<th class="text-center">ยอดที่ฝากครั้งแรก</th>
													</tr>
												</thead>
												<tbody> </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <!-- <th class="text-center"></th> -->
                                                    <!-- <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th> -->
                                            </tfoot>
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
		var datatablePGGmage;
		var datatablePGGmagele;
		$(function() {
			$('.MenuTotalMember').addClass('active');
			datatablePGGmagele = $('.pg-data-table-level').DataTable({
				"ajax": "<?php echo base_url('reports/list_user_json?trx_date=&trx_date2=&user=') ?>",
				autoWidth: false,
				searching: false,
				paginate: false,
				ordering: false,
				info: false,
				// dom: 'Bfrtip',
				// buttons: [{
				// 	extend: 'excel',
				// 	text: 'Export excel',
				// 	className: 'exportExcel',
				// 	filename: 'ประวัติการฝากเงิน-<?= $PGDateNow ?>',
				// 	exportOptions: {
				// 		columns: [0, 1, 2, 3, 4],
				// 		modifier: {
				// 			page: 'all'
				// 		}
				// 	}
				// }],
				"columns": [{
						"width": "20%"
					},
					{
						"width": "18%"
					},
					{
						"width": "18%"
					},
				],
				"order": [
					// [0, "desc"]
				],
				"initComplete": function(settings, json) {

				},
				"rowCallback": function(row, data, index) {
					if ((data[9] != '')) {
						$(row).addClass(data[9]);
					}
				}
			});
			datatablePGGmage = $('.pg-data-table').DataTable({
				"ajax": "<?php echo base_url('reports/list_user2_json?trx_date=&trx_date2=&user=') ?>",
				autoWidth: false,
				// dom: 'Bfrtip',
				// buttons: [{
				// 	extend: 'excel',
				// 	text: 'Export excel',
				// 	className: 'exportExcel',
				// 	filename: 'ประวัติการฝากเงิน-<?= $PGDateNow ?>',
				// 	exportOptions: {
				// 		columns: [0, 1, 2, 3, 4],
				// 		modifier: {
				// 			page: 'all'
				// 		}
				// 	}
				// }],
				"columns": [{
						"width": "5%"
					},
					{
						"width": "18%"
					},
					{
						"width": "18%"
					},
					{
						"width": "18%"
					},
					{
						"width": "18%"
					},
				],
				"order": [
					[0, "asc"]
				],
				"initComplete": function(settings, json) {

					$('.dataTables_filter').prepend('<div class="mt-1 mb-1" style="display: inline-block;"><input type="text" id="user" value="" class="form-control" placeholder="ค้นหาลูกค้า" autocomplete="off" /></div> <div class="mt-1 mb-1" style="display: inline-block;"><div class="form-group mr-1">' +
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

					$('#user').on('keyup', function() {
						LoadDataDeposite($('.search_date').val(), $('.search_date2').val(), $('#user').val());
					});

					$('.search_date').pickadate({
						selectMonths: true,
						selectYears: true,
						monthsFull: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม'],
						monthsShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
						weekdaysShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
						format: 'yyyy-mm-dd',
						formatSubmit: 'yyyy-mm-dd',
						onClose: function(e) {
							LoadDataDeposite($('.search_date').val(), $('.search_date2').val(), $('#user'));
						}
					});
					$('.search_date2').pickadate({
						selectMonths: true,
						selectYears: true,
						monthsFull: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม'],
						monthsShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
						weekdaysShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
						format: 'yyyy-mm-dd',
						formatSubmit: 'yyyy-mm-dd',
						onClose: function(e) {
							LoadDataDeposite($('.search_date').val(), $('.search_date2').val(), $('#user'));
						}
					});

				},
				"rowCallback": function(row, data, index) {
					if ((data[9] != '')) {
						$(row).addClass(data[9]);
					}
				},
				footerCallback: function(row, data, start, end, display) {
                    sumfilter_to_footer();
                }
			});

		});

		function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

		function sumfilter_to_footer() {
            var api = $('.pg-data-table').dataTable().api();
            var pageTotal = 0;
            var total_deposit = 0;
            var intVal = function(i) {
                if (typeof i == 'string') {
                    i = i.replace(/<.*?>/g, '');
                    i = i.replace(/[^0-9.]/g, "");
                }
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            var betpageTotal = api.column(4, {
                page: 'current'
            }).data().reduce(function(a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            var bettotal = api.column(4).data().reduce(function(a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            // var winlose_pageTotal = api.column(2, {
            //     page: 'current'
            // }).data().reduce(function(a, b) {
            //     return intVal(a) + intVal(b);
            // }, 0);
            // var winlose_total_deposit = api.column(2).data().reduce(function(a, b) {
            //     return intVal(a) + intVal(b);
            // }, 0);

            //update footer  Number.parseFloat(x).toFixed(2);
            var deposit_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(bettotal).toFixed(2))} บาท</b>`;
            // var winlose_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(winlose_total_deposit).toFixed(2))} บาท</b>`;

            $(api.column(4).footer()).html(deposit_total_string);
            // $(api.column(2).footer()).html(winlose_total_string);
        }

		function LoadDataDeposite(inputDatePG, inputDatePG2, user) {
			datatablePGGmagele.ajax.url("<?php echo base_url('reports/list_user_json?trx_date=') ?>" + inputDatePG + "&trx_date2=" + inputDatePG2).load();
			datatablePGGmage.ajax.url("<?php echo base_url('reports/list_user2_json?trx_date=') ?>" + inputDatePG + "&trx_date2=" + inputDatePG2 + "&user=" + user).load();
		}
	</script>
</body>

</html>