<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate extends MY_Controller
{ 
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library("ConfigData");
	}
	public function view()
	{ 

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Affiliate';

		$this->load->view('view_affiliate', $item);
	}

	public function history()
	{  $this->Model_function->LoginValidation();
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Affiliate';

		$this->load->view('view_affiliate_history', $item);
	}
	public function moneyincom()
	{   $this->Model_function->LoginValidation();
		$this->load->model('Model_aff_win_loss'); 
		$member_no=$this->input->get('member_no', TRUE);
		$timestart=$this->input->get('timestart', TRUE);
		$timeend=$this->input->get('timeend', TRUE);
        if($timestart==''){$timestart = date('Y-m-01'); }
		if($timeend==''){$timeend = date('Y-m-t'); }
		$res_site=$this->Model_affiliate->list_m_site();
		$site_id=$res_site['row']->site_id;
		$member_no=str_ireplace($site_id,"",$member_no); 
		$item['param_member_no']=$member_no;
		$item['param_timestart']=$timestart;
		$item['param_timeend']=$timeend;

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'รายได้จากสมาชิก'; 
		$item['card_title'] = 'รายได้จากสมาชิก'; 

       $item['m_site']=$this->Model_affiliate->list_m_site(); 
	   $item['member_aff_type']='';
	   $item['credit_aff_total_current']=0;
	   $item['aff_l1_count']=0; 
	   $item['aff_l2_count']=0;
	   if($member_no>0){
		$memberwallet=$this->Model_member->SearchMemberByIDs($member_no);  
		$aff_l1=$this->Model_affiliate->search_aff_l1_by_member_no($member_no);
		$aff_l2=$this->Model_affiliate->search_aff_l2_by_member_no($member_no);
	   }
	   if(isset($aff_l1['num_rows'])){
		$item['aff_l1_count']=number_format($aff_l1['num_rows']);
	   }
	   if(isset($aff_l2['num_rows'])){
		$item['aff_l2_count']=number_format($aff_l2['num_rows']);
	   }
	   if(isset($memberwallet['row'])){
		   $item['member_aff_type']=$memberwallet['row']->aff_type;
		  if($memberwallet['row']->aff_type==1){
			 $item['credit_aff_total_current']=($memberwallet['row']->aff_wallet_l1+$memberwallet['row']->aff_wallet_l2)*1;
		  }else if($memberwallet['row']->aff_type==2){
			$WebSites =$item['m_site']['row'];
			$income_level1=$this->Model_aff_win_loss->GetWinlossLevel($member_no,0,1,$timestart,$timeend); 
			$income_level2=$this->Model_aff_win_loss->GetWinlossLevel($member_no,0,2,$timestart,$timeend); 
			$tot1 = isset($income_level1['total']['total']) ? $income_level1['total']['total'] : 0;
			$income_level1_total = ($tot1 * $WebSites->aff_rate1) / 100;
			 
			$tot2 = isset($income_level2['total']['total']) ? $income_level2['total']['total'] : 0;
			$income_level2_total = ($tot2 * $WebSites->aff_rate2) / 100;
			$log_win_loss = $this->Model_aff_win_loss->GetLogWinloss($member_no);
		  
			$item['credit_aff_total_current']= ($income_level1_total + $income_level2_total)+($log_win_loss); 
			$item['income_level1']=$income_level1;
			$item['income_level2']=$income_level2;
		  } 
		  $item['credit_aff_total_current']=number_format((float)$item['credit_aff_total_current'], 2, '.', '');
	   }
	   
		$this->load->view('aff/viewmoneyincome', $item);
	}
	// public function ranking(){
	// 	$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
	// 	$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
	// 	$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

	// 	$item['pg_title'] = 'Affiliate';

	// 	$this->load->view('view_affiliate_ranking',$item);
	// }
 

	public function list_log_aff_history_json()
	{

		$this->Model_function->LoginValidation();
		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$length = isset($_GET['length']) ? $_GET['length'] : 0;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$orderARR = isset($_GET['order']) ? $_GET['order'][0] : NULL;
		$date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : NULL;
		$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : NULL;
		$searchName = isset($_GET['search']) ? $_GET['search']['value'] : NULL;

		$Result = $this->Model_affiliate->list_aff_history($start, $length, $orderARR, $date_begin, $date_end, $searchName);
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					"<center><a target='_blank' href='" . base_url("member/profile/" . $item['username']) . "#aff'>" . $item['username'] . "</a></center>",
					"<center>" . (($item['count_af_l1']) ? $item['count_af_l1'] : 0) . "</center>",
					"<center>" . (($item['count_af_l2']) ? $item['count_af_l2'] : 0) . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => $Result['num_rows'], 'recordsFiltered' => $Result['num_rows']);
		echo json_encode($json_arr);
	} 
	public function list_log_aff_json()
	{
		$this->Model_function->LoginValidation();
		$Result = $this->Model_affiliate->list_aff_bonut();
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					"<center>" . $item['member_username'] . "</center>",
					"<center>" . $item['amount'] . "</center>",
					"<center>" . $item['update_date'] . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	} 
	public function refregister()
	{  $this->Model_function->LoginValidation();
		$inputdata = $this->input->get(null, TRUE);  
		$this->load->model("read/Model_rlog_reportsumbyday");	
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Reports';
		$item['card_title'] = 'ข้อมูลการสมัครตามสมาชิกผู้แนะนำ';      
		$member_no=@$inputdata['member_no'];$source_ref=@$inputdata['who_source'];$timestart=@$inputdata['timestart'];$timeend=@$inputdata['timeend'];
	    $res_site=$this->Model_affiliate->list_m_site();
		$site_id=$res_site['row']->site_id;
		$member_no=str_ireplace($site_id,"",$member_no); 
		$list_members=$this->Model_rlog_reportsumbyday->FindAll_Register($member_no,$source_ref,$timestart,$timeend);	 
		$item['list_members'] =$list_members;
		$item['listsource'] =$this->configdata->source_ref;
		$this->load->view('reports/register_source',$item);   
	}
	public function transferofwork()
	{   $this->Model_function->LoginValidation(); 
		$item['pg_title'] = 'จัดการสายงานแนะนำเพื่อน'; 
		$item['card_title'] = 'จัดการสายงานแนะนำเพื่อน';  
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['member_aff_type']='';
		$item['credit_aff_total_current']=0;
		$item['aff_l1_count']=0; 
		$item['aff_l2_count']=0;
		$item['member_aff_type']=0;
		$item['memberoriginno']='';
		$item['member_destinationno']='';
		$item['action']='';
		
		$member_destinationno='';
		$member_origin_no=$this->input->post('memberoriginno', TRUE);  
		if($member_origin_no==null||$member_origin_no==''){$member_origin_no=$this->input->get('memberoriginno', TRUE);  } 
		$action=$this->input->get('action', TRUE);   
		$item['m_site']=$this->Model_affiliate->list_m_site(); 
		$site_id=$item['m_site']['row']->site_id;
		$member_no=str_ireplace($site_id,"",$member_origin_no); 
		$item['memberoriginno']=$member_no;
        if($member_origin_no!=null&&$member_origin_no!=''&&$this->input->method(TRUE)=='POST'){
			$member_destination_no=$this->input->post('member_destination_no', TRUE); 
			$member_destinationno=str_ireplace($site_id,"",$member_destination_no);  
			$item['member_destinationno']=$member_destinationno; 
		}
		// return $this->input->method(TRUE);
		if($this->input->method(TRUE)=='POST'){

			$action=$this->input->post('action', TRUE);   
			$create_by=@$this->encryption->decrypt($this->session->userID);
			$create_by=($create_by!=null&&$create_by!=''?$create_by:null);
			$input_data = $this->input->post(null, TRUE); 
			$item['m_site']=$this->Model_affiliate->list_m_site(); 
			$site_id=$item['m_site']['row']->site_id;
			$memberoriginno=str_ireplace($site_id,"",@$input_data['memberoriginno']); 
			$memberoriginno=str_ireplace($site_id,"",$memberoriginno);
			$delete_list=[];$delete_member_branch_list=[];$logtransferofwork_list=[];$transfer_to_list=[];
			 
			$this->db->select_max('sequence_id'); 
			$this->db->where('group_af_l1', $memberoriginno);
			$this->db->or_where('group_af_l2', $memberoriginno);
			$rowsequence = $this->db->get('log_transferofwork')->row();  
            $sequence_id=isset($rowsequence->sequence_id)?($rowsequence->sequence_id+1):1;
			$member_destinationno=($member_destinationno!='')?$member_destinationno:null;
			$group_af_username=($member_destinationno!='')?$site_id.$member_destinationno:null;
			$member_list_insert=[];
			if(isset($input_data['frm_l1_affwork'])){
			// if(@sizeof($input_data['frm_l1_affwork'])){
				$aff_l1=$this->Model_affiliate->search_aff_l1_by_member_no($memberoriginno,'','');
				foreach (@$aff_l1['result_array'] as $k => $v) {
					$input_name = 'check_aff1_'.$v['id'];
					$memberid_aff = isset($input_data['frm_l1_affwork'][$input_name])?$input_data['frm_l1_affwork'][$input_name]:''; 
					if($memberid_aff!=''){ 
						$group_afl1=$v['group_af_l1'];
						$member_list_insert[]=$memberid_aff; 
						$logtransferofwork_list[]=['sequence_id'=>$sequence_id,'operation_type'=>$action,'member_no'=>$memberid_aff,'group_af_l1'=>$group_afl1,'create_by'=>$create_by,'ip'=>$this->Model_function->getIP()];
						$delete_list[]=['id'=>$memberid_aff,'group_af_l1'=>null,'group_af_username_l1'=>null]; 
						$transfer_to_list[]=['id'=>$memberid_aff,'group_af_l1'=>$member_destinationno,'group_af_username_l1'=>$group_af_username]; 
						$delete_member_branch_list[]=['member_no'=>$memberid_aff,'group_af_l1'=>$member_destinationno,'total_turnover'=>0,'comm_given'=>0]; 
					}
				}
			}
			$delete_list_1=$delete_list;$delete_member_branch_list_l1=$delete_member_branch_list;
			$logtransferofwork_list_l1=$logtransferofwork_list;$transfer_to_list_l1=$transfer_to_list;
			$delete_list=[];$delete_member_branch_list=[];$logtransferofwork_list=[];$transfer_to_list=[];
			
			if(isset($input_data['frm_l2_affwork'])){
			// if(@sizeof($input_data['frm_l2_affwork'])){
				$aff_l1=$this->Model_affiliate->search_aff_l2_by_member_no($memberoriginno,'',''); 
				foreach (@$aff_l1['result_array'] as $k => $v) {
					$input_name = 'check_aff2_'.$v['id'];
					$memberid_aff = isset($input_data['frm_l2_affwork'][$input_name])?$input_data['frm_l2_affwork'][$input_name]:''; 
					if($memberid_aff!=''){ 
						$group_afl2=$v['group_af_l2'];
						$member_list_insert[]=$memberid_aff; 
						$logtransferofwork_list[]=['sequence_id'=>$sequence_id,'operation_type'=>$action,'member_no'=>$memberid_aff,'group_af_l2'=>$group_afl2,'create_by'=>$create_by,'ip'=>$this->Model_function->getIP()];
						$delete_list[]=['id'=>$memberid_aff,'group_af_l2'=>null,'group_af_username_l2'=>null]; 
						$transfer_to_list[]=['id'=>$memberid_aff,'group_af_l2'=>$member_destinationno,'group_af_username_l2'=>$group_af_username];
						$delete_member_branch_list[]=['member_no'=>$memberid_aff,'group_af_l2'=>$member_destinationno,'total_turnover'=>0,'comm_given'=>0]; 
					}
				}
			}
			$delete_list_2=$delete_list;$delete_member_branch_list_l2=$delete_member_branch_list;
			$logtransferofwork_list_l2=$logtransferofwork_list;$transfer_to_list_l2=$transfer_to_list;
			$check_state=false; 
			if($member_destinationno==$memberoriginno){
				$this->respone->status_code = 200; 
				$this->respone->status_msg = 'DUPLICATE2';
				$this->ResponeJson($this->respone, $this->respone->status_code);
			}    
			if(sizeof($member_list_insert)>0){  
				$check_dupicatestate=false;
				foreach ($member_list_insert as $k => $v) {
					  if($member_destinationno==$v){$check_dupicatestate=true;}
				}
				if ($check_dupicatestate) {
					$this->db->select('username,fname,lname');
					$this->db->where('id', $member_destinationno); 
					$row_member= $this->db->get('members')->row_array(); 
					$this->respone->status_code = 200;
					$this->respone->data = $row_member;
					$this->respone->status_msg = 'DUPLICATE';
					$this->ResponeJson($this->respone, $this->respone->status_code);	 
				}
			}  
		
			if(@$input_data['action']=='delete'&&sizeof($member_list_insert)>0){ 
				$this->db->trans_start(); 
				if(sizeof($logtransferofwork_list_l1)>0){$this->db->insert_batch('log_transferofwork', $logtransferofwork_list_l1);}
				if(sizeof($logtransferofwork_list_l2)>0){$this->db->insert_batch('log_transferofwork', $logtransferofwork_list_l2);}
				if(sizeof($delete_list_1)>0){$this->db->update_batch('members', $delete_list_1, 'id');}
				if(sizeof($delete_list_2)>0){$this->db->update_batch('members', $delete_list_2, 'id');} 
				if(sizeof($delete_member_branch_list)>0){$this->db->update_batch('aff_member_branch', $delete_member_branch_list_l1, 'member_no');} 
				if(sizeof($delete_member_branch_list_l2)>0){$this->db->update_batch('aff_member_branch', $delete_member_branch_list_l2, 'member_no');} 
				$this->db->trans_complete();        
				$check_state=($this->db->trans_status() === FALSE)? FALSE:TRUE;
			}else if(@$input_data['action']=='transfer'&&sizeof($member_list_insert)>0){
				$this->db->trans_start();
			  	if(sizeof($logtransferofwork_list_l1)>0){$this->db->insert_batch('log_transferofwork', $logtransferofwork_list_l1);}
				if(sizeof($logtransferofwork_list_l2)>0){$this->db->insert_batch('log_transferofwork', $logtransferofwork_list_l2);}
				if(sizeof($delete_list_1)>0){$this->db->update_batch('members', $delete_list_1, 'id');}
				if(sizeof($delete_list_2)>0){$this->db->update_batch('members', $delete_list_2, 'id');}
				if(sizeof($transfer_to_list_l1)>0){$this->db->update_batch('members', $transfer_to_list_l1, 'id');} 
				if(sizeof($transfer_to_list_l2)>0){$this->db->update_batch('members', $transfer_to_list_l2, 'id');} 
				if(sizeof($delete_member_branch_list_l1)>0){$this->db->update_batch('aff_member_branch', $delete_member_branch_list_l1, 'member_no');} 
				if(sizeof($delete_member_branch_list_l2)>0){$this->db->update_batch('aff_member_branch', $delete_member_branch_list_l2, 'member_no');} 
				$this->db->trans_complete();        
				$check_state=($this->db->trans_status() === FALSE)? FALSE:TRUE;
			}
          if($check_state){
			$this->respone->status_code = 200;
			$this->respone->status_msg = 'OK';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		  }else{
			$this->respone->status_code = 200;
			$this->respone->status_msg = 'FAIL';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		  } 
		}  
		
		if($member_origin_no!=null&&$member_origin_no!=''){
			$aff_l1=$this->Model_affiliate->search_aff_l1_by_member_no($member_no,'','');
			$aff_l2=$this->Model_affiliate->search_aff_l2_by_member_no($member_no,'','');
	   	}
	   	if(isset($aff_l1['num_rows'])){
			$item['aff_l1_count']=number_format($aff_l1['num_rows']);
			$item['listmember_aff_l1']=$aff_l1['result_array'];
	   	}
	   	if(isset($aff_l2['num_rows'])){
			$item['aff_l2_count']=number_format($aff_l2['num_rows']);
			$item['listmember_aff_l2']=$aff_l2['result_array'];
	   	}  
		$this->load->view('aff/viewtransferofwork', $item);
	}
	public function manage()
	{ 
		$mSite = $this->db->get_where('m_site', array('site_id' => SITE_ID))->row();
		if (isset($mSite->create_user_by_admin)) {
			$item['create_user_by_admin'] = $mSite->create_user_by_admin;
		} else {
			$item['create_user_by_admin'] = $mSite->create_user_by_admin;
		}
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'จัดการรายการแนะนำเพื่อน';

		$this->load->view('view_affiliate_manage', $item);

	}
	public function list_manage_aff_json()
	{
		$this->Model_function->LoginValidation();
		$Result = $this->Model_affiliate->list_aff_manage();
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					'<center><a target="_blank" href="' . base_url("member/profile/" . $item['username']) . '">' . $item['username'] . '</a></center>',
					"<center>" . $item['fname'].' ' .$item['lname'] . "</center>",
					'<center><a target="_blank" href="' . base_url("member/profile/" . $item['group_af_username_l1']) . '">' . $item['group_af_username_l1'] . '</a></center>',
					// '<center><a target="_blank" href="' . base_url("member/profile/" . $item['group_af_username_l2']) . '">' . $item['group_af_username_l2'] . '</a></center>',
					"<center>" . '<div class="d-flex align-items-center"><button onclick="EditAff(\'' . $this->encryption->encrypt($item['id']) . '\')" class="btn btn-icon btn-info btn-action"><i class="la la-edit" style="vertical-align: bottom;"></i></button>' .'</div>' . "</center>",
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	} 
	public function list_json_show_edit_aff()
	{
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);

		$MessageBool = false;
		$dataArr = array();
		if ($pg_ids != false) {
			$MessageBool = true;
			$Result = $this->Model_member->SearchMemberByIDs($pg_ids);

			if ($Result['num_rows'] > 0) {
				$MessageBool = true;

				foreach ($Result['result_array'] as $item) {
					$statusChecked = "";
					if ($item['status'] == 1) {
						$statusChecked = "checked";
					}

					$jsonArr = array(
						'username' => $item['username'],
						'group_af_l1' => $item['group_af_l1'],
						'group_af_l2' => $item['group_af_l2'],
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}
	public function add_edit_aff()
	{
		$data = $this->input->post(null, TRUE);
		$ids = isset($data['pg_ids']) ? $data['pg_ids'] : 0;
		$pg_l1 = isset($data['pg_l1']) ? $data['pg_l1'] : 0;
		// $pg_l2 = isset($data['pg_l2']) ? $data['pg_l2'] : 0;
		
		$pg_ids = $this->Model_function->get_decrypt($ids);
		$chk_l1 = $this->Model_member->SearchMemberByIDs($pg_l1);
		// $chk_l2 = $this->Model_member->SearchMemberByIDs($pg_l2);
		$create_by=@$this->encryption->decrypt($this->session->userID);
		$create_by=($create_by!=null&&$create_by!=''?$create_by:null);
		
		if($pg_l1 && $chk_l1['num_rows']<1 || $pg_l1 == $pg_ids){
			$json_arr = array('Message' => false, 'ErrorText' => 'กรุณาระบุ id ผู้แนะนำ(ลำดับ1)ให้ถูกต้อง', 'boolError' => 1);
			echo json_encode($json_arr);
			exit();
		}
		// if($pg_l2 && $chk_l2['num_rows']<1 || $pg_l2 == $pg_ids){
		// 	$json_arr = array('Message' => false, 'ErrorText' => 'กรุณาระบุ id ผู้แนะนำ(ลำดับ2)ให้ถูกต้อง', 'boolError' => 1);
		// 	echo json_encode($json_arr);
		// 	exit();
		// }

		$boolMessage = false;
		$MessageErrorText = '';
		if ($pg_ids != false) {
			$r_data = $this->Model_member->member_updated_aff($pg_ids, $pg_l1, $create_by);
			$r_data_member_branch = $this->Model_affiliate->member_updated_aff_member_branch($pg_ids, $pg_l1);
			if ($r_data == 200 && $r_data_member_branch == 200) {
				$boolMessage = true;
				$MessageErrorText = '';
			}
		}

		$json_arr = array('Message' => $boolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => 1);
		echo json_encode($json_arr);
	}
	public function addafftowork()
	{
		$this->Model_function->LoginValidation(); 
		$item['pg_title'] = 'จัดการสมาชิกแนะนำเพื่อน'; 
		$item['card_title'] = 'จัดการสมาชิกแนะนำเพื่อน';  
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		
		
		if($this->input->method(TRUE)=='POST'){

		}

		$this->load->view('aff/viewaddafftowork', $item);
	}
	// public function list_log_aff_ranking_json(){
	// 	$Result = $this->Model_affiliate->list_aff_ranking();
	// 	$dataArr = array();
	// 	$MessageBool = false;
	// 	if($Result['num_rows'] > 0){
	// 		$MessageBool = true;
	// 		$RankingI = 1;
	// 		foreach ($Result['result_array'] as $item) {
	// 			$jsonArr = array("<center>".$RankingI."</center>",
	// 							 "<center>".$item['aff_upline']."</center>",
	// 							 "<center>".$item['aff_count']."</center>");
	// 			array_push($dataArr, $jsonArr);

	// 			$RankingI++;
	// 		}
	// 	}
	// 	$json_arr = array('data'=> $dataArr);
	// 	echo json_encode($json_arr);
	// }

}
