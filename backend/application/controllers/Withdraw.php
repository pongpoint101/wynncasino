<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends MY_Controller {
	public function __construct() {
		parent:: __construct(); 
		$this->load->library("ConfigData");
		$this->load->model('read/Model_rbank');
	}
	public function view(){ 
		
		$item['PGDateNow'] = date("Y-m-d");


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';

		$this->load->view('view_withdraw',$item);
	}

	public function pending(){
		$item['PGDateNow'] = date("Y-m-d");


		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);

		$item['pg_title'] = 'Dashbord';
		if($this->Model_function->PG_Login_BanEmployee() == false){
			redirect($_SERVER['REQUEST_URI'], 'refresh'); 
		}

		$this->load->view('view_withdraw_padding',$item);
	}
	public function UploadImage(){

		require dirname(__FILE__). '/../../private69/vendor/autoload.php';  

		$filename = $_FILES['image_field']['name'];
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION); 
		$imageFileType = strtolower($imageFileType);  

		$file_new_name='slip_'.uniqid();
		$slip = $file_new_name.'.'.$imageFileType;
		$pg_w_ids = isset($_POST['pg_w_ids_slip']) ? $_POST['pg_w_ids_slip'] : "";
		$log_withdraw_id = $this->Model_function->get_decrypt($pg_w_ids);

		if ($log_withdraw_id != false) {

			$Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);

			if ($Result['num_rows'] > 0) {

				$res_save=$this->Model_db_log->update_log_withdraw_slip($log_withdraw_id,$slip);

				if($res_save==false){ exit();}  

				$handle = new \Verot\Upload\Upload($_FILES['image_field']);
				if ($handle->uploaded) {
					$handle->allowed = array('image/*'); 
					$handle->image_resize = true; 
					$handle->image_x =450; 
					$handle->image_ratio_y = true;
					$handle->forbidden = array('application/*'); 
					$handle->file_new_name_body   =$file_new_name;  
					$handle->process('./images/slip');
					if ($handle->processed) {
						// @unlink('./images/web/'.$file_old_name); 
						$handle->clean();
					} else {
						echo 'error : ' . $handle->error;
					}
				}

			} else {
				exit();
				echo 'error : '."ไม่สามารถทำรายการได้ !!";
			}
		} 

	} 

	public function list_withdraw_json(){
		$trx_date = isset($_GET['trx_date']) ? $_GET['trx_date'] : NULL;
		$trx_date_check = $this->Model_function->CheckFormatDate($trx_date);
		$dataArr = array();
		if($trx_date_check == true){
			$Result = $this->Model_db_log->search_log_withdraw_by_date($trx_date);
			if($Result['num_rows'] > 0){
				foreach ($Result['result_array'] as $item) {
					$btn_remark_detail ='';
					$txt_remark_internal_detail=htmlentities($item['remark_internal_detail']);
					$txt_remark_internal_detail=preg_replace('~[\r\n]+~', '', $txt_remark_internal_detail);
					$txt_remark_internal_detail=preg_replace('/\s+/', ' ', trim($txt_remark_internal_detail));
					if(@$item['remark_internal_detail']!=''&&$item['remark_internal_detail']!=null&&strlen(trim($item['remark_internal_detail']))>0){
					   $btn_remark_detail ='<br/><button onclick="Show_remark_detail(\'' . $txt_remark_internal_detail. '\')" class="btn btn-info btn-sm btnmr5px"><i class="la la-search-plus iconfontsize12px"></i> รายละเอียด</button>';
					 }
					$RScc = $this->Model_function->condition_check_status($item['status'],$item['channel'], $item['remark'],$item['remark_internal']);
					if($item['username'] == NULL){
						$item['username'] = '<i class="fa fa-times danger"></i>';
					}
					$textslip="<a href='".base_url()."/images/slip/".$item['slip']."'"." target='_blank'>คลิก</a>";
                    if($item['slip']==null||$item['slip']==''){$textslip='--';}

					$jsonArr = array("<center>".$item['trx_date']." ".$item['trx_time']."</center>",
									 "<center>".$item['update_date']."</center>",    
									 "<center><a target='_blank' href='".base_url("member/profile/".$item['username'])."'>".$item['username']."</a></center>",
									 "<center>".number_format($item['amount'], 2)."</center>",
									 "<center>".number_format($item['amount_actual'], 2)."</center>",
									 "<center class='".$RScc['class_text_color']."'>".$RScc['statusText']."</center>",
									 "<center>".$item['withdraw_by']."</center>",
									 "<center>".$item['withdraw_otp']."</center>",
									 $item['remark'].$btn_remark_detail,
									 "<center>".$textslip."</center>");
					array_push($dataArr, $jsonArr);
				}
			}
		}

		$json_arr = array('data'=> $dataArr);
		echo json_encode($json_arr);

	}

	public function list_withdraw_padding_json()
	{ 
		$datain = $this->input->get(null, TRUE);
		$latest=100000;
		if(@$datain['latest']>0){$latest=$datain['latest'];}
		$m_Result = $this->Model_affiliate->list_m_site(); 
    	$max_Aprove_auto_withdraw=$m_Result['row']->max_approve_auto_withdraw;

		$dataArr = array();
		$Result = $this->Model_db_log->search_log_withdraw_by_padding($latest);
		 
		if ($Result['num_rows'] > 0) {
			foreach ($Result['result_array'] as $item) {

				$RScc = $this->Model_function->condition_check_status(2, $item['channel'], $item['remark'],$item['remark_internal']);
				if ($item['username'] == NULL) {
					$item['username'] = '<i class="fa fa-times danger"></i>';
				}

				$pg_amount = "0.00";

				if ($item['amount'] >= $m_Result['row']->max_auto_withdraw) {
					$pg_amount = '<label class="text-warning">' . number_format($item['amount'], 2) . '</label>';
				} else {
					$pg_amount = number_format($item['amount'], 2);
				}
				$txt_hint='(auto)';
				if($item['amount_actual']>$max_Aprove_auto_withdraw||$max_Aprove_auto_withdraw<=0||$item['promo']!=0){$txt_hint='';}
				$btn_success = '<button onclick="success_withdraw(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\',\'' . $item['promo'] . '\')" class="btn btn-primary btn-sm btnmr5px"><i class="la la-check iconfontsize12px"></i> <b>A'.$txt_hint.'</b></button>';
				if ($item['amount'] >= $m_Result['row']->max_auto_withdraw ||$item['bank_accountnumber']==''||is_null($item['bank_accountnumber'])) {
					$btn_success = "";
				} 
				$status='รอดำเนินการ';$bank_accountnumber_bank='';$bank_name_bank='';$bank_accountnumber_tw='';$bank_name_tw='';$bank_accountnumber_vz='';$bank_name_vz='';
				if($item['status']==5){$status='<span style="color:red;" >ไม่สามารถถอนได้ '.$item['remark_internal'].'</span>';}
				$btn_success_manual = '<button onclick="success_withdraw_manual(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\',\'' . $item['promo'] . '\')" class="btn btn-success btn-sm btnmr5px"><i class="la la-check iconfontsize12px"></i> <b>M'.$txt_hint.'</b></button>';
				$btn_refund_credit = '<button onclick="refund_credit(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\')" class="btn btn-info btn-sm btnmr5px"><i class="la la-refresh iconfontsize12px"></i> <b>C'.$txt_hint.'</b></button>';
				$btn_cancel = '<button onclick="cancel_withdraw(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\')" class="btn btn-danger btn-sm"><i class="la la-close iconfontsize12px"></i> <b>R'.$txt_hint.'</b></button>';
                $btn_true_wallet=''; 
				if($item['bank_accountnumber']!=''&&!is_null($item['bank_accountnumber'])){ 
					$bank_accountnumber_bank=($item['bank_accountnumber']!=''&&!is_null($item['bank_accountnumber']))?$item['bank_accountnumber']:'ไม่พบข้อมูลบัญชีถอน';
				    $bank_name_bank='ธนาคาร'; 
					$bank_accountnumber_bank=$bank_accountnumber_bank ." <br/>(" . $bank_name_bank . ")<br/>";
				}
				if($item['choose_bank']==2){
					$btn_true_wallet='<button onclick="truewallet_withdraw(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\',\'' . $item['promo'] . '\')" class="btn btn-warning btn-sm btnmr5px"><i class="la la-check iconfontsize12px"></i> <b>TW'.$txt_hint.'</b></button>';
					$bank_accountnumber_tw=($item['truewallet_phone']!=''&&!is_null($item['truewallet_phone']))?$item['truewallet_phone']:'ไม่พบข้อมูลบัญชีถอน';
				    $bank_name_tw='ทรูมันนี่ วอลเล็ท'; 
					$bank_accountnumber_tw=$bank_accountnumber_tw ." <br/>(" . $bank_name_tw . ")<br/>";
				}
				if($item['bank_accountnumber']!=''&&!is_null($item['bank_accountnumber'])){ 
					$btn_vz_wallet='<button onclick="vizplay_withdraw(\'' . $this->encryption->encrypt($item['id']) . '\',\'' . $this->encryption->encrypt($item['member_no']) . '\',\'' . $item['promo'] . '\')" class="btn btn-secondary btn-sm btnmr5px"><i class="la la-check iconfontsize12px"></i> <b>VZ'.$txt_hint.'</b></button>';
					// $bank_accountnumber_vz=($item['bank_accountnumber']!=''&&!is_null($item['bank_accountnumber']))?$item['bank_accountnumber']:'ไม่พบข้อมูลบัญชีถอน';
					// $bank_name_vz='Vizplay'; 
					// $bank_accountnumber_vz=$bank_accountnumber_vz ." <br/>(" . $bank_name_vz . ")<br/>";
			    }

				$jsonArr = array(
					"<center>" . $item['trx_date'] . " <br/>" . $item['trx_time'] . "</center>",
					"<center><a target='_blank' href='" . base_url("member/profile/" . $item['username']) . "'>" . $item['username'] . "</a></center>",
					"<center>" . $item['fname'] . " " . $item['lname'] . "</center>",
					"<center>" . $bank_accountnumber_bank . $bank_accountnumber_tw.$bank_accountnumber_vz. "</center>",
					"<center>" . $pg_amount . "</center>",
					"<center>" . $status . "</center>",
					"<center>" . $RScc['gif_alert'] . $item['remark'] . "</center>",
					"<span class='text-right block'>".$btn_vz_wallet.$btn_true_wallet . $btn_success . $btn_success_manual . $btn_refund_credit . $btn_cancel . "</span>",
					$item['remark']
				);
				array_push($dataArr, $jsonArr);
			}
		}

		$json_arr = array('data' => $dataArr);
		echo json_encode($json_arr);
	}

	public function list_withdraw_id_json(){
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		$pg_ids = $this->Model_function->get_decrypt($ids);

		$MessageBool = false;
		$dataArr = array();

		if($pg_ids != false){
			$Result = $this->Model_db_log->search_log_withdraw_by_id($pg_ids);
			$tw=$this->Model_rbank->FindAll_truewallet(); 
			$tw_list=[];
			foreach ($tw['result_array'] as $k => $v) {
				$tw_list[]=['id'=>$v['id'],'twName'=>$v['twName'],'twNumber'=>$v['twNumber']];
			}
			if($Result['num_rows'] > 0){
				$MessageBool = true;
				foreach ($Result['result_array'] as $item) {
					$statusChecked = "";
					if($item['status'] == 1){
						$statusChecked = "checked";
					}

					$jsonArr = array(
						'username' => $item['username'],
						'amount' => number_format($item['amount'],2)." บาท",
						'name' => $item['fname'].' '.$item['lname'],
						'bank' => $item['bank_accountnumber'].' ('.$item['bank_name'].')',
						'tw'=>$tw_list
									);
					array_push($dataArr, $jsonArr);

					

				}
			}
		}
		$json_arr = array('Message' => $MessageBool, 'Result' => $dataArr);
		echo json_encode($json_arr);
	}

	public function withdraw_auto(){
		require dirname(__FILE__). '/../../pg_vendor/autoload.php';

		$m_Result = $this->Model_affiliate->list_m_site();

		$this->load->helper('file'); 
		$inputdata = $this->input->post(null, TRUE);  
		$pg_w_ids = isset($inputdata['pg_w_ids']) ? $inputdata['pg_w_ids'] : 0;
		$pg_m_ids = isset($inputdata['pg_m_ids']) ? $inputdata['pg_m_ids'] : 0;
		$promo_ids = isset($inputdata['promo_ids']) ? $inputdata['promo_ids'] : 0;
		$choose_bank = isset($inputdata['wd_type']) ? $inputdata['wd_type'] : 1;
		$tw_id = isset($inputdata['tw_id']) ? $inputdata['tw_id'] : 1;
		$remark_detail = isset($inputdata['remark_detail']) ? $inputdata['remark_detail'] : null;
		$tw=$this->Model_rbank->FindAll_truewallet($tw_id);
		$wallet_acc=@$tw['row']->twNumber;
		$log_withdraw_id = $this->Model_function->get_decrypt($pg_w_ids);
		$member_no = $this->Model_function->get_decrypt($pg_m_ids);
		$vizplay_withdrawURL = $m_Result['row']->vizplay_withdraw;
		$site_id = $m_Result['row']->site_id;

		$BoolMessage = false;
		$boolError = 0;
		$StatusDescription='';
		$boolCheckForm = true;
		$MessageErrorText = "";
		$bodysend = '';
		$res = '';
		$Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);
		if ($log_withdraw_id != false && $member_no != false &&in_array($Result['row']->status,[2,5])) { 
			if ($Result['num_rows'] > 0) {
				$wd_actual_amount = $Result['row']->amount;
				$RScc = $this->Model_function->condition_check_status(2,$Result['row']->channel, $Result['row']->remark,$Result['row']->remark_internal);
				if($Result['row']->status!=2){
					$boolError = 1;
					$MessageErrorText = "ไม่สามารถทำรายการได้ อาจจะมีรายการซ้ำ กรุณารอและเลือกการถอนด้วยถอนมือ!";
					$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);  
					echo json_encode($json_arr);exit();

				} // ไม่ใช่สถานะรอถอน 

				// $remark = $RScc['channelText'];
				// if($promo_ids == 1){
				// 	$wd_actual_amount = 100;
				// } else if($promo_ids == 16){
				// 	$wd_actual_amount = 120;
				// }

				// if ($promo_ids == 9 || $promo_ids == 21 || $promo_ids == 22 || $promo_ids == 23 || $promo_ids == 24) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 2) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 30) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 33) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// } elseif ($promo_ids == 6) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// } elseif ($promo_ids == 102) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// }elseif ($promo_ids == 52) {
				// 	$wd_actual_amount = ($wd_actual_amount > 500) ? 500 : $wd_actual_amount;
				// }
				if($promo_ids == 37){ 
					$this->db->where('member_no', $member_no); 
					$this->db->order_by('id', 'DESC');
					$sql = $this->db->get('promop37p');
					$row_array = $sql->row_array(); 
					$actual_withdraw=$row_array['actual_withdraw'];
					$wd_actual_amount =($Result['row']->amount>$actual_withdraw) ? $actual_withdraw : $wd_actual_amount;
			   }else{
				$wd_actual_amount=$this->Model_log_withdraw->get_withdraw_max($member_no,$Result['row']->amount,$promo_ids);
			   }  
			   $remark = $RScc['channelText']; 

				$transferFailed = 0;

				//if ($wd_actual_amount <= $m_Result['row']->max_auto_withdraw||($wd_actual_amount<=1500&&in_array($promo_ids, [2,33,6,102]))) {
				if ($wd_actual_amount <= $m_Result['row']->max_auto_withdraw) {

					$RSMember = $this->Model_member->SearchMemberByIDs($member_no);
					if ($Result['num_rows'] > 0) {

						try {

                            if($choose_bank==2){
								$wallet_acc=@$tw['row']->twNumber;
								$req2['wallet_acc'] = $wallet_acc;
								$req2['type'] = 'p2p';
								$req2['customer_phone'] = (trim(@$Result['row']->truewallet_account) > 4) ? trim(@$Result['row']->truewallet_account) : ((trim(@$Result['row']->truewallet_phone) > 4 && $tel == '') ? trim(@$Result['row']->truewallet_phone) : trim(@$Result['row']->telephone));
								$req2['amount'] = $wd_actual_amount;  
								$req2['msg'] = 'ถอนเงิน';  
								$withdrawURL = @$tw['row']->twURL; 	
								$arr['body'] = json_encode($req2);@$res['status']=200;@$res['message']='message';
						   	}else  if($choose_bank==3){
								$req['site_id'] = $site_id;
								$req['member_no'] = $member_no;
								$req['amount'] = $wd_actual_amount;
								$req['to_account_no'] =$RSMember['row']->bank_accountnumber;
								$req['to_bank_code'] =$RSMember['row']->bank_code;
								$req['to_name'] = $RSMember['row']->fname.' '.$RSMember['row']->lname;
								$req['trx_id'] =$Result['row']->trx_id;
								$withdrawURL = $vizplay_withdrawURL;
								$arr['body'] = json_encode($req);
							}else{
								$req['bank_code'] = $RSMember['row']->bank_code;
								$req['bank_acct'] = $RSMember['row']->bank_accountnumber;
								$req['amount'] = $wd_actual_amount;
								$req['fname'] = $RSMember['row']->fname;
								$req['lname'] = $RSMember['row']->lname;
								$withdrawURL = $m_Result['row']->withdraw_url;
								$arr['body'] = json_encode($req);
							}
							$this->Model_log_withdraw->update_log_withdraw_by_id($log_withdraw_id, 5,'semiauto','auto',$remark_detail);
							$client = new GuzzleHttp\Client([
								'exceptions'       => false,
								'verify'           => false,
								'timeout' => 60, // Response timeout
            					'connect_timeout' => 60, // Connection timeout
								'headers'          => ['Content-Type'   => 'application/json']
							]); 
							  
							$res = $client->request('POST', $withdrawURL, $arr); 
							$res_status=$res->getStatusCode();
							$resJSON = $res->getBody()->getContents();
							$res = json_decode($resJSON, true);
							  
							if($choose_bank==2){
								$StatusDescription = @$res['message'];
								if (@$res['status'] ==200) { 
									$boolError = 0;
									$MessageErrorText = 'โอนเงินสำเร็จ';
									$StatusDescription = 'สำเร็จ';
								}else{
									$boolError = 1;
									$MessageErrorText = "ไม่สามารถทำรายการได้ A $res_status! " . $StatusDescription;
								}

							 }else if($choose_bank==3){
								$StatusDescription = @$res['message'];
								$boolError = 1;
								$MessageErrorText = "ไม่สามารถทำรายการได้! " . $StatusDescription;
								if ($res_status==200) { 
									if($res['code']==200){
									  $boolError = 0;
									  $MessageErrorText = 'โอนเงินสำเร็จ';
									}
								} 
							  }else{
								$StatusDescription = $res['status']['description'];
								if (($res['status']['code'] != 1000) && ($res['status']['code'] != 9003)) { //($res['status']['code'] != 1000) && ($res['status']['code'] != 9003)
									$boolError = 1;
									if ($res['status']['code'] == 404) {
										$MessageErrorText = 'ชื่อบัญชีปลายทางไม่ตรงกับระบบ<BR>ชื่อในระบบ : ' . $res['status']['sysName'] . '<BR>ชื่อบัญชีปลายทาง : ' . $res['status']['scbName'];
									} else {
										$MessageErrorText = "ไม่สามารถทำรายการได้ B $res_status! " . $StatusDescription;
									}
								} else {
									$boolError = 0;
									$MessageErrorText = 'โอนเงินสำเร็จยอดเงินคงเหลือ ' . $res['data']['remainingBalance'] . ' บาท';
								} 
							}

							if ($boolError == 1) {
								$BoolMessage = false;
							} else {
								$BoolMessage = true;
							}

							if ($BoolMessage) {
								$this->Model_member->reset_member_promo($member_no);
								$this->Model_db_log->clear_Userinfo_data($member_no);
								$this->Model_db_log->update_log_withdraw_by_id($log_withdraw_id, $wd_actual_amount, $remark);

								$this->Model_member->update_member_turnover($member_no);
								$this->Model_db_log->insert_log_auto_withdraw($member_no, $wd_actual_amount, $StatusDescription, $resJSON);
								if ($promo_ids == 13) {
									$RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($member_no);
									if ($RSPromo50['num_rows'] > 0) {
										$this->Model_promo->update_status_profree50_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
									}
								}
								if ($promo_ids == 14) {

									$RSPromo100P = $this->Model_promo->SearchPromop100pBymember_no($member_no);
									if ($RSPromo100P['num_rows'] > 0) {
										$this->Model_promo->update_status_promop100p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
									}
								}
								if ($promo_ids == 10) {

									$RSPromo50P = $this->Model_promo->SearchPromop50pBymember_no($member_no);
									if ($RSPromo50P['num_rows'] > 0) {
										$this->Model_promo->update_status_promop50p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
									}
								}
								if ($promo_ids == 101) {
									$RSPromo10P = $this->Model_promo->SearchPromop10pBymember_no($member_no);
									if ($RSPromo10P['num_rows'] > 0) {
										$this->Model_promo->update_status_promop10p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
									}
								}

								$RSMempro = $this->Model_member->SearchMemberPromoWhere($member_no, $promo_ids, 1);

								if ($RSMempro['num_rows'] > 0) {
									$this->Model_member->Update_member_promo_status($member_no, 2);
								}
							}
						} catch (Exception $e) {
							$boolError = 1;
							$MessageErrorText = $e->getMessage();
						}
					} else {
						$boolError = 1;
						$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
					}
				} else {
					$boolError = 1;
					$MessageErrorText = "ไม่สามารถถอน Auto ได้ ยอดถอนเกิน " . number_format($m_Result['row']->max_auto_withdraw) . " บ. !!";
				}
			}
		} else {
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถทำรายการได้ 4 !!";
		}
		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);

		// write_file(FCPATH .'/tesxdata/bodysend_withdraw_auto.txt', $bodysend);
		// write_file(FCPATH .'/tesxdata/reswithdraw_auto.txt', $res);
		// write_file(FCPATH .'/tesxdata/debugwithdraw_auto.txt', json_encode($json_arr)); 
		echo json_encode($json_arr);
	}

	public function withdraw_manual(){
		$inputdata = $this->input->post(null, TRUE);  
		$pg_w_ids = isset($inputdata['pg_w_ids']) ? $inputdata['pg_w_ids'] : 0;
		$pg_m_ids = isset($inputdata['pg_m_ids']) ? $inputdata['pg_m_ids'] : 0;
		$promo_ids = isset($inputdata['promo_ids']) ? $inputdata['promo_ids'] : 0;
		$remark_detail = isset($inputdata['remark_detail']) ? $inputdata['remark_detail'] : null;

		$log_withdraw_id = $this->Model_function->get_decrypt($pg_w_ids);
		$member_no = $this->Model_function->get_decrypt($pg_m_ids);

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";


		if ($log_withdraw_id != false && $member_no != false) {
			$Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);
			$promo_ids=$Result['row']->promo;
			if ($Result['num_rows'] > 0 &&in_array($Result['row']->status,[2,5])) {
				$BoolMessage = true;

				$wd_actual_amount = $Result['row']->amount;
				$RScc = $this->Model_function->condition_check_status(2,$Result['row']->channel,$Result['row']->remark,$Result['row']->remark_internal);


				// $remark = $RScc['channelText'];
				// if($promo_ids == 1){
				// 	$wd_actual_amount = 100;
				// } else if($promo_ids == 16){
				// 	$wd_actual_amount = 120;
				// }

				// if ($promo_ids == 9 || $promo_ids == 21 || $promo_ids == 22 || $promo_ids == 23 || $promo_ids == 24) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 2) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 30) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1000) ? 1000 : $wd_actual_amount;
				// } elseif ($promo_ids == 33) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// } elseif ($promo_ids == 6) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// } elseif ($promo_ids == 102) {
				// 	$wd_actual_amount = ($wd_actual_amount > 1500) ? 1500 : $wd_actual_amount;
				// }elseif ($promo_ids == 52) {
				// 	$wd_actual_amount = ($wd_actual_amount > 500) ? 500 : $wd_actual_amount;
				// }
				if($promo_ids == 37){ 
					$this->db->where('member_no', $member_no); 
					$this->db->order_by('id', 'DESC');
					$sql = $this->db->get('promop37p');
					$row_array = $sql->row_array(); 
					$actual_withdraw=$row_array['actual_withdraw'];
					$wd_actual_amount =($Result['row']->amount>$actual_withdraw) ? $actual_withdraw : $wd_actual_amount;
			   }else{
				$wd_actual_amount=$this->Model_log_withdraw->get_withdraw_max($member_no,$Result['row']->amount,$promo_ids);
			   } 
			   $remark = $RScc['channelText']; 

				$this->Model_member->reset_member_promo($member_no);
				$this->Model_db_log->clear_Userinfo_data($member_no);
				$this->Model_db_log->update_log_withdraw_by_id($log_withdraw_id, $wd_actual_amount, $remark,'',$remark_detail);

				$this->Model_member->update_member_turnover($member_no);

				if ($promo_ids == 13) {
					$RSPromo50 = $this->Model_promo->SearchPromofree50Bymember_no($member_no);
					if ($RSPromo50['num_rows'] > 0) {
						$this->Model_promo->update_status_profree50_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
					}
				}
				if ($promo_ids == 14) {

					$RSPromo100P = $this->Model_promo->SearchPromop100pBymember_no($member_no);
					if ($RSPromo100P['num_rows'] > 0) {
						$this->Model_promo->update_status_promop100p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
					}
				}
				if ($promo_ids == 10) {

					$RSPromo50P = $this->Model_promo->SearchPromop50pBymember_no($member_no);
					if ($RSPromo50P['num_rows'] > 0) {
						$this->Model_promo->update_status_promop50p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
					}
				}
				if ($promo_ids == 101) {
					$RSPromo10P = $this->Model_promo->SearchPromop10pBymember_no($member_no);
					if ($RSPromo10P['num_rows'] > 0) {
						$this->Model_promo->update_status_promop10p_withdraw($member_no, 2, $wd_actual_amount, $wd_actual_amount);
					}
				}

				$RSMempro = $this->Model_member->SearchMemberPromoWhere($member_no, $promo_ids, 1);

				if ($RSMempro['num_rows'] > 0) {
					$this->Model_member->Update_member_promo_status($member_no, 2);
				}
			} else {


				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
			}
		} else {


			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
		}

		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}

	public function refund_credit(){
		$pg_w_ids = isset($_POST['pg_w_ids']) ? $_POST['pg_w_ids'] : 0;
		$pg_m_ids = isset($_POST['pg_m_ids']) ? $_POST['pg_m_ids'] : 0;
		$remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;

		$log_withdraw_id = $this->Model_function->get_decrypt($pg_w_ids);
		$member_no = $this->Model_function->get_decrypt($pg_m_ids);
		$trx_id=uniqid();
		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";


		if ($log_withdraw_id != false && $member_no != false) {
			$Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);
			if ($Result['num_rows'] > 0 &&in_array($Result['row']->status,[2,5])) {
				$BoolMessage = true;

				$RSMember = $this->Model_member->SearchMemberByIDs($member_no);

				$this->Model_db_log->update_log_withdraw_by_id_s($log_withdraw_id, $remark, 4);

				$RSM_Wallet = $this->Model_member->SearchMemberWalletBymember_no($member_no);
				$amount = 0;
				if ($RSM_Wallet['num_rows'] > 0) {
					if (is_numeric($RSM_Wallet['row']->main_wallet)) {
						$amount = ($Result['row']->amount + $RSM_Wallet['row']->main_wallet);
					}
				}
				$this->Model_member->update_member_wallet($member_no, $amount);

				$this->Model_member->insert_adjust_wallet_history($member_no,$Result['row']->amount,$RSM_Wallet['row']->main_wallet,$remark,$trx_id);


			} else {
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
			}
		} else {
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
		}
		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);

	}

	public function cancle_credit(){
		$pg_w_ids = isset($_POST['pg_w_ids']) ? $_POST['pg_w_ids'] : 0;
		$pg_m_ids = isset($_POST['pg_m_ids']) ? $_POST['pg_m_ids'] : 0;
		$remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;

		$log_withdraw_id = $this->Model_function->get_decrypt($pg_w_ids);
		$member_no = $this->Model_function->get_decrypt($pg_m_ids);

		$BoolMessage = false;
		$boolError = 0;
		$boolCheckForm = true;
		$MessageErrorText = "";


		if($log_withdraw_id != false && $member_no != false){
			$Result = $this->Model_db_log->search_log_withdraw_by_id($log_withdraw_id);
			if($Result['num_rows'] > 0){
				$BoolMessage = true;

				$this->Model_db_log->update_log_withdraw_by_id_s($log_withdraw_id,$remark,3);

				$this->Model_member->reset_member_promo($member_no);

			} else {
				$BoolMessage = false;
				$boolError = 1;
				$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
			}
		} else {
			$BoolMessage = false;
			$boolError = 1;
			$MessageErrorText = "ไม่สามารถทำรายการได้ !!";
		}
		$json_arr = array('Message' => $BoolMessage, 'ErrorText' => $MessageErrorText, 'boolError' => $boolError);
		echo json_encode($json_arr);
	}
	public function check_have_session(){
		echo $this->session->userdata('Username');
	}
}
?>