
<?php
class Model_game_type extends MY_Model{   
	public function FindAll($search_id=null){ 
		    $row='';$result_array='';     
			$this->db->select('id,code,name_th,name_eng,status');  
			if ($search_id!=''&&$search_id!=null) { 
				$this->db->where('id', $search_id); 
			}   
			$this->db->where('status!=', -1);  
			$this->db->order_by('m_order', 'ASC'); 
			$sql = $this->db->get('game_type'); 

			$num_rows=$sql->num_rows();
			if ($num_rows>0) {
				$result_array = $sql->result_array(); 
			}  
			$result['num_rows'] = $num_rows;
			$result['result_array'] = $result_array;
			$result['row'] = $row;  
		   return $result;
	}	
	public function FindAll2($search_id=null){ 
		$row='';$result_array='';      
		if ($search_id!=''&&$search_id!=null) { 
			$this->db->where('id', $search_id); 
		}    
		$this->db->order_by('game_type', 'ASC'); 
		$sql = $this->db->get('comm_gametype'); 

		$num_rows=$sql->num_rows();
		if ($num_rows>0) {
			$result_array = $sql->result_array(); 
		}  
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;  
	   return $result;
} 

public function FindAll_aff_fix_byprovider($search_id=null){ 
	$row='';$result_array='';      
	if ($search_id!=''&&$search_id!=null) { 
		$this->db->where('id', $search_id); 
	}    
	$this->db->order_by('game_type', 'ASC'); 
	$sql = $this->db->get('aff_gametype'); 

	$num_rows=$sql->num_rows();
	if ($num_rows>0) {
		$result_array = $sql->result_array(); 
	}  
	$result['num_rows'] = $num_rows;
	$result['result_array'] = $result_array;
	$result['row'] = $row;  
   return $result;
} 

}
?>