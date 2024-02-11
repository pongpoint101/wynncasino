<?php
defined('BASEPATH') or exit('No direct script access allowed');
use GuzzleHttp\Client;
class Bank extends MY_Controller
{


	public function __construct()
	{
		parent::__construct(); 
		$this->load->library("ConfigData");
	}
	public function view()
	{
	 
		if ($this->allow_action) {
			$dis = "";
		} else {
			$dis = "disabled";
		}
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['RSBank'] = $this->Model_bank->list_bank_system();

		$item['pg_title'] = 'Dashbord';
		$item['hide_btn'] = $dis;

		$this->load->view('view_bank', $item);
	}

	public function list_bank_withdraw_json()
	{    
		$m_Result = $this->Model_affiliate->list_m_site(); 
		$ch = curl_init($m_Result['row']->withdraw_trx_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result, true);



		$json_arr = array('data' => $data);
		echo json_encode($json_arr);
	}


	public function withdraw()
	{
		$bank_withdraw=$this->Model_bank->Get_bankByType(2); 
		$m_Result = $this->Model_affiliate->list_m_site();  
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);    
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);//NO CACHE 
        curl_setopt($ch, CURLOPT_URL, $m_Result['row']->withdraw_trx_url);
        
        $result = curl_exec($ch);  
		curl_close($ch);
		$item['bank_withdraw'] =[];
		if($bank_withdraw['num_rows']>0){
			$item['bank_withdraw'] =(array)$bank_withdraw['row'];
		} 
		// echo "<pre>";print_r(json_decode($result, true));exit();
		$item['curl'] = json_decode($result, true); 
		// echo "<pre>";
		// echo var_dump($item['curl']["transactions"]);
		// echo "</pre>";
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['current_time'] =date("Y-m-d H:i:s", time());
		$item['RSBank'] = $this->Model_bank->list_bank_system();

		$item['pg_title'] = 'Bank Withdraw';

		$this->load->view('view_bank_withdraw', $item);
	}

	public function history()
	{
		$item['PGDateNow'] = date("Y-m-d");


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$this->load->view('view_bank_history', $item);
	}

	public function statement()
	{
		$ref = isset($_GET['ref']) ? $_GET['ref'] : NULL;

		$bank_setting_id = $this->Model_function->get_decrypt($ref);

		if ($bank_setting_id == false) {
			redirect('bank', 'refresh');
			exit();
		}
		if ($this->db->table_exists("auto_bank_scb_" . $bank_setting_id) == false) {
			redirect('bank', 'refresh');
			exit();
		}

		$Result = $this->Model_bank->search_list_bank_setting_by_id($bank_setting_id);

		if ($Result['num_rows'] <= 0) {
			redirect('bank', 'refresh');
			exit();
		}

		$item['bank_setting_id'] = $ref;

		$item['SumCashIN'] = $this->Model_bank->sumCashINSCB($bank_setting_id);
		$item['SumCashOUT'] = $this->Model_bank->sumCashOUTSCB($bank_setting_id);

		$item['bank_account'] = $Result['row']->bank_account;
		$item['account_name'] = $Result['row']->account_name;

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$this->load->view('view_bank_statement', $item);
	}

	public function list_json()
	{
		$Result = $this->Model_bank->list_bank_setting();
		$MessageBool = false;
		$dataArr = array();
 
		if ($this->allow_action) {
			$dis = "";
		} else {
			$dis = "disabled";
		}
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			$Ti = 1;
			foreach ($Result['result_array'] as $item) {

				$typeText = "ปิด";
				if ($item['type'] == 1) {
					$typeText = '<span class="badge badge-default badge-success">ฝาก</span>';
				} elseif ($item['type'] == 2) {
					$typeText = '<span class="badge badge-default badge-danger">ถอน</span>';
				}
				$Statement = '<center><i class="la la-ban danger"></i></center>';
				$TRXChecked = '<center><i class="la la-ban danger"></i></center>';
				if ($item['bank_code'] == "014") {
					if ($this->db->table_exists("auto_bank_scb_" . $item['id']) != false) {
						$Statement = '<center><a target="_blank" class="btn btn-icon btn-light" href="' . base_url('bank/statement?ref=' . $this->encryption->encrypt($item['id'])) . '"><i class="la la-list"></i></a></center>';
					} else {
						$Statement = '<center class="danger">NO TABLE !!</center>';
					}
					$statusTRXChecked = '';
					if ($item['get_trx'] == 1) {
						$statusTRXChecked = "checked";
					}

					$TRXChecked = '<fieldset>
                						<div class="float-left">
                							<input type="checkbox"' . $dis . '  class="switch_get_trx_pggamge" ref-id="' . $this->encryption->encrypt($item['id']) . '" data-on-color="success" data-off-color="danger" ' . $statusTRXChecked . '/>
                						</div>
                				   </fieldset>';
				}
				$statusChecked = "";
				if ($item['status'] == 1) {
					$statusChecked = "checked";
				}

				$jsonArr = array(
					$Ti,
					'<center>' . $item['short_name'] . '</center>',
					'<center>' . $item['account_name'] . '</center>',
					'<center>' . $item['bank_account'] . '</center>',
					'<center>' . $typeText . '</center>',
					$Statement,
					'<fieldset>
								 	<div class="float-left">
								 		<input type="checkbox"' . $dis . ' class="switch_status_pggamge" ref-id="' . $this->encryption->encrypt($item['id']) . '" data-on-color="success" data-off-color="danger" ' . $statusChecked . '/>
								 	</div>
								 </fieldset>',
					$TRXChecked,
					'<button ' . $dis . ' onclick="EditBankSetting(\'' . $this->encryption->encrypt($item['id']) . '\')" class="btn btn-icon btn-info"><i class="la la-edit" style="vertical-align: bottom;"></i></button><button type="button" ' . $dis . ' class="btn btn-icon btn-danger ml-1" onclick="DeleteMainActive(\'' . $this->encryption->encrypt($item['id']) . '\')"><i class="la la-trash-o"></i></button>'
				);
				array_push($dataArr, $jsonArr);
				$Ti++;
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_bank_history_json()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array();
		if ($trx_date_check == true) {
			$Result = $this->Model_bank->search_log_auto_bank_by_date($trx_date);
			if ($Result['num_rows'] > 0) {
				foreach ($Result['result_array'] as $item) {

					$res = json_decode($item['log_msg'], true);

					$jsonArr = array(
						"<center>" . $item['update_date'] . "</center>",
						"<center>" . $res['acct'] . "</center>",
						"<center>" . $item['account_name'] . "</center>",
						"<center>" . number_format($res['total']) . "</center>",
						"<center>" . number_format($res['total_dup']) . "</center>",
						"<center>" . number_format($res['total_rec']) . "</center>",
						"Success"
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function add_edit_m_bank_setting()
	{
		$ids = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$bank_code = isset($_POST['bank_code']) ? $_POST['bank_code'] : NULL;
		$bank_account = isset($_POST['bank_account']) ? $_POST['bank_account'] : NULL;
		$account_name = isset($_POST['account_name']) ? $_POST['account_name'] : NULL;
		$type = isset($_POST['m_bank_setting_type']) ? $_POST['m_bank_setting_type'] : NULL;
		$username = isset($_POST['m_bank_setting_username']) ? $_POST['m_bank_setting_username'] : NULL;
		$password = isset($_POST['m_bank_setting_password']) ? $_POST['m_bank_setting_password'] : NULL;
 
		if ($this->allow_action) {
			$bank_setting_id = $this->Model_function->get_decrypt($ids);
			if ($bank_setting_id != false) {
				$this->Model_bank->bank_updated($bank_setting_id, $bank_code, $bank_account, $account_name, $type);
			} else {
				$this->Model_bank->bank_inserted($bank_code, $bank_account, $account_name, $type, $username, $password);
			}
		} else {
			$json_arr = array('Message' => false, 'boolError' => 1);
			echo json_encode($json_arr);
		}
	}

	public function delete_bank_setting()
	{
		$ids = isset($_POST['ref_ids']) ? $_POST['ref_ids'] : 0;
		$bank_setting_id = $this->Model_function->get_decrypt($ids);
		$BoolMessage = false;
		if ($bank_setting_id != false) {
			$BoolMessage = true;
			$this->Model_bank->delete_bank_setting($bank_setting_id);
		}
		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function list_json_id()
	{

		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$bank_setting_id = $this->Model_function->get_decrypt($ids); 

		if ($this->allow_action) {
			$MessageBool = false;
			$dataArr = array();
			if ($bank_setting_id != false) {
				$MessageBool = true;
				$Result = $this->Model_bank->search_list_bank_setting_by_id($bank_setting_id);

				if ($Result['num_rows'] > 0) {
					$MessageBool = true;

					foreach ($Result['result_array'] as $item) {

						$jsonArr = array(
							'bank_code' => $item['bank_code'],
							'bank_account' => $item['bank_account'],
							'account_name' => $item['account_name'],
							'type' => $item['type']
						);
						array_push($dataArr, $jsonArr);
					}
				}
			}

			$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
			echo json_encode($json_arr);
		} else {
			$json_arr = array('Message' => false, 'Result' => '');
			echo json_encode($json_arr);
		}
	}

	public function update_bank_setting_status()
	{
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_status = isset($_POST['pg_status']) ? $_POST['pg_status'] : 0;


		//0 = ปิด ; 1 = เปิด
		if ($pg_status == "true") {
			$pg_status = 1;
		} else if ($pg_status == "false") {
			$pg_status = 0;
		}
		$BoolMessage = false;


		$pg_ids = $this->Model_function->get_decrypt($ids);

		if ($pg_ids != false) {
			$BoolMessage = true;
			$this->Model_bank->update_bank_setting_status($pg_ids, $pg_status);
		}

		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function update_bank_setting_trx()
	{
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_status = isset($_POST['pg_status']) ? $_POST['pg_status'] : 0;


		//0 = NO ; 1 = YES
		if ($pg_status == "true") {
			$pg_status = 1;
		} else if ($pg_status == "false") {
			$pg_status = 0;
		}
		$BoolMessage = false;


		$pg_ids = $this->Model_function->get_decrypt($ids);

		if ($pg_ids != false) {
			$BoolMessage = true;
			$this->Model_bank->update_bank_setting_trx($pg_ids, $pg_status);
		}

		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function list_scb_auto_json()
	{

		$ids = isset($_GET['ref']) ? $_GET['ref'] : 0;
		$bank_setting_id = $this->Model_function->get_decrypt($ids);

		$dataArr = array();
		if ($bank_setting_id != false) {

			if ($this->db->table_exists("auto_bank_scb_" . $bank_setting_id) == true) {
				$Result = $this->Model_bank->SearchSCBAutoByBank_setting_id($bank_setting_id);

				if ($Result['num_rows'] > 0) {
					$MessageBool = true;

					foreach ($Result['result_array'] as $item) {
						// $can_use = ($item['auto_amount_wd'] < 0 ? false : true);

						// if ($can_use) {
						// 	$amount = $item['auto_amount_wd'];
						// } else {
						// 	$amount = $item['auto_amount'];
						// }

						$amount = $item['auto_amount'];

						$jsonArr = array(
							"<center>" . $item['auto_date'] . " " . $item['auto_time'] . "</center>",
							"<center>" . $item['auto_datetime'] . "</center>",
							"<center>" . $item['auto_channel'] . "</center>",
							//"<center class='".(!$can_use ? 'text-danger' : '')."'>".$amount."</center>",
							"<center>" . $amount . "</center>",
							"<center>" . $item['auto_name'] . "</center>"
						);
						array_push($dataArr, $jsonArr);
					}
				}
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
}
