<?php    
$member_promo=$row['member_promo'];
$win_expect=0; 
$sql_pro = "SELECT deposit_amount,promo_amount,turnover_expect AS win_expect FROM promo_hny50 WHERE member_no=?  ORDER BY id DESC LIMIT 1;";
$channel = $member_promo;
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
// if ($member_promo == 31 || $member_promo == 32 || $member_promo == 33) {
//   $member_turnover = getMemberTurnover($member_no);
//   $memberCasinoTurnover = $member_turnover['sac_turnover'] + $member_turnover['aec_turnover'] + $member_turnover['kmc_turnover'] + $member_turnover['ambpk_turnover'];
//   $arr_return['expect_turnover'] = $res['turnover_expect'];
//   $arr_return['current_turnover'] = $memberCasinoTurnover;
//   if ($memberCasinoTurnover < $res['turnover_expect']) {
//     $arr_return['valid'] = 0;
//   } else {
//     $arr_return['valid'] = 1;
//   }
// } else {
//   $arr_return['win_expect'] = $res['win_expect'];
// } 