<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller
{ 
    public function __construct()
    {
        parent::__construct(); 
    } 
    public function xxxxxxxxxaddPermission(){
   
    $this->Permission->add([ 
      'group_name'=>'dashbord', 
      'display_name' => 'DashBoard',
      'url_list'=>'dashbord/view,dashbord/json_data', 
      'status' => 1 ]); 

      $this->Permission->add([ 
      'group_name'=>'dashbord', 
      'display_name' => 'DashBoard month',
      'url_list'=>'dashbord/month,dashbord/json_month_data', 
      'status' => 1 ]); 

      //member
       $this->Permission->add([ 
        'group_name'=>'member', 
        'display_name' => 'รายการผู้ใช้งานระบบ',
        'url_list'=>'member/view,member/profile/*,member/list_af_l1,member/list_af_l2,member/list_log_deposit_json,member/list_log_withdraw_json,member/list_log_game_json,member/list_json', 
        'status' => 1 ]); 

        $this->Permission->add([ 
          'group_name'=>'member', 
          'display_name' => 'แก้ไขข้อมูลสมาชิก',
          'url_list'=>'member/view,member/list_json,member/add_edit_member,member/list_json_id', 
          'status' => 1 ]);  

        // End member
     
        //เติมเครดิต 
        $this->Permission->add([ 
          'group_name'=>'member', 
          'display_name' => 'เติมเครดิต',
          'url_list'=>'member/view,member/list_json,promotion/add_credit_manual,member/add_credit_truewallet,member/add_credit_scb,member/add_credit_kbank,member/free_credit', 
          'status' => 1
         ]); 
        // End เติมเครดิต

        //ลบเครดิต 
        $this->Permission->add([ 
          'group_name'=>'member', 
          'display_name' => 'ลบเครดิต',
          'url_list'=>'member/view,member/list_json,member/del_credit', 
          'status' => 1
        ]); 
        // End เติมเครดิต

         //พนักงาน 
         $this->Permission->add([ 
          'group_name'=>'admin', 
          'display_name' => 'รายการพนักงาน',
          'url_list'=>'admin/view,admin/list_json', 
          'status' => 1
          ]); 

          $this->Permission->add([ 
            'group_name'=>'admin', 
            'display_name' => 'เพิ่ม-แก้ไขข้อมูลพนักงาน',
            'url_list'=>'admin/view,admin/list_json,admin/update_employee_status,admin/list_json_id,admin/add_edit_member,admin/delete', 
            'status' => 1
            ]); 
        // End พนักงาน

        // affiliate
        $this->Permission->add([ 
          'group_name'=>'affiliate', 
          'display_name' => 'ประวัติการรับโบนัส',
          'url_list'=>'affiliate/view,affiliate/list_log_aff_json', 
          'status' => 1
          ]);
          $this->Permission->add([ 
            'group_name'=>'affiliate', 
            'display_name' => 'ประวัติการแนะนำเพื่อน',
            'url_list'=>'affiliate/history,affiliate/list_log_aff_history_json', 
            'status' => 1
            ]);
          $this->Permission->add([ 
            'group_name'=>'affiliate', 
            'display_name' => 'รายได้จากสมาชิก',
            'url_list'=>'affiliate/moneyincom', 
            'status' => 1
            ]);     
        // End affiliate

        //รายการโปรโมชั่น 
        $this->Permission->add([ 
          'group_name'=>'promotion', 
          'display_name' => 'จัดการโปรโมชั่น',
          'url_list'=>'promotion/*', 
          'status' => 1
          ]);     

        // Emd รายการโปรโมชั่น
 
        
        //ประวัติการฝากเงิน 
        $this->Permission->add([ 
          'group_name'=>'deposit', 
          'display_name' => 'ประวัติการฝากเงิน',
          'url_list'=>'deposit/view,deposit/list_deposit_json', 
          'status' => 1
          ]);     

        // Emd ประวัติการฝากเงิน

        //ทำรายการถอน 
        $this->Permission->add([ 
          'group_name'=>'deposit', 
          'display_name' => 'ทำรายการถอน',
          'url_list'=>'withdraw/pending,withdraw/list_withdraw_id_json,withdraw/list_withdraw_padding_json,withdraw/withdraw_manual,withdraw/withdraw_auto,withdraw/refund_credit,withdraw/cancle_credit', 
          'status' => 1
          ]);     

        // Emd ทำรายการถอน

        //ประวัติการถอนเงิน 
        $this->Permission->add([ 
          'group_name'=>'withdraw', 
          'display_name' => 'ประวัติการถอนเงิน',
          'url_list'=>'withdraw/view,withdraw/list_withdraw_json', 
          'status' => 1
          ]);     

        // Emd ประวัติการถอนเงิน

        $this->Permission->add([ 
          'group_name'=>'truewallet', 
          'display_name' => 'จัดการบัญชี TrueWallet',
          'url_list'=>'truewallet/*', 
          'status' => 1
          ]);    
       // End truewallet

       $this->Permission->add([ 
        'group_name'=>'bank', 
        'display_name' => 'จัดการบัญชี ธนาคาร',
        'url_list'=>'bank/*', 
        'status' => 1
        ]);    
     // End bank

     $this->Permission->add([ 
      'group_name'=>'games', 
      'display_name' => 'ตั้งค่าเกมส์',
      'url_list'=>'games/*', 
      'status' => 1
      ]);    
   // End games

   $this->Permission->add([ 
    'group_name'=>'website', 
    'display_name' => 'ตั้งค่าเว็บไซต์',
    'url_list'=>'website/*', 
    'status' => 1
    ]);    
   // End website
   

   //Permissions 
   $this->Permission->add([ 
    'group_name'=>'permissions', 
    'display_name' => 'จัดการสิทธิ์ผู้ใช้งาน',
    'url_list'=>'permissions/*', 
    'status' => 1
    ]);  
  // End Permissions

    $this->Permission->add([ 
      'group_name'=>'reports', 
      'display_name' => 'รายละเอียดการเดิมพัน',
      'url_list'=>'reports/reportwinlosemember,reports/reportmemberplaygame_list', 
      'status' => 1 ]); 
     $this->Permission->add([
      'group_name'=>'reports',
      'display_name' => 'ประวัติการเติมมือ',
      'url_list'=>'reports/deposit_mannual,reports/deposit_history_list', 
      'status' => 1, ]);
     $this->Permission->add([
      'group_name'=>'reports',
      'display_name' => 'ประวัติการลบเครดิต',
      'url_list'=>'reports/delete_mannual,reports/deposit_delete_history_list', 
      'status' => 1, ]); 
  
  
	}
    public function view()
     {  
      $item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
	    $item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
	    $item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE);
      $item['pg_title'] = 'สิทธิ์การใช้งาน';
	    $item['card_title'] = 'สิทธิ์การใช้งาน'; 
      $item['roles']=$this->Role->all();
      $item['Permissions']=$this->Permission->all();
      $item['module_lists']=[];

      if ($this->allow_action) {
        $dis = "";
        } else {
        $dis = "disabled";
      }
      $item['create_hide_btn'] = $dis;
      $item['delete_hide_btn'] = $dis;
      $item['update_hide_btn'] = $dis;   

      return $this->load->view('permission/view', $item);
    }
    public function create()
    {
        if($this->input->method()=='get'){ 
            echo $this->load->view('permission/create', null,true);
           exit();
        } 
        $pdata = $this->input->post(null, TRUE);  
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('display_name', 'display_name', 'required');
        if ($this->form_validation->run() == true){
            $check=$this->Role->add(['name'=>$pdata['name'],'display_name'=>$pdata['display_name'],'description'=>$pdata['description']]); 
            $this->respone->status_code =500;
            $this->respone->status_msg = 'Error';
            if($check){
                $this->respone->status_code =200;
                $this->respone->status_msg = 'ok';
                $this->cache->clean(); 
            } 
         }else{
            $this->respone->status_code =500;
            $this->respone->status_msg = 'Error';
        }  
        $this->ResponeJson($this->respone, $this->respone->status_code); 
        return ;
    }
    public function update()
    {
      if($this->input->method()=='get'){
        $gdata = $this->input->get(null, TRUE);  
        $item['permissions']=$this->Permission->all_byrole();
        $item['permissions_all']=$this->Permission->all();
        $item['rolesby_id']=$this->Role->find($gdata['per_id']);
        $item['permissionsby_id']=$this->Permission->all_perrole($gdata['per_id']);//var_dump($item['permissions'],$item['permissionsby_id']);exit();
        echo $this->load->view('permission/edit', $item,true);
        exit();  
      }
      $data = $this->input->post(null, TRUE);
      $role_id=$data['role_id']; 
     $data_master=$this->Permission->all();//var_dump($old);exit(); data['permissions']  role_id
    // $permissionsby_id=$this->Permission->all_byrole($role_id);//var_dump($data_master);exit(); 
     $permissions_add_list=[];
     foreach ($data_master as $k => $v) {   
            $permissions_all_list[]=$v->id*1; 
            if(isset($data['permission_'. $v->id])){ 
               $permissions_add_list[]=$v->id*1;
            }   
          }
          if(sizeof($permissions_all_list)>0){ 
            $this->Role->deleteByPermissions($role_id,$permissions_all_list);  
          }   
          if(sizeof($permissions_add_list)>0){ 
            $this->Role->addPermissions($role_id, $permissions_add_list);
          } 
          $this->cache->clean(); 
          $this->respone->status_code =200;
          $this->respone->status_msg = 'ok';
          $this->ResponeJson($this->respone, $this->respone->status_code);
    }
    public function delete()
    {
     $pdata = $this->input->post(null, TRUE);  
     $this->Permission->deleteBy_role($pdata['role_id']);
     $check=$this->Role->delete($pdata['role_id']); 
     $this->respone->status_code =500;
     $this->respone->status_msg = 'Error';
     if($check){
         $this->respone->status_code =200;
         $this->respone->status_msg = 'ok';
         $this->cache->clean(); 
     }  
     $this->ResponeJson($this->respone, $this->respone->status_code);
    } 


}