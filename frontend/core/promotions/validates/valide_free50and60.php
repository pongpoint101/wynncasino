<?php
// เครดิดฟรีเท่านั้น 50
$resMemberWallet = $conn->query("SELECT * FROM member_wallet WHERE member_no=?", [$member_no]);
$resMemberWallet = $resMemberWallet->row_array();
$currentWalletAmount = $resMemberWallet['main_wallet'];
$sql = "SELECT * FROM promofree50 WHERE status=1 AND member_no=? ORDER BY id DESC LIMIT 1";
$res = $conn->query($sql, [$member_no]);
if ($res->num_rows() > 0) {  // Free50  
  $IsFreeCredit = 1;
  $res = $res->row_array();
  $win_expect = (int)$res['win_expect'];
  // if ($win_expect > $game_turnover) {
  if ($win_expect > $currentWalletAmount) {
    echojs("ยอดไม่ถึงเงื่อนไขที่ถอนได้", "รับเคดิตฟรีต้องมี$alertAmountText เท่ากับหรือมากกว่า " . number_format($win_expect, 2, ".", ",") . " (A)", 2, "error");
  }
}
$sql = "SELECT * FROM promofree60 WHERE status=1 AND member_no=? ORDER BY id DESC LIMIT 1";
$res = $conn->query($sql, [$member_no]);
if ($res->num_rows() > 0) {  // Free60  
  $IsFreeCredit = 1;
  $res = $res->row_array();
  $win_expect = (int)$res['win_expect'];
  // if ($win_expect > $game_turnover) {
  if ($win_expect > $currentWalletAmount) {
    echojs("ยอดไม่ถึงเงื่อนไขที่ถอนได้", "รับเคดิตฟรีต้องมี$alertAmountText เท่ากับหรือมากกว่า " . number_format($win_expect, 2, ".", ",") . " (A)", 2, "error");
  }
}

if (($member_last_deposit != 16 || $member_last_deposit != 17|| $member_last_deposit != 18) && $turnover_now <= 0&&$member_ignore_zero_turnover == 0&&$member_promo != 0) {
  if($member_promo!=0&&abs($turnover_expect)>0){ 
    echojs("ไม่สามารถถถอนได้", "ต้องมียอดเล่นมากกว่า 1. บาท จึงจะสามารถถอนได้ <BR><small style='color:#ff873f'>(เพื่อป้องกันการฟอกเงินจากมิจฉาชีพ)</small>", 2, "error");
  }else if(abs($turnover_expect)>0){
    echojs("ไม่สามารถถถอนได้", "ต้องมียอดเล่นมากกว่า 1. บาท จึงจะสามารถถอนได้", 2, "error");
  } 
  
} 
//  if($member_promo != 0){ /// เช็คไม่รับโปร ถ้า type = 0 แสดงว่ายังไม่ได้รับโปร ใดๆ
//    if($IsFreeCredit == 0){ /// ถ้า เป็น 0 แสดงว่า ไม่ได้รับ ฟรีเครดิต
//      if ($turnover_now <= 0) { // ถ้า turn over น้อยกว่า 0 เท่ากับว่า ยังไม่เคยเล่นเกมส์ เพราะ ไม่รับโปรจะต้องเข้า turn over อยู่แล้ว
//        if (!in_array($member_last_deposit,[16,17,18])) {
//          echojs("ไม่สามารถถถอนได้","ต้องมียอดเล่นมากกว่า 1. บาท จึงจะสามารถถอนได้",2,"error");
//        }
//      }
//    }
//  }
