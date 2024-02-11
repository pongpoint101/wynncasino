<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Model_sportbook_report");
		$this->load->library("ConfigData");
	}
	public function index()
	{
		exit();
	}
	public function deposit_mannual()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'Reports';
		$item['card_title'] = 'ประวัติการเติมมือ';
		$item['report_type'] = '1';
		$this->load->view('reports/credit_remove_list', $item);
	}
	public function delete_mannual()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'Reports';
		$item['card_title'] = 'ประวัติการลบเครดิต';
		$item['report_type'] = '2';
		$this->load->view('reports/credit_remove_list', $item);
	}
	public function deposit_history_list()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$filter_type_action = isset($_GET['filter_type_action']) ? $_GET['filter_type_action'] : 'all';
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array();

		if ($trx_date_check == true) {
			if ($filter_type_action == 1) {
				$Result = $this->Model_db_log->credit_deposit_mannual_by_date($trx_date);
			} else if ($filter_type_action == 2) {
				$Result = $this->Model_db_log->credit_delete_by_date($trx_date);
			} else if ($filter_type_action == 'all') {
				$Result = $this->Model_db_log->search_log_deposit_by_date($trx_date);
			}

			if ($Result['num_rows'] > 0) {
				foreach ($Result['result_array'] as $item) {
					$manual_deposit = '';
					$remark_internal = (isset($item['remark_internal']) ? $item['remark_internal'] : '');
					$RScc = $this->Model_function->condition_check_status($item['status'], $item['channel'], $item['remark'],$item['remark_internal']);
					if ($item['username'] == NULL) {
						$item['username'] = '<i class="fa fa-times danger"></i>';
					}
					if ($item['status'] == 3) {
						$manual_deposit = 'row_admin_deposit3';
					}
					$rejectClick = "";
					if ($item['status'] != 1 && $item['status'] != 4 && $item['status'] != 3) {
						$rejectClick = '<button class="btn btn-danger btn-sm mt005px" onclick="reject(\'' . $this->encryption->encrypt($item['id']) . '\')">ยกเลิกรายการ</button>';
						$manual_deposit = 'row_admin_deposit4';
					}
					$BtnView = "";
					if ($item['channel'] == 1 || $item['channel'] == 3) {
						$BtnView = '<button onclick="view_fullMsg(\'' . $item['trx_id'] . '\',' . $item['channel'] . ')" class="btn btn-info btn-sm"><i class="la la-search"></i></button>';
					}
					if (isset($item['openration_type'])) {
						if (($item['channel'] <= 3 || $item['channel'] == 5) && in_array($item['openration_type'], $this->configdata->Getgroup_manual('bank'))) {
							$manual_deposit = 'row_admin_deposit1';
						} else if (($item['channel'] > 3 && $item['channel'] != 5) && in_array($item['openration_type'], $this->configdata->Getgroup_manual('bank_orther'))) {
							$manual_deposit = 'row_admin_deposit2';
						} else if (in_array($item['openration_type'], $this->configdata->Getgroup_manual('pro'))) {
							$manual_deposit = 'row_admin_deposit5';
						}
					}

					$jsonArr = array(
						"<center>" . $item['trx_date'] . " " . $item['trx_time'] . "</center>",
						"<center>" . $item['update_date'] . "</center>",
						"<center><a target='_blank' href='" . base_url("member/profile/" . $item['username']) . "'>" . $item['username'] . "</a></center>",
						"<center>" . $item['admin_name'] . "</center>",
						"<center>" . number_format($item['amount'], 2) . "</center>",
						"<center class='" . $RScc['class_text_color'] . "'>" . $RScc['statusText'] . $rejectClick . "</center>",
						$item['remark'] . ':' . $remark_internal,
						$BtnView, $manual_deposit
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function ReportWinloseMember()
	{
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'Reports';
		$item['card_title'] = 'สรุปประวัติการเดิมพัน';
		$item['report_type'] = @$_GET['provi'];
		switch (@$_GET['provi']) {
			case 'filter':
				$item['card_title'] = 'รายละเอียดการเดิมพัน';
				$this->load->view('reports/by_provi/reportmemberplaygame', $item);
				break;
			default:
				$item['card_title'] = 'สรุปประวัติการเดิมพัน';
				$this->load->view('reports/reportwinlosemember', $item);
				break;
		}
	}
	public function Reportwinlosemember_list()
	{
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$member_no = isset($_GET['member_no']) ? $_GET['member_no'] : NULL;
		$res_site=$this->Model_affiliate->list_m_site();
		$site_id=$res_site['row']->site_id;
		$member_no=str_ireplace($site_id,"",$member_no); 
		$timestart = isset($_GET['timestart']) && @$_GET['timestart'] != '' ? $_GET['timestart'] : date('Y-m-d H:i:s');
		$timeend = isset($_GET['timeend']) && @$_GET['timeend'] != '' ? $_GET['timeend'] : date('Y-m-d H:i:s');
		$provider_name = isset($_GET['provider_name']) ? $_GET['provider_name'] : NULL;

		$timestart = strtotime($timestart);
		$timestart = date('Y-m-d H:i:s', $timestart);
		$timeend = strtotime($timeend);
		$timeend = date('Y-m-d H:i:s', $timeend);

		$dataArr = array();
		if ($member_no > 0) {
			$Result = $this->Model_report->ReportWinloseByMember($member_no, $provider_name, $timestart, $timeend);
			foreach ($Result as $item) {
				$jsonArr = [
					$item['provider_name'],
					$item['bet_amount'],
					$item['wl_amount']
				];
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0);
		echo json_encode($json_arr);
	}
	public function Reportmemberplaygame_list()
	{
		if (@$_GET['detail'] == 1) {
			$this->Get_history_detail();
			exit();
		}
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$member_no = isset($_GET['member_no']) ? $_GET['member_no'] : NULL;
		$timestart = isset($_GET['timestart']) && @$_GET['timestart'] != '' ? $_GET['timestart'] : date('Y-m-d H:i:s');
		$timeend = isset($_GET['timeend']) && @$_GET['timeend'] != '' ? $_GET['timeend'] : date('Y-m-d H:i:s');
		$provider_name = isset($_GET['provider_name']) ? $_GET['provider_name'] : NULL;

		$timestart = strtotime($timestart);
		$timestart = date('Y-m-d H:i:s', $timestart);
		$timeend = strtotime($timeend);
		$timeend = date('Y-m-d H:i:s', $timeend);

		$dataArr = array();
		if ($member_no > 0) {
			$Result = $this->Model_report->ReportWinloseProvider($member_no, $provider_name, $timestart, $timeend);
			foreach ($Result as $item) {
				$jj = json_decode($item['json_data']);
				if (isset($jj->operatorId)) {
					unset($jj->operatorId);
				}
				if (isset($jj->operatorToken)) {
					unset($jj->operatorToken);
				}
				if (isset($jj->seamlessKey)) {
					unset($jj->seamlessKey);
				}
				if (isset($jj->operator_token)) {
					unset($jj->operator_token);
				}
				if (isset($jj->secret_key)) {
					unset($jj->secret_key);
				}
				if (isset($jj->site_id)) {
					unset($jj->site_id);
				}
				if (isset($jj->CompanyKey)) {
					unset($jj->CompanyKey);
				}
				if(!isset($jj->bet_details)){
					$jj->bet_details=$item['bet_details'];
				}
				if(!isset($jj->platformCode)){
					$jj->platformCode=$item['platform_code'];
				}else if(!isset($jj->platform_code)){
					$jj->platformCode=$item['platform_code'];
				}
				if(!isset($jj->action)){
					$jj->action=$item['req_type'];
				}
			
				if (isset($jj->updated_time) && @$jj->updated_time > 0) {
					$jj->updated_time = date("Y-m-d H:i:s", ceil(($jj->updated_time / 1000)));
				}
				$trx_type = '';
				if (isset($item['trx_type'])) {
					if ($item['trx_type'] != 0) {
						$trx_type = '-' . $item['trx_type'];
					}
				}
				$jj = json_encode($jj);
				$BtnView = "<button onclick='view_fullMsg(" . json_encode($jj) . ")' class='btn btn-info btn-sm'><i class='la la-search'></i></button>";
				$color = '';
				if ($item['wl_amount'] <= 0) {
					$color = 'color_red';
				} else if ($item['wl_amount'] > 0) {
					$color = 'color_green';
				}
				$jsonArr = [
					$item['id'],
					$item['bet_amount'],
					"<span class='$color'>" . $item['wl_amount'] . "</span>",
					$item['balance_remain'],
					$item['balance_after_settle'],
					$item['bettime'],
					$item['round_id'],
					$item['game_id'],
					$item['req_type'] . $trx_type,
					$BtnView
				];
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0);
		echo json_encode($json_arr);
	}
	public function bet_history_sportbook()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['PGDatenext'] = date('Y-m-d', strtotime("+1 day"));
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'Reports';
		$item['card_title'] = 'รายงานการเดิมพันกี่ฬา sbo';
		$item['report_type'] = '1';
		$item['gamestatus'] = '1';
		$this->load->view('reports/bet_history_list', $item);
	}
	public function bet_history_sbo_list()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date_next = isset($_GET['trx_date_next']) ? $_GET['trx_date_next'] : NULL;
		$gamestatus = isset($_GET['gamestatus']) ? $_GET['gamestatus'] : NULL;
		$member_no = isset($_GET['iduser']) ? $_GET['iduser'] : NULL;
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array();
		$list_filter = [];
		$list_filter_in = [];
		if ($trx_date_check == true) {
			$Result = $this->Model_sportbook_report->search_sportbooksbymember($member_no, $trx_date, $trx_date_next, $gamestatus, $list_filter, $list_filter_in);
			if ($Result['num_rows'] > 0) {
				foreach ($Result['result_array'] as $item) {
					$manual_deposit = '';
					// /$manual_deposit='row_admin_deposit3'; 
					$status = '';
					if ($item['status'] == 1) {
						$status = 'ยังไม่จบ';
					} else if ($item['status'] == 7) {
						$status = 'จบ';
					}
					$end_time = '';
					if ($item['end_time'] != null) {
						$end_time = $item['end_time'];
					}
					$BtnView = "";
					$jsonArr = array(
						"<center>" . $item['member_no'] . "</a></center>",
						"<center>" . $item['bet_time'] . "</center>",
						"<center>" . $end_time . "</center>",
						"<center>" . $item['before_bet_amount'] . "</center>",
						"<center>" . $item['bet_amount'] . "</center>",
						"<center>" . $item['win_amount'] . "</center>", "<center>" . $status . "</center>", $BtnView, $manual_deposit
					);
					array_push($dataArr, $jsonArr);
				}
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	private function Get_history_detail()
	{  
		$inputdata = $this->input->get(null, TRUE);
		$platformcode = isset($inputdata['platformCode']) ? $inputdata['platformCode'] : NULL;
		if ($platformcode == null) {
			$platformcode = isset($inputdata['productId']) ? $inputdata['productId'] : NULL;
		}
		$platformcode = strtoupper($platformcode);
		$roundid = isset($inputdata['roundId']) ? $inputdata['roundId'] : NULL;
		$trxID = isset($inputdata['txnId']) ? $inputdata['txnId'] : NULL;
		if (in_array($platformcode,['PGSLOT','PGSL'])) {
			$trxID = $inputdata['txns'][0]['txnId'];
		}
		if ($trxID == null && isset($inputdata['TransactionId'])) {
			$trxID = $inputdata['TransactionId'];
		}
		$res = [];
		$url = '';
		$bet_single = ['PG', 'PGSL','KA', 'PGSLOT','JILI','SPGS','SPGF','JKR','JKS','SEXYBCRT'];
		$bet_multiple = ['SBO','SABA'];
		$is_loadreport=false;$uri_loadreport='';
		$bet_details=str_replace('https://', 'http://', $inputdata['bet_details']);
		$is_loadreport=is_valid_domain_name($bet_details); 
		try {
			$mplatformcode=strtoupper($platformcode);
			$qt_type=(preg_match('/^QT-/', $mplatformcode)==1)?'QT':'NO_DATA';
			if($qt_type=='QT'){$mplatformcode=$qt_type;}
			switch ($mplatformcode) {//https://callmeback.vslot1688.com/bethistory/jili?wagersID=
				case 'SBO': if($is_loadreport){$url=$bet_details;} break;
				case 'JILI':if($is_loadreport){$url=$bet_details;} break;
				case 'SPGS':
				case 'SPGF':if($is_loadreport){$url=$bet_details;} break;
				case 'JKS':
				case 'JKR':if($is_loadreport){$url=$bet_details;} break;
				case 'KA':if($is_loadreport){$url=$bet_details;} break;
				case 'SEXYBCRT':if($is_loadreport){$url=$bet_details;} break;
				case 'SA':if($is_loadreport){$url=$bet_details;} break;
				case 'QT':if($is_loadreport){$url=$bet_details;} break;
				case 'DGL':if($is_loadreport){$url=$bet_details;} break;
				case 'PG':
				case 'PGSL':
				case 'PGSLOT':
					$url = "https://services.apiplaza.com/app/autosystem/api/PGBetHistoryDirect?trxID=$trxID";
					break;
				case 'SABA':
					$url = "https://services.apiplaza.com/app/autosystem/api/BetRecordDetails?roundid={$roundid}&pvd={$platformcode}";
					break;
				default:
					break;
			}
			if ($url != '') {
				$result = $this->Get_history_curl_detail($url);
				//echo "<pre>";print_r(json_decode($result, true));exit();
				$res_data = json_decode($result, true);
				if (@sizeof($res_data) > 0) {
					if (in_array($platformcode, $bet_single)) { // single
						$res['image_url'] = (isset($res_data['url']) ? $res_data['url'] : '');
					} else {
						$res = $res_data;
					}
				}
			}
		} catch (\Throwable $th) {
			//throw $th;
		}
		$this->ResponeJson($res);
	}
	private function Get_history_curl_detail($url)
	{
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //NO CACHE 
			curl_setopt($ch, CURLOPT_URL, $url);

			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		} catch (\Throwable $th) {
			return [];
		}
	}
	public function ReportSummary()
	{
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'ยอด ฝาก/ถอน (กระเป๋าเงิน)';
		$item['card_title'] = 'ยอด ฝาก/ถอน (กระเป๋าเงิน)';
		$item['report_type'] = @$_GET['provi'];
		$this->load->view('reports/by_provi/reportsumary', $item);
	}
	public function ReportCashback()
	{
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'รายการคืนยอด turnover / เสีย';
		$item['card_title'] = 'รายการคืนยอด turnover / เสีย';
		$item['report_type'] = @$_GET['provi'];
		$this->load->view('cashback/reportcashback', $item);
	}
	public function Reportsummary_list()
	{
		if (@$_GET['detail'] == 1) {
			$this->Get_history_detail();
			exit();
		}
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		// $timestart = isset($_GET['timestart']) && @$_GET['timestart'] != '' ? $_GET['timestart'] : null;
		// $timeend = isset($_GET['timeend']) && @$_GET['timeend'] != '' ? $_GET['timeend'] : null;


		$timestart = $_GET['timestart'] ?? date('Y-m-d', $_GET['timestart']);
		$timeend = $_GET['timeend'] ?? date('Y-m-d', $_GET['timeend']);

		$dataArr = array();
		$Result = $this->Model_report->ReportSummary($timestart, $timeend);
		foreach ($Result as $item) {
			// $jj = json_decode($item['json_data']);

			$jsonArr = array(
				date("d/m/Y", strtotime($item['trx_date'])),
				$item['deposit'] ?? 0,
				$item['withdraw'] ?? 0,

			);
			array_push($dataArr, $jsonArr);
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0);
		echo json_encode($json_arr);
	}
	public function ReportCashback_list()
	{
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$member_no = isset($_GET['member_no']) ? $_GET['member_no'] : NULL;


		$timestart = $_GET['timestart'] ?? date('Y-m-d', $_GET['timestart']);
		$timeend = $_GET['timeend'] ?? date('Y-m-d', $_GET['timeend']);

		$dataArr = array();
		$Result = $this->Model_report->ReportCashback($member_no, $timestart, $timeend);
		// echo json_encode($Result);
		// exit();
		foreach ($Result as $item) {
			$jj = json_decode($item['json_data']);
			// 0:รอรับคืนยอด\r\n1:โอนเข้ากระเป๋าหลักแล้ว\r\n2:หมดอายุ\r\n
			$jsonArr = [
				date("d/m/Y", strtotime($item['date_return'])),
				$item['amount'],
				$item['username'],
				$item['fname'] . ' ' . $item['lname'],
				$item['status'] == 0 ? 'รอรับคืนยอด' : ($item['status'] == 1 ? 'โอนเข้ากระเป๋าหลักแล้ว' : 'หมดอายุ')
			];
			array_push($dataArr, $jsonArr);
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0);
		echo json_encode($json_arr);
	}
	public function ReportSumCashback()
	{
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'สรุปยอด รายการคืนยอดเสีย / turnover';
		$item['card_title'] = 'สรุปยอด รายการคืนยอดเสีย / turnover';
		$item['report_type'] = @$_GET['provi'];
		$this->load->view('cashback/reportsumcashback', $item);
		// var_dump($this->Model_report->ReportSumCashback1('', ''));
	}
	public function ReportSumCashback_list()
	{
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		// $member_no = isset($_GET['member_no']) ? $_GET['member_no'] : NULL;


		$timestart = $_GET['timestart'] ?? date('Y-m-d', $_GET['timestart']);
		$timeend = $_GET['timeend'] ?? date('Y-m-d', $_GET['timeend']);

		$dataArr = array();
		$Result = $this->Model_report->ReportSumCashback1($timestart, $timeend);
		// echo json_encode($Result);
		// exit();
		foreach ($Result as $item) {
			$jj = json_decode($item['json_data']);
			// 0:รอรับคืนยอด\r\n1:โอนเข้ากระเป๋าหลักแล้ว\r\n2:หมดอายุ\r\n
			$jsonArr = [
				date("d/m/Y", strtotime($item['date_return'])),
				$item['data_return'],
				$item['data_return_get']
				// $item['amount']
			];
			array_push($dataArr, $jsonArr);
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0);
		echo json_encode($json_arr);
	}
	public function total_deposit_withdraw(){ 
		$item['PGDateNow'] = date("Y-m-d"); 
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'สรุปยอดฝาก ถอน';

		$this->load->view('total_deposit_withdraw',$item);
	}
	public function list_total_deposit_withdraw(){
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$dataArr = array(); 
		$DataReport = $this->query_deposit_withdraw($trx_date,$trx_date2);
		// $DataReport = $this->queryProByCate($trx_date, $trx_date2, $pro_id);
		if(count($DataReport)>0){
			foreach ($DataReport as $item) {
				$jsonArr = array(
								"<center>" . $item['member_no'] . "</center>",
								"<center>" . number_format($item['deposit'], 2) . "</center>",
								"<center>" . $item['count_deposit'] . "</center>",
								"<center>" . number_format($item['withdraw'], 2) . "</center>",
								"<center>" . $item['count_withdraw'] . "</center>",
								"<center>" . $item['bank']." ".$item['bank_accountnumber'] . "</center>"
								
								);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data'=> $dataArr);
		echo json_encode($json_arr);
	}
	public function query_deposit_withdraw($date1,$date2){
		$DataReport = $this->Model_report->ReportDepositWithdraw($date1, $date2);
		return $DataReport;
	}
	public function win_loss()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'สรุป ยอดแพ้ชนะ';
		$item['card_title'] = 'สรุป ยอดแพ้ชนะ';
		$this->load->view('win_loss', $item);
	}

	public function list_winloss_json()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$dataArr = array();
		$DataReport = $this->queryWinloss($trx_date, $trx_date2);

		if (count($DataReport) > 0) {
			foreach ($DataReport as $item) {
				$jsonArr = array(
					"<center>" . $this->getFullNameMem($item['member_no']) . "</center>",
					"<center>" . $item['member_no'] . "</center>",
					"<center>" . number_format($item['deposit'], 2) . "</center>",
					"<center>" . number_format($item['withdraw'], 2) . "</center>",
					"<center class='" . (($item['deposit'] - $item['withdraw']) < 0 ? 'text-red' : 'text-green') . "' >" . number_format(($item['deposit'] - $item['withdraw']), 2) . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	function getFullNameMem($member_no)
	{
		$name = $this->db->select('fname,lname')->where('id', $member_no)->get('members')->row();
		return $name->fname . ' ' . $name->lname;
	}
	public function queryWinloss($date1, $date2)
	{
		$DataReport = $this->Model_report->ReportWinloss($date1, $date2);
		$d1 = array();
		$d2 = array();
		$data1 = array();
		$data2 = array();
		$check = array(); //array สำหรับการตรวจสอบ
		foreach ($DataReport as $key => $value) {
			$check[$value->member_no]++; //mark array
			if ($check[$value->member_no] == 1) {
				$deposit = $value->tot;
				$deposit_count = $value->count;
				$data1  = array(
					'member_no' => $value->member_no,
					'deposit' => $deposit,
					'deposit_count' => $deposit_count,
				);

				array_push($d1, $data1);
			} elseif ($check[$value->member_no] > 1) {
				$withdown = $value->tot;
				$withdown_count = $value->count;
				// var_dump("รายการ". $value->member_no." ซ้ำกัน<br />");
				$data2  = array(
					'member_no' => $value->member_no,
					'withdraw' => $withdown,
					'withdraw_count' => $withdown_count
				);
				array_push($d2, $data2);
			}
		}

		$keyed = array_column($d2, NULL, 'member_no');

		foreach ($d1 as &$row) {
			if (isset($keyed[$row['member_no']])) {
				$row += $keyed[$row['member_no']];
			}
		}

		if (count($d1) >= count($d2)) {
			$keyed = array_column($d2, NULL, 'member_no');
			foreach ($d1 as &$row) {
				if (isset($keyed[$row['member_no']])) {
					$row += $keyed[$row['member_no']];
				}
			}
			$data = $d1;
		} else {
			$keyed = array_column($d1, NULL, 'member_no');
			foreach ($d2 as &$row) {
				if (isset($keyed[$row['member_no']])) {
					$row += $keyed[$row['member_no']];
				}
			}
			$data = $d2;
		}
		return $data;
	}
	public function deposit_increase()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'ยอดเพิ่มรายการฝาก';

		$this->load->view('total_deposit_increase', $item);
	}
	public function list_deposit_increase_json()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array();
		// if($trx_date_check == true){
		$Result = $this->Model_db_log->search_log_deposit_increase_by_date($trx_date, $trx_date2);
		if ($Result['num_rows'] > 0) {
			foreach ($Result['result_array'] as $item) {
				if ($item['username'] == NULL) {
					$item['username'] = '<i class="fa fa-times danger"></i>';
				}

				$jsonArr = array(
					"<center>" . $item['trx_date'] . "</center>",
					"<center>" . $item['num'] . "</center>",
					"<center>" . number_format($item['amount'], 2) . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		// }

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function pro_cate(){ 
		// $DataReport = $this->queryProByCate('', '', 97);
		// var_dump($DataReport);

		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'สรุปแยกตามโปรโมชั่น';

		$this->load->view('total_pro', $item);
	}
	public function list_pro_cate_json()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$pro_id = isset($_GET['pro']) ? $_GET['pro'] : NULL;
		$dataArr = array();
		$DataReport = $this->queryProByCate($trx_date, $trx_date2, $pro_id);
		if(count($DataReport)>0){
			foreach ($DataReport as $item) {
				$jsonArr = array(
								"<center>" . $item['member_no'] . "</center>",
								"<center>" . number_format($item['deposit'], 2) . "</center>",
								"<center>" . $item['count_deposit'] . "</center>",
								"<center>" . number_format($item['withdraw'], 2) . "</center>",
								"<center>" . $item['count_withdraw'] . "</center>"
								);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function queryProByCate($date1, $date2, $pro_id)
	{
		$DataReport = $this->Model_report->ReportPro($date1, $date2, $pro_id);
		return $DataReport;

	}
	public function get_pro_list()
	{
		$pro = $this->Model_promotion->pro_list();
		$json_arr = array('data' => $pro['row']);
		echo json_encode($json_arr);
	}
	public function user()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'สรุปแยกตามลูกค้า';
		$item['card_title'] = 'สรุปแยกตามลูกค้า';
		$this->load->view('user', $item);
	}

	public function list_user_json()
	{
		// $user = isset($_GET['user']) ? $_GET['user'] : NULL;
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		// $dataArr = array();
		// $DataReport = $this->Model_report->ReportUserGroup($trx_date, $trx_date2,0,49);
		// echo var_dump($DataReport);
		// exit();
		$data = array(
			[
				'0-49',
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,0,49)->mem_count,
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,0,49)->total ?? '0.00'
			],
			[
				'50-99',
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,50,99)->mem_count,
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,50,99)->total ?? '0.00'
			],
			[
				'100-199',
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,100,199)->mem_count,
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,100,199)->total ?? '0.00'
			],
			[
				'200-299',
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,200,299)->mem_count,
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,200,299)->total ?? '0.00'
			],
			[
				'มากกว่าหรือเท่ากับ 300',
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,300,-1)->mem_count,
				$this->Model_report->ReportUserGroup($trx_date, $trx_date2,300,-1)->total ?? '0.00'
			],
		);

		$json_arr = array('data' => $data);
		echo json_encode($json_arr);
	}
	public function list_user2_json()
	{
		$user = isset($_GET['user']) ? $_GET['user'] : NULL;
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$dataArr = array();
		$DataReport = $this->Model_report->ReportUserList($trx_date, $trx_date2,$user);

		if (count($DataReport) > 0) {
			foreach ($DataReport as $i => $item) {
				$jsonArr = array(
					$i+1,
					"<center>" . $item['member_no'] . "</center>",
					"<center>" . $this->getFullNameMem($item['member_no']) . "</center>",
					"<center>" . date('d/m/Y',strtotime($item['update_date'])) . "</center>",
					"<center >" . number_format($item['amount'], 2) . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	function deposit_member(){
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'สรุปการฝากของลูกค้า';
		$item['card_title'] = 'สรุปการฝากของลูกค้า';
		$this->load->view('total_member_deposit', $item);
	}
	public function list_deposit_member_cate_json()
	{
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$pro_id = isset($_GET['pro']) ? $_GET['pro'] : NULL;
		$pro_time = isset($_GET['pro_time']) ? $_GET['pro_time'] : NULL;
		$dataArr = array();
		$DataReport = $this->Model_report->member_deposit_data($trx_date, $trx_date2, $pro_id, $pro_time);
		if (count($DataReport) > 0) {
			foreach ($DataReport as $item) {
				$jsonArr = array(
					"<center>" . $item['userName'] . "</center>",
					"<center>" . $item['telephone'] . "</center>",
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function admin_update_aff()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'ประวัติการแก้ไข Aff';
		$item['card_title'] = 'ประวัติการแก้ไข Aff';
		$item['report_type'] = '1';
		$this->load->view('reports/admin_update_aff', $item);
	}
	public function admin_update_aff_json()
	{
		$this->Model_function->LoginValidation();
		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$length = isset($_GET['length']) ? $_GET['length'] : 0;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$orderARR = isset($_GET['order']) ? $_GET['order'][0] : NULL;
		$date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : NULL;
		$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : NULL;
		$searchName = isset($_GET['search']) ? $_GET['search']['value'] : NULL;

		$Result = $this->Model_report->AdminUpdateAff($date_begin, $date_end,$orderARR,$searchName);
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					"<center>" . (($item['member_no']) ? $item['member_no'] : '') . "</center>",
					"<center>" . (($item['group_af_l1']) ? $item['group_af_l1'] : '') . "</center>",
					"<center>" . (($item['fname']) ? $item['fname'] : 0) . "</center>",
					"<center>" . (($item['create_at']) ? $item['create_at'] : 0) . "</center>",
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => $Result['num_rows'], 'recordsFiltered' => $Result['num_rows']);
		echo json_encode($json_arr);
	} 
	public function admin_update_aff_team()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'ประวัติการแก้ไข สายงานAff';
		$item['card_title'] = 'ประวัติการแก้ไข สายงานAff';
		$item['report_type'] = '1';
		$this->load->view('reports/admin_update_aff_team', $item);
	}
	public function admin_update_aff_team_json()
	{
		$this->Model_function->LoginValidation();
		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$length = isset($_GET['length']) ? $_GET['length'] : 0;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$orderARR = isset($_GET['order']) ? $_GET['order'][0] : NULL;
		$date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : NULL;
		$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : NULL;
		$searchName = isset($_GET['search']) ? $_GET['search']['value'] : NULL;

		$Result = $this->Model_report->AdminUpdateAffTeam($date_begin, $date_end,$orderARR,$searchName);
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					"<center>" . (($item['member_no']) ? $item['member_no'] : '') . "</center>",
					"<center>" . (($item['group_af_l1']) ? $item['group_af_l1'] : '') . "</center>",
					"<center>" . (($item['fname']) ? $item['fname'] : '') . "</center>",
					"<center>" . (($item['create_at']) ? $item['create_at'] : '') . "</center>",
					"<center>" . (($item['operation_type']) ? $item['operation_type'] : '') . "</center>",
					
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => $Result['num_rows'], 'recordsFiltered' => $Result['num_rows']);
		echo json_encode($json_arr);
	} 
	public function admin_update_member()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'ประวัติการแก้ไขข้อมูลลูกค้า';
		$item['card_title'] = 'ประวัติการแก้ไขข้อมูลลูกค้า';
		$item['report_type'] = '1';
		$this->load->view('reports/admin_update_member', $item);
	}
	public function admin_update_member_json()
	{
		$this->Model_function->LoginValidation();
		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$length = isset($_GET['length']) ? $_GET['length'] : 0;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;
		$orderARR = isset($_GET['order']) ? $_GET['order'][0] : NULL;
		$date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : NULL;
		$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : NULL;
		$searchName = isset($_GET['search']) ? $_GET['search']['value'] : NULL;

		$Result = $this->Model_report->AdminUpdateMem($date_begin, $date_end,$orderARR,$searchName);
		$dataArr = array();
		$MessageBool = false;
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			foreach ($Result['result_array'] as $item) {
				if($item['data_before']){
					$data_before = json_encode(json_decode($item['data_before']), JSON_PRETTY_PRINT);
				}else{
					$data_before ='';
				}
				if($item['data_after']){
					$data_after = json_encode(json_decode($item['data_after']), JSON_PRETTY_PRINT);
				}else{
					$data_after ='';
				}
				$jsonArr = array(
					"<center>" . (($item['member_no']) ? $item['member_no'] : '') . "</center>",
					"<p><pre>" . (($item['data_before']) ? $data_before : '') . "</pre></p>",
					"<p><pre>" . (($item['data_after']) ? $data_after : '') . "</pre></p>",
					"<center>" . (($item['fname']) ? $item['fname'] : '') . "</center>",
					"<center>" . (($item['update_date']) ? $item['update_date'] : '') . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr, 'draw' => $draw, 'recordsTotal' => $Result['num_rows'], 'recordsFiltered' => $Result['num_rows']);
		echo json_encode($json_arr);
	} 
	public function top20_total_deposit_withdraw(){ 
		$item['PGDateNow'] = date("Y-m-d"); 
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'สรุปยอดฝาก ถอน';

		$this->load->view('top20_total_deposit_withdraw',$item);
	}
	public function list_top20_total_deposit_withdraw(){
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date2 = isset($_GET['trx_date2']) ? $_GET['trx_date2'] : NULL;
		$dataArr = array(); 
		$DataReport = $this->Model_report->FindAllDataTop20Deposit($trx_date, $trx_date2);
		if(count($DataReport)>0){
			foreach ($DataReport as $item) {
				$jsonArr = array(
								"<center>" . $item['member_no'] . "</center>",
								"<center>" . number_format($item['deposit'], 2) . "</center>",
								"<center>" . number_format($item['withdraw'], 2) . "</center>",
								"<center>" . number_format(($item['deposit']-$item['withdraw']), 2) . "</center>",
								);
				if($item['deposit']>0){
					array_push($dataArr, $jsonArr);
				}
				
			}
		}

		$json_arr = array('data'=> $dataArr);
		echo json_encode($json_arr);
	}
}
