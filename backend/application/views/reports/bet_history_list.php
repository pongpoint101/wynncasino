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
											    <div class="container-fluid">
													<div class="row">
														<div class="col-sm-12 pb-2">
														   <form class="form-inline" onSubmit="return false">
															<div class="form-group">
																<label class="sr-only">User:</label>
																<input type="text" value="" id="iduser" class="form-control" placeholder="User ลูกค้า" autocomplete="off" >
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">สถานะ</label>
																 <select class="form-control" id="gamestatus">
																    <option value=''>สถานะ</option>
																	<option value='7' <?=(@$gamestatus=='7') ? 'selected' : '';?> >คิดเงิน</option>
																	<option value='1' <?=(@$gamestatus=='1') ? 'selected' : '';?> >ยังไม่คิดเงิน</option> 
																 </select>
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">วันที่</label>
																<input type="text" value="<?=$PGDateNow?>" id="idPGDateNow" class="search_date form-control" placeholder="จากวันที่" autocomplete="off" >
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">วันที่</label>
																<input type="text" value="<?=$PGDatenext?>" id="idPGDatenext" class="search_date form-control" placeholder="ถึงวันที่" autocomplete="off" >
															</div>
															<button type="submit" class="btn btn-primary" id="btn_search">ค้นหา</button>
													       </form> 
														</div> 
													</div>
												</div>
										       
												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">สมาชิก</th>
															<th class="text-center">วันที่เดิมพัน</th>
															<th class="text-center">วันที่จบผล</th> 
															<th class="text-center">เงินก่อนเดิมพัน</th>
															<th class="text-center">เงินเดิมพัน</th>
															<th class="text-center">แพ้/ชนะ</th>
															<th class="text-center">สถานะ</th>
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
			  $('.Menubet_history_sportbook').addClass('active');
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

			$(document).on("click","#btn_search",function() {
				ReloadloadData();
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
					        // ReloadloadData();
					    }
			   });
			// Setup - add a text input to each footer cell
			$('#edata_table thead tr').clone(true).addClass('filters').appendTo('#edata_table thead');
		
		     datatablePGGmage = $('#edata_table').DataTable({
				orderCellsTop: true,
				fixedHeader: true,
				processing: true,
				searching: true, paging: true, info: true,
				"ajax":{
					'url':"<?=base_url('reports/bet_history_sbo_list?trx_date='.$PGDateNow)?>&trx_date_next=<?=@$PGDatenext?>&gamestatus=<?=@$gamestatus?>"
					,complete: function (data) { },
				},
				autoWidth: false,
				"columns": [
					{ "width": "14%" },
					{ "width": "14%" },
					{ "width": "5%" }, 
				    { "width": "5%" },
				    { "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" }
				] ,
				"order": [[ 1, "desc" ]],   
				initComplete: function () {
					var api = this.api();  
					api.columns().eq(0).each(function (colIdx) {
							// Set the header cell to contain the input element 
					    //    if (colIdx==1||colIdx==2||colIdx==7) {return}
							var cell = $('.filters th').eq(
								$(api.column(colIdx).header()).index()
							);
							var title = $(cell).text();
							$(cell).html('<input type="text" placeholder="' + title + '" size="12" style="text-align: center" />'); 
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
				  },
				  "rowCallback": function (row, data, index) {  
					    if ((data[8] !='')) { $(row).addClass(data[8]); } 
				  },
				  footerCallback: function (row, data, start, end, display) {
					sumfilter_to_footer();
				} 
			});  

		});
        function ReloadloadData(){  
			var inputDatePG=$('#idPGDateNow').val(); 
			var inputDatePGnext=$('#idPGDatenext').val(); 
			var iduser=$('#iduser').val();
			var gamestatus=$('#gamestatus').val(); 
			datatablePGGmage.ajax.url("<?php echo base_url('reports/bet_history_sbo_list?trx_date=')?>"+inputDatePG+'&trx_date_next='+inputDatePGnext+'&iduser='+iduser+'&gamestatus='+gamestatus).load(); 
			$('.filters input[type="text"],.dataTables_filter input[type="search"]').val('');
			datatablePGGmage.search('').columns().search('').draw();  
		} 
		function sumfilter_to_footer(){  
			var api=$('#edata_table').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0;
			function numberWithCommas(x) { return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");}
			var intVal = function (i) {
				if (typeof i=='string') {  i=i.replace( /<.*?>/g, '');i = i.replace(/[^0-9.]/g, ""); } 
				return typeof i === 'string' ?i.replace(/[\$,]/g, '')*1 :typeof i === 'number' ? i : 0;};  

			 var betpageTotal = api.column(4,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var bettotal = api.column(4).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			 var winlose_pageTotal = api.column(5,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var winlose_total_deposit = api.column(5).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			//update footer  Number.parseFloat(x).toFixed(2);
			var bettotal_string = `รวม: <b class='Block'>${numberWithCommas(betpageTotal)}/(${numberWithCommas(bettotal)}) บาท</b>`; 
			var winlose_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(betpageTotal-winlose_pageTotal).toFixed(2))}/(${numberWithCommas(Number.parseFloat(bettotal-winlose_total_deposit).toFixed(2))}) บาท</b>`;
 
			$(api.column(4).footer()).html(bettotal_string); 
			$(api.column(5).footer()).html(winlose_total_string);      
		}  
		</script>
	</body>
</html>