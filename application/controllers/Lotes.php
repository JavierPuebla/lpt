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

    /** PHPExcel_IOFactory */
    // include(APPPATH.'libraries/PHPExcel/IOFactory.php');


		include (APPPATH . 'JP_classes/Atom.php');
		// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$this->types_id = 2;
		$this->type_text = 'LOTE';
		$this->route = 'lotes/';


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
		$user = $this -> session -> userdata('logged_in');
		if (is_array($user)) {
			// ****** NAVBAR DATA ********
			$userActs = $this -> app_model -> get_activities($user['user_id']);
			$acts = explode(',',$userActs['elements_id']);
			// ****** END NAVBAR DATA ********
			// ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********
			$selects = [
				'emprendimiento'=>$this->cmn_functs->fill_select_by_atom_types_id(4),
				'estado'=>$this->cmn_functs->fill_select_by_atom_types_id(5),
			];
			// PREPARO LOS DATOS DEL VIEW
			$var=array(
				'route'=>$this->route,
				'user_id'=>$user['user_id'],
				'permisos'=>$this -> app_model -> get_user_data($user['user_id'])['permisos_usuario'],
				'selects'=>$selects,
				'locked'=>($user['user_id'] == 484)?false:false,
				'screen'=>$this->get_screen($this ->app_model->get_user_data($user['user_id'])),
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
			['call'=>['method'=>'listado','sending'=>true],'tag'=>'Listado de Lotes'],
			['call'=>['method'=>'call_new_atom','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Alta de Lote'],
			['call'=>['method'=>'import_data','sending'=>false,'action'=>'call','data'=>0],'tag'=>'Importar desde Archivo']
		];
		// LIMITACIONES POR PERMISOS USUARIO
		$permisos = intval($u['permisos_usuario']);
		if($permisos == 0){
			return $btns;
		}

		elseif($permisos == 1){
			$r = [$btns[0],$btns[1]];
		}
		else{
			$r = [$btns[0]];
		}
		return $r;
		// return $btns;
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
				'route'=>$this->route,
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
				'tit'=>'ALTA DE LOTE',
				'msg'=>'Error No se registro el nuevo Lote',
				'type'=>'warning',
				'container'=>'modal',
				'win_close_method' => 'back'
			];
			$this->cmn_functs->resp('myAlert',$res);
		}

	}

	/*
	query lotes
	SELECT id ,
	MAX(CASE WHEN label = 'name'  THEN value END) AS name,
    MAX(CASE WHEN label = 'emprendimiento' THEN value END) AS emprendimiento,
    MAX(CASE WHEN label = 'metros2'  THEN value END) AS metros2,
    MAX(CASE WHEN label = 'frente'  THEN value END) AS frente,
    MAX(CASE WHEN label = 'fondo'  THEN value END) AS fondo,
    MAX(CASE WHEN label = 'estado'  THEN value END) AS estado,
	MAX(CASE WHEN label = 'propietario' THEN value END) AS propietario,
    MAX(CASE WHEN label = 'esquina'  THEN value END) AS esquina,
    MAX(CASE WHEN label = 'expediente'  THEN value END) AS expediente,
    MAX(CASE WHEN label = 'partida'  THEN value END) AS partida,
    MAX(CASE WHEN label = 'circ'  THEN value END) AS circ,
    MAX(CASE WHEN label = 'manzana'  THEN value END) AS manzana,
    MAX(CASE WHEN label = 'parcela'  THEN value END) AS parcela,
    MAX(CASE WHEN label = 'calle'  THEN value END) AS calle,
    MAX(CASE WHEN label = 'altura'  THEN value END) AS altura
    FROM atoms_pcles WHERE atom_types_id = 2 GROUP BY atom_id
	*/

	// *************************************************************************
	// ******* 6 agosto 2020
	// ****** listado de lotes *******
	// *************************************************************************

	function listado(){
		$d = [];
		$pr = $this->Mdb->db->query("SELECT atom_id,
			MAX(CASE WHEN label = 'name'  THEN value END) AS name,
			MAX(CASE WHEN label = 'emprendimiento' THEN value END) AS emprendimiento,
			MAX(CASE WHEN label = 'metros2'  THEN value END) AS metros2,
			MAX(CASE WHEN label = 'frente'  THEN value END) AS frente,
			MAX(CASE WHEN label = 'fondo'  THEN value END) AS fondo,
			MAX(CASE WHEN label = 'estado'  THEN value END) AS estado,
			MAX(CASE WHEN label = 'propietario' THEN value END) AS propietario,
			MAX(CASE WHEN label = 'esquina'  THEN value END) AS esquina,
			MAX(CASE WHEN label = 'expediente'  THEN value END) AS expediente,
			MAX(CASE WHEN label = 'partida'  THEN value END) AS partida,
			MAX(CASE WHEN label = 'circ'  THEN value END) AS circ,
			MAX(CASE WHEN label = 'manzana'  THEN value END) AS manzana,
			MAX(CASE WHEN label = 'parcela'  THEN value END) AS parcela,
			MAX(CASE WHEN label = 'calle'  THEN value END) AS calle,
			MAX(CASE WHEN label = 'altura'  THEN value END) AS altura
			FROM atoms_pcles WHERE atom_types_id = 2 	GROUP BY atom_id");
		$st = $this->Mdb->db->query("SELECT st.id,st.label,st.title,st.vis_ord_num, v.nombre as vis_elem_type,st.validates  FROM atoms_struct st JOIN visual_objects v on v.id = vis_elem_type WHERE atom_types_id = 2 ORDER BY st.vis_ord_num ASC");
		$struct = ($st->result_id->num_rows)?$st->result_array():[];
		if($pr->result_id->num_rows){
			$r = [
				'route'=>$this->route,
				'method'=>'listado',
				'action'=>'response',
				'title'=>' Listado de Lotes',
				'data'=> $pr->result_array(),
				'struct'=> $struct
			];
		}else{
			exit('error en query');
		}
		$this->cmn_functs->resp('front_call',$r);
	}



	// *************************************************************************
	// ******* 05 marzo 2020
	// ******* ACTUALIZA EL PCLE POR EL ID USADO EN LIST
	// *************************************************************************
	function pcle_updv(){
		if(!$this -> session -> userdata('logged_in')){redirect('login', 'refresh');}
		$p = $this->input->post();
		$this->cmn_functs->atom_updv($this->route,$p);
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
	// ******* 10 marzo 2020
	// ******* Borra los atoms seleccionados en el array recibido
	// *************************************************************************
	function delete_selected(){
		$p = $this->input->post('data');
		foreach ($p as $id) {
			$x = new Atom($id);
			$x->kill();
		}
		$this->cmn_functs->resp('front_call',
			[
				'method'=>'delete_selected',
				'response'=>true,
				'msg'=>'Borrado...'
			]
		);
	}
	// *************************************************************************

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
