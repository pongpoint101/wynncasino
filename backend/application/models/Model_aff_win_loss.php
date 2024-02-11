
<?php
class Model_aff_win_loss extends CI_Model{
	
    function insertDeposit($data)
    {
        $res = array(
            'status' => 'error',
            'message' => '',
        );
        if($data){
            foreach ($data as $key => $row) {
                $group_af = $this->groupAfL($row['member_no']);
                $group_af1 = $group_af[0]['group_af_l1'];
                $group_af2 = $group_af[0]['group_af_l2'];
                $where = array('member_no' => $row['member_no'], 'created_date' => date('Y-m-d'));
                $this->db->select('member_no,deposit');
                $this->db->where($where);
                $chk_dup = $this->db->get('aff_win_loss');
                $result_array = $chk_dup->result_array();
                if ($chk_dup->num_rows() < 1) {
                    $this->db->set('member_no', $row['member_no']);
                    $this->db->set('group_af_l1', $group_af1);
                    $this->db->set('group_af_l2', $group_af2);
                    $this->db->set('deposit', $row['deposit']);
                    $this->db->set('created_date', $row['created_date']);
                    $this->db->set('updated_at', $row['updated_at']);
                    $query = $this->db->insert('aff_win_loss');
                    if($query){
                        $res = array(
                            'status' => 'success',
                            'message' => 'successful.',
                        );
                    }else{
                        $res = array(
                            'status' => 'error',
                            'message' => 'save failed.',
                        );
                    }
                }else{
                    if($result_array[0]['deposit'] == null || $result_array[0]['deposit'] == 0){
                        $this->db->set('deposit', $row['deposit']);
                        $this->db->where('member_no', $row['member_no']);
                        $this->db->update('aff_win_loss');
                        $res = array(
                            'status' => 'success',
                            'message' => 'update successful. 1',
                        );
                    }
                }
                
            }

        }else{
            $res = array(
                'status' => 'error',
                'message' => 'data not empty.',
            );
        }
        return json_encode($res);
    }
    function insertPromotion($data)
    {
        $res = array(
            'status' => 'error',
            'message' => '',
        );
        if($data){
            foreach ($data as $key => $row) {
                $group_af = $this->groupAfL($row['member_no']);
                $group_af1 = $group_af[0]['group_af_l1'];
                $group_af2 = $group_af[0]['group_af_l2'];
                $where = array('member_no' => $row['member_no'], 'created_date' => date('Y-m-d'));
                $this->db->select('member_no,promotion');
                $this->db->where($where);
                $chk_dup = $this->db->get('aff_win_loss');
                $result_array = $chk_dup->result_array();
                if ($chk_dup->num_rows() < 1) {
                    $this->db->set('member_no', $row['member_no']);
                    $this->db->set('group_af_l1', $group_af1);
                    $this->db->set('group_af_l2', $group_af2);
                    $this->db->set('promotion', $row['promotion']);
                    $this->db->set('created_date', $row['created_date']);
                    $this->db->set('updated_at', $row['updated_at']);
                    $query = $this->db->insert('aff_win_loss');
                    if($query){
                        $res = array(
                            'status' => 'success',
                            'message' => 'successful.',
                        );
                    }else{
                        $res = array(
                            'status' => 'error',
                            'message' => 'save failed.',
                        );
                    }
                }else{
                    if($result_array[0]['promotion']==null || $result_array[0]['promotion'] == 0){
                        $this->db->set('promotion', $row['promotion']);
                        $this->db->where('member_no', $row['member_no']);
                        $this->db->update('aff_win_loss');
                        $res = array(
                            'status' => 'success',
                            'message' => 'update successful.2',
                        );
                    }
                }
                
            }

        }else{
            $res = array(
                'status' => 'error',
                'message' => 'data not empty.',
            );
        }
        return json_encode($res);
    }
    function insertWithdraw($data)
    {
        $res = array(
            'status' => 'error',
            'message' => '',
        );
        if($data){
            foreach ($data as $key => $row) {
                $group_af = $this->groupAfL($row['member_no']);
                $group_af1 = $group_af[0]['group_af_l1'];
                $group_af2 = $group_af[0]['group_af_l2'];
                $where = array('member_no' => $row['member_no'], 'created_date' => date('Y-m-d'));
                $this->db->select('member_no,withdraw');
                $this->db->where($where);
                $chk_dup = $this->db->get('aff_win_loss');
                $result_array = $chk_dup->result_array();
                if ($chk_dup->num_rows() < 1) {
                    $this->db->set('member_no', $row['member_no']);
                    $this->db->set('group_af_l1', $group_af1);
                    $this->db->set('group_af_l2', $group_af2);
                    $this->db->set('withdraw', $row['withdraw']);
                    $this->db->set('created_date', $row['created_date']);
                    $this->db->set('updated_at', $row['updated_at']);
                    $query = $this->db->insert('aff_win_loss');
                    if($query){
                        $res = array(
                            'status' => 'success',
                            'message' => 'successful.',
                        );
                    }else{
                        $res = array(
                            'status' => 'error',
                            'message' => 'save failed.',
                        );
                    }
                }else{
                    if($result_array[0]['withdraw']==null || $result_array[0]['withdraw'] == 0){
                        $this->db->set('withdraw', $row['withdraw']);
                        $this->db->where('member_no', $row['member_no']);
                        $this->db->update('aff_win_loss');
                        $res = array(
                            'status' => 'success',
                            'message' => 'update successful.3',
                        );
                    }
                }
                
            }

        }else{
            $res = array(
                'status' => 'error',
                'message' => 'data not empty.',
            );
        }
        return json_encode($res);
    }
    function groupAfL($member_no){
        $this->db->select('group_af_l1,group_af_l2');
        $this->db->where('id',$member_no);
        $query = $this->db->get('members');
        $result_array = $query->result_array();
        
        return $result_array;
    }
    function getValue(){
        $this->db->select('COUNT(member_no) AS num_rows', FALSE);
        $num_rows = $this->db->get('aff_win_loss')->row()->num_rows;
        if ($num_rows > 0) {
            $this->db->select('member_no, SUM(deposit) AS deposit, SUM(withdraw) AS withdraw, SUM(promotion) AS promotion', FALSE);
            $this->db->group_by("member_no");
            $sql = $this->db->get('aff_win_loss');
            $result_array = $sql->result_array();
            $row = $sql->row();
        }
        $result['num_rows'] = $num_rows;
        $result['result_array'] = $result_array;
        $result['row'] = $row;
        return $result;
    }
    public function GetWinlossLevel($member_no,$date_before,$level,$date_start,$date_end){
         $data['data']=null;
         $data['total']=null;
        if($member_no){
             if($level==1){
                $sql1 = "SELECT members.id
                FROM view_list_af_l1
                LEFT JOIN members
                ON view_list_af_l1.username=members.username
                WHERE view_list_af_l1.group_af_l1 = ?
                ORDER BY view_list_af_l1.username";   
             }else{
                $sql1 = "SELECT members.id
                FROM view_list_af_l2
                LEFT JOIN members
                ON view_list_af_l2.username=members.username
                WHERE view_list_af_l2.group_af_l2 = ?
                ORDER BY view_list_af_l2.username"; 
             }
            $query1 = $this->db->query($sql1, [$member_no])->result();
            $arr_member_no=array();
            foreach($query1 as $row){ 
                array_push($arr_member_no,$row->id);
            }
            $implode_member_no = implode(',', $arr_member_no);
            if($implode_member_no){ 
                $sql = "SELECT  member_no,SUM(deposit) as deposit,SUM(withdraw) as withdraw,SUM(promotion) as promotion, created_date
                        FROM aff_win_loss
                        WHERE member_no  IN ($implode_member_no)
                        AND created_date >= ? AND created_date<= ?
                        GROUP BY created_date
                        ORDER BY created_date
                        "; 
                $query_win_loss = $this->db->query($sql, [$date_start,$date_end])->result(); 
                $deposit_tot=0;
                $withdraw_tot=0;
                $winloss_tot=0;
                $promotion_tot=0;
                $total_tot=0;
                $data['data']=null;
                $data['total']=null;
                foreach($query_win_loss as $row){
                    $deposit = !empty($row->deposit) ? $row->deposit : (float)0.00;
                    $withdraw = !empty($row->withdraw) ? $row->withdraw : (float)0.00;
                    $promotion = !empty($row->promotion) ? $row->promotion : (float)0.00;
                    $winloss = $deposit-$withdraw;
                    $total = $winloss-$promotion;
    
                    $deposit_tot= $deposit;
                    $withdraw_tot= $withdraw;
                    $winloss_tot= $winloss;
                    $promotion_tot= $promotion;
                    $total_tot= $total;
                    $data['data'][]=array(
                        "member_no" => $row->member_no,
                        "date" =>date("d-m-Y", strtotime($row->created_date)),
                        "deposit" => $deposit_tot,
                        "withdraw" => $withdraw_tot,
                        "winloss" => $winloss_tot,
                        "promotion" => $promotion_tot,
                        "total" => $total_tot,
                    );
                    
                }
                if($data['data']){
                    $deposit_t=0;
                    $withdraw_t=0;
                    $winloss_t=0;
                    $promotion_t=0;
                    $total_t=0;
                    foreach($data['data'] as $row){
                        $deposit_t+= !empty($row['deposit']) ? $row['deposit']:(float)0.00;
                        $withdraw_t+= !empty($row['withdraw']) ? $row['withdraw']:(float)0.00;
                        $winloss_t+= !empty($row['winloss']) ? $row['winloss']:(float)0.00;
                        $promotion_t+= !empty($row['promotion']) ? $row['promotion']:(float)0.00;
                        $total_t+= !empty($row['total']) ? $row['total']:(float)0.00;
                        $data['total']=array(
                            "deposit" => $deposit_t,
                            "withdraw" => $withdraw_t,
                            "winloss" => $winloss_t,
                            "promotion" => $promotion_t,
                            "total" => $total_t,
                        );
                    }
                }
            } 
          }else{
            $data['data']=null;
            $data['total']=null;
        } 
        return $data;
    } 
    public function GetLogWinloss($member_no){ 
        $data = 0;
        $sql = "SELECT * FROM log_aff_win_loss WHERE member_no=? ORDER BY id DESC LIMIT 1"; 
        $query = $this->db->query($sql, [$member_no])->result(); 
        if($query){
            if($query[0]->cal<0){
                $data =$query[0]->cal;
            }else{
                $data =0;
            }
        }
        return $data;
    }
}
?>