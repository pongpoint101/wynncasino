<?php  
//คำนวนโบนัส 
if($pro_bonus_type==1){//  โบนัสเครดิต
    $promo_cal_result = $pro_bonus_amount;   
    }else if($pro_bonus_type==2){//โบนัสจำนวนเปอร์เซ็นต์(%)  
    $promo_cal_result = round($latest_deposit_amount *$cal_bonus_percentage);  // ปัดทศนิยมเป็นจำนวนเต็ม
    if($promo_max!=-1){
        $promo_cal_result = ($promo_cal_result > $promo_max) ? $promo_max : $promo_cal_result;
    } 
    if($pro_deposit_type==3){$promo_cal_result= $promo_max;}
    if($pro_deposit_type==4){$promo_cal_result= $pro_bonus_amount;} 
} 
//คำนวนโบนัส 
if(@$is_reward==1){ ////pro_bonus_amount=ยอดเงินโบนัส อันนี้เป็นรางวัลพิเศษจะเท่ากับ pro_bonus_amount ทันที 
    $promo_cal_result =$latest_deposit_amount;
} 
//คำนวนเทิร์นโอเวอร์
if($pro_turnover_type==1){ //ทำยอดเล่นจำนวนเต็ม
    $expect_turnover = $pro_turnover_amount;   
}else if($pro_turnover_type==2){//ทำยอดเล่นกี่เท่า
    $expect_turnover = (($main_wallet)+$promo_cal_result) * (($pro_turnover_amount==1)?2:$pro_turnover_amount); 
}else if($pro_turnover_type==3){//ทำเทิร์นยอดบวก
    $expect_turnover = $pro_turnover_amount;   
}else if($pro_turnover_type==4){//ทำเทิร์นจำนวนเท่า
    $expect_turnover = (($main_wallet)+$promo_cal_result) * (($pro_turnover_amount==1)?2:$pro_turnover_amount); 
} 
$win_expect=$expect_turnover;
?>