
<?php
class Model_jobbg24 extends CI_Model
{
  public function __construct()
  {
    date_default_timezone_set('Asia/Bangkok');
    $this->load->model('Model_log_deposit');
    $this->load->model('Model_log_withdraw');
    $this->load->model('read/Model_rlog_systeme');
    $this->load->model('write/Model_wlog_systeme');
  }
  public function Check_Queue($queue_name=null,$day=null,$queue_status=1){ 
    $rows=$this->Get_Queue($queue_name,$day,$queue_status);
    if(sizeof($rows)>0){return false; } 
    return true;
}
public function Get_Queue($queue_name=null,$day=null,$queue_status=1){ 
    if($queue_status==1){
      $this->db->where('queue_status', $queue_status);
    }  
   if($day!=null){ 
     $this->db->where('create_at >= ', $day." 00:00:00");
     $this->db->where('create_at <= ', $day." 23:59:59");
    }  
  $this->db->where('queue_name', $queue_name);    
  $this->db->limit(1);
  $rows = $this->db->get('sysqueue')->result_array();
  $num_rows=  (is_array($rows)?sizeof($rows):0);
  if($num_rows<=0){return []; } 
  return $rows;
}
public function Create_Queue($queue_name=null){ 
  $data=[
  'id' => uniqid(),  
  'queue_name' => $queue_name,  
  'queue_status' => 1,  
  'create_at' => date('Y-m-d H:i:s') 
  ];  
  $this->db->insert('sysqueue', $data);   
  return ($this->db->affected_rows() != 1) ? false : true;
}
public function Update_Queue($queue_name,$day=null){ 
  $this->db->set('queue_status', 2); 
  $this->db->where('queue_status', 1);
  $this->db->where('queue_name', $queue_name); 
  if($day!=null){ 
    $this->db->where('create_at >= ', $day." 00:00:00");
    $this->db->where('create_at <= ', $day." 23:59:59");
   } 
  $this->db->update('sysqueue');   
  // clear log 
  $this->db->where('create_at < (NOW() - interval 1 DAY)', "", false); 
  $this->db->where('queue_status', 2);
  $this->db->delete('sysqueue');
}
private function Calcashback($v,$cashback_rate,$cashback_max_payout){
      $deposit=$v['deposit']; 
      $money_remove = $v['money_remove']; 
      // $deposit_cal = ($deposit-abs($money_remove)>0?$deposit-abs($money_remove):0);
      $this->db->select('main_wallet');
      $this->db->where('member_no =', $v['member_no']);
      $itemMWallet = $this->db->get('member_wallet')->row(); 
      $main_wallet=$itemMWallet->main_wallet;
      $deposit_cal = ($deposit-abs($money_remove+$main_wallet)>0?$deposit-abs($money_remove+$main_wallet):0);
      $withdraw = $v['withdraw']; 
      $cbackAmount=0;
      if ($deposit_cal > $withdraw) {   // ฝากมากกว่าถอน เป็นยอดเสียไป
        $diff = ($deposit_cal - $withdraw) * ($cashback_rate / 100);
        $cbackAmount = ($diff > $cashback_max_payout) ? $cashback_max_payout : $diff; 
      }  
    return $cbackAmount;
}
  public function cashbackdaily($mdate=''){  
    $unixtime = strtotime('tuesday');  
    $count_process=0;  $caskback_return_type=1;
    $res= $this->Model_affiliate->list_m_site();
    $website_aff_type=(isset($res['row']->aff_type)?$res['row']->aff_type:1); 
    $bae_cashback_rate=$res['row']->cashback_rate;
    $cashback_max_payout=$res['row']->cashback_max_payout;
    if ($caskback_return_type==1) {
     // ยอดเสียหมดอายุ   
     $rescheck = $this->db->query("SELECT Count(id)  as numrows FROM member_a_return_loss WHERE date_return=?",[$mdate])->row();
     if (@$rescheck->numrows>0) {
       return false;
     }
    $this->db->set('status', 2); 
    $this->db->where('status=', 0);
    $this->db->where('date_return<', $mdate); 
    $this->db->update('member_a_return_loss');   
    }
    if($caskback_return_type==2&&date("Y-m-d",$unixtime)==date("Y-m-d")){  
        // ยอดเสียหมดอายุ   
       $this->db->set('status', 2); 
       $this->db->where('status=', 0);  
       $this->db->where('CAST(date_return AS DATE) < CAST((NOW() - interval 1 WEEK) AS DATE)', "", false); 
       $this->db->update('member_a_return_loss');    
    }
    $this->UpdateDailyReport($mdate);    

    if($website_aff_type==5){$this->affByWinlose($mdate);}

    $date_return=$mdate;
    $create_date=date('Y-m-d H:i:s');
     
    $this->db->select('member_no,turnover,deposit,money_remove,deposit_all,withdraw,deposit_pro'); 
    $this->db->where('create_day =',$date_return); 
    $sql = $this->db->get('report_sumbyday'); 
    $rows = $sql->result_array();    

   foreach ($rows as $v) {  
       try {
           $member_no = $v['member_no'];
           if ($member_no==null||$member_no==0) {continue; }
           //คำนวนยอดเสีย 
            // $main_wallet=$v['main_wallet'];
            $deposit=$v['deposit'];
            $deposit_all=$v['deposit_all'];
            $money_remove = $v['money_remove'];
            $deposit_pro=$v['deposit_pro'];
            $withdraw = $v['withdraw']; 
            // $mbr=$this->Model_member->SearchMemberPromoBymember_no2($member_no);
            // $member_cashback_rate=@$mbr['row']->cashback_rate;
            $cashback_rate=$bae_cashback_rate;
            // if($member_cashback_rate!=-1&&$member_cashback_rate!=''&&$member_cashback_rate!=null){$cashback_rate=$member_cashback_rate;} 
            $mbackAmount=$this->Calcashback($v,$cashback_rate,$cashback_max_payout); 
            $data=[
                'member_no' => $member_no,  
                'deposit' => $deposit, // ฝากไม่รับโปรทั้งหมด
                'deposit_all' => $deposit_all,// ฝากทั้งหมด 
                'deposit_pro' => $deposit_pro,// ยอดรับโปรทั้งหมด 
                'money_remove' => $money_remove,
                'withdraw' => $withdraw ,
                'create_day' => $date_return,
                'create_at' => $create_date 
            ];  
            //$this->db->insert('report_sumbyday', $data); 
              $data=[
                'member_no' => $member_no,
                'amount' => $mbackAmount,
                'deposit' => $deposit,
                'withdraw' => $withdraw,
                'deposit_pro' => $deposit_pro,
                'money_remove' => $money_remove,
                'status' => 0,
                'remark' => NULL,
                'date_return' => $date_return,
                'create_date' => $create_date
                ];
                if($caskback_return_type==1&&$mbackAmount>0){  
                   $this->db->insert('member_a_return_loss', $data);   
                  }else if($caskback_return_type==2){
                    $this->UpdateCaskback($member_no,$mbackAmount,$data); 
                 } 
            $count_process++;
           } catch (Exception $e) { 
             
         }
      }   
      if($caskback_return_type==2&&date("Y-m-d",$unixtime)==date("Y-m-d")){
        $this->InsertCaskback($cashback_rate,$cashback_max_payout,$date_return); 
        $this->db->set('status', 2); 
        $this->db->where('status=', 0); 
        $this->db->update('member_a_return_loss_daily');  
      }
      //ลบข้อมูลทิ้ง 
      $this->db->where('CAST(date_return AS DATE) < CAST((NOW() - interval 3 WEEK) AS DATE)', "", false); 
      $this->db->delete('member_a_return_loss_daily'); 
      $this->UpdateReward_Cashback_Daily();
      $this->UpdateReward_Maxplayer_Daily();
      $this->UpdateReward_MaxWinner_Player_Daily();
  return $count_process;
}
  private function InsertCaskback($cashback_rate,$cashback_max_payout,$date_return){ 
    $query = $this->db->query("SELECT * FROM member_a_return_loss_daily WHERE status=0"); 
    if ($query->num_rows()> 0){ 
       $rows = $query->result_array(); 
       foreach ($rows as $v) {   
        $InsertData=$v;
        unset($InsertData['id']); // remove id 
        $ibackAmount=$this->Calcashback($v,$cashback_rate,$cashback_max_payout); 
        $ibackAmount=($ibackAmount>0?$ibackAmount:0);  
        $InsertData['amount']=$ibackAmount;
        $InsertData['date_return']=$date_return;
        $InsertData['create_date']=date("Y-m-d H:i:s");
        if($ibackAmount>0){
         $this->db->insert('member_a_return_loss', $InsertData);
        } 
       } 
    }
  }
  private function UpdateCaskback($member_no, $amount, $InsertData = [])
  {
    $query = $this->db->query("SELECT amount AS amount FROM member_a_return_loss_daily WHERE member_no=? AND status=0 LIMIT 1", [$member_no]);
    if ($query->num_rows() > 0) {
      $this->db->set('amount', "amount+$amount", FALSE);
      $this->db->set('deposit', 'deposit+' . $InsertData['deposit'], FALSE);
      $this->db->set('withdraw', 'withdraw+' . $InsertData['withdraw'], FALSE);
      $this->db->set('deposit_pro', 'deposit_pro+' . $InsertData['deposit_pro'], FALSE);
      $this->db->set('money_remove', 'money_remove+' . $InsertData['money_remove'], FALSE);
      $this->db->where('status=', 0);
      $this->db->where('member_no', $member_no);
      $this->db->update('member_a_return_loss_daily');
    } else {
      $this->db->insert('member_a_return_loss_daily', $InsertData);
    }
    return 0;
  }
  public function commdaily()
  {
    return;
  }
  public function depositclearing()
  {
    //$this->SendLineNotify();
    $count_process = 0;
    $this->db->where('status', 2);
    $this->db->where('member_no!=', 0);
    $this->db->where('trx_date >= (NOW() - interval 1 DAY)', "", false);
    $this->db->where('member_no IS NOT NULL', NULL, FALSE);
    $this->db->order_by('trx_date', 'DESC');
    $this->db->order_by('trx_time', 'ASC');
    $rows = $this->db->get('log_deposit')->result_array();

    foreach ($rows as $v) {
      $skipCheckBalance = 0;
      $member_no = $v['member_no'];
      // if (strpos($res[0]['remark'], 'มียอดถอนค้าง')) {
      //   $res[0]['remark'] = str_replace('มียอดถอนค้าง(', '', $res[0]['remark']);
      //   $res[0]['remark'] = str_replace(')', '', $res[0]['remark']);
      // }  
      $timeFirst  = strtotime(date('Y-m-d H:i:s'));
      $timeSecond = strtotime($v['trx_date'] . ' ' . $v['trx_time']);
      $differenceInSeconds = abs($timeSecond - $timeFirst);

      $member_row = $this->Model_member->SearchMemberWalletBymember_no($member_no);
      $num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_no, $v['amount'], $v['trx_date'], $v['trx_time']);
      if ($differenceInSeconds < (rand(60, 120))) {
        continue;
      }
      if ($num_rows_Duplicate > 1) {
        $this->db->set('status', 0);
        $this->db->where('trx_id', $v['trx_id']);
        $this->db->update('log_deposit');
        continue;
      }

      $datamsite = $this->Model_affiliate->list_m_site();
      $min_auto_deposit = $datamsite['row']->min_auto_deposit;
      if ($member_row['row']->main_wallet >= $min_auto_deposit) { // ถ้ามียอดเงินเหลือน้อยกว่า 10 บาท ให้เช็คยอดค้าง  และรับโปร 
        $this->db->select('member_promo,member_last_deposit');
        $this->db->where('id =', $member_no);
        $member_pro = $this->db->get('members')->row();
        if ($member_pro->member_promo != 0 || (!in_array($member_pro->member_last_deposit, [1, 2, 3, 5]))) {
          $skipCheckBalance = 1;
        }
        if ($skipCheckBalance == 1) {
          continue;
        }
      }

      $this->db->where('status', 2);
      $this->db->where('member_no', $v['member_no']);
      $res_padding_w = $this->db->get('log_withdraw');

      if ($res_padding_w->num_rows() == 0) { // ยอดถอนค้างจะไม่เติมเงินออโต้ให้
        $depositAmount = $v['amount'];

        $this->db->set('status', 1);
        if ($v['channel'] == 1) {
          if (preg_match('/^VizPay.*/', $v['admin_name'], $matches) && $v['remark'] == 'มียอดถอนค้าง') {
            $this->db->set('remark', 'VizPay <= waitwithdraw');
            $this->db->set('remark_internal', $v['remark']);
          } else if (preg_match('/^VizPay.*/', $v['admin_name'], $matches) && $v['remark'] == 'Bal>10') {
            $this->db->set('remark', 'VizPay <= wait');
            $this->db->set('remark_internal', $v['remark']);
          } else {
            $this->db->set('remark', 'SCB <= waitwithdraw');
            $this->db->set('remark_internal', $v['remark']);
          }
        } else if ($v['channel'] == 2) {
          if ($v['remark'] == 'Bal>10') {
            $this->db->set('remark', 'SCB <= wait');
            $this->db->set('remark_internal', $v['remark']);
          } else if ($v['remark'] == 'มียอดถอนค้าง') {
            $this->db->set('remark', 'SCB <= waitwithdraw');
            $this->db->set('remark_internal', $v['remark']);
          }
        } else if ($v['channel'] == 3) {
          if ($v['remark'] == 'Bal>10') {
            $this->db->set('remark', 'TrueWallet <= wait');
            $this->db->set('remark_internal', $v['remark']);
          } else if ($v['remark'] == 'มียอดถอนค้าง') {
            $this->db->set('remark', 'TrueWallet <= waitwithdraw');
            $this->db->set('remark_internal', $v['remark']);
          }
        }
        $this->db->where('trx_id', $v['trx_id']);
        $this->db->update('log_deposit');

        $member_row = $this->Model_member->SearchMemberWalletBymember_no($member_no);
        $main_wallet = $member_row['row']->main_wallet;
        $this->Model_member->update_member_wallet_03($member_no, $depositAmount);
        $this->Model_member->insert_adjust_wallet_history_02('depositclear', $member_no, $depositAmount, $main_wallet, "");
        //Record and set promo condition 
        $this->db->where('member_no', $member_no); // ต้องลบก่อนเพราะ  บาง function ไม่ได้เลือกข้อมูลล่าสุด
        $this->db->delete('member_promo');

        $this->Model_promo->Update_member_promo_last($member_no, 0, $v['channel']);
        // $this->Model_member->insert_member_promo($member_no,0,1,$depositAmount,'Auto1'); 
        $this->Model_promo->ClearProOrTurnOver($member_no);
        $count_process++;
      }
    }
    return $count_process;
  }
  public function Depositkbiz()
  {
    $count_process = 0;
    $this->db->where('trx_status!=', 2);
    $this->db->where('trx_date >= (NOW() - interval 1 DAY)', "", false);
    $this->db->order_by('trx_date', 'ASC');
    $this->db->order_by('trx_time', 'ASC');
    $this->db->limit(1);
    $rows = $this->db->get('log_deposit_kbiz')->result_array();
    foreach ($rows as $v) {
      $remark = '';
      $balance_before = 0;
      $member_no = $v['member_no'];
      $this->Model_log_deposit->UpdateDepositKbiz($v['id'], ['trx_status' => 2]);
      $datetime_midnight = date_create(date('Y-m-d 00:00:00'));
      $datetime_now = date_create(date('Y-m-d H:i:s'));
      $datetime_bank = date_create($v['trx_date'] . ' ' . $v['trx_time']);

      $timeFirst  = strtotime(date('Y-m-d H:i:s'));
      $timeSecond = strtotime($v['trx_date'] . ' ' . $v['trx_time']);
      $differenceInSeconds = abs($timeSecond - $timeFirst) / 60;
      if ($datetime_bank > $datetime_now) {
        continue;
      }
      if ($differenceInSeconds > 30) { //เกิน 30  นาที 
        continue;
      }

      if ($member_no > 0) {
        $res = $this->Model_member->SearchMemberWalletBymember_no($member_no);
        $datamsite = $this->Model_affiliate->list_m_site();
        $balance_before = $res['row']->main_wallet;
        $min_auto_deposit = $datamsite['row']->min_auto_deposit;
        if ($balance_before <= $min_auto_deposit) {
          $status = 1;
        } else {
          $status = 2;
          $remark = 'Bal>10';
        }
        $ResultMemPD = $this->Model_db_log->search_log_withdraw_by_padding_member_no($member_no);
        if ($ResultMemPD['num_rows'] > 0) {
          $status = 2;
          $remark = 'Withdraw > Status Padding';
        }
      } else {
        $status = 2;
        $remark = 'ไม่พบบัญชี';
      }
      $bank_code = $v['from_bank'];
      $bank_name = BankCode2ShortName($bank_code);
      $trx_id = $v['trx_id'];
      $trx_time = date("H:i", strtotime($v['trx_time']));
      if ($v['from_bank'] == '004') { // KBANK 
        if ($remark == '') {
          $remark = $bank_name;
        }
      } else {
        if ($remark == '') {
          $remark = 'KBANK <= ' . $bank_name;
        }
      }
      $RslogDpTRX = 0;
      if ($member_no > 0) {
        $res = $this->Model_member->SearchMemberWalletBymember_no($member_no);
        $RslogDpTRX = $this->Model_db_log->search_log_deposit_trx($member_no, $v['trx_amount'], $v['trx_date'], $trx_time);
        if ($RslogDpTRX <= 0) {

          if ($status == 1) {
            $bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
            $bal_remain = $this->Model_member->adjustMemberWallet($member_no, $v['trx_amount'], 1);
            $this->Model_member->insert_adjust_wallet_history_02('Deposit-' . $remark, $member_no, $v['trx_amount'], $bal_b4['row']->main_wallet, "");

            $this->Model_member->Update_member_promo($member_no, 0, 1, $v['trx_amount'], $bank_name);
            $this->Model_promo->ClearProOrTurnOver($member_no);
            $this->Model_member->reset_member_promo_02($member_no);
          }
          $RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
          if ($RS1stDepo['num_rows'] <= 0) {
            $this->Model_db_log->insert_1st_deposit($member_no, $v['trx_amount'], $bank_name);
          }
        }
      }

      if ($RslogDpTRX <= 0) {
        $this->Model_db_log->insert_log_deposit_02($member_no, $v['trx_amount'], 1, $trx_id, $status, $remark, $v['trx_date'], $trx_time, -1, $balance_before);
      }
      $count_process++;
    }
    return  $count_process;
  }
  public function Autowithdraw_trx()
  {
    require dirname(__FILE__) . '/../../pg_vendor/autoload.php';
    $m_Result = $this->Model_affiliate->list_m_site();
    $max_Aprove_auto_withdraw = $m_Result['row']->max_approve_auto_withdraw;
    $mainTenance = $m_Result['row']->maintenance;
    $withdrawURL = $m_Result['row']->withdraw_url;
    $vizplay_withdrawURL = $m_Result['row']->vizplay_withdraw;
    $site_id = $m_Result['row']->site_id;
    $tw = $this->Model_rbank->FindAll_truewallet();
    $tw_list = [];
    foreach ($tw['result_array'] as $k => $v) {
      $tw_list[] = ['id' => $v['id'], 'twName' => $v['twName'], 'twNumber' => $v['twNumber'], 'twURL' => $v['twURL']];
    }
    $wallet_acc = @$tw_list[0]['twNumber'];
    $twURL = @$tw_list[0]['twURL'];
    $count_process = 0;
    $rows = $this->Model_db_log->search_log_withdraw_by_padding(1);
    $withdraw_by = 'botauto';
    foreach ($rows['result_array'] as $v) {
      $BoolMessage = false;
      $boolError = 0;
      $StatusDescription = '';
      $MessageErrorText = "";
      $res = '';
      $bank_acc = $v['bank_accountnumber'];
      $tw_phone = (trim(@$v['truewallet_account']) > 4) ? trim(@$v['truewallet_account']) : ((trim(@$v['truewallet_phone']) > 4 && $tel == '') ? trim(@$v['truewallet_phone']) : trim(@$v['telephone']));
      $choose_bank = $v['choose_bank'];
      if ($v['username'] === null || trim($v['username'] || $v['promo'] != 0) === '') {
        continue;
      } // ไม่พบ user 
      if ($v['amount_actual'] > $max_Aprove_auto_withdraw || $max_Aprove_auto_withdraw <= 0 || $mainTenance != 1) {
        continue;
      }  // ยอดถอนมากกว่า ที่ตั้งออโต้ไว้  
      if ((in_array($choose_bank, [1, 3]) && ($bank_acc == null || trim($bank_acc) == '')) || ($choose_bank == 2 && sizeof($tw_list) <= 0 || ($choose_bank == 2 && ($tw_phone == null || trim($tw_phone) == '')))) {
        continue;
      } // ไม่พบ ข้อมูลบัญชี 
      $log_withdraw_id = $v['id'];
      $member_no = $v['member_no'];
      $promo_ids = $v['promo'];

      // $timeFirst  = strtotime(date('Y-m-d H:i:s'));
      // $timeSecond = strtotime($v['trx_date'] . ' ' .$v['trx_time']);
      // $differenceInSeconds = abs($timeSecond - $timeFirst)/60; 
      // if ($differenceInSeconds< 2) {//น้อยกว่า 2 นาทีไม่ทำงาน 
      // 	 continue;
      //  }

      $Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);
      if ($Result['num_rows'] > 0) {
        if ($v['status'] != 2) {
          continue;
        } // ไม่ใช่สถานะรอถอน 
        $RSMember = $this->Model_member->SearchMemberByIDs($member_no);
        if ($RSMember['num_rows'] <= 0) {
          continue;
        }

        $wd_actual_amount = $Result['row']->amount;
        $RScc = $this->Model_function->condition_check_status(2, $Result['row']->channel, $Result['row']->remark, $Result['row']->remark_internal);

        $remark = $RScc['channelText'];
        if ($promo_ids == 37) {
          $this->db->where('member_no', $member_no);
          $this->db->order_by('id', 'DESC');
          $sql = $this->db->get('promop37p');
          $row_array = $sql->row_array();
          $actual_withdraw = $row_array['actual_withdraw'];
          $wd_actual_amount = ($Result['row']->amount > $actual_withdraw) ? $actual_withdraw : $wd_actual_amount;
        } else {
          $wd_actual_amount = $this->Model_log_withdraw->get_withdraw_max($member_no, $Result['row']->amount, $promo_ids);
        }

        try {
          if ($choose_bank == 2) {
            $req2['wallet_acc'] = $wallet_acc;
            $req2['type'] = 'p2p';
            $req2['customer_phone'] = $tw_phone;
            $req2['amount'] = $wd_actual_amount;
            $req2['msg'] = 'ถอนเงิน';
            $withdrawURL = $twURL;
            $arr['body'] = json_encode($req2);
          } else if ($choose_bank == 3) {
            $req['site_id'] = $site_id;
            $req['member_no'] = $member_no;
            $req['amount'] = $wd_actual_amount;
            $req['to_account_no'] = $RSMember['row']->bank_accountnumber;
            $req['to_bank_code'] = $RSMember['row']->bank_code;
            $req['to_name'] = $RSMember['row']->fname . ' ' . $RSMember['row']->lname;
            $req['trx_id'] = $Result['row']->trx_id;
            $withdrawURL = $vizplay_withdrawURL;
            $arr['body'] = json_encode($req);
          } else {
            $req['bank_code'] = $RSMember['row']->bank_code;
            $req['bank_acct'] = $RSMember['row']->bank_accountnumber;
            $req['amount'] = $wd_actual_amount;
            $req['fname'] = $RSMember['row']->fname;
            $req['lname'] = $RSMember['row']->lname;
            $withdrawURL = $m_Result['row']->withdraw_url;
            $arr['body'] = json_encode($req);
          }
          $this->Model_log_withdraw->update_log_withdraw_by_id($log_withdraw_id, 5, $withdraw_by, $withdraw_by);
          $client = new GuzzleHttp\Client([
            'exceptions'       => false,
            'http_errors' => false,
            'verify'           => false,
            'timeout' => 15, // Response timeout
            'connect_timeout' => 15, // Connection timeout
            'headers'          => ['Content-Type'   => 'application/json']
          ]);
          $res = $client->request('POST', $withdrawURL, $arr);
          $res_status = $res->getStatusCode();
          $resJSON = $res->getBody()->getContents();
          $res = json_decode($resJSON, true);

          if ($choose_bank == 2) {
            $StatusDescription = @$res['message'];
            if ($res_status == 200) {
              $boolError = 0;
              $MessageErrorText = 'โอนเงินสำเร็จ';
            } else {
              $boolError = 1;
              $MessageErrorText = "ไม่สามารถทำรายการได้! " . $StatusDescription;
            }
          } else if ($choose_bank == 3) {
            $StatusDescription = @$res['message'];
            $boolError = 1;
            $MessageErrorText = "ไม่สามารถทำรายการได้! " . $StatusDescription;
            if ($res_status == 200) {
              if ($res['code'] == 200) {
                $boolError = 0;
                $MessageErrorText = 'โอนเงินสำเร็จ';
              }
            }
          } else {
            $StatusDescription = @$res['status']['description'];
            if (($res['status']['code'] != 1000) && ($res['status']['code'] != 9003)) { //($res['status']['code'] != 1000) && ($res['status']['code'] != 9003)
              $boolError = 1;
              if ($res['status']['code'] == 404) {
                $MessageErrorText = 'ชื่อบัญชีปลายทางไม่ตรงกับระบบ<BR>ชื่อในระบบ : ' . $res['status']['sysName'] . '<BR>ชื่อบัญชีปลายทาง : ' . $res['status']['scbName'];
              } else {
                $MessageErrorText = "ไม่สามารถทำรายการได้! " . $StatusDescription;
              }
            } else {
              $boolError = 0;
              $MessageErrorText = 'โอนเงินสำเร็จยอดเงินคงเหลือ ' . $res['data']['remainingBalance'] . ' บาท';
            }
          }

          if ($boolError == 1) {
            $BoolMessage = false;
          } else {
            $BoolMessage = true;
          }
          if ($boolError == 1) {
            throw new Exception($MessageErrorText);
          }

          if ($BoolMessage) {
            $this->Model_member->reset_member_promo($member_no);
            $this->Model_db_log->clear_Userinfo_data($member_no);
            $this->Model_db_log->update_log_withdraw_by_id($log_withdraw_id, $wd_actual_amount, $remark, $withdraw_by);

            $this->Model_member->update_member_turnover($member_no);
            $this->Model_db_log->insert_log_auto_withdraw($member_no, $wd_actual_amount, $StatusDescription, $resJSON, $withdraw_by);
            if ($promo_ids == 13) {
              $RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($member_no);
              if ($RSPromo50['num_rows'] > 0) {
                $this->Model_promo->update_status_profree50_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount, $withdraw_by);
              }
            }
            if ($promo_ids == 14) {

              $RSPromo100P = $this->Model_promo->SearchPromop100pBymember_no($member_no);
              if ($RSPromo100P['num_rows'] > 0) {
                $this->Model_promo->update_status_promop100p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount, $withdraw_by);
              }
            }
            if ($promo_ids == 10) {

              $RSPromo50P = $this->Model_promo->SearchPromop50pBymember_no($member_no);
              if ($RSPromo50P['num_rows'] > 0) {
                $this->Model_promo->update_status_promop50p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount, $withdraw_by);
              }
            }
            if ($promo_ids == 101) {
              $RSPromo10P = $this->Model_promo->SearchPromop10pBymember_no($member_no);
              if ($RSPromo10P['num_rows'] > 0) {
                $this->Model_promo->update_status_promop10p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount, $withdraw_by);
              }
            }

            $RSMempro = $this->Model_member->SearchMemberPromoWhere($member_no, $promo_ids, 1);
            if ($RSMempro['num_rows'] > 0) {
              $this->Model_member->Update_member_promo_status($member_no, 2);
            }
          }
          $count_process++;
        } catch (Exception $e) {
          $boolError = 1;
          $StatusDescription = $e->getMessage();
          $this->Model_db_log->insert_log_auto_withdraw($member_no, $wd_actual_amount, $StatusDescription, (isset($resJSON) ? $resJSON : json_encode([])), $withdraw_by);
          $this->db->set('withdraw_by', $withdraw_by);
          $this->db->set('remark_internal', $StatusDescription);
          $this->db->set('status', 5);
          $this->db->where('id', $log_withdraw_id);
          $this->db->update('log_withdraw');
        }
      }
    }
    return $count_process;
  }
  public function affCalTurnOverMember($mdate = '', $website_com_type = 1)
  {
    $tmp_member_list = [];
    $categoryall = $this->Model_member->getCategoryAll();
    $sql = "SELECT member_no,platform_code, Sum(correct_turnover) AS total_turnover,NULL AS remark,issue_date AS date_com,issue_date AS create_date FROM member_provider_wl_monthly 
      WHERE  issue_date =? GROUP BY member_no,platform_code ORDER BY member_no,platform_code;";
    $rows = $this->db->query($sql, $mdate)->result_array();
    foreach ($rows as $v) {
      $member_no = $v['member_no'];
      $categorya_id = $this->Model_member->getCategory($v['platform_code'], $categoryall);
      $total_turnover = $v['total_turnover'];
      if($categorya_id==7||strtoupper($v['platform_code'])=='JILI'){continue;}
      if ($total_turnover <= 0) {
        continue;
      }
      if (!isset($tmp_member_list['m' . $member_no])) {
        $row_member = $this->db->query("SELECT id,username,group_af_l1,group_af_l2 FROM members WHERE id =?", $member_no)->row_array();
        $tmp_member_list['m' . $member_no] = $row_member;
      }
      $useraff = $tmp_member_list['m' . $member_no];
      $numrows = 0;
      if (@$useraff['group_af_l1'] > 0) {
        $member_aff_l1 = $useraff['group_af_l1'];
        if ($website_com_type == 1) {
          $query = $this->db->query('SELECT * FROM aff_transaction_v2 WHERE member_no=? AND create_date=?', [$member_aff_l1, $v['create_date']]);
        } else if ($website_com_type == 4) {
          $query = $this->db->query('SELECT * FROM aff_transaction_v2 WHERE member_no=? AND game_type=? AND create_date=?', [$member_aff_l1, $categorya_id, $v['create_date']]);
        }
        $numrows = $query->num_rows();
        if ($numrows <= 0) {
          $data = array(
            'member_no' => $member_aff_l1,
            'create_date' => $v['create_date'],
            'game_type' => $categorya_id,
            'total_turnover' => $total_turnover
          );
          $this->db->insert('aff_transaction_v2', $data);
        } else {
          $this->db->set('total_turnover', "total_turnover+$total_turnover", FALSE);
          $this->db->where('member_no', $member_aff_l1);
          if ($website_com_type == 4) {
            $this->db->where('game_type', $categorya_id);
          }
          $this->db->where('create_date', $v['create_date']);
          $this->db->update('aff_transaction_v2');
        }
      }

      if (@$useraff['group_af_l2'] > 0) {
        $member_aff_l2 = $useraff['group_af_l2'];
        if ($website_com_type == 1) {
          $query = $this->db->query('SELECT * FROM aff_transaction_v2 WHERE member_no=? AND create_date=?', [$member_aff_l2, $v['create_date']]);
        } else if ($website_com_type == 4) {
          $query = $this->db->query('SELECT * FROM aff_transaction_v2 WHERE member_no=? AND game_type=? AND create_date=?', [$member_aff_l2, $categorya_id, $v['create_date']]);
        }
        $numrows = $query->num_rows();
        if ($numrows <= 0) {
          $data = array(
            'member_no' => $member_aff_l2,
            'create_date' => $v['create_date'],
            'game_type' => $categorya_id,
            'total_turnover2' => $total_turnover
          );
          $this->db->insert('aff_transaction_v2', $data);
        } else {
          $this->db->set('total_turnover2', "total_turnover2+$total_turnover", FALSE);
          $this->db->where('member_no', $member_aff_l2);
          if ($website_com_type == 4) {
            $this->db->where('game_type', $categorya_id);
          }
          $this->db->where('create_date', $v['create_date']);
          $this->db->update('aff_transaction_v2');
        }
      }
    }
  }
  public function affByprovider($mdate = '', $row = [])
  {
    $RSSite = $this->Model_affiliate->list_m_site();
    $website_com_type = (isset($RSSite['row']->aff_type) ? $RSSite['row']->aff_type : 1);
    $aff_rate1 = $RSSite['row']->aff_rate1;
    $aff_rate2 = $RSSite['row']->aff_rate2;
    if($website_com_type==5){exit();}
    $commcheck = $this->db->query("SELECT Count(id)  as numrows FROM aff_daily WHERE status=2 AND CAST(trx_date AS date) =SUBDATE(CURDATE(),1);")->row();
    if (!isset($commcheck)) {
      return false;
    }
    $today = date('Y-m-d');
    $yesterdayDT = new DateTime('yesterday');
    $yesterday = $yesterdayDT->format('Y-m-d');
    $resCommTypeSetting = [];
    $resCommGameType = [];
    //  cal turn 
    $this->affCalTurnOverMember($mdate, $website_com_type);
    if ($website_com_type == 1) {
      $sql = "SELECT member_no,game_type, Sum(total_turnover) AS total_turnover,SUM(total_turnover2)AS total_turnover2,status,NULL AS remark,trx_date AS date_com,create_date FROM aff_transaction_v2 WHERE  status=-1 AND create_date =? GROUP BY member_no ORDER BY member_no;";
    } else if ($website_com_type == 2) {
      $sql = "SELECT member_no,game_type, Sum(total_turnover) AS total_turnover,SUM(total_turnover2)AS total_turnover2,status,NULL AS remark,trx_date AS date_com,create_date FROM aff_transaction_v2 WHERE  status=-1 AND create_date =? GROUP BY member_no,game_type ORDER BY member_no,game_type;";
    } else if ($website_com_type == 3) {
      $sql = "SELECT member_no,game_type, Sum(total_turnover) AS total_turnover,SUM(total_turnover2)AS total_turnover2,status,NULL AS remark,trx_date AS date_com,create_date FROM aff_transaction_v2 WHERE  status=-1 AND create_date =? GROUP BY member_no,game_type ORDER BY member_no,game_type;";
    } else if ($website_com_type == 4) {
      $sql = "SELECT member_no,game_type, Sum(total_turnover) AS total_turnover,SUM(total_turnover2)AS total_turnover2,status,NULL AS remark,trx_date AS date_com,create_date FROM aff_transaction_v2 WHERE  status=-1 AND create_date =? GROUP BY member_no,game_type ORDER BY member_no,game_type;";
    }
    $resYesterday = $this->db->query($sql, $mdate)->result_array();
    if (sizeof($resYesterday) <= 0) {
      return 0;
    }
    foreach ($resYesterday as $value) {
      $member_no = $value['member_no'];
      @$aff_wallet_l1['m' . $member_no] = isset($aff_wallet_l1['m' . $member_no]) ? $aff_wallet_l1['m' . $member_no] : 0;
      @$aff_wallet_l2['m' . $member_no] = isset($aff_wallet_l2['m' . $member_no]) ? $aff_wallet_l2['m' . $member_no] : 0;
      @$aff_turnover_l1['m' . $member_no] = isset($aff_turnover_l1['m' . $member_no]) ? $aff_turnover_l1['m' . $member_no] : 0;
      @$aff_turnover_l2['m' . $member_no] = isset($aff_turnover_l2['m' . $member_no]) ? $aff_turnover_l2['m' . $member_no] : 0;
      @$amount_commission_bonus_l1['m' . $member_no] = isset($amount_commission_bonus_l1['m' . $member_no]) ? $amount_commission_bonus_l1['m' . $member_no] : 0;
      @$amount_commission_bonus_l2['m' . $member_no] = isset($amount_commission_bonus_l2['m' . $member_no]) ? $amount_commission_bonus_l2['m' . $member_no] : 0;

      if ($website_com_type == 1) {
        $resCommFix1 = $aff_rate1;
        $resCommFix2 = $aff_rate2;
      } else if ($website_com_type == 4) {
        if (!isset($resCommGameType['game_type' . $value['game_type']])) {
          $resCommGameType = $this->db->query("SELECT * FROM aff_gametype WHERE game_type=? ", [$value['game_type']])->row_array();
        }
        if (count($resCommGameType) <= 0) {
          continue;
        }
        $resCommGameType['game_type' . $value['game_type']] = $resCommGameType;
        $resCommFix1 = $resCommGameType['game_type' . $value['game_type']]['fix_bygame1'];
        $resCommFix2 = $resCommGameType['game_type' . $value['game_type']]['fix_bygame2'];
      }

      if ($resCommFix1 > 0) {
        $aff_turnover_l1['m' . $member_no] += ($value['total_turnover']);
        $aff_wallet_l1['m' . $member_no] += ($value['total_turnover'] * $resCommFix1) / 100;
        // $amount_commission_bonus_l1['m' . $member_no] += ($value['aff_wallet_l1_bonus']);
      }
      if ($resCommFix2 > 0) {
        $aff_turnover_l2['m' . $member_no] += ($value['total_turnover2']);
        $aff_wallet_l2['m' . $member_no] += ($value['total_turnover2'] * $resCommFix2) / 100;
        // $amount_commission_bonus_l2['m' . $member_no] += ($value['aff_wallet_l2_bonus']);
      }
    }
    $count_process = 0;
    $member_old = null;
    foreach ($resYesterday as $value) {
      $amount_commission_l1 = 0;
      $amount_commission_l2 = 0;
      $amount_bonus_l1 = 0;
      $amount_bonus_l2 = 0;
      $member_no = $value['member_no'];
      $amount_commission_bonus_l1 = $amount_commission_bonus_l1['m' . $member_no];
      $amount_commission_bonus_l2 = $amount_commission_bonus_l2['m' . $member_no];
      $total_aff_wallet_l1 = $aff_wallet_l1['m' . $member_no];
      $total_aff_wallet_l2 = $aff_wallet_l2['m' . $member_no];
      $total_aff_turnover_l1 = $aff_turnover_l1['m' . $member_no];
      $total_aff_turnover_l2 = $aff_turnover_l2['m' . $member_no];
      if ($member_old != $value['member_no']) {
        $this->db->select('aff_type');
        $this->db->where('id=', $member_no);
        $query = $this->db->get('members');
        if ($query->num_rows() <= 0 || $value['status'] != -1) {
          continue;
        }
        $row = $query->row();
        if ($row->aff_type != 1) {
          continue;
        }


        $aff_amount_l1 = $total_aff_wallet_l1;
        $aff_amount_l2 = $total_aff_wallet_l2;
        if ($aff_amount_l1 < 0) {
          $aff_amount_l1 = 0;
        }
        if ($aff_amount_l2 < 0) {
          $aff_amount_l2 = 0;
        }
        $this->db->query("UPDATE member_wallet  SET aff_wallet_l1=IFNULL(aff_wallet_l1,0)+IFNULL($aff_amount_l1,0),aff_wallet_l2=IFNULL(aff_wallet_l2,0)+IFNULL($aff_amount_l2,0)
      WHERE member_no = $member_no;");

        $this->db->where("member_no = $member_no  AND aff_month=MONTH(CURDATE()) AND aff_year=YEAR(CURDATE())", "", false);
        $q = $this->db->get('aff_monthly');

        if ($q->num_rows() > 0) {
          $data = ['amount' => $aff_amount_l1 + $aff_amount_l2];
          $this->db->where("member_no = $member_no  AND aff_month=MONTH(CURDATE()) AND aff_year=YEAR(CURDATE())", "", false);
          $this->db->update('aff_monthly', $data);
        } else {
          $this->db->query("INSERT INTO aff_monthly (member_no,amount,aff_month,aff_year) 
        VALUES ($member_no ,$aff_amount_l1+$aff_amount_l2,MONTH(CURDATE()),YEAR(CURDATE()));");
        }
        $this->db->set('aff_wallet_l1', "aff_wallet_l1+$aff_amount_l1", FALSE);
        $this->db->set('aff_wallet_l2', "aff_wallet_l2+$aff_amount_l2", FALSE);
        $this->db->where('create_date', $mdate);
        $this->db->where('member_no', $member_no);
        $this->db->update('aff_transaction_v2', ['status' => 2]);
        $insert_aff = [
          'member_no' => $member_no,
          'aff_wallet_l1' => $aff_amount_l1,
          'aff_wallet_l2' => $aff_amount_l2,
          'aff_total'=>$aff_amount_l1+$aff_amount_l2,
          'status' => 2,
          'trx_date' => $mdate
        ];
        $this->db->insert('aff_daily', $insert_aff);
      }
      $member_old = $value['member_no'];
      $count_process++;
    }
    return $count_process;
  }
  public function Mcalculator($mdate = '', $row = [])
  {
    $d=  $this->affByprovider($mdate, $row);
    $this->UpdateReward_Wineraff_Daily();
    return $d;
    exit();
    $count_process = 0;
    $RSSite = $this->Model_affiliate->list_m_site();
    $website_com_type = (isset($RSSite['row']->aff_type) ? $RSSite['row']->aff_type : 1);
    $aff_rate1 = $RSSite['row']->aff_rate1;
    $aff_rate2 = $RSSite['row']->aff_rate2;
    // $this->db->where('status', 1); 
    // $this->db->where('member_no!=', 0);  
    // $this->db->where('member_no IS NOT NULL', NULL, FALSE);  
    // $this->db->where('trx_date=', $mdate); 
    // $rows = $this->db->get('aff_daily')->result_array();
    $sql = "SELECT member_no,game_type, Sum(total_turnover) AS total_turnover,SUM(total_turnover2)AS total_turnover2,status,NULL AS remark,trx_date AS date_com,create_date FROM aff_transaction_v2 WHERE  status=-1 AND create_date =? GROUP BY member_no ORDER BY member_no;";
    $rows = $this->db->query($sql, $mdate)->result_array();
    foreach ($rows as $v) {
      $amount_commission_l1 = 0;
      $amount_commission_l2 = 0;
      $amount_commission_bonus_l1 = 0;
      $amount_commission_bonus_l2 = 0;
      $amount_bonus_l1 = 0;
      $amount_bonus_l2 = 0;
      $aff_wallet_l1 = ($v['total_turnover'] * $aff_rate1) / 100;
      $aff_wallet_l2 = ($v['total_turnover2'] * $aff_rate2) / 100;
      $member_no = $v['member_no'];

      $this->db->select('aff_type');
      $this->db->where('id=', $member_no);
      $query = $this->db->get('members');
      if ($query->num_rows() <= 0) {
        continue;
      }
      $row = $query->row();
      if ($row->aff_type != 1) {
        continue;
      }

      // com ของ  lelve 1
      //  $query = $this->db->query("SELECT Sum(amount) AS amount
      //   FROM comm_daily WHERE member_no IN(
      //             SELECT id FROM members WHERE group_af_l1 IS NOT NULL AND group_af_l1=?
      //      ) AND trx_date = ?; ",[$member_no,$mdate]); 
      //  if ($query->num_rows()> 0){
      //     $row = $query->row(); 
      //     $amount_commission_l1=$row->amount;  
      //    }   
      // com ของ lelve 2
      //   $query = $this->db->query("SELECT Sum(amount) AS amount
      //   FROM comm_daily WHERE member_no IN(
      //             SELECT id  FROM members WHERE group_af_l2 IS NOT NULL AND group_af_l2=?
      //    ) AND trx_date = ?; ",[$member_no,$mdate]); 
      //  if ($query->num_rows()> 0){
      //     $row = $query->row(); 
      //     $amount_commission_l2=$row->amount;  
      //    }     

      // if($amount_commission_bonus_l1>0){
      //   $query = $this->db->query("SELECT Sum(amount) AS amount
      //   FROM view_deposit_pro_perday WHERE member_no IN(
      //             SELECT id  FROM members WHERE group_af_l1 IS NOT NULL AND group_af_l1=?
      //  ) AND trx_date = ?; ",[$member_no,$mdate]);     
      //  if ($query->num_rows()> 0){
      //     $row = $query->row(); 
      //     $amount_bonus_l1=$row->amount;   
      //  }
      // }

      // if($amount_commission_bonus_l2>0){
      //   $query = $this->db->query("SELECT Sum(amount) AS amount
      //   FROM view_deposit_pro_perday WHERE member_no IN(
      //      SELECT id  FROM members WHERE group_af_l2 IS NOT NULL AND group_af_l2=?
      //  ) AND trx_date = ?; ",[$member_no,$mdate]);     
      //  if ($query->num_rows()> 0){
      //     $row = $query->row(); 
      //     $amount_bonus_l2=$row->amount;   
      //  }    
      // }

      $aff_amount_l1 = ($aff_wallet_l1 - $amount_commission_l1);
      if ($amount_commission_bonus_l1 > 0) {
        $aff_amount_l1 = $aff_amount_l1 + ($amount_commission_bonus_l1 - $amount_bonus_l1);
      }
      // $aff_amount_l2=($aff_wallet_l2-$amount_commission_l2);
      // if($amount_commission_bonus_l2>0){$aff_amount_l2=$aff_amount_l2+($amount_commission_bonus_l2-$amount_bonus_l2);}
      $aff_amount_l2 = $aff_wallet_l2;
      if ($aff_amount_l1 < 0) {
        $aff_amount_l1 = 0;
      }
      if ($aff_amount_l2 < 0) {
        $aff_amount_l2 = 0;
      }
      $this->db->query("UPDATE member_wallet  SET aff_wallet_l1=IFNULL(aff_wallet_l1,0)+IFNULL($aff_amount_l1,0),aff_wallet_l2=IFNULL(aff_wallet_l2,0)+IFNULL($aff_amount_l2,0)
          WHERE member_no = $member_no;");

      $this->db->where("member_no = $member_no  AND aff_month=MONTH(CURDATE()) AND aff_year=YEAR(CURDATE())", "", false);
      $q = $this->db->get('aff_monthly');

      if ($q->num_rows() > 0) {
        $data = ['amount' => $aff_amount_l1 + $aff_amount_l2];
        $this->db->where("member_no = $member_no  AND aff_month=MONTH(CURDATE()) AND aff_year=YEAR(CURDATE())", "", false);
        $this->db->update('aff_monthly', $data);
      } else {
        $this->db->query("INSERT INTO aff_monthly (member_no,amount,aff_month,aff_year) 
            VALUES ($member_no ,$aff_amount_l1+$aff_amount_l2,MONTH(CURDATE()),YEAR(CURDATE()));");
      }

      $this->db->where('create_date', $mdate);
      $this->db->where('member_no', $member_no);
      $this->db->update('aff_transaction_v2', ['status' => 2]);
      $insert_aff = [
        'member_no' => $member_no,
        'aff_wallet_l1' => $aff_amount_l1,
        'aff_wallet_l2' => $aff_amount_l2,
        'status' => 2,
        'trx_date' => $mdate
      ];
      $this->db->insert('aff_daily', $insert_aff);

      $count_process++;
    }
    return $count_process;
  }
  private function SendLineNotify()
  {
    $data = ['error' => 500, 'error_msg' => ''];
    try {
      $m_Result = $this->Model_affiliate->list_m_site();
      $token = $m_Result['row']->token_linenotify;
      $rows = $this->Model_rlog_systeme->FindAll_waitalrt()['result_array'];
      if ($token == '' || $token == null) {
        return $data;
      }
      foreach ($rows as $k => $v) {
        $pmessage = $v['log_type'] . '' . $v['txt_data'];
        if ($pmessage == '' || $pmessage == null) {
          continue;
        }
        $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token . '',);
        $fields = ['message' => $pmessage];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //NO CACHE   
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result == false) {
          throw new Exception(curl_error($ch), curl_errno($ch));
        }
      }
      $this->Model_wlog_systeme->UpdateOnly((object)['id' => $v['id'], 'alert_status' => 1]);
      // $res = json_decode($result, true);
      $data['error_msg'] = 200;
    } catch (Exception $e) {
      $data['error_msg'] = 500;
      $data['error_msg'] = 'error:' . $e->getMessage();
    }
    return $data;
  }
  public function UpdateDailyReport($mdate){  
    $result = $this->db->query("CALL sp_cal_deposit_withdraw('$mdate')");
    $rows = $result->result_array(); 
    mysqli_next_result($this->db->conn_id);
    $result->free_result(); 
    $date_return=$mdate;
    $create_date=date('Y-m-d H:i:s');
  
    foreach ($rows as $v) {  
      try {
          $member_no = $v['member_no'];
          if ($member_no==null||$member_no==0) {continue; } 
          $this->db->select('turnover,correct_turnover,deposit,money_remove,deposit_all,withdraw,deposit_pro'); 
          $this->db->where('member_no =',$member_no);
          $this->db->where('create_day =',$date_return);
          $this->db->limit(1);
          $sql = $this->db->get('report_sumbyday'); 
          $result_array = $sql->result_array();   
          $num_rows= (is_array($result_array)?sizeof($result_array):0); 
  
           $deposit=$v['deposit'];
           $deposit_all=$v['deposit_all'];
           $money_remove = $v['money_remove'];
           $deposit_pro=$v['deposit_pro'];
           $withdraw = $v['withdraw'];   
           
           $this->db->select('SUM(ABS(bet_amount-settle_amount)) AS turnover_total,SUM(ABS(correct_turnover)) AS correct_turnover', FALSE);
           $this->db->where('member_no =', $member_no);
           $this->db->where('issue_date=', $date_return); 
           $turn_rows = $this->db->get('member_provider_wl_monthly')->row();
           $meber_turnOver=0;$correct_mturnover=0;
           if(isset($turn_rows->turnover_total)){$meber_turnOver=$turn_rows->turnover_total;$correct_mturnover=$turn_rows->correct_turnover;}
            
           if($num_rows<=0){
              $this->db->select('main_wallet');
              $this->db->where('member_no =', $member_no);
              $itemMWallet = $this->db->get('member_wallet')->row(); 
              $remainbalance=$itemMWallet->main_wallet*1; 
            $data=[
              'member_no' => $member_no,  
              'deposit' => $deposit, // ฝากไม่รับโปรทั้งหมด
              'deposit_all' => $deposit_all,// ฝากทั้งหมด 
              'deposit_pro' => $deposit_pro,// ยอดรับโปรทั้งหมด 
              'money_remove' => $money_remove,
              'withdraw' => $withdraw ,
              'turnover' => $meber_turnOver,
              'correct_turnover' => $correct_mturnover,
              'remain_balance' => $remainbalance,
              'status' => 1 , 
              'create_day' => $date_return,
              'create_at' => $create_date 
            ];  
            $this->db->insert('report_sumbyday', $data);  
           }else { // update
            $r= $sql->row_array();
            $d1=[];$d2=[];$d3=[];$d4=[];$d5=[];$d6=[];
            if($r['deposit']!=$deposit){$d1=['deposit' => $deposit];}
            if($r['deposit_all']!=$deposit_all){$d2=['deposit_all' => $deposit_all];}
            if($r['deposit_pro']!=$deposit_pro){$d3=['deposit_pro' => $deposit_pro];}
            if($r['money_remove']!=$money_remove){$d4=['money_remove' => $money_remove];}
            if($r['withdraw']!=$withdraw){$d4=['withdraw' => $withdraw];} 
            if($r['turnover']!=$meber_turnOver){$d5=['turnover' => $meber_turnOver];}
            if($r['correct_turnover']!=$correct_mturnover){$d6=['correct_turnover' => $correct_mturnover];}
            
            $data = array_merge($d1, $d2, $d3, $d4, $d5,$d6); 
            if(sizeof($data)>0){
              $this->db->where('member_no =',$member_no);
              $this->db->where('create_day =',$date_return);
              $this->db->update('report_sumbyday', $data);
            } 
           } 
          } catch (Exception $e) { 
            
        }
     }
     $unixtime = strtotime('monday'); 
     if(date("Y-m-d",$unixtime)==date("Y-m-d")){
        // $this->db->set('status', 2); 
        // $this->db->where('status=', 1);  
        // $this->db->where('create_at >=', date('Y-m-d', strtotime($mdate . " -7 days")));
        // $this->db->where('create_at <=', $mdate);
        // $this->db->update('report_sumbyday'); 
     }
  
   }
   public function affByWinlose($mdate=[]){ 
    $unixtime = strtotime('monday'); return ;  
    $tmp_member_list=[];
    //$RSSite = $this->Model_affiliate->list_m_site();
    //$aff_cal_type_rate1=(isset($RSSite['row']->aff_rate1)?$RSSite['row']->aff_rate1:0); 
    //$aff_cal_type_rate2=(isset($RSSite['row']->aff_rate1)?$RSSite['row']->aff_rate1:0); 
  
    $this->db->select('member_no,turnover,deposit,money_remove,deposit_all,withdraw,deposit_pro'); 
    $this->db->where('create_day =',$mdate); 
    $sql = $this->db->get('report_sumbyday'); 
    $rows = $sql->result_array();  
    foreach ($rows as $v) {  
      $member_no=$v['member_no'];
      $aff_amount_l1=0;$aff_amount_l2=0;
      $lose_l1=0;$lose_l2=0;
      if(!isset($tmp_member_list['m'.$member_no])){
        $row_member= $this->db->query("SELECT id,username,group_af_l1,group_af_l2 FROM members WHERE status=1 AND id =?", $member_no)->row_array();
        $tmp_member_list['m'.$member_no]=$row_member;
      }
      $useraff=$tmp_member_list['m'.$member_no]; 
      if(!isset($useraff['id'])){continue;}
      $numrows=0;
      $deposit=$v['deposit_all'];
      $withdraw=$v['withdraw'];
      if (@$useraff['group_af_l1']>0) {
        $member_aff_l1 = $useraff['group_af_l1']; 
        $lose_l1=$deposit-$withdraw;
        //if($lose>0){$aff_amount_l1=($lose*$aff_cal_type_rate1)/100; }  
      }
      if (@$useraff['group_af_l2']>0) {
        $member_aff_l2 = $useraff['group_af_l2'];
        $lose_l2=$deposit-$withdraw;
       // if($lose>0){$aff_amount_l2=($lose*$aff_cal_type_rate2)/100; }
       }
       $this->db->select('member_no,aff_wallet_l1,aff_wallet_l2,total_turnover,group_af_l1,group_af_l2'); 
       $this->db->where('member_no =',$member_no);
       $this->db->where('trx_date >=', $mdate.' 00:00:00');
       $this->db->where('trx_date <=', $mdate.' 23:59:59'); 
       $this->db->limit(1);
       $sql = $this->db->get('aff_daily'); 
       $result_array = $sql->result_array();   
       $num_rows= (is_array($result_array)?sizeof($result_array):0);
       if ($lose_l1>0||$lose_l2>0) {  
        if($num_rows<=0){ 
          $insert_aff=[
            'member_no'=>$member_no,
            'deposit'=>$deposit,
            'withdraw'=>$withdraw,
            'group_af_l1'=>$member_aff_l1,
            'group_af_l2'=>$member_aff_l2, 
            'status'=>1,
            'trx_date'=>$mdate
            ]; 
           $this->db->insert('aff_daily', $insert_aff); 
           }else{ //  update 
              $data=[
              'member_no'=>$member_no,
              'deposit'=>$deposit,
              'withdraw'=>$withdraw,
              'group_af_l1'=>$member_aff_l1,
              'group_af_l2'=>$member_aff_l2  
              ]; 
              $this->db->where('member_no =',$member_no);
              $this->db->where('trx_date =',$mdate);
              $this->db->update('aff_daily', $data);
          }
       }  
    }  
      if(date("Y-m-d",$unixtime)==date("Y-m-d")){
          // $this->db->set('status', 99); 
          // $this->db->where('status=', 1);  
          // $this->db->where('trx_date >=', date('Y-m-d', strtotime($mdate . " -7 days")));
          // $this->db->where('trx_date <=', $mdate);
          // $this->db->update('aff_daily');   
      }
   }
   public  function UpdateReward_Maxplayer_Daily(){ //ยอดการเล่น 5 อันดับสูงสุด 
    $this->db->where('trx_date < SUBDATE(CURDATE(),1)', "", false); 
    $this->db->where("status!=",2); 
    $this->db->update('rewardmaxplayer_daily',['status' => 3]);

    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=19 AND rating=1;")->row();
    $reward_number1=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=19 AND rating=2;")->row();
    $reward_number2=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=19 AND rating=3;")->row();
    $reward_number3=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=19 AND rating=4;")->row();
    $reward_number4=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=19 AND rating=5;")->row();
    $reward_number5=$res->amount;
    $res=$this->db->query("SELECT pro_turnover_amount,pro_status FROM pro_promotion_detail WHERE pro_id=19;")->row();
    $reward_turnover=$res->pro_turnover_amount;
    
    $res=$this->db->query("SELECT 1 FROM rewardmaxplayer_daily WHERE trx_date = SUBDATE(CURDATE(),1);")->row();
    if(!isset($res)){
      $sql="INSERT INTO rewardmaxplayer_daily (member_no, turnover_total,status,trx_date,amount,turnover_expect)
      SELECT member_no,total_turnover,status,trx_date,
         CASE
             WHEN t_rank=1 THEN   $reward_number1
             WHEN t_rank =2 THEN  $reward_number2
             WHEN t_rank =3 THEN  $reward_number3
             WHEN t_rank =4 THEN  $reward_number4
             WHEN t_rank =5 THEN  $reward_number5
             ELSE 0
         END AS amount
         ,CASE
             WHEN t_rank=1 THEN  $reward_number1*$reward_turnover
             WHEN t_rank =2 THEN  $reward_number2*$reward_turnover
             WHEN t_rank =3 THEN  $reward_number3*$reward_turnover
             WHEN t_rank =4 THEN  $reward_number4*$reward_turnover
             WHEN t_rank =5 THEN  $reward_number5*$reward_turnover
             ELSE 0
         END AS turnover_expect
          FROM(
         SELECT DISTINCT member_no, Sum(correct_turnover) AS total_turnover
         ,
          RANK() OVER (
                 PARTITION BY DATE(create_day)
                 ORDER BY correct_turnover DESC
             ) t_rank 
         ,1 AS status,SUBDATE(CURDATE(),1) AS trx_date FROM report_sumbyday 
         WHERE create_day = SUBDATE(CURDATE(),1) GROUP BY member_no ORDER BY correct_turnover DESC LIMIT 5 
         ) AS t; ";
            $this->db->query($sql);
     }
   }

   public  function UpdateReward_Cashback_Daily(){ //ยอดเสีย 5 อันดับสูงสุด
    $this->db->where('trx_date < SUBDATE(CURDATE(),1)', "", false); 
    $this->db->where("status!=",2); 
    $this->db->update('rewardcashback_daily',['status' => 3]);
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=180 AND rating=1;")->row();
    $reward_number1=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=180 AND rating=2;")->row();
    $reward_number2=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=180 AND rating=3;")->row();
    $reward_number3=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=180 AND rating=4;")->row();
    $reward_number4=$res->amount;
    $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=180 AND rating=5;")->row();
    $reward_number5=$res->amount;
    $res=$this->db->query("SELECT pro_turnover_amount,pro_status FROM pro_promotion_detail WHERE pro_id=180;")->row();
    $reward_turnover=$res->pro_turnover_amount;
    
    $res=$this->db->query("SELECT 1 FROM rewardcashback_daily WHERE trx_date = SUBDATE(CURDATE(),1);")->row();
    if(!isset($res)){
      $sql="INSERT INTO rewardcashback_daily (member_no, turnover_total,status,trx_date,amount,turnover_expect)
      SELECT member_no,total_turnover,status,trx_date,
         CASE
             WHEN t_rank=1 THEN   $reward_number1
             WHEN t_rank =2 THEN  $reward_number2
             WHEN t_rank =3 THEN  $reward_number3
             WHEN t_rank =4 THEN  $reward_number4
             WHEN t_rank =5 THEN  $reward_number5
             ELSE 0
         END AS amount
         ,CASE
             WHEN t_rank=1 THEN  $reward_number1*$reward_turnover
             WHEN t_rank =2 THEN  $reward_number2*$reward_turnover
             WHEN t_rank =3 THEN  $reward_number3*$reward_turnover
             WHEN t_rank =4 THEN  $reward_number4*$reward_turnover
             WHEN t_rank =5 THEN  $reward_number5*$reward_turnover
             ELSE 0
         END AS turnover_expect
          FROM(
         SELECT DISTINCT member_no, Sum(deposit-withdraw) AS total_turnover
         ,
          RANK() OVER (
                 PARTITION BY DATE(date_return)
                 ORDER BY (deposit-withdraw) DESC
             ) t_rank 
         ,1 AS status,SUBDATE(CURDATE(),1) AS trx_date FROM member_a_return_loss 
         WHERE date_return = SUBDATE(CURDATE(),1) GROUP BY member_no ORDER BY (deposit-withdraw) DESC LIMIT 5 
         ) AS t; ";
             $this->db->query($sql);
     }
    }  
     public  function UpdateReward_Wineraff_Daily(){ //ยอดแนะนำเพื่อน 5 อันดับสูงสุด
      $this->db->where('trx_date < SUBDATE(CURDATE(),1)', "", false); 
      $this->db->where("status!=",2); 
      $this->db->update('rewardaff_winner_daily',['status' => 3]);

      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=179 AND rating=1;")->row();
      $reward_number1=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=179 AND rating=2;")->row();
      $reward_number2=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=179 AND rating=3;")->row();
      $reward_number3=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=179 AND rating=4;")->row();
      $reward_number4=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=179 AND rating=5;")->row();
      $reward_number5=$res->amount;
      $res=$this->db->query("SELECT pro_turnover_amount,pro_status FROM pro_promotion_detail WHERE pro_id=179;")->row();
      $reward_turnover=$res->pro_turnover_amount;
      
      $res=$this->db->query("SELECT 1 FROM rewardaff_winner_daily WHERE trx_date = SUBDATE(CURDATE(),1);")->row();
      if(!isset($res)){
        $sql="INSERT INTO rewardaff_winner_daily (member_no, turnover_total,status,trx_date,amount,turnover_expect)
        SELECT member_no,total_turnover,status,trx_date,
           CASE
               WHEN t_rank=1 THEN   $reward_number1
               WHEN t_rank =2 THEN  $reward_number2
               WHEN t_rank =3 THEN  $reward_number3
               WHEN t_rank =4 THEN  $reward_number4
               WHEN t_rank =5 THEN  $reward_number5
               ELSE 0
           END AS amount
           ,CASE
               WHEN t_rank=1 THEN  $reward_number1*$reward_turnover
               WHEN t_rank =2 THEN  $reward_number2*$reward_turnover
               WHEN t_rank =3 THEN  $reward_number3*$reward_turnover
               WHEN t_rank =4 THEN  $reward_number4*$reward_turnover
               WHEN t_rank =5 THEN  $reward_number5*$reward_turnover
               ELSE 0
           END AS turnover_expect
            FROM(
           SELECT DISTINCT member_no, Sum(aff_total) AS total_turnover
           ,
            RANK() OVER (
                   PARTITION BY DATE(trx_date)
                   ORDER BY aff_total DESC
               ) t_rank 
           ,1 AS status,SUBDATE(CURDATE(),1) AS trx_date FROM aff_daily 
           WHERE trx_date = SUBDATE(CURDATE(),1) GROUP BY member_no ORDER BY aff_total DESC LIMIT 5 
           ) AS t; ";
              $this->db->query($sql);
       }
     }
     public  function UpdateReward_MaxWinner_Player_Daily(){ //ยอดชนะ 5 อันดับสูงสุด 
      $this->db->where('trx_date < SUBDATE(CURDATE(),1)', "", false); 
      $this->db->where("status!=",2); 
      $this->db->update('rewardmaxwinner_player_daily',['status' => 3]);
  
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=181 AND rating=1;")->row();
      $reward_number1=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=181 AND rating=2;")->row();
      $reward_number2=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=181 AND rating=3;")->row();
      $reward_number3=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=181 AND rating=4;")->row();
      $reward_number4=$res->amount;
      $res=$this->db->query("SELECT amount  FROM pro_reward WHERE pro_id=181 AND rating=5;")->row();
      $reward_number5=$res->amount;
      $res=$this->db->query("SELECT pro_turnover_amount,pro_status FROM pro_promotion_detail WHERE pro_id=181;")->row();
      $reward_turnover=$res->pro_turnover_amount;
      
      $res=$this->db->query("SELECT 1 FROM rewardmaxwinner_player_daily WHERE trx_date = SUBDATE(CURDATE(),1);")->row();
      if(!isset($res)){
        $sql="INSERT INTO rewardmaxwinner_player_daily (member_no, turnover_total,status,trx_date,amount,turnover_expect)
        SELECT member_no,total_turnover,status,trx_date,
         CASE
             WHEN t_rank=1 THEN   $reward_number1
             WHEN t_rank =2 THEN  $reward_number2
             WHEN t_rank =3 THEN  $reward_number3
             WHEN t_rank =4 THEN  $reward_number4
             WHEN t_rank =5 THEN  $reward_number5
             ELSE 0
         END AS amount
         ,CASE
             WHEN t_rank=1 THEN  $reward_number1*$reward_turnover
             WHEN t_rank =2 THEN  $reward_number2*$reward_turnover
             WHEN t_rank =3 THEN  $reward_number3*$reward_turnover
             WHEN t_rank =4 THEN  $reward_number4*$reward_turnover
             WHEN t_rank =5 THEN  $reward_number5*$reward_turnover
             ELSE 0
         END AS turnover_expect
          FROM(
         SELECT DISTINCT member_no, SUM(withdraw-remain_balance)-Sum(deposit_all+deposit_pro-(money_remove)) AS total_turnover
         ,
          RANK() OVER (
                 PARTITION BY DATE(create_day)
                 ORDER BY SUM(withdraw-remain_balance)-Sum(deposit_all+deposit_pro-(money_remove)) DESC
             ) t_rank 
         ,1 AS status,SUBDATE(CURDATE(),1) AS trx_date FROM report_sumbyday 
         WHERE create_day = SUBDATE(CURDATE(),1) GROUP BY member_no ORDER BY SUM(withdraw-remain_balance)-Sum(deposit_all+deposit_pro-(money_remove)) DESC LIMIT 5 
         ) AS t WHERE total_turnover>0; "; 
         $this->db->query($sql);
       }
     }

     public function DepositCredit_Queue()
     {
       //$this->SendLineNotify(); 
       $count_process = 0;
       $remark =''; 
       $this->db->where('status', 1);   
       $this->db->where('member_no IS NOT NULL', NULL, FALSE);
       $this->db->where("member_no!=''", NULL, FALSE);  
       $this->db->where('trx_date >= (NOW() - interval 3 DAY)', "", false);
       $this->db->order_by('trx_date', 'DESC');
       $this->db->order_by('trx_time', 'ASC');
       $this->db->group_by('member_no');
       $this->db->limit(1);
       $rows = $this->db->get('log_action_queue')->result_array();
       foreach ($rows as $v) {
        $count_process++;
        //   update id 
        $this->db->set('status',2);
        $this->db->where('id', $v['id']);
        $this->db->update('log_action_queue'); 
         // variable 
        $member_no=trim($v['member_no']); 
        $list = array();
        $list['status']=1; 
        $list['trx_id']=$v['trx_id'];
        $error_code=200;
        $balance_before = 0;
        if($member_no==''||$member_no==null||$v['action_type']!='deposit'){continue;}

        $list['deposit_amount'] =$v['amount'];
        $list['date'] = $v['trx_date'];
        $list['time'] = $v['trx_time']; 
        $list['deposit_acct_number'] = $v['from_account']; // Deposit bank account
        $list['bank_code'] = $v['from_bank']; // Bank code of deposit account   
        $list['withdraw_amount'] = 0; 
        
        $mcontinue=false;
        switch ($v['mtype']) {
         case 'APPSCB':   
           $lrow=json_decode($v['raw_content'],true);
           if(!($lrow['type']['description'] == 'ฝากเงิน' || $lrow['type']['description'] == 'Deposit')){ $mcontinue=true;}  
            $list['info'] = $lrow['txRemark']; // Deposit info   
            $bank = BankCode2ShortName($v['from_bank']);
            $depositbank='Deposit-Bank-SCB';
            $channel=1;
         break; 
         case 'SMSSCB':    
           $list['info'] = $v['raw_content']; // Deposit info   
           $bank = BankCode2ShortName($v['from_bank']);
           $depositbank='Deposit-Bank-SCB'; 
           $channel=1;
         break; 
         case 'APPTRUEWALLLET':
          $depositbank='Deposit-TRUEWALLLET';
          $bank ='TRUEWALLLET';  
          $channel=2;
        break; 
        case 'SMSKBANK':  $channel=3;$depositbank='Deposit-Bank-BANK';$bank ='KBANK';break; 
        case 'APPKBANK':  $channel=3;$depositbank='Deposit-Bank-BANK';$bank ='KBANK';break; 
        }

        if(trim($list['deposit_acct_number'])==''||trim($list['deposit_acct_number'])==null||$mcontinue){continue;} 

        $trxdata=$v['trx_date'];
        $trxtime=$v['trx_time'];

        $datetime_now = date_create(date('Y-m-d H:i:s'));
        $datetime_bank = date_create($trxdata. ' ' . $trxtime);
  
        $timeFirst  = strtotime(date('Y-m-d H:i:s'));
        $timeSecond = strtotime($trxdata. ' ' . $trxtime);
        $differenceInSeconds = abs($timeSecond - $timeFirst) / 60;
        if ($datetime_bank > $datetime_now) { 
          continue;
        }
        if ($differenceInSeconds > 30) { //เกิน 30  นาที 
          continue;
        }  
  
        $num_deposit=0; 
        if($error_code==200){  
          $this->db->select('COUNT(l_dps.id) AS num_rows', FALSE);
          $this->db->where('l_dps.trx_id =', $list['trx_id']);
          $num_deposit = $this->db->get('log_deposit AS l_dps')->row()->num_rows;
          if($num_deposit<=0){
             $num_deposit= $this->Model_db_log->search_log_deposit_trx($member_no, $list['deposit_amount'], $list['date'], $list['time']);
          } 
        }
         //    
       if($error_code==200){ 
           $ResultMemPD = $this->Model_db_log->search_log_withdraw_by_padding_member_no($member_no);
            if ($ResultMemPD['num_rows'] > 0) {
            $list['status'] = 2;
            $error_code=204;
            $remark = 'Withdraw > Status Padding';
           } 
        }
        $res = $this->Model_member->SearchMemberWalletBymember_no($member_no);
        $balance_before = $res['row']->main_wallet;
        if($error_code==200){  
          $datamsite = $this->Model_affiliate->list_m_site(); 
          $min_auto_deposit = $datamsite['row']->min_auto_deposit;
          if ($balance_before <= $min_auto_deposit) {
            $list['status'] = 1; 
          } else {
            $list['status'] = 2;
            $remark = 'Bal>10';
            $error_code=205;
          }
       }
 
       $RslogDpTRX = $this->Model_db_log->search_log_deposit_trx($member_no, $list['deposit_amount'], $list['date'], $list['time']);
      if ($RslogDpTRX <= 0) {
        $del_date = date("Y-m-d", strtotime("-1 days", strtotime($list['date'] . ' ' . $list['time'])));
        $duplicate_log = $this->Model_db_log->duplicate_log_deposit_scb($member_no, $list['deposit_amount'], $del_date, $list['time']);
        if ($duplicate_log > 0) {
          $RslogDpTRX = $duplicate_log; 
        }
      }
      if ($RslogDpTRX>0) {$error_code=501;} 

       // add history  
       if (in_array($v['mtype'],['APPSCB','SMSSCB'])) { // SCB -> SCB 
				if ($remark == '') {
					  $remark = 'SCB <= ' . $bank;
				}
      }
      if (in_array($v['mtype'],['APPKBANK','SMSKBANK'])) { // SCB -> SCB 
				if ($remark == '') {
					  $remark = 'KBANK <= KBANK';
				}
      }
      if (in_array($v['mtype'],['APPTRUEWALLLET'])) { // SCB -> SCB 
				if ($remark == '') {
					  $remark = 'TrueWallet';
				}
      }

       if($error_code>=200&&$error_code<=299&&$num_deposit<=0){ 
            $RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
						if ($RS1stDepo['num_rows'] <= 0) {
							$this->Model_db_log->insert_1st_deposit($member_no, $list['deposit_amount'], $bank);
						}
						$this->Model_db_log->insert_log_deposit_02($member_no, $list['deposit_amount'], $channel, $list['trx_id'], $list['status'], $remark, $list['date'], $list['time'], -1, $balance_before);
						if(in_array($v['mtype'],['APPSCB','SMSSCB'])){
              $this->Model_db_log->insert_log_deposit_scb($member_no, $list['trx_id'], $list['date'], $list['time'], $list['deposit_amount'], $list['bank_code'], $list['deposit_acct_number'], $list['deposit_acct_number'], $list['info'], 1, $list['status']); 
            }
       }
       // add credit
       if($error_code==200&&$num_deposit<=0){
        $bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
        $bal_remain = $this->Model_member->adjustMemberWallet($member_no, $list['deposit_amount'], 1);
        $this->Model_member->insert_adjust_wallet_history_02($depositbank, $member_no, $list['deposit_amount'], $bal_b4['row']->main_wallet, "");  

        $this->Model_member->Update_member_promo($member_no, 0, 1, $list['deposit_amount'], $depositbank); 
        $this->Model_promo->ClearProOrTurnOver($member_no); 
        $this->Model_member->reset_member_promo_02($member_no);
       }
     }
    return $count_process;
  } 

}
?> 