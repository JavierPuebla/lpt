<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lotes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
		$this->Mdb->load->database();
		$this->load->model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->library('cmn_functs');

  
		include (APPPATH . 'JP_classes/Atom.php');
		// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$this->types_id = 2;
		$this->type_text = 'LOTE';

		//****  USER PRIVILEDGES
		$user = $this -> session -> userdata('logged_in');
		if (is_array($user)){
			$this->usr_obj = $this->app_model->get_obj("SELECT * FROM usuarios WHERE id = {$user['user_id']} ");
		} else {
			redirect('login', 'refresh');
		}

	}
	// ****** END CONSTRUCT  ******

	public function index() {
		// ****** DATA PARA CUSTOMIZAR LA CLASE
		$cls_name = 'lotes';
		// ****** RUTA DE ACCESO DEL CONTROLLER
		$route = 'lotes/';
		// ****** ******************

		$user = $this -> session -> userdata('logged_in');
		if (is_array($user)) {

			// ****** NAVBAR DATA ********
			$userActs = $this -> app_model -> get_activities($user['user_id']);
			$acts = explode(',',$userActs['elements_id']);
			// ****** END NAVBAR DATA ********

			// ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********
			$selects = [
				// 'barrio'=>$this->cmn_functs->get_dpdown_data_barrios()
			];
			// PREPARO LOS DATOS DEL VIEW
			$var=array(
				'route'=>$route,
				'user_id'=>$user['user_id'],
				'permisos'=>$this -> app_model -> get_user_data($user['user_id'])['permisos_usuario'],
				'selects'=>$selects,
				'locked'=>($user['user_id'] == 484)?false:false,
				'screen'=>$this->get_screen($user),
				'screen_title'=> "LOTES",
			);
			// ****** LOAD VIEW ******
			$this -> load -> view('header-responsive');
			$this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['user_id'])['usr_usuario']));
			$this -> load -> view('screen_view',$var);
		} else {
			redirect('login', 'refresh');
		}
	}
	// ****** END INDEX  ******

	// *** CREA EL SCREEN EN BASE A LOS PERMISOS DEL USUARIO
	function get_screen($u){
		$btns = [
			// ['call'=>['method'=>'resumen_cuenta_prov','sending'=>false,'action'=>'call','data'=>0],'tag'=>'Resumen de Cuenta'],
			// ['call'=>['method'=>'manage','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Gestor de Productos'],
			['call'=>['method'=>'list','sending'=>true],'tag'=>'Listado de Lotes'],
			['call'=>['method'=>'call_new_atom','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Alta de Lote'],
			['call'=>['method'=>'import_data','sending'=>false,'action'=>'call','data'=>0],'tag'=>'Importar desde Archivo']
		];
		// LIMITACIONES POR PERMISOS USUARIO
		// $permisos = intval($u['permisos_usuario']);
		// if($permisos == 0){
		// 	return $btns;
		// }

		// elseif($permisos == 1){
		// 	$r = [$btns[0],$btns[1],$btns[2],$btns[3],$btns[4]];
		// }elseif($permisos > 5){
		// 	$r = [$btns[1]];
		// }
		// else{
		// 	$r = [$btns[1],$btns[4]];
		// }
		// return $r;
		return $btns;
	}

	// *************************************************************************
	// ******* 25 enero 2020
	// ******* importacion de datos desde archivo excel
	// *************************************************************************
	function import_data(){

		// hacer nuevo import de datos de cmn functs

		// setear datos de la clase productos
		$fname = $this->cmn_functs->file_import($this->type_text);
		$response = $this->cmn_functs->import_excel($fname,$this->type_text);
		if($response === 'OK'){
			$this->cmn_functs->resp('front_call',[
				'method'=> 'import_data',
				'sending'=>false,
				'action'=> 'response',
				'data'=> ['type'=>$this->type_text]
			]);
		}else{
			$r =[
				'tit'=>'Importacion de datos ',
				'msg'=>'Error ',
				'type'=>'danger',
				'container'=>'modal'
			];
			$this->cmn_functs->resp('myAlert',$r);

		}


	}

	// *************************************************************************
	// ******* 7 de octubre 2019
	// ******* PREPARA LA VENTANA DEl NUEVO ATOM
	// *************************************************************************
	function call_new_atom(){
		$st = $this->cmn_functs->call_atom_struct($this->type_text);
		if($st){
			$this->cmn_functs->resp('front_call',[
				'method'=> 'call_new_atom',
				'sending'=>false,
				'action'=> 'call_response',
				'data'=> ['title'=>$this->type_text,'pcles'=>$st]
			]);
		}else{
			$res =[
				'title'=>'Alta de '.$this->type_text,
				'msg'=>'Error de conexión, intente nuevamente ',
				'type'=>'warning',
				'container'=>'modal',
				'win_close_method' => 'back'
			];
			$this->cmn_functs->resp('myAlert',$res);
		}
	}

	// *************************************************************************
	// ******* 18 de octubre 2019
	// ******* GUARDAR NUEVO ATOM
	// *************************************************************************
	function save_new_atom(){
		$p = $this->input->post('data');
		$atom_id = $this->cmn_functs->save_new_atom($p['type_text'],$p['fields']);
		if($atom_id){
			$this->cmn_functs->resp('front_call',[
				'method'=> 'call_new_atom',
				'sending'=>false,
				'action'=> 'save_response',
				'data'=> ['title'=>'Nuevo '.$p['type_text'],'atom_id'=>$atom_id]
			]);
		}else{
			$res =[
				'tit'=>'ALTA DE PRODUCTO',
				'msg'=>'Error No se registro el nuevo Produto',
				'type'=>'warning',
				'container'=>'modal',
				'win_close_method' => 'back'
			];
			$this->cmn_functs->resp('myAlert',$res);
		}

	}

	// *************************************************************************
	// ******* 17 de enero 2020
	// ****** PRODUCT MAIN LIST *******
	// *************************************************************************
	public function list(){
		$d = [];
		$pr = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = $this->types_id ORDER BY id ASC limit 10");
		if($pr->result_id->num_rows){
			foreach ($pr->result_array() as $prx) {
				$o = new Atom(intval($prx['id']));
				$d[] = $o->get_pcle();
			}
		}
		$r = [
			'method'=>'list',
			'action'=>'response',
			'title'=>' Listado de Lotes',
			'editable'=>false,
			'nolabel'=>true,
			'data'=>$d
		];
		$this->cmn_functs->resp('front_call',$r);
	}

	// *************************************************************************
	// ******* 27 de enero 2020
	// ******* ACTUALIZA EL PCLE POR EL ID USADO EN LIST
	// *************************************************************************
	function pcle_updv($type=0,$prnt_id=0,$id=0,$v=0){
		if($type == 0 && $prnt_id == 0 && $id == 0 && $v == 0){
			$p = $this->input->post('data');
			if(array_key_exists('type',$p) && array_key_exists('prnt_id',$p) && array_key_exists('id',$p) && array_key_exists('val',$p)){
				$type = $p['type'];
				$prnt_id = $p['prnt_id'];
				$id = $p['id'];
				$v = $p['val'];
			}
		}
		if($type && $prnt_id && $id && $v){
			$e = new $type($prnt_id);
			$e->pcle_updv($id,$v);
			// return 'ok';
			$this->cmn_functs->resp('front_call',['method'=>'pcle_updv','response'=>true,'msg'=>'OK :)']);
		}else{
			// return false;
			$this->cmn_functs->resp('front_call',['method'=>'pcle_updv','response'=>true,'msg'=>'Error.. (:<  ']) ;
		}

	}


	// *************************************************************************
	// ******* 27 de enero 2020
	// ******* GESTIONA ELEMENT DE UN ATOM
	// *************************************************************************
	function manage(){
		$p = $this->input->post('data');
		$elm = new Element($p['elm_id']);
		$str = $elm->get_props();
		$this->Cmn_functs->resp('front_call',[
			'method'=> 'manage',
			'sending'=>false,
			'action'=> 'call_response',
			'data'=> $str
		]);
	}



	// DEPRECADOS POR PCLE_UPDV
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
			'data'=> ['result'=>'OK','after_action'=>$p['after_action']]
		]);
	}

	// **************************************************

	// *************************************************************************
	// *******  Borra atom_id y sus pcles
	// *************************************************************************
	function kill_atom(){
		$p = $this->input->post('data');
		if($p['id']){
			$k = new Atom($p['id']);
			$k->kill();
		}
		if($p['after_action']){
			$r = [
				'method'=>$p['after_action'],
				'sending'=>1
			];
			$this->cmn_functs->resp('front_call',$r);
		}
	}

}
