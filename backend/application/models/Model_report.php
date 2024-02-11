<?php
class Model_report extends CI_Model{

	public function DashbordDay($PGDate){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");
		$datetoday_back=date('Y-m-d', strtotime("-1 day", strtotime($PGDate))); 
		$Result['deposit_today_amount'] = 0;
		$deposit_today_amount = 0;
		$num_rows_first_deposit_today=0;
		$first_deposit_sum=0;
		$count100=0;$count120=0;$count150=0;$count200=0;
		$Result['withdraw_today_amount'] = 0;
		$withdraw_today_amount = 0;
		$Result['profit_percent'] = "0%";
		$Result['deposit_today_count'] = 0;
		$Result['withdraw_today_count'] = 0;
		$Result['aff_claimed_amount'] = 0;
		$Result['claim_comm_amount'] = 0;
		$Result['claim_return_loss_amount'] = 0;
		$Result['mt_amount'] = 0;
		$Result['add_credit_amount'] = 0;
		$Result['del_credit_amount'] = 0;
		$Result['bonus_cardgame'] = 0;
		$Result['bonus_luckywheel_amount'] = 0;
		$Result['sms_delayed'] = "0 / 0";
		$Result['bank_not_found'] = "0 / 0";
		$Result['promop50p_sum'] = 0;
		$Result['promop100p_sum'] = 0;
		$Result['p122_amount'] = 0;
		$Result['p103_amount'] = 0;
		$Result['p100_amount'] = 0;
		$Result['p122_amount_actual'] = 0;
		$Result['p103_amount_actual'] = 0;
		$Result['p100_amount_actual'] = 0;
		$Result['topup_truewallet'] = 0;
		$Result['promop2p_desposit_sum'] = 0; 
		$Result['promop2p_withdraw_sum'] = 0; 
		$Result['promofree50_count'] = 0;
		$Result['promofree50_deposit'] = "0.00";
		$Result['sum_profree50_total'] = "0.00";
		$Result['sum_profree50_actual'] = "0.00";
		$Result['sum_profree50_dif'] = "0.00";

		$Result['promofree60_count'] = 0;
		$Result['promofree60_deposit'] = "0.00";
		$Result['sum_profree60_total'] = "0.00";
		$Result['sum_profree60_actual'] = "0.00";
		$Result['sum_profree60_dif'] = "0.00";
		$Result['remain'] = "0.00";
		$Result['promop29_accept'] =0;
		$Result['promop29_summary'] =0; 
		$Result['promop79_accept'] =0;
		$Result['promop79_summary'] =0; 
		$Result['promopmv50_accept'] =0;
		$Result['promopmv50_summary'] =0; 
		$Result['promop122_accept'] =0;
		$Result['promop122_summary'] =0;
		$Result['promop124_accept'] =0;
		$Result['promop124_summary'] =0;
		$Result['promop125_accept'] =0;
		$Result['promop125_summary'] =0;
		$Result['promop34_accept'] =0;
		$Result['promop34_summary'] =0;
		$Result['promop37_accept'] =0;
		$Result['promop37_summary'] =0;
		$Result['promop140_accept'] =0;
		$Result['promop140_summary'] =0;
		$Result['promop141_accept'] =0;
		$Result['promop141_summary'] =0;
		$Result['promop101_accept'] =0;
		$Result['promop101_summary'] =0;
		//ยอดคงค้าง  
		$this->db->select('SUM(main_wallet) AS remain', FALSE);
		$this->db->where('main_wallet >= ', 1);
		$sql_remain = $this->db->get('member_wallet');
		$item_remain = $sql_remain->row();
		if (isset($item_remain)){
			$Result['remain'] = number_format($item_remain->remain,2);
		}  
  
		// รายการถอน  
		$withdraw_data=[];  
		$withdraw_data['pro_another_num']=0;$withdraw_data['pro_another_sum']=0;$withdraw_data['pro_another_amount_actual']=0;
		$withdraw_data['free50_num']=0;$withdraw_data['free50_sum']=0;$withdraw_data['free50_amount_actual']=0;
		$withdraw_data['free60_num']=0;$withdraw_data['free60_sum']=0;$withdraw_data['free60_amount_actual']=0; 
		$withdraw_data['pro2_num']=0;$withdraw_data['pro2_sum']=0;$withdraw_data['pro2_amount_actual']=0; 
		$withdraw_data['pro102_num']=0;$withdraw_data['pro102_sum']=0;$withdraw_data['pro102_amount_actual']=0; 
		$withdraw_data['pro103_num']=0;$withdraw_data['pro103_sum']=0;$withdraw_data['pro103_amount_actual']=0; 
		$withdraw_data['pro3_num']=0;$withdraw_data['pro3_sum']=0;$withdraw_data['pro3_amount_actual']=0; 
		$withdraw_data['pro4_num']=0;$withdraw_data['pro4_sum']=0;$withdraw_data['pro4_amount_actual']=0; 
		$withdraw_data['pro5_num']=0;$withdraw_data['pro5_sum']=0;$withdraw_data['pro5_amount_actual']=0;
		$withdraw_data['pro31_num']=0;$withdraw_data['pro31_sum']=0;$withdraw_data['pro31_amount_actual']=0;
		$withdraw_data['pro32_num']=0;$withdraw_data['pro32_sum']=0;$withdraw_data['pro32_amount_actual']=0;
		$withdraw_data['pro33_num']=0;$withdraw_data['pro33_sum']=0;$withdraw_data['pro33_amount_actual']=0;
		$withdraw_data['pro34_num']=0;$withdraw_data['pro34_sum']=0;$withdraw_data['pro34_amount_actual']=0;
		$withdraw_data['pro37_num']=0;$withdraw_data['pro37_sum']=0;$withdraw_data['pro37_amount_actual']=0;
		$withdraw_data['pro120_num']=0;$withdraw_data['pro120_sum']=0;$withdraw_data['pro120_amount_actual']=0;
		$withdraw_data['pro121_num']=0;$withdraw_data['pro121_sum']=0;$withdraw_data['pro121_amount_actual']=0;
		$withdraw_data['pro122_num']=0;$withdraw_data['pro122_sum']=0;$withdraw_data['pro122_amount_actual']=0;
		$withdraw_data['pro51_num']=0;$withdraw_data['pro51_sum']=0;$withdraw_data['pro51_amount_actual']=0;
		$withdraw_data['pro12_num']=0;$withdraw_data['pro12_sum']=0;$withdraw_data['pro12_amount_actual']=0;
		$withdraw_data['pro83_num']=0;$withdraw_data['pro83_sum']=0;$withdraw_data['pro83_amount_actual']=0;
		$withdraw_data['pro87_num']=0;$withdraw_data['pro87_sum']=0;$withdraw_data['pro87_amount_actual']=0;
		$withdraw_data['pro90_num']=0;$withdraw_data['pro90_sum']=0;$withdraw_data['pro90_amount_actual']=0;
		$withdraw_data['pro95_num']=0;$withdraw_data['pro95_sum']=0;$withdraw_data['pro95_amount_actual']=0;
		$withdraw_data['pro112_num']=0;$withdraw_data['pro112_sum']=0;$withdraw_data['pro112_amount_actual']=0;
		$withdraw_data['pro113_num']=0;$withdraw_data['pro113_sum']=0;$withdraw_data['pro113_amount_actual']=0;
		$withdraw_data['pro114_num']=0;$withdraw_data['pro114_sum']=0;$withdraw_data['pro114_amount_actual']=0; 
		$withdraw_data['pro125_num']=0;$withdraw_data['pro125_sum']=0;$withdraw_data['pro125_amount_actual']=0; 
		$withdraw_data['pro11_num']=0;$withdraw_data['pro11_sum']=0;$withdraw_data['pro11_amount_actual']=0; 
		$withdraw_data['pro140_num']=0;$withdraw_data['pro140_sum']=0;$withdraw_data['pro140_amount_actual']=0; 
		$withdraw_data['pro141_num']=0;$withdraw_data['pro141_sum']=0;$withdraw_data['pro141_amount_actual']=0; 
		$withdraw_data['pro101_num']=0;$withdraw_data['pro101_sum']=0;$withdraw_data['pro101_amount_actual']=0; 
		$proqlist=[10,11,12,19,25,34,37,112,113,114,103,101,122,125,140,141,142,144,147,152,182];
		$x_loop=sizeof($proqlist);
		for ($i=0; $i < $x_loop; $i++) { 
			$index_pro=$proqlist[$i];
			if(!isset($Result["promop{$index_pro}_accept"])){
				$Result["promop{$index_pro}_accept"] =0;
				$Result["promop{$index_pro}_summary"] =0;
			}
			if(!isset($withdraw_data["pro{$index_pro}_num"])){
				$withdraw_data["pro{$index_pro}_num"]=0;
				$withdraw_data["pro{$index_pro}_sum"]=0;
				$withdraw_data["pro{$index_pro}_amount_actual"]=0; 
			}
		}

		$this->db->select('amount,amount_actual,remark,promo', FALSE);  
		$this->db->where('amount > ', 0);
		$this->db->where('status = ', 1);	
		$this->db->where('trx_date = ', $PGDate);		
		$rows_item = $this->db->get('log_withdraw')->result_array();
		foreach ($rows_item as $k => $v) {
			$Result['withdraw_today_count']++;
			$Result['withdraw_today_amount']+=(double)$v['amount_actual'];
			switch ($v['promo']) { 
				 case 101: // Pro 10%
				 case 34: //โบนัสพิเศษฉลองวันหยุดยาว
				 case 37: //ฝากประจำ
				 case 25: //ทุนน้อย
				 case 140: //ฝาก 25 รับ 100
				 case 141: //โปร 2
				 case 142: //โปร 3	
				 case 143: //โปร 4		
				 case 144: //โปร 5
				 case 145: //โปร 6	
				 case 146: //โปร 7		
				 case 147: //โปร 8
				 case 152: //โปร 8	
				 case 182: // สำหรับลูกค้าที่มีประวัติฝากเงิน 20 บาทขึ้นไป      
					$withdraw_data['pro'.$v['promo'].'_num']++;$withdraw_data['pro'.$v['promo'].'_sum']+=(double)$v['amount']; $withdraw_data['pro'.$v['promo'].'_amount_actual']+=(double)$v['amount_actual'];break;
				 //โปรสงกรานต์สาดกระจายส์
				 case 125:$withdraw_data['pro'.$v['promo'].'_num']++;$withdraw_data['pro'.$v['promo'].'_sum']+=(double)$v['amount']; $withdraw_data['pro'.$v['promo'].'_amount_actual']+=(double)$v['amount_actual'];break;
				 //โปรวาเลนไทน์ 50%	
				 case 122:$withdraw_data['pro'.$v['promo'].'_num']++;$withdraw_data['pro'.$v['promo'].'_sum']+=(double)$v['amount']; $withdraw_data['pro'.$v['promo'].'_amount_actual']+=(double)$v['amount_actual'];break;
				 // โปรฯ 29 
				 case 112:$withdraw_data['pro112_num']++;$withdraw_data['pro112_sum']+=(double)$v['amount']; $withdraw_data['pro112_amount_actual']+=(double)$v['amount_actual'];break;
				 // โปรฯ 79 
				 case 113:$withdraw_data['pro113_num']++;$withdraw_data['pro113_sum']+=(double)$v['amount']; $withdraw_data['pro113_amount_actual']+=(double)$v['amount_actual'];break;
				 //โปรลูกค้าเก่าย้ายค่าย 50% สูงสุด 200
				 case 114:$withdraw_data['pro114_num']++;$withdraw_data['pro114_sum']+=(double)$v['amount']; $withdraw_data['pro114_amount_actual']+=(double)$v['amount_actual'];break;
				 //Free50
				case 13:$withdraw_data['free50_num']++;$withdraw_data['free50_sum']+=(double)$v['amount']; $withdraw_data['free50_amount_actual']+=(double)$v['amount_actual'];break;
				// Free 100
				case 14:$withdraw_data['free60_num']++;$withdraw_data['free60_sum']+=(double)$v['amount']; $withdraw_data['free60_amount_actual']+=(double)$v['amount_actual'];break;
				//// โปรฯ 100% สมัครใหม่ ไม่เกิน 200 ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000
				case 11:$withdraw_data['pro2_num']++;$withdraw_data['pro2_sum']+=(double)$v['amount']; $withdraw_data['pro2_amount_actual']+=(double)$v['amount_actual'];break;
				// โปรฯ 50% สมัครใหม่ ฝาก 200 รับเพิ่ม 100 เป็น 300 - ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000
				case 10:$withdraw_data['pro102_num']++;$withdraw_data['pro102_sum']+=(double)$v['amount']; $withdraw_data['pro102_amount_actual']+=(double)$v['amount_actual'];break;
				//proรับปรุงเว็บ
				case 103:$withdraw_data['pro103_num']++;$withdraw_data['pro103_sum']+=(double)$v['amount']; $withdraw_data['pro103_amount_actual']+=(double)$v['amount_actual'];break;
				// Happy Time (Deposit 400, get more 100)
				case 106:$withdraw_data['pro4_num']++;$withdraw_data['pro4_sum']+=(double)$v['amount']; $withdraw_data['pro4_amount_actual']+=(double)$v['amount_actual'];break;
				 // ขาประจำ เราจัดให้
				case 108:$withdraw_data['pro5_num']++;$withdraw_data['pro5_sum']+=(double)$v['amount']; $withdraw_data['pro5_amount_actual']+=(double)$v['amount_actual'];break;
				// สมาชิกใหม่ คาสิโน รับ 60% สูงสุด 1,000 ฝากแรก
				//case 31:$withdraw_data['pro31_num']++;$withdraw_data['pro31_sum']+=(double)$v['amount']; $withdraw_data['pro31_amount_actual']+=(double)$v['amount_actual'];break;
				// โบนัส 100% ฝาก 100 รับฟรีอีก 100 เทิร์น 12 เท่า ถอนได้ทันที สูงสุด 1,500 (1 user / 1 สิทธิ์)
				//case 33:$withdraw_data['pro33_num']++;$withdraw_data['pro33_sum']+=(double)$v['amount']; $withdraw_data['pro33_amount_actual']+=(double)$v['amount_actual'];break;
			    // ฝากแรกของวัน ฝาก 300 รับ 50 เป็น 350 - เทิร์น 5 เท่า - ถอนไม่อั้น casino
				case 50:$withdraw_data['pro120_num']++;$withdraw_data['pro120_sum']+=(double)$v['amount']; $withdraw_data['pro120_amount_actual']+=(double)$v['amount_actual'];break;
				// รับโบนัส 2% ทั้งวัน - เทิร์น 5 เท่า - สูงสุด 1,000 - ถอนไม่อั้น  casino
				case 51:$withdraw_data['pro121_num']++;$withdraw_data['pro121_sum']+=(double)$v['amount']; $withdraw_data['pro121_amount_actual']+=(double)$v['amount_actual'];break;
				
				//case 122:$withdraw_data['pro122_num']++;$withdraw_data['pro122_sum']+=(double)$v['amount']; $withdraw_data['pro122_amount_actual']+=(double)$v['amount_actual'];break;
				 // ยอดการเล่น 5 อันดับสูงสุดรายวัน
				case 19:$withdraw_data['pro51_num']++;$withdraw_data['pro51_sum']+=(double)$v['amount']; $withdraw_data['pro51_amount_actual']+=(double)$v['amount_actual'];break;
				// ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing
				case 12:$withdraw_data['pro12_num']++;$withdraw_data['pro12_sum']+=(double)$v['amount']; $withdraw_data['pro12_amount_actual']+=(double)$v['amount_actual'];break;
				///ฝากประจำ 3 วัน
				case 83:$withdraw_data['pro83_num']++;$withdraw_data['pro83_sum']+=(double)$v['amount']; $withdraw_data['pro83_amount_actual']+=(double)$v['amount_actual'];break;
				//ฝากประจำ 7 วัน
				case 87:$withdraw_data['pro87_num']++;$withdraw_data['pro87_sum']+=(double)$v['amount']; $withdraw_data['pro87_amount_actual']+=(double)$v['amount_actual'];break;
				//ฝากประจำ 10 วัน
				case 90:$withdraw_data['pro90_num']++;$withdraw_data['pro90_sum']+=(double)$v['amount']; $withdraw_data['pro90_amount_actual']+=(double)$v['amount_actual'];break;
				//ฝากประจำ 15 วัน
				case 95:$withdraw_data['pro95_num']++;$withdraw_data['pro95_sum']+=(double)$v['amount']; $withdraw_data['pro95_amount_actual']+=(double)$v['amount_actual'];break;
				default:$withdraw_data['pro_another_num']++;$withdraw_data['pro_another_sum']+=(double)$v['amount'];$withdraw_data['pro_another_amount_actual']+=(double)$v['amount_actual']; break;
			}
		}

		$deposit_pro_info=[];   
		// รายการฝาก โปรทั้งหมด 
		$this->db->where('trx_date >= ', $PGDate." 00:00:00");
		$this->db->where('trx_date <= ', $PGDate." 23:59:59");
		$this->db->where_in('channel', $proqlist); 
		$sql = $this->db->get('view_deposit_pro_perday');
		$result_deposit_pro_perday=$sql->result_array();
		$size_result_deposit_pro_perday=(is_array($result_deposit_pro_perday)?sizeof($result_deposit_pro_perday):0);
		if($size_result_deposit_pro_perday>0){
			for ($i=0; $i < $size_result_deposit_pro_perday; $i++) { 
				$v=$result_deposit_pro_perday[$i];
				if (!isset($deposit_pro_info['sumpro_'.$v['channel']])) {
					$deposit_pro_info['sumpro_'.$v['channel']]=0;
				}
				if (!isset($deposit_pro_info['countpro_'.$v['channel']])) {
					$deposit_pro_info['countpro_'.$v['channel']]=0;
				}
				if (!isset($deposit_pro_info['total_'.$v['channel']])&&$v['channel']==114) {
						$this->db->select('SUM(log_deposit.amount) as amount', FALSE);
						$this->db->join('log_deposit', 'log_deposit.id = pro_logs_promotion.deposit_id');
						$this->db->where('pro_logs_promotion.create_at >= ', $PGDate." 00:00:00");
						$this->db->where('pro_logs_promotion.create_at <= ', $PGDate." 23:59:59");
						$this->db->where_in('log_deposit.channel', [1,2,3,5]);  
						$this->db->where('log_deposit.promo', $v['promo']);  
						$this->db->where('log_deposit.status', 1);  
						$query = $this->db->get('pro_logs_promotion');  
						$deposit_pro_info['total_'.$v['channel']]=0;
						if($query !== FALSE && $query->num_rows() > 0){ 
							$row = $query->row();
							$deposit_pro_info['total_'.$v['channel']]=$row->amount;
						} 
				} 
				$deposit_pro_info['sumpro_'.$v['channel']]+=is_numeric($deposit_pro_info['sumpro_'.$v['channel']])?$v['amount']:0;
				$deposit_pro_info['countpro_'.$v['channel']]+=is_numeric($deposit_pro_info['countpro_'.$v['channel']])?1:0;  
			} 
		}  
	
		//ยอดฝาก - วันนี้   
		$this->db->select('sum(amount) as deposit_today_amount, count(*) as deposit_today_count');
		$this->db->where('trx_date =', $PGDate);
		$sql = $this->db->get('view_deposit_day_amount');
		$item = $sql->row();
		if (isset($item)){
			$Result['deposit_today_amount'] = number_format($item->deposit_today_amount,2);
			$deposit_today_amount = $item->deposit_today_amount;
			//รายการฝาก
			$Result['deposit_today_count'] = number_format($item->deposit_today_count);
		}
       //ยอดถอน - วันนี้
	   $Result['withdraw_today_count'] =number_format($Result['withdraw_today_count']);
	   $withdraw_today_amount =  $Result['withdraw_today_amount'];
	   //รายการถอน
	   $Result['withdraw_today_amount']=number_format($withdraw_today_amount,2);

       //สมัครใหม่(วันนี้)
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('create_at >= ', $PGDate." 00:00:00");
		$this->db->where('create_at <= ', $PGDate." 23:59:59");
		$num_rows_user_day = $this->db->get('members')->row()->num_rows;
		//สมาชิกในระบบ
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$num_rows_user_all_system = $this->db->get('members')->row()->num_rows;
		//สมัครใหม่(วันนี้)	
		$this->db->select('amount', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$rows_first_deposit_today=$this->db->get('1st_deposit')->result_array(); 
		foreach ($rows_first_deposit_today as $v) {
			$num_rows_first_deposit_today++;
			$first_deposit_sum+=(double)$v['amount'];
		}  
		// รอดำเนินการ(วันนี้)
		$this->db->select('COUNT(id) AS deposit_pending_count', FALSE);
		$this->db->where('amount >', 0);
		$this->db->where('status =', 2);
		$this->db->where('trx_date =', $PGDate);
		$deposit_pending_count = $this->db->get('log_deposit')->row()->deposit_pending_count;
		// รอดำเนินการ(วันนี้) ถอน
		$this->db->select('COUNT(id) AS withdraw_pending_count', FALSE);
		$this->db->where('amount >', 0);
		$this->db->where('status =', 2);
		$this->db->where('trx_date =', $PGDate);
		$withdraw_pending_count = $this->db->get('log_withdraw')->row()->withdraw_pending_count;
       //แนะนำเพื่อน
		$this->db->select('sum(amount) AS aff_claimed_amount', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$sql = $this->db->get('log_claim_aff');
		$item = $sql->row();
		if (isset($item)){
			$Result['aff_claimed_amount'] = (int)$item->aff_claimed_amount;
	 	}   
		//ยอดรับค่าคอม   
		$this->db->select('sum(amount) AS claim_comm_amount', FALSE);
		$this->db->where('date_com = ', $datetoday_back);
		$this->db->where('status = ', 1); /// where เฉพาะโอนเข้ากระเป๋าหลัก 
		$sql = $this->db->get('member_a_commission');
		$item = $sql->row();
		if (isset($item)){
			$Result['claim_comm_amount'] = $item->claim_comm_amount;
		}  
		//ยอดรับคืนยอดเสีย 
		$this->db->select('sum(amount) AS claim_return_loss_amount', FALSE);
		$this->db->where('date_return = ', $datetoday_back);
		$this->db->where('status = ', 1); /// where เฉพาะโอนเข้ากระเป๋าหลัก 
		$sql = $this->db->get('member_a_return_loss');
		$item = $sql->row();
		if (isset($item)){
			$Result['claim_return_loss_amount'] = $item->claim_return_loss_amount;
		}  
		//เติมมือ SCB Manual-Topup SCB 
		$this->db->select('sum(amount) AS mt_amount ', FALSE);
		$this->db->where('openration_type = ', $this->configdata->toOrdinal('Manual-Topup SCB')); 
		$this->db->where(' DATE(update_date) =', $PGDate);
		$sql = $this->db->get('log_add_credit');
		$item = $sql->row();
		if (isset($item)){
			$Result['mt_amount'] = number_format($item->mt_amount,2);
		}  
		//เติมมือ Kbank Manual-Topup Kbank 
		$this->db->select('sum(amount) AS mt_amount', FALSE);
		$this->db->where('openration_type = ', $this->configdata->toOrdinal('Manual-Topup KBANK')); 
		$this->db->where('DATE(update_date) =', $PGDate);
		$sql = $this->db->get('log_add_credit');
		$item = $sql->row();
		if (isset($item)){
			$Result['topup_kbank_amount'] = number_format($item->mt_amount,2);
		} 

		//เติมมือ TrueWallet Manual-Topup True Wallet  
		$this->db->select('sum(amount) AS mt_amount', FALSE);
		$this->db->where('openration_type =', $this->configdata->toOrdinal('Manual-Topup TrueWallet')); 
		$this->db->where('DATE(update_date) =', $PGDate);
		$sql = $this->db->get('log_add_credit');
		$item = $sql->row();
		if (isset($item)){
			$Result['topup_truewallet'] = number_format($item->mt_amount,2);
		}  
		//เติมเงิน (วันนี้)
		//,$this->configdata->toOrdinal('SMS Delayed')
        $not_in_add_credit=[$this->configdata->toOrdinal('Manual-Topup SCB'),$this->configdata->toOrdinal('Manual-Topup KBANK')
		    ,$this->configdata->toOrdinal('Manual-Topup TrueWallet')
			,$this->configdata->toOrdinal('VizPay <= Manual-Topup')
			,$this->configdata->toOrdinal('Free200'),$this->configdata->toOrdinal('Free60')];
		$this->db->select('sum(amount) AS add_credit_amount', FALSE);  
		$this->db->where_not_in('openration_type',$not_in_add_credit);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$sql = $this->db->get('log_add_credit');
		$item = $sql->row();  
		if (isset($item)){
			$Result['add_credit_amount'] = number_format($item->add_credit_amount,2);
		}  
        //ลบเครดิต (วันนี้)
		$this->db->select('sum(amount) AS del_credit_amount', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$sql = $this->db->get('log_del_credit');
		$item = $sql->row();
		if (isset($item)){
			$Result['del_credit_amount'] = number_format($item->del_credit_amount,2);
		}  
       //เปิดไพ่
		$this->db->select('sum(amount) AS bonus_cardgame_amount', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$sql = $this->db->get('bonus_cardgame');
		$item = $sql->row();
		if (isset($item)){
			$Result['bonus_cardgame'] = (int)$item->bonus_cardgame_amount;
		}  
		//วงล้อ
		$this->db->select('sum(amount) AS bonus_luckywheel_amount', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$sql = $this->db->get('bonus_luckywheel');
		$item = $sql->row();
		if (isset($item)){
			$Result['bonus_luckywheel_amount'] =(int) $item->bonus_luckywheel_amount;
		}  
		//SMS Delayed
		$this->db->select('count(id) as sms_delayed_count, sum(amount) as sms_delayed_sum', FALSE);
		$this->db->where('status =', 5);
		$this->db->where('trx_date >= ', $PGDate." 00:00:00");
		$this->db->where('trx_date <= ', $PGDate." 23:59:59");
		$this->db->like('remark', 'SMS delayed', 'after');
		$this->db->group_by("status");
		$sql = $this->db->get('log_deposit');
		$item = $sql->row();
		if (isset($item)){
			$Result['sms_delayed'] = number_format($item->sms_delayed_sum,2). ' / ' . $item->sms_delayed_count;
		} 
       //ไม่พบบัญชี
		$this->db->select('count(id) as acct_notfound_count, sum(amount) as acct_notfound_sum', FALSE);
		$this->db->where('status =', 2);
		$this->db->where('member_no =', 0);
		$this->db->where('remark =', 'ไม่พบบัญชี');
		$this->db->where('trx_date >= ', $PGDate." 00:00:00");
		$this->db->where('trx_date <= ', $PGDate." 23:59:59");
		$this->db->group_by("status,remark");
		$sql = $this->db->get('log_deposit');
		$item = $sql->row();
		if (isset($item)){
			$Result['bank_not_found'] = number_format($item->acct_notfound_sum,2). ' / ' . $item->acct_notfound_count;
		} 
        //Active deposite user
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('(channel <= 3 OR channel = 5)');
		$this->db->where('status = ', 1);
		$this->db->where('trx_date = ', $PGDate);
		$this->db->group_by("member_no");
		$active_deposit = $this->db->get('log_deposit')->num_rows();

         //สำหรับลูกค้าที่มีประวัติฝากเงิน 20 บาทขึ้นไป      
		$promop182_sum_accept=(isset($deposit_pro_info['sumpro_182'])?$deposit_pro_info['sumpro_182']:0);
		$promop182_count_accept=(isset($deposit_pro_info['countpro_182'])?$deposit_pro_info['countpro_182']:0);
		//โปรเราเพิ่มให้ 5% โบนัสสูงสุด 8888
		$promop144_sum_accept=(isset($deposit_pro_info['sumpro_144'])?$deposit_pro_info['sumpro_144']:0);
		$promop144_count_accept=(isset($deposit_pro_info['countpro_144'])?$deposit_pro_info['countpro_144']:0); 
		//8.8 แจกสนั่น 8 วันติด
		$promop147_sum_accept=(isset($deposit_pro_info['sumpro_147'])?$deposit_pro_info['sumpro_147']:0);
		$promop147_count_accept=(isset($deposit_pro_info['countpro_147'])?$deposit_pro_info['countpro_147']:0); 
		//กิจกรรม reward		 
		$promop152_sum_accept=(isset($deposit_pro_info['sumpro_152'])?$deposit_pro_info['sumpro_152']:0);
		$promop152_count_accept=(isset($deposit_pro_info['countpro_152'])?$deposit_pro_info['countpro_152']:0); 

		//โปรลงทุนน้อยกำไรก้อนโต
		$promop142_sum_accept=(isset($deposit_pro_info['sumpro_142'])?$deposit_pro_info['sumpro_142']:0);
		$promop142_count_accept=(isset($deposit_pro_info['countpro_142'])?$deposit_pro_info['countpro_142']:0); 

		$promop141_sum_accept=(isset($deposit_pro_info['sumpro_141'])?$deposit_pro_info['sumpro_141']:0);
		$promop141_count_accept=(isset($deposit_pro_info['countpro_141'])?$deposit_pro_info['countpro_141']:0); 

		//โปรโบนัส 20%
		$promop101_sum_accept=(isset($deposit_pro_info['sumpro_101'])?$deposit_pro_info['sumpro_101']:0);
		$promop101_count_accept=(isset($deposit_pro_info['countpro_101'])?$deposit_pro_info['countpro_101']:0); 

		//ฝาก 25 รับ 100
		$promop140_sum_accept=(isset($deposit_pro_info['sumpro_140'])?$deposit_pro_info['sumpro_140']:0);
		$promop140_count_accept=(isset($deposit_pro_info['countpro_140'])?$deposit_pro_info['countpro_140']:0); 

		//โปรโมชั่นประจำเดือน
		$promop112_sum_accept=(isset($deposit_pro_info['sumpro_112'])?$deposit_pro_info['sumpro_112']:0);
		$promop112_count_accept=(isset($deposit_pro_info['countpro_112'])?$deposit_pro_info['countpro_112']:0); 

		//ทุนน้อย
		$promop25_sum_accept=(isset($deposit_pro_info['sumpro_25'])?$deposit_pro_info['sumpro_25']:0);
		$promop25_count_accept=(isset($deposit_pro_info['countpro_25'])?$deposit_pro_info['countpro_25']:0); 

		//โบนัสพิเศษฉลองวันหยุดยาว
		$promop37_sum_accept=(isset($deposit_pro_info['sumpro_37'])?$deposit_pro_info['sumpro_37']:0);
		$promop37_count_accept=(isset($deposit_pro_info['countpro_37'])?$deposit_pro_info['countpro_37']:0); 

		//โบนัสพิเศษฉลองวันหยุดยาว
		$promop34_sum_accept=(isset($deposit_pro_info['sumpro_34'])?$deposit_pro_info['sumpro_34']:0);
		$promop34_count_accept=(isset($deposit_pro_info['countpro_34'])?$deposit_pro_info['countpro_34']:0); 

		//โปรสงกรานต์สาดกระจายส์
		$promop125_sum_accept=(isset($deposit_pro_info['sumpro_125'])?$deposit_pro_info['sumpro_125']:0);
		$promop125_count_accept=(isset($deposit_pro_info['countpro_125'])?$deposit_pro_info['countpro_125']:0); 
		//โปรวาเลนไทน์ 50%	
		$promop122_sum_accept=(isset($deposit_pro_info['sumpro_122'])?$deposit_pro_info['sumpro_122']:0);
		$promop122_count_accept=(isset($deposit_pro_info['countpro_122'])?$deposit_pro_info['countpro_122']:0); 
		// ทำโปร 29
		$promop29_sum_accept=(isset($deposit_pro_info['sumpro_112'])?$deposit_pro_info['sumpro_112']:0);
		$promop29_count_accept=(isset($deposit_pro_info['countpro_112'])?$deposit_pro_info['countpro_112']:0);

		// ทำโปร 79
		$promop79_sum_accept=(isset($deposit_pro_info['sumpro_113'])?$deposit_pro_info['sumpro_113']:0);
		$promop79_count_accept=(isset($deposit_pro_info['countpro_113'])?$deposit_pro_info['countpro_113']:0);
		
		// โปรลูกค้าเก่าย้ายค่าย 50% สูงสุด 200
		$promopmv50_sum_accept=(isset($deposit_pro_info['sumpro_114'])?$deposit_pro_info['sumpro_114']:0);
		$promopmv50_count_accept=(isset($deposit_pro_info['countpro_114'])?$deposit_pro_info['countpro_114']:0);
		$promopmv50_sum_total=(isset($deposit_pro_info['total_114'])?$deposit_pro_info['total_114']:0);
		// ฝากครั้งแรก 20 ได้ 100  - ถอนสูงสด 500 - Slot/Fishing  
		$promop20x100_count=(isset($deposit_pro_info['countpro_12'])?$deposit_pro_info['countpro_12']:0);
		$promop20x100_sum=(isset($deposit_pro_info['sumpro_12'])?$deposit_pro_info['sumpro_12']:0);
		 
		// ฝาก Pro50% (P122)
		$promop50p_count=(isset($deposit_pro_info['countpro_10'])?$deposit_pro_info['countpro_10']:0);
		$Result['promop50p_sum']=(isset($deposit_pro_info['sumpro_10'])?$deposit_pro_info['sumpro_10']:0); 
		
        //ถอน Pro50 50% (P122)
		$Result['p122_amount'] = $withdraw_data['pro102_sum'];
		$Result['p122_amount_actual'] =$withdraw_data['pro102_amount_actual']; 

		// ฝาก ปรับปรุงเว็บ (P103)
		$promop103p_count=(isset($deposit_pro_info['countpro_103'])?$deposit_pro_info['countpro_103']:0);
		$Result['promop103p_sum']=(isset($deposit_pro_info['sumpro_103'])?$deposit_pro_info['sumpro_103']:0); 

		//ถอน ปรับปรุงเว็บ (P103)
		$Result['p103_amount'] = $withdraw_data['pro103_sum'];
		$Result['p103_amount_actual'] =$withdraw_data['pro103_amount_actual']; 

		// $this->db->select('sum(amount_actual) as amount_actual, sum(amount) as amount');
		// $this->db->where('trx_date =', $PGDate);
		// $this->db->where('promo =', 102);
		// $sql = $this->db->get('view_withdraw_day_amount');
		// $item = $sql->row();
		// if(isset($item)){
		// 	$Result['p122_amount'] = number_format($item->amount,2);
		// 	$Result['p122_amount_actual'] =number_format($item->amount_actual,2);
		// }   
		//ฝาก Pro100%
		$promop100p_count=(isset($deposit_pro_info['countpro_11'])?$deposit_pro_info['countpro_11']:0);
		$Result['promop100p_sum']=(isset($deposit_pro_info['sumpro_11'])?$deposit_pro_info['sumpro_11']:0); 
		//ถอน Pro100%
		// $this->db->select('sum(amount_actual) as amount_actual, sum(amount) as amount');
		// $this->db->where('trx_date =', $PGDate);
		// $this->db->where('promo=', 2);
		// $sql = $this->db->get('view_withdraw_day_amount');
		// $item = $sql->row();
		// if(isset($item)){
		// 	$Result['p100_amount'] = ($item->amount)>0?$item->amount:0; 
		// 	$Result['p100_amount_actual'] =($item->amount_actual)>0?$item->amount_actual:0;
		// } 
		// โปรฯ 10% - Slot ฝากประจำ [300] [500] [1000] [3000] [5000]
		$promop10p_sum_all=0;$promop10p_sum_300 =0;$promop10p_sum_500 =0;$promop10p_sum_1000 =0;$promop10p_sum_3000 =0;$promop10p_sum_5000 =0;
		// $this->db->select('type', FALSE);
		// $this->db->where('arrival_date >= ', $PGDate." 00:00:00");
		// $this->db->where('arrival_date <= ', $PGDate." 23:59:59");
		// $rows_item=$this->db->get('promop10p')->result_array();
		// foreach ($rows_item as $v) {
		// 	  $promop10p_sum_all++;
        //       switch ($v['type']) {
		// 		  case 1: $promop10p_sum_300++; break; 
		// 		  case 2: $promop10p_sum_500++; break; 
		// 		  case 3: $promop10p_sum_1000++; break; 
		// 		  case 4: $promop10p_sum_3000++; break;
		// 		  case 5: $promop10p_sum_5000++; break;  
		// 		  default:  break;
		// 	  }
		// } 
       //Happy Time 200 [3pm-4pm]
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('status = ', 1);
		$this->db->where('win_expect = ', 500);
		$this->db->where('promo_date = ', $PGDate);
		$happyTime_01_50 = $this->db->get('promo_happy_time')->row()->num_rows;
		 //Happy Time 500 [3pm-4pm] 
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('status = ', 1);
		$this->db->where('win_expect = ', 1000);
		$this->db->where('promo_date = ', $PGDate);
		$happyTime_01_100 = $this->db->get('promo_happy_time')->row()->num_rows;
		//Happy Time  200 [1am-2am]
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('status = ', 2);
		$this->db->where('win_expect = ', 500);
		$this->db->where('promo_date = ', $PGDate);
		$happyTime_02_50 = $this->db->get('promo_happy_time')->row()->num_rows;
		//Happy Time  500 [1am-2am]
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('status = ', 2);
		$this->db->where('win_expect = ', 1000);
		$this->db->where('promo_date = ', $PGDate);
		$happyTime_02_100 = $this->db->get('promo_happy_time')->row()->num_rows;
        // Welcome back
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");
		$promoWB100_count = $this->db->get('promo_wb100')->row()->num_rows;
       //ขาประจำ[100][120][150][200]
		$this->db->select('amount', FALSE); 
		$this->db->where('update_date >= ', $PGDate." 00:00:00");
		$this->db->where('update_date <= ', $PGDate." 23:59:59");  
		$rows_item=$this->db->get('promo_cc200')->result_array(); 
		foreach ($rows_item as $k => $v) {
			switch ($v['amount']) {
				case 100: $count100++; break;
				case 120: $count120++; break;
				case 150: $count150++; break;
				case 200: $count200++; break;
				default: break;
			 } 
		} 
        //ฝากประจำ [200][500][100][2000]
		$this->db->select('member_no,promo_amount,promo,accept_datetime', FALSE); 
		$this->db->where('accept_date =',$PGDate); 
		$rows_frequency= $this->db->get('promo_deposit_frequency')->result_array();
        $frequ_count200=0;$frequ_count500=0;$frequ_count1000=0;$frequ_count2000=0;
		$frequ_sum200=0;$frequ_sum500=0;$frequ_sum1000=0;$frequ_sum2000=0;
		foreach (@$rows_frequency as $k => $v) {
			 switch ((int)$v['promo']) {
				 case 83:$frequ_count200++;$frequ_sum200+=$v['promo_amount'];  break;
				 case 87:$frequ_count500++;$frequ_sum500+=$v['promo_amount'];   break;
				 case 90:$frequ_count1000++;$frequ_sum1000+=$v['promo_amount'];   break;
				 case 95:$frequ_count2000++;$frequ_sum2000+=$v['promo_amount'];   break;
				 default:  break;
			 }
		}
       //ยอดฝาก TrueWallet
		$this->db->select('sum(amount) as deposit_tw_sum, count(id) as deposit_tw_count', FALSE);
		$this->db->where('amount >', 0);
		$this->db->where('status =', 1);
		$this->db->where('channel =', 2);
		$this->db->where('trx_date = ', $PGDate);
		$RSTruewallet = $this->db->get('log_deposit')->row();
  
		//ยอดฝาก Vizplay
		$this->db->select('sum(amount) as deposit_sum, count(id) as deposit_count', FALSE);
		$this->db->where('amount >', 0);
		$this->db->where('status =', 1);
		$this->db->where('channel =', 1); 
		$this->db->like('remark', 'VizPay', 'after');  
		$this->db->where('trx_date = ', $PGDate);
		$resvizplay = $this->db->get('log_deposit')->row();

		// Free 50  
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('arrival_date >= ', $PGDate." 00:00:00");
		$this->db->where('arrival_date <= ', $PGDate." 23:59:59");
		$Result['promofree50_count'] = $this->db->get('promofree50')->row()->num_rows;
		if($Result['promofree50_count'] > 0){
			$Result['promofree50_deposit'] = number_format(($Result['promofree50_count'] * 50), 2);
		} 
		$Result['sum_profree50_total'] = number_format($withdraw_data['free50_sum'], 2); //รวม
		$Result['sum_profree50_actual'] = number_format($withdraw_data['free50_amount_actual'],2); //ถอนจริง
		$Result['sum_profree50_dif'] = number_format($withdraw_data['free50_sum']-$Result['sum_profree50_actual'],2);//ส่วนต่าง

		$Result['text_promofree50_deposit'] = $Result['promofree50_deposit']." / ".$Result['promofree50_count'];
		$Result['text_sum_profree50_total'] = "[".$Result['sum_profree50_total']."] [".$Result['sum_profree50_actual']."] [".$Result['sum_profree50_dif']."]";
		
		// Free 100
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('arrival_date >= ', $PGDate." 00:00:00");
		$this->db->where('arrival_date <= ', $PGDate." 23:59:59");
		$Result['promofree60_count'] = $this->db->get('promofree60')->row()->num_rows;
		if($Result['promofree60_count'] > 0){
			$Result['promofree60_deposit'] = number_format(($Result['promofree60_count'] * 100), 2);
		}
		$Result['sum_profree60_total'] = number_format($withdraw_data['free60_sum'], 2); //รวม
		$Result['sum_profree60_actual'] = number_format($withdraw_data['free60_amount_actual'],2); //ถอนจริง
		$Result['sum_profree60_dif'] = number_format($Result['sum_profree60_total']-$Result['sum_profree60_actual'],2);//ส่วนต่าง

		$Result['text_promofree60_deposit'] = $Result['promofree60_deposit']." / ".$Result['promofree60_count'];
		$Result['text_sum_profree60_total'] = "[".$Result['sum_profree60_total']."] [".$Result['sum_profree60_actual']."] [".$Result['sum_profree60_dif']."]";
 
		// Happy New Year 
		$Result['promo_hny50_count']=0;
		$Result['promo_hny50_sumtotal']=0; 
		$this->db->select('COUNT(id) AS num_rows', FALSE);
		$this->db->where('accept_date >= ', $PGDate." 00:00:00");
		$this->db->where('accept_date <= ', $PGDate." 23:59:59");
		$itemcount= $this->db->get('promo_hny50')->row()->num_rows; 
		if($itemcount>0){ $Result['promo_hny50_count']=$itemcount;}

		$this->db->select('SUM(promo_amount) AS sumtotal', FALSE);
		$this->db->where('accept_date >= ', $PGDate." 00:00:00");
		$this->db->where('accept_date <= ', $PGDate." 23:59:59");
		$itemhny50= $this->db->get('promo_hny50');  
        if($itemhny50->num_rows()>0){ 
			$Result['promo_hny50_sumtotal']=$itemhny50->row()->sumtotal;
		}      
		
		$Result['hny50'] =number_format($Result['promo_hny50_sumtotal'], 2). " / " .$Result['promo_hny50_count'];

		//โปรฯ 300 รับ 50 (P120) - Casino [ฝาก] 
		$this->db->select('count(1) as total_count', FALSE);  
		$this->db->where('accept_date = ', $PGDate);
		$promop300p_desposit_count = $this->db->get('promop120')->row()->total_count; 
		$Result['promop300p_desposit_sum']=((int)$promop300p_desposit_count)*50; 
		//โปรฯ 300 รับ 50 (P120) - Casino [ถอน]
		$promop300p_withdraw_count=$withdraw_data['pro120_num'];
		$Result['promop300p_withdraw_sum']=number_format($withdraw_data['pro120_amount_actual'],2); 

		//โปรฯ 2% ทั้งวัน (P121) - Casino [ฝาก]
		$promop2p_desposit_count=0;
		$this->db->select('promo_amount', FALSE);  
		$this->db->where('accept_date = ', $PGDate);
		$rows_items = $this->db->get('promop121')->result_array();
		foreach ($rows_items as $v) {
			$promop2p_desposit_count++;
			$Result['promop2p_desposit_sum']+= $v['promo_amount'];
		}  
		//โปรฯ 2% ทั้งวัน (P121) - Casino [ถอน]
		$promop2p_withdraw_count=$withdraw_data['pro121_num'];
		$Result['promop2p_withdraw_sum']=number_format($withdraw_data['pro121_amount_actual'],2); 

		//กำไร - วันนี้
		$Result['profit_today'] = ($deposit_today_amount - $withdraw_today_amount);
		$deposit_today_withdraw_today = ($deposit_today_amount-$withdraw_today_amount);
		$deposit_today_100 = ($deposit_today_amount / 100);
		if($deposit_today_100 > 0){
			$Result['profit_percent'] = number_format($deposit_today_withdraw_today/$deposit_today_100,2)."%";
		}

		$Result['profit_color_text'] = "success";
		$Result['profit_icon'] = "ft-trending-up";
		if($Result['profit_today'] < 0){
			$Result['profit_color_text'] = "danger";
			$Result['profit_icon'] = "ft-trending-down";
		} 
		$Result['profit_today_02'] = number_format($Result['profit_today'],2)." (".$Result['profit_percent'].")"; 
		$Result['registered_today'] = number_format($num_rows_user_day);  
		$Result['first_deposit_td'] = number_format($num_rows_first_deposit_today)." / ".number_format($first_deposit_sum,2); 
		$Result['member_count'] = number_format($num_rows_user_all_system);  
		$Result['deposit_pending_withdraw_pending'] = "(D) ".number_format($deposit_pending_count)." - ".number_format($withdraw_pending_count)." (W)"; 
		$Result['active_deposit'] = $active_deposit;   
		$Result['promop50p'] = $Result['promop50p_sum'] . " / " .number_format($promop50p_count); 
		$Result['promop103p'] = $Result['promop103p_sum'] . " / " .number_format($promop103p_count); 
		
		$Result['P122'] = '['.number_format($Result['p122_amount']).'] ['.number_format($Result['p122_amount_actual']).'] [' . number_format(($Result['p122_amount'] - $Result['p122_amount_actual']), 2) . ']';
		$Result['P103'] = '['.number_format($Result['p103_amount']).'] ['.number_format($Result['p103_amount_actual']).'] [' . number_format(($Result['p103_amount'] - $Result['p103_amount_actual']), 2) . ']';
		$Result['promop100p'] =number_format($Result['promop100p_sum']). " / " .number_format($promop100p_count);
		$Result['P100'] = '['.number_format($withdraw_data['pro2_sum']).'] ['.number_format($withdraw_data['pro2_amount_actual']).'] [' . number_format(($withdraw_data['pro2_sum'] - $withdraw_data['pro2_amount_actual']), 2) . ']';
		$Result['promop10p_sum'] = '['.number_format($promop10p_sum_all).']'.'[' . number_format($promop10p_sum_300) . '] [' . number_format($promop10p_sum_500) . '] [' . number_format($promop10p_sum_1000) . '] [' . number_format($promop10p_sum_3000) . '] [' . number_format($promop10p_sum_5000) . ']';
		$Result['HappyTime200'] = '[' . number_format($happyTime_01_50 * 50, 2) . ' / ' . $happyTime_01_50 . '] [' . number_format($happyTime_02_50 * 50, 2) . ' / ' . $happyTime_02_50 . ']';
		$Result['HappyTime400'] = '[' . number_format($happyTime_01_100 * 100, 2) . ' / ' . $happyTime_01_100 . '] [' . number_format($happyTime_02_100 * 100, 2) . ' / ' . $happyTime_02_100 . ']';
		$Result['promoWB100_count'] = number_format($promoWB100_count * 100, 2) . ' / ' . $promoWB100_count;
		$Result['promo_cc'] = '[' . $count100 . ']['  . $count120 . ']['  . $count150 . ']['  . $count200 . ']'; // ขาประจำ
		$Result['promo_frequency'] = '[' .$frequ_count200 . ']['  . $frequ_count500. ']['  . $frequ_count1000. ']['  .$frequ_count2000. ']'; // ฝากประจำ
		$Result['deposit_tw'] = number_format($RSTruewallet->deposit_tw_sum, 2) . ' / ' . $RSTruewallet->deposit_tw_count;
		$Result['deposit_promop20X100p'] =$promop20x100_sum. " / " .number_format($promop20x100_count); 
		$Result['promop20X100p'] ='['.number_format($withdraw_data['pro12_sum']).']'.'['.$withdraw_data['pro12_amount_actual'].']'.'['.number_format(($withdraw_data['pro12_sum']-$withdraw_data['pro12_amount_actual'])).']'; 
		$Result['promop2p_desposit_withdraw_sum'] = "[".$Result['promop2p_desposit_sum']."/". number_format($promop2p_desposit_count)."] [".$Result['promop2p_withdraw_sum']."/". number_format($promop2p_withdraw_count)."]";
		$Result['promop300p_desposit_withdraw_sum'] = "[".$Result['promop300p_desposit_sum']."/". number_format($promop300p_desposit_count)."] [".$Result['promop300p_withdraw_sum']."/". number_format($promop300p_withdraw_count)."]";
		$Result['promop29_accept'] =number_format($promop29_sum_accept). " / " .number_format($promop29_count_accept);
		$Result['promop29_summary'] ='['.number_format($withdraw_data['pro112_sum']).']'.'['.$withdraw_data['pro112_amount_actual'].']'.'['.number_format(($withdraw_data['pro112_sum']-$withdraw_data['pro112_amount_actual'])).']'; 
		$Result['promop79_accept'] =number_format($promop79_sum_accept). " / " .number_format($promop79_count_accept);
		$Result['promop79_summary'] ='['.number_format($withdraw_data['pro113_sum']).']'.'['.$withdraw_data['pro113_amount_actual'].']'.'['.number_format(($withdraw_data['pro113_sum']-$withdraw_data['pro113_amount_actual'])).']'; 
		$Result['promopmv50_accept'] ='['.number_format($promopmv50_sum_total).']['.number_format($promopmv50_sum_accept). "] [" .number_format($promopmv50_count_accept).']';		
		$Result['promopmv50_summary'] ='['.number_format($withdraw_data['pro114_sum']).']'.'['.$withdraw_data['pro114_amount_actual'].']'.'['.number_format(($withdraw_data['pro114_sum']-$withdraw_data['pro114_amount_actual'])).']'; 
		$Result['promop122_accept'] =number_format($promop122_sum_accept). " / " .number_format($promop122_count_accept);
		$Result['promop122_summary'] ='['.number_format($withdraw_data['pro122_sum']).']'.'['.$withdraw_data['pro122_amount_actual'].']'.'['.number_format(($withdraw_data['pro122_sum']-$withdraw_data['pro122_amount_actual'])).']'; 
		$Result['promop125_accept'] =number_format($promop125_sum_accept). " / " .number_format($promop125_count_accept);
		$Result['promop125_summary'] ='['.number_format($withdraw_data['pro125_sum']).']'.'['.$withdraw_data['pro125_amount_actual'].']'.'['.number_format(($withdraw_data['pro125_sum']-$withdraw_data['pro125_amount_actual'])).']'; 
		$Result['promop34_accept'] =number_format($promop34_sum_accept). " / " .number_format($promop34_count_accept);
		$Result['promop34_summary'] ='['.number_format($withdraw_data['pro34_sum']).']'.'['.$withdraw_data['pro34_amount_actual'].']'.'['.number_format(($withdraw_data['pro34_sum']-$withdraw_data['pro34_amount_actual'])).']';
		$Result['promop37_accept'] =number_format($promop37_sum_accept). " / " .number_format($promop37_count_accept);
		$Result['promop37_summary'] ='['.number_format($withdraw_data['pro37_sum']).']'.'['.$withdraw_data['pro37_amount_actual'].']'.'['.number_format(($withdraw_data['pro37_sum']-$withdraw_data['pro37_amount_actual'])).']'; 
		$Result['promop25_accept'] =number_format($promop25_sum_accept). " / " .number_format($promop25_count_accept);
		$Result['promop25_summary'] ='['.number_format($withdraw_data['pro25_sum']).']'.'['.$withdraw_data['pro25_amount_actual'].']'.'['.number_format(($withdraw_data['pro25_sum']-$withdraw_data['pro25_amount_actual'])).']'; 
		$Result['promop112_accept'] =number_format($promop112_sum_accept). " / " .number_format($promop112_count_accept);
		$Result['promop112_summary'] ='['.number_format($withdraw_data['pro112_sum']).']'.'['.$withdraw_data['pro112_amount_actual'].']'.'['.number_format(($withdraw_data['pro112_sum']-$withdraw_data['pro112_amount_actual'])).']'; 
		$Result['promop140_accept'] =number_format($promop140_sum_accept). " / " .number_format($promop140_count_accept);
		$Result['promop140_summary'] ='['.number_format($withdraw_data['pro140_sum']).']'.'['.$withdraw_data['pro140_amount_actual'].']'.'['.number_format(($withdraw_data['pro140_sum']-$withdraw_data['pro140_amount_actual'])).']'; 
		$Result['promop101_accept'] =number_format($promop101_sum_accept). " / " .number_format($promop101_count_accept);
		$Result['promop101_summary'] ='['.number_format($withdraw_data['pro101_sum']).']'.'['.$withdraw_data['pro101_amount_actual'].']'.'['.number_format(($withdraw_data['pro101_sum']-$withdraw_data['pro101_amount_actual'])).']'; 
		$Result['promop141_accept'] =number_format($promop141_sum_accept). " / " .number_format($promop141_count_accept);
		$Result['promop141_summary'] ='['.number_format($withdraw_data['pro141_sum']).']'.'['.$withdraw_data['pro141_amount_actual'].']'.'['.number_format(($withdraw_data['pro141_sum']-$withdraw_data['pro141_amount_actual'])).']'; 
	    $Result['promop142_accept'] =number_format($promop142_sum_accept). " / " .number_format($promop142_count_accept);
		$Result['promop142_summary'] ='['.number_format($withdraw_data['pro142_sum']).']'.'['.$withdraw_data['pro142_amount_actual'].']'.'['.number_format(($withdraw_data['pro142_sum']-$withdraw_data['pro142_amount_actual'])).']'; 
		$Result['promop144_accept'] =number_format($promop144_sum_accept). " / " .number_format($promop144_count_accept);
		$Result['promop144_summary'] ='['.number_format($withdraw_data['pro144_sum']).']'.'['.$withdraw_data['pro144_amount_actual'].']'.'['.number_format(($withdraw_data['pro144_sum']-$withdraw_data['pro144_amount_actual'])).']'; 
		$Result['promop152_accept'] =number_format($promop152_sum_accept). " / " .number_format($promop152_count_accept);
		$Result['promop152_summary'] ='['.number_format($withdraw_data['pro152_sum']).']'.'['.$withdraw_data['pro152_amount_actual'].']'.'['.number_format(($withdraw_data['pro152_sum']-$withdraw_data['pro152_amount_actual'])).']'; 
		$Result['promop147_accept'] =number_format($promop147_sum_accept). " / " .number_format($promop147_count_accept);
		$Result['promop147_summary'] ='['.number_format($withdraw_data['pro147_sum']).']'.'['.$withdraw_data['pro147_amount_actual'].']'.'['.number_format(($withdraw_data['pro147_sum']-$withdraw_data['pro147_amount_actual'])).']'; 
		$Result['promop182_accept'] =number_format($promop182_sum_accept). " / " .number_format($promop182_count_accept);
		$Result['promop182_summary'] ='['.number_format($withdraw_data['pro182_sum']).']'.'['.$withdraw_data['pro182_amount_actual'].']'.'['.number_format(($withdraw_data['pro182_sum']-$withdraw_data['pro182_amount_actual'])).']'; 

		$Result['deposit_vizplay'] = number_format($resvizplay->deposit_sum, 2);//. ' / ' . $resvizplay->deposit_count
		
		$deposit_today2 = $deposit_today_amount;

		$allPromo = $Result['aff_claimed_amount'] + 
					$Result['claim_comm_amount'] + 
					$Result['claim_return_loss_amount'] + 
					$Result['promop50p_sum'] +
                    $Result['bonus_cardgame'] +
                    $Result['bonus_luckywheel_amount'] +
                    ($Result['promofree50_count'] * 50) +
					($Result['promofree60_count'] * 60) +
                    ($promop10p_sum_300 * 30) +
                    ($promop10p_sum_500 * 50) +
                    ($promop10p_sum_1000 * 100) +
                    ($promop10p_sum_3000 * 300) +
                    ($promop10p_sum_5000 * 500) +
                    ($happyTime_01_50 * 50) +
                    ($happyTime_01_100 * 100) +
                    ($happyTime_02_50 * 50) +
                    ($happyTime_02_100 * 100) +
                    ($promoWB100_count * 100) +
					((int)$Result['promop300p_desposit_sum']) + 
                    ($count100*100) +
                    ($count120*120) +
                    ($count150*150) +
                    ($count200*200)+
					($frequ_sum200)+
					($frequ_sum500)+
					($frequ_sum1000)+
					($frequ_sum2000)+
					($promop20x100_sum)+
					($promop29_sum_accept)+($promop79_sum_accept)+
					($promopmv50_sum_accept)+($promop122_sum_accept)+($promop125_sum_accept)+($promop34_sum_accept)+($promop37_sum_accept)
					+($promop140_count_accept)+$promop101_sum_accept+$promop142_sum_accept+$promop152_sum_accept+$promop147_sum_accept+
					+$promop182_sum_accept+
					($Result['promo_hny50_sumtotal']);

        $promoMargin = 0;
        $promoMargin_t = ($deposit_today2 / 100);
        if($promoMargin_t > 0){
        	$promoMargin = $allPromo / $promoMargin_t;
        }
        $Result['overall'] = number_format($deposit_today2,2) . ' / ' . number_format($allPromo,2) . ' => ' . number_format($promoMargin,2) . '%'; 
		return $Result;
	}
	public function ReportMonth($PGYear,$PGMonth){
		$result = $this->db->query( 'CALL sp_report_month('.$PGYear.','.$PGMonth.')');
		return $result->result_array();
	}

	public function ReportMonth_02($PGYear,$PGMonth){ 
		//SELECT deposit_total, withdraw_total, profit FROM reportsum_of_month WHERE YEAR(report_date) = 2021 AND MONTH(report_date) = 11 ORDER BY report_date 
		$result_array = "";
		$row = ""; 
			$this->db->select('deposit_total, withdraw_total, profit, report_date');
			$this->db->where('YEAR(report_date) =', $PGYear);
			$this->db->where('MONTH(report_date) =', $PGMonth);
			$sql = $this->db->get('reportsum_of_month');
			$result_array = $sql->result_array();
			$row = $sql->row(); 
			
		  $result['num_rows'] = (is_array($result_array)?sizeof($result_array):0); 
		  $result['result_array'] = $result_array;
		  $result['row'] = $row;
		return $result;
	}
	public function ReportWinloseByMember($member_no,$provider_name,$timestart,$timeend){
		$result = $this->db->query('CALL spc_reports_winlose('.$member_no.',\''.$provider_name.'\',\''.$timestart.'\',\''.$timeend.'\')'); 
		return $result->result_array();
	}
	public function ReportWinloseProvider($member_no,$provider_name,$timestart,$timeend){
		$result = $this->db->query('CALL spc_reports_memberplay('.$member_no.',\''.$provider_name.'\',\''.$timestart.'\',\''.$timeend.'\')'); 
		return $result->result_array();
	} 

 public function Report_deposit_withdraw_daily($member_no,$timestart,$timeend){
		$result['result_array']=[]; 
		$this->db->select('turnover, deposit, deposit_all, withdraw,money_remove,deposit_pro,create_day');
		$this->db->where('create_at >= ', $timestart);
		$this->db->where('create_at <= ', $timeend);
		$this->db->where('member_no', $member_no); 
		$sql = $this->db->get('report_sumbyday');
		$result_xarray = $sql->result_array();  
		$result['num_rows'] = (is_array($result_xarray)?sizeof($result_xarray):0); 
		$result['result_array'] = $result_xarray; 
	return $result;
 } 
 public function Report_deposit_withdraw_current_daily($member_no,$timeend){
	$result_array = "";
	$date_return=date('Y-m-d', strtotime("0 day"));
	$deposit_total=0;$bonus_all=0;$withdraw_total=0;$turnover_total=0;
	$date = DateTime::createFromFormat('Y-m-d H:i', $timeend);
	if($date->format('Y-m-d')!=date('Y-m-d')){
		return ['create_day'=>$date_return,'deposit_all'=>$deposit_total,'bonus_all'=>$bonus_all,'withdraw'=>$withdraw_total,'turnover'=>$turnover_total];
	} 
	// $this->db->select('SUM(amount) AS total_amount');
	// $this->db->where('trx_date=SUBDATE(CURDATE(),0)', "", false); 
	// $this->db->where('member_no', $member_no); 
	// $sql = $this->db->get('log_deposit');
	   $sql ="SELECT deposit,deposit_all,bonus_all,money_remove    
	    FROM (
		SELECT 
		COALESCE(SUM(CASE WHEN  channel IN (0,1,2,3,5) AND promo =-1 AND remark NOT IN ('Free50', 'Free60', 'Pro50%', 'Pro100%', 'WB50', 'WB100', 'HNY50')  THEN dps.amount ELSE 0 END),0) deposit 
		,COALESCE(SUM(CASE WHEN  channel IN (0,1,2,3,5) THEN dps.amount ELSE 0 END),0) deposit_all 	
		,COALESCE(SUM(CASE WHEN  channel NOT IN (0,1,2,3,5) AND promo !=-1 THEN dps.amount ELSE 0 END),0) bonus_all  
		,(SELECT COALESCE(SUM(amount),0) 	FROM  log_del_credit WHERE CAST(update_date AS DATE) =SUBDATE(CURDATE(),0) AND member_no = dps.member_no) AS money_remove  
		FROM  log_deposit AS dps 
	 WHERE dps.member_no!=0 AND dps.member_no=? AND dps.amount>0 AND dps.status=1 AND dps.trx_date = SUBDATE(CURDATE(),0)  
	 ) AS t   
	ORDER BY deposit DESC";
	$sql=$this->db->query($sql,$member_no);
    $numrows=$sql->num_rows();
	if($numrows>0){
		$row = $sql->row();  
		$deposit_total=(isset($row->deposit_all)?$row->deposit_all:0);  
		$bonus_all=(isset($row->bonus_all)?$row->bonus_all:0);  
	} 
	$this->db->select('SUM(amount) AS total_amount');
	$this->db->where('trx_date=SUBDATE(CURDATE(),0)', "", false); 
	$this->db->where('member_no', $member_no); 
	$sql = $this->db->get('log_withdraw');
	$row = $sql->row();  
	$withdraw_total=(isset($row->total_amount)?$row->total_amount:0);  
	
	$this->db->select('SUM(total_turnover) AS total_amount', FALSE);
	$this->db->where('member_no =', $member_no);  
	$this->db->where('update_date >=', $date_return.' 00:00:00'); 
	$this->db->where('update_date <=', $date_return.' 23:59:59');  
	$sql = $this->db->get('member_turnover_product');  
	$row = $sql->row();  
	$turnover_total=(isset($row->total_amount)?$row->total_amount:0); 
	
   if(!($deposit_total>0 || $withdraw_total>0||$turnover_total>0||$bonus_all>0)){ return null;}
  return ['create_day'=>$date_return,'deposit_all'=>$deposit_total,'bonus_all'=>$bonus_all,'withdraw'=>$withdraw_total,'turnover'=>$turnover_total];
}
public function ReportSummary($timestart, $timeend)
	{
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");
		$data[] = array(
			'deposit' => '', 
			'deposit_count' => '',
			'withdraw' => '',
			'withdraw_count' => '', 
		);

		// $this->db->select('sum(log_deposit.amount) as deposit,sum(log_withdraw.amount) as withdraw ,log_deposit.trx_date as trx_date');
		// $this->db->join('log_withdraw','log_deposit.trx_date = log_withdraw.trx_date', 'LEFT');	
		// $this->db->where('log_deposit.status = ', 1);	
		// $this->db->where_in('log_deposit.channel', [1,2,3,5]);
		// if($timestart && $timeend){
		// 	$this->db->where('log_deposit.trx_date >= ', $timestart);
		// 	$this->db->where('log_deposit.trx_date <= ', $timeend);
		// }
		// $this->db->group_by('log_deposit.trx_date');
		// $this->db->from('log_deposit');

		$this->db->select('sum(amount) as deposit,0 as withdraw ,trx_date');
		$this->db->where('status = ', 1);	
		$this->db->where_in('channel', [1,2,3,5]);
		if($timestart && $timeend){
			$this->db->where('trx_date >= ', $timestart);
			$this->db->where('trx_date <= ', $timeend);
		}
		$this->db->group_by('trx_date');
		$this->db->from('log_deposit');
		$query1 = $this->db->get_compiled_select();

		// $this->db->select('sum(log_deposit.amount) as deposit,sum(log_withdraw.amount) as withdraw ,log_withdraw.trx_date as trx_date');
		// $this->db->join('log_withdraw','log_deposit.trx_date = log_withdraw.trx_date', 'RIGHT');	
		// $this->db->where('log_withdraw.status = ', 1);	
		// $this->db->where_in('log_withdraw.channel', [1,2,3,5]);
		// if($timestart && $timeend){
		// 	$this->db->where('log_withdraw.trx_date >= ', $timestart);
		// 	$this->db->where('log_withdraw.trx_date <= ', $timeend);
		// }
		// $this->db->group_by('log_withdraw.trx_date');
		// $this->db->from('log_deposit');

		$this->db->select('0 as deposit,sum(amount) as withdraw ,trx_date');
		$this->db->where('status = ', 1);	
		$this->db->where_in('channel', [1,2,3,5]);
		if($timestart && $timeend){
			$this->db->where('trx_date >= ', $timestart);
			$this->db->where('trx_date <= ', $timeend);
		}
		$this->db->group_by('trx_date');
		$this->db->from('log_withdraw');
		$query2 = $this->db->get_compiled_select();

		$query = $this->db->query('select sum(t.deposit) as deposit,sum(t.withdraw) as withdraw,t.trx_date from ('.$query1 . ' UNION all ' . $query2.') as t GROUP BY t.trx_date')->result_array();
		return  $query;
	}
	public function ReportCashback($username,$timestart, $timeend)
	{
		if ($timestart) {
			$this->db->where('mrl.date_return >=', $timestart);
		}
		if ($timeend) {
			$this->db->where('mrl.date_return <=', $timeend);
		}
		if ($username) {
			$this->db->like('mb.username',$username);
		}
		$this->db->select('mb.lname,mb.fname,mb.username,mrl.amount,mrl.status,mrl.date_return');
		$this->db->join('members as mb','mrl.member_no = mb.id','left');
		return $this->db->get('member_a_return_loss as mrl')->result_array();
	}
	public function ReportSumCashback1($timestart, $timeend){
		$arr=array();
		if ($timestart) {
			$this->db->where('date_return >=', $timestart);
		}
		if ($timeend) {
			$this->db->where('date_return <=', $timeend);
		}
		$this->db->select('sum(deposit) as deposit,sum(amount) as amount,date_return');
		$this->db->group_by('date_return');
		$date =  $this->db->get('member_a_return_loss')->result_array();
		
		foreach ($date as $key => $value) {
			$date_return = $value['date_return'];
			
			if ($timestart && $timeend) {
				$this->db->where('date_return >=', $timestart);
				$this->db->where('date_return <=', $timeend);
			}
			if($date_return){
				$this->db->where('date_return = ', $date_return);
			}
			$this->db->where('status !=', 2);
			$this->db->select('sum(deposit) as deposit,sum(amount) as amount,status,date_return');
			$this->db->group_by('date_return');
			$date1 = $this->db->get('member_a_return_loss')->row();
			if($date1){
				$data_return = $date1->amount;
			}else{
				$data_return = 0;
			}

			if ($timestart && $timeend) {
				$this->db->where('date_return >=', $timestart);
				$this->db->where('date_return <=', $timeend);
			}
			if($date_return){
				$this->db->where('date_return = ', $date_return);
			}
			$this->db->where('status =', 2);
			$this->db->select('sum(deposit) as deposit,sum(amount) as amount,status');
			$this->db->group_by('date_return');
			$date2 = $this->db->get('member_a_return_loss')->row();
			
			if($date2){
				$data_return_get = $date2->amount;
			}else{
				$data_return_get = 0;
			}
			if($date1 || $date2){
				array_push($arr,array(
					'date_return'=> $date_return,
					'data_return' => $data_return, 
					'data_return_get' => $data_return_get,
					'amount' => $data_return-$data_return_get
				));
			}
			
		}

		return $arr;
	}
	public function ReportDepositWithdraw($date1,$date2){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");

		$arr = array();
		$member = $this->db->get('members')->result_array();
		foreach ($member as $key => $v) {
			$id = $v['id'];
			
			$this->db->select('member_no, sum(amount) as deposit,count(member_no) as count_deposit');
			$this->db->where_in('channel', [1,2,3,5]);
			$this->db->where('status =', 1);
			$this->db->where('member_no =', $id);
			if($date1 && $date2){
				$this->db->where('trx_date >= ', $date1);
				$this->db->where('trx_date <= ', $date2);
			}
			$this->db->group_by('member_no');
			$this->db->order_by('id', 'asc');
			$deposit = $this->db->get('log_deposit')->row();

			if($deposit){
				$deposit_data = $deposit->deposit;
				$deposit_data_count = $deposit->count_deposit;
			}else{
				$deposit_data = 0;
				$deposit_data_count = 0;
			}

			$this->db->select('member_no,sum(amount) as withdraw,count(member_no) as count_with');
			$this->db->where_in('channel', [1,2,3,5]);
			$this->db->where('status =', 1);
			$this->db->where('member_no =', $id);
			if($date1 && $date2){
				$this->db->where('trx_date >= ', $date1);
				$this->db->where('trx_date <= ', $date2);
			}
			$this->db->group_by('member_no');
			$this->db->order_by('id', 'asc');
			$withdraw = $this->db->get('log_withdraw')->row();

			if($withdraw){
				$withdraw_data = $withdraw->withdraw;
				$withdraw_data_count = $withdraw->count_with;
			}else{
				$withdraw_data = 0;
				$withdraw_data_count = 0;
			}
			if($withdraw || $deposit){
				array_push($arr,array(
					'member_no'=> $v['id'],
					'deposit' => $deposit_data, 
					'withdraw' => $withdraw_data, 
					'count_deposit' => $deposit_data_count, 
					'count_withdraw' => $withdraw_data_count, 
					'bank' => $v['bank_name'],
					'bank_accountnumber' => $v['bank_accountnumber'],
				));
			}
		}
		return $arr;
	}
	public function ReportWinloss($date1,$date2){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");
		$data[] = array(
			'deposit' => '', 
			'deposit_count' => '',
			'withdraw' => '',
			'withdraw_count' => '', 
		);

		$this->db->select('member_no, sum(amount) as tot,count(member_no) as count');
		$this->db->where('status = ', 1);	
		$this->db->where_in('channel', [1,2,3,5]);
		if($date1 && $date2){
			$this->db->where('trx_date >= ', $date1);
			$this->db->where('trx_date <= ', $date2);
		}
		$this->db->group_by('member_no');
		$this->db->from('log_deposit');
		$query1 = $this->db->get_compiled_select();

		$this->db->select('member_no, sum(amount) as tot,count(member_no) as count');
		$this->db->where('status = ', 1);	
		if($date1 && $date2){
			$this->db->where('trx_date >= ', $date1);
			$this->db->where('trx_date <= ', $date2);
		}

		$this->db->group_by('member_no');
		$this->db->from('log_withdraw');
		$query2 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . ' UNION ' . $query2)->result();
		return  $query;
	}
	public function ReportPro($date1,$date2,$pro_id){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");

		$arr = array();
		$member = $this->db->get('members')->result_array();
		foreach ($member as $key => $v) {
			$id = $v['id'];
			
			$this->db->select('member_no, sum(amount) as deposit,count(member_no) as count_deposit');
			$this->db->where_in('channel', [1,2,3,5]);
			$this->db->where('status =', 1);
			$this->db->where('member_no =', $id);
			if($date1 && $date2){
				$this->db->where('trx_date >= ', $date1);
				$this->db->where('trx_date <= ', $date2);
			}
			if($pro_id){
				$this->db->where('promo = ', $pro_id);
			}
			$this->db->group_by('member_no');
			$this->db->order_by('id', 'asc');
			$deposit = $this->db->get('log_deposit')->row();

			if($deposit){
				$deposit_data = $deposit->deposit;
				$deposit_data_count = $deposit->count_deposit;
			}else{
				$deposit_data = 0;
				$deposit_data_count = 0;
			}

			$this->db->select('member_no,sum(amount_actual) as withdraw,count(member_no) as count_with');
			// $this->db->where_not_in('channel', [1,2,3,5]);
			$this->db->where('status =', 1);
			$this->db->where('member_no =', $id);
			if($date1 && $date2){
				$this->db->where('trx_date >= ', $date1);
				$this->db->where('trx_date <= ', $date2);
			}
			if($pro_id){
				$this->db->where('promo = ', $pro_id);
			}
			$this->db->group_by('member_no');
			$this->db->order_by('id', 'asc');
			$withdraw = $this->db->get('log_withdraw')->row();

			if($withdraw){
				$withdraw_data = $withdraw->withdraw;
				$withdraw_data_count = $withdraw->count_with;
			}else{
				$withdraw_data = 0;
				$withdraw_data_count = 0;
			}
			if($withdraw || $deposit){
				array_push($arr,array(
					'member_no'=> $v['id'],
					'deposit' => $deposit_data, 
					'withdraw' => $withdraw_data, 
					'count_deposit' => $deposit_data_count, 
					'count_withdraw' => $withdraw_data_count, 
				));
			}
		}
		return $arr;
	}
	public function ReportUserGroup($date1,$date2,$l1,$l2){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");

		$this->db->select('sum(amount) as total, count(member_no) as mem_count');
		if($date1 && $date2){
			$this->db->where('update_date >= ', $date1.' 00:00:00');
			$this->db->where('update_date <= ', $date2.' 00:00:00');
		}
		$this->db->where('amount >= '.$l1);
		if ($l2 != -1) {
			$this->db->where('amount <= '.$l2);
		}
		$query = $this->db->get('1st_deposit');
		return  $query->row();
	}
	public function ReportUserList($date1,$date2,$user){
		date_default_timezone_set('Asia/Bangkok');
		$this->load->library("ConfigData");

		$this->db->select('amount, member_no,update_date');
		if($date1 && $date2){
			$this->db->where('update_date >= ', $date1.' 00:00:00');
			$this->db->where('update_date <= ', $date2.' 00:00:00');
		}
		if($user){
			$this->db->like('member_no', $user);
		}

		$query = $this->db->get('1st_deposit');
		return  $query->result_array();
	}
	public function member_deposit_data($date1,$date2,$pro_id, $pro_time){
		if($pro_id == 1){
			if ($date1) {
				$this->db->where('dps.update_date >=', $date1);
			}
			if ($date2) {
				$this->db->where('dps.update_date <=', $date2);
			}
			$this->db->select('mb.telephone,mb.username as userName');
			$this->db->join('members as mb','dps.member_no = mb.id','left');
			$this->db->order_by('update_date', 'DESC');
			return $this->db->get('1st_deposit as dps')->result_array();
		}elseif($pro_id == 2){

			if ($pro_time) {
				$arr= array();
				if($pro_time==1){
					//ไม่ได้ฝากนาน 7 วัน
					$this->db->select('telephone, username, id');
					$member = $this->db->get('members')->result_array();
					foreach ($member as $key => $v) {
						$id = $v['id'];
						$this->db->where_in('channel', [1,2,3,5]);
						$this->db->where('member_no =', $id);

						// $this->db->where("trx_date <= DATE(NOW()) - INTERVAL 7 DAY");
						$this->db->select('member_no, trx_date, channel');
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$deposit = $this->db->get('log_deposit')->row();

						if($deposit){
							if($deposit->trx_date <= date('Y-m-d', strtotime('-7 days'))){
								array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['username']));
							}

						}
					}


				}elseif($pro_time==2){
					//ไม่ได้ฝากนาน 15 วัน
					$this->db->select('telephone, username, id');
					$member = $this->db->get('members')->result_array();
					foreach ($member as $key => $v) {
						$id = $v['id'];
						$this->db->where_in('channel', [1,2,3,5]);
						$this->db->where('member_no =', $id);

						// $this->db->where("trx_date <= DATE(NOW()) - INTERVAL 7 DAY");
						$this->db->select('member_no, trx_date, channel');
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$deposit = $this->db->get('log_deposit')->row();

						if($deposit){
							if($deposit->trx_date <= date('Y-m-d', strtotime('-15 days'))){
								array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['username']));
							}

						}
					}

				}elseif($pro_time==3){
					//ไม่ได้ฝากนาน 30 วัน
					$this->db->select('telephone, username, id');
					$member = $this->db->get('members')->result_array();
					foreach ($member as $key => $v) {
						$id = $v['id'];
						$this->db->where_in('channel', [1,2,3,5]);
						$this->db->where('member_no =', $id);
						// $this->db->where("trx_date <= DATE(NOW()) - INTERVAL 7 DAY");
						$this->db->select('member_no, trx_date, channel');
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$deposit = $this->db->get('log_deposit')->row();

						if($deposit){
							if($deposit->trx_date <= date('Y-m-d', strtotime('-30 days'))){
								array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['username']));
							}

						}
					}
				}elseif($pro_time==4){
					//ไม่ได้ฝากนานมากกว่า 30 วัน
					$this->db->select('telephone, username, id');
					$member = $this->db->get('members')->result_array();
					foreach ($member as $key => $v) {
						$id = $v['id'];
						$this->db->where_in('channel', [1,2,3,5]);
						$this->db->where('member_no =', $id);
						$this->db->select('member_no, trx_date, channel');
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$deposit = $this->db->get('log_deposit')->row();

						if($deposit){
							// 2023-07-08 <= 2023-07-07 && 2023-07-08 >= 2021-08-07
							if($deposit->trx_date <= date('Y-m-d', strtotime('-31 days')) && $deposit->trx_date >= date('Y-m-d', strtotime('-730 days'))){
							// if($deposit->trx_date <= date('Y-m-d', strtotime('-31 days'))){
								array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['username']));
							}

						}
					}
				}

				$this->db->where("dps.amount =",null);
				$this->db->select('mb.telephone ,mb.username as userName');
				$this->db->join('members as mb','dps.member_no = mb.id','right');

				$this->db->group_by(array('mb.id','dps.channel','dps.trx_date'));
				$deposit_nothave = $this->db->get('log_deposit as dps')->result_array();
				foreach ($deposit_nothave as $key => $v) {
					array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['userName']));
				}

				return $arr;
			}else{
				//ไม่เคยฝากเงินเลย
				$this->db->select('mb.telephone,mb.username as userName, dps.amount');
				$this->db->where("dps.amount =",null);
				$this->db->join('members as mb','dps.member_no = mb.id','right');
				$this->db->order_by('mb.id', 'asc');
				return $this->db->get('1st_deposit as dps')->result_array();
			}

		}elseif($pro_id == 3){
			$arr= array();
			$this->db->select('telephone, username, id');
			$member = $this->db->get('members')->result_array();
			foreach ($member as $key => $v) {
				$id = $v['id'];
				if ($date1) {
					$this->db->where('trx_date >=', $date1);
				}
				if ($date2) {
					$this->db->where('trx_date <=', $date2);
				}

				$this->db->where_not_in('channel', [1,2,3,5]);
				$this->db->where('member_no =', $id);
				$this->db->select('member_no, trx_date, channel');
				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$deposit = $this->db->get('log_deposit')->row();

				if($deposit){
					$this->db->where('member_no =', $deposit->member_no);
					$this->db->select('member_no');
					$this->db->limit(1);
					$chk_deposit = $this->db->get('1st_deposit')->row();

					if(!isset($chk_deposit)){
						array_push($arr,array('telephone' => $v['telephone'],'userName' => $v['username']));
					}

				}
			}
			return $arr;

		}elseif($pro_id == 4){
			$this->db->select('username as userName, telephone');
			$member = $this->db->get('members')->result_array();
			return $member;
		}else{
			return '';
		}
	}
	public function AdminUpdateAff($date_begin, $date_end, $orderARR,$searchName)
	{
		$this->db->select('COUNT(log.id) AS num_rows', FALSE);
		$this->db->join('employees as em','log.create_by = em.id','left');
		if($date_begin && $date_end){
			$this->db->where('log.create_at >= ', $date_begin . " 00:00:00");
			$this->db->where('log.create_at <= ', $date_end . " 23:59:59");
		}
		$num_rows = $this->db->get('log_edit_aff_member as log')->row()->num_rows;

		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('log.group_af_l1,log.group_af_l2,log.create_at,log.create_by,em.fname,em.lname,em.username,log.member_no');
			$this->db->join('employees as em','log.create_by = em.id','left');
			$this->db->where('log.member_no != ', null);
			if($date_begin && $date_end){
				$this->db->where('log.create_at >= ', $date_begin . " 00:00:00");
				$this->db->where('log.create_at <= ', $date_end . " 23:59:59");
			}
			if ($searchName != "") {
				$this->db->like('log.member_no', $searchName, 'both');
			}
			$columns = array(
				0 => 'log.member_no',
				1 => 'log.group_af_l1',
				2 => 'em.fname',
				3 => 'log.create_at'
			);
			$columnName = $columns[$orderARR['column']];

			$this->db->order_by($columnName . " " . $orderARR['dir']);
			$sql = $this->db->get('log_edit_aff_member as log');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function AdminUpdateAffTeam($date_begin, $date_end, $orderARR,$searchName)
	{
		$this->db->select('COUNT(log.id) AS num_rows', FALSE);
		$this->db->join('employees as em','log.create_by = em.id','left');
		if($date_begin && $date_end){
			$this->db->where('log.create_at >= ', $date_begin . " 00:00:00");
			$this->db->where('log.create_at <= ', $date_end . " 23:59:59");
		}
		$num_rows = $this->db->get('log_transferofwork as log')->row()->num_rows;

		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('log.group_af_l1,log.create_at,log.create_by,em.fname,em.lname,em.username,log.member_no,log.operation_type');
			$this->db->join('employees as em','log.create_by = em.id','left');
			$this->db->where('log.member_no != ', null);
			if($date_begin && $date_end){
				$this->db->where('log.create_at >= ', $date_begin . " 00:00:00");
				$this->db->where('log.create_at <= ', $date_end . " 23:59:59");
			}
			if ($searchName != "") {

				$this->db->like('log.member_no', $searchName, 'both');
			}
			$columns = array(
				0 => 'log.member_no',
				1 => 'log.group_af_l1',
				2 => 'em.fname',
				3 => 'log.create_at',
				4 => 'log.operation_type'
			);
	
			$columnName = $columns[$orderARR['column']];

			$this->db->order_by($columnName . " " . $orderARR['dir']);
			$sql = $this->db->get('log_transferofwork as log');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
	public function AdminUpdateMem($date_begin, $date_end, $orderARR,$searchName)
	{
		$this->db->select('COUNT(log.id) AS num_rows', FALSE);
		$this->db->join('employees as em','log.update_by = em.id','left');
		if($date_begin && $date_end){
			$this->db->where('log.update_date >= ', $date_begin . " 00:00:00");
			$this->db->where('log.update_date <= ', $date_end . " 23:59:59");
		}
		$num_rows = $this->db->get('log_edit_data_member as log')->row()->num_rows;

		$result_array = "";
		$row = "";
		if ($num_rows > 0) {
			$this->db->select('log.member_no,log.update_date,log.update_by,em.fname,em.lname,em.username,log.data_before,log.data_after');
			$this->db->join('employees as em','log.update_by = em.id','left');
			$this->db->where('log.member_no != ', null);
			if($date_begin && $date_end){
				$this->db->where('log.update_date >= ', $date_begin . " 00:00:00");
				$this->db->where('log.update_date <= ', $date_end . " 23:59:59");
			}
			if ($searchName != "") {

				$this->db->like('log.member_no', $searchName, 'both');
			}
			$columns = array(
				0 => 'log.member_no',
				1 => 'log.data_before',
				1 => 'log.data_after',
				2 => 'em.fname',
				3 => 'log.update_date'
			);
	
			$columnName = $columns[$orderARR['column']];

			$this->db->order_by($columnName . " " . $orderARR['dir']);
			$sql = $this->db->get('log_edit_data_member as log');

			$result_array = $sql->result_array();
			$row = $sql->row();
		}

		$result['num_rows'] = $num_rows;
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}

	public function Find_report_sumbyday($timestart='',$timeend=''){  
		$row='';$result_array=[];$num_rows=0;        
		$condition='';
		$condition_withdraw='';
		if(trim($timestart)!=''&&trim($timeend)!=''){ 
		   $condition = "AND dps.trx_date >= ?";
		   $condition .= " AND dps.trx_date <= ?";
		   $condition_withdraw="AND log_withdraw.trx_date >= ?";
		   $condition_withdraw .= " AND log_withdraw.trx_date <= ?";
		}
		 $query = $this->db->query("SELECT 
			 dps.member_no  
			 ,COALESCE(SUM(CASE WHEN  channel IN (1,2,3,5) AND promo =-1  THEN dps.amount ELSE 0 END),0) deposit
			 ,COALESCE((SELECT SUM(amount_actual) AS amount_actual FROM  log_withdraw  WHERE  STATUS = 1 AND member_no = dps.member_no $condition_withdraw),0) AS withdraw
			 ,0 as turnover
			,dps.trx_date
			FROM  log_deposit AS dps
			WHERE  dps.member_no IS NOT NULL AND dps.status=1 $condition
			GROUP BY  dps.member_no
			ORDER BY deposit DESC LIMIT 20", [date('Y-m-d',strtotime($timestart)),date('Y-m-d',strtotime($timeend)),date('Y-m-d',strtotime($timestart)),date('Y-m-d',strtotime($timeend))]);//,dps.trx_date
		

		$num_rows2 = $query->num_rows();
		
		if ($num_rows2 > 0) {
			$row = $query->result_array();   
		}
		 $result['num_rows'] = $num_rows;
		 $result['result_array'] = $result_array;
		 $result['row'] = $row;  
		
		return $result;
	  } 

	public function FindAllDataTop20Deposit($timestart='',$timeend=''){  
		$row_sumday=[]; 
		
				$row_sumday=$this->Find_report_sumbyday($timestart,$timeend);
				
				  if(isset($row_sumday['row'])){
					return $row_sumday['row'];
				  } 
	 return $row_sumday;
 }
 
}
?>