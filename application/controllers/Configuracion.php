<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Configuracion extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this -> load -> model('app_model');
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');
    $this->user = $this -> session -> userdata('logged_in');


    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    include (APPPATH . 'JP_classes/Atom.php');
    include (APPPATH . 'JP_classes/Element.php');
    include (APPPATH . 'JP_classes/Event.php');
    // include (APPPATH . 'JP_classes/Historial.php');

    include (APPPATH . 'controllers/Clientes.php');
    //****  USER PRIVILEDGES
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)){
      $this->usr_obj = $this->app_model->get_obj("SELECT * FROM usuarios WHERE id = {$user['user_id']} ");
    } else {
      redirect('login', 'refresh');
    }

  }

  // ****** INDEX ******
  public function index() {
    // ****** DATA PARA CUSTOMIZAR LA CLASE
    $cls_name = 'configuracion';
    // ****** RUTA DE ACCESO DEL CONTROLLER
    $route = 'configuracion/';
    // ****** ******************

    $user = $this -> session -> userdata('logged_in');
    if ( ! is_array($user)){
      redirect('login', 'refresh');
    }else{
      // ****** NAVBAR CONFIG DATA ********
      $userActs = $this -> app_model -> get_activities($user['user_id']);
      $acts = explode(',',$userActs['elements_id']);
      // ****** END NAVBAR DATA ********

      // ************ VAR INYECTA INIT DATA EN SCREEN VIEW ***********
      // PREPARO LOS DATOS DEL VIEW
      $var=array(
        'route'=>'configuracion/',
        'user_id'=>$user['user_id'],
        'permisos'=>$this -> app_model -> get_user_data($user['user_id'])['permisos_usuario'],
        'locked'=>($user['user_id'] == 484)?false:false,
        'selects'=>[
          'atoms'=>$this->create_atoms_select(),
          'contab'=>$this->create_contab_select(),
          'cuenta_de_imputacion'=>$this->cmn_functs->get_servicios_dpdwn_data(),
            // 'usuarios'=>$this->cmn_functs->get_usuarios_dpdwn_data(),
          'proveedor' =>$this->cmn_functs->get_dpdown_data('PROVEEDOR'),
          'barrio_id'=>$this->cmn_functs->get_dpdown_data('BARRIO'),
          'clausula_revision'=>[['id'=>'NO','lbl'=>'NO'],['id'=>'SI','lbl'=>'SI']],
          'corte_cesped'=>[['id'=>'NO','lbl'=>'NO'],['id'=>'SI','lbl'=>'SI']],
          'estado_contrato'=>$this->cmn_functs->fill_select_by_atom_types_id(22),
          'tipo_contab_cuenta_de_imputacion'=>[
              ['id'=>'INGRESOS','lbl'=>'INGRESOS'],
              ['id'=>'EGRESOS' , 'lbl'=> 'EGRESOS'],
              ['id'=> 'AMBAS' , 'lbl'=>'AMBAS']
            ],
          'tipo_contab_cuentas'=>$this->cmn_functs->get_dpdown_tipo_contab_cuentas(),
          'categoria_contab_cuenta_de_imputacion'=>$this->cmn_functs->get_dpdown_categoria_contab_cuenta_imputacion(),
          'anticipo'=>[
              ['id'=>-1,'lbl'=>'NO'],
              ['id'=>1,'lbl'=>'SI']
            ],
          'financ_type'=>$this->cmn_functs->get_dpdown_financ_type(),
          'asignado_a'=>$this->cmn_functs->get_asigando_a_dpdown_data(),
          'asignado_a2'=>$this->cmn_functs->get_asigando_a_dpdown_data(),
          'rev_fplan'=>$this->cmn_functs->get_dpdown_data_rev_fplan(),
          'indac' => [['id'=>-1,'lbl'=>'NO'],['id'=>14,'lbl'=>'14%'],['id'=>16,'lbl'=>'16%'],['id'=>18,'lbl'=>'18%'],['id'=>25,'lbl'=>'25%']],
          'frecuencia_indac' => [['id'=>-1,'lbl'=>'NO'],['id'=>6,'lbl'=>'Semestral']],

          'prod_id'=>$this->cmn_functs->get_dpdown_data('LOTE'),
          'cli_id'=>$this->cmn_functs->get_dpdown_data('CLIENTE'),
          'titular_id'=>$this->cmn_functs->get_dpdown_data('CLIENTE'),
          'cotitular_id'=> array_merge([['id'=>-1,'lbl'=>'Sin Cotitular']],$this->cmn_functs->get_dpdown_data('CLIENTE')),
          'beneficiario_id'=> array_merge([['id'=>-1,'lbl'=>'Sin beneficiario']],$this->cmn_functs->get_dpdown_data('BENEFICIARIO')),
          'vendedor'=>$this->cmn_functs->get_dpdown_data('VENDEDOR'),
          'anticipo'=>[['id'=>-1,'lbl'=>'NO'],['id'=>1,'lbl'=>'SI']],
          'aplica_revision'=>[['id'=>-1,'lbl'=>'NO'],['id'=>1,'lbl'=>'SI']],
          'frecuencia_ctas_refuerzo'=>[['id'=>-1,'lbl'=>'NO'],['id'=>3,'lbl'=>'3'],['id'=>6,'lbl'=>'6'],['id'=>9,'lbl'=>'9'],['id'=>12,'lbl'=>'12']],
          'cant_ctas' => [['id'=>1,'lbl'=>'1'],['id'=>2,'lbl'=>'2'],['id'=>3,'lbl'=>'3'],['id'=>6,'lbl'=>'6'],['id'=>12,'lbl'=>'12'],['id'=>24,'lbl'=>'24'],['id'=>36,'lbl'=>'36'],['id'=>48,'lbl'=>'48'],['id'=>60,'lbl'=>'60'],['id'=>90,'lbl'=>'90'],['id'=>150,'lbl'=>'150'],['id'=>156,'lbl'=>'156'],['id'=>198,'lbl'=>'198'],['id'=>204,'lbl'=>'204']],
          'cant_ctas_ciclo_2' => [['id'=>-1,'lbl'=>'NO'],['id'=>120,'lbl'=>'120'],['id'=>150,'lbl'=>'150']],
          'indac' => [['id'=>0,'lbl'=>'NO'],['id'=>14,'lbl'=>'14%'],['id'=>16,'lbl'=>'16%'],['id'=>18,'lbl'=>'18%'],['id'=>25,'lbl'=>'25%']],
          'frecuencia_indac' => [['id'=>0,'lbl'=>'NO'],['id'=>6,'lbl'=>'Semestral']],
          'frecuencia_revision' => [['id'=>-1,'lbl'=>'NO'],['id'=>6,'lbl'=>'6 meses'],['id'=>12,'lbl'=>'12 meses'],['id'=>18,'lbl'=>'18 meses'],['id'=>24,'lbl'=>'24 meses']],
          'emprendimiento'=>$this->cmn_functs->fill_select_by_atom_types_id(4),
          'estado'=>$this->cmn_functs->fill_select_by_atom_types_id(5),
        ],
        'screen'=>$this->get_screen($user),
        'screen_title'=> 'Configuración'
      );
      // ****** LOADS HEADER ******
      $this -> load -> view('header-responsive');
      // ****** LOADS NAVBAR ******
      $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['user_id'])['usr_usuario']));
      // ****** LOADS  SCREEN VIEW ******
      $this -> load -> view('screen_view',$var);
    }
  }
  // ****** END INDEX  ******


  //****** 15 julio 2020
  //**** actualizar columna en tabla comprobantes
  //************************************************
  function update_comprobante(){
    $p = $this->input->post('data');
    $label_to_col = [
      'Intereses' => 'intereses_monto',
      'Total' => 'monto',
      'Saldo'=> 'saldo'
    ];
    if(empty($p['id'])){echo 'fail';}
    $q = "UPDATE comprobantes SET `{$label_to_col[$p['label']]}` = '{$p['val']}' WHERE `nro_comprobante` = {$p['id']}";
    $res = $this->Mdb->db->query($q);
    $this->cmn_functs->resp('front_call',[
      'method'=> 'update_comprobante',
      'response'=> true,
      'data'=> $res
    ]);
  }

  // *** CREA EL SCREEN EN BASE A LOS PERMISOS DEL USUARIO
  function get_screen($u){
    $b = [
        ['call'=>['method'=>'list_contab','sending'=>false,'action'=>''],'tag'=>'Items de Cajas y Bancos'],
        ['call'=>['method'=>'list_atoms','sending'=>false,'action'=>''],'tag'=>'Items Generales'],
        ['call'=>['method'=>'list_revision','sending'=>true,'action'=>''],'tag'=>'Reportados con Problemas'],
        ['call'=>['method'=>'edit_element','sending'=>false,'action'=>'get_elem_id'],'tag'=>'Contrato'],
        ['call'=>['method'=>'config_update_plan','sending'=>false,'action'=>'get_elem_id'],'tag'=>'Revision Plan de Finaciacion'],
        ['call'=>['method'=>'cancelar_serv','sending'=>false,'action'=>'get_elem_id'],'tag'=>'Cancelar servicio por falta de pago'],
      ];
    $id = $u['user_id'];
    if($id == 484){return $b;}
    if($id == 501 || $id == 498 || $id == 499 || $id == 502){return [$b[0],$b[1],$b[2],$b[3],$b[4],$b[5]];}

    if($id != 501 && $id != 498 && $id != 502){return [$b[0],$b[1],$b[2],$b[4]];}
    else{return false;}
  }

    // *************************************************************************
  // ******* 17 de enero 2020
  // ****** PRODUCT MAIN LIST *******
  // *************************************************************************
  // public function list(){
  //   $p = $this->input->post('data');
  //   $d = [];
  //   $pr = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = {$p['atp_id']} ORDER BY id ASC");
  //   if($pr->result_id->num_rows){
  //     $type_name = $this->Mdb->db->query("SELECT name FROM atom_types WHERE id = {$p['atp_id']}")->row()->name;
  //     foreach ($pr->result_array() as $prx) {
  //       $o = new Atom(intval($prx['id']));
  //       $d[] = $o->get_pcle();
  //     }
  //   }
  //   $r = [
  //     'method'=>'list',
  //     'action'=>'response',
  //     'title'=>' Listado de '.$type_name."S",
  //     'data'=>$d
  //   ];
  //   $this->cmn_functs->resp('front_call',$r);
  // }


  // *** 21/02/2020
  // kill modal content
  // usado para borrar archivos subidos en cliente
  function kill_modal_content(){
    $p = $this->input->post('data');
    if(is_array($p)){
      if(unlink($p['del_target'])){
        $x['method']='kill_modal_content';
        $x['action']='response';
        $x['elm_id']= $p['elm_id'];
        $this->cmn_functs->resp('front_call',$x);
      }else{
        // FALLO no pudo borrar el archivo
        $res =[
          'tit'=>'Archivos del contrato ',
          'msg'=>'No se pudo borrar el archivo, intente nuevamente  ',
          'type'=>'warning',
          'container'=>'modal',
          'win_close_method' => 'back'
        ];
        $this->cmn_functs->resp('myAlert',$res);
      }
    }
  }


  function create_contab_select(){
    // SELECTS DE CONTAB
    $r=[
        ['id'=>'contab_cuentas','lbl'=>'Cuentas'],
        ['id'=>'contab_cuenta_de_imputacion','lbl'=>'Cuentas de Imputacion']
      ];
    return $r;
  }

  function create_atoms_select(){
    $a = $this->app_model->get_arr("SELECT DISTINCT atom_types_id, t.name FROM atoms a join atom_types t on t.id = a.atom_types_id ");
    $r=[];
    foreach ($a as $v) {
      //  LIMITANDO LA CANTIDAD DE INTEMS EN EL SELECT
      if($v['atom_types_id'] == 1 || $v['atom_types_id'] == 2 || $v['atom_types_id'] == 6 || $v['atom_types_id'] == 4 || $v['atom_types_id'] == 8 || $v['atom_types_id'] == 14 ){
        $r[]=['id'=>$v['atom_types_id'],'lbl'=>$v['name']];
      }

    }
    return $r;
  }

  function list_contab(){
		$p = $this->input->post('data');
		$qtb = "SELECT * FROM  {$p['id']} ORDER BY id ASC ";
		// $qtb = "SELECT * FROM  contab_cuentas  ORDER BY id limit 5";
		$tb_list = $this->app_model->get_arr($qtb);
    $rows = [];
		foreach($tb_list as $tb_row){
			$pcle= [];
			foreach($tb_row as $key => $item){
				if($key == 'tipo' || $key == 'categoria' || $key == 'caja_vinculada' ){
          $pcle[]=['label'=>$key.'_'.$p['id'],'value'=>$item,'title'=>ucwords($key), 'vis_elem_type'=>'3','elem_type_src'=> $p['id']];
        }else{
          $pcle[]=['label'=>$key,'value'=>$item,'title'=>ucwords($key), 'vis_elem_type'=>'1'];
        }

			}
			$rows[]=$pcle;
		}
    $res['rows']=$rows;
		echo json_encode(array(
      'callback'=> 'mk_contab_list',
      'param'=> $res
    ));
	}

  function save_contab(){
    $p = $this->input->post();
    $this->app_model->update($p['table'],$p['data'],'id',$p['data']['id']);
    $r =[
      'tit'=>'Modificación de item',
      'msg'=>'el item fue actualizado correctamente',
      'type'=>'success',
      'container'=>'modal',
      'after_action'=> Array( 'method' => 'refresh_contab' )
    ];
    echo json_encode(array(
      'callback'=>'myAlert',
      'param'=>$r
    ));
  }

  function new_contab(){
    $p = $this->input->post();
    $this->app_model->insert($p['table'],$p['data']);
    $r =[
      'tit'=>'Modificación de item',
      'msg'=>'el item fue ingresado correctamente',
      'type'=>'success',
      'container'=>'modal',
      'after_action'=> Array( 'method' => 'refresh_contab' )
    ];
    echo json_encode(array(
      'callback'=>'myAlert',
      'param'=>$r
    ));
  }

  function delete_contab(){
    $p = $this->input->post();
    $this->app_model->clean($p['table']." WHERE id = ".$p['data']);
    $r =[
      'tit'=>'Eliminar item',
      'msg'=>'el item fue eliminado correctamente',
      'type'=>'success',
      'container'=>'modal',
      'after_action'=> Array( 'method' => 'refresh_contab' )
    ];
    echo json_encode(array(
      'callback'=>'myAlert',
      'param'=>$r
    ));
  }

  function list_atoms(){
		$p = $this->input->post('data');
    $res_data = [];
    $cant_cols = 3;
    $lids = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = {$p['atp_id']}");
    if($lids->result_id->num_rows){
      $type_name = $this->Mdb->db->query("SELECT name FROM atom_types WHERE id = {$p['atp_id']}")->row()->name;
      foreach ($lids->result_array() as $key=> $lids_rec) {
        $a = new Atom($lids_rec['id']);
        $pcles = $a->get_pcle();
        for ($i=0; $i < $cant_cols ; $i++) {
          $res_data[$key][$pcles[$i]->title] = $pcles[$i]->value;
        }
        $res_data[$key]['Acciones'] = "<button type=\"button\" class=\"btn-normal mr-3\" onClick=front_call({method:'call_edit',action:'call',sending:true,data:{type:'Atom',id:".$lids_rec['id']."}})><i class=\"material-icons \">open_in_new</i><span class=\'align-top \'></span></button><button type=\"button\" class=\"btn-normal mr-3\" onClick=front_call({method:'kill_atom',sending:false,data:{type:'Atom',id:".$lids_rec['id']."}})><i class=\"material-icons \">delete_forever</i><span class=\'align-top \'></span></button>";
      }
    }


    $res=[
      'sending'=>false,
      'method'=>'list_atoms',
      'action'=>'response',
      'type'=>$type_name,
      'atp_id'=>$p['atp_id'],
      'data'=> $res_data
    ];
		$this->cmn_functs->resp('front_call',$res);
  }



    // *************************************************************************
    // ******* 7 de octubre 2019
    // ******* PREPARA LA VENTANA DEl NUEVO ATOM
    // *************************************************************************

    function call_new_atom(){
      $p = $this->input->post('data');
      $st = $this->cmn_functs->call_atom_struct($p['type_text']);
      if($st){
        $this->cmn_functs->resp('front_call',[
          'route'=>'configuracion/',
          'method'=> 'call_new_atom',
          'sending'=>false,
          'action'=> 'call_response',
          'data'=> ['type'=>$p['type_text'],'pcles'=>$st]
        ]);
      }else{
        $res =[
            'tit'=>'Alta de Elemento del sistema',
            'msg'=>'Error de conección ',
            'type'=>'warning',
            'container'=>'modal',
            'win_close_method' => 'back',
            'route'=>'configuracion/'
          ];
          $this->cmn_functs->resp('myAlert',$res);
      }
    }

    // *************************************************************************
    // ******* 18 de octubre 2019
    // ******* PREPARA LA VENTANA DEl NUEVO ATOM CLIENTE
    // *************************************************************************

    function save_new_atom(){
      $p = $this->input->post('data');
      $atom_id = $this->cmn_functs->save_new_atom($p['type_text'],$p['fields']);
      if($atom_id){
        $this->cmn_functs->resp('front_call',[
          'route'=>'configuracion/',
          'method'=> 'call_new_atom',
          'sending'=>false,
          'action'=> 'save_response',
          'data'=> ['title'=>$p['type_text'],'atom_id'=>$atom_id]
        ]);
      }else{
        $res =[
            'tit'=>'ALTA DE ELEMENTO',
            'msg'=>'Error No se registro el nuevo Elemento',
            'type'=>'warning',
            'container'=>'modal',
            'win_close_method' => 'back'
          ];
          $this->cmn_functs->resp('myAlert',$res);
      }

    }




    // *************************************************************************
    // ******* 4 de octubre 2019
    // ******* PREPARA LA VENTANA DEl ATOM / ELEM / EVENT A EDITAR
    // *************************************************************************

    function call_edit(){
      $p = $this->input->post('data');
      $type = 'Atom';
      $id = $p['id'];
      $r = $this->cmn_functs->call_edit($type,intval($id));
      $this->cmn_functs->resp('front_call',[
        'method'=> 'call_edit',
        'sending'=>false,
        'action'=> 'call_response',
        'data'=> $r
      ]);
    }

    // *************************************************************************
    // ******* 4 de octubre 2019
    // *******  GUARDA LOS DATOS DEl ATOM EDITADO
    // *************************************************************************

    function save_edit(){
      $p = $this->input->post('data');
      $this->cmn_functs->save_edit('Atom',$p);
      $this->cmn_functs->resp('front_call',[
        'method'=> 'call_edit',
        'sending'=>false,
        'action'=> 'save_response',
        'data'=> ['result'=>'OK']
      ]);
    }





  function kill_atom(){
    $p = $this->input->post();
    $atp_id = (array_key_exists('atp_id', $p))?$p['atp_id']:0;
    $a = new Atom($p['data']['id']);
    $a->kill();
    $this->cmn_functs->resp('front_call',['method'=>'refresh_atoms','atom_type_id'=>$atp_id]);
  }

  function new_atom(){
    $p = $this->input->post();
    $str = $this->cmn_functs->get_struct($p['data']);
    $r = ['method'=>'new_atom','action'=>'call_response','act_title'=> $p['data'],'data'=>$str];
    $this->cmn_functs->resp('front_call',$r);
  }


  function save_atom(){
    $p = $this->input->post();
    if(array_key_exists('id', $p)){
      $t = new Atom($p['id']);
      $t->set('name',$p['atom_name']);
    }else{
      $t = new Atom(0,$p['type'],$p['atom_name']);
    }
    //  ACTUALIZO SOLAMENTE LABEL Y VALUE EN EL PCLE
    foreach ($p['data'] as $key => $val) {
      $x = $t->get_pcle($key);
      if(!empty($x)){
        $t->set_pcle($x->id,$key,$val,$x->title,$x->vis_elem_type);
      }else{
        $t->set_pcle(0,$key,$val);
      }
    }
    $r =[
      'tit'=>'Guardando',
      'msg'=>'OK!',
      'type'=>'success',
      'container'=>'modal',
      'after_action'=> Array('method'=>'refresh_atoms','atom_type_id' => $t->type_id)
    ];
    echo json_encode(array(
      'callback'=>'myAlert',
      'param'=>$r
    ));
  }
  function get_all_ctas_servicios(){
    $s = $this->get_servicios();
    $res = [];
    if($s){
      foreach ($s as $srv) {
        $e = new Element($srv['id']);
        $srv_atom = new Atom($e->get_pcle('atom_id')->value);


        $res[]=['name'=>$srv_atom->name,'events'=>$e->get_events_cuota()];
      }
    }
    return $res;
  }

  // *************************************************************************
  // ******* 20/mayo/2020
  // *******  CANCELA EL SERVICIO Y ACREDITA LOS PAGOS PREVIOS EN CUENTA DEL CONTRATO
  // *************************************************************************
  function cancelar_serv(){
    $p = $this->input->post('data');
    $e = new Element($p['elm_id']);
    $items=[];
    $titular = (new Atom($e->get_pcle('titular_id')->value))->name;
    $lote = (new Atom($e->get_pcle('prod_id')->value))->name;
    $s = $e->get_servicios();
    if(!empty($s)){
      foreach ($s as $sx) {
        $srv = new Element($sx['id']);
        $srva = new Atom($srv->get_pcle('atom_id')->value);
        $ccr = intval($srv->get_pcle('cant_ctas_restantes')->value);
        if($ccr > 1){
          $items[] = [
            'call'=>[
              'method'=>'cancelar_serv',
              'sending'=>true,
              'action'=>'confirma_cancelar_serv',
              'elm_id'=>$sx['id'],
            ],
            'tag'=> "Cancelar ".$this->cmn_functs->get_srv_name($srv)
          ];
        }

      }
    }
    if(count($items)>0){
      $r =[
        'route'=>'configuracion/',
        'method'=>'cancelar_serv',
        'sending'=>false,
        'action' =>'response',
        'title'=> $lote.' '.$titular,
        'data'=>$items
      ];
    }else{
      $r =[
        'method'=>'alert',
        'data'=>['container'=>'#my_modal_body','type'=>'danger','tit'=>'Error!','msg'=>'No hay Servicios para cancelar','extra'=>'hide_modal']
      ];
    }
    $this->cmn_functs->resp('front_call',$r);
  }


  //*** REVISION PLAN DE FINANC
  // ESTA ANULADO OBTENER SERVICIOS PARA ACTUALIZAR
  function config_update_plan(){
    $p = $this->input->post('data');
    $e = new Element($p['elm_id']);
    // $s = $e->get_servicios();
    $items = [];
    $lt = new Atom($e->get_pcle('prod_id')->value);
    $lote_ccr = intval($e->get_pcle('cant_ctas_restantes')->value);
    $items[] = [
      'call'=>[
        'method'=>'config_update_plan',
        'sending'=>true,
        'action'=>'update_lote',
        'elm_id'=>$e->id
      ],
      'tag'=> $lt->name.' - Ctas.('.$lote_ccr.')'
    ];
    // if(!empty($s)){
    //   foreach ($s as $sx) {
    //     $srv = new Element($sx['id']);
    //     $srva = new Atom($srv->get_pcle('atom_id')->value);
    //     $ccr = intval($srv->get_pcle('cant_ctas_restantes')->value);
    //     if($ccr > 1){
    //       $items[] = [
    //         'call'=>[
    //           'method'=>'config_update_plan',
    //           'sending'=>true,
    //           'action'=>'update_srvc',
    //           'elm_id'=>$sx['id'],
    //         ],
    //         'tag'=> $srva->name.' - Ctas.('.$ccr.')'
    //       ];
    //     }
    //
    //   }
    // }
      $r =[
        'route'=>'configuracion/',
        'method'=>'config_update_plan',
        'sending'=>false,
        'action' =>'response',
        'data'=>$items
      ];
      $this->cmn_functs->resp('front_call',$r);
  }


  //  CUANDO HAY SERVICIOS ACTIVOS LOS ADJUNTA AL PANEL DE EDICION
  function edit_service(){
    $p = $this->input->post('data');
    $e = new Element($p['elem_id']);
    if($e->type == 'CONTRATO'){
      $ep = $e->get_pcle();
      $cli_id = $e->get_pcle('cli_id')->value;
      $prod_id = $e->get_pcle('prod_id')->value;
      $l = new Atom($prod_id);
      $lname = $l->name;
      $cl = new Atom($cli_id);
      $clname = $cl->name;
      $res = [
        'elm_id'=>$e->id,
        'lname'=>$lname,
        'clname'=>$clname,
        'el_pcles'=>$ep,
        'plan'=>$e->get_plan(),
        'evs'=>$e->get_events_all()
      ];
    }
    if($e->type == 'SERVICIO'){
      $ep = $e->get_pcle();
      $lname = 'servicio';
      $clname = '';
      $res = [
        'user_id'=> $this->user['user_id'],
        'permisos'=> $this->user['permisos_usuario'],
        'elm_id'=>$e->id,
        'lname'=>$lname,
        'clname'=>$clname,
        'el_pcles'=>$ep,
        'plan'=>$e->get_plan(),
        'evs'=>$e->get_events_all()
      ];
    }
    echo json_encode(array(
      'callback'=> 'edit_element',
      'param'=> $res
    ));
  }

    // *************************************************************************
    // ******* 14 julio 2020
    // ******* PREPARA LA VENTANA DEl CONTRATO nueva version
    // *************************************************************************

    function edit_element(){
      $p = $this->input->post('data');
      $type = $p['type'];
      $id = $p['id'];
      $e = new Element($id);
      $pr_nom = (new Atom($e->get_pcle('prod_id')->value))->name;
      $r = $e->get_active_props();
      $r['servicios'] = $e->get_all_ctas_servicios();
      $r['comprobantes'] = $this->get_comprobantes($e->id);
      $r['uploaded_files'] = [
          'lote_data_gen'=>$this->cmn_functs->get_uploaded_files($id,'lote_data_gen'),
          'web_cli'=>$this->cmn_functs->get_uploaded_files($id,'web_cli')
        ];
      $r['lote'] = ['elements_id'=>$id,'lote_nom'=>$pr_nom];

      $this->cmn_functs->resp('front_call',[
        'user_id'=> $this->user['user_id'],
        'permisos'=> $this->user['user_permisos'],
        'method'=> 'edit_element',
        'sending'=>false,
        'action'=> 'call_response',
        'data'=> $r
      ]);
    }

    //****** 14 julio 2020
    //**** retorna la data de los comprobantes correspondientes al contrato editado
    //************************************************
    function get_comprobantes($elm_id){
        $q = "SELECT
                DATE_FORMAT(fecha,'%d/%m/%Y') as 'Fecha',
                nro_comprobante as 'Recibo Nro.',
                concepto as 'Descripcion',
                intereses_monto as 'Intereses',
                monto as 'Total',
                saldo as 'Saldo'
                FROM `comprobantes`
                WHERE elements_id = {$elm_id} AND estado > 0
                ORDER BY id DESC";
        $c = $this->Mdb->db->query($q);
        if($c->result_id->num_rows){
            return $c->result_array();
        }else{
            return null;
        }
    }


    // *************************************************************************
    // *** 19/12/2019
    // *** rutea a la funcion para subir archivos
    // ***
    // ************************************************************************
    public function lotes_file_upload(){
      $folder = 'lote_data_gen';
      $nom = $this->input->post('lote_nom');
      $elm_id = $this->input->post('elm_id');

      $upld_res = $this->cmn_functs->file_upload($nom,$elm_id,$folder);
      $this->cmn_functs->resp('',$upld_res);
    }




    /* *****************
    *** autocomplete del editor de contrato
    */

  function autocomplete_edit_elem(){
    parse_str($_SERVER['QUERY_STRING'], $_GET);
    $r = $this->app_model->atcp_get_elements_CR($_GET['term']);
    echo json_encode($r);
  }

  function pcle_updv(){
    $p = $this->input->post('data');
    if(array_key_exists('val',$p) && array_key_exists('type',$p) && array_key_exists('pcle_id',$p) && array_key_exists('prnt_id',$p)){
      $e = new $p['type']($p['prnt_id']);
      // SI ESTA MODIFICANDO EL LOTE DEL CONTRATO MODIFICA LOS ESTADOS DEL OS LOTES y recarga editar contrato
      if($p['type'] == 'Element' && strpos($p['lid'],'prod_id_') !== false ){
        //*** REVIERTO EL ESTADO DEL LOTE PREVIO
        $olt = $e->get_pcle('prod_id')->value;
        $aolt = new Atom($olt);
        $aolt->pcle_updv($aolt->get_pcle('estado')->id,'DISPONIBLE');
        //** NUEVO VALUE DEL PCLE
        $e->pcle_updv($p['pcle_id'],$p['val']); 
        // ** NUEVO OWNER
        $e->set('owner_id',$p['val']);
        // ** CAMBIO EL ESTADO DEL NUEVO LOTE
        $a = new Atom($p['val']);
        $a->pcle_updv($a->get_pcle('estado')->id,'ACTIVO');
        // ** CAMBIO EL BARRIO EN EL CONTRATO
        $barrio_id = $a->get_pcle('barrio_id')->value;
        $e->pcle_updv($e->get_pcle('barrio_id')->id,$barrio_id);
        //** RECARGO EDITAR CONTRATO
        $this->cmn_functs->resp('front_call',['method'=>'edit_element','sending'=>true,'data'=>['type'=>"Element",'id'=>$p['prnt_id']]]);
        return;
      }
      //** NUEVO VALUE DEL PCLE
      $e->pcle_updv($p['pcle_id'],$p['val']); 
      
      // return 'ok';
      $this->cmn_functs->resp('front_call',['method'=>'pcle_updv_cnfg','response'=>true,'msg'=>'OK :)']);
    }else{
      // return false;
      $this->cmn_functs->resp('front_call',['method'=>'pcle_updv_cnfg','response'=>true,'msg'=>'Error.. (:<  ']) ;
    }
  }

 
  // ** ACTUALIZA EL ELEMENT PCLE
  function update_elem_pcle(){
    $p = $this->input->post('data');
    // $x = $this->app_model->update('elements_pcles',['label'=>$p['label'],'value'=>$p['value'],id,$p['id']]);
    $e = new Element($p['parent_id']);
    $pid = $e->get_pcle($p['label'])->id;
    $e->set_pcle($pid,$p['label'],$p['val']);
    $x=['elem_id'=>$p['parent_id']];
    $this->edit_element($x);
  }

  /* ******** 19/11/2019
  *** actualiza un event cuando lo llama el editor de contatos
  *****
  */
  function update_event(){
    $p = $this->input->post('data');
    //  obtengo el evento que quiero editar o error
    $ev = new Event($p['parent_id']);
    if(empty($ev) || empty($p['elem_id'])){
      exit('event_id no valido');
    }
    // DEFINIR QUE ESTOY ACTUALIZANDO
    // INGRESO FECHA Y USUARIO QUE MODIFICA EL EVENTO
    $ev->set_pcle(0,'user_id',$p['user_id'],'',-1);
    $ev->set_pcle(0,'modif_date',date('Y-m-d'),'',-1);
    $ev->set_pcle(0,'prev_val','label:'.$p['label'].' value:'.$ev->get_pcle($p['label'])->value,'',-1);
    $ev->set_pcle(0,'last_modif','label:'.$p['label'].' value:'.$p['val'],'',-1);
    switch ($p['label']) {
      case 'monto_cta':
        $ev->set_pcle(0,'monto_cta',intval($p['val']));
        break;
      case 'fecha_vto':
        //**** ACTUALIZO  DATE DEL EVENT Y EVENT_TYPE_ID
        if(DateTime::createFromFormat('d/m/Y', $p['val'])){
          $ev->set('date',DateTime::createFromFormat('d/m/Y', $p['val'])->format('Y-m-d'));
          $ev->set_pcle(0,'fecha_vto',$p['val'],'Fecha de Vto.',1);
          $x = $this->cmn_functs->get_event_type_by_fecha_y_estado_de_pago($ev->get_pcle('fec_pago')->value,$p['val'],$ev->get_pcle('estado')->value);
          $ev->set($ev->types_ref_name,$x['ev_type_id']);
        }
      break;
      case 'monto_pagado':
        // SOLO SE PUEDE MODIFICAR O ANULAR NO SE PUEDE INGRESAR NUEVOS PAGOS 
        // NUA VES ANULADO EL USUARIO NO SABE QUE EL RECORD SIGUE AHI SOLO VE EL ESTADO ACTUAL
        // SET_PCLE CERO VA A BUSCAR EL PCLE DE ESTE EVENTO
        $ev->pcle_updv($ev->get_pcle('monto_pagado')->id,intval($p['val']));
        $nro_rec = $ev->get_pcle('recibo_nro')->value;
        // ES UNA MODIFICACION DEL PAGO 
        if(intval($p['val']) > 0 ){
          // FECHA DE PAGO NULA O '-' 
          if(!DateTime::createFromFormat('d/m/Y', $ev->get_pcle('fec_pago')->value) || $ev->get_pcle('fec_pago')->value == '-'){
            // TODAY  
            $xd = new DateTime(date('Y-m-d'));
            $fp = $xd->format('d/m/Y');
          }else{
            // FECHA DEL EVENTO 
            $fp = $ev->get_pcle('fec_pago')->value;
          }
          // DETERMINA EL ESTADO ENTRE FUERA DE TERMINO A TERMINO O ADELANTADA EN BASE A LA FECHA DE PAGO
          $estado = ($this->cmn_functs->get_estado_pago($ev->get_pcle('fecha_vto')->value,$fp) == 'adelantada')?'pagado':$this->cmn_functs->get_estado_pago($ev->get_pcle('fecha_vto')->value,$fp);
          $ev->set_pcle(0,'estado',$estado);
          $ev->set_pcle(0,'recibo_nro',-1);
        }
        // ES UNA ANULACION DEL PAGO 
        else{
          $ev->set_pcle(0,'estado','a_pagar');
          if(!empty($nro_rec)){
            //  ** ESTADO PASA A SER -1 
            $this->app_model->update('comprobantes',['estado'=>-1],'nro_comprobante',$nro_rec);
          }
        }
        //*** RECALCULAR SALDO
        $this->cmn_functs->recalc_saldo($p['elem_id'],$nro_rec);
      break;
      case 'fec_pago':
        if(DateTime::createFromFormat('d/m/Y', $p['val'])){
          // FECHA ES VALIDA
          $estado = ($this->cmn_functs->get_estado_pago($ev->get_pcle('fecha_vto')->value,$p['val']) == 'adelantada')?'pagado':$this->cmn_functs->get_estado_pago($ev->get_pcle('fecha_vto')->value,$p['val']);
          $ev_fp_arr = $this->cmn_functs->get_event_type_by_fecha_y_estado_de_pago($p['val'], $ev->get_pcle('fecha_vto')->value, $estado);
          $ev->set_pcle(0,'fec_pago',$p['val'],'Fecha de Pago',1);
          $ev->set_pcle(0,'estado',$ev_fp_arr['estado']);
          $ev->set_pcle(0,'recibo_nro',-1);
          $ev->set($ev->types_ref_name,$ev_fp_arr['ev_type_id']);
        }else{
          //  FECHA DE PAGO ES INVALIDA O GUION
          if(stripos($ev->get_pcle('estado')->value, 'p') === 0){
            $ev->set_pcle(0,'monto_pagado',0,'Monto Pagado',1);
            $ev->set_pcle(0,'estado','a_pagar');
            $ev->set_pcle(0,'fec_pago','-','Fecha de Pago',1);

            $today = new DateTime(date('Y-m-d'));
            $fv = new DateTime($this->cmn_functs->fixdate_ymd($ev->get_pcle('fecha_vto')->value));

            $ev_type_id = ($today->diff($fv)->invert === 0 && $today->diff($fv)->days > 0 )?8:4;
            $ev->set($ev->types_ref_name,$ev_type_id);
          }
        }
      break;
      default:
        $ev->set_pcle(0,$p['label'],intval($p['val']));
      break;
    }
    $res = ['method'=>'update_event','response'=>'success','estado'=>$ev->get_pcle('estado')->value,'scrn_elem_id'=>'edi_'.$ev->get_pcle('estado')->id];
    $this->cmn_functs->resp('front_call',$res);
  }


  function kill_event(){
    $p = $this->input->post('data');
    $ev = new Event($p['ev_id']);
    $ev->kill();
    $r = ['method'=>'edit_element','sending'=>true,'data'=>['elem_id'=>$p['elm_id']]];
      echo json_encode(array('callback'=> 'front_call','param'=> $r));
  }

  // ** REPORTADOS  CON PROBLEMAS
  function list_revision(){
    $q = "SELECT rev.id,rev.element_id as elm_id, date(rev.curr_time)as fec, usr.nombre_usuario,rev.asignado_a, rev.coment, cli.value as cli , lot.value as lote, IF(rev.solucionado = 1 ,'resuelto','pendiente') as estado  FROM revision as rev
      LEFT OUTER JOIN elements_pcles epcl ON (epcl.elements_id = rev.element_id AND epcl.label = 'cli_id')
      LEFT OUTER JOIN elements_pcles eplote ON (eplote.elements_id = rev.element_id AND eplote.label = 'prod_id')
      LEFT OUTER join atoms_pcles cli ON (cli.atom_id = epcl.value AND cli.label = 'nombre')
      LEFT OUTER join atoms_pcles lot ON (lot.atom_id = eplote.value AND lot.label = 'name')
      LEFT OUTER JOIN usuarios usr ON (rev.user_id = usr.id)
      ORDER BY lote ASC, rev.solucionado ASC, rev.curr_time DESC ";
    $r = $this->app_model->get_arr($q);
    $res = [
      'method'=>'list_revision',
      'action'=>'response',
      'rows'=>[]
    ];
    foreach($r as $row){
      $res['rows'][]=[
        'rev_id'=> $row['id'],
        'estado'=>$row['estado'],
        'hstate'=>$row['estado'],
        'fecha'=>$this->cmn_functs->fixdate_dmY($row['fec']),
        'usr'=>$row['nombre_usuario'],
        'lote'=>$row['lote'],
        'cli'=>$row['cli'],
        'coment'=>$row['coment'],
        'asignado_a'=>$row['asignado_a']
      ];
    };
    echo json_encode(array('callback'=> 'front_call','param'=> $res));
  }


  function update_rev_asignado(){
    $p = $this->input->post('data');
    $r = $this->app_model->update('revision',['asignado_a'=>$p['value']],'id',$p['id']);
    echo json_encode(array('callback'=> 'check','param'=> 'ok'));
    // $this->list_revision();
  }

  function new_revision(){
    $p = $this->input->post();
       // get elem id desde el nombre de lote
    $a = new Atom(0,"LOTE",$p['lote']);
    $e = new Element(0,"CONTRATO",$a->id);
    $this->app_model->insert('revision',['element_id'=> $e->id,'user_id'=>$p['user_id'],'coment'=>$p['coment'],'asignado_a'=>$p['asignado_a']]);
    // return list
    $this->list_revision();
  }

  function revision_set_estado()
  {
    $p = $this->input->post();
    $res = ['id'=>$p['id'],'method'=>'revision_set_estado','sending'=>false,'estado'=>$p['state']];
    $this->app_model->update('revision',['solucionado'=>intval($p['state'])],'id',$p['id']);
    $rep = $this->app_model->get_obj("SELECT element_id,user_id,coment  FROM revision WHERE id = ".$p['id']);
    $lote_id = intval((new Element($rep->element_id))->owner_id);
    $h = new Historial((new Atom($lote_id))->get_pcle('hist_id')->value);


    if($p['state'] == 1){
      $lst_ev = $h->update($rep->user_id,'resuelto','','REVISADO');
    }elseif($p['state'] == 0){
      $lst_ev = $h->update($rep->user_id,'reporte_problema',$rep->coment,'EN_REVISION');
    }
    $this->cmn_functs->resp('front_call',$res);
  }

  function set_config_curr_state(){
    $p = $this->input->post();
    $e = new Element($p['elem_id']);
    $e->set_pcle(0,'curr_state',$p['state'],$title='',$vet= -1);
    $l = $e->get_pcle('prod_id')->value;
    if($p['state'] == 'a_revisar'){
      $r = ['method'=>'new_revision','sending'=>false,'lote'=>$p['lote_nom'],'element_id'=>$p['elem_id'],'coment'=>'Ingresa una descripción del problema reportado.'];
      echo json_encode(array('callback'=> 'front_call','param'=> $r));
    }else{
      $t = $this->app_model->update('revision',['solucionado'=>1],'element_id',$p['elem_id']);
      echo json_encode(array('callback'=> 'check','param'=> 'ok'));
    }
  }

}
