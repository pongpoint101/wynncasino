<?php
class Promotion extends MY_Controller
{
	public function index()
	{
		exit();
	}

	public function add_credit_manual()
	{   $this->load->model('Model_log_deposit');
		$BoolMessage = false;
		$boolError = 1;
		$MessageErrorText = 'Error!';
		$member_no = isset($_POST['pg_ids']) ? $_POST['pg_ids'] : 0;
		$subpro_id = isset($_POST['subpro_id']) ? $_POST['subpro_id'] : NULL;

		$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
		$amount_org=$amount;
		$turnover_amount = isset($_POST['turnover_amount']) ? $_POST['turnover_amount'] : 0;
		$remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;
		$remarkOption = isset($_POST['remark-option']) ? $_POST['remark-option'] : -1;

		$data_deposit = [];$insert_promotion=[];$pro_id='';
		$member_last_deposit = '';
		$datamsite= $this->Model_affiliate->list_m_site();    
		$skipChkBalance = $datamsite['row']->min_auto_deposit;
		$promo_id = $remarkOption;
		$channel = $promo_id;
		$deposit_id='';
		$member_no = $this->Model_function->get_decrypt($member_no);
		$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);

		switch ($remarkOption) {
			case 0:
				$remark = 'other'; // No promo
				break;
			case 2:
				$remark = "ยอดข้าม";
				$channel = 1;
				$member_last_deposit = "1";
				break;
			case 4:
				$remark = 'SMS Delayed';
				$channel = 3;
				$member_last_deposit = "1";
				break;
			case 5:
				$remark = 'ไม่พบบัญชี-SCB-Bank';
				$channel = 1;
				$member_last_deposit = "1";
				break;
			case 6:
				$remark = 'ไม่พบบัญชี-SCB-SMS';
				$channel = 3;
				$member_last_deposit = "1";
				break;
			case 7:
				$remark = 'ไม่พบบัญชี-KBank-Bank';
				$channel = 1;
				$member_last_deposit = "1";
				break;
			case 1:
				$remark = 'ไม่พบบัญชี-KBank-SMS';
				$channel = 3;
				$member_last_deposit = "1";
				break;
		}

		if ($RSM_Wallet['num_rows'] > 0) {
			$BoolMessage = true;
			$datapro = $this->Model_promotion->FindById($promo_id);

			if ($datapro['num_rows'] > 0) {
				$pro_deposit_start_amount = $datapro['row']->pro_deposit_start_amount;
				$skipChkBalance = $datapro['row']->pro_chekbalance;
				$pro_id=$datapro['row']->pro_id;
				if ($datapro['row']->pro_bonus_type == 2) {
					$amount = $datapro['row']->pro_bonus_amount;
				}
				$remark = $datapro['row']->pro_symbol;
				$channel = $datapro['row']->channel;
				if (in_array($datapro['row']->pro_id,[114,51,150,151,152,153,157])) { 
					$check=$this->Model_bus_promo->Valid_limit_per_user($member_no,$datapro);
					if(!$check['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = 'ยูสเซอร์รับโปรได้ครบแล้วค่ะ!';}else{
						$vdeposit=$this->Model_bus_promo->Valid_deposit($member_no,$datapro); 
						if(!$vdeposit['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = $vdeposit['errMsg']; }
						$amount =$vdeposit['promo_amount'];
						$deposit_id=$vdeposit['deposit_id'];
					} 
					if(in_array($datapro['row']->pro_id,[114,150,151,152,153,157])){
						$insert_promotion=$vdeposit['data_all'];	
					}
					if(in_array($datapro['row']->pro_id,[150,151,152,153,157])){
						$deposit_id='';
					}
					if(in_array($datapro['row']->pro_id,[151,157])){
						$amount=$amount_org;
						$insert_promotion['deposit_id']=0;
						$insert_promotion['turnover_expect']=$turnover_amount;
						$insert_promotion['promo_amount']=$amount; 
					}
					if(in_array($datapro['row']->pro_id,[152])){
						$insert_promotion['deposit_id']=0; 
					}
				}
				if ($datapro['row']->pro_id == 21) { ///  Welcome back 100%
					$RSLastDeposit = $this->Model_member->SearchMemberByIDs($member_no); // check true Wallet
					if ($RSLastDeposit['num_rows'] > 0) {
						if ($RSLastDeposit['row']->member_last_deposit == 2) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'ยูสเซอร์ฝากด้วย True Wallet ไม่สามารถรับโปรฯ นี้ได้';
						}
					} 
					$checkx=$this->Model_bus_promo->Deposit_last($member_no,7);
					if($checkx>0){
						    $BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'ลูกค้าต้องไม่ได้เล่นนาน 7 วัน ขึ้นไป';
					}
					$checkx=$this->Model_bus_promo->Deposit_Count($member_no);
					if($checkx<3){
						    $BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = 'ลูกค้าต้องต้องมียอดฝาก 3 ยอดขึ้นไป';
					}
					if($BoolMessage){
					$check=$this->Model_bus_promo->Valid_limit_per_user($member_no,$datapro); 
					if(!$check['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = 'ยูสเซอร์รับโปรได้ครบแล้วค่ะ!';}else{
						$vdeposit=$this->Model_bus_promo->Valid_deposit($member_no,$datapro); 
						if(!$vdeposit['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = $vdeposit['errMsg']; }
						$amount =$vdeposit['promo_amount'];
						$deposit_id=$vdeposit['deposit_id'];
						$insert_promotion=$vdeposit['data_all'];
					} 
				}
				} else if ($datapro['row']->pro_id == 22) { /// Check Pro Merry Christmas 25%   
					$RSMC25 = $this->Model_promo->SearchPromoMC25Bymember_no($member_no);
					if ($RSMC25['num_rows'] > 0&&$RSMC25['num_rows']>=2) {
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = 'ยูสเซอร์เคยรับโปรฯ นี้ไปแล้ว @ ' . $this->Model_function->ConvertDateTimeShortTH($RSMC25['row']->update_date);
					} 
					if($BoolMessage){
						$check=$this->Model_bus_promo->Valid_limit_per_user($member_no,$datapro); 
						if(!$check['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = 'ยูสเซอร์รับโปรได้ครบแล้วค่ะ!';}else{
							$vdeposit=$this->Model_bus_promo->Valid_deposit($member_no,$datapro); 
							if(!$vdeposit['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = $vdeposit['errMsg']; }
							$amount =$vdeposit['promo_amount'];
							$deposit_id=$vdeposit['deposit_id'];
							$insert_promotion=$vdeposit['data_all'];
						} 
					} 
				} else if ($datapro['row']->pro_id == 20) { /// Check Pro Happy New Year ฝาก 100 รับเพิ่ม 50 
					if($BoolMessage){
						$check=$this->Model_bus_promo->Valid_limit_per_user($member_no,$datapro); 
						if(!$check['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = 'ยูสเซอร์รับโปรได้ครบแล้วค่ะ!';}else{
							$vdeposit=$this->Model_bus_promo->Valid_deposit($member_no,$datapro); 
							if(!$vdeposit['vstatus']){$BoolMessage = false;$boolError = 1;$MessageErrorText = $vdeposit['errMsg']; }else{
								$dataturn = $this->Model_member->GetTurnOverTotalByMember($member_no);
								$turnover_now = $dataturn['turnover_now'];
								if ($turnover_now > 0) {
									$BoolMessage = false;
									$boolError = 1;
									$MessageErrorText = 'มียอดเล่นแล้วรับโปรฯ นี้ไม่ได้!';
								}
							} 
							$amount =$vdeposit['promo_amount'];
							$deposit_id=$vdeposit['deposit_id'];
							$insert_promotion=$vdeposit['data_all'];
						} 
					} 

				} else if ($datapro['row']->pro_id == 23) { // รางวัลประจำเดือน aff
					$datasubpro = $this->Model_month_aff->FindAll($subpro_id);
					$amount = $datasubpro['row']->amount;
					$remark = $datapro['row']->pro_symbol . $datasubpro['row']->id;
					$checkPro = $this->Model_promo->promo_reward_month_blocked($member_no, $datapro['row']->channel);
					if ($checkPro != null) {
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = ' ยูสเซอร์เคยรับโบนัส นี้ไปแล้ว - AFF-' . $checkPro->remark . '-' . number_format($checkPro->amount, 2) . ' @' . $checkPro->trx_date . '';
					}
				} else if ($datapro['row']->pro_id == 24) { // รางวัลประจำเดือน comm
					$datasubpro = $this->Model_month_comm->FindAll($subpro_id);
					$amount = $datasubpro['row']->amount;
					$remark = $datapro['row']->pro_symbol . $datasubpro['row']->id;
					$checkPro = $this->Model_promo->promo_reward_month_blocked($member_no, $datapro['row']->channel);
					if ($checkPro != null) {
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = ' ยูสเซอร์เคยรับโบนัส นี้ไปแล้ว - Comm-' . $checkPro->remark . '-' . number_format($checkPro->amount, 2) . ' @' . $checkPro->trx_date . '';
					}
				} else  if ($datapro['row']->pro_id >= 80 && $datapro['row']->pro_id <= 95) { //ฝากประจำต่อเนื่อง 
					$daynum = ($remarkOption - 80);
					$checkPro = $this->Model_promo->promo_frequency_blocked($member_no, $pro_deposit_start_amount, $daynum);
					if ($daynum != @sizeof($checkPro)) {
						$BoolMessage = false;
						$boolError = 1;
						$MessageErrorText = "ไม่สามารถรับโปรได้เนื่องจากยังมียอดฝากไม่ครบตามเงื่อนไข (" . sizeof($checkPro) . ")!!";
					} else {
						$log_deposit_id = [];
						foreach ($checkPro as $k => $v) {
							$log_deposit_id[] = $v['id'];
						}
						if ($this->Model_promo->insert_PromoDepositfrequency($member_no, $amount, $promo_id) <= 0) {
							$BoolMessage = false;
							$boolError = 1;
							$MessageErrorText = "วันนี้ผู้ใช้รับโปรนี้แล้ว";
						} else {
							$this->Model_db_log->update_log_depositBylist($promo_id, $log_deposit_id);
						}
					}
				}
			}

			if ($RSM_Wallet['row']->main_wallet > $skipChkBalance && $skipChkBalance != 0) {
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = 'ยูสเซอร์นี้ มีเงินค้างในกระเป๋ามากกว่า ' . $skipChkBalance . ' บ.(' . $RSM_Wallet['row']->main_wallet . ')';
			}

			$RSMember = $this->Model_member->SearchMemberByIDs($member_no);
			$member_username = $RSMember['row']->username;
			if ($BoolMessage == true) {
				$re_status=$this->Model_log_deposit->UploadImage();
				if($re_status['status']!=200){
					$json_arr = array('Message' => false, 'ErrorText' =>'ไม่สามรถอัพโหลดสลิปได้!'.$re_status, 'boolError' => 1);echo json_encode($json_arr);exit();
				}
				$res = $this->Model_promotion->AddCreditManual($member_no, $member_username, $amount, $remark, $remarkOption, $data_deposit = [], $promo_id, $channel, $member_last_deposit,$re_status['file_name']);
				$MessageErrorText = "บันทึกข้อมูลเรียบร้อย";
				if ($res->error_code != 200) { 
					$BoolMessage = false;
					$boolError = 1;
					$MessageErrorText = "ไม่สามารถบันทึกข้อมูล!";
				}else{
					switch ($pro_id) {
						case 51:
							  $data =[
							  'member_no' => $member_no,
							  'deposit_amount' => $insert_promotion['deposit_amount'],
							  'promo_amount' => $insert_promotion['promo_amount'], 
							  'turnover_expect' =>$insert_promotion['turnover_expect'],
							  'status' => 1,
							  'accept_date' => date('Y-m-d H:i:s')
							  ];  
							  $this->db->insert('promop121', $data); 
					   break;  
					 }
				   if($deposit_id!=''){$this->Model_db_log->update_log_depositBylist($promo_id, $deposit_id);}
				   if(sizeof($insert_promotion)>0){unset($insert_promotion['deposit_amount']); $this->Model_promotion->SaveLogsPro($insert_promotion);}	 
				}
			}
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function PromotionList()
	{
		$param_pro_cat_id = $this->input->get('pro_cat_id', TRUE);
		$item['pro_id'] = $this->input->get('pro_id', TRUE);
		if ($param_pro_cat_id == 'all') {
			$param_pro_cat_id = null;
		}
		$item['pro_category_list'] = [];
		$item['pro_master_list'] = [];
		$item['pro_list'] = [];
		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
		$item['pg_title'] = 'รายการ Promotion';
		$item['card_title'] = 'รายการ Promotion';

		$data = $this->Model_promotion->GetCategoryGroup();
		if ($data['num_rows'] > 0) {
			$item['pro_category_list'] = $data['result_array'];
		}
		$data = $this->Model_promotion->ListMasterData($param_pro_cat_id);
		if ($data['num_rows'] > 0) {
			$item['pro_master_list'] = $data['result_array'];
		}
		$data = $this->Model_promotion->ListDataBySite($param_pro_cat_id);
		if ($data['num_rows'] > 0) {
			$item['pro_list'] = $data['result_array'];
		}
 
		if ($this->allow_action) {
			$dis="";
		}else{
			$dis="disabled";
		}
		$item['hide_btn'] = $dis;
		if (!$this->input->is_ajax_request()) {
			$this->load->view('promotion/list', $item);
		} else {
			$item['reward_daily'] = $this->Model_reward->FindAll();
			$item['reward_m_aff'] = $this->Model_month_aff->FindAll();
			$item['reward_m_comm'] = $this->Model_month_comm->FindAll();
			$data = $this->load->view('promotion/frm_promotion/frm_get_bonus', $item, true);
			echo json_encode(['data' => $data]);
			exit();
		}
	}

	public function Loadfrompromotion()
	{
		$this->load->model('Model_game_type');
		$item['pro_id'] = $this->input->get('pro_id', TRUE);
		$item['m_order'] = $this->input->get('m_order', TRUE);
		$data = $this->Model_game_type->FindAll();
		if ($data['num_rows'] > 0) {
			$item['game_type_list'] = $data['result_array'];
		} 
		$item['list_deposit_type']=$this->deposit_type;
		$item['list_deposit_dis']=$this->deposit_dis;
		$item['pro_data'] = [];
		$datapro=$this->Model_promotion->FindById($item['pro_id']);
		$item['pro_data'] = $datapro;
		$item['deposit_range_list'] = $this->Model_promotion->Getdetail_More($item['pro_id']);
		$item['deposit_touptoday_list'] = $this->Model_promotion->Getdetail_TopupToday($item['pro_id']);
		switch ($item['pro_id']) {
			case 10: // รับโบนัส 50% สูงสุด 200
			case 11: // รับโบนัส 100% สูงสุด 200 
			case 12: // 20 ได้ 100
			case 13: //รับเครดิตฟรี 50
			case 14: //รับเครดิตฟรี 60 		
			case 50: //ฝาก 300 รับ 50
			case 51: //รับโบนัส 2% ทั้งวัน   
			case 20: //Happy New Year
			case 21: //Welcome back
			case 22: //Merry Christmas 	  
		    case 25: // ต้อนรับสมาชิกใหม่ ฝากครั้งแรกเอาไป 150%
			case 101: //โปรฝากประจำ รับโบนัส 10% ทั้งวัน
			case 102: //โปรฝากประจำ รับโบนัส 10% ทั้งวัน
			case 103:
			case 104:
			case 105: //โปรฝากประจำ รับโบนัส 10% ทั้งวัน 	
		    case 112: //โปรฝากประจำ รับโบนัส 29 รับ 100 ทั้งวัน	
		    case 113: //โปรฝากประจำ รับโบนัส 79 รับ 200 ทั้งวัน			
		    case 114: //โปรลูกค้าเก่าย้ายเว็บ 50% สูงสุด 100 เครดิต รับได้ 3 ครั้ง (แอดมือเหมือน WB)		
			case 115: //สล็อตแตก แจกเพิ่ม	
			case 116: //บาคาร่าแทงถูกหรือผิด
	        case 123: //ฝากแรก รับ 100% 	
		    case 124: //โปรวันเกิด		
			case 125: //โปรสงกรานต์สาดกระจายส์			
		    case 34: //โบนัสพิเศษฉลองวันหยุดยาว			
		    case 37: //ฝากประจำ เริ่มต้น 299 บาท ได้เพิ่ม X2 ทุกวัน				
			case 140: //โปร 1	
			case 141: //โปร 2
			case 142: //โปร 3	
			case 143: //โปร 4		
			case 144: //โปร 5
			case 145: //โปร 6	
			case 146: //โปร 7		
		    case 147: //โปร 8	
			case 148: //รับเครดิตฟรี 20		 			
		    case 149: //สเต็ปตาย 1 คู่	
		    case 150: // โปรวันเกิด 		 			
		    case 151: // โปรพิเศษสำหรับสมาชิก ระบุเทิร์นโอเวอร์ 	
			case 152: // กิจกรรม reward		
		    case 153: // กิจกรรม แคปปุ๊บ รับปั๊บ					
			case 157: // โปรสะสมประจำสัปดาห์		
		    case 182: // สำหรับลูกค้าที่มีประวัติฝากเงิน 20 บาทขึ้นไป      	
			case 187: //โปร 187	
			case 188: //โปร 188
			case 189: //โปร 189					
				$item['reward_list'] = $this->Model_promotion->Getdetail_More($item['pro_id']); 
				$from = $this->load->view('promotion/frm_promotion/pro50', $item, true);
				break;
			// case 20: //Happy New Year
			// case 21: //Welcome back
			// case 22: //Merry Christmas  
			// 	$from = $this->load->view('promotion/frm_promotion/add_credit', $item, true);
			// 	break;
			// case 101: //โปรฝากประจำ รับโบนัส 10% ทั้งวัน
			// case 102: //โปรฝากประจำ รับโบนัส 10% ทั้งวัน
			// case 103:
			// case 104:
			// case 105: 
			// 	$from = $this->load->view('promotion/frm_promotion/p10', $item, true);
			// 	break;
			case 106: //HappyTime ช่วงเช้า 
			case 107: //HappyTime รอบดึก  
				$from = $this->load->view('promotion/frm_promotion/happytime', $item, true);
				break;
			case 108: // สะสมครบ 5,000
			case 109: //สะสมครบ 10,000
			case 110: //สะสมครบ 20,000
			case 111: //สะสมครบ 50,000 	 
				$from = $this->load->view('promotion/frm_promotion/p5', $item, true);
				break;
			case 83: //500 ชึ้นไปครบ 3 วัน	 
			case 87: //500 ชึ้นไปครบ 7 วัน	
			case 90: //500 ชึ้นไปครบ 10 วัน	
			case 95: //500 ชึ้นไปครบ 15 วัน	 
				$from = $this->load->view('promotion/frm_promotion/p6', $item, true);
				break;
			case 19: //ยอดการเล่น 5 อันดับสูงสุด 
			case 179: // ยอดแนะนำเพื่อน 5 อันดับสูงสุด	
			case 180: // ยอดเสีย 5 อันดับสูงสุด	
			case 181: // ยอดขนะ 5 อันดับสูงสุด			
				$item['reward_list'] = $this->Model_reward->FindAll(null,$item['pro_id']);
				$from = $this->load->view('promotion/frm_promotion/reward_daily', $item, true);
				break;
			case 23: //รางวัลประจำเดือน - AFF
				$item['frm_type'] = 'month_aff';
				$item['reward_list'] = $this->Model_month_aff->FindAll(null, $item['pro_id']); 
				$from = $this->load->view('promotion/frm_promotion/reward_month', $item, true);
				break;
			case 24: // รางวัลประจำเดือน - Comm
				$item['frm_type'] = 'month_comm';
				$item['reward_list'] = $this->Model_month_comm->FindAll(null, $item['pro_id']); 
				$from = $this->load->view('promotion/frm_promotion/reward_month', $item, true);
				break;
		    case 16: //แนะนำเพื่อนรับเลยค่าคอมกดรับได้ตลอดเวลา
			case 17: // คืนยอดเสีย เอาใจขาเล็ก
		    case 18: // คืนยอดเสีย เอาใจขาเล็ก	
	        case 26: // เปิดไพ่วัดดวง		
		    case 27: //สปินนำโชค... ลุ้นทองทุกวัน	
			case 8: 
			case 9: 
		    case 28: //	บอดสนิทก็ได้ตังค์	
		    case 29: // ชนะทั้งที ต้องมี 2 เด้ง
		    case 30: //	แพ้หรือชนะติดๆ กัน
		    case 115: //สล็อตแตก แจกเพิ่ม	
		    case 116: //แพ้แต้ม "0" ติดกัน 3 ตาซ้อน รับไปเลย 5,000
		    case 117: //ชนะด้วยไพ่ 8 แต้ม 2 เด้ง
		    case 118: //แพ้หรือชนะ 8 ตา รับ 5,000
				$item['reward_list'] = $this->Model_promotion->Getdetail_More($item['pro_id']); 
				$from = $this->load->view('promotion/frm_promotion/pro_foradmin', $item, true);
				break;
		      default:
				$from = $this->load->view('promotion/pro_notfound', $item, true);
				break;
		}
		echo $from;
		exit();
	}
	public function SaveData()
	{
		$data = $this->input->post(null, TRUE);
		$old_data = $this->Model_promotion->FindById($data['pro_id']);
		if ($old_data['num_rows'] <= 0) {
			$this->respone->status_code = 404;
			$this->respone->status_msg = 'not found';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		}
		$new_data = $old_data['row'];
		$new_data->pro_name = $data['pro_name'];
		$new_data->pro_bonus_type = (isset($data['pro_bonus_type']) ? $data['pro_bonus_type'] : $new_data->pro_bonus_type);
		$new_data->pro_bonus_amount = (isset($data['pro_bonus_amount']) ? $data['pro_bonus_amount'] : $new_data->pro_bonus_amount);
		$new_data->pro_bonus_max = (isset($data['pro_bonus_max']) ? $data['pro_bonus_max'] : $new_data->pro_bonus_max);
		$new_data->pro_turnover_type = (isset($data['pro_turnover_type']) ? $data['pro_turnover_type'] : $new_data->pro_turnover_type);
		$new_data->pro_turnover_amount = (isset($data['pro_turnover_amount']) ? $data['pro_turnover_amount'] : $new_data->pro_turnover_amount);
		$new_data->pro_withdraw_max_amount = (isset($data['pro_withdraw_max_amount']) ? $data['pro_withdraw_max_amount'] : $new_data->pro_withdraw_max_amount);
		$new_data->pro_status = $data['pro_status'];
		$new_data->pro_description = (isset($data['pro_description']) ? $data['pro_description'] : $new_data->pro_description);
		$new_data->pro_deposit_type = (isset($data['pro_deposit_type']) ? $data['pro_deposit_type'] : $new_data->pro_deposit_type);
		$new_data->pro_deposit_start_date = (isset($data['pro_deposit_start_date']) ? $data['pro_deposit_start_date'] : $new_data->pro_deposit_start_date);
		$new_data->pro_deposit_end_date = (isset($data['pro_deposit_end_date']) ? $data['pro_deposit_end_date'] : $new_data->pro_deposit_end_date);
		$new_data->pro_deposit_start_date = date("Y-m-d H:i:s", strtotime($new_data->pro_deposit_start_date));
		$new_data->pro_deposit_end_date = date("Y-m-d H:i:s", strtotime($new_data->pro_deposit_end_date));
		$new_data->pro_last_deposit_amount = (isset($data['pro_last_deposit_amount']) ? $data['pro_last_deposit_amount'] : $new_data->pro_last_deposit_amount); 
		$new_data->pro_deposit_fix = (isset($data['pro_deposit_fix']) ? $data['pro_deposit_fix'] : $new_data->pro_deposit_fix);
		$new_data->pro_deposit_start_amount = (isset($data['pro_deposit_start_amount']) ? $data['pro_deposit_start_amount'] : $new_data->pro_deposit_start_amount);
		$new_data->pro_deposit_end_amount = (isset($data['pro_deposit_end_amount']) ? $data['pro_deposit_end_amount'] : $new_data->pro_deposit_end_amount);
		$new_data->pro_start = (isset($data['pro_start']) ? $data['pro_start'] : $new_data->pro_start);
		$new_data->pro_end = (isset($data['pro_end']) ? $data['pro_end'] : $new_data->pro_end);
		$new_data->pro_repeat = (isset($data['pro_repeat']) ? $data['pro_repeat'] : 1);
		$new_data->pro_weekly_day = (isset($data['pro_weekly_day']) ? $data['pro_weekly_day'] : 'mon');
		$new_data->pro_max_repeat = (isset($data['pro_max_repeat']) ? $data['pro_max_repeat'] : $new_data->pro_max_repeat);
		$new_data->pro_max_repeat_web = (isset($data['pro_max_repeat_web']) ? $data['pro_max_repeat_web'] : $new_data->pro_max_repeat_web);
		$new_data->pro_withdraw_type = (isset($data['pro_withdraw_type']) ? $data['pro_withdraw_type'] : $new_data->pro_withdraw_type);
		$allow_playgame = [];
		if (isset($data['pro_allow_playgame'])) {
			foreach ($data['pro_allow_playgame'] as $v) {
				$allow_playgame[] = $v;
			}
		}
		$new_data->pro_allow_playgame = implode(',', $allow_playgame);

		unset($new_data->id); // remove id 

		$Result = $this->Model_promotion->SaveData($new_data);
		if ($Result->error_code != 200) {
			$this->respone->status_code = $Result->error_code;
			$this->respone->status_msg = 'fail';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		}
		if (isset($data['m_reward'])) {
			$reward_list = $this->Model_reward->FindAll(null, $data['pro_id']);
			foreach ($reward_list['result_array'] as $v) {
				$input_name = 'm_reward_' . $v['id'];
				$amount = $data[$input_name];
				$tmp = ['amount' => $amount];
				$this->Model_reward->EditById($v['id'], $tmp);
			}
		}

        if($new_data->pro_deposit_type==3){
			$t_list = $this->Model_promotion->Getdetail_More($data['pro_id']);
			if(@$t_list['row']->action=='new'){ //insert
                $action='insert';    
		      	}else{// update
				$action='update';    
			}
			foreach ($t_list['result_array'] as $v) { 
				$deposit_start = $data['deposit_start_' . $v['id']];
				$deposit_end = $data['deposit_end_' . $v['id']];
				$turnover = $data['turnover_' . $v['id']]; 
				$pro_bonus_amount = isset($data['pro_bonus_amount_' . $v['id']])?$data['pro_bonus_amount_' . $v['id']]:0; 
				$bonus_max = $data['bonus_max_' . $v['id']];
				$withdraw_max_amount = (isset($data['withdraw_max_amount_' . $v['id']])?$data['withdraw_max_amount_' . $v['id']]:0);
				$tmp = ['bonus_max_amount' =>$bonus_max,'deposit_start_amount' => $deposit_start,'deposit_end_amount' => $deposit_end,'turnover_amount' => $turnover,'pro_bonus_amount'=>$pro_bonus_amount,'withdraw_max_amount'=>$withdraw_max_amount]; 
				if($action=='insert'){
					$tmp = ['pro_id'=>$data['pro_id'],'bonus_max_amount' =>$bonus_max,'deposit_start_amount' => $deposit_start,'deposit_end_amount' => $deposit_end,'turnover_amount' => $turnover,'pro_bonus_amount'=>$pro_bonus_amount,'withdraw_max_amount'=>$withdraw_max_amount]; 
					$t=$this->Model_promotion->InsertById_Detail_More($tmp);
				}else{
					$t=$this->Model_promotion->EditById_Detail_More($v['id'], $tmp);
				} 
			} 
		} 

		if($new_data->pro_deposit_type==4){
			$t_list = $this->Model_promotion->Getdetail_TopupToday($data['pro_id']);
			if(@$t_list['row']->action=='new'){ //insert
                $action='insert';    
		      	}else{// update
				$action='update';    
			}
			foreach ($t_list['result_array'] as $v) { 
				$deposit_start = $data['deposit_start_' . $v['id']];
				$deposit_end = $data['deposit_end_' . $v['id']];
				$morder = $data['morder_' . $v['id']];
				$turnover = (isset($data['turnover_' . $v['id']]))?$data['turnover_' . $v['id']]:-1; 
				$pro_bonus_amount = isset($data['pro_bonus_amount_' . $v['id']])?$data['pro_bonus_amount_' . $v['id']]:0; 
				$bonus_max = @$data['bonus_max_' . $v['id']];
				$withdraw_max_amount = (isset($data['withdraw_max_amount_' . $v['id']])?$data['withdraw_max_amount_' . $v['id']]:0);
				$tmp = ['morder'=>$morder,'bonus_max_amount' =>$bonus_max,'deposit_start_amount' => $deposit_start,'deposit_end_amount' => $deposit_end,'turnover_amount' => $turnover,'pro_bonus_amount'=>$pro_bonus_amount,'withdraw_max_amount'=>$withdraw_max_amount]; 
				if($action=='insert'){
					$tmp = ['pro_id'=>$data['pro_id'],'bonus_max_amount' =>$bonus_max,'deposit_start_amount' => $deposit_start,'deposit_end_amount' => $deposit_end,'turnover_amount' => $turnover,'pro_bonus_amount'=>$pro_bonus_amount,'withdraw_max_amount'=>$withdraw_max_amount]; 
					$t=$this->Model_promotion->InsertById_Detail_TopupToday($tmp);
				}else{
					$t=$this->Model_promotion->EditById_Detail_TopupToday($v['id'], $tmp);
				} 
			} 
		} 

		if (@$data['frm_type'] == 'month_aff') {
			$reward_list = $this->Model_month_aff->FindAll(null,$data['pro_id']);
			foreach ($reward_list['result_array'] as $v) {
				$input_status = 'pro_status_' . $v['id'];
				$input_name = 'm_reward_' . $v['id'];
				$amount = $data[$input_name];
				$status = $data[$input_status];
				$tmp = ['amount' => $amount, 'status' => $status];
				$this->Model_month_aff->EditById($v['id'], $tmp);
			}
		}
		if (@$data['frm_type'] == 'month_comm') {
			$reward_list = $this->Model_month_comm->FindAll(null,$data['pro_id']);
			foreach ($reward_list['result_array'] as $v) {
				$input_status = 'pro_status_' . $v['id'];
				$input_name = 'm_reward_' . $v['id'];
				$amount = $data[$input_name];
				$status = $data[$input_status];
				$tmp = ['amount' => $amount, 'status' => $status];
				$this->Model_month_comm->EditById($v['id'], $tmp);
			}
		}


		$this->respone->status_code = $Result->error_code;
		$this->respone->status_msg = 'ok';
		$this->ResponeJson($this->respone, $this->respone->status_code);
	}
	public function DeleteImagePro(){
		$data = $this->input->post(null, TRUE);
		$old_data = $this->Model_promotion->FindById($data['pro_id']);
		if ($old_data['num_rows'] <= 0) {
			$this->respone->status_code = 404;
			$this->respone->status_msg = 'not found';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		}
		$new_data = $old_data['row'];
		$file_old_name = $new_data->pro_img1;
		if($new_data->pro_img1==''||is_null($new_data->pro_img1)){
			$this->respone->status_code = 200;
			$this->respone->status_msg = 'ok';
			$this->ResponeJson($this->respone, $this->respone->status_code);	
		}
		unset($new_data->id); // remove id 
		$new_data->pro_img1=Null;
		@unlink('./images/promotions/' . $file_old_name);
		$Result = $this->Model_promotion->SaveData($new_data);
		if ($Result->error_code != 200) {
			$this->respone->status_code = $Result->error_code;
			$this->respone->status_msg = 'fail';
			$this->ResponeJson($this->respone, $this->respone->status_code);
		}
		$this->respone->status_code = 200;
		$this->respone->status_msg = 'ok';
		$this->ResponeJson($this->respone, $this->respone->status_code);
	}
	public function UploadImage()
	{
		require dirname(__FILE__) . '/../../private69/vendor/autoload.php';
		$pro_id = $this->input->post('pro_id', TRUE);
		$old_data = $this->Model_promotion->FindById($pro_id);

		$filename = $_FILES['image_field']['name'];
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
		$imageFileType = strtolower($imageFileType);

		$file_new_name = uniqid();
		$new_old_data = $old_data['row'];
		$file_old_name = $new_old_data->pro_img1;

		$new_data = new stdClass();
		$new_data->pro_id = $new_old_data->pro_id;
		$new_data->pro_img1 = $file_new_name . '.' . $imageFileType;
		//unset($new_data->id); // remove id   
		$res_save = $this->Model_promotion->EditById($pro_id, $new_data);
		if ($res_save->error_code != 200) {
			exit();
		}
		$res_save = $this->Model_promotion->EditMesterById($pro_id, $new_data);
		if ($res_save->error_code != 200) {
			exit();
		}
		$handle = new \Verot\Upload\Upload($_FILES['image_field']);
		if ($handle->uploaded) {
			$handle->allowed = array('image/*');
			$handle->image_resize = true;
			$handle->image_x = 450;
			$handle->image_ratio_y = true;
			$handle->forbidden = array('application/*');
			$handle->file_new_name_body   = $file_new_name;
			$handle->process('./images/promotions');
			if ($handle->processed) {
				@unlink('./images/promotions/' . $file_old_name);
				$handle->clean();
			} else {
				echo 'error : ' . $handle->error;
			}
		}
	}
	public function EditById()
	{
	}
}
