<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cleardata extends CI_Controller
{
	public function trx()
	{

		$this->Model_db_log->clear_archive_data();
		// $this->Model_db_log->clear_archive_data_pg();
		echo 'ok';
	}
}
