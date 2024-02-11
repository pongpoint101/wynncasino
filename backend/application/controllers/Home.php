<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();  
		date_default_timezone_set('Asia/Bangkok');
	}
	public function index() {  
		$item['pg_title']='home';
        $item['datalist']='';
		$item['PGDateTimeNow']=date("d/m/Y h:i:s");  

		$item['pg_header'] = $this->load->view('pg_header', NULL, TRUE);
		$item['pg_menu'] = $this->load->view('pg_menu', NULL, TRUE);
		$item['pg_footer'] = $this->load->view('pg_footer', NULL, TRUE); 

        $this->load->view('/home/index', $item);   
   } 
}
