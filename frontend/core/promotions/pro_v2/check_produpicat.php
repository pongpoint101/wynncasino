<?php
 $progroup_1allow=(isset($promo_detail['pro_group_to_1pro'])?explode(',',$promo_detail['pro_group_to_1pro']):[]);
 if(sizeof($progroup_1allow)>0){
       foreach ($progroup_1allow as $k => $pro_allow_id) {  
        $rowdata = $conn->query($sqlrepeat,[$member_no,$pro_allow_id])->row_array(); 
        if ($rowdata['count_total']> 0) { 
            $errCode = 402;
            $errMsg = "สมาชิกเคยรับ {$rowdata['pro_name']} ไปแล้ว<br>จะรับโปรฯ นี้ไม่ได้ค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
            break; 
         }
    } 
 } 
?>