<?php

require '../bootstart.php';
require_once ROOT . '/core/security.php';
require ROOT . '/core/promotions/pro_data.php';
$member_no = $_SESSION['member_no'];
if (isset($limiter)) {
    $limiterns = $limiter->hit('requestproclaim:' .$member_no, 1, 5); // เรียกได้ 1 ครั้งใน 5 วินาที
    if ($limiterns['overlimit']) {
        InSertLogSys(['member_no'=>$member_no,'ip'=>getIP(),'log_type'=>'promo','txt_data'=>date('Y-m-d').' '.date('H:i:s')]);
        exit(0);
    }
}
$Website = GetWebSites(); 
$min_autodeposit=(isset($Website['min_auto_deposit'])?$Website['min_auto_deposit']:10);
$promotion_list = [102, 3, 4, 5, 120, 121, 122, 51, 52, 83, 87, 90, 95]; //31 33 102
$promo_id = intval(preg_replace('/[^\d.]/', '', @$_POST['promo_id']));

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
$allow_channel='1,3,5';
$channel = 0;
$member_last_deposit = 0;
$deposit_id = '';

if(@$Website['truewallet_is_bonus']==2){
    $allow_channel='1,2,3,5';
}
$sql = "SELECT * FROM pro_promotion_detail WHERE pro_id=? AND pro_status=1 ORDER BY id;";
$datakey = 'promo:' . $promo_id;
$promo_detail = GetDataSqlWhereOne($datakey, $sql, [$promo_id], 5 * 60);
if (!isset($promo_detail['pro_turnover_type'])) {
    $errCode = 402;
    $errMsg = "หมดเวลารับโปรโมชั่นหรือยกเลิกโปรโมชั่นนี้แล้วคะ";
    echojs("ผิดพลาด", $errMsg, 1, "error");
}


$pro_bonus_type = $promo_detail['pro_bonus_type'];
$pro_bonus_amount = $promo_detail['pro_bonus_amount'];
$pro_turnover_type = $promo_detail['pro_turnover_type'];
$pro_turnover_amount = $promo_detail['pro_turnover_amount'];
$promo_max = $promo_detail['pro_bonus_max'];    // ยอดรับโบนัสสูงสุด 
$remark = $promo_detail['pro_symbol'];
$pro_deposit_type = $promo_detail['pro_deposit_type'];
$pro_deposit_fix = $promo_detail['pro_deposit_fix']; 
$pro_start= $promo_detail['pro_start'];
$pro_end= $promo_detail['pro_end'];
$pro_deposit_start_amount= $promo_detail['pro_deposit_start_amount'];
$pro_deposit_end_amount= $promo_detail['pro_deposit_end_amount'];

$pro_detail_id = $promo_detail['id'];
$pro_id = $promo_detail['pro_id'];
$channel = $promo_detail['channel'];
$pro_group_id = $promo_detail['pro_group_id'];
$pro_is_group = $promo_detail['pro_is_group'];
$pro_name = $promo_detail['pro_name'];
$pro_cat_id = $promo_detail['pro_cat_id'];
$pro_cat_name = $promo_detail['pro_cat_name'];
$pro_deposit_expire = $promo_detail['pro_deposit_expire'];
$pro_repeat = $promo_detail['pro_repeat'];
$pro_max_repeat = $promo_detail['pro_max_repeat'];
$pro_withdraw_max_amount=$promo_detail['pro_withdraw_max_amount'];
$pro_id_more=null;
$pro_weekly_day=$promo_detail['pro_weekly_day'];
if($pro_bonus_type==2){$cal_bonus_percentage = ($pro_bonus_amount / 100);}
if ($pro_turnover_type == 2||$pro_turnover_type == 4) { //1 turnoverจากยอดเล่นจำนวนเต็ม ,2 turnover จากยอดเล่นจำนวนเท่า ,3 ยอดเครดิตจำนวนเต็ม,4 เครดิตจำนวนเท่า
    $cal_percentage = ($pro_turnover_amount / 100);
}

$row = getMemberWallet($member_no);
$main_wallet = $row['main_wallet'];

$res = $conn->query("SELECT member_promo,member_last_deposit FROM members WHERE id=?", [$member_no])->row_array();
$member_promo = $res['member_promo'];
$member_last_deposit = $res['member_last_deposit'];
$sql = "SELECT id FROM log_deposit WHERE status=2 AND channel<=3 AND member_no=?;"; // AND trx_date < CURRENT_DATE()";
if ($conn->query($sql, [$member_no])->num_rows() > 0) {
    $errCode = 403;
    $errMsg = "สมาชิกไม่สามารถรับโปรฯ นี้ได้ เนื่องจากมียอดฝากค้าง";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด  
}
$allow_money=true;
if($pro_deposit_type==4){ $allow_money=false;}
if(!in_array($promo_id, [19,179,180,181, 83, 87, 90, 95,182])&&$allow_money){
//$skipChkBalance && $skipChkBalance != 0
if (($row['main_wallet'] < $min_autodeposit) && !in_array($promo_id, [19,179,180,181, 83, 87, 90, 95])) {
    $errCode = 403;
    $errMsg = "สมาชิกไม่สามารถรับโปรฯ ใดๆ ได้ เนื่องจากยอดฝากต่ำกว่าที่กำหนด ($min_autodeposit บ.)";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

$sql = "SELECT id FROM log_deposit WHERE status=2 AND channel<=3 AND member_no=?;"; // AND trx_date < CURRENT_DATE()";
if ($conn->query($sql, [$member_no])->num_rows() > 0) {
    $errCode = 403;
    $errMsg = "สมาชิกไม่สามารถรับโปรฯ นี้ได้ เนื่องจากมียอดฝากค้าง";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด  
}

if ($member_promo == 13 || $member_promo == 14) {
    $errCode = 402;
    $errMsg = "สมาชิกรับเครดิตฟรี ไม่สามารถรับโบนัสใด ๆ ได้ค่ะ";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

if ($member_last_deposit == 16 || $member_last_deposit == 17 || $member_last_deposit == 18) {
    $errCode = 402;
    $errMsg = "ยอดเงินจากค่าแนะนำเพื่อน ไม่สามารถรับโบนัสใด ๆ ได้ค่ะ";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

// $memberTurnover = $conn->query("SELECT * FROM member_turnover WHERE outstanding_turnover > 0 AND member_no=?", [$member_no]);
$memberTurnover = $conn->query("SELECT * FROM member_turnover_product WHERE current_turnover > 0 AND member_no=?", [$member_no]);
if ($memberTurnover->num_rows() > 0) {
    $errMsg = "ท่านไม่สามารถรับโบนัสได้ เนื่องจากมียอดเล่นไปแล้ว<BR>หากต้องการรับโบนัส กรุณากดรับหลังจากฝากเงินทันทีหรือก่อนเริ่มเล่นเท่านั้น";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // 
}
if ($member_last_deposit == 2&&$Website['truewallet_is_bonus']!=2) {
    $errCode = 402;
    $errMsg = "ยอดฝากจาก True Wallet ไม่สามารถรับโบนัสได้นะคะ";
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}

$sql = "SELECT promo FROM log_deposit WHERE status=1 AND channel IN($allow_channel) AND member_no=?";
$sql .= " ORDER BY trx_date DESC,trx_time DESC";
$res = $conn->query($sql, [$member_no]);

if ($res->num_rows() >= 1 && !in_array($promo_id, [19, 83, 87, 90, 95])) {
    $res = $res->row_array();
    if ($res['promo'] != (-1)) {
        $errCode = 402;
        $errMsg = "รายการฝากล่าสุด เคยใช้สำหรับรับโบนัสไปแล้วค่ะ";
        echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
}
}
// เช็ครับได้กี่ครั้ง // 1. รับได้ครั้งเดียว 2. รับได้วันละกี่ครั้ง 3.รับได้ตลอด,4.รับได้เดือนละกี่ครั้ง 5.รับได้ปีละกี่ครั้ง 6.เครดิตฟรี

require ROOT . '/core/promotions/pro_v2/check_pro_repeat.php';
require ROOT.'/core/promotions/pro_v2/check_produpicat.php'; 
switch ($promo_id) { 
    case 182: // สำหรับลูกค้าที่มีประวัติฝากเงิน 20 บาทขึ้นไป      
    require ROOT . '/core/promotions/pro_v2/promo182.php';  break;
    case 153: // กิจกรรม แคปปุ๊บ รับปั๊บ	
    case 140: //โปร 1	
    case 141: //โปร 2
    case 142: //โปร 3	
    case 143: //โปร 4		
    case 144: //โปร 5
    case 145: //โปร 6	
    case 146: //โปร 7		
    case 147: //8.8 แจกสนั่น 8 วันติด
    case 101: // โปรฝากประจำ รับโบนัส 10% ทั้งวัน        
     require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;   
    case 37: // ฝากประจำ
        if(!(date('Y-m-d')>='2022-05-01'&&date('Y-m-d')<='2022-05-10')){
            $errCode = 402;
            $errMsg = "ระยะเวลารับโบนัส 01/05/2023 เวลา 00.00 ถึง 10/05/2023 เวลา 23.59";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
       }
     require ROOT . '/core/promotions/pro_v2/promo35.php';
    break;
    case 12: // ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing
        require ROOT . '/core/promotions/pro_v2/promo52.php';
    break;
    case 34: //โปรสงกรานต์สาดกระจายส์		
        if(!in_array(date('Y-m-d'),['2023-04-29','2023-04-30','2023-05-01'])){
            $errCode = 402;
            $errMsg = "ระยะเวลารับโบนัส 29/4/2023 เวลา 00.00 ถึง 1/5/2023 เวลา 23.59";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
       }
    require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;
    case 125: //โปรสงกรานต์สาดกระจายส์		
        if(!in_array(date('m-d'),['04-13','04-14','04-15'])){
            $errCode = 402;
            $errMsg = "โบนัสหมดช่วงเวลารับแล้วค่ะ";
            echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
      }
    require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;
    case 123: //ฝากแรก รับ 100%
    $pro_deposit_first_day=2;  
    require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break; 
    case 32: //ทำโปรฝาก 100 รับเพิ่มไปอีก100  ทั้งวัน
     require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;
   case 112: //ทำโปรฝาก 100 รับเพิ่มไปอีก100  ทั้งวัน
        require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;
   case 113: //ทำโปรฝาก 100 รับเพิ่มไปอีก100  ทั้งวัน
       require ROOT . '/core/promotions/pro_v2/promo_all_check.php';  break;  
    case 10: //โปรฯ 50% สมัครใหม่ ฝาก 200 รับเพิ่ม 100 เป็น 300 - ทำยอดบวก 1 เท่า - ถอนสูงสด 1,500 - Slot/Fishing
        $pro_deposit_first_day=2;  
        require ROOT . '/core/promotions/pro_v2/promo102.php';
        break;
    case 11: //โปรฯ 100% สมัครใหม่ ไม่เกิน 200 ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000 - Slot/Fishing
        require ROOT . '/core/promotions/pro_v2/promo2.php';
        break;
    case 12: // ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing
        require ROOT . '/core/promotions/pro_v2/promo52.php';
        break;
    // case 101: //  โปรฝากประจำ รับโบนัส 10% ทั้งวัน 
    // case 102:
    // case 103:
    // case 104:
    // case 105:
    //     require ROOT . '/core/promotions/pro_v2/promo3.php';
    //     break;
    case 106:  // Happy time ช่วงเช้า 12.00-13.00 ฝาก 200 รับเพิ่ม 50 บาท & รอบดึก 00.00-01.00 ฝาก 400 รับเพิ่ม 100 บาท
    case 107:
        require ROOT . '/core/promotions/pro_v2/promo4.php';
        break;
    case 108: // ขาประจำ เราจัดให้
    case 109:
    case 110:
    case 111:
        require ROOT . '/core/promotions/pro_v2/promo5.php';
        break;
    case 83: ///ฝากประจำต่อเนื่อง 
    case 87:
    case 90:
    case 95:
        require ROOT . '/core/promotions/pro_v2/promo_deposit_frequency.php';
        break;
    case 50:  //ฝากแรกของวัน ฝาก 300 รับ 50 เป็น 350 - เทิร์น 5 เท่า - ถอนไม่อั้น casino
        require ROOT . '/core/promotions/pro_v2/promo120.php';
        break;
    case 51: // รับโบนัส 2% ทั้งวัน - เทิร์น 5 เท่า - สูงสุด 1,000 - ถอนไม่อั้น casino
        require ROOT . '/core/promotions/pro_v2/promo121.php';
        break;
    case 19: // ยอดการเล่น 5 อันดับสูงสุดรายวัน
        // if(date('Y-m-d')<'2023-11-30'||true){
        //     $errCode = 402;
        //     $errMsg = "โบนัสยังไม่เปิดกรุณาติดต่อแอดมิน";
        //     echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
        // }
        $is_reward=1;//pro_bonus_amount=ยอดเงินโบนัส อันนี้เป็นรางวัลพิเศษจะเท่ากับ pro_bonus_amount ทันที 
        require ROOT . '/core/promotions/pro_v2/promo_reward_daily_turover.php';
        break;
    case 179: // ยอดการเล่น 5 อันดับสูงสุดรายวัน 
        $is_reward=1;//pro_bonus_amount=ยอดเงินโบนัส อันนี้เป็นรางวัลพิเศษจะเท่ากับ pro_bonus_amount ทันที 
        require ROOT . '/core/promotions/pro_v2/promo_reward_daily179.php';
      break;
    case 180: // ยอดการเล่น 5 อันดับสูงสุดรายวัน 
        $is_reward=1;//pro_bonus_amount=ยอดเงินโบนัส อันนี้เป็นรางวัลพิเศษจะเท่ากับ pro_bonus_amount ทันที 
       require ROOT . '/core/promotions/pro_v2/promo_reward_daily180.php';
    break;
    case 181: // ยอดชนะ 5 อันดับสูงสุดรายวัน 
        $is_reward=1;//pro_bonus_amount=ยอดเงินโบนัส อันนี้เป็นรางวัลพิเศษจะเท่ากับ pro_bonus_amount ทันที 
       require ROOT . '/core/promotions/pro_v2/promo_reward_daily181.php';
    break; 
    case 25: // ต้อนรับสมาชิกใหม่ ฝากครั้งแรกเอาไป 150%
        require ROOT . '/core/promotions/pro_v2/promo_newUser150.php';
        break;  
    case 20:  //Happy New Year
        require ROOT . '/core/promotions/pro_v2/promo_hny50.php';
      break; 
    case 21: //Welcome back
    require ROOT . '/core/promotions/pro_v2/promo_wb.php';
    break; 
    case 22: //Merry Christmas 	  
        require ROOT . '/core/promotions/pro_v2/promo_mc.php';
      break; 

    default:
        break;
}
if ($channel == 0 || $promo_cal_result <= 0 || $promo_id == 0) {
    $errMsg = 'ไม่พบข้อมูลโปรฯ !';
    echojs("ผิดพลาด", $errMsg, 1, "error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
$member_wallet = getMemberWallet($member_no);   // return Array
$main_wallet = $member_wallet['main_wallet'];
$bal_remain = adjustMemberWallet($member_no, $promo_cal_result, 1);
adjustWalletHistory('Promo-' . $promo_id, $member_no, $promo_cal_result, $member_wallet['main_wallet']);

$data = [
    'member_no' => $member_no,
    'balance_before'=>$main_wallet,
    'amount' => $promo_cal_result,
    'channel' => $channel,
    'openration_type' => $channel,
    'trx_id' => uniqid(),
    'trx_date' => date('Y-m-d'),
    'trx_time' => date('H:i:s'),
    'status' => 1,
    'remark' => $remark,
    'promo' => $promo_id
];
if(@sizeof($deposit_list_id)>0){
    $conn->set('promo',$promo_id);
    $conn->where_in('id', $deposit_list_id); 
    $conn->update('log_deposit'); 
}
$conn->insert('log_deposit', $data);
$conn->query("UPDATE members SET member_promo=? WHERE id=?", [$promo_id, $member_no]);
$conn->query("UPDATE member_turnover_product SET current_turnover=0 WHERE member_no=?", [$member_no]);
$data = [
    'member_no' => $member_no,
    'promo_id' => $promo_id,
    'amount' => $promo_cal_result
];
$conn->insert('log_promo', $data);

$pro_withdraw_accept=-1;   
 if ($pro_withdraw_max_amount != -1&&$promo_detail['pro_withdraw_type']== 2) { 
    $pro_withdraw_accept = (($main_wallet)+$promo_cal_result) * (($pro_withdraw_max_amount==1)?2:$pro_withdraw_max_amount);  
 }
 switch ($promo_id) {
    case 19: $conn->query('UPDATE rewardmaxplayer_daily SET status=2,turnover_expect=? WHERE status!=2 AND id=?', [$expect_turnover, $reward_id]);   break;
    case 179: $conn->query('UPDATE rewardaff_winner_daily SET status=2,turnover_expect=? WHERE status!=2 AND id=?', [$expect_turnover, $reward_id]);   break;
    case 180: $conn->query('UPDATE rewardcashback_daily SET status=2,turnover_expect=? WHERE status!=2 AND id=?', [$expect_turnover, $reward_id]);   break; 
    case 181: $conn->query('UPDATE rewardmaxwinner_player_daily SET status=2,turnover_expect=? WHERE status!=2 AND id=?', [$expect_turnover, $reward_id]); break; 
}
$data = [
    'member_no' => $member_no,
    'trx_id' => $trx_id,
    'deposit_id' => $deposit_id,
    'pro_id' => $pro_id,
    'pro_id_more'=>$pro_id_more,
    'pro_group_id' => $pro_group_id,
    'pro_symbol' => $remark,
    'pro_name' => $pro_name,
    'pro_cat_id' => $pro_cat_id,
    'pro_cat_name' => $pro_cat_name,
    'turnover_expect' => $expect_turnover,
    'pro_withdraw_accept'=>$pro_withdraw_accept,
    'promo_amount' => $promo_cal_result,
    'create_by' => $member_no,
    'pro_repeat'=>$pro_repeat,
    'status' => 0
];
$conn->insert('pro_logs_promotion', $data);

$errMsg = "ท่านได้รับโบนัสเป็นจำนวน " . number_format($promo_cal_result, 2, ".", ",") . " เครดิต";
switch ($channel) { 
        case 19:
            ?>
            <script type="text/javascript">
                $(function() {
                    App.ReWardtabs('maxplayer');
                });
            </script>
            <?php
     break;
        case 179:
            ?>
            <script type="text/javascript">
                $(function() {
                    App.ReWardtabs('aff');
                });
            </script>
            <?php
          break;
        case 180:
            ?>
            <script type="text/javascript">
                $(function() {
                    App.ReWardtabs('cashbackplayer');
                });
            </script>
            <?php
          break; 
          case 181:
            ?>
            <script type="text/javascript">
                $(function() {
                    App.ReWardtabs('maxwinnerplayer');
                });
            </script>
            <?php
          break;             
    default:
        break;
}
echojs("ยินดีด้วยค่ะ", $errMsg, 1, "success"); // ยินดีด้วยค่ะ ผิดพลาด    
?>