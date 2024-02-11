<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style>
	     table.dataTable td, table.dataTable th{padding: 8px;}
		.row_admin_deposit1{color: #000000;background-color:#c59308 !important;}
		.row_admin_deposit2{color: #707185;background-color:#380e0e !important;}
		.row_admin_deposit3{color: #cdcdcd;background-color:#0e1738 !important;}
		.row_admin_deposit4{color: #000;background-color:#0a8a31 !important;}
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">ฝากเงิน</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active">ประวัติการฝากเงิน
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
										<h4 class="card-title">ประวัติการฝากเงิน</h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">

											<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle">
												<thead>													
													<tr>
														<th class="text-center">Date - Time</th>
														<th class="text-center">System</th>
														<th class="text-center">Username</th>
														<th class="text-center">Type</th>
														<th class="text-center">Balance Before</th>
														<th class="text-center">Amount</th>
														<th class="text-center">Status</th>
														<th class="text-center">Remark</th>
														<th class="text-center">เรียกดู</th>
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
		<div class="modal animated pulse text-left" id="modal-deposit-reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel37" aria-hidden="true">
		    <div class="modal-dialog modal-sm" role="document">
		        <div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
		            <div class="modal-body mt-2 text-center">
		            	<i class="la la-info-circle font-large-5 warning"></i>
		            	<h3 class="text-center mt-3" style="color:#776c95;">ต้องการยกเลิกการฝากรายการนี้ ?</h3>
		        	</div>
		            <div class="modal-footer border-0 text-center justify-content-center">
		                <input type="button" style="border-radius: 0.7rem;" class="btn btn-danger width-100 submit-myway-form-deposit-reject" value="ยืนยัน">
		                <input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-dark width-100" data-dismiss="modal" value="ยกเลิก">
		            </div>
		        </div>
		    </div>
		</div>
		<form onsubmit="return false;" class="myway-deposit-reject" method="post" action>
		    <input type="hidden" class="delete-pg_id" name="pg_ids">
		</form>
		<div class="modal animated pulse text-left" id="modal-view-fullMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3755663" aria-hidden="true">
		    <div class="modal-dialog modal-sm" role="document">
		        <div class="modal-content" style="border-radius: 1rem !important;">
		            <div class="modal-body mt-2">
		            	<span class="block FullMsgDisplay" style="margin-bottom: 5px;"></span>
						<span class="block DatetimeDisplay" style="margin-bottom: 5px;"></span>
						<span class="block serverDatetimeDisplay" style="margin-bottom: 5px;"></span>
		            	<span class="block AmountDisplay" style="margin-bottom: 5px;"></span>
		            	<span class="block logtrx_id" style="margin-bottom: 5px;"></span>
						<img src="https://placehold.jp/3d4070/ffffff/300x600.png?text=No%20Image." id="img_slip"  width="370" height="513" style="display:none;">
		        	</div>
		            <div class="modal-footer">
		                <input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-primary width-100" data-dismiss="modal" value="ปิด">
		            </div>
		        </div>
		    </div>
		</div>
		<?php echo $pg_footer?>
		<script type="text/javascript">
		var datatablePGGmage;
		$(function(){
			$('.MenuDepositMain').addClass('active');
			$('#modal-deposit-reject').on('hidden.bs.modal', function (event) {
	            $('.delete-pg_id').val("");
	        });
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('deposit/list_deposit_json?trx_date='.$PGDateNow)?>",
				autoWidth: false,
				dom: 'Bfrtip',
				buttons: [
					{
					extend: 'excel',
					text: 'Export excel',
					className: 'exportExcel',
					filename: 'ประวัติการฝากเงิน-<?=$PGDateNow?>',
					exportOptions: {
						columns:[0,1,2,3,4,5,6,7],
						modifier: {
						page: 'all'
						}
					  }
					} 
				],
				"columns": [
				    { "width": "15%" },
					{ "width": "18%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
				    { "width": "5%" },
				    { "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" }
				] 
				,
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
					
				},
				"rowCallback": function (row, data, index) {  
					    if ((data[9] !='')) { $(row).addClass(data[9]); } 
				  }
			});

			// setTimeout(() => {
			// 	setInterval(() => {
			// 	  datatablePGGmage.ajax.reload();
			//   }, 5*1000);
			// },10*1000);
			
			$('.submit-myway-form-deposit-reject').click(function(){
				$('.myway-deposit-reject').submit();
			});
			var SubmitFormReject = $('.myway-deposit-reject');
            SubmitFormReject.ajaxForm({
                url: '<?php echo site_url("member/reject_deposit")?>',
                type: 'post',
                dataType: 'json',
                data: {},
                beforeSubmit: function () {Myway_ShowLoader();},
                success: function (result, statusText, xhr, form) {
                    if (result.Message == true) {
						datatablePGGmage.ajax.reload();
						Myway_HideLoader();
						model_success_pggame();
                    } else {
                    	if (result.boolError == 1) {
                    		$('.form-error-text').html(result.ErrorText);
                    	} else {
                    		$('.modal').modal('hide');
                    	}
                        Myway_HideLoader();
                    }
                }
            });

		});

		function LoadDataDeposite(inputDatePG){
			datatablePGGmage.ajax.url("<?php echo base_url('deposit/list_deposit_json?trx_date=')?>"+inputDatePG).load();
		}
		function reject(pg_id){
			$('.delete-pg_id').val(pg_id);
			$('#modal-deposit-reject').modal();
		}

		function view_fullMsg(trx_id,channel){
			Myway_ShowLoader();
			$.ajax({
		        url: '<?php echo base_url("deposit/log_autobank_json")?>',
		        type: 'post',
		        data: {trx_id:trx_id,channel:channel},
		        dataType: 'json',
		        success: function (sr) {
		            if (sr.Message == true) {
		            	var result_log = sr.Result[0];
		            	$('.FullMsgDisplay').html(result_log.full_msg); 
						$('.DatetimeDisplay').html("โอนจริง : "+result_log.trx_datetime);
						$('.serverDatetimeDisplay').html("เข้าระบบ : "+result_log.trx_sevser_datetime);
						$('.AmountDisplay').html("ยอดเงิน : "+result_log.trx_amount);
						$('.logtrx_id').html("รหัสรายการ : "+result_log.logtrx_id);
						$('#img_slip').hide();
						if(result_log.img_slip!=null&&result_log.img_slip!=''){
							$('#img_slip').attr('src',result_log.img_slip);
							$('#img_slip').show();
						} 
						$('#modal-view-fullMsg').modal({
				            show: true
				        });
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