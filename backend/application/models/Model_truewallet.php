<?php
class Model_truewallet extends CI_Model{
	public function SettingTruewallet(){
		$this->db->select('COUNT(wallet_no) AS num_rows', FALSE);
		$num_rows = $this->db->get('m_truewallet_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('wallet_no, wallet_phone, wallet_show, wallet_pass, wallet_name, wallet_status, wallet_ref, wallet_access, 
							   wallet_tracking, update_by, update_date');
			$sql = $this->db->get('m_truewallet_setting');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function update_truewallet_status($wallet_no,$pg_status){
		$this->db->set('wallet_status', $pg_status);
		$this->db->where('wallet_no', $wallet_no);
		$this->db->update('m_truewallet_setting');
	}

	public function wallet_inserted($wallet_phone,$wallet_show,$wallet_name,$wallet_pass){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;

		$this->db->set('wallet_phone', $wallet_phone);
		$this->db->set('wallet_show', $wallet_show);
		$this->db->set('wallet_name', $wallet_name);
		$this->db->set('wallet_pass', $wallet_pass);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->insert('m_truewallet_setting');

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function wallet_updated($wallet_no,$wallet_phone,$wallet_show,$wallet_name,$wallet_pass){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");


		$bool = true;
		$MessageErrorText = "";

		$boolError = 0;

		$this->db->set('wallet_phone', $wallet_phone);
		$this->db->set('wallet_show', $wallet_show);
		$this->db->set('wallet_name', $wallet_name);
		$this->db->set('wallet_pass', $wallet_pass);
		$this->db->set('update_by', $this->session->userdata("Username"));
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('wallet_no =', $wallet_no);
		$this->db->update('m_truewallet_setting');

		$json_arr = array('Message' => $bool, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function delete_truewallet($wallet_no){
		$this->db->where('wallet_no', $wallet_no);
		$this->db->delete('m_truewallet_setting');
	}
	public function SearchTruewalletByID($wallet_no){
		$this->db->select('COUNT(wallet_no) AS num_rows', FALSE);
		$this->db->where('wallet_no =', $wallet_no);
		$num_rows = $this->db->get('m_truewallet_setting')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('wallet_no, wallet_phone, wallet_show, wallet_pass, wallet_name, wallet_status, wallet_ref, wallet_access, 
							   wallet_tracking, update_by, update_date');
			$this->db->where('wallet_no =', $wallet_no);
			$sql = $this->db->get('m_truewallet_setting');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	// public function UpdateSettingProviders($provider_id,$status,$config_string){
	// 	date_default_timezone_set('Asia/Bangkok');
	// 	$DateTimeNow = date("Y-m-d H:i:s");

	// 	$this->db->set('status', $status);
	// 	$this->db->set('config_string', $config_string);
	// 	$this->db->set('update_date', $DateTimeNow);
	// 	$this->db->where('id =', $provider_id);
	// 	$this->db->update('m_providers');
	// }
}
?>