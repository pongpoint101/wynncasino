
<?php
class Model_promo extends CI_Model{
	public function SearchPromofree50Bymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promofree50')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect, status, arrival_date, update_by,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promofree50');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchPromofree60Bymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promofree60')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect, status, arrival_date, update_by,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promofree60');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchPromo300pBymember_no($member_no){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promop120')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, deposit_amount, withdraw_accept_amount, promo_amount, turnover_expect, status, accept_date,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('DATE_FORMAT(accept_date, "%Y-%m-%d") = CURDATE()');
			$sql = $this->db->get('promop120');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function insert_promo_casino_300p($member_no,$latest_deposit_amount,$promo_cal_result,$expect_turnover){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('deposit_amount', $latest_deposit_amount);
		$this->db->set('promo_amount', $promo_cal_result);
		$this->db->set('turnover_expect', $expect_turnover);
		$this->db->set('accept_date', $DateTimeNow);
		$this->db->insert('promop120');
	}

	public function SearchPromop100pBymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promop100p')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect, status, arrival_date, update_by,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promop100p');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function SearchPromop50pBymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promop50p')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect, status, arrival_date, update_by,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promop50p');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchPromop10pBymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promop10p')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect, status, arrival_date, update_by,
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promop10p');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function insert_profree50($member_no,$status,$full_amount,$actual_withdraw,$win_expect){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('win_expect', $win_expect);
		$this->db->set('status', $status);
		$this->db->set('arrival_date', $DateTimeNow);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('promofree50');
	}
	public function insert_profree60($member_no,$status,$full_amount,$actual_withdraw,$win_expect){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('win_expect', $win_expect);
		$this->db->set('status', $status);
		$this->db->set('arrival_date', $DateTimeNow);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('promofree60');
	}
	public function update_status_promofree60_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('status !=', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('promofree60');
	}
	public function update_status_profree50($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('status !=', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('promofree50');
	}
	public function update_status_profree50_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('status !=', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('promofree50');
	}
	public function update_status_promop100p_withdraw($member_no,$status,$actual_withdraw,$full_amount,$update_by=''){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
        if($update_by==''){$update_by=$this->session->userdata("Username");}
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('status', $status);
		$this->db->set('update_by',$update_by);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop100p');
	}
	public function update_status_promop50p_withdraw($member_no,$status,$actual_withdraw,$full_amount,$update_by=''){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		if($update_by==''){$update_by=$this->session->userdata("Username");}
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('status', $status);
		$this->db->set('update_by', $update_by);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop50p');
	}
	public function update_status_promop50p_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop50p');
	}

	public function update_status_promo_happy_time_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promo_happy_time');
	}
	public function update_status_promo_hny_50($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('status!=', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('promo_hny50');
	}

	public function update_status_promo_cc200_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		
		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promo_cc200');
	}

	public function update_status_promo_casino_300p_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		
		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop120');
	}

	public function update_status_promop10p_withdraw($member_no,$status,$actual_withdraw,$full_amount,$update_by=''){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		if($update_by==''){$update_by=$this->session->userdata("Username");}
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('status', $status);
		$this->db->set('update_by', $update_by);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop10p');
	}
	public function update_status_profree50_withdraw($member_no,$status,$actual_withdraw,$full_amount,$update_by=''){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
        if($update_by==''){$update_by=$this->session->userdata("Username");}
		$this->db->set('actual_withdraw', $actual_withdraw);
		$this->db->set('full_amount', $full_amount);
		$this->db->set('status', $status);
		$this->db->set('update_by', $update_by);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promofree50');
	}
	public function SearchPromoWb100Bymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promo_wb100')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promo_wb100');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function insert_PromoWb100($member_no,$amount){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('promo_wb100');
	}
	public function SearchPromoMC25Bymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('promo_date >=', date('Y').'-12-24');
		$this->db->where('promo_date <=', date('Y').'-12-25');
		$num_rows = $this->db->get('promo_mc25')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, promo_date, promo_time, update_date');
			$this->db->where('member_no =', $member_no);
			$this->db->where('promo_date >=', date('Y').'-12-24');
			$this->db->where('promo_date <=', date('Y').'-12-25');
			$sql = $this->db->get('promo_mc25');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function insert_PromoMC25($member_no,$amount){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$PGDateNow = date("Y-m-d");
		$PGTimeNow = date('H:m:s');

		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('promo_date', $PGDateNow);
		$this->db->set('promo_time', $PGTimeNow);
		$this->db->insert('promo_mc25');
	}
	//promo_hny30
	public function SearchPromoHNY30Bymember_no($member_no){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promo_hny30')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, member_no, amount, promo_date, promo_time, update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promo_hny30');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	//promo_hny50
	public function SearchPromoHNY50Bymember_no($member_no){
		$filter_date=date("Y-m-d");
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('DATE(accept_date)', $filter_date);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('promo_hny50')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id,deposit_amount,promo_amount,turnover_expect AS win_expect,update_date');
			$this->db->where('DATE(accept_date)', $filter_date);
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('promo_hny50'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function insert_PromoHNY50($member_no,$amount,$deposit_amount){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s"); 
        $this->ClearProOrTurnOver($member_no,true); 
		$this->db->set('member_no', $member_no);
		$this->db->set('deposit_amount', $deposit_amount);
		$this->db->set('promo_amount', $amount); 
		$this->db->set('turnover_expect', ($deposit_amount+$amount)*2); 
		$this->db->set('accept_date', $DateTimeNow);
		$this->db->insert('promo_hny50'); 
	}

	public function insert_PromoHNY30($member_no,$amount){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$PGDateNow = date("Y-m-d");
		$PGTimeNow = date('H:m:s');

		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('promo_date', $PGDateNow);
		$this->db->set('promo_time', $PGTimeNow);
		$this->db->insert('promo_hny30');
	}
	public function insert_PromoDeposit300($member_no,$amount){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$this->db->set('member_no', $member_no);
		$this->db->set('amount', $amount);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('promo_deposit300');
	}
	public function insert_PromoDepositfrequency($member_no,$amount,$promo_id){
		date_default_timezone_set('Asia/Bangkok'); 
		$data =[
			'member_no' => $member_no, 
			'promo_amount' => $amount,
			'turnover_expect' => $amount*2,
			'promo' => $promo_id
		   ];   
	     $this->db->insert('promo_deposit_frequency', $data);  
	   return $this->db->affected_rows(); 
	}
	public function update_status_promop100p($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop100p');
	}
	public function update_status_promop100p_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop100p');
	}
	public function update_status_promop10p($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop10p');
	}
	public function update_status_promop10p_02($member_no,$status){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop10p');
	}

	public function update_status_promo_casino_2p_02($member_no,$status){

		$this->db->set('status', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('promop121');
	}
	public function Update_member_promo_last($member_id,$promo_id,$member_last_deposit){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$this->db->set('member_promo', $promo_id);
		if($member_last_deposit != ''){
			$this->db->set('member_last_deposit', $member_last_deposit);
		}
		$this->db->where('id', $member_id);
		$this->db->update('members');
	}
	public function promo_reward_month_blocked($member_no,$channel){
		date_default_timezone_set('Asia/Bangkok'); 

		$this->db->select('channel,remark,amount,trx_date', FALSE);
		$this->db->where('member_no =', $member_no);  
		$this->db->where_in('channel', [16,17,18]); 
		$this->db->where('YEAR(update_date)=', date('Y')); 
		$this->db->where('MONTH(update_date)=',date('m')); 
		$res=$this->db->get('reward_monthly');
		
        if ($res->num_rows() > 0) {
            $res = $res->row();
			return  $res; 
          }
		return null; 
	}
	public function promo_frequency_blocked($member_no,$pro_amount,$daynum){
		date_default_timezone_set('Asia/Bangkok');  
		$result = $this->db->query("CALL spc_deposit_frequency($member_no,$pro_amount,$daynum);"); 
		$res=$result->result_array();
		mysqli_next_result($this->db->conn_id);
		$result->free_result(); 
		$datatfreq=[];$checkfreq=0;$old_trx_date=null; 
		for ($i=0; $i < 15; $i++) {  
			if(isset($res[$i])){
			    $vv=$res[$i]; 
				if($old_trx_date==$vv['trx_date']){continue;}
				if ($vv['daydiff']>1) {
					$checkfreq=1;
				}
				if ($checkfreq==0) {
				$datatfreq[]=$vv;
			   } 
			   $old_trx_date=$vv['trx_date'];
			}
		  } 
		return $datatfreq; 
	}
	public function promo_blocked($member_no,$promo_id,$isReceivePro,$checkTruewallet=2,$checkLastdeposit=true){

		$errCode = 200;
		$errMsg = "";

		 $skipChkBalance = 0;
		  if ($promo_id == 21||$promo_id == 22) {
		   $skipChkBalance = 1;
		 } 
		 //--------------------------------------------------------------------------------------------------------------
		    $this->db->select('COUNT(id) AS num_rows', FALSE);
			$this->db->where('member_no =', $member_no);
			$this->db->where('status = ', 2);
			$this->db->where('channel <= ', 3);
			$pedding_num_rows = $this->db->get('log_deposit')->row()->num_rows;
			if ($pedding_num_rows > 0) {
				$result['errCode']= 402;
				$result['errMsg']= "สมาชิกไม่สามารถรับโปรฯ นี้ได้ เนื่องจากมียอดฝากค้าง";
				return $result;
		    }
		 //--------------------------------------------------------------------------------------------------------------
		 $this->db->select('id,main_wallet');
		 $this->db->where('member_no =', $member_no);
		 $itemMWallet = $this->db->get('member_wallet')->row(); 
		 if ($skipChkBalance==0) { 
			 if (($itemMWallet->main_wallet > 10) && ($skipChkBalance == 0)) {
				 $result['errCode'] = 402;
				 $result['errMsg'] = "ยูสเซอร์นี้ มีเงินค้างในกระเป๋ามากกว่า 10 บ.({$itemMWallet->main_wallet})";
				 return $result;
			  } 
		 }
		 //--------------------------------------------------------------------------------------------------------------
		 $this->db->select('member_promo,member_last_deposit');
		 $this->db->where('id =', $member_no);
		 $itemMember = $this->db->get('members')->row();
 
		//  if ($itemMember->member_promo == 13 || $itemMember->member_promo == 14) {
		// 	$result['errCode']= 402;
		// 	$result['errMsg']= "สมาชิกรับเครดิตฟรี ไม่สามารถรับโบนัสใด ๆ ได้ค่ะ";
		// 	return $result;
		//  }
		 if ($itemMember->member_last_deposit == 16 || $itemMember->member_last_deposit == 17|| $itemMember->member_last_deposit == 18) {
			$result['errCode']= 402;
			$result['errMsg'] = "ยอดเงินจากค่าคอมมิชชั่นหรือแนะนำเพื่อน ไม่สามารถรับโบนัสใด ๆ ได้ค่ะ";
			return $result;
		 }

     //#####################################################################################################################
	    $this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$this->db->where('status =', 1);
		$this->db->where('(channel<=3 OR channel=5)');
		$promo_num_rows = $this->db->get('log_deposit')->row()->num_rows;

	   if ($checkTruewallet==2) { 
		 $this->db->select('COUNT(id) AS num_rows', FALSE);
		 $this->db->where('id =', $member_no);
		 $this->db->where('member_last_deposit =', 2);
		 $tw_num_rows = $this->db->get('members')->row()->num_rows;
		 if ($tw_num_rows > 0) { // Last deposit from True Wallet
			$result['errCode']= 402;
			$result['errMsg']= "ยอดฝากจาก True Wallet ไม่สามารถรับโบนัสได้นะคะ";
			return $result;
		   } 
	    } 
     //#####################################################################################################################
       if ($checkLastdeposit) { 
		$this->db->select('promo,member_no, remark');
		$this->db->where('member_no =', $member_no);
		$this->db->where('status =', 1);
		$this->db->where('(channel<=3 OR channel=5)');
		$this->db->order_by('trx_date', 'DESC');
		$this->db->order_by('trx_time', 'DESC');
		$sql = $this->db->get('log_deposit');
	
		$row = $sql->row(); 
		if (@$row->member_no>0) { 
		     if ($row->promo != (-1)){ 
			$result['errCode']= 402;
			$result['errMsg']= "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ (".$row->promo.")";
			return $result;
		  } 
	    }
	  } 
      //#####################################################################################################################
	//   if($promo_id != 1){ /// ยอดรับเครดิตฟรี 50
	// 	   if($itemMWallet->main_wallet < 10) {
	// 	   $result['errCode']= 402;
	// 	   $result['errMsg']= "สมาชิกไม่สามารถรับโปรฯ ใดๆ ได้ เนื่องจากยอดฝากต่ำกว่าที่กำหนด (10 บ.)";
	// 	   return $result;
	//      } 
    //    }
	   // ยอดรับเครดิตฟรี 50 60
	   
	//    if($promo_id == 1||$promo_id==16){ 
	// 	$rowfree50=$this->SearchPromofree($member_no,'free50');
	// 	$rowfree60=$this->SearchPromofree($member_no,'free60');
	// 	if ($rowfree50['num_rows']>0) {
	// 	   $result['errCode']= 402;
	// 	   $result['errMsg']= "สมาชิกไม่สามารถรับโปรฟรี 50 ได้ เนื่องจากเคยรับไปแล้ว";	 
	// 	   return $result; 
	// 	  }else if($rowfree60['num_rows']>0){
	// 	   $result['errCode']= 402;
	// 	   $result['errMsg']= "สมาชิกไม่สามารถรับโปรฟรี 60 ได้ เนื่องจากเคยรับไปแล้ว";
	// 	   return $result;	  
	// 	}
		if($promo_num_rows> 0&&$promo_id == 13){
			// $result['errCode']= 402;
			// $result['errMsg']= "สมาชิกไม่สามารถรับโปรฯ เครดิตรี 200 เนื่องจากมียอดฝากแล้ว ให้ใช้โปรฯเครดิตฟรี 60 (เคยฝาก)";
			// return $result;
		} 
	//    }  
	 //--------------------------------------------------------------------------------------------------------------
		if($isReceivePro == 1){ //รับโปรได้ครั้งเดียว
			$resultPro = $this->Model_member->SearchMemberPromoWhere($member_no,$promo_id);
			if($resultPro['num_rows'] > 0){
				$result['errCode']= 402;
				$result['errMsg']= "เคยรับโปรฯ นี้ไปแล้วค่ะ ".$resultPro['row']->update_date;
				return $result;
			}
		}  
		$result['errCode'] = $errCode;
		$result['errMsg'] = $errMsg;
		return $result;
	}
	//promo_free 50 60
	public function SearchPromofree($member_no,$type='free50'){ 
		$result_array = "";
		$row = ""; 
		$this->db->select('id, member_no, full_amount, actual_withdraw, win_expect,status,arrival_date,update_by, update_date');
		$this->db->where('member_no =', $member_no);
		if($type=='free50'){
			$sql = $this->db->get('promofree50');
		}else if($type =='free60'){
			$sql = $this->db->get('promofree60');
		}
		$result_array = $sql->result_array();
		$row = $sql->row(); 
		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
public function ClearProOrTurnOver($member_no,$turnover=true){
	// $this->update_status_promo_casino_2p_02($member_no,2);
	// $this->update_status_promop10p_02($member_no,2);
	// $this->update_status_promop100p_02($member_no,2);
	// $this->update_status_promop50p_02($member_no,2);
	// $this->update_status_promo_happy_time_02($member_no,2);
	// $this->update_status_promo_hny_50($member_no,2);
	// $this->update_status_promo_cc200_02($member_no,2);
	// $this->update_status_promo_casino_300p_02($member_no,2);
	$this->update_status_promofree60_02($member_no,2);
	$this->update_status_profree50_02($member_no,2);
	if($turnover){
    	$this->Model_member->update_member_turnover($member_no);
	} 
   }
   public function insert_reward_monthly($member_no,$channel,$remark,$amount){ 
	// $this->db->select('id,main_wallet');
	// $this->db->where('member_no =', $member_no);
	// $main_wallet = $this->db->get('member_wallet')->row()->main_wallet;  
	$this->db->set('member_no', $member_no);
	$this->db->set('channel', $channel);
	$this->db->set('remark', $remark);
	$this->db->set('amount', $amount); 
	// $this->db->set('turnover_expect', ($main_wallet+$amount)*5);  
	$this->db->insert('reward_monthly');
  }	
}
?>