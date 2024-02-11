
<?php
class Model_log_deposit extends MY_Model {
    
    public function log_deposit_vizpay($list_status=['WAIT_CONFIRM']){
        $result_array = [];
		$row = "";   
        $this->db->where_in('deposit_status', $list_status);  
		$sql = $this->db->get('log_deposit_vizpay'); 
		$result_array = $sql->result_array();
		//$row = $sql->row(); 
		$result['num_rows'] = (is_array($result_array)?sizeof($result_array):0);
		$result['result_array'] = $result_array;
		//$result['row'] = $row;
		return $result;
    }
    public function UpdateDepositKbiz($update_id,$Data){
        $this->db->where('id', $update_id); 
        $this->db->where('trx_status!=', 2);   
        $edit =$this->db->update('log_deposit_kbiz', $Data);   
        if (!$edit) {
            $this->Data->error_code=500;return false;
        }
        return $this->Data;
    }
    public function get_deposit($date)
    {
        $pro = array('ฟรี50','Free200','Free60','WB50','HNY50');
        $this->db->select('COUNT(member_no) AS num_rows', FALSE);
        $this->db->where("(channel=1
                       OR channel = 2
                       OR channel = 3
					   OR channel = 5)", NULL, FALSE);
        $this->db->where("status = 1");  
        $this->db->where_not_in('remark', $pro);
        $num_rows = $this->db->get('log_deposit')->row()->num_rows;

        if ($num_rows > 0) {
            $pro = array('ฟรี50','Free200','Free60','WB50','HNY50');

            $this->db->select('member_no, SUM(amount) AS total', FALSE);
            $this->db->where("(channel=1
                       OR channel = 2
                       OR channel = 3
					   OR channel = 5)", NULL, FALSE);
            $this->db->where("status = 1");           
            $this->db->where("trx_date = ",$date);    
            $this->db->where_not_in('remark', $pro);
            $this->db->group_by("member_no");
            $sql = $this->db->get('log_deposit');
            $result_array = $sql->result_array();
            $row = $sql->row();
        }

        $result['num_rows'] = $num_rows;
        $result['result_array'] = $result_array;
        $result['row'] = $row;
        return $result;
    }

    public function get_bonus($date)
    {
        $this->db->select('COUNT(member_no) AS num_rows', FALSE);
        $this->db->where("(channel !=1
                       OR channel != 2
                       OR channel != 3
					   OR channel != 5)", NULL, FALSE);
        $this->db->where("status = 1");  
        $num_rows = $this->db->get('log_deposit')->row()->num_rows;

        if ($num_rows > 0) {
            $this->db->select('member_no, SUM(amount) AS total', FALSE);
            $this->db->where("(channel !=1
                       AND channel != 2
                       AND channel != 3
					   AND channel != 5)", NULL, FALSE);
            $this->db->where("status = 1");                 
            $this->db->where("trx_date = ",$date);  
            $this->db->group_by("member_no");
            $sql = $this->db->get('log_deposit');
            $result_array = $sql->result_array();
            $row = $sql->row();
        }

        $result['num_rows'] = $num_rows;
        $result['result_array'] = $result_array;
        $result['row'] = $row;
        return $result;
    }
    
	public function load_log_deposit_truewallet($trx_id){

		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('w_tx_id =', $trx_id);
		$num_rows = $this->db->get('auto_truewallet')->row()->num_rows;
		$result_array = "";
		$row = "";
		if($num_rows > 0){
			$this->db->select('id,w_tx_id AS trx_id, member_no,update_date,w_transfer_date AS trx_date,w_transfer_time AS trx_time,w_amount AS trx_amount,w_truewallet_number AS from_bank,w_sender AS from_acc,w_status_msg AS full_msg');
			$this->db->where('w_tx_id =', $trx_id);
			$sql = $this->db->get('auto_truewallet'); 
			$row = $sql->row();
		} 
		$result['num_rows'] = $num_rows;
		$result['row'] = $row;
		return $result;
	} 

    public function insert_log_trx_deposit($data){ 
      date_default_timezone_set('Asia/Bangkok');
      $DateTimeNow = date("Y-m-d H:i:s");
      try { 
          $adminid=(isset($this->session->userID)?$this->encryption->decrypt($this->session->userID):null); 
          $data['admin_name']=($this->session->userdata("Username"))?$this->session->userdata("Username"):null;
          $data['admin_id']=$adminid;
          $data['update_date']=$DateTimeNow;
          $this->db->trans_start();
          $insert =$this->db->insert('log_deposit', $data);
          $this->db->trans_complete();
          if(!$insert){
         $this->Data->error_code=500;return false;
        }  
       } catch (Exception $e) {
        $this->Data->error_code=500;$this->Data->error_msg=$e->getMessage();return $this->Data;
      }
     $this->Data->error_code=200; 
     return $this->Data;  
    }
	public function UploadImage($file_old_name=''){ 
        require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
        $res=[];
        if(@$_FILES['slip']['size']<=0){$res['status']=200;$res['file_name']=null; return $res;} 
        try {
                $filename = $_FILES['slip']['name'];
                $imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
                $imageFileType = strtolower($imageFileType);  

                $file_new_name='slip_'.uniqid();
                $slip = $file_new_name.'.'.$imageFileType;
                $handle = new \Verot\Upload\Upload($_FILES['slip']);
                if ($handle->uploaded) {
                $handle->allowed = array('image/*'); 
                $handle->image_resize = true; 
                $handle->image_x =450; 
                $handle->image_ratio_y = true;
                $handle->forbidden = array('images/*'); 
                $handle->file_new_name_body   =$file_new_name;  
                $handle->process('./images/slip');
                if ($handle->processed) {
                     if($file_old_name!=''){@unlink('./images/slip/'.$file_old_name);} 
                     $handle->clean();
                     $res['status']=200;$res['file_name']=$slip; return $res;
                  } else {
                    $res['status']=500; return $res;
                 }
              } 
              $res['status']=500; return $res;
           } catch (\Throwable $th) {
            $res['status']=500; return $res;
       }   
  } 
  public function search_pro_list($member_no,$timestart='',$timeend='')
  {
      $this->db->select('COUNT(member_no) AS num_rows', FALSE);
      $this->db->where("(channel !=1
                     OR channel != 2
                     OR channel != 3
                     OR channel != 5)", NULL, FALSE);
      $this->db->where("status = 1");  
      if($timestart!='' &&$timeend !=''){
          $this->db->where('update_date >= ', $timestart);
          $this->db->where('update_date <= ', $timeend); 
      }
      $num_rows = $this->db->get('log_deposit')->row()->num_rows;

      if ($num_rows > 0) {
          $this->db->select('l_dps.member_no, l_dps.amount, l_dps.remark, l_dps.trx_id, l_dps.promo, l_dps.create_date, l_dps.trx_date,l_dps.channel, l_dps.status, l_dps.trx_time, l_dps.promo, pro.pro_name,pro.pro_id', FALSE);
          $this->db->where("(l_dps.channel !=1
                     AND l_dps.channel != 2
                     AND l_dps.channel != 3
                     AND l_dps.channel != 5)", NULL, FALSE);
                     
          $this->db->where("member_no = ",$member_no);  
          if($timestart!='' &&$timeend !=''){
              $this->db->where('l_dps.update_date >= ', $timestart);
              $this->db->where('l_dps.update_date <= ', $timeend); 
          }
          $this->db->join('pro_promotion_detail AS pro', 'l_dps.promo = pro.pro_id', 'left');;
          $sql = $this->db->get('log_deposit AS l_dps');
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