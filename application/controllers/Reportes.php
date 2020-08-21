<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
    	$this->Mdb->load->database();

		$this -> load -> model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->helper('download');
		$this->load->library('cmn_functs');


	  	// include (APPPATH . 'controllers/Excel_features.php');

		include (APPPATH . 'JP_classes/Atom.php');
		include (APPPATH . 'JP_classes/Element.php');
		include (APPPATH . 'JP_classes/Event.php');


    	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
		date_default_timezone_set('America/Argentina/Buenos_Aires');

		//****  USER PRIVILEDGES
	    $user = $this -> session -> userdata('logged_in');
	    if (is_array($user)){
	      $this->usr_obj = $this->app_model->get_obj("SELECT * FROM usuarios WHERE id = {$user['user_id']} ");
	    } else {
	      redirect('login', 'refresh');
	    }

	}


	public function index() {
      // ****** DATA PARA CUSTOMIZAR LA CLASE
		$cls_name = 'reportes';
      // ****** TABLA DE PERSISTENCIA DE DATOS
		$table = '';

      //******** ELEMENT
		$element=[""];


      // ****** RUTA DE ACCESO DEL CONTROLLER
		$route = 'reportes/';
      // ****** ******************


		$user = $this -> session -> userdata('logged_in');
		if (is_array($user)) {
		// ****** NAVBAR DATA ********
	      $userActs = $this -> app_model -> get_activities($user['user_id']);
	      $acts = explode(',',$userActs['elements_id']);
	    // ****** END NAVBAR DATA ********

	      // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********

      // $elements = $this->get_elements();

      // PREPARO LOS DATOS DEL VIEW
      $var=array(
        'route'=>$route,
        'user_id'=>$user['user_id'],
        'permisos'=>$this -> app_model -> get_user_data($user['user_id'])['permisos_usuario'],
        'locked'=>($user['user_id'] == 484)?false:false,
        'selects'=>[
          'barrio'=>$this->cmn_functs->get_dpdown_data_barrios()
        ],

        'screen'=>$this->get_screen($user),
        'screen_title'=> "Reportes",
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
			['call'=>['method'=>'repo_tool','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Reporte de Contratos'],
			['call'=>['method'=>'mora_3','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Lotes con Mora 3 Cuotas'],
			['call'=>['method'=>'mora_mas_de_3','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Lotes con Mora Mayor a 3 Cuotas'],
			['call'=>['method'=>'lotes_con_posesion','sending'=>true],'tag'=>'Lotes Con Posesion'],
			['call'=>['method'=>'rescindidos','sending'=>true],'tag'=>'Contratos Rescindidos'],
			['call'=>['method'=>'ctas_pagas_gen','sending'=>false],'tag'=>'Cuotas Lotes Pagadas'],
			['call'=>['method'=>'lotes_disponibles','sending'=>true],'tag'=>'Lotes Disponibles'],
			['call'=>['method'=>'ctas_pagas_srv','sending'=>false],'tag'=>'Servicios Pagados'],

			['call'=>['method'=>'cobranza_futura_2','sending'=>true,'action'=>'call','data'=>['barrio'=> -1,'elm_type'=> 1]],'tag'=>'Cobranza Futura'],
			['call'=>['method'=>'ctas_pagas_xmes_xcli','sending'=>false],'tag'=>'Cuotas Pagas X Mes'],
			['call'=>['method'=>'stock_lotes','sending'=>true],'tag'=>'Stock Lotes'],
			['call'=>['method'=>'revision_plan','sending'=>true],'tag'=>'Revision de Plan'],
			['call'=>['method'=>'repo_ingresos_por_lote','sending'=>false],'tag'=>'Ingresos Por Lote'],
			// ['call'=>['method'=>'lr1','sending'=>true],'tag'=>'Repo 1']
		];

		$id = intval($u['user_id']);
		if($id == 499 || $id == 498 || $id == 501 || $id == 484 || $id == 511 || $id == 502){
			return $btns;
		}

		elseif($id == 500){
			$r = [$btns[3],$btns[5],$btns[6]];
		}


		else{
			$r = [$btns[2],$btns[4],$btns[5]];
		}
		return $r;
	}

	function fetch_popover(){
		if($this->input->post('id')){
			$q = "SELECT CONCAT(a.name,' / ',a2.name) as name FROM `elements_pcles` ep
				LEFT OUTER JOIN `elements_pcles` ep1 on ep1.elements_id = ep.elements_id and ep1.struct_id = 20
				LEFT OUTER JOIN atoms a on a.id = ep1.value
				LEFT OUTER JOIN `elements_pcles` ep2 on ep2.elements_id = ep.elements_id and ep2.struct_id = 21
				LEFT OUTER JOIN atoms a2 on a2.id = ep2.value
				WHERE ep.elements_id = {$this->input->post('id')} LIMIT 1";
			$r = $this->Mdb->db->query($q);
			if($r->result_id->num_rows){
				echo $r->row()->name;
			}
		}
	}

	function repo_tool(){
		$df = new Element(0,"CONTRATO",0);
		$filter = $df->get_filters();
		$tbl_head = array_map(function($x){return ["label"=>$x['label'],"title"=>$x['title']];},$filter);
		array_unshift($tbl_head,["label"=>'prod_id',"title"=>"Lote"]);

		$fdta = $this->Mdb->db->query("SELECT * from CONTRATO WHERE cant_ctas_restantes > 0 ")->result_array();;
		$r = [
			'method'=>'repo_tool',
			'action'=>'call_response',
			'title'=>'Reporte de contratos',
			'data'=>['filter'=>$filter,'tbl_data'=>$fdta,'tbl_head'=>$tbl_head]
		];
		$this->cmn_functs->resp('front_call',$r);
	}


	//*********** 2 julio 2020
	// OBTIENE  QUERY SEGUN EL PEDIDO DE POST DATA
	/* CONTRATO VIEW MySQL

	**** 3 julio 2020
	******* NEW QUERY DE CONTRATOS

	CREATE OR REPLACE VIEW CONTRATO AS SELECT elements_id as id,
	MAX(CASE WHEN label = 'barrio_id' THEN (SELECT name FROM atoms WHERE id = value) END) AS barrio_id,
	MAX(CASE WHEN label = 'prod_id' THEN (SELECT name FROM atoms WHERE id = value) END) AS prod_id,
	MAX(CASE WHEN label = 'fec_ini' THEN value END) AS fec_ini,
	MAX(CASE WHEN label = 'current_ciclo' THEN value END) AS current_ciclo,
	MAX(CASE WHEN label = 'cant_ctas_restantes' THEN value END) AS cant_ctas_restantes,
	MAX(CASE WHEN label = 'clausula_revision' THEN value END) AS clausula_revision,
	MAX(CASE WHEN label = 'cant_ctas' THEN value END) AS cant_ctas,
	MAX(CASE WHEN label = 'indac' THEN value END) AS indac,
	MAX(CASE WHEN label = 'estado_contrato' THEN value END) AS estado_contrato,
	MAX(CASE WHEN label = 'corte_cesped' THEN value END) AS corte_cesped
	FROM elements_pcles
	WHERE elements_types_id = 1  GROUP BY elements_id


	*/

/*
note
SELECT
max(CASE WHEN label = 'cant_ctas_restantes' THEN (SELECT saldo from comprobantes c WHERE c.elements_id = elements_id ORDER BY id DESC LIMIT 1) END) AS saldo,
MAX(CASE WHEN label = 'prod_id' THEN (SELECT name from atoms WHERE id = value) END) AS lote
FROM `elements_pcles`  WHERE elements_types_id = 1 GROUP BY elements_id
*/

	function r1(){
		$token = $this->input->get('token');
		if($token == 'AG0923431BGJ2343J3'){
			$xq = "SELECT fecha,res FROM repo_1 ORDER BY id DESC LIMIT 1";
			$xqry = $this->Mdb->db->query($xq);
			if(!$xqry->result_id->num_rows){echo 'fallo la consulta..'; exit();}
			$this->output
        ->set_content_type('application/json')
        ->set_output($xqry->row()->res);
			// echo json_encode($data);
			//echo $xqry->row()->res;
				// $this->output
	      //   ->set_content_type('application/json')
	      //   ->set_output(json_encode(array('foo' => 'bar')));
				//
		}
		else{
			echo 'token no valido';
		}
	}

	function lr1(){
    $xq = "SELECT fecha,res FROM repo_1 ORDER BY id DESC LIMIT 1";
		$xqry = $this->Mdb->db->query($xq);
		if(!$xqry->result_id->num_rows){echo 'fallo la consulta..'; exit();}
		// if(empty($p)){
    //   $response =[
    //         'tit'=>'repo 1',
    //         'msg'=>'error el listado esta vacio',
    //         'type'=>'warning',
    //         'container'=>'modal',
    //         'win_close_method' => 'light_back'
    //       ];
    //   $this->cmn_functs->resp('myAlert',$response);
    // }
    $response = [
        'method'=>'lr1',
        'action'=>'response',
        'data'=>json_decode($xqry->row()->res,true),
        'tit'=>"R 1"
      ];
    $this->cmn_functs->resp('front_call',$response);
  }



	//****** 13 julio 2020;
	//**** Old reporte rodrigo 1
	//************************************************
	function old_r1(){
		$token = $this->input->get('token');
		if($token == 'EF0913431AFJ2343J1'){
			$q ="SELECT
				MAX(CASE WHEN label = 'prod_id' THEN (SELECT name FROM atoms WHERE id = ep.value) END) AS codigo_lote,
				MAX(CASE WHEN label = 'prod_id' THEN (SELECT ap.value FROM atoms_pcles ap WHERE ap.atom_id = ep.value AND ap.label = 'propietario') END) AS propietario,
				MAX(CASE WHEN label = 'barrio_id' THEN (SELECT name FROM atoms WHERE id = ep.value) END) AS barrio,
				MAX(CASE WHEN label = 'estado_contrato' THEN value END) AS estado_contrato,
				(SELECT count(id) FROM `events` WHERE elements_id = ep.elements_id and events_types_id >= 4 AND events_types_id <= 6) as cant_ctas_pagadas,
				(SELECT count(id) FROM `events` WHERE elements_id = ep.elements_id and events_types_id = 8) as cant_ctas_a_pagar,
				(SELECT count(id) FROM `events` WHERE elements_id = ep.elements_id and events_types_id = 6) as ctas_ahorro,


				(SELECT MAX((SELECT evp.value from events_pcles evp where evp.events_id = ev.id and evp.label = 'monto_pagado' AND evp.value > 0)) as monto FROM events ev WHERE ev.elements_id = ep.elements_id and events_types_id = 4) as ultimo_pago
				FROM elements_pcles ep
				WHERE ep.elements_types_id = 1 GROUP BY ep.elements_id" ;

			$r = $this->app_model->get_arr($q);
			echo json_encode($r);

		}
		else{
			echo 'token no valido';
		}
	}


	function filter(){
		$p = $this->input->post('data');
		$df = new Element(0,"CONTRATO",0);
		$filter = $df->get_filters();
		$tbl_head = array_map(function($x){return ["label"=>$x['label'],"title"=>$x['title']];},$filter);
		$extra_head =[
			['label'=>'cant_pagos','title'=>"Cuotas Pagas"],
			['label'=>'ultimo_pago_fecha','title'=>"Fecha Ultimo Pago"],
			['label'=>'ultimo_pago_monto','title'=>"Monto Ultimo Pago"],
			['label'=>'total_pagos','title'=>"Total Pagado"],
			['label'=>'total_a_pagar','title'=>"Total Adeudado"]
		];
		$th = array_merge($tbl_head,$extra_head);
		array_unshift($th,["label"=>'prod_id',"title"=>"Lote"]);
		$fdta = $this->cmn_functs->get_ftrd_qry($p,"CONTRATO");
		$d2 = [];
		foreach ($fdta as $f) {
			$x = new Element($f['id']);
			$lp= $x->get_last_payment();
			$cp = $x->get_ctas_pagas();
			$ap = $x->get_tot_a_pagar_lote();
			$d1 = [
				'cant_pagos'=>count($cp['events']),
				'ultimo_pago_fecha'=>(!empty($lp))?$lp->get_pcle('fec_pago')->value:'',
				'ultimo_pago_monto'=>(!empty($lp))?$lp->get_pcle('monto_pagado')->value:0,
				'total_pagos'=>$cp['tot_pagado'],
				'total_a_pagar'=>$ap
				] ;
			$d2[] = array_merge($f,$d1);
		}
		$r = [
			'method'=>'filter',
			'action'=>'response',
			'data'=>['tbl_data'=>$d2,'tbl_head'=>$th]
		];
		$this->cmn_functs->resp('front_call',$r);
	}


	// ****** SQL ELEM_ID LOTE_ID LOTE NAME *****
	/*
		SELECT emp.elements_id,emp.value as lote_id, lote.value as lote_nro FROM `elements_pcles` emp
		LEFT OUTER join atoms_pcles lote on lote.atom_id = emp.value AND lote.label = 'lote'
		WHERE emp.label LIKE 'prod_id' AND emp.value NOT LIKE '' AND lote.value NOT LIKE 'G%' ORDER BY lote.value
	*/


	//  result es ['lote_atm_id', 'lote_num','element' ]
	function report_cr1(){
		$ltxemp=$this->app_model->get_arr("SELECT p.atom_id as lote_atm_id, atmlote.name as lote_num, ep.elements_id as element FROM `atoms_pcles` p LEFT OUTER JOIN atoms atmlote on atmlote.id = p.atom_id LEFT OUTER JOIN elements_pcles ep on ep.value = atmlote.name WHERE p.atom_types_id = 2 AND p.value LIKE 'CERRO RICO I' AND ep.label = 'prod_id' ");
		// echo "</br> count: ".count($ltxemp);
		$tp = 0;
		$res = [];
		$limit = 4;
		$x=0;
		foreach ($ltxemp as $lt) {
			// $x++;
			// if($x == $limit) break;

			$elm = new Element($lt['element'],'','');

			$cli = new Atom($this->app_model->get_cli_by_owner_id($elm->owner_id)->id,'','');




			$cp = $elm->get_ctas_pagas();
			// echo "</br> pagas : ".$cp['tot_pagado'];
			$tp +=intval($cp['tot_pagado']);
			$cadl = $elm->get_events(6,'pagado');
			// echo "</br> adls : ".$cadl['tot_pagado'];
			// echo '</br>Tot Cli:'.(intval($cp['tot_pagado']) + intval($cadl['tot_pagado']));
			$tp +=intval($cadl['tot_pagado']);
			$capg = $elm->get_events(8,'a_pagar');
			$rap = [];
			$trap = 0;
			foreach ($capg['events'] as $e) {
				$rap[]=['fecha'=>$e['fecha'],'monto'=>$e['pcles'][0]['value']];
				$trap += intval($e['pcles'][0]['value']);
				// echo "  fecha".$e['fecha'];
				// echo "   monto".$e['pcles'][0]['value'];
			}

			$r[]=[
				'cli'=>$cli->get_pcle('nombre'),
				'pagado_cli'=>(intval($cp['tot_pagado']) + intval($cadl['tot_pagado'])),
				'a_pagar'=>['events'=>$rap,'tot'=>$trap]
				];
		}
		$res=['totgen'=>$tp,'data'=>$r];
	 	//    $t = intval($cp['tot_pagado']) + intval($cadl['tot_pagado']);
		// $out = "<table style='table-layout: fixed;'><thead><tr>";
		// $out .= "<th>".$cli->get_pcle('nombre')."</th>";
		// $out .= "<th>".$t. "</th>";


	    echo json_encode(array(
	      'callback'=> 'mk_report_cr1',
	      'param'=> $res
	    ));
	}

	function repo_en_mora(){
		$p = $this->input->post('data');
		$els = $this->app_model->get_arr("SELECT e.id,epl.value AS prod_id, epc.value AS cli_id FROM elements e
			JOIN elements_pcles epl ON epl.elements_id = e.id AND epl.label LIKE 'prod_id'
			JOIN elements_pcles epc ON epc.elements_id = e.id AND epc.label LIKE 'cli_id'
			WHERE elements_types_id = {$p['elm_type']}");

		$r = [];
		$ttl_impagas = 0;
		$gttl = 0;
		$e = 0;
		$c = 0;
		foreach ($els as $el) {
			$elx = new Element($el['id']);
			// if($elx->type !== "EMPTY"){
				$e++;
				$m = $elx->get_events(4,'a_pagar');
				if(intval(count($m['events']) > 0)){
					$clid = $elx->get_pcle('cli_id')->value;
					$cl = new Atom($clid);
					$cln = $cl->get_pcle('nombre')->value.', '.$cl->get_pcle('apellido')->value;
					$fev = $elx->get_first_event('a_pagar');
					$fnro_cta = $fev['pcles']['nro_cta']->value;
					$lev = $elx->get_last_event('a_pagar');
					$lnro_cta = $lev['pcles']['nro_cta']->value;

					if($fnro_cta !== $lnro_cta){
						$ordnums = $fnro_cta.' A '.$lnro_cta;
					}else{
						$ordnums = $fnro_cta;
					}

					$r[] = [
						'elem_id'=>$elx->id,
						'cli_name'=>$cln,
						'cant_events'=>count($m['events']),
						'ordnums'=>str_replace('Cuota ', '', $ordnums),
						'total'=>$m['total']
					];
					$ttl_impagas += count($m['events']);
					$gttl += intVal($m['total']);
					$c++;
				}
			// }
		}
		// echo '<br> Elements checked:'.$e;
		// echo '<br> Elements en mora:'.$c;
		// echo '<br> Raw:'.json_encode($r);

		$res = [
			'method'=>'repo_en_mora',
			'action'=>'response',
			'gttl'=>$gttl,
			'impagas'=>$ttl_impagas,
			'elmts_con_mora'=>$c,
			'tot_elmts'=>$e,
			'drill_data'=>$r
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}

	function mora_3(){
		$elms = $this->app_model->get_arr("SELECT ev.elements_id as id FROM `events` ev
			JOIN events_pcles estado on estado.events_id = ev.id and estado.label = 'estado'
			JOIN elements_pcles ep on ep.elements_id = ev.elements_id and ep.label = 'prod_id'
			JOIN elements e on e.id = ep.elements_id
            JOIN atoms ow on ow.id = e.owner_id
			JOIN atoms a on a.id = ep.value
			WHERE ow.atom_types_id < 8 AND ev.events_types_id = 4 AND estado.value = 'a_pagar' GROUP BY ev.elements_id HAVING count(ev.elements_id) <= 3 ORDER BY a.name ASC,  ev.ord_num ASC");
		$r=[];
		$ci = 0;
		$gttl = 0;
		$not_found = [];
		foreach ($elms as $elm){
			$e = new Element($elm['id']);
			if(!empty($e->id)){
				$cli_name = (new Atom($e->get_pcle('prod_id')->value))->name .' '. (new Atom($e->get_pcle('cli_id')->value))->get_pcle('apellido')->value .' '. (new Atom($e->get_pcle('cli_id')->value))->get_pcle('nombre')->value ;
				$m = $e->get_events(4,'a_pagar');
				if(count($m['events'])>1){
					$nro_cta = 'desde: ' . intval($m['events'][0]['ord_num']) .' hasta: '. intVal($m['events'][count($m['events'])-1]['ord_num']);
				}else{
					$nro_cta = 'cuota: ' . intval($m['events'][0]['ord_num']);
				}

			}else{
				$not_found[]=$elm['id'];
			}
			$r[] = [
				'elem_id'=>$e->id,
				'cli_name'=>$cli_name,
				'cant_events'=>count($m['events']),
				'ordnums'=>$nro_cta,
				'total'=>$m['total']
			];
			$ci += count($m['events']);
			$gttl += intVal($m['total']);

		}
		// $file_name = Excel_features::create_file($r,'reporte_hasta_3_cuotas_mora');
		// $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
		// $print = $this->cmn_functs->get_accion_icon('print','print_repo','mora_3',-1);

		$res = [
				'method'=>'mora_3',
				'action'=>'response',
				'gttl'=>$gttl,
				'impagas'=>$ci,
				'elmts_con_mora'=>count($elms),
				'not_found'=>$not_found,
				'drill_data'=>$r,
				// 'download'=>$dnld,
				// 'print'=>$print
			];
		$this->cmn_functs->resp('front_call',$res);
	}



	function mora_mas_de_3(){
		$elms = $this->app_model->get_arr("SELECT ev.elements_id as id FROM `events` ev
			JOIN events_pcles estado on estado.events_id = ev.id and estado.label = 'estado'
			JOIN elements_pcles ep on ep.elements_id = ev.elements_id and ep.label = 'prod_id'
			JOIN elements e on e.id = ep.elements_id
            JOIN atoms ow on ow.id = e.owner_id
			JOIN atoms a on a.id = ep.value
			WHERE ow.atom_types_id < 8 AND ev.events_types_id = 4 AND estado.value = 'a_pagar' GROUP BY ev.elements_id HAVING count(ev.elements_id) > 3 ORDER BY a.name ASC,  ev.ord_num ASC");
		$r=[];
		$ci = 0;
		$gttl = 0;
		$not_found = [];
		foreach ($elms as $elm){
			$e = new Element($elm['id']);
			if(!empty($e->id)){
				$cli_name = (new Atom($e->get_pcle('prod_id')->value))->name .' '. (new Atom($e->get_pcle('cli_id')->value))->get_pcle('apellido')->value .' '. (new Atom($e->get_pcle('cli_id')->value))->get_pcle('nombre')->value ;
				$m = $e->get_events(4,'a_pagar');
				if(count($m['events'])>1){
					$nro_cta = 'desde: ' . intval($m['events'][0]['ord_num']) .' hasta: '. intVal($m['events'][count($m['events'])-1]['ord_num']);
				}else{
					$nro_cta = 'cuota: ' . intval($m['events'][0]['ord_num']);
				}

			}else{
				$not_found[]=$elm['id'];
			}
			$r[] = [
				'elem_id'=>$e->id,
				'cli_name'=>$cli_name,
				'cant_events'=>count($m['events']),
				'ordnums'=>$nro_cta,
				'total'=>$m['total']
			];
			$ci += count($m['events']);
			$gttl += intVal($m['total']);

		}
		$print = $this->cmn_functs->get_accion_icon('print','print_repo','mora_mas_de_3',-1);

		$res = [
				'method'=>'mora_mas_de_3',
				'action'=>'response',
				'gttl'=>$gttl,
				'impagas'=>$ci,
				'elmts_con_mora'=>count($elms),
				'not_found'=>$not_found,
				'drill_data'=>$r,
				'print'=>$print
			];
		$this->cmn_functs->resp('front_call',$res);
	}


	function rescindidos(){
    $p = $this->cmn_functs->get_rescindidos();
    if(empty($p)){
      $response =[
            'tit'=>'Registro de Rescindidos',
            'msg'=>'error el listado esta vacio',
            'type'=>'warning',
            'container'=>'modal',
            'win_close_method' => 'light_back'
          ];
      $this->cmn_functs->resp('myAlert',$response);
    }
    $response = [
        'method'=>'rescindidos',
        'action'=>'response',
        'data'=>$p,
        'tit'=>"Listado de rescindidos"
      ];
    $this->cmn_functs->resp('front_call',$response);
  }



	// **************** REPORTE DE EGRESOS DE CAJA
	// function repo_egresos_cajas(){
	// 	$p = $this->input->post();
	// 	$fec_in = $this->cmn_functs->fixdate_ymd($p['fec_in'])." 00:00:00";
	// 	$fec_out = $this->cmn_functs->fixdate_ymd($p['fec_out'])." 23:59:59";
	// 	$q= "SELECT
	// 			g.fecha,
	// 			pnm.value as proveedor ,
	// 			cci.nombre concepto,
	// 			g.monto as monto,
	// 			g.observaciones as detalle,
	// 			caja.nombre as caja,
	// 			g.operacion_nro as nro_op,
	// 			g.id as op_id,
	// 			cnt.nombre as caja_pases,
	// 			 FROM contab_asientos g
	// 	LEFT OUTER join atoms_pcles pnm ON pnm.atom_id = g.proveedor_id AND label LIKE 'nombre'
	// 	LEFT OUTER JOIN contab_cuenta_de_imputacion cci ON cci.id = g.cuenta_imputacion_id
	// 	LEFT OUTER JOIN contab_cuentas caja ON caja.id = g.cuentas_id
	// 	LEFT OUTER JOIN contab_cuentas cnt ON cnt.id = g.cta_contraparte_id
	// 	WHERE tipo_asiento = 'EGRESOS' AND g.fecha >= '{$fec_in}' AND g.fecha <= '{$fec_out}'";
	// 	$gd = $this->app_model->get_arr($q);
	// 	if(!$gd){exit;}
	// 	foreach ($gd as $i {
	// 		$q2 = "SELECT b.value as cc_name, cc.percent as porcentaje FROM `contab_cc_distrib` cc
	// 				LEFT OUTER join atoms_pcles b on b.label = 'name' AND b.atom_id = cc.barrio_id
	// 				WHERE cc.asiento_id = {$i['op_id']}";
	// 		$qdst = $this->app_model->get_arr($q2);
	// 		$gd['cc_distrib']=$qdst;

	// 	}

	// }



	// **************** END REPO EGRESOS DE DCAJA


		//****** 03 agosto 2020
		//**** nueva version de cobranza futura
		//************************************************
		function cobranza_futura_2(){
			$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
			// date viene por post cuando este listo el front
			$p = $this->input->post('data');
			$barrio = null;
			if(is_array($p) && array_key_exists('barrio',$p)){
				$barrio = $this->Mdb->db->query("SELECT name FROM atoms WHERE id = {$p['barrio']}")->row()->name;
			}
			$date = null;
			if($date){
				$xq = "SELECT fecha,res FROM repo_contratos WHERE fecha LIKE STR_TO_DATE($date, '%d/%m/%Y') ORDER BY id DESC LIMIT 1";
			}else{
				$xq = "SELECT fecha,res FROM repo_contratos ORDER BY id DESC LIMIT 1";
			}
			$xqry = $this->Mdb->db->query($xq);
			if($xqry->result_id->num_rows > 0){
				$d = json_decode($xqry->row()->res,TRUE);
				$ctrs = [];
				$t_val_presente = 0;
				$t_val_financ = 0;
				foreach($d as $data){
					if(intval($data['monto_1_pago'])> 0){
						if($barrio){
							if($data['barrio'] == $barrio){
								$ctrs[]=
									[
										'Codigo Lote'=>$data['codigo_lote'],
										"Barrio"=>$data['barrio'],
										'Valor Presente'=>$data['monto_1_pago'],
										'Valor Financiado'=>$data['monto_a_pagar_financ']
									];
								$t_val_presente += $data['monto_1_pago'];
								$t_val_financ += $data['monto_a_pagar_financ'];
							}
						}else{
							$ctrs[]=
								[
									'Codigo Lote'=>$data['codigo_lote'],
									"Barrio"=>$data['barrio'],
									'Valor Presente'=>$data['monto_1_pago'],
									'Valor Financiado'=>$data['monto_a_pagar_financ']
								];
							$t_val_presente += $data['monto_1_pago'];
							$t_val_financ += $data['monto_a_pagar_financ'];
						}
					}
				}
			}
			$res = [
				'method'=>'cobranza_futura_2',
				'action'=>'response',
				'barrio'=>$barrio,
				'tot_1pg'=>$t_val_presente,
				'tot_gen'=>$t_val_financ,
				'contratos'=>$ctrs,
			];
			echo json_encode(array(
				'callback'=> 'front_call',
				'param'=> $res
			));
		}






	// **************** COBRANZA FUTURA
	function cobranza_fut(){
		$p = $this->input->post('data');
		$br = new Atom($p['barrio']);
		$slc = ($p['barrio']!= -1)?'AND b.value  LIKE \''.$br->name.'\'':'';
		//****  SELECCIONANDO TODOS LOS CONTRATOS
		$els = $this->app_model->get_arr("
			SELECT
			e.id,
			epl.value AS prod_id,
			epc.value AS cli_id,
			b.value as barrio
			FROM elements e
			JOIN elements_pcles epl ON epl.elements_id = e.id AND epl.label = 'prod_id'
			JOIN elements_pcles epc ON epc.elements_id = e.id AND epc.label = 'cli_id'
			join atoms_pcles b ON b.atom_id = epl.value AND b.label LIKE 'emprendimiento'
			WHERE e.elements_types_id = 1 {$slc}  ORDER BY e.id");
		// AND epct.value > 0
		$r = [];
		$r2 = [];
		$dt = new DateTime();
		// PROY FULL LIFE
		$tgen = 0;
		$tt_1pago = 0;

		// PROY CICLO ANTICIPO
		$tfn = 0;
		$ttfn_1pago = 0;

		$vt = 0;
		$vsf = 0;
		$vp = 0;

		$mm = [];
		$mm[$dt->format('Y-m')] = 0;
		$mmu = [];

		$mm_fn = [];
		$mm_fn [$dt->format('Y-m')] = 0;
		$mmu_fn = [];

		// **** LOOPING CONTRATOS
		foreach ($els as $el) {
			$elx = new Element($el['id']);
			$prod_id = $elx->get_pcle('prod_id')->value;
			$cli_id = $elx->get_pcle('cli_id')->value;
			$cli_atm = new Atom($cli_id);
			$prod_atm = new Atom($prod_id);
			$barrio = $prod_atm->get_pcle('emprendimiento')->value;
			$lote = (!empty($prod_atm))?$prod_atm->name:'';
			//*** TIENE NOMBRE DE LOTE Y DE BARRIO
			if(!empty($lote) && !empty($barrio)){
				//*** ELM_CF DEVUELVE TODOS LOS EVENTOS CUOTA_FUTURA
				$cfut = $this->get_elm_cf($elx);
				// *** COBRANZA PROYECCION FULL LIFE
				if($cfut['vt'] > 0 && $cfut['evs_t'] > 0){
					$vt += $cfut['vt'];
					// **** EXISTE EL ATOM DE CLIENTE Y CUOTAS FUTURAS TIENE UN VALOR
					if(!empty($cli_atm->name) && !empty($cfut['vt'])){
						$r[] = ['elm_id'=>$el['id'],'cliente'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'cobranza'=>$cfut,'vt'=>$cfut['vt'],'vp'=>$cfut['vp']];
						// $vt_get += intVal($cfut['t_1p']);
						// *** CONSTRUYE EVENTOS DE COBRANZA MES A MES
						foreach ($cfut['evs_t'] as $ev) {
						 	// *** AGRUPA EN CADA MES LOS TOTALES DE CADA LOTE
						 	$evdt = new DateTime($ev['date']);
						 	if(array_key_exists($evdt->format('Y-m'),$mm)){
						 		$mm[$evdt->format('Y-m')] += intval($ev['monto']);
						 		$mmu[$evdt->format('Y-m')][] = ['name'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'monto'=>intval($ev['monto']),'ord_num'=>$ev['ord_num']];
						 	}else{
						 		$mm[$evdt->format('Y-m')] = intval($ev['monto']);
						 		$mmu[$evdt->format('Y-m')][] = ['name'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'monto'=>intval($ev['monto']),'ord_num'=>$ev['ord_num']];
						 	}
						}
						// FIX DE MES A MES
						$mm2 = [];
						foreach ($mm as $k => $v) {
							if($v > 20000){
								$mm2[] = ['date'=>$k,'value'=>$v];

							}
						}
					}
				}
				// *** PROYECCION CICLO 1 Y CICLO 2
				if($cfut['vsf'] > 0 && $cfut['evs_saf'] > 0){
					$vsf += $cfut['vsf'];
					// *** CONSTRUYE EVENTOS DE COBRANZA MES A MES
					if(!empty($cli_atm->name) && !empty($cfut['vsf'])){
						$r2[] = ['elm_id'=>$el['id'],'cliente'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'cobranza'=>$cfut,'vsf'=>$cfut['vsf'],'vp'=>$cfut['vp']];
						// $ttfn_1pago += intVal($cfut['tcfn_1p']);
						foreach ($cfut['evs_saf'] as $efn) {
						 	// *** AGRUPA EN CADA MES LOS TOTALES DE CADA LOTE
						 	$fndt = new DateTime($efn['date']);
						 	if(array_key_exists($fndt->format('Y-m'),$mm_fn)){
						 		$mm_fn[$fndt->format('Y-m')] += intval($efn['monto']);
						 		$mmu_fn[$fndt->format('Y-m')][] = ['name'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'monto'=>intval($efn['monto']),'ord_num'=>$efn['ord_num']];
						 	}else{
						 		$mm_fn[$fndt->format('Y-m')] = intval($efn['monto']);
						 		$mmu_fn[$fndt->format('Y-m')][] = ['name'=>$cli_atm->name,'lote'=>$lote,'barrio'=>$barrio,'monto'=>intval($efn['monto']),'ord_num'=>$efn['ord_num']];
						 	}
						}
						// FIX DE MES A MES
						// $mm_fn = ksort($mm_fn);
						$mm_fn2 = [];
						foreach ($mm_fn as $xk => $xv) {
							if($xv > 20000){
								$mm_fn2[] = ['date'=>$xk,'value'=>$xv];

							}
						}
					}
				}
				$vp += $cfut['vp'];
			}
		}
		$res = [
			'method'=>'cobranza_fut',
			'action'=>'response',
			'selection'=>$p['barrio'],
			'tot_gen'=>$vt,
			'tot_1pg'=>$vp,
			'contratos'=>$r,
			'mes_a_mes'=>(!empty($mm2))?$mm2:[],
			'mmu'=>$mmu,
			'ttfn_1pago'=>$vsf,
			'mm_fn'=>(!empty($mm_fn2))?$mm_fn2:[],
			'mmu_fn'=>$mmu_fn
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}





	//******  HELP FUNCS DE COBRANZA FUTURA
	//****  EVENTOS DE CADA CONTRATO
	function get_elm_cf($elm){
		$dte = new DateTime;
		// toma año y mes, pone dia del mes en 1 y obtiene
		$dx = ($dte->format('Y')).'-'.($dte->format('m')).'-01';
		$events = $this->app_model->get_arr("SELECT
			ev.id as ev_id,
			ev.events_types_id,
			ev.date AS fecha,
			ev.ord_num AS ord_num,
			evp_mto.value AS monto
			FROM `events` ev
			JOIN events_pcles evp_mto ON evp_mto.events_id = ev.id AND evp_mto.label LIKE 'monto_cta'
			WHERE ev.elements_id = {$elm->id} AND ev.date > '{$dx}' AND ev.events_types_id = 8 ORDER BY fecha ASC ");

	    // *** EVENTS TIENE TODOS LOS PAGOS FUTURO DEL CONTRATO DESDE MES ACTUAL
		if(!empty($events)){

		//***** $X => TODOS LOS PAGOS FUTUROS DEL CONTRATO
			$x = array_map(
				function($i){
					return  [
						'date'=>$i['fecha'],
						'ord_num'=>$i['ord_num'],
						'monto'=>$i['monto']
					];
				},
				$events
			);
	    // VALOR PRESENTE = TODAS LAS CUOTAS RESTANTES A VALOR HOY MAS 120 CUOTAS RESTANTES A VALOR HOY
	    // SI EL CONTRATO ($P) ESTA EN EL CICLO 1, TOMO EL ULTIMO PAGO DE $X OBTENGO MONTO A REFINANCIAR Y LO GUARDO EN $CFN ADEMAS SIMULO LA FINANCIACION del ciclo 2 LA GUARDO EN $C.
	        $saf_1p =[];
	        $rf = [];
	      //***  ESTA EN  CICLO 1 DE FINANC DE DOS CICLOS
	        if(intval($elm->get_pcle('current_ciclo')->value) === 1 && intval($elm->get_pcle('cant_ctas_ciclo_2')->value) > 0){
	        // *** CONCAT DE MONTO A REFINANCIAR $X
	        // LX => ULTIMO PAGO
	        	$lx = $x[count($x)-1];
	        	$fnd = new DateTime($lx['date']);
	        	$fndate = $fnd->modify('next month');
	        	//ERROR-16/09_9:57 ->  no encuentro cantidad de cuotas restantes en el pcle
	        	$saf_1p[] = ['date'=>$fndate->format('Y-m'),'ord_num'=>'saldo a refinanciar','monto'=>(intval($lx['monto'])*intval($elm->get_pcle('cant_ctas_restantes')->value))];

	        // *** CONCAT DE TOTAL CON CUOTAS DE REFINANCIADO DEL CICLO 2
	        	$rf = $this->simulate_cuotas_new(
	        		intval($elm->get_pcle('cant_ctas_ciclo_2')->value),
	        		$lx['monto'],
	        		$lx['date'],
	        		intval($elm->get_pcle('indac')->value),
	        		intval($elm->get_pcle('frecuencia_indac')->value)
	        	);
	        	$xt = array_merge($x,$rf);
	        }
	      //*** ES CICLO 2 NO HACE FALTA SIMULAR CUOTAS FUTURAS
	        else{
	        	$xt = $x;

	        }
	        $vsaf = array_merge($x,$saf_1p);
	        $vt = array_reduce($xt,function($z,$i){return $z += intval($i['monto']);});
	        $vsf = array_reduce($vsaf,function($v,$i){return $v += intval($i['monto']);});
	        $vp = (intval($x[0]['monto']) * count($xt));

	        return ['vt'=>$vt,'vsf'=>$vsf,'vp'=>$vp,'evs_t'=>$xt,'evs_saf'=>$vsaf];
	    }else{
	    	return ['vt'=>0,'vsf'=>0,'vp'=>0,'evs_t'=>0,'evs_saf'=>0];
	    }
	}


	function simulate_cuotas_new($cant_ctas,$mto_cta,$fec_init,$indac,$frec_indac){
		$c = [];
		$d = new DateTime($fec_init);
		$fv = $d->modify('next month');
		for ($i=1; $i <= intval($cant_ctas); $i++){
			$c[]= [
				'date'=>$fv->format('Y-m-d'),
				'ord_num'=>$i,
				'monto'=>$mto_cta
			];
	          // INCREMENTA EL MONTO NOMINAL DE CUOTAS
	          // APLICACION DE INTERES SEMESTRAL

	          // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
			if(intval($indac) > 0 && intval($frec_indac) > 0){
				if($i > 1 && $i % $frec_indac == 0){
					$mto_cta = round($mto_cta * $indac / 100 + $mto_cta);
				}
			}
	        // INCREMENTO DEL MES DE FV
			$fv->modify('next month');
		}
		return $c;
	}






	// *********************
	// esta funcion stock_lotes es estatica queda por hacer una real
	//******* A VENDER
	function stock_lotes(){

		$tbl_cnt = [
			[
				'Emprendimiento'=>'Cerro Rico 1',
				'Cant. lotes disp.'=>77,
				'Valor cta. inicial'=>7800,
				'Valor lote'=>1544400,
				'Valor total'=>118918800
			],
			[
				'Emprendimiento'=>'Cerro Rico 2',
				'Cant. lotes disp.'=>38,
				'Valor cta. inicial'=>8800,
				'Valor lote'=>1663200,
				'Valor total'=>63201600
			],
			[
				'Emprendimiento'=>'Cerro Rico 2 etapa 2',
				'Cant. lotes disp.'=>32,
				'Valor cta. inicial'=>9200,
				'Valor lote'=>1821600,
				'Valor total'=>58291200
			],
			[
				'Emprendimiento'=>'Garin',
				'Cant. lotes disp.'=>54,
				'Valor cta. inicial'=>9850,
				'Valor lote'=>1950300,
				'Valor total'=>105316200
			],
			[
				'Emprendimiento'=>'Moreno',
				'Cant. lotes disp.'=>118,
				'Valor cta. inicial'=>11500,
				'Valor lote'=>2277000,
				'Valor total'=>268686000
			]
		];
		$data = [['Total General'=>614413800],$tbl_cnt];
		$res = [
				'method'=>'stock_lotes',
				'action'=>'response',
				'data'=>$data,
			];
		$this->cmn_functs->resp('front_call',$res);
	}

	//****** CUOTAS PAGAS GENERAL
	function ctas_pagas_gen(){
		$p = $this->input->post('data');
		$indt = $p['fec_desde']; //'10/02/2019';
		$xdt = $p['fec_hasta'];//date('d/m/Y');
		// $prd contiene cada mes en formato '%/03/2019'
 		$prd = $this->get_period($indt,$xdt);
 		$months = [];
		// $lts = $this->app_model->get_obj("SELECT COUNT(*) as activos FROM `atoms_pcles` p JOIN atoms a on a.id = p.atom_id WHERE label = 'estado' AND value = 'ACTIVO'");
		$items = [];
		for ($i = 0; $i < count($prd); $i++) {
			// $items[]=$prd[$i];
			// $elm_activos = $this->get_elms_activos($prd[$i]);
			$q = "SELECT
					(CASE WHEN a.name != '' THEN 'CUOTA LOTE '  WHEN  asrv.name != '' THEN serv.name END) as 'Detalle',
					(CASE WHEN a.name != '' THEN a.name WHEN asrv.name != '' THEN asrv.name END) as 'Codigo Lote',
                    nc.value as 'Nro. Cuota',
                    dp.value as 'Fecha de Pago',
                    -- cnt.nombre as 'Caja / Cuenta',
                    p.value as 'Monto Cuota',
                   	IF( intrs.value > 0 , intrs.value,0 ) as 'Intereses',
                   	IF(intrs.value > 0, intrs.value +p.value,p.value ) as 'Total'
					FROM `events` ev
                    LEFT OUTER JOIN elements ctr on ctr.id = ev.elements_id
                    LEFT OUTER JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
					LEFT OUTER JOIN events_pcles nc on nc.events_id = ev.id AND nc.label = 'nro_cta'
                    LEFT OUTER JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
					LEFT OUTER JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'
					LEFT OUTER JOIN events_pcles intrs on intrs.events_id = ev.id AND intrs.label = 'interes_mora'
					LEFT OUTER JOIN elements_pcles epp on epp.elements_id = ev.elements_id AND epp.label = 'prod_id'
                    LEFT OUTER JOIN elements_pcles eps on eps.elements_id = ev.elements_id AND eps.label = 'atom_id'
                    LEFT OUTER JOIN elements_pcles epsn on epsn.elements_id = ev.elements_id AND epsn.label = 'atom_name'
					-- LEFT OUTER JOIN events_pcles eprec on eprec.events_id = ev.id AND eprec.label = 'recibo_nro'
					-- LEFT OUTER JOIN contab_asientos  opc on opc.nro_comprobante = eprec.value
					LEFT OUTER JOIN elements elm2 on elm2.id = eps.elements_id
                    LEFT OUTER JOIN elements srvo on srvo.id = elm2.owner_id
                    LEFT OUTER JOIN elements_pcles srvop on srvop.elements_id = srvo.id AND srvop.label = 'prod_id'
                    LEFT OUTER JOIN atoms asrv on asrv.id = srvop.value
                    LEFT OUTER JOIN atoms a on a.id = epp.value
                    LEFT OUTER JOIN atoms serv on serv.id = eps.value
                    -- LEFT OUTER JOIN contab_cuentas cnt on cnt.id = cuentas_id

                    WHERE STR_TO_DATE(dp.value,'%d/%m/%Y') >= STR_TO_DATE('{$indt}','%d/%m/%Y')   AND STR_TO_DATE(dp.value,'%d/%m/%Y') <= STR_TO_DATE('{$xdt}','%d/%m/%Y')  ORDER BY STR_TO_DATE(dp.value,'%d/%m/%Y') ASC";
			$res = $this->app_model->get_arr($q);

			// to test query
			 /*LEFT OUTER JOIN elements ctr on ctr.id = ev.elements_id
			        LEFT OUTER JOIN (SELECT cuentas_id,lote_id from contab_asientos WHERE cuenta_imputacion_id = 191 AND estado = 1 ) cj on cj.lote_id = ctr.owner_id
					LEFT OUTER JOIN contab_cuentas cnt on cnt.id = cj.cuentas_id
			 */
			// foreach ($m as $mv) {
			// 	if(!empty($mv['detalle'])){
			// 		$items[]=['Detalle'=>$mv['detalle'],'Cuota Nro'=>$mv['cta_nro'],'Monto'=>$mv['monto'],'Fecha de Pago'=>$mv['fec_pago'],'Caja / Cuenta'=>$mv['caja_cuenta'],'Nro. Operación'=>$mv['op_numero']];
			// 	}
			// }
		}
		if(count($res) > 0){
			// $file_name = Excel_features::create_file($res,'reporte_ctas_pagas');
			// $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
			// $print = $this->cmn_functs->get_accion_icon('print','print_repo','ctas_pagas_gen',-1);
			$response = [
					'method'=>'ctas_pagas_gen',
					'action'=>'response',
					'data'=>$res,
					// 'download'=>$dnld,
					// 'print'=>$print,
					'tit'=>'Reporte de cuotas pagadas'
				];
			$this->cmn_functs->resp('front_call',$response);
		}else{
			$response =[
	          'tit'=>'Reporte de cuotas pagadas ',
	          'msg'=>'El rango de fechas seleccionadas no es valido',
	          'type'=>'warning',
	          'container'=>'modal',
	          'win_close_method' => 'back'
	        ];
        	$this->cmn_functs->resp('myAlert',$response);
		}

	}
	//****** SERVICIOS PAGOS POR MES POR RANGO MESES
	 	function ctas_pagas_srv(){
		$p = $this->input->post('data');
		$indt = $p['fec_desde']; //'10/02/2019';
		$xdt = $p['fec_hasta'];//date('d/m/Y');
		// $prd contiene cada mes en formato '%/03/2019'
 		// fake dates
 		// 	$indt = '10/05/2019';
		// $xdt = date('d/m/Y');
 		$prd = $this->get_period($indt,$xdt);
 		$months = [];
		// $lts = $this->app_model->get_obj("SELECT COUNT(*) as activos FROM `atoms_pcles` p JOIN atoms a on a.id = p.atom_id WHERE label = 'estado' AND value = 'ACTIVO'");
		$items = [];
		for ($i = 0; $i < count($prd); $i++) {
			// $items[]=$prd[$i];
			// $elm_activos = $this->get_elms_activos($prd[$i]);
			$q = "SELECT
					(CASE WHEN asrv.name != '' THEN serv.name END) as 'Detalle',
                    dp.value as 'Fecha de Pago',
                    asrv.name as 'Codigo Lote',
                    nc.value as 'Cuota Nro.',
                    p.value as 'Monto Cuota'

                    -- cnt.nombre as 'Caja / Cuenta'

					FROM `events` ev
                    JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
					LEFT OUTER JOIN events_pcles nc on nc.events_id = ev.id AND nc.label = 'nro_cta'
                    JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
					JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'

                    LEFT OUTER JOIN elements_pcles eps on eps.elements_id = ev.elements_id AND eps.label = 'atom_id'
                    LEFT OUTER JOIN elements_pcles epsn on epsn.elements_id = ev.elements_id AND epsn.label = 'atom_name'
					-- LEFT OUTER JOIN events_pcles eprec on eprec.events_id = ev.id AND eprec.label = 'recibo_nro'
					-- LEFT OUTER JOIN contab_asientos  opc on opc.nro_comprobante = eprec.value
					-- LEFT OUTER JOIN contab_cuentas cnt on cnt.id = opc.cuentas_id
					LEFT OUTER JOIN elements elm2 on elm2.id = eps.elements_id
                    LEFT OUTER JOIN elements srvo on srvo.id = elm2.owner_id
                    LEFT OUTER JOIN elements_pcles srvop on srvop.elements_id = srvo.id AND srvop.label = 'prod_id'
                    LEFT OUTER JOIN atoms asrv on asrv.id = srvop.value

                    LEFT OUTER JOIN atoms serv on serv.id = eps.value

					WHERE  serv.name != 'RESERVA DE LOTE' AND  eps.value != '' AND STR_TO_DATE(dp.value,'%d/%m/%Y') >= STR_TO_DATE('{$indt}','%d/%m/%Y')   AND STR_TO_DATE(dp.value,'%d/%m/%Y') <= STR_TO_DATE('{$xdt}','%d/%m/%Y') ORDER BY STR_TO_DATE(dp.value,'%d/%m/%Y') ASC";
			$m = $this->app_model->get_arr($q);
			// foreach ($m as $mv) {
			// 	if(!empty($mv['detalle'])){
			// 		$items[]=['Detalle'=>$mv['detalle'],'Cuota Nro'=>$mv['cta_nro'],'Monto'=>$mv['monto'],'Fecha de Pago'=>$mv['fec_pago'],'Caja / Cuenta'=>$mv['caja_cuenta'],'Nro. Operación'=>$mv['op_numero']];
			// 	}
			// }
		}
		if(count($m)>0){
			// $file_name = Excel_features::create_file($m,'reporte_servicios_pagados');
			// $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
			// $print = $this->cmn_functs->get_accion_icon('print','print_repo','lotes_con_posesion',-1);
			$res = [
					'method'=>'ctas_pagas_srv',
					'action'=>'response',
					'data'=>$m,
					// 'download'=>$dnld,
					// 'print'=>$print,
					'tit'=>'Cuotas de Servicios Pagadas '
				];
			$this->cmn_functs->resp('front_call',$res);
		}else{
			// ****** ERROR WINDOW
			$res =[
	          'tit'=>'Cuotas de Servicios Pagadas ',
	          'msg'=>'El rango de fechas seleccionadas no es valido',
	          'type'=>'warning',
	          'container'=>'modal',
	          'win_close_method' => 'back'
	        ];
        	$this->cmn_functs->resp('myAlert',$res);
		}

	}

	//  **** INGRESOS POR LOTE PARA RANGO DE FECHAS *******
	function repo_ingresos_por_lote(){
	    $p = $this->input->post('data');
	    $indt = $p['fec_desde']; //'10/02/2019';
	    $xdt = $p['fec_hasta'];//date('d/m/Y');
	    // $indt = '01/09/2019';
	    // $xdt = '31/09/2019';
	    $rl = [];
	    //***  OBTENGO EL RANGE DE ASIENTOS POR PAGO DE CUOTA
	    $asiento = $this->Mdb->db->query("SELECT DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha ,a.lote_id,SUM(a.monto) as monto,a.nro_comprobante FROM `contab_asientos` a WHERE a.tipo_asiento = 'INGRESOS' AND a.cuenta_imputacion_id = 191 AND a.estado = 1 AND a.fecha >= STR_TO_DATE('{$indt}','%d/%m/%Y')  and a.fecha <= STR_TO_DATE('{$xdt}','%d/%m/%Y') GROUP BY a.lote_id ORDER BY a.fecha  ASC ");
	    if($asiento->result_id->num_rows){
	    	foreach ($asiento->result_array() as $av) {
					$lote = new Atom($av['lote_id']);
					if($lote->name != 'EMPTY'){
						$elm = new Element(0,'CONTRATO',$av['lote_id']);
		        // echo "<br/> fecha".$av['fecha']." lote:". $lote->name . " lote id:" . $av['lote_id'] ." elm id:". $elm->id ." monto:". $av['monto']. " nro compr:".$av['nro_comprobante'];
		        // *** OBTENGO LAS IMPUTACIONES DE LA MISMA FECHA
		        $imputaciones = $this->Mdb->db->query("SELECT nro_comprobante,concepto,monto,intereses_monto, saldo FROM `comprobantes` WHERE elements_id = {$elm->id} AND DATE_FORMAT(fecha,'%d/%m/%Y') LIKE '{$av['fecha']}' AND tipo_comprobante LIKE 'RECIBO' AND op_caja_nro < 0 ");
		        $monto_imputado = 0;
		        $concepto = "<div class='row'>";
		        $concepto_exl = '';
		        $saldo = 0;
		        if($imputaciones->result_id->num_rows){
		          foreach ($imputaciones->result_array() as $imp) {
		            $monto_imputado += (intval($imp['monto'])+intval($imp['intereses_monto']));
		            $concepto .= $imp['concepto']."</div><div class='row'>";
		            $concepto_exl .= $imp['concepto']." |'>";
		            $saldo  = $imp['saldo'];
		          }
		        }
						$rl[] = [
							'LOTE'=>$lote->name,
							'FECHA'=>$av['fecha'],
							'MONTO PAGADO'=>intval($av['monto']),
							'MONTO IMPUTADO'=>$monto_imputado,
							'CONCEPTO'=>$concepto,
							'SALDO'=>$saldo
						];
						$rexl[] = [
							'LOTE'=>$lote->name,
							'FECHA'=>$av['fecha'],
							'MONTO PAGADO'=>intval($av['monto']),
							'MONTO IMPUTADO'=>$monto_imputado,
							'CONCEPTO'=>$concepto_exl,
							'SALDO'=>$saldo
						];
	      	}
				}
			}
	    if(count($rl) > 0){
		    // $file_name = Excel_features::create_file($rexl,'ingresos_por_lote');
		    // $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
		    // $print = $this->cmn_functs->get_accion_icon('print','print_repo','ingresos_por_lote',-1);
		    $response = [
		    	'method'=>'repo_ingresos_por_lote',
		    	'action'=>'response',
		    	'data'=>$rl,
		    	// 'download'=>$dnld,
		    	// 'print'=>$print,
		    	'tit'=>'Reporte de ingresos por lote'
		    ];
		    $this->cmn_functs->resp('front_call',$response);
	    }else{
	    	$response =[
	    		'tit'=>'Reporte de ingresos por lote ',
	    		'msg'=>'El rango de fechas seleccionadas no es valido',
	    		'type'=>'warning',
	    		'container'=>'modal',
	    		'win_close_method' => 'back'
	    	];
		    $this->cmn_functs->resp('myAlert',$response);
	    }

	}


	function file_download(){
		$p = $this->input->get('id');
		$data = file_get_contents(base_url('uploads/'.$p));
   	 	force_download($p,$data);
	}

	function ctas_pagas_xmes_xcli(){
		$p = $this->input->post('data');
		$indt = $p['fec_desde']; //'10/02/2019';
		$xdt = $p['fec_hasta'];//date('d/m/Y');
		// $p contiene cada mes en formato '%/03/2019'
 		$p = $this->get_period($indt,$xdt);
 		if($p == 'fecha no valida'){
			$res =[
	          'tit'=>'Reporte cuotas pagas por mes',
	          'msg'=>'El rango de fechas seleccionadas no es valido',
	          'type'=>'warning',
	          'container'=>'modal',
	          'win_close_method' => 'back'
	        ];
        	$this->cmn_functs->resp('myAlert',$res);
		}else{
			$months = [];
			for ($i = 0; $i < count($p); $i++) {
				$elm_activos = $this->get_elms_activos($p[$i]);
				$q = "SELECT
						ev.elements_id as ctr_id,
						a.name as lote,
						p.value as monto,
						dp.value as fecha,
						COUNT(ev.elements_id) as ctas_pagadas,
						SUM(p.value)as total
						FROM `events`ev
						JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
						JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
						JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'
						join elements_pcles epp on epp.elements_id = ev.elements_id AND epp.label = 'prod_id'
						join atoms a on a.id = epp.value
						WHERE dp.value  like '%{$p[$i]}' GROUP BY ev.elements_id WITH ROLLUP ";
				$m = $this->app_model->get_arr($q);
				$t = count($m);
				$la = count($elm_activos);
				$months[] = [
					'tit_month'=>$this->get_month_year($p[$i]),
					'ttl_activos'=>$la,
					'ttl_cl_con_pagos'=>$t,
					'percent_cl_con_pagos'=>number_format($t/$la*100,2).' %',
					'ttl_ctas_pagadas'=>end($m)['ctas_pagadas'],
					'ctas_pagas_x_lt_activo'=>number_format(intval(end($m)['ctas_pagadas'])/$la,2).' %',
					'ctas_pagas_x_lt_pago'=>number_format(intval(end($m)['ctas_pagadas'])/$t,2).' %',
					'ttl_pagos'=>end($m)['total'],
					'data'=>$m

				];
			}
			$res = [
					'method'=>'ctas_pagas_xmes_xcli',
					'action'=>'response',
					'months'=>$months
				];
			$this->cmn_functs->resp('front_call',$res);
		}
	}

	//***** SUB FUNCTS DE CUTAS PAGAS POR MES
	// DEVUELVE EL TOTAL DE CLIENTES ACTIVOS EN EL MES INDICADO EN $dt
	function get_elms_activos($dt){
		$i = DateTime::createFromFormat('d/m/Y', str_replace('%', '01', $dt));
		$d = $i->format('Y').'-'.$i->format('m').'-%';
		return  $this->app_model->get_arr("SELECT ev.elements_id FROM events ev
			LEFT OUTER JOIN elements_pcles ep ON ep.elements_id = ev.elements_id and ep.label = 'prod_id'
			LEFT OUTER JOIN atoms a on a.id = ep.value
			WHERE ev.date LIKE '{$d}' AND a.name != '' AND a.name NOT LIKE 'R_%'
			GROUP BY ev.elements_id
			ORDER BY ev.ord_num ASC");
	}

	function get_month_year($dt){
		$meses = ['null','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Sept.','Oct.','Nov.','Dic.'];
		$i = DateTime::createFromFormat('d/m/Y', str_replace('%', '01', $dt));
		return $meses[intval($i->format('m'))].' '.$i->format('Y');
	}

	function get_period($indt,$xdt){
		$i = DateTime::createFromFormat('d/m/Y', $indt);
		$x = DateTime::createFromFormat('d/m/Y', $xdt);
		$p = $this->validate_prd(date_diff($i,$x));
		$r = [];
		if(!$p['res']){
			return  $p['error'];
		}
		else{
			$r[] = '%'.$x->format('/m/Y');
			for ($i=0; $i < $p['months']; $i++) {
				// var_dump($x->modify('-1 month'));
				$t = $x->modify('-1 month');
				$r[] = '%'.$t->format('/m/Y');
			}
			return array_reverse($r);
		}
	}


	function validate_prd($dt){
		if($dt->invert > 0){return ['res'=>false,'error'=>'fecha no valida'];}
		if($dt->y > 1){return ['res'=>false,'error'=>'fecha no valida'];}
		if($dt->days > 300){return ['res'=>false,'error'=>'fecha no valida'];}
		if($dt->days < 1){return ['res'=>false,'error'=>'fecha no valida'];}
		return ['res'=>true,'months'=>$dt->m];
	}

	//*****  END CUOTAS PAGAS POR MES




	// NO LO ESTOY USANDO
	function repo_g120(){
		$lid =$this->app_model->get_arr("SELECT id FROM `atoms` where atom_types_id = 2 and name like 'G%'");
		// $t = count($lid);
		// $i = 0;

		// $en_contrato = 0;
		// $en_ant = 0;
		// $en_120 = 0;
		$r=[];
		foreach ($lid as $l) {
			// $i ++;
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				// $en_contrato ++;
				$plan = $e->get_plan();
				if(strpos($plan, '120')> -1){
					// $en_120++;
					// var_dump($e->get_owner_name());
					// var_dump($e->get_pcle('cli_id')->value);
					//  numero del lote, nombre , telefono, ctas en mora, ctas ftrm
					$cli_atom = new Atom($e->get_pcle('cli_id')->value);
					$cli = $cli_atom->get_pcle('nombre')->value.' '.$cli_atom->get_pcle('apellido')->value;
					$tel = $cli_atom->get_pcle('telefono')->value.'/'.$cli_atom->get_pcle('celular')->value;
					$ft = count($e->get_events(4,'p_ftrm')['events']);
					$mr = count($e->get_events(4,'a_pagar')['events']);
					$mrt = $e->get_events(4,'a_pagar')['total'];
					$nv = $e->get_first_future_event('a_pagar');
					if(!empty($nv)){
						$monto = $nv['pcles']['monto']->value;
						$pcn = str_replace('Cuota', '', $nv['pcles']['nro_cta']->value);
						$pfv = $nv['pcles']['fec_vto']->value;
					}else{
						$monto = 0;
						$pcn = 0;
						$pfv = 0;
					}
					$r[] = [
						'Lote '=> $e->get_owner_name(),
						'Cliente'=>$cli,
						'Telefonos'=>$tel,
						'Prox. Cta.'=>$pcn,
						'Prox. Vto.'=>$pfv,
						'Monto'=>$monto,
						'Ctas. Fuera Term'=>$ft,
						'Ctas. Mora'=>$mr,
						'Mora $'=>intval($mrt)
					];
				}
			}
		}
		$res = [
			'method'=>'repo_g120',
			'action'=>'response',
			'title'=>'Reporte Garin 120',
			'data'=>$r
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}

	function lotes_con_posesion(){
		$lid =$this->cmn_functs->get_lotes_activos();
		$r=[];
		foreach ($lid as $l) {
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				$monto = 0;
				$pcn = 0;
				$pfv = 0;
				$nv = $e->get_first_future_event('a_pagar');
				if(!empty($nv)){
					$monto = $nv['pcles']['monto_cta']->value;
					$pcn = str_replace('Cuota', '', $nv['pcles']['nro_cta']->value);
					$pfv = $nv['pcles']['fecha_vto']->value;
				}
				$plan = $e->get_plan();
				$cp = $e->get_ctas_pagas();
				if(count($cp['events']) > 36 ){
					//****  numero del lote, nombre , telefono, ctas en mora, ctas ftrm
					$lote_num = $e->get_owner_name();
					$barrio = '';
					$pr_id = $e->get_pcle('prod_id');
					if(!empty($pr_id)){
						$l = new Atom($pr_id->value);
						$barrio = $l->get_pcle('emprendimiento')->value;
					}
					$cli_atom = new Atom($e->get_pcle('cli_id')->value);
					$cli = $cli_atom->get_pcle('nombre')->value.' '.$cli_atom->get_pcle('apellido')->value;
					$r[] = [
						'Barrio'=>$barrio,
						'Codigo Lote '=> $lote_num,
						// 'Financiación'=>$plan,
						'Cliente'=>$cli,
						'Nro. Cta. Actual'=>$pcn,
						'Vencimiento '=>$pfv,
						'Monto $'=>$monto
					];
				}
			}
		}
		$print = $this->cmn_functs->get_accion_icon('print','print_repo','lotes_con_posesion',-1);
		$res = [
			'method'=>'lotes_con_posesion',
			'action'=>'response',
			'tit'=>'Reporte Lotes con Posesion',
			'data'=>$r,
			'print'=>$print
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}

			//**************************************************
			//*** 07/01/2020
			//*** listado de lotes sin posesion
			//*** o en ciclo 1
			//*************************************************

function lotes_en_ciclo1(){
		$lid =$this->cmn_functs->get_lotes_activos();
		$r=[];
		foreach ($lid as $l) {
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				$monto = 0;
				$pcn = 0;
				$pfv = 0;
				$nv = $e->get_first_future_event('a_pagar');
				if(!empty($nv)){
					$monto = $nv['pcles']['monto_cta']->value;
					$pcn = str_replace('Cuota', '', $nv['pcles']['nro_cta']->value);
					$pfv = $nv['pcles']['fecha_vto']->value;
				}
				$plan = $e->get_plan();
				$cp = $e->get_ctas_pagas();
				if(!empty($nv) && $e->get_pcle('current_ciclo')->value == '1' && $e->get_pcle('cant_ctas_ciclo_2')->value > 0 ){
					//****  numero del lote, nombre , telefono, ctas en mora, ctas ftrm
					$lote_num = $e->get_owner_name();
					$barrio = '';
					$pr_id = $e->get_pcle('prod_id');
					if(!empty($pr_id)){
						$l = new Atom($pr_id->value);
						$barrio = $l->get_pcle('emprendimiento')->value;
					}
					$cli_atom = new Atom($e->get_pcle('cli_id')->value);
					$cli = $cli_atom->get_pcle('nombre')->value.' '.$cli_atom->get_pcle('apellido')->value;
					$r[] = [
						'Barrio'=>$barrio,
						'Codigo Lote '=> $lote_num,
						// 'Financiación'=>$plan,
						'Cliente'=>$cli,
						'Nro. Cta. Actual'=>$pcn,
						'Vencimiento '=>$pfv,
						'Monto $'=>$monto
					];
				}
			}
		}
		$print = $this->cmn_functs->get_accion_icon('print','print_repo','lotes_con_posesion',-1);
		$res = [
			'method'=>'lotes_en_ciclo1',
			'action'=>'response',
			'tit'=>'Reporte Lotes en Primer Ciclo',
			'data'=>$r,
			'print'=>$print
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}


	function lotes_disponibles(){
		$lid = $this->app_model->get_arr("SELECT atom_id FROM atoms_pcles p WHERE p.value = 'DISPONIBLE' or p.value = 'NO A LA VENTA'");

		$r=[];
		foreach ($lid as $l) {
			$a = new atom($l['atom_id']);
			if(!empty($a)){
				if($a->name && $a->get_pcle('emprendimiento')->value && $a->get_pcle('propietario')->value){
					$r[] = [
						'Codigo Lote'=> $a->name,
						'Barrio'=>$a->get_pcle('emprendimiento')->value,
						'Estado'=>$a->get_pcle('estado')->value,
						'Propietario'=>$a->get_pcle('propietario')->value,
					];
				}
			}
		}
		// $file_name = Excel_features::create_file($r,'lotes_disponibles');
		// $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
		// $print = $this->cmn_functs->get_accion_icon('print','print_repo','lotes_disponibles',-1);

		$res = [
			'method'=>'lotes_disponibles',
			'action'=>'response',
			'tit'=>'Reporte Lotes Disponibles',
			'data'=>$r,
			// 'download'=>$dnld,
			// 'print'=>$print
		];
		$this->cmn_functs->resp('front_call',$res);
	}

	// function download(){
 //      $p = $this->input->post('data');
 //      $this->cmn_functs->resp('check',$p);
 //      // $data = file_get_contents(base_url('upload/inscriptos_en_talleres.xlsx'));

 //      // HELPER PARA PONER ENPANTALLA EN DIALOGO DE DESCARGA
 //      // force_download('inscriptos_en_talleres.xlsx',$data);

 //    }


	function revision_plan(){
		$x = 0;
		$e = $this->Mdb->db->query("SELECT id FROM elements WHERE elements_types_id = 1 ");
		if($e->result_id->num_rows){
	      // array_map(function($i){$this->revisar_plan($i['id']);}, $e->result_array());
			$r = [];
			foreach ($e->result_array() as  $elm) {
				$x = $this->revisar_plan($elm['id']);
				if($x['Monto'] > 0){
					$r[] = $x;
				}
			}
			// $file_name = Excel_features::create_file($r,'revision_plan');
			// $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
			// $print = $this->cmn_functs->get_accion_icon('print','print_repo','revision_plan',-1);
			$response = [
				'method'=>'revision_plan',
				'action'=>'response',
				'data'=>$r,
				// 'download'=>$dnld,
				// 'print'=>$print,
				'tit'=>'Revision de Plan'
			];
			$this->cmn_functs->resp('front_call',$response);
		}else{
			$response =[
				'tit'=>'Revision de Plan ',
				'msg'=>'no hay registros',
				'type'=>'warning',
				'container'=>'modal',
				'win_close_method' => 'light_back'
			];
			$this->cmn_functs->resp('myAlert',$response);
		}

	}

	function revisar_plan_old($id){
		$e = new Element($id);
		$indac = $e->get_pcle('indac')->value;
		$a = new Atom($e->get_pcle('prod_id')->value);


		// $last_pay_ev = $e->get_last_payment();

		$r = [
			// 'Id Contrato'=>$id,
			'Lote'=> $a->name,
			'Barrio'=> $a->get_pcle('emprendimiento')->value,
			'Indice Act.'=>0,
			'Nro. Cta.'=>'',
			'Monto'=>0,
			'Vencimiento'=>''
		];
		$ct_r = intval($e->get_pcle('cant_ctas_restantes')->value);
		$ct_t = intval($e->get_pcle('cant_ctas')->value);
		$curr_ciclo = intval($e->get_pcle('current_ciclo')->value);


		if(($ct_t - $ct_r) > 22 && !preg_match('/R_/', $r['Lote']) && $curr_ciclo == 2){
			$r ['Indice Act.'] = $indac;
			$ev_id = intval($e->get_first_event_id(8,'a_pagar'));
			if($ev_id > 0){
				// $n = intval(substr($ev->get_pcle('nro_cta')->value, strpos($ev->get_pcle('nro_cta')->value , ' ')+1,strrpos($ev->get_pcle('nro_cta')->value , ' ')));
				$ev = new Event($ev_id);
				$nc = intval($ev->get('ord_num'));
				$fv = $ev->get_pcle('fecha_vto')->value;
				$nc_txt = $ev->get_pcle('nro_cta')->value;
				$monto = $ev->get_pcle('monto_cta')->value;

				$fr = intval($e->get_pcle('frecuencia_revision')->value);

				if($nc < intval($e->get_pcle('cant_ctas')->value)){
					//  ESTA EN NUMERO DE REVISION
					// for ($i=$nc - 3; $i < $nc + 3 ; $i++) {
						// if($i > 0 && $i % $fr == 0){
							// echo 'evaluating '.$i;
							if($nc >= 23 ){
								$r['Vencimiento'] = $fv;
								$r['Nro. Cta.'] = $nc_txt;
								$r['Monto'] = $monto;
								$e->pcle_updv($e->get_pcle('plan_update_pending')->id,'true');
							}
						// }
					// }
				}



			}
		}
		return $r;
	}



	function revisar_plan($id){
		$e = new Element($id);
		$indac = $e->get_pcle('indac')->value;
		$a = new Atom($e->get_pcle('prod_id')->value);


		// $last_pay_ev = $e->get_last_payment();

		$r = [
			// 'Id Contrato'=>$id,
			'Lote'=> $a->name,
			'Barrio'=> $a->get_pcle('emprendimiento')->value,
			'Indice Act.'=>0,
			'Nro. Cta.'=>'',
			'Monto'=>0,
			'Vencimiento'=>'',
			'verif_indac'=>''
		];
		$ct_r = intval($e->get_pcle('cant_ctas_restantes')->value);
		$ct_t = intval($e->get_pcle('cant_ctas')->value);
		$curr_ciclo = intval($e->get_pcle('current_ciclo')->value);
		$fr = intval($e->get_pcle('frecuencia_revision')->value);


		if(($ct_t - $ct_r) > 22 && !preg_match('/R_/', $r['Lote']) && $fr > 0 && $curr_ciclo == 2){
			$r ['Indice Act.'] = $indac;
			$ev_id = intval($e->get_first_event_id(8,'a_pagar'));
			if($ev_id > 0){
				// $n = intval(substr($ev->get_pcle('nro_cta')->value, strpos($ev->get_pcle('nro_cta')->value , ' ')+1,strrpos($ev->get_pcle('nro_cta')->value , ' ')));
				$ev = new Event($ev_id);
				$nc = intval($ev->get('ord_num'));
				$fv = $ev->get_pcle('fecha_vto')->value;
				$nc_txt = $ev->get_pcle('nro_cta')->value;
				$monto = $ev->get_pcle('monto_cta')->value;

				// $fr = intval($e->get_pcle('frecuencia_revision')->value);

				if($nc < intval($e->get_pcle('cant_ctas')->value)){
					//  ESTA EN NUMERO DE REVISION
					// for ($i=$nc - 3; $i < $nc + 3 ; $i++) {
						// if($i > 0 && $i % $fr == 0){
							// echo 'evaluating '.$i;
							if($nc >= 23 ){
								$r['verif_indac'] = $this->verif_indac($e, $nc);
								$r['Vencimiento'] = $fv;
								$r['Nro. Cta.'] = $nc_txt;
								$r['Monto'] = $monto;
								$e->pcle_updv($e->get_pcle('plan_update_pending')->id,'true');
							}
							// echo '<br/>indac:'.$r['verif_indac'];
						// }
					// }
				}



			}
		}
		return $r;
	}

	function verif_indac($elm,$nro_cta){
		$fr = intval($elm->get_pcle('frecuencia_revision')->value);
		$prev_ev = $elm->get_event_by_ord_num(intval($nro_cta));
		$prev_cta = intval($prev_ev->get_pcle('monto_cta')->value);
		$verif=0;

		// echo '<br>verifying elm id ->'. (new Atom($elm->get_pcle('prod_id')->value))->name;
		// echo ' prev_cta:'.$prev_cta;
		for ($i=0; $i <= $fr; $i++) {
			// echo '<br/>nro i'.$i;
			$ev = $elm->get_event_by_ord_num(intval($nro_cta + $i));
			$last_cta = intval($ev->get_pcle('monto_cta')->value);
			if($last_cta > $prev_cta){

				// echo ' last_cta:'.$last_cta;
				// echo ' prev_cta:'.$prev_cta;
				$verif = number_format(floatval((($last_cta-$prev_cta)/$prev_cta)*100),2);
				return $verif;
			}
		}

	}



}
