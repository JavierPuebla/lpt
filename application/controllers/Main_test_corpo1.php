<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main_test_corpo1 extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this -> load -> model('app_model');
    $this->load->helper('array');
     //****  USER PRIVILEDGES GESTIONA PERMISOS DE USUARIOS ENTODA LA CLASE
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)){
      $this->usr_obj = $this->app_model->get_obj("SELECT * FROM usuarios WHERE id = {$user['user_id']} ");
    } else {
      redirect('login', 'refresh');
    }


  }

  function index() {
    $user = $this -> session -> userdata('logged_in');
    if ( ! is_array($user)) {
      redirect('login', 'refresh');
    } else {
      // ****** RUTA DE ACCESO DEL CONTROLLER
      $route = 'main/';
      $user_data = $this -> app_model -> get_user_data($user['user_id']);
      $userActs = $this -> app_model -> get_activities($user['user_id']);
      $acts = explode(',',$userActs['elements_id']);
      //if($quien['usuario'] == "algun tipo seleccionado de user"){
        // CONTROLLER Y ACTION son settings en la DB
        //$this->app_model->getUserSetup()
        // $var=array('controller'=>'init','user'=>$user_data);
      $var=array(
        'route'=>$route,
        'user_id'=>$user['user_id'],
        'permisos'=>$this -> app_model -> get_user_data($user['user_id'])['permisos_usuario'],
        'selects'=>[],
        'locked'=>false,
        'screen'=>$this->app_model->get_menu_items($user['user_id']),
        'screen_title'=> 'Nuberio App'
      );
      $this -> load -> view('header-responsive');
      // $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
      $this -> load -> view('test_createx-corporate',$var);
    }
  }

}
