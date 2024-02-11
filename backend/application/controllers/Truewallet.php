<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Truewallet extends MY_Controller
{ 
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library("ConfigData");
	}
	public function view()
	{

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Truewallet'; 
		if ($this->allow_action) {
			$dis = "";
		} else {
			$dis = "disabled";
		}
		$item['hide_btn'] = $dis;

		$this->load->view('view_truewallet', $item);
	}

	public function list_json()
	{
		$Result = $this->Model_truewallet->SettingTruewallet();
		$MessageBool = false;
		$dataArr = array();
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			$Ti = 1;
			foreach ($Result['result_array'] as $item) {
				$statusChecked = "";
				if ($item['wallet_status'] == 1) {
					$statusChecked = "checked";
				} 

				if ($this->allow_action) {
					$dis = '';
				} else {
					$dis = 'disabled';
				}
				$jsonArr = array(
					$Ti,
					$item['wallet_show'],
					$item['wallet_phone'],
					$item['wallet_name'],
					'<fieldset>
								 	<div class="float-left">
								 		<input '.$dis.' type="checkbox" class="switch_status_pggamge" ref-id="' . $this->encryption->encrypt($item['wallet_no']) . '" data-on-color="success" data-off-color="danger" ' . $statusChecked . '/>
								 	</div>
								 </fieldset>',
					'<button '.$dis.' onclick="EditTruewallet(\'' . $this->encryption->encrypt($item['wallet_no']) . '\')" class="btn btn-icon btn-info"><i class="la la-edit" style="vertical-align: bottom;"></i></button><button '.$dis.' type="button" class="btn btn-icon btn-danger ml-1" onclick="DeleteMainActive(\'' . $this->encryption->encrypt($item['wallet_no']) . '\')"><i class="la la-trash-o"></i></button>'
				);
				array_push($dataArr, $jsonArr);
				$Ti++;
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function update_truewallet_status()
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
			$this->Model_truewallet->update_truewallet_status($pg_ids, $pg_status);
		}

		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function add_edit_truewallet()
	{
		$ids = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$wallet_phone = isset($_POST['wallet_phone']) ? $_POST['wallet_phone'] : NULL;
		$wallet_show = isset($_POST['wallet_show']) ? $_POST['wallet_show'] : NULL; // อันนนี้คือ Email
		$wallet_name = isset($_POST['wallet_name']) ? $_POST['wallet_name'] : NULL;
		$wallet_pass = isset($_POST['wallet_pass']) ? $_POST['wallet_pass'] : NULL;


		$wallet_no = $this->Model_function->get_decrypt($ids);
		if ($wallet_no != false) {
			$this->Model_truewallet->wallet_updated($wallet_no, $wallet_phone, $wallet_show, $wallet_name, $wallet_pass);
		} else {
			$this->Model_truewallet->wallet_inserted($wallet_phone, $wallet_show, $wallet_name, $wallet_pass);
		}
	}
	public function delete_truewallet()
	{
		$ids = isset($_POST['ref_ids']) ? $_POST['ref_ids'] : 0;
		$wallet_no = $this->Model_function->get_decrypt($ids);
		$BoolMessage = false;
		if ($wallet_no != false) {
			$BoolMessage = true;
			$this->Model_truewallet->delete_truewallet($wallet_no);
		}
		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function list_json_id()
	{

		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$wallet_no = $this->Model_function->get_decrypt($ids);

		$MessageBool = false;
		$dataArr = array();
		if ($wallet_no != false) {
			$MessageBool = true;
			$Result = $this->Model_truewallet->SearchTruewalletByID($wallet_no);

			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {

					$jsonArr = array(
						'wallet_no' => $item['wallet_no'],
						'wallet_phone' => $item['wallet_phone'],
						'wallet_show' => $item['wallet_show'],
						'wallet_pass' => $item['wallet_pass'],
						'wallet_name' => $item['wallet_name']
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}
}
