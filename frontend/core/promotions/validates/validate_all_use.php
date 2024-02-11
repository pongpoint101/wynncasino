<?php 
$promo_id = $row['member_promo'];  
$sql = "SELECT pro_turnover_type FROM pro_promotion_detail WHERE pro_id=? ORDER BY id;";
$datakey = 'promo:' . $member_promo;
$promo_detail = GetDataSqlWhereOne($datakey, $sql, [$promo_id], 5 * 60); 
if(isset($promo_detail['pro_turnover_type'])){
    $pro_turnover_type=$promo_detail['pro_turnover_type']; 
    $alertAmountText = "เทิร์นยอดเล่น";$gs_game_type = 1;
    $game_turnover = $turnover_now;
    if($pro_turnover_type==3||$pro_turnover_type==4){
        $game_turnover = $before_credit;  
        $alertAmountText = "ยอดเครดิต";$gs_game_type=2;   
    } 
}


?>