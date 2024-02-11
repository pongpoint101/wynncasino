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
		 #show_game_images{background-color: aliceblue;padding: 5px;}
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
			background-color: brown;
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
			color: cornflowerblue;
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
			color: blue;
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
			/* line-height:.2; */
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
													<form class="form form-horizontal" onSubmit="return false">
															<div class="form-body"> 
																<div class="row">
																	<div class="col-md-2">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto"> 
																				<input type="number" value="" id="member_no" class="form-control border-primary" placeholder="รหัส ลูกค้า" >
																			</div>
																		</div>
																	</div>
																	<div class="col-md-3">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto"> 
																				<input type="hidden" id="datestart"/>
																				<input type="hidden"  id="starttime"/> 
																				<input type="hidden" id="dateend"/>
																				<input type="hidden"  id="endtime"/> 
																				<div id="outlet"></div> 
																				<input type="text" value="<?=date('Y-m-d H:i', strtotime('-30 minutes', strtotime(date("Y-m-d H:i:s"))))?>" id="timestart" class="search_date form-control" placeholder="จากวันที่" autocomplete="off" >
																				<input type="datetime-local" id="timestart_m" class="search_date form-control w-100 p-5"  style="width: 400px; line-height:33px;"  value="<?=date('Y-m-d H:i', strtotime('-30 minutes', strtotime(date("Y-m-d H:i:s"))))?>" >
																			</div>
																		</div>
																	</div>

																	<div class="col-md-3">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto">
																				<div id="outlet2"></div> 
																				<input type="text" value="<?=date('Y-m-d H:i')?>" id="timeend" class="search_date form-control" placeholder="ถึงวันที่" autocomplete="off" >
																				<input type="datetime-local" id="timeend_m" class="search_date form-control w-100 p-5"  style="width: 400px; line-height:33px;" value="<?=date('Y-m-d H:i')?>" > 
																			</div>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group row"> 
																			<div class="col-md-12 mx-auto">
																			<button type="submit" class="btn btn-primary" id="btn_search"><i class="la la-search"></i> ค้นหา</button>  
																			&nbsp;&nbsp;<span style="color:red">ทำให้ค้นหาช้า ถ้า user ไหนข้อมูลเยอะ กรุณาระบุช่วงเวลา ที่จะค้นหาสั่นลงไม่เกิน 30 นาที </span>
																			</div>
																		</div>
																	</div>
																	

																</div> 
															</div> 
														</form>
													</div> 
										       
												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">รหัส</th>
															<th class="text-center">เดิมพัน</th>
															<th class="text-center">แพ้ชนะ</th>
															<th class="text-center">ก่อนเดิมพัน</th>
															<th class="text-center">หลังเดิมพัน</th>
															<th class="text-center">เวลาทำรายการ</th>  
															<th class="text-center">ค่าย</th> 
															<th class="text-center">เกมส์</th>  
															<th class="text-center">ประเภทรายการ</th> 
															<th class="text-center">รายละเอียด</th>
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
			$('.Menu_reportmemberplay').addClass('active');  
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
				processing: true,
				searching: true, paging: true, info: false,
				ordering: true,
				"ajax": {
						"url":"<?=base_url('reports/reportmemberplaygame_list')?>",
						"type": "GET",
						"data": function(d){
							d.member_no =$('#member_no').val();
							d.timestart =getdate_player('#timestart','#timestart_m');
							d.timeend =getdate_player('#timeend','#timeend_m');
							d.provider_name =$('#provider_name').val();
						}
						,complete: function (data) { },
				 }, 
				autoWidth: false,
				"columns": [
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "15%" },
					{ "width": "15%" },
					{ "width": "15%" },
					{ "width": "5%" } 
				] ,
				"order": [[ 0, "desc" ]],   
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
        function ReloadloadData(){  
			datatablePGGmage.ajax.reload();     
		} 
		function numberWithCommas(x) {return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}
		function sumfilter_to_footer(){   
			var api=$('#edata_table').dataTable().api();   
			var pageTotal = 0;
			var total_deposit = 0; 
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
				wltext=`<span style='color: green;'>${numberWithCommas(Number.parseFloat(wl).toFixed(2))}</span>`;
			}
			var winlose_total_string = `รวม: <b class='Block'>${numberWithCommas(Number.parseFloat(winlose_total_deposit).toFixed(2))}<br>[${wltext}] บาท</b>`;
 
			$(api.column(1).footer()).html(bettotal_string); 
			$(api.column(2).footer()).html(winlose_total_string);      
		}  
		const dateoptions = {
		year: "numeric",
		month: "long",
		day: "numeric",
		hour: "2-digit",
		minute: "2-digit",
		second: "2-digit",
		timeZoneName: "short",
		};
		
		function view_fullMsg(pdata){ 
			let data=JSON.parse(pdata);
			$('#show_game_images,#json_view').empty();
			if(['JILI'].indexOf(data.platformCode)!=-1){ 
				getGamehistoryData(data,function(res_data){  
					if(res_data.image_url!=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					}
				});
			 return; 	
			}
			if(['SPGS','SPGF'].indexOf(data.platformCode)!=-1){ //Spade Gaming
				getGamehistoryData(data,function(res_data){ 
					if(res_data.image_url!=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					}
				});
			 return; 	
			 }
			 if(['JKR','JKS'].indexOf(data.platformCode)!=-1){ //Joker
				getGamehistoryData(data,function(res_data){ 
					if(res_data.image_url!=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			 return; 	
			 }
			 if(['SEXYBCRT'].indexOf(data.platformCode)!=-1){ //AWC
				getGamehistoryData(data,function(res_data){ 
					if(res_data.image_url!=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			  return; 	
			 }
			 if(['SA'].indexOf(data.platformCode)!=-1){ //SA
				getGamehistoryData(data,function(res_data){ 
					if(res_data.error!=0){ console.log('res_data.error',res_data);return;}
					if(typeof res_data.url =='object'){  
						  res_data=res_data.url;
					 	  let betbetail=res_data.BetDetailList.BetDetail; 
                          let betteamlist='';
                          if(betbetail.length>0){
                              betbetail.forEach((v) => {
								betteamlist+=`<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">เลขที่: <span> BetID:${v.BetID} TransactionID:${v.TransactionID}</span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${v.BetAmount} ) </span></div>
									<div class="statementCard-ticketInfo-item">รายละเอียด: <span class="notranslate"> ${v.GameType}(${v.BetType})</span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${v.ResultAmount} </span></div>
								</div> 
								<div class="statementCard-detailInfo">  
									<p>วันที่เดิมพัน: ${v.BetTime}</p>
									<p>วันที่คิดเงิน: ${v.PayoutTime}</p>
								</div>
								`;
							 });
							 $('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard _won"> 
							        ${betteamlist}  
							   </div> 
					         `);
						   }else{
							$('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard._won"> 
								<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">เลขที่: <span> BetID:${betbetail.BetID} TransactionID:${betbetail.TransactionID}</span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${betbetail.BetAmount} ) </span></div>
									<div class="statementCard-ticketInfo-item">รายละเอียด: <span class="notranslate"> ${betbetail.GameType}(${betbetail.BetType})</span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${betbetail.ResultAmount} </span></div>
								</div> 

								<div class="statementCard-detailInfo">  
									<p>วันที่เดิมพัน: ${betbetail.BetTime}</p>
									<p>วันที่คิดเงิน: ${betbetail.PayoutTime}</p>
								</div>

							  </div> 
					       `);
						  }  
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			  return; 	
			 }
			 if (/^QT-/.test(data.platformCode.toUpperCase())) {
				getGamehistoryData(data,function(res_data){ 
					if(res_data.url !=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			  return; 	
			 } 
			 if(['DGL'].indexOf(data.platformCode)!=-1){ //DGL
				getGamehistoryData(data,function(res_data){ 
					if(res_data.error!=0){ console.log('res_data.error',res_data);return;}
					if(typeof res_data.url =='object'){ 
						let txt_player='';
						if(res_data.url.betDetail.hasOwnProperty('player')){
							txt_player=`ผู้เล่น:${res_data.url.betDetail.player} ผู้เล่นชนะ:${res_data.url.betDetail.playerW}`;
						}
						if(res_data.url.betDetail.hasOwnProperty('banker')){
							txt_player=`เจ้ามือ:${res_data.url.betDetail.banker} เจ้ามือชนะ:${res_data.url.betDetail.bankerW}`;
                         }
                          res_data=res_data.url;
						$('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard._won"> 
								<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">เลขที่: <span> ${res_data.id} </span></div>
									<div class="statementCard-ticketInfo-item">เงินก่อนเดิมพัน: <span class="notranslate">(${res_data.balanceBefore} ) </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${res_data.betPoints} ) </span></div>
									<div class="statementCard-ticketInfo-item">รายละเอียด: <span class="notranslate"> ${txt_player}</span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${res_data.winOrLoss} </span></div>
								</div> 

								<div class="statementCard-detailInfo"> 
									<p>วันที่เดิมพัน: ${res_data.betTime}</p>
									<p>วันที่คิดเงิน: ${res_data.calTime}</p>
								</div>

							</div> 
					     `);
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			  return; 	
			 }  
			 if(['SBO'].indexOf(data.platformCode)!=-1){ //SBOBET
				  if(data.action=='credit'){
							$('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard._won"> 
								<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">เลขที่: <span> ${data.TransferCode} </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${data.CommissionStake} ) </span></div>
									<div class="statementCard-ticketInfo-item">สถานะ: <span class="notranslate">จบเกมส์ </span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${data.WinLoss} </span></div>
								</div> 
							</div> 
					     `);
						 $('#modal-view-fullMsg').modal({  show: true });  
				}
				getGamehistoryData(data,function(res_data){  
					if(!res_data.hasOwnProperty('url')){return;}
					if(res_data.url.length>0){ 
						const resdata=res_data.url[0];
						let odds='',betTime='',winlostDate='',pricebet='';
						let markettype='',betoption='';
						let endgame='error',txtstatus='';
						let scorebet='',scoreht=0,scoreft=0;
						let kickofftime='',actualstake=0,betlivetxt='',oddsType=''
						,txId='',winlost=0,betteamlist='',betChoice='',betamount=0
						,leagueName='',match='',betteam='',actualAmount=0;
 
						let betlist= resdata.subBet;
						const odds_all=resdata.odds;  
						      betamount=resdata.stake;  
							  actualAmount=resdata.actualStake; 
							  winlost=resdata.winLost; 
							  let mTime = new Date(resdata.settleTime);
							  winlostDate= mTime.toLocaleDateString("th-TH", dateoptions); 
							  mTime = new Date(resdata.orderTime);
							  betTime= mTime.toLocaleDateString("th-TH", dateoptions);  
							  txId=resdata.refNo; 
							  txtstatus=(resdata.isLive)?'Live':'Non Live';
							  betlivetxt=resdata.status;
						   betlist.forEach((v) => { 
							odds=v.odds;  
							pricebet=v.hdp;
							markettype=v.marketType;
							betoption=v.betOption;
							endgame=v.status;
							scorebet=v.liveScore; 
							const kickOffTime = new Date(v.kickOffTime);
							kickofftime=kickOffTime.toLocaleDateString("th-TH", dateoptions); 
							leagueName=v.league;
							match=v.match; 
							betteam=`${markettype} ${betoption}`; 
							betteamlist+=`
								  <div class="statementCard-oddInfo-team leagueName">${leagueName}</div>
								  <div class="statementCard-oddInfo-team">
										${match}
									</div>
									<div class="statementCard-oddInfo-odd">
										${betteam}
										<!-- <span> • แฮนดีแคพ </span>-->
										<span class="_negative"> ${odds} </span>
										• ${endgame} • 
									</div>
									<div class="statementCard-oddInfo-odd">
									  <span> เดิมพัน:(${scorebet})[ครึ่งแรก:${v.ftScore},เต็มเวลา:${v.htScore}] </span>
									</div>
									<div class="statementCard-oddInfo-odd">
									วันที่แข่งขัน: ${kickofftime}
									</div>
									  <div class="statementCard-oddInfo-match notranslate">
										<span class="statementCard-oddInfo-match-liveStatus">
										${betChoice}
										<!-- <time> 1H 14' </time><span> 0 : 1 </span></span><span>|</span>  -->
						             </div> 
							  `;

						});

						$('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard _${txtstatus}">
								<span class="statementCard-status"> ${endgame}</span>
								<div class="statementCard-oddInfo">
									   ${betteamlist}
								</div>
								<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">อ็อดซ์: <span> ${odds_all} </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${betamount} ) </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพันจริง: <span class="notranslate">${actualAmount} </span></div>
									<div class="statementCard-ticketInfo-item">สถานะ: <span class="notranslate">${betlivetxt} </span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${winlost} </span></div>
								</div>
								<div class="statementCard-detailInfo">
									<p>เงินก่อนเดิมพัน: - </p>
									<p>เงินหลังเดิมเดิมพัน: -</p>
									<p>เลขที่: ${txId}</p>
									<p>วันที่เดิมพัน: ${betTime}</p>
									<p>วันที่คิดเงิน: ${winlostDate}</p>
								</div>
							</div> 
					     `);
						 $('#modal-view-fullMsg').modal({  show: true });  
						
					}
				});
			 return; 	
			 }
			 if(['KA'].indexOf(data.platformCode)!=-1){ //KA
				getGamehistoryData(data,function(res_data){   
					if(res_data.image_url!=''){ 
						$('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					} 
				});
			 return; 	
			 }
			if((['PG','PGSL','PGSLOT'].indexOf(data.platformCode)!=-1)||['PGSLOT'].indexOf(data.productId)!=-1){  
				getGamehistoryData(data,function(res_data){ 
					  if(res_data.image_url!=''){ 
						 $('#show_game_images').empty().prepend(`<iframe frameborder="0" scrolling="yes" width="100%" height="700"
							src="${res_data.image_url}" >   
							</iframe>
							<br><a class="block text-center" target="_blank" href="${res_data.image_url}">เปิดดูรูปใน tab ใหม่</a>
							`);
						 $('#modal-view-fullMsg').modal({  show: true });  
					  }
				});
				return; }
				if(['SABA'].indexOf(data.platformCode)!=-1){  
				getGamehistoryData(data,function(res_data){ 
					  if(res_data.length>0){  
                           let endgame='error',txtstatus='';
						   let betteamlist='';
						   let odds='',betTime='',winlostDate='';
						   let oddsType='error ออดซ์'; 
						   let betamount=0,payout=0,actualAmount=0,txId=0
						       ,balanceBefore=0,balanceAfter=0;
						   res_data.forEach((v) => {
							   let betteam=v.homeName;
							   let leagueName=v.leagueName;
							   let betlivetxt='Non Live';
							   let betChoice=v.betChoice;
							   let matchDateTime='';
							   betamount=v.betAmount;
							   payout=v.payout;
							   actualAmount=v.actualAmount;
							   odds=v.odds;
							   txId=v.txId;
							   balanceBefore=v.balanceBefore;balanceAfter=v.balanceAfter=0; 
							   try {
								let mediumTime = new Intl.DateTimeFormat("th-TH", {timeStyle: "medium",dateStyle: "medium"}); 
								// betTime=mediumTime.format(new Date(v.betTime).valueOf());
								betTime= new Date(v.betTime).toString();
								winlostDate=mediumTime.format(new Date(v.winlostDate).valueOf());  
								matchDateTime=mediumTime.format(new Date(v.matchDateTime).valueOf());  
								if(v.status=='running'){ endgame='กำลังวิ่ง';txtstatus='running';}
								if(v.status=='won'){ endgame='ชนะ';txtstatus='won';}
								if(v.status=='lose'){ endgame='แพ้';txtstatus='lose';}
								if(v.status=='draw'){ endgame='เสมอ';txtstatus='draw';}

								if(v.status=='half won'){ endgame='ได้ครึ่ง';txtstatus='won';}
								if(v.status=='half lose'){ endgame='เสียครึ่ง';txtstatus='lose';}

								if(v.betTeam=='a'){betteam=v.awayName;}
								if(v.isLive==1){betlivetxt=betlivetxt='Live';}
								if(betChoice==v.homeName||betChoice==v.awayName){
									betChoice='';
								}else{
									betChoice='<span>• <i class="icon-arrow-right"></i>'+v.betChoice+'</span>'
								}
								switch (v.oddsType) {
									case 1: oddsType='ออดซ์ MY';break; 
									case 2: oddsType='ออดซ์ CN';break; 
									case 3: oddsType='ออดซ์ DEC';break; 
									case 4: oddsType='ออดซ์ IND';break; 
									case 5: oddsType='ออดซ์ US';break; 
									default:oddsType='error ออดซ์'; break;
								}
							      } catch (error) {
								   console.log('error1',error);
							      }
							      betteamlist+=`
								  <div class="statementCard-oddInfo-team leagueName">${leagueName}</div>
								  <div class="statementCard-oddInfo-team">
										${v.homeName} - ${v.awayName}
									</div>
									<div class="statementCard-oddInfo-odd">
										${betteam}
										<!-- <span> • แฮนดีแคพ </span>-->
										<span class="_negative"> -0.25 </span>
										• ${betlivetxt} • [${v.homeScore}:${v.awayScore}]
									</div>
									<div class="statementCard-oddInfo-odd">
									วันที่แข่งขัน: ${matchDateTime}
									</div>
									  <div class="statementCard-oddInfo-match notranslate">
										<span class="statementCard-oddInfo-match-liveStatus">
										${betChoice}
										<!-- <time> 1H 14' </time><span> 0 : 1 </span></span><span>|</span>  -->
						             </div> 
							  `;
							});
 
						 $('#show_game_images').empty().prepend(` 
							  <div data-betslip="single" class="statementCard _${txtstatus}">
								<span class="statementCard-status"> ${endgame}</span>
								<div class="statementCard-oddInfo">
									   ${betteamlist}
								</div>
								<div class="statementCard-ticketInfo">
									<div class="statementCard-ticketInfo-item">อ็อดซ์: <span> ${odds} ( ${oddsType} ) </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพัน: <span class="notranslate">(${betamount} ) </span></div>
									<div class="statementCard-ticketInfo-item">เงินเดิมพันจริง: <span class="notranslate">${actualAmount} </span></div>
									<div class="statementCard-ticketInfo-item">แพ้/ชนะ: <span class="notranslate">${payout} </span></div>
								</div>
								<div class="statementCard-detailInfo">
									<p>เงินก่อนเดิมพัน: ${balanceBefore}</p>
									<p>เงินหลังเดิมเดิมพัน: ${balanceAfter}</p>
									<p>เลขที่: ${txId}</p>
									<p>วันที่เดิมพัน: ${betTime}</p>
									<p>วันที่คิดเงิน: ${winlostDate}</p>
								</div>
							</div>

					     `);
						 $('#modal-view-fullMsg').modal({  show: true });  
					  }
			  	});
				return; }

			document.getElementById("json_view").textContent = JSON.stringify(JSON.parse(pdata), undefined, 2);
			$('#modal-view-fullMsg').modal({
				         show: true
			     }); 
		}
		function getGamehistoryData(indata,callbacl){
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: '<?=base_url('reports/reportmemberplaygame_list')?>?detail=1',
				data: indata,
				beforeSend: function() { Myway_ShowLoader();},
				success: function(data) {
					Myway_HideLoader();
					callbacl(data);return 
				} 
			});
		}
		function getdate_player(el,el2){
			    let v=$(el).val(); 
				 if($('#timestart_m').is(":visible")){
					v=$(el2).val();
                 }  
				if(v!=''){return moment(v).format('YYYY-MM-DD HH:mm'); } 
				return v;
		   }
		</script>
	</body>
</html>