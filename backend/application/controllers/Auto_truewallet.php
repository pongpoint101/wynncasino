<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_truewallet extends CI_Controller {

	
	public function __construct() {
		parent:: __construct();
		//$this->Model_function->LoginValidation();
	}


	public function trx(){
		header("Content-type: application/json; charset=utf-8");
		date_default_timezone_set("Asia/Bangkok");

		$str_post = file_get_contents('php://input');

		if (!isset($str_post)) {
			$arr['code'] = 404;
			$arr['msg'] = 'Invalid request data';
			echo json_encode($arr);
			exit();
		}

		$start_dt = Date('Y-m-d H:i:s.u');
		$member_no = 0;
		$channel = 2; //True Wallet
		$depositStatus = 1;
		$remark = 'TrueWallet';

		$arrRawData = json_decode($str_post, true);
		$bank_id='truewallet';
		 // file_put_contents('../../../test/tesxdata'.'/'.$bank_id.'/'.date('YmdHi').'.txt',$str_post);

		if ($arrRawData['info']['records'] == 0) {
			echo "New deposit records NOT FOUND" . '<BR>';
			echo '@ ' . date('Y-m-d H:i:s') . '<BR>';
			exit();
		}

		$this->Model_db_log->insert_log_auto_truewallet($arrRawData['info']['name'],$arrRawData['info']['mobile_number'],$arrRawData['info']['balance'],$arrRawData['info']['time_stamp'],$arrRawData['info']['records']);
		
		$arrTrx = $arrRawData['transactions'];
		foreach ($arrTrx as $row) {

			$RSMember = $this->Model_member->SearchMemberByTelephone($row['w_sender']);
			$balance_before=0;
			if($RSMember['num_rows'] <= 0){
				$member_no = 0;
			    $depositStatus = 2;
			    $remark = 'TW-NotFound'.'<br><small>('.$row['w_sender'].')</small>';
			} else {
				$member_no = $RSMember['row']->member_no;
				$depositStatus = 1;
				$remark = 'TrueWallet';
				$memberpromo=$RSMember['row']->member_promo;
				$memberlast_deposit=$RSMember['row']->member_last_deposit;
			}


			$trx_date = explode(" ", $row['w_transfer_date'])[0];
			$trx_time = explode(" ", $row['w_transfer_date'])[1];
			$trx_id = $row['w_tx_id'];

			$this->Model_member->insert_auto_truewallet($member_no,$arrRawData['info']['mobile_number'],$trx_id,$row['w_amount'],
														$row['w_sender'],$row['w_sender_name'],$trx_date,$trx_time,0,$row['w_status_msg']);

			$num_rows_Duplicate = $this->Model_db_log->search_log_deposit_trx($member_no,$row['w_amount'],$trx_date,$trx_time);
			
			if($num_rows_Duplicate == 0){
				$res = $this->Model_db_log->search_log_deposit_by_trx_id($trx_id);
				if ($res['num_rows'] < 1) {
					if ($member_no != 0) {
						$bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
						$datamsite= $this->Model_affiliate->list_m_site();  
						$balance_before=$bal_b4['row']->main_wallet; 
						$min_auto_deposit=$datamsite['row']->min_auto_deposit;
						if ($balance_before > $min_auto_deposit&&($memberpromo!=0||(!in_array($memberlast_deposit,[1,2,3,5])))) { 
							$depositStatus = 2;
	        				$remark = 'Bal>10';
						}
						if($depositStatus==1){
							$ResultMemPD = $this->Model_db_log->search_log_withdraw_by_padding_member_no($member_no);
							if($ResultMemPD['num_rows'] > 0){
								$depositStatus = 2;
								$remark = 'Withdraw > Status Padding';
							}
						}

						if($depositStatus==1){
							$datetime_sms = date_create($trx_date. ' ' . $trx_time);
							$datetime_now = date_create(date('Y-m-d') . ' ' . date('H:i:s'));
							$datetime_diff = $datetime_now->diff($datetime_sms);
							$time_diff = 0;
							if ($datetime_diff->format('%d') != '0') {
								$time_diff = 1;
							}
							if ($datetime_diff->format('%h') != '0') {
								$time_diff = 1;
							}
							if ($time_diff == 0) {
								if (((int) $datetime_diff->format('%i')) >= 5) {  //} $site['sms_delay']) {  //Delayed equal or more than 5 minutes
									$time_diff = 1;
								}
							} 
							if ($time_diff == 1) {
								$depositStatus = 5;
								$remark = 'delayed-TRUE'; 
							}	
						}

						if($depositStatus==1){
							$this->Model_member->adjustMemberWallet($member_no, (float)$row['w_amount'], 1);
							$this->Model_member->insert_adjust_wallet_history_02('Deposit-TrueWallet',$member_no,(float)$row['w_amount'],$balance_before,"");
							
							$this->Model_promo->ClearProOrTurnOver($member_no);  
							$this->Model_member->reset_member_promo_truewallet($member_no);
					    }

						$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
					    if ($RS1stDepo['num_rows'] <= 0) {
					        $this->Model_db_log->insert_1st_deposit($member_no,$row['w_amount'],"TW");
					    }
					} 

					$this->Model_db_log->insert_log_deposit_02($member_no, $row['w_amount'], $channel, $row['w_tx_id'], $depositStatus, $remark,$trx_date,$trx_time,-1,$balance_before);
				}
			}
			

			
		}



		echo 'Name       : ' . $arrRawData['info']['name'] . '<BR>';
		echo 'TrueWallet : ' . $arrRawData['info']['mobile_number'] . '<BR>';
		echo 'Start      : ' . $start_dt . '<BR>';
		echo 'End        : ' . date('Y-m-d H:i:s') . '<BR>';
		echo 'Balance    : ' . $arrRawData['info']['balance'] . '<BR>';
		echo 'Time Stamp : ' . $arrRawData['info']['time_stamp'] . '<BR>';
		echo 'Records    : ' . $arrRawData['info']['records'] . '<BR>';
		echo 'Hostname   : ' . $_SERVER['HTTP_HOST'] . '<BR><BR>';


	}
}
?>