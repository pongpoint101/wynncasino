<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autoscb extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		//$this->Model_function->LoginValidation();
	}
	// public function index()
	// {

	// 	$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
	// 	$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
	// 	$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

	// 	$item['pg_title'] = 'Dashbord';

	// 	$this->load->view('a_view_form_auto_scb_test',$item);
	// }
	public function trx_test()
	{
		date_default_timezone_set("Asia/Bangkok");
		header("Content-type: application/json; charset=utf-8");
		// date("Y-m-d",strtotime("-1 days",strtotime('2021-12-01 23:12:00')))
		// $duplicate_log = $this->Model_db_log->duplicate_log_deposit_scb(121453,200,'2021-12-06','23:12:00');
		// var_dump($duplicate_log);
	}
	public function trx()
	{
		date_default_timezone_set("Asia/Bangkok");
		header("Content-type: application/json; charset=utf-8");

		$str_post = file_get_contents('php://input');
		//$str_post = file_get_contents($_FILES["filetest"]['tmp_name']);
		$this->load->helper('file');

		if (!isset($str_post)) {
			$arr['code'] = 404;
			$arr['msg'] = 'Invalid request data';
			echo json_encode($arr);
			exit();
		}
		$start_dt = Date('Y-m-d H:i:s.u');
		$total_count = 0;
		$total_dup = 0;
		$total_rec = 0;
		$processType = '';
		$channel = 1;

		$trx = json_decode($str_post, true);

		$ResultBank = $this->Model_bank->search_list_bank_setting_by_bank_account($trx['accountNumber']);

		$bank_id = $ResultBank['row']->id; //date('YmdHi')  $bank_id
		//// file_put_contents('../../../test/tesxdata'.'/'.$bank_id.'/'.date('YmdHi').'.txt',$str_post);
		// write_file(FCPATH .'/tesxdata'.'/'.$bank_id.'/'.date('YmdHi').'.txt',$str_post);
		$ii = 1;
		foreach ($trx['transactions'] as $row) {
			if (!($row['type']['description'] == 'ฝากเงิน' || $row['type']['description'] == 'Deposit'))
				continue;
			$ii++;

			$list = array();
			$balance_before = 0;
			$list['deposit_amount'] = (float) str_replace(',', '', $row['amount']); // Deposit amount
			$list['withdraw_amount'] = 0;
			$list['date'] = explode(' ', $row['dateTime'])[0]; // Deposit date
			$list['time'] = substr(explode(' ', $row['dateTime'])[1], 0, -3) . ':00'; // Deposit time
			$list['info'] = $row['txRemark']; // Deposit info
			$list['deposit_acct_number'] = $row['remark']['number']; // Deposit bank account
			$list['bank_code'] = $this->getBankCode($row['remark']['bank']); // Bank code of deposit account
			$list['acct_name'] = $row['remark']['name']; // Deposit account name
			$list['channel'] = $row['channel']['code'];

			$total_count++;

			$bank = $row['remark']['bank'];

			$datetime_midnight = date_create(date('Y-m-d 00:00:00'));
			$datetime_now = date_create(date('Y-m-d H:i:s'));
			$datetime_bank = date_create($list['date'] . ' ' . $list['time']);

			$timeFirst  = strtotime(date('Y-m-d H:i:s'));
			$timeSecond = strtotime($list['date'] . ' ' . $list['time']);
			$differenceInSeconds = abs($timeSecond - $timeFirst) / 60;
			if ($datetime_bank > $datetime_now) {
				// file_put_contents('../../../test/delay' . $row['remark']['bank'] . $list['deposit_acct_number'] . '.txt', json_encode($row));
				continue;
			}
			if ($differenceInSeconds > 30) { //เกิน 30  นาที 
				continue;
			}

			$remark = '';

			$arr_members = $this->Model_member->getMemberNoByBankAcct($list['deposit_acct_number'], $list['bank_code'], $list['info']);

			//if (strtoupper($row['remark']['bank'])=='SCB'||strtoupper($row['remark']['bank'])=='KTB') {
			if ($arr_members['rows_count'] <= 0) { //date('YmdHi')
				// file_put_contents('../../../test/debug' . $row['remark']['bank'] . $list['deposit_acct_number'] . '.txt', json_encode($row));
				// write_file(FCPATH .'/tesxdata/debug'.$row['remark']['bank'].$list['deposit_acct_number'].'.txt',json_encode($row));
			}
			//}

			$member_no = $arr_members['member_no'];
			$list['status'] = $arr_members['rows_count'];

			if ($arr_members['rows_count'] <= 0) {
				$list['status'] = 2;
				$remark = 'ไม่พบบัญชี';
			} elseif ($arr_members['rows_count'] > 1) {
				$list['status'] = 2;
				$remark = 'เลขบัญชีซ้ำ' . '<br><small>(' . $bank . '-' . $list['deposit_acct_number'] . ')</small>';
			} else {
				$res = $this->Model_member->SearchMemberWalletBymember_no($member_no);
				$datamsite = $this->Model_affiliate->list_m_site();
				$balance_before = $res['row']->main_wallet;
				$min_auto_deposit = $datamsite['row']->min_auto_deposit;
				if (($balance_before <= $min_auto_deposit)||(($arr_members['member_promo']==0&&in_array($arr_members['member_last_deposit'],[1,2,3,5]))&&$balance_before>$min_auto_deposit)) {
					$list['status'] = 1;
				} else {
					$list['status'] = 2;
					$remark = 'Bal>10';
				}

				$ResultMemPD = $this->Model_db_log->search_log_withdraw_by_padding_member_no($member_no);
				if ($ResultMemPD['num_rows'] > 0) {
					$list['status'] = 2;
					$remark = 'Withdraw > Status Padding';
				}
			}
			// $timeFirst  = strtotime(date('Y-m-d H:i:s'));
			// $timeSecond = strtotime($list['date'] . ' ' . $list['time']);
			// $differenceInSeconds = abs($timeSecond - $timeFirst)/60;
			// if ($differenceInSeconds> 30) {//เกิน 30  นาที 
			// 	if ($list['status']!=1) {
			// 		$list['status'] = 5;
			// 		$remark = 'SCB delayed';
			// 	}
			// }
			// Check if date from bank's transaction newer than current date
			$datetime_midnight = date_create(date('Y-m-d 00:00:00'));
			$datetime_now = date_create(date('Y-m-d H:i:s'));
			$datetime_bank = date_create($list['date'] . ' ' . $list['time']);
			if ($datetime_bank > $datetime_now) {
				if ($datetime_bank->diff($datetime_now)->format('%h') > 12) {
					$list['date'] = $datetime_bank->modify('-1 day')->format('Y-m-d');
				}
			}
			$raw_content=json_encode($row);
			if($arr_members['rows_count']==1){
				$list['trx_id'] =hexdec(crc32($member_no.$list['date'].$list['time']));
			}else{
				$list['trx_id'] =hexdec(crc32($raw_content));
			}
			if ($bank == 'SCB') { // SCB -> SCB 
				if ($remark == '') {
					$remark = 'SCB <= ' . $bank;
				}
				$processType = 'SCB -> SCB';
				$count_data = $this->Model_bank->CheckSCBAutoDuplicate($bank_id, $list['deposit_amount'], $list['deposit_acct_number'], $list['date'], $list['time'], $list['info']);

				if ($count_data == 0 && $list['deposit_acct_number'] != '') {

					$total_rec++;

					$this->Model_bank->auto_bank_scb_inserted($bank_id, $list['deposit_acct_number'], $list['date'], $list['time'], $list['deposit_amount'], $list['withdraw_amount'], $trx['accountNumber'], 0, $list['bank_code'], $list['info'], $list['channel']);

					$datetime_midnight = date_create(date('Y-m-d 00:00:00'));
					$datetime_now = date_create(date('Y-m-d H:i:s'));
					$datetime_bank = date_create($list['date'] . ' ' . $list['time']);
					if ($datetime_bank > $datetime_now) {
						if ($datetime_midnight->diff($datetime_now)->format('%h') < 4) {
							if ($datetime_bank->diff($datetime_now)->format('%h') > 6) {
								$list['date'] = $datetime_bank->modify('-1 day')->format('Y-m-d');
							}
						}
					}

					$RslogDpTRX = $this->Model_db_log->search_log_deposit_trx($member_no, $list['deposit_amount'], $list['date'], $list['time']);
					if ($RslogDpTRX <= 0) {
						$del_date = date("Y-m-d", strtotime("-1 days", strtotime($list['date'] . ' ' . $list['time'])));
						$duplicate_log = $this->Model_db_log->duplicate_log_deposit_scb($member_no, $list['deposit_amount'], $del_date, $list['time']);
						if ($duplicate_log > 0) {
							$RslogDpTRX = $duplicate_log;
							// $list['status'] = 5;
							// $remark = 'SCB delayed'; 
							// file_put_contents('../../../test/duplicate' . $row['remark']['bank'] . $list['deposit_acct_number'] . '.txt', json_encode($row));
							// write_file(FCPATH .'/tesxdata/duplicate/'.$row['remark']['bank'].$list['deposit_acct_number'].'.txt',json_encode($row));
						}
					}
					if ($RslogDpTRX <= 0) {
						if ($list['status'] == 1) {
							// $bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
							// $this->Model_member->adjustMemberWallet($member_no, $list['deposit_amount'], 1);
							// $this->Model_member->insert_adjust_wallet_history_02('Deposit-Bank-SCB', $member_no, $list['deposit_amount'], $bal_b4['row']->main_wallet, "");


							// $this->Model_member->Update_member_promo($member_no, 0, 1, $list['deposit_amount'], 'SCB');

							// $this->Model_promo->ClearProOrTurnOver($member_no);
							// $this->Model_member->reset_member_promo_02($member_no);
							$insertdata=['member_no'=>$member_no,'mtype'=>'APPSCB'
							,'to_account'=>$trx['accountNumber'],'from_bank'=>$list['bank_code'],'from_account'=>$list['deposit_acct_number']
							,'amount'=>$list['deposit_amount'],'trx_date'=>$list['date'],'trx_time'=>$list['time']
							,'status'=>1,'raw_content'=>$raw_content,'trx_id'=>$list['trx_id'],'arrive_date'=>Date('Y-m-d H:i:s')];
							$this->db->insert('log_action_queue', $insertdata);
						} 
						// $RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
						// if ($RS1stDepo['num_rows'] <= 0) {
						// 	$this->Model_db_log->insert_1st_deposit($member_no, $list['deposit_amount'], "SCB");
						// }
						if($arr_members['rows_count']<=0||$arr_members['rows_count']>1){ 
							$this->Model_db_log->insert_log_deposit_02($member_no, $list['deposit_amount'], 1, $list['trx_id'], $list['status'], $remark, $list['date'], $list['time'], -1, $balance_before);
							$this->Model_db_log->insert_log_deposit_scb($member_no, $list['trx_id'], $list['date'], $list['time'], $list['deposit_amount'], $list['bank_code'], $list['deposit_acct_number'], $trx['accountNumber'], $list['info'], 1, $list['status']);
						}
					}
				} else {
					$total_dup++;
				}
			} else { // Other -> SCB ##############################################################3
				$processType = 'Other -> SCB';
				if ($remark == '') {
					$remark = 'SCB <= ' . $bank;
				}
				$list['origi_bank'] = 'SCB';


				$RslogDpTRX = $this->Model_db_log->search_log_deposit_trx($member_no, $list['deposit_amount'], $list['date'], $list['time']);
				if ($RslogDpTRX <= 0) {
					$del_date = date("Y-m-d", strtotime("-1 days", strtotime($list['date'] . ' ' . $list['time'])));
					$duplicate_log = $this->Model_db_log->duplicate_log_deposit_scb($member_no, $list['deposit_amount'], $del_date, $list['time']);
					if ($duplicate_log > 0) {
						$RslogDpTRX = $duplicate_log;
						// $list['status'] = 5;
						// $remark = 'SCB delayed'; 
						// file_put_contents('../../../test/duplicate' . $row['remark']['bank'] . $list['deposit_acct_number'] . '.txt', json_encode($row));
						// write_file(FCPATH .'/tesxdata/duplicate/'.$row['remark']['bank'].$list['deposit_acct_number'].'.txt',json_encode($row));
					}
				}
				if ($RslogDpTRX <= 0) { 
					if ($list['status'] == 1) {
						// $bal_b4 = $this->Model_member->SearchMemberWalletBymember_no($member_no);
						// $bal_remain = $this->Model_member->adjustMemberWallet($member_no, $list['deposit_amount'], 1);
						// $this->Model_member->insert_adjust_wallet_history_02('Deposit-Bank-SCB', $member_no, $list['deposit_amount'], $bal_b4['row']->main_wallet, "");


						// $this->Model_member->Update_member_promo($member_no, 0, 1, $list['deposit_amount'], 'SCB');

						// $this->Model_promo->ClearProOrTurnOver($member_no);

						// $this->Model_member->reset_member_promo_02($member_no);
						$insertdata=['member_no'=>$member_no,'mtype'=>'APPSCB'
						,'to_account'=>$trx['accountNumber'],'from_bank'=>$list['bank_code'],'from_account'=>$list['deposit_acct_number']
						,'amount'=>$list['deposit_amount'],'trx_date'=>$list['date'],'trx_time'=>$list['time']
						,'status'=>1,'raw_content'=>$raw_content,'trx_id'=>$list['trx_id'],'arrive_date'=>Date('Y-m-d H:i:s')];
						$this->db->insert('log_action_queue', $insertdata);
					} 

					// $RS1stDepo = $this->Model_db_log->Search1st_depositBymember_no($member_no);
					// if ($RS1stDepo['num_rows'] <= 0) {
					// 	$this->Model_db_log->insert_1st_deposit($member_no, $list['deposit_amount'], "SCB");
					// }
					if($arr_members['rows_count']<=0||$arr_members['rows_count']>1){ 
						$this->Model_db_log->insert_log_deposit_02($member_no, $list['deposit_amount'], 1, $list['trx_id'], $list['status'], $remark, $list['date'], $list['time'], -1, $balance_before);
						$this->Model_db_log->insert_log_deposit_scb($member_no, $list['trx_id'], $list['date'], $list['time'], $list['deposit_amount'], $list['bank_code'], $list['deposit_acct_number'], $trx['accountNumber'], $list['info'], 1, $list['status']);
					}
				}
			}
		}

		$end_dt = Date('Y-m-d H:i:s.u');

		$arr_res = array(
			'bank' => 'SCB-' . $bank_id,
			'acct' => $trx['accountNumber'],
			'start' => $start_dt,
			'end' => $end_dt,
			'total' => $total_count,
			'total_dup' => $total_dup,
			'total_rec' => $total_rec,
			'host' => $_SERVER['HTTP_HOST']
		);

		$json_output = json_encode($arr_res);

		//$this->Model_db_log->insert_log_auto_bank($bank_id,$json_output);

		echo 'Bank       : ' . $arr_res['bank'] . '<BR>';
		echo 'Acct. No.  : ' . $trx['accountNumber'] . '<BR>';
		echo 'Process    : ' . $processType . '<BR>';
		echo 'Start      : ' . $start_dt . '<BR>';
		echo 'End        : ' . $end_dt . '<BR>';
		echo 'Total      : ' . $total_count . '<BR>';
		echo 'Duplicated : ' . $total_dup . '<BR>';
		echo 'New        : ' . $total_rec . '<BR>';
		echo 'Hostname   : ' . $arr_res['host'] . '<BR><BR>';
	}

	private function getBankCode($str_bnk)
	{
		$str_bnk = str_replace("SCBA", "014", $str_bnk);   // ไทยพานิชย์
		$str_bnk = str_replace("SCB", "014", $str_bnk);   // ไทยพานิชย์
		$str_bnk = str_replace("BBLA", "002", $str_bnk);  // กรุงเทพ
		$str_bnk = str_replace("BBL", "002", $str_bnk);   // กรุงเทพ
		$str_bnk = str_replace("KBANK", "004", $str_bnk); // กสิกร
		$str_bnk = str_replace("KBNK", "004", $str_bnk);  // กสิกร
		$str_bnk = str_replace("KTBA", "006", $str_bnk);  // กรุงไทย
		$str_bnk = str_replace("KTB", "006", $str_bnk);   // กรุงไทย
		$str_bnk = str_replace("BAAC", "034", $str_bnk);  // ธกส.
		$str_bnk = str_replace("TMBA", "011", $str_bnk);
		$str_bnk = str_replace("TMB", "011", $str_bnk);
		$str_bnk = str_replace("TTBA", "011", $str_bnk);  // ทหารไทย
		$str_bnk = str_replace("TTB", "011", $str_bnk);   // ทหารไทย
		$str_bnk = str_replace("SEC", "018", $str_bnk);   // 
		$str_bnk = str_replace("CMBT", "022", $str_bnk);  // ซีไอเอ็มบี
		$str_bnk = str_replace("CIMB", "022", $str_bnk);  // ซีไอเอ็มบี
		$str_bnk = str_replace("UOBT", "024", $str_bnk);  // UOB
		$str_bnk = str_replace("UOB", "024", $str_bnk);   // UOB
		$str_bnk = str_replace("BAYA", "025", $str_bnk);  // กรุงศรีฯ
		$str_bnk = str_replace("BAY", "025", $str_bnk);   // กรุงศรีฯ
		$str_bnk = str_replace("GSBA", "030", $str_bnk);  // ออมสิน
		$str_bnk = str_replace("GSB", "030", $str_bnk);   // ออมสิน
		$str_bnk = str_replace("HSBC", "031", $str_bnk);  // HSCB
		$str_bnk = str_replace("TCD", "071", $str_bnk);   //
		$str_bnk = str_replace("LHBA", "073", $str_bnk);  // Land & House
		$str_bnk = str_replace("LH", "073", $str_bnk);    // Land & House
		$str_bnk = str_replace("TBANK", "065", $str_bnk); // ธนชาติ
		$str_bnk = str_replace("TBNK", "065", $str_bnk);  // ธนชาติ
		$str_bnk = str_replace("TISCO", "067", $str_bnk); // ทิสโก้
		$str_bnk = str_replace("KK", "069", $str_bnk);   // เกียรตินาคิน
		$str_bnk = str_replace("KKB", "069", $str_bnk);   // เกียรตินาคิน
		$str_bnk = str_replace("KKP", "069", $str_bnk);   // เกียรตินาคิน
		$str_bnk = str_replace("ISBT", "066", $str_bnk);  // ธ.อิสลาม
		$str_bnk = str_replace("GHBA", "033", $str_bnk);   // ธ.อาคารสงเคราะห์
		$str_bnk = str_replace("GHB", "033", $str_bnk);   // ธ.อาคารสงเคราะห์
		$str_bnk = str_replace("CITI", "017", $str_bnk);  // ซิตี้แบงค์
		$str_bnk = str_replace("ICBCT", "070", $str_bnk); // ICBCT
	  
		return preg_replace('/[^0-9]/', '', $str_bnk);
	}
}
