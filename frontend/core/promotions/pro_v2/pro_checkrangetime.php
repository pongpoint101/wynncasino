<?php  
$current_datetime = date_create(date('Y-m-d H:i:s')); 
$time_1_start = '';
$time_1_end   = '';  
//เช็คช่วงเวลาโปร 
$list_allow_txt=''; 
 
$sqltime = "SELECT * FROM pro_promotion_detail WHERE pro_id=? AND pro_status=1 ORDER BY m_order;"; 
$datakey = 'promo:' . $promo_id;
$promo_local_time_detail = GetDataSqlWhereOne($datakey, $sqltime, [$promo_id], 5 * 60); 
$allowGetPromo = 0; 
$time_promo_local_detail=$promo_local_time_detail;

$time_start_pro=date('H:i:s',strtotime($promo_local_time_detail['pro_deposit_start_date']));
$time_end_pro=date('H:i:s',strtotime($promo_local_time_detail['pro_deposit_end_date']));
$time_1_start = date_create(date('Y-m-d').' '.$time_start_pro);
$time_1_end   = date_create(date('Y-m-d').' '.$time_end_pro);   

$list_allow_txt.=$time_start_pro.'-'.$time_end_pro.' น. '; 
if (($current_datetime >= $time_1_start) && ($current_datetime <= $time_1_end)) {   
    $allowGetPromo = 1;  
}   
if ($allowGetPromo == 0) {
    $errCode = 402;
    $errMsg = "โปรฯ นี้ สามารถรับได้เฉพาะช่วงเวลา $list_allow_txt เท่านั้นค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} 
if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} else { 
    $restime = $res->row_array(); 
    //เช็คยอดเงินฝาก   
    $time_start_pro=date('H:i:s',strtotime($time_promo_local_detail['pro_deposit_start_date']));
    $time_end_pro=date('H:i:s',strtotime($time_promo_local_detail['pro_deposit_end_date']));
    $time_1_start = date_create(date('Y-m-d').' '.$time_start_pro);
    $time_1_end   = date_create(date('Y-m-d').' '.$time_end_pro);   
   //เช็คช่วงเวลาฝาก
    $allowGetPromo = 0;
    $restime['create_date'] = date_create($restime['trx_date'].' '.$restime['trx_time']); 
    if (($restime['create_date'] >= $time_1_start) && ($restime['create_date'] <= $time_1_end)) {
        $allowGetPromo = 1; 
    }
    if ($allowGetPromo == 0) {
        $errCode = 402;
        $errMsg = "โปรฯ นี้ ใช้สำหรับยอดฝากในช่วงเวลา $list_allow_txt เท่านั้นค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }  
}
 
