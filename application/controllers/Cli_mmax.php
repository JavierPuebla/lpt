<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cli_mmax extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('user','',TRUE);
    $this -> load -> model('app_model');
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();


    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');

    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    include (APPPATH . 'JP_classes/Atom.php');
    include (APPPATH . 'JP_classes/Element.php');
    include (APPPATH . 'JP_classes/Event.php');

  }

  function index()
  {
    $dni = $this->input->get('dni');
    $cli = $this->check_dni($dni);
    if( $cli == FALSE)
    {
      //Field validation failed.  User redirected to login page
      // $this->load->view('login_cli_view');
      echo 'no valido..';
    }
    else
    {
      $e = new Element (intval($cli->elements_id));
      echo $this->cmn_functs->get_disponible_credito($e);
    }
  }


  function check_dni($user_dni)
  {
    if(preg_match('/[^\-0-9]/i', $user_dni))
    {
      return false;
    }
    //query the database
    $result = $this->user->login_cli($user_dni);
    if($result)
    {
        return $result[0];
    }
    else
    {
      return false;
    }
  }
}
?>
