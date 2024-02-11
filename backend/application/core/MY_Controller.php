<?php 
class MY_Controller extends CI_Controller {
    protected $respone = '';
    protected $deposit_type  = ["FIX" =>1,"MIN_MAX" =>2,"RANGE" =>3,"topuptoday" =>4,];  
    protected $deposit_dis  = [0,"ยอดฝากฟิค","ยอดฝากขั้นต่ำ,สูงสุด","ยอดฝากขั้นบันได","ยอดฝากเรียงลำดับ",];
    public $allow_action=false;
    public function __construct() {
       parent::__construct();
       date_default_timezone_set('Asia/Bangkok');
       $this->allow_action=$this->auth->route_access();
       $this->Model_function->LoginValidation();  
       $this->respone=new \stdClass;
       $this->CheckRouterAdmin();
    } 
    public function CheckRouterAdmin(){ 
        $msegment1=strtolower($this->uri->segment(1));
        if($this->input->method(TRUE)=='POST'&&($msegment1=='member'||$msegment1=='promotion')){
               $msegment2=strtolower($this->uri->segment(2));
               if(in_array($msegment2,['add_credit_scb','add_credit_manual','add_credit_truewallet','add_credit_kbank','add_credit_vizplay'])){
                $deposit_inputdata = $this->input->post(null, TRUE); 
                $xdeposit_amount=0;  
                if(isset($deposit_inputdata['amount'])){$xdeposit_amount=$deposit_inputdata['amount'];}
                $xxheck_deposit=$this->Permission->getLimitDepositByEmpoyee($xdeposit_amount);
                if(!$xxheck_deposit['status']){
                    $json_arr = array('Message' => false, 'ErrorText' =>"คุณไม่สามารถเติมเงินต่อวันเกิน {$xxheck_deposit['depositlimit']} บาท!", 'boolError' => 1);
                    echo json_encode($json_arr);exit();	
                 }
               }  
        } 
    }
    public function ResponeJson($data=[],$status_header=200){
        if(is_object($data)){$data=(array)$data;}
        $this->output
        ->set_status_header($status_header)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data))
        ->_display();exit(); 
    }
} 