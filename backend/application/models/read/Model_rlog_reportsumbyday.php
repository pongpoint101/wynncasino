
<?php
class Model_rlog_reportsumbyday extends MY_Model {  
   private $table='report_sumbyday';
   public function __construct() { 
	date_default_timezone_set('Asia/Bangkok'); 
	}  
	public function FindAll_Register($member_no='',$source_ref='',$timestart='',$timeend=''){  
           $rows_member=$this->GetMember_Register($member_no,$source_ref,$timestart,$timeend);
            $list_all=[]; 
		    foreach ($rows_member['result_array'] as $k => $v) { 
				    if(trim($member_no)!=''){// มี aff id หาตามวันฝาก  
						$row_sumday=$this->Find_report_sumbyday($v['id'],$timestart,($timeend=='')?date("Y-m-d"):$timeend,$member_no);
					 }else{ // ไม่มี aff id  หาตามวันสมัคร 
						$row_sumday=$this->Find_report_sumbyday($v['id'],$timestart,($timeend=='')?date("Y-m-d"):$timeend,$member_no);	
					 }
					 $deposit=0;$withdraw=0;//var_dump($row_sumday['row']);
					 if(isset($row_sumday['row']->deposit)){
						$deposit=$row_sumday['row']->deposit;
						$withdraw=$row_sumday['row']->withdraw;
					 } 
					 @$v['deposit']=$deposit;
					 @$v['withdraw']=$withdraw;
					 $list_all[]=$v;

		    } 
        return $list_all;
	}
   public function Find_report_sumbyday($member_no='',$timestart='',$timeend='',$member_no_id){  
       $row='';$result_array=[];$num_rows=0;        
	    //     if(trim($member_no_id)!=''){// มี aff id หาตามวันฝาก  
				  
	    //     }else{ // ไม่มี aff id  หาตามวันสมัคร 

	    //   }
		$this->db->select('member_no,turnover,deposit_all,money_remove,deposit_pro,status,create_day,create_at');
		$this->db->select('SUM(deposit) AS deposit', FALSE);
		$this->db->select('SUM(withdraw) AS withdraw', FALSE);
		$this->db->where('member_no=', $member_no);  
		if(trim($timestart)!=''&&trim($timeend)!=''){ 
		  $this->db->where('create_day >=', date('Y-m-d',strtotime($timestart)));
		  $this->db->where('create_day <=', date('Y-m-d',strtotime($timeend)));
		}else{
		  $this->db->where('create_day =SUBDATE(CURDATE(),0)', "", false); 
		}  
		$this->db->order_by('create_day', 'ASC');
		$sql = $this->db->get($this->table); 
		$num_rows=$sql->num_rows();
		if ($num_rows>0) {
		  $result_array = $sql->result_array(); 
		  $row = $sql->row();
		 } 
		 //if($member_no==1000667){print_r($this->db->last_query());exit();}
		 //print_r($this->db->last_query());exit();
		  // วันปัจจุบัน  
		  if(date('Y-m-d',strtotime($timestart))==date("Y-m-d")||date('Y-m-d',strtotime($timeend))==date("Y-m-d")){
		 $query = $this->db->query("SELECT 
			dps.member_no  
			,COALESCE(SUM(CASE WHEN  channel IN (1,2,3,5) AND promo =-1 THEN dps.amount ELSE 0 END),0) deposit
			,COALESCE(SUM(CASE WHEN  channel NOT IN (1,2,3,5) AND promo !=-1  THEN dps.amount ELSE 0 END),0) deposit_pro
			,COALESCE((SELECT SUM(amount_actual) AS amount_actual FROM  log_withdraw  WHERE  trx_date = dps.trx_date  AND STATUS = 1 AND member_no = dps.member_no),0) AS withdraw
			,0 as turnover
		,dps.trx_date
		FROM  log_deposit AS dps
		WHERE dps.member_no=? AND dps.member_no IS NOT NULL AND dps.trx_date=? AND dps.status=1 
		GROUP BY  dps.member_no
		ORDER BY member_no,deposit DESC", [$member_no,date('Y-m-d')]);//,dps.trx_date
		$num_rows2=$query->num_rows();//if($member_no==1000667){print_r($this->db->last_query());exit();}
		if($num_rows2 > 0){
			// $result_array2 =$query->result_array();
			$row2 = $query->row();   
		}  
	    }
		if(isset($row->deposit)&&isset($row2->deposit)){
			$row->deposit=$row->deposit+$row2->deposit;
			$row->withdraw=$row->withdraw+$row2->withdraw; 
		 }else if(isset($row2->deposit)){
			$row=$row2;
		}
	//if($member_no==1000723) {var_dump($row);exit();}  

	   /*
	   if(date('Y-m-d',strtotime($timeend))==date("Y-m-d")){
		$query = $this->db->query("SELECT 
		dps.member_no  
		,COALESCE(SUM(CASE WHEN  channel IN (1,2,3,5) AND promo =-1 THEN dps.amount ELSE 0 END),0) deposit
		  ,COALESCE(SUM(CASE WHEN  channel NOT IN (1,2,3,5) AND promo !=-1  THEN dps.amount ELSE 0 END),0) deposit_pro
		,COALESCE((SELECT SUM(amount_actual) AS amount_actual FROM  log_withdraw  WHERE  trx_date = dps.trx_date  AND STATUS = 1 AND member_no = dps.member_no),0) AS withdraw
	    ,0 as turnover
	   ,dps.trx_date
	   FROM  log_deposit AS dps
	   WHERE dps.member_no=? AND dps.member_no IS NOT NULL AND dps.trx_date BETWEEN ? AND ? AND dps.status=1 
	   GROUP BY  dps.member_no,dps.trx_date
	   ORDER BY member_no,deposit DESC", [$member_no,date('Y-m-d',strtotime($timestart)),date('Y-m-d',strtotime($timeend))]);
		$num_rows=$query->num_rows();//print_r($this->db->last_query());exit();
		if($num_rows > 0){
			$result_array =$query->result_array();
			$row = $query->row();
		}
	   }else{
		   if(trim($member_no)!=''){
			$this->db->where('member_no=', $member_no); 
		    }
		  if(trim($timestart)!=''&&trim($timeend)!=''){ 
			$this->db->where('create_day >=', $timestart);
			$this->db->where('create_day <=', $timeend);
		  }else{
			$this->db->where('create_day =SUBDATE(CURDATE(),1)', "", false); 
		  }  
		  $this->db->order_by('create_day', 'ASC');
		  $sql = $this->db->get($this->table); 
		  $num_rows=$sql->num_rows();
		  if ($num_rows>0) {
			$result_array = $sql->result_array(); 
			$row = $sql->row();
		   } 
	      } 
		  */
		   // print_r($this->db->last_query());
		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;  
	   return $result;
     } 
	 public function GetMember_Register($member_no='',$source_ref='',$timestart='',$timeend=''){
		$result_array = "";
		$row = "";
		$this->db->select('mber.id, mber.username, mber.fname, mber.lname, mber.telephone,mber.truewallet_account, mber.bank_code, mber.bank_name, 
							   mber.bank_accountnumber, mber.update_at, mber.create_at, mber.status,source_ref,group_af_l1,group_af_l2,group_af_username_l1,create_at, 
							   mber.setting_bank_id, mber.line_id, mber.af_code, mber.ip, mber.member_last_deposit, mber.scb_scb, mber.kbank_kbank, mber.aff_type
							   ,mber.ignore_zero_turnover, mber.aff_percent_l1, mber.aff_percent_l2, mber.aff_percent_use_default, mber.type_member,mber.com_use_default,mber.cashback_rate');
		if(trim($member_no)!=''){// มี aff id หาสมาชิกทั้งหมด เพื่อหาตามวันฝาก
		  $this->db->where('group_af_l1=', $member_no); 
		  $this->db->or_where('mber.id=', $member_no); 
		}else{// ไม่มี aff id หาสมาชิกทั้งหมด ตามวันสมัคร
			if(trim($timestart)!=''&&trim($timeend)!=''){ 
				$this->db->where('create_at >=', $timestart);
				$this->db->where('create_at <=', $timeend);
			  }else{ 
				$this->db->where("create_at >=CONCAT(SUBDATE(CURDATE(),0),' ','00:00:00')","", false);
				$this->db->where("create_at <=CONCAT(SUBDATE(CURDATE(),0),' ','23:59:59')","", false); 
			  }
		}
		if(trim($source_ref)!=''&&trim($source_ref)!='all'){
			$this->db->where('source_ref=', $source_ref); 
		  }
		//   if(trim($timestart)!=''&&trim($timeend)!=''){ 
		// 	$this->db->where('create_at >=', $timestart);
		// 	$this->db->where('create_at <=', $timeend);
		//   }else{ 
		// 	$this->db->where("create_at >=CONCAT(SUBDATE(CURDATE(),0),' ','00:00:00')","", false);
		// 	$this->db->where("create_at <=CONCAT(SUBDATE(CURDATE(),0),' ','23:59:59')","", false); 
		//   }

		$sql = $this->db->get('members AS mber');
		 // print_r($this->db->last_query());exit();
		$result_array = $sql->result_array();
		$row = $sql->row();
   
		$result['num_rows'] = (is_array($result_array) ? sizeof($result_array) : 0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	 } 
}
?>