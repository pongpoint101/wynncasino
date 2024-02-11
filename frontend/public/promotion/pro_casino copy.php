<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?>
<!-- ####################################################### -->
<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

    <div class="row">
        <div class="col-xl-6 col-12">
        <div class="propic">
            <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_deposit300-50.jpg')?>" alt="">
        </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="protexthead1">
                ฝาก 300 รับ 50
            </div>
            <div class="protexthead2 protexthead2border mb-2">
                ฝากแรกของวัน ฝาก 300 รับเพิ่มอีก 50<br>
                เทิร์นโอเวอร์ 5 เท่า ถอนได้ไม่อั้น
            </div>
            <div class="protexthead2">
                *** คาสิโนเท่านั้น ***
            </div>
            <?php if(isset($_SESSION['member_no'])){ ?>
            <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(120)">คลิก</button>
            <?php }?>
        </div> 
    </div>

</div> 
</div> 
<!-- ####################################################### -->
<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

<div class="row">
    <div class="col-xl-6 col-12">
        <div class="propic">
            <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_deposit2per.jpg')?>" alt="">
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="protexthead1">
            รับโบนัส 2% ทั้งวัน สูงสุด 1,000
        </div>
        <div class="protexthead2 protexthead2border mb-2">
            รับโบนัส 2% ทุกยอดฝาก 50 บ. ขึ้นไป<br>
            ทำเทิร์น 5 เท่าขึ้นไป ถอนไม่อั้น
        </div>
        <div class="protexthead2">
            *** คาสิโนเท่านั้น ***
        </div>
        <?php if(isset($_SESSION['member_no'])){ ?>
        <button type="button" class="btn btn-warning btnfull" v-on:click="get_bonus(121)">คลิก</button>
        <?php } ?>
    </div> 
</div>

</div> 
</div>  

<div class="col-md-6 col-12 mb-4">
<div class="gold-box rounded-lg">

<div class="row">
    <div class="col-xl-6 col-12">
        <div class="propic">
            <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_lose-win.jpg')?>" alt="">
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="protexthead1">
            บอดสนิทก็มีสิทธิ์ได้ตังค์
        </div>
        <div class="protexthead2 protexthead2border mb-2">
            แพ้แต้ม "0" ติดกัน 3 ตาซ้อน<br>
            รับไปเลย 5,000
        </div>
        <div class="protexthead2">
            *** เดิมพันขั้นต่ำ 500 ต่อตา(Banker หรือ Player เท่านั้น)<br>
            *** แจ้งรับภายใน 1 ชม. เมื่อผ่านเงื่อนไข<br>
            *** เฉพาะบาคาร่าเท่านั้น<br>
            *** เฉพาะยอดฝากที่ไม่รับโบนัส
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
            <img src="<?=$tg_fn->tugame_url('assets/images/promo/pro_winup2.jpg')?>" alt="">
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="protexthead1">
            ชนะทั้งที ต้องมี 2 เด้ง
        </div>
        <div class="protexthead2 protexthead2border mb-2">
            ชนะด้วยไพ่ 8 แต้ม 2 เด้ง<br>
            ติดกัน 3 ตาซ้อน รับไปเลย 5,000
        </div>
        <div class="protexthead2">
            *** เดิมพันขั้นต่ำ 500 ต่อตา(Banker หรือ Player เท่านั้น)<br>
            *** แจ้งรับภายใน 1 ชม. เมื่อผ่านเงื่อนไข<br>
            *** เฉพาะคาสิโนเท่านั้น<br>
            *** เฉพาะยอดฝากที่ไม่รับโบนัส
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
            <img src="<?=$tg_fn->tugame_url('assets/images/promo/casino_winner10-12.jpg')?>" alt="">
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="protexthead1">
         แพ้หรือชนะติดๆ กัน
        </div>
        <div class="protexthead2 protexthead2border mb-2">
            แพ้หรือชนะ 8 ตา รับ 5,000<br>
            แพ้หรือชนะ 10 ตา รับทองคำหนัก 1 บ.<br>
            แพ้หรือชนะ 12 ตา รับ iPhone 12 Pro
        </div>
        <div class="protexthead2">
            *** เดิมพันขั้นต่ำ 500 ต่อตา(Banker หรือ Player เท่านั้น)<br>
            *** แจ้งรับภายใน 1 ชม. เมื่อผ่านเงื่อนไข<br>
            *** เฉพาะบาคาร่าเท่านั้น<br>
            *** เฉพาะยอดฝากที่ไม่รับโบนัส
        </div>
    </div> 
</div>

</div>
</div>






