<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit extends MY_Controller {
	public function __construct() {
		parent:: __construct();  
		$this->load->library("ConfigData");
		$this->load->model('Model_log_deposit');
	}
	public function view(){ 
		$item['PGDateNow'] = date("Y-m-d"); 
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$this->load->view('view_deposit',$item);
	}

	public function list_deposit_json(){
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array(); 
		if($trx_date_check == true){
			$Result = $this->Model_db_log->search_log_deposit_by_date($trx_date);
			if($Result['num_rows'] > 0){
				foreach ($Result['result_array'] as $item) {
					$manual_deposit='';
					$remark_internal=(isset($item['remark_internal'])?':'.$item['remark_internal']:'');
					$RScc = $this->Model_function->condition_check_status($item['status'],$item['channel'], $item['remark'],$item['remark_internal']);
					if($item['username'] == NULL){
						$item['username'] = '<i class="fa fa-times danger"></i>';
					}
					if ($item['status']==3) {
						$manual_deposit='row_admin_deposit3';
					}
					$rejectClick = "";
					if ($item['status'] != 0 &&$item['status'] != 1 && $item['status'] != 4 && $item['status'] != 3) {
						$rejectClick = '<button class="btn btn-danger btn-sm mt005px" onclick="reject(\''.$this->encryption->encrypt($item['id']).'\')">ยกเลิกรายการ</button>';
						$manual_deposit='row_admin_deposit4';
					}
					$BtnView = "";
					if($item['channel'] == 1 || $item['channel'] == 2|| $item['channel'] == 3){
						$BtnView = '<button onclick="view_fullMsg(\''.$item['trx_id'].'\','.$item['channel'].')" class="btn btn-info btn-sm"><i class="la la-search"></i></button>';
					}
					if(isset($item['openration_type'])){
					if(($item['channel'] <= 3 ||$item['channel']==5) && in_array($item['openration_type'],$this->configdata->Getgroup_manual('bank'))){ $manual_deposit='row_admin_deposit1'; }
					else if(($item['channel'] > 3 && $item['channel']!=5) && in_array($item['openration_type'],$this->configdata->Getgroup_manual('bank_orther'))) {$manual_deposit='row_admin_deposit2';}
					else if(in_array($item['openration_type'],$this->configdata->Getgroup_manual('pro'))) {$manual_deposit='row_admin_deposit5';}
				    }

					$jsonArr = array("<center>".$item['trx_date']." ".$item['trx_time']."</center>",
									 "<center>".$item['update_date']."</center>",
									 "<center><a target='_blank' href='".base_url("member/profile/".$item['username'])."'>".$item['username']."</a></center>",
									 "<center>".$RScc['channelText']."</center>",
									 "<center>".number_format($item['balance_before'], 2)."</center>",
									 "<center>".number_format($item['amount'], 2)."</center>",
									 "<center class='".$RScc['class_text_color']."'>".$RScc['statusText'].$rejectClick."</center>",
									 $item['remark'].$remark_internal,
									 $BtnView
									,$manual_deposit
									);
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data'=> $dataArr);
		echo json_encode($json_arr);

	}

	public function log_autobank_json(){
		
		$trx_id = isset($_POST['trx_id']) ? $_POST['trx_id'] : 0;
		$channel = isset($_POST['channel']) ? $_POST['channel'] : 0;

		$MessageBool = false;
		$dataArr = array();
		$Result = "";

		if($channel == 1){
			$Result = $this->Model_db_log->load_log_deposit_scb($trx_id);
		} else if($channel == 2){
			$Result = $this->Model_log_deposit->load_log_deposit_truewallet($trx_id);
		}else if($channel == 3){
			$Result = $this->Model_db_log->load_log_deposit_kbank($trx_id);
		}
		$deposit=$this->Model_db_log->search_log_deposit_by_trx_id($trx_id);
		if($Result != ""&&$Result['num_rows'] <= 0&&$deposit['num_rows']>0){
			$Result=[];$Result['num_rows']=1; 
			$Result['row']['full_msg']='';
			$Result['row']['trx_amount']=$deposit['row']->amount;
			$Result['row']['trx_id']=$deposit['row']->trx_id;
			$Result['row']['update_date']=$deposit['row']->update_date;
			$Result['row']['trx_date']=$deposit['row']->trx_date;
			$Result['row']['trx_time']=$deposit['row']->trx_time;
			$Result['row']=(object)$Result['row']; 
		}
		$img_slip='';
		if($deposit['num_rows']>0){if($deposit['row']->slip!=null&&$deposit['row']->slip!=''){$img_slip=base_url('images/slip/'.$deposit['row']->slip);} }
		
		if($Result != ""){
			if($Result['num_rows'] > 0){
				$MessageBool = true;

				$jsonArr = array('full_msg' => $Result['row']->full_msg,
								 'img_slip'=>$img_slip,
								 'trx_amount' => $Result['row']->trx_amount,
								 'logtrx_id'=>$Result['row']->trx_id,
								 'trx_sevser_datetime' => $Result['row']->update_date,
								 'trx_datetime' => $Result['row']->trx_date." ".$Result['row']->trx_time);
				array_push($dataArr, $jsonArr);
			}	
		} 
		
		
		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}
}
?>