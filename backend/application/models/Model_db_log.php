<?php
class Model_db_log extends CI_Model{
	public function insert_log_deposit($member_no, $amount, $channel,$trx_id, $status, $remark,$remark_internal,$trx_date,$trx_time,$promo,$openration_type=1,$pbalance_before=0,$file_name=null){
		//uniqid(), date('Y-m-d'), date('H:i:s'), 
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$adminid=(isset($this->session->userID)?$this->encryption->decrypt($this->session->userID):null);
		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('channel', $channel);
		$this->db->set('openration_type', $openration_type);
        $this->db->set('admin_name', ($this->session->userdata("Username"))?$this->session->userdata("Username"):null);
		$this->db->set('admin_id', $adminid);
		$this->db->set('trx_id', $trx_id);
		$this->db->set('trx_date', $trx_date);
		$this->db->set('trx_time', $trx_time);
		$this->db->set('status', $status);
		$this->db->set('remark', $remark);
		$this->db->set('remark_internal', $remark_internal);
		$this->db->set('promo', $promo);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->set('balance_before', $pbalance_before);
		$this->db->set('slip', $file_name); 
		$this->db->insert('log_deposit'); 
	}

	public function update_log_deposit_promo($promo_id, $log_deposit_id){
		$this->db->set('promo', $promo_id);
		$this->db->where('id =', $log_deposit_id);
		$this->db->update('log_deposit');
	}
	public function update_log_depositBylist($promo_id,$log_deposit_id=[]) {
		$this->db->set('promo', $promo_id); 
		$this->db->where_in('id', $log_deposit_id);  
		$this->db->update('log_deposit'); 
	}
	public function insert_log_deposit_02($member_no, $amount, $channel, $trx_id, $status, $remark,$trx_date,$trx_time,$promo,$pbalance_before){
		//uniqid(), date('Y-m-d'), date('H:i:s'), 
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$adminid=(isset($this->session->userID)?$this->encryption->decrypt($this->session->userID):null);

		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('channel', $channel);
		$this->db->set('trx_id', $trx_id);
		$this->db->set('trx_date', $trx_date);
		$this->db->set('trx_time', $trx_time);
		$this->db->set('status', $status);
		$this->db->set('remark', $remark);
		$this->db->set('promo', $promo);
		$this->db->set('admin_id', $adminid);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->set('balance_before', $pbalance_before);
		$this->db->insert('log_deposit');
	}
	public function update_reject_log_deposit($ids){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', 3);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('id =', $ids);
		$this->db->update('log_deposit');
	}
	public function insert_log_add_credit($member_no,$member_username,$amount,$remark,$remark_internal,$openration_type=1,$trx_date=null,$trx_time=null){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('member_username', $member_username);
		$this->db->set('amount', $amount);
		$this->db->set('openration_type', $openration_type);
		$this->db->set('admin_name', ($this->session->userdata("Username")?$this->session->userdata("Username"):null));
		$this->db->set('remark', $remark);
		$this->db->set('remark_internal', $remark_internal);
		$this->db->set('update_date', $DateTimeNow);
		if($trx_date!=''&&$trx_date!=null){
			$this->db->set('trx_date', $trx_date);	
		}
		if($trx_time!=''&&$trx_time!=null){
			$this->db->set('trx_time', $trx_time);	
		}
		$this->db->insert('log_add_credit');
	}
	public function insert_1st_deposit($member_no,$amount,$channel){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('channel', $channel);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('1st_deposit');
	}

	public function insert_log_auto_bank($bank_id,$log_msg){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('bank_id', $bank_id);
		$this->db->set('log_msg', $log_msg);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_auto_bank');
	}

	public function insert_log_auto_truewallet($l_name,$l_mobile_number,$l_balance,$l_time_stamp,$l_records){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('l_name', $l_name);
		$this->db->set('l_mobile_number', $l_mobile_number);
		$this->db->set('l_balance', $l_balance);
		$this->db->set('l_time_stamp', $l_time_stamp);
		$this->db->set('l_records', $l_records);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_auto_truewallet');
	}

	public function insert_log_deposit_scb($member_no,$trx_id,$trx_date,$trx_time,$trx_amount,$from_bank,$from_acc,$to_acc,
										   $full_msg,$channel,$trx_status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$this->db->set('member_no', $member_no);
		$this->db->set('trx_id', $trx_id);
		$this->db->set('trx_date', $trx_date);
		$this->db->set('trx_time', $trx_time);
		$this->db->set('trx_amount', $trx_amount);
		$this->db->set('from_bank', $from_bank);
		$this->db->set('from_acc', $from_acc);
		$this->db->set('to_acc', $to_acc);
		$this->db->set('full_msg', $full_msg);
		$this->db->set('channel', $channel);
		$this->db->set('trx_status', $trx_status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_deposit_scb');
	}

	public function insert_log_deposit_kbank($member_no,$trx_id,$trx_date,$trx_time,$trx_amount,$from_bank,$from_acc,$to_acc,
											 $full_msg,$trx_status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$this->db->set('member_no', $member_no);
		$this->db->set('trx_id', $trx_id);
		$this->db->set('trx_date', $trx_date);
		$this->db->set('trx_time', $trx_time);
		$this->db->set('trx_amount', $trx_amount);
		$this->db->set('from_bank', $from_bank);
		$this->db->set('from_acc', $from_acc);
		$this->db->set('to_acc', $to_acc);
		$this->db->set('full_msg', $full_msg);
		$this->db->set('trx_status', $trx_status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_deposit_kbank');
	}

	public function Search1st_depositBymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('1st_deposit')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, channel, update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('1st_deposit');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function insert_log_del_credit($member_no,$member_username,$amount,$remark){
		
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		

		$this->db->set('member_no', $member_no);
		$this->db->set('member_username', $member_username);
		$this->db->set('amount', ($amount*(-1)));
		$this->db->set('admin_name', $this->session->userdata("Username"));
		$this->db->set('remark', $remark);

		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_del_credit');
	}
	public function insert_log_agent($member_username,$ag_type,$ag_amount,$ag_info){
		
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$this->db->set('ag_datetime', $DateTimeNow);
		$this->db->set('ag_username', $member_username);
		$this->db->set('ag_type', $ag_type);
		$this->db->set('ag_amount', $ag_amount);
		$this->db->set('ag_info', $ag_info);
		$this->db->set('ag_admin', $this->session->userdata("Username"));

		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_agent');
	}

	public function search_log_deposit_lessthen($channelLessThen,$status,$member_no){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('channel <=', $channelLessThen);
		$this->db->where('status =', $status);
		$num_rows = $this->db->get('log_deposit')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, status, remark, promo, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('channel <=', $channelLessThen);
			$this->db->where('status =', $status);
			$sql = $this->db->get('log_deposit');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function search_log_deposit_today_by_amount($member_no,$amount,$promo=-1){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('amount =', $amount);
		$this->db->where('promo =', $promo);
		// $this->db->where('DATE(trx_date) >=', '2021-12-30');
		// $this->db->where('DATE(trx_date) <=', '2022-01-31');
		$this->db->where('(channel<=3 OR channel=5)');
		$this->db->where('status =', 1); 
		$this->db->where('DATE_FORMAT(trx_date, "%Y-%m-%d") = CURDATE()');
		$num_rows = $this->db->get('log_deposit')->row()->num_rows;  
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, status, remark, promo, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('amount =', $amount);
			$this->db->where('promo =', $promo);
			// $this->db->where('DATE(trx_date) >=', '2021-12-30');
			// $this->db->where('DATE(trx_date) <=', '2022-01-31');
			$this->db->where('(channel<=3 OR channel=5)');
			$this->db->where('status =', 1);
			$this->db->where('DATE_FORMAT(trx_date, "%Y-%m-%d") = CURDATE()');
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			$sql = $this->db->get('log_deposit'); 
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows; 
		$result['row'] = $row;
		return $result;
	}
	public function search_log_deposit_lessthen_today($member_no){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('(channel<=3 OR channel=5)');
		$this->db->where('status =', 1);
		$this->db->where('DATE_FORMAT(trx_date, "%Y-%m-%d") = CURDATE()');
		$num_rows = $this->db->get('log_deposit')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, status, remark, promo, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('(channel<=3 OR channel=5)');
			$this->db->where('status =', 1);
			$this->db->where('DATE_FORMAT(trx_date, "%Y-%m-%d") = CURDATE()');
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			$sql = $this->db->get('log_deposit');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_deposit_totle($member_no){
		$result_array = "";
		$row = ""; 
		$num_rows=0;
		$this->db->select('SUM(amount) as amount', FALSE);
		$this->db->where('member_no', $member_no);
		$this->db->where('(channel<=3 OR channel=5)');
		$this->db->where('status', 1); 
		// $this->db->order_by('trx_date', 'DESC');
		// $this->db->order_by('trx_time', 'DESC');
		$query = $this->db->get('log_deposit');  
		if($query !== FALSE && $query->num_rows() > 0){
			$num_rows=$query->num_rows();
			$result_array = $query->result_array();
			$row = $query->row();
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row; 
		return $result; 
	}

	public function search_log_deposit_trx($member_no,$amount,$trx_date,$trx_time,$manual_deposit=false){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('amount =', $amount);
		$this->db->where('trx_date =', $trx_date);
		if ($manual_deposit) {
			$this->db->where_in('status',[1,2]);
		}
		$this->db->where('trx_time >=',date('H:i:s', (strtotime($trx_time)-60)));
		$this->db->where('trx_time <=',date('H:i:s', (strtotime($trx_time)+60))); 
		$num_rows = $this->db->get('log_deposit')->row()->num_rows;
		// $result_array = "";
		// $row = "";
		// if($num_rows > 0){
		// 	$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, status, remark, promo, update_date');
		// 	$this->db->where('member_no =', $member_no);
		// 	$this->db->where('amount =', $amount);
		// 	$this->db->where('trx_date =', $trx_date);
		// 	$this->db->where('trx_time =', $trx_time);
		// 	$sql = $this->db->get('log_deposit');

		// 	$result_array = $sql->result_array();
		// 	$row = $sql->row();

		// }

		// $result['num_rows'] = $num_rows;
		// $result['result_array'] = $result_array;
		// $result['row'] = $row;
		//return $result;
		return $num_rows;
	}
	public function search_log_deposit_kbank($member_no,$trx_date,$trx_time,$trx_amount,$from_acc,$to_acc){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('trx_date =', $trx_date);
		$this->db->where('trx_time =', $trx_time);
		$this->db->where('trx_amount =', $trx_amount);
		$this->db->where('from_acc =', $from_acc);
		$this->db->where('to_acc =', $to_acc);
		$num_rows = $this->db->get('log_deposit_kbank')->row()->num_rows;

		return $num_rows;
	}

	public function sum_log_deposit($member_no){

		$this->db->select('COUNT(member_no) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('view_member_sum_deposit')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('member_no, sum_deposit');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('view_member_sum_deposit');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function sum_deposit_backup($member_no){

		$this->db->select('COUNT(member_no) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('view_member_sum_deposit_backup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('member_no, sum_deposit');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('view_member_sum_deposit_backup');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function sum_withdraw($member_no){

		$this->db->select('COUNT(member_no) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('view_member_sum_withdraw')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('member_no, sum_withdraw');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('view_member_sum_withdraw');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function sum_withdraw_backup($member_no){

		$this->db->select('COUNT(member_no) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('view_member_sum_withdraw_backup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('member_no, sum_withdraw');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('view_member_sum_withdraw_backup');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_login($member_no){
		$this->db->select('id, member_no, ip_address, user_agent, device_info, update_date');
		$this->db->where('member_no =', $member_no);
		$this->db->limit(1);
		$this->db->order_by('update_date', 'DESC');
		$sql = $this->db->get('log_login'); 
		$row = $sql->row();	 
        $result=[]; 
		$result['num_rows'] =(isset($row->id)?1:0);  
		$result['row'] = $row;
		return $result;
	}

	public function search_log_deposit_member($member_no,$timestart='',$timeend=''){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('create_date >= ', $timestart);
		$this->db->where('create_date <= ', $timeend);
		$num_rows = $this->db->get('log_deposit')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, channel, status, remark, promo, create_date,update_date,openration_type,admin_name,remark_internal');
			$this->db->where('member_no =', $member_no);
			$this->db->where('create_date >= ', $timestart);
			$this->db->where('create_date <= ', $timeend);
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			$sql = $this->db->get('log_deposit');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function search_log_deposit_member_data_old($member_no,$timestart='',$timeend=''){ 
			
		$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, channel, status, remark, promo,create_date, update_date,openration_type,admin_name,remark_internal');
		$this->db->where('member_no =', $member_no);
		$this->db->where('create_date >= ', $timestart);
		$this->db->where('create_date <= ', $timeend);
		$this->db->order_by('trx_date', 'DESC');
		$this->db->order_by('trx_time', 'DESC');
		$sql = $this->db->get('log_deposit_backup');

		$result_array = $sql->result_array();
		$row = $sql->row(); 

		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
	return $result;
   }
    public function credit_delete_by_date($trx_date){
		    $result_array = "";  
			$this->db->select('member_no,amount,"" AS trx_id,DATE(update_date) AS trx_date,TIME(update_date)AS trx_time
							  ,5 AS channel,1 AS status,"ลบเครดิต"AS remark,"-1" AS promo,update_date,member_username AS username,admin_name,"" AS remark_internal');
			$this->db->where('update_date >= ', $trx_date." 00:00:00");
			$this->db->where('update_date <= ', $trx_date." 23:59:59");
			$sql = $this->db->get('log_del_credit');
			$result_array = $sql->result_array();  

		    $result['num_rows'] =(is_array($result_array)?sizeof($result_array):0);
		    $result['result_array'] = $result_array; 
		return $result;
	}
    public function credit_deposit_mannual_by_date($trx_date){
		$result_array = "";  
		$this->db->select('member_no,amount,"" AS trx_id,trx_date,trx_time
						  ,5 AS channel,1 AS status,remark AS remark,"-1" AS promo,update_date,member_username AS username,openration_type,admin_name,remark_internal');
		$this->db->where('update_date >= ', $trx_date." 00:00:00");
		$this->db->where('update_date <= ', $trx_date." 23:59:59");
		$sql = $this->db->get('log_add_credit');
		$result_array = $sql->result_array();  

		$result['num_rows'] =(is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array; 
	return $result;
   }
	public function search_log_deposit_by_date($trx_date,$filte_list_e=[],$filte_list_in=[]){   
		    $result_array = ""; 
			$row = "";
			$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.trx_id, l_dps.trx_date, l_dps.trx_time,l_dps.balance_before, 
							   l_dps.channel, l_dps.status, l_dps.remark, l_dps.promo, l_dps.update_date, mber.username,l_dps.openration_type,l_dps.admin_name,l_dps.remark_internal,slip');
			$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
			$this->db->where('l_dps.trx_date >=', $trx_date." 00:00");
			$this->db->where('l_dps.trx_date <=', $trx_date." 23:59");
			if (sizeof($filte_list_e)>0) {
				foreach($filte_list_e as $k=>$v) { 
					$this->db->where($v, "", false);
			   }
			}
			if (sizeof($filte_list_in)>0) {
				foreach($filte_list_in as $k=>$v) {  
					$this->db->where_in($k, $v); 
			   }
			}
			$sql = $this->db->get('log_deposit AS l_dps'); 
			$result_array = $sql->result_array();   
			$row = $sql->row();

		   $result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		   $result['result_array'] = $result_array; 
		   $result['row'] = $row;
		return $result;
	}

	public function search_log_deposit_by_trx_id($trx_id){

		$this->db->select('COUNT(l_dps.id) AS num_rows', FALSE);
		$this->db->where('l_dps.trx_id =', $trx_id);
		$num_rows = $this->db->get('log_deposit AS l_dps')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.trx_id, l_dps.trx_date, l_dps.trx_time,l_dps.balance_before, 
							   l_dps.channel, l_dps.status, l_dps.remark, l_dps.promo, l_dps.update_date, mber.username,l_dps.openration_type,l_dps.admin_name,l_dps.remark_internal,slip');
			$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
			$this->db->where('l_dps.trx_id =', $trx_id);
			$sql = $this->db->get('log_deposit AS l_dps');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_deposit_backup_member($member_no,$start = -1,$length = -1){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('log_deposit_backup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, trx_id, trx_date, trx_time, channel, status, remark,remark_internal, promo, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			if(($start > -1) && ($length > 0)){
				$this->db->limit($length,$start);
			}
			$sql = $this->db->get('log_deposit_backup');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_withdraw_member($member_no,$timestart='',$timeend=''){ 

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		if($timestart!='' &&$timeend !=''){
			$this->db->where('update_date >= ', $timestart);
			$this->db->where('update_date <= ', $timeend); 
		}
		
		$num_rows = $this->db->get('log_withdraw')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, amount_actual,balance_after, channel, trx_id, trx_date, trx_time, status, remark,remark_internal,remark_internal_detail, promo, 
							   withdraw_by, withdraw_otp, update_date');
			$this->db->where('member_no =', $member_no);
			if($timestart!='' &&$timeend !=''){
			$this->db->where('update_date >= ', $timestart);
			$this->db->where('update_date <= ', $timeend); 
			}
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			$sql = $this->db->get('log_withdraw');

			$result_array = $sql->result_array();
			$row = $sql->row();

		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function search_log_withdraw_backup_member($member_no,$timestart='',$timeend=''){ 

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('update_date >= ', $timestart);
	    $this->db->where('update_date <= ', $timeend); 
		$num_rows = $this->db->get('log_withdraw_backup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, amount_actual, channel, trx_id, trx_date, trx_time, status, remark,remark_internal,remark_internal_detail, promo, 
							   withdraw_by, withdraw_otp, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('update_date >= ', $timestart);
			$this->db->where('update_date <= ', $timeend); 
			$this->db->order_by('trx_date', 'DESC');
			$this->db->order_by('trx_time', 'DESC');
			$sql = $this->db->get('log_withdraw_backup');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_withdraw_by_date($trx_date){ 
			$result_array = "";
			$row = ""; 
			$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.amount_actual, l_dps.channel, l_dps.trx_id, 
							   l_dps.trx_date, l_dps.trx_time, l_dps.status, l_dps.remark,remark_internal,remark_internal_detail, l_dps.promo, 
							   l_dps.withdraw_by, l_dps.withdraw_otp, l_dps.update_date, mber.username, l_dps.slip');
			$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
			$this->db->where_not_in('l_dps.status', [2,5]); 
			$this->db->where('l_dps.trx_date >=', $trx_date." 00:00");
			$this->db->where('l_dps.trx_date <=', $trx_date." 23:59");
			$sql = $this->db->get('log_withdraw AS l_dps');

			$result_array = $sql->result_array();
			$row = $sql->row(); 

		$result['num_rows'] =  (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_withdraw_by_id($ids){ 
		$result_array = "";
		$row = ""; 
		$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.amount_actual, l_dps.channel, l_dps.trx_id, 
							l_dps.trx_date, l_dps.trx_time, l_dps.status, l_dps.remark,remark_internal,remark_internal_detail, l_dps.promo, 
							l_dps.withdraw_by, l_dps.withdraw_otp, l_dps.update_date, mber.username, mber.bank_accountnumber, mber.bank_name, mber.fname, mber.lname,mber.telephone,mber.choose_bank');
		$this->db->where('l_dps.id =', $ids);
		$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
		$sql = $this->db->get('log_withdraw AS l_dps');

		$result_array = $sql->result_array();
		$row = $sql->row(); 
		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function update_log_withdraw_by_id($log_withdraw_id,$wd_actual_amount,$remark,$withdraw_by='',$remark_internal_detail=''){
        if($withdraw_by==''){ $withdraw_by=$this->session->userdata("Username"); }
		$this->db->set('amount_actual', $wd_actual_amount);
		//$this->db->set('remark', $remark);
		if($remark_internal_detail!=''&&$remark_internal_detail!=null&&strlen(trim($remark_internal_detail))>0){
			$this->db->set('remark_internal_detail', $remark_internal_detail);
		} 
		$this->db->set('withdraw_by',$withdraw_by);
		$this->db->set('status', 1);
		$this->db->where('id', $log_withdraw_id);
		$this->db->update('log_withdraw');
	}
	public function update_log_withdraw_slip($log_withdraw_id,$slip){

		$this->db->set('slip', $slip);
		$this->db->where('id', $log_withdraw_id);
		$edit = $this->db->update('log_withdraw');
		if (!$edit) {
			return false;
		}else{
			return true;
		}

	}

	public function update_log_withdraw_by_id_s($log_withdraw_id,$remark,$status,$withdraw_by=''){
		if($withdraw_by==''){ $withdraw_by=$this->session->userdata("Username"); }
		$this->db->set('remark', $remark);
		$this->db->set('withdraw_by', $withdraw_by);
		$this->db->set('status', $status);
		$this->db->where('id', $log_withdraw_id);
		$this->db->update('log_withdraw');
	}

	public function search_log_game_member($member_no){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('log_game_access')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('logG.id, logG.member_no, logG.provider_id, logG.update_date, pvd.provider_name,logG.money_log');
			$this->db->where('logG.member_no =', $member_no);
			$this->db->where('1=CASE WHEN logG.product_type IS NULL THEN 1 ELSE logG.product_type=pvd.product_type END', "", false); 
			$this->db->order_by('logG.update_date', 'DESC');
			$this->db->join('lobby_control AS pvd', 'logG.provider_id = pvd.provider_id', 'left');
			$sql = $this->db->get('log_game_access AS logG');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_withdraw_by_padding($latest=100000){ 
		$result_array = "";
		$row = ""; 
		$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.amount_actual, l_dps.channel, l_dps.trx_id, 
							l_dps.trx_date, l_dps.trx_time, l_dps.status, l_dps.remark,remark_internal,remark_internal_detail, l_dps.promo, 
							l_dps.withdraw_by, l_dps.withdraw_otp, l_dps.update_date, mber.username, mber.fname, mber.lname, 
							mber.bank_name, mber.bank_accountnumber, mwl.main_wallet,mber.truewallet_phone,mber.truewallet_account,mber.telephone,mber.choose_bank'); 
		$this->db->where_in('l_dps.status', [2,5]);
		$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
		$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
		$this->db->limit($latest);
		$this->db->order_by('l_dps.update_date', 'ASC');
		$sql = $this->db->get('log_withdraw AS l_dps');

		$result_array = $sql->result_array();
		$row = $sql->row(); 
		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_log_withdraw_by_padding_member_no($member_no){

		$this->db->select('COUNT(l_dps.id) AS num_rows', FALSE);
        $this->db->where_in('l_dps.status', [2,5]);
		$this->db->where('l_dps.member_no =', $member_no);
		$num_rows = $this->db->get('log_withdraw AS l_dps')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('l_dps.id, l_dps.member_no, l_dps.amount, l_dps.amount_actual, l_dps.channel, l_dps.trx_id, 
							   l_dps.trx_date, l_dps.trx_time, l_dps.status, l_dps.remark,remark_internal,remark_internal_detail, l_dps.promo, 
							   l_dps.withdraw_by, l_dps.withdraw_otp, l_dps.update_date, mber.username, mber.fname, mber.lname, 
							   mber.bank_name, mber.bank_accountnumber, mwl.main_wallet');
            $this->db->where_in('l_dps.status', [2,5]);
			$this->db->where('l_dps.member_no =', $member_no);
			$this->db->join('members AS mber', 'l_dps.member_no = mber.id', 'left');
			$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
			$this->db->order_by('l_dps.update_date', 'DESC');
			$sql = $this->db->get('log_withdraw AS l_dps');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function insert_log_auto_withdraw($member_no, $amount, $StatusDescription, $resJSON,$withdraw_by=''){
		//uniqid(), date('Y-m-d'), date('H:i:s'), 
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

        if($withdraw_by==''){$withdraw_by=$this->session->userdata("Username");}
		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('status', $StatusDescription);
		$this->db->set('withdraw_by', $withdraw_by);
		$this->db->set('details', $resJSON);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_auto_withdraw');
	}

	public function clear_archive_data(){
		try {  
		date_default_timezone_set("Asia/Bangkok");
		$time = strtotime(date("Y-m-d H:i:s"));
		$limitdelete=100000;
		$time = $time - (6*60*60);
		$periodTime = date("Y-m-d H:i:s", $time);

		$this->db->where('platform', 'RT');
		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('ae_trx');
  
		$this->db->where('update_date < (NOW() - interval 3 DAY)', "", false); 
		$this->db->where_in('platform', ['KINGMAKER','SEXYBCRT']); 
		$this->db->limit($limitdelete);
		$this->db->delete('ae_trx');

		// $this->db->where('update_date < ', $periodTime);
		$this->db->where('update_date < (NOW() - interval 3 DAY)', "", false); 
		$this->db->limit($limitdelete);
		$this->db->delete('ae_api_log');

		// $this->db->where('update_date < ', '(NOW() - interval 6 hour)'); 
		$this->db->where('update_date < (NOW() - interval 3 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('sa_trx');

		// $this->db->where('update_date < ', '(NOW() - interval 6 hour)'); 
		$this->db->where('update_date < (NOW() - interval 3 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('sa_api_log');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('jk_trx');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('jk_api_log');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('evp_trx');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('evp_api_log');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('ka_trx');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('ka_api_log');

		// $this->db->where('update_date < ', $periodTime);
		$this->db->where('update_date < (NOW() - interval 3 DAY)', "", false);  
		$this->db->limit($limitdelete);
		$this->db->delete('ambp_trx');

	 
		// $this->db->where('update_date < ', $periodTime); 
		$this->db->where('update_date < (NOW() - interval 4 hour)', "", false); 
		$this->db->limit($limitdelete); 
		$this->db->delete('ambpg_trx');     
 
		  
		$this->db->where('update_date < (NOW() - interval 2 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('log_auto_bank');
 
		$this->db->where('update_date < (NOW() - interval 10 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('log_login');
 
		$this->db->where('update_date < (NOW() - interval 7 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('log_game_access');
 
		$this->db->where('update_date < (NOW() - interval 1 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('fg_trx');
 
		$this->db->where('update_date < (NOW() - interval 1 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('fg_api_log');
 
		$this->db->where('updated_date < (NOW() - interval 1 DAY)', "", false);
		$this->db->limit($limitdelete);
		$this->db->delete('jili_trx');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('isb_trx');

		$this->db->where('update_date < ', $periodTime);
		$this->db->limit($limitdelete);
		$this->db->delete('isb_api_log');
		
		$this->db->where('STATUS', 2);
		$this->db->limit($limitdelete);
		$this->db->delete('aff_transaction');

		$this->db->where('STATUS', 2);
		$this->db->where('updated_date < (NOW() - interval 2 DAY)', "", false); 
		$this->db->delete('aff_daily');
		 
	} catch (Exception $e) {
		write_file(FCPATH .'/tesxdata/delete'.date('YmdHi').'.txt',$e->getMessage()); 
	}	
		
	}
	public function clear_Userinfo_data($member_no){
		$this->db->where('member_no', $member_no);
		$this->db->delete('wallet_history');
 
	}
	public function clear_archive_data_pg(){
		date_default_timezone_set("Asia/Bangkok");
		$time = strtotime(date("Y-m-d H:i:s"));
		$time = $time - (15 * 60);
		$periodTime = date("Y-m-d H:i:s", $time);

		$this->db->where('update_date < ', $periodTime);
		$this->db->delete('pg_trx');
	}

	public function load_log_deposit_kbank($trx_id){ 
	    $this->db->select('COUNT(id) AS num_rows', FALSE); 
		$this->db->where('trx_id =', $trx_id);
		$num_rows = $this->db->get('log_deposit_kbank')->row()->num_rows; 
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id,trx_id, member_no, trx_id,update_date, trx_date, trx_time, trx_amount, from_bank, from_acc, full_msg');
			$this->db->where('trx_id =', $trx_id);
			$sql = $this->db->get('log_deposit_kbank');

			$row = $sql->row();
		}
	 
		$result['num_rows'] = $num_rows;
		$result['row'] = $row;
		return $result; 
	}

	public function load_log_deposit_scb($trx_id){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('trx_id =', $trx_id);
		$num_rows = $this->db->get('log_deposit_scb')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id,trx_id, member_no, trx_id,update_date, trx_date, trx_time, trx_amount, from_bank, from_acc, full_msg');
			$this->db->where('trx_id =', $trx_id);
			$sql = $this->db->get('log_deposit_scb');

			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['row'] = $row;
		return $result;
	}
	public function duplicate_log_deposit_scb($member_no,$amount,$date,$time){ 
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('trx_amount =', $amount);
		$this->db->where('trx_date =', $date);
		$this->db->where('trx_time =', $time);
		$num_rows = $this->db->get('log_deposit_scb')->row()->num_rows;  
		return ($num_rows>0?$num_rows*1:0);
	}
	public function search_log_delete_member($member_no,$timestart='',$timeend=''){   
		$select='id, member_no, amount, amount AS amount_actual, 0 AS balance_after';
		$select.=',1 AS channel, 0 AS trx_id,CAST(update_date AS DATE) AS trx_date, CAST(update_date AS time) AS trx_time, 1 AS status, remark, "" AS remark_internal, "" AS promo
		, admin_name AS withdraw_by,"" AS withdraw_otp,update_date';
		$this->db->select($select);
		$this->db->where('member_no =', $member_no); 
		$this->db->where('update_date >= ', $timestart);
		$this->db->where('update_date <= ', $timeend);
		$sql =$this->db->get('log_del_credit');
		$result_array = $sql->result_array();  
		$result['num_rows'] =  (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;    
		return $result;
	}
	public function insert_log_deposit_Vizplay($data=[]){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");  
		return $this->db->insert('log_deposit_vizpay', $data);
	 }
	 public function search_log_deposit_increase_by_date($trx_date,$trx_date2){   
		$result_array = ""; 
		$row = "";
		$this->db->select('count(id) as num, member_no, sum(amount) as amount, trx_date,
						channel');
		$this->db->where('status =','1');
		$this->db->where_in('channel', ['1','2','3']);
		if($trx_date && $trx_date2){
			$this->db->where('trx_date >=', $trx_date);
			$this->db->where('trx_date <=', $trx_date2);
		}
		$this->db->order_by("trx_date", "desc");
		$this->db->group_by('trx_date');
		
		
		$sql = $this->db->get('log_deposit'); 
		$result_array = $sql->result_array();   
		$row = $sql->row();
	
		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array; 
		$result['row'] = $row;
		return $result;
	}
}
?>