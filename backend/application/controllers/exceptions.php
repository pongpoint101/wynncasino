<?php
defined('BASEPATH') or exit('No direct script access allowed');
class exceptions extends CI_Controller
{ 
    public function custom_403(){ 
      return  $this->load->view('errors/html/error403',null);
    }
}
?>