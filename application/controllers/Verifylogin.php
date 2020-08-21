<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('user','',TRUE);
  }

  function index()
  {
    //This method will have the credentials validation
    $this->load->library('form_validation');

    $this->form_validation->set_rules('usr_usuario', 'User Name','trim|required|min_length[5]|max_length[20]');

    $this->form_validation->set_rules('clave_usuario', 'Password', 'trim|required|callback_check_database');

    if($this->form_validation->run() == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->load->view('login_view');
    }
    else
    {
      //Go to private area
     redirect('main');
    }

  }

  function check_database($password)
  {
    //Field validation succeeded.  Validate against database
    $username = $this->input->post('usr_usuario',TRUE);
    if(preg_match('/[^a-z_\-0-9]/i', $username))
    {
	$this->form_validation->set_message('check_database', 'Usuario/Password no valido');
	return false;
    }

    //query the database
    $result = $this->user->login($username, $password);
    if($result)
    {
      $sess_array = array();
      foreach($result as $row)
      {
        $sess_array = array(
          'user_id' => $row->id,
          'user_nombre_usuario' => $row->usr_usuario,
          'user_permisos'=> $row->permisos_usuario,
          'user_type'=> $row->tipo_usuario
        );
        $this->session->set_userdata('logged_in', $sess_array);
      }
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_database', 'Usuario/Clave no valido');
      return false;
    }
  }
}
?>
