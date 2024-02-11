<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    private $secretkeycaptcha='';
	private $publickeycaptcha='';
	private $maintenance='';
	public function __construct() {
		parent::__construct();  
		$res=$this->Model_affiliate->list_m_site(); 
		$this->secretkeycaptcha=$res['row']->secret_key_captcha;
		$this->publickeycaptcha=$res['row']->public_key_captcha;
		$this->maintenance=$res['row']->maintenance;
	} 
	public function index()
	{ 
        if($this->maintenance==99){'code 5007';exit();}
		$this->Model_function->LoginGotoDashbord();

		$this->load->view('view_login',['publickeycaptcha'=>$this->publickeycaptcha]);
	}
	private function checkcaptch(){
	  $secretkeyrecaptcha =$this->secretkeycaptcha;
	  if($secretkeyrecaptcha==''||is_null($secretkeyrecaptcha)){return true;} 
	  if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
		$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkeyrecaptcha&response=$captcha&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if(!$captcha){
		    $missinginputsecret = ["The response parameter is missing."];
		     print_r($missinginputsecret);exit();
		   }        
		  }else{
		  exit();
		}  
	 }
	public function system(){
		date_default_timezone_set('Asia/Bangkok');
		$UserName = isset($_POST['PGUserName']) ? $_POST['PGUserName'] : NULL;
		$UserPass = isset($_POST['PGPassword']) ? $_POST['PGPassword'] : NULL;
		$MywayRemember = isset($_POST['MywayRemember']) ? $_POST['MywayRemember'] : 0;
        
		$this->checkcaptch();
		$boollogin = true;
		if($UserName == NULL){
			$boollogin = false;$ErrorText = "code 400";
		}
		if($UserPass == NULL){
			$boollogin = false;$ErrorText = "code 401";
		}
		$ErrorText = "";
		if($this->maintenance==99){$boollogin = false;$ErrorText = "code 5007";}
		if($boollogin == true){
			$ResultUser = $this->Model_member->SearchEmployeesByUsername($UserName);
			if($ResultUser['num_rows'] > 0){
				$md5DecodePass = $this->encryption->decrypt($ResultUser['row']->password);
				if(password_verify($UserPass,$ResultUser['row']->password)){

					if($ResultUser['row']->status == 1){
						// if($MywayRemember == 1){
						// 	setcookie("ck_login", true, time()+ (10 * 365 * 24 * 60 * 60),$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
						// 	setcookie("ck_ref", $this->encryption->encrypt($ResultUser['row']->id),  time()+ (10 * 365 * 24 * 60 * 60),$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
						// 	setcookie("ck_Username", $ResultUser['row']->username,  time()+ (10 * 365 * 24 * 60 * 60),$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
						// 	setcookie("ck_SurName", $ResultUser['row']->fname,  time()+ (10 * 365 * 24 * 60 * 60),$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
						// } 
						$lastaccess=date("Y-m-d H:i:s");
						$ipaddress=$this->Model_function->getIP();
						$arrSession = array('login' => true,
											'id' => $this->encryption->encrypt($ResultUser['row']->id),
											'userID' => $this->encryption->encrypt($ResultUser['row']->id), 
											'Username' => $ResultUser['row']->username,
											'password_change' => $ResultUser['row']->password_change,
											'level' => $ResultUser['row']->level,
											'Lastaccess' => $lastaccess,
											'Ip' => $ipaddress,
											'token' => base64_encode($ResultUser['row']->id),
											'chktime_userinfo' =>time(),
											'SurName' => $ResultUser['row']->fname);
						$this->session->set_userdata($arrSession); 
 
						$this->db->set('lastaccess',$lastaccess); 
						$this->db->set('ip',$ipaddress); 
						$this->db->where('id', $ResultUser['row']->id);
						$this->db->update('employees'); 

					} else {
						$ErrorText = "บัญชีของท่านถูกระงับการใช้งาน !!";
						$boollogin = false;
					}
				} else {
					$ErrorText = "ชื่อบัญชีหรือรหัสผ่านไม่ถูกต้อง !!";
					$boollogin = false;
				}
			} else {
				$boollogin = false;$ErrorText = "code 402";
			}
		}

		$json_arr = array('Message' => $boollogin, 'ErrorText' => $ErrorText);
		echo json_encode($json_arr);
	}
}
