<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin_cli extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('user','',TRUE);
  }

  function index()
  {
    //This method will have the credentials validation
    $this->load->library('form_validation');

    $this->form_validation->set_rules('user_dni', 'DNI ','trim|required|numeric|min_length[8]|max_length[8]|callback_check_database');

    // $this->form_validation->set_rules('clave_usuario', 'Password', 'trim|required|callback_check_database');
    $fv_res = $this->form_validation->run();
    if( $fv_res == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->db->close();
      $this->load->view('login_cli_view');
    }
    else
    {
      $this->db->close();
      //Go to user area
     redirect('web_cli');
    }

  }

  function check_database()
  {
    //Field validation succeeded.  Validate against database
    $user_dni = $this->input->post('user_dni',TRUE);
    if(preg_match('/[^\-0-9]/i', $user_dni))
    {
    	$this->form_validation->set_message('check_database', 'Nro DNI no valido');
    	return false;
    }
    //query the database
    $result = $this->user->login_cli($user_dni);
    if(!empty($result))
    {
      $sess_array = array();
      foreach($result as $row)
      {
        $sess_array = array(
          'user_dni' => $row['dni'],
          'user_apellido' => $row['apellido'],
          'user_nombre' => $row['nombre'],
          'user_permisos'=> 100,
          'user_type'=> "web",
          'user_id'=>$row ['user_id'],
          'elements_id'=>$row['elements_id']
        );
        $this->session->set_userdata('logged_in', $sess_array);
      }
      return true;
    }
    else
    {
      $this->form_validation->set_message('check_database', 'Nro. DNI no registrado');
      return false;
    }
  }
}
?>
