
<?php
class Model_reward extends MY_Model{   
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
		$edit =$this->db->update('pro_reward', $Data);   
		if (!$edit) {
			$this->Data->error_code=500;return false;
		} 
	  } catch (Exception $e) {
		$this->Data->error_code=500;return false;
	}
	$this->Data->error_code=200; 
    }
	public function FindAll($id=null,$pro_id=null){ 
		    $row='';$result_array='';      
			if ($id!=''&&$id!=null) { 
				$this->db->where('id', $id); 
			}   
			if ($pro_id!=''&&$pro_id!=null) { 
				$this->db->where('pro_id', $pro_id); 
			} 
			
			$this->db->where('status!=', -1);  
			$this->db->order_by('rating', 'ASC'); 
			$sql = $this->db->get('pro_reward'); 

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
	public function FindBy_Reward_Group($pro_group_id){
		$result_array = "";
		$row = "";   
		$this->db->from('pro_promotion_detail');
		if ($pro_group_id!=''&&$pro_group_id!=null) { 
			$this->db->where('pro_reward.id =', $pro_group_id); 
		}
      	$this->db->join('pro_reward', 'pro_promotion_detail.pro_id = pro_reward.pro_id'); 
		$sql = $this->db->get();
		$num_rows=$sql->num_rows();  
	    if($num_rows>0){
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