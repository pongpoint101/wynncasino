<?php 
class Model_log_withdraw extends CI_Model
{
    public function get_withdraw($date)
    {
        $this->db->select('COUNT(member_no) AS num_rows', FALSE);
        $num_rows = $this->db->get('log_withdraw')->row()->num_rows;

        if ($num_rows > 0) {
            $this->db->select('member_no, SUM(amount_actual) AS total', FALSE);
            $this->db->where("trx_date = ",$date); 
            $this->db->group_by("member_no");
            $sql = $this->db->get('log_withdraw');
            $result_array = $sql->result_array();
            $row = $sql->row();
        }

        $result['num_rows'] = $num_rows;
        $result['result_array'] = $result_array;
        $result['row'] = $row;
        return $result;
    }
    public function update_log_withdraw_by_id($log_withdraw_id,$status,$remark_internal,$withdraw_by='',$remark_internal_detail=''){
		if($withdraw_by==''){ $withdraw_by=$this->session->userdata("Username"); } 
		$this->db->set('withdraw_by', $withdraw_by);
        $this->db->set('remark_internal', $remark_internal);
        if($remark_internal_detail!=''&&$remark_internal_detail!=null&&strlen(trim($remark_internal_detail))>0){
            $this->db->set('remark_internal_detail', $remark_internal_detail);
        } 
		$this->db->set('status', $status);
		$this->db->where('id', $log_withdraw_id);
		$this->db->update('log_withdraw');
	}

    public function get_withdraw_max($member_no,$withdraw_amount,$promo_ids)
    {
    $wd_actual_amount=$withdraw_amount;       
    if($promo_ids!=0){ 
      $proinfo = $this->Model_promotion->FindByGroupId($promo_ids);   
    if ($proinfo['num_rows'] > 0&&$promo_ids != 37) {
        if ($proinfo['row']->pro_withdraw_max_amount != -1) {
            if ($proinfo['row']->pro_withdraw_type== 2) {       
                $this->db->select('id, member_no, withdraw_amount,pro_withdraw_accept');
                $this->db->where('member_no', $member_no);
                $this->db->limit(1); 
                $this->db->order_by('id', 'DESC'); 
                $sql = $this->db->get('pro_logs_promotion');  
                $num_rows=$sql->num_rows();
                if ($num_rows>0) { 
                    $row = $sql->row(); 
                    $wd_actual_amount =($withdraw_amount>$row->pro_withdraw_accept?$row->pro_withdraw_accept:$withdraw_amount);
                }
              }else{ 
                $wd_actual_amount =($withdraw_amount>$proinfo['row']->pro_withdraw_max_amount) ? $proinfo['row']->pro_withdraw_max_amount : $withdraw_amount;
            } 
        }
        if ($proinfo['row']->pro_deposit_type == 3) { 
            $promember=$this->Model_logs_promotion->FindByproMember($member_no);
                if(@$promember['row']->pro_id_more > 0){
                $listmore= $this->Model_promotion->Getdetail_More($proinfo['row']->pro_id,$promember['row']->pro_id_more); 
                if(isset($listmore['row']->withdraw_max_amount)&&@$listmore['row']->withdraw_max_amount>0){ 
                       if ($proinfo['row']->pro_withdraw_type== 2) {       
                        $this->db->select('id, member_no, withdraw_amount,pro_withdraw_accept');
                        $this->db->where('member_no', $member_no);
                        $this->db->limit(1); 
                        $this->db->order_by('id', 'DESC'); 
                        $sql = $this->db->get('pro_logs_promotion');  
                        $num_rows=$sql->num_rows();
                        if ($num_rows>0) { 
                            $row = $sql->row();
                            $wd_actual_amount =($withdraw_amount>$row->pro_withdraw_accept?$row->pro_withdraw_accept:$withdraw_amount);
                        }   
                       }else{
                        $wd_actual_amount =($withdraw_amount>$listmore['row']->withdraw_max_amount) ? $listmore['row']->withdraw_max_amount : $withdraw_amount;
                    } 
                  }
                }  
             } 
          }   
      }
     return $wd_actual_amount;
    }

}
?>