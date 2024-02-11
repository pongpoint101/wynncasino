
<?php
class Model_member extends CI_Model
{
	public function __construct()
	{
		$this->load->library("ConfigData");
		$this->load->model('read/Model_rbank'); 
	}
	public function checkUser($username)
	{
		///username
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('username =', $username);
		$this->db->where('level =', 1);
		$num_rows = $this->db->get('employees')->row()->num_rows;
		$result_array = "";
		$row = "";
		
		$result['num_rows'] = $num_rows;
		
		return $result;
	}
	public function employees_inserted($fname, $username, $password, $password_change, $level = 1)
	{

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$GenUserPass = password_hash($password, PASSWORD_DEFAULT);


		$EmployeesRS = $this->Model_member->SearchEmployeesByUsername($username);
		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;
		if ($EmployeesRS['num_rows'] > 0) {
			$bool = false;
			$boolError = 1;
			$MessageErrorText = "ชื่อผู้ใข้งานซ้ำ !!";
		} else {

			$this->db->set('username', $username);
			$this->db->set('fname', $fname);
			$this->db->set('password', $GenUserPass);
			$this->db->set('status', '1');
			$this->db->set('password_change', $password_change);
			$this->db->set('level', $level);
			$this->db->set('lastaccess', $DateTimeNow);
			$this->db->set('create_at', $DateTimeNow);
			$this->db->insert('employees');
		}

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);exit();
	}

	public function employees_updated($fname, $password, $pg_ids, $password_change,$depositlimit=4999)
	{

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$GenUserPass = password_hash($password, PASSWORD_DEFAULT);

		$bool = true;
		$MessageErrorText = "";
		$this->db->select('username', FALSE); 
		$this->db->where('id', $this->encryption->decrypt($this->session->userID));
		$row_emp= $this->db->get('employees')->row();

		$boolError = 0;
		$this->db->set('fname', $fname);
		if($password!=null&&$password!=''){
			$this->db->set('password', $GenUserPass); 
			$this->db->set('password_change', $password_change); 
		} 
		if($depositlimit>0&&$depositlimit!=4999&&can('admin/add_edit_member')){
	      $this->db->set('deposit_limit', $depositlimit);	
		} 
		$this->db->where('id', $pg_ids);
		$this->db->update('employees');

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);exit();
	}

	public function member_updated($fname, $lname, $pg_ids, $bank_accountnumber, $bank_code, $line_id, $setting_bank_id, $telephone, $password)
	{

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$boolError = 0;
		if ($fname != NULL) {
			$this->db->set('fname', $fname);
		} else {
			$this->db->set('fname', NULL);
		}
		if ($lname != NULL) {
			$this->db->set('lname', $lname);
		} else {
			$this->db->set('lname', NULL);
		}
		if ($telephone != NULL) {
			$this->db->set('telephone', $telephone);
		} else {
			$this->db->set('telephone', NULL);
		}
		if ($bank_accountnumber != NULL) {
			$this->db->set('bank_accountnumber', $bank_accountnumber);
		} else {
			$this->db->set('bank_accountnumber', NULL);
		}
		if ($bank_code != NULL) {
			if ($bank_code != 0) {
				$RSBankSYS = $this->Model_bank->SearchBankSysBybank_code($bank_code);
				if ($RSBankSYS['num_rows'] > 0) {
					$this->db->set('bank_name', $RSBankSYS['row']->bank_short);
				} else {
					$this->db->set('bank_name', NULL);
				}

				$this->db->set('bank_code', $bank_code);
			} else {
				$this->db->set('bank_code', NULL);
			}
		} else {
			$this->db->set('bank_code', NULL);
		}
		if ($password != NULL) {

			$this->db->set('passwordText', $password);
			$this->db->set('password', hash("sha512", $password . '#mTbd6w#qtDBnUw7W$ph^h6%&p!U@6'));
		}
		if ($line_id != NULL) {
			$this->db->set('line_id', $line_id);
		} else {
			$this->db->set('line_id', NULL);
		}
		if ($setting_bank_id != NULL) {
			$this->db->set('setting_bank_id', $setting_bank_id);
		} else {
			$this->db->set('setting_bank_id', NULL);
		}
		$this->db->set('update_at', $DateTimeNow);
		$this->db->where('id', $pg_ids);
		$this->db->update('members');
	}

	public function member_updated_02($pg_ids, $bank_accountnumber, $bank_code, $line_id, $setting_bank_id, $telephone, $password, $scb_scb, $scb_other, $kbank_kbank, $truewallet_account, $aff,$allowturn=0, $aff_percent1, $aff_percent2, $aff_percent_use_default, $type_member=1,$com_use_default=-1,$cashbackrate=-1,$ptruewallet_phone=null,$pchoose_bank=null)
	{

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s"); 
        $t=$this->SearchMemberPromoBymember_no2($pg_ids);
		if($t['row']->ignore_zero_turnover!=$allowturn&&$allowturn==1){
			$this->db->select('main_wallet');
		 	$this->db->where('member_no =', $pg_ids);
		 	$itemMWallet = $this->db->get('member_wallet')->row(); 
			if($t['row']->member_promo!=0&&$itemMWallet->main_wallet>10){  return 402; }  
		} 
	    if(($ptruewallet_phone !=null&&trim($ptruewallet_phone)!='')&&($bank_accountnumber !=null&&trim($bank_accountnumber)!='')
	     &&($t['row']->bank_accountnumber !=$bank_accountnumber)){ // เพิ่มข้อมูลไม่มี บัญชีธนาคาร
		     $bank_acc=$this->Model_member->GetBankAccount($bank_code,$bank_accountnumber);
			 if (@$bank_acc['firstName']!=null&&trim(@$bank_acc['firstName'])!='') {
                 if(!($t['row']->fname==@$bank_acc['firstName']&&$t['row']->lname==@$bank_acc['lastName'])){ 
					return 405;
				 }
			  }else{
				return 406;
		     }
	    }else if(($ptruewallet_phone !=null&&trim($ptruewallet_phone)!='')&&($bank_accountnumber !=null&&trim($bank_accountnumber)!='')
		      &&($t['row']->truewallet_phone !=$ptruewallet_phone)){ // เพิ่มข้อมูลไม่มี true wallet
				$bank_acc=$this->Model_member->GetTrueWalletAccount($ptruewallet_phone); 
				if (@$bank_acc['firstName']!=null&&trim(@$bank_acc['firstName'])!='') {
					if(!($t['row']->fname==@$bank_acc['firstName']&&$t['row']->lname==@$bank_acc['lastName'])){ 
					   return 407;
					}
				 }else{
				   return 408;
				}
	   } 
	   $mbr_bank=$this->SearchAccountduplicate($pg_ids,null,$bank_accountnumber, $bank_code); 
	   if($mbr_bank['num_rows']>0){
		 return 409;
	   } 
	   $mbr_bank=$this->SearchAccountduplicate($pg_ids,$ptruewallet_phone); 
	   if($mbr_bank['num_rows']>0){
		return 410;
	   } 
	   if($bank_code != 0) {
		 $RSBankSYS = $this->Model_bank->SearchBankSysBybank_code($bank_code);
	    }
		$boolError = 0; 
		if ($telephone != NULL) {
			$this->db->set('telephone', $telephone);
		} else {
			$this->db->set('telephone', NULL);
		}
		if ($aff != NULL) {
			$this->db->set('aff_type', $aff);
		} else {
			$this->db->set('aff_type', NULL);
		}
		if ($aff_percent1 != NULL) {
			$this->db->set('aff_percent_l1', $aff_percent1);
		} else {
			$this->db->set('aff_percent_l1', NULL);
		}
		if ($aff_percent2 != NULL) {
			$this->db->set('aff_percent_l2', $aff_percent2);
		} else {
			$this->db->set('aff_percent_l2', NULL);
		}
		if ($aff_percent_use_default != NULL) {
			$this->db->set('aff_percent_use_default', $aff_percent_use_default);
		} else {
			$this->db->set('aff_percent_use_default', NULL);
		}
		if ($type_member != NULL) {
			$this->db->set('type_member', $type_member);
		} else {
			$this->db->set('type_member', 1);
		}
		
		$this->db->set('ignore_zero_turnover', $allowturn);

		if ($bank_accountnumber != NULL) {
			$this->db->set('bank_accountnumber', $bank_accountnumber);
			$this->db->set('scb_scb', $scb_scb);
			$this->db->set('scb_other', $scb_other);
			$this->db->set('kbank_kbank', $kbank_kbank);
		} else {
			$this->db->set('bank_accountnumber', NULL);
			$this->db->set('scb_scb', NULL);
			$this->db->set('scb_other', NULL);
			$this->db->set('kbank_kbank', NULL);
		}
		if ($bank_code != NULL) {
			if ($bank_code != 0) {
				//$RSBankSYS = $this->Model_bank->SearchBankSysBybank_code($bank_code);
				if ($RSBankSYS['num_rows'] > 0) {
					$this->db->set('bank_name', $RSBankSYS['row']->bank_short);
				} else {
					$this->db->set('bank_name', NULL);
				}

				$this->db->set('bank_code', $bank_code);
			} else {
				$this->db->set('bank_code', NULL);
			}
		} else {
			$this->db->set('bank_code', NULL);
		}
		if ($password != NULL) {

			$this->db->set('passwordText', $password);
			$this->db->set('password', hash("sha512", $password . '#mTbd6w#qtDBnUw7W$ph^h6%&p!U@6'));
		}
		if ($line_id != NULL) {
			$this->db->set('line_id', $line_id);
		} else {
			$this->db->set('line_id', NULL);
		}
		if ($setting_bank_id != NULL) {
			$this->db->set('setting_bank_id', $setting_bank_id);
		} else {
			$this->db->set('setting_bank_id', NULL);
		}
		$this->db->set('truewallet_phone', $ptruewallet_phone);
		$this->db->set('choose_bank', $pchoose_bank); 
		$this->db->set('update_at', $DateTimeNow);
		$this->db->set('truewallet_account', $truewallet_account);
		$this->db->where('id', $pg_ids);
		$this->db->update('members');
		return 200;
	}

	public function SearchEmployeesByUsername($username)
	{
		///username
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('username =', $username);
		$num_rows = $this->db->get('employees')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, username, password, fname, lastaccess,level,password_change,ip, create_at, status,deposit_limit');
			$this->db->where('username =', $username);
			$sql = $this->db->get('employees');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchEmployeesByIDs($ids)
	{
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('id =', $ids);
		$num_rows = $this->db->get('employees')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, username, password, fname, lastaccess,level,password_change,ip, create_at, status, level,deposit_limit');
			$this->db->where('id =', $ids);
			$sql = $this->db->get('employees');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function ListEmployees()
	{
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('employees')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, username, password, fname, lastaccess,level,password_change,ip, create_at, status,deposit_limit');
			$this->db->order_by('create_at', 'DESC');
			$sql = $this->db->get('employees');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function update_employee_status($pg_ids, $pg_status)
	{
		$this->db->set('status', $pg_status);
		$this->db->where('id', $pg_ids);
		$this->db->update('employees');
	}
	public function update_member_status($pg_ids, $pg_status)
	{
		$this->db->set('status', $pg_status);
		$this->db->where('id', $pg_ids);
		$this->db->update('members');
		
		$this->db->set('log_code', 200);
		$this->db->where('member_no', $pg_ids);
		$this->db->update('log_systeme'); 
	}
	public function reset_member_promo($pg_ids)
	{
		$this->db->set('member_promo', 0);
		$this->db->where('id', $pg_ids);
		$this->db->update('members');
	}
	public function reset_member_promo_02($member_no)
	{
		$this->db->set('member_promo', 0);
		$this->db->set('member_last_deposit', 1);
		$this->db->where('id', $member_no);
		$this->db->update('members');
	}
	public function reset_member_promo_truewallet($member_no)
	{
		$this->db->set('member_promo', 0);
		$this->db->set('member_last_deposit', 2);
		$this->db->where('id', $member_no);
		$this->db->update('members');
	}
	public function delete_employee($pg_ids)
	{
		$this->db->where('id', $pg_ids);
		$this->db->delete('employees');
	}

	public function ListMember($start = -1, $length = -1, $orderARR = NULL, $search_input = NULL, $search_category = null)
	{
		$result['num_rows'] = 0;
		$result['result_array'] = '';
		$result['row'] = '';

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		if ($search_input != '' && $search_input != null) {
			switch ($search_category) {
				case 1: // เบอร์โทร  
					$this->db->like('telephone', $search_input, 'both');
					break;
				case 2: // USERNAME
					$this->db->like('username', $search_input, 'both');
					break;
				case 3: //ชื่อ-สกุล 
					$this->db->like('CONCAT(fname,",",lname)', $search_input, 'both');
					break;
				case 4: // หมายเลขบัญชี
					$this->db->like('bank_accountnumber', $search_input, 'both');
					break;
				case 5: // Account TrueWallet
					$this->db->like('truewallet_account', $search_input, 'both');
					$this->db->where('truewallet_account is NOT NULL', NULL, FALSE);
					break;
			}
		}
		$num_rows = $this->db->get('members')->row()->num_rows;
		$row = "";
		if ($num_rows > 0) {
			$columns = array(
				0 => 'mber.username',
				1 => 'mber.telephone',
				2 => 'mber.bank_accountnumber',
				3 => 'mber.fname',
				4 => 'mber.lname',
				5 => 'mwl.main_wallet'
			);
			$columnName = $columns[$orderARR['column']];
			$this->db->select('mber.id, mber.username, mber.fname, mber.lname, mber.telephone, mber.bank_code, mber.bank_name, 
								mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status, mwl.main_wallet, 
								mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.member_last_deposit,mber.type_member,mber.com_use_default,mber.cashback_rate');
			$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
			if ($search_input != '' && $search_input != null) {
				switch ($search_category) {
					case 1: // เบอร์โทร  
						$this->db->like('mber.telephone', $search_input, 'both');
						break;
					case 2: // USERNAME
						$this->db->like('mber.username', $search_input, 'both');
						break;
					case 3: //ชื่อ-สกุล 
						$this->db->like('CONCAT(mber.fname,",",mber.lname)', $search_input, 'both');
						break;
					case 4: // หมายเลขบัญชี
						$this->db->like('mber.bank_accountnumber', $search_input, 'both');
						break;
					case 5: // Account TrueWallet
						$this->db->like('mber.truewallet_account', $search_input, 'both');
						$this->db->where('mber.truewallet_account is NOT NULL', NULL, FALSE);
						break;
				}
			}
			if (($start > -1) && ($length > 0)) {
				$this->db->limit($length, $start);
			}
			$this->db->order_by($columnName . " " . $orderARR['dir']);
			$sql = $this->db->get('members AS mber');

			$result_array = $sql->result_array();
			$row = $sql->row();

			$result['num_rows'] = $num_rows;
			$result['result_array'] = $result_array;
			$result['row'] = $row;
		}
		return $result;
	}

	public function SearchMemberByIDs($ids)
	{
		$result_array = "";
		$row = "";
		$this->db->select('mber.id, mber.username, mber.fname, mber.lname, mber.telephone,mber.truewallet_account, mber.bank_code, mber.bank_name, 
		mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status, mwl.main_wallet, aff_wallet_l1, aff_wallet_l2, 
		mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.member_last_deposit, mber.scb_scb, mber.kbank_kbank, mber.aff_type
		,mber.ignore_zero_turnover, mber.aff_percent_l1, mber.aff_percent_l2, mber.aff_percent_use_default, mber.type_member,mber.com_use_default,mber.cashback_rate
		,mber.truewallet_phone,mber.choose_bank,mber.group_af_l1,mber.group_af_l2');
		$this->db->where('mber.id =', $ids);
		$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
		$sql = $this->db->get('members AS mber');

		$result_array = $sql->result_array();
		$row = $sql->row();

		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function SearchMemberByUsername($username)
	{ 
		$result_array = "";
		$row = "";
		$this->db->select('mber.id, mber.username,mber.member_login, mber.fname, mber.lname, mber.telephone,mber.truewallet_account, mber.bank_code, mber.bank_name, 
		mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status, mwl.main_wallet,mber.member_promo,mber.member_last_deposit, 
		mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.password, mber.passwordText,source_ref,mber.truewallet_phone,mber.choose_bank,mber.partner,mber.group_af_l1,mber.group_af_l2');
		$this->db->where('mber.username =', $username);
		$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
		$sql = $this->db->get('members AS mber');

		$result_array = $sql->result_array();
		$row = $sql->row();

		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchMemberByTelephone($telephone)
	{
		$result_array = "";
		$row = "";
		$this->db->select('mber.id as member_no, mber.username, mber.fname, mber.lname, mber.telephone, mber.bank_code, mber.bank_name, 
							   mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status, mwl.main_wallet,mber.member_promo,mber.member_last_deposit, 
							   mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.password');
		if (preg_match('/^0[0-9]\d{8}$/', $telephone) == 1) {
			$this->db->where('telephone =', $telephone);
		} else {
			$this->db->where('truewallet_account =', $telephone);
			$this->db->where('truewallet_account is NOT NULL', NULL, FALSE);
			$this->db->where('TRIM(truewallet_account) !=""', NULL, FALSE);
		}
		$this->db->join('member_wallet AS mwl', 'mber.id = mwl.member_no', 'left');
		$sql = $this->db->get('members AS mber');

		$result_array = $sql->result_array();
		$row = $sql->row();

		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchMemberWalletBymember_no($member_no)
	{

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('member_wallet')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, member_no, main_wallet, aff_wallet_l1, aff_wallet_l2, commission_wallet, cashback_wallet, 
							   turnover, deposit, withdraw, update_by, updated_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('member_wallet');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchMemberPromoBymember_no($member_no)
	{

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('member_no =', $member_no);
		$num_rows = $this->db->get('member_promo')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, member_no, promo_id, status, promo_accepted_date, turnover_expect, remark, 
							   update_date');
			$this->db->where('member_no =', $member_no);
			$sql = $this->db->get('member_promo');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function SearchMemberPromoBymember_no2($member_no)
	{ 
		 $result_array = "";
		 $row = ""; 
			$this->db->select('id, member_last_deposit, member_promo,ignore_zero_turnover,cashback_rate,bank_accountnumber,truewallet_phone,choose_bank,fname,lname');
			$this->db->where('id =', $member_no);
			$sql = $this->db->get('members');

			$result_array = $sql->result_array();
			$row = $sql->row(); 

		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchMemberPromoWhere($member_no, $promo_id, $status = '')
	{
		$result_array = "";
		$row = "";
		$this->db->select('id, member_no, promo_id, status, promo_accepted_date, turnover_expect, remark,update_date');
		$this->db->where('member_no =', $member_no);
		$this->db->where('promo_id =', $promo_id);
		if ($status != '') {
			$this->db->where('status =', $status);
		}
		$sql = $this->db->get('member_promo');

		$result_array = $sql->result_array();
		$row = $sql->row();

		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function Update_member_promo($member_no, $promo_id, $status, $turnover_expect, $remark)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$this->db->set('promo_id', $promo_id);
		$this->db->set('status', $status);
		$this->db->set('promo_accepted_date', $DateTimeNow);
		$this->db->set('turnover_expect', $turnover_expect);
		$this->db->set('remark', $remark);
		$this->db->where('member_no', $member_no);
		$this->db->update('member_promo');
	}
	public function Update_member_promo_status($member_no, $status)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->where('member_no', $member_no);
		$this->db->update('member_promo');
	}

	public function Search_member_turnover_By_member_no($member_no)
	{ 
		$result_array =[];
		$row = ""; 
		$this->db->where('member_no =', $member_no);
		$sql = $this->db->get('member_turnover_product'); 
		$num_rows = $sql->num_rows();
		if($num_rows>0){
			$result_array = $sql->result_array();
			$row = $sql->row();
		} 
		$result['num_rows'] =$num_rows; 
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function insert_member_promo($member_no, $promo_id, $status, $turnover_expect, $remark)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('promo_id', $promo_id);
		$this->db->set('status', $status);
		$this->db->set('promo_accepted_date', $DateTimeNow);
		$this->db->set('turnover_expect', $turnover_expect);
		$this->db->set('remark', $remark);
		$this->db->insert('member_promo');
	}

	public function AddCreditManual($member_no, $member_username, $amount, $remark, $remarkOption, $p_data_deposit = [])
	{
		$channel = 5;
		$promo_id = 0;
		$member_last_deposit = '';
		$trx_id = uniqid();
		$before_balance=0;
		$openration_type = $remarkOption;
		$remark_internal = $remark;
		switch ($remarkOption) {

			case 17:  // TrueWallet
				$channel = 2;
				$promo_id = 0;
				break;
			case 0: // No promo
				$remark = 'other';
				$RsMemberPro = $this->SearchMemberPromoBymember_no($member_no);
				if ($RsMemberPro['num_rows'] > 0) {
					$this->Update_member_promo($member_no, 0, 1, $amount, "Credited by Admin");
				} else {
					$this->insert_member_promo($member_no, 0, 1, $amount, "Credited by Admin");
				}
				break;
			case 2: // ยอดข้าม
				$remark = "ยอดข้าม";
				$channel = 1;
				$member_last_deposit = "1";
				break;

			case 3: // Free 200
				$remark = "Free200";
				$promo_id = 1;
				$amount = 200;
				$this->Model_promo->insert_profree50($member_no, 1, 0, 0, 500);
				break;
			case 4:
				$remark = 'SMS Delayed';
				$channel = 3;
				$member_last_deposit = "1";
				break;
			case 5:
				$remark = 'ไม่พบบัญชี-SCB-Bank';
				$channel = 1;
				$member_last_deposit = "1";
				break;
			case 6:
				$remark = 'ไม่พบบัญชี-SCB-SMS';
				$channel = 3;
				$member_last_deposit = "1";
				break;
			case 7:
				$remark = 'ไม่พบบัญชี-KBank-Bank';
				$channel = 1;
				$member_last_deposit = "1";
				break;
			case 8:
				$remark = 'ไม่พบบัญชี-KBank-SMS';
				$channel = 3;
				$member_last_deposit = "1";
				break;
			case 9:
				$remark = 'WB100';
				$promo_id = 9;
				$channel = $promo_id;
				$this->Model_promo->insert_PromoWb100($member_no, $amount);
				break;
			case 10:
				$remark = 'MC25';
				$promo_id = 10;
				$channel = $promo_id;
				$this->Model_promo->insert_PromoMC25($member_no, $amount);
				break;
			case 11: // Happy new year
				$remark = 'HNY50';
				$channel = 30;
				$amount = 50;
				$promo_id = 30;
				//$RSlogDeposit = $this->Model_db_log->search_log_deposit_lessthen_today($member_nos)  
				if (isset($p_data_deposit['row']->amount)) {
					$deposit_id = $p_data_deposit['row']->id;
					$deposit_amount = $p_data_deposit['row']->amount;
					$this->Model_promo->insert_PromoHNY50($member_no, $amount, $deposit_amount);
					$this->Model_db_log->update_log_deposit_promo($promo_id, $deposit_id);
				}
				break;
			default:
				# code...
				break;
		}
		if ($remarkOption >= 61 && $remarkOption <= 72) { // รางวัลประจำเดือน
			$promoinfo = $this->Model_function->GetchannelInfo($remarkOption);
			$amount = $promoinfo['amount'];
			$remark = $promoinfo['remark'];
			$channel = $promoinfo['channel'];
			$promo_id = $promoinfo['channel'];

			$this->Model_promo->insert_reward_monthly($member_no, $promo_id, $remark, $amount);
		}
		if ($remarkOption >= 80 && $remarkOption <= 95) { // ฝากประจำ
			$promoinfo = $this->Model_function->GetchannelInfo($remarkOption);
			$amount = $promoinfo['amount'];
			$remark = $promoinfo['remark'];
			$channel = $promoinfo['channel'];
			$promo_id = $promoinfo['channel'];
		}

		$RsMemberPro02 = $this->SearchMemberPromoBymember_no($member_no);
		if ($RsMemberPro02['num_rows'] > 0) {
			$this->Update_member_promo($member_no, $promo_id, 1, $amount, $remark);
		} else {
			$this->insert_member_promo($member_no, 0, 1, $amount, $remark);
		}

		if ($remarkOption != 3) {
			$this->Model_promo->update_status_profree50($member_no, 2);
		}

		$trx_date = date("Y-m-d");
		$trx_time = date('H:i:s');
		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
		$this->Model_promo->Update_member_promo_last($member_no, ($member_last_deposit == '1') ? 0 : $promo_id, $member_last_deposit);
		$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark, $remark_internal, $trx_date, $trx_time, ($member_last_deposit == '1') ? -1 : $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet);
		$this->Model_db_log->insert_log_add_credit($member_no, $member_username, $amount, $remark, $remark_internal, $openration_type,$trx_date, $trx_time);

		

		if ($RSM_Wallet['num_rows'] > 0) {
			if (is_numeric($RSM_Wallet['row']->main_wallet)) {
				$amount += $RSM_Wallet['row']->main_wallet;
				$before_balance=$RSM_Wallet['row']->main_wallet;
			}
		}

		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
		if ($RS1stDepo['num_rows'] <= 0) {

			$this->Model_db_log->insert_1st_deposit($member_no, $amount, "Credited by Admin");
		}
		$this->update_member_wallet($member_no, $amount);

		$this->insert_adjust_wallet_history($member_no, $amount, $before_balance, $remark,$trx_id);

		$log_info = "Added : " . $amount . " (" . trim($remark . ':' . $remark_internal) . ")";
		$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);
		$this->update_member_turnover($member_no);
	}
    public function getCategoryAll(){
		$this->db->select('provider_code,category_id'); 
		$this->db->order_by('category_id', 'ASC');
		$sql = $this->db->get('provider_category');
		$result_array=[]; 
		$num_rows=$sql->num_rows();
        if($num_rows>0){
			$result_array = $sql->result_array(); 
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array; 
	    return $result;
	}
	public function getCategory($provider_code,$pcategoryall=[])
	{     
		 $categoryall=$pcategoryall['result_array'];
		 $loop=sizeof($categoryall);
         for ($i=0; $i < $loop; $i++) { 
              $v=$categoryall[$i];
			 if (strtoupper($provider_code)==strtoupper($v['provider_code'])) { return $v['category_id'];break;}
		  }
		 return 0; 
	}

	public function GetTurnOverTotalByMember($member_no, $sport_type = 'all')
	{
		$turnover_now = 0;
		$turnover_collect = 0;
		$pro_allow_playgame = [];
		$RSTurn = $this->Model_member->Search_member_turnover_By_member_no($member_no);
		$t=$this->SearchMemberPromoBymember_no2($member_no); 
		if($t['row']->member_promo!=0){ 
			$pro_allow_playgame ='';
			$member_promo=$t['row']->member_promo;
			$this->db->where('pro_id=', $member_promo);  
			$this->db->order_by('id', 'ASC'); 
			$sql = $this->db->get('pro_promotion_detail'); 
			$num_rows=$sql->num_rows();
			if ($num_rows>0) { 
				$row = $sql->row();
				if (isset($row->pro_allow_playgame)) {
					$pro_allow_playgame = @explode(',', $row->pro_allow_playgame);
				} 
			  }   
		}
		if ($RSTurn['num_rows'] > 0) {
			// if ($RSTurn['row'] > 0) {
			$turnover_Sportsbook = 0;
			$turnover_Sportsbook_total = 0;
			$turnover_Casino = 0;
			$turnover_Casino_total = 0;
			$turnover_Slot = 0;
			$turnover_Slot_total = 0;
			$turnover_Fish = 0;
			$turnover_Fish_total = 0;
			$turnover_Arcade = 0;
			$turnover_Arcade_total = 0;
			$turnover_ESport = 0;
			$turnover_ESport_total = 0;
			$turnover_Lotto = 0;
			$turnover_Lotto_total = 0;
			$turnover_Crypto = 0;
			$turnover_Crypto_total = 0;
			$turnover_Current = 0;
			$turnover_Total = 0;

            $categoryall=$this->getCategoryAll();

			foreach ($RSTurn['result_array'] as $value) {
				$turnover_Sportsbook += (($this->getCategory($value['platform_code'],$categoryall) == 1) ? $value['current_turnover'] : 0);
				$turnover_Sportsbook_total += (($this->getCategory($value['platform_code'],$categoryall) == 1) ? $value['total_turnover'] : 0);
				$turnover_Casino += (($this->getCategory($value['platform_code'],$categoryall) == 2) ? $value['current_turnover'] : 0);
				$turnover_Casino_total += (($this->getCategory($value['platform_code'],$categoryall) == 2) ? $value['total_turnover'] : 0);
				$turnover_Slot += (($this->getCategory($value['platform_code'],$categoryall) == 3) ? $value['current_turnover'] : 0);
				$turnover_Slot_total += (($this->getCategory($value['platform_code'],$categoryall) == 3) ? $value['total_turnover'] : 0);
				$turnover_Fish += (($this->getCategory($value['platform_code'],$categoryall) == 4) ? $value['current_turnover'] : 0);
				$turnover_Fish_total += (($this->getCategory($value['platform_code'],$categoryall) == 4) ? $value['total_turnover'] : 0);
				$turnover_Arcade += (($this->getCategory($value['platform_code'],$categoryall) == 5) ? $value['current_turnover'] : 0);
				$turnover_Arcade_total += (($this->getCategory($value['platform_code'],$categoryall) == 5) ? $value['total_turnover'] : 0);
				$turnover_ESport += (($this->getCategory($value['platform_code'],$categoryall) == 6) ? $value['current_turnover'] : 0);
				$turnover_ESport_total += (($this->getCategory($value['platform_code'],$categoryall) == 6) ? $value['total_turnover'] : 0);
				$turnover_Lotto += (($this->getCategory($value['platform_code'],$categoryall) == 7) ? $value['current_turnover'] : 0);
				$turnover_Lotto_total += (($this->getCategory($value['platform_code'],$categoryall) == 7) ? $value['total_turnover'] : 0);
				$turnover_Crypto += (($this->getCategory($value['platform_code'],$categoryall) == 8) ? $value['current_turnover'] : 0);
				$turnover_Crypto_total += (($this->getCategory($value['platform_code'],$categoryall) == 8) ? $value['total_turnover'] : 0);
			}
			$turnover_Current += $turnover_Sportsbook + $turnover_Casino + $turnover_Slot + $turnover_Fish + $turnover_Arcade + $turnover_ESport + $turnover_Lotto + $turnover_Crypto;
			$turnover_Total += $turnover_Sportsbook_total + $turnover_Casino_total + $turnover_Slot_total + $turnover_Fish_total + $turnover_Arcade_total + $turnover_ESport_total + $turnover_Lotto_total + $turnover_Crypto_total;
			switch ($sport_type) {
				case 'sport':
					$turnover_collect = $turnover_Sportsbook_total;
					$turnover_now = $turnover_Sportsbook;
					break;

				case 'casino':
					$turnover_collect = $turnover_Casino_total;
					$turnover_now = $turnover_Casino;
					break;

				case 'slot':
					$turnover_collect = $turnover_Slot_total;
					$turnover_now = $turnover_Slot;
					break;

				case 'fish':
					$turnover_collect = $turnover_Fish_total;
					$turnover_now = $turnover_Fish;
					break;

				case 'arcade':
					$turnover_collect = $turnover_Arcade_total;
					$turnover_now = $turnover_Arcade;
					break;

				case 'esport':
					$turnover_collect = $turnover_ESport_total;
					$turnover_now = $turnover_ESport;
					break;

				case 'lotto':
					$turnover_collect = $turnover_Lotto_total;
					$turnover_now = $turnover_Lotto;
					break;

				case 'crypto':
					$turnover_collect = $turnover_Crypto_total;
					$turnover_now = $turnover_Crypto;
					break;

				default:
					$turnover_collect = $turnover_Total;
					$turnover_now = $turnover_Current;
					break;
			}
			$turnover_xnow=0; 
			if (in_array(1, $pro_allow_playgame)) { // casino only
				$turnover_xnow += $turnover_Sportsbook; 
			  }
			  if (in_array(2, $pro_allow_playgame)) { // casino only
				$turnover_xnow += $turnover_Casino; 
			  }
			  if (in_array(3, $pro_allow_playgame)) { // slot only
				$turnover_xnow += $turnover_Slot; 
			  }
			  if($turnover_xnow>0){
				$turnover_now=$turnover_xnow; 
			  }

		}
		return ['turnover_collect' => $turnover_collect, 'turnover_now' => $turnover_now];

		// switch ($sport_type) {
		// 	case 'slot':
		// 		if ($RSTurn['num_rows'] > 0) {
		// 			$turnover_now = $RSTurn['row']->sac_turnover;
		// 			$turnover_now += $RSTurn['row']->aec_turnover;
		// 			$turnover_now += $RSTurn['row']->pgs_turnover;
		// 			$turnover_now += $RSTurn['row']->wmc_turnover;
		// 			$turnover_now += $RSTurn['row']->sps_turnover;
		// 			$turnover_now += $RSTurn['row']->jks_turnover;
		// 			$turnover_now += $RSTurn['row']->kmc_turnover;
		// 			$turnover_now += $RSTurn['row']->rts_turnover;
		// 			$turnover_now += $RSTurn['row']->evp_turnover;
		// 			$turnover_now += $RSTurn['row']->kas_turnover;
		// 			$turnover_now += $RSTurn['row']->ambpg_turnover;
		// 			$turnover_now += $RSTurn['row']->ambpk_turnover;
		// 			$turnover_now += $RSTurn['row']->jls_turnover;
		// 			$turnover_now += $RSTurn['row']->fgs_turnover;
		// 			$turnover_now += $RSTurn['row']->isb_turnover;
		// 			$turnover_collect = $RSTurn['row']->total_turnover;
		// 		}
		// 		break;
		// 	case 'casino':
		// 		if ($RSTurn['num_rows'] > 0) {
		// 			$turnover_now = $RSTurn['row']->sac_turnover;
		// 			$turnover_now += $RSTurn['row']->aec_turnover;
		// 			$turnover_now += $RSTurn['row']->kmc_turnover;
		// 			$turnover_now += $RSTurn['row']->ambpk_turnover;
		// 			$turnover_collect = $RSTurn['row']->total_turnover;
		// 		}
		// 		break;
		// 	default:
		// 		if ($RSTurn['num_rows'] > 0) {
		// 			$turnover_now = $RSTurn['row']->sac_turnover;
		// 			$turnover_now += $RSTurn['row']->aec_turnover;
		// 			$turnover_now += $RSTurn['row']->pgs_turnover;
		// 			$turnover_now += $RSTurn['row']->wmc_turnover;
		// 			$turnover_now += $RSTurn['row']->sps_turnover;
		// 			$turnover_now += $RSTurn['row']->jks_turnover;
		// 			$turnover_now += $RSTurn['row']->kmc_turnover;
		// 			$turnover_now += $RSTurn['row']->rts_turnover;
		// 			$turnover_now += $RSTurn['row']->evp_turnover;
		// 			$turnover_now += $RSTurn['row']->kas_turnover;
		// 			$turnover_now += $RSTurn['row']->sbo_turnover;
		// 			$turnover_now += $RSTurn['row']->ambpg_turnover;
		// 			$turnover_now += $RSTurn['row']->ambpk_turnover;
		// 			$turnover_now += $RSTurn['row']->jls_turnover;
		// 			$turnover_now += $RSTurn['row']->fgs_turnover;
		// 			$turnover_now += $RSTurn['row']->isb_turnover;
		// 			$turnover_collect = $RSTurn['row']->total_turnover;
		// 		}
		// 		break;
		// }
		// }
		// return ['turnover_collect' => $turnover_collect, 'turnover_now' => $turnover_now];
	}
	public function update_member_turnover($member_no)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$this->db->set('current_turnover', 0);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->where('current_turnover!=',0); 
		$this->db->update('member_turnover_product');
       //ล็อค Turnover
		$this->db->set('ignore_zero_turnover', 0); 
		$this->db->where('id', $member_no);
		$this->db->update('members');
	}

	public function update_member_wallet($member_no, $amount)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('main_wallet', $amount);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('updated_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('member_wallet');
	}

	public function update_member_wallet_02($member_no, $amount)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		$this->db->trans_start();
		$this->db->set('main_wallet', $amount);
		$this->db->set('updated_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('member_wallet');
		$this->db->trans_complete();
	}
	public function update_member_wallet_03($member_no, $amount)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
		if (!is_numeric($amount)) {
			return 0;
		}
		if ($amount > 0) {
			$this->db->set('main_wallet', "main_wallet+$amount", FALSE);
		} else if ($amount < 0) {
			$this->db->set('main_wallet', "main_wallet-$amount", FALSE);
		}
		$this->db->set('update_by', ($this->session->userdata("Username")) ? $this->session->userdata("Username") : null);
		$this->db->set('updated_date', $DateTimeNow);
		$this->db->where('member_no', $member_no);
		$this->db->update('member_wallet');
	}

	public function insert_adjust_wallet_history($adjust_to, $adjust_amount, $b4_adjust_amount, $remark,$ptrx_id=null)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('adjust_from', $this->session->userdata("Username"));
		$this->db->set('adjust_to', $adjust_to);
		$this->db->set('adjust_amount', $adjust_amount);
		$this->db->set('b4_adjust_amount', $b4_adjust_amount);
		$this->db->set('trx_id', $ptrx_id);
		$this->db->set('remark', $remark);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('adjust_wallet_history');
	}

	public function insert_adjust_wallet_history_02($adjust_from, $adjust_to, $adjust_amount, $b4_adjust_amount, $remark)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('adjust_from', $adjust_from);
		$this->db->set('adjust_to', $adjust_to);
		$this->db->set('adjust_amount', $adjust_amount);
		$this->db->set('b4_adjust_amount', $b4_adjust_amount);
		$this->db->set('trx_id', uniqid());
		$this->db->set('remark', $remark);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('adjust_wallet_history');
	}

	public function AddCreditSCB(
		$member_no,
		$member_username,
		$selectdate,
		$starttime,
		$from_acc,
		$amount,
		$scb_bank_id,
		$member_firstname,
		$member_lastname,
		$scb_scb,
		$file_name=null
	) {
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$channel = 1;
		$promo_id = -1;
		$remark = "Manual-Topup SCB";
		$remark_internal = $remark;
		$trx_id = uniqid();
		$before_balance=0;
		$openration_type = $this->configdata->toOrdinal($remark);

		$this->Model_promo->ClearProOrTurnOver($member_no);
		$this->Update_member_promo($member_no, 0, 1, $amount, $remark);

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
		$this->Model_promo->Update_member_promo_last($member_no, 0, 1);
		$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark, $remark_internal, $selectdate, $starttime, $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet,$file_name);
		$this->Model_db_log->insert_log_add_credit($member_no, $member_username, $amount, $remark, $remark_internal, $openration_type,$selectdate, $starttime);

		$full_msg = 'รับโอนจาก SCB x' . $from_acc . ' นาย-นางสาว ' . $member_firstname . ' ' . $member_lastname;
		$this->Model_db_log->insert_log_deposit_scb($member_no, $trx_id, $selectdate, $starttime, $amount, "014", $from_acc, $scb_bank_id, $full_msg, 1, 1);

		$RsBank = $this->Model_bank->search_list_bank_setting_by_bank_account($scb_bank_id);
		if ($RsBank['num_rows'] > 0) {
			$this->Model_bank->auto_bank_scb_inserted($RsBank['row']->id, $scb_scb, $selectdate, $starttime, $amount, 0, $RsBank['row']->bank_account, 0, $RsBank['row']->bank_code, $full_msg, $remark);
		}


		

		if ($RSM_Wallet['num_rows'] > 0) {
			if (is_numeric($RSM_Wallet['row']->main_wallet)) {
				$amount += $RSM_Wallet['row']->main_wallet;
				$before_balance=$RSM_Wallet['row']->main_wallet;
			}
		}
		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
		if ($RS1stDepo['num_rows'] <= 0) {
			$this->Model_db_log->insert_1st_deposit($member_no, $amount, "Credited by Admin");
		}
		$this->update_member_wallet($member_no, $amount);
		$this->insert_adjust_wallet_history($member_no, $amount, $before_balance, $remark,$trx_id);

		$log_info = "Added : " . $amount . " (" . trim($remark) . ")";
		$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);
		$this->update_member_turnover($member_no);
	}

	public function AddCreditKbank(
		$member_no,
		$member_username,
		$selectdate,
		$starttime,
		$from_acc,
		$amount,
		$kbank_bank_id,
		$member_firstname,
		$member_lastname,
		$kbank_kbank,
		$file_name=null
	) {
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$channel = 3;
		$promo_id = -1;
		$remark = "Manual-Topup KBANK";
		$remark_internal = $remark;
		$trx_id = uniqid();
		$before_balance=0;
		$openration_type = $this->configdata->toOrdinal($remark);
		//   	$arrTrx['trx_id'] = $this->Model_bank->log_sms_genTrxID();

		// $this->Model_bank->inserted_log_sms($phoneNumber,$smsContent,$arrTrx['trx_id']);
		$this->Model_promo->ClearProOrTurnOver($member_no);

		$this->Update_member_promo($member_no, 0, 1, $amount, $remark);
		$this->Model_promo->Update_member_promo_last($member_no, 0, 1);

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
		$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark, $remark_internal, $selectdate, $starttime, $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet,$file_name);
		$this->Model_db_log->insert_log_add_credit($member_no, $member_username, $amount, $remark, $remark_internal, $openration_type,$selectdate, $starttime);

		$full_msg = 'รับโอนจาก KBANK x' . $from_acc . ' นาย-นางสาว ' . $member_firstname . ' ' . $member_lastname;
		$this->Model_db_log->insert_log_deposit_kbank($member_no, $trx_id, $selectdate, $starttime, $amount, "004", $from_acc, $kbank_bank_id, $full_msg, 1);
		// $RsBank = $this->Model_bank->search_list_bank_setting_by_bank_account($kbank_bank_id);
		// if($RsBank['num_rows'] > 0){
		// 	$this->Model_bank->auto_bank_scb_inserted($RsBank['row']->id,$kbank_kbank,$selectdate,$starttime,$amount,$amount,$RsBank['row']->bank_account,0,$RsBank['row']->bank_code,$full_msg,$remark);
		// }
 

		if ($RSM_Wallet['num_rows'] > 0) {
			if (is_numeric($RSM_Wallet['row']->main_wallet)) {
				$amount += $RSM_Wallet['row']->main_wallet;
				$before_balance=$RSM_Wallet['row']->main_wallet;
			}
		}
		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
		if ($RS1stDepo['num_rows'] <= 0) {
			$this->Model_db_log->insert_1st_deposit($member_no, $amount, "Credited by Admin");
		}
		$this->update_member_wallet($member_no, $amount);
		$this->insert_adjust_wallet_history($member_no, $amount, $before_balance, $remark,$trx_id);

		$log_info = "Added : " . $amount . " (" . trim($remark) . ")";
		$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);
		$this->update_member_turnover($member_no);
	}

	public function AddCreditTrueWallet($member_no, $member_username, $selectdate, $starttime, $from_acc, $amount, $member_firstname, $member_lastname,$file_name=null)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$channel = 2;
		$promo_id = -1;
		$remark = "Manual-Topup TrueWallet";
		$remark_internal = $remark;
		$trx_id = uniqid();
		$before_balance=0;
		$openration_type = $this->configdata->toOrdinal($remark);
		$this->Model_promo->ClearProOrTurnOver($member_no);
		$this->Update_member_promo($member_no, 0, 1, $amount, $remark);

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
		$this->Model_promo->Update_member_promo_last($member_no, 0, 2);
		$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark, $remark_internal, $selectdate, $starttime, $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet,$file_name);
		$this->Model_db_log->insert_log_add_credit($member_no, $member_username, $amount, $remark, $remark_internal, $openration_type,$selectdate, $starttime);
 
		$this->Model_member->insert_auto_truewallet($member_no,$from_acc,$trx_id,$amount,$this->session->userdata("Username"),$member_firstname,$selectdate,$starttime,0,'');
		if ($RSM_Wallet['num_rows'] > 0) {
			if (is_numeric($RSM_Wallet['row']->main_wallet)) {
				$amount += $RSM_Wallet['row']->main_wallet;
				$before_balance=$RSM_Wallet['row']->main_wallet;
			}
		}
		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
		if ($RS1stDepo['num_rows'] <= 0) {
			$this->Model_db_log->insert_1st_deposit($member_no, $amount, "Credited by Admin");
		}
		$this->update_member_wallet($member_no, $amount);
		$this->insert_adjust_wallet_history($member_no, $amount, $before_balance, $remark,$trx_id);

		$log_info = "Added : " . $amount . " (" . trim($remark) . ")";
		$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);
		$this->update_member_turnover($member_no);
	}
	public function getMemberNoByBankAcct($bank_acct, $bank_code, $info)
	{

		$rows_count = 0;
		$member_no = '';
		$fname = '';
		$lname = '';
		$arr_return['member_no'] = 0;  // Bank account not found ##Default Value
		$arr_return['rows_count'] = 0; // Bank account not found ##Default Value

		if ($info != '') {
			$datain = trim($info);
			$search = 'Transfer';
			$info_arr = explode(' ', trim($datain));
			$pattern_thai = "/^[ก-๏\s]+$/u";
			if (isset($info_arr[5])) {
				$data_input_name = $info_arr[5];
				if (!is_null($data_input_name) && preg_match($pattern_thai, $data_input_name)) { // Thai language
					$fname =  (isset($info_arr[4]) ? $info_arr[4] : '');
					$lname  = (isset($info_arr[5]) ? $info_arr[5] : '');
					if (preg_match("/{$search}/i", $datain)) {
						$fname =  (isset($info_arr[5]) ? $info_arr[5] : '');
						$lname  = (isset($info_arr[6]) ? $info_arr[6] : '');
					}
				} else {
					//$fname = (isset($info_arr[5]) ? $info_arr[5] : '');
					//$lname  = (isset($info_arr[6]) ? $info_arr[6] : ''); 
					$needle=['MR.','MR','MS.','MS','MISS.','MISS','MRS.','MRS'];
					$lastPos = 0;
					$positions = array();  
					foreach ($info_arr as $value) {
						if(in_array(strtoupper($value),$needle)){ $lastPos=1; }
						if($lastPos==1){$positions[]=$value;}
					}
					if(sizeof($positions)==3){
						$fname = $positions[1];
						$lname  = $positions[2]; 
				      }else if(sizeof($positions)==4){
						$fname = $positions[1];
						$lname  = $positions[2].' '.$positions[3]; 
				     } 
				}
				$fname = (string)$fname;
				$lname = (string)$lname;
			}
			//   $info_arr = explode(' ', trim($info));
			//   $pattern_thai = "/^[ก-๏\s]+$/u";
			//   if (isset($info_arr[5])) { 
			//   $data_input_name=$info_arr[5]; 
			//   if (!is_null($data_input_name) &&preg_match($pattern_thai, $data_input_name)) {// Thai language
			// 	$fname =  (isset($info_arr[4])?$info_arr[4]:'');
			// 	$lname  = (isset($info_arr[5])?$info_arr[5]:'');
			//   }else{
			// 	$fname = (isset($info_arr[5])?$info_arr[5]:'');
			// 	$lname  =(isset($info_arr[6])?$info_arr[6]:'');
			//    } 
			//    $fname=trim((string)$fname);
			//    $lname=trim((string)$lname);
			//   }
		}

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		if ($bank_code == '014') {
			$this->db->where('scb_scb =', trim($bank_acct));
		} elseif ($bank_code == '004') {
			$this->db->where('scb_other =', trim($bank_acct));
		} else {
			$this->db->where('scb_other =', trim($bank_acct));
		}
		if ($fname != '') {
			$this->db->like('fname', $fname, 'after');
		}
		if ($lname != '') {
			$this->db->like('lname', $lname, 'after');
		}
		$this->db->where('bank_code =', $bank_code);
		$num_rows = $this->db->get('members')->row()->num_rows;



		if ($num_rows == 1) { // Bank account founded and only 1
			$this->db->select('id, username,member_promo,member_last_deposit');
			if ($bank_code == '014') {
				$this->db->where('scb_scb =', trim($bank_acct));
			} elseif ($bank_code == '004') {
				$this->db->where('scb_other =', trim($bank_acct));
			} else {
				$this->db->where('scb_other =', trim($bank_acct));
			}
			if ($fname != '') {
				$this->db->like('fname', $fname, 'after');
			}
			if ($lname != '') {
				$this->db->like('lname', $lname, 'after');
			}
			$this->db->where('bank_code =', $bank_code);
			$sql = $this->db->get('members');
			$row = $sql->row();

			$arr_return['member_no'] = $row->id;
			$arr_return['member_promo'] =$row->member_promo;
			$arr_return['member_last_deposit'] =$row->member_last_deposit;
			$arr_return['rows_count'] = 1;
		} else if ($num_rows > 1) { // Possible duplicated bank account number
			$arr_return['member_no'] = 0;
			$arr_return['rows_count'] = 2;
		}
		return $arr_return;
	}

	public function getMemberNoByBankAcct_kbank($bank_acct, $info)
	{

		$rows_count = 0;
		$member_no = '';
		$arr_return = array();

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		if ($info['from_bank'] == '014') {
			$this->db->where('scb_scb =', $bank_acct);
		} elseif ($info['from_bank'] == '004') {
			$this->db->like('bank_accountnumber', $bank_acct, 'before'); 
		} else {
			$this->db->where('scb_other =', $bank_acct);
		}
		$this->db->where('bank_code =', "004");
		$num_rows = $this->db->get('members')->row()->num_rows;

		if ($num_rows > 0) { // Bank account founded and only 1
			$this->db->select('id, username,member_promo,member_last_deposit');
			if ($info['from_bank'] == '014') {
				$this->db->where('scb_scb =', $bank_acct);
			} elseif ($info['from_bank'] == '004') {
				$this->db->like('bank_accountnumber', $bank_acct, 'before');
			} else {
				$this->db->where('scb_other =', $bank_acct);
			}
			$this->db->where('bank_code =', "004");
			$sql = $this->db->get('members');
			$result_array = $sql->result_array();
			$row = $sql->row();
			foreach ($result_array as $item) {
				$rows_count++;
				$member_no .= $item['id'] . ',';
			}
			if ($rows_count >= 2) {  // Possible duplicated bank account number
				$arr_return['member_no'] = 0;
				$arr_return['rows_count'] = 2;
			} elseif ($rows_count == 1) {   // Bank account founded and only 1
				$arr_return['member_no'] = substr($member_no, 0, -1);
				$arr_return['member_promo'] =$row->member_promo;
				$arr_return['member_last_deposit'] =$row->member_last_deposit;
				$arr_return['rows_count'] = 1;
			} elseif ($rows_count <= 0) {   // Bank account not found
				$arr_return['member_no'] = 0;
				$arr_return['rows_count'] = 0;
			}
		}
		return $arr_return;
	}

	public function adjustMemberWallet($member_no, $update_amount, $update_type)
	{
		$result = $this->Model_member->SearchMemberWalletBymember_no($member_no);

		if (is_numeric($result['row']->main_wallet)) {
			if ($result['row']->main_wallet < 0) {
				$result['row']->main_wallet = 0;
			}
		} else {
			$result['row']->main_wallet = 0;
		}

		if ($update_amount != 0) {
			if ($update_type == 1) { // บวก
				$result['row']->main_wallet += $update_amount;
			} else { // ลบ
				if ($result['row']->main_wallet <= $update_amount) {
					$result['row']->main_wallet = 0;
				} else {
					$result['row']->main_wallet -= $update_amount;
				}
			}

			$this->update_member_wallet_02($member_no, $result['row']->main_wallet);

			$result = $this->SearchMemberWalletBymember_no($member_no);
			if (is_numeric($result['row']->main_wallet)) {
				if ($result['row']->main_wallet < 0) {
					$result['row']->main_wallet = 0;
				}
			} else {
				$result['row']->main_wallet = 0;
			}
		}

		return $result['row']->main_wallet;
	}

	public function insert_auto_truewallet(
		$member_no,
		$w_truewallet_number,
		$w_tx_id,
		$w_amount,
		$w_sender,
		$w_sender_name,
		$w_transfer_date,
		$w_transfer_time,
		$w_status,
		$w_status_msg
	) {
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('member_no', $member_no);
		$this->db->set('w_truewallet_number', $w_truewallet_number);
		$this->db->set('w_tx_id', $w_tx_id);
		$this->db->set('w_amount', $w_amount);
		$this->db->set('w_sender', $w_sender);
		$this->db->set('w_sender_name', $w_sender_name);
		$this->db->set('w_transfer_date', $w_transfer_date);
		$this->db->set('w_transfer_time', $w_transfer_time);
		$this->db->set('w_status', $w_status);
		$this->db->set('w_status_msg', $w_status_msg);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('auto_truewallet');
	}
	public function SearchAccountduplicate($member_no='',$truewallet_acc=null,$bank_acc=null,$bank_code=null)
	{
		$result_array = [];$is_query=true;
		$row = ""; 
		if($truewallet_acc !=null&&trim($truewallet_acc)!=''){
			$this->db->where('truewallet_phone =', $truewallet_acc); 
		}else if($bank_acc !=null&&trim($bank_acc)!=''){
			$this->db->where('bank_accountnumber =', $bank_acc); 
			$this->db->where('bank_code =', $bank_code);
		}else{
			$is_query=false;
		} 
		if($is_query){
			$this->db->select('mber.id as member_no, mber.username, mber.fname, mber.lname, mber.telephone, mber.bank_code, mber.bank_name, 
							   mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status,mber.member_promo,mber.member_last_deposit, 
							   mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.password');
			if($member_no>0){$this->db->where('mber.id !=', $member_no);}
			$sql = $this->db->get('members AS mber'); 
			$result_array = $sql->result_array();
			$row = $sql->row();
		} 
		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	} 
	public function GetBankAccount($bank_code=null,$bank_acct=null){
		require dirname(__FILE__) . '/../../pg_vendor/autoload.php';  
		$targetURL = "https://services.12pay.org/VerifyAccount?bankcode=" .$bank_code. '&bankacct='.$bank_acct;
		$client = new \GuzzleHttp\Client([
			'exceptions'       => false,
			'http_errors' => false,
			'verify'           => false,
			'headers'          => [
			'Content-Type'   => 'application/json'
			]
		]); 

		$res = $client->request('GET', $targetURL);
		$res = $res->getBody()->getContents();  
		$res=(array)@json_decode($res); 
		$res_data=json_encode($res);
		$_SESSION['verifybank']=$res_data;
		$_SESSION['bank_account']=$bank_acct;
		$_SESSION['bank_code']=$bank_code;
		return $res;
	}
	public function GetTrueWalletAccount($phone=null){ 
		$tw=$this->Model_rbank->FindAll_truewallet();
		require dirname(__FILE__) . '/../../pg_vendor/autoload.php'; 
		$targetURL = $tw['row']->twURL;
		$client = new GuzzleHttp\Client([
			'exceptions'       => false,
			 'http_errors' => false,
			'verify'           => false,
			'timeout' => 15, // Response timeout
			'connect_timeout' => 15, // Connection timeout
			'headers'          => ['Content-Type'   => 'application/json']
		]); 
		$req['wallet_acc'] = $tw['row']->twNumber;
		$req['type'] = 'getname';
		$req['customer_phone'] =$phone;
		$arr['body'] = json_encode($req);
		$res = $client->request('POST', $targetURL, $arr); 
		$resJSON = $res->getBody()->getContents(); 
		$datares['firstName']='';
		$datares['lastName']='';
		$datares['code']=500;
	    if($res->getStatusCode()==200){
			$res =json_decode($resJSON, true);
			$t=explode(' ',@$res['message']);

			$datares['firstName']=$t[0];
			$datares['lastName']=$t[1];
			$datares['code']=0;
			$res_data=json_encode($res);
			$_SESSION['verifybank']=json_encode($datares);
			$_SESSION['bank_account']=$phone;
			$_SESSION['bank_code']='';
		}
		return $datares;
	}
	public function AddCreditVizplay($member_no,$member_username,$selectdate,$starttime
		                            ,$from_acc,$amount,$member_firstname
									,$member_lastname,$bank_code,$file_name=null) 
		{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$channel =1;
		$promo_id = -1;
		$remark = "VizPay <= Manual-Topup";
		$remark_internal = '';
		$trx_id = uniqid();
		$before_balance=0;
		$openration_type = $this->configdata->toOrdinal($remark);
		//   	$arrTrx['trx_id'] = $this->Model_bank->log_sms_genTrxID();

		// $this->Model_bank->inserted_log_sms($phoneNumber,$smsContent,$arrTrx['trx_id']);
		$this->Model_promo->ClearProOrTurnOver($member_no);

		$this->Update_member_promo($member_no, 0, 1, $amount, $remark);
		$this->Model_promo->Update_member_promo_last($member_no, 0, 1);

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
		$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark, $remark_internal, $selectdate, $starttime, $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet,$file_name);
		$this->Model_db_log->insert_log_add_credit($member_no, $member_username, $amount, $remark, $remark_internal, $openration_type,$selectdate, $starttime);
		$data_insert=['member_no'=>$member_no, 'txn_order_id'=>$trx_id, 'trx_date_time'=>$selectdate.' '.$starttime
		,'amount'=>$amount, 'stm_account_no'=>$from_acc,'deposit_type'=>'DEPOSIT','deposit_status'=>'SUCCESS','deposit_status_code'=>'DEPOSIT_MANUAL'];
		$this->Model_db_log->insert_log_deposit_Vizplay($data_insert); 

		if ($RSM_Wallet['num_rows'] > 0) {
			if (is_numeric($RSM_Wallet['row']->main_wallet)) {
				$amount += $RSM_Wallet['row']->main_wallet;
				$before_balance=$RSM_Wallet['row']->main_wallet;
			}
		}
		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
		if ($RS1stDepo['num_rows'] <= 0) {
			$this->Model_db_log->insert_1st_deposit($member_no, $amount, "Credited by Admin");
		}
		$this->update_member_wallet($member_no, $amount);
		$this->insert_adjust_wallet_history($member_no, $amount, $before_balance, $remark,$trx_id);

		$log_info = "Added : " . $amount . " (" . trim($remark) . ")";
		$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);
		$this->update_member_turnover($member_no);
	}
	public function member_updated_aff($pg_ids, $pg_l1, $create_by)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s"); 
		
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('id =', $pg_l1);
		$num_rows_l1 = $this->db->get('members')->row();
		$row_l1 = "";
		if ($num_rows_l1 > 0) {
			$this->db->select('id, username');
			$this->db->where('id =', $pg_l1);
			$sql_l1 = $this->db->get('members');
			$row_l1 = $sql_l1->row();

			if ($pg_l1 != NULL) {
				$this->db->set('group_af_username_l1', $row_l1->username);
			} else {
				$this->db->set('group_af_username_l1', NULL);
			}
			
		}

		// $this->db->select('COUNT(id) AS num_rows', FALSE);
		// $this->db->where('id =', $pg_l2);
		// $num_rows_l2 = $this->db->get('members')->row();
		// $row_l2 = "";
		// if ($num_rows_l2 > 0) {
		// 	$this->db->select('id, username');
		// 	$this->db->where('id =', $pg_l2);
		// 	$sql_l2 = $this->db->get('members');
		// 	$row_l2 = $sql_l2->row();
			
		// 	if ($pg_l2 != NULL) {
		// 		$this->db->set('group_af_username_l2', $row_l2->username);
		// 	} else {
		// 		$this->db->set('group_af_username_l2', NULL);
		// 	}
			
		// }
		if ($pg_l1 != NULL) {
			$this->db->set('group_af_l1', $pg_l1);
		} else {
			$this->db->set('group_af_l1', NULL);
		}
		// if ($pg_l2 != NULL) {
		// 	$this->db->set('group_af_l2', $pg_l2);
		// } else {
		// 	$this->db->set('group_af_l2', NULL);
		// }
		$this->db->set('update_at', $DateTimeNow);
		$this->db->where('id', $pg_ids);
		$this->db->update('members');
		//dd

		if ($pg_l1 != NULL) {
			$this->db->set('group_af_l1', $pg_l1);
		} else {
			$this->db->set('group_af_l1', NULL);
		}
		// if ($pg_l2 != NULL) {
		// 	$this->db->set('group_af_l2', $pg_l2);
		// } else {
		// 	$this->db->set('group_af_l2', NULL);
		// }
		$this->db->set('member_no', $pg_ids);
		$this->db->set('create_at', $DateTimeNow);
		$this->db->set('create_by', $create_by);
		$this->db->insert('log_edit_aff_member');
		
		return 200;
	}
}
?>