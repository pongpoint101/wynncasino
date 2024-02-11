<?php

require '../bootstart.php';
require_once ROOT . '/core/security.php';
require ROOT . '/core/promotions/pro_data.php';
 

$promotion_list = [102, 3, 4, 5, 120, 121, 122, 51, 52, 83, 87, 90, 95]; //31 33 102
$promo_id = intval(preg_replace('/[^\d.]/', '', @$_POST['promo_id']));
$member_no = $_SESSION['member_no'];
$errCode = 200;
$errMsg = "";
$cal_percentage = 0.00;                      // % คำนวณโบนัส
$cal_bonus_percentage = 0.00;                      // % คำนวณโบนัส
$promo_max = 0.00;
$latest_deposit_amount = 0.00;               // ยอดรับโบนัสสูงสุด
$expect_turnover = 0.00;
$trx_id = uniqid();
$remark = '';
$promo_cal_result = 0;
$channel = 0;
$member_last_deposit = 0;
$deposit_id = '';

$mess ='';
    $row = getMemberWallet($member_no);
    $main_wallet = $row['main_wallet'];

    $sql1 = "SELECT pro_bonus_amount, pro_withdraw_max_amount, pro_turnover_amount FROM pro_promotion_detail WHERE pro_status=1 AND pro_id=?";
    $res_pro = $conn->query($sql1, [$promo_id]);
    $res_pro = $res_pro->row_array();

    switch ($promo_id) {
        case 10: //โปรฯ 50% สมัครใหม่ ฝาก 200 รับเพิ่ม 100 เป็น 300 - ทำยอดบวก 1 เท่า - ถอนสูงสด 1,500 - Slot/Fishing

            // - สมัครใหม่ รับโบนัส 50% สูงสุด 1,000 บาท<br>
            // - ฝากขั้นต่ำ 100 บาท<br>
            // - ทำยอดเทิร์น 20 เท่าขึ้นไป ถอนได้ทันที<br>
            // - โปรสมาชิกใหม่ เลือกรับได้แค่โปรเดียวเท่านั้น<br>
            // - 1 ยูส / 1 สิทธิ์<br>
            // - เฉพาะสมาชิกใหม่<br>
            // - รวมทุกเกม บอล คาสิโน สล็อต ยิงปลา ได้ทั้งหมด

            $sql = "SELECT amount FROM log_deposit WHERE status=1 AND channel IN(1,3,5) AND member_no=?";
            $sql .= " ORDER BY trx_date DESC,trx_time DESC";
            $res = $conn->query($sql, [$member_no]);
            $res = $res->row_array();
            
            $bonus = ($main_wallet+$res['amount']*$res_pro['pro_bonus_amount'])/100;
            $num = $main_wallet+$res['amount'];
            if($bonus > $res_pro['pro_withdraw_max_amount']){
               $tot = ($num+$res_pro['pro_withdraw_max_amount'])*$res_pro['pro_turnover_amount'];
            }else{
               $tot = ($num+$bonus)*$res_pro['pro_turnover_amount'];
            }
            $mess = 'ต้องมียอดเทิร์นถึง '. $tot .' ขึ้นไปถึงจะถอนได้';
    
            break;
        case 11: //โปรฯ 100% สมัครใหม่ ไม่เกิน 200 ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000 - Slot/Fishing

            // - ฝากครั้งถัดไป รับโบนัส 10% สูงสุด 2,000 บาท<br>
            // - ฝากขั้นต่ำ 100 บาท<br>
            // - ทำยอดเทิร์น 10 เท่าขึ้นไป ถอนได้ทันที<br>
            // - สามารถรับได้ทุกครั้งที่ทำรายการฝาก<br>
            // - รวมทุกเกม บอล คาสิโน สล็อต ยิงปลา ได้ทั้งหมด
            $sql = "SELECT amount FROM log_deposit WHERE status=1 AND channel IN(1,3,5) AND member_no=?";
            $sql .= " ORDER BY trx_date DESC,trx_time DESC";
            $res = $conn->query($sql, [$member_no]);
            $res = $res->row_array();
            
            $bonus = ($main_wallet+$res['amount']*$res_pro['pro_bonus_amount'])/100;
            $num = $main_wallet+$res['amount'];
            if($bonus > $res_pro['pro_withdraw_max_amount']){
               $tot = ($num+$res_pro['pro_withdraw_max_amount'])*$res_pro['pro_turnover_amount'];
            }else{
               $tot = ($num+$bonus)*$res_pro['pro_turnover_amount'];
            }
            $mess = 'ต้องมียอดเทิร์นถึง '. $tot .' ขึ้นไปถึงจะถอนได้';

            break;
        case 12: // ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing
            break;
        case 101: //  โปรฝากประจำ รับโบนัส 10% ทั้งวัน 
            // เฉพาะยอดฝาก 100 1,000 2,000<br>
            // ทำยอดเทิร์นโอเวอร์ 5 เท่าถอนได้เลย สูงสุด 3,000 บาท<br>
            // ***เกมส์สล็อตเท่านั้น***
            $sql = "SELECT amount FROM log_deposit WHERE status=1 AND channel IN(1,3,5) AND member_no=?";
            $sql .= " ORDER BY trx_date DESC,trx_time DESC";
            $res = $conn->query($sql, [$member_no]);
            $res = $res->row_array();
            
            $bonus = ($main_wallet+$res['amount']*$res_pro['pro_bonus_amount'])/100;
            $num = $main_wallet+$res['amount'];
            if($bonus > $res_pro['pro_withdraw_max_amount']){
               $tot = ($num+$res_pro['pro_withdraw_max_amount'])*$res_pro['pro_turnover_amount'];
            }else{
               $tot = ($num+$bonus)*$res_pro['pro_turnover_amount'];
            }
            $mess = 'ต้องมียอดเทิร์นถึง '. $tot .' ขึ้นไปถึงจะถอนได้';
            break;
        case 102:
        case 103:
        case 104:
        case 105:
        case 106:  // Happy time ช่วงเช้า 12.00-13.00 ฝาก 200 รับเพิ่ม 50 บาท & รอบดึก 00.00-01.00 ฝาก 400 รับเพิ่ม 100 บาท
        case 107:
        case 108: // ขาประจำ เราจัดให้
        case 109:
        case 110:
        case 111:
        case 83: ///ฝากประจำต่อเนื่อง 
        case 87:
        case 90:
        case 95:
        case 50:  //ฝากแรกของวัน ฝาก 300 รับ 50 เป็น 350 - เทิร์น 5 เท่า - ถอนไม่อั้น casino
            // ฝากแรกของวัน ฝาก 300 รับเพิ่มอีก 50<br>
            // เทิร์นโอเวอร์ 5 เท่า ถอนได้ไม่อั้น<br>
            // *** คาสิโนเท่านั้น ***
            $tot = 350*5;
            $mess = 'ต้องมียอดเทิร์นถึง '. $tot .' ขึ้นไปถึงจะถอนได้';
            break;
        case 51: // รับโบนัส 2% ทั้งวัน - เทิร์น 5 เท่า - สูงสุด 1,000 - ถอนไม่อั้น casino
        case 19: // ยอดการเล่น 5 อันดับสูงสุดรายวัน
        case 25: // ต้อนรับสมาชิกใหม่ ฝากครั้งแรกเอาไป 150%
        case 20:  //Happy New Year
        case 21: //Welcome back
        case 22: //Merry Christmas 	  
        default:
            break;
    }
   echo $mess;

?>