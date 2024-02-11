<?php
class Model_banner extends MY_Model{

	public function get_pic(){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('banner')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){ 
			$this->db->select('id, name, type');
			$sql = $this->db->get('banner'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function get_pic_by_type($type){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('banner')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){ 
			$this->db->select('id, name, type');
			$this->db->where('type =', $type);
			$sql = $this->db->get('banner'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function get_pic_old($id){
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('banner')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){ 
			$this->db->select('id, name, type');
			$this->db->where('id =', $id);
			$sql = $this->db->get('banner'); 
			$result_array = $sql->result_array();
			$row = $sql->row(); 
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function delete_banner($id)
	{
		try {
			$this->db->where('id', $id);
			$del = $this->db->delete('banner'); 
			if (!$del) {
				$this->Data->error_code=500;return false;
			} 
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
      	return $this->Data;
		
	}

	public function EditById($Data){
		try {
			$this->db->where('id =',$Data->id);
			$edit =$this->db->update('banner', $Data);   
			if (!$edit) {
				$this->Data->error_code=500;return false;
			} 
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
      return $this->Data;
    }
    public function banner_insert($name, $type)
	{

		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");
        $this->db->set('name', $name);
        $this->db->set('type', $type);
        $this->db->set('update_date', $DateTimeNow);
        $add = $this->db->insert('banner');
        if (!$add) {
            $this->Data->error_code=500;return false;
        } 
        $this->Data->error_code=200; 
        return $this->Data;

	}

}
?>