    <?php 
    $pro_deposit_first_day=2;
     require_once ROOT.'/core/promotions/pro_v2/promo_all_use.php'; 
     if ($res->num_rows() <= 0) {
         $errCode = 402;
         $errMsg = "สมาชิกยังไม่มีรายการฝากเข้ามาค่ะ";
         echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
     }else {    
     if ($res->num_rows() >= 1) { 
        require_once ROOT.'/core/promotions/pro_v2/promo_turn_bonus.php';
        $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$deposit_id]);  
        $data =[
            'member_no' => $member_no,
            'deposit_amount' => $latest_deposit_amount,
            'promo_amount' =>$promo_cal_result,
            'turnover_expect' => $expect_turnover,
            'status' => 1,
            'accept_date' => date('Y-m-d H:i:s')
        ];  
        $conn->insert('promop120', $data); 
        //Update others promo to completed status preventing confusing 
        $conn->query("UPDATE promofree50 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
        $conn->query("UPDATE promofree60 SET status=2 WHERE status!=2 AND member_no=?",[$member_no]);
    } else {
        $errCode = 402;
        $errMsg = "สมาชิกยังไม่มีรายการฝากที่สามารถรับโปรฯ นี้ได้ค่ะ";
        echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด 
    }
    } 
