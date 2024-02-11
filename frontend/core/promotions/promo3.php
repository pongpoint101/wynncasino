<?php
    $sql  = "SELECT * FROM log_deposit WHERE member_no=?";
    $sql .= " AND status=1 AND (channel<=3 OR channel=5)";
    $sql .= " ORDER BY trx_date DESC,trx_time DESC LIMIT 1";
    $res = $conn->query($sql,[$member_no]);
    if ($res->num_rows() <= 0) {
        $errCode = 402;
        $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    } else {
        $res = $res->row_array();
        if ($res['promo'] != (-1)) {
            $errCode = 402;
            $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
        }
        $trx_date = date_create($res['trx_date']);
        $dateNow = date_create(date('Y-m-d'));
        if ($trx_date->diff($dateNow)->format('%h') >= 3) {
            $errCode = 402;
            $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
        }
        $trx_id = $res['trx_id'];
        $sql = "SELECT * FROM promop10p WHERE ";
        $sql .= " member_no=? AND trx_id=?";
        if ($conn->query($sql,[$member_no,$trx_id])->num_rows() > 0) {
            $errCode = 402;
            $errMsg = "สมาชิกรับโบนัสจากยอดฝากรายการล่าสุดไปแล้วค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
        }
        $deposit_amount = (int)$res['amount'];
        $remark = 'Pro10%';
        $channel = 12;
        $promo_type = 1;
        $expect_turnover = 0.00;
        switch ($deposit_amount) {
            case 300:
                $promo_cal_result = 30; $promo_type = 1;
                break;
            case 500:
                $promo_cal_result = 50; $promo_type = 2;
                break;
            case 1000:
                $promo_cal_result = 100; $promo_type = 3; 
                break;
            case 3000:
                $promo_cal_result = 300; $promo_type = 4;
                break;
            case 5000:
                $promo_cal_result = 500; $promo_type = 5;
                break;
            default:
                $errCode = 402;
                $errMsg = "โปรฯ นี้สำหรับยอดฝาก 300 500 1,000 3,000 หรือ 5,000 เท่านั้นค่ะ";
                echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
                break;
        }
        $conn->query("UPDATE log_deposit SET promo=? WHERE id=?" ,[$promo_id,$res['id']]);
    }
    //Update others promo to completed status 
    $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
