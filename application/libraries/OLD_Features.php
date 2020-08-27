<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Features {
  private $table = 'atm_hubs';
  private $struct_tbl = 'atm_structs';
  private $tp_tbl = 'atm_types';
  private $tp_key = 'type_id';
  private $pcle_tbl = 'atm_pcles';
  private $pcle_key = 'atom_id';

        // CodeIgniter issue, lo resuelvo con un super object de CI
        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
  public function __construct(){
    // Assign the CodeIgniter super-object
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
  }


  // ** obtener el struct de un tipo de atom para crear una instancia
  public function create_atom($type_id){
    $st = '';
    $t = $this->Mdb->db->query("SELECT id FROM $this->tp_tbl WHERE id = $type_id ");
    if($t->result_id->num_rows){
      $st = $this->Mdb->db->query("SELECT * FROM $this->struct_tbl WHERE $this->tp_key = {$t->row()->id} ORDER BY vord ASC")->result_array();
    }
    return $st;

  }
  // *** retorna los objetos del main container
  public function pcles_to_json($o){
    $r=[];
    foreach($o['pcles'] as $k=>$v) {
      $x = new Atom($v['value']);
      $r[] = $x->expose_as_json_obj();
    }
    return $r;
  }



  // ****** FEature LIST PRODUCTOS  *******
  // *** recibe un array de params donde atom_types_id es requerido
  // ***
public function list($p){
  $pr = $this->Mdb->query("SELECT id FROM atoms WHERE atom_types_id = {$p['atom_types_id']} ORDER BY id ASC");

  // $pr = $this->Mdb->query("SELECT * FROM atoms");
  if($pr->result_id->num_rows){
    foreach ($pr->result_array() as $prx) {
      var_dump($prx['id']);
    }
  }

  // $r = [];
  //
  // foreach ($pr as $x) {
  // 	$r[] = (new Atom($x['']))
  // 	$lt = new Atom($l['id']);
  // 	// $x=$lt->get_props();
  // 	$r[]=[
  // 		'id'=>$lt->id
  // 		,'name'=>$lt->get_pcle('name')->value
  // 		,'emprendimiento'=>$lt->get_pcle('emprendimiento')->value
  // 		,'estado'=>$lt->get_pcle('estado')->value
  // 	];
  // }
  // if(empty($r)){
  // 	$c = 'myAlert';
  // 	$res =[
  //         'tit'=>'Listado de Lotes '
  //         ,'msg'=>'error de Query'
  //         ,'type'=>'danger'
  //         ,'container'=>'modal'
  //     ];
  // }else{
  // 	$c = 'front_call';
  // 	$res = [
  // 		'method'=> 'list_lotes'
  // 		,'action'=>'res_ok'
  // 		,'data'=>$r
  // 	];
  // }
  //   echo json_encode(array(
  //     'callback'=> $c,
  //     'param'=> $res
  //   ));
  }

}
