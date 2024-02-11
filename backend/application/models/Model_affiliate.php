<?php
class Model_affiliate extends MY_Model
{
	public function list_aff_bonut()
	{
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('log_claim_aff')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, member_no, member_username, amount, update_date');
			$sql = $this->db->get('log_claim_aff');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function list_aff_history($start, $length, $orderARR, $date_begin, $date_end, $searchName)
	{
		$date_begin_check = $this->Model_function->CheckFormatDate($date_begin);
		$date_end_check = $this->Model_function->CheckFormatDate($date_end);

		$WhereDateAf_l1 = "";
		$WhereDateAf_l2 = "";
		if (($date_begin_check == true) && ($date_end_check == true)) {
			$WhereDateAf_l1 = " WHERE l1.create_at > \"" . $date_begin . " 00:01\" AND l1.create_at < \"" . $date_end . " 23:59\" ";
			$WhereDateAf_l2 = " WHERE l2.create_at > \"" . $date_begin . "00:01\" AND l2.create_at < \"" . $date_end . " 23:59\" ";
		}

		$this->db->select('COUNT(listM.id) AS num_rows', FALSE);

		if ($searchName != "") {

			$this->db->like('listM.username', $searchName, 'both');
		}
		$this->db->where('afl1.count_af_l1 >', 0);
		$this->db->join('(SELECT l1.group_af_l1, COUNT(l1.group_af_l1) AS count_af_l1 FROM view_list_af_l1 AS l1 ' . $WhereDateAf_l1 . ' GROUP BY l1.group_af_l1) AS afl1', 'listM.id = afl1.group_af_l1', 'left');
		$num_rows = $this->db->get('members AS listM')->row()->num_rows;
		$result_array = "";
		$row = "";

		$columns = array(
			0 => 'listM.username',
			1 => 'afl1.count_af_l1',
			2 => 'afl2.count_af_l2'
		);

		$columnName = $columns[$orderARR['column']];


		if ($num_rows > 0) {
			$this->db->select('listM.username, afl1.count_af_l1, afl2.count_af_l2, listM.create_at');
			$this->db->join('(SELECT l1.group_af_l1, COUNT(l1.group_af_l1) AS count_af_l1 FROM view_list_af_l1 AS l1 ' . $WhereDateAf_l1 . ' GROUP BY l1.group_af_l1) AS afl1', 'listM.id = afl1.group_af_l1', 'left');
			$this->db->join('(SELECT l2.group_af_l2, COUNT(l2.group_af_l2) AS count_af_l2 FROM view_list_af_l2 AS l2 ' . $WhereDateAf_l2 . ' GROUP BY l2.group_af_l2) AS afl2', 'listM.id = afl2.group_af_l2', 'left');
			if ($searchName != "") {
				$this->db->like('listM.username', $searchName, 'both');
			}
			$this->db->where('afl1.count_af_l1 >', 0);
			$this->db->group_by("listM.username, afl1.count_af_l1, afl2.count_af_l2, listM.create_at");
			if (($start > -1) && ($length > 0)) {
				$this->db->limit($length, $start);
			}
			$this->db->order_by($columnName . " " . $orderARR['dir']);
			$sql = $this->db->get('members AS listM');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_aff_l1_by_member_no($member_no,$timestart='',$timeend='')
	{ 
		$result_array = "";
		$row = "";
		if($timestart!=''){
			$this->db->select('username,create_at');
			$this->db->where('group_af_l1 =', $member_no);
			$this->db->where('create_at >= ', $timestart);
			$this->db->where('create_at <= ', $timeend);
			$this->db->order_by('create_at', 'DESC');
			$sql = $this->db->get('view_list_af_l1');
		}else{
			$this->db->select('username,create_at');
			$this->db->where('group_af_l1 =', $member_no); 
			$this->db->order_by('create_at', 'DESC');
			$sql = $this->db->get('view_list_af_l1');
		}

		$result_array = $sql->result_array();  
		$num_rows=(is_array($result_array)?sizeof($result_array):0);
		if ($num_rows > 0) { 
			$row = $sql->row();
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function search_aff_l2_by_member_no($member_no,$timestart='',$timeend='')
	{ 
		$result_array = "";
		$row = ""; 
		if($timestart!=''){
			$this->db->select('username,create_at');
			$this->db->where('group_af_l2 =', $member_no);
			$this->db->where('create_at >= ', $timestart);
			$this->db->where('create_at <= ', $timeend);
			$this->db->order_by('create_at', 'DESC');
			$sql = $this->db->get('view_list_af_l2');
		}else{
			$this->db->select('username,create_at');
			$this->db->where('group_af_l2 =', $member_no); 
			$this->db->order_by('create_at', 'DESC');
			$sql = $this->db->get('view_list_af_l2');
		}

		$result_array = $sql->result_array();  
		$num_rows=(is_array($result_array)?sizeof($result_array):0);
		if ($num_rows > 0) { 
			$row = $sql->row();
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function list_aff_ranking()
	{
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('aff_branch')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id, aff_upline, COUNT(id) AS aff_count, aff_downline, total_turnover, comm_given, register_date, claim_date, last_refresh, 
							   update_date');
			$this->db->group_by("aff_upline");
			$sql = $this->db->get('aff_branch');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function list_m_site()
	{
		$this->db->select('COUNT(site_id) AS num_rows', FALSE);
		$num_rows = $this->db->get('m_site')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('*');
			$this->db->where('site_id =',SITE_ID);
			$sql = $this->db->get('m_site');
			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function UpdateSettingMSite($aff_comm_level_1, $aff_comm_level_2, $aff_rate1, $aff_rate2)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('aff_comm_level_1', $aff_comm_level_1);
		$this->db->set('aff_comm_level_2', $aff_comm_level_2);
		$this->db->set('aff_rate1', $aff_rate1);
		$this->db->set('aff_rate2', $aff_rate2);
		$this->db->where('site_id =',SITE_ID);
		$this->db->update('m_site');
	}

	public function UpdateSettingMSite_01($line_at_name, $title, $text_marquee, $line_at_url)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s");

		$this->db->set('line_at_name', $line_at_name);
		$this->db->set('line_at_url', $line_at_url);
		$this->db->set('title', $title);
		$this->db->set('text_marquee', $text_marquee);
		$this->db->where('site_id =',SITE_ID);
		$this->db->update('m_site');
	}

	public function UpdateSettingMSite_03($data)
	{
		$this->db->where('site_id =',SITE_ID);
		return $this->db->update('m_site', $data);
	}
	public function EditById($Data)
	{
		try {
			$this->db->where('site_id =', $Data->site_id);
			$edit = $this->db->update('m_site', $Data);
			if (!$edit) {
				$this->Data->error_code = 500;
				return false;
			}
		} catch (Exception $e) {
			$this->Data->error_code = 500;
			return false;
		}
		$this->Data->error_code = 200;
		return $this->Data;
	}


	public function UpdateSettingMSiteBygame($Data=NULL){  
		try { 
			
			$this->db->where('id', $Data->id);  
			unset($Data->id); 
			$edit =$this->db->update('aff_gametype', $Data);	 
			 
		 } catch (Exception $e) {
		  $this->Data->error_code=500;return false;
		}
	  $this->Data->error_code=200; 
	  return $this->Data;     
  }
  public function list_aff_manage()
	{
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows = $this->db->get('members')->row()->num_rows;
		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('id,username,fname,lname,group_af_l1,group_af_l2,group_af_username_l1,group_af_username_l2,update_at');
			$this->db->order_by('update_at', 'DESC');
			$sql = $this->db->get('members');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function member_updated_aff_member_branch($pg_ids, $pg_l1)
	{
		date_default_timezone_set('Asia/Bangkok');
		$DateTimeNow = date("Y-m-d H:i:s"); 
		if($pg_l1){
			$this->db->set('group_af_l1', $pg_l1);
		}else{
			$this->db->set('group_af_l1', null);
		}
		// if($pg_l2){
		// 	$this->db->set('group_af_l2', $pg_l2);
		// }else{
		// 	$this->db->set('group_af_l2', null);
		// }
		
		
		$this->db->set('update_at', $DateTimeNow);
		$this->db->where('member_no', $pg_ids);
		$this->db->update('aff_member_branch');
		return 200;
	}

}
