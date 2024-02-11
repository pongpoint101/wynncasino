<?php 
class MY_Model extends CI_Model {
    protected $Data = ''; 
    public function __construct() {
       parent::__construct();
       $this->Data=new \stdClass;
    }  
}
