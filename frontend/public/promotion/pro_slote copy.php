<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?>
<!-- <div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_new_member.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                สมาชิกใหม่ 
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                ฝากครั้งแรก รับโบนัส 50% สูงสุด 200
            </div>
            <div class="protexthead2">
                ทำยอดบวก 1 เท่าขึ้นไป ถอนได้เลย สูงสุด 1,500 บาท<br>
                **1 ยูส / 1 สิทธิ์**<br>
                **เฉพาะสมาชิกใหม่**<br>
                **เกมส์สล็อตเท่านั้น**<br>
            </div>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(102)">รับโบนัส</button>
        </div> 
    </div> 

</div> 
</div>  -->

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_new_member.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                สมาชิกใหม่ 
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                ฝากครั้งแรก รับโบนัส 50% สูงสุด 200
            </div>
            <div class="protexthead2">
                -ทำยอดบวก 1 เท่าขึ้นไป ถอนได้เลย สูงสุด 1,500 บาท<br>
                -โปรสมาชิกใหม่เลือกรับได้แค่โปรเดียวเท่านั้น<br>
                -1 ยูส / 1 สิทธิ์<br>
                -เฉพาะสมาชิกใหม่<br>
                -เกมส์สล็อตเท่านั้น<br>
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(102)">รับโบนัส</button>
            <?php }?>       
            </div> 
    </div>  
</div> 
</div> 

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro52.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                สมาชิกใหม่ 
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                ฝากครั้งแรก 20 ได้ 100  
            </div>
            <div class="protexthead2">
                - ทำยอดบวก 1000 ถอนได้เลย สูงสุด 500 บาท<br>
                - โปรสมาชิกใหม่เลือกรับได้แค่โปรเดียวเท่านั้น<br>
                - 1 ยูส / 1 สิทธิ์<br>
                - เฉพาะสมาชิกใหม่<br>
                -เกมส์สล็อตเท่านั้น<br>
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(52)">รับโบนัส</button>
            <?php }?>       
            </div> 
    </div>  
</div> 
</div>

<!-- <div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_free60.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                รับเครดิตฟรี 60฿
            </div>
            <div class="protexthead2 protexthead2border mb-2">
            เงื่อนไขการรับ<br>
            - ลูกค้าที่เคยมีประวัติฝากเงินแล้ว 50 บาทขึ้นไป<br> 
            เงื่อนไขการถอน<br>
            - ทำยอด 600 เครดิต ถอนได้ 120 บาท 
            </div>
            <div class="protexthead2">
            **1 ยูส / 1 สิทธิ์**<br>
            **เกมส์สล็อตเท่านั้น**<br>
            </div>
        </div> 
    </div>

</div> 
</div> -->
<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_free50x3.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                รับเครดิตฟรี 50฿
            </div>
            <div class="protexthead2 protexthead2border mb-2">
            เงื่อนไขการรับ<br>
            - Like & Share ติดต่อแอดมิน<br> 
            เงื่อนไขการถอน<br> 
            - ทำยอด 500 เครดิต ถอนได้ 100 บาท 
            </div>
            <div class="protexthead2">
            **1 ยูส / 1 สิทธิ์**<br> 
            **เกมส์สล็อตเท่านั้น**<br>
            </div>
        </div> 
    </div>

</div> 
</div>

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">
    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/slot_winner_combo.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
            สล็อตแตก แจกเพิ่ม
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                แตก 5,000 รับเพิ่ม 200<br>
                แตก 10,000 รับเพิ่ม 500<br>
                แตก 30,000 รับเพิ่ม 1,500<br>
                แตก 50,000 รับเพิ่ม 3,000
            </div>
            <div class="protexthead2">
                เงื่อนไข :<br>
                1. รับได้วันละ 1 ครั้ง<br>
                2. ติดต่อแอดมินเพื่อรับสิทธิ์<br>
                3. เกมส์สล็อตเท่านั้น<br>
                4. เฉพาะยอดฝากที่ไม่รับโบนัส<br> 
                ***เกมส์สล็อตเท่านั้น***
            </div> 
        </div> 
    </div>
</div> 
</div>

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

<div class="row ">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_frequency02.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                โปรฝากประจำ 
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                  ฝาก 500 ชึ้นไปครบ 3 วันรับ 200<br>
                  ฝาก 500 ชึ้นไปครบ 7 วันรับ 500<br>
                  ฝาก 500 ชึ้นไปครบ 10 วันรับ 1,000<br>
                  ฝาก 500 ชึ้นไปครบ 15 วันรับ 2,000<br>
                 
            </div>
            <div class="protexthead2">
                 เงื่อนไขกิจกรรม:<br> 
                ***เฉพาะยอดฝาก 500 ขึ้นไปที่ไม่รับโบนัสเท่านั้น***<br>
                ***ทำยอด 1 เท่าถอนได้เลย ไม่มีอั้น***<br>
                ***เกมส์สล็อตเท่านั้น***
            </div>
           <?php if(isset($_SESSION['member_no'])){
               ?>
                <!-- <a class="btn btn-warning btnfull" href="<?=$tg_fn->tugame_url('depositfrequency')?>" role="button">รับโบนัส</a> -->
               <?php
           } ?> 
        </div> 
    </div>

</div> 
</div>

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

<div class="row ">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_deposit10.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                โปรฝากประจำ รับโบนัส 10% ทั้งวัน
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                เฉพาะยอดฝาก 300 500 1,000 3,000 5,000<br>ทำยอด 1 เท่าถอนได้เลย ไม่มีอั้น
            </div>
            <div class="protexthead2">
                ***เกมส์สล็อตเท่านั้น***
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(3)">รับโบนัส</button>
            <?php }?>
        </div> 
    </div>

</div> 
</div>

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_happy_time.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                Happy Time
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                ช่วงเช้า 12.00-13.00 <br> ฝาก 200 รับเพิ่ม 50 บาท<br>
                รอบดึก 00.00-01.00 <br> ฝาก 400 รับเพิ่ม 100 บาท
            </div>
            <div class="protexthead2">
                ทำยอด 1 เท่าถอนได้เลย รับได้ 1 ครั้ง/1 ช่วงเวลา<br>
                ***เกมส์สล็อตเท่านั้น***
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(4)">คลิก</button>
            <?php }?>
        </div> 
    </div>

</div> 
</div>

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">
    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="propic">
                <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_frequency.jpg')?>" alt="">
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                ขาประจำ เราจัดให้
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                สะสมครบ 5,000 รับโบนัส 100%<br>
                สะสมครบ 10,000 รับโบนัส 120%<br>
                สะสมครบ 20,000 รับโบนัส 150%<br>
                สะสมครบ 50,000 รับโบนัส 200%
            </div>
            <div class="protexthead2">
                เงื่อนไข :<br>
                1. คำนวณจากยอดฝากสะสมทั้งเดือนของเดือนที่ผ่านมา<br>
                2. รับโบนัสจากยอดฝาก 100 บ. ของเดือนถัดไป<br>
                3. รับได้ 1 ครั้ง/เดือน เท่านั้น<br>
                4. ทำยอด 1 เท่าถอนได้เลย<br>
                5. ถอนได้สูงสุด 1,000<br>
                ***เกมส์สล็อตเท่านั้น***
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(5)">คลิก</button>
            <?php }?>
        </div> 
    </div>
</div> 
</div>  