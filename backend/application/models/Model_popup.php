<?php
class Model_popup extends MY_Model{ 
	public function findById($id){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('popup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){  
			$this->db->where('id =', $id);
			$sql = $this->db->get('popup'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function Getall(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('popup')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){   
			$this->db->order_by('m_order', 'ASC');
			$sql = $this->db->get('popup'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	} 

	public function EditById($Data){
		try {
			$this->db->where('id =',$Data->id);
			unset($Data->id);
			$edit =$this->db->update('popup', $Data);   
			if (!$edit) {
				$this->Data->error_code=500;return false;
			} 
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
      return $this->Data;
    }  
}
?>