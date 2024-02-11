
<?php
class Model_rlog_systeme extends MY_Model {  
   private $table='log_systeme';
   public function FindAll_waitalrt(){  
      $row='';$result_array=[];      
      $this->db->where('create_at >= (NOW() - interval 1 DAY)', "", false); 
      $this->db->where('alert_status', 0);  
      $this->db->where_in('log_type', ['aff-maxlimit','com-maxlimit','promo','withdraw']);
      $this->db->limit(20);
      $this->db->order_by('create_at', 'ASC');  
		$sql = $this->db->get($this->table); 

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