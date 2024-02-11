<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library("ConfigData");
		$this->load->model('Model_log_deposit');
	}
	public function view()
	{
		$mSite = $this->db->get_where('m_site', array('site_id' => SITE_ID))->row();
		if (isset($mSite->create_user_by_admin)) {
			$item['create_user_by_admin'] = $mSite->create_user_by_admin;
		}else{
			$item['create_user_by_admin'] = $mSite->create_user_by_admin;
		}

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$item['RSBank'] = $this->Model_bank->list_bank_system();
		$item['RSBankSetting'] = $this->Model_bank->list_bank_setting();
		$item['RSBankSettingS'] = $this->Model_bank->search_list_bank_setting("014", 1);
		$item['RSKbank'] = $this->Model_bank->search_list_bank_setting("004", 1);
		// $item['pro_list_m_aff'] = $this->Model_month_aff->FindAll();
		// $item['pro_list_m_comm'] = $this->Model_month_comm->FindAll();
		$item['pro_list_all'] = $this->Model_promotion->FindByGroupIdAll(2);
		$this->load->view('view_member', $item);
	}
	public function create()
	{
		$mSite = $this->db->get_where('m_site', array('site_id' => SITE_ID))->row();
		if (isset($mSite->create_user_by_admin)) {
			$create_user_by_admin = $mSite->create_user_by_admin;
			if($create_user_by_admin==2){
				$data["heading"] = "404 Page Not Found";
    			$data["message"] = "The page you requested was not found ";
				return $this->load->view('errors/html/error_404',$data); 
			}
		}
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$item['RSBank'] = $this->Model_bank->list_bank_system();
		$item['RSBankSetting'] = $this->Model_bank->list_bank_setting();
		$item['RSBankSettingS'] = $this->Model_bank->search_list_bank_setting("014", 1);
		$item['RSKbank'] = $this->Model_bank->search_list_bank_setting("004", 1);
		// $item['pro_list_m_aff'] = $this->Model_month_aff->FindAll();
		// $item['pro_list_m_comm'] = $this->Model_month_comm->FindAll();
		$item['pro_list_all'] = $this->Model_promotion->FindByGroupIdAll(2);
		$this->load->view('view_member_create', $item);
	}
	public function register(){

		// $user_data = $this->session->Username;
		// echo $user_data;
		// echo uniqid();
		// echo $this->GetFullDomain();
		// exit();
		$choose_bank=$_POST['choose_bank']*1;
		$txt_choose_bank='ธนาคาร';
		if($choose_bank==2){$txt_choose_bank='ทรูมันนี่ วอลเล็ท';} 


		$data_success = array();
		$errorMSG = "";
		$errorCode = 200;
		$aff_upline_id1 = null;
		$aff_upline_username1 = null;
		$aff_upline_id2 = null;
		$aff_upline_username2 = null;
		$json_arr = array('status' => '' ,'message' => '');

		$verifybank = @json_decode($_SESSION['verifybank'],true);
		$_POST['first_name'] = ($verifybank['firstName']) ? $verifybank['firstName'] : '';
		$_POST['last_name'] = ($verifybank['lastName']) ? $verifybank['lastName'] : '';
		$_POST['bank_account'] = @$_SESSION['bank_account'];
		$_POST['bank_code'] = @$_SESSION['bank_code'];

		if (empty($_POST['phone'])) {
			$errorMSG = "กรุณากรอก เบอร์มือถือ";
		} elseif (empty($_POST['password'])) {
			$errorMSG = "กรุณากรอก รหัสผ่าน";
		} elseif (empty($_POST['password_confirmation'])) {
			$errorMSG = "กรุณากรอก ยืนยันรหัสผ่าน";
		} elseif (trim($_POST['password']) != trim($_POST['password_confirmation'])) {
			$errorMSG = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
		} elseif (empty($_POST['bank_account'])) {
			$errorMSG = "กรุณากรอก เลขบัญชี".$txt_choose_bank;
		} elseif (strlen(trim($_POST['bank_account'])) < 10) {
			$errorMSG = "หมายเลขบัญชี{$txt_choose_bank}ไม่ถูกต้อง";
		} elseif (empty($_POST['bank_code'])&&$choose_bank==1) {
			$errorMSG = "กรุณาเลือก ".$txt_choose_bank;
		} elseif (empty($_POST['first_name'])) {
			$errorMSG = "กรุณากรอก ชื่อในสมุดบัญชี{$txt_choose_bank}";
		} elseif (empty($_POST['last_name'])) {
			$errorMSG = "กรุณากรอก นามสกุลในสมุดบัญชี{$txt_choose_bank}";
		} 

		if (!empty($errorMSG)) {
			$json_arr = array('status' => 'error' ,'message' => $errorMSG);
			echo json_encode($json_arr);

			exit();
		}

		// Check duplicated IP

		$ip = $this->getIP();

		$phone = $this->str_check(trim($_POST['phone']));
		$password = hash("sha512", trim($_POST['password']) . KEY_SALT);
		$passwordText = trim($_POST['password']);
		$fullname =  (isset($verifybank['fullName'])) ? $verifybank['fullName'] : '';
		$firstname = $this->str_check(trim($_POST['first_name']));
		$lastname = trim($_POST['last_name']); 
		if($lastname!='-'){$lastname =$this->str_check(trim($_POST['last_name']));}
		$bank_code = $this->str_check(trim($_POST['bank_code']));
		$bank_name = $this->BankCode2ShortName($bank_code);
		$bank_account = $this->str_check(trim($_POST['bank_account']));
		$line_id = $this->str_check(trim(@$_POST['line_id']));
		$source = trim(@$_POST['source']);
		$bank_account=($choose_bank==1)?$bank_account:'';

		$row_bank = $this->check_bank_num($bank_account, $bank_code);
		$row_phone = $this->check_phone($phone);
		$know_us_from = $this->str_check(@trim($_POST['source']));
		$choose_bank = $this->str_check(trim($_POST['choose_bank']));
		$truewallet_phone=$this->str_check(trim($_POST['bank_account']));
		$truewallet_account=$this->str_check(trim($_POST['truewallet_account']));
		$row_tw=0;
		if($choose_bank==2){
			$mbr_bank=$this->Model_member->SearchAccountduplicate('',$truewallet_phone); 
			$row_tw =$mbr_bank['num_rows']; 
		}

		$scb_scb = substr($bank_account, -4);
		$scb_other = substr($bank_account, -6);
		$kbank_kbank = substr($bank_account, -7, 6);

		$re = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/i';

		if ($this->misc_parsestring($phone, '0123456789') == false || strlen($phone) != 10) {
			$errorMSG = "หมายเลขโทรศัพท์ ต้องเป็นตัวเลข 10 หลักเท่านั้น";
			$errorCode = 400;
		} elseif (!preg_match('/^[ก-๙-เa-zA-Z.\s]+$/', $firstname)) {
			$errorMSG = "ชื่อจริงต้องเป็นภาษาไทยเท่านั้น ห้ามมีเว้นวรรค";
			$errorCode = 400;
		} elseif (!preg_match('~[ก-๙-เa-zA-Z-_./]+~', $lastname)) {
			$errorMSG = "นามสกุลต้องเป็นภาษาไทยเท่านั้น ห้ามมีเว้นวรรค $lastname";
			$errorCode = 400;
		} elseif (mb_strlen($passwordText, 'UTF8') < 6) {
			$errorMSG = "รหัสผ่านต้องมีไม่ต่ำกว่า 6 ตัว";
			$errorCode = 400;
		} 

		elseif ($this->misc_parsestring($bank_account, '0123456789') == false&&$choose_bank==1) {
			$errorMSG = "หมายเลขบัญชี ต้องเป็นตัวเลข 10 หลักเท่านั้น";
			$errorCode = 400;
		} elseif ($row_bank > 0) {
			$errorMSG = "หมายเลขบัญชี $bank_account นี้ถูกใช้งานแล้ว";
			$errorCode = 400;
		} elseif ($row_phone > 0) {
			$errorMSG = "หมายเลขโทรศัพท์: $phone นี้ถูกใช้งานแล้ว";
			$errorCode = 400;
		}elseif ($row_tw > 0) {
			$errorMSG = "ทรูมันนี่ วอลเล็ท: $truewallet_phone นี้ถูกใช้งานแล้ว";
			$errorCode = 400;
		}

		if ($errorMSG !='') {
			$json_arr = array('status' => 'error' ,'message' => $errorMSG);
			echo json_encode($json_arr);
			exit();
		}

		$this->db->select('id');
		$row = $this->db->get_where('members', array('fname' => $firstname,'lname' =>$lastname))->row();
		if (isset($row->id)) {
			$errorMSG = "คุณเคยสมัครไปแล้ว";
			$errorCode = 400;
		}

		if ($errorCode == 200) {

			try {
				$uuid = uniqid();
				$this->db->trans_start();

				$data = array( 
					'choose_bank'=>$choose_bank,
					'truewallet_phone'=>($choose_bank==2)?$truewallet_phone:null,
					'truewallet_account'=>($choose_bank==2)?$truewallet_account:null,
					'member_login'=>  uniqid(), 
					'password'	=>  $password,
					'passwordText'	=>  $passwordText,
					'full_name'	=>  $fullname,
					'fname'	=>  $firstname,
					'lname'	=>  $lastname,
					'telephone'	=>  $phone,
					'bank_code'	=>  $bank_code,
					'bank_name'	=>  $bank_name,
					'bank_accountnumber'	=>($choose_bank==1)?$bank_account:null,
					'line_id'	=>  $line_id,
					'source_ref'	=> $know_us_from,
					'af_code'	=>  $uuid,
					'scb_scb'	=>  $scb_scb,
					'scb_other'	=>  $scb_other,
					'kbank_kbank'	=>  $kbank_kbank,
					'group_af_l1'	=>  $aff_upline_id1,
					'group_af_username_l1'	=>  $aff_upline_username1,
					'group_af_l2'	=>  $aff_upline_id2,
					'group_af_username_l2'	=>  $aff_upline_username2,
					'ip'	=>  $ip,
					'create_at'	=>  date('Y-m-d H:i:s'),
					'aff_type'	=>  DEFAULT_AFF_TYPE_MEMBER,
				);
				$this->db->insert('members', $data);
				$id = $this->db->insert_id();

				$username = strtoupper(SITE_ID) . $id;
				$this->db->set('username', $username);
				$this->db->where('id', $id);
				$this->db->update('members');

				$data_wall = array( 
					'member_no'=>  $id, 
				);
				$this->db->insert('member_wallet', $data_wall);

				$data_aff_mem = array( 
					'member_no'=>  $id, 
					'group_af_l1'=>  $aff_upline_id1, 
					'group_af_l2'=>  $aff_upline_id2, 
					'create_at'=>  date('Y-m-d H:i:s'), 
				);
				$this->db->insert('aff_member_branch', $data_aff_mem);


				$user_em = $this->session->Username;
				$data_log = array( 
					'member_no'=>  $id, 
					'ip'	=>  $ip,
					'create_by'	=>  $user_em,
					'create_at'	=>  date('Y-m-d H:i:s'),
				);
				$this->db->insert('log_admin_create', $data_log);

				$this->db->trans_complete();


				$findUser = $this->db->get_where('members', array('id' => $id))->row();
				if (isset($findUser->id)) {
					$data_success = array(
						'username' => $findUser->username, 
						'telephone' => $findUser->telephone, 
					);
				}

			} catch (\Exception $e) {
				// handle Error 
				$this->db->trans_rollback();
				$errorCode = $e->getCode();
				$errorMSG = 'INSERT DATA ERROR!';
				$json_arr = array('status' => 'error' ,'message' => $e->getMessage());
				echo json_encode($json_arr);

			}
			if ($this->db->trans_status() === TRUE)
			{
				$errorMSG = 'ลงทะเบียนเรียบร้อย!';
				$this->db->trans_commit();
				$json_arr = array('status' => 'success' ,'message' => $errorMSG ,'data' => $data_success );
				echo json_encode($json_arr);
			}



		} else {
			$json_arr = array('status' => 'error' ,'message' => $errorMSG);
			echo json_encode($json_arr);
		}
		exit();
	}
	function misc_parsestring($text, $allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		if (empty($allowchr)) {
			$allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		if (empty($text)) {
			return false;
		}
		$size = strlen($text);
		for ($i = 0; $i < $size; $i++) {
			$tmpchr = substr($text, $i, 1);
			if (strpos($allowchr, $tmpchr) === false) {
				return false;
			}
		}
		return true;
	}
	function check_phone($phone)
	{
		$this->db->select('telephone');
		$this->db->where('telephone =', $phone);
		$num_rows = $this->db->get('members')->num_rows();

		if ( $num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	function check_bank_num($bank_number, $bank_code)
	{
		$this->db->select('bank_accountnumber');
		$this->db->where('bank_code =', $bank_code);
		$this->db->where('bank_accountnumber =', $bank_number);
		$num_rows = $this->db->get('members')->num_rows();
		if ( $num_rows > 0) {
			return 1;
		} else {
			return 0;
		}

	}
	function BankCode2ShortName($str_bnk)
	{ 
		$str_bnk = str_replace("014", "SCB", $str_bnk);   // ไทยพานิชย์ 
		$str_bnk = str_replace("002", "BBLA", $str_bnk);  // กรุงเทพ
		$str_bnk = str_replace("002", "BBL", $str_bnk);   // กรุงเทพ BBL
		$str_bnk = str_replace("004", "KBANK", $str_bnk); // กสิกร KBANK
		$str_bnk = str_replace("004", "KBNK", $str_bnk);  // กสิกร KBNK
		$str_bnk = str_replace("006", "KTBA", $str_bnk);  // กรุงไทย KTBA
		$str_bnk = str_replace("006", "KTB", $str_bnk);   // กรุงไทย KTB
		$str_bnk = str_replace("034", "BAAC", $str_bnk);  // ธกส. SEC
		$str_bnk = str_replace("018", "SEC", $str_bnk);   // 
		$str_bnk = str_replace("011", "TMBA", $str_bnk);  // ทหารไทย
		$str_bnk = str_replace("011", "TMB", $str_bnk);   // ทหารไทย
		$str_bnk = str_replace("022", "CIMB", $str_bnk);  // ซีไอเอ็มบี
		$str_bnk = str_replace("024", "UOBT", $str_bnk);  // UOB
		$str_bnk = str_replace("024", "UOB", $str_bnk);   // UOB
		$str_bnk = str_replace("025", "BAYA", $str_bnk);  // กรุงศรีฯ
		$str_bnk = str_replace("025", "BAY", $str_bnk);   // กรุงศรีฯ
		$str_bnk = str_replace("030", "GSBA", $str_bnk);  // ออมสิน
		$str_bnk = str_replace("030", "GSB", $str_bnk);   // ออมสิน
		$str_bnk = str_replace("031", "HSBC", $str_bnk);  // HSCB
		$str_bnk = str_replace("071", "TCD", $str_bnk);   //
		$str_bnk = str_replace("073", "LHBA", $str_bnk);  // Land & House
		$str_bnk = str_replace("073", "LH", $str_bnk);  // Land & House
		$str_bnk = str_replace("065", "TBANK", $str_bnk); // ธนชาติ
		$str_bnk = str_replace("065", "TBNK", $str_bnk);  // ธนชาติ
		$str_bnk = str_replace("067", "TISCO", $str_bnk); // ทิสโก้
		$str_bnk = str_replace("069", "KKP", $str_bnk);   // เกียรตินาคิน
		$str_bnk = str_replace("066", "ISBT", $str_bnk);  // ธ.อิสลาม
		$str_bnk = str_replace("033", "GHB", $str_bnk);   // ธ.อาคารสงเคราะห์
		$str_bnk = str_replace("017", "CITI", $str_bnk);  // ซิตี้แบงค์
		$str_bnk = str_replace("070", "ICBCT", $str_bnk); // ICBCT
		$str_bnk = str_replace("020", "SCBT", $str_bnk); // แตนดาร์ดชาร์เตอร์ด
		return $str_bnk;
	}  		
	function getIP()
	{
		$clientIP = '0.0.0.0'; 
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$clientIP = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			# when behind cloudflare
			$clientIP = $_SERVER['HTTP_CF_CONNECTING_IP']; 
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$clientIP = $_SERVER['HTTP_X_FORWARDED'];
		} elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$clientIP = $_SERVER['HTTP_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_FORWARDED'])) {
			$clientIP = $_SERVER['HTTP_FORWARDED'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$clientIP = $_SERVER['REMOTE_ADDR'];
		} 
		// Strip any secondary IP etc from the IP address
		$listIP = explode(',', $clientIP); 
		if (isset($listIP[1]))  {
			$clientIP = $listIP[0];
		 } 
		// if (strpos($clientIP, ',') > 0) {
		//     $clientIP = substr($clientIP, 0, strpos($clientIP, ','));
		// }
		return $clientIP;   
	} 
	public function VerifyBankAccount(){ 
		header("Content-type: application/json; charset=utf-8");
		header("Access-Control-Allow-Origin: " . @$_SERVER['HTTP_ORIGIN'] . "");
		if (preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $this->config->slash_item('base_url'))!=preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", @$_SERVER['HTTP_ORIGIN'])) {exit();}
		$data = $this->input->post(null, TRUE);
		if($data['choose_bank']==2){
			$bank_acc=$this->Model_member->GetTrueWalletAccount($data['bank_acct']);
			$res_data=json_encode($bank_acc);  
		}else{
			$bank_acc=$this->Model_member->GetBankAccount($data['bank_code'],$data['bank_acct']);
			$res_data=json_encode($bank_acc);  
		} 
		echo $res_data;  
		exit();
	}
	function str_check($str)
	{ 
		$str = str_replace("_", "", $str);

		$str = str_replace("%", "", $str);

		$str = str_replace("=", "", $str);

		$str = str_replace("<", "", $str);

		$str = str_replace(">", "", $str);

		$str = str_replace("\'", "", $str);

		$str = str_replace("-", "", $str);

		$str = str_replace(";", "", $str);

		$str = str_replace("select", "", $str);

		$str = str_replace("update", "", $str);

		$str = str_replace("delete", "", $str);

		$str = str_replace("insert", "", $str);

		$str = str_replace("union", "", $str);

		$str = str_replace("--", "", $str);

		$str = str_replace("$", "", $str);

		$str = str_replace("#", "", $str); 
		return $str;
	}

	public function profile()
	{
		$UserName = $this->uri->segment(3);

		$RSMember = $this->Model_member->SearchMemberByUsername($UserName);
		if ($RSMember['num_rows'] == 0) {
			redirect('member', 'refresh');
			exit();
		}


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$member_no = $RSMember['row']->id;

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);

		$item['main_wallet'] = 0.00;
		if ($RSM_Wallet['num_rows'] > 0) {
			$item['main_wallet'] = number_format($RSM_Wallet['row']->main_wallet, 2);
		}
		$dataturn = $this->Model_member->GetTurnOverTotalByMember($member_no);
		$item['turnover_now'] = $dataturn['turnover_now'];
		$item['turnover_collect'] = $dataturn['turnover_collect'];

		$RSSumdeposit = $this->Model_db_log->sum_log_deposit($member_no);
		$item['sum_deposit'] = 0.00;
		if ($RSSumdeposit['num_rows'] > 0) {
			$item['sum_deposit'] = $RSSumdeposit['row']->sum_deposit;
		}

		$RSSumdepositB = $this->Model_db_log->sum_deposit_backup($member_no);
		if ($RSSumdepositB['num_rows'] > 0) {
			$item['sum_deposit'] = ($item['sum_deposit'] + $RSSumdepositB['row']->sum_deposit);
		}
		$RSSumWithdraw = $this->Model_db_log->sum_withdraw($member_no);
		$item['sum_withdraw'] = 0.00;
		if ($RSSumWithdraw['num_rows'] > 0) {
			$item['sum_withdraw'] = ($RSSumWithdraw['row']->sum_withdraw);
		}

		$RSSumWithdrawB = $this->Model_db_log->sum_withdraw_backup($member_no);
		if ($RSSumWithdrawB['num_rows'] > 0) {
			$item['sum_withdraw'] = ($item['sum_withdraw'] + $RSSumWithdrawB['row']->sum_withdraw);
		}
		$item['InfoMember']  = $RSMember['row'];

		$item['pg_ref']  = $this->encryption->encrypt($RSMember['row']->id);

		$item['win_loss'] = ($item['sum_deposit'] - $item['sum_withdraw']);

		$RSLogin = $this->Model_db_log->search_log_login($member_no);
		$item['latest_ip'] = "-";
		$item['latest_login_date'] = "-";

		if ($RSLogin['num_rows'] > 0) {
			$item['latest_ip'] = $RSLogin['row']->ip_address;
			$item['latest_login_date'] = $this->Model_function->ConvertDateTimeShortTH($RSLogin['row']->update_date);
		}
		$item['pg_title'] = 'Dashbord';

		$item['UserName'] = $UserName;
		$item['member_no'] = $member_no;
		$item['status'] = $RSMember['row']->status;
		$item['RSBank'] = $this->Model_bank->list_bank_system();
		$item['RSBankSetting'] = $this->Model_bank->list_bank_setting();
		$item['RSBankSettingS'] = $this->Model_bank->search_list_bank_setting("014", 1);
		$item['linkloginmem'] = DOMAIN_FRONTEND.'actions/login.php?token='.base64_encode($RSMember['row']->id);

		$this->load->view('view_member_profile', $item);
	}

	public function profile_history()
	{
		$UserName = $this->uri->segment(3);

		$RSMember = $this->Model_member->SearchMemberByUsername($UserName);
		if ($RSMember['num_rows'] == 0) {
			redirect('member', 'refresh');
			exit();
		}

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['UserName'] = $UserName;

		$item['pg_ref']  = $this->encryption->encrypt($RSMember['row']->id);
		$member_no = $RSMember['row']->id;

		$item['pg_title'] = 'Dashbord';

		$this->load->view('view_member_profile_history', $item);
	}

	public function add_edit_member()
	{   $data = $this->input->post(null, TRUE);
		$ids = isset($data['pg_ids']) ? $data['pg_ids'] : 0; 
		$bank_accountnumber = isset($data['bank_accountnumber']) ? $data['bank_accountnumber'] : NULL;
		$bank_code = isset($data['bank_code']) ? $data['bank_code'] : NULL;
		$line_id = isset($data['line_id']) ? $data['line_id'] : NULL;
		$setting_bank_id = isset($data['setting_bank_id']) ? $data['setting_bank_id'] : 0;
		$telephone = isset($data['telephone']) ? $data['telephone'] : NULL;
		$aff = isset($data['pg_aff']) ? $data['pg_aff'] : NULL;
		$password = isset($data['password']) ? $data['password'] : NULL;
		$truewallet_account = isset($data['truewallet_account']) ? $data['truewallet_account'] : NULL;
		$lock_Turnover = isset($data['lock_Turnover']) ? $data['lock_Turnover'] : 0;
		$aff_percent1 = isset($data['aff_percent1']) ? $data['aff_percent1'] : NULL;
		$aff_percent2 = isset($data['aff_percent2']) ? $data['aff_percent2'] : NULL;
		$aff_setting = isset($data['pg_setting']) ? $data['pg_setting'] : NULL;
		$com_use_default = isset($data['pg_com']) ? $data['pg_com'] : NULL;
		$type_member = isset($data['pg_type']) ? $data['pg_type'] :1;
		$cashbacksetting = isset($data['cashbacksetting']) ? $data['cashbacksetting'] : NULL;
		$truewallet_phone = isset($data['pg_truewallet_phone']) ? $data['pg_truewallet_phone'] :NULL;
		$choose_bank = isset($data['choose_bank']) ? $data['choose_bank'] :NULL;
		if(is_null($cashbacksetting)){$cashbacksetting=-1;}
		$scb_scb = substr($bank_accountnumber, -4);

		$scb_other = substr($bank_accountnumber, -6);

		$kbank_kbank = substr($bank_accountnumber, -7, 6);


		$pg_ids = $this->Model_function->get_decrypt($ids);

		$boolMessage = false;
        $MessageErrorText='';
		if ($pg_ids != false) { 
			$r_data=$this->Model_member->member_updated_02($pg_ids, $bank_accountnumber, $bank_code, $line_id, $setting_bank_id, $telephone, $password, $scb_scb, $scb_other, $kbank_kbank, $truewallet_account, $aff,$lock_Turnover, $aff_percent1, $aff_percent2, $aff_setting, $type_member,$com_use_default,$cashbacksetting,$truewallet_phone,$choose_bank);
		    if($r_data==200){
			 $boolMessage = true; 
			 $MessageErrorText='';
		   }else if($r_data==402){
			$MessageErrorText='ปลดล็อค Turnover เป็นไม่ล็อคไม่ได้เพราะลูกค้ารับโบนัส!';
		   }else if($r_data==405){
			$MessageErrorText='ไม่สามารถเพิ่มบัญชีธนาคารเนื่องจาก ชื่อ-สกุลในบัญชีธนาคาร และ ชื่อ-สกุลผู้ใช้งานทรูมันนี่ วอลเล็ท ไม่ตรงกัน!';
		   }else if($r_data==406){
			$MessageErrorText='ไม่พบบัญชีธนาคาร!';
		   }else if($r_data==407){
			$MessageErrorText='ไม่สามารถเพิ่มทรูมันนี่ วอลเล็ทเนื่องจาก ชื่อ-สกุลในบัญชีธนาคาร และ ชื่อ-สกุลผู้ใช้งานทรูมันนี่ วอลเล็ท ไม่ตรงกัน!';
		   }else if($r_data==408){
			$MessageErrorText='ไม่พบบัญชีทรูมันนี่ วอลเล็ท!';
		   }else if($r_data==409){
			$MessageErrorText='พบบัญชีธนาคาร ซ้ำ!';
		   }else if($r_data==410){
			$MessageErrorText='พบบัญชีทรูมันนี่ ซ้ำ!';
		   }
		}
		
		$json_arr = array('Message' => $boolMessage ,'ErrorText' => $MessageErrorText,'boolError'=>1);
		echo json_encode($json_arr);
	}

	public function list_json()
	{

		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$length = isset($_GET['length']) ? $_GET['length'] : 0;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$orderARR = isset($_GET['order']) ? $_GET['order'][0] : NULL;
		$searchName = isset($_GET['search']) ? $_GET['search']['value'] : NULL;
		$search_input = isset($_GET['search_input']) ? $_GET['search_input'] : NULL;
		$search_category = isset($_GET['search_category']) ? $_GET['search_category'] : NULL;

		$Result = $this->Model_member->ListMember($start, $length, $orderARR, $search_input, $search_category);
		$MessageBool = false;
		$dataArr = array();


		if ($Result['num_rows'] > 0) {
			$MessageBool = true;

			foreach ($Result['result_array'] as $item) {
				$statusChecked = "";
				if ($item['status'] == 1) {
					$statusChecked = "checked";
				}
				$com_default =''; 
				if ($item['type_member'] == 1) {
					$type = " ";
				}else{
					$type = "เฝ้าระวัง";
				}

				$jsonArr = array(
					'<a target="_blank" href="' . base_url("member/profile/" . $item['username']) . '">' . $item['username'] . '</a>',
					$item['telephone'],
					"<center>" . $item['bank_name'] . "</center><center>" . $item['bank_accountnumber'] . "</center>",
					$item['fname'],
					$item['lname'],
					number_format($item['main_wallet'], 2) . '<button onclick="AddCreditMember(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $item['username'] . '\')" class="btn btn-primary btn-sm mt005px"><i class="ft-credit-card"></i> เติมเครดิต</button>',
					'<fieldset>
								 	<div class="float-left">
								 		<input type="checkbox" class="switch_status_pggamge" ref-id="' . $this->encryption->encrypt($item['id']) . '" data-on-color="success" data-off-color="danger" ' . $statusChecked . '/>
								 	</div>
								 </fieldset>',
					'<div class="d-flex"><button onclick="EditMember(\'' . $this->encryption->encrypt($item['id']) . '\')" class="btn btn-icon btn-info btn-action"><i class="la la-edit" style="vertical-align: bottom;"></i></button><a target="_blank" href="' . base_url("member/profile/" . $item['username']) . '" class="btn btn-icon btn-light ml-1 btn-action"><i class="la la-eye"></i></a>'.$com_default.'&nbsp;'.$type.'</div>'
					,$type
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => $Result['num_rows'], 'recordsFiltered' => $Result['num_rows']);
		echo json_encode($json_arr);
	}

	public function list_json_id()
	{

		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);

		$MessageBool = false;
		$dataArr = array();
		if ($pg_ids != false) {
			$MessageBool = true;
			$Result = $this->Model_member->SearchMemberByIDs($pg_ids);

			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {
					$statusChecked = "";
					if ($item['status'] == 1) {
						$statusChecked = "checked";
					}

					$jsonArr = array(
						'username' => $item['username'],
						'fname' => $item['fname'],
						'lname' => $item['lname'],
						'telephone' => $item['telephone'],
						'bank_code' => $item['bank_code'],
						'bank_accountnumber' => $item['bank_accountnumber'],
						'setting_bank_id' => $item['setting_bank_id'],
						'truewallet_account' => $item['truewallet_account'],
						'line_id' => $item['line_id'],
						'aff_type' => $item['aff_type'],
						'ignore_zero_turnover' => $item['ignore_zero_turnover'],
						'aff_percent1' => $item['aff_percent_l1'],
						'aff_percent2' => $item['aff_percent_l2'],
						'aff_percent_use_default' => $item['aff_percent_use_default'],
						'com_use_default' => $item['com_use_default'],
						'cashback_rate' => $item['cashback_rate'],
						'type_member' => $item['type_member'],
						'truewallet_phone' => $item['truewallet_phone'],
						'choose_bank' => ($item['choose_bank']>0?$item['choose_bank']:0),
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}


	public function update_member_status()
	{
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_status = isset($_POST['pg_status']) ? $_POST['pg_status'] : 0;



		if ($pg_status == "true") {
			$pg_status = 1;
		} else if ($pg_status == "false") {
			$pg_status = 2;
		}
		$BoolMessage = false;


		$pg_ids = $this->Model_function->get_decrypt($ids);

		if ($pg_ids != false) {
			$BoolMessage = true;
			$this->Model_member->update_member_status($pg_ids, $pg_status);
		}

		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function delete_employee()
	{
		$ids = isset($_POST['ref_ids']) ? $_POST['ref_ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);
		$BoolMessage = false;
		if ($pg_ids != false) {
			$BoolMessage = true;
			$this->Model_member->delete_employee($pg_ids);
		}
		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function add_credit_manual()
	{
		$ids = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
		$remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;
		$remarkOption = isset($_POST['remark-option']) ? $_POST['remark-option'] : -1;
		$data_deposit = [];


		$pg_ids = $this->Model_function->get_decrypt($ids);
		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";
		if ($remarkOption == -1) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกหมายเหตุ !!";
		}
		if ($amount < 1) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ยอดเงินไม่ถูกต้อง !!";
		}
		if ($amount == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ยอดเงิน !!";
		}
		if ($boolCheckForm == true) {
			if ($pg_ids != false) {
				$RSMember = $this->Model_member->SearchMemberByIDs($pg_ids);
				if ($RSMember['num_rows'] > 0) {
					$BoolMessage = true;

					$skipChkBalance = 0;

					if ($remarkOption == 9 || $remarkOption == 15) {
						$skipChkBalance = 1;
					}
					if ($remarkOption != 11) {
						$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($pg_ids);

						if ($RSM_Wallet['num_rows'] > 0) {
							if (($RSM_Wallet['row']->main_wallet > 10) && ($skipChkBalance == 0)) {
								$BoolMessage = false;
								$boolError = 1;
								$MessageErrorText = 'ยูสเซอร์นี้ มีเงินค้างในกระเป๋ามากกว่า 10 บ.(' . $RSM_Wallet['row']->main_wallet . ')';
							}
						}
					}
					if ($remarkOption == 3) { /// Check Pro Free 200
						// $RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($pg_ids);
						// if ($RSPromo50['num_rows'] > 0) {
						// 	$BoolMessage = false;
						// 	$boolError = 1;
						// 	$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ Free200 ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromo50['row']->update_date);
						// }
					}
					if ($remarkOption == 9) { /// Check Pro Welcome back 100%
						$RSLastDeposit = $this->Model_member->SearchMemberByIDs($pg_ids); // check true Wallet
						if ($RSLastDeposit['num_rows'] > 0) {
							if ($RSLastDeposit['row']->member_last_deposit == 2) {
								$BoolMessage = false;
								$boolError = 1;
								$MessageErrorText = 'ยูสเซอร์ฝากด้วย True Wallet ไม่สามารถรับโปรฯ นี้ได้';
							}
						}

						$RSPromoWB100 = $this->Model_promo->SearchPromoWb100Bymember_no($pg_ids);
						if ($RSPromoWB100['num_rows'] > 0) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ นี้ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromoWB100['row']->update_date);
						}
					}
					if ($remarkOption == 10) { /// Check Pro Merry Christmas 25%

						$RSMC25 = $this->Model_promo->SearchPromoMC25Bymember_no($pg_ids);
						if ($RSMC25['num_rows'] > 0) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ นี้ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSMC25['row']->update_date);
						}
					}

					if ($remarkOption == 11) { /// Check Pro Happy New Year ฝาก 100 รับเพิ่ม 50
						$data_deposit = $this->Model_db_log->search_log_deposit_today_by_amount($pg_ids, 100);
						if ($data_deposit['num_rows'] <= 0) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'วันนี้ไม่พบยอดฝากถึงเงื่อนไขหรือยอดฝากใช้กับโปรฯอื่นแล้ว!';
						} else {
							$dataturn = $this->Model_member->GetTurnOverTotalByMember($pg_ids);
							$turnover_now = $dataturn['turnover_now'];
							if ($turnover_now > 0) {
								$BoolMessage = false;
								$boolError = 1;
								$MessageErrorText = 'มียอดเล่นแล้วรับโปรฯ นี้ไม่ได้!';
							} else {
								$RSHNY50 = $this->Model_promo->SearchPromoHNY50Bymember_no($pg_ids);
								if ($RSHNY50['num_rows'] > 0) {
									$BoolMessage = false;
									$boolError = 1;
									$MessageErrorText = 'วันนี้ยูสเซอร์เคยรับโปรฯ นี้ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSHNY50['row']->update_date);
								}
							}
						}
					}
					if ($remarkOption >= 61 && $remarkOption <= 72) { // รางวัลประจำเดือน
						$promoinfo = $this->Model_function->GetchannelInfo($remarkOption);
						if ($promoinfo['remark'] == '') {
							echo 'error';
							exit();
						}
						$checkPro = $this->Model_promo->promo_reward_month_blocked($pg_ids, $promoinfo['channel']);
						if ($checkPro != null) {
							$channelText = ($checkPro->channel == 6) ? 'Comm' : 'AFF';
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = ' ยูสเซอร์เคยรับโบนัส นี้ไปแล้ว - ' . $channelText . '-' . $checkPro->remark . '-' . number_format($checkPro->amount, 2) . ' @' . $checkPro->trx_date . '';
						}
					}
					if ($remarkOption >= 80 && $remarkOption <= 95) { //ฝากประจำ
						$promoinfo = $this->Model_function->GetchannelInfo($remarkOption);
						$daynum = ($promoinfo['promo_id'] - 80);
						$checkPro = $this->Model_promo->promo_frequency_blocked($pg_ids, 500, $daynum);
						if ($daynum != @sizeof($checkPro)) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = "ไม่สามารถรับโปรได้เนื่องจากยังมียอดฝากไม่ครบตามเงื่อนไข (" . sizeof($checkPro) . ")!!";
						} else {
							$log_deposit_id = [];
							foreach ($checkPro as $k => $v) {
								$log_deposit_id[] = $v['id'];
							}
							$amount = $promoinfo['amount'];
							$promo_id = $promoinfo['channel'];
							if ($this->Model_promo->insert_PromoDepositfrequency($pg_ids, $amount, $promo_id) <= 0) {
								$BoolMessage = false;
								$boolError = 1;
								$MessageErrorText = "วันนี้ผู้ใช้รับโปรนี้แล้ว";
							} else {
								$this->Model_db_log->update_log_depositBylist($promo_id, $log_deposit_id);
							}
						}
					}



					$member_no = $pg_ids;
					$member_username = $RSMember['row']->username;

					if ($BoolMessage == true) {
						$this->Model_member->AddCreditManual($member_no, $member_username, $amount, $remark, $remarkOption, $data_deposit);
					}
				} else {
					$boolCheckForm = false;
					$BoolMessage = false;
					$boolError = 1;
					$MessageErrorText = "ไม่พบ User นี้ !!";
				}
			} else {
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่สามารถบันทึกได้ !!";
			}
		}
		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}



	public function del_credit()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;

		$amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
		$remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;

		$member_nos = $this->Model_function->get_decrypt($member_no);
		$trx_id = uniqid();
		$BoolMessage = false;
		$MessageErrorText = "";
		$boolError = 0;


		if ($member_nos != false) {
			$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);

			if ($RSMember['num_rows'] > 0) {
				$BoolMessage = true;
				$member_username = $RSMember['row']->username;

				$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_nos);

				if ($RSM_Wallet['num_rows'] > 0) {
					$amount_main_wallet = ($RSM_Wallet['row']->main_wallet - $amount);
					if ($amount_main_wallet < 0) { /// ถ้ามันลบค่ากันแล้ว ติดลบ (-)
						$amount_main_wallet = 0;
					}
					$this->Model_member->update_member_wallet($member_nos, $amount_main_wallet);
				}


				$this->Model_db_log->insert_log_del_credit($member_nos, $member_username, $amount, $remark);

				$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_nos);

				if ($RSM_Wallet['num_rows'] > 0) {

					$this->Model_member->insert_adjust_wallet_history($member_nos, ($amount * (-1)), $RSM_Wallet['row']->main_wallet, $remark,$trx_id);
				}
				$log_info = "Deleted : " . $amount . " (" . $remark . ")";

				$this->Model_db_log->insert_log_agent($member_username, 'DelCredit', $amount, $log_info);


				$this->Model_promo->ClearProOrTurnOver($member_nos);
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		} else {
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถบันทึกได้ !!";
		}
		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function free_credit()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$remark = isset($_POST['remark-option']) ? $_POST['remark-option'] : NULL;
		$BoolMessage = false;
		$MessageErrorText = "";
		$boolError = 0;
		$member_nos = $this->Model_function->get_decrypt($member_no);
		$trx_id = uniqid();
		$openration_type = $remark;
		$remark_msg = "";

		if ($member_nos != false) {
			$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);

			if ($RSMember['num_rows'] > 0) {

				$checkPro = "";
				$datapro50 = $this->Model_promotion->FindById(13);
				$datapro60 = $this->Model_promotion->FindById(14);
				if ($remark == 1) {
					$openration_type = $datapro50['row']->pro_group_id;
					$promo_id = $datapro50['row']->pro_group_id;
					$channel = $datapro50['row']->channel;
					$remark_msg = $datapro50['row']->pro_symbol;
					$amount = $datapro50['row']->pro_bonus_amount;
					$pro_withdraw_max_amount = $datapro50['row']->pro_withdraw_max_amount;
					$pro_turnover_amount = $datapro50['row']->pro_turnover_amount;
					$checkPro = $this->Model_promo->promo_blocked($member_nos, $promo_id, 0, 0, false);
				} else if ($remark == 2) {
					$openration_type = $datapro60['row']->pro_group_id;
					$promo_id = $datapro60['row']->pro_group_id;
					$channel = $datapro60['row']->channel;
					$remark_msg = $datapro60['row']->pro_symbol;
					$amount = $datapro60['row']->pro_bonus_amount;
					$pro_withdraw_max_amount = $datapro60['row']->pro_withdraw_max_amount;
					$pro_turnover_amount = $datapro60['row']->pro_turnover_amount;
					$checkPro = $this->Model_promo->promo_blocked($member_nos, $promo_id, 0, 1, false);
				}


				$boolError = 1;
				$MessageErrorText = $checkPro['errMsg'];
				if ($checkPro['errCode'] == 200) {

					$BoolMessage = true;
					$member_username = $RSMember['row']->username;

					if ($remark == 1) {
						// $RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($member_nos);
						// if ($RSPromo50['num_rows'] > 0) {
						// 	$BoolMessage = false;
						// 	$boolError = 1;
						// 	$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ ' . $datapro50['row']->pro_bonus_amount . ' ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromo50['row']->update_date);
						// }

						// $RSPromo60 = $this->Model_promo->SearchPromofree60Bymember_no($member_nos);
						// if ($RSPromo60['num_rows'] > 0) {
						// 	$BoolMessage = false;
						// 	$boolError = 1;
						// 	$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ ' . $datapro60['row']->pro_bonus_amount . ' ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromo60['row']->update_date);
						// }
					} else if ($remark == 2) {
						/*
						$RSlogDeposit = $this->Model_db_log->search_log_deposit_totle($member_nos);

						if ($RSlogDeposit['num_rows'] > 0) {

							if ($RSlogDeposit['row']->amount < $datapro50['row']->pro_bonus_amount) {
								$BoolMessage = false;
								$boolError = 1;
								$MessageErrorText = "ยอดฝากจะต้องมากกว่า {$datapro50['row']->pro_bonus_amount} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
							}
							// if ($RSlogDeposit['row']->promo != (-1)) {
							// 	$BoolMessage = false;
							// 	$boolError = 1;
							// 	$MessageErrorText = "รายการฝากนี้ เคยใช้สำหรับรับโปรฯ นี้หรือโปรฯ อื่นไปแล้วค่ะ";
							// } 
						} else {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = "ยอดฝากจะต้องมากกว่า {{$datapro50['row']->pro_bonus_amount}} บาท สมาชิกไม่สามารถรับโปรฯ นี้ได้ค่ะ";
						}
                       */
						// $RSPromo60 = $this->Model_promo->SearchPromofree60Bymember_no($member_nos);
						// if ($RSPromo60['num_rows'] > 0) {
						// 	$BoolMessage = false;
						// 	$boolError = 1;
						// 	$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ ' . $datapro60['row']->pro_bonus_amount . ' ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromo60['row']->update_date);
						// }

						// $RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($member_nos);
						// if ($RSPromo50['num_rows'] > 0) {
						// 	$BoolMessage = false;
						// 	$boolError = 1;
						// 	$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ ' . $datapro50['row']->pro_bonus_amount . ' ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSPromo50['row']->update_date);
						// }
					} else {
						$boolCheckForm = false;
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "กรุณาเลือกหมายเหตุ !!";
					}


					if ($BoolMessage == true) {

						//$RSlogDeposit = $this->Model_db_log->search_log_deposit_lessthen_today($member_nos) 
						$this->Model_promo->ClearProOrTurnOver($member_nos);
						if ($remark == 1) {
							$this->Model_promo->insert_profree50($member_nos, 1, 0, $pro_withdraw_max_amount, $pro_turnover_amount);
						} else if ($remark == 2) {
							$this->Model_promo->insert_profree60($member_nos, 1, 0, $pro_withdraw_max_amount, $pro_turnover_amount);
						}
						$this->Model_promo->Update_member_promo_last($member_nos, $promo_id, "");

						$RsMemberPro = $this->Model_member->SearchMemberPromoBymember_no($member_nos);
						if ($RsMemberPro['num_rows'] > 0) {
							$this->Model_member->Update_member_promo($member_nos, $promo_id, 1, $amount, $remark_msg);
						} else {
							$this->Model_member->insert_member_promo($member_nos, $promo_id, 1, $amount, $remark_msg);
						}

						$trx_date = date("Y-m-d");
						$trx_time = date('H:i:s');
						$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_nos);
						$this->Model_db_log->insert_log_deposit($member_nos, $amount, $channel, $trx_id, 1, $remark_msg, $remark_msg, $trx_date, $trx_time, $promo_id, $openration_type,$RSM_Wallet['row']->main_wallet);
						$this->Model_db_log->insert_log_add_credit($member_nos, $member_username, $amount, $remark_msg, $remark_msg, $openration_type,$trx_date, $trx_time);

						

						if ($RSM_Wallet['num_rows'] > 0) {
							$amount += $RSM_Wallet['row']->main_wallet;
							$this->Model_member->update_member_wallet($member_nos, $amount);

							$this->Model_member->insert_adjust_wallet_history($member_nos, $amount, $RSM_Wallet['row']->main_wallet, $remark_msg,$trx_id);
						}

						$log_info = "Added : " . $amount . " (" . $remark_msg . ")";

						$this->Model_db_log->insert_log_agent($member_username, 'AddCredit', $amount, $log_info);

						$this->Model_member->update_member_turnover($member_nos);
					}
				}
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		} else {
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถบันทึกได้ !!";
		}


		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function add_credit_scb()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;

		$selectdate = isset($_POST['selectdate']) ? $_POST['selectdate'] : NULL;
		$starttime = isset($_POST['starttime']) ? $_POST['starttime'] : NULL;
		$from_acc = isset($_POST['from_acc']) ? $_POST['from_acc'] : NULL;
		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
		$scb_bank_id = isset($_POST['scb_bank_id']) ? $_POST['scb_bank_id'] : 0;

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";
		if ($scb_bank_id == 0) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($amount == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($from_acc == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($starttime == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกเวลา !!";
		}
		if ($selectdate == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกวันที่ !!";
		}

		$member_nos = $this->Model_function->get_decrypt($member_no);

		if ($boolCheckForm == true) {
			if ($member_nos != false) {


				$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);
				if ($RSMember['num_rows'] > 0) {
					$BoolMessage = true;
					$member_username = $RSMember['row']->username;

					$num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_nos, $amount, $selectdate, $starttime, true);

					if ($num_rows_Duplicate == 0) {
						$re_status=$this->Model_log_deposit->UploadImage();
						if($re_status['status']!=200){
							$json_arr = array('Message' => false, 'ErrorText' =>'ไม่สามรถอัพโหลดสลิปได้!'.$re_status, 'boolError' => 1);echo json_encode($json_arr);exit();
						}
						$this->Model_member->AddCreditSCB($member_nos, $member_username, $selectdate, $starttime, $from_acc, $amount, $scb_bank_id, $RSMember['row']->fname, $RSMember['row']->lname, $RSMember['row']->scb_scb,$re_status['file_name']);
					} else {
						$boolCheckForm = false;
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "ยอดซ้ำ !!";
					}
				} else {
					$boolCheckForm = false;
					$BoolMessage = false;
					$boolError = 1;
					$MessageErrorText = "ไม่พบ User นี้ !!";
				}
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function add_credit_kbank()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;

		$selectdate = isset($_POST['selectdate']) ? $_POST['selectdate'] : NULL;
		$starttime = isset($_POST['starttime']) ? $_POST['starttime'] : NULL;
		$from_acc = isset($_POST['from_acc']) ? $_POST['from_acc'] : NULL;
		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
		$kbank_bank_id = isset($_POST['kbank_bank_id']) ? $_POST['kbank_bank_id'] : 0;

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";
		if ($kbank_bank_id == 0) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($amount == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($from_acc == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($starttime == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกเวลา !!";
		}
		if ($selectdate == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกวันที่ !!";
		}

		$member_nos = $this->Model_function->get_decrypt($member_no);

		if ($boolCheckForm == true) {
			if ($member_nos != false) {


				$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);
				if ($RSMember['num_rows'] > 0) {
					$BoolMessage = true;
					$member_username = $RSMember['row']->username;


					$num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_nos, $amount, $selectdate, $starttime, true);

					if ($num_rows_Duplicate == 0) {
						$re_status=$this->Model_log_deposit->UploadImage();
						if($re_status['status']!=200){
							$json_arr = array('Message' => false, 'ErrorText' =>'ไม่สามรถอัพโหลดสลิปได้!'.$re_status, 'boolError' => 1);echo json_encode($json_arr);exit();
						}
						$this->Model_member->AddCreditKbank($member_nos, $member_username, $selectdate, $starttime, $from_acc, $amount, $kbank_bank_id, $RSMember['row']->fname, $RSMember['row']->lname, $RSMember['row']->kbank_kbank,$re_status['file_name']);
					} else {
						$boolCheckForm = false;
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "ยอดซ้ำ !!";
					}
				} else {
					$boolCheckForm = false;
					$BoolMessage = false;
					$boolError = 1;
					$MessageErrorText = "ไม่พบ User นี้ !!";
				}
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function add_credit_truewallet()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;

		$selectdate_truewallet = isset($_POST['selectdate_truewallet']) ? $_POST['selectdate_truewallet'] : NULL;
		$starttime_truewallet = isset($_POST['starttime_truewallet']) ? $_POST['starttime_truewallet'] : NULL;
		$from_acc_truewallet = isset($_POST['from_acc_truewallet']) ? $_POST['from_acc_truewallet'] : NULL;
		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";
		$member_nos = $this->Model_function->get_decrypt($member_no);
		if ($amount == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($from_acc_truewallet == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($starttime_truewallet == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกเวลา !!";
		}
		if ($selectdate_truewallet == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกวันที่ !!";
		}
		if ($member_nos != false) {
			$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);

			if ($RSMember['num_rows'] > 0) {

				if ($boolCheckForm == true) {

					$BoolMessage = true;
					$member_username = $RSMember['row']->username;
					$num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_nos, $amount, $selectdate_truewallet, $starttime_truewallet, true);

					if ($num_rows_Duplicate == 0) {
						$re_status=$this->Model_log_deposit->UploadImage();
						if($re_status['status']!=200){
							$json_arr = array('Message' => false, 'ErrorText' =>'ไม่สามรถอัพโหลดสลิปได้!'.$re_status, 'boolError' => 1);echo json_encode($json_arr);exit();
						}
						$this->Model_member->AddCreditTrueWallet($member_nos, $member_username, $selectdate_truewallet, $starttime_truewallet, $from_acc_truewallet, $amount, $RSMember['row']->fname, $RSMember['row']->lname,$re_status['file_name']);
					} else {
						$boolCheckForm = false;
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "ยอดซ้ำ !!";
					}
				}
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		} else {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่พบ User นี้ !!";
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function list_af_l1()
	{
		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i'); 
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_affiliate->search_aff_l1_by_member_no($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {



					$jsonArr = array(
						"<center>" . $item['username'] . "</center>",
						"<center>" . $item['create_at'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function list_af_l2()
	{
		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_affiliate->search_aff_l2_by_member_no($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {



					$jsonArr = array(
						"<center>" . $item['username'] . "</center>",
						"<center>" . $item['create_at'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_log_deposit_json()
	{
        $data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {

			$Result = $this->Model_db_log->search_log_deposit_member_data_old($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				foreach ($Result['result_array'] as $item) {
					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel'], $item['remark'],$item['remark_internal']);
					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}
					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $item['create_date'] . "</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . $item['trx_id'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
			$Result = $this->Model_db_log->search_log_deposit_member($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel'], $item['remark'],$item['remark_internal']);

					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}  
					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $item['create_date'] . "</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . $item['trx_id'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_log_deposit_backup_json()
	{

		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_db_log->search_log_deposit_backup_member($member_no);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel'], $item['remark'],$item['remark_internal']);

					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}

					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . $item['trx_id'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_log_withdraw_json()
	{
		$param=$this->input->get(null, TRUE);
		$ref = isset($param['ref']) ? $param['ref'] : 0;
		$timestart = isset($param['timestart'])&&@$param['timestart']!='' ? $param['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($param['timeend'])&&@$param['timeend']!='' ? $param['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');
        if(@$param['reporttype']=='sumdaily'){$this->deposit_withdraw_daily();}

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_db_log->search_log_withdraw_member($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel']);

					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}

					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $item['update_date'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . number_format($item['amount_actual'], 2) . " (".@$item['balance_after'].")</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_log_withdraw_backup_json()
	{

		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');
		
		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_db_log->search_log_withdraw_backup_member($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel'], $item['remark'],$item['remark_internal']);

					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}

					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $item['update_date'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . number_format($item['amount_actual'], 2) . "</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_log_game_json()
	{

		$ref = isset($_GET['ref']) ? $_GET['ref'] : 0;

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {


			$Result = $this->Model_db_log->search_log_game_member($member_no);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$jsonArr = array(
						"<center>" . $item['update_date'] . "</center>", "<center>" . $item['provider_name'] . "</center>", "<center>" . $item['money_log'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function reject_deposit()
	{
		$pg_ids = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;


		$ids = $this->Model_function->get_decrypt($pg_ids);

		$boolMessage = false;

		if ($ids != false) {
			$boolMessage = true;

			$this->Model_db_log->update_reject_log_deposit($ids);
		}

		$json_arr = array('Message' => $boolMessage);
		echo json_encode($json_arr);
	}

	public function deposit_withdraw_daily()
	{  
		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i'); 

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {  

			$report_current=$this->Model_report->Report_deposit_withdraw_current_daily($member_no,$timeend);
			if($report_current!=null){
				if(!($report_current['deposit_all']==0&&$report_current['bonus_all']&&$report_current['withdraw']==0&&$report_current['turnover']==0)){
					$profit=($report_current['deposit_all'])-($report_current['withdraw']);
					$profit_text = 'text-success'; 
					if ($profit< 0) {
						$profit_text = 'text-danger';
					} 
					$jsonArr = array(
						"<center>" . $report_current['create_day'] ."</center>",
						"<center>" .number_format($report_current['deposit_all'], 2). "</center>", 
						"<center style='color:#dc35d1'>" .number_format($report_current['withdraw'], 2) . "</center>",
						"<center>" .number_format($report_current['bonus_all'], 2). "</center>",
						"<center class='" . $profit_text . "'>" .number_format($profit,2)."</center>",  
						"<center>" .number_format($report_current['turnover']) . "</center>"
					);
					array_push($dataArr, $jsonArr);
			   }
			}  
			$Result = $this->Model_report->Report_deposit_withdraw_daily($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true; 
				foreach ($Result['result_array'] as $item) {   
					if($item['deposit_all']==0&&$item['withdraw']==0&&$item['turnover']==0&&$item['deposit_pro']==0){continue;}
					$profit=($item['deposit_all'])-($item['withdraw']);
					$profit_text = 'text-success'; 
					if ($profit< 0) {
						$profit_text = 'text-danger';
					} 
					$jsonArr = array(
						"<center>" . $item['create_day'] ."</center>", 
						"<center>" .number_format($item['deposit_all'], 2). "</center>",
						"<center style='color:#dc35d1'>" .number_format($item['withdraw'], 2) . "</center>",
						"<center>" .number_format($item['deposit_pro'], 2). "</center>",
						"<center class='" . $profit_text . "'>" .number_format($profit,2)."</center>",  
						"<center>" .number_format($item['turnover']) . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);exit();
	}
	public function add_credit_vizplay()
	{
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;

		$selectdate = isset($_POST['selectdate']) ? $_POST['selectdate'] : NULL;
		$starttime = isset($_POST['starttime']) ? $_POST['starttime'] : NULL;
		$from_acc = isset($_POST['from_acc']) ? $_POST['from_acc'] : NULL;
		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL; 

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = ""; 
		if ($amount == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($from_acc == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาใส่ข้อมูลให้ครบ !!";
		}
		if ($starttime == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกเวลา !!";
		}
		if ($selectdate == NULL) {
			$boolCheckForm = false;
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "กรุณาเลือกวันที่ !!";
		}

		$member_nos = $this->Model_function->get_decrypt($member_no);

		if ($boolCheckForm == true) {
			if ($member_nos != false) {


				$RSMember = $this->Model_member->SearchMemberByIDs($member_nos);
				if ($RSMember['num_rows'] > 0) {
					$BoolMessage = true;
					$member_username = $RSMember['row']->username;


					$num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_nos, $amount, $selectdate, $starttime, true);

					if ($num_rows_Duplicate == 0) {
						$re_status=$this->Model_log_deposit->UploadImage();
						if($re_status['status']!=200){
							$json_arr = array('Message' => false, 'ErrorText' =>'ไม่สามรถอัพโหลดสลิปได้!'.$re_status, 'boolError' => 1);echo json_encode($json_arr);exit();
						}
						$this->Model_member->AddCreditVizplay($member_nos, $member_username, $selectdate, $starttime, $from_acc, $amount, $RSMember['row']->fname, $RSMember['row']->lname, BankCode2ShortName($RSMember['row']->bank_code),$re_status['file_name']);
					} else {
						$boolCheckForm = false;
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "ยอดซ้ำ !!";
					}
				} else {
					$boolCheckForm = false;
					$BoolMessage = false;
					$boolError = 1;
					$MessageErrorText = "ไม่พบ User นี้ !!";
				}
			} else {
				$boolCheckForm = false;
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่พบ User นี้ !!";
			}
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function list_pro()
	{
		$data = $this->input->get(null, TRUE);
		$ref = isset($data['ref']) ? $data['ref'] : 0;
		$timestart = isset($data['timestart'])&&@$data['timestart']!='' ? $data['timestart'] : date('Y-m-d\TH:i', strtotime('-10 year', strtotime(date("Y-m-d\TH:i"))));
		$timeend = isset($data['timeend'])&&@$data['timeend']!='' ? $data['timeend'] : date('Y-m-d\TH:i');
		$temp_date = DateTime::createFromFormat('Y-m-d\TH:i', $timestart);
		$timestart= $temp_date->format('Y-m-d H:i');
		$temp_date2 = DateTime::createFromFormat('Y-m-d\TH:i', $timeend);
		$timeend= $temp_date2->format('Y-m-d H:i');
		$ref = isset($_GET['ref']) ? $_GET['ref'] : 0;

		$member_no = $this->Model_function->get_decrypt($ref);
		$MessageBool = false;
		$dataArr = array();
		if ($member_no != false) {

			$Result = $this->Model_log_deposit->search_pro_list($member_no,$timestart,$timeend);
			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {
					if ($item['pro_id']) {
						$res = "[P." . $item['pro_id'] . "] " . $item['pro_name'];
					} else {
						$res = "";
					}
					if ($item['status'] == 1) {
						$status = 'Success';
					} elseif ($item['status'] == 0) {
						$status = 'Failed';
					} elseif ($item['status'] == 2) {
						$status = 'Pending';
					} elseif ($item['status'] == 3) {
						$status = 'Rejected';
					} elseif ($item['status'] == 4) {
						$status = 'Refund';
					} elseif ($item['status'] == 5) {
						$status = 'SMS delayed';
					} else {
						$status = '';
					}
					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel']);
					$text_color = 'text-success';
					if ($item['status'] != 1) {
						$text_color = 'text-danger';
					}

					$jsonArr = array(
						// "<center>" . $item['trx_date'] . ' ' . $item['trx_time'] . "</center>",
						// "<center>" . $item['amount'] . "</center>",
						// "<center>" . $status . "</center>",
						// "<center>" . $res . "</center>",


						"<center>" . $item['create_date'] . "</center>",
						"<center>" . $RScc['channelText'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center>" . $item['trx_id'] . "</center>",
						"<center class='" . $text_color . "'>" . $RScc['statusText'] . "</center>",
						"<center>" . $item['remark'] . "</center>"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
}
