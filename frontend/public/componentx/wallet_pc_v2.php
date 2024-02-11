<div class="boxsub77head head-username-style">
    <span class="boxsub77headt1 circle_style_head">รหัสสมาชิกของคุณ : {{data_global.username}}</span><br>
</div>
<div class="boxsub77body contain-body-wallet-style mb-4">
    <div class="d-flex justify-content-around align-items-center">

    <div class="border-gold-circle-g btn-border-gole styleInfoUserCircle">
    <div class="bg-gold-circle-g">
       <span class="text-white font-medium-3">ยอดเงินที่ใช้ได้</span>
            <span class="boxfont2" id="credit-amount">{{data_global.main_wallet}}<font class="font-large-1 bn-995">฿</font></span>
            <span class="text-white font-small-3 bs03 mb-1"><i class="bi bi-play-fill"></i>
                ข้อมูลบัญชีของคุณ : {{data_global.bank_accountnumber}} | ธนาคาร {{data_global.bank_name}}
            </span>
            <div class="butbox butbox-black mb-1" style="z-index: 3;">
                <div class="btn-border-gole border-in-model w100">
                   <a class="btn btn-gold btn_gold_black  ps-3 pe-3" href="<?=$tg_fn->tugame_url('deposit')?>" role="button">ฝากเงิน</a> 
			    </div>  
            </div>
            <div class="butbox butbox-white mb-2" style="z-index: 3;">
                <a href="<?=$tg_fn->tugame_url('withdraw')?>">
                    ถอนเงิน
                </a>
            </div>
            <div style="position: absolute;z-index: 2;">
                <a href="<?=$tg_fn->tugame_url('lobby')?>" class="d-flex flex-column justify-content-center stylePlayUserCircle">
                    <i class="fas fa-play font-large-1"></i>
                    <span class="font-small-3">เข้าเล่นเกมส์</span>
                </a>
            </div>
    </div>
   </div>
 
    </div>
</div>