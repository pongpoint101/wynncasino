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
		#datestart, #starttime,#dateend,#endtime { display: none; }
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
																<label class="sr-only">รหัส ลูกค้า:</label>
																<input type="text" value="" id="member_no" class="form-control" placeholder="รหัส ลูกค้า" autocomplete="off" >
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">ค่ายเกมส์</label>
																 <select class="form-control" id="provider_name"> 
																    <option value='all'>ทั้งหมด</option> 
																	<option value='ambpg'>ambpg</option> 
																	<option value='jili'>jili</option>
																	<option value='joker'>joker</option>
																 </select>
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">วันที่</label>
																<input type="hidden" id="datestart"/>
																<input type="hidden"  id="starttime"/> 
																<input type="hidden" id="dateend"/>
																<input type="hidden"  id="endtime"/> 
																<div id="outlet"></div> 
																<input type="text" value="" id="timestart" class="search_date form-control" placeholder="จากวันที่" autocomplete="off" >
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">วันที่</label>
																<div id="outlet2"></div> 
																<input type="text" value="" id="timeend" class="search_date form-control" placeholder="ถึงวันที่" autocomplete="off" >
															</div>
															<button type="submit" class="btn btn-primary" id="btn_search">ค้นหา</button>
													       </form> 
														</div> 
													</div>
												</div>
										       
												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">ค่าย</th>
															<th class="text-center">เดิมพัน</th>
															<th class="text-center">แพ้ชนะ</th>  
															</tr>
														</thead>
														<tbody> </tbody>
														<tfoot>
															<tr>
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
			$('.Menu_reportwinlosemember').addClass('active');  
			$('#modal-deposit-reject').on('hidden.bs.modal', function (event) {
	            $('.delete-pg_id').val("");
	        });  

			$(document).on("click","#btn_search",function() {
				ReloadloadData();
			}); 
			// $('.search_date').pickadate({
			// 			selectMonths: true,
			// 			selectYears: true,
			// 			monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
			// 			monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
			// 			weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
			// 			format: 'yyyy-mm-dd',
			// 			formatSubmit: 'yyyy-mm-dd',
			// 		    onClose: function(e) { 
			// 		        // ReloadloadData();
			// 		    }
			//    });

var datepicker = $('#datestart').pickadate({
        container: '#outlet',
		monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
		monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
		weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        onSet: function(item) {
            if ( 'select' in item ) setTimeout( timepicker.open, 0 );
        }
    }).pickadate('picker');

var timepicker = $('#starttime').pickatime({
        container: '#outlet',
        interval: 1,
				format: 'HH:i',
		    formatSubmit: 'HH:i',
        onRender: function() {
            $('<button>back to date</button>').
                on('click', function() {
                    timepicker.close();
                    datepicker.open();
                }).prependTo( this.$root.find('.picker__box') );
        },
        onSet: function(item) {
            if ( 'select' in item ) setTimeout( function() {
                $datetime.
                    off('focus').
                    val( datepicker.get() + ' ' + timepicker.get() ).
                    focus().
                    on('focus', datepicker.open);
            }, 0 )
        }
    }).pickatime('picker');

var $datetime = $('#timestart').
    on('focus', datepicker.open).
    on('click', function(event) { event.stopPropagation(); datepicker.open() });
    
    //--------
    var datepicker2 = $('#dateend').pickadate({
    container: '#outlet2',
	monthsFull: [ 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม' ],
	monthsShort: [ 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' ],
	weekdaysShort: [ 'อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.' ],
    format: 'yyyy-mm-dd',
    formatSubmit: 'yyyy-mm-dd',
    onSet: function(item) {
        if ( 'select' in item ) setTimeout( endtimepicker.open, 0 );
    }
}).pickadate('picker');

var endtimepicker = $('#endtime').pickatime({
    container: '#outlet2',
    interval: 1,
    format: 'HH:i',
    formatSubmit: 'HH:i',
    onRender: function() {
        $('<button>back to date</button>').
            on('click', function() {
                endtimepicker.close()
                datepicker2.open()
            }).prependTo( this.$root.find('.picker__box') )
    },
    onSet: function(item) {
        if ( 'select' in item ) setTimeout( function() {
            $timeend.off('focus').val( datepicker2.get() + ' ' + endtimepicker.get() ).
                focus().on('focus', datepicker2.open);
        },0);
    }
}).pickatime('picker');

var $timeend = $('#timeend').
on('focus', datepicker2.open).
on('click', function(event) { event.stopPropagation(); datepicker2.open() });


			// Setup - add a text input to each footer cell 
		
		     datatablePGGmage = $('#edata_table').DataTable({
				orderCellsTop: true,
				fixedHeader: true,
				processing: true,
				searching: false, paging: false, info: false,
				ordering: false,
				"ajax": {
						"url":"<?=base_url('reports/reportwinlosemember_list')?>",
						"type": "GET",
						"data": function(d){
							d.member_no =$('#member_no').val();
							d.timestart =$('#timestart').val();
							d.timeend =$('#timeend').val();
							d.provider_name =$('#provider_name').val();
						}
						,complete: function (data) { },
				 }, 
				autoWidth: false,
				"columns": [
					{ "width": "14%" },
					{ "width": "14%" },
					{ "width": "5%" } 
				] ,
				"order": [[ 1, "desc" ]],   
				initComplete: function () {
					 
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
			datatablePGGmage.ajax.reload();     
		} 
		function sumfilter_to_footer(){  
			var api=$('#edata_table').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0;
			function numberWithCommas(x) { return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");}
			var intVal = function (i) {
				if (typeof i=='string') {  i=i.replace( /<.*?>/g, '');i = i.replace(/[^0-9.]/g, ""); } 
				return typeof i === 'string' ?i.replace(/[\$,]/g, '')*1 :typeof i === 'number' ? i : 0;};  

			 var betpageTotal = api.column(1,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var bettotal = api.column(1).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			 var winlose_pageTotal = api.column(2,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var winlose_total_deposit = api.column(2).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			//update footer  Number.parseFloat(x).toFixed(2);
			var bettotal_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(bettotal).toFixed(2))} บาท</b>`; 
			var wl=winlose_total_deposit-bettotal;
            var wltext='0.00';
			if(wl<0){
				wltext=`<span style='color: red;'>${numberWithCommas(Number.parseFloat(wl).toFixed(2))}</span>`;
			}else if(wl>0){
				wltext=`<span style='color: blue;'>${numberWithCommas(Number.parseFloat(wl).toFixed(2))}</span>`;
			}
			var winlose_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(winlose_total_deposit).toFixed(2))} (${wltext}) บาท</b>`;
 
			$(api.column(1).footer()).html(bettotal_string); 
			$(api.column(2).footer()).html(winlose_total_string);      
		}  
		</script>
	</body>
</html>