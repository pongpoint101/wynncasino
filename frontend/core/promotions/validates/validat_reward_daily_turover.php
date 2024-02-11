<?php    
$member_promo=$row['member_promo'];
$win_expect=0; 
$sql_pro = "SELECT amount,turnover_expect AS win_expect FROM pro_reward_daily_turover WHERE member_no=? ORDER BY id DESC LIMIT 1;";
$game_turnover = $amount;  
$channel = $member_promo;
$alertAmountText = "ยอดเทิร์นโอเวอร์";  

$res = $conn->query($sql_pro,[$member_no]); 
if ($res->num_rows()>0) {    
    $res =$res->row_array(); 
    $win_expect=$res['win_expect']; 
    if ($game_turnover<$win_expect) { 
     $turnover_expect=(int)@$res['win_expect'];
     $error=402;
     $msg="ต้องมี".$alertAmountText." ".number_format($turnover_expect,2,".",",")."ขึ้นไป<BR>".$alertAmountText."ปัจจุบัน ".number_format($game_turnover,2,".",",");
     echojs("ยอดไม่ถึงเงื่อนไขที่ถอนได้",$msg,2,"error");  
    }  
} 