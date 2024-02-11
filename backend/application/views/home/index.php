<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $pg_header?>
		<title><?php echo $pg_title?></title>
	</head>
	<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
		<?php echo $pg_menu?>

		<div class="app-content content">
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block"><?=$pg_title?></h3> 
					</div> 

				</div>
				<div class="content-body">
					<div class="row">
						<div class="col-xl-6">
							<div class="card pull-up">
								<div class="card-content">
									
                                <div class="container">
    <div class="row"> 
        <div class="col-lg-12">
            <div class="tab-content" id="faq-tab-content">
                <div class="tab-pane show active" id="tab1" role="tabpanel" aria-labelledby="tab1">
                    <div class="accordion" id="accordion-tab-1">
                        <div class="card">
                            <div class="card-header" id="accordion-tab-1-heading-1">
                                <h5>
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#accordion-tab-1-content-1" aria-expanded="false" aria-controls="accordion-tab-1-content-1">
                                        Home back office system
                                    </button>
                                </h5>
                            </div>
                            <div class="collapse show" id="accordion-tab-1-content-1" aria-labelledby="accordion-tab-1-heading-1" data-parent="#accordion-tab-1">
                                <div class="card-body">
                                    <p>
                                       ระบบจัดการข้อมูล อัพเดตล่าสุด: <?php echo $PGDateTimeNow ?>
                                    </p> 
                                </div>
                            </div>
                        </div> 

                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>


								</div>
							</div>
						</div> 
					</div> 

				</div>
			</div>
		</div>
		

		<?php echo $pg_footer?>
		<script type="text/javascript">
			 
		</script>
	</body>
</html>