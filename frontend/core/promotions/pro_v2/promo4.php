<?php  
$current_datetime = date_create(date('Y-m-d H:i:s')); 
$time_1_start = '';
$time_1_end   = '';  
//เช็คช่วงเวลาโปร
$list_allow_txt=''; 

$sql = "SELECT * FROM pro_promotion_detail WHERE pro_group_id=? AND pro_status=1 ORDER BY m_order;";  
$datakey='promo:local:'.$pro_group_id; 
$promo_local_detail=GetDataSqlWhereAll($datakey,$sql,[$pro_group_id],5*60);
$allowGetPromo = 0;
$filter_promo_local_detail=[];
foreach ($promo_local_detail as $k => $v) { 
    $time_start_pro=date('H:i:s',strtotime($v['pro_deposit_start_date']));
    $time_end_pro=date('H:i:s',strtotime($v['pro_deposit_end_date']));
    $time_1_start = date_create(date('Y-m-d').' '.$time_start_pro);
    $time_1_end   = date_create(date('Y-m-d').' '.$time_end_pro);  
    if($k>0){$list_allow_txt.=' และ ';}
    $list_allow_txt.=$time_start_pro.'-'.$time_end_pro.' น. '; 
    if (($current_datetime >= $time_1_start) && ($current_datetime <= $time_1_end)) {  
        $remark = 'HappyTime-0'.($k+1); 
        $allowGetPromo = 1;
        $filter_promo_local_detail=$v; 
        $status = $k+1;
    }   
}
if ($allowGetPromo == 0) {
    $errCode = 402;
    $errMsg = "โปรฯ นี้ สามารถรับได้เฉพาะช่วงเวลา $list_allow_txt เท่านั้นค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
if ($res->num_rows() <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} else { 
    $res = $res->row_array(); 
    //เช็คยอดเงินฝาก   
    $time_start_pro=date('H:i:s',strtotime($filter_promo_local_detail['pro_deposit_start_date']));
    $time_end_pro=date('H:i:s',strtotime($filter_promo_local_detail['pro_deposit_end_date']));
    $time_1_start = date_create(date('Y-m-d').' '.$time_start_pro);
    $time_1_end   = date_create(date('Y-m-d').' '.$time_end_pro); 

    $pro_id=$filter_promo_local_detail['pro_id']; 
    $pro_group_id=$filter_promo_local_detail['pro_group_id']; 
    $pro_name=$filter_promo_local_detail['pro_name'];
    $channel = $filter_promo_local_detail['pro_id'];
    $remark = $filter_promo_local_detail['pro_symbol'];  
    $promo_id=$pro_id; 
   
   //เช็คช่วงเวลาฝาก
    $allowGetPromo = 0;
    $res['create_date'] = date_create($res['trx_date'].' '.$res['trx_time']); 
    if (($res['create_date'] >= $time_1_start) && ($res['create_date'] <= $time_1_end)) {
        $allowGetPromo = 1; 
    }
    if ($allowGetPromo == 0) {
        $errCode = 402;
        $errMsg = "โปรฯ นี้ ใช้สำหรับยอดฝากในช่วงเวลา $list_allow_txt เท่านั้นค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php'; 
    $data =[
        'member_no' => $member_no,
        'channel'=>$channel,
        'trx_id' => $deposit_id,
        'promo_date' =>date('Y-m-d'), 
        'win_expect' => $win_expect,
        'status' => $status
       ]; 
 
      try {
        $conn->insert('promo_happy_time', $data); 
        if ($conn->affected_rows() <= 0) {
           $errCode = 402;
           $errMsg = "ท่านสมาชิกใช้สิทธิ์สำหรับรับโปรฯ ในช่วงเวลานี้ไปแล้วค่ะ";
           echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
         }
      } catch (\Throwable $th) {
        $errCode = 402;
        $errMsg = "ท่านสมาชิกใช้สิทธิ์สำหรับรับโปรฯ ในช่วงเวลานี้ไปแล้วค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }  
    $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]);

    //Update others promo to completed status 
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);

}
 
