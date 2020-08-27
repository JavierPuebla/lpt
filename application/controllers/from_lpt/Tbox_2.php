<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tbox_1 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this ->load->model('user');
		$this ->load->model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->library('cmn_functs');

	  date_default_timezone_set('America/Argentina/Buenos_Aires');


		include (APPPATH . 'JP_classes/Atom.php');
		include (APPPATH . 'JP_classes/Element.php');
		include (APPPATH . 'JP_classes/Event.php');

  }
	//***** INDEX PAGE DISABLED
	public function index() {
		exit('FORBIDEN!')
	}
  // ****** END INDEX  ******

	
	function test_import(){
		$t = $this->excel_to_arr('to_import.xls');
		var_dump($r);


	}


}
