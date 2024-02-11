<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index()
	{

		setcookie("ck_login", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_ref", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_Username", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie("ck_SurName", "", time()-3600,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		$array_items = array('login' => false,
							 'id' => '',
							 'Username' => '',
							 'SurName' => '');
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy();

		redirect('','refresh');
		exit();
	}
}
