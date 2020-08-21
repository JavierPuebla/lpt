<?php
class app_model extends CI_Model {

	public function __construct() {
		$this -> load -> database();
	}

	function clean($q){
		$x = "DELETE FROM ".$q;
		return $this->db->query($x);
	}

	function get_lotes_by_user_dni($d){
		$x = $this->db->query(
			"SELECT
				atmp.atom_id as user_id,
				atmp.value as dni,
				ep.elements_id as elements_id,
				lt.name as lote,
				ap2.value as nombre,
				ap3.value as apellido
				FROM atoms_pcles atmp
				JOIN elements_pcles ep on ep.value = atmp.atom_id AND ep.struct_id >= 20 AND ep.struct_id <= 21
				JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id and ep2.struct_id = 1
				JOIN atoms lt on lt.id = ep2.value
				JOIN atoms_pcles ap2 on ap2.atom_id = atmp.atom_id and ap2.struct_id = 1
				JOIN atoms_pcles ap3 on ap3.atom_id = atmp.atom_id and ap3.struct_id = 23
				WHERE atmp.value = {$d} and atmp.struct_id = 19 and atmp.atom_types_id = 1 and ep.elements_types_id = 1"
			);
		if($x->result_id->num_rows > 0){

			return $x->result_array();
		}
	}

	// OLD GET LOTES POR DNI PARA LOGIN CLI
	// DEPRECATED
	function get_cli_lotes($user_dni)	{
			$res = [];
			$x = $this -> db -> query(
				"SELECT ap.atom_id as cli_atom_id, ep.elements_id as elm_id, lt.name as lote, ap2.value as nombre,ap3.value as apellido FROM `atoms_pcles` ap
				LEFT OUTER JOIN elements_pcles ep on ep.value = ap.atom_id
				LEFT OUTER JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id and ep2.struct_id = 1
				LEFT OUTER JOIN atoms lt on lt.id = ep2.value
				LEFT OUTER JOIN atoms_pcles ap2 on ap2.atom_id = ap.atom_id and ap2.struct_id = 1
				LEFT OUTER JOIN atoms_pcles ap3 on ap3.atom_id = ap.atom_id and ap3.struct_id = 23
				WHERE  ap.value = {$user_dni}  AND ep.elements_types_id = 1 GROUP BY ep.elements_id ");
			if($x->result_id->num_rows > 0)
			{
				foreach ($x->result_array() as $xv) {
					$res[] =[
						'apellido'=>$xv['apellido'],
						'nombre'=>$xv['nombre'],
						'dni'=>$user_dni,
						'user_id'=>'cli_atom_id',
						'elements_id'=>$xv['elm_id'],
						'lote'=>$xv['lote'],
					];
				}
			}
			return $res;
	}

	function get_atom($id){
		$q = "SELECT * from atoms where id = {$id} ";
		return $this->db->query($q)->row();
	}


	function events_pcle_upd_or_create($ev_id,$data){
		$pcle=$this->db->query("SELECT * FROM events_pcles WHERE events_id = {$ev_id} AND label = '{$data['label']}'")->row();
        if(empty($pcle)){
              $this->db->insert('events_pcles',$data);
        }
        else{
            $this->db->where('id', $pcle->id);
			$this->db->update('events_pcles',['value'=>$data['value']]);
        }
	}

	function elems_pcle_upd_or_create($elm_id,$data){
		$pcle=$this->db->query("SELECT * FROM elements_pcles WHERE elements_id = {$elm_id} AND label = '{$data['label']}'")->row();
        if(empty($pcle)){
              $this->db->insert('elements_pcles',$data);
        }
        else{
            $this->db->where('id', $pcle->id);
			$this->db->update('elements_pcles',['value'=>$data['value']]);
        }
	}

	function pcle_upd_or_create($atom_id,$data){
		$pcle=$this->db->query("SELECT * FROM pcles WHERE atom_id = {$atom_id} AND label = '{$data['label']}'")->row();
        if(empty($pcle)){
              $this->db->insert('atoms_pcles',$data);
        }
        else{
            $this->db->where('id', $pcle->id);
			$this->db->update('atoms_pcles',['value'=>$data['value']]);
        }
	}


	function get_cuenta_de_imputacion_by_name($n){
		$q = "SELECT id FROM contab_cuenta_de_imputacion WHERE nombre LIKE '{$n}'";
		return $this->db->query($q)->row();
	}



	// GETS CLI DATA BY OWNER ID
	function get_cli_by_owner_id($oid){
		$q = "SELECT ac.id as id, ac.name as name FROM `atoms` aw LEFT OUTER JOIN atoms ac on ac.id = SUBSTR(aw.name,9)  WHERE  aw.atom_types_id = 3 AND  aw.id = ".$oid;
		return $this->db->query($q)->row();
	}


	// busca los pcles de cada atom
	function get_pcles($id){
		$q="Select * from pcles where atom_id = '{$id}'";
	    return $this->db->query($q)->result_array();
	}

	function get_pcle_obj($atom_id,$lbl){
		$q="Select * from pcles where atom_id = {$atom_id} AND label = '{$lbl}'";
	    return $this->db->query($q)->row();
	}

	  // busca el element por id o todos
	function get_events_by_type($type,$elm_id = null){
		$q = "Select * from events WHERE event_types_id ='{$type}'";
	    $q .= ($elm_id != null)?" AND elements_id = '{$elm_id}'":"";
	    return $this->db->query($q)->result_array();
	}

	// busca si hay atom_id label en un atom
   	function find_subpcles($pcles){
    	$res = [];
    	foreach ($pcles as $p) {
      		if($p['label'] == 'atom_id')
        	$res[] = $this->get_pcles($p['value']);
    	}
    	return $res;
  	}

  	function set_element_saldo($elements_id,$saldo){
  		$data = ['label'=>'saldo','value'=>$saldo,'title'=>'','vis_elem_type'=>-1];
  		$this->elems_pcle_upd_or_create($elements_id,$data);
  	}

  	function get_nombre_caja_by_nro_comprobante($n){
  		$q  = "SELECT c.nombre FROM `contab_asientos` ca LEFT OUTER JOIN contab_cuentas c on c.id = ca.cuentas_id WHERE ca.nro_comprobante = {$n}";
  		$n = $this->db->query($q)->row();
  		if(!$n){return 'debitado de cuenta';}
  		return $n->nombre;
  	}


  	function get_num_operac_caja(){
  		$q = "SELECT operacion_nro as num FROM contab_asientos ORDER BY id DESC LIMIT 1";
  		$n = $this->db->query($q)->row()->num;
  		if(!$n){return false;}
  		return intval($n)+1;
  	}

  	function insert_pase_entre_cajas($egre,$ingre){
  		$this->db->trans_begin();
  		$op_num_egr= $this->get_num_operac_caja();
      	$egre['operacion_nro'] = $op_num_egr;
     	$this->db->insert('contab_asientos',$egre);
  		$op_num_ingr= $this->get_num_operac_caja();
  		$ingre['operacion_nro'] = $op_num_ingr;
  		$this->db->insert('contab_asientos',$ingre);
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
  	}

  	function get_plcaja_bk($caja,$tipo_asiento,$fec_desde,$fec_hasta){
  		$q = "SELECT a.id, DATE(a.fecha) as fecha ,a.operacion_nro as nro_operac, c.nombre, i.nombre as imputacion,IF(p.name != '',p.name, ac.name)as contraparte, a.nro_comprobante,a.monto,a.observaciones, a.saldo  FROM `contab_asientos` a LEFT OUTER JOIN contab_cuentas c on c.id = a.cuentas_id LEFT OUTER JOIN atoms p on p.id = a.proveedor_id LEFT OUTER JOIN atoms ac on ac.id = a.cliente_id  LEFT OUTER  JOIN contab_cuenta_de_imputacion i on i.id = a.cuenta_imputacion_id WHERE a.estado > 0 AND a.cuentas_id = {$caja} AND a.tipo_asiento = '{$tipo_asiento}' AND a.fecha >= '{$fec_desde}' AND a.fecha <= '{$fec_hasta}' ORDER BY a.id ASC";
  		$r = $this->db->query($q)->result_array();
  		foreach ($r as $key => $rv) {
  			$ccd_q = "SELECT * FROM contab_cc_distrib WHERE asiento_id = {$rv['id']} ";
  			$ccd_arr = $this->db->query($ccd_q)->result_array();
  			$ccd_str = '';
  			foreach ($ccd_arr as $itm){
  				$itm_nom_qry = $this->get_obj("SELECT name FROM atoms WHERE id = {$itm['barrio_id']} ");
  				$itm_nom = (!empty($itm_nom_qry))?$itm_nom_qry->name:'';
  				$ccd_str .= $itm_nom .': '.$itm['percent'].'% - ';

  			}
  			$r[$key]['ccd'] = $ccd_str;
  		}
  		return $r;
  	}

		function get_plcaja($caja,$tipo_asiento,$fec_desde,$fec_hasta){
		$q = "SELECT a.id,
		DATE(a.fecha) as fecha ,
		a.operacion_nro as nro_operac,
		c.nombre, i.nombre as imputacion,
		(CASE WHEN a.proveedor_id > 0 THEN p.name WHEN  a.cliente_id > 0 AND a.lote_id > 0 THEN CONCAT(lt.name, ' ', 	ac.name) WHEN  a.cliente_id > 0 THEN ac.name  WHEN a.cta_contraparte_id > 0 THEN ctac.nombre END) as contraparte,
		a.nro_comprobante,a.monto , a.observaciones
		FROM `contab_asientos` a
		LEFT OUTER JOIN contab_cuentas ctac on a.cta_contraparte_id = ctac.id
		LEFT OUTER JOIN contab_cuentas c on c.id = a.cuentas_id
		LEFT OUTER JOIN atoms p on p.id = a.proveedor_id
		LEFT OUTER JOIN atoms ac on ac.id = a.cliente_id
		LEFT OUTER JOIN atoms lt on lt.id = a.lote_id
		LEFT OUTER  JOIN contab_cuenta_de_imputacion i on i.id = a.cuenta_imputacion_id
		WHERE a.estado > 0 AND a.cuentas_id = {$caja}
		AND a.tipo_asiento = '{$tipo_asiento}'
		AND a.fecha >= '{$fec_desde}'
		AND a.fecha <= '{$fec_hasta}'
		ORDER BY a.id ASC";
		$r = $this->db->query($q)->result_array();
		foreach ($r as $key => $rv) {
			$ccd_q = "SELECT * FROM contab_cc_distrib WHERE asiento_id = {$rv['id']} ";
			$ccd_arr = $this->db->query($ccd_q)->result_array();
			$ccd_str = '';
			foreach ($ccd_arr as $itm){
				$itm_nom_qry = $this->get_obj("SELECT name FROM atoms WHERE id = {$itm['barrio_id']} ");
				$itm_nom = (!empty($itm_nom_qry))?$itm_nom_qry->name:'';
				$ccd_str .= $itm_nom .': '.$itm['percent'].'% - ';

			}
			$r[$key]['ccd'] = $ccd_str;
		}
		return $r;
	}


//  para tirar
  	// function get_saldo($c,$fec_desde,$fec_hasta){
  	// 	$q = "SELECT  * FROM `contab_asientos` WHERE cuentas_id = {$c} AND fecha >= '{$fec_desde}' AND fecha <= '{$fec_hasta}' ORDER BY id DESC LIMIT 1";
  	// 	return $this->db->query($q)->row();
  	// }


  	function get_saldo_previo($c,$f){
  		$this->db->query('SET @T:=0');
  		$q="SELECT operacion_nro,fecha, tipo_asiento, monto, IF(tipo_asiento = 'INGRESOS',(@T:=@T+monto),(@T:=@T-monto)) AS saldo FROM contab_asientos WHERE fecha < '{$f}' AND cuentas_id = {$c}  AND estado > 0 ";
  		$r = $this->db->query($q)->result_array();
  		return (empty($r))?0:end($r)['saldo'];

  	}

  	function get_saldo_anterior($c,$fec_desde){
  		$q = "SELECT  * FROM `contab_asientos` WHERE cuentas_id = {$c} AND fecha < '{$fec_desde}' ORDER BY fecha DESC LIMIT 1";
  		return $this->db->query($q)->row();
  	}
  	// TODAS LAS CUOTAS PAGAS DEL ELEMENT EN ARRAY CON EVENTS_ID DE CADA CUOTA
  	function get_cuotas_info($elem_id,$ev_type,$pcle_lbl,$pcle_val){
  		$q = "SELECT e.date,ep.events_id FROM `events` e JOIN events_pcles ep on e.id = ep.events_id where e.elements_id = {$elem_id} AND e.events_types_id = {$ev_type} AND ep.label = '{$pcle_lbl}' AND ep.value = '{$pcle_val}'";
  		return $this->db->query($q)->result_array();
  	}


	function find_in($tbl,$cond){
		$q= "SELECT * FROM {$tbl} ". $cond;
			return $this->db->query($q)->result_array();
	}

	function get_all_from($tbl,$cond){
		$q= "SELECT * FROM {$tbl} ". $cond;
			return $this->db->query($q)->result_array();
	}



	function get_obj_from($tbl,$cond){
		$q= "SELECT * FROM " . $tbl ." ". $cond;
		return $this->db->query($q)->row();
	}

	function get_value($q){
		$t = $this->db->query($q);
		if(!empty($t)){
			return $t->row()->value;
		}else{
			return "";
		}
	}

	function get_obj($q){
		$t = $this->db->query($q);
		if(!empty($t)){
			return $t->row();
		}else{
			return $t;
		}
	}

	function get_arr($q){
		$t = $this->db->query($q);
		if(!empty($t)){
			return $t->result_array();
		}else{
			return $t;
		}
	}


	function get_count_from($tbl,$cond){
		$q= "SELECT * FROM {$tbl} ". $cond;
			return $this->db->query($q)->num_rows();
	}

	function update($table,$data,$ikey,$id){
		$this->db->trans_start(); # Starting Transaction

		$this->db->where($ikey, $id);
		$this->db->update($table, $data);

		$this->db->trans_complete(); # Completing transaction


		if ($this->db->trans_status() === FALSE) {
		    # Something went wrong.
		    $this->db->trans_rollback();
		    return false;
		}
		else {
		    # Everything is Perfect.
		    # Committing data to the database.
		    $this->db->trans_commit();
		    return true;
		}
	}

	function insert($table,$data){
		$this->db->trans_start(); # Starting Transaction

		$this->db->insert($table, $data); # Inserting data
		$id = $this->db->insert_id();

		$this->db->trans_complete(); # Completing transaction


		if ($this->db->trans_status() === FALSE) {
		    # Something went wrong.
		    $this->db->trans_rollback();
		    return false;
		}
		else {
		    # Everything is Perfect.
		    # Committing data to the database.
		    $this->db->trans_commit();
		    return $id;
		}
	}


	// *OK*

	function get_dpdown_data($tbl,$fields,$modif){
		$f=implode('`,`', $fields);
		$q = "SELECT `{$f}` FROM `{$tbl}` {$modif} ";
		$x = $this->db->query($q);
		return $x -> result_array();
	}



	function get_activities($user_id){
		$q="SELECT elements_id from `actividades` WHERE usuarios_id = {$user_id}";
		$x = $this->db->query($q);
		return ($x)?$x -> row_array() : false;
	}

	function get_visual_elements($id){
		$q="SELECT * from `visual_elements` WHERE id = {$id}";
		$x = $this->db->query($q);
		return ($x)?$x -> row_array() : false;
	}

	public function get_user_data($userid){
		$query = $this -> db -> get_where('usuarios', array('id' => $userid));
		return $query -> row_array();

	}

	// AUTOCOMPLETE DE GET_ELEMENTS SIN RESCINDIDOS
	function atcp_get_elements_new($t){
		// SI TERM ES DIGITO BUSCO ID EN LOTES
		if(preg_match('/(?<!\d)\d{1,4}(?!\d)/', $t)){
			$q = "SELECT CONCAT(a.name,' ' ,a1.name) as label,
			ep.elements_id as id FROM `atoms` a
			JOIN elements_pcles ep on ep.label = 'prod_id' and VALUE = a.id
			JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id AND ep2.label = 'cli_id'
			JOIN elements_pcles eptit on eptit.elements_id = ep.elements_id AND eptit.label = 'titular_id'
			JOIN atoms a1 on a1.id = ep2.value
			WHERE a.atom_types_id = 2 AND a.name LIKE '%{$t}%' LIMIT 20";
		}else{
			// TERM ES CHAR BUSCO EN CLIENTES
			$q = "SELECT
			CONCAT(a1.name,' ' ,a.name) as label,
			ep.elements_id as id FROM atoms a
			JOIN elements_pcles ep on ep.label = 'cli_id' and VALUE = a.id
			JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id AND ep2.label = 'prod_id'
			JOIN atoms a1 on a1.id = ep2.value
			WHERE a.atom_types_id = 1 AND a.name LIKE '%{$t}%' LIMIT 15 ";
		}
		return $this->db->query($q)->result_array();

	}


	// AUTOCOMPLETE DE GET_ELEMENTS SIN RESCINDIDOS

	function atcp_get_elements($t){
		// SI TERM ES DIGITO BUSCO ID EN LOTES
		if(preg_match('/(?<!\d)\d{1,4}(?!\d)/', $t)){
			$q = "SELECT
			CONCAT(a.name,' ' ,a1.name) as label,
			e.id as id
			FROM atoms a
			JOIN elements e on e.owner_id = a.id
			JOIN elements_pcles ep2 on ep2.elements_id = e.id AND ep2.label LIKE 'titular_id'
			JOIN atoms a1 on a1.id = ep2.value
			WHERE a.atom_types_id = 2 AND a.name LIKE '%{$t}%' limit 10";
		}else{
			// TERM ES CHAR BUSCO EN CLIENTES
			$q = "SELECT CONCAT(a1.name,' ' ,a.name) as label , e.id as id FROM `atoms` a
				JOIN elements_pcles ep on ep.label LIKE '%titular_id' and VALUE = a.id
				JOIN elements e on e.id = ep.elements_id
				JOIN atoms a1 on a1.id = e.owner_id
				WHERE a.atom_types_id = 1 AND a1.atom_types_id = 2 AND a.name LIKE '%{$t}%' limit 10 ";
		}
		return $this->db->query($q)->result_array();
	}


	// AUTOCOMPLETE DE GET_ELEMENTS CON RESCINDIDOS
	function atcp_get_elements_CR($t){
		// SI TERM ES DIGITO BUSCO ID EN LOTES
		if(preg_match('/(?<!\d)\d{1,4}(?!\d)/', $t)){
			$q = "SELECT CONCAT_WS(' ',a.name,a1.name) as label, e.id as id FROM `atoms` a
			JOIN elements e on e.owner_id = a.id
			JOIN elements_pcles ep2 on ep2.elements_id = e.id AND ep2.label = 'titular_id'
			LEFT OUTER JOIN atoms a1 on a1.id = ep2.value
            WHERE a.name LIKE '%{$t}%' limit 10";
		}else{
			// TERM ES CHAR BUSCO EN CLIENTES
			$q = "SELECT CONCAT_WS(' ',a.name,a1.name) as label , e.id as id FROM `atoms` a
				JOIN elements_pcles ep on ep.label = 'cli_id' and VALUE = a.id
				JOIN elements e on e.id = ep.elements_id
				JOIN atoms a1 on a1.id = e.owner_id
				WHERE a.atom_types_id = 1 AND a.name LIKE '%{$t}%' limit 10 ";
		}
		return $this->db->query($q)->result_array();

	}


	function atcp_edit_elem($t){

		$q = "SELECT owner_id as label , id FROM `elements` WHERE  owner_id LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}


	// autocomplete de cuenta de imputacion
	function atcp_imputacion($t){
		$q= "SELECT cuenta as label , id FROM `contab_cuenta_de_imputacion` WHERE name LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}



	// autocomplete de lista de precios
	function atcp_lpr($t){
		$q= "SELECT name as label , id FROM `atoms` WHERE atom_types_id = 4 AND name LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}

	// autocomplete de atom name by type
	function atcp_atom_name($t,$n){
		$q= "SELECT name as label , id FROM `atoms` WHERE atom_types_id = {$t}  AND name LIKE '%{$n}%'";
		return $this->db->query($q)-> result_array();
	}


	// autocomplete de emprendimiento
	function atcp_empre($t){
		$q= "SELECT name as label , id FROM `atoms` WHERE atom_types_id = 3 AND name LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}
	// autocomplete de clientes
	function atcp_cli($t){
		$q= "SELECT value as label ,atom_id as id FROM `pcles` WHERE label LIKE 'nombre' AND value LIKE '%{$t}%'LIMIT 50";
		return $this->db->query($q)-> result_array();
	}

	// autocomplete de clientes en venta de lotes
	function atcp_cli_venta_lote($t){
		$q= "SELECT CONCAT(p.value,' ',p2.value,' ',p3.value) as label ,p.atom_id as id FROM `pcles` p
		join atoms_pcles p2 on (p2.atom_id = p.atom_id AND p2.label LIKE 'apellido')
		join atoms_pcles p3 on (p3.atom_id = p.atom_id AND p3.label LIKE 'dni')
		WHERE p.label LIKE 'nombre' AND p.value LIKE '%{$t}%' OR p2.value LIKE '%{$t}%' group by p.atom_id LIMIT 50";
		return $this->db->query($q)-> result_array();
	}

	// autocomplete de lotes disponibles
	function atcp_lotes_disponibles($t){
		$q= "SELECT s.value label, s.atom_id as id FROM `pcles` as D join atoms_pcles s on s.atom_id = D.atom_id WHERE D.label LIKE 'estado' AND D.value LIKE 'DISPONIBLE%' AND s.label LIKE 'name' AND s.value LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}


	function atcp_lotes_vendidos($t){
		$q= "SELECT a.name as label, a.id as id FROM `atoms` as a join atoms_pcles s on s.atom_id = a.id WHERE s.label LIKE 'estado' AND s.value = 'ACTIVO' AND a.name LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}


	function atcp_lotes_vendidos_old($t){
		$q= "SELECT s.value label, s.atom_id as id FROM `pcles` as D join atoms_pcles s on s.atom_id = D.atom_id WHERE D.label LIKE 'estado' AND D.value = 'VENDIDO' AND s.label = 'lote'  AND s.value LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}

	function atcp_cli_by_nomap($t){
		$q = "SELECT CONCAT(p1.value,' ',p2.value) as label, p.atom_id as id FROM `pcles` p
		join atoms_pcles p1 on (p1.atom_id = p.atom_id AND p1.label = 'nombre')
		join atoms_pcles p2 on (p2.atom_id = p.atom_id AND p2.label = 'apellido' )
		WHERE p.value LIKE '%$t%'GROUP BY id LIMIT 50";
		return $this->db->query($q)-> result_array();
	}

	function get_lote_by_cli($cid){
		$q= "SELECT ep2.value  as id, a.name as name from elements_pcles ep
		join elements_pcles ep2 on ep.elements_id = ep2.elements_id AND ep2.label = 'prod_id'
		join atoms  a on a.id = ep2.value
	where ep.label = 'cli_id' and ep.value = {$cid}";
		return $this->db->query($q)-> result_array();
	}

	function atcp_lotes($t){
		$q = "SELECT name as label, id as id FROM `atoms` WHERE atom_types_id = 2 AND name LIKE '{$t}%' LIMIT 50";
		return $this->db->query($q)-> result_array();
	}

	// autocomplete de financiacion
	function atcp_financ($t){
		$q= "SELECT name as label , id FROM `atoms` WHERE atom_types_id = 7 AND name LIKE '%{$t}%'";
		return $this->db->query($q)-> result_array();
	}


}
