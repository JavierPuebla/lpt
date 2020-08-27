<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Proveedores extends CI_Controller {
  // ******   CONSTRUCTOR AND INDEX
  public function __construct() {
    parent::__construct();
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    
    $this->load->model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');
    
    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    include (APPPATH . 'JP_classes/Atom.php');
    include (APPPATH . 'JP_classes/Element.php');
    include (APPPATH . 'JP_classes/Event.php');
    
    $this->types_id = 6;
    $this->type_text = 'PROVEEDOR';
    $this->route = 'proveedores/';
    
    //****  USER PRIVILEDGES
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)){
      if($user['user_permisos'] < 100){
        $this->usr_obj = $this->app_model->get_obj("SELECT permisos_usuario FROM usuarios WHERE id = {$user['user_id']} ");
      }else{
        $this->usr_obj = $this->app_model->get_obj("SELECT permisos_usuario FROM usuarios WHERE id = 509 ");
      }
    }else {
      redirect('login', 'refresh');
    }
  }
  
  public function index() {
    $cls_name = 'proveedores';
    $route = $this->route;
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
      // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********
      
      // $financ_type = new Atom(0,'TIPO_DE_FINANCIACION','LOTE ANTICIPO Y CUOTAS CICLO 2');
      $selects = [
        'owner_id'=>$this->cmn_functs->get_dpdown_data('PROVEEDOR'),
        'cant_ctas' => [['id'=>1,'lbl'=>'1'],['id'=>2,'lbl'=>'2'],['id'=>3,'lbl'=>'3'],['id'=>6,'lbl'=>'6'],['id'=>12,'lbl'=>'12'],['id'=>24,'lbl'=>'24'],['id'=>36,'lbl'=>'36'],['id'=>48,'lbl'=>'48'],['id'=>60,'lbl'=>'60'],['id'=>90,'lbl'=>'90'],['id'=>150,'lbl'=>'150'],['id'=>156,'lbl'=>'156'],['id'=>198,'lbl'=>'198'],['id'=>204,'lbl'=>'204']],
        'lote_id'=>$this->cmn_functs->get_dpdown_data('LOTE'),
        'barrio_id'=>$this->cmn_functs->get_dpdown_data('BARRIO'),
        'tipo_obra_id'=>$this->cmn_functs->get_dpdown_data('SERVICIO'),
      ];
      // PREPARO LOS DATOS DEL VIEW
      $var=array(
        'route'=>$this->route,
        'user_id'=>$user['user_id'],
        'permisos'=>$this->usr_obj->permisos_usuario,
        'selects'=>$selects,
        'locked'=>($user['user_id'] == 484)?false:false,
        'screen'=>$this->get_screen($this->usr_obj),
        'screen_title'=>'Proveedores '
      );
      
      
      // ****** LOAD VIEWS ******
      $this -> load -> view('header-responsive');
      
      if($user['user_permisos'] < 100){
        // ****** NAVBAR DATA ********
        $userActs = $this -> app_model -> get_activities($user['user_id']);
        $acts = explode(',',$userActs['elements_id']);
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['user_id'])['usr_usuario']));
      }
      $this -> load -> view('screen_view', $var);
    } else {
      redirect('login', 'refresh');
    }
  }
  // ****** END INDEX  ******
  
  // *** CREA EL SCREEN EN BASE A LOS PERMISOS DEL USUARIO
  function get_screen($u){
    $btns = [
      ['call'=>['method'=>'get_resumen_proveedor','sending'=>false,'action'=>'call','data'=>0],'tag'=>'Gestión Proveedor'],
      // ['call'=>['method'=>'alta_de_obra','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Alta de Contratación'],
      ['call'=>['method'=>'listado','sending'=>true],'tag'=>'Listado de Proveedores'],
      ['call'=>['method'=>'call_new_atom','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Alta de Proveedor'],
    ];
    if(intval($u->permisos_usuario) < 5 ){
      return $btns;
    }
    else{
      $r = [$btns[0]];
    }
    return $r;
  }
  
  //*********** 20/marzo/2020
  // OBTIENE  QUERY SEGUN EL PEDIDO DE POST DATA
  // ENVIA A GESTION DE OBRAS SI EL POST ESTA VACIO
  function filter(){
    $p = $this->input->post('data');
    $df = new Element(0,"OBRA",0);
    $filter = $df->get_filters();
    $tbl_head = array_map(function($x){return ["label"=>$x['label'],"title"=>$x['title']];},$filter);
    $fdta = $this->cmn_functs->get_ftrd_qry($p,"OBRA");
    $r = [
      'method'=>'filter',
      'action'=>'response',
      'data'=>['tbl_data'=>$fdta,'tbl_head'=>$tbl_head]
    ];
    $this->cmn_functs->resp('front_call',$r);
  }
  
  // *************************************************************************
  // ******* 25 de agosto 2020
  // ****** OBTIENE EL RESUMEN DE CUENTA DE UN PROVEEDOR **********
  // *************************************************************************
  function get_resumen_proveedor(){
    $p = $this->input->post();
    if(array_key_exists('data',$p) && intval($p['data']['elm_id'])> 0){
      $cpr = new Element(0,"CONTRATO_PROVEEDOR",intval($p['data']['elm_id']));
      if(!empty($cpr->id)){
        $obras = $this->app_model->get_obras_by_owner(intval($cpr->id));
        $elm =[
          'method'=>'get_resumen_proveedor',
          'action'=>'response',
          'route'=> $this->route,
          'props'=>$cpr->get_props(),
          'obras'=>$obras
        ];
        $this->cmn_functs->resp('front_call',$elm);
      }
    }
    else{
      $res =[
        'route'=>$this->route,
        'tit'=>'Gestion de Contrtaciones',
        'msg'=>'Error No existe el ID...',
        'type'=>'warning',
        'container'=>'modal',
        'win_close_method' => 'back'
      ];
      $this->cmn_functs->resp('myAlert',$res);
    }
    
  } 
  
  // DESHABILITADO
  function gestion_de_obras(){
    $df = new Element(0,"OBRA",0);
    $filter = $df->get_filters();
    $tbl_head = array_map(function($x){return ["label"=>$x['label'],"title"=>$x['title']];},$filter);
    $fdta = $this->Mdb->db->query("SELECT * FROM OBRA ")->result_array();
    
    $felm = [];
    $r = [
      'route'=>$this->route,
      'method'=>'gestion_de_obras',
      'action'=>'call_response',
      'title'=>' Gestión de Contrataciones',
      'data'=>['filter'=>$filter,'tbl_data'=>$fdta,'tbl_head'=>$tbl_head]
    ];
    $this->cmn_functs->resp('front_call',$r);
  }
  
  // *************************************************************************
  // ******* 11 de marzo 2020
  // ****** GESTION DE OBRA CONTRATADA **********
  // *************************************************************************
  public function get_obra(){
    $p = $this->input->post('data');
    // $type = 'OBRA';
    if(array_key_exists('elm_id',$p)){
      // DATOS DEL PROVEEDOR para el titulo
      
      
      
      // ULTIMOS MOVIMIENTOS 
      
      // OBRAS CONCLUIDAS
      
      $this->cmn_functs->resp('front_call',[
        'route'=> 'proveedores/',
        'method'=> 'get_obra',
        'sending'=>false,
        'action'=> 'response',
        'data'=> $r
        ]);
        
    }else{
        $res =[
          'route'=>$this->route,
          'tit'=>'Gestion de Contrtaciones',
          'msg'=>'Error No existe el ID...',
          'type'=>'warning',
          'container'=>'modal',
          'win_close_method' => 'back'
        ];
        $this->cmn_functs->resp('myAlert',$res);
    }
      
  }
    
    
  // *************************************************************************
  // ******* 11 de marzo 2020
  // ****** ALTA DE OBRAS RETORNA LA STRUCT PARA NUEVA OBRA **********
  // *************************************************************************
  public function alta_de_obra_call(){
    $p = $this->input->post('data');
    $struct = $this->app_model->get_arr("SELECT label,value,title,vis_elem_type,vis_ord_num,validates FROM `elements_struct` WHERE elements_types_id = {$p['elements_types_id']} AND vis_ord_num > 0 ORDER BY vis_ord_num ASC");
    if($struct){
      //  loop para control y/o  fixes de la data en struct y el front end
      // get_vis_elem_name convierte el int en la base a un text para el front
      foreach ($struct as $key => $s){
        $struct[$key]['vis_elem_type'] = $this->cmn_functs->get_vis_elem_name($s['vis_elem_type']);
      }
      $this->cmn_functs->resp('front_call',[
        'route'=>$this->route,
        'method'=> 'alta_de_obra',
        'sending'=>false,
        'action'=> 'call_response',
        'data'=> $struct
        ]);
    }
  }
    
  // *************************************************************************
  // ******* 12 de marzo 2020
  // ****** GUARDA EL ALTA DE NUEVA OBRA **********
  // *************************************************************************
  function alta_de_obra_save(){
    $p =$this->input->post('data');
    // //*** CONTROL DEL POST
    if(array_key_exists('owner_id',$p)  && array_key_exists('fields',$p)){
      //*** CREAR NUEVO OBRA ELEMENT
      $obra = new Element(-1,'OBRA',$p['owner_id']);
      foreach ($p['fields'] as $pc) {
        $obra->pcle_updv($obra->get_pcle($pc['label'])->id,$pc['value']);
      }
      
      // CREAR FORMA DE PAGO
      // REQUIERE UN OBJETO OBRA PARA CREAR LA FORMA DE PAGO
      $this->obra_forma_de_pago_create($obra);
      $r = ['result'=>'ok','elm_id'=>$obra->id];
    }else{
      $r = ['result'=>'FAIL','elm_id'=>-1];
    }
    $this->cmn_functs->resp('front_call',[
      'route'=>$this->route,
      'method'=> 'alta_de_obra',
      'sending'=>false,
      'action'=> 'save_response',
      'data'=> $r
      ]);
  }
  
  function obra_forma_de_pago_create($obr){
    $cant_ctas = intval($obr->get_pcle('cant_ctas_obra')->value);
    $tot_obra = intval($obr->get_pcle('monto_obra')->value);
    $fec_init_pago = $obr->get_pcle('fec_pago_inicial')->value;
    $ev_type = $this->app_model->get_obj("SELECT id FROM events_types WHERE nombre LIKE 'PAGO_OBRA'");
    
    if($cant_ctas > 0 && $tot_obra > 0 && !empty($ev_type)){
      $fec_ven = new DateTime($this->cmn_functs->fixdate_ymd($fec_init_pago));
      $monto_cta = $tot_obra / $cant_ctas;
      for ($i=1; $i <= $cant_ctas  ; $i++) {
        $this->set_cuota_pago_obra($obr->id,$ev_type->id,$monto_cta,$fec_ven->format('d/m/Y'),$i,$i);
        $fec_ven->modify('+1 month');
      }
    }
  }
  
  function set_cuota_pago_obra($elm_id,$ev_type,$monto,$fec_vto,$ord_num,$nro_cta){
    $evnt = new Event(0,$ev_type,$fec_vto,$elm_id,$ord_num);
    $evnt->set_pcle(0,'monto_cta',$monto,'Monto Cuota',1);
    $evnt->set_pcle(0,'fecha_vto',$fec_vto,'Fecha Vto.',1);
    $evnt->set_pcle(0,'estado','a_pagar','',-1);
    $evnt->set_pcle(0,'nro_cta',$nro_cta,'Nro. Cuota',1);
    $evnt->set_pcle(0,'monto_pagado',0,'Monto Pagado',1);
    $evnt->set_pcle(0,'fec_pago','-','Fecha de Pago',1);
    
  }
        
        
  //****** 07 agosto 2020
  //**** LISTADO EDITABLE DE PROVEEDORES
  //************************************************
  function listado(){
    $d = [];
    $qry = $this->cmn_functs->create_query('atom',6);
    $pr = $this->Mdb->db->query($qry);
    $st = $this->Mdb->db->query("SELECT st.id,
    st.label,
    st.title,
    st.vis_ord_num,
    v.nombre as vis_elem_type,
    st.validates
    FROM atoms_struct st
    JOIN visual_objects v on v.id = vis_elem_type
    WHERE atom_types_id = {$this->types_id} ORDER BY st.vis_ord_num ASC");
    $struct = ($st->result_id->num_rows)?$st->result_array():[];
    if($pr->result_id->num_rows){
      $r = [
        'route'=>$this->route,
        'method'=>'listado',
        'action'=>'response',
        'title'=>' Listado de Proveedores',
        'data'=> $pr->result_array(),
        'struct'=> $struct
      ];
      
    }else{
      exit('error en query');
    }
    $this->cmn_functs->resp('front_call',$r);
  }

  public function autocomplete_prov(){
    parse_str($_SERVER['QUERY_STRING'], $_GET);
    $r = $this->app_model->atcp_prov($_GET['term']);
    echo json_encode($r);
  }
    
}
  