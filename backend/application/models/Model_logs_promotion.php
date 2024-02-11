
<?php
class Model_logs_promotion extends MY_Model{   
	public function SaveData($data){
		try {  
		      $this->EditById($data->pro_id, $data); 
			 if ($this->Data->error_code!=200) {
			   throw new Exception('save errro.'); 
			 }  
		  } catch (Exception $e) {
			$this->Data->error_code=500;
		 } 
	   return $this->Data;
   }
   public function EditById($id,$Data){
	try {
		$this->db->where('id', $id);  
		$edit =$this->db->update('pro_logs_promotion', $Data);   
		if (!$edit) {
			$this->Data->error_code=500;return false;
		} 
	  } catch (Exception $e) {
		$this->Data->error_code=500;return false;
	}
	$this->Data->error_code=200; 
    }
	public function FindAll($pro_id=null){ 
		    $row='';$result_array='';      
			if ($pro_id!=''&&$pro_id!=null) { 
				$this->db->where('pro_id', $pro_id); 
			}   
			$this->db->where('status!=', -1);  
			$this->db->order_by('rating', 'ASC'); 
			$sql = $this->db->get('pro_logs_promotion'); 

			$num_rows=$sql->num_rows();
			if ($num_rows>0) {
				$result_array = $sql->result_array(); 
			}  
			$result['num_rows'] = $num_rows;
			$result['result_array'] = $result_array;
			$result['row'] = $row;  
		   return $result;
	}
	public function FindByproMember($member_no=null){ 
		$row='';$result_array='';      
		if ($member_no!=''&&$member_no!=null) { 
			$this->db->where('member_no', $member_no); 
		}      
		$this->db->order_by('id', 'DESC'); 
		$sql = $this->db->get('pro_logs_promotion'); 

		$num_rows=$sql->num_rows();
		if ($num_rows>0) {
			$result_array = $sql->result_array(); 
			$row = $sql->row();
		}  
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;  
	   return $result;
}		 
}
?>