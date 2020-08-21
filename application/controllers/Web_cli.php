<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Web_cli extends CI_Controller {
  // ***********************************************************************************************
  // ******   PRESENTA LAS PANTALLAS A USUARIOS WEB QUE SE IDENTIFICARON CON SU DNI EN LOGIN_CLI
  // ***********************************************************************************************

  public function __construct() {
    parent::__construct();
    $this -> load -> model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    include (APPPATH . 'JP_classes/Atom.php');
    include (APPPATH . 'JP_classes/Element.php');
    include (APPPATH . 'JP_classes/Event.php');
    }

  function index(){
    $user = $this -> session -> userdata('logged_in');
    if ( ! is_array($user) || $user['user_type']!='web') {
      redirect('login_cli', 'refresh');
    }else{
      $route = 'web_cli/';
      $this->choose_lote($user,$route);
    }

  }

  function choose_lote($user,$route){
    $elm = [];
    $l = $this->app_model->get_lotes_by_user_dni($user['user_dni']);
    foreach ($l as $lote) {
      $elm[]=[
        'elm_id'=>$lote['elements_id'],
        'lote' => $lote['lote']
      ];
    }
    $screen = 0;
    if(count($elm) > 1){
          //   selector de lotes
          $screen = 1;
    }
    $v = [
      'route'=>$route,
      'user_id'=>$user['user_id'],
      'user_data'=>$user,
      'permisos'=>100,
      'selects'=>[],
      'locked'=>false,
      'screen'=>$this->get_screen($elm),
      'screen_title'=>'Selecciona el lote',


    ];
    $this->load->view('header-responsive');
    $this->load->view('screen_view',$v);

  }

  function get_screen($elm){
    $r = [];
    foreach ($elm as $e) {
      $r[]=['call'=>['method'=>'web_cli_get_resumen_de_cta','sending'=>true,'action'=>'call','data'=>['elm_id'=>$e['elm_id']]],'tag'=>$e['lote']];
    }
    return $r;
  }

  function lote($elm_id){
    $e = new Element($elm_id);
    $this->cmn_functs->update_estado_de_eventos_a_pagar($e);
    $l = new Atom($e->get_pcle('prod_id')->value);
    $b = new Atom (0,'BARRIO',$l->get_pcle('emprendimiento')->value);
    return [
      'lote_nom'=>$l->name,
      'barrio_nom'=>$b->name
      // 'cta_upc'=>$e->get_cta_upc(),
      // 'ctas_pagas'=>$e->get_events(4,'p%'),
      // 'ctas_adelantadas'=>$e->get_events(6,'pagado'),
      // 'ctas_restantes'=>$e->get_events(8,'a_pagar'),
      // 'ctas_mora'=>$e->get_events(4,'a_pagar'),
      // 'ctas_pft'=>$e->get_events(4,'p_ftrm'),
    ];
  }

  function servicios($elm_id){
    $servs_arr=[];
      $srv_elmtype = $this->app_model->get_obj("SELECT id FROM elements_types WHERE name = 'SERVICIO' ");
      // **** COLECTA LOS SERVICIOS DEL CONTRATO CON ELM_ID
      $s = $this->app_model->get_arr("SELECT id FROM elements WHERE elements_types_id = {$srv_elmtype->id} AND owner_id = {$elm_id} ");
      foreach ($s as $v) {
        $srv = new Element($v['id']);
        $this->cmn_functs->update_estado_de_eventos_a_pagar($srv);
        $srv_atom_id = $srv->get_pcle('atom_id')->value;
        $srv_atm = new Atom($srv_atom_id);
        $tot_pagado = $srv->get_tot_pagado();
        $lp = $srv->get_last_payment();
        $ctupc = $srv->get_cta_upc();
        $servs_arr[]=[
          'srvc_id'=>$srv->id,
          'cta_upc'=>$ctupc,
          'ctas_pagas'=>$srv->get_events(4,'p%'),
          'ctas_adelantadas'=>$srv->get_events(6,'pagado'),
          'ctas_restantes'=>$srv->get_events(8,'a_pagar'),
          'ctas_mora'=>$srv->get_events(4,'a_pagar'),
          'ctas_pft'=>$srv->get_events(4,'p_ftrm'),
          'financ_name'=>$srv->get_plan(),
          'srvc_name'=>$srv_atm->name,
          'fec_ini'=> $srv->get_pcle('fec_ini')->value,
          'tot_pagado'=> (!empty($tot_pagado))?$tot_pagado:0,
          'fec_ultimo_pago'=> (!empty($lp))?$lp->get_pcle('fec_pago')->value:0,
          // 'cta_actual'=>($ctupc > 0)?$ctupc['pcles']->get_pcle('monto_cta')->value:0
        ];
      }
      return $servs_arr;
  }




      // $elm['method']='get_elements';
      // $elm['action']='response';


      // if($elm['lote']){
      //   $this->cmn_functs->resp('front_call',$elm);

      // }else{
      //   // FALLO LA BUSQUEDA DEL NRO. DE LOTE
      //   $res =[
      //     'tit'=>'Estado de cuenta de clientes',
      //     'msg'=>'No se pudo acceder al resumen de cuenta ',
      //     'type'=>'warning',
      //     'container'=>'modal',
      //     'win_close_method' => 'back'
      //   ];
      //   $this->cmn_functs->resp('myAlert',$res);
      // }


}
