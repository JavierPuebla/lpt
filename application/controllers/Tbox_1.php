<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tbox_1 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this ->load->model('user');
		$this ->load->model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->library('cmn_functs');

	   $this->Atom_types_id = 1;

		date_default_timezone_set('America/Argentina/Buenos_Aires');

	  // LIBRERIA PARA EXPORTAR EXCEL
    include (APPPATH . 'controllers/Excel_features.php');


		/**** PHPExcel ****/
		// include(APPPATH.'libraries/PHPExcel/IOFactory.php');

		// include (APPPATH . 'JP_classes/Atom.php');
		// include (APPPATH . 'JP_classes/Element.php');
		include (APPPATH . 'JP_classes/Event.php');
    // 	include (APPPATH . 'JP_classes/Historial.php');
		//
    // 	include (APPPATH . 'controllers/Clientes.php');
    // 	// include (APPPATH . 'JP_classes/Abstract_test.php');

  }


	public function index() {
    // ****** DATA PARA CUSTOMIZAR LA CLASE
		$cls_name = 'tbox_1';
      // ****** TABLA DE PERSISTENCIA DE DATOS
		$table = '';

      //******** ELEMENT
		$element=[""];

      // ****** RUTA DE ACCESO DEL CONTROLLER
		$route = 'tbox_1/';
      // ****** ******************

		$user = $this -> session -> userdata('logged_in');
		if (is_array($user)) {
			// DEFAULT ACTION
		} else {
			redirect('login', 'refresh');
		}
	}
  	// ****** END INDEX  ******

	 //  *** CHECK ELEMENTS DB


	// function test_set_cta_next_date(){
	// 	$fv = new DateTime($this->cmn_functs->fixdate_ymd('10/06/2019'));
	// 	var_dump($this->set_cta_next_date($fv,5521));
	// }
	// //  ******* CAMBIA LA PROXIMA FECHA DE VENCIMIENTO EN CREATE CUTOAS NEW
	// function set_cta_next_date($dt_obj,$elm_id){
 //      $fn_id = (new Element($elm_id))->get_pcle('financ_id')->value;
 //      $frq_ctas = (new atom($fn_id))->get_pcle('frecuencia_cuota')->value;
 //      return  $dt_obj->modify('+ '.$frq_ctas .' month');
 //    }


 // <-- tester -->

	function tcli(){
		// var_dump($this->user->login_cli('26367123')->result_id->num_rows);
		$this->user->login_cli('26367123');
	}

 	//***  13/04/2020
 	//*** reparar rescindidos  con elements types id 1 , camiarlos a 5  para que no los detecteel view de contratos
 	function fix_rescindidos(){
 		$q = "SELECT ep0.elements_id as id FROM `elements_pcles` ep0
 		LEFT OUTER JOIN `elements_pcles` ep1 on ep1.elements_id = ep0.elements_id AND ep1.label = 'prod_id'
 		LEFT OUTER JOIN atoms a on a.id = ep1.value
 		WHERE a.name like 'R_%' and ep0.elements_types_id = 1 GROUP BY ep0.elements_id";
 		$t = $this->Mdb->db->query($q);
 		if($t->result_id->num_rows){
 			foreach ($t->result_array() as $v) {
 				$nq = "UPDATE `elements_pcles` SET elements_types_id = 5 WHERE elements_id = {$v['id']}";
 				$this->Mdb->db->query($nq);
 				echo '<br/> updated'.$v['id'];
 			}
 		}
 	}




 function fix_etp(){
	 $e = $this->Mdb->db->query("SELECT id from elements WHERE elements_types_id = 6 ");
	 $x = 0;
	 foreach ($e->result() as $elm) {
		 $x ++;
		 $c = new Element($elm->id);
		 // $owner = new Atom($c->get('owner_id'));
		 // if($owner->get('type_id') == 2){
		 // 	$tp = 1;
		 // }
		 // if($owner->get('type_id') == 15){
			//  $tp = 5;
		 // }
		 $tp = 6;


		 $i = $this->Mdb->db->update(
			 'elements_pcles',
			 ['elements_types_id' => $tp],
			 "elements_id = ".$elm->id
		 );

	 echo '<br/>updated'.	$elm->id ." type".$tp;

		 // $c = new Element($elm->id);
		 // $pcles_arr = $c->get_pcle();
		 // foreach ($pcles_arr as $pcl) {
		 // 	echo "<br/>";
		 // 	print_r($pcl);
		 // }
	 }
	 echo "<br/> updated: ".$x;
 }



	function ifc(){
		$xl = $this->cmn_functs->excel_to_arr('test_import_infocliente.xls');
		var_dump($xl);
	}



 	function xtst(){
		$e = new Element(4773);
		// $e->kill();
		// $pmt = $e->get_last_payment();
		// if(($this->dif_today($last_pay_ev->date)->m == 0)){
		// 	$lp_monto =  $last_pay_ev->get_pcle('monto_pagado')->value;
		// }else{
		// 	$lp_monto = (new Event($ctas_disp['events'][0]['id']))->get_pcle('monto_cta')->value;
		// }

		// var_dump($pmt->get_pcle('fec_pago')->value);
		// var_dump($pmt->get_pcle('monto_pagado')->value);
		// var_dump($this->dif_today($pmt->get_pcle('fec_pago')->value)->m);


		// $v['value'] = 99;
		//
		// $last_titular = $e->get_pcle('titular_id')->value;
		// $prod_id = $e->get_pcle('prod_id')->value;
		//
		// if($v['value'] != $last_titular){
		// 	$e->pcle_updv( $e->get_pcle('cli_id')->id , $v['value'] );
		// 	$this->Mdb->db->query("UPDATE contab_asientos SET cliente_id = {$v['value']} WHERE lote_id = ".$prod_id);
		// }
		// echo 'done';
 	}

	// COMPARA HOY CON LA FECHA EN PARAM Y DEVUELVE OBJETO DATE
  function dif_today($date){
    $ddt = new DateTime($date);
		var_dump($ddt);
    $today = new DateTime();
    $dif_date = $today->diff($ddt);
    return $dif_date;
  }



	function test_obra(){
		// $obra = new Element(-1,'OBRA',12602);
		$obra = new Element(0,'CONTRATO','0');
		$ftr = $obra->get_filters();
		echo "<pre> titles: <br/>";
		print_r($ftr);
		echo "<pre> qrys: <br/>";
		// print_r($ftr['qry']);
		// $this->cmn_functs->filter($ftr['qry']);


	}

	function fix_barrio(){
		exit();
		$o = 820;
		$l = 200;
		$elm = $this->Mdb->db->query("SELECT * FROM `elements` where elements_types_id = 1 LIMIT {$o},$l ");
		foreach ($elm->result_array() as $key => $r) {
			echo '<br/>line '.$key;
			$x = new Element($r['id']);
			$l = new Atom($x->get_pcle('prod_id')->value);
			$lote_barrio = $l->get_pcle('emprendimiento')->value;

			$l_name = $l->get('name');
			$lb = $this->Mdb->db->query("SELECT * FROM `atoms` where atom_types_id = 4 and name LIKE '{$lote_barrio}' ");
			if($lb->result_id->num_rows){
				echo "<br>set pcle lote y elem".$lb->row()->id;
				echo "<br> en lote  ".$l_name;
				$l->set_pcle(0,'barrio_id',$br_id = $lb->row()->id);
				$x->set_pcle(0,'barrio_id',$lb->row()->id);
			}else {
				echo "no se inserto barrio id en ".$l_name;
			}

		}








	}

	function fix_cli(){

		$c = $this->Mdb->db->query("SELECT id from elements where elements_types_id = 1 limit 200");
		foreach ($c->result() as $cl) {
			var_dump($cl->id);

			// $e = new Element($cl->id)
		}
	}
	function upd_barrio(){
		$a = $this->Mdb->db->query("SELECT id FROM `atoms` WHERE atom_types_id = 2 ")->result();
		foreach ($a as $at) {
			//  el lote
			$lt = new Atom($at->id);
			$barrio_name = $lt->get_pcle('emprendimiento')->value;

			$br_id = $this->Mdb->db->query("SELECT id FROM `atoms` WHERE atom_types_id = 4 AND name = '{$barrio_name}' ")->result();

			if(!empty($br_id[0]->id)){
				if(strpos($lt->name,'R') > -1){
					echo "<br>".$lt->name;
					$lt->pcle_updv($lt->get_pcle('barrio_id')->id,-1);
				}else{
					echo '<br>setting '.$lt->name;
					$lt->pcle_updv($lt->get_pcle('barrio_id')->id,$br_id[0]->id);
				}
			}
		}
	}

	// BORRAR LOS CLIENTES SI DNI
	function kill_no_dni(){
		$q = "SELECT ap.id as atom_id, ap.value as DNI,ap.value as nombre,ap3.value as apellido FROM atoms_pcles ap
			LEFT OUTER JOIN atoms_pcles ap2 on ap.atom_id = ap2.atom_id AND ap2.label = 'nombre'
			LEFT OUTER JOIN atoms_pcles ap3 on ap.atom_id = ap3.atom_id AND ap3.label = 'apellido'
			WHERE ap.atom_types_id = 1 AND ap.struct_id = 19 AND ap.value IS NULL";

	}

	//  borro usuarios mal
	function dup_3(){
		echo "<pre>";
		$usr = $this->Mdb->db->query("SELECT value FROM `atoms_pcles` ap WHERE ap.struct_id = 19 ")->result_array();
		//*** TODOS LOS USUARIOS
		foreach ($usr as $u){
			$d = $this->Mdb->db->query("SELECT atom_id FROM `atoms_pcles` ap WHERE ap.struct_id = 19 AND ap.value = '{$u['value']}' ")->result();
			if(count($d) > 1){
				foreach ($d as $dupli) {
					$a = new Atom($dupli->atom_id);
					echo "<br> name: ".$a->name. ' atom_id '.$a->id;
					if($a->name == 'EMPTY'){$a->kill();}
					$c = $this->Mdb->db->query("SELECT elements_id FROM `elements_pcles` WHERE elements_types_id = 1 AND (label = 'titular_id' OR label = 'cotitular_id' ) AND value = '{$a->id}' ")->result();
					if(!empty($c)){
						echo '--> Has contracts ';
					}else{
						echo '--> Killing duplicated ';
						$a->kill();
					}
				}
			}
		}
	}


	function tdn2(){
		echo "<pre>";
		$usr = $this->Mdb->db->query("SELECT * FROM `atoms_pcles` ap WHERE ap.struct_id = 19 ")->result_array();
		// todos los usuarios por su dni
		foreach ($usr as $u) {

			echo "<br>busco si hay mas de uno con el mismo dni:".$u['value'].'atom id:'.$u['atom_id'];
			$d = $this->Mdb->db->query("SELECT atom_id FROM `atoms_pcles` ap WHERE ap.struct_id = 19 AND ap.value = '{$u['value']}' ")->result_array();
			echo '<br>encontrados:'.count($d);
			foreach ($d as $dx){
				// BUSCO EL ATOM ID EN CONTRATOS
				$f = $this->Mdb->db->query("SELECT * FROM `elements_pcles` WHERE elements_types_id = 1 AND value = '{$dx['atom_id']}'"	);
				if($f->result_id->num_rows){

						echo "<br>ESTA EN UN CONTRATO NO SE BORRA:".$dx['atom_id'];
				}else{
					if(empty($u['value'])){
						echo "<br>killing empty value...".$dx['atom_id'];
						$k = new Atom($dx['atom_id']);
						$k->kill();
					}
					else if(count($d) > 1)
					echo "<br>killing  duplicate...".$dx['atom_id'];
					$k = new Atom($dx['atom_id']);
					$k->kill();
				}
			}
		}
	}


	// feb 17 2020 resolver nombres de cliente duplicados
	function t_dn(){
		// clientes sin dni
		$nm = $this->Mdb->db->query("SELECT p1.id,p1.name,pn.value as nombre,pap.value as apellido,pdn.value as dni FROM `atoms` p1
LEFT OUTER JOIN atoms_pcles pn on p1.id = pn.atom_id and pn.label = 'nombre'
LEFT OUTER JOIN atoms_pcles pap on p1.id = pap.atom_id and pap.label = 'apellido'
LEFT OUTER JOIN atoms_pcles pdn on p1.id = pdn.atom_id and pdn.label = 'dni'
WHERE p1.atom_types_id = 1  AND pdn.value IS NULL GROUP BY p1.id ");
    if($nm->result_id->num_rows){
			echo "<pre/>";
			foreach ($nm->result_array() as $x){
				// es un duplicado ??
					// obtener otro atom con mismo 	apellido similar
					$e = $this->Mdb->db->query("SELECT * FROM `atoms_pcles` p LEFT OUTER JOIN `atoms_pcles` p2 ON p2.atom_id = p.atom_id AND p2.label = 'apellido' WHERE  p2.value LIKE '".$x['apellido']."' ");
					// p.label = 'nombre' AND p.value LIKE '".$x['nombre']."' AND
					// $e = $this->Mdb->db->query("SELECT * FROM `atoms_pcles` p LEFT OUTER JOIN `atoms_pcles` p2 ON p2.atom_id = p.atom_id AND p2.label = 'apellido' WHERE p.label = 'nombre' ");

					if($e->result_id->num_rows){
						echo '<br>****CLIENTE DUPLICADO';

						if(count($e->result_array()) > 1){
								foreach ($e->result_array() as $dupli) {
										// ** EL CLI DUPLICADO ES OWNER DE UN CONTRATO
										$cl = $this->Mdb->db->query("SELECT * FROM `elements_pcles` WHERE label = 'cli_id' AND value = {$dupli['atom_id']} ");
										if($cl->result_id->num_rows){

											// $elm = new Element($cl->result_array()[0]['elements_id']);
											echo '<br> ******* element init';
											var_dump($cl->result_array());
											echo '<br> ******* element END';
										}
								}

						}


					}
				// es cli de un elem no rescinido?

				// es cli de un rescindido ?



				// echo '<br/> x:'.$x;
				//
        // echo "<br/>________________________________________________";
      }
    }
	}

	//  11/02 test vasd amount

	function test_ipn(){
		$n = '20191127170214';

		// $x = new DateTime(substr($n,0,4).'-'.substr($n,4,2).'-'.substr($n,6,2).' '.substr($n,8,2).':'.substr($n,10,2).':'.substr($n,12,2));
		// var_dump(($this->cmn_functs->get_transaction_date($n))->format('Y-m-d H:i:s'));

		// echo $n. \n .'xxx';


	}


	function tvf(){


    // var_dump($this->new_pcle_updv('Element',4533,38919,'new value'));

		$v = new Atom(2336);

		var_dump($v->get_props());

  }

  function new_pcle_updv($type=0,$prnt_id=0,$id=0,$v=0){
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
      return $e;
    }else{
      return false;
    }

  }


  // ****************************
  // OBTIENE EL INDICE DE ACTUALIZACION CALCULANDO LA DIF ENTRE LAS CUOTAS
  function verif_indac($e_id){
    $elm = new Element($e_id);
    $p = $elm->get_last_payment();
    $fr = intval($elm->get_pcle('frecuencia_indac')->value);
    $mto_a = intval($p->get_pcle('monto_pagado')->value);
    $ctas_restantes = intval($elm->get_pcle('cant_ctas')->value);
    $px = $p->id ;
    $mto_x = $mto_a;
    while ($mto_x >= $mto_a) {
      $px --;
      $ev_x = new Event($px);
      if(property_exists($ev_x,'id')){
        $mto_x = intval($ev_x->get_pcle('monto_pagado')->value);
      }else{
        $mto_x = 0;
        break;
      }
    }
    if($mto_x){
      $res = number_format(floatval((($mto_a-$mto_x)/$mto_x)*100),2);
    }else{
      $res = 0;
    }
    return $res;

  }
  function get_monto_max(){
    $dni = $this->input->get('dni');

  }


  //***   MEW
  function tup(){
    $x = 700;
    $e = $this->Mdb->db->query("SELECT id FROM elements WHERE elements_types_id = 1 LIMIT 500,300 ");
    if($e->result_id->num_rows){
      foreach ($e->result_array() as  $elm) {
        $x ++;
        echo '<br/> x:'.$x;
        $this->check_update_pending($elm['id']);
        echo "<br/>________________________________________________";
      }
    }
    echo 'done ...';
  }


  function check_update_pending($elm_id){
        $r = -1;
        $e = new Element($elm_id);
        $lote = (new Atom($e->get_pcle('prod_id')->value))->name;
        //** datos de revision
        $aprv = intval($e->get_pcle('aplica_revision')->value);
        $frec_rev =intval($e->get_pcle('frecuencia_revision')->value);
        $cclo = intval($e->get_pcle('current_ciclo')->value);
        $cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);
        $cant_ctas = intval($e->get_pcle('cant_ctas')->value);
        $ctas_restantes = $e->get_cant_ctas_restantes();
        $pagos = $e->get_cant_ctas_imputadas();


        $e->pcle_updv($e->get_pcle('cant_ctas_restantes')->id,$ctas_restantes);
        echo "<br/>Lote: ".$lote;
        echo '/curr cclo: '.$cclo;
        echo '/frec rev: '.$frec_rev;
        echo '/ctas ciclo 2: '.$cclo2;
        echo '/ctas restantes: ' .$ctas_restantes;
        echo '/Pagos :'.$pagos;
        echo '/cant ctas :'.$cant_ctas;
        // echo '  mod result : '. ($ctas_restantes % $frec_rev);
        // // ** CAMBIO DE PLAN ** HAY DOS CICLOS, CURR CICLO ES 1 Y CTAS RESTANTES ES 0
        if($pagos < $cant_ctas){
          if($cclo == 1 && $ctas_restantes == 0 ){$r = 1;}
          // REVISON DE PLAN UNICO CICLO
          if($cclo == 1 && $cclo2 == 0 && $ctas_restantes % $frec_rev == 0){$r = 1;}
          // REVISION  DE CICLO UNO CON APLICA REV
          if($cclo == 1 && $aprv == 1 && $ctas_restantes % $frec_rev == 0){$r = 1;}
          // REVISION DE CICLO 2
          if($cclo == 2 && $ctas_restantes % $frec_rev == 0){$r = 1;}
          // NO HAY PAGOS REGISTRADOS
          if($pagos == 0){$r = -1;}

          $e->pcle_updv($e->get_pcle('plan_update_pending')->id,$r);
        }


       echo '-->Update Pending: '.$r;
      }


  function ingresos_por_lote(){
    // $p = $this->input->post('data');
    // $indt = $p['fec_desde']; //'10/02/2019';
    // $xdt = $p['fec_hasta'];//date('d/m/Y');
    $indt = '01/09/2019';
    $xdt = '31/09/2019';
    $rl = [];
    //***  OBTENGO EL RANGE DE ASIENTOS POR PAGO DE CUOTA
    $asiento = $this->Mdb->db->query("SELECT DATE_FORMAT(a.fecha,'%d/%m/%Y') as fecha ,a.lote_id,SUM(a.monto) as monto,a.nro_comprobante FROM `contab_asientos` a WHERE a.tipo_asiento = 'INGRESOS' AND a.cuenta_imputacion_id = 191 AND a.estado = 1 AND a.fecha >= STR_TO_DATE('{$indt}','%d/%m/%Y')  and a.fecha <= STR_TO_DATE('{$xdt}','%d/%m/%Y') GROUP BY a.lote_id ORDER BY a.fecha  ASC ");
     if($asiento->result_id->num_rows){
      foreach ($asiento->result_array() as $av) {
        // echo '<br/> '.$av['fecha']." monto: ".$av['monto'];
        $elm = new Element(0,'CONTRATO',$av['lote_id']);
        $lote = new Atom($av['lote_id']);
        // echo "<br/> fecha".$av['fecha']." lote:". $lote->name . " lote id:" . $av['lote_id'] ." elm id:". $elm->id ." monto:". $av['monto']. " nro compr:".$av['nro_comprobante'];
        // *** OBTENGO LAS IMPUTACIONES DE LA MISMA FECHA
        $imputaciones = $this->Mdb->db->query("SELECT nro_comprobante,concepto,monto,intereses_monto, saldo FROM `comprobantes` WHERE elements_id = {$elm->id} AND DATE_FORMAT(fecha,'%d/%m/%Y') LIKE '{$av['fecha']}' AND tipo_comprobante LIKE 'RECIBO' AND op_caja_nro < 0 ");
        $monto_imputado = 0;
        $concepto = '';
        $saldo = 0;
        if($imputaciones->result_id->num_rows){
          foreach ($imputaciones->result_array() as $imp) {
            $monto_imputado += (intval($imp['monto'])+intval($imp['intereses_monto']));
            $concepto .= ' |'. $imp['concepto'];
            $saldo  = $imp['saldo'];
          }
        }
        $rl[] = [
        'LOTE'=>$lote->name,
        'FECHA'=>$av['fecha'],
        'MONTO PAGADO'=>$av['monto'],
        'MONTO IMPUTADO'=>$monto_imputado,
        'CONCEPTO'=>$concepto,
        'SALDO'=>$saldo
      ];
      }
    }
    // ?echo "<pre/>";
    // print_r($rl);
    if(count($rl) > 0){
      $file_name = Excel_features::create_file($res,'ingresos_por_lote');
      $dnld = $this->cmn_functs->get_accion_icon('cloud_download','file_download',$file_name);
      $print = $this->cmn_functs->get_accion_icon('print','print_repo','ingresos_por_lote',-1);
      $response = [
          'method'=>'repo_ingresos_por_lote',
          'action'=>'response',
          'data'=>$rl,
          'download'=>$dnld,
          'print'=>$print,
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


  //  SALDO VERSION 2
  function get_saldo(){
    // $e = new Element($elm_id);

    // $imputado_pago = $e->get_imputaciones_ctas();
    // $tdda = $e->get_deuda_total();
    // //***  ESTADO DEUDA  = ADEUDADO  - IMPUTADO
    // $estado_deuda = intval($imputado_pago) - intval($tdda);
    $s = $this->cmn_functs->get_new_saldo(4976);
    var_dump($s);

  }

  // FIX A PATCH SALDOS
  function fps(){
    $e = $this->Mdb->db->query("SELECT * FROM patch_saldos");
    if($e->result_id->num_rows){
      foreach ($e->result_array() as  $elm){
        $t = (new Atom((new Element($elm['elm_id']))->get_pcle('prod_id')->value))->name;
        $this->Mdb->db->where('id',$elm['id']);
          $this->Mdb->db->update('patch_saldos', ['lote' => $t]);
        echo "<br/>";
        var_dump($t);

      }
    }

  }

  // REVISAR SALDO DE CUENTA
  //  GET ELEMENTS CON SALDO MAYOR A CERO
    function t_rs(){
      $x = 0;
      $e = $this->Mdb->db->query("SELECT id FROM elements WHERE elements_types_id = 1 limit 600,300");
      if($e->result_id->num_rows){
        // array_map(function($i){$this->revisar_plan($i['id']);}, $e->result_array());
        $r = [];
        foreach ($e->result_array() as  $elm) {
          $x = $this->cmn_functs->get_new_saldo($elm['id']);
          if($x && !empty($x['lote'])){
            $i = $this->Mdb->db->insert('saldo_cuentas',[
              'lote' => $x['lote'],
              'saldo'=> $x['r_saldo_2']
            ]);
            if(intval($x['r_saldo_2']) > 50){
              $this->Mdb->db->insert('patch_saldos',[
               'lote' => $x['lote'],
              'elm_id' =>$elm['id'],
              'monto'=> $x['r_saldo_2']
            ]);
            }

             echo "<br/>Lote: ".$x['lote'].' Saldo: '.$x['r_saldo_2'];
          }
        }
      }
    }

    function t_fu(){
    $cadena = 'boleto 15- 2003';
    echo $this->cmn_functs->sanitize_filename($cadena);
    }


  function tm(){
    $t = new Element(7874);
    var_dump($t->get_ahorro());
  }


  function t_update_cuotas(){
    $e = new Element(6221);
    $evs = $e->get_events(8,'a_pagar');
    // var_dump($evs);
    foreach ($evs['events'] as $ev) {
      $evo = new Event($ev['id']);
      echo '<br/>updting:';
      echo '<br/>fecha'. $evo->get('date');
      echo '<br/> monto:'. $evo->get_pcle('monto_cta')->value;
      echo '<br/> fec_vto:'. $evo->get_pcle('fecha_vto')->value;
      echo '<br/> nro cta:'. $evo->get_pcle('nro_cta')->value;

    }
  }


  function frev_nro_cta($fr,$n){
    $freq_rev = 24;
    $num = 77;
    for ($i=$n - 6; $i < $n + 6 ; $i++) {
      if($i  % $fr == 0){
        echo 'evaluating '.$i;
      }
    }
  }

  function t_rpl(){
    $x = 0;
    $e = $this->Mdb->db->query("SELECT id FROM elements WHERE elements_types_id = 1 ");
    if($e->result_id->num_rows){
      // array_map(function($i){$this->revisar_plan($i['id']);}, $e->result_array());
      $r = [];
      foreach ($e->result_array() as  $elm) {
      $r[] = $this->revisar_plan($elm['id']);
      }
      $file_name = Excel_features::create_file($r,'revision_plan');
      $dnld = $this->cmn_functs->get_accion_icon('cloud-download','file_download',$file_name);
      $print = $this->cmn_functs->get_accion_icon('print','print_repo','revision_plan',-1);
      $response = [
          'method'=>'revision_plan',
          'action'=>'response',
          'data'=>$r,
          'download'=>$dnld,
          'print'=>$print,
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

  function revisar_plan($id){
    $e = new Element($id);
    $r = [
      'Id Contrato'=>$id,
      'Lote'=> (new Atom($e->get_pcle('prod_id')->value))->name,
      'Indice Act.'=>0,
      'Ultimo Pago'=>'',
      'Nro. Cta.'=>'',
      'Monto'=>0
    ];

    if($e->get_pcle('indac')->value > 0 && $e->get_pcle('indac')->value < 16 && !empty($r['Lote']) && !preg_match('/R_/', $r['Lote'])){
      $r ['Indice Act.'] = $e->get_pcle('indac')->value;
      $ev = $e->get_last_event('pagado');
      if(!empty($ev)){
        $r['Ultimo Pago'] = $ev['pcles']['fec_pago']->value;
        $r['Nro. Cta.'] = $ev['pcles']['nro_cta']->value;
        $r['Monto'] = $ev['pcles']['monto']->value;
      }
    }
    return $r;
  }



    //  ******************

  function t_call_edit_atom(){
    // $p = $this->input->post('data');
    $type = 'Atom';
    $id = "10996";
    $r = $this->cmn_functs->call_edit($type,$id);
    $this->cmn_functs->resp('front_call',[
      'method'=> 'call_edit_atom',
      'sending'=>false,
      'action'=> 'call_response',
      'data'=> $r
    ]);
  }
  function df(){
    echo date('d/m/Y-H:i');
  }

  function tc(){
    $e = $this->Mdb->db->query("SELECT id FROM elements WHERE elements_types_id = 1 OR elements_types_id = 4 ");


    if($e->result_id->num_rows){
      array_map(function($i){$this->fix_cta_1($i['id']);}, $e->result_array());

    }

    // $this->fix_cta_1(4843);

  }
  function fix_cta_1($elm_id){
    $e = new Element($elm_id);
    echo '<br/>updating->'. (new Atom($e->get_pcle('prod_id')->value))->name;

    $q = $this->Mdb->db->query("SELECT evp.value FROM events ev JOIN events_pcles evp on evp.events_id = ev.id and evp.label = 'monto_cta' WHERE ev.elements_id = {$elm_id} AND ev.ord_num = '1.0' ");
    // ENCUENTRA LA CUOTA 1
    if($q->result_id->num_rows > 0){
      $v = $q->row()->value;

    }else{
      $v = 0;
    }
    $e->set_pcle(0,'monto_cta_1',$v);
    echo "result cta ->".$e->get_pcle('monto_cta_1')->value;
  }


  function tf(){
    $lnom = 'ES-345';
    $partida = '2222';
    $files_list = [];
    $this->load->helper('file');
    $this->load->helper('directory');

    $d = directory_map('./uploads/lote_data_gen');
    var_dump($d);
    if($d){
      foreach ($d as $v) {
        if(!is_array("/$lnom/") && preg_match("/$lnom/",$v) || substr($v,0,strpos($v,'.')) === $partida ){
            $file = './uploads/lote_data_gen/'.$v;
            $files_list[] = get_file_info($file);
        }
      }

      // Sort array
      usort($files_list, function ($e1, $e2) {return $e1['date'] - $e2['date'];});
      // array_reverse($files_list,true);
      var_dump($files_list);
    }
  }



  function expose_cntr(){
    $l = new Atom(0,"LOTE",$this->input->get('lote'));
    $e = new Element(0,"CONTRATO",$l->id);
    echo '<h3>Elem Id-> '.$e->id."</h3>";
    echo '<pre>';
    var_dump($e->expose());

  }


  function update_financ(){
    $l = new Atom(0,"LOTE",$this->input->get('lote'));
    $e = new Element(0,"CONTRATO",$l->id);

    $this->upd_elm_financ($e->id);
    echo 'Done';
  }



  function tfp(){
    $in = $this->input->get('in');
    $out = $this->input->get('out');
    $ordnum = intVal($in);
    $l = $this->app_model->get_arr("SELECT * FROM elements WHERE elements_types_id = 4 or elements_types_id = 1 ORDER BY id LIMIT {$in} , {$out}");

    foreach ($l as $lv) {
      echo '<br>ordnum->'.$ordnum;

      $this->update_struct($lv['id']);
      $this->upd_elm_financ(intval($lv['id']));
      $ordnum ++;
    }

    echo 'done...';
  }

  function upda(){
    $in = $this->input->get('in');
    $out = $this->input->get('out');
    $ordnum = intVal($in);
    $l = $this->app_model->get_arr("SELECT * FROM atoms ORDER BY id LIMIT {$in} , {$out}");

    foreach ($l as $lv) {
      echo '<br>ordnum->'.$ordnum;

      $this->update_atom_struct($lv['id']);

      $ordnum ++;
    }

    echo 'done...';
  }

  function update_atoms(){

    $this->update_atom_struct(848);
    echo 'done';
  }


    function update_atom_struct($id){
      $a = new Atom($id);
      $str = $a->struct;
      echo '<br>updating->'.$a->name;
      foreach ($str as $s) {
        $p = $this->Mdb->db->query("SELECT * FROM atoms_pcles WHERE atom_id = {$id} AND label = '{$s->label}'")->row();
        if(empty($p)){
          $x = $this->Mdb->db->insert('atoms_pcles',[
            'atom_id' => $a->id,
            'atom_types_id'=>$a->type_id,
            'struct_id'=>$s->id,
            'label'=>$s->label,
          ]);
        }else{
          $this->Mdb->db->where('id',$p->id);
          $this->Mdb->db->update('atoms_pcles', ['struct_id'=>intval($s->id),'atom_types_id'=>$a->type_id]);
        }
      }
    }


    function update_struct($e_id){
      $elm = new Element($e_id);
      $str = $elm->struct;
      foreach ($str as $s) {
        $p = $this->Mdb->db->query("SELECT * FROM elements_pcles WHERE elements_id = {$e_id} AND label = '{$s->label}'")->row();
        if(empty($p)){
          $x = $this->Mdb->db->insert('elements_pcles',[
            'elements_id' => $elm->id,
            'struct_id'=>$s->id,
            'label'=>$s->label,
          ]);
        }else{
          $this->Mdb->db->where('id',$p->id);
          $this->Mdb->db->update('elements_pcles', ['struct_id'=>intval($s->id)]);
        }
      }
    }

  //  UPDATE  PARA LOS CAMBIOS DE FINANCIACION 09/09/20019
  function upd_elm_financ($elm_id){
    $e = new Element($elm_id);

    //***  ATOM ID DEL MODO DE FINANCIACION VIEJO
    $f = $this->app_model->get_obj("SELECT * from elements_pcles WHERE elements_id = {$elm_id} AND label = 'financ_id' ");
    if(empty($f)){
      echo 'empty financ id en '.$elm_id;
      return false;
    }

    $fid = intval($f->value);
    //*** TODOS LOS EVENTO CUOTA DEL CONTRATO
    $x = $this->app_model->get_obj("SELECT count(*) as cc from events where elements_id = {$elm_id}  and events_types_id > 3 AND  events_types_id < 9");
    //*** SUMA DE MONTO_CUOTA
    $t = $this->app_model->get_obj("SELECT SUM(p.value) as res FROM events e JOIN events_pcles p ON p.events_id = e.id AND p.label = 'monto_cta' WHERE e.elements_id = {$elm_id}  AND e.events_types_id > 3 AND e.events_types_id < 9");

    if($x->cc == 0){
      echo "<br> eventos cuota empty ->".$elm_id;
      $lote = (new Atom($e->get_pcle('prod_id')->value))->name;
      if(empty($lote)){echo 'fallo prod_id';return false;}
      $this->app_model->insert('lotes_error_log',array('lote'=>$lote,'error'=>'0 eventos cuota'));
      return false;
    }
    if($t->res == 0){
      echo "<br> monto total empty ->".$elm_id;
      $lote = (new Atom($e->get_pcle('prod_id')->value))->name;
      $this->app_model->insert('lotes_error_log',array('lote'=>$lote,'error'=>'monto Total = 0'));
      return false;
    }

    $monto_total = $t->res;

    $c = intval($x->cc);

    // ES DE UN SOLO CICLO POR DEFAULT
    $newcc = $c;
    $cclo = 1;
    $cclo2 = 0;

    // CICLO 1 Y ES DE DOS CICLOS
    if($fid == 2225 || $fid == 9436 || $fid == 10766 || $fid == 10780){
      //** LAS CUOTAS DEL SEGUNDO CICLO NO SON EVENTOS CREADOS , SUMO LA CANTIDAD DE CUOTAS POST POSESION A NEW_CANT_CTAS
      $cpp = ($fid == 10780)?150:120;
      $newcc = $c + $cpp;
      $cclo = 1;
      $cclo2 = $cpp;

    }
    // CICLO 2 Y ES DE DOS CILOS
    elseif($fid == 2235|| $fid == 10784 || $fid == 12402 || $fid == 9380){
      //****  ACA AS CUOTAS DEL SEGUNDO CICLO YA ESTAN CREADAS Y SE SUMARON A LAS DEL CICLO 1
      $cpp = ($fid == 10780)?150:120;
      $cclo = 2;
      $cclo2 = $cpp;

    }

    // get cuotas restantes
    // var_dump($e->get_cant_ctas_imputadas());
    $crest = $newcc - ($e->get_cant_ctas_imputadas());
    $indac = $this->app_model->get_value("SELECT * from atoms_pcles WHERE atom_id = {$fid} AND label = 'indac' ");
    $frec_indac = $this->app_model->get_value("SELECT * from atoms_pcles WHERE atom_id = {$fid} AND label = 'frecuencia_indac' ");
    $interes = $this->app_model->get_value("SELECT * from atoms_pcles WHERE atom_id = {$fid} AND label = 'interes' ");
    $anticipo = $this->app_model->get_value("SELECT * from atoms_pcles WHERE atom_id = {$fid} AND label = 'anticipo' ");

    echo " -- updating elm-> ".$e->id ." lote->".(new Atom($e->get_pcle(($e->type_id == 1)?'prod_id':'atom_id')->value))->name;

    $e->pcle_updv($e->get_pcle('monto_total')->id,$monto_total);
    $e->pcle_updv($e->get_pcle('plan_update_pending')->id,'false');
    $e->pcle_updv($e->get_pcle('cant_ctas_restantes')->id,$crest);
    $e->pcle_updv($e->get_pcle('current_ciclo')->id,$cclo);
    $e->pcle_updv($e->get_pcle('cant_ctas')->id,$newcc);
    $e->pcle_updv($e->get_pcle('cant_ctas_ciclo_2')->id,$cclo2);
    $e->pcle_updv($e->get_pcle('indac')->id,$indac);
    $e->pcle_updv($e->get_pcle('frecuencia_indac')->id,$frec_indac);
    $e->pcle_updv($e->get_pcle('interes')->id,$interes);
    $e->pcle_updv($e->get_pcle('anticipo')->id,$anticipo);
    $e->pcle_updv($e->get_pcle('frecuencia_ctas_refuerzo')->id,0);
    $e->pcle_updv($e->get_pcle('aplica_revision')->id,0);
    $e->pcle_updv($e->get_pcle('frecuencia_revision')->id,24);

    echo '<br>Done.';
  }



  function tfp_elm_id(){
    $id = $this->input->get('id');
    $this->update_struct(intval($id));
    $this->upd_elm_financ(intval($id));

    echo 'done...';
  }




	// TEST DE ACTUALIZAR CONTRATO
  function tac(){
    // $c = new Element(4533);
              //**** FIX DE CLI_ID  BACK COMPAT ***
              // $c->pcle_updv($c->get_pcle('cli_id')->id,1111);


    $this->upd_elm_financ(5453);

    // var_dump($c->get_pcle());
    // $c->set_pcle(0,'some_other_new_label',100);

    // var_dump($c->get_pcle('some_other_new_label'));

    // var_dump($c->get_pcle());

  }








  // actualizar contrato
  function actualizar_contrato($elm_id=null){
    if(!$elm_id){
      $p = $this->input->post('data');
      $elm_id = $p['elm_id'];
    }
    $e = new Element($elm_id);


    // guardar todos los pagos realizados en una var
    $t = $e->get_tot_pagado();
    //  cerrar todos los servicios actuales que pueda tener el contrato


    // crear nuevo contrato con el mismo lote y poner en un pago el monto de los pagos del contrato anterior
    //
  }


  // *************************************************************************
    // ******* PREPARA LA VENTANA DE NUEVO ATOM SEGUN SU ESTRUCTURA
    // *************************************************************************
     function call_new_atom(){
        //*** POST data CONTIENE EL TIPO DE ATOM A CREAR
        $p = $this->input->post('data');
        $struct = $this->app_model->get_arr("SELECT label,value,title,vis_elem_type,vis_ord_num FROM `elements_struct` WHERE elements_types_id = {$p['elements_types_id']} ORDER BY vis_ord_num ASC");
        if($struct){
          switch ($p['elements_types_id']) {
            case '4':
              //  fix label atom_id to servicios
              foreach ($struct as $key => $s) {
               if($s['label'] == 'atom_id'){$struct[$key]['label'] = 'servicios';}
              }
              $m = 'new_service_elem';
              break;
            default:
             $m = 'new_contrato_elem';
              break;
          }
          $this->cmn_functs->resp('front_call',[
            'method'=> $m,
            'sending'=>false,
            'action'=> 'call_response',
            'data'=> $struct
          ]);
        }
      }

      // *********************************************************
      //*** GUARDA EL NUEVO ATOM  Y CREA LOS PCLES
      function save_new_elem(){
        $p = $this->input->post('data');

        switch ($p['elem_type']) {
            case '4':
              // var_dump($p);
              $m = 'new_service_elem';
              $srvc_atom_id = -1;
              // ID DEL SERVICIO
              foreach ($p['fields'] as $key => $s){
                if($s['label'] == 'servicios'){
                  $srvc_atom_id = $s['value'];
                  // retorna la etiqueta servicios al nombre atom_id
                  $p['fields'][$key]['label'] = 'atom_id';
                }
                if($s['label'] == 'fec_ini'){
                  $fec_ini = $s['value'];
                }

              }
              $srv_atm = new Atom($srvc_atom_id);
              //  EL -1 DE NEW ELEMENT CREA UN NUEVO SERVICIO CON EL MISMO OWNER ID
              $c = new Element(-1,'SERVICIO',$p['elem_id']);
              $c->pcle_updv($c->get_pcle('atom_id')->id,$srv_atm->id);
              // $c->pcle_updv($c->get_pcle('atom_name')->id,$srv_atm->name);
              // $c->pcle_updv($c->get_pcle('estado')->id,'normal');
              $c->pcle_updv($c->get_pcle('fec_ini')->id,$fec_ini);


              break;
            case '1':
             $m = 'new_contrato_elem';
              $lid = -1;
              $clid = -1;
              // ID DE LOTE
              foreach ($p['fields'] as $v) {
                if ($v['label'] == 'prod_id'){
                  $lid = $v['value'];
                }
                // fix de cli_id back compat
                if ($v['label'] == 'titular_id'){
                  $clid = $v['value'];
                }
              }
              if(!$lid){exit('ERROR EN lote id');}
              // SET NUEVO ESTADO DEL LOTE
              $l = new Atom($lid);
              $l->pcle_updv($l->get_pcle('estado')->id,'ACTIVO');
              // CONTRATO ELEMENT
              $c = new Element(0,'CONTRATO',$lid);
              //**** FIX DE CLI_ID  BACK COMPAT ***
              $c->pcle_updv($c->get_pcle('cli_id')->id,$clid);
              $c->pcle_updv($c->get_pcle('titular_id')->id,$clid);
              break;
          }

        // GUARDA LOS PCLES POR SUS LABELS
        foreach ($p['fields'] as $pcle) {
          $c->pcle_updv(intval($c->get_pcle($pcle['label'])->id),$pcle['value']);
        }

        // CREA CUOTAS EVENTS
        $this->create_cuotas_new_v2($c);

        // PONE UPDATE PENDING EN FALSE HASTA QUE SE EVALUE ES PENDING EN EL PAGO
        $c->pcle_updv($c->get_pcle('plan_update_pending')->id,'false');


        $d = ($p['elem_type'] == '4')?['result'=>'ok','elm_id'=>$p['elem_id']]:['result'=>'ok','elm_id'=>$c->id];
        // response
        $this->cmn_functs->resp('front_call',[
                'method'=> $m,
                'action'=> 'save_response',
                'data'=> $d
              ]);
      }


    /*


        // recorre pcles para actualizar el struct id correspondiente al label
        foreach ($this->pcles as $pcl) {
          $f = 0;

          foreach ($this->struct as $str) {
            if($pcl->label === $str->label){
              $f = $str->id;
            }else{
              $p = $this->Mdb->db->insert($this->pcles_db_name,[
              $this->foreign_key => $this->id,
              'struct_id'=>$str->id,
              'label'=>$str->label,
            ]);
            }

          }
          if($f > 0){
            $this->Mdb->db->where('id', $pcl->id);
            $this->Mdb->db->update($this->pcles_db_name, ['struct_id'=>intval($f)]);
          }
        }


      // RECORRE STRUC
        // foreach ($this->struct as $s) {
        //   // busca el pcle lbl en s
        //   foreach ($this->pcles as $cp) {
        //     // si encuentra el label en struct, actualiza el id de struct
        //     if($cp->label === $s->label){
        //       $this->Mdb->db->where('id', $cp->id);
        //       $this->Mdb->db->update($this->pcles_db_name, ['struct_id'=>$s->id]);
        //     }else{
        //        // si no lo encuentra lo agrega a la estructura de elemen_pcle
        //       $this->Mdb->db->insert($this->pcles_db_name,[
        //         $this->foreign_key => $this->id,
        //         'struct_id'=>$s->id,
        //         'label'=>$s->label,
        //       ]);
        //     }
        //   }
        //   }
        $this->pcles = $this->get_all_pcles();


    */






    	function clean_lote(){
    		$c = $this->input->get('cod');
    		$l = $this->app_model->get_obj("SELECT id FROM atoms WHERE name = '{$c}' ");

    		$a = new Atom($l->id);
    		$estado_pcle = $a->get_pcle('estado');
    		$a->pcle_updv($estado_pcle->id,'DISPONIBLE');

    		$e = new Element(0,'CONTRATO',$l->id);
    		$e->kill_events_all();
    		$e->kill();

    		echo "it's done";
    	}

    	function update_plan($e){
          $r = false;
          $pmt = $e->get_last_payment();
          if(empty($pmt)){return false;}
          $lp = new Event($pmt->id);
          $x = $lp->get_pcle('nro_cta')->value;
          if(preg_match_all('!\d+!', $x, $m)){
            //** cuota numero y total de cuotas
            $nc = intval($m[0][0]); $tc = intval($m[0][1]);
            //** datos de revision
            $aprv = intval($e->get_pcle('aplica_revision')->value);
            $cclo = intval($e->get_pcle('current_ciclo')->value);
            $frec_rev = intval($e->get_pcle('frecuencia_revision')->value);
            //** control
            //** APLICAR REVISION EN CICLO 1 CUANDO "APLIC_REV" ESTA SELECCIONADO, CUANDO ES CICLO 2 Y FREC_REV ES MULTIPLO O CUANDO ES CAMBIO DE CICLO
            if($cclo == 1 && $aprv == 1 && $nc % $frec_rev === 0){
              $r = true;
            }elseif($cclo == 2 && $nc % $frec_rev == 0){
              $r = true;
            }elseif(intval($m[0][0]) === intval($m[0][1]) ){
              $e->set_pcle($e->get_pcle('current_ciclo')->id,'current_ciclo',2,'',-1);
              $r = true;
            }
          }
          if($r){
            // OBTENGO LOS DATOS PARA LA VENTANA DE CAMBIO DE PLAN
            $lbls = $this->app_model->get_arr("SELECT label FROM elements_pcles WHERE elements_id = {$e->id} AND  vis_ord_num >= 5 and vis_ord_num <= 14 ORDER BY vis_ord_num ASC");
            $data = [];
            foreach ($lbls as $l) {
            	$data[] = $e->get_pcle($l['label']);
            }
            $res=[
                  'method'=>'set_cambio_financ_plan',
                  'action' =>'response',
                  'elem_id'=>$e->id,
                  'last_fec_pago'=>$pmt->get_pcle('fec_pago')->value,
                  'last_monto_pagado'=>$pmt->get_pcle('monto_pagado')->value,
                  'data'=>$data
                ];
            $this->cmn_functs->resp('front_call',$res);
            exit();
          }
        }


    	//  test de lyc
    	function test_mk_ctas(){

    		$e = new Element(0,'CONTRATO',12151);
    		$e->kill_events_all();

    		// total de cuotas
    		$e->set_pcle(0,'cant_ctas',10);
    		// ciclo 2 de cuotas
    		$e->set_pcle(0,'cant_ctas_ciclo_2',6);
    		$e->set_pcle(0,'monto_total',100000);
    		$e->set_pcle(0,'interes',0);
    		$e->set_pcle(0,'indac',16);
    		$e->set_pcle(0,'frecuencia_indac',6);
    		$e->set_pcle(0,'anticipo',0);
    		$e->set_pcle(0,'frecuencia_ctas_refuerzo',0);
    		$e->set_pcle(0,'fec_ini','22/08/2019');
    		$e->set_pcle(0,'cli_id',7681);
    		$e->set_pcle(0,'titular_id',7681);
    		$e->set_pcle(0,'prod_id',12151);

    		$res = $this->create_cuotas_new_v2($e);
    		var_dump($res);
    	}


    	function test_get_elm(){
    		$e = new Element(0,'CONTRATO',12151);
    		if($this->cambio_de_ciclo($e)){
    			$ev_lpay = $e->get_last_payment();
    			$res=[
    				'method'=>'set_cambio_financ_plan',
    				'action' =>'response',
    				'elem_id'=>$e->id,
    				'last_fec_pago'=>$ev_lpay->get_pcle('fec_pago')->value,
    				'last_monto_pagado'=>$ev_lpay->get_pcle('monto_pagado')->value
    			];
    			$this->cmn_functs->resp('front_call',$res);
    			exit();
    		}
    		$this->update_vencimientos($e);

    		$this->revision_plan($e);

    		echo 'done';
    	}


    	function get_plan_name($e){
    		$c = intval($e->get_pcle('cant_ctas')->value);
    		$cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);
    		$indac = intval($e->get_pcle('indac')->value);
    		$intr = intval($e->get_pcle('interes')->value);
    		if($cclo2>0){
    			return "Plan: ".($c-$cclo2).' Mas '.$cclo2.'Cuotas con '.$indac.'% de actualizacion';
    		}else{
    			return "Plan: ".$c .'Cuotas con '.$intr.'% de interes';
    		}

    	}


    	//*** OK ***
    	function create_cuotas_new_v2($elm){
          // $tr= [];
          // CONTROLA CTAS REFUERZO SI HAY PARA DAR EL NUMERO DE CUOTA EN LA DESCRIPCION
          $ctas_refuerzo_creadas = 0;

          //***  GENERA CUOTA ANTICIPO (CUOTA CERO) el campo anticipo es el monto
          $ant = intval($elm->get_pcle('anticipo')->value);
          if($ant > 0){
            $lyc = 'Cuota Anticipo';
            // $tr[]=['cuota'=>$lyc,'ordnum'=>0,'fec_ven'=>$elm->get_pcle('fec_ini')->value,'monto'=>$ant,'elm'=>$elm->id];
            $this->set_new_cuota($elm->id,8,$ant,$elm->get_pcle('fec_ini')->value,0,$lyc);
          }
          //*****
          //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
          $nfi = '10'.substr($elm->get_pcle('fec_ini')->value, 2);
          $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
          //******



          $totm = intval($elm->get_pcle('monto_total')->value);
          $interes = intval($elm->get_pcle('interes')->value);
          $ctas = intval($elm->get_pcle('cant_ctas')->value);
          //*** MONTO DE CUOTA 1
          $monto_cta = intval($totm / $ctas);

          // CONTROLA INTERES % SI ES MAYOR A CERO CALCULA EL INTERES MENSUAL PARA APLICARLO A LAS CUOTAS A CREAR QUE SON FIJAS
          if($interes > 0){
            $monto_cta = intval(($totm*($interes/100)+$totm)/$ctas);
            // $tr[]=['aplica_interes'=>$interes];
          }

          //*** GENERAR LAS CUOTAS SEGUN CANTIDAD TOTAL DE CUOTAS
          $tc = intval($elm->get_pcle('cant_ctas')->value);
          $cclo2 = intval($elm->get_pcle('cant_ctas_ciclo_2')->value);
          if($cclo2 > $tc){exit('error en la cantidad de cuotas');}

          for ($i=1; $i <= $tc; $i++){
            // FECHA DE VENCIMIENTO
            $curr_fec_ven = $fv->format('d/m/Y');
            //*** OBTENGO NUMERO DE CTA ACTUAL TOTAL DE CUOTAS DE CADA CICLO Y CICLO ACTUAL
            $nro_cta = $this->check_nro_de_cuota($i,$tc,$cclo2);
            $lyc = "Cuota ".$nro_cta['num'].' de '.$nro_cta['tot'];



            // $tr[]=['cuota'=>$lyc,'ordnum'=>$i,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
            $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$i,$lyc);

            // APLICA CUOTA REFUERZO SI HAY
            //*** EVALUA SI HAY CUOTAS REFUERZO Y LA APLICA SI EL MULTIPLO DEL MES ES EL CORRIENTE MES
            $rfz = intval($elm->get_pcle('frecuencia_ctas_refuerzo')->value);
            if($rfz > 0 && $i % $rfz === 0){
              $ctas_refuerzo_creadas ++;
              $lyc = 'Cuota Refuerzo '.$ctas_refuerzo_creadas;
              $ord_num = floatval($i)+0.1;
              // $tr[]=['cuota'=>$lyc,'ordnum'=>$ord_num,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
              $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$ord_num,$lyc);
            }


            //*************  ACCIONES SOBRE CADA CUOTA ****************
            // INCREMENTA EL MONTO NOMINAL DE CUOTAS SI HAY INDAC
            // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
            $indac = intval($elm->get_pcle('indac')->value);
            $frec_indac = intval($elm->get_pcle('frecuencia_indac')->value);
            // echo '<br/> pre monto_cta:'.$monto_cta;
            if($indac > 0 && $frec_indac > 0){
              if($i > 1 && $i % $frec_indac === 0){
                $monto_cta = round($monto_cta * $indac / 100 + $monto_cta);
                // var_dump(round($monto_cta * $indac / 100 + $monto_cta));
                // echo '<br/> modificado: '.$monto_cta;
              }
            }


            // INCREMENTO DEL MES DE FV
            $fv->modify('next month');
          }
          //***  FIN LOOP CREACION DE CUOTAS
          return $tr;
        }


        //*** OK ****
        function update_vencimientos($e){
        	$dt_now = new DateTime(date('Y-m-d'));
        	$f = $e->get_events(8,'a_pagar');
        	foreach ($f['events'] as $xv) {
        		$dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
        		$dt_diff = $dt_xv->diff($dt_now);
        		if($dt_diff->invert == 0){
        			if($dt_diff->days >= 25){
        				$this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
        			}
        		}
        	}
        }

        //*** OK ****
        function revision_plan($e){
        	$lp = new Event($e->get_last_payment()->id);
        	$x = $lp->get_pcle('nro_cta')->value;
        	preg_match_all('!\d+!', $x, $m);
        	$nc = intval($m[0][0]);

        	$aprv = intval($e->get_pcle('aplica_revision')->value);
        	$cclo = intval($e->get_pcle('current_ciclo')->value);
        	$frec_rev = intval($e->get_pcle('frecuencia_revision')->value);
        	// APLICAr REVISION EN CICLO 1 cuando "Aplic_rev" esta seleccionado o si es ciclo 2
        	if($cclo == 1 && $aprv == 1 && $nc % $frec_rev === 0){
        		$res=[
    				'method'=>'set_cambio_financ_plan',
    				'action' =>'response',
    				'elem_id'=>$e->id,
    				'last_fec_pago'=>$lp->get_pcle('fec_pago')->value,
    				'last_monto_pagado'=>$lp->get_pcle('monto_pagado')->value
    			];
    			$this->cmn_functs->resp('front_call',$res);
    			exit();

        	}elseif($cclo == 2 && $nc % $frec_rev == 0){
        		$res=[
    				'method'=>'set_cambio_financ_plan',
    				'action' =>'response',
    				'elem_id'=>$e->id,
    				'last_fec_pago'=>$lp->get_pcle('fec_pago')->value,
    				'last_monto_pagado'=>$lp->get_pcle('monto_pagado')->value
    			];
    			$this->cmn_functs->resp('front_call',$res);
    			exit();
        	}
        }

        //*** OK **** CHECKEA EL CAMBIO DE CILO DEL CONTRATO
        function cambio_de_ciclo($e){
        	$lp = new Event($e->get_last_payment()->id);
        	$x = $lp->get_pcle('nro_cta')->value;
        	preg_match_all('!\d+!', $x, $m);
    		if(intval($m[0][0]) === intval($m[0][1]) ){
        		$e->set_pcle($e->get_pcle('current_ciclo')->id,'current_ciclo',2,'',-1);
        		return true;
        	}
    		return false;
        }


    	// checkEA NUMERO DE CUOTAS $x int cuota actual
        function check_nro_de_cuota($x,$tc,$cclo2){
          $cclo = 1;
          if($cclo2 > 0 && $x <= ($tc-$cclo2)){
            $tc = $tc-$cclo2;
          }elseif($cclo2 > 0 && $x > ($tc-$cclo2)){
            $cclo = 2;
            $x = $x - ($tc-$cclo2);
            $tc = $cclo2;
          }
          return ['num'=>$x,'tot'=>$tc,'ciclo'=>$cclo];
        }


        //  fix para error en pcl propietario
    	function update_prop(){
    		$q  = "SELECT * FROM `atoms` WHERE atom_types_id = 2 AND name LIKE 'MO-%'";
    		$lotes = $this->app_model->get_arr($q);
    		foreach ($lotes as $l) {
    			$a = new Atom($l['id']);
    			echo 'setting lote: '.$a->name;
    			if(!empty($a->id)){
    				$a->set('atom_types_id',2);
    				$a->set_pcle(0,'propietario','CERRO RICO S.A.','Propietario',1);
    			}
    		}
    	}

    	//****** PARA CORRER DESPUES DE LAS 21

    	function update_type(){
    		$q  = "SELECT * FROM `atoms` WHERE atom_types_id = 2";
    		$lotes = $this->app_model->get_arr($q);
    		foreach ($lotes as $l) {
    			$a = new Atom($l['id']);
    			if(!empty($a->id) && !preg_match('/R_/' , $a->name)){
    				$pcles = $a->get_pcle('');
    				foreach ($pcles as $p) {
    					$a->set_pcle($p->id,$p->label,$p->value,$p->title,$p->vis_elem_type,2);
    				}
    			}
    		}
    	}

    	function test_saldo1(){
          $p=$this->input->post();
          // $elm = new Element($p['e_id']);
          $elm = new Element(5373);
          $dttl = intval($elm->get_deuda_total());
          $pttl = intval($elm->get_pagos_total());
          $cta_upc = intval($elm->get_cta_upc()['total']);
          $ctas_imputadas = intval($elm->get_imputaciones_ctas());
          $intereses_cobrados = intval($elm->get_imputaciones_intereses());

          $srv_arr = $elm->get_servicios();

          $srv_dttl = 0; $srv_pttl = 0; $srv_cta_upc = 0; $srv_ctas_imputadas = 0; $srv_intereses_cobrados = 0;

          foreach ($srv_arr as $srv) {
          	$s = new Element($srv['id']);
          	$srv_dttl += intval($s->get_deuda_total());
          	// $srv_pttl += $s->get_pagos_total();
          	$srv_cta_upc += intval($s->get_cta_upc()['total']);
          	$srv_ctas_imputadas += intval($s->get_imputaciones_ctas());
          	var_dump($srv_ctas_imputadas);
          	$srv_intereses_cobrados += intval($s->get_imputaciones_intereses());
          	var_dump($srv_intereses_cobrados);
          }

    		$ttl = $dttl + $srv_dttl;
    		$dx = $cta_upc + $srv_cta_upc;
    		$impt = $ctas_imputadas + $srv_ctas_imputadas;
    		$int = $intereses_cobrados + $srv_intereses_cobrados;
          echo "<br /> Deuda total :".$ttl;
          echo "<br /> Pagos total :".$pttl ;

          echo "<br /> deuda exigible:".$dx;
          echo "<br /> total cuotas imputadas:".$impt;
          echo "<br /> intereses cobrados:".$int;


         }



        function test_reporte_saldos(){

        	$elems = $this->app_model->get_arr("SELECT id,owner_id FROM elements WHERE elements_types_id = 1 LIMIT 200 ");
        	$xc= 0;
        	foreach ($elems as $elem) {
        		$lt_atm = new Atom($elem['owner_id']);
        		$rdo = preg_match('/R_/', $lt_atm->name);
        		if(!$rdo){
        			$saldo_ob = $this->cmn_functs->get_new_saldo($elem['id']);
        			$saldo = $saldo_ob['creditos_menos_intereses'] - $saldo_ob['tot_cargos_imputados'];
        			if($saldo < -100){
    	    			echo '<br /> lote: '.$lt_atm->name.'saldo: '.$saldo;
    	    			// var_dump($saldo_ob);
    	    			$xc ++;
    	    		}
        		}

        		// echo '<br /> lote: '.$lt_atm->name.'saldo: '.$saldo;

        	}
        	echo '<br/>saldos negativos: '. $xc.'total de lotes :'.count($elems);

        }

        function report_saldo_by_elem(){

        	$saldo_ob = $this->cmn_functs->get_new_saldo(4699);
        	$saldo = $saldo_ob['creditos_menos_intereses'] - $saldo_ob['tot_cargos_imputados'];
        	echo '<br /> saldo: '.$saldo;
        	var_dump($saldo_ob);

        }


    	// test saldo
    	function ts(){
    		$elm_id = 6075;
    		$lote_id = 1308;
    		$contab_asientos  = "SELECT * FROM `contab_asientos` WHERE tipo_asiento = 'INGRESOS' AND cuenta_imputacion_id = 191 and lote_id = {$lote_id}";
    		$pagos_rec = $this->app_model->get_arr($contab_asientos);

    	}

    	//  test preg_match
    	function tpm(){
    		$pvalue = 'ES-234';
    		$v = 'lote dd ES-234 oeirn_edss';

    		echo preg_match("/$pvalue/",$v);
    	}
    	//  TEST REPO EGRESOS
    	function t_repo_egresos_cajas(){
    		//$p = $this->input->post();
    		$p['fec_in'] = '01/07/2019';
    		$p['fec_out'] = '15/07/2019';

    		$fec_in = $this->cmn_functs->fixdate_ymd($p['fec_in'])." 00:00:00";
    		$fec_out = $this->cmn_functs->fixdate_ymd($p['fec_out'])." 23:59:59";
    		$q= "SELECT
    				g.fecha,
    				pnm.value as proveedor ,
    				cci.nombre concepto,
    				g.monto as monto,
    				g.observaciones as detalle,
    				caja.nombre as caja,
    				g.operacion_nro as nro_op,
    				g.id as op_id,
    				cnt.nombre as caja_pases
    				 FROM contab_asientos g
    		LEFT OUTER join atoms_pcles pnm ON pnm.atom_id = g.proveedor_id AND label LIKE 'nombre'
    		LEFT OUTER JOIN contab_cuenta_de_imputacion cci ON cci.id = g.cuenta_imputacion_id
    		LEFT OUTER JOIN contab_cuentas caja ON caja.id = g.cuentas_id
    		LEFT OUTER JOIN contab_cuentas cnt ON cnt.id = g.cta_contraparte_id
    		WHERE tipo_asiento = 'EGRESOS' AND g.fecha >= '{$fec_in}' AND g.fecha <= '{$fec_out}'";
    		$gd = $this->app_model->get_arr($q);
    		if(!$gd){exit;}
    		foreach ($gd as $key => $i ){
    			$q2 = "SELECT b.name as cc_name, cc.percent as porcentaje FROM `contab_cc_distrib` cc
    				LEFT OUTER JOIN atoms b on b.id = cc.barrio_id WHERE cc.asiento_id = {$i['op_id']} ";
    			$qdst = $this->app_model->get_arr($q2);
    			$gd[$key]['cc_distrib']=$qdst;

    		}
    	var_dump($gd);

    	}


    	//****   FUNCIONA OK QUEDA ON HOLD PARA VER SI VA ESTE O VA UNO DE CUOTAS EN MORA
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
    		// $lts = $this->app_model->get_obj("SELECT COUNT(*) as activos FROM `pcles` p JOIN atoms a on a.id = p.atom_id WHERE label = 'estado' AND value = 'ACTIVO'");
    		$items = [];
    		for ($i = 0; $i < count($prd); $i++) {
    			// $items[]=$prd[$i];
    			// $elm_activos = $this->get_elms_activos($prd[$i]);
    			$q = "SELECT
    					(CASE WHEN a.name != '' THEN concat(a.name , '  CUOTA LOTE ')  WHEN  asrv.name != '' THEN CONCAT(asrv.name ,'  ',serv.name) END) as 'detalle',
                        nc.value as 'cta_nro',
                        p.value as 'monto',
    					dp.value as 'fec_pago',
                        cnt.nombre as 'caja_cuenta',
                        opc.operacion_nro as'op_numero',


    					FROM `events` ev
                        LEFT OUTER JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
    					LEFT OUTER JOIN events_pcles nc on nc.events_id = ev.id AND nc.label = 'nro_cta'
                        LEFT OUTER JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
    					LEFT OUTER JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'
    					LEFT OUTER JOIN elements_pcles epp on epp.elements_id = ev.elements_id AND epp.label = 'prod_id'
                        LEFT OUTER JOIN elements_pcles eps on eps.elements_id = ev.elements_id AND eps.label = 'atom_id'
                        LEFT OUTER JOIN elements_pcles epsn on epsn.elements_id = ev.elements_id AND epsn.label = 'atom_name'
    					LEFT OUTER JOIN events_pcles eprec on eprec.events_id = ev.id AND eprec.label = 'recibo_nro'
    					LEFT OUTER JOIN contab_asientos  opc on opc.nro_comprobante = eprec.value
    					LEFT OUTER JOIN contab_cuentas cnt on cnt.id = opc.cuentas_id
    					LEFT OUTER JOIN elements elm2 on elm2.id = eps.elements_id
                        LEFT OUTER JOIN elements srvo on srvo.id = elm2.owner_id
                        LEFT OUTER JOIN elements_pcles srvop on srvop.elements_id = srvo.id AND srvop.label = 'prod_id'
                        LEFT OUTER JOIN atoms asrv on asrv.id = srvop.value
                        LEFT OUTER JOIN atoms a on a.id = epp.value
                        LEFT OUTER JOIN atoms serv on serv.id = eps.value

    					WHERE eps.value != '' AND STR_TO_DATE(dp.value,'%d/%m/%Y') >= STR_TO_DATE('{$indt}','%d/%m/%Y')   AND STR_TO_DATE(dp.value,'%d/%m/%Y') <= STR_TO_DATE('{$xdt}','%d/%m/%Y') ORDER BY STR_TO_DATE(dp.value,'%d/%m/%Y') ASC";
    			$m = $this->app_model->get_arr($q);
    			foreach ($m as $mv) {
    				if(!empty($mv['detalle'])){
    					$items[]=['Detalle'=>$mv['detalle'],'Cuota Nro'=>$mv['cta_nro'],'Monto'=>$mv['monto'],'Fecha de Pago'=>$mv['fec_pago'],'Caja / Cuenta'=>$mv['caja_cuenta'],'Nro. Operación'=>$mv['op_numero']];
    				}
    			}
    		}
    		if(count($items)>0){
    			// var_dump($items);
    			$file_name = Excel_features::create_file($items,'reporte_servicios_pagados');
    			$dnld = $this->cmn_functs->get_accion_icon('cloud-download','file_download',$file_name);
    			$res = [
    					'method'=>'ctas_pagas_srv',
    					'action'=>'response',
    					'data'=>$items,
    					'download'=>$dnld
    				];
    			$this->cmn_functs->resp('front_call',$res);
    		}else{

    			// echo 'error';
    			$res =[
    	          'tit'=>'Reporte cuotas pagadas ',
    	          'msg'=>'El rango de fechas seleccionadas no es valido',
    	          'type'=>'warning',
    	          'container'=>'modal',
    	          'win_close_method' => 'back'
    	        ];
            	$this->cmn_functs->resp('myAlert',$res);
    		}

    	}



    	function kill_events_from_element($d=-1){
    	    if(empty($d)){$d = $this->input->post('elm_id');}
    	    $e = new Element($d);
    		$owner_id = $e->owner_id;
    		$l = new Atom($owner_id);
    		if(empty($e->get_pcle('cli_id')->value) && empty($e->get_pcle('financ_id')->value)){
    			echo "<br> Killing elem #: ".$d ." owned by: ".$l->name;
          		$e->kill_events_all();
        	  	$e->kill();
    		}else{
    			echo "<br/> not killinkg elem #: ".$d;
    		}
    	      // $r =[
    	      //   'method'=>'get_elements',
    	      //   'sending'=>true,
    	      //   'data'=>['elm_id'=>$owner_id]
    	      // ];
    	      // echo json_encode(
    	      //   array(
    	      //     'callback'=>'front_call',
    	      //     'param'=>$r
    	      //   )
    	      // );
    	}

        function kill_selected_from_db(){
        	$q = "SELECT e.id as id,ep.value as ctas_post,ep2.value as financ_id,a.name as lote FROM `elements_pcles` ep
    		LEFT OUTER JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id and ep2.label LIKE 'financ_id'
    		LEFT OUTER JOIN elements_pcles ep3 on ep3.elements_id = ep.elements_id and ep3.label LIKE 'cli_id'
    		LEFT OUTER JOIN elements e on e.id = ep.elements_id
    		LEFT OUTER JOIN atoms a on a.id = e.owner_id
    		WHERE ep.label = 'cant_ctas_post_posesion'AND ep.value is NULL";
    		$k = $this->app_model->get_arr($q);
    		foreach ($k as $kd) {
    			$this->kill_events_from_element($kd['id']);
    		}
    		echo 'Done';
        }


    	// USADO PARA LA IMPORTACION DE COLUMNAS DESDE EXCEL
    	function check_xx(){
    		$h = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    		$x = 0;
    		$x2 = 0;
    		for ($i=0; $i < 140 ; $i++) {
    			if($i > count($h)){
    				// echo '<br> col:'.$h[$x].' '.$h[$i - count($h)*$x];
    				echo '<br> col:'.$x;//.' -->'.$i - count($h)*$x;
    				$t = ($i - (count($h)*($x+1)))-1;
    				echo '<br> t:'.$t;
    				echo '<br> col:'.$h[$x] . $h[$t];
    				if($i > count($h) && $i % count($h) == 0){$x ++;}


    				// $x2 = 0;
    				// echo '<br> x:'.$x;
    				// echo '<br> xv:'.(-$i*$x);
    				// echo '<br> xc:'. $i+(-$i*$x);
    				// echo '<br> col %:'.$h[$x2].' '.$h[$x];
    				// $x ++;
    				// $x2 ++;
    				// echo '<br> col:'.$h[($i-count($h))].' '.$h[($i-count($h))];
    			}else{
    				 if($i < count($h)){
    				 	echo '<br> col-->:'.$h[$i];
    				 }

    			}
    		}
    	}


    	function test_event(){

    		$tev = new Event(0,4,'10/06/2019');

    		var_dump($tev->get_pcle());
    		var_dump($tev->id);
    		$t2= new Event($tev->id);
    		var_dump($t2->get_pcle());
    	}

    	function compara_activos(){
    		$lotes = $this->cmn_functs->get_lotes_activos();
    		$cntrs = $this->app_model->get_arr("SELECT * FROM elements WHERE elements_types_id = 1 ");
    		$found_lotes = [];
    		echo '<pre>';
    		foreach ($cntrs as $c) {

    			$t = $this->app_model->get_arr("SELECT * FROM elements WHERE elements_types_id = 1 AND owner_id = {$c['owner_id']}");
    			if(count($t)>1)
    				print_r($t);
    			// $owner = $c['owner_id'];
    			// // ARR CON TODOS LOS LOTES ACTIVOS
    			// $lotes_id = array_map(function($i){return $i['id'];},$lotes);
    			// // found owner_id en lostes activos
    			// $found = array_search($owner, $lotes_id);

    			// if(count($found) > 0 ){
    			// 	echo '<br> lotes id'.$lotes_id[$found];
    			// 	// echo '<br> lotes id'.$lotes_id[$found];
    			// 	if(array_search($lotes_id[$found],$found_lotes)){
    			// 		echo 'DUPLICATED lote ID ';
    			// 	}else{

    			// 		$found_lotes[]=$lotes_id[$found];
    			// 	}

    			// 	echo '<br/> found lote in cntrs: '.$c['id'];
    			// }else{
    			// 	echo '<br/> BAD contract  -> '.$c['id']. '  owner->'.$owner;
    			// }
    		}
    		// echo '<br> count lotes:'.count($lotes);
    		// echo '<br> count contratos:'.count($cntrs);
    		// echo '<pre>';
    		// print_r($found_lotes);

    	}



    	function contratos_ok(){
    		$activos = $this->cmn_functs->get_lotes_activos();
    		foreach ($activos as $la) {

    				$cntr = new Element(0,'CONTRATO',$la['id']);
    				$lote = (new Atom($cntr->get_pcle('prod_id')->value))->name;
    				$cli = (new Atom($cntr->get_pcle('cli_id')->value))->name;
    				$fnn = (new Atom($cntr->get_pcle('financ_id')->value))->name;

    				echo '<br >--> Atm_id: '.$la['id'].' name: '.$la['name'].' -->Nom Lote:'.$lote.'  -->Cli: '.$cli.' -->Financ: '.$fnn. '<--';

    		}
    		echo '<br>Count: '.count($activos);
    	}


    	function t_contratos_activos(){
    		$t = $this->cmn_functs->get_contratos_activos();
    		echo '<pre>';
    		var_dump($t);
    	}

    	function contratos_all(){
    		$c = $this->app_model->get_arr("SELECT * FROM elements WHERE elements_types_id = 1 ");
    		$ind_r = 0;
    		$ind = 0;
    		foreach ($c as $x) {
    				$cntr = new Element($x['id']);
    				$lote = (new Atom($cntr->get_pcle('prod_id')->value))->name;
    				$cli = (new Atom($cntr->get_pcle('cli_id')->value))->name;
    				$fnn = (new Atom($cntr->get_pcle('financ_id')->value))->name;
    				$owner = (new Atom($x['owner_id']))->name;
    				$estado_lote = (new Atom($cntr->get_pcle('prod_id')->value))->get_pcle('estado')->value;

    				if(strstr($owner, 'R_') >- 1){
    					$ind_r ++;
    					echo '<br > RESCINDIDO --> elm_id: '.$x['id'].' name: '.$owner.' -->Nom Lote:'.$lote.'  -->Cli: '.$cli.' -->Financ: '.$fnn.' -->Estado Lote:'.$estado_lote.'<--';
    				}else{
    					$ind ++;
    					echo '<br > ACTIVO --> elm_id: '.$x['id'].' name: '.$owner.' -->Nom Lote:'.$lote.'  -->Cli: '.$cli.' -->Financ: '.$fnn.' -->Estado Lote:'.$estado_lote.'<--';
    				}

    		}
    		echo '<br>Count activos: '. $ind;
    		echo '<br>Count rescindidos: '. $ind_r;
    	}

    	function contratos_bad(){

    		$c = $this->app_model->get_arr("SELECT * FROM elements WHERE elements_types_id = 1 ");
    		$count = 0;
    		$i = 0;
    		foreach ($c as $x) {
    			$i++;
    			$cntr = new Element($x['id']);
    			$lote = (new Atom($cntr->get_pcle('prod_id')->value))->name;
    			$cli = (new Atom($cntr->get_pcle('cli_id')->value))->name;
    			$fnn = (new Atom($cntr->get_pcle('financ_id')->value))->name;
    			$owner = (new Atom($x['owner_id']))->name;

    			echo '<br/>Checking id:'.$x['id'];
    			if(!empty($cntr)){
    				echo '<br/>Checking element id:'.$cntr->id;

    				$estado_lote = (new Atom($cntr->get_pcle('prod_id')->value))->get_pcle('estado')->value;
    				echo 'estado: '.$estado_lote;
    				if(empty($estado_lote) || $estado_lote == 'NO A LA VENTA' || $estado_lote == "DISPONIBLE"){
    					$count ++;
    					echo '<br >--> elm_id: '.$x['id'].' name: '.$owner.' -->Nom Lote:'.$lote.'  -->Cli: '.$cli.' -->Financ: '.$fnn.' -->Estado Lote:'.$estado_lote.'<--';
    					$evs = $cntr->get_events_all();
    					if(strstr($lote,'R_')> -1){
    						echo '<br/>tipo '. $cntr->type_id;
    						echo '<br/>name tipo '. $cntr->type;
    						// $cntr->set('type',"RESCISION");
    						$cntr->set('elements_types_id',5);
    					}

    					else if(empty($fnn) && count($evs) == 0){
    						echo 'killing'. $cntr->id;
    						$cntr->kill();
    					}

    					echo '<br/> eventos: '.count($evs);
    				}
    			}
    		}
    		echo '<br/>count:'.$count ;

    		echo '<br/>index:'.$i ;
    	}

    	function kill_cntr(){
    		$g = $this->input->get();
    		$cntr = new Element($g['id']);
    		$p = $cntr->get_pcle();
    		$evs = $cntr->get_events_all();
    		if(count($evs) == 0){
    			echo 'killing'. $cntr->id;
    			$cntr->kill();
    		}
    	}

    		function compare_l2(){
    			// *************** TODOS LOS CONTRATOS
    			$els1 = $this->app_model->get_arr("
    			SELECT
    			e.id,
    			epl.value AS prod_id,
    			epc.value AS cli_id,
    			b.value as barrio
    			FROM elements e
    			JOIN elements_pcles epl ON epl.elements_id = e.id AND epl.label = 'prod_id'
    			JOIN elements_pcles epc ON epc.elements_id = e.id AND epc.label = 'cli_id'
    			-- JOIN elements_pcles epct ON epct.elements_id = e.id AND epct.label = 'cant_ctas_post_posesion'
    			join atoms_pcles b ON b.atom_id = epl.value AND b.label LIKE 'emprendimiento'
    			WHERE elements_types_id = 1  ORDER BY e.id");
    		// AND epct.value > 0

    			// CONTRATOS CON FINANC LPT CANT_CTAS_POST_POSESION
    			$els2 = $this->app_model->get_arr("
    			SELECT
    			e.id,
    			epl.value AS prod_id,
    			epc.value AS cli_id,
    			b.value as barrio
    			FROM elements e
    			JOIN elements_pcles epl ON epl.elements_id = e.id AND epl.label = 'prod_id'
    			JOIN elements_pcles epc ON epc.elements_id = e.id AND epc.label = 'cli_id'
    			JOIN elements_pcles epct ON epct.elements_id = e.id AND epct.label = 'cant_ctas_post_posesion'
    			join atoms_pcles b ON b.atom_id = epl.value AND b.label LIKE 'emprendimiento'
    			WHERE elements_types_id = 1 AND epct.value > 0  ORDER BY e.id");

    			// lotes activos

    			$activos = $this->cmn_functs->get_lotes_activos();

    			echo '<pre>';

    			echo '<br>activos count:'.count($activos);
    			echo '<br>all contratos count:'.count($els1);
    			echo '<br>financ ant_y_ctas  count:'.count($els2);
    			exit;


    			$arr_comp = array_map(function($i){return $i['id'];},$els2);
    			// var_dump($els1);
    			// var_dump($els2);
    			// var_dump($arr_comp);
    			// exit;
    			$nf= 0;
    			foreach ($els1 as $v1) {
    				echo '<br/>searching  elm1:'.$v1['id'];
    				$found = array_search($v1['id'], $arr_comp);
    				if(!empty($found)){
    					echo '<br/>Found -->';//. $els2[$found]['id'];
    					$x = new Element($els2[$found]['id']);
    					$y = $x->get_pcle('prod_id')->value;
    					$z = $x->get_pcle('cli_id')->value;
    					echo '<br> Lote:'. (new Atom($y))->name;
    					echo '<br> cli:'. (new Atom($z))->name;
    				}else{

    					echo '<br/>NOT found ';//.$v1['id'];
    					$x = new Element($v1['id']);
    					$y = $x->get_pcle('prod_id')->value;
    					$z = $x->get_pcle('cli_id')->value;
    					echo '<br> Lote:'.(new Atom($y))->name;
    					echo '<br> cli:'.(new Atom($z))->name;
    				}
    			}

    			echo '<br/> total els1:'.count($els1);
    			echo '<br/> total els2:'.count($els2);
    			// echo '<br/> total not found:'.$nf;
    		}



    		function compare_lotes_rev(){


    		$get_lotes_financ_normal = $this->app_model->get_arr("SELECT
    			l.name as cod_lote,
    			f.name as financ_name
    			FROM `elements_pcles` ep1
    			LEFT OUTER JOIN elements_pcles ep2 on ep2.elements_id = ep1.elements_id AND ep2.label = 'financ_id'
    			LEFT OUTER JOIN elements_pcles ep3 on ep3.elements_id = ep1.elements_id AND ep3.label = 'prod_id'
    			LEFT outer join atoms f on f.id = ep2.value
    			LEFT outer join atoms l on l.id = ep3.value
    			where ep1.label = 'cant_ctas_post_posesion' AND ep1.value > 0 ");

    		$lotes_activos = $this->app_model->get_arr("SELECT a.name as name FROM `atoms`a
    			LEFT OUTER join atoms_pcles p on p.atom_id = a.id AND p.label = 'estado'
    			left outer join elements_pcles ep on ep.label = 'prod_id' AND ep.value = a.id
    			where a.atom_types_id = 2 AND p.value = 'ACTIVO' OR p.value = 'CANJE' OR p.value = 'CEDIDO'");

    		$arr_activos = array_map(function($i){return trim($i['name']);}, $lotes_activos);

    		// $lf = array_map(function($i){return trim($i['name']);}, $lotes_financiacion_normal);

    		// print_r($lf);

    		foreach ($lotes_financiacion_normal as $lf) {
    			// var_dump(array_search($la['name'], $lf));
    			echo '<br/><br/> checking :'.$lf['name'];
    			$x = array_search($lf['name'], $arr_activos);
    			if($x){
    				echo '<br>---- LOTE CON FINANC ---';
    				$conf = new Atom(0,'LOTE',$lf['name']);
    				$contrato = new Element(0,'CONTRATO',$conf->id);
    				$financ = new Atom($contrato->get_pcle('financ_id')->value);
    				echo '<br/> Lote :'.$conf->name. ' financ:'. $financ->name;
    			}else {
    				echo '<br> -- no financ';
    				$ncf = new Atom(0,'LOTE',$lf['name']);
    				$ncc = new Element(0,'CONTRATO',$ncf->id);
    				$fnc = new Atom($ncc->get_pcle('financ_id')->value);
    				echo '<br/> Lote :'.$ncf->name. ' financ:'. $fnc->name;
    			}

    		}
    		 echo '<br> total lotes activos:'.count($lotes_activos);

    		 echo '<br><br> total lotes FNormal:'.count($lotes_financiacion_normal);
    		 echo '<br><br> total arr_activos :'.count($arr_activos);

    	}



    	function compare_lotes(){
    		$lotes_financiacion_normal = $this->app_model->get_arr("SELECT l.name FROM `elements_pcles` ep1
    			LEFT OUTER JOIN elements_pcles ep2 on ep2.elements_id = ep1.elements_id AND ep2.label = 'financ_id'
    			LEFT OUTER JOIN elements_pcles ep3 on ep3.elements_id = ep1.elements_id AND ep3.label = 'prod_id'
    			LEFT outer join atoms f on f.id = ep2.value
    			LEFT outer join atoms l on l.id = ep3.value
    			where ep1.label = 'cant_ctas_post_posesion' AND ep1.value > 0");

    		$lotes_activos = $this->app_model->get_arr("SELECT a.name as name FROM `atoms`a
    			LEFT OUTER join atoms_pcles p on p.atom_id = a.id AND p.label = 'estado'
    			left outer join elements_pcles ep on ep.label = 'prod_id' AND ep.value = a.id
    			where a.atom_types_id = 2 AND p.value = 'ACTIVO' OR p.value = 'CANJE' OR p.value = 'CEDIDO'");

    		$lf = array_map(function($i){return trim($i['name']);}, $lotes_financiacion_normal);

    		print_r($lf);

    		foreach ($lotes_activos as $la) {
    			// var_dump(array_search($la['name'], $lf));
    			echo '<br/><br/> checking :'.$la['name'];
    			$x = array_search($la['name'], $lf);
    			if($x){
    				echo '<br>---- LOTE CON FINANC ---';
    				$conf = new Atom(0,'LOTE',$lf[$x]);
    				$contrato = new Element(0,'CONTRATO',$conf->id);
    				$financ = new Atom($contrato->get_pcle('financ_id')->value);
    				echo '<br/> Lote :'.$conf->name. ' financ:'. $financ->name;
    			}else {
    				echo '<br> -- no financ';
    				$ncf = new Atom(0,'LOTE',$la['name']);
    				$ncc = new Element(0,'CONTRATO',$ncf->id);
    				$fnc = new Atom($ncc->get_pcle('financ_id')->value);
    				echo '<br/> Lote :'.$ncf->name. ' financ:'. $fnc->name;
    			}

    		}
    		 echo '<br> total lotes activos:'.count($lotes_activos);

    		 echo '<br><br> total lotes FNormal:'.count($lotes_financiacion_normal);
    		 echo '<br><br> total lf:'.count($lf);

    	}


    	/*

     ********* QUERY TODOS LOS CONTRATOS, SUS NOMBRE DE LOTE Y CLIENTE

    	SELECT e.id as element, a.name as lote,acl.name as cli,af.name as financ FROM `elements`e
    left join atoms a on a.id =  e.owner_id
    LEFT JOIN elements_pcles ept on ept.elements_id = e.id and ept.label = 'cli_id'
    LEFT JOIN atoms acl on acl.id = ept.value
    LEFT JOIN elements_pcles epf on epf.elements_id = e.id and epf.label = 'financ_id'
    LEFT JOIN atoms af on af.id = epf.value
    WHERE elements_types_id = 1
    	*/

    	function fix_financ_ctaspostpos(){
    		$q= $this->app_model->get_arr("SELECT
    			e.id as elm_id,
    			a.name as lote,
    			acl.name as cli,
    			af.name as financ_name,
    			af.id as financ_id
    			FROM `elements` e
    			JOIN atoms a on a.id =  e.owner_id
    			JOIN elements_pcles ept on ept.elements_id = e.id and ept.label = 'cli_id'
    			JOIN atoms acl on acl.id = ept.value
    			JOIN elements_pcles epf on epf.elements_id = e.id and epf.label = 'financ_id'
    			JOIN atoms af on af.id = epf.value
    			WHERE elements_types_id = 1 ");

    		foreach ($q as $c) {
    			$e = new Element($c['elm_id']);
    			if(!empty($e)){
    				if(strstr($c['financ_name'], '36 Ctas') > -1){
    					$e->set_pcle(0,'cant_ctas_post_posesion',120);
    				}else if(strstr($c['financ_name'], '120 Cuotas') > -1){
    					$e->set_pcle(0,'cant_ctas_post_posesion',120);
    				}
    				else if(strstr($c['financ_name'], '48 Ctas') > -1){
    					$e->set_pcle(0,'cant_ctas_post_posesion',150);
    				}
    			}
    		}
    	}


    	//  checkea que no haya duplicados , si encunetra el servicio devuelve el id
    	function search_service($elm_id,$srv_atm_id){
    		$q= $this->app_model->get_arr("SELECT id FROM elements WHERE owner_id = ".$elm_id);
    		if(empty($q)){return false;}
    		foreach ($q as $f){
    			$felm = new Element($f['id']);
    			if(!empty($felm) && $felm->type == "SERVICIO"){
    				if($felm->get_pcle('atom_id')->value == $srv_atm_id){
    					return $felm->id;
    				}
    			}
    		}
    		return false;
    	}

    	function deuda_gral(){
    		$arch = 'Deuda-General-2019.xls';

    		$t = $this->excel_to_arr($arch);


    			for ($i = 2;$i <= count($t); $i++ ) {

    				$lote = new Atom(0,'LOTE',$t[$i]['B']);

    				if(empty($lote)){
    					echo '<br>lote not found';
    					var_dump($t[$i]['B']);
    					exit();
    				}

    				$elm = new Element(0,'CONTRATO',$lote->id);
    				if(empty($elm)){echo 'no elment for: '. $lote->id; exit();}

    				$srvc_atom = new Atom($t[$i]['C']);

    				$found_srv = $this->search_service($elm->id,$t[$i]['C']);

    				if(!$found_srv){
    					// echo '<br>SETTING service';
    					// echo('<br/>'.$srvc_atom->name);
    					// echo '<br/><pre>';
    					// print_r($t[$i]);
    					$srv = new Element(-1,'SERVICIO',$elm->id);
    					$srv->set_pcle(0,'atom_id',$t[$i]['C']);
    					$srv->set_pcle(0,'fec_ini',$t[$i]['A']);
    					$srv->set_pcle(0,'financ_id',9386);
    					$srv->set_pcle(0,'atom_name',$srvc_atom->name);
    					$srv->set_pcle(0,'estado','normal');


    					$ev = new Event(0,8,$this->cmn_functs->fixdate_ymd($t[$i]['A']),$srv->id,1);
    					$ev->set_pcle(0,'monto_cta',$t[$i]['D']);
    					$ev->set_pcle(0,'fecha_vto',$t[$i]['A']);
    					$ev->set_pcle(0,'estado','a_pagar');
    					$ev->set_pcle(0,'nro_cta','Cuota 1 de 1');

    				}else{

    					echo '<br>found service - SKIPING ';
    				}
    			echo '<br> Done lote : '.$t[$i]['B'];

    		}
    	}







    	function deuda_gas(){
    		$arch = 'DEUDA_GAS.xls';

    		$t = $this->excel_to_arr($arch);


    			for ($i = 2;$i <= count($t); $i++ ) {

    				$lote = new Atom(0,'LOTE',$t[$i]['A']);

    				if(empty($lote)){
    					echo '<br>not found lote';
    					var_dump($t[$i]['A']);
    					exit();
    				}

    				$elm = new Element(0,'CONTRATO',$lote->id);
    				if(empty($elm)){echo 'no elm for: '.$lote->id;exit();}

    				$found_srv = $this->search_service($elm->id,12168);

    				if(!$found_srv){
    					$srv = new Element(-1,'SERVICIO',$elm->id);
    					echo '<br>NOT found service';
    					$srv->set_pcle(0,'atom_id',12168);
    					$srv->set_pcle(0,'fec_ini',$t[$i]['B']);
    					$srv->set_pcle(0,'financ_id',9386);
    					$srv->set_pcle(0,'atom_name','GAS');
    					$srv->set_pcle(0,'estado','normal');
    					$srv->set_pcle(0,'estado_deuda',0);
    					$srv->set_pcle(0,'estado','normal');


    					$ev = new Event(0,8,$this->cmn_functs->fixdate_ymd($t[$i]['D']),$srv->id,1);
    					$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
    					$ev->set_pcle(0,'fecha_vto',$t[$i]['D']);
    					$ev->set_pcle(0,'estado','a_pagar');
    					$ev->set_pcle(0,'nro_cta','Cuota 1 de 1');

    				}else{

    					echo '<br>found service - skip ';
    				}
    			echo '<br> Done lote : '.$t[$i]['A'];

    		}
    	}


    	function test_validate_dia_pago(){
    		// $e = new Element(1752);
    		// $c = $e->get_events_first_future();

    		// 	$c = new Event($ct['events']['id']);
      		//	$t = $this->validate_dt_cta($c['events']['fecha']);

    		// // $this->validate_dt_cta($c['events']['fecha']);
    		// // var_dump($this->validate_dt_cta('2019-05-01'));
    		// var_dump($c);
    		// var_dump($this->dif_mes($c['events']['fecha']));
    		echo 'pago en fecha';
    		$x = $this->validate_dia_pago('10/03/2019','20/03/2019');
    		var_dump($x);
    		echo 'pago  fuera de termino';
    		$x = $this->validate_dia_pago('10/03/2019','26/03/2019');
    		var_dump($x);

    		echo 'pago adelantado';
    		$x = $this->validate_dia_pago('10/03/2019','26/02/2019');
    		var_dump($x);

    	}

    	function validate_dt_cta($date){
    		$ddt = new DateTime($date);
          	$today = new DateTime('2019-05-30');
          	$dif_date = $today->diff($ddt);
          	if($ddt->format('m') <= $today->format('m') && $ddt->format('Y') == $today->format('Y')){
          		return true;
          	}else{
          		return false;
          	}
        }

    	function fut(){
    		$ctr = new Element(6075);
    		$cf = $ctr->get_cobranza_futura();
    		$fut_ev = $ctr->get_events(8,'a_pagar');
    		echo '<pre>';
    		var_dump($cf);
    		var_dump($fut_ev);

    	}

    	function dif_mes($date){
          $ddt = new DateTime($date);
          $today = new DateTime();
          $dif_date = $today->diff($ddt);
          return $dif_date;
        }


        function validate_dia_pago($fv,$fp){
        	$fv = $this->cmn_functs->fixdate_ymd($fv);
        	$fp = $this->cmn_functs->fixdate_ymd($fp);
        	$dt_fv = new DateTime($fv);
        	$dt_fp = new DateTime($fp);
        	$dif_vp = $dt_fv->diff($dt_fp);
        	if($dif_vp->invert === 1  && $dif_vp->days > 10){
        		return 'adelantada';
        	}
        	if($dif_vp->invert === 0  && $dif_vp->days > 15){
        		return 'p_ftrm';
        	}else{
        		return 'pagado';
        	}
        }



    	// TEST SREPORTE CTAS PAGAS POR MES
    	function repo_ctas_pagas_xmes_xcli(){
    		$indt = '01/05/2019';
    		$xdt = date('d/m/Y');
    		// $p contiene cada mes en formato '%/03/2019'
     		$p = $this->get_period($indt,$xdt);


     	// 	$months = [];

    		// $lts = $this->app_model->get_obj("SELECT COUNT(*) as activos FROM `pcles` p JOIN atoms a on a.id = p.atom_id WHERE label = 'estado' AND value = 'ACTIVO'");
    		// for ($i = 0; $i < count($p); $i++) {
    		// 	$q = "SELECT
    		// 			ev.elements_id as ctr_id,
    		// 			a.name as lote,
    		// 			p.value as monto,
    		// 			dp.value as fecha,
    		// 			COUNT(ev.elements_id) as ctas_pagadas,
    		// 			SUM(p.value)as total
    		// 			FROM `events`ev
    		// 			JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
    		// 			JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
    		// 			JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'
    		// 			join elements_pcles epp on epp.elements_id = ev.elements_id AND epp.label = 'prod_id'
    		// 			join atoms a on a.id = epp.value
    		// 			WHERE dp.value  like '%{$p[$i]}' GROUP BY ev.elements_id WITH ROLLUP ";
    		// 	$m = $this->app_model->get_arr($q);
    		// 	$t = count($m);
    		// 	$la = intval($lts->activos);
    		// 	$months[] = [
    		// 		'tit_month'=>$this->get_month_year($p[$i]),
    		// 		'ttl_activos'=>$la,
    		// 		'ttl_cl_con_pagos'=>$t,
    		// 		'percent_cl_con_pagos'=>$t/$la*100,
    		// 		'ttl_ctas_pagadas'=>end($m)['ctas_pagadas'],
    		// 		'ctas_pagas_x_lt_activo'=>intval(end($m)['ctas_pagadas'])/$la,
    		// 		'ctas_pagas_x_lt_pago'=>intval(end($m)['ctas_pagadas'])/$t,
    		// 		'ttl_pagos'=>end($m)['total'],
    		// 		'data'=>$m

    		// 	];
    		// }

    		// echo "<pre/>";
    		// print_r($months);


    		echo "<pre/>";

    		var_dump($p);

    		// var_dump($lts_actv);

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

    /*
     query ctas pagas
     SELECT
    ev.elements_id,
    a.name,
    ev.date,
    ev.ord_num,
    p.events_id as event_id,
    p.value as monto,
    dp.value as fecha,
    st.value as termino,
    COUNT(p.value) as nro,
    count(ev.elements_id) as ctrs,
    SUM(p.value)as total
    FROM `events`ev
    JOIN events_pcles st on st.events_id = ev.id AND st.label = 'estado' AND st.value LIKE 'p%'
    JOIN events_pcles p on p.events_id = ev.id AND p.label = 'monto_pagado'
    JOIN events_pcles dp on dp.events_id = ev.id AND dp.label = 'fec_pago'
    join elements_pcles epp on epp.elements_id = ev.elements_id AND epp.label = 'prod_id'
    join atoms a on a.id = epp.value

    WHERE dp.value  like '%/05/2019' GROUP BY ev.elements_id HAVING COUNT(ev.elements_id) >1


    */



	function th(){
		$a = new Atom(0,'RESCISION',1002);
		var_dump($a);
		exit;
		$rh = new Historial($a->get_pcle('hist_id')->value);

		var_dump($rh->get_event_last());

	}



	function check_historial($l){
      $rscn_obj = 0;
      $stt = 'NORMAL';
      if($l->get_pcle('hist_id')->value == 0 || empty($l->get_pcle('hist_id'))){

        var_dump($l);
        exit();

        $stt = 'NORMAL';
      }else{
        $h = new Historial($l->get_pcle('hist_id')->value);
        $ev = $h->get_event_last();
        $stt = $ev->get_pcle('state')->value;
        if($stt === "RESCINDIDO"){
          if($ev->get_pcle('accion')->value === 'rescision_id'){
            $rscn_obj = new Atom($ev->get_pcle('detalle')->value);
          }
        }
      }
      return ['state'=>$stt,'rscn_obj'=>$rscn_obj];
    }


/* mapa infoclientes old
	LOTE  A
	ESQUINA	 B
	FRENTE C
	FONDO  D
	M2 TOTALES	E
	EXPEDIENTE	F
	PARTIDA	G
	CIRC H
	MANZANA I
	PARCELA	J
	CALLE	K
	ALTURA	L
	EMPRENDIMIENTO	M

	APELLIDO TITULAR 1	N
	NOMBRE TITULAR 1 O
	APELLIDO TITULAR 2	P
	NOMBRE TITULAR 2 Q
	DOMICILIO TITULAR 1  R
	DOMICILIO TITULAR 2	  S
	LOCALIDAD	T
	CP	U
	DNI 1 V
	DNI 2	W
		CUIL 1	X
		CUIL 2	Y
		TELEFONO  Z
		CELULAR	 AA
		OCUPACION	AB
	MAIL	 AC


	VENDEDOR   AD
	1-12	AE
	13-18	AF
	19-24	AG
	25-30	AH
	31 O MAS AI
	SCANEADO	AJ
	RESERVA	  AK
	FECHA BOLETO	AL
	REGLAMENTO	AM
	COPIA DNI	AN
	UBICACION	AO
	POSESION	AP
	MANTENIMIENTO  AQ
	FECHA SELLADO	AR
	MONTO DE SELLADO	 AS
	ESTADO	   AT
	OBSERVACIONES AU
	BENEFICIARIO	 AV
	DNI	 AW
	TELEFONO	 AX
	DIRECCION	AY
	LOCALIDAD	AZ
	MOTIVO DE CESION  BA
*/


//******* SUB FUNCS DE LOAD INFOCLIENTES
	function set_rscn($xl_line){
		$x =$xl_line;
		$r = new atom(0,'RESCISION',$x['A']);
		echo '<br/>RESCISION-->' . $x['A'];
		return $r;
	}

	function set_lote($xl_line,$l){
		$x = $xl_line;
			// DATOS DEL LOTE


		if($x['AF'] != 'RESCINDIDO'){
			echo '-->  setting...' . $x['A'].' estado: '.$x['AF'];
			// var_dump($x);
			// $l->set_pcle(0,'name',$x['A'],'Nombre');
			// $l->set_pcle(0,'emprendimiento',$x['M'],'Emprendimiento');
			// $l->set_pcle(0,'metros2',$x['E'],'Metros Cuadrados');
			// $l->set_pcle(0,'frente',$x['C'],'Frente');
			// $l->set_pcle(0,'fondo',$x['D'],'Fondo');
			// $l->set_pcle(0,'esquina',$x['B']);
			// $l->set_pcle(0,'expediente',$x['F']);
			// $l->set_pcle(0,'partida',$x['G']);
			// $l->set_pcle(0,'circ',$x['H']);
			// $l->set_pcle(0,'manzana',$x['I']);
			// $l->set_pcle(0,'parcela',$x['J']);
			// $l->set_pcle(0,'calle',$x['K']);
			// $l->set_pcle(0,'altura',$x['L']);
			// $l->set_pcle(0,'propietario',$x['N']);
			// $l->set_pcle(0,'estado',$x['AF']);
		}
	}


	function set_titular($xl_line){
		$x = $xl_line;
		$cl_tit_id = $this->app_model->get_obj("SELECT * FROM atoms WHERE atom_types_id = 1 AND name LIKE '%".$x['P']." ".$x['Q']."%' ");
		echo '<br/> TITULAR-->';
		// CLIENTE TITULAR
		if(!empty($cl_tit_id)){
			$cl_tit = new Atom($cl_tit_id->id);
		}else{
			$cl_tit = new Atom(0,'CLIENTE',$x['P']." ".$x['Q']);
		}
		$name = $x['P']." ".$x['Q'];
		echo 'setting name :'. $name;
		if(!empty($name)){
			$cl_tit->set('name',$name);
		}
		$cl_tit->set_pcle(0,'nombre',$x['Q']);
		$cl_tit->set_pcle(0,'apellido',$x['P']);
		$cl_tit->set_pcle(0,'dni',$x['X']);
		$cl_tit->set_pcle(0,'cuit_cuil',$x['Z']);
		$cl_tit->set_pcle(0,'ocupacion',$x['AD']);
		$cl_tit->set_pcle(0,'categoria','titular');
		$cl_tit->set_pcle(0,'domicilio',$x['T']);
		$cl_tit->set_pcle(0,'codigo_postal',$x['W']);
		$cl_tit->set_pcle(0,'localidad',$x['V']);
		$cl_tit->set_pcle(0,'telefono',$x['AB']);
		$cl_tit->set_pcle(0,'celular',$x['AC']);
		$cl_tit->set_pcle(0,'email',$x['AE']);
		return $cl_tit;
	}


	function set_cotit($xl_line){
		$x = $xl_line;
		$cl_cotit_id = $this->app_model->get_obj("SELECT * FROM atoms WHERE atom_types_id = 1 AND name LIKE '%".$x['R']." ".$x['S']."%'");
		echo '<br/> CO TITULAR-->';
		// var_dump($cl_cotit_id->name);

		if(!empty($cl_cotit_id)){
			$cl_cotit = new Atom($cl_cotit_id->id);
		}else{
			$cl_cotit = new Atom(0,'CLIENTE',$x['R']." ".$x['S']);
		}

		$n = $x['R']." ".$x['S'];
		echo 'setting cotit '.$n. ' id:'.$cl_cotit->id;
		$cl_cotit->set('name',$n);
		$cl_cotit->set_pcle(0,'nombre',$x['S']);
		$cl_cotit->set_pcle(0,'apellido',$x['R']);
		$cl_cotit->set_pcle(0,'dni',$x['Y']);
		$cl_cotit->set_pcle(0,'cuit_cuil',$x['AA']);
		$cl_cotit->set_pcle(0,'categoria','co_titular');
		$cl_cotit->set_pcle(0,'domicilio',$x['U']);
		return $cl_cotit;
	}

	function set_beneficiario($xl_line){
		$x = $xl_line;
		$cl_bnf = $this->app_model->get_obj("SELECT * FROM atoms WHERE atom_types_id = 1 AND name LIKE '".$x['AY']."'");
		if(!empty($cl_bnf)){
			$bnf = new Atom($cl_bnf->id);
		}else{
			$bnf = new Atom(0,'BENEFICIARIO',$x['AY']);
		}

		echo 'setting benef:'.$x['AY']. ' id:'.$bnf->id;
		$bnf->set('name',$x['AY']);
		$bnf->set_pcle(0,'nombre',$x['AY']);
		$bnf->set_pcle(0,'dni',$x['AZ']);
		$bnf->set_pcle(0,'categoria','beneficiario');
		$bnf->set_pcle(0,'telefono',$x['BA']);
		$bnf->set_pcle(0,'domicilio',$x['BB']);
		$bnf->set_pcle(0,'localidad',$x['BC']);
		$bnf->set_pcle(0,'motivo_de_cesion',$x['BD']);
		return $bnf;
	}


	function load_infoclientes_ES(){
		set_time_limit(0);
		$t = $this->excel_to_arr('inflo-clientes-ES-may_24.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {

			echo "<br/>lote: ".$t[$i]['A'];

			if($t[$i]['A'] != ''){
				$lt = new Atom(0,'LOTE',$t[$i]['A']);

				$this->set_lote($t[$i],$lt);

				// SI ES ACTIVO / CANJE / CEDIDO DEBE TENER CONTRATO
				if($t[$i]['AF'] == "ACTIVO" || $t[$i]['AF'] == "CANJE"|| $t[$i]['AF'] == "CEDIDO"){
					//**** EL CONTRATO
					$e = new Element(0,'CONTRATO',$lt->id);

					//  atom del cliente del contrato
					$atmcli =  new Atom($e->get_pcle('cli_id')->value);
					// var_dump($atmcli->name);

					if(!empty($atmcli->name)){
						//***  EL CLI_ID EN EL CONTRATO ES UN VALID ATOM
						echo '  found cli: '.$atmcli->name.'   id: '.$atmcli->id;

						$q = $this->app_model->get_arr("SELECT * FROM atoms where name = '{$atmcli->name}' AND id != '{$atmcli->id}' ");
						//  clientes duplicados
						if(count($q)>0){
							echo '<br>clientes duplicados to kill:<br/>';
							print_r($q);
							foreach ($q as $tk) {
								$this->set_cli_anulado($tk['id']);
							}
						}
					}else{
						echo "************ CONTRATO SIN CLI_ID !!";
					}
					// actualizar datos del cliente titular
					// si en excel hay cotitular buscar en element un cotitular id
					// CLIENTE CO-TITULAR
					if(!empty($t[$i]['R']) && !empty($t[$i]['S'])){
						$cl_cotit = $this->set_cotit($t[$i]);
					}
					// CLIENTE BENEFICIARIO
					if(!empty($t[$i]['AY'])){
						$bnf = $this->set_beneficiario($t[$i]);
					}
					// ACTUALIZAR DATOS DEL CONTRATO
					echo 'actualizando contrato..';
					$e->set_pcle(0,'prod_id',$lt->id);
					$e->set_pcle(0,'titular_id',$atmcli->id);
					$e->set_pcle(0,'cotitular_id',(!empty($cl_cotit))?$cl_cotit->id:0);
					// $e->set_pcle(0,'cli_id',$cl_tit->id);
					$e->set_pcle(0,'beneficiario_id',(!empty($bnf))?$bnf->id:0);
					$e->set_pcle(0,'vendedor',$t[$i]['AG']);
					$e->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));
					$e->set_pcle(0,'escaneado',$t[$i]['AM']);
					$e->set_pcle(0,'reserva',$t[$i]['AN']);
					$e->set_pcle(0,'fecha_boleto',$t[$i]['AO']);
					$e->set_pcle(0,'reglamento',$t[$i]['AP']);
					$e->set_pcle(0,'copia_dni',$t[$i]['AQ']);
					$e->set_pcle(0,'ubicacion',$t[$i]['AR']);
					$e->set_pcle(0,'posesion',$t[$i]['AS']);
					$e->set_pcle(0,'fecha_sellado',$t[$i]['AU']);
					$e->set_pcle(0,'monto_sellado',$t[$i]['AV']);
					$e->set_pcle(0,'observaciones',$t[$i]['AX']);
				}
				else{
					echo '  sin contrato '.$t[$i]['A'];
				}
			}
		}
		echo 'DOne...';
	}




	function load_infoclientes_GA(){
		set_time_limit(0);
		$t = $this->excel_to_arr('inflo-clientes-GA-may_24.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {

			echo "<br/>lote: ".$t[$i]['A'];

			if($t[$i]['A'] != ''){
				$lt = new Atom(0,'LOTE',$t[$i]['A']);

				$this->set_lote($t[$i],$lt);

				// SI ES ACTIVO / CANJE / CEDIDO DEBE TENER CONTRATO
				if($t[$i]['AF'] == "ACTIVO" || $t[$i]['AF'] == "CANJE"|| $t[$i]['AF'] == "CEDIDO"){
					//**** EL CONTRATO
					$e = new Element(0,'CONTRATO',$lt->id);

					//  atom del cliente del contrato
					$atmcli =  new Atom($e->get_pcle('cli_id')->value);
					// var_dump($atmcli->name);

					if(!empty($atmcli->name)){
						//***  EL CLI_ID EN EL CONTRATO ES UN VALID ATOM
						echo '  found cli: '.$atmcli->name.'   id: '.$atmcli->id;

						$q = $this->app_model->get_arr("SELECT * FROM atoms where name = '{$atmcli->name}' AND id != '{$atmcli->id}' ");
						//  clientes duplicados
						if(count($q)>0){
							echo '<br>clientes duplicados to kill:<br/>';
							print_r($q);
							foreach ($q as $tk) {
								$this->set_cli_anulado($tk['id']);
							}
						}
					}else{
						echo "************ CONTRATO SIN CLI_ID !!";
					}
					// actualizar datos del cliente titular
					// si en excel hay cotitular buscar en element un cotitular id
					// CLIENTE CO-TITULAR
					if(!empty($t[$i]['R']) && !empty($t[$i]['S'])){
						$cl_cotit = $this->set_cotit($t[$i]);
					}
					// CLIENTE BENEFICIARIO
					if(!empty($t[$i]['AY'])){
						$bnf = $this->set_beneficiario($t[$i]);
					}
					// ACTUALIZAR DATOS DEL CONTRATO
					echo 'actualizando contrato..';
					$e->set_pcle(0,'prod_id',$lt->id);
					$e->set_pcle(0,'titular_id',$atmcli->id);
					$e->set_pcle(0,'cotitular_id',(!empty($cl_cotit))?$cl_cotit->id:0);
					// $e->set_pcle(0,'cli_id',$cl_tit->id);
					$e->set_pcle(0,'beneficiario_id',(!empty($bnf))?$bnf->id:0);
					$e->set_pcle(0,'vendedor',$t[$i]['AG']);
					$e->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));
					$e->set_pcle(0,'escaneado',$t[$i]['AM']);
					$e->set_pcle(0,'reserva',$t[$i]['AN']);
					$e->set_pcle(0,'fecha_boleto',$t[$i]['AO']);
					$e->set_pcle(0,'reglamento',$t[$i]['AP']);
					$e->set_pcle(0,'copia_dni',$t[$i]['AQ']);
					$e->set_pcle(0,'ubicacion',$t[$i]['AR']);
					$e->set_pcle(0,'posesion',$t[$i]['AS']);
					$e->set_pcle(0,'fecha_sellado',$t[$i]['AU']);
					$e->set_pcle(0,'monto_sellado',$t[$i]['AV']);
					$e->set_pcle(0,'observaciones',$t[$i]['AX']);
				}
				else{
					echo '  sin contrato '.$t[$i]['A'];
				}
			}
		}
		echo 'DOne...';
	}

	function fix_infoclientes_MO(){
		set_time_limit(0);
		$t = $this->excel_to_arr('infoclientes_fix_MO.xlsx');
		echo '<pre>';
		for ($i=1; $i <= count($t); $i++) {

			echo "<br/>lote: ".$t[$i]['A'];

			if($t[$i]['A'] != ''){
				$lt = new Atom(0,'LOTE',$t[$i]['A']);

				$this->set_lote($t[$i],$lt);

				// SI ES ACTIVO / CANJE / CEDIDO DEBE TENER CONTRATO
				if($t[$i]['AF'] == "ACTIVO" || $t[$i]['AF'] == "CANJE"|| $t[$i]['AF'] == "CEDIDO"){
					//**** EL CONTRATO
					$e = new Element(0,'CONTRATO',$lt->id);

					//  atom del cliente del contrato
					$atmcli =  new Atom($e->get_pcle('cli_id')->value);
					// var_dump($atmcli->name);

					if(!empty($atmcli->name)){
						//***  EL CLI_ID EN EL CONTRATO ES UN VALID ATOM
						echo '  found cli: '.$atmcli->name.'   id: '.$atmcli->id;

						$q = $this->app_model->get_arr("SELECT * FROM atoms where name = '{$atmcli->name}' AND id != '{$atmcli->id}' ");
						//  clientes duplicados
						if(count($q)>0){
							echo '<br>clientes duplicados to kill:<br/>';
							print_r($q);
							foreach ($q as $tk) {
								$this->set_cli_anulado($tk['id']);
							}
						}
					}else{
						$this->set_titular($t[$i]);
					}
					// actualizar datos del cliente titular
					// si en excel hay cotitular buscar en element un cotitular id
					// CLIENTE CO-TITULAR
					if(!empty($t[$i]['R']) && !empty($t[$i]['S'])){
						$cl_cotit = $this->set_cotit($t[$i]);
					}
					// CLIENTE BENEFICIARIO
					if(!empty($t[$i]['AY'])){
						$bnf = $this->set_beneficiario($t[$i]);
					}
					// ACTUALIZAR DATOS DEL CONTRATO
					echo 'actualizando contrato..';
					$e->set_pcle(0,'prod_id',$lt->id);
					$e->set_pcle(0,'titular_id',$atmcli->id);
					$e->set_pcle(0,'cotitular_id',(!empty($cl_cotit))?$cl_cotit->id:0);
					// $e->set_pcle(0,'cli_id',$cl_tit->id);
					$e->set_pcle(0,'beneficiario_id',(!empty($bnf))?$bnf->id:0);
					$e->set_pcle(0,'vendedor',$t[$i]['AG']);
					$e->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));
					$e->set_pcle(0,'escaneado',$t[$i]['AM']);
					$e->set_pcle(0,'reserva',$t[$i]['AN']);
					$e->set_pcle(0,'fecha_boleto',$t[$i]['AO']);
					$e->set_pcle(0,'reglamento',$t[$i]['AP']);
					$e->set_pcle(0,'copia_dni',$t[$i]['AQ']);
					$e->set_pcle(0,'ubicacion',$t[$i]['AR']);
					$e->set_pcle(0,'posesion',$t[$i]['AS']);
					$e->set_pcle(0,'fecha_sellado',$t[$i]['AU']);
					$e->set_pcle(0,'monto_sellado',$t[$i]['AV']);
					$e->set_pcle(0,'observaciones',$t[$i]['AX']);
				}
				else{
					echo '  sin contrato '.$t[$i]['A'];
				}
			}
		}
		echo 'DOne...';
	}



	function set_cli_anulado($atm_id){
		$cl = new Atom($atm_id);
		$new_name =  $cl->name . '--ANULADO--';
		$cl->set('name',$new_name);
		$cl->set('atom_types_id',18);
	}


	function load_infoclientes_ES_OLD(){
		set_time_limit(0);
		$t = $this->excel_to_arr('inflo-clientes-ES-test.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {
			//  SETEA LOTE O RESCISION Y DEVUELVE EL ATOM
			echo "<br/>Setting : ".$t[$i]['A'];
			if($t[$i]['A'] != ''){
				// $lote_atm = (strpos($t[$i]['A'], 'R_') > -1)?$this->set_rscn($t[$i]):$this->set_lote($t[$i]);
				// CLIENTE TITULAR
				if(!empty($t[$i]['P']) && !empty($t[$i]['Q'])){
					$cl_tit = $this->set_titular($t[$i]);
				}
				// CLIENTE CO-TITULAR
				// if(!empty($t[$i]['R']) && !empty($t[$i]['S'])){
				// 	$cl_cotit = $this->set_cotit($t[$i]);
				// }
				// CLIENTE BENEFICIARIO
				if(!empty($t[$i]['AY'])){
					$bnf = $this->set_beneficiario($t[$i]);
				}
				// if($t[$i]['AF'] == 'ACTIVO' || $t[$i]['AF'] == 'RESCINDIDO'){
				// 	// CONTRATO
				// 	$ctr = new Element(0,'CONTRATO',$lote_atm->id);
				// 	$ctr->set_pcle(0,'prod_id',$lote_atm->id);
				// 	$ctr->set_pcle(0,'titular_id',$cl_tit->id);
				// 	// $ctr->set_pcle(0,'cotitular_id',(!empty($cl_cotit))?$cl_cotit->id:0);
				// 	$ctr->set_pcle(0,'cli_id',$cl_tit->id);
				// 	$ctr->set_pcle(0,'beneficiario_id',(!empty($bnf))?$bnf->id:0);
				// 	$ctr->set_pcle(0,'vendedor',$t[$i]['AG']);
				// 	$ctr->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));
				// 	$ctr->set_pcle(0,'escaneado',$t[$i]['AM']);
				// 	$ctr->set_pcle(0,'reserva',$t[$i]['AN']);
				// 	$ctr->set_pcle(0,'fecha_boleto',$t[$i]['AO']);
				// 	$ctr->set_pcle(0,'reglamento',$t[$i]['AP']);
				// 	$ctr->set_pcle(0,'copia_dni',$t[$i]['AQ']);
				// 	$ctr->set_pcle(0,'ubicacion',$t[$i]['AR']);
				// 	$ctr->set_pcle(0,'posesion',$t[$i]['AS']);
				// 	$ctr->set_pcle(0,'fecha_sellado',$t[$i]['AU']);
				// 	$ctr->set_pcle(0,'monto_sellado',$t[$i]['AV']);
				// 	$ctr->set_pcle(0,'observaciones',$t[$i]['AX']);

				// 	echo '<br/> CONTRATO:'.$ctr->id;
				// }

			}
		}
		echo 'DOne...';
	}


	function load_infoclientes_GA_old(){
		set_time_limit(0);
		$t = $this->excel_to_arr('inflo-clientes-GA-may_7.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {
			//  SETEA LOTE O RESCISION Y DEVUELVE EL ATOM
			echo "<br/>Setting : ".$t[$i]['A'];
			if($t[$i]['A'] != ''){
				$lote_atm = (strpos($t[$i]['A'], 'R_') > -1)?$this->set_rscn($t[$i]):$this->set_lote($t[$i]);
				// CLIENTE TITULAR
				if(!empty($t[$i]['P']) && !empty($t[$i]['Q'])){
					$cl_tit = $this->set_titular($t[$i]);
				}
				// CLIENTE CO-TITULAR
				// if(!empty($t[$i]['R']) && !empty($t[$i]['S'])){
				// 	$cl_cotit = $this->set_cotit($t[$i]);
				// }
				// CLIENTE BENEFICIARIO
				if(!empty($t[$i]['AY'])){
					$bnf = $this->set_beneficiario($t[$i]);
				}
				if($t[$i]['AF'] == 'ACTIVO' || $t[$i]['AF'] == 'RESCINDIDO'){
					// CONTRATO
					$ctr = new Element(0,'CONTRATO',$lote_atm->id);
					$ctr->set_pcle(0,'prod_id',$lote_atm->id);
					$ctr->set_pcle(0,'titular_id',$cl_tit->id);
					// $ctr->set_pcle(0,'cotitular_id',(!empty($cl_cotit))?$cl_cotit->id:0);
					$ctr->set_pcle(0,'cli_id',$cl_tit->id);
					$ctr->set_pcle(0,'beneficiario_id',(!empty($bnf))?$bnf->id:0);
					$ctr->set_pcle(0,'vendedor',$t[$i]['AG']);
					$ctr->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));
					$ctr->set_pcle(0,'escaneado',$t[$i]['AM']);
					$ctr->set_pcle(0,'reserva',$t[$i]['AN']);
					$ctr->set_pcle(0,'fecha_boleto',$t[$i]['AO']);
					$ctr->set_pcle(0,'reglamento',$t[$i]['AP']);
					$ctr->set_pcle(0,'copia_dni',$t[$i]['AQ']);
					$ctr->set_pcle(0,'ubicacion',$t[$i]['AR']);
					$ctr->set_pcle(0,'posesion',$t[$i]['AS']);
					$ctr->set_pcle(0,'fecha_sellado',$t[$i]['AU']);
					$ctr->set_pcle(0,'monto_sellado',$t[$i]['AV']);
					$ctr->set_pcle(0,'observaciones',$t[$i]['AX']);

					echo '<br/> CONTRATO:'.$ctr->id;
				}

			}
		}
		echo 'DOne...';
	}



	function load_lotes_MO(){
		set_time_limit(0);
		$t = $this->excel_to_arr('inflo-clientes-MO-may_7.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {
			//  SETEA LOTE O RESCISION Y DEVUELVE EL ATOM
			echo "<br/>Setting : ".$t[$i]['A'];
			if($t[$i]['A'] != ''){

				$lote_atm = $this->set_lote($t[$i]);
			}
		}
		echo 'DOne...';
	}

	function check_contratos(){
		$ct = $this->app_model->get_arr("SELECT * from elements where elements_types_id = 1 ");
		foreach ($ct as $c) {
			$e = new Element($c['id']);
			$l = new Atom($e->get_pcle('prod_id')->value);
			$cl = new Atom($e->get_pcle('cli_id')->value);
			echo "<br/>Elem id:".$e->id ." Lote: ".$l->name. " --- Estado: ".$l->get_pcle('estado')->value."  Cliente: ". $cl->name ;
			// if(empty($l->name)){
			// 	echo "  DELETING... ";
			// 	if(!empty($e->id)){
			// 		$e->kill_events_all();
			// 		$e->kill();

			// 	}
			// 	if(!empty($l->id)){
			// 		$l->kill();
			// 	}

			// }
		}

	}

/*
query find duplicados

SELECT
    id,
    name,
    COUNT(name)
FROM
    atoms
WHERE atom_types_id = 2
GROUP BY name
HAVING COUNT(name) > 1

*/



	function fix_dupli(){
		// trae todos los lotes activos
		// levanta el contrato del lote
		// obtiene el cli_id
		// levanta el atom del cli
		// busca el nombre del cli con otro atom id
		// si encuentra un atom lo borra


		$dpl = $this->app_model->get_arr("SELECT
				    id,
				    name,
				    COUNT(name)
				FROM
				    atoms
				WHERE atom_types_id = 1
				GROUP BY name
				HAVING COUNT(name) > 1");
		foreach ($dpl as $d) {
			echo "<br/><hr><br/> checking :".$d['name'];
			$a = $this->app_model->get_arr("SELECT * from atoms WHERE name ='{$d['name']}'");
			foreach ($a as $cli) {

				$in_cntr = $this->app_model->get_arr("SELECT * from elements_pcles WHERE label = 'cli_id' AND value = '{$cli['id']}'");
				if (empty($in_cntr)){
					echo '<br/>deleting dupli'. $cli['name'] .'  id: '.$cli['id'];
 				}else{
 					echo '<br/>keeping cli'. $cli['name'] .'  id: '.$cli['id'];
 				}
			}
		}

	}

	function t_l(){
		$l = new Atom(11492);
      	$d = $l->get_pcle('estado');
      var_dump($d);
	}


	function kill_contrato_lote(){
		$id = $_GET['id'];
		// echo "id".$id;
		// DESACTIVADO
		// $e = new Element($id);
		// $l = new Atom($e->get_pcle('prod_id')->value);
		// $cl = new Atom($e->get_pcle('cli_id')->value);
		if(!empty($e->id)){
			$e->kill_events_all();
			$e->kill();
		}
		if(!empty($l->id)){
			$l->kill();
		}
		echo "Done...";
	}


	function fix_cotitulares(){
		// recorrer todos los contratos
		$cl = $this->app_model->get_arr("SELECT * FROM atoms WHERE atom_types_id = 1 ORDER BY id DESC");
		foreach ($cl as $cli) {
			$a = new Atom($cli['id']);
			$cl_name = $a->get_pcle('nombre')->value;
			$cl_ape = $a->get_pcle('apellido')->value;
			$cl_cat = $a->get_pcle('categoria')->value;
			$ok_name = trim($cl_ape)." ".trim($cl_name);





			//
			echo "<br/>Setting ".trim($a->name);

			if($ok_name != ''){
				echo '<br/>replacing with: '. $ok_name;
				 $a->set('name',$ok_name);
			}


			if(preg_match('/^G{1}\d+/', trim($a->name)) || preg_match('/^\d+/', trim($a->name))){


				echo "<br/> DELETING....: ".$a->name;
				$a->kill();
			}

			if(trim($a->name) == ''){
				echo "<br/> DELETING....: ".$a->name;
				$a->kill();
			}
		}
	}


/*

temp
SELECT tit.id,tit.label,tit.value,cotit.label,cotit.value,lote.name,tln.name,ctln.name,pap.value,pnm.value FROM `elements_pcles` tit
LEFT OUTER JOIN elements_pcles prod on prod.elements_id = tit.elements_id AND prod.label = 'prod_id'
LEFT OUTER JOIN elements_pcles cotit on cotit.elements_id = tit.elements_id AND cotit.label = 'cotitular_id'
LEFT OUTER JOIN atoms lote on lote.id = prod.value
LEFT OUTER JOIN atoms tln on tln.id = tit.value
LEFT OUTER JOIN atoms ctln on ctln.id = cotit.value
LEFT OUTER join atoms_pcles pap on pap.atom_id = cotit.value and pap.label = 'apellido'
LEFT OUTER join atoms_pcles pnm on pnm.atom_id = cotit.value and pnm.label = 'nombre'
WHERE tit.label = 'titular_id'


*/



function m3(){
	$elms = $this->app_model->get_arr("SELECT ev.elements_id as id FROM `events` ev JOIN events_pcles estado on estado.events_id = ev.id and estado.label = 'estado' JOIN elements_pcles ep on ep.elements_id = ev.elements_id and ep.label = 'prod_id' JOIN atoms a on a.id = ep.value WHERE events_types_id = 4 AND estado.value = 'a_pagar' GROUP BY ev.elements_id HAVING count(ev.elements_id) <= 3 ORDER BY a.name ASC,  ev.ord_num ASC");
	$r=[];
	$ci = 0;
	$gttl = 0;
	$not_found = [];
	foreach ($elms as $elm){
		$e = new Element($elm['id']);
		if(!empty($e->id)){
			$cli_name = (new Atom($e->get_pcle('cli_id')->value))->get_pcle('apellido')->value .' '. (new Atom($e->get_pcle('cli_id')->value))->get_pcle('nombre')->value ;
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
	$res = [
			'method'=>'en_mora_3',
			'action'=>'response',
			'gttl'=>$gttl,
			'impagas'=>$ci,
			'elmts_con_mora'=>count($elms),
			'not_found'=>$not_found,
			'drill_data'=>$r
		];
	var_dump($res);
}


//  TO TEST *****

	function get_ev_type_id($f){
		$today = new DateTime(date('Y-m-d'));
      // $fv = new DateTime($this->cmn_functs->fixdate_ymd($ev->get_pcle('fecha_vto')->value));
      $fv = new DateTime($this->cmn_functs->fixdate_ymd($f));
      if($today->diff($fv)->invert === 0 && $today->diff($fv)->days > 0 )  {
        $ev_type_id = 8;
      }else{
        $ev_type_id = 4;
      }
      return $ev_type_id;
	}

	function test_get_mto_rtg(){
		echo '<pre>';
		$t = new Element(4765);
		var_dump($this->get_mto_rtg($t));

	}


	function get_mto_rtg($elm){
		$tasa_rtg = $elm->get_pcle('tasa_reintegro_id')->value;
		if($tasa_rtg > 0){
			$r = 0;
			$ts = new Atom($tasa_rtg);
			$cp = $elm->get_ctas_pagas();
			$c = count($cp['events']);
			if($c > 0 && $c <= 12 ){ $r = intval($ts->get_pcle('1-12')->value);}
			if($c > 12 && $c <= 18){ $r = intval($ts->get_pcle('13-18')->value);}
			if($c > 18 && $c <= 24){ $r = intval($ts->get_pcle('19-24')->value);}
			if($c > 24 && $c <= 30){ $r = intval( $ts->get_pcle('25-30')->value);}
			if($c > 30){ $r = intval($ts->get_pcle('31_o_mas')->value);}
			if($r > 0){
				return intval($cp['tot_pagado'] - ($cp['tot_pagado']*$r/100));
			}
		}
		return 0;
	}



	function fix_clean(){
		$t = $this->excel_to_arr('test_infocl.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {

			$l = new Atom(0,'LOTE',$t[$i]['A']);
			$l->kill();
			$r = new Atom(0,'CLIENTE',$t[$i]['N']." ".$t[$i]['O']);
			$r->kill();
			$ct = new Atom(0,'CLIENTE',$t[$i]['P']." ".$t[$i]['Q']);
			$ct->kill();
			$ct = new Atom(0,'CLIENTE',$t[$i]['Q']." ".$t[$i]['P']);
			$ct->kill();
		}
		echo 'Done';
	}


	function test_ptr(){
		$t = $this->excel_to_arr('test_infocl.xlsx');
		echo '<pre>';
		for ($i=3; $i <= count($t); $i++) {
			// $this->get_ptr_id($t[$i]);
			var_dump($this->get_ptr_id($t[$i]));
		}
	}


	function get_ptr_id($t){
		if(!empty($t['AH']) && !empty($t['AI']) && !empty($t['AJ']) && !empty($t['AK']) && !empty($t['AL'])){
			$ptrs = $this->app_model->get_arr('SELECT * FROM atoms WHERE atom_types_id = 17');
			foreach ($ptrs as $p) {
			$ptr = new Atom($p['id']);
				if(
					$ptr->get_pcle('1-12')->value == intval(str_replace('%', '', $t['AH'])) &&
					$ptr->get_pcle('13-18')->value == intval(str_replace('%', '', $t['AI'])) &&
					$ptr->get_pcle('19-24')->value == intval(str_replace('%', '', $t['AJ'])) &&
					$ptr->get_pcle('25-30')->value == intval(str_replace('%', '', $t['AK'])) &&
					$ptr->get_pcle('31_o_mas')->value == intval(str_replace('%', '', $t['AL']))
				){
					return $ptr->id;
				}
			}
		}
		return null;
	}


	function set_ptr1(){
		$ptr1 = new Atom(0,'TASA_REINTEGRO','PTR_1');
			$ptr1->set_pcle(0,'1-12',25);
			$ptr1->set_pcle(0,'13-18',25);
			$ptr1->set_pcle(0,'19-24',25);
			$ptr1->set_pcle(0,'25-30',25);
			$ptr1->set_pcle(0,'31_o_mas',25);
		var_dump($ptr1->id);

	}


	function set_ptr2(){
		$ptr1 = new Atom(0,'TASA_REINTEGRO','PTR_2');
			$ptr1->set_pcle(0,'1-12',100);
			$ptr1->set_pcle(0,'13-18',90);
			$ptr1->set_pcle(0,'19-24',80);
			$ptr1->set_pcle(0,'25-30',70);
			$ptr1->set_pcle(0,'31_o_mas',60);
			var_dump($ptr1->id);

	}

	function set_ptr3(){
		$ptr1 = new Atom(0,'TASA_REINTEGRO','PTR_3');
			$ptr1->set_pcle(0,'1-12',60);
			$ptr1->set_pcle(0,'13-18',60);
			$ptr1->set_pcle(0,'19-24',60);
			$ptr1->set_pcle(0,'25-30',60);
			$ptr1->set_pcle(0,'31_o_mas',60);
			var_dump($ptr1->id);

	}

	function tadl(){
		echo '<pre>';
		$t = new Element(6506);
		$id_planes_adl = $this->cmn_functs->get_adl_able_plan_id();

		$tp = $t->get_pcle('financ_id');
        if(!empty($tp))
        	$f_id = intval($t->get_pcle('financ_id')->value);


        // NEW ADL_LIST
        $to_adl = $this->get_ctas_adl($t);

        if(in_array($f_id, $id_planes_adl) && !empty($to_adl))
        	$ctas_adl = $to_adl;




		var_dump($ctas_adl);


	}




	// function get_ctas_adl($el){
 //      //*******  CONDICION REQUERIDA
 //      // CUOTAS FUTURAS A PAGAR
 //      $ctas_disp = $el->get_events(8,'a_pagar');
 //      var_dump($ctas_disp);
 //      if(count($ctas_disp['events'])){
 //        // OBTENGO EL MONTO A PAGAR DE LA PROXIMA CUOTA
 //        $lp_monto = (new Event($ctas_disp['events'][0]['id']))->get_pcle('monto_cta')->value;
 //      }
 //      if(count($ctas_disp['events'])> 0 && !empty($lp_monto)){
 //        // OBTENGO UN ARRAY DE CUOTAS DISPONIBLES PARA PAGAR EN FORMA ADELANTADA
 //        $ev = new Event($ctas_disp['events'][0]['id']);
 //        $min_id = $ctas_disp['events'][0]['id'];
 //        $max_id = end($ctas_disp['events'])['id'];
 //        if($min_id && $max_id){
 //          $cda = $this->app_model->get_arr("SELECT id as events_id, date as fec_vto FROM events WHERE id >= {$min_id} AND id <= {$max_id} ");
 //          if(count($cda)>0){
 //            $cd_pcles = [];
 //            foreach($cda as $cd){
 //              $x = new Event($cd['events_id']);
 //              $cd_pcles[]=$x->get_pcle('nro_cta')->value;
 //            };

 //            return ['disp'=>$cda,'mt_cta'=>$lp_monto,'pcles'=>$cd_pcles];
 //          }
 //        }
 //      }
 //      return false;
 //    }



	//  test lotes en 120 ctas garin
	function g120(){
		$lid =$this->app_model->get_arr("SELECT id FROM `atoms` where atom_types_id = 2 and name like 'G%'");
		$t = count($lid);

		$en_contrato = 0;
		$en_ant = 0;
		$en_120 = 0;
		$r=[];
		foreach ($lid as $l) {
			$i ++;
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				$en_contrato ++;
				$plan = $e->get_plan();
				if(strpos($plan, '120')> -1){
					$en_120++;
					// var_dump($e->get_owner_name());
					// var_dump($e->get_pcle('cli_id')->value);
					//  numero del lote, nombre , telefono, ctas en mora, ctas ftrm
					$cli_atom = new Atom($e->get_pcle('cli_id')->value);
					$cli = $cli_atom->get_pcle('nombre')->value.' '.$cli_atom->get_pcle('apellido')->value;
					$tel = $cli_atom->get_pcle('telefono')->value.'/'.$cli_atom->get_pcle('celular')->value;
					$ft = $e->get_events(4,'p_ftrm');
					$mr = $e->get_events(4,'a_pagar');
					$nv = $e->get_first_future_event('a_pagar');
					$r[] = [
						'Lote '=> $e->get_owner_name(),
						'Cliente'=>$cli,
						'Telefono'=>$tel,
						'Prox. Cta. Nro.'=>$nv['pcles']['nro_cta']->value,
						'Prox. Fec. Vto.'=>$nv['pcles']['fec_vto']->value,
						'Prox. Fec. Mnto.'=>$nv['pcles']['monto']->value,
						'Cant Pagos Fuera Termino'=>count($ft['events']),
						'Cant Ctas. en Mora'=>count($mr['events']),
						'Monto $ en Mora'=>intval($mr['total'])
					];
				}else{
					$en_ant ++;
				}

				// $lp = $e->get_last_payment();
				// if(!empty($lp)){
				// 	var_dump($lp->get_props());
				// }
			}
		}
		echo '<br> tot:'.$t;
		echo '<br> index:'.$i;
		echo '<br> en_contrato:'.$en_contrato;
		echo '<br> en anticipo:'.$en_ant;
		echo '<br> en 120:'.$en_120;
		echo '<pre>';
		print_r($r)	;
	}


	function repo_lotes_con_posesion(){
		$lid =$this->app_model->get_arr("SELECT id FROM `atoms` where atom_types_id = 2");
		$r=[];
		foreach ($lid as $l) {
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				$nv = $e->get_first_future_event('a_pagar');
				if(!empty($nv)){
					$monto = $nv['pcles']['monto']->value;
					$pcn = str_replace('Cuota', '', $nv['pcles']['nro_cta']->value);
					$pfv = $nv['pcles']['fec_vto']->value;
					$plan = $e->get_plan();
					if(strpos($plan, '120')> -1 || strpos($plan, '_fija')> -1 ){
						//  numero del lote, nombre , telefono, ctas en mora, ctas ftrm
						$lote_num = $e->get_owner_name();
						$barrio = '';
						$pr_id = $e->get_pcle('prod_id');
						if(!empty($pr_id)){
							$l = new Atom($pr_id->value);
							$barrio = $l->get_pcle('emprendimiento')->value;
						}
						$cli_atom = new Atom($e->get_pcle('cli_id')->value);
						$cli = $cli_atom->get_pcle('nombre')->value.' '.$cli_atom->get_pcle('apellido')->value;
						$tel = $cli_atom->get_pcle('telefono')->value.'/'.$cli_atom->get_pcle('celular')->value;
						$r[] = [
							'Barrio'=>$barrio,
							'Lote '=> $lote_num,
							'Cliente'=>$cli,
							'Telefonos'=>$tel,
							'Prox. Cta.'=>$pcn,
							'Prox. Vto.'=>$pfv,
							'Monto'=>$monto
							// 'Ctas. Fuera Term'=>$ft,
							// 'Ctas. Mora'=>$mr,
							// 'Mora $'=>intval($mrt)
						];
					}
				}
			}
		}
		$res = [
			'method'=>'repo_lotes_con_posesion',
			'action'=>'response',
			'title'=>'Reporte Lotes con Posesion',
			'data'=>$r
		];
		echo json_encode(array(
			'callback'=> 'front_call',
			'param'=> $res
		));
	}


	//******  TESTER DE EDIT ELEMENTS
	function edit_element_2($p=0){
	    $p=['elem_id'=> 5107] ;
	    // SI PARAM ESTA VACIO BUSCA EN $_POST
	    if($p === 0){
	      $p = $this->input->post('data');
	    }
	    $e = new Element($p['elem_id']);
	    //  NEW ELEMENT RETORNA EMPTY SI FALLO
	    if($e->type === 'EMPTY'){
	      $r =[
	            'tit'=>'Error',
	            'msg'=>'Element Vacio',
	            'type'=>'danger',
	            'container'=>'modal',
	            'after_action'=>'back'
	          ];
	          echo json_encode(array
	            (
	              'callback'=>'myAlert',
	              'param'=>$r
	            )
	          );
	    }else{

	      $cli_id = $e->get_pcle('cli_id')->value;
	      $financ_id = $e->get_pcle('financ_id')->value;
	      $cli_atom = new Atom($cli_id);
	      $financ_atom = new Atom($financ_id);
	      $cli = $cli_atom->get_pcle('nombre');
	      $fec_ini = $e->get_pcle('fec_ini');
	      $financ = $financ_atom->get_pcle('name');
	      $saldo = $e->get_pcle('saldo');
	      $estado = $e->get_pcle('curr_state');

	      $cuotas = $e->get_events_all();

	      // var_dump($cli);
	      // var_dump($fec_ini);
	      // var_dump($financ);
	      // var_dump($saldo);
	      // var_dump($estado);
	      // var_dump($cuotas);
	      $d = ['cli'=>$cli,'fec_ini'=>$fec_ini,'financ'=>$financ,'saldo'=>$saldo,'estado'=>$estado,'cuotas'=>$cuotas];

	      echo json_encode(array(
	        'callback'=> 'front_call',
	        'param'=> ['method'=>'edit_element_2','sending'=>false,'action'=>'response','data'=>$d]
	      ));
	    }
	}


	function test_update_ctas_rev_fplan(){

		// $x = $this->app_model->update('elements_pcles',['label'=>$p['label'],'value'=>$p['value'],id,$p['id']]);
		$e = new Element(6100);
		$p = ['new_plan_id'=>10746,'new_monto_cta'=>100];

		$ev_lp = $e->get_last_payment();
      $atm_fn = new Atom($p['new_plan_id']);
      $cctas = $atm_fn->get_pcle('cant_ctas')->value;

      $indac = $atm_fn->get_pcle('indac')->value;
      $frec_indac = $atm_fn->get_pcle('frecuencia_indac')->value;


      $ord_num = floatval($ev_lp->ord_num);
      $ctas_rest = (intval($cctas) - $ord_num);

      $mto = $p['new_monto_cta'];

      $fv = DateTime::createFromFormat('Y-m-d', $ev_lp->date);


      for ($i=0; $i < $ctas_rest; $i++) {
        $fv->modify('next month');
        $ord_num ++;
        $lyc = 'Cuota '.intval($ord_num).' de '.intval($cctas);

        // $this->set_new_cuota($e->id,8,$mto,$fv->format('Y-m-d'),floatval($ev_lp->ord_num)+$i,$lyc);
        var_dump($e->id,8,$mto,$fv->format('Y-m-d'),$ord_num,$lyc);


        if(intval($indac) > 0 && intval($frec_indac) > 0){
          if($i > 1 && $i % intval($frec_indac) == 0){
            $mto = intval(round($mto * $indac / 100 + $mto));
          }
        }

      }




	}

	function hist_check(){
		$h = new Historial(0,'HISTORIAL',848);
		$st = $h->get_event_last()->get_pcle('state')->value;
		var_dump($st);
	}

	function tt(){
		echo '<pre>';
		// elem id 5397 es lote 230 y tiene un objeto historial


		// $h = new Historial((new Element(5397))->get_pcle('hist_id')->value);

		// $x = new Element(6402);


		// $t = $this->cmn_functs->resp('front_call',['method'=>'hist_list','data'=>$h->get_events_all()]);
		// var_dump($this->get_cambio_financ_plan($rn));

		// $lp_ev = $x->get_last_payment();
		// $v = $x->get_events(8,'a_pagar');
		// $t = $x->get_events_first_future();
		// $u = $x->get_cta_upc();

		// $adls = $this->get_ctas_adl($x);



		$l = new Atom(712);
		// var_dump($l->get_props());
		$t = $this->t_check_historial($l);
		// $e = new Element(0,'CONTRATO',$l->id);
		//  $h=new Historial(0,'HISTORIAL',$l->id);
		// var_dump(intval($lp_ev->get_pcle('monto_pagado')->value));
		var_dump($t);
	}


	function t_check_historial($l){
      $rscn_obj = 0;
      var_dump((empty($l->get_pcle('hist_id')->value)));
      // if(empty($l->get_pcle('hist_id'))){
      //   $h = $this->cmn_functs->hist_init($l);
      // }
      // else{
      //   $h = new Historial($l->get_pcle('hist_id')->value);
      // }
      // var_dump($h);
      exit;

      $ev = $h->get_event_last();
      $stt = $ev->get_pcle('state')->value;
      if($stt === "RESCINDIDO"){
        if($ev->get_pcle('accion')->value === 'rescision_id'){
          $rscn_obj = (new Atom($ev->get_pcle('detalle')->value))->get_props();
        }
      }
      return ['state'=>$stt,'rscn_obj'=>$rscn_obj];
    }





    // DIF DIAS PARA LOS INTERESES
    function dif_dias($date){
      $ddt = new DateTime($date);
      $today = new DateTime();
      $dif_date = $today->diff($ddt);
      return intval($dif_date->format('%a'));
    }


	function get_lote($elm_id){
      $e = new Element($elm_id);
      $cl = new Atom ($e->get_pcle('cli_id')->value);
      $l = new Atom($e->get_pcle('prod_id')->value);
      $b = new Atom (0,'BARRIO',$l->get_pcle('emprendimiento')->value);
        //  ACTUALIZA EL ESTADO FUTURE PAST DE LAS CUOTAS
      $this->upd_futr_past($e);
        //  CHECKEA EL SALDO A FAVOR SI LO HUBIERA
      $x = $e->get_pcle('saldo');
      if(!empty($x)){
        $sld = $e->get_pcle('saldo')->value;
      }else{
        $sld = 0;
      }
        // ESTADO DEL CONTRATO DEVUELVE UN ARRAY CON STATE Y RSN_ID
      $h = $this->check_historial($l->get_pcle('hist_id')->value);

      $el = [
        'cli_id'=>$cl->id,
        'owner_id'=>$e->owner_id,
        'elements_id'=>$e->id,
        'cli_atom_name'=>$cl->name,
        'cli_data'=>$cl->get_pcle(),
        'lote_id'=>$l->id,
        'barrio_nom'=>$b->name,
        'barrio_id'=>$b->id,
        'lote_nom'=>$l->name,
        'lote_uf'=>$l->name,
        'fec_init'=>$e->get_pcle('fec_ini')->value,
        'curr_state'=>$h['state'],
        'rsn_id'=>$h['rscn_obj'],
        'sf'=>$e->get_saldo_a_financiar(),
        'cta_upc'=>$e->get_cta_upc(),
        'ctas_pagas'=>$e->get_events(4,'p%'),
        'ctas_adelantadas'=>$e->get_events(6,'pagado'),
        'ctas_restantes'=>$e->get_events(8,'a_pagar'),
        'ctas_mora'=>$e->get_events(4,'a_pagar'),
        'ctas_pft'=>$e->get_events(4,'p_ftrm'),
        'financ'=>$e->get_plan(),
        'saldo'=>$sld
      ];
      return $el;
    }



	function get_cambio_financ_plan($e){
        $cp = $e->get_ctas_pagas();
        $plan = $e->get_plan();
      if(count($cp['events']) == 36 && strpos($plan, 'Anticipo 36') > -1){
        return true;
      }else{
        return false;
      }
    }





	function contratos_con_rev_fplan(){
		$lid =$this->app_model->get_arr("SELECT id,name FROM `atoms` where atom_types_id = 2 ");
		foreach ($lid as $l) {
			$e = new Element(0,'CONTRATO',$l['id']);
			if(!empty($e)){
				var_dump($l['name']);
				// $this->upd_futr_past($e);
				$rev = $this->check_rev_fplan($e);
				if(is_array($rev)){
					var_dump($rev);

				}
			}
		}



	}


	//  TEST SI ESTA EN LA CUOTA DE REVISION
    public function check_rev_fplan($e){
	    $x = $e->get_last_payment();
	    $last_pay = (!empty($x))?$x->get_pcle('fec_pago')->value:null;

	    $f = $e->get_pcle('financ_id');
	    // var_dump((!empty($f) && !empty($last_pay)));
	    if(!empty($f) && !empty($last_pay)){
	    	$fn = new Atom($f->value);
	    	$fr = $fn->get_pcle('frecuencia_revision');
	    	if(!empty($fr) && intval($fr->value) > 0){
	    		if(intval($x->ord_num) % intval($fr->value) == 0){
					$int_s = $this->get_saldo_a_pagar($e);
					$lpdt = $x->get_pcle('fec_pago');
					$lpmto = $x->get_pcle('monto_pagado');
					$dt_fecha_pago = (!empty($lpdt))?intval($lpdt->value):'';
					$int_lpm = (!empty($lpmto))?intval($lpmto->value):0;
					return [
						'elm_id'=>$e->id,
						'financ_id'=>$fn->id,
						'last_pay_date'=>$dt_fecha_pago,
						'last_pay_amount'=>$int_lpm,
						'atm_fnanc'=>$fn->get_props(),
						'saldo_a_Pagar'=>$int_s
					];
				}
				return false;
	    	}
	    	return false;
	    }
    	return false;
	}


  	function get_saldo_a_pagar($e){
  		$lp = $e->get_last_payment();
  		$monto = $lp->get_pcle('monto_pagado')->value;
  		$ctas_restantes = count($e->get_events_id_by_state('a_pagar'));
  		return intval($monto) * $ctas_restantes;
  	}




    //**** CHECKEO DEL HISTORIAL
    // function check_historial($h_id){
    //   $h = new Historial($h_id);
    //   $ev = (!empty($h->id))?$h->get_event_last():'';
    //   $stt = $ev->get_pcle('state')->value;
    //   if($stt === "RESCINDIDO"){
    //     // if($ev->get_pcle('accion')->value === 'rescision_id'){
    //     //   $rscn_obj = (new Atom($ev->get_pcle('detalle')->value))->get_props();
    //     // }
    //   }
    //   $rscn_obj = 0; //? (new Atom($h->owner_id))->get_props():'';
    //   return ['state'=>$stt,'rscn_obj'=>$rscn_obj];
    // }


	    // **** UPDATER Y CONTROL DE CUOTAS A PAGAR
    public function upd_futr_past($e){
      //****  CHECKEO SI ESTA EN EL CAMBIO DE CICLO DE 36 A 120
      if($this->get_cambio_financ_plan($e)){
        // $e->clean_a_pagar_events();
        $ev_lpay = $e->get_last_payment();
        if(!empty($ev_lpay)){
          $res=[
            'method'=>'set_cambio_financ_plan',
            'action' =>'response',
            'elem_id'=>$e->id,
            'last_fec_pago'=>$ev_lpay->get_pcle('fec_pago')->value,
            'last_monto_pagado'=>$ev_lpay->get_pcle('monto_pagado')->value
          ];
          $this->cmn_functs->resp('front_call',$res);
        }
        exit();
      }
      $rev_fp = $this->check_rev_fplan($e);
      if(!empty($rev_fp)){
        $res=[
          'method'=>'set_revision_fplan',
          'action' =>'response',
          'data'=>$rev_fp
        ];
        $this->cmn_functs->resp('front_call',$res);
        exit();
      }
      // *** HAY QUE REFACTORIZAR PUEDO HACER ESTO DESDE MYSQL EN EL MODEL
      $dt_now = new DateTime(date('Y-m-d'));
      $f = $e->get_events(8,'a_pagar');
      foreach ($f['events'] as $xv) {
        $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
        $dt_diff = $dt_xv->diff($dt_now);
        if($dt_diff->invert == 0){
          if($dt_diff->days >= 25){
            $this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
          }
        }
      }
    }


	function t2(){
		$p_elm_id = 6007;
      	$e = new Element($p_elm_id);

      	$t = $e->get_pcle('financ_id');
      	if(!empty($t)) $f_id = intval($e->get_pcle('financ_id')->value);
	      	//**** PLANES QUE PUEDEN ADELANTAR CUOTAS EN CICLO 36 O 120
        $id_planes_adl = [2225,2235,9380,9436];

        // NEW ADL_LIST
        if(in_array($f_id, $id_planes_adl)){
           echo $f_id;

           // $ctas_adl = $this->get_ctas_adl($e);
        }else{
        	echo 'not found';
        }

	}

	// **** SETEA LAS CUOTAS DE AGUINALDO DEL ELEMENTO INDICADO
	// *** ESTA ACA POR QUE SOLO YO TENGA ACCESO
	public function set_ctas_aguinaldo()
	{
	  	$p_elm_id = 6007;
      	$e = new Element($p_elm_id);
      	$t1 = $e->get_pcle('cant_ctas');
      	$t2 = $e->get_last_event('pagado');
      	if(!empty($t1)) intval($cctas = $e->get_pcle('cant_ctas')->value);
      	if(!empty($t2)) $mto_cta = intval($e->get_last_event('pagado')['pcles']['monto']->value);
      	for ($i=0; $i < ($cctas + 6); $i++) {
      		if($i > 1 && $i % 6 == 0){
      			$ev_ag = $e->get_event_by_ord_num($i);
      			$evnt = new Event(0,8,$ev_ag->get_pcle('fecha_vto')->value,$e->id,floatval($ev_ag->ord_num )+0.1);
		    	$evnt->set_pcle(0,'monto_cta',$mto_cta,'Monto Cuota',1);
		    	$evnt->set_pcle(0,'fecha_vto',$ev_ag->get_pcle('fecha_vto')->value,'Fecha Vto.',1);
		    	$evnt->set_pcle(0,'estado','a_pagar','',-1);
		    	$evnt->set_pcle(0,'nro_cta',('Cuota Aguinaldo '.$i/6),'Nro. Cuota',1);
		    	$evnt->set_pcle(0,'monto_pagado',0,'Monto Pagado',1);
		      	$evnt->set_pcle(0,'fec_pago','-','Fecha de Pago',1);
      		}
      	}




	}


	public function get_servicios()
	{

		$e = new Element(4932);

		$servicios = $e->get_servicios();

		if(!empty($servicios)){
        $servs = [];
        foreach ($servicios as $srv) {
          $x = new Element($srv['id']);
          $xs = new Atom($x->get_pcle('atom_id')->value);
          $servs[] = ['name'=>$xs->get_pcle('name')->value,'cuotas'=>$x->get_events_all()];
        }
      }

	}


	public function excel_to_event()
	{
		$t = $this->excel_to_arr('454.xls');

		$e = new Element(4934);
		$c = $e->get_pcle('cant_ctas')->value;
		for ($i=0; $i < intval($c); $i++) {
			$ev = $e->get_event_by_ord_num($i);
			$fv = $ev->get_pcle('fec_pago');
			$id = (empty($fv))?0:$fv->id;
			$in = $ev->set_pcle($id,'fec_pago',$t[$i+2]['D']);

			// $d = DateTime::createFromFormat('d/m/Y', $t[$i+2]['B']);
			// $ev->set('date',$d->format('Y-m-d'));

		}
		echo 'Done...';
	}

	//**** TESTS PARA USAR EN LA MODIFICACION DE LOS NOMBRES DE LOS LOTES
	public function list_lotes(){
		$lotes = $this->app_model->get_arr('SELECT id FROM atoms WHERE atom_types_id = 2 ORDER BY id ASC');
		foreach ($lotes as $l) {
			$lt = new Atom($l['id']);
			if(strpos($lt->name, 'G')> -1){
				echo '<br>';
				echo 'GA-'.$lt->name;
			}
			else if(strpos($lt->name, 'MO')> -1){
				echo '<br>';
				echo 'MO-'.$lt->name;
			}
			else if(strpos($lt->name, 'SM')> -1){
				echo '<br>';
				echo 'SM-'.$lt->name;
			}
			else if(strpos($lt->name, 'C')> -1){
				echo '<br>';
				echo 'ES-'.$lt->name;
			}
			else{
				echo '<br>';
				echo 'ES-'.$lt->name;
			}
		}
	}

/*  *** OLD FIXES
	function fix_295(){
		$t = $this->excel_to_arr('295.xls');
		$e = new Element(5417);

		for ($i = 2;$i <= 157; $i++ ) {

				$dt_obj = DateTime::createFromFormat('d/m/Y',$t[$i]['B']);
				// $order = ($i < 38)?'ASC':'DESC';
				$ord_num = $t[$i]['A'];

				// *** SETEO PROPS DEL  EVENT
				if($i <38){
					$id=0;
					$type_id=$t[$i]['H'];
					$date=$dt_obj->format('Y-m-d');
					$elem_id=$e->id;
					$ord_num=$t[$i]['A'];
					$ev = new Event($id,$type_id,$date,$elem_id,$ord_num);
					$lyc = 'Cuota '.intval($ord_num).' de 36';
					$ev->set_pcle(0,'nro_cta',$lyc);
				}

				if($i > 37){
					$ev = $e->get_event_by_ord_num_ordered($ord_num,'ASC');
					$ev->set('date',$dt_obj->format('Y-m-d'));
					$ev->set('events_types_id',$t[$i]['H']);
				}

				//  *** SETEO PARTICLES DEL EVENT
				$ev->set_pcle(0,'fecha_vto',$t[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
				$ev->set_pcle(0,'monto_pagado',$t[$i]['E']);
				$ev->set_pcle(0,'fec_pago',$t[$i]['D']);
				$ev->set_pcle(0,'estado',$t[$i]['F']);

				var_dump($ev->id);

				// *** INTERESES SI HAY
				// if(!empty($t[$i]['G']) && $t[$i]['G'] > 0 ){
				// 	$ev->set_pcle(0,'intereses',$t[$i]['G']);
				// }
		}
		echo 'Done...';
	}



	function fix_167(){
		$t = $this->excel_to_arr('167.xls');
		$e = new Element(6109);

		for ($i = 2;$i <= 157; $i++ ) {
				$order = ($i < 38)?'ASC':'DESC';
				$ord_num = $t[$i]['A'];
				$ev = $e->get_event_by_ord_num_ordered($ord_num,$order);

				$dt_obj = DateTime::createFromFormat('d/m/Y',$t[$i]['B']);
				var_dump($ev->id);
				$ev->set('date',$dt_obj->format('Y-m-d'));
				if($i < 74){
					$ev->set('events_types_id',$t[$i]['H']);
				}

				$ev->set_pcle(0,'fecha_vto',$t[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
				if($i < 71){
					$ev->set_pcle(0,'monto_pagado',$t[$i]['E']);
					$ev->set_pcle(0,'fec_pago',$t[$i]['D']);
					$ev->set_pcle(0,'estado',$t[$i]['F']);
				}

				if(!empty($t[$i]['G']) && $t[$i]['G'] > 0 ){
					$ev->set_pcle(0,'intereses',$t[$i]['G']);
				}
		}
		echo 'Done...';
	}




	function fix_212(){
		$t = $this->excel_to_arr('212.xls');
		$e = new Element(5383);
		$ord_num = 1;
		for ($i = 2;$i <= 121; $i++ ) {
				$ev = $e->get_event_by_ord_num_ordered($ord_num,'DESC');

				$dt_obj = DateTime::createFromFormat('d/m/Y',$t[$i]['B']);
				var_dump($ev->id);
				$ev->set('date',$dt_obj->format('Y-m-d'));
				$ev->set_pcle(0,'fecha_vto',$t[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
				if($ord_num <= 24){
					$ev->set_pcle(0,'monto_pagado',$t[$i]['E']);
					$ev->set_pcle(0,'fec_pago',$t[$i]['D']);
				}

				$ord_num++;
		}
		echo 'Done...';
	}



	function fix_143(){
		$t = $this->excel_to_arr('143.xls');
		$e = new Element(6248);
		$ord_num = 1;
			for ($i = 2;$i <= 157; $i++ ) {
				$order = ($i < 38)?'ASC':'DESC';
				$ord_num = $t[$i]['A'];
				$ev = $e->get_event_by_ord_num_ordered($ord_num,$order);

				$dt_obj = DateTime::createFromFormat('d/m/Y',$t[$i]['B']);
				var_dump($ev->id);
				$ev->set('date',$dt_obj->format('Y-m-d'));

				if(!empty($t[$i]['H']) && $t[$i]['H'] > 0 ){
					$ev->set('events_types_id',$t[$i]['H']);
				}

				$ev->set_pcle(0,'fecha_vto',$t[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
				$ev->set_pcle(0,'monto_pagado',$t[$i]['E']);
				$ev->set_pcle(0,'fec_pago',$t[$i]['D']);
				$ev->set_pcle(0,'estado',$t[$i]['F']);

				if(!empty($t[$i]['G']) && $t[$i]['G'] > 0 ){
					$ev->set_pcle(0,'intereses',$t[$i]['G']);
				}
		}
		echo 'Done...';
	}



	function fix_496(){
		$t = $this->excel_to_arr('496.xls');
		$e = new Element(4976);
		$ord_num = 1;
			for ($i = 2;$i <= 86; $i++ ) {
				$order = ($i <= 86)?'ASC':'DESC';
				$ord_num = $t[$i]['A'];
				$ev = $e->get_event_by_ord_num_ordered($ord_num,$order);

				$dt_obj = DateTime::createFromFormat('d/m/Y',$t[$i]['B']);
				var_dump($ev->id);
				$ev->set('date',$dt_obj->format('Y-m-d'));

				if(!empty($t[$i]['H']) && $t[$i]['H'] > 0 ){
					$ev->set('events_types_id',$t[$i]['H']);
				}

				$ev->set_pcle(0,'fecha_vto',$t[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$t[$i]['C']);
				$ev->set_pcle(0,'monto_pagado',$t[$i]['E']);
				$ev->set_pcle(0,'fec_pago',$t[$i]['D']);
				$ev->set_pcle(0,'estado',$t[$i]['F']);

				if(!empty($t[$i]['G']) && $t[$i]['G'] > 0 ){
					$ev->set_pcle(0,'intereses',$t[$i]['G']);
				}
		}
		echo 'Done...';
	}

*/

	function xl_to_elem(){
		// var_dump($this->fix_lote(496));
		$this->replace_events_lote("389_ALTAMIRANO",'ES-389');
	}

	function validate_data($d){
		$r = false;
		if(!empty($d['A']) && intval($d['A']) > 1 && intval($d['A']) <= 120){$r = true;}
		if(!empty($d['B']) && !empty($d['C']) && !empty($d['D']) && !empty($d['E'])){$r = true;}
		if(DateTime::createFromFormat('d/m/Y',$d['B']) && DateTime::createFromFormat('d/m/Y',$d['D'])){$r = true;}
		return $r;
	}

	function replace_events_lote($file_name,$cod_lote){
		$xl_data = $this->excel_to_arr($file_name.'.xlsx');
		$lote = new Atom(0,'LOTE',$cod_lote);
		$e = new Element(0,'CONTRATO',$lote->id);
		$e->kill_events_all();
		$ord_num = 1;
		for ($i = 2;$i <= count($xl_data); $i++ ) {
			if($this->validate_data($xl_data[$i])){
				$ord_num = $xl_data[$i]['A'];
				$dt_obj = DateTime::createFromFormat('d/m/Y',$xl_data[$i]['B']);

				$estado = 'a_pagar';
				// SI ES CUOTA PAGADA
				if(!empty($xl_data[$i]['E']) && !empty($xl_data[$i]['D'])){
					$estado = $this->cmn_functs->get_estado_pago($xl_data[$i]['B'],$xl_data[$i]['D']);
				}

				$ev_fp_arr = $this->cmn_functs->get_event_type_by_fecha_y_estado_de_pago($xl_data[$i]['D'], $xl_data[$i]['B'], $estado);
				echo '<br>setting event:'.$i;
				// var_dump($ev_fp_arr['ev_type_id'],$dt_obj->format('Y-m-d'),$e->id,$ord_num);
				$ev = new Event(0,$ev_fp_arr['ev_type_id'],$dt_obj->format('Y-m-d'),$e->id,$ord_num);
				//  ESTE ORDEN DE CREACION DEL PCLE DEBE MANTENERSE PARA EL LISTADO DE DETALLE DE CUOTA
				$ev->set_pcle(0,'monto_cta',intval($xl_data[$i]['C']),'Monto Cuota ',1);
				$ev->set_pcle(0,'fecha_vto',$xl_data[$i]['B'],'Fecha de Vto.',1);
				if($i <= 37){
					$ev->set_pcle(0,'nro_cta','Cuota '.$ord_num.' de 36');
				}else{
					$ev->set_pcle(0,'nro_cta','Cuota '.$ord_num.' de 84');
				}

				$ev->set_pcle(0,'monto_pagado',intval($xl_data[$i]['E']),'Monto Pagado',1);
				$ev->set_pcle(0,'fec_pago',(!empty($xl_data[$i]['D']))?$xl_data[$i]['D']:'-','Fecha de Pago',1);
				$ev->set_pcle(0,'estado',$ev_fp_arr['estado'],'Estado',-1);

				if(!empty($xl_data[$i]['G']) && $xl_data[$i]['G'] > 0 ){
					$ev->set_pcle(0,'intereses',$xl_data[$i]['G'],'Intereses',1);
				}
			}


		}
		echo 'Done...';
	}




	function update_events_lote($file_name,$cod_lote){
		$xl_data = $this->excel_to_arr($file_name.'.xls');

		$lote = new Atom(0,'LOTE',$cod_lote);

		$e = new Element(0,'CONTRATO',$lote->id);
		$ord_num = 1;
			for ($i = 2;$i <= count($xl_data); $i++ ) {
				$order = ($i <= 38)?'ASC':'DESC';
				$ord_num = $xl_data[$i]['A'];
				$ev = $e->get_event_by_ord_num_ordered($ord_num,$order);

				$dt_obj = DateTime::createFromFormat('d/m/Y',$xl_data[$i]['B']);
				var_dump($ev->id);
				$ev->set('date',$dt_obj->format('Y-m-d'));

				$estado = $this->cmn_functs->get_estado_pago($xl_data[$i]['B'],$xl_data[$i]['D']);
	          	$ev_fp_arr = $this->cmn_functs->get_event_type_by_fecha_y_estado_de_pago($xl_data[$i]['D'], $xl_data[$i]['B'], $estado);

          		$ev->set('events_types_id',$ev_fp_arr['ev_type_id']);

				$ev->set_pcle(0,'fecha_vto',$xl_data[$i]['B']);
				$ev->set_pcle(0,'monto_cta',$xl_data[$i]['C']);
				$ev->set_pcle(0,'monto_pagado',$xl_data[$i]['E']);
				$ev->set_pcle(0,'fec_pago',$xl_data[$i]['D']);
				$ev->set_pcle(0,'estado',$ev_fp_arr['estado']);

				if(!empty($xl_data[$i]['G']) && $xl_data[$i]['G'] > 0 ){
					$ev->set_pcle(0,'intereses',$xl_data[$i]['G']);
				}
		}
		echo 'Done...';
	}




		// RECURSIVE FACTORIAL
	function factorial($n){
		if($n === 0){return 1;}
		return $n * $this->factorial($n-1);

	}

	function f(){
		echo $this->factorial(7);
	}

	// crea un contrato manualmente
	function new_contrato_elem_fix(){
      // $p = $this->input->post('data');
      // $l = new Atom($p['lote_id']);
      // $d = $l->get_pcle('estado');
      // if(!empty($d) && $d->value == 'DISPONIBLE'){
        // DATOS FINANCIACION
        $p = ['lote_id'=>789,'date_contrato'=>'10/02/2019','financ_id'=>9436,'elem_id'=>4699,'mto_cta_1'=>12304];
        $f = new Atom($p['financ_id']);
        $indac = intval($f->get_pcle('indac')->value);
        $frec_indac = intval($f->get_pcle('frecuencia_indac')->value);
        $cant_ctas = $f->get_pcle('cant_ctas')->value;

        // ELEMENT
        $e = new Element($p['elem_id']);
        $e->set_pcle(0,'prod_id',$p['lote_id'],'',-1);
        $e->set_pcle(0,'fec_ini',$p['date_contrato'],'',-1);
        $e->set_pcle(0,'financ_id',$p['financ_id'],'',-1);
        // $e->set_pcle(0,'cli_id',$p['cli_id']);
        // $e->set_pcle(0,'indac',$indac);
        // $e->set_pcle(0,'frec_indac',$frec_indac);
        // $e->set_pcle(0,'cant_ctas',$cant_ctas);
        // $e->set_pcle(0,'mto_cta_1',$p['mto_cta_1']);
        // DATOS DE ANTICIPO sirve para ventas de lotes con  anticipo por fuera de los ciclos normales
        // SUSPENDIDO
        // if(intval($p['anticipo'])>0){
        //   $req_anticipo = true;
        //   $mto_anticipo = intval($p['anticipo']);
        // }else{
          $req_anticipo = false;
          $mto_anticipo = 0;
        // }
        $this->create_cuotas_new($cant_ctas,$p['mto_cta_1'],$p['date_contrato'],$indac,$frec_indac,$e->id,$req_anticipo,$mto_anticipo);
        // NUEVO ESTADO DEL LOTE
        // $l->set_pcle($l->get_pcle('estado')->id,'estado','ACTIVO');
        // $l->set_pcle(0,'event_1_'.time(),'vendido a:'.$p['cli_id']);
        // $l->set_pcle(0,'event_2_'.time(),'contrato_id:'.$e->id);

        // INICIO DEL HISTORIAL
        // $this->cmn_functs->hist_init($l);

        // $this->cmn_functs->resp('front_call',[
        //       'method'=> 'new_contrato_elem',
        //       'sending'=>false,
        //       'action'=> 'save_response',
        //       'data'=> ['result'=>'ok','elm_id'=>$e->id]
        //     ]);
      // }else{
      //   $r =[
      //     'tit'=>'Error',
      //     'msg'=>'Fallo el inicio del nuevo contrato ->'.json_encode($d),
      //     'type'=>'danger',
      //     'container'=>'modal',
      //   ];
      //   $this->cmn_functs->resp('myAlert',$r);
      // }
    }

    // **** CONTRATO Y CUOTAS
    function create_cuotas_new($cant_ctas,$mto_cta,$fec_init,$indac,$frec_indac,$elm_id,$anticipo='false',$mto_anticipo=0){
      if($anticipo === true && $mto_anticipo > 0){
        $this->set_new_cuota($elm_id,8,$mto_anticipo,$fec_init,0,'Anticipo');
      }
      $nfi = '10'.substr($fec_init, 2);
      $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
      for ($i=1; $i <= intval($cant_ctas); $i++){
        $index_ctas = $i;
            // FECHA DE MES ANTERIOR MAS UN MES
        $curr_fec_ven = $fv->format('d/m/Y');
            // LEYENDA DE NUMERO DE CUOTAS
        $lyc = 'Cuota '.$i.' de '.intval($cant_ctas);

        $this->set_new_cuota($elm_id,8,$mto_cta,$curr_fec_ven,$i,$lyc);

          //*************  POST SAVE ACTIONS ****************
          // INCREMENTA EL MONTO NOMINAL DE CUOTAS
          // APLICACION DE INTERES SEMESTRAL

              // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
        if(intval($indac) > 0 && intval($frec_indac) > 0){
          if($i > 1 && $i % $frec_indac == 0){
            $mto_cta = round($mto_cta * $indac / 100 + $mto_cta);
          }
        }
        // INCREMENTO DEL MES DE FV
        $fv = $this->set_cta_next_date($fv,$elm_id);
        // $fv->modify('next month');
      }
      return true;
    }

    // ***************************************************************************************************
    // SETEA LA FECHA DE VENCIMIENTO DE LA PROXIMA CUOTA EN BASE A LA FRECUENCIA DE CUOTA EN LA FINACIACION
    // ***************************************************************************************************
    function set_cta_next_date($dt_obj,$elm_id){
      $fn_id = (new Element($elm_id))->get_pcle('financ_id')->value;
      $frq_ctas = (new atom($fn_id))->get_pcle('frecuencia_cuota')->value;
      return  $dt_obj->modify('+ '.$frq_ctas .' month');
    }

      //****** DATA STRUCT DE LA CUOTA
    function set_new_cuota($elm_id,$ev_type,$monto,$fec_vto,$ord_num,$nro_cta){
      $evnt = new Event(0,$ev_type,$fec_vto,$elm_id,$ord_num);
      $evnt->set_pcle(0,'monto_cta',$monto,'Monto Cuota',1);
      $evnt->set_pcle(0,'fecha_vto',$fec_vto,'Fecha Vto.',1);
      $evnt->set_pcle(0,'estado','a_pagar','',-1);
      $evnt->set_pcle(0,'nro_cta',$nro_cta,'Nro. Cuota',1);
      $evnt->set_pcle(0,'monto_pagado',0,'Monto Pagado',1);
      $evnt->set_pcle(0,'fec_pago','-','Fecha de Pago',1);

    }





/*

CLIPBOARD CONTENT javascript

someDomNode.onpaste = function(e) {
    var paste = e.clipboardData && e.clipboardData.getData ?
        e.clipboardData.getData('text/plain') :                // Standard
        window.clipboardData && window.clipboardData.getData ?
        window.clipboardData.getData('Text') :                 // MS
        false;
    if(paste) {
        // ...
    }
};


*/




	function tst(){
		//  **** NOMBRE DE ARCHIVO
		$t = $this->excel_to_arr('tst_events_update.xls');
	  	// *** CICLO DEL NRO. DE ORDEN DE CUOTAS
	  	$ciclo = 120;
	  	// *** OWNER DEL CONTRATO
	  	$l = new atom(0,'LOTE','076');

	  	$el = new Element(0,'CONTRATO',$l->id);

	  	// *** EVENTOS EXITENTES DEL CONTRATO
	  	if($ciclo == 120){$events = array_slice($el->get_events_all(), 36,120);}
	  	else{$events = $el->get_events_all();}
	  	foreach ($t as $key => $value) {
	  		if($key > 1 && !empty($value['A'])){
	  			$ev_id = $events[(intval($value['A'])-1)]['event']['id'];

	  			$event = new Event($ev_id);

	  			foreach ($t[1] as $xk => $xv) {

	  				var_dump($xv);
	  				var_dump($value[$xk]);
	  			}
	  		}
	  	}
	}






	function excel_to_arr($arch){
		$inputFileName = 'uploads/'.$arch;
		echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME);
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		return $sheetData;
	}

}
