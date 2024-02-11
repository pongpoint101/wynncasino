<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';

if (empty($_SESSION['member_no'])) {
  exit(0);
}  
$member_no = $_SESSION['member_no'];
$duplicate_check=false;
$LINE_API="https://notify-api.line.me/api/notify";
$token = "Xfqa0IfqoVelSQ3VZcK8AKDxItflwnt1KS2We9OkmGF"; //ใส่Token ที่copy เอาไว้
if (isset($limiter)) {
  $limiterns = $limiter->hit('requestwithdraw:' . $_SESSION['member_no'], 1, 5); // เรียกได้ 40 ครั้งใน 10 วินาที
  if ($limiterns['overlimit']) {
    InSertLogSys(['member_no'=>$member_no,'ip'=>getIP(),'log_type'=>'withdraw','txt_data'=>date('Y-m-d').' '.date('H:i:s')]);
    $duplicate_check=true;  
    $member_wallet = getMemberWallet($member_no);
    $str = "HOST:{$_SERVER['HTTP_HOST']}\n member_no: {$_SESSION['member_no']} \n main_wallet:{$member_wallet['main_wallet']} \n datetime:".Date('Y-m-d').' '.Date('H:i:s')." \n microtime:".microtime(true).""; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
     
    $queryData = array('message' => $str);
    $queryData = http_build_query($queryData,'','&');
    $headerOptions = array( 
            'http'=>array(
               'method'=>'POST',
               'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                         ."Authorization: Bearer ".$token."\r\n"
                         ."Content-Length: ".strlen($queryData)."\r\n",
               'content' => $queryData
            ),
    );
    $context = stream_context_create($headerOptions);
    $result = file_get_contents($LINE_API,FALSE,$context);
    $res = json_decode($result); 
    exit(0);
  }
}
$msg = 'สำเร็จ';
$error = 200;

$sql = "SELECT id,username,fname,lname,bank_name,bank_accountnumber,member_promo, member_last_deposit,ignore_zero_turnover FROM members WHERE id=?"; // AND status='1'";
$row = $conn->query($sql, [$member_no])->row_array();
$Website = GetWebSites();
$member_promo = '';
$member_promo = $row['member_promo'];
$member_last_deposit = $row['member_last_deposit'];
$member_ignore_zero_turnover = $row['ignore_zero_turnover'];

$member_wallet = getMemberWallet($member_no);
$amount = intval(preg_replace('/[^\d.]/', '', @$_POST['amount']));
if($member_promo!=0||in_array($member_last_deposit,[16,17,18])){
  $amount = intval(preg_replace('/[^\d.]/', '', $member_wallet['main_wallet'])); //บังคับทำให้ถอนออกทั้งหมด => ถอนจำนวนเต็ม ปัดเศษออก
} 
// $amount = intval(preg_replace('/[^\d.]/', '', $member_wallet['main_wallet'])); //บังคับทำให้ถอนออกทั้งหมด => ถอนจำนวนเต็ม ปัดเศษออก
$before_credit = $member_wallet['main_wallet'];
$after_credit = $member_wallet['main_wallet'] - $amount;

$channel = 1;
$trx_id = uniqid();
$withdraw_by = $member_no;
$name_in_bank = $row['fname'] . ' ' . $row['lname'];
$bank_name_to = $row['bank_name'];
$bank_number_to = $row['bank_accountnumber'];

if (!isset($member_wallet)) {
  return;
}

$sql = "SELECT COUNT(1) totals  FROM log_withdraw WHERE member_no=? AND trx_date=SUBDATE(CURDATE(),0)"; // AND status='1'";
$rowtotals = $conn->query($sql, [$member_no])->row_array()['totals'];
if(($rowtotals+1)>$Website['max_withdraw_perday']&&$Website['max_withdraw_perday']>0){
  $msg = "ถอนเงินได้สูงสุดจำนวนครั้ง {$Website['max_withdraw_perday']} ครั้ง(ต่อวัน).";
  echojs("ไม่สามารถถถอนได้", $msg, 2, "error");
  $error = 400;
} 
if($amount>$Website['max_withdraw']&&$Website['max_withdraw']>0){
  $msg = "ถอนเงินได้สูงสุด {$Website['max_withdraw']} บาทต่อครั้ง.";
  echojs("ไม่สามารถถถอนได้", $msg, 2, "error");
  $error = 400;
}  

if (($amount < ($Website['min_withdraw'] * 1) || $amount <= 0)&&(!in_array($member_promo,[182]))) {
  $msg = "ถอนขั้นต่ำ " . number_format($Website['min_withdraw'], 2, ".", ",") . " บาท.";var_dump(!in_array($member_promo,[182]));exit();
  echojs("ถอนขั้นต่ำ", $msg, 2, "error");
  $error = 400;
}

if ($amount > $before_credit) {
  $msg = "ยอดเงินคงเหลือ " . number_format($before_credit, 2, ".", ",") . " บาท. น้อยกว่ายอดที่ต้องการถอน";
  echojs("ท่านไม่สามารถถอนเงินเกินจำนวนที่มีในกระเป๋าหลักได้ค่ะ", $msg, 2, "error");
  $error = 410;
}

$res = $conn->query("SELECT COUNT(1) AS total_rows FROM log_withdraw WHERE member_no=? AND status IN(2,5)",[$member_no]);
$totalrows =@$res->row_array()['total_rows']; 
if ($totalrows>0) {  
    $error=402;
    $msg='ไม่สามารถถอนได้ ยังมีรายการถอนรอดำเนินการ <br>กรุณารอทำรายการสักครู่ค่ะ';
    echojs("ไม่สามารถถถอนได้",$msg,1,"error"); 
}  
// $res = $conn->query("SELECT outstanding_turnover FROM member_turnover WHERE member_no=?",[$member_no]);
// $outstanding_turnover =@$res->row_array()['outstanding_turnover']; 
// if ($res->num_rows()<=0||$outstanding_turnover<=0) {  
//     $error=402;
//     $msg='ต้องมียอดเล่นมากกว่า 1.บาท จึงจะสามารถถอนได้';
//     echojs("ไม่สามารถถถอนได้",$msg,1,"error"); 
// }  
// คำนวนติดเทริน 
$channel = $member_promo;
$turnover_now = 0;
$pro_allow_playgame = [];
$wd_actual_amount = $amount;
if ($member_promo != 0) {
  $sql = "SELECT * FROM pro_promotion_detail WHERE pro_id=? ORDER BY id;";
  $datakey = 'promo:' . $member_promo;
  $promo_detail = GetDataSqlWhereOne($datakey, $sql, [$member_promo], 5 * 60);
  if (isset($promo_detail['pro_turnover_type'])) {
    $pro_allow_playgame = @explode(',', $promo_detail['pro_allow_playgame']);
  }
  if (sizeof($promo_detail) > 0) {
    if ($promo_detail['pro_withdraw_max_amount'] != -1) {
      $wd_actual_amount = $promo_detail['pro_withdraw_max_amount'];
      $wd_actual_amount = ($amount > $wd_actual_amount ? $wd_actual_amount : $amount);
    }
    if ($promo_detail['pro_deposit_type']== 3) { 
      $promember=FindByproMember($member_no);
                   if(@$promember['row']->pro_id_more > 0){
        $listmore= Getdetail_More($promo_detail['pro_id'],$promember['row']->pro_id_more); 
        if(isset($listmore['row']->withdraw_max_amount)&&@$listmore['row']->withdraw_max_amount>0){
          $wd_actual_amount =($amount>$listmore['row']->withdraw_max_amount) ? $listmore['row']->withdraw_max_amount : $wd_actual_amount; 
        }
       }  
    }
  } else {
    exit();
  }
}

if (in_array(1, $pro_allow_playgame)) { // casino only
  $turnover_now += getTotalSportbookTurnover($member_no);
}
if (in_array(2, $pro_allow_playgame)) { // casino only
  $turnover_now += getTotalCasinoTurnover($member_no);
}
if (in_array(3, $pro_allow_playgame)) { // slot only
  $turnover_now += getTotalSlotTurnover($member_no);
}


//checkCurrentPromo.php
$game_turnover = 0;
$gs_game_type = 0; // 1 = slot // 2 = casino
$IsFreeCredit = 0;
if ($member_promo == 0) {
  // $member_ignore_zero_turnover=1;
  $turnover_now = getTotalTurnover($member_no);
  $sql = "SELECT amount,trx_date,trx_time FROM log_deposit WHERE member_no=? AND channel IN (1,2,3,5) ORDER BY id DESC LIMIT 1";
  $res = $conn->query($sql, [$member_no]);
  $withsql = "SELECT balance_after AS amount,trx_date,trx_time FROM log_withdraw WHERE member_no=? ORDER BY id DESC LIMIT 1";
  $with_res = $conn->query($withsql, [$member_no]); 
  $deposit =0;

  if ($res->num_rows() >0){
     $res = $res->row_array();
     if(!in_array($member_last_deposit,[16])){ 
      $deposit = (int)@$res['amount'] * 0.2;
     } 
     }else{
    $res=[];
    } 
    if(in_array($member_last_deposit,[17])){ 
        if($member_last_deposit==17){//CASHBACK
          $sql = "SELECT amount,turnover_expect FROM log_claim_cashback WHERE member_no=? ORDER BY id DESC LIMIT 1";
        }else if($member_last_deposit==18){//COMM
          $sql = "SELECT amount,turnover_expect FROM log_claim_commission WHERE member_no=? ORDER BY id DESC LIMIT 1";
        }
      $bonus_res = $conn->query($sql, [$member_no]);    
      if ($bonus_res->num_rows() >0){
        $bonus_res = $bonus_res->row_array();
        $deposit = (@$bonus_res['turnover_expect']);
      }
   }

   $with_res = $with_res->row_array();   
  if (isset($res['trx_date'])&&isset($with_res['trx_date'])) { 
    $date_start=strtotime("{$res['trx_date']} {$res['trx_time']}");
    $date_end= strtotime("{$with_res['trx_date']} {$with_res['trx_time']}"); 
    if($date_end>$date_start&&$with_res['amount']>=1&&(!in_array($member_last_deposit,[16,17]))){
       $deposit = 0;
     }  
  }
  if ($turnover_now < $deposit) {
    if ($member_ignore_zero_turnover == 0) {
      $alertAmountText = "เทิร์นยอดเล่น";
      $msg = "ต้องมี" . $alertAmountText . " " . number_format($deposit, 2, ".", ",") . "ขึ้นไปจึงจะถอนเงินได้<BR>" . $alertAmountText . "ปัจจุบัน " . number_format($turnover_now, 2, ".", ",");
      if($member_promo!=0){
        $msg.="<BR><small style='color:#ff873f'>(เพื่อป้องกันการฟอกเงินจากมิจฉาชีพ)</small>";
      } 
      echojs($alertAmountText . "ยังไม่ถึงกำหนดที่จะทำรายการถอนได้ตามเงื่อนไข", $msg, 2, "error");
    }
  }
} else {
  require_once ROOT . '/core/promotions/validates/validate_all_use.php';
  switch ($member_promo) {
    case 8: // เปิดไพ่
      $sql = "SELECT turnover_expect FROM bonus_cardgame WHERE member_no=? ORDER BY id DESC LIMIT 1"; $alertAmountText = "เทิร์นยอดเล่น";
      break; 
    case 9 : // spin
      $sql = "SELECT turnover_expect FROM bonus_luckywheel WHERE member_no=? ORDER BY id DESC LIMIT 1"; $alertAmountText = "เทิร์นยอดเล่น";
      break;
    case 11: //โปรฯ 100% สมัครใหม่ ไม่เกิน 200 ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000 - Slot/Fishing
      $sql = "SELECT win_expect AS turnover_expect FROM promop100p WHERE member_no=? ORDER BY id DESC LIMIT 1";
      break;
    case 12: // ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing
      $sql = "SELECT win_expect AS turnover_expect FROM promop52 WHERE member_no=? ORDER BY id DESC LIMIT 1";
      break;
    // case 101: // โปรฝากประจำ รับโบนัส 10% ทั้งวัน
    //   $sql = "SELECT win_expect AS turnover_expect FROM promop10p WHERE member_no=? ORDER BY id DESC LIMIT 1;";
    //   break;
    case 106:  // Happy time ช่วงเช้า 12.00-13.00 ฝาก 200 รับเพิ่ม 50 บาท & รอบดึก 00.00-01.00 ฝาก 400 รับเพิ่ม 100 บาท
    case 107:
      $sql = "SELECT win_expect AS turnover_expect,channel, status FROM promo_happy_time WHERE member_no=? ORDER BY id DESC LIMIT 1;";
      $res_status = $conn->query($sql, [$member_no]);
      if ($res_status->num_rows() > 0) {
        $item_status = $res_status->row_array();
        $channel = $item_status['channel'];
      }
      break;
    // case 20:  // Happy New Year 
    //   require_once ROOT . '/core/promotions/validates/valide_hny50.php';
    //   break;
    // case 21:  // Welcome back ฝาก 100 รับ 50
    //   require_once ROOT . '/core/promotions/validates/valide_wb100.php';
    //   break;
    // case 22:  //Merry Christmas 
    //   require_once ROOT . '/core/promotions/validates/valide_mc.php';
    //   break;
    case 25:
      require ROOT . '/core/promotions/validates/valide_newUser150.php';
      break;
    case 108: //ขาประจำ สะสมครบ 5,000 รับโบนัส 100%  
    case 109: //ขาประจำ สะสมครบ 10,000 รับโบนัส 120%  
    case 110: //ขาประจำ สะสมครบ 20,000 รับโบนัส 150%  
    case 111: //ขาประจำ สะสมครบ 50,000 รับโบนัส 200%
      $sql = "SELECT * FROM promo_cc200 WHERE member_no=? ORDER BY id DESC LIMIT 1;";
      break;
    // case 19: // ยอดการเล่น 5 อันดับสูงสุดรายวัน
    //   require_once ROOT . '/core/promotions/validates/validat_reward_daily_turover.php';
    //   break;
    case 83: ///ฝากประจำ
    case 87:
    case 90:
    case 95:
      require ROOT . '/core/promotions/validates/valide_deposit_frequency.php';
      break;
    case 10: //โปรฯ 50% สมัครใหม่ ฝาก 200 รับเพิ่ม 100 เป็น 300 - ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000 - Slot/Fishing
      $sql = "SELECT win_expect AS turnover_expect FROM promop50p WHERE member_no=? ORDER BY id DESC LIMIT 1";
      break;
    case 50: //ฝากแรกของวัน ฝาก 300 รับ 50 เป็น 350 - เทิร์น 5 เท่า - ถอนไม่อั้น  casino
      $sql = "SELECT * FROM promop120 WHERE member_no=? ORDER BY id DESC LIMIT 1";
      break;
    case 51: // รับโบนัส 2% ทั้งวัน - เทิร์น 5 เท่า - สูงสุด 1,000 - ถอนไม่อั้น  casino
      $sql = "SELECT * FROM promop121 WHERE member_no=? ORDER BY id DESC LIMIT 1";
      break; 
    case 101: // โปรฝากประจำ รับโบนัส 10% ทั้งวัน   
    case 20:  // Happy New Year  
    case 22:  //Merry Christmas      
    case 21:  // Welcome back ฝาก 100 รับ 50
    case 112: //โปรฝากประจำ รับโบนัส 29 รับ 100 ทั้งวัน
    case 113: //โปรฝากประจำ รับโบนัส 79 รับ 200 ทั้งวัน
    case 114: //โปรลูกค้าเก่าย้ายเว็บ 50% สูงสุด 100 เครดิต รับได้ 3 ครั้ง (แอดมือเหมือน WB)	    
    case 125: //โปรสงกรานต์สาดกระจายส์		       
    case 34: //โบนัสพิเศษฉลองวันหยุดยาว	        
    case 37: // ฝากประจำ      
    case 140: //โปร 1	
    case 141: //โปร 2
    case 142: //โปร 3	
    case 143: //โปร 4		
    case 144: //โปร 5
    case 145: //โปร 6	
    case 146: //โปร 7		
    case 147: //8.8 แจกสนั่น 8 วันติด
    case 150: // โปรวันเกิด    
    case 151: // โปรพิเศษสำหรับสมาชิก  
    case 152: // กิจกรรม reward		    
    case 153: // กิจกรรม แคปปุ๊บ รับปั๊บ	     
    case 157: // โปรสะสมประจำสัปดาห์	       
    case 19: // ยอดการเล่น 5 อันดับสูงสุดรายวัน	   
    case 179: // ยอดแนะนำเพื่อน 5 อันดับสูงสุด
    case 180: // ยอดเสีย 5 อันดับสูงสุด
    case 181: // ยอดชนะ 5 อันดับสูงสุด   
    case 182: // สำหรับลูกค้าที่มีประวัติฝากเงิน 20 บาทขึ้นไป
       if($member_promo==37){$game_turnover = $before_credit;$alertAmountText = "ยอดเครดิต";$gs_game_type=2; }   
        $sql = "SELECT turnover_expect AS turnover_expect FROM pro_logs_promotion WHERE member_no=? AND pro_id=? ORDER BY id DESC LIMIT 1";   
        break;      
    default:
      if ($member_promo != 0 && ($member_promo != 13 && $member_promo != 14)) { // ถ้ารับโปรมีรหัสโปรแต่เจอ case ไหน 
        $msg = "$member_promo ข้อมูลรายการถอนไม่ถูกต้อง กรุณาติดต่อ admin!";
        echojs("ไม่สามารถทำรายการได้ค่ะ", $msg, 2, "error");
        $error = 410;
      }
      break;
  }

if(in_array($member_promo,[19,20,22,21,34,37,112,101,113,114,125,140,141,142,143,144,145,146,147,150,151,152,153,157,179,180,181,182])){
  $res = $conn->query($sql, [$member_no,$member_promo]);
}else{
  $res = $conn->query($sql, [$member_no]);
}
  if ($res->num_rows() > 0) {
    $res = $res->row_array();
    $turnover_expect = (int)@$res['turnover_expect'];
    if ($game_turnover < $turnover_expect) {
      $msg = "ต้องมี" . $alertAmountText . " " . number_format($turnover_expect, 2, ".", ",") . "ขึ้นไป<BR>" . $alertAmountText . "ปัจจุบัน " . number_format($game_turnover, 2, ".", ",");
      if($member_promo!=0){
        $msg.="<BR><small style='color:#ff873f'>(เพื่อป้องกันการฟอกเงินจากมิจฉาชีพ)</small>";
      } 
      echojs($alertAmountText . "ยังไม่ถึงกำหนดที่จะทำรายการถอนได้ตามเงื่อนไข", $msg, 2, "error");
    }
  }
}


//valide_free50and60.php.php 
require_once ROOT . '/core/promotions/validates/valide_free50and60.php';

if ($error == 200 && $amount != 0) {
  try {
    if ($member_promo != 0) {
      $sql = "SELECT pro_symbol,channel FROM pro_promotion WHERE pro_id=? LIMIT 1;";
      $res_pro = $conn->query($sql, [$member_promo]);
      if ($res_pro->num_rows() > 0) {
        $item_pro = $res_pro->row_array();
        $remark = $item_pro['pro_symbol'];
      } else {
        $remark = 'อื่นๆ1';
      }
    } else {
      if ($member_last_deposit == 1) {
        $remark = 'Bank';
      } elseif ($member_last_deposit == 2) {
        $remark = 'TrueWallet';
      } elseif ($member_last_deposit == 3) {
        $remark =  'SMS';
      } elseif ($member_last_deposit == 4) {
        $remark = 'Pro 20%';
      } elseif ($member_last_deposit == 5) {
        $remark = 'Credited by Admin';
      } elseif ($member_last_deposit == 16) {
        $remark =  'AFF';
      } elseif ($member_last_deposit == 17) {
        $remark = 'Cashback';
      } elseif ($member_last_deposit == 18) {
        $remark = 'Commission';
      } elseif ($member_last_deposit == 8) {
        $remark = 'CardGame';
      } elseif ($member_last_deposit == 9) {
        $remark = 'LuckyWheel';
      } else {
        $remark = 'อื่นๆ2';
      }
    }
    if($member_last_deposit!=0){$channel = $member_last_deposit;}
    $conn->trans_start();
    $member_wallet = getMemberWallet($member_no);
    $before_credit = $member_wallet['main_wallet'];
    $after_credit = $member_wallet['main_wallet'] - $amount;
    if ($amount > $before_credit) {
      $msg = "ยอดเงินคงเหลือ " . number_format($before_credit, 2, ".", ",") . " บาท. น้อยกว่ายอดที่ต้องการถอน";
      echojs("ท่านไม่สามารถถอนเงินเกินจำนวนที่มีในกระเป๋าหลักได้ค่ะ", $msg, 2, "error");
      $error = 410;
    }  
    $data = [
      'member_no' => $member_no,
      'amount'=>$amount,
      'amount_actual' => $wd_actual_amount,
      'balance_before' => $before_credit,
      'balance_after' => $after_credit,
      'channel' => $channel, 
      'trx_id' => $trx_id,
      'trx_date' => Date('Y-m-d'),
      'trx_time' => date('H:i:s'),
      'status' => 2,
      'promo' => $member_promo,
      'remark' => $remark
    ]; 
    $conn->insert('log_withdraw', $data);
    adjustMemberWallet($member_no, $amount, 2);
    adjustWalletHistory('Withdraw', $member_no, $amount * (-1), $member_wallet['main_wallet'], $trx_id,$remark);
    $sql = "UPDATE members SET ignore_zero_turnover=0 WHERE id=?";
    $conn->query($sql, [$member_no]);
    $conn->trans_complete(); 
  } catch (Exception $e) {  // handle error
    $error = 500;  // echo $e->getMessage();
    $msg = "ไม่สำเร็จกรุณาลองอีกครั้ง";
    $conn->trans_rollback();
  }
}

if ($error == 200) {
  $key = 'DW_withdraw:' . $member_no;
  DelteCache($key);
  $key = 'DW_deposit:' . $member_no;
  DelteCache($key);
  echojs("แจ้งถอนเงิน", $msg, 1, "success");
} else {
  echojs("แจ้งถอนเงิน", $msg, 1, "error");
}
exit(0);
