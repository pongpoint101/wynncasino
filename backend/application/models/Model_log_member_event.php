
<?php
class Model_log_member_event extends MY_Model{    
   public function SaveData_log($Data){
	try {
		$this->db->where('member_no', $Data['member_no']); 
		$this->db->where('event_code', $Data['event_code']); 
		$sql = $this->db->get('log_member_event');  
		$num_rows=$sql->num_rows(); 
		if ($num_rows>0) {
			$this->db->where('member_no', $Data['member_no']); 
			$this->db->where('event_code', $Data['event_code']);   
			$this->db->update('log_member_event', $Data);   
			$this->Data->error_code=500;return false;
	     	}else{
			$this->db->insert('log_member_event', $Data); 
		 } 
	  } catch (Exception $e) {
		$this->Data->error_code=500;return false;
	  }
	 $this->Data->error_code=200; 
    }
	public function FindAll($id=null){ 
		    
	}  
}
?>