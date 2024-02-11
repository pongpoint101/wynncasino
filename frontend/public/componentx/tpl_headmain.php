<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?>
<div class="nav-bar">
    <nav class="container navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid head-menu">
            <button class="navbar-toggler mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="color: #fff;"></span>
            </button>
            <div class="navbar-brand"> <img src="<?= GetFullDomain() ?>/assets/images/common/logo.png?v=2" style="height:40px"></div>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav w-100 row p-0 m-0">
                    <a class="nav-link  col-xl-2 col-md-12 px-3" href="<?=GetFullDomain()?>">
                        <div class="title-th pb-2 page-links text-md-center text-sm-start">หน้าแรก <br> <span
                                class="title-en"> HOME</span>
                        </div>
                    </a>
                    <a class="nav-link col-xl-2 col-md-12 px-3" href="<?=GetFullDomain()?>/login">
                        <div class="title-th pb-2  page-links text-md-center text-sm-start">เข้าสู่ระบบ <br> <span
                                class="title-en"> LOGIN</span>
                        </div>
                    </a>
                    <a class="nav-link col-xl-2 col-md-12 px-3" href="<?=GetFullDomain()?>/register">
                        <div class="title-th pb-2  page-links text-md-center text-sm-start">สมัครสมาชิก <br> <span
                                class="title-en">
                                REGISTER</span>
                        </div>
                    </a>
                    <a class="nav-link col-xl-2 col-md-12 px-3" href="<?=GetFullDomain()?>/promotion">
                        <div class="title-th pb-2  page-links text-md-center text-sm-start">โปรโมชั่น <br> <span
                                class="title-en">
                                PROMOTION</span>
                        </div>
                    </a>

                    <a class="nav-link col-xl-2 col-md-12 px-3" href="<?=$WebSites['line_at_url']?>" >
                        <div class="title-th pb-2  page-links text-md-center text-sm-start">ติดต่อเรา <br> <span
                                class="title-en"> CONTACT
                                US</span>
                        </div>
                    </a>
                </div>
            </div>
            <?php 
            if(!isset($_SESSION['member_no'])){
                ?>
                <div class="div-log">
                    <a href="<?=GetFullDomain()?>/login" style="text-decoration: none;">
                    <div class="title-th  btn-register  btn-m">เข้าสู่ระบบ</div>
                    </a>
                </div>
                
                <?php
                }
            ?> 

        </div>
    </nav>
</div>
