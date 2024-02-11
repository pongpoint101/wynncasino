<!DOCTYPE html>
<html lang="th">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
		<style type="text/css">
			.dataTables_wrapper{padding:0;}
		</style>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">ตั้งค่า</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item active"><?=$pg_title?>
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
										<h4 class="card-title"><?=$pg_title?></h4>
										<div class="heading-elements">
											<button <?php echo $hide_btn ?> class="btn btn-primary btn-md width-200 save-providers"><i class="ft-download-cloud white mr-1"></i> บันทึก</button>
										</div>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">   
											<form onsubmit="return false;" class="myway-form-providers" method="post" action>
											    <input type="hidden" name="game_type" value="provider" >
												<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle p-0">
													<thead>													
														<tr>
															<th class="text-center">ID</th>
															<th class="text-center">Game CODE</th>
															<th class="text-center">Game Name (Eng)</th> 
															<th class="text-center">STATUS <input type="checkbox" id="chk_all" value="0"></th> 
															<th class="text-center">Update Date/Time</th>
														</tr>
													</thead>
											    </table>
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

		<?php echo $pg_footer?>
		<script type="text/javascript">
			var datatablePGGmage;
		$(function(){
			$('.MenuEnableprovidergames').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('games/ListDataprovider')?>",
				"ordering": false,
				"searching": true,
				"lengthChange": false,
				"paging": false
			});

			$("#chk_all").change(function() {
				if(this.checked) {
					$('.PGStatus').prop('selectedIndex', 0);	 
				}else{
					$('.PGStatus').prop('selectedIndex', 1);
				}
			 });
			 
			var SubmitFormProviders = $('.myway-form-providers');
            SubmitFormProviders.ajaxForm({
                url: '<?php echo site_url("games/updategame")?>',
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
            $('.save-providers').click(function(){
            	$('.myway-form-providers').submit();
            });
		}); 
		</script>
	</body>
</html>