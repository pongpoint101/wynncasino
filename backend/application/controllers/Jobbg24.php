<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobbg24 extends CI_Controller {
	private $commaxpayout=300000;
	public function __construct() {
		parent::__construct();    
		$this->load->model('Model_jobbg24'); 
		date_default_timezone_set("Asia/Bangkok");  
	}
	public function cashbackdaily(){ 
		$mdate=$this->input->get('mdate', TRUE); 
		// file_put_contents('../../../test/cashbackdaily.txt',1);
		$regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"; 
        if (preg_match ($regex, $mdate)<=0) { $mdate = date('Y-m-d', strtotime(' -1 day')); } 
		$count_process=$this->Model_jobbg24->cashbackdaily($mdate);
		echo "Done @" . date('Y-m-d H:i:s') . " (cashback records : " .$count_process. ")";
	}
	
	public function depositclearing(){ 
		//// file_put_contents('../../../test/depositclearing.txt',1);
		$queue_name='depositclearing';
		$queue_dailyreport='dailyreport';
		$count_kbizprocess=0;
		$count_process=0;
		$mdate = date('Y-m-d', strtotime(' -1 day'));
		$currentTime = time(); // เวลาปัจจุบันในรูปของ timestamp
		$startTime = strtotime('23:50:00'); // เวลาเริ่มต้นในรูปของ timestamp
		$endTime = strtotime('23:55:00'); // เวลาสิ้นสุดในรูปของ timestamp
		$q1=($this->Model_jobbg24->Check_Queue($queue_name));
		if($q1){ 
			$q2=($this->Model_jobbg24->Create_Queue($queue_name));
			if($q2){ 
			$count_kbizprocess=$this->Model_jobbg24->Depositkbiz();
			$count_process=$this->Model_jobbg24->depositclearing();  
			$count_queue_process=$this->Model_jobbg24->DepositCredit_Queue();  
			$this->Model_jobbg24->Update_Queue($queue_name);
	     }
	    }
		echo "Done @" . date('Y-m-d H:i:s') . " (depositclearing records : " .$count_process. ")";
		echo "Done @" . date('Y-m-d H:i:s') . " (Depositkbiz records : " .$count_kbizprocess. ")";  

		if(!$this->Model_jobbg24->Check_Queue($queue_dailyreport,date('Y-m-d'),0)){return false;};
		if(!$this->Model_jobbg24->Create_Queue($queue_dailyreport)){return false;};
		if ($currentTime >= $startTime && $currentTime <= $endTime) {
			$this->Model_jobbg24->UpdateDailyReport($mdate);	
		} 
		$this->Model_jobbg24->Update_Queue($queue_dailyreport); 
	}
	public function rundailyreport(){ 
		$mdate=$this->input->get('mdate', TRUE); 
		$regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"; 
        if (preg_match ($regex, $mdate)<=0) { $mdate = date('Y-m-d', strtotime(' -1 day')); } 
		$this->Model_jobbg24->UpdateDailyReport($mdate);
		echo "Done @rundailyreport";
	}
	public function autowithdraw_trx(){ 
		//// file_put_contents('../../../test/autowithdraw_trx.txt',1);
		$queue_name='autowithdraw';
		if(!$this->Model_jobbg24->Check_Queue('autowithdraw')){return false;};
		if(!$this->Model_jobbg24->Create_Queue($queue_name)){return false;};
		$count_process=$this->Model_jobbg24->Autowithdraw_trx();
		$this->Model_jobbg24->Update_Queue($queue_name);
		echo "Done @" . date('Y-m-d H:i:s') . " (records : " .$count_process. ")"; 
	}
	public function affcalculator(){  
		$mdate=$this->input->get('mdate', TRUE); 
		//// file_put_contents('../../../test/cashbackdaily.txt',1);
		$regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"; 
        if (preg_match ($regex, $mdate)<=0) { $mdate = date('Y-m-d', strtotime(' -1 day')); } 
		$count_process=$this->Model_jobbg24->Mcalculator($mdate);
		echo "Done @" . date('Y-m-d H:i:s') . " (aff records : " .$count_process. ")"; 
	}
  public function commdaily(){ 
	$today = date('Y-m-d');
    $yesterdayDT = new DateTime('yesterday');
    $yesterday = $yesterdayDT->format('Y-m-d');  
    // ถ้า เคยรันแล้ว 
    $commcheck=$this->db->query("SELECT Count(id)  as numrows FROM member_a_commission WHERE date_com =SUBDATE(CURDATE(),1);")->row();
    if (!isset($commcheck)){return false;}

    $m_site=$this->Model_affiliate->list_m_site(); 
    $website_com_type=$m_site['row']->com_type;
    $resCommFix=$m_site['row']->com_fix;   
    $resCommRange= $this->db->query("SELECT * FROM comm_raterange WHERE game_type=-1 AND status=1 ORDER BY comm_range")->result_array();
    $resCommTypeSetting=[];
    $resCommGameType=[];

	if($website_com_type==1){
		$sql="SELECT member_no,platform_code, Sum(correct_turnover) AS total_turnover,NULL AS remark,issue_date AS date_com,issue_date AS create_date,issue_date AS trx_date FROM member_provider_wl_monthly 
		WHERE  issue_date =? AND correct_turnover>0 GROUP BY member_no  ORDER BY member_no;";
	  }else if($website_com_type==2){
		$sql="SELECT member_no,platform_code, Sum(correct_turnover) AS total_turnover,NULL AS remark,issue_date AS date_com,issue_date AS create_date,issue_date AS trx_date FROM member_provider_wl_monthly 
		WHERE  issue_date =? AND correct_turnover>0 GROUP BY member_no ORDER BY member_no;";
	   }else if($website_com_type==3){
		$sql="SELECT member_no,platform_code, Sum(correct_turnover) AS total_turnover,NULL AS remark,issue_date AS date_com,issue_date AS create_date,issue_date AS trx_date FROM member_provider_wl_monthly 
		WHERE  issue_date =? AND correct_turnover>0 GROUP BY member_no,platform_code ORDER BY member_no,platform_code;";
	   }else if($website_com_type==4){
		$sql="SELECT member_no,platform_code, Sum(correct_turnover) AS total_turnover,NULL AS remark,issue_date AS date_com,issue_date AS create_date,issue_date AS trx_date FROM member_provider_wl_monthly 
		WHERE  issue_date =? GROUP BY member_no,platform_code ORDER BY member_no,platform_code;";
	  }
 
    $resYesterday = $this->db->query($sql, $yesterday)->result_array();
    $resYesterdayCount = count($resYesterday);
	$categoryall=$this->Model_member->getCategoryAll(); 
    echo 'Today : ' . $today . '<br>';
    echo 'Yesterday : ' . $yesterday . '<br>'; 
    echo 'Count : ' . $resYesterdayCount . '<br>';

    foreach ($resYesterday as $value) {
		   $value['game_type']=$this->Model_member->getCategory($value['platform_code'],$categoryall);
		   if($value['game_type']==7){continue;}
         if($website_com_type==3||$website_com_type==4){
            if(!isset($resCommTypeSetting['game_type'.$value['game_type']])){
             $resComm= $this->db->query("SELECT * FROM comm_raterange WHERE game_type=? ORDER BY comm_range",[$value['game_type']])->result_array();
             if(count($resComm)<=0){continue;} 
             $resCommTypeSetting['game_type'.$value['game_type']]=$resComm;
            }
            if(!isset($resCommGameType['game_type'.$value['game_type']]))
            $resGametypeComm= $this->db->query("SELECT * FROM comm_gametype WHERE game_type=? ",[$value['game_type']])->row_array();
            if(count($resGametypeComm)<=0){continue;} 
            $resCommGameType['game_type'.$value['game_type']]=$resGametypeComm; 
          }

          if($website_com_type==3){
            $com_type=$resCommGameType['game_type'.$value['game_type']]['com_type'];
            if($com_type==1){$website_com_type=2;$resCommRange=$resCommTypeSetting['game_type'.$value['game_type']];}
            if($com_type==2){$website_com_type=1;$resCommFix=$resCommGameType['game_type'.$value['game_type']]['com_fix_bygame'];}
          }
          if($website_com_type==4){$website_com_type=1;$resCommFix=$resCommGameType['game_type'.$value['game_type']]['com_fix_bygame'];}
		  
          if($website_com_type==1||$website_com_type==4){
             $this->CalCommFixAll($resCommFix,$value);
          }else if($website_com_type==2){
            $this->CalCommRangeAll($resCommRange,$value);
           }else if($website_com_type==3){ 
           // CalCommRangeByGameType($resCommTypeSetting['game_type'.$value['game_type']],$value);
          }else if($website_com_type==4){  
           // CalCommDynamicRangeByGameType($resCommTypeSetting['game_type'.$value['game_type']],$value);
          }
    }  
   $this->db->query("UPDATE member_a_commission SET status = 2 WHERE date_com <= SUBDATE(CURDATE(),2) AND status = 0;");
		
	}
	public	function CalCommFixAll($resCommSetting,$turnOver){ 
		$totalComm=$turnOver['total_turnover']*($resCommSetting/100);
		$total_turnover=$turnOver['total_turnover'];
		$totalComm=($totalComm>$this->commaxpayout?$this->commaxpayout:$totalComm);
	   if ($totalComm>0){
		 $data = array(
		   'member_no' => $turnOver['member_no'], 
		   'status' => 0,
		   'remark' => NULL,
		   'amount'=>$totalComm,
		   'total_turnover'=>$total_turnover,
		   'date_com' => $turnOver['date_com'],
		   'create_date' => $turnOver['create_date'] 
		  );   
			$query = $this->db->query('SELECT 1 FROM member_a_commission WHERE member_no=? AND date_com=?',[$turnOver['member_no'],$turnOver['date_com']]); 
			$affectedrows=$query->num_rows();  
			if($affectedrows<=0){
				$data['amount']=$totalComm;
				$this->db->insert('member_a_commission', $data); 
			}else{
				$this->db->set('amount', "amount+$totalComm", FALSE);    
				$this->db->set('total_turnover', "total_turnover+$total_turnover", FALSE);    
				$this->db->where('date_com',$turnOver['date_com']);
				$this->db->where('member_no', $turnOver['member_no']);
				$this->db->update('member_a_commission'); 
		  } 
		  $this->UpdateCommMonthly($turnOver['member_no'],$totalComm); 
		 }       
	 return 0;
   }
   public function CalCommRangeAll($resCommSetting,$turnOver){ 
	   $member_no = $turnOver['member_no'];
	   $commDate = $turnOver['trx_date']; 
	   $turnoverTotal = (float)$turnOver['total_turnover'];
	   $i = 0;
	   $arrCal = array();
	   while ($turnoverTotal > 0 && ($i != (count($resCommSetting)))) {
		 $commRate = $resCommSetting[$i]['comm_range'] / 100;
		 $commStart = (float)$resCommSetting[$i]['comm_range_start'];
		 $commEnd = (float)$resCommSetting[$i]['comm_range_end'];
		 if ($commEnd <= 0) {
		   $commEnd = 1000000000;
		 } 
		 $range = ($commEnd - $commStart) + 1;
	 
		 $arrCal[$i]['comm_range'] = $resCommSetting[$i]['comm_range'];
	 
		 if ($turnoverTotal <= $range) {
		   $arrCal[$i]['comm_cal'] = $turnoverTotal;
		   $arrCal[$i]['comm_result'] = $turnoverTotal * $commRate;
		   $turnoverTotal = 0;
		 } else {
		   $arrCal[$i]['comm_cal'] = $range;
		   $arrCal[$i]['comm_result'] = $range * $commRate;
		   $turnoverTotal = $turnoverTotal - $range; 
		   if ($turnoverTotal < 0) {
			 $turnoverTotal = $range + $turnoverTotal;
		   }
		  }
		 $i++;
	   }
	 
	   $totalComm = 0;
	   for ($i = 0; $i < count($arrCal); $i++) {
		 $totalComm += $arrCal[$i]['comm_result'];
	   }
	   $all_totalComm=$totalComm;
	   $totalComm=($totalComm>$this->commaxpayout?$this->commaxpayout:$totalComm);
	   $value['comm_result'] = $totalComm;
	   $value['all_comm_result'] = $all_totalComm;
	   $value['status'] = 1;
	   $value['details'] = json_encode($arrCal);
	  // echo '<pre>';
	  // print_r($value);
	  // echo '</pre>';
	   $data = array(
		'member_no' => $turnOver['member_no'], 
		'status' => 0,
		'remark' => NULL,
		'amount'=>$totalComm,
		'total_turnover'=>$turnOver['total_turnover'],
		'date_com' => $commDate,
		'create_date' => date('Y-m-d H:i:s')
	     );   

		$query = $this->db->query('SELECT 1 FROM member_a_commission WHERE member_no=? AND date_com=?',[$turnOver['member_no'],$commDate]); 
		$affectedrows=$query->num_rows();
		if($affectedrows<=0){
			$data['amount']=$totalComm;
		    $this->db->insert('member_a_commission', $data); 
		}else{
			$this->db->set('amount', "amount+$totalComm", FALSE); 
			$this->db->where('date_com',$commDate);
			$this->db->where('member_no', $turnOver['member_no']);
			$this->db->update('member_a_commission');
		} 
	  $this->UpdateCommMonthly($turnOver['member_no'],$totalComm);  
	 return 0;
   }
   public  function CalCommRangeByGameType($resCommSetting,$turnOver){
	 $this->CalCommRangeAll($resCommSetting,$turnOver);
	 return 0;
   }
   function CalCommDynamicRangeByGameType($resCommSetting,$turnOver){ 
	 return 0;
   }
   public  function UpdateCommMonthly($member_no,$amount){ 
	 if($amount<=0){return false;}
	 try{ 
		  $data = array(
		 'member_no' => $member_no,
		 'amount' => $amount,
		 'comm_month' => date('m'),
		 'comm_year' => date('Y')  
		 ); 
		$chk= $this->db->insert('comm_monthly', $data);
		 if(!$chk){
			$this->db->set('amount',"amount+($amount)", FALSE); 
			$this->db->where('member_no', $member_no);
			$this->db->where('comm_month', date('m'));
			$this->db->where('comm_year',date('Y')); 
			$this->db->update('comm_monthly');
		  }
		}catch(Exception $e){ 
		  echo 'error';
		}
		
   }    
   public function get_aff()
   {
	   date_default_timezone_set('Asia/Bangkok');
	   $date_now =date('Y-m-d',strtotime("-1 days"));

	   // $date_now ='2022-04-28';

	   //หาdeposit
	   $this->load->model('Model_log_deposit');
	   $deposit = $this->Model_log_deposit->get_deposit($date_now);
	   $promotion = $this->Model_log_deposit->get_bonus($date_now);
	   $data=array();
	   $data2=array();
	   $data3=array();
   

	   foreach ($deposit['result_array'] as $row) {
		   $data[] = array(
			   'member_no' => $row['member_no'],
			   'deposit' => $row['total'],
			   'created_date' => date('Y-m-d'),
			   'updated_at' => date('Y-m-d H:i:s')
		   );
	   }
	   foreach ($promotion['result_array'] as $key => $row) {
		   $data2[] = array(
			   'member_no' => $row['member_no'],
			   'promotion' => $row['total'],
			   'created_date' => date('Y-m-d'),
			   'updated_at' => date('Y-m-d H:i:s')
		   );
	   }

	   //หาwithdraw 
	   $withdraw = $this->Model_log_withdraw->get_withdraw($date_now);
	   foreach ($withdraw['result_array'] as $row) {
		   $data3[] = array(
			   'member_no' => $row['member_no'],
			   'withdraw' => $row['total'],
			   'created_date' => date('Y-m-d'),
			   'updated_at' => date('Y-m-d H:i:s')
		   );
	   }
	   
	   $this->load->model('Model_aff_win_loss');
	   
	   $date_now = date("Y-m-d H:i:s",time());
	   $date_end = date("Y-m-d 00:20:00",time());
	   if($date_now < $date_end){
		   $save_deposit = $this->Model_aff_win_loss->insertDeposit($data);
		   $save_promotion = $this->Model_aff_win_loss->insertPromotion($data2);
		   $save_withdraw = $this->Model_aff_win_loss->insertWithdraw($data3);
		   
		   echo "<pre>";
		   var_dump($save_deposit);
		   echo "</pre>";
	   }else{
	   }

   }
   public function winloss(){
	   $data=array();
	   $this->load->model('Model_aff_win_loss');
	   $getVal = $this->Model_aff_win_loss->getValue();

	   echo "<pre>";
	   var_dump($getVal['result_array']) ;
	   echo "</pre>";
   }
}