<?php
class Model_providers extends CI_Model{
	public function SettingProviders(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('m_providers')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, site_id, operation_mode, provider_code, provider_name, status, config_string, update_date');
			$sql = $this->db->get('m_providers');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function SearchSettingProvidersByID($provider_id){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('id =', $provider_id);
		$num_rows = $this->db->get('m_providers')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id, site_id, operation_mode, provider_code, provider_name, status, config_string, update_date');
			$this->db->where('id =', $provider_id);
			$sql = $this->db->get('m_providers');

			$result_array = $sql->result_array();
			$row = $sql->row();

		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function UpdateSettingProviders($provider_id,$status,$config_string){
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('status', $status);
		$this->db->set('config_string', $config_string);
		$this->db->set('update_date', $DateTimeNow);
		$this->db->where('id =', $provider_id);
		$this->db->update('m_providers');
	}
}
?>