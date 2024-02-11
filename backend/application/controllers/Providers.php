<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Providers extends MY_Controller {
	public function __construct() {
		parent:: __construct(); 
		$this->load->library("ConfigData");
	}
	public function view(){

		
		$item['PGDateNow'] = date("Y-m-d");


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';



		$this->load->view('view_providers',$item);
	}
	public function json_providers_list(){

		$MessageBool = false;
		$dataArr = array();
		$Result = $this->Model_providers->SettingProviders();


		if($Result['num_rows'] > 0){
			$MessageBool = true;

			foreach ($Result['result_array'] as $item) {

				$commission = json_decode($item['config_string'], true);
				$jsonArr = array("<center>".$item['provider_name']." <br/>(".$item['provider_code'].")</center>",
								 "<select name='PGStatus[]' id='status_".$item['id']."'>
								 	<option ". (($item['status'] == "1") ? 'selected' : '') ." value='1,".$item['id']."'>เปิดบริการ</option>
								 	<option ". (($item['status'] == "0") ? 'selected' : '') ." value='0,".$item['id']."'>ปิดปรับปรุงชั่วคราว</option>
								 	<option ". (($item['status'] == "3") ? 'selected' : '') ." value='3,".$item['id']."'>ปิดบริการ</option>
								 </select>",
								 "<center><input style='width: 50px; text-align:right;' type='number' name='casino_comm_".$item['id']."' id='casino_comm_".$item['id']."' value='".$commission['casino_comm']."' /></center>",
								 "<center><input style='width: 50px; text-align:right;' type='number' name='slot_comm_".$item['id']."' id='slot_comm_".$item['id']."' value='".$commission['slot_comm']."' /></center>",
								 "<center><input style='width: 50px; text-align:right;' type='number' name='fishing_comm_".$item['id']."' id='fishing_comm_".$item['id']."' value='".$commission['fishing_comm']."' /></center>",
								 "<center><input style='width: 50px; text-align:right;' type='number' name='sports_comm_".$item['id']."' id='sports_comm_".$item['id']."' value='".$commission['sportsbook_comm']."' /></center>",
								 "<center>".$item['update_date']."</center>");
				array_push($dataArr, $jsonArr);
				

			}
		}

		$json_arr = array('data'=> $dataArr);
		echo json_encode($json_arr);
	}

	public function update_setting(){
		//PGStatus[]
		$PGStatus = isset($_POST['PGStatus']) ? $_POST['PGStatus'] : 0;

		$PGi = 0;
		$boolMessage = false;
		if(COUNT($PGStatus) > 0){
			$boolMessage = true;
			while ($PGi < COUNT($PGStatus)) {

				$PGItem = explode(",", $PGStatus[$PGi]);

				$status = $PGItem[0];
				$provider_id = $PGItem[1];

				$casino_comm = isset($_POST['casino_comm_'.$provider_id]) ? $_POST['casino_comm_'.$provider_id] : 0;
				$slot_comm = isset($_POST['slot_comm_'.$provider_id]) ? $_POST['slot_comm_'.$provider_id] : 0;
				$fishing_comm = isset($_POST['fishing_comm_'.$provider_id]) ? $_POST['fishing_comm_'.$provider_id] : 0;
				$sports_comm = isset($_POST['sports_comm_'.$provider_id]) ? $_POST['sports_comm_'.$provider_id] : 0;
				
				$RSPoviders = $this->Model_providers->SearchSettingProvidersByID($provider_id);
				if($RSPoviders['num_rows'] > 0){
					$config_string = json_decode($RSPoviders['row']->config_string, true);

					$config_string['casino_comm'] = number_format($casino_comm, 2);
					$config_string['slot_comm'] = number_format($slot_comm, 2);
					$config_string['fishing_comm'] = number_format($fishing_comm, 2);
					$config_string['sportsbook_comm'] = number_format($sports_comm, 2);

					//$new_spec = json_encode($config_string);
					//var_dump(json_encode($config_string));
					$this->Model_providers->UpdateSettingProviders($provider_id,$status,json_encode($config_string));


				}



				$PGi++;
			}
		}
		

		$json_arr = array('Message' => $boolMessage);
		echo json_encode($json_arr);
	}

}
?>

