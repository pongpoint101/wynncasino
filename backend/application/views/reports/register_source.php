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
		.color_red{color: red;}.color_green{color: green;}
		.table th, .table td { padding: 0.5rem 0.5rem;}
		@media screen and (max-width:1270px) {
			.dataTables_wrapper table{
			overflow-x: auto;
			display: block;
		  }
         }
		 .statementCard {
			position: relative;
			padding:.8rem;
			border-radius: 0.5rem;
			margin: 3px;
			-webkit-box-shadow: 0 0 1rem rgb(0 0 0 / 20%);
			box-shadow: 0 0 1rem rgb(0 0 0 / 20%); 
			overflow: hidden;
		}
		.statementCard-status {
			float: right;
			border-radius: 0.5rem;
			padding: 0.5rem 1rem;
			color: #fff;
			font-weight: 700;
		}
		.statementCard._running .statementCard-status, .statementCard._waiting .statementCard-status{ background-color: #ffbd00;}
		.statementCard._lose .statementCard-status{  background-color: #eb545a;}
		.statementCard._won .statementCard-status{background-color: #69ce68;}
		.statementCard._draw .statementCard-status { background-color: #54c4eb;}
		.statementCard-oddInfo {
			font-weight: 700;
			padding-bottom: 0.8rem;
			border-bottom: 1px solid #171d24;
			margin-bottom: 0.8rem;
		} 
		.statementCard-oddInfo-team{
			color: #7c7c7c; 
		}
		.statementCard-oddInfo-odd {
			font-size:1rem;
		}
		.svgIcon {
			width: 1em;
			height: 1em;
			margin: 0 0.3rem;
		}
		.statementCard-oddInfo-team .svgIcon{
			width: 1.2em;
			height: 1.2em;
			margin: 0 0.3rem 0 0;
			-webkit-transform: translateY(0.2rem);
			transform: translateY(0.2rem);
		}
		.statementCard-oddInfo-team .leagueName{margin-bottom: 0rem;}
		.statementCard-oddInfo-odd span{
			color: #2a67c1;
		}
		.statementCard-oddInfo-odd span._negative{
			color: #eb545a;
		}
		.statementCard-oddInfo-match {
			color: #7c7c7c;
			font-weight: 400;
			font-size: 0.8rem;
		}
		.statementCard-oddInfo-match-liveStatus{
			font-size:0.8rem;
		}
		.statementCard-oddInfo-match>span .svgIcon, .statementCard-oddInfo-match>span{
			vertical-align: middle;
		}
		.statementCard-oddInfo-match-liveStatus time {
			font-size: 1rem;
		}
		.statementCard-oddInfo-match-liveStatus span {
			color: #eb545a;
			font-weight: 700;
			font-size: medium;
		}  
		.statementCard-ticketInfo-item {
			margin: 0.3rem 0;
			overflow: hidden;
		}
		.statementCard-ticketInfo-item span{
			float: right;
		} 
		.statementCard-detailInfo {
			padding-top: 1rem;
			border-top: 1px solid #171d24;
			margin-top: 0.2rem;
			font-size: 1rem;
			color: #7c7c7c;
			line-height:.2;
		}
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
							<div class="col-12 p-0 m-0">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title"><?=$card_title?></h4>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
										
													<div class="container-fluid"> 
													<form class="form form-horizontal" id="frm_search" method="GET" ><!-- onSubmit="return false" -->
															<div class="form-body"> 
															   <?php 
															        $t_member_no='';
																	if(isset($_GET['member_no'])){$t_member_no=$_GET['member_no'];}
																	$t_timestart='';//date('Y-m-d H:i', strtotime('-1 day', strtotime(date("Y-m-d H:i:s"))));
																	if(isset($_GET['timestart'])){$t_timestart=$_GET['timestart'];}
																	$t_timeend='';//date('Y-m-d H:i');
																	if(isset($_GET['timeend'])){$t_timeend=$_GET['timeend'];}
																	$t_who_source='all';
																	if(isset($_GET['who_source'])){$t_who_source=$_GET['who_source'];}
															     ?>
																<div class="row">
																	<div class="col-md-2">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto"> 
																				<input type="number" value="<?=$t_member_no?>" name="member_no" id="member_no" class="form-control border-primary" placeholder="รหัสสมาชิกผู้แนะนำ" >
																			</div>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto">  
																			  <select id="who_source" name="who_source" class="form-control">
																				<option value="all" <?=($t_who_source=='all')?'selected':'' ?> >ทั้งหมด</option>
																				<?php foreach ($this->configdata->source_ref as $k => $v) { ?>
																					<option value="<?=$k?>" <?=($t_who_source==$k)?'selected':'' ?>><?=$v?></option>
																				<?php }?> 
																			</select>
																			</div>
																		</div>
																	</div> 
																	<div class="col-md-2">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto"> 
																				<input type="hidden" id="datestart"/>
																				<input type="hidden"  id="starttime"/> 
																				<input type="hidden" id="dateend"/>
																				<input type="hidden"  id="endtime"/> 
																				<div id="outlet"></div> 
																				<input type="text" name="timestart" value="<?=$t_timestart?>" id="timestart" class="search_date form-control" placeholder="จากวันที่" autocomplete="off" >
																				<input type="datetime-local" id="timestart_m" class="search_date form-control w-100 p-5"  style="width: 400px; line-height:33px;"  value="<?=date('Y-m-d H:i', strtotime('-30 minutes', strtotime(date("Y-m-d H:i:s"))))?>" >
																			</div>
																		</div>
																	</div>

																	<div class="col-md-2">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto">
																				<div id="outlet2"></div> 
																				<input type="text" name="timeend" value="<?=$t_timeend?>" id="timeend" class="search_date form-control" placeholder="ถึงวันที่" autocomplete="off" >
																				<input type="datetime-local" id="timeend_m" class="search_date form-control w-100 p-5"  style="width: 400px; line-height:33px;" value="<?=date('Y-m-d H:i')?>" > 
																			</div>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto">
																			<button type="submit" class="btn btn-primary" id="btn_search"><i class="la la-search"></i> ค้นหา</button>  
																			&nbsp;&nbsp;<span style="color:red"> </span>
																			</div>
																		</div>
																	</div>
																	

																</div> 
															</div> 
														</form>
													</div>   
												<table id="edata_table" class="table  table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">ลำดับ</th>		
															<th class="text-center">รหัสสมาชิก</th>	
															<th class="text-center">รหัสผู้แนะนำ</th>
															<th class="text-center">แหล่งที่มา</th>
															<th class="text-center">ฝาก(บาท)</th>
															<th class="text-center">ถอน(บาท)</th> 
															<th class="text-center">กำไร</th>   
															</tr>
														</thead>
														<tbody> 
													    <?php 
														$sum_source_deposit=[];$sum_source_none_deposit=[];
														foreach ($listsource as $k => $v) {
															if(!isset($sum_source_deposit[$k])){$sum_source_deposit[$k]=0;}
															if(!isset($sum_source_none_deposit[$k])){$sum_source_none_deposit[$k]=0;}
														}
														foreach ($list_members as $k => $v) {  
															if(isset($sum_source_deposit[$v['source_ref']])&&$v['deposit']>0){  
																$sum_source_deposit[$v['source_ref']]=$sum_source_deposit[$v['source_ref']]+1;  
															}
															if(isset($sum_source_none_deposit[$v['source_ref']])&&$v['deposit']==0){
																$sum_source_none_deposit[$v['source_ref']]=$sum_source_none_deposit[$v['source_ref']]+1; 
															}   
															?>
															<tr role="row"> 
																<td class="text-center"><?=$k+1?></td>
																<td><a href="<?=base_url("member/profile/{$v['username']}")?>" target="_blank"><?=$v['username']?></a> </td>
																<td><a href="<?=base_url("member/profile/{$v['group_af_username_l1']}")?>" target="_blank"><?=$v['group_af_username_l1']?></a> </td>
																<td><?=htmlentities($v['source_ref'])?></td>
																<td><?=number_format($v['deposit'])?></td>
																<td><?=number_format($v['withdraw'])?></td>
																<td><?=number_format($v['deposit']-$v['withdraw'])?></td>  
															</tr>
														   <?php
														}
														?>		 
														</tbody>
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


													<table id="sumdata_table" class="table table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">ลำดับ</th>		
															<th class="text-center">แหล่งที่มา</th>	
															<th class="text-center">ฝากเงิน(คน)</th>
															<th class="text-center">ไม่ฝากเงิน(คน)</th> 
															</tr>
														</thead>
														<tbody> 
													     <?php $ii=0; foreach ($listsource as $k => $v) {$ii++; ?>
														 <tr role="row"> 
														    <td class="text-center"><?=$ii?></td>
															<td><?=$v?></td>
															<td><span id="total_deposit_<?=$k?>"><?=(isset($sum_source_deposit[$k])? number_format($sum_source_deposit[$k]):0)?></span></td>
															<td><span id="total_withdraw_<?=$k?>"><?=(isset($sum_source_none_deposit[$k])? number_format($sum_source_none_deposit[$k]):0)?></span></td> 
													 	  </tr> 
														<?php } ?>
														</tbody> 
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
		    <div class="modal-dialog modal-lg" role="document">
		        <div class="modal-content" style="border-radius: 1rem !important;">
		            <div class="modal-body mt-2">
					  <div id="show_game_images"></div>
					   <pre id="json_view"></pre>
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
			$('.MenuAffiliateref_register').addClass('active');  
			$('#modal-deposit-reject').on('hidden.bs.modal', function (event) {
	            $('.delete-pg_id').val("");
	        });   
			$('#timestart_m,#timeend_m,#timestart,#timeend').hide();
            if(window.matchMedia("only screen and (max-width: 760px)").matches){
              $('#timestart_m,#timeend_m').show();
			  }else{
				$('#timestart,#timeend').show();
			}
			$(document).on("click","#btn_search",function() {
				ReloadloadData();
			}); 

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
        container: '#outlet',editable: true,
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
    container: '#outlet2',editable: true,
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
				fixedHeader: true,  lengthMenu: [ [10,20, -1], [10,20, "All"]],
				processing: false,
				searching: true, paging: true, info: false,
				ordering: true, 
				autoWidth: false,
				"columns": [
					{ "width": "5%" },
					{ "width": "15%" },
					{ "width": "15%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" } 
				],
				"order": [[ 0, "asc" ]],   
				initComplete: function () {
					 
				  },
				  "rowCallback": function (row, data, index) {  
					    // if ((data[8] !='')) { $(row).addClass(data[8]); } 
				  },
				  footerCallback: function (row, data, start, end, display) {
					 sumfilter_to_footer();
				} 
			});  

		});
        function ReloadloadData(){ $('#frm_search').submit();} 
		function numberWithCommas(x) {return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}
		function sumfilter_to_footer(){   
			var api=$('#edata_table').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 
			var intVal = function (i) {
				if (typeof i=='string') {  i=i.replace( /<.*?>/g, '');i = i.replace(/[^0-9-.]/g, ""); } 
				return typeof i === 'string' ?i.replace(/[\$,]/g, '')*1 :typeof i === 'number' ? i : 0;};  

			//  var betpageTotal = api.column(4,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var bettotal = api.column(4).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			//  var winlose_pageTotal = api.column(5,{ page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b);},0);
			 var winlose_total_deposit = api.column(5).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

			 var profit_total = api.column(6).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 ); 
			//update footer  Number.parseFloat(x).toFixed(2);
			var bettotal_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(bettotal).toFixed(2))} บาท</b>`; 
			var wl=bettotal-winlose_total_deposit;
            var wltext='0.00';
			if(wl<0){
				wltext=`<span style='color: red;'>${numberWithCommas(Number.parseFloat(wl).toFixed(2))}</span>`;
				var profit_total_string = `รวม: <b class='Block' style='color: red;'>${numberWithCommas(Number.parseFloat(profit_total).toFixed(2))} บาท</b>`; 
			}else if(wl>0){
				wltext=`<span style='color: green;'>${numberWithCommas(Number.parseFloat(wl).toFixed(2))}</span>`;
				var profit_total_string = `รวม: <b class='Block' style='color: green;'>${numberWithCommas(Number.parseFloat(profit_total).toFixed(2))} บาท</b>`; 
			}
			var winlose_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(winlose_total_deposit).toFixed(2))} บาท</b>`;//<br>[${wltext}]
            // var totalpage=api.fnGetData().length;

			// api.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
			//   console.log('rowLoop',this.data());
		    // });

			$(api.column(4).footer()).html(bettotal_string); 
			$(api.column(5).footer()).html(winlose_total_string);   
			$(api.column(6).footer()).html(profit_total_string);     
		}  
		function view_fullMsg(pdata){ 
			 
		}
		function getGamehistoryData(indata,callbacl){
			 
		}
		function getdate_player(el,el2){
			    let v=$(el).val();
                if(v!=''){return moment(v).format('YYYY-MM-DD HH:mm'); }
				let vx=$(el2).val();
				if(vx!=''){return moment(vx).format('YYYY-MM-DD HH:mm'); }
				return '';
		   }
		</script>
	</body>
</html>