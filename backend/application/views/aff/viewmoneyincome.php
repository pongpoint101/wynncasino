<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?=$pg_header?>
		<title><?=$pg_title?></title>
		<style>
		.row_admin_deposit1{color: #000000;background-color:#c59308 !important;}
		.row_admin_deposit2{color: #707185;background-color:#380e0e !important;}
		.row_admin_deposit3{color: #cdcdcd;background-color:#0e1738 !important;}
		.row_admin_deposit4{color: #000;background-color:#0a8a31 !important;} 
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
															<div class="form-group">
																<label class="sr-only">รหัส ลูกค้า:</label>
																<input type="text" value="<?=$param_member_no?>" id="member_no" name="member_no" class="form-control" placeholder="รหัส ลูกค้า">
															</div> 
															<div class="form-group mx-sm-1">
																<label  class="sr-only">วันที่</label>
																<input type="hidden" id="datestart"/>
																<input type="hidden"  id="starttime"/> 
																<input type="hidden" id="dateend"/>
																<input type="hidden"  id="endtime"/> 
																<div id="outlet"></div> 
																<input type="text" value="<?=$param_timestart?>" id="timestart" name="timestart" class="search_date form-control" placeholder="จากวันที่">
															</div>
															<div class="form-group mx-sm-1">
																<label  class="sr-only">ถึงวันที่</label>
																<div id="outlet2"></div> 
																<input type="text" value="<?=$param_timeend?>" id="timeend" name="timeend" class="search_date form-control" placeholder="ถึงวันที่">
															</div>
															<button type="submit" class="btn btn-primary" id="btn_search">ค้นหา</button>
													       </form> 
														</div> 
													</div>
                                                     
                                                    <div class="row">
                                                            <div class="col-xl-4 col-12">
                                                                <div class="card">
                                                                    <div class="card-content">
                                                                        <div class="card-body">
                                                                            <div class="media d-flex">
                                                                                <div class="align-self-center">
                                                                                    <i class="icon-user-follow font-large-2 float-left"></i>
                                                                                </div>
                                                                                <div class="media-body text-right">
                                                                                    <h3 class="text_registered_today"><?=$aff_l1_count?></h3>
                                                                                    <span>แนะนำเพื่อน(ลำดับที่ 1)</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 col-12">
                                                                <div class="card">
                                                                    <div class="card-content">
                                                                        <div class="card-body">
                                                                            <div class="media d-flex">
                                                                                <div class="align-self-center">
                                                                                    <i class="icon-user-follow font-large-2 float-left"></i>
                                                                                </div>

                                                                                <div class="media-body text-right">
                                                                                    <h3 class="text_first_deposit_td"><?=$aff_l2_count?></h3>
                                                                                    <span>แนะนำเพื่อน(ลำดับที่ 2)</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 col-12">
                                                                <div class="card">
                                                                    <div class="card-content">
                                                                        <div class="card-body">
                                                                            <div class="media d-flex">
                                                                                <div class="align-self-center">
                                                                                    <i class="ft-upload warning font-large-2 float-left"></i>
                                                                                </div>

                                                                                <div class="media-body text-right">
                                                                                    <h3 class="text_member_count"><?=$credit_aff_total_current?></h3>
                                                                                    <span>รายได้ AFF คงเหลือ</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

												</div>
                                                
                                                <?php if($member_aff_type==2){  ?>
                                                <div class="accout-head mt-2">
                                                    <h1>Income From Member Level 1 (<?=$m_site['row']->aff_rate1. '%'; ?>)</h1>
                                                </div>
												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">วันที่</th>
															<th class="text-center">เงินฝาก</th>
															<th class="text-center">ถอนเงิน</th>
                                                            <th class="text-center">W/L</th>
                                                            <th class="text-center">Bonus/Promotion</th>
                                                            <th class="text-center">Total</th>  
															</tr>
														</thead>
														<tbody> 
                                                          <?php if($income_level1['data']){ ?>
                                                            <?php foreach ($income_level1['data'] as $row) { ?>
                                                                <tr class="text-right">
                                                                    <td class="text-center"><?=$row['date'] ?></td>
                                                                    <td class="text-green"><?=number_format($row['deposit'], 2, '.', ',') ?></td>
                                                                    <td class="text-red"><?=number_format($row['withdraw'], 2, '.', ',') ?></td>
                                                                    <?php
                                                                    if ($row['winloss'] > 0) {
                                                                        $text_green = 'text-green';
                                                                    } else {
                                                                        $text_green = '';
                                                                    }
                                                                    ?>
                                                                    <td <?=$text_green ?>><?=number_format($row['winloss'], 2, '.', ',') ?></td>
                                                                    <td><?=number_format($row['promotion'], 2, '.', ',') ?></td>
                                                                    <td class="text-blue"><?=number_format($row['total'], 2, '.', ',') ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr class="text-right">
                                                                <?php
                                                                if ($income_level1['total']['winloss'] > 0) {
                                                                    $text_green = 'text-green';
                                                                } else {
                                                                    $text_green = '';
                                                                }
                                                                ?>
                                                                
                                                                <td colspan="2" class="text-green"><?=number_format($income_level1['total']['deposit'], 2, '.', ',') ?></td>
                                                                <td class="text-red"><?=number_format($income_level1['total']['withdraw'], 2, '.', ',') ?></td>
                                                                <td class="<?=$text_green ?>"><?=number_format($income_level1['total']['winloss'], 2, '.', ',') ?></td>
                                                                <td><?=number_format($income_level1['total']['promotion'], 2, '.', ',') ?></td>
                                                                <td class="text-blue"><?=number_format($income_level1['total']['total'], 2, '.', ',') ?></td>
                                                            </tr>
                                                            <tr class="text-right">
                                                                <td class="text-center" colspan="5">Rate <?=$m_site['row']->aff_rate1; ?>%</td>
                                                                <td class="text-blue"><?=number_format(($income_level1['total']['total'] * $m_site['row']->aff_rate1) / 100, 2, '.', ',')  ?></td>
                                                            </tr>
                                                        <?php }else{ ?>
                                                            <tr class="text-right">
                                                                <td class="text-center" colspan="6">...ไม่พบข้อมูล...</td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody> 
													</table>

                                                    <div class="accout-head mt-2">
                                                    <h1>Income From Member Level 2 (<?=$m_site['row']->aff_rate2. '%'; ?>)</h1>
                                                </div>
												<table id="edata_table" class="table table-striped table-bordered ajax-sourced table-middle" style="width:100%">
														<thead>
															<tr>
															<th class="text-center">วันที่</th>
															<th class="text-center">เงินฝาก</th>
															<th class="text-center">ถอนเงิน</th>
                                                            <th class="text-center">W/L</th>
                                                            <th class="text-center">Bonus/Promotion</th>
                                                            <th class="text-center">Total</th>  
															</tr>
														</thead>
														<tbody> 
                                                          <?php if($income_level2['data']){ ?>
                                                            <?php foreach ($income_level2['data'] as $row) { ?>
                                                                <tr class="text-right">
                                                                    <td class="text-center"><?=$row['date'] ?></td>
                                                                    <td class="text-green"><?=number_format($row['deposit'], 2, '.', ',') ?></td>
                                                                    <td class="text-red"><?=number_format($row['withdraw'], 2, '.', ',') ?></td>
                                                                    <?php
                                                                    if ($row['winloss'] > 0) {
                                                                        $text_green = 'text-green';
                                                                    } else {
                                                                        $text_green = '';
                                                                    }
                                                                    ?>
                                                                    <td <?=$text_green ?>><?=number_format($row['winloss'], 2, '.', ',') ?></td>
                                                                    <td><?=number_format($row['promotion'], 2, '.', ',') ?></td>
                                                                    <td class="text-blue"><?=number_format($row['total'], 2, '.', ',') ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr class="text-right">
                                                                <?php
                                                                if ($income_level1['total']['winloss'] > 0) {
                                                                    $text_green = 'text-green';
                                                                } else {
                                                                    $text_green = '';
                                                                }
                                                                ?>
                                                                
                                                                <td colspan="2" class="text-green"><?=number_format($income_level1['total']['deposit'], 2, '.', ',') ?></td>
                                                                <td class="text-red"><?=number_format($income_level1['total']['withdraw'], 2, '.', ',') ?></td>
                                                                <td class="<?=$text_green ?>"><?=number_format($income_level1['total']['winloss'], 2, '.', ',') ?></td>
                                                                <td><?=number_format($income_level1['total']['promotion'], 2, '.', ',') ?></td>
                                                                <td class="text-blue"><?=number_format($income_level1['total']['total'], 2, '.', ',') ?></td>
                                                            </tr>
                                                            <tr class="text-right">
                                                                <td class="text-center" colspan="5">Rate <?=$m_site['row']->aff_rate2; ?>%</td>
                                                                <td class="text-blue"><?=number_format(($income_level1['total']['total'] * $m_site['row']->aff_rate2) / 100, 2, '.', ',')  ?></td>
                                                            </tr>
                                                        <?php }else{ ?>
                                                            <tr class="text-right">
                                                                <td class="text-center" colspan="6">...ไม่พบข้อมูล...</td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody> 
													</table>

                                                 <?php } ?>
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
			$('.MenuAffiliateincome').addClass('active');  
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

		});  
		</script>
	</body>
</html>  