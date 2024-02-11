<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashbord extends MY_Controller { 
	public function __construct() {
		parent:: __construct(); 
		$this->load->library("ConfigData");
	}
	public function view()
	{   
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$item['PGDateNow'] = date("Y-m-d");  
		$item['PGDateTimeNow'] = date("d/m/Y h:i:s");  
		$this->load->view('view_dashbord',$item);
	}

	public function month(){

		$item['select_option_year'] = "";
		$YearStart = 2021;
		$YearNow = date("Y");
		$item['YearNows'] = $YearNow;
		while($YearNow >= $YearStart){
			$item['select_option_year'] .= "<option value='".$YearNow."'>ปี ".$YearNow."</option>";
			$YearNow--;
		}
		$MonthNow = date("n");
		$item['MonthNows'] = $MonthNow;
		$thMonArr =array("0"=>"","1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน",	"7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤษจิกายน","12"=>"ธันวาคม");
		$item['select_option_month'] = "";
		foreach ($thMonArr as $key => $value) {
			if($key != 0){
				$MonthSelect = "";
				if($key == $MonthNow){
					$MonthSelect = "selected";
				}
				$item['select_option_month'] .= "<option value='".$key."' ".$MonthSelect.">".$value."</option>";
			}
		}

		
		$item['deposit_total_all'] = 0;
		$item['withdraw_total_all'] = 0;
		$item['profit_total_all'] = 0;
		$item['profit_percent'] = 0;
		$ResultRM = $this->Model_report->ReportMonth_02($item['YearNows'],$item['MonthNows']);
		//$ResultRM = $this->Model_report->ReportMonth(2021,5);
		if(COUNT($ResultRM) > 0){
			foreach ($ResultRM['result_array'] as $itemRM) {
				$item['deposit_total_all'] += $itemRM['deposit_total'];
				$item['withdraw_total_all'] += $itemRM['withdraw_total'];
			// 		//$item['deposit_total']
			// 		//$item['withdraw_total']
			}
			$deposit_today_100 = ($item['deposit_total_all'] / 100);
			$deposit_today_withdraw_today = ($item['deposit_total_all']-$item['withdraw_total_all']);
			if($deposit_today_100 > 0){
				$item['profit_percent'] = ($deposit_today_withdraw_today/$deposit_today_100);
			}
			$item['profit_total_all'] = ($item['deposit_total_all']-$item['withdraw_total_all']);
		}
		$item['remain'] = 0.00;
		$this->db->select('SUM(main_wallet) AS remain', FALSE);
		$this->db->where('main_wallet >= ', 1);
		$sql_remain = $this->db->get('member_wallet');
		$item_remain = $sql_remain->row();
		if (isset($item_remain)){
			$item['remain'] = $item_remain->remain;
		}  


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$item['PGDateNow'] = date("Y-m-d");


		$this->load->view('view_dashbord_month',$item);
	}

	public function json_data(){
		$MessageBool = false;
		$inputDatePG = isset($_POST['inputDatePG']) ? $_POST['inputDatePG'] : NULL;

		$date_check = $this->Model_function->CheckFormatDate($inputDatePG);
		$dataArr = array();
		if($date_check == true){
			$MessageBool = true;
			$DataReport = $this->Model_report->DashbordDay($inputDatePG);
			$jsonArr = $DataReport;
			array_push($dataArr, $jsonArr);
		}
		
		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}

	public function json_month_data(){
		$pg_year = isset($_GET['pg_year']) ? $_GET['pg_year'] : NULL;
		$pg_month = isset($_GET['pg_month']) ? $_GET['pg_month'] : NULL;
		$draw = isset($_GET['draw']) ? $_GET['draw'] : 1;

		$Result = $this->Model_report->ReportMonth_02($pg_year,$pg_month);

		$dataArr = array();
		
		if(COUNT($Result) > 0){
			foreach ($Result['result_array'] as $item) {
				
				$margin = 0;

				$marginColor = 'success';
				if ($item['profit'] < 0) {

					$margin_d = (floatval($item['withdraw_total']) / 100);
					if($margin_d > 0){
						$margin =  (($item['withdraw_total'] - $item['deposit_total']) / $margin_d * (-1));
					}
			        
			        $marginColor = 'danger';
			    } elseif ($profit = 0) {
			        $margin = 0;
			        $marginColor = 'success';
			    } else {
			    	$margin_d = (floatval($item['deposit_total']) / 100);
					if($margin_d > 0){
						$margin =  ($item['deposit_total'] - $item['withdraw_total']) / $margin_d;
					}
			        
			        $marginColor = 'success';
			    }
			    if ($item['deposit_total'] == 0){
			        $margin = 0;
			    }

			    // if ($margin != 0){
			    //     $month_avg++;
			    // }

			    if ($margin > 0 && $margin < 10){
			        $marginColor = 'warning';
			    }

				$jsonArr = array("<center>".$this->Model_function->ConvertDateShortTH($item['report_date'])."</center>",
								 "<span class='text-right block info'>".number_format($item['deposit_total'], 2)."</span>",
								 "<span class='text-right block danger'>".number_format($item['withdraw_total'], 2)."</span>",
								 "<span class='text-right block ".$marginColor."'>".number_format($item['profit'], 2)."</span>",
								 "<span class='text-right block ".$marginColor."'>".number_format($margin, 2)."</span>");
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data'=> $dataArr, 'draw' => $draw,'recordsTotal' => COUNT($Result), 'recordsFiltered' => COUNT($Result));
		echo json_encode($json_arr);
	}

	public function json_month_all_data(){
		$pg_year = isset($_POST['pg_year']) ? $_POST['pg_year'] : NULL;
		$pg_month = isset($_POST['pg_month']) ? $_POST['pg_month'] : NULL;

		$deposit_total_all = 0;
		$withdraw_total_all = 0;
		$profit_total_all = 0;
		$profit_percent = 0;
		$ResultRM = $this->Model_report->ReportMonth_02($pg_year,$pg_month);
		if(COUNT($ResultRM) > 0){
			foreach ($ResultRM['result_array'] as $itemRM) {
				$deposit_total_all += $itemRM['deposit_total'];
				$withdraw_total_all += $itemRM['withdraw_total'];
			}
			$deposit_today_100 = ($deposit_total_all / 100);
			$deposit_today_withdraw_today = ($deposit_total_all-$withdraw_total_all);
			if($deposit_today_100 > 0){
				$profit_percent = ($deposit_today_withdraw_today/$deposit_today_100);
			}
			$profit_total_all = ($deposit_total_all-$withdraw_total_all);
		}
		$remain = 0.00;
		$this->db->select('SUM(main_wallet) AS remain', FALSE);
		$this->db->where('main_wallet >= ', 1);
		$sql_remain = $this->db->get('member_wallet');
		$item_remain = $sql_remain->row();
		if (isset($item_remain)){
			$remain = $item_remain->remain;
		}  

		$dataArr = array();
		$jsonArr = array('deposit_total_all' => number_format($deposit_total_all,2),
						 'withdraw_total_all' => number_format($withdraw_total_all,2),
						 'remain' => number_format($remain,2),
						 'profit_total_all' =>  number_format($profit_total_all,2)." (".number_format($profit_percent,2)."%)");
		array_push($dataArr, $jsonArr);


		$json_arr = array('Message' => true, 'Result' => $dataArr);
		echo json_encode($json_arr);

	}
}
