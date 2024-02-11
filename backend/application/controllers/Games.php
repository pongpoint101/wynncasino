<?php
class Games extends MY_Controller
{
	public function index()
	{
		exit();
	}

	public function ListPgs()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'ปิด-เปิด ทางเข้าเกมส์ (pgs)';
		$this->load->view('game/view_pgs_list', $item);
	}
	public function ListDataPgs()
	{
		$dataArr = array();
		$Result = $this->Model_Games->FindData();
		if ($Result['num_rows'] > 0) {
			foreach ($Result['result_array'] as $item) {
				$jsonArr = array(
					$item['id'],
					"<center>" . $item['game_code'] . " </center>",
					"<center>" . $item['game_name_eng'] . " </center>",
					"<center>" . $item['game_name_tha'] . " </center>",
					"<select name='PGStatus[]' id='status_" . $item['id'] . "'>
								 	<option " . (($item['status'] == "1") ? 'selected' : '') . " value='1," . $item['id'] . "'>เปิดบริการ</option>
								 	<option " . (($item['status'] == "0") ? 'selected' : '') . " value='0," . $item['id'] . "'>ปิดบริการ</option>
								 </select>",
					"<center>" . $item['update_date'] . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function updatepgs()
	{
		$PGStatus = $this->input->post('PGStatus', TRUE);
		$PGi = 0;
		$boolMessage = false;
		if (COUNT($PGStatus) > 0) {
			$boolMessage = true;
			while ($PGi < COUNT($PGStatus)) {
				$PGItem = explode(",", $PGStatus[$PGi]);
				$status = $PGItem[0];
				$game_id = $PGItem[1];

				$old_data = $this->Model_Games->FindData($game_id);
				if ($old_data['num_rows'] > 0) {
					$new_data = $old_data['row'];
					if ($new_data->status != $status) {
						$new_data->status = $status;
						$new_data->update_date = date("Y-m-d H:i:s");
						$Result = $this->Model_Games->SaveData('pgs', $new_data);
					}
				}
				$PGi++;
			}
		}
		$json_arr = array('Message' => $boolMessage);
		echo json_encode($json_arr);
	}
	public function Listprovidergames()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'เปิด-ปิด ค่ายเกมส์';
 
		if (can('games/Listprovidergames') ) {
			$dis = "";
		} else {
			$dis = "disabled";
		}
		$item['hide_btn'] = $dis;
		$Gamegroup = $this->Model_Games->FindData2(null, true);
		$item['gamegroup'] = $Gamegroup;

		$this->load->view('game/view_providerlistgames', $item);
	}

	public function ListDataprovider()
	{
		$dataArr = array();
		$Result = $this->Model_Games->FindDataProvider(null, true);
		if ($Result['num_rows'] > 0) {
			foreach ($Result['result_array'] as $item) {
				$cate_name = 'ไม่พบข้อมูล';
				if ($item['provider_name'] != '') {
					$cate_name = $item['provider_name'];
				} 
				if (can('games/Listprovidergames') ) {
					$dis = '';
				} else {
					$dis = 'disabled';
				}
				$jsonArr = array(
					$item['provider_id'],
					"<center>" . $item['provider_code'] . " </center>",
					"<center>" . $cate_name . " </center>",
					"<select " . $dis . " name='PGStatus[]' class='PGStatus' id='status_" . $item['id'] . "'>
								 	<option " . (($item['status'] == "1") ? 'selected' : '') . " value='1," . $item['id'] . "'>เปิดบริการ</option>
								 	<option " . (($item['status'] == "0") ? 'selected' : '') . " value='0," . $item['id'] . "'>ปิดบริการ</option>
								 </select>",
					"<center>" . $item['update_date'] . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
	public function updategame()
	{
		$PGStatus = $this->input->post('PGStatus', TRUE);
		$provider = $this->input->post('game_type', TRUE);
		$PGi = 0;
		$boolMessage = false;

		if (can('games/Listprovidergames') ) {
			if (COUNT($PGStatus) > 0) {
				$boolMessage = true;
				while ($PGi < COUNT($PGStatus)) {
					$PGItem = explode(",", $PGStatus[$PGi]);
					$status = $PGItem[0];
					$game_id = $PGItem[1];

					if ($provider == 'provider') {
						$old_data = $this->Model_Games->FindDataProvider($game_id);
						if ($old_data['num_rows'] > 0) { // 
							$new_data = $old_data['row'];
							if ($new_data->status != $status) {
								$new_data->status = $status;
								$new_data->status = $status;
								$new_data->update_date = date("Y-m-d H:i:s");
								$Result = $this->Model_Games->SaveData($provider, $new_data);
							}
						}
					} else if ($provider == 'gamelist') {
						$old_data = $this->Model_Games->FindDatagamelist($game_id);
						if ($old_data['num_rows'] > 0) { // 
							$new_data = $old_data['row'];
							if ($new_data->isActive != $status) {
								$new_data->isActive = $status;
								$new_data->updatedAt = date("Y-m-d H:i:s");
								$Result = $this->Model_Games->SaveData($provider, $new_data);
							}
						} else { // insert
							$old_provi = $this->Model_Games->FindMasterDatagamelist($game_id)['row'];
							$new_data = new stdClass();
							$new_data->platformId = $old_provi->platformId;
							$new_data->game_id = $old_provi->id;
							$new_data->isActive = $status;
							$Result = $this->Model_Games->SaveData($provider, $new_data, true);
						}
					} else if ($provider == 'pgs') {
						$old_data = $this->Model_Games->FindData($game_id);
						if ($old_data['num_rows'] > 0) {
							$new_data = $old_data['row'];
							if ($new_data->status != $status) {
								$new_data->status = $status;
								$new_data->update_date = date("Y-m-d H:i:s");
								$Result = $this->Model_Games->SaveData($provider, $new_data);
							}
						}
					}

					$PGi++;
				}
			}
			$json_arr = array('Message' => $boolMessage);
			echo json_encode($json_arr);
		} else {
			$json_arr = array('Message' => false, 'ErrorText' => 'user คุณเป็น admin ไม่สามารถแก้ไขข้อมูลส่วนนี้ได้', 'boolError' => 1);
			echo json_encode($json_arr);
		}
	}
	public function Listgames()
	{
		$item['PGDateNow'] = date("Y-m-d");
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'เปิด-ปิด เกมส์'; 
		if (can('games/Listprovidergames') ) {
			$dis = "";
		} else {
			$dis = "disabled";
		}
		$item['hide_btn'] = $dis;

		$Gamegroup = $this->Model_Games->FindData2(null, true);
		$item['gamegroup'] = $Gamegroup;

		$this->load->view('game/view_listgames', $item);
	}
	public function ListDatagames()
	{
		$provider = $this->input->get('provider_id', TRUE);
		$dataArr = array();
		$Result = $this->Model_Games->FindData2();
		$Gamelist_Result = $this->Model_Games->FindDatagamelist();
		if (can('games/Listprovidergames') ) {
			$dis='';
		} else {
			$dis='disabled';
		}
		if ($Result['num_rows'] > 0) {
			foreach ($Result['result_array'] as $item) {
				$item['isActive'] = 0;
				if ($provider != '' && $item['platformId'] != $provider) {
					continue;
				}
				foreach ($Gamelist_Result['result_array'] as $v2) {
					if ($item['id'] == $v2['game_id']) {
						$item['updatedAt'] = $v2['updatedAt'];
						$item['isActive'] = $v2['isActive'];
						break;
					}
				}
				$cate_name = 'ไม่พบข้อมูล';
				$gameCode = $item['gameCode'];
				if ($item['slug'] != '') {
					$cate_name = $item['slug'];
				} else if ($item['name'] != '') {
					$cate_name = $item['name'];
				}  
				if($item['gameCode']>0){
					$gameCode=$item['name']."({$item['gameCode']})";
				}
				$jsonArr = array(
					$item['id'],
					"<center>" . $gameCode . " </center>",
					"<center>" . $cate_name . " </center>",
					"<select ".$dis." name='PGStatus[]' class='PGStatus' id='status_" . $item['id'] . "'>
								 	<option " . (($item['isActive'] == "1") ? 'selected' : '') . " value='1," . $item['id'] . "'>เปิดบริการ</option>
								 	<option " . (($item['isActive'] == "0") ? 'selected' : '') . " value='0," . $item['id'] . "'>ปิดบริการ</option>
								 </select>",
					"<center>" . $item['updatedAt'] . "</center>"
				);
				array_push($dataArr, $jsonArr);
			}
		}
		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}
}
