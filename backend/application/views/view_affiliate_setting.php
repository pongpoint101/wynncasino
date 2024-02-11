<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $pg_header ?>
	<title><?php echo $pg_title ?></title>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
	<?php echo $pg_menu ?>

	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
					<h3 class="content-header-title mb-0 d-inline-block">ตั้งค่า</h3>
					<div class="row breadcrumbs-top d-inline-block">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item active">Affiliate
								</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

			<div class="content-body">
				<section>
					<div class="row match-height">

						<div class="col-md-6">
							<div class="card">

								<div class="card-content collapse show">
									<div class="card-body">

										<form onsubmit="return false;" class="myway-form-setting-aff" id="myway-form-setting-aff" autocomplete="off" method="post" action>
											<div class="form-body">
												<div class="row">
													<div class="col-md-12">
														<h4 class="form-section"><i class="ft-cpu"></i>  ตั้งค่า Affiliate</h4>
													</div>
													
													<div class="col-md-12"> 
															<div class="form-group">
                                                                <label for="aff_type">รูปแบบค่า Affiliate</label>
                                                                    <select id="aff_type" name="aff_type" class="form-control aff_type"> 
                                                                        <option value="1"  <?=($aff_type==1 ?'selected':'')?> >แบบรวมทุกประเภทกีฬา</option> 
                                                                        <option value="4"  <?=($aff_type==4 ?'selected':'')?> >แบบฟิกให้เรท แยกทุกประเภทกีฬา</option> 
                                                                    </select>
                                                              </div> 
													</div>


													<div class="col-md-12 boxcommtype boxcomm_type1" style="<?=($aff_type==1?'':'display:none')?>"> 
															<div class="form-group">
                                                                <label for="aff_type">แบบขั้นบันได รวมทุกประเภทกีฬา</label>
                                                                       <style> .tbmpro th,.tbmpro td{padding: .2em !important;} </style>
                                                                        <div class="box_pro_deposit_type pro_deposit_type_range">
                                                                         
																		<div class="row aff-div">
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="textbox2">Affiliate Commission Level 1</label>
															<input type="text" value="<?= $aff_comm_level_1 ?>" name="aff_comm_level_1" class="form-control aff_comm_level_1" id="aff_comm_level_1">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="textbox2">Affiliate Commission Level 2</label>
															<input type="text" value="<?= $aff_comm_level_2 ?>" name="aff_comm_level_2" class="form-control aff_comm_level_2" id="aff_comm_level_2">
														</fieldset>
													</div>


													<!-- <div class="row aff-div"> -->
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="textbox2">Affiliate Win/Loss Level 1</label>
															<input type="text" value="0.00" name="aff_rate1" class="form-control aff_rate1" id="aff_rate1">
														</fieldset>
													</div>
													<div class="col-md-6">
														<fieldset class="form-group form-group-style">
															<label for="textbox2">Affiliate Win/Loss Level 2</label>
															<input type="text" value="0.00" name="aff_rate2" class="form-control aff_rate2" id="aff_rate2">
														</fieldset>
													</div>
												<!-- </div> -->
												
												</div> 

                                                                    </div> 
                                                              </div> 
												  </div>

												  <div class="col-md-12 boxcommtype boxcomm_type4"  style="<?=($aff_type==4?'':'display:none')?>">
															<div class="form-group">
                                                                <label for="com_type">แบบฟิกให้เรท แยกทุกประเภทกีฬา</label> 
                                                                        <div class="table-responsive box_pro_deposit_type pro_deposit_type_range">
                                                                        <table class="table table-xs table-bordered mb-0 tbmpro">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="padding:.5em;">ลำดับที่</th>  
                                                                                    <th>ประเภทกีฬา</th>
                                                                                    <th>ค่า Affiliate Level 1</th> 
																					<th>ค่า Affiliate Level 2</th> 
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php 
                                                                                $i=0;//var_dump($comm_type_all['result_array']);exit();
                                                                                foreach ($comm_type_all['result_array'] as $k => $v) {//var_dump($v);exit();
                                                                                    $i++
                                                                                    ?>
                                                                                   <tr>
                                                                                    <th scope="row"><?=$i?></th>
                                                                                    <td><?=$v['game_name_type']?></td> 
                                                                                    <td><input type="hidden" name="game_type_fix_<?=$v['game_type']?>"><input type="hidden" name="id_type_fix_<?=$v['id']?>">
                                                                                        <input type="number" class="form-control" placeholder="aff ประเภทกีฬา <?=$v['game_name_type']?> Level 1" value="<?=$v['fix_bygame1']?>" name="fix_bygame1_<?=$v['id']?>" /></td>
																					<td> 
																					<input type="number" class="form-control" placeholder="aff ประเภทกีฬา <?=$v['game_name_type']?> Level 2" value="<?=$v['fix_bygame2']?>" name="fix_bygame2_<?=$v['id']?>" /></td>	  
                                                                                 </tr>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                              </div> 
													 </div>

                                                    

													
												</div> 

											</div>
											<div class="form-actions right">
												<button type="button" class="btn btn-primary submit-setting-aff">
													<i class="la la-check-square-o"></i> Save
												</button>
											</div>
										</form>
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
		$(function() {
			$(document).on("change",".aff_type",function() {
                 var aff_type=$(this).val(); 
                 $('.boxcommtype').hide();
                 $('.boxcomm_type'+aff_type).show(); 
            });
			$('.MenuAffSetting').addClass('active');
			var SubmitFormSettingAFF = $('.myway-form-setting-aff');
			SubmitFormSettingAFF.ajaxForm({
				url: '<?php echo site_url("website/update_aff_site") ?>',
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSubmit: function() {
					Myway_ShowLoader();
				},
				success: function(result, statusText, xhr, form) {
					if (result.Message == true) {
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

			$('.submit-setting-aff').click(function() {
				$('.myway-form-setting-aff').submit();
			});
		});
	</script>
</body>

</html>