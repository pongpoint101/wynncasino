
<?php
class Model_rbank extends MY_Model {   
   public function FindAll_truewallet($id=null){  
      $row='';$result_array=[];       
	  if($id!=null){
		$this->db->where('id', $id);    
	  }
      $this->db->where('isActive', 1);    
      $this->db->order_by('update_date', 'DESC');  
		$sql = $this->db->get('true_wallet'); 

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