<?php 
require '../bootstart.php';  
require ROOT.'/public/theme_v1/functionall.php';
$WebSites=GetWebSites(); 
?>
<!doctype html>

<html lang="th" style="scroll-behavior: smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>โปรโมชั่น - <?=$WebSites['title']?></title>

        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
		require ROOT.'/core/headermatadata.php';
        ?> 
        <style type="text/css">
            .mainheadbg{
                min-height: 600px;
            }
            @media screen and (max-width: 415px) {
            	.protexthead1{
            		font-size:0.9rem;
            	}
            	.protexthead2{
            		font-size: 0.6rem;
            	}
            	.carousel-control-next {
				    right: -50px !important;
				}
				.carousel-control-prev {
				    left: -50px !important;
				}
				.boxsub78{
					width: 100%;  margin-top: 40px;
				}
			}
        </style>
    </head>
    <body>
    	<?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>

        <div class="w100" id="app" v-cloak>

	        <div class="pcbox pcPromoMain">
	            <div class="mainheadbg">
	                <div class="container">
	                    <div class="boxsub78 promoMain">
                            
						      <div class="row ma-0 align-center justify-center">
							   <div class="wrap-nav ma-0 pa-0 col col-12"> 
									  <div class="row ma-0 align-center justify-center">
										     <div class="col col-6">
											    <a href="javascript:void(0)" @click='switchelement =1'  :class="{ active: switchelement==1}"  class="btn btn-warning btnswitchpro">
												  <span class="">
												  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trophy-fill" viewBox="0 0 16 16">
													<path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935z"/>
													</svg> 	  
												  สล็อตโปรโมชั่น</span>
												</a>
												</div> 
	                                            <div class="col col-6">
													<a href="javascript:void(0)" @click='switchelement =2' :class="{ active: switchelement==2}"class="btn btn-warning btnswitchpro">
														<span class="">
														  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award-fill" viewBox="0 0 16 16">
															<path d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z"/>
															<path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
															</svg> 
															คาสิโนและกีฬา</span>
													</a>
												</div>
											</div> 
									  </div> 
									  <div class="mt-3"> 
										<div class="row">  
									       <template v-if="switchelement==1"> 
									         <?php include('pro_slote.php');?>
										   </template>
										   <template v-if="switchelement==2">
										     <?php include('pro_casino.php');?>
										   </template>
										   <?php include('pro_all_use.php');?>
										  </div>  



									  </div>

								   </div>


	                    </div>
	                </div>
	            </div>
				<br/><br/><br/><br/><br/><br/>
	        </div>


        	<div class="bgpc">
                <div class="mainbodyfootbg">
                    <div class="container">
                        <div class="row g-3">
                            <?php
                            require_once ROOT.'/public/theme_v1/footer.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        require_once ROOT.'/public/theme_v1/js_script.php';
        ?>
    </body>

</html>