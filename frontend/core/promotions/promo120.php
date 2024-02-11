    <?php 
    $sql = "SELECT 1 FROM promop120 WHERE member_no=? AND DATE_FORMAT(accept_date, '%Y-%m-%d') = CURDATE() LIMIT 1";
    $res = $conn->query($sql,[$member_no]);
    if ($res->num_rows()> 0) {
    $errCode = 402;
    $errMsg = "สมาชิกรับโปรนี้ได้วันละ 1 ครั้งต่อวันค่ะ";
    echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
    } else { 
    $cal_percentage = 1.0;
    $promo_max = 300;    // ยอดรับโบนัสสูงสุด
    $expect_turnover = 0.00;
    $turnover_multiplier = 5;
    $remark = 'P120';
    $channel = 120;
    $sql = "SELECT * FROM log_deposit WHERE status=1 AND (channel<=3 OR channel=5) AND member_no=?";
    $sql .= " AND DATE_FORMAT(trx_date, '%Y-%m-%d') = CURDATE()";
    $sql .= " ORDER BY trx_date ASC,trx_time ASC LIMIT 1"; 
    $res = $conn->query($sql,[$member_no]);
    if ($res->num_rows() >= 1) {
        $res = $res->row_array();
        if ($res['amount'] != 300) {
            $errCode = 402;
            $errMsg = "ยอดฝากแรกของวันไม่ใช่ 300 บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
        }
        if ($res['promo'] != (-1)) {
            $errCode = 402;
            $errMsg = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
            echojs("ผิดพลาด",$errMsg,1,"error"); // ยินดีด้วยค่ะ ผิดพลาด  
        } 
        $latest_deposit_amount = $res['amount'];
        $promo_cal_result = 50;   
        $conn->query("UPDATE log_deposit SET promo=? WHERE id=?",[$promo_id,$res['id']]); 
        $expect_turnover = round(($latest_deposit_amount+$promo_cal_result)* $turnover_multiplier);  // Turnover x 5 
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
