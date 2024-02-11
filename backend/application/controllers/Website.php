<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends MY_Controller {
 
	public function __construct() {
		parent:: __construct(); 
		$this->load->library("ConfigData");
		$this->load->model('Model_game_type');
		$this->load->model('Model_commission');
		$this->load->model('Model_popup');
	}
	public function view()
	{

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$RSSite = $this->Model_affiliate->list_m_site();

		//  min_accept_promo, min_withdraw
		$item['line_at_name'] = "";
		$item['line_at_url'] = "";
		$item['title'] = "";
		$item['text_marquee'] = "";
		$item['line_img_qr'] = "";
		$item['manage_'] = "";
		$item['create_user_by_admin'] = "";
		

		$item['min_aff_claim'] = 0;
		$item['min_comm_claim'] = 0;
		$item['min_cashback_claim'] = 0;
		$item['min_accept_promo'] = 0;
		$item['min_withdraw'] = 0;
		$item['max_auto_withdraw'] = 0;
		if($RSSite['num_rows'] > 0){
			$item['line_at_name'] = $RSSite['row']->line_at_name;
			$item['line_at_url'] = $RSSite['row']->line_at_url;
			$item['line_img_qr'] = $RSSite['row']->line_img_qr;
			$item['title'] = $RSSite['row']->title;
			$item['text_marquee'] = $RSSite['row']->text_marquee;
			$item['maintenance'] = $RSSite['row']->maintenance;
			$item['min_aff_claim'] = $RSSite['row']->min_aff_claim;
			$item['min_cashback_claim'] = $RSSite['row']->min_cashback_claim;
			$item['min_comm_claim'] = $RSSite['row']->min_comm_claim;
			$item['min_accept_promo'] = $RSSite['row']->min_accept_promo;
			$item['min_withdraw'] = $RSSite['row']->min_withdraw;
			$item['max_withdraw'] = $RSSite['row']->max_withdraw;
			$item['max_withdraw_perday'] = $RSSite['row']->max_withdraw_perday;
			$item['min_auto_deposit'] = $RSSite['row']->min_auto_deposit;
			$item['max_auto_withdraw'] = $RSSite['row']->max_auto_withdraw;
			$item['min_lose_lucky_spin_reward'] = $RSSite['row']->min_lose_lucky_spin_reward;
			$item['max_approve_auto_withdraw'] = $RSSite['row']->max_approve_auto_withdraw; 
			$item['truewallet_is_bonus'] = $RSSite['row']->truewallet_is_bonus;
			$item['create_user_by_admin'] = $RSSite['row']->create_user_by_admin;
			$item['truewallet_is_register'] = $RSSite['row']->truewallet_is_register;
		}

		$this->load->view('view_setting_website',$item);
	}
	public function commission(){ 
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$RSSite = $this->Model_affiliate->list_m_site(); 
		$item['com_type'] = $RSSite['row']->com_type;
		$item['com_fix'] = $RSSite['row']->com_fix;   
		$item['pg_title'] = 'ตั้งค่าคอมมิชชั่น';
		$item['comm_rate_range_all'] = $this->Model_commission->CommRange();   
		$item['comm_rate_range_type'] = $this->Model_commission->CommRange('all'); 
		$item['comm_type_all'] = $this->Model_game_type->FindAll2();   
		$this->load->view('website/view_comm_setting',$item);
	}
	public function update_comm(){ 
		$data = $this->input->post(null, TRUE);
		$com_type = isset($data['com_type']) ? $data['com_type'] : NULL; 
		if($com_type!=NULL){ 
					$old_data = $this->Model_affiliate->list_m_site();
					$new_data = $old_data['row'];
					$new_data->com_type=isset($data['com_type']) ? $data['com_type'] : $new_data->com_type;
					$new_data->com_fix=isset($data['com_fix']) ? $data['com_fix'] : $new_data->com_fix; 
					$this->Model_affiliate->UpdateSettingMSite_03($new_data);  
			      if($com_type==2){
				    $old_data = $this->Model_commission->CommRange();   
					foreach ($old_data['result_array'] as $k => $v) {
					  	    $new_data = (object) $v; 
							$new_data->comm_range=isset($data['comm_range_' . $v['id']]) ? $data['comm_range_' . $v['id']] : $new_data->comm_range;
							$new_data->comm_range_start=isset($data['comm_range_start_' . $v['id']]) ? $data['comm_range_start_' . $v['id']] :$new_data->comm_range_start; 
							$new_data->comm_range_end=isset($data['comm_range_end_' . $v['id']]) ? $data['comm_range_end_' . $v['id']] :$new_data->comm_range_end; 
							$this->Model_commission->UpdateCommRange($new_data,$new_data->id);
					} 
			       }else if($com_type==3){
					$res_type=$this->Model_game_type->FindAll2(); 
					foreach ($res_type['result_array']   as $k => $v) {
						$new_type=(object)$v;
						 $old_data = $this->Model_commission->CommRange('all'); 
						 if($data['game_3com_type_' .$v['game_type']]==1){ 
							 foreach ($old_data['result_array']   as $kk => $vv) {
								$new_data = (object) $vv;
								$new_data->comm_range=isset($data['comm3_range_' . $vv['id']]) ? $data['comm3_range_' . $vv['id']] : $new_data->comm_range; 
								$new_data->comm_range_start=isset($data['comm3_range_start_' . $vv['id']]) ? $data['comm3_range_start_' . $vv['id']] :$new_data->comm_range_start; 
								$new_data->comm_range_end=isset($data['comm3_range_end_' . $vv['id']]) ? $data['comm3_range_end_' . $vv['id']] :$new_data->comm_range_end; 
								$this->Model_commission->UpdateCommRange($new_data,$new_data->id); 
								$new_type->com_type=1;
								$this->Model_commission->UpdateCommGameFixType($new_type,$new_type->id);
							 } 
						     }else if($data['game_3com_type_' .$v['game_type']]==2){
								$new_data = (object) $v; 
								$new_data->com_type=2;
								$new_data->com_fix_bygame=isset($data['com_3fix_bygame_' . $v['id']]) ? $data['com_3fix_bygame_' . $v['id']] : $new_data->com_fix_bygame;
							    $this->Model_commission->UpdateCommGameFixType($new_data,$new_data->id);
						   }
					}
					

				   }else if($com_type==4){ 
					 $old_data = $this->Model_game_type->FindAll2();   
					 foreach ($old_data['result_array'] as $k => $v) {
						$new_data = (object) $v; 
						$new_data->com_fix_bygame=isset($data['com_fix_bygame_' . $v['id']]) ? $data['com_fix_bygame_' . $v['id']] : $new_data->com_fix_bygame;
						$this->Model_commission->UpdateCommGameFixType($new_data,$new_data->id);
					 }  
			  }
			  $json_arr = array('Message' => true);
		      echo json_encode($json_arr);
		}
	}
	public function Cashback(){
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$RSSite = $this->Model_affiliate->list_m_site(); 
		$item['cashback_rate'] = $RSSite['row']->cashback_rate;   
		$item['cashback_max_payout'] = $RSSite['row']->cashback_max_payout;    
		$item['pg_title'] = 'ตั้งค่าคืนยอดเสีย'; 
		$this->load->view('website/view_Cashback',$item);	
	}
	public function update_Cashback(){
		$data = $this->input->post(null, TRUE);
		$old_data = $this->Model_affiliate->list_m_site();
		$new_data = $old_data['row'];
		$new_data->cashback_rate=isset($data['cashback_rate']) ? $data['cashback_rate'] : $new_data->cashback_rate; 
		$new_data->cashback_max_payout=isset($data['cashback_max_payout']) ? $data['cashback_max_payout'] : $new_data->cashback_max_payout; 
		$t=$this->Model_affiliate->UpdateSettingMSite_03($new_data);   
		$json_arr = array('Message' => true);
		if(!$t){ $json_arr = array('Message' => false,'ErrorText'=>'บันทึกข้อมูลไม่สำเร็จ'); } 
		echo json_encode($json_arr);
	}
	public function update_m_site_01(){

		$line_at_name = isset($_POST['line_at_name']) ? $_POST['line_at_name'] : NULL;
		$line_at_url = isset($_POST['line_at_url']) ? $_POST['line_at_url'] : NULL;
		$title = isset($_POST['title']) ? $_POST['title'] : NULL;
		$text_marquee = isset($_POST['text_marquee']) ? $_POST['text_marquee'] : NULL;
		$maintenance = isset($_POST['maintenance']) ? $_POST['maintenance'] : 1;
		$truewallet_is_bonus = isset($_POST['truewallet_is_bonus']) ? $_POST['truewallet_is_bonus'] : 0;
		$create_user_by_admin = isset($_POST['create_user_by_admin']) ? $_POST['create_user_by_admin'] : 3;
		$truewallet_is_register = isset($_POST['truewallet_is_register']) ? $_POST['truewallet_is_register'] : 1;
		$data=['line_at_name'=>$line_at_name,'line_at_url'=>$line_at_url,'title'=>$title,'text_marquee'=>$text_marquee,'maintenance'=>$maintenance
	          ,'truewallet_is_bonus'=>$truewallet_is_bonus, 'create_user_by_admin' => $create_user_by_admin,'truewallet_is_register'=>$truewallet_is_register];
		$this->Model_affiliate->UpdateSettingMSite_03($data); 
		$json_arr = array('Message' => true);
		echo json_encode($json_arr);
	}

	public function update_m_site_02(){


		$min_aff_claim = isset($_POST['min_aff_claim']) ? $_POST['min_aff_claim'] : 0;
		$min_comm_claim = isset($_POST['min_comm_claim']) ? $_POST['min_comm_claim'] : 0;
		$min_cashback_claim = isset($_POST['min_cashback_claim']) ? $_POST['min_cashback_claim'] : 0;
		$min_accept_promo = isset($_POST['min_accept_promo']) ? $_POST['min_accept_promo'] : 0;
		$min_withdraw = isset($_POST['min_withdraw']) ? $_POST['min_withdraw'] : 0;
		$max_withdraw = isset($_POST['max_withdraw']) ? $_POST['max_withdraw'] : 100000;
		$max_withdraw_perday = isset($_POST['max_withdraw_perday']) ? $_POST['max_withdraw_perday'] : 10;
		$max_auto_withdraw = isset($_POST['max_auto_withdraw']) ? $_POST['max_auto_withdraw'] : 0;
		$min_auto_deposit = isset($_POST['min_auto_deposit']) ? $_POST['min_auto_deposit'] : 0;
		$min_lose_lucky_spin_reward = isset($_POST['min_lose_lucky_spin_reward']) ? $_POST['min_lose_lucky_spin_reward'] : 0;
        $max_approve_auto_withdraw = isset($_POST['max_approve_auto_withdraw']) ? $_POST['max_approve_auto_withdraw'] : 0;

        $data=['min_aff_claim'=>$min_aff_claim,'min_comm_claim'=>$min_comm_claim,'min_cashback_claim'=>$min_cashback_claim,'min_accept_promo'=>$min_accept_promo
	          ,'min_withdraw'=>$min_withdraw,'max_withdraw'=>$max_withdraw,'max_withdraw_perday'=>$max_withdraw_perday,'max_auto_withdraw'=>$max_auto_withdraw,'min_auto_deposit'=>$min_auto_deposit
			  ,'min_lose_lucky_spin_reward'=>$min_lose_lucky_spin_reward,'max_approve_auto_withdraw'=>$max_approve_auto_withdraw];
		$this->Model_affiliate->UpdateSettingMSite_03($data);
		$json_arr = array('Message' => true);
		echo json_encode($json_arr);
	}


	public function affsetting()
	{ $this->Model_function->LoginValidation();
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Affiliate';

		$RSSite = $this->Model_affiliate->list_m_site();
		$item['comm_type_all'] = $this->Model_game_type->FindAll_aff_fix_byprovider();   
		$item['aff_comm_level_1'] = 0;
		$item['aff_comm_level_2'] = 0;
		$item['aff_rate1'] = 0;
		$item['aff_rate2'] = 0;
		$item['aff_type'] = 1;
		$old_data = $this->Model_affiliate->list_m_site();
		$new_data = $old_data['row']; 
		$item['aff_type']=$new_data->aff_type; 
		if ($RSSite['num_rows'] > 0) {
			$item['aff_comm_level_1'] = $RSSite['row']->aff_comm_level_1;
			$item['aff_comm_level_2'] = $RSSite['row']->aff_comm_level_2;
			$item['aff_rate1'] = $RSSite['row']->aff_rate1;
			$item['aff_rate2'] = $RSSite['row']->aff_rate2;
			
			
		}
      
		$this->load->view('view_affiliate_setting', $item);
	}
	public function update_aff_site()
	{
		$data = $this->input->post(null, TRUE);
		$this->Model_function->LoginValidation();

		$old_data = $this->Model_affiliate->list_m_site();
		$new_data = $old_data['row']; 
		$new_data->aff_type=isset($data['aff_type']) ? $data['aff_type'] : $new_data->aff_type; 
		$this->Model_affiliate->UpdateSettingMSite_03($new_data);  

		if(@$_POST['aff_type']==1){
			$aff_comm_level_1 = isset($_POST['aff_comm_level_1']) ? $_POST['aff_comm_level_1'] : 0;
			$aff_comm_level_2 = isset($_POST['aff_comm_level_2']) ? $_POST['aff_comm_level_2'] : 0;
			$aff_rate1 = isset($_POST['aff_rate1']) ? $_POST['aff_rate1'] : 0;
			$aff_rate2 = isset($_POST['aff_rate2']) ? $_POST['aff_rate2'] : 0;
			$this->Model_affiliate->UpdateSettingMSite($aff_comm_level_1, $aff_comm_level_2, $aff_rate1, $aff_rate2);
			$json_arr = array('Message' => true);
		}else if(@$_POST['aff_type']==4){
			$rows=$this->Model_game_type->FindAll_aff_fix_byprovider();   
			foreach ($rows['result_array'] as $k => $v) {
				$new_data = (object) $v;
				$new_data->fix_bygame1=isset($data['fix_bygame1_' . $v['id']]) ? $data['fix_bygame1_' . $v['id']] : $new_data->fix_bygame1;
				$new_data->fix_bygame2=isset($data['fix_bygame2_' . $v['id']]) ? $data['fix_bygame2_' . $v['id']] : $new_data->fix_bygame2;
				$this->Model_affiliate->UpdateSettingMSiteBygame($new_data);
			} 
			$json_arr = array('Message' => true);
		}
		
		echo json_encode($json_arr);
	}

	public function UploadImage(){
		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
		$old_data = $this->Model_affiliate->list_m_site();

		$filename = $_FILES['image_field']['name'];
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
		$imageFileType = strtolower($imageFileType);  
		 
		$file_new_name='line_qr_'.uniqid();
		$new_old_data=$old_data['row']; 
		$file_old_name=$new_old_data->line_img_qr; 

		$new_data=new stdClass();
		$new_data->site_id=$new_old_data->site_id;
		$new_data->line_img_qr=$file_new_name.'.'.$imageFileType; 
		//unset($new_data->id); // remove id   
		$res_save=$this->Model_affiliate->EditById($new_data);  
		if($res_save->error_code!=200){ exit();}  
		$handle = new \Verot\Upload\Upload($_FILES['image_field']);
		if ($handle->uploaded) {
		    $handle->allowed = array('image/*'); 
			$handle->image_resize = true; 
			$handle->image_x =450; 
			$handle->image_ratio_y = true;
			$handle->forbidden = array('application/*'); 
			$handle->file_new_name_body   =$file_new_name;  
			$handle->process('./images/web');
			if ($handle->processed) {
				@unlink('./images/web/'.$file_old_name); 
				$handle->clean();
			   } else {
				echo 'error : ' . $handle->error;
		   }
		}
	 } 
	 public function home(){ 
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$RSSite = $this->Model_affiliate->list_m_site(); 
		$item['com_type'] = $RSSite['row']->com_type;
		$item['com_fix'] = $RSSite['row']->com_fix;   
		$item['pg_title'] = 'ตั้งค่าหน้าหลัก';
		$item['comm_rate_range_all'] = $this->Model_commission->CommRange();   
		$item['comm_rate_range_type'] = $this->Model_commission->CommRange('all'); 

		$pic = $this->Model_banner->get_pic_by_type(1);
		$item['pic'] = $pic; 
		$pic_m = $this->Model_banner->get_pic_by_type(2);
		$item['pic_mb'] = $pic_m; 
		

		// $item['comm_type_all'] = $this->Model_game_type->FindAll2();   
		$this->load->view('website/view_home_setting',$item);
	}
	public function add_pic_pc(){ 
		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
		$json_arr = array('status' => 'error', 'message' => '', 'action' => '', 'id' => '', 'res' =>'');
		$id = $_POST['id']; 

		if($id){
			//edit
			$old_data = $this->Model_banner->get_pic_old($id);
			$filename = $_FILES['file']['name']; 
			$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
			$imageFileType = strtolower($imageFileType);  

			$file_new_name='banner_pc_'.uniqid();
			
			$new_old_data=$old_data['row']; 
			$file_old_name=$new_old_data->name; 

			$new_data=new stdClass();
			$new_data->id = $new_old_data->id;
			$new_data->name = $file_new_name.'.'.$imageFileType; 
			//unset($new_data->id); // remove id   
			$res_save=$this->Model_banner->EditById($new_data);  
			if($res_save->error_code!=200){ exit();}  
			
			$handle = new \Verot\Upload\Upload($_FILES['file']);

			if ($handle->uploaded) {
				$handle->allowed = array('image/*'); 
				$handle->image_resize = false; 
				$handle->image_x =450; 
				$handle->image_ratio_y = true;
				$handle->forbidden = array('application/*'); 
				$handle->file_new_name_body   =$file_new_name;  
				$handle->process('./images/banner');
				
				if ($handle->processed) {
					@unlink('./images/banner/'.$file_old_name); 
					$json_arr = array('status' => 'success', 'message' => 'edit succesfully', 'action' => 'edit', 'id' => $id, 'res' =>  $file_new_name.'.'.$imageFileType);
					$handle->clean();
				} else {
					echo 'error : ' . $handle->error;
				}
			}
		}else{
			//add
			$filename = $_FILES['file']['name']; 
			$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
			$imageFileType = strtolower($imageFileType);  

			$file_new_name='banner_pc_'.uniqid();

			$this->Model_banner->banner_insert($file_new_name.'.'.$imageFileType, 1);
			$handle = new \Verot\Upload\Upload($_FILES['file']);
			if ($handle->uploaded) {
				$handle->allowed = array('image/*'); 
				$handle->image_resize = false; 
				$handle->image_x =450; 
				$handle->image_ratio_y = true;
				$handle->forbidden = array('application/*'); 
				$handle->file_new_name_body   =$file_new_name;  
				$handle->process('./images/banner');
				
				if ($handle->processed) {
					$json_arr = array('status' => 'success', 'message' => 'save succesfully', 'action' => 'add', 'id' => '', 'res' =>  $file_new_name);
					$handle->clean();
				} else {
					echo 'error : ' . $handle->error;
				}
			}
		}
		
		echo json_encode($json_arr);
		
	}
	public function delete_pic_pc(){ 
		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
		$json_arr = array('status' => 'error', 'message' => '', 'id' => '');
		$id = $_POST['id']; 

		if($id){
			//edit
			$old_data = $this->Model_banner->get_pic_old($id);
			$new_old_data=$old_data['row']; 
			$file_old_name=$new_old_data->name; 
			
			if($file_old_name){
				$del=$this->Model_banner->delete_banner($id);  
				if($del->error_code!=200){ exit();}  
				@unlink('./images/banner/'.$file_old_name); 
				$json_arr = array('status' => 'success', 'message' => 'delete succesfully', 'id' => $id);
			}
			
		}
		
		echo json_encode($json_arr);
		
	}
	public function delete_pic_mb(){ 
		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
		$json_arr = array('status' => 'error', 'message' => '', 'id' => '');
		$id = $_POST['id']; 

		if($id){
			//edit
			$old_data = $this->Model_banner->get_pic_old($id);
			$new_old_data=$old_data['row']; 
			$file_old_name=$new_old_data->name; 
			
			if($file_old_name){
				$del=$this->Model_banner->delete_banner($id);  
				if($del->error_code!=200){ exit();}  
				@unlink('./images/banner/'.$file_old_name); 
				$json_arr = array('status' => 'success', 'message' => 'delete succesfully', 'id' => $id);
			}
			
		}
		
		echo json_encode($json_arr);
		
	}
	public function add_pic_mb(){ 
		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
		$json_arr = array('status' => 'error', 'message' => '', 'action' => '', 'id' => '', 'res' =>'');
		$id = $_POST['id']; 

		if($id){
			//edit
			$old_data = $this->Model_banner->get_pic_old($id);
			$filename = $_FILES['file']['name']; 
			$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
			$imageFileType = strtolower($imageFileType);  

			$file_new_name='banner_mb_'.uniqid();
			
			$new_old_data=$old_data['row']; 
			$file_old_name=$new_old_data->name; 

			$new_data=new stdClass();
			$new_data->id = $new_old_data->id;
			$new_data->name = $file_new_name.'.'.$imageFileType; 
			//unset($new_data->id); // remove id   
			$res_save=$this->Model_banner->EditById($new_data);  
			if($res_save->error_code!=200){ exit();}  
			
			$handle = new \Verot\Upload\Upload($_FILES['file']);

			if ($handle->uploaded) {
				$handle->allowed = array('image/*'); 
				$handle->image_resize = false; 
				$handle->image_x =450; 
				$handle->image_ratio_y = true;
				$handle->forbidden = array('application/*'); 
				$handle->file_new_name_body   =$file_new_name;  
				$handle->process('./images/banner');
				
				if ($handle->processed) {
					@unlink('./images/banner/'.$file_old_name); 
					$json_arr = array('status' => 'success', 'message' => 'edit succesfully', 'action' => 'edit', 'id' => $id, 'res' =>  $file_new_name.'.'.$imageFileType);
					$handle->clean();
				} else {
					echo 'error : ' . $handle->error;
				}
			}
		}else{
			//add
			$filename = $_FILES['file']['name']; 
			$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
			$imageFileType = strtolower($imageFileType);  

			$file_new_name='banner_mb_'.uniqid();

			$this->Model_banner->banner_insert($file_new_name.'.'.$imageFileType, 2);
			$handle = new \Verot\Upload\Upload($_FILES['file']);
			if ($handle->uploaded) {
				$handle->allowed = array('image/*'); 
				$handle->image_resize = false; 
				$handle->image_x =450; 
				$handle->image_ratio_y = true;
				$handle->forbidden = array('application/*'); 
				$handle->file_new_name_body   =$file_new_name;  
				$handle->process('./images/banner');
				
				if ($handle->processed) {
					$json_arr = array('status' => 'success', 'message' => 'save succesfully', 'action' => 'add', 'id' => '', 'res' =>  $file_new_name);
					$handle->clean();
				} else {
					echo 'error : ' . $handle->error;
				}
			}
		}
		
		echo json_encode($json_arr);
		
	}
	public function popuplist(){
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);  
		$item['pg_title'] = 'รายการป๊อปอัพ';
		$datapopup = $this->Model_popup->Getall(); 
		$item['listitem'] = $datapopup['result_array'];  
		$this->load->view('website/view_popup_list_setting',$item);
  }	
 public function popup(){ 
	    $data = $this->input->get(null, TRUE);
        $item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);  
		$item['pg_title'] = 'ตั้งค่าป๊อปอัพ';$item['m_order'] ='';$item['status'] ='';   
		$item['message'] = '';$item['images'] = '';$item['pop_style'] ='';  
		$item['position'] ='';$item['pop_start'] = '';$item['pop_expired'] = '';  
		$datapopup = $this->Model_popup->findById(@$data['id']);
        if(isset($datapopup['row'])){ 
			$datapopup=$datapopup['row'];
			$item['message'] = $datapopup->message;  
			$item['images'] = $datapopup->images; 
			$item['pop_style'] = $datapopup->pop_style; 
			$item['position'] = $datapopup->position; 
			$item['pop_start'] = $datapopup->pop_start; 
			$item['pop_expired'] = ($datapopup->pop_expired!=null&&$datapopup->pop_expired!=''?$datapopup->pop_expired:date('Y-m-d H:i', strtotime('+10 year', strtotime(date("Y-m-d H:i:s"))))); 
			$item['m_order'] = $datapopup->m_order;  
			$item['status'] = $datapopup->status;  
			$item['link_popup'] = $datapopup->link_popup; 
			$item['open_style_popup'] = ($datapopup->open_style_popup==''||$datapopup->open_style_popup==null)?'_blank':$datapopup->open_style_popup;
			$item['popup_link_name'] = $datapopup->popup_link_name;  
		 }  
		$this->load->view('website/view_popup_setting',$item);
 }
 public function save_popup(){ 
	  require dirname(__FILE__). '/../../private69/vendor/autoload.php';  
	  $data = $this->input->post(null, TRUE);
	  $gdata = $this->input->get(null, TRUE);
	  if(isset($gdata['morder'])){ 
		  foreach ($data['list'] as $k => $v) { 
			$updatedata =['m_order' => $v['popup_order']]; 
			$this->db->where('id', $v['popup_id']);
			$this->db->update('popup', $updatedata);
		  }
		 $json_arr = array('status' => 'success', 'message' => 'save succesfully');
		 echo json_encode($json_arr);
		 exit();
	  }
	  $json_arr = array('status' => 'error', 'message' => 'save error');
	 if(isset($data['id'])){
		 $old_data = $this->Model_popup->findById($data['id']);
        
		$filename = @$_FILES['popup_img']['name'];
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
		$imageFileType = strtolower($imageFileType);  
		 
		$file_new_name='popup_img_'.uniqid();
		$new_old_data=$old_data['row']; 
		$file_old_name=$new_old_data->images; 
		
		$new_data=new stdClass();  
		$new_data=$new_old_data;
		$new_data->message = isset($data['message']) ? $data['message'] : $new_old_data->message;
		$new_data->images =  (isset($_FILES['popup_img']['name'])) ? $file_new_name.'.'.$imageFileType : $new_old_data->images;
		$new_data->pop_style = isset($data['pop_style']) ? $data['pop_style'] : $new_old_data->pop_style;
		$new_data->position = isset($data['position']) ? $data['position'] : $new_old_data->position;
		$new_data->m_order = isset($data['m_order']) ? $data['m_order'] : $new_old_data->m_order;
		$new_data->pop_start = isset($data['pop_start']) ? $data['pop_start'] : $new_old_data->pop_start;
		$new_data->pop_expired = isset($data['pop_expired']) ? $data['pop_expired'] : $new_old_data->pop_expired;
		$new_data->status = isset($data['status']) ? $data['status'] : $new_old_data->status;
		$new_data->popup_link_name = isset($data['popup_link_name']) ? $data['popup_link_name'] : $new_old_data->popup_link_name;
		$new_data->update_at=date('Y-m-d H:i:s');  
		$new_data->open_style_popup = isset($data['open_style_popup']) ? $data['open_style_popup'] : $new_old_data->open_style_popup;
		if(strlen(trim(@$data['link_popup']))>10){
			$new_data->link_popup = isset($data['link_popup']) ? $data['link_popup'] : $new_old_data->link_popup;   
		}else{
			$new_data->link_popup=null;  
		} 	
		//unset($new_data->id); // remove id   
		$res_save=$this->Model_popup->EditById($new_data);  
		if($res_save->error_code!=200){ exit();}  
		$handle = new \Verot\Upload\Upload(@$_FILES['popup_img']);
		if ($handle->uploaded&&$res_save->error_code==200) {
		    $handle->allowed = array('image/*'); 
			$handle->image_resize = true; 
			$handle->image_x =540; 
			$handle->image_ratio_y = true;
			$handle->forbidden = array('application/*'); 
			$handle->file_new_name_body   =$file_new_name;  
			$handle->process('./images/web');
			if ($handle->processed) {
				@unlink('./images/web/'.$file_old_name); 
				$json_arr = array('status' => 'success', 'message' => 'save succesfully');
				$handle->clean();
			    } else {
				echo 'error : ' . $handle->error;
				$json_arr = array('status' => 'error', 'message' => 'save error');
		       }
		      }else if($res_save->error_code==200){
				$json_arr = array('status' => 'success', 'message' => 'save succesfully');
		   }   
	    } 
		echo json_encode($json_arr);
 }
 public function changepassword()
	{
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'เเก้ไขรหัสผ่าน';
		$this->load->view('other/changepassword', $item);
	}

	function savechangepassword()
	{
	 $inputdata = $this->input->post(null, TRUE);
	 $password = isset($inputdata['password']) ? $inputdata['password'] : NULL;
	 // $this->session->userdata('Username');
   
	 date_default_timezone_set('Asia/Bangkok');
	 $password_change = date('Y-m-d H:i:s', strtotime('+1 month'));
	 $GenUserPass = password_hash($password, PASSWORD_DEFAULT);
   
	 $id = $this->Model_function->get_decrypt($this->session->userdata('id'));
	 try {
	   $this->session->set_userdata('password_change', $password_change);
	   $this->db->set('password', $GenUserPass);
	   $this->db->set('password_change', $password_change);
	   $this->db->where('id', $id);
	   $edit = $this->db->update('employees');
   
	  if (!$edit) {
	   $json_arr = array('Message' => false, 'ErrorText' => 'บันทึกข้อมูลส่วนนี้ไม่สำเร็จ', 'boolError' => 1);
	   echo json_encode($json_arr);
	   exit();
	  }else{
	   $json_arr = array('Message' => true, 'ErrorText' => 'บันทึกข้อมูลส่วนนี้สำเร็จ!', 'boolError' => 1);
	   echo json_encode($json_arr);
	   exit();
	  }
   
	 } catch (Exception $e) {
	  return $e->getMessage();
	 }
	}
	  
}
