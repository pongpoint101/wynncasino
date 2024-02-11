<?php
class Model_sportbook_report extends CI_Model{

	public function search_sportbooksbymember($member_no,$trx_date,$trx_date_next,$status,$filte_list_e=[],$filte_list_in=[]){
		date_default_timezone_set('Asia/Bangkok');  
			$this->db->select("id, member_no,CONVERT_TZ(STR_TO_DATE(bet_time,'%Y-%m-%dT%H:%i'),'+00:00','+11:00') AS bet_time, 
			 CONVERT_TZ(STR_TO_DATE(end_time,'%Y-%m-%dT%H:%i'),'+00:00','+11:00') AS end_time, before_bet_amount, bet_amount, win_amount, status, update_date"); 
			if ($status!=null&&$status!='') { $this->db->where('status =', $status);} 
			if ($member_no!=null) { $this->db->where('member_no =', $member_no);} 
			$this->db->where("CONVERT_TZ(STR_TO_DATE(bet_time,'%Y-%m-%dT%H:%i'),'+00:00','+11:00') >=", $trx_date);
			$this->db->where("CONVERT_TZ(STR_TO_DATE(bet_time,'%Y-%m-%dT%H:%i'),'+00:00','+11:00') <=", $trx_date_next); 
			if (sizeof($filte_list_e)>0) {
				foreach($filte_list_e as $k=>$v) { 
					$this->db->where($v, "", false);
			   }
			}
			if (sizeof($filte_list_in)>0) {
				foreach($filte_list_in as $k=>$v) {  
					$this->db->where_in($k, $v); 
			   }
			}
			$this->db->order_by('end_time', 'DESC');
			$this->db->order_by('bet_time', 'DESC');
			$sql = $this->db->get('sbo_trx');    

			$result_array = $sql->result_array();
			$row = $sql->row();  
			$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
			$result['result_array'] = $result_array;
			$result['row'] = $row;
		return $result; 
	}
 
}
?>