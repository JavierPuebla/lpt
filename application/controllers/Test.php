<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
    	$this->Mdb->load->database();
    	$this ->load->model('user');
		$this ->load->model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->library('cmn_functs');
		$this->load->library('unit_test');

		date_default_timezone_set('America/Argentina/Buenos_Aires');

		// include (APPPATH . 'JP_classes/Atom.php');
		// include (APPPATH . 'JP_classes/Element.php');
		include (APPPATH . 'JP_classes/Event.php');
  	}

	public function index() {
		// $t = $this->test_api_rodrigo1();
		// $this->create_servicio_corte_cesped($elm_id);
		// $t =  $this->get_comprobantes();

		// $t = $this->fix_corte_cesped();
		// $t = $this->t_cta_upc();

		// $t = $this->kill_contrato();
		// var_dump($t);
	}
	// ****** END INDEX  ******



		function fix_rescindidos(){
			// todos los lotes con name R_
			$x = $this->Mdb->db->query("SELECT id,name FROM atoms WHERE atom_types_id = 2 AND name LIKE 'R_%' ORDER BY id DESC")->result_array();
			foreach($x as $x1){
				// busco los de atp 15
				$t = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = 15 AND name = '{$x1['name']}' ");
				//  si existe con atp 15 borro el atom y pcles con atp 2
				if($t->result_id->num_rows > 0){
					echo '<br/>killing ..'.$x1['name'];
					$this->Mdb->db->query("DELETE FROM atoms_pcles where atom_id = {$x1['id']}");
		      $this->Mdb->db->query("DELETE FROM atoms where id = {$x1['id']}");
				}else{
					// cambio el attp de 2 a 15
					echo '<br/>updating ..'.$x1['name'];
					$this->Mdb->db->query("UPDATE atoms_pcles SET atom_types_id = 15 WHERE atom_id = {$x1['id']}");
					$this->Mdb->db->query("UPDATE atoms SET atom_types_id = 15 WHERE id = {$x1['id']}");
				}
			}


		}

		function fix_rescindidos_attp3(){
			// todos los lotes con name R_
			$x = $this->Mdb->db->query("SELECT id,name FROM atoms WHERE atom_types_id = 3  ORDER BY id DESC")->result_array();
			foreach($x as $x1){
				// busco los de atp 15
				$t = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = 15 AND name = '{$x1['name']}' ");
				 // si existe con atp 15 borro el atom y pcles con atp 2
				if($t->result_id->num_rows > 0){
					echo '<br/>killing ..'.$x1['id'];
					$this->Mdb->db->query("DELETE FROM atoms_pcles where atom_id = {$x1['id']}");
		      $this->Mdb->db->query("DELETE FROM atoms where id = {$x1['id']}");
				}else{
					// cambio el attp de 3 a 15
					echo '<br/>updating ..'.$x1['name'];
					$this->Mdb->db->query("UPDATE atoms_pcles SET atom_types_id = 15 WHERE atom_id = {$x1['id']}");
					$this->Mdb->db->query("UPDATE atoms SET atom_types_id = 15 WHERE id = {$x1['id']}");
				}
			}


		}



		//****** 03 de agosto 2020
		//**** realiza el reporte de contratos
		//************************************************
		function feed_contratos(){
			set_time_limit(0);
			$res = [];
			// validate fecha de ultima consulta
			// si dif_fecha_consulta_horas (ultima conulta mas tiempo)
			// es mayor a now() retorna el reg en la base
			// sino hace el recorrido y guara la nueva version en repo_1
			// $xq = "SELECT fecha,res FROM repo_1 ORDER BY id DESC LIMIT 1";
			// $xqry = $this->Mdb->db->query($xq);
			// if(!$xqry->result_id->num_rows){echo 'fallo la consulta..'; exit();}
			// $lastcall = new dateTime($xqry->row()->fecha);
			// $dif_fecha_consulta_horas = $lastcall->add(new DateInterval('PT22H1M'));
			// $now = new dateTime();
			// // echo $dif_fecha_consulta_horas->format('Y-m-d H:m:s') ." //// ".$now->format('Y-m-d H:m:s');
			// if($dif_fecha_consulta_horas  > $now){
			// 	// echo '<br> returning from db';
			// 	return $xqry->row()->res;
			// 	// $res = ' not calling report';
			// }else{
				// echo '<pre>';
				// echo '<br> calling new repo & saving';
				// $res = ' CALLING report';
				echo "<br/>querying contratos...";
				$q_contratos_activos = "SELECT id FROM elements WHERE elements_types_id = 1";
				$qry = $this->Mdb->db->query($q_contratos_activos);
				//****** DESACTIVADO
				// $qry = null;
				$cntr=[];
				// HAY CONTRATOS
				if($qry && $qry->result_id->num_rows){
					$cntr=[];
					// RECORRO  LOS CONTRATOS PARA OBTENER DATA
					foreach ($qry->result() as $row){
						$rx=[];
						$c = new Element($row->id);
						$cr = intval($c->get_pcle('cant_ctas_restantes')->value);
						// HAY CUOTAS RESTANTES
						if($cr > 1){
							$owner = new Atom($c->get_pcle('prod_id')->value);
							if($owner->type == "WRONG_TYPE"){
								echo '<br/>wrong type'. $row->id."<br/>";
							}
							$rx['codigo_lote'] = $owner->name;
							$rx['barrio'] = $owner->get_pcle("emprendimiento")->value;
							$rx['propietario'] = $owner->get_pcle("propietario")->value;
							$rx['estado_contrato'] = $c->get_pcle('estado_contrato')->value;
							$rx['cantidad_cuotas_pagadas'] = intval($c->get_ctas_pagas()['ev_count']);
							$rx['saldo_en_cuenta'] = $this->get_saldo_contrato($row->id);

							$apg = $c->get_total_ctas_a_pagar();
							$ahorro = $c->get_ahorro_actual_y_acumulado();
							$serv = $c->get_srv_data();

							//*** Cleaning $c;
							$c = null;
							unset($c);
							// ****
							if(intval($apg['monto_1_pago']) > 0){
								$rx['cantidad_cuotas_a_pagar'] = $apg['cant_ctas'];
								$rx['monto_1_pago']= $apg['monto_1_pago'];
								$rx['monto_a_pagar_financ']= $apg['monto_financ'];
								$rx['monto_cuota_actual'] = $apg['cta_upc'];
								$rx['ahorro_actual']= $ahorro['actual'];
								$rx['ahorro_acumulado']= $ahorro['acumulado'];
								$rx['servcios_cantidad_cuotas_pagadas'] = $serv['cant_ctas_pagadas'];
								$rx['servcios_cantidad_cuotas_a_pagar'] = $serv['cant_ctas_a_pagar'];
								$rx['servcios_total_1_pago'] = $serv['monto_1_pago'];
								$rx['servcios_total_financ'] = $serv['monto_financ'];
								// CNTR CONTIENE LOS DATOS DEL CONTRATO ACTUAL
								$cntr[]=$rx;
							}
						}
						$cr = null;
					}
				}

				//*** SAVE DATA DE TODOS LOS CONTRATOS
				$p = $this->Mdb->db->insert('repo_contratos',[
						'res'=>json_encode($cntr)
					]);
			echo "<br/> Done...";
		}



	
	function feed_ingresos_caja(){
		echo "<br/> Querying ingresos de caja...";
		$dt_in = date('Y')."-01-01";
		$dt_out = date('Y-m-d');
		$qi = "SELECT
		DATE_FORMAT(ca.fecha, '%d/%m/%Y') as fecha,
		FLOOR(ca.monto) as importe,
		cc.nombre as caja,
		(CASE WHEN p.name != 'null' THEN p.name
			-- WHEN p2.name != 'null' THEN CONCAT(atlt.name, ' ' ,p2.name)
			WHEN p2.name != 'null' THEN CONCAT(atlt.name, ' ' ,p2.name)
			WHEN cctr.nombre != 'null' THEN  cctr.nombre
			END ) as contraparte,
			ci.nombre as concepto,
			pdni.value as dni,
			ca.observaciones as detalle,
			GROUP_CONCAT(atbr.name SEPARATOR ',') as 'cdc_name',
			GROUP_CONCAT(ccd.percent SEPARATOR ',') as 'cdc_percent'
			FROM contab_asientos ca
			LEFT OUTER JOIN contab_cuentas cc on cc.id = ca.cuentas_id
			LEFT OUTER JOIN contab_cuenta_de_imputacion ci on ci.id = ca.cuenta_imputacion_id
			LEFT OUTER JOIN contab_cuentas cctr on cctr.id = ca.cta_contraparte_id
			LEFT OUTER JOIN atoms p on p.id = ca.proveedor_id
			LEFT OUTER JOIN atoms p2 on p2.id = ca.cliente_id
			LEFT OUTER JOIN atoms_pcles pdni on pdni.atom_id = ca.cliente_id AND pdni.label = 'dni'
			LEFT OUTER JOIN contab_cc_distrib ccd on ca.id = ccd.asiento_id
			LEFT OUTER JOIN atoms atbr on atbr.id = ccd.barrio_id
			LEFT OUTER JOIN atoms atlt on atlt.id = ca.lote_id
			WHERE estado = 1 AND
			tipo_asiento = 'INGRESOS' AND
			ca.fecha >= STR_TO_DATE('{$dt_in}','%Y-%m-%d') AND
			ca.fecha <= STR_TO_DATE('{$dt_out}','%Y-%m-%d')
			GROUP BY ca.operacion_nro";
		$qryi = $this->Mdb->db->query($qi);
		if($qryi && $qryi->result_id->num_rows){
			$recs = [];
			setlocale(LC_MONETARY, 'en_US');
			foreach ($qryi->result_array() as $row) {
				$tr = [
					'Fecha'=> $row['fecha'],
					'Importe'=>$row['importe'],
					'Caja'=>$row['caja'],
					'Contraparte'=>$row['contraparte'],
					'DNI'=>$row['dni'],
					'Concepto'=>$row['concepto'],
					'Detalle'=>$row['detalle']
				];
				$cdc_names = explode(',', $row['cdc_name']);
				$cdc_percents = explode(',', $row['cdc_percent']);
				$i = 0;
				foreach ($cdc_names as $key => $c) {
					$i ++;
					// SUMA LOS PORCENTAJES EN LAS COLUMNAS DE CENTRO DE COSTOS
					$tr[$c]=intval(intval($row['importe']) * intval($cdc_percents[$key])/100);
					// MUESTRA PORCENTAJES
					// $tr['Ctro. Ctos. '.$i]=$c.': '. money_format('%(#10n', intval(intval($row['importe']) * intval($cdc_percents[$key])/100));
				}
				$recs[] = $tr;
			}
			//*** SAVE DATA
			$p = $this->Mdb->db->insert('repo_ingresos_caja',[
				'res'=>json_encode($recs)
			]);
			echo 'saving..';
		}
		echo "done...";
	}
                
	function feed_cuotas_pagadas(){
			$dt_in = date('Y')."-01-01";
			$dt_out = date('Y-m-d');
			// CUOTA PAGADAS
			echo "<br/> Querying cuotas pagadas...".$dt_out;
			$qp = "SELECT
			(CASE WHEN a.name != '' THEN 'CUOTA LOTE '  WHEN  asrv.name != '' THEN serv.name END) as 'Detalle',
			(CASE WHEN a.name != '' THEN a.name WHEN asrv.name != '' THEN asrv.name END) as 'Codigo Lote',
			nc.value as 'Nro. Cuota',
			dp.value as 'Fecha de Pago',
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
			WHERE STR_TO_DATE(dp.value,'%d/%m/%Y') >= STR_TO_DATE('2020-01-01','%Y-%m-%d') AND STR_TO_DATE(dp.value,'%d/%m/%Y') <= STR_TO_DATE('{$dt_out}','%Y-%m-%d')ORDER BY STR_TO_DATE(dp.value,'%d/%m/%Y') ASC";

			$qcp = $this->Mdb->db->query($qp);
				echo 'num rows'. $qcp->result_id->num_rows;	
			if($qcp->result_id->num_rows){
			
				//*** SAVE DATA
				$p = $this->Mdb->db->insert('repo_cuotas_pagadas',[
					'res'=>json_encode($qcp->result_array())
				]);
				echo 'saving..';
			}
			echo "done...";
		}


	function get_saldo_contrato($e_id){
		$q_saldo = "SELECT saldo from comprobantes WHERE elements_id = {$e_id} AND estado > 0  ORDER BY id DESC LIMIT 1 ";
		$cs = $this->Mdb->db->query($q_saldo);
		if($cs->result_id->num_rows){
			return intval($cs->row()->saldo);
		}else{
			return 0;
		}
	}
	
	
	function r1(){
			$token = $this->input->get('token');
			$repos = $this->input->get('repo');
			if($token == 'AG0923431BGJ2343J3'){
				$res=[];
				// $repos = ['contratos','ingresos_caja','cuotas_pagadas'];
				// foreach($repos as $key => $r){
					$dbr = 'repo_'.$repos;
					// $dbr = 'repo_'.$repos[0];
					$xq = "SELECT fecha,res FROM {$dbr} ORDER BY id DESC LIMIT 1";
					$xqry = $this->Mdb->db->query($xq);
					if($xqry->result_id->num_rows > 0){
						$res[] = $xqry->row()->res;
					}
				// }

				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($res));
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




	function t_cta_upc(){
		$e = new Element(9999);
		var_dump(empty($e->id));
		exit();
		// return $e->get_cta_upc2();
	}


	function fix_corte_cesped(){
		$q = "SELECT id FROM elements WHERE elements_types_id = 1 ";
		$c = $this->Mdb->db->query($q);
		if($c->result_id->num_rows){

			foreach($c->result_array() as $elm){
				$e = new Element($elm['id']);
				$cc = $e->get_pcle('corte_cesped')->value;

				if(empty($cc)){
					$e->set_pcle(0,'corte_cesped','NO');

				}

				// $srv = $e->get_servicios();
				// if(!empty($srv)){
				// 	foreach($srv as $srvi){
				// 		$xs = new Element($srvi['id']);
				// 		$atom_id = $xs->get_pcle('atom_id')->value;
				// 		$xsrv_name = (new Atom($atom_id))->name;
				// 		if($xsrv_name === 'Corte Cesped'){
				// 			$e->set_pcle(0,'corte_cesped','SI');
				// 		}else{
				// 			$e->set_pcle(0,'corte_cesped','NO');
				// 		}
				// 		$t = $e->get_pcle('corte_cesped')->value;
				//
				// 	}
				// }
			}
		}
		echo 'done...';
	}


	function set_corte_cesped(){
		$q = "SELECT id FROM elements WHERE elements_types_id = 1 ";
		$c = $this->Mdb->db->query($q);
		if($c->result_id->num_rows){
			foreach($c->result_array() as $elm){
				$e = new Element($elm['id']);
				$srv = $e->get_servicios();
				if(!empty($srv)){
					foreach($srv as $srvi){
						$xs = new Element($srvi['id']);
						$atom_id = $xs->get_pcle('atom_id')->value;
						$xsrv_name = (new Atom($atom_id))->name;
						if($xsrv_name === 'Corte Cesped'){
							$e->set_pcle(0,'corte_cesped','SI');
						}else{
							$e->set_pcle(0,'corte_cesped','NO');
						}
						$t = $e->get_pcle('corte_cesped')->value;
						highlight_string("<?php serv = " . var_export($e->id, true) . ";\n?>");
					}
				}
			}
		}

	}


	function get_comprobantes($elm_id){
			$q = "SELECT
	                DATE_FORMAT(fecha,'%d/%m/%Y') as 'Fecha',
	                nro_comprobante as 'Recibo Nro.',
	                concepto as 'Descripcion',
	                monto as 'Total',
	                saldo as 'Saldo de Cuenta'
	                FROM `comprobantes`
	                WHERE elements_id = {$elm_id} AND estado > 0
	                ORDER BY id DESC";
	        $c = $this->Mdb->db->query($q);
			if($c->result_id->num_rows){
	            return $c->result_array();
	        }else{
	            return '';
	        }
	    }



	// *************************************************************************
    // ******* 14 julio 2020
    // ******* retorna las operaciones de pago con tarjeta
    // *************************************************************************
	function get_pagos_online(){
		$res = [];
		$dt = $this->Mdb->db->query("SELECT
			ci.id,
			ci.elem_id,
			DATE_FORMAT(ci.date,'%d/%m/%Y') as fecha,
			al.name as lote,
			ci.cargos,
			ci.effective_amount,
			ci.auth_number,
			ci.card_brand,
			ci.card_number
			FROM `contab_cobro_inmediato` ci
			JOIN elements_pcles ep on ep.elements_id = ci.elem_id AND ep.label = 'prod_id'
			JOIN atoms al on  al.id = ep.value
			ORDER BY ci.id DESC "
		);
		if($dt->result_id->num_rows){
			foreach ($dt->result_array() as $row) {
				// $r = str_replace('[','',$row['cargos']);
				// $r = str_replace(']','',$r);
				$r = json_decode($row['cargos'],TRUE);
				$cta_nro ="";
				$fec_ven ="";
				$cuota="";
				$interes="";
				if(is_array($r)){
					foreach($r as $mr){
						$cta_nro .= (!empty($mr['nro_cta']))?$mr['nro_cta'].', ':'';
						$fec_ven .= (!empty($mr['fec_vto']))?date('d/m/Y', strtotime($mr['fec_vto'])):'';
						$cuota .= (!empty($mr['tot_cta']))?$mr['tot_cta'].', ':'';
						$interes .= (!empty($mr['interes_mora']))?$mr['interes_mora'].', ':'';
						// $tot_intereses += (!empty($mr['interes_mora']))?intval($mr['interes_mora']):0;
						// $tot_pago += (!empty($mr['tot_cta']))?intval($mr['tot_cta']):0;
					}
				}

				$res[] = [
					'Fecha Pago'=> $row['fecha'],
					'ID Transaccion' => $row['id'],
					'Lote' => $row['lote'],
					'Cuota Nro.'=>$cta_nro,
					'Fecha Vto.'=>$fec_ven,
					'Monto Cuota'=>$cuota,
					"Intereses"=>$interes,
					'Total Pagado' =>$row['effective_amount'],
					'Autorización Nro.' =>$row['auth_number'],
					'Tarjeta' =>$row['card_brand'],
					'Tarjeta Nro.' =>$row['card_number'],
				];
			}
		}
		return $res;
	}




/*

	[{	"selected":"true",
		"events_id":"426754",
		"nro_cta":"Cuota 1 de 150",
		"fec_vto":"2020-07-10","tipo":"cta_lote","lote_name":"ES-207","termino":"Normal","tot_cta":"7860","dias_mora":"0","interes_mora":"0"}]



	[{	"selected":"true",
		"events_id":"386715",
		"tipo":"cta_lote",
		"lote_name":"ES-182",
		"nro_cta":"Cuota 22 de 36",
		"fec_vto":"2020-06-10","termino":"EN_MORA",
		"tot_cta":"9314",
		"dias_mora":"27",
		"interes_mora":"503"
	},
	{
		"selected":"true",
		"events_id":"419074",
		"nro_cta":"Cuota 6 de 12",
		"fec_vto":"2020-06-10","tipo":"cta_srvc",
		"termino":"EN_MORA","tot_cta":"900",
		"dias_mora":"27",
		"interes_mora":"49",
		"srv_name":"Corte Cesped",
		"srv_cta_imputacion_id":""
	}]


	[{
		"selected":"true",
		"events_id":"401354",
		"nro_cta":"Cuota 14 de 120",
		"fec_vto":"2020-07-10",
		"tipo":"cta_lote",
		"lote_name":"ES-252",
		"termino":"Normal",
		"tot_cta":"7520",
		"dias_mora":"0",
		"interes_mora":"0"
	},
	{
		"selected":"true",
		"events_id":"401410",
		"fec_vto":"2025-03-10",
		"nro_cta":"Cuota 70 de 120",
		"tipo":"cta_lote",
		"termino":"ADL",
		"dias_mora":"0",
		"interes_mora":"0",
		"tot_cta":"7520"
	}]

	//****** 11 julio 2020;
	//**** crea el servicio corte de cesped
	//************************************************
	function create_corte_cesped_clico1($elm_id){
		$e = new Element($elm_id);
		$cant_ctas = intval(getPcle('cant_ctas')->value) - intval(getPcle('cant_ctas_ciclo_2')->value);

	}

*/
	function test_api_rodrigo1(){

	}
// query rodrigo
// 	{
// 		codigo_lote:string,
// 		barrio:string,
// 		propietario:string,
// 		estadode_contrato:string,
// 	 	cantidad_cuotas_pagadas:int,
// 		cantidad_cuotas_a_pagar:int,
// 		monto_cuota_actual:int,
// 		ahorro_actual:int,
// 		ahorro_acumulado:int,
// 		monto_1_pago:int,
// 		monto_a_pagar_financ:int
// 		servcios_cantidad_cuotas_pagadas:int,
// 		servcios_cantidad_cuotas_a_pagar:int,
// 		servcios_monto_cuota_actual:int,
// 		servcios_ahorro_actual:int,
// 		servcios_ahorro_acumulado:int,
//
// 	}





	function update_fec_vto(){
		// $el = new Element(8932);
		if(!empty($el->id)){
			// TODOS LOS EVENTOS CUOTA
			$ctas = $el->get_events_all();
			$nd = new dateTime('2019-10-10');

			foreach($ctas as $cta){
				$ev = new Event($cta['event']['id']);
				$evp = $ev->get_pcle('fecha_vto')->value;

				$dx2 = $nd->format('Y-m-d');
				$dx3 = $nd->format('d/m/Y');

				$nd->modify('next month');
				$ev->set('date',$dx2);
				$ev->pcle_updv($ev->get_pcle('fecha_vto')->id,$dx3);
				highlight_string("\n <?php event = " . var_export($dx2, true) . "; ?>");
				highlight_string("\n<?php  event pcle = " . var_export($dx3, true) . "; ?>");
			}
		}
	}



	function test_estado(){
		//element con cuotas pendientes pero sin cuota upc; 5516
		//elements con cuotas a pagar  6475
		// lote cancelado cero ctas a pagar o vencidas 8550
		$q = "SELECT e.id FROM elements e WHERE e.elements_types_id = 1  ";
		$c = 0;
		$elms = $this->Mdb->db->query($q);
		if($elms->result_id->num_rows){
			foreach($elms->result_array() as $el){
				$c ++;
				$e = new Element($el['id']);
				highlight_string("<?php\n\$curr_state num:{$c} , elem id:{$e->id}  ->" . var_export($e->get_pcle('curr_state')->value, true) . ";\n?>");
				highlight_string("<?php\n\$estado_contrato =" . var_export($e->get_pcle('estado_contrato')->value, true) . ";\n?>");
				if(empty($e->get_pcle('estado_contrato')->value)){
					$e->set_pcle(0,'estado_contrato',"NORMAL");

				}
			}
		}

	}

	function prestamo_numerator($id){
		$e = new Element($id);
		$s = $e->get_servicios();
		if(!empty($s)){
			$c = 1;
			foreach($s as $srv){
				$srvx = new Element($srv['id']);
				if ($srvx->get_pcle('atom_id')->value == 9370 && $srvx->get_pcle('cant_ctas_restantes')->value > 0 ){
					$srvx->set_pcle(0,'descripcion',"PRESTAMO ".$c." - ");
					$c ++;
				}
			}
		}
	}

	function test_update_prestamos(){
		$q = "SELECT e.owner_id,a.name  FROM `elements_pcles` ep
			JOIN elements e on e.id = ep.elements_id
			JOIN elements_pcles ep2 on ep2.elements_id = e.owner_id and ep2.label = 'prod_id'
			JOIN atoms a on a.id = ep2.value
			WHERE ep.label = 'atom_id' and ep.value = 9370  GROUP BY a.name ORDER BY e.owner_id ASC";
		$p = $this->Mdb->db->query($q);
		if($p->result_id->num_rows){
			foreach($p->result_array() as $pr){
				echo "<br/>calling  LOTE :".$pr['name'];
				$t = $this->prestamo_numerator($pr['owner_id']);
			}
		}

	}

	function test_saldos(){
		$r =[];
		$q = "SELECT e.elements_id,a.name FROM `elements_pcles` e
			LEFT OUTER JOIN elements_pcles ep on ep.elements_id = e.elements_id AND ep.label = 'prod_id'
			LEFT OUTER JOIN atoms a on a.id = ep.value
			WHERE e.elements_types_id = 1 AND e.label = 'cant_ctas_restantes' and e.value >0 group by e.elements_id ORDER BY a.name ASC";
		$c = 0;
		$e = $this->Mdb->db->query($q);
		if($e->result_id->num_rows){
			foreach($e->result_array() as $el){
				$l = '';$s='';$nc='';
				$q_saldo = "SELECT nro_comprobante,saldo from comprobantes WHERE elements_id = {$el['elements_id']} ORDER BY id DESC LIMIT 1 ";
				$cs = $this->Mdb->db->query($q_saldo);
				if($cs->result_id->num_rows){
					$s = $cs->row()->saldo;
					$nc = $cs->row()->nro_comprobante;
				}
				$r[]=['elm_id'=>$el['elements_id'],'lote'=>$el['name'],'saldo'=>$s,'nro_comprobante'=>$nc];
			}
		}
		return $r;
	}


	function kill_contrato($elm_id){
		$e = new Element($elm_id);
		if(!empty($e->id)){
			$e->kill_events_all();
			$e->kill();
			echo 'done';
		}else{
			echo "Not found ".$elm_id;
		}
	}


	// pre trash de funciones 
	        
  // *************************************************************************
  // ******* 17 de enero 2020
  // ****** OLD PRODUCT MAIN LIST TO DEPRECATE *******
  // *************************************************************************        
  public function list(){
    $d = [];
    $pr = $this->Mdb->db->query("SELECT id FROM atoms WHERE atom_types_id = $this->types_id ORDER BY id ASC");
    if($pr->result_id->num_rows){
      foreach ($pr->result_array() as $prx) {
        $o = new Atom(intval($prx['id']));
        $d[] = $o->get_pcle();
      }
    }
    $r = [
      'route'=> $this->route,
      'method'=>'list',
      'action'=>'response',
      'title'=>' Listado de proveedores',
      'data'=>$d
    ];
    $this->cmn_functs->resp('front_call',$r);
  }
        
        
  // *************************************************************************
  // ******* 10 marzo 2020
  // *************************************************************************
  // ******* Borra los atoms seleccionados en el array recibido
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
      'msg'=>'Registro borrado'
      ]
    );
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
  // ******* 7 de octubre 2019
  // ******* PREPARA LA VENTANA DEL NUEVO ATOM
  // *************************************************************************
  function call_new_atom(){
    $st = $this->cmn_functs->call_atom_struct($this->type_text);
    if($st){
      $this->cmn_functs->resp('front_call',[
        'route'=>$this->route,
        'method'=> 'call_new_atom',
        'sending'=>false,
        'action'=> 'call_response',
        'data'=> ['type'=>$this->type_text,'title'=> $this->type_text,'pcles'=>$st],
        ]);
      }
    else{
        $res =[
          'tit'=>'Alta de '.$this->type_text,
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
  // ******* GUARDAR NUEVO ATOM PROVEEDOR
  // *************************************************************************
  function save_new_atom(){
    $p = $this->input->post('data');
    $atom_id = $this->cmn_functs->save_new_atom($p['type_text'],$p['fields']);
    if($atom_id){
      // crea element CONTRATO_PROVEEDOR
      $cpr = new Element(0,"CONTRATO_PROVEEDOR",$atom_id);
      $cpr->pcle_updv($cpr->get_pcle('fecha_inicio')->id,date('d/m/Y'));
      $this->cmn_functs->resp('front_call',[
        'method'=> 'call_new_atom',
        'sending'=>false,
        'action'=> 'save_response',
        'data'=> ['title'=>'Nuevo '.$p['type_text'],'atom_id'=>$atom_id]
        ]);
    }
    else{
      $res =[
        'tit'=>'ALTA DE PROVEEDOR',
        'msg'=>'Error No se registro el nuevo Proveedor',
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
      'data'=> ['result'=>'OK','after_action'=>$p['after_action']]
      ]);
  }
  

}
