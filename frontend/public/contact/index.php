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
        <title>ติดต่อเรา - <?=$WebSites['title']?></title>

        <?php
        require ROOT.'/public/theme_v1/head_site_js_css.php';
        require ROOT.'/core/headermatadata.php';
        ?> 
        <style type="text/css">
            .mainheadbg{
                min-height: 600px;
            }
        </style>
    </head>
    <body>
        
        <?php
        require_once ROOT.'/public/theme_v1/menu_site.php';
        ?>
        <div class="w100" id="app" v-cloak>
            <div class="pcboxx">
                <div class="mainheadbg">
                    <div class="container mt-4">
                        <div class="boxsub77white pt-4">
                            <div class="m-4">
                              <div class="col-12 text-center">
                               <h1>ติดต่อทีมงานได้ตลอด 24 ชั่วโมง</h1>
                                   <a href="<?=$WebSites['line_at_url']?>" target="_blank">
                                        <img src="<?=CDN_URI?>/web/<?=$WebSites['line_img_qr']?>" class="img-fluid"></a>
                                    <a href="<?=$WebSites['line_at_url']?>" target="_blank" class="btn btn-login-tg-s butregis w100" style=" font-size: 20px; padding: 8px 6px; display: inline-grid;">
                                    LINE ID : <?=$WebSites['line_at_name']?> 
                                    </a>
                               </div>
                            </div> 

                        </div>
                    </div>
                </div>

                <br/><br/><br/><br/><br/>
                
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