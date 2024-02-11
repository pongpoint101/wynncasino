
<?php
class Model_promotion extends MY_Model{  
	
	protected $Rangepro_list=[
		'1'=>['id'=>1,'deposit_start_amount'=>100,'deposit_end_amount'=>499,'turnover_amount'=>25,'bonus_max_amount'=>150,'withdraw_max_amount'=>100,'pro_bonus_amount'=>1,'action'=>'new'],
		'2'=>['id'=>2,'deposit_start_amount'=>500,'deposit_end_amount'=>999,'turnover_amount'=>25,'bonus_max_amount'=>750,'withdraw_max_amount'=>100,'pro_bonus_amount'=>1,'action'=>'new'],
		'3'=>['id'=>3,'deposit_start_amount'=>1000,'deposit_end_amount'=>99999,'turnover_amount'=>25,'bonus_max_amount'=>1500,'withdraw_max_amount'=>100,'pro_bonus_amount'=>1,'action'=>'new'],
	    '4'=>['id'=>4,'deposit_start_amount'=>1500,'deposit_end_amount'=>9999999,'turnover_amount'=>25,'bonus_max_amount'=>1500,'withdraw_max_amount'=>100,'pro_bonus_amount'=>1,'action'=>'new'],
	];
	protected $topuptoday_pro_list=[
		'1'=>['id'=>1,'deposit_start_amount'=>100,'deposit_end_amount'=>101,'turnover_amount'=>25,'bonus_max_amount'=>50,'withdraw_max_amount'=>100,'pro_bonus_amount'=>50,'action'=>'new'],
		'2'=>['id'=>2,'deposit_start_amount'=>300,'deposit_end_amount'=>301,'turnover_amount'=>25,'bonus_max_amount'=>150,'withdraw_max_amount'=>100,'pro_bonus_amount'=>150,'action'=>'new'],
		'3'=>['id'=>3,'deposit_start_amount'=>500,'deposit_end_amount'=>501,'turnover_amount'=>25,'bonus_max_amount'=>350,'withdraw_max_amount'=>100,'pro_bonus_amount'=>350,'action'=>'new'],
	    '4'=>['id'=>4,'deposit_start_amount'=>1000,'deposit_end_amount'=>1001,'turnover_amount'=>25,'bonus_max_amount'=>500,'withdraw_max_amount'=>100,'pro_bonus_amount'=>500,'action'=>'new'],
		'5'=>['id'=>5,'deposit_start_amount'=>2000,'deposit_end_amount'=>2001,'turnover_amount'=>25,'bonus_max_amount'=>1000,'withdraw_max_amount'=>100,'pro_bonus_amount'=>1000,'action'=>'new'],
	];
	public function AddCreditManual($member_no,$member_username,$amount,$remark,$remarkOption,$p_data_deposit=[],$promo_id='',$channel='',$member_last_deposit='',$file_name=null){
        try {
			$this->Data->error_code=200;
			$trx_id=uniqid(); 
			$before_balance=0;
			$openration_type=$remarkOption;//var_dump($remarkOption);exit();
			$remark_internal=$remark; 
	      switch ($remarkOption) {  
	         case 21: 	            
	            $this->Model_promo->insert_PromoWb100($member_no,$amount);
	          break;
	        case 22: 	
	            $this->Model_promo->insert_PromoMC25($member_no,$amount);
	        break;
	        case 20: // Happy new year  
				 if (isset($p_data_deposit['row']->amount)) {
					$deposit_id=$p_data_deposit['row']->id;
					$deposit_amount=$p_data_deposit['row']->amount; 
					$this->Model_promo->insert_PromoHNY50($member_no,$amount,$deposit_amount); 
					$this->Model_db_log->update_log_deposit_promo($promo_id,$deposit_id);
				 } 
	            break; 
			 case 23: // รางวัลประจำเดือน  
			 case 24: 	
				  $this->Model_promo->insert_reward_monthly($member_no,$channel,$remark,$amount);
				break;		
	        default: break;
	      }    
	     $RsMemberPro02 = $this->Model_member->SearchMemberPromoBymember_no($member_no);
         if($RsMemberPro02['num_rows'] > 0){
    		$this->Model_member->Update_member_promo($member_no,$promo_id,1,$amount,$remark);
    	 } else {
    		$this->Model_member->insert_member_promo($member_no,0,1,$amount,$remark);
     	} 
		   
	    $trx_date = date("Y-m-d");
		$trx_time = date('H:i:s');

		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no); 
		$balance_before=$RSM_Wallet['row']->main_wallet;
		if(!in_array($promo_id,[115,116,117])){
			$this->Model_promo->Update_member_promo_last($member_no,($member_last_deposit=='1')?0:$promo_id,$member_last_deposit);
		} 
	   	$this->Model_db_log->insert_log_deposit($member_no, $amount, $channel, $trx_id, 1, $remark,$remark_internal,$trx_date,$trx_time,($member_last_deposit=='1')?-1:$promo_id,$openration_type,$balance_before,$file_name);
	   	$this->Model_db_log->insert_log_add_credit($member_no,$member_username,$amount,$remark,$remark_internal,$openration_type,$trx_date,$trx_time);

		$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no); 
	   	if($RSM_Wallet['num_rows'] > 0){
	   		if (is_numeric($RSM_Wallet['row']->main_wallet)) {
		        $amount += $RSM_Wallet['row']->main_wallet; 
				$before_balance=$RSM_Wallet['row']->main_wallet; 
		    }
	   	} 

	    if ($RS1stDepo['num_rows'] <= 0) { 
	        $this->Model_db_log->insert_1st_deposit($member_no,$amount,"Credited by Admin");
	    }
	    $this->Model_member->update_member_wallet($member_no,$amount); 
	    $this->Model_member->insert_adjust_wallet_history($member_no,$amount,$before_balance,$remark,$trx_id);

	    $log_info = "Added : " . $amount . " (" . trim($remark.':'.$remark_internal) . ")";
	    $this->Model_db_log->insert_log_agent($member_username,'AddCredit',$amount,$log_info);
	    $this->Model_member->update_member_turnover($member_no); 
		} catch (Exception $e) {
			 $this->Data->error_code=500;
	   } 
	   return $this->Data;
	}

	public function SaveData($data){
		 try { 
			$dupicate=$this->Model_promotion->LoadProById($data->pro_id);
			if($dupicate['num_rows']>0){
				$this->EditById($data->pro_id, $data); 
			    }else{
				$this->Insert($data); 
			  } 
			  if ($this->Data->error_code!=200) {
				throw new Exception('save errro.'); 
			  }  
		   } catch (Exception $e) {
			 $this->Data->error_code=500;
		  } 
		return $this->Data;
	}
	public function Insert($data){
		try {
			 if($data->pro_start==null){$data->pro_start=null;}
			 if($data->pro_end==null){$data->pro_end=null;}
			 $insert =$this->db->insert('pro_promotion_detail', $data);
	  	     if(!$insert){
			  $this->Data->error_code=500;return false;
	 	    } 
	       $this->AfterSaveData();
		  } catch (Exception $e) {
			 $this->Data->error_code=500;return false;
		 }
		 $this->Data->error_code=200; 
	}
	public function EditById($Pro_Code,$Data){
		    try {
				$this->db->where('pro_id', $Pro_Code);  
				$edit =$this->db->update('pro_promotion_detail', $Data);   
				if (!$edit) {
					$this->Data->error_code=500;return false;
				}
				$this->AfterEdit();
			  } catch (Exception $e) {
				$this->Data->error_code=500;return false;
			}
		    $this->Data->error_code=200; 
			return $this->Data;
	  }
  public function EditMesterById($Pro_Code,$Data){
		try {
			$this->db->where('pro_id', $Pro_Code);  
			$edit =$this->db->update('pro_promotion', $Data);   
			if (!$edit) {
				$this->Data->error_code=500;return false;
			}
			$this->AfterEdit();
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
		return $this->Data;
  }
	public function DeleteById($Pro_Code){
		     try {
				$this->db->where('pro_id', $Pro_Code); 
				$delete=$this->db->delete('pro_promotion_detail');
				if (!$delete) {
					return $delete;
				}
			 } catch (Exception $e) {
				 return ['status_code'=>500];
			}
			return ['status_code'=>200];
	}
	public function FindById($Pro_Code){
		$result_array = "";
		$row = "";  
		$this->db->where('pro_id =', $Pro_Code); 
		$sql = $this->db->get('pro_promotion_detail');
		$num_rows=$sql->num_rows(); 
		if($num_rows<=0){
			$this->db->where('pro_id =', $Pro_Code); 
			$sql = $this->db->get('pro_promotion');
			$num_rows=$sql->num_rows(); 	 
		} 
	    if($num_rows>0){
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function FindByGroupId($pro_group_id){
		$result_array = "";
		$row = "";  
		$this->db->where('pro_group_id =', $pro_group_id); 
		$sql = $this->db->get('pro_promotion_detail');
		$num_rows=$sql->num_rows(); 
		if($num_rows<=0){
			$this->db->where('pro_group_id =', $pro_group_id); 
			$sql = $this->db->get('pro_promotion');
			$num_rows=$sql->num_rows(); 	 
		} 
	    if($num_rows>0){
			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	} 	
	public function FindByGroupIdAll($pro_bonus_in_website=''){
		$result_array = "";
		$row = "";   
		if($pro_bonus_in_website!=''){$this->db->where('pro_bonus_in_website =', $pro_bonus_in_website); } 
		$this->db->order_by('m_order', 'ASC');
		$sql = $this->db->get('pro_promotion_detail'); 
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
	public function LoadProById($Pro_Code){
		$result_array = "";
		$row = "";  
		$this->db->where('pro_id =', $Pro_Code);  
		$sql = $this->db->get('pro_promotion_detail');
		$num_rows=$sql->num_rows();  
	    if($num_rows>0){
			$row = $sql->row();
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function Getdetail_More($pro_id=null,$pro_id_more=null){
		$result_array = "";
		$row = "";  
		$this->db->where('pro_id =', $pro_id); 
		if($pro_id_more!=null){
			$this->db->where('id =', $pro_id_more);
		}
		$sql = $this->db->get('pro_promotion_detail_more');
		$num_rows=$sql->num_rows(); 
		if($num_rows<=0){
			$this->db->where('pro_id =', $pro_id); 
			$this->db->order_by('deposit_start_amount', 'asc');
			$sql = $this->db->get('pro_promotion_detail_more'); 
			$num_rows=$sql->num_rows(); 	 
		} 
	    if($num_rows>0){
			$result_array = $sql->result_array();
			$row = $sql->row();
		}else{
			$result_array=$this->Rangepro_list;
			$row =(object) $result_array['1'];
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function InsertById_Detail_More($data){
		try { 
			$insert =$this->db->insert('pro_promotion_detail_more', $data);
			  if(!$insert){
			 $this->Data->error_code=500;return false;
			} 
		  $this->AfterSaveData();
		 } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
  }
	public function EditById_Detail_More($Pro_Code,$Data){
		try {
			$this->db->where('id', $Pro_Code);  
			$edit =$this->db->update('pro_promotion_detail_more', $Data);  
			if (!$edit) {
				$this->Data->error_code=500;return false;
			}
			$this->AfterEdit();
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
		return $this->Data;
  }
	public function GetCategoryGroup($pro_cat_id=null){
		$result_array = "";
		$row = "";   
		$this->db->select('pro_id,channel,pro_symbol,pro_name,pro_name_short,pro_type,pro_cat_id,pro_cat_name'); 
		$this->db->group_by('channel'); 
		$this->db->order_by('m_order', 'asc');
		if ($pro_cat_id!=''&&$pro_cat_id!=null) { 
			$this->db->where('channel', $pro_cat_id); 
		}
		$this->db->where('pro_status!=', -1); 
		$sql = $this->db->get('pro_promotion');
		$num_rows=$sql->num_rows();  
	    if ($num_rows>0) {
				$result_array = $sql->result_array(); 
	    } 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function ListMasterData($search_category=null){ 
		    $row='';$result_array='';     
			$this->db->select('pro_id,pro_symbol,pro_turnover_type,pro_withdraw_max_amount,pro_bonus_max,pro_turnover_amount,channel,pro_group_id,pro_group_name,pro_is_group,pro_name,pro_name_short,pro_description,pro_description_short,pro_cat_name,pro_img1,pro_img2,pro_bonus_in_website,pro_status,m_order');  
			if ($search_category!=''&&$search_category!=null) { 
				$this->db->where('channel', $search_category); 
			}   
			$this->db->where('pro_status!=', -1);  
			$this->db->order_by('m_order', 'ASC'); 
			$sql = $this->db->get('pro_promotion'); 
			
			$num_rows=$sql->num_rows();
			if ($num_rows>0) {
				$result_array = $sql->result_array(); 
			}  
			$result['num_rows'] = $num_rows;
			$result['result_array'] = $result_array;
			$result['row'] = $row;  
		   return $result;
	}	
	public function ListDataBySite($search_category=null){ 
		$row='';$result_array='';     
		$this->db->select('pro_id,pro_symbol,pro_turnover_type,pro_withdraw_max_amount,pro_bonus_max,pro_turnover_amount,channel,pro_group_id,pro_group_name,pro_is_group,pro_name,pro_name_short,pro_description,pro_description_short,pro_cat_name,pro_img1,pro_img2,pro_bonus_in_website,pro_status,m_order');  
		if ($search_category!=''&&$search_category!=null) { 
			$this->db->where('channel', $search_category); 
		}    
		$this->db->where('pro_status!=', -1);  
		$this->db->order_by('m_order', 'ASC'); 
		$sql = $this->db->get('pro_promotion_detail');  
		$num_rows=$sql->num_rows();
		if ($num_rows>0) {
			$result_array = $sql->result_array(); 
		}  
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;  
	   return $result;
   }	
   public function SaveLogsPro($data){
	try { 
		   $insert =$this->db->insert('pro_logs_promotion', $data);
		   if(!$insert){
		   $this->Data->error_code=500;return false;
		}  
	   } catch (Exception $e) {
		$this->Data->error_code=500;return false;
	}
	$this->Data->error_code=200; 
 }
	// หลังจากบันทึกข้อมูลให้ทำอะไร
	public function AfterSaveData(){ }	
 	// หลังจากบันทึกข้อมูลให้ทำอะไร
   public function AfterEdit(){
	  $this->AfterSaveData();	    
   }	
 	// หลังจากลบข้อมูลให้ทำอะไร
    public function AfterDelete(){ }
	public function pro_list(){
		$result_array = "";
		$row = "";  
		$this->db->select('pro_id,pro_name'); 
		$sql = $this->db->get('pro_promotion_detail');
		$num_rows=$sql->num_rows(); 
		if($num_rows<=0){
			$this->db->select('pro_id,pro_name'); 
			$sql = $this->db->get('pro_promotion_detail');
			$num_rows=$sql->num_rows(); 	 
		} 
	    if($num_rows>0){
			$row = $sql->result();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function Getdetail_TopupToday($pro_id=null,$pro_id_more=null){
		$result_array = "";
		$row = "";  
		$this->db->where('pro_id =', $pro_id); 
		if($pro_id_more!=null){
			$this->db->where('id =', $pro_id_more);
		}
		$sql = $this->db->get('pro_promotion_topup_today');
		$num_rows=$sql->num_rows(); 
		if($num_rows<=0){
			$this->db->where('pro_id =', $pro_id); 
			$this->db->order_by('morder', 'asc');
			$sql = $this->db->get('pro_promotion_topup_today'); 
			$num_rows=$sql->num_rows(); 	 
		} 
	    if($num_rows>0){
			$result_array = $sql->result_array();
			$row = $sql->row();
		}else{
			$result_array=$this->topuptoday_pro_list;
			$row =(object) $result_array['1'];
		} 
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function InsertById_Detail_TopupToday($data){
		try { 
			$insert =$this->db->insert('pro_promotion_topup_today', $data);
			  if(!$insert){
			 $this->Data->error_code=500;return false;
			} 
		  $this->AfterSaveData();
		 } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
  }
	public function EditById_Detail_TopupToday($Pro_Code,$Data){
		try {
			$this->db->where('id', $Pro_Code);  
			$edit =$this->db->update('pro_promotion_topup_today', $Data);  
			if (!$edit) {
				$this->Data->error_code=500;return false;
			}
			$this->AfterEdit();
		  } catch (Exception $e) {
			$this->Data->error_code=500;return false;
		}
		$this->Data->error_code=200; 
		return $this->Data;
  }
}
?>