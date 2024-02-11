<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library("ConfigData");
		$this->load->model('Model_employees');
	}
	public function view() {  
		$this->Getview();
   }
	private function Getview()
	{

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE); 
		$dis = ""; 
		$item['hide_btn'] = $dis; 
		$item['role_all'] =$this->Role->all(); 
		$item['pg_page'] = $this->load->view('view_member', NULL, TRUE); 
		$item['pg_title'] = 'Dashbord'; 
		$this->load->view('view_admin', $item);
	} 
	public function add_new_member(){ //var_dump(can('admin/add_new_member'));exit();
		if(!can('admin/add_new_member')){ 
		$json_arr = array('Message' => false, 'ErrorText' => 'user คุณไม่สามารถจัดการข้อมูลส่วนนี้ได้!', 'boolError' => 1);
		echo json_encode($json_arr);exit();	
		} 
		$inputdata = $this->input->post(null, TRUE);  
		$fname = isset($inputdata['fname']) ? $inputdata['fname'] : NULL;
		$username = isset($inputdata['username']) ? $inputdata['username'] : NULL;
		$password = isset($inputdata['password']) ? $inputdata['password'] : NULL;
		$password = password_hash($password, PASSWORD_DEFAULT);
		$password_change = isset($inputdata['password_change']) ? $inputdata['password_change'] : date('Y-m-d H:i:s', strtotime('-12 month'));
		$level = isset($inputdata['level']) ? $inputdata['level'] : 1; 
		$depositlimit =5000;// isset($inputdata['depositlimit']) ? $inputdata['depositlimit'] : 0;
		$datarows=$this->Model_employees->FindBy('username=',$username);
		if($datarows['num_rows']>0){
			 $json_arr = array('Message' => false, 'ErrorText' => 'ข้อมูลซ้ำไม่สามารถเพิ่มข้อมูลได้!', 'boolError' => 1);
			 echo json_encode($json_arr);exit();	
		 }
		 $datainsert=['username'=>$username,'level'=>$level,'fname'=>$fname,'lname'=>$this->session->Username,'password'=>$password,'password_change'=>$password_change
		             ,'create_at'=>date("Y-m-d H:i:s"),'status'=>1,'deposit_limit'=>$depositlimit];
		 $savedata=$this->Model_employees->Insert($datainsert);
         if($savedata->error_code!=200){
			$json_arr = array('Message' => false, 'ErrorText' => 'ไม่สามารถเพิ่มข้อมูลได้!', 'boolError' => 1);
			 echo json_encode($json_arr);exit();	
		 }
		 $json_arr = array('Message' => true, 'ErrorText' => 'บันทึกข้อมูลส่วนนี้ได้', 'boolError' => 1);
		  echo json_encode($json_arr);exit();	
	}
	public function add_edit_member()
	{
		$inputdata = $this->input->post(null, TRUE);  
		$ids = isset($inputdata['pg_ids']) ? $inputdata['pg_ids'] : 0;
		$fname = isset($inputdata['fname']) ? $inputdata['fname'] : NULL;
		$username = isset($inputdata['username']) ? $inputdata['username'] : NULL;
		$password = isset($inputdata['password']) ? $inputdata['password'] : NULL;
		$password_change = isset($inputdata['password_change']) ? $inputdata['password_change'] : date('Y-m-d H:i:s', strtotime('+1 month')); 
        $depositlimit = isset($inputdata['depositlimit']) ? $inputdata['depositlimit'] : 0; 
		$pg_ids = $this->Model_function->get_decrypt($ids);
		$allow_action=true;
		if ($pg_ids != false) {

            if($username == $this->session->userdata('Username')){$allow_action=false;}else{
				if(can('admin/add_edit_member')){$allow_action=false;}
				if($allow_action&&$username != $this->session->userdata('Username')&&in_array($username,$this->configdata->Userrootlist)){$allow_action=false;}
			  }
			  if($allow_action){ 
				$json_arr = array('Message' => false, 'ErrorText' => 'user คุณไม่สามารถแก้ไขข้อมูลส่วนนี้ได้', 'boolError' => 1);
				echo json_encode($json_arr);exit();	
			 }  
			if ($username == $this->session->userdata('Username')) {
				  $this->session->set_userdata('password_change', $password_change);
				  $this->Model_member->employees_updated($fname, $password, $pg_ids, $password_change,$depositlimit); 
				} else{ 	
				  $this->Model_member->employees_updated($fname, $password, $pg_ids, date('Y-m-d H:i:s', strtotime('-12 month')),$depositlimit);
			    }
		    } else { 
			  $json_arr = array('Message' => false, 'ErrorText' => 'ไม่พบข้อมูล!', 'boolError' => 1);
			  echo json_encode($json_arr);exit();	
		 } 
		 $json_arr = array('Message' => true, 'ErrorText' => 'บันทึกข้อมูลส่วนนี้ได้', 'boolError' => 1);
		 echo json_encode($json_arr);exit();	
	}

	public function list_json()
	{
		$Result = $this->Model_member->ListEmployees();
		$MessageBool = false;
		$dataArr = array();
		if ($Result['num_rows'] > 0) {
			$MessageBool = true;
			$updateadmin = can('admin/view'); 
			if ($updateadmin) {
				$dis_updateadmin = "";	$dis_deleteadmin = "";
			} else {
				$dis_updateadmin = "disabled";$dis_deleteadmin = "disabled";
			} 

			foreach ($Result['result_array'] as $item) {
				$statusChecked = "";
				if ($item['status'] == 1) {
					$statusChecked = "checked";
				}  
				if($item['id']==$this->encryption->decrypt($this->session->userID)){$dis_updateadmin='';}
				$jsonArr = array(
					$item['username'],
					$item['fname'],
					$item['deposit_limit'],
					$item['ip'] . "(lastaccess:{$item['lastaccess']})",
					'<fieldset>
								 	<div class="float-left">
								 		<input ' . $dis_updateadmin . ' type="checkbox" class="switch_status_pggamge" ref-id="' . $this->encryption->encrypt($item['id']) . '" data-on-color="success" data-off-color="danger" ' . $statusChecked . '/>
								 	</div>
								 </fieldset>',
					'<button ' . $dis_updateadmin . ' onclick="EditEmployee(\'' . $this->encryption->encrypt($item['id']) . '\')" class="btn btn-icon btn-info"><i class="la la-key" style="vertical-align: bottom;"></i></button><button ' . $dis_deleteadmin . ' type="button" class="btn btn-icon btn-danger ml-1" onclick="DeleteMainActive(\'' . $this->encryption->encrypt($item['id']) . '\')"><i class="la la-trash-o"></i></button>'
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_json_id()
	{

		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids); 
		$allow_action=false;
		if($pg_ids == $this->encryption->decrypt($this->session->userID)){$allow_action=true;}
		if(can('admin/view')){$allow_action=true;}
		if ($allow_action) {

			$MessageBool = false;
			$dataArr = array();
			if ($pg_ids != false) {
				$MessageBool = true;
				$Result = $this->Model_member->SearchEmployeesByIDs($pg_ids);

				if ($Result['num_rows'] > 0) {
					$MessageBool = true;

					foreach ($Result['result_array'] as $item) {
						$statusChecked = "";
						if ($item['status'] == 1) {
							$statusChecked = "checked";
						}
						$jsonArr = array(
							'username' => $item['username'],
							'fname' => $item['fname'],
							'phash' => '',
							'depositlimit'=>$item['deposit_limit'],
							'deposit_edit_allow'=>(can('admin/add_edit_member')?true:false),
							'level' => $item['level']
						);
						array_push($dataArr, $jsonArr);
					}
				}
			}

			$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
			echo json_encode($json_arr);
		} else {
			$json_arr = array('Message' => false, 'Result' => '');
			echo json_encode($json_arr);
		}
	}


	public function update()
	{    

		$ids = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);
		$allow_action=false;
		if($pg_ids == $this->encryption->decrypt($this->session->userID)){$allow_action=true;}
		if(can('admin/update_employee_status')){$allow_action=true;}

		$pg_status = isset($_POST['pg_status']) ? $_POST['pg_status'] : 0;
		if ($pg_status == "true") {
			$pg_status = 1;
		} else if ($pg_status == "false") {
			$pg_status = 2;
		}
		$BoolMessage = false; 

		if ($pg_ids != false&&$allow_action) {
			$BoolMessage = true;
			$this->Model_member->update_employee_status($pg_ids, $pg_status);
		}
		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}

	public function delete()
	{   
		$ids = isset($_POST['ref_ids']) ? $_POST['ref_ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);
		$allow_action=false;
		if($pg_ids == $this->encryption->decrypt($this->session->userID)){$allow_action=true;}
		if(can('admin/delete')){$allow_action=true;}
		$BoolMessage = false;
		if ($pg_ids != false &&$allow_action) {
			$BoolMessage = true;
			$this->Model_member->delete_employee($pg_ids);
		}
		$json_arr = array('Message' => $BoolMessage);
		echo json_encode($json_arr);
	}
	public function clodesysteme(){
		$data=['maintenance'=>99];
		$this->Model_affiliate->UpdateSettingMSite_03($data);   
		$data=['status'=>2,'updateby'=>$this->session->userdata("Username")];
		$this->db->update('employees', $data);  
		redirect('Logout/index', 'refresh');
    }
}
