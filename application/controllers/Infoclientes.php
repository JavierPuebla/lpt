<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Infoclientes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this ->load->model('user');
		$this ->load->model('app_model');
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->library('cmn_functs');

	  date_default_timezone_set('America/Argentina/Buenos_Aires');

	  // LIBRERIA PARA EXPORTAR EXCEL
    // include (APPPATH . 'controllers/Excel_features.php');


		/**** PHPExcel ****/
		// include(APPPATH.'libraries/PHPExcel/IOFactory.php');

		// include (APPPATH . 'JP_classes/Atom.php');
		// include (APPPATH . 'JP_classes/Element.php');
		// include (APPPATH . 'JP_classes/Event.php');

  }
	//***** INDEX PAGE DISABLED
	public function index() {
		exit('FORBIDEN!');
	}
  // ****** END INDEX  ******

/*
query para encontrar cotitulares duplicados
SELECT
   id,
   elements_id,
   struct_id,
   value,
    COUNT(value)
FROM
    elements_pcles
WHERE struct_id = 21
GROUP BY value
HAVING COUNT(value) > 1

*/

//  pending para correr el fix despues de las 18 hs

	function import(){
		set_time_limit(0);
		$from = 3;
		$cant = 900;
		$t = $this->cmn_functs->excel_to_arr('ifc_1.xls');
		echo '<pre>';
		$h=[];
		foreach($t[2] as $key => $value){$h[$key] = trim($value);}
		//*** POR CADA LINEA en $i
		for($i=3; $i <= count($t); $i++) {
			$cotit_id='';
			$bnf_id='';
			echo "<br/>lote: ".$t[$i][array_search('LOTE',$h)]."--------------------------------------------";
			$lote = $t[$i][array_search('LOTE',$h)];
			// EXISTE CODIGO DE LOTE EN XL & no es rescindido
			if($lote != '' && !strpos($lote,'R_')){
				$lt = new Atom(0,'LOTE',$lote);
			// ***** ACTUALIZA EL LOTE
				$this->set_lote($lt,$t[$i],$h);

			//*** SI ES ACTIVO / CANJE / CEDIDO TIENE ID CONTRATO
				$stt = $t[$i][array_search('ESTADO DEL LOTE',$h)];
				if($stt == "ACTIVO" || $stt == "CANJE" || $stt == "CEDIDO"){
					// echo "<br/>actualizando titular....... ";
					//**** ACTUALIZA EL CONTRATO
					$e = new Element(0,'CONTRATO',$lt->id);
					echo "<br/>".$e->id;

					// ***  DATOS DEL TITULAR
					$tit_id = $this->set_titular($t[$i],$h);
					echo '<br/>Titular: '.$tit_id;

					//***  CO-TITULAR hay un dni EN EXCEL
					if(!empty($t[$i][array_search('DNI 2',$h)])){
						$cotit_id = $this->set_cotit($t[$i],$h);
						echo '<br/>Cotitular: '.$cotit_id;
					}

					//*** CLIENTE BENEFICIARIO
					if(!empty($t[$i][array_search('BENEFICIARIO',$h)])){
						$bnf_id = $this->set_beneficiario($t[$i],$h);
						echo '<br/>BNF: '.$bnf_id;
					}
					echo '<br/>----------------------------';


					//
					// ACTUALIZAR DATOS DEL CONTRATO
					// echo '<br/> actualizando contrato..';
					// $e->pcle_updv($e->get_pcle('prod_id')->id,$lt->id);
					//
					// $e->pcle_updv($e->get_pcle('titular_id')->id,$tit_id);
					if(!empty($cotit_id)){
						$e->pcle_updv($e->get_pcle('cotitular_id')->id,$cotit_id);
					}else{
						$e->pcle_updv($e->get_pcle('cotitular_id')->id,0);
					}
					if(!empty($bnf_id)){
						$e->pcle_updv($e->get_pcle('beneficiario_id')->id,$bnf_id);
					}
					else{
						$e->pcle_updv($e->get_pcle('cotitular_id')->id,0);
					}

					// ***** SET REVISION CONTRATO
					// if(!empty($t[$i][array_search('CLAUSULA DE REVISION (CADA 2 ANOS)',$h)])){
					// 	$e->pcle_updv($e->get_pcle('clausula_revision')->id,$t[$i][array_search('CLAUSULA DE REVISION (CADA 2 ANOS)',$h)]);
					// }
					// if(!empty($t[$i][array_search('CANTIDAD TOTALES DE CUOTAS',$h)])){
					// 	$e->pcle_updv($e->get_pcle('clausula_revision_tot_ctas')->id,$t[$i][array_search('CANTIDAD TOTALES DE CUOTAS',$h)]);
					// }
					// if(!empty($t[$i][array_search('INDICE DE ACTUALIZACION',$h)])){
					// 	$e->pcle_updv($e->get_pcle('clausula_revision_indice')->id,$t[$i][array_search('INDICE DE ACTUALIZACION',$h)]);
					// }

					// $kt=[
					// 	'vendedor'=>'VENDEDOR',
					// 	'escaneado'=>'SCANEADO',
					// 	'reserva'=>'RESERVA',
					// 	'fecha_boleto'=>'FECHA BOLETO',
					// 	'reglamento'=>'REGLAMENTO',
					// 	'copia_dni'=>'COPIA DNI',
					// 	'ubicacion'=>'UBICACION',
					// 	'posesion'=>'POSESION',
					// 	'fecha_sellado'=>'FECHA SELLADO',
					// 	'monto_sellado'=>'MONTO DE SELLADO',
					// 	'observaciones'=>'OBSERVACIONES',
					//
					// ];
					// $this->fill_obj($e,$kt,$t[$i],$h);

					// $e->set_pcle(0,'tasa_reintegro_id',$this->get_ptr_id($t[$i]));

				}
			}
			if($i > $cant){exit();}
		}
		echo 'DOne...';
	}

	// recibe type Atom  / Element , id
	// $kt = [pclelabel=>exel_tag]
	// $src = linea del excel_to_arr
	// $h = header tag to excel tag
	function fill_obj($o,$kt,$src,$h){
		$o->set('last_update',Date('Y-m-d H:i:s'));
		foreach ($kt as $k => $t) {
			echo '<br> K: '.$k;
			echo '<br> T: '.$t;
			echo '<br> SRC: '.$src[array_search($t,$h)];
			// $o->pcle_updv($o->get_pcle($k)->id,$src[array_search($t,$h)]);
		}
	}


	function set_lote($lt,$src,$h){
		echo '<br/>updating lote.....' . $src[array_search('LOTE',$h)];
		$kt = [
			'name'=>'LOTE',
			'emprendimiento'=> 'EMPRENDIMIENTO',
			'metros2'=> 'M2 TOTALES',
			'frente'=>'FRENTE',
			'fondo'=>'FONDO',
			'esquina'=>'ESQUINA',
			'expediente'=>'EXPEDIENTE',
			'partida'=>'PARTIDA',
			'circ'=>'CIRC',
			'manzana'=>'MANZANA',
			'parcela'=>'PARCELA',
			'calle'=>'CALLE',
			'altura'=>'ALTURA',
			'propietario'=>'PROPIETARIO',
			'estado'=>'ESTADO DEL LOTE'
		];
		$this->fill_obj($lt,$kt,$src,$h);
	}


	function set_titular($src,$h){
		echo '<br/>** TITULAR-->';
		// BUSCA EL TITULAR COMO CLIENTE
		$n = $src[array_search('APELLIDO TITULAR 1',$h)]." ".$src[array_search('NOMBRE TITULAR 1',$h)];

		$tt = $this->Mdb->db->query("SELECT atom_id FROM `atoms_pcles` WHERE atom_types_id = 1 AND struct_id = 19 AND value = '{$src[array_search('DNI 1',$h)]}' ");
		if($tt->result_id->num_rows){
			// FOUND CLIENTE TITULAR
			$tt_atm = new Atom($tt->row()->atom_id);
			$tt_atm->set('name',$n);
		}else{
			// CREO UN ID PARA UN TITULAR CON EL NOMBRE
			$tt_atm = new Atom(0,'CLIENTE',$n);
		}

		echo 'setting titular '.$n.' id:'.$tt_atm->id;
		$kt = [
			'nombre'=>'NOMBRE TITULAR 1',
			'apellido' => 'APELLIDO TITULAR 1',
			'dni'=>'DNI 1',
			'cuit_cuil'=> 'CUIL 1',
			'ocupacion' =>'OCUPACION',
			'domicilio' => 'DOMICILIO TITULAR 1',
			'codigo_postal' => 'CP',
			'localidad' => 'LOCALIDAD',
			'telefono' => 'TELEFONO 1',
			'celular_difusion' => 'CELULAR DIFUSION 1',
			'celular' => 'CELULAR 1',
			'email' => 'MAIL'
		];
		$this->fill_obj($tt_atm,$kt,$src,$h);
		return $tt_atm->id;
	}

	function set_cotit($src,$h){
		$cott = $this->Mdb->db->query("SELECT atom_id FROM `atoms_pcles` WHERE atom_types_id = 1 AND struct_id = 19 AND value = {$src[array_search('DNI 2',$h)]}");
		echo '<br/>*** CO TITULAR-->';
		$n = $src[array_search('APELLIDO TITULAR 2',$h)]." ".$src[array_search('NOMBRE TITULAR 2',$h)];
		// EL DNI ESTA EN DB
		if($cott->result_id->num_rows){
			$cott_atom = new Atom($cott->row()->atom_id);
			$cott_atom->set('name',$n);
		}else{
			$cott_atom = new Atom(0,'CLIENTE',$n);
		}

		// echo 'setting cotit '.$n.' id:'.$cott->row()->atom_id;

		$kt= [
			'nombre'=>'NOMBRE TITULAR 2',
			'apellido'=> 'APELLIDO TITULAR 2',
			'dni' => 'DNI 2',
			'cuit_cuil'=> 'CUIL 2',
			'domicilio' => 'DOMICILIO TITULAR 2'
		];
		$this->fill_obj($cott_atom,$kt,$src,$h);
		return $cott_atom->id;
	}


	function set_beneficiario($src,$h){
		$bnf = $this->Mdb->db->query("SELECT id FROM `atoms` WHERE atom_types_id = 16 AND name LIKE '%{$src[array_search('BENEFICIARIO',$h)]}%'");
		if($bnf->result_id->num_rows){
			$bnf_atom = new Atom($bnf->row()->id);
			$bnf_atom->set('name',$src[array_search('BENEFICIARIO',$h)]);
		}else{
			$bnf_atom = new Atom(0,'BENEFICIARIO',$src[array_search('BENEFICIARIO',$h)]);
		}
		echo 'setting benef:'.$src[array_search('BENEFICIARIO',$h)]. ' id:'.$bnf_atom->id;
		$kt= [
			'nombre' => 'BENEFICIARIO',
			'dni' => 'DNI',
			'telefono' => 'TELEFONO',
			'domicilio' => 'DIRECCION',
			'localidad' => 'LOCALIDAD',
			'motivo_de_cesion' => 'MOTIVO DE CESION'
		];
		$this->fill_obj($bnf_atom,$kt,$src,$h);
		return $bnf_atom->id;
	}





	// *********************
	// *** TRAE LOS DATOS DE UN EXCEL A UN ARRAY
	function excel_to_arr($arch){
		$inputFileName = 'uploads/'.$arch;
		echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME);
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		return $sheetData;
	}



}
