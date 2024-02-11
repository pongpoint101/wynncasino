<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autokbank extends CI_Controller {
	public function trx(){ 
		$phoneNumber = $_REQUEST['phoneNumber'] ?? $_POST['phoneNumber'] ?? $_GET['phoneNumber'] ?? NULL;
		$smsContent  = $_REQUEST['sms'] ?? $_POST['sms'] ?? $_GET['sms'] ?? NULL;
		// $this->load->helper('file');  
		// write_file(FCPATH .'/tesxdata/kbank.txt', json_encode($smsContent));
		
		if (!isset($_POST['phoneNumber'])) {
		    echo json_encode(['code' => 404, 'msg' => '[phoneNumber not found] Error!!!!']);
		    exit();
		} elseif (!isset($_POST['sms'])) {
		    echo json_encode(['code' => 404, 'msg' => '[sms not found] Error!!!!']);
		    exit();
		} elseif (strpos($smsContent, "รับโอนจาก") === false) {  //Can not find "รับโอนจาก" word then rejecte
		    echo json_encode(['code' => 404, 'msg' => '[รับโอนจาก not found] Error!!!!']);
		    exit();
		}
		 
		$balance_before=0;
		$smsContentOriginal = $smsContent;
		$smsContent = str_replace('บช X-', '', $smsContent);
		$smsContent = str_replace('รับโอนจาก X-', '', $smsContent);

		$arr = explode(' ', $smsContent);
		$arr_date = explode("/", $arr[0]);
		$arrTrx['deposit_date'] = (($arr_date[2] + 2500) - 543) . '-' . $arr_date[1] . '-' . $arr_date[0];   // Deposit Date & Time
		$arrTrx['deposit_time'] =  $arr[1] . ':00';
		$arrTrx['deposit_to'] = $arr[2];                                          // Deposit to...
		$arrTrx['deposit_from'] = $arr[3];                                  // Deposit from...
		$arrTrx['deposit_amount'] = $arr[4];                                                            // Deposit amount
		$arrTrx['deposit_amount'] = str_replace(',', '', $arrTrx['deposit_amount']); // Deposit amount 
		$arrTrx['from_bank'] = '004';  // KBank code = 004
		$arrTrx['sms'] = $smsContentOriginal;
		
		if ((float)$arrTrx['deposit_amount'] < 0) {
		    exit();
		}
		
		$remark = '';
		$arrTrx['origi_bank'] = 'KBANK';
		$arr_members = $this->Model_member->getMemberNoByBankAcct_kbank($arrTrx['deposit_from'], $arrTrx);

		$RslogDpKbank = $this->Model_db_log->search_log_deposit_kbank($arr_members['member_no'],$arrTrx['deposit_date'],$arrTrx['deposit_time'],$arrTrx['deposit_amount'],$arrTrx['deposit_from'],$arrTrx['deposit_to']);
		if ($RslogDpKbank > 0) {
		    exit();
		}
		$RslogDpKbank = $this->Model_db_log->search_log_deposit_trx($arr_members['member_no'],$arrTrx['deposit_amount'], $arrTrx['deposit_date'], $arrTrx['deposit_time']);
		if ($RslogDpKbank > 0) {
		    exit();
		}
		$arrTrx['status'] = $arr_members['rows_count'];
		$res = $this->Model_member->SearchMemberWalletBymember_no($arr_members['member_no']);

		if ($arr_members['rows_count'] <= 0) {
		    $arrTrx['status'] = 2;
		    $remark = 'ไม่พบบัญชี';
		} elseif ($arr_members['rows_count'] > 1) {

		    $arrTrx['status'] = 2;
		    $remark = 'เลขบัญชีซ้ำ(KBANK-' . $arrTrx['deposit_from'] . ')';
		    $this->Model_bank->inserted_duplicate_acct($arrTrx['deposit_to'],'KBank',$arrTrx['deposit_from'],$arrTrx['deposit_amount'],$arrTrx['deposit_date']. " " . $arrTrx['deposit_time'],'SMS');

		} else {
			$datamsite= $this->Model_affiliate->list_m_site();  
			$balance_before=$res['row']->main_wallet; 
			$min_auto_deposit=$datamsite['row']->min_auto_deposit;
			if ($balance_before <= $min_auto_deposit||(($arr_members['member_promo']==0&&in_array($arr_members['member_last_deposit'],[1,2,3,5]))&&$balance_before>$min_auto_deposit)) {
		        $arrTrx['status'] = 1; 
		        $remark = 'SMS-KBank';
		    } else {
		        $arrTrx['status'] = 2;
		        $remark = 'Bal>10';
		    }
		    // if (isPromoLocked($arr_members['member_no'])) {
		    //     $arrTrx['status'] = 2;
		    //     $remark = 'ติดโปรฯ';
		    // }
		}
		if ($arrTrx['status'] == 1) {
		    $ResultMemPD = $this->Model_db_log->search_log_withdraw_by_padding_member_no($arr_members['member_no']);
		    if($ResultMemPD['num_rows'] > 0){
				$arrTrx['status'] = 2;
		        $remark = 'มียอดถอนค้าง(' . $remark . ')';
			}
		}

		

		$arrTrx['trx_id'] = $this->Model_bank->log_sms_genTrxID();

		$this->Model_bank->inserted_log_sms($phoneNumber,$smsContent,$arrTrx['trx_id']);

		header("Content-type: application/json; charset=utf-8");

		$member_last_deposit = 1;

		//$RslogDpTRX = $this->Model_db_log->search_log_deposit_trx($arr_members['member_no'],$arrTrx['deposit_amount'],$arrTrx['deposit_date'],$arrTrx['deposit_time']);
		$RslogDpTRX=5;
		if($RslogDpTRX <= 0){
			$datetime_sms = date_create($arrTrx['deposit_date'] . ' ' . $arrTrx['deposit_time']);
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
		        if (((int) $datetime_diff->format('%i')) >= 2) {  //} $site['sms_delay']) {  //Delayed equal or more than 5 minutes
		            $time_diff = 1;
		        }
		    }

		    if ($time_diff == 1) {
		        if ($arrTrx['status'] != 2) {
		            $arrTrx['status'] = 5;
		            $remark = 'SMS delayed-KBank';
		        }
		    }

		    $arrTrx['trx_id'] = uniqid();

		    $this->Model_db_log->insert_log_deposit_02($arr_members['member_no'], $arrTrx['deposit_amount'], 3, $arrTrx['trx_id'], $arrTrx['status'], $remark,$arrTrx['deposit_date'],$arrTrx['deposit_time'],'-1',$balance_before);
			$this->Model_db_log->insert_log_deposit_kbank($arr_members['member_no'],$arrTrx['trx_id'],$arrTrx['deposit_date'],$arrTrx['deposit_time'],$arrTrx['deposit_amount'],"004",$arrTrx['deposit_from'],$arrTrx['deposit_to'],$arrTrx['sms'],$arrTrx['status']);
			if ($arrTrx['status'] == 1) {

				$member_no = $arr_members['member_no'];

				$this->Model_member->reset_member_promo_02($member_no);
				$this->Model_promo->ClearProOrTurnOver($member_no);  

				$bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
				$this->Model_member->adjustMemberWallet($member_no, $arrTrx['deposit_amount'], 1);
				$this->Model_member->insert_adjust_wallet_history_02('Deposit-SMS-KBank',$member_no,$arrTrx['deposit_amount'],$bal_b4['row']->main_wallet,"");

				

			}
			$RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($arr_members['member_no']);
		    if ($RS1stDepo['num_rows'] <= 0) {
		        $this->Model_db_log->insert_1st_deposit($arr_members['member_no'],$arrTrx['deposit_amount'],"KBank");
		    }
			
			echo json_encode($arrTrx);
		}  else {
		    echo json_encode([
		        'code' => 1034,
		        'msg' => 'Transaction duplicated or already deposit'
		    ]);
		}
	}
}