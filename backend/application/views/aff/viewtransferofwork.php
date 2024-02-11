<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?=$pg_header?>
		<title><?=$pg_title?></title>
		<style>
		.row_admin_deposit1{color: #000000;background-color:#cad5ff !important;}
		.row_admin_deposit2{color: #707185;background-color:#380e0e !important;}
		.row_admin_deposit3{color: #252525;background-color:#fbcece !important;}
		.row_admin_deposit4{color: #000;background-color:#bbf0b0 !important;} 
		#datestart, #starttime,#dateend,#endtime { display: none; }
        .card .card { border: 1px solid; }
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?=$pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">รายงาน</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?=base_url()?>">Home</a>
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
														   <form class="form-inline" method="GET">
                                                            <input type="hidden" name="search_action" value="search">
															<div class="form-group">
																<label class="sr-only">รหัส สายงานต้นทาง:</label>
																<input type="text" value="<?=$memberoriginno?>" id="memberoriginno" required name="memberoriginno" class="form-control" placeholder="รหัส สายงานต้นทาง">
															</div>   
                                                            &nbsp;&nbsp;<button type="submit" class="btn btn-primary" id="btn_search">ค้นหา</button>
													       </form> 
														</div> 
													</div>
                                                      
												</div>
                                                
                                                <?php //var_dump($listmember_aff_l1); exit();
                                                ?>
												<form id="frm_l1_affwork"  method="post">
                                                <div class="accout-head mt-2">
                                                    <h5>Member Level 1</h5>
                                                </div>
												<table id="edata_table" class="table table-striped  ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
                                                            <th class="text-center"><input type="checkbox" id="check_aff1_all" style="cursor:pointer;" />&nbsp;เลือกทั้งหมด</th>
															<th class="text-center">ชื่อ-นามสกุล</th>
															<th class="text-center">USERNAME</th> 
															<th class="text-center">วันที่สมัคร</th>   
															</tr>
														</thead>
														<tbody> 
                                                          <?php if($aff_l1_count>0){   ?>
                                                            <?php foreach ($listmember_aff_l1 as $row) { ?>
                                                                <tr class="text-right">
                                                                    <td class="text-center"><input type="checkbox" name="check_aff1_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                                                                    <td class="text-center"><?=$row['fname'] ?> <?=$row['lname'] ?></td>
                                                                    <td class="text-center"><?=$row['username'] ?></td>
                                                                    <td class="text-center"><?=$row['create_at'] ?></td>   
                                                                </tr>
                                                            <?php } ?> 
                                                        <?php }else{ ?>
                                                            <tr class="text-right">
                                                                <td class="text-center" colspan="4">...ไม่พบข้อมูล...</td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody> 
													</table>
													</form>
													<form id="frm_l2_affwork" method="post">
                                                    <?php if($aff_l2_count>0){ ?> 
                                                   <div class="accout-head mt-2">
                                                     <h5>Member Level 2</h5>
                                                   </div>
											 	 <table id="edata_table" class="table table-striped  ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
                                                             <th class="text-center"><input type="checkbox" id="check_aff2_all" style="cursor:pointer;" />&nbsp;เลือกทั้งหมด</th>
                                                             <th class="text-center">ชื่อ-นามสกุล</th>
															 <th class="text-center">USERNAME</th> 
															 <th class="text-center">วันที่สมัคร</th>     
															</tr>
														</thead>
														<tbody>  
                                                            <?php foreach ($listmember_aff_l2 as $row) { ?>
                                                                <tr class="text-right">
                                                                    <td class="text-center"><input type="checkbox" name="check_aff2_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                                                                    <td class="text-center"><?=$row['fname'] ?> <?=$row['lname'] ?></td>
                                                                    <td class="text-center"><?=$row['username'] ?></td>
                                                                    <td class="text-center"><?=$row['create_at'] ?></td>  
                                                                </tr>
                                                            <?php } ?> 
                                                      
                                                        </tbody> 
													</table> 
                                                 <?php } ?>
											  </form>

                                                 <div class="row">
														<div class="col-sm-12 pb-2">
														   <form class="form-inline pull-right" method="GET" id="frm_fransfer">
                                                            <input type="hidden" name="action" value="transfer">  
															<div class="form-group boxmoveaff">
																<label class="sr-only">รหัส สายงานต้นทาง:</label>
																<input type="text" value="<?=$memberoriginno?>" id="member_origin_no" required name="member_origin_no" class="form-control" placeholder="รหัส สายงานต้นทาง" readonly>
															</div>  
                                                            <div class="form-group boxmoveaff">
                                                              &nbsp;&nbsp;<i class="icon-arrow-right font-large-2 float-left"></i>
															</div>  
                                                            <div class="form-group mx-sm-1 boxmoveaff">
																<label class="sr-only">รหัส สายงานปลายทาง:</label>
																<input type="text" value="<?=$member_destinationno?>" id="member_destination_no" name="member_destination_no" class="form-control" placeholder="รหัส สายงานปลายทาง">
															</div>  

															<div class="form-group">
																<label for="transferworkSelect">&nbsp;เลือกวิธีจัดการสายงาน&nbsp;</label>
																<select class="form-control" id="transferworkSelect">
																<option value="1">ย้ายสายงาน</option>
																<option value="2">ลบสายงาน</option> 
																</select>
													   	    </div>&nbsp; 
															 <input type="checkbox" style="cursor:pointer;" id="check_affgroupall" value="เลือกทุก Level" />&nbsp;เลือกทุกLevel&nbsp; 
															<button type="submit" class="btn btn-primary btn_save">บันทึกข้อมูล</button> 
													       </form> 
														   
														   <!-- <form class="form-inline pull-right" method="GET" id="frm_delete" style="display: none;">
                                                            <input type="hidden" name="action" value="delete">
															<button type="submit" class="btn btn-primary btn_save">บันทึกข้อมูล</button>
													       </form> -->

														</div> 
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

		<?=$pg_footer;  ?>
		<script type="text/javascript">
		var datatablePGGmage;
		$(function(){
			$('.MenuAffiliateref_transferofwork').addClass('active'); 
			$("#check_affgroupall").click(function() {
				$("#check_aff1_all,#check_aff2_all").trigger("click"); 
			 });
			  $("#frm_fransfer").submit(function(e) {  
				e.preventDefault();
				var action=$(this).find("input[name='action']").val();
				var frm_l1_affwork = $('#frm_l1_affwork').serializeArray().reduce(function(obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});
				var frm_l2_affwork = $('#frm_l2_affwork').serializeArray().reduce(function(obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});  
				if(Object.keys(frm_l1_affwork).length<=0&&Object.keys(frm_l2_affwork).length<=0){alert('ไม่พบข้อมูลกรุณาเลือกข้อมูล Member Level 1 หรือ Member Level 2 ก่อนคะ'); return} 
				 var member_originno=''+$('#member_origin_no').val();
				
				 var memberoriginno=''+$('#memberoriginno').val();
				 var member_destinationno=''+$('#member_destination_no').val();
				 if(action=='transfer'){
					if(member_originno.length<5){alert('กรุณาระบุ รหัส สายงานต้นทาง'); $('#member_origin_no').focus(); return} 
					if(member_destinationno.length<5){alert('กรุณาระบุ รหัส สายงานปลายทาง'); $('#member_destination_no').focus();return}   
				 }else if(action=='delete'){

				 } 
				 swal({
					title: "ยืนยันการดำเนินการ ?",
					text: "กรุณาตรวจสอบข้อมูลให้ถูกต้องเพื่อดำเนินการต่อ",
					icon: "warning",
					buttons: true,
					buttons: ["ยกเลิก", "ตกลง"],
					dangerMode: true,
					})
					.then((willDelete) => {
					if (willDelete) { 
						let senddata={'action':action,'memberoriginno':memberoriginno,'member_destination_no':member_destinationno,'frm_l1_affwork':frm_l1_affwork,'frm_l2_affwork':frm_l2_affwork};
						$.ajax({
							type:'post',
							url:'<?=site_url("affiliate/transferofwork") ?>',
							data:senddata,
							beforeSend:function(){
								Myway_ShowLoader();
							},
							success:function(result){ 
								console.log(result);
								if(result.status_msg=='DUPLICATE'){
									swal(`เนื่องจากรหัส สายงานปลายทาง ${result.data.username} เป็นสมาชิกมาสายเดียวกันก่อนย้ายไม่ได้`); 
									Myway_HideLoader();
									return false;
								} 
								if(result.status_msg=='DUPLICATE2'){
									swal(`บันทึกข้อมูลไม่สำเร็จ เนื่องจากรหัส สายงานปลายทางและต้นทางเป็นคนเดียวกัน`); 
									Myway_HideLoader();
									return false;
								}
								if(result.status_msg=='FAIL'){
									swal(`บันทึกข้อมูลไม่สำเร็จ`); 
									Myway_HideLoader();
									return false;
								} 
								if(result.status_msg=='OK'){ 
									Myway_HideLoader();
									model_success_pggame();  
								} 
							   setTimeout(() => {window.location.reload();},1000); 
							}
						});
			          } 
					});

			  });
			  
			  var transferworkSelect=$("#transferworkSelect option").filter(":selected").val()*1;
			   switch (transferworkSelect) {
					case 1:$('.boxmoveaff').show();$("input[name='action']").val('transfer'); break; 
					case 2:$('.boxmoveaff').hide();$("input[name='action']").val('delete'); break;  
			   }
			  $('#transferworkSelect').on('change', function() {
			     $('.boxmoveaff').hide();
				 switch (this.value*1) {
					case 1:$('.boxmoveaff').show();$("input[name='action']").val('transfer'); break; 
					case 2:$('.boxmoveaff').hide();$("input[name='action']").val('delete'); break;  
				 }
			 });  

			$('#modal-deposit-reject').on('hidden.bs.modal', function (event) {
	            $('.delete-pg_id').val("");
	        });  
            $("#check_aff1_all").click(function(){
                $('input[name^="check_aff1_"]:checkbox').not(this).prop('checked', this.checked);
            });
            $("#check_aff2_all").click(function(){
                $('input[name^="check_aff2_"]:checkbox').not(this).prop('checked', this.checked);
            }); 
		});  
		</script>
	</body>
</html>  