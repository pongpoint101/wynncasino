<?php
     require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
    if ($res->num_rows() <= 0) {
        $errCode = 402;
        $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    } else {
        $res = $res->row_array(); 
        $trx_date = date_create($res['trx_date']);
        $dateNow = date_create(date('Y-m-d'));
        if ($trx_date->diff($dateNow)->format('%h') >= $pro_deposit_expire) {
            $errCode = 402;
            $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
        } 
        // $sql = "SELECT * FROM promop10p WHERE ";
        // $sql .= " member_no=? AND trx_id=?";
        // if ($conn->query($sql,[$member_no,$deposit_id])->num_rows() > 0) {
        //     $errCode = 402;
        //     $errMsg = "สมาชิกรับโบนัสจากยอดฝากรายการล่าสุดไปแล้วค่ะ";
        //     echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
        // } 
        $promo_type = 0;
        $macth_pro=true;$list_allow_txt='';

        $sql = "SELECT * FROM pro_promotion_detail WHERE pro_group_id=? AND pro_status=1 ORDER BY pro_deposit_start_amount;";  
        $datakey='promo:local:'.$pro_group_id; 
        $promo_local_detail=GetDataSqlWhereOne($datakey,$sql,[$pro_group_id],5*60);
        $channel = $promo_local_detail['channel'];
        require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';
        $conn->query("UPDATE log_deposit SET promo=? WHERE id=?" ,[$promo_id,$res['id']]);   
        
        $data =[
            'member_no' => $member_no,
            'pro_id'=>$channel,
            'trx_id' => $res['trx_id'],
            'status' => 1,
            'type' => $promo_type,
            'update_by' =>'SYSTEM',
            'arrival_date' => date('Y-m-d H:s:i'),
            'win_expect' =>($expect_turnover)
           ]; 
         $conn->insert('promop10p', $data); 
    }
    //Update others promo to completed status 
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
