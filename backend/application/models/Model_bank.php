
<?php
class Model_bank extends CI_Model{
	public function list_bank_system(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('m_bank')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, bank_code, bank_short, short_name, full_name, img, update_date');
			$sql = $this->db->get('m_bank');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function SearchBankSysBybank_code($bank_code){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('bank_code =', $bank_code);
		$num_rows = $this->db->get('m_bank')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, bank_code, bank_short, short_name, full_name, img, update_date');
			$this->db->where('bank_code =', $bank_code);
			$sql = $this->db->get('m_bank');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function list_bank_setting(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('m_bank_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('mbs.id, mbs.bank_code, mbs.bank_account, mbs.status, mbs.type, mbs.get_trx, mbs.account_name, mb.bank_short, mb.short_name');
			$this->db->join('m_bank AS mb', 'mbs.bank_code = mb.bank_code', 'left');
			$sql = $this->db->get('m_bank_setting AS mbs');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function Get_bankByType($type=1){ 
		$result_array = "";
		$row = "";
		$this->db->select('mbs.id, mbs.bank_code, mbs.bank_account, mbs.status, mbs.type, mbs.get_trx, mbs.account_name, mb.bank_short, mb.short_name');
		$this->db->join('m_bank AS mb', 'mbs.bank_code = mb.bank_code', 'left');
		$this->db->where('type =', $type);
		$sql = $this->db->get('m_bank_setting AS mbs');

		$result_array = $sql->result_array();
		$row = $sql->row(); 

		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function search_list_bank_setting_by_id($bank_setting_id){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('id =', $bank_setting_id);
		$num_rows = $this->db->get('m_bank_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('mbs.id, mbs.bank_code, mbs.bank_account, mbs.status, mbs.type, mbs.get_trx, mbs.account_name, mb.bank_short, 
							   mbs.username, mbs.password');
			$this->db->where('mbs.id =', $bank_setting_id);
			$this->db->join('m_bank AS mb', 'mbs.bank_code = mb.bank_code', 'left');
			$sql = $this->db->get('m_bank_setting AS mbs');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_list_bank_setting_by_bank_account($bank_account){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('bank_account =', $bank_account);
		$num_rows = $this->db->get('m_bank_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('mbs.id, mbs.bank_code, mbs.bank_account, mbs.status, mbs.type, mbs.get_trx, mbs.account_name, mb.bank_short, 
							   mbs.username, mbs.password');
			$this->db->where('mbs.bank_account =', $bank_account);
			$this->db->join('m_bank AS mb', 'mbs.bank_code = mb.bank_code', 'left');
			$sql = $this->db->get('m_bank_setting AS mbs');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_list_bank_setting($bank_code,$status){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('bank_code =', $bank_code);
		$this->db->where('status =', $status);
		$this->db->where('type =', 1);
		$num_rows = $this->db->get('m_bank_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('mbs.id, mbs.bank_code, mbs.bank_account, mbs.account_name, mb.bank_short');
			$this->db->where('mbs.bank_code =', $bank_code);
			$this->db->where('mbs.status =', $status);
			$this->db->where('type =', 1);
			$this->db->join('m_bank AS mb', 'mbs.bank_code = mb.bank_code', 'left');
			$sql = $this->db->get('m_bank_setting AS mbs');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function search_log_auto_bank_by_date($update_date){

		$this->db->select('COUNT(lab.id) AS num_rows', FALSE);
		$this->db->where('lab.update_date >=', $update_date." 00:00");
		$this->db->where('lab.update_date <=', $update_date." 23:59");
		$num_rows = $this->db->get('log_auto_bank AS lab')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('lab.id, lab.bank_id, lab.log_msg, lab.update_date, mbst.account_name');
			$this->db->join('m_bank_setting AS mbst', 'lab.bank_id = mbst.id', 'left');
			$this->db->where('lab.update_date >=', $update_date." 00:00");
			$this->db->where('lab.update_date <=', $update_date." 23:59");
			$sql = $this->db->get('log_auto_bank AS lab');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function bank_inserted($bank_code,$bank_account,$account_name,$type,$username,$password){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;

		$this->db->set('bank_code', $bank_code);
		$this->db->set('bank_account', $bank_account);
		$this->db->set('account_name', $account_name);
		$this->db->set('type', $type);
		$this->db->set('username', $username);
		$this->db->set('password', $password);
		$this->db->set('updated_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('m_bank_setting');
		$bank_setting_id = $this->db->insert_id();

		
		$this->CreatedTableSCBAuto($bank_setting_id);
		

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function bank_updated($bank_setting_id,$bank_code,$bank_account,$account_name,$type){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;

		$this->db->set('bank_code', $bank_code);
		$this->db->set('bank_account', $bank_account);
		$this->db->set('account_name', $account_name);
		$this->db->set('type', $type); 
		$this->db->set('updated_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('id', $bank_setting_id);
		$this->db->update('m_bank_setting');

		if($this->db->table_exists("auto_bank_scb_".$bank_setting_id) == false){
			$this->CreatedTableSCBAuto($bank_setting_id);
		}

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function delete_bank_setting($bank_setting_id){
		if($this->db->table_exists("auto_bank_scb_".$bank_setting_id) == true){
			$this->db->query("DROP TABLE IF EXISTS auto_bank_scb_".$bank_setting_id);
		}
		$this->db->where('id', $bank_setting_id);
		$this->db->delete('m_bank_setting');
	}

	public function update_bank_setting_status($bank_setting_id,$pg_status){
		$this->db->set('status', $pg_status);
		$this->db->where('id', $bank_setting_id);
		$this->db->update('m_bank_setting');
	}

	public function update_bank_setting_trx($bank_setting_id,$pg_status){
		$this->db->set('get_trx', $pg_status);
		$this->db->where('id', $bank_setting_id);
		$this->db->update('m_bank_setting');
	}

	private function CreatedTableSCBAuto($bank_setting_id){
		$sql = "CREATE TABLE auto_bank_scb_".$bank_setting_id." (
						  auto_id int(11) NOT NULL AUTO_INCREMENT,
						  auto_scb_id smallint(6) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_bank_id varchar(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_date date NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_time time NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_amount decimal(10,2) NOT NULL DEFAULT 0.00 COLLATE 'utf8mb4_general_ci',
						  auto_amount_wd decimal(10,2) NOT NULL DEFAULT 0.00 COLLATE 'utf8mb4_general_ci',
						  auto_username varchar(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_status int(11) NOT NULL DEFAULT 0 COLLATE 'utf8mb4_general_ci',
						  auto_bank_type varchar(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_name varchar(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_channel varchar(20) DEFAULT NULL COLLATE 'utf8mb4_general_ci',
						  auto_datetime datetime NULL DEFAULT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
						  PRIMARY KEY (auto_id) USING BTREE,
						  INDEX auto_bank_id (auto_bank_id) USING BTREE,
						  INDEX auto_date (auto_date) USING BTREE,
						  INDEX auto_username (auto_username) USING BTREE,
						  INDEX auto_status (auto_status) USING BTREE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			$this->db->query($sql);
	}

	public function sumCashINSCB($bank_setting_id){
		$this->db->select('SUM(auto_amount) as auto_amount', FALSE);
		$this->db->where('auto_amount >', 0);
		$auto_amount = $this->db->get('auto_bank_scb_'.$bank_setting_id)->row()->auto_amount;
		


		return $auto_amount;
	}

	public function sumCashOUTSCB($bank_setting_id){
		$this->db->select('SUM(auto_amount) as auto_amount', FALSE);
		$this->db->where('auto_amount_wd <', 0);
		$auto_amount = $this->db->get('auto_bank_scb_'.$bank_setting_id)->row()->auto_amount;
		


		return $auto_amount;
	}

	public function SearchSCBAutoByBank_setting_id($bank_setting_id){
		$this->db->select('COUNT(auto_id) AS num_rows', FALSE);
		$num_rows = $this->db->get('auto_bank_scb_'.$bank_setting_id)->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('auto_id, auto_scb_id, auto_bank_id, auto_date, auto_time, auto_amount, auto_amount_wd, auto_username, 
							   auto_status, auto_bank_type, auto_name, auto_channel, auto_datetime');
			$sql = $this->db->get('auto_bank_scb_'.$bank_setting_id);

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function CheckSCBAutoDuplicate($bank_setting_id,$auto_amount,$auto_bank_id,$auto_date,$auto_time,$auto_name){
		$num_rows = 999;
		if($this->db->table_exists("auto_bank_scb_".$bank_setting_id) == true){
			$this->db->select('COUNT(auto_id) AS num_rows', FALSE);
			$this->db->where('auto_scb_id = ', $bank_setting_id);
			$this->db->where('auto_amount =', $auto_amount);
			$this->db->like('auto_bank_id', $auto_bank_id, 'both');
			$this->db->where('auto_date =', $auto_date);
			$this->db->where('auto_time =', $auto_time);
			$this->db->like('auto_name', $auto_name, 'both');



			$num_rows = $this->db->get('auto_bank_scb_'.$bank_setting_id)->row()->num_rows;
		}

		return $num_rows;
	}

	public function auto_bank_scb_inserted($bank_setting_id,$auto_bank_id,$auto_date,$auto_time,$auto_amount,$auto_amount_wd,$auto_username,
										   $auto_status,$auto_bank_type,$auto_name,$auto_channel){

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		if($this->db->table_exists("auto_bank_scb_".$bank_setting_id) == true){

			$this->db->set('auto_scb_id', $bank_setting_id);
			$this->db->set('auto_bank_id', $auto_bank_id);
			$this->db->set('auto_date', $auto_date);
			$this->db->set('auto_time', $auto_time);
			$this->db->set('auto_amount', $auto_amount);
			$this->db->set('auto_amount_wd', $auto_amount_wd);
			$this->db->set('auto_username', $auto_username);		
			$this->db->set('auto_status', $auto_status);
			$this->db->set('auto_bank_type', $auto_bank_type);
			$this->db->set('auto_name', $auto_name);
			$this->db->set('auto_channel', $auto_channel);
			$this->db->insert('auto_bank_scb_'.$bank_setting_id);
		}
	}

	public function inserted_duplicate_acct($acct_no,$bank_code,$dup_acct_no,$amount,$trx_datetime,$type){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;

		$this->db->set('acct_no', $acct_no);
		$this->db->set('bank_code', $bank_code);
		$this->db->set('dup_acct_no', $dup_acct_no);
		$this->db->set('amount', $amount);
		$this->db->set('trx_datetime', $trx_datetime);
		$this->db->set('type', $type);
		$this->db->insert('duplicate_acct');

		return $this->db->insert_id();;

	}

	public function inserted_log_sms($phone_number,$sms_content,$trx_id){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('phone_number', $phone_number);
		$this->db->set('sms_content', $sms_content);
		$this->db->set('trx_id', $trx_id);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('log_sms');
		if ($this->db->affected_rows() <= 0) {

		    $this->db->set('phone_number', $phone_number);
			$this->db->set('sms_content', $sms_content);
			$this->db->set('trx_id', $trx_id);
			$this->db->set('update_date', $DateTimeNow);
			$this->db->insert('log_sms_duplicate');

		    echo json_encode([
		        'code' => 1034,
		        'msg' => 'Transaction duplicated or already deposit'
		    ]);
		    exit();
		}

	}

	public function log_sms_genTrxID(){
		$trx_id = uniqid();

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('trx_id =', $trx_id);
		$num_rows = $this->db->get('log_sms')->row()->num_rows;
		
	    if ($num_rows > 0) {
	        $this->log_sms_genTrxID();
	    } else {
	        return $trx_id;
	    }
	}

}
?>