<?php
Class User extends CI_Model
{
	public function __construct() {
		include (APPPATH . 'JP_classes/Atom.php');
		include (APPPATH . 'JP_classes/Element.php');

	}


	function login($usuario, $clave)
	{

		$q = "SELECT * FROM usuarios WHERE usr_usuario = '{$usuario}' AND clave_usuario = '{$clave}' LIMIT 1";
		$x = $this -> db -> query($q);
		if(! empty($x))
		{
			$this->db->close();
			return $x->result();
		}
		else
		{
			$this->db->close();
			return false;
		}
	}

	function login_cli($user_dni)
	{
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
		$this->db->close();
		return $res;
}

	// -- LEFT OUTER JOIN elements_pcles et on et.label LIKE '%titular'AND et.value = c.atom_id
	// -- LEFT OUTER JOIN elements_pcles el on el.label = 'prod_id' AND el.elements_id = e.elements_id
	//LEFT OUTER JOIN atoms l on l.id = el.value


	function login_cli_old($user_dni)
	{
		$x = $this -> db -> query(
			"SELECT cap.value as apellido, cnm.value as nombre, c.value as dni,c.atom_id as user_id, e.elements_id,l.name as lote_name FROM atoms_pcles c
			LEFT OUTER join atoms_pcles cap ON cap.label = 'apellido'AND cap.atom_id = c.atom_id
			LEFT OUTER join atoms_pcles cnm ON cnm.label = 'nombre' AND cnm.atom_id = c.atom_id
			LEFT OUTER JOIN elements_pcles e on e.label = 'cli_id' AND e.value = c.atom_id
			LEFT OUTER JOIN elements_pcles et on et.label LIKE '%titular'AND et.value = c.atom_id
			LEFT OUTER JOIN elements_pcles el on el.label = 'prod_id' AND el.elements_id = e.elements_id
			LEFT OUTER JOIN atoms l on l.id = el.value
			WHERE c.label = 'dni' AND c.value = {$user_dni} AND e.elements_id != 'NULL' ");
		if(! empty($x))
		{
			return $x->result();
		}
		else
		{
			return false;
		}
	}

	function u_data($id){
		// $this -> db -> select('nombre, apellido, email, direccion, ciudad, provincia, idpais');
		// $this -> db -> from('usuarios');
		// $this -> db -> where("id_usuario = " . "'" . $id. "'");
		// $this -> db -> limit(1);

		$q = "SELECT * FROM usuarios WHERE id_usuario = {$id} LIMIT 1 ";
		$x = $this -> db -> query($q);
		if(! empty($x))
		{
			$this->db->close();
			return $x->result();
		}
		else
		{
			$this->db->close();
			return false;
		}


	}
}
?>
