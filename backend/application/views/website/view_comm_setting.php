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
								<li class="breadcrumb-item active">เว็บไซต์
								</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

			<div class="content-body">
				<section>
					<div class="row match-height">

						<div class="col-xs-12 col-md-6">
							<div class="card">

								<div class="card-content collapse show">
									<div class="card-body">

										<form onsubmit="return false;" class="myway-form-setting-comm-01" id="myway-form-setting-comm-01" autocomplete="off" method="post" action>
											<div class="form-body">
												<h4 class="form-section"><i class="ft-airplay"></i> <?=$pg_title?></h4>
												<div class="row">
													<div class="col-md-12"> 
															<div class="form-group">
                                                                <label for="com_type">รูปแบบค่าคอมมิชชั่น</label>
                                                                    <select id="com_type" name="com_type" class="form-control com_type"> 
                                                                        <option value="1" <?=($com_type==1 ?'selected':'')?> >แบบฟิกให้เรทเท่ากันหมด รวมทุกประเภทกีฬา</option>
                                                                        <option value="2" <?=($com_type==2 ?'selected':'')?> >แบบขั้นบันได รวมทุกประเภทกีฬา</option>
                                                                        <option value="3" <?=($com_type==3 ?'selected':'')?> >แบบขั้นบันได หรือฟิก แยกประเภทกีฬา</option>
                                                                        <option value="4" <?=($com_type==4 ?'selected':'')?> >แบบฟิกให้เรท แยกทุกประเภทกีฬา</option>
                                                                    </select>
                                                              </div> 
													</div>

                                                    <div class="col-md-12 boxcommtype boxcomm_type1" style="<?=($com_type==1?'':'display:none')?>"> 
															<div class="form-group">
                                                                <label for="com_type">แบบฟิกให้เรทเท่ากันหมด รวมทุกประเภทกีฬา</label>
                                                                <input type="number" value="<?= $com_fix ?>" id="com_fix" class="form-control com_fix" name="com_fix" autocomplete="off">
                                                              </div> 
													 </div>

                                                     <div class="col-md-12 boxcommtype boxcomm_type2" style="<?=($com_type==2?'':'display:none')?>"> 
															<div class="form-group">
                                                                <label for="com_type">แบบขั้นบันได รวมทุกประเภทกีฬา</label>
                                                                       <style> .tbmpro th,.tbmpro td{padding: .2em !important;} </style>
                                                                        <div class="table-responsive box_pro_deposit_type pro_deposit_type_range">
                                                                        <table class="table table-xs table-bordered mb-0 tbmpro">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="padding:.5em;">ลำดับที่</th> 
                                                                                    <th>ค่าคอม</th>
                                                                                    <th>ยอดเล่นเริ่มต้น</th>
                                                                                    <th>ยอดเล่นสูงสุด</th> 
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php 
                                                                                $i=0;
                                                                                foreach ($comm_rate_range_all['result_array'] as $k => $v) {
                                                                                    $i++
                                                                                    ?>
                                                                                   <tr>
                                                                                    <th scope="row"><?=$i?></th>
                                                                                    <td><input type="number" class="form-control" placeholder="ค่าคอม" value="<?=$v['comm_range']?>" name="comm_range_<?=$v['id']?>" /></td> 
                                                                                    <td><input type="number" class="form-control" placeholder="ยอดเล่นเริ่มต้น" value="<?=$v['comm_range_start']?>" name="comm_range_start_<?=$v['id']?>" /></td>
                                                                                    <td><input type="number" class="form-control" placeholder="ยอดเล่นสูงสุด" value="<?=$v['comm_range_end']?>" name="comm_range_end_<?=$v['id']?>" /></td> 
                                                                                 </tr>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                              </div> 
													 </div>

                                                     <div class="col-md-12 boxcommtype boxcomm_type3" style="<?=($com_type==3?'':'display:none')?>"> 
                                                            <?php 
                                                            foreach ($comm_type_all['result_array'] as $k => $v) {   ?>
                                                                <div class="form-group"> 
                                                                   <label for="com_type">ประเภทกีฬา <?=$v['game_name_type']?></label> 
                                                                    <div class="input-group">
                                                                        <div class="d-inline-block custom-control custom-radio mr-1">
                                                                            <input type="radio" data-game_type="<?=$v['game_type']?>" name="game_3com_type_<?=$v['game_type']?>" class="custom-control-input game_3com_type" value="1" <?=($v['com_type']==1) ? 'checked' : '';?> id="game_3com_type1<?=$v['game_type']?>" />
                                                                            <label class="custom-control-label" for="game_3com_type1<?=$v['game_type']?>">แบบขั้นบันได</label>
                                                                        </div>
                                                                        <div class="d-inline-block custom-control custom-radio">
                                                                        <input type="radio"  data-game_type="<?=$v['game_type']?>" name="game_3com_type_<?=$v['game_type']?>" class="custom-control-input game_3com_type" value="2" <?=($v['com_type']==2) ? 'checked' : '';?> id="game_3com_type2<?=$v['game_type']?>" />
                                                                            <label class="custom-control-label" for="game_3com_type2<?=$v['game_type']?>">แบบฟิกเรท</label>
                                                                        </div> 

                                                                    </div>
                                                               </div> 
															<div class="form-group box3commtype<?=$v['game_type']?> boxcomm31" style="<?=($v['com_type']==1?'':'display:none')?>"> 
                                                                        <div class="table-responsive box_pro_deposit_type pro_deposit_type_range">
                                                                        <table class="table table-xs table-bordered mb-0 tbmpro">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="padding:.5em;">ลำดับที่</th> 
                                                                                    <th>ค่าคอม</th>
                                                                                    <th>ยอดเล่นเริ่มต้น</th>
                                                                                    <th>ยอดเล่นสูงสุด</th> 
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php 
                                                                                $ii=0;
                                                                                foreach ($comm_rate_range_type['result_array'] as $kk => $vv) { 
                                                                                    if($vv['game_type']!=$v['game_type']){continue;}
                                                                                    $ii++
                                                                                    ?>
                                                                                   <tr>
                                                                                    <th scope="row"><?=$ii?></th>
                                                                                    <td><input type="number" class="form-control" placeholder="ค่าคอม" value="<?=$vv['comm_range']?>" name="comm3_range_<?=$vv['id']?>" /></td> 
                                                                                    <td><input type="number" class="form-control" placeholder="ยอดเล่นเริ่มต้น" value="<?=$vv['comm_range_start']?>" name="comm3_range_start_<?=$vv['id']?>" /></td>
                                                                                    <td><input type="number" class="form-control" placeholder="ยอดเล่นสูงสุด" value="<?=$vv['comm_range_end']?>" name="comm3_range_end_<?=$vv['id']?>" /></td> 
                                                                                 </tr>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                              </div> 
                                                                <?php ?>
                                                             <div class="form-group box3commtype<?=$v['game_type'] ?> boxcomm32" style="<?=($v['com_type']==2?'':'display:none')?>">
                                                             <div class="table-responsive box_pro_deposit_type pro_deposit_type_range">
                                                                        <table class="table table-xs table-bordered mb-0 tbmpro">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="padding:.5em;">ลำดับที่</th>  
                                                                                    <th>ประเภทกีฬา</th>
                                                                                    <th>ค่าคอม</th> 
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php 
                                                                                $iii=0;
                                                                                foreach ($comm_type_all['result_array'] as $kkk => $vvv) {//var_dump($v);exit();
                                                                                    $iii++
                                                                                    ?>
                                                                                   <tr>
                                                                                    <th scope="row"><?=$iii?></th>
                                                                                    <td><?=$vvv['game_name_type']?></td> 
                                                                                    <td><input type="hidden" name="game_3type_fix_<?=$vvv['game_type']?>"><input type="hidden" name="id_3type_fix_<?=$vvv['id']?>">
                                                                                        <input type="number" class="form-control" placeholder="คอมประเภทกีฬา <?=$vvv['game_name_type']?>" value="<?=$vvv['com_fix_bygame']?>" name="com_3fix_bygame_<?=$vvv['id']?>" /></td>  
                                                                                 </tr>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                             </div>
 

                                                              <?php } ?>
													 </div>

                                                     <div class="col-md-12 boxcommtype boxcomm_type4"  style="<?=($com_type==4?'':'display:none')?>">
															<div class="form-group">
                                                                <label for="com_type">แบบฟิกให้เรท แยกทุกประเภทกีฬา</label> 
                                                                        <div class="table-responsive box_pro_deposit_type pro_deposit_type_range">
                                                                        <table class="table table-xs table-bordered mb-0 tbmpro">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="padding:.5em;">ลำดับที่</th>  
                                                                                    <th>ประเภทกีฬา</th>
                                                                                    <th>ค่าคอม</th> 
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php 
                                                                                $i=0;
                                                                                foreach ($comm_type_all['result_array'] as $k => $v) {//var_dump($v);exit();
                                                                                    $i++
                                                                                    ?>
                                                                                   <tr>
                                                                                    <th scope="row"><?=$i?></th>
                                                                                    <td><?=$v['game_name_type']?></td> 
                                                                                    <td><input type="hidden" name="game_type_fix_<?=$v['game_type']?>"><input type="hidden" name="id_type_fix_<?=$v['id']?>">
                                                                                        <input type="number" class="form-control" placeholder="คอมประเภทกีฬา <?=$v['game_name_type']?>" value="<?=$v['com_fix_bygame']?>" name="com_fix_bygame_<?=$v['id']?>" /></td>  
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
												<button type="button" class="btn btn-primary submit-setting-website-01">
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
			$('.MenuCommSetting').addClass('active');
            $(document).on("click",".game_3com_type",function() { 
                 var game_3com_type=$(this).data("game_type"); 
                 $('.box3commtype'+game_3com_type).hide();
                 $('.box3commtype'+game_3com_type+'.boxcomm3'+$(this).val()).show(); 
            });

            $(document).on("change",".com_type",function() {
                 var com_type=$(this).val(); 
                 $('.boxcommtype').hide();
                 $('.boxcomm_type'+com_type).show(); 
            });

			var SubmitFormSettingWebsite01 = $('.myway-form-setting-comm-01');
			SubmitFormSettingWebsite01.ajaxForm({
				url: '<?php echo site_url("website/update_comm") ?>',
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

			$('.submit-setting-website-01').click(function() {
				$('.myway-form-setting-comm-01').submit();
			});
             
		});
 
	</script>
</body> 
</html>