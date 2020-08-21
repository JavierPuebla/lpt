<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main extends CI_Controller {

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
        'screen'=>$this->get_screen($user),
        'screen_title'=> 'Lotes Para Todos V 1.2'
      );
      $this -> load -> view('header-responsive');
      $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
      $this -> load -> view('screen_view',$var);
    }
  }

  function get_screen($u){
    // $id = $u['user_id']; 
    // if($id == 484 || $id == 490 || $id == 501 || $id == 502 ){
    //   $r = [
    //     ['call'=>['method'=>'list_contab','sending'=>false,'action'=>''],'tag'=>'Items de Cajas y Bancos'],
    //     ['call'=>['method'=>'list_atoms','sending'=>false,'action'=>''],'tag'=>'Items Generales'],
    //     ['call'=>['method'=>'list_revision','sending'=>true,'action'=>''],'tag'=>'Reportados con Problemas'],
    //     ['call'=>['method'=>'edit_element','sending'=>false,'action'=>'get_elem_id'],'tag'=>'Editar Contrato'],
    //     // ['call'=>['method'=>'config_rev_fplan','sending'=>false,'action'=>'get_elem_id'],'tag'=>'Revision Plan de Finaciacion']
    //   ];  
    // }else{
    //   $r = [
    //     ['call'=>['method'=>'list_contab','sending'=>false,'action'=>''],'tag'=>'Items de Cajas y Bancos'],
    //     ['call'=>['method'=>'list_atoms','sending'=>false,'action'=>''],'tag'=>'Items Generales'],
    //     ['call'=>['method'=>'list_revision','sending'=>true,'action'=>''],'tag'=>'Reportados con Problemas'],
    //   ];
    // }
    return []; 
  }



}
