
<?php
class Model_commission extends MY_Model{    
	public function CommRange($search_id=-1){ 
		$row='';$result_array=''; 
		 if($search_id=='all'){
			$this->db->where('game_type!=', $search_id);
		   } else{
			$this->db->where('game_type', $search_id);
		}     
		$this->db->order_by('game_type', 'ASC'); 
		$this->db->order_by('comm_range', 'ASC'); 
		$sql = $this->db->get('comm_raterange'); 

		$num_rows=$sql->num_rows();
		if ($num_rows>0) {
			$result_array = $sql->result_array(); 
		}  
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;  
	   return $result;
   } 
   public function UpdateCommRange($data,$where_id){ 
	$this->db->where('id =', $where_id);
	$this->db->update('comm_raterange', $data);
  }
  public function UpdateCommGameFixType($data,$where_id){ 
	$this->db->where('id =', $where_id);
	$this->db->update('comm_gametype', $data);
  }
}
?>