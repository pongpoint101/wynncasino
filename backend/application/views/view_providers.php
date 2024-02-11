<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
									<li class="breadcrumb-item active">Providers
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
										<h4 class="card-title">ตั้งค่า Providers</h4>
										<div class="heading-elements">
											<button class="btn btn-primary btn-md width-200 save-providers"><i class="ft-download-cloud white mr-1"></i> บันทึก</button>
										</div>
									</div>
									<div class="card-content collpase show">
										<div class="card-body card-dashboard">
											<form onsubmit="return false;" class="myway-form-providers" method="post" action>
												<table class="table table-striped table-bordered ajax-sourced pg-data-table table-middle p-0">
													<thead>													
														<tr>
															<th class="text-center">CODE / NAME</th>
															<th class="text-center">Status</th>
															<th class="text-center">Casino(%)</th>
															<th class="text-center">Slot(%)</th>
															<th class="text-center">Fishing(%)</th>
															<th class="text-center">Sports(%)</th>
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
			$('.MenuProviders').addClass('active');
			datatablePGGmage = $('.pg-data-table').DataTable( {
				"ajax": "<?php echo base_url('providers/json_providers_list')?>",
				"ordering": false,
				"searching": false,
				"lengthChange": false,
				"paging": false
			});
			var SubmitFormProviders = $('.myway-form-providers');
            SubmitFormProviders.ajaxForm({
                url: '<?php echo site_url("providers/update_setting")?>',
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
		function saveConfig() {



			var i;
			for (i = 1; i < 13; i++) {
				var row_id = document.getElementById("row_id_" + i).value;
				var status = document.getElementById("status_" + i).value;
				var casino_comm = document.getElementById("casino_comm_" + i).value;
				var slot_comm = document.getElementById("slot_comm_" + i).value;
				var fishing_comm = document.getElementById("fishing_comm_" + i).value;
				var sports_comm = document.getElementById("sports_comm_" + i).value;
				$.ajax({
					type: 'POST',
					url: 'api/providers-edit.php',
					data: {
						'row_id': row_id,
						'status': status,
						'casino_comm': casino_comm,
						'slot_comm': slot_comm,
						'fishing_comm': fishing_comm,
						'sports_comm': sports_comm
					},
					success: function() {
						console.log("row_id_" + i);
					}
				});
				if (i >= 12) {
				  alert('Update completed!!!!');
				  // exit();
				}
			}
		}
		</script>
	</body>
</html>