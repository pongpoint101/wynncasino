<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style>
		.row_admin_deposit1{color: #000000;background-color:#c59308 !important;}
		.row_admin_deposit2{color: #707185;background-color:#380e0e !important;}
		.row_admin_deposit3{color: #cdcdcd;background-color:#0e1738 !important;}
		.row_admin_deposit4{color: #000;background-color:#0a8a31 !important;}
		tr.filters th,tr.filters td{padding: 4px;}
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">รายงาน</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active"><?=$card_title?>
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
										<h4 class="card-title"><?=$card_title?></h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">

												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">Date - Time</th>
															<th class="text-center">System</th>
															<th class="text-center">Username</th>
															<th class="text-center">Admin</th>
															<th class="text-center">Amount</th>
															<th class="text-center">Status</th>
															<th class="text-center">Remark</th>
															<th class="text-center">เรียกดู</th>
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
															<th class="text-center"></th>
															<th class="text-center"></th>
															<th class="text-center"></th>
															</tr>
														</tfoot>
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
		        	</div>
		            <div class="modal-footer">
		                <input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-primary width-100" data-dismiss="modal" value="ปิด">
		            </div>
		        </div>
		    </div>
		</div>
		<?php echo $pg_footer;  ?>
		<script type="text/javascript">
		var datatablePGGmage;
		$(function(){
			<?php
			if (@$report_type=='1') {
			  ?>
			  $('.MenuDeposit_mannual').addClass('active');
			  <?php
			}else if(@$report_type=='2'){
			    ?>
				$('.MenuDelete_mannual').addClass('active');
			  <?php 
		     }	
		     ?>  
			$('#modal-deposit-reject').on('hidden.bs.modal', function (event) {
	            $('.delete-pg_id').val("");
	        });  

			// Setup - add a text input to each footer cell
			$('#edata_table thead tr').clone(true).addClass('filters').appendTo('#edata_table thead');
		
		     datatablePGGmage = $('#edata_table').DataTable({
				orderCellsTop: true,
				fixedHeader: true,
				"ajax":{
					'url':"<?=base_url('reports/deposit_history_list?trx_date='.$PGDateNow)?>&filter_type_action=<?=@$report_type?>"
					,complete: function (data) { },
				},
				autoWidth: false,
				dom: 'Bfrtip',
				buttons: [
					{
					extend: 'excel',
					text: 'Export excel',
					className: 'exportExcel',
					filename: '<?=$card_title?>-<?=$PGDateNow?>',
					exportOptions: {
						columns:[0,1,2,3,4,5,6],
						modifier: {
						page: 'all'
						}
					  }
					} 
				],
				"columns": [
					{ "width": "14%" },
					{ "width": "14%" },
					{ "width": "5%" },
					{ "width": "5%" },
				    { "width": "5%" },
				    { "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" }
				] ,
				"order": [[ 0, "desc" ]],   
				initComplete: function () {
					var api = this.api();  
					api.columns().eq(0).each(function (colIdx) {
							// Set the header cell to contain the input element 
					       if (colIdx==0||colIdx==1||colIdx==7) {return}
							var cell = $('.filters th').eq(
								$(api.column(colIdx).header()).index()
							);
							var title = $(cell).text();
							$(cell).html('<input type="text" placeholder="' + title + '" style="text-align: center;width:100%;" />'); 
							// On every keypress in this input
							$('input',$('.filters th').eq($(api.column(colIdx).header()).index()))
								.off('keyup change')
								.on('keyup change', function (e) {
									e.stopPropagation(); 
									// Get the search value
									 $(this).attr('title', $(this).val());
									 var regexr = '({search})'; //$(this).parents('th').find('select').val();
		
								 	var cursorPosition = this.selectionStart;
									// Search the column for that value
									api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))'): '',this.value != '',this.value == '').draw();
		
									$(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
								});
						 });
						// -------------------------------------------------------
						$('.dataTables_filter').prepend('<div style="display: inline-block;"><div class="form-group mr-1">' + 
		        										'<div class="input-group">' +
														`<select class="form-control" onchange="ReloadloadData();" id="filter_type_action"  style="width:180px;">
															<option value='all'>&nbsp;  เลือกประเภทประวัติ   &nbsp; </option>
															<option value='1' <?=(@$report_type=='1') ? 'selected' : '';?> >ประวัติการเติมมือ</option>
															<option value='2' <?=(@$report_type=='2') ? 'selected' : '';?>>ประวัติการลบเครดิต</option> 
															</select>&nbsp;&nbsp;
														` +   
														'<input type="text" value="<?=$PGDateNow?>" id="idPGDateNow" class="form-control search_date" placeholder="วันที่" autocomplete="off" />' + 
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
					        ReloadloadData();
					    }
					  }); 
				  },
				  "rowCallback": function (row, data, index) {  
					    if ((data[8] !='')) { $(row).addClass(data[8]); } 
				  },
				  footerCallback: function (row, data, start, end, display) {
					sumfilter_to_footer();
				} 
			}); 

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
        function ReloadloadData(){  
			var inputDatePG=$('#idPGDateNow').val(); 
			var filter_type_action=$('#filter_type_action').val(); 
			datatablePGGmage.ajax.url("<?php echo base_url('reports/deposit_history_list?trx_date=')?>"+inputDatePG+'&filter_type_action='+filter_type_action).load(); 
		} 
		function sumfilter_to_footer(){  
			var api=$('#edata_table').dataTable().api(); 
			var data = api.column(4).data();  
			var pageTotal = 0;
			var total_deposit = 0;
			function numberWithCommas(x) { return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");}
			var intVal = function (i) {
				if (typeof i=='string') {  i=i.replace( /<.*?>/g, '');i = i.replace(/[^0-9.]/g, ""); } 
				return typeof i === 'string' ?i.replace(/[\$,]/g, '')*1 :typeof i === 'number' ? i : 0;}; 
			
			 pageTotal = api.column(4,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 total_deposit = api.column(4,{filter:'applied'}).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );  
			//update footer
			var total_string = `รวม: <b class='Block'>${numberWithCommas(pageTotal)}/(${numberWithCommas(total_deposit)}) บาท</b>`; 
			$(api.column(4).footer()).html(total_string);     
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