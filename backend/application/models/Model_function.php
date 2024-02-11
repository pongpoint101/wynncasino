
<?php
class Model_function extends CI_Model{
	public function testsql(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('members')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, username');
			$sql = $this->db->get('members');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function LoginValidation(){

		if($this->uri->segment(1) != ""){
			if($this->session->userdata("login") != true){
				redirect('','refresh');
				exit();
			} else {
				if($this->PG_Login_BanEmployee() == false){
					redirect('','refresh');
					exit();
				}
			}
		}
	}

	public function LoginGotoDashbord(){
		if($this->session->userdata("login") == true){
			if($this->PG_Login_BanEmployee() == false){
				redirect('','refresh');
				exit();
			}
			redirect('home/index','refresh');
		}
	}

	public function PG_Login_BanEmployee(){

		$ids = $this->session->userdata("id");
		$pg_ids = $this->Model_function->get_decrypt($ids);

		$boolCheck = false;
		if($pg_ids != false){
			$result = $this->Model_member->SearchEmployeesByIDs($pg_ids);
			if($result['num_rows'] > 0){
				if($result['row']->lastaccess==$this->session->userdata("Lastaccess")&&$result['row']->ip==$this->session->userdata("Ip")&&$result['row']->status == 1){
					$boolCheck = true;
				   }else{
					$boolCheck = false;
				}
			}
		}
		if($boolCheck == false){
			$this->Logout();
		}
		return $boolCheck;
	}

	public function Logout(){
		setcookie("ck_login", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_ref", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_Username", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_SurName", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		$array_items = array('login' => false,
							 'id' => '',
							 'Username' => '',
							 'SurName' => '');
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy();
	}
	public function get_decrypt($decrypt_ref){
		$string_raw_ref = strtr(
                $decrypt_ref,
                array(
                    '.' => '+',
                    '-' => '=',
                    '~' => '/',
                    ' ' => '+'
                )
            );
		$REFIDs = $this->encryption->decrypt($string_raw_ref);

		return $REFIDs;
	}
	public function ConvertDateTimeShortTH($fs_Date){

		$date_check = $this->Model_function->CheckFormatDate($fs_Date);

		$ResultDate = ""; 
		$thMonArr =array(
				"0"=>"",
				"1"=>"ม.ค.",
				"2"=>"ก.พ.",
				"3"=>"มี.ค.",
				"4"=>"เม.ย",
				"5"=>"พ.ค.",
				"6"=>"มิ.ย.",	
				"7"=>"ก.ค.",
				"8"=>"ส.ค.",
				"9"=>"ก.ย.",
				"10"=>"ต.ค.",
				"11"=>"พ.ย.",
				"12"=>"ธ.ค."					
			);
		$date = date('j',strtotime($fs_Date));
		$month = $thMonArr[date('n',strtotime($fs_Date))];
		$year = intval(date('Y',strtotime($fs_Date)))+543;

		$ResultDate = $date." ".$month." ".$year." / ".date('H:i',strtotime($fs_Date));
		return $ResultDate;
	}

	public function ConvertDateShortTH($fs_Date){

		$date_check = $this->Model_function->CheckFormatDate($fs_Date);
		$ResultDate = ""; 
		$thMonArr =array(
					"0"=>"",
					"1"=>"ม.ค.",
					"2"=>"ก.พ.",
					"3"=>"มี.ค.",
					"4"=>"เม.ย",
					"5"=>"พ.ค.",
					"6"=>"มิ.ย.",	
					"7"=>"ก.ค.",
					"8"=>"ส.ค.",
					"9"=>"ก.ย.",
					"10"=>"ต.ค.",
					"11"=>"พ.ย.",
					"12"=>"ธ.ค."					
			);
		$date = date('j',strtotime($fs_Date));
		$month = $thMonArr[date('n',strtotime($fs_Date))];
		$year = intval(date('Y',strtotime($fs_Date)))+543;

		$ResultDate = $date." ".$month." ".$year;
		
		return $ResultDate;
	}

	function CheckFormatDate($date){
		$dt = DateTime::createFromFormat("Y-m-d", $date);
		return $dt !== false && !array_sum($dt::getLastErrors());
	}
	public function condition_check_status($status,$channel,$remark='',$remark_internal=''){
		$item['statusText'] = "-";
		$item['channelText'] = "-";

		$item['class_text_color'] = "";
		$item['gif_alert'] = "";

		if ($channel == 1) { 
			$item['channelText'] = 'Bank'; 
			if (preg_match('/^VizPay.*/', $remark, $matches)||preg_match('/^VizPay.*/', $remark_internal, $matches)) { 
				$item['channelText']='VizPay';
			}  
		} elseif ($channel == 2) {
			$item['channelText'] = 'TrueWallet';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/siren01.gif').'" width="30%"> ';
		} elseif ($channel == 3) {
			$item['channelText'] = 'SMS';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel == 4) {
			$item['channelText'] = 'Pro20%';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel == 5) {
			$item['channelText'] = 'Credited by Admin';
		} elseif ($channel == 16) {
			$item['channelText'] = 'AFF'; 
			$item['gif_alert'] = '<img src="'.base_url('pg_image/siren01.gif').'" width="30%"> ';
		}elseif ($channel == 17) {
			$item['channelText'] = 'Cashback';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/siren01.gif').'" width="30%"> ';
		} elseif ($channel == 18) {
			$item['channelText'] = 'Commission';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/siren01.gif').'" width="30%"> ';
		} elseif ($channel == 8) {
			$item['channelText'] = 'CardGame';
		} elseif ($channel == 9) {
			$item['channelText'] = 'LuckyWheel';
		} elseif ($channel == 13) {
			$item['channelText'] = 'Free200';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 14) {
			$item['channelText'] = 'Free60';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 10) {
			$item['channelText'] = 'Pro50%';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel == 11) {
			$item['channelText'] = 'Pro100%';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 12) {
			$item['channelText'] = 'Bonus32';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel>=101&&$channel <=105) {
			$item['channelText'] = 'Pro10%';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel == 106) {
			$item['channelText'] = 'HappyTime-01';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/happytime.gif').'" width="30%"> ';
		} elseif ($channel == 107) {
			$item['channelText'] = 'HappyTime-02';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/happytime.gif').'" width="30%"> ';
		} elseif ($channel>=108&&$channel <=111) {
			$item['channelText'] = 'ขาประจำ';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}  elseif ($channel == 20) {
			$item['channelText'] = 'HNY50'; 
		}elseif ($channel == 19) {
			$item['channelText'] = 'DAILY_TO_RD';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 83) {
			$item['channelText'] = 'FQ200';
		} elseif ($channel == 87) {
			$item['channelText'] = 'FQ500';
		} elseif ($channel == 90) {
			$item['channelText'] = 'FQ1000';
		} elseif ($channel == 95) {
			$item['channelText'] = 'FQ2000';
		} elseif ($channel == 50) {
			$item['channelText'] = 'CASINO300';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		} elseif ($channel == 51) {
			$item['channelText'] = 'CASINO2%';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 21) {
			$item['channelText'] = 'WB100';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 22) {
			$item['channelText'] = 'MC25';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 25) {
			$item['channelText'] = 'ProU150';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 112) {
			$item['channelText'] = 'Pro112';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 113) {
			$item['channelText'] = 'Pro599';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}elseif ($channel == 114) {
			$item['channelText'] = 'MV50';
			$item['gif_alert'] = '<img src="'.base_url('pg_image/warning.gif').'" width="30%"> ';
		}else{
			$item['channelText'] = 'อื่น ๆ';
		} 
		
		if ($status == 0) {
           $item['statusText'] = 'Failed';
           $item['class_text_color'] = 'text-danger';
        } elseif ($status == 1) {
           $item['statusText'] = 'Success';
           $item['class_text_color'] = 'text-success';
        } elseif ($status == 2) {
           $item['statusText'] = 'Pending';
           $item['class_text_color'] = 'text-warning';
        } elseif ($status == 3) {
           $item['statusText'] = 'Reject';
           $item['class_text_color'] = 'text-danger';
        } elseif ($status == 4) {
           $item['statusText'] = 'Refund';
           $item['class_text_color'] = 'text-danger';
        } elseif ($status == 5) {
           $item['statusText'] = 'SMS delayed';
           $item['class_text_color'] = 'text-danger';
        } else {
           $item['statusText'] = 'อื่น ๆ';
           $item['class_text_color'] = 'text-primary';
        }


		return $item;
	}
	public function GetchannelInfo($channel){
		  $remark='';$amount=0;$member_last_deposit='';
		  $promo_id=0;
          switch ($channel) {
			case 61:
				$remark = "A108";
				$amount = 3000;
				$channel = 7; 
				break;
			case 62:
				$remark = "A109";
				$amount = 2000;
				$channel = 7;
				break;
			case 63:
				$remark = "A110";
				$amount = 1500;
				$channel = 7;
				break;
			case 64:
				$remark = "A111";
				$amount = 1000;
				$channel = 7;
				break;
			case 65:
				$remark = "A112";
				$amount = 500;
				$channel = 7;
				break;
			case 66:
				$remark = "A113";
				$amount = 250;
				$channel = 7;
				break;
			case 67:
				$remark = "A114";
				$amount = 5000;
				$channel = 6;
				break;
			case 68:
				$remark = "A115";
				$amount = 3000;
				$channel = 6;
				break;
			case 69:
				$remark = "A116";
				$amount = 2000;
				$channel = 6;
				break;
			case 70:
				$remark = "A117";
				$amount = 1000;
				$channel = 6;
				break;
			case 71:
				$remark = "A118";
				$amount = 500;
				$channel = 6;
				break;
			case 72:
			$remark = "A119";
			$amount = 250;
			$channel = 6;
			break;
			case 83:
				$remark = "FQ200";
				$amount = 200; 
				$promo_id =$channel;
				break;	
			case 87:
				$remark = "FQ500";
				$amount =500; 
				$promo_id =$channel;
				break;	
			case 90:
				$remark = "FQ1000";
				$amount =1000; 
				$promo_id =$channel;
				break;	
			 case 95:
				$remark = "FQ2000";
				$amount =2000; 
				$promo_id =$channel; 
				break;	
			  default:   break;
		  } 
		  return ['remark'=>$remark,'amount'=>$amount,'channel'=>$channel,'promo_id'=>$promo_id
		  		  ,'member_last_deposit'=>$member_last_deposit];
	}
	public function getIP() {
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
	public function isDomain($url) {
		$pattern = '/^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,6}\/?$/i';
		try {
			return (bool) preg_match($pattern, $url);
		} catch (Exception $e) { // จัดการกับข้อผิดพลาดที่เกิดขึ้นที่นี่ (ถ้ามี)
			return false;
		}
	}
	
}
?>