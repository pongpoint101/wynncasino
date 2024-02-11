<?php  
$channel = 83; 
$rewardpro =GetDatapro('deposit_freq');

$loopfreq=$promo_id-80;   
$pro_opend=false; 
$promo_cal_result=0;$remark = ''; 
foreach ($rewardpro as $k => $v) {
   if($v['status']==1&&$loopfreq==$k) {$promo_cal_result=$v['reward'];$remark =$v['remark'];$pro_opend=true;}  
}
if (!$pro_opend) {
    $errCode = 404; 
    $errMsg = "หมดเวลารับโปรโมชั่นหรือยกเลิกโปรโมชั่นนี้แล้วคะ! $errCode"; 
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}  
if ($main_wallet> 10) {
    $errCode = 403;
    $errMsg = "สมาชิกไม่สามารถรับโปรฯ ได้ เนื่องจากยอดฝากมากกว่าที่กำหนด (10 บ.)";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
} 

$sql = "SELECT 1 FROM promo_deposit_frequency WHERE member_no=? AND accept_date=SUBDATE(CURDATE(),0)";
$res = $conn->query($sql,[$member_no]);
if ($res->num_rows() != 0) {
    $errCode = 402;
    $errMsg = "ท่านสมาชิกรับโบนัสสำหรับวันนี้ไปแล้วค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}
$sql  = " CALL spc_deposit_frequency($member_no,500);";    
$mysqli = new mysqli(DBHost,DBUser, DBPassword,DBName,DBPort);
$res=[];
if ($result = $mysqli -> query($sql)) {
    $res=$result->fetch_all(MYSQLI_ASSOC); 
    $result->free_result();
} 
$mysqli->close();   
if (sizeof($res) <= 0) {
    $errCode = 402;
    $errMsg = "สมาชิกยังไม่มีรายการที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}    

$countfreq=0;$checkfreq=0;$list_trx_id=[]; 
for ($i=0; $i < 15; $i++) {  
    if(isset($res[$i])){
       $vv=$res[$i]; 
        if ($checkfreq==0&&$vv['daydiff']!=1) {
            $checkfreq=1; 
        }
        if ($checkfreq==0) {
        $countfreq++;
        $list_trx_id[]=$vv['id'];
       } 
     }
  } 
if ($countfreq==0||$countfreq< $loopfreq) {
    $errCode = 403;
    $errMsg = "สมาชิกยังไม่มีรายการฝากประจำที่ตรงกับเงื่อนไขสำหรับการรับโปรฯ นี้เข้ามาค่ะ $errCode";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
}   
$turnover_expect = ($promo_cal_result) * 2; 

$data =[
    'member_no' => $member_no,
    'deposit_amount' => 0,
    'promo_amount' => $promo_cal_result, // amount = promo_amount
    'turnover_expect' => $turnover_expect,
    'promo' => $promo_id
   ];   
$conn->insert('promo_deposit_frequency', $data);
if ($conn->affected_rows()<=0) {
    $errCode = 402;
    $errMsg = "เคยรับโบนัสในวันนี้แล้ว!";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด    
} 
$conn->set('promo', $promo_id);
$conn->where('member_no', $member_no);
// $conn->where('status=1 AND (channel<=3 OR channel=5) AND promo=-1', "", false);  
$conn->where_in('id', $list_trx_id);  
$conn->update('log_deposit'); 

$conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
$conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
