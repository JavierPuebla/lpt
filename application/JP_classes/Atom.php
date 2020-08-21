<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Atom {
  public $id;
  public $name;
  public $type;

  public $type_id;
  public $db_name = 'atoms';
  public $struct_db_name = 'atoms_struct';
  public $types_db_name = 'atom_types';
  public $types_ref_name = 'atom_types_id';
  public $pcles_db_name = 'atoms_pcles';
  public $foreign_key = 'atom_id';
  public $visual_objects_db_name = 'visual_objects';
  public $pcles = [];


  public function __construct($id=0,$type=null,$name=null) {
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    if($id <= 0){
      $this->type_id = $this->verify_type($type);
      if(!empty($this->type_id)){
        // SI ESTA EN LA BASE DE DATOS LINKEA EL ID EXISTENTE SINO CREA UNO NUEVO
        $this->name= $name;
        $this->type = $type;
        $this->id_handler($id,$type,$name);
      }else{
        $this->id = null;
        $this->name = $name;
        $this->type = 'WRONG_TYPE';
      }
    }else{
        // ID ES MAYOR QUE 0 TRAE EL RECORD QUE ESTA EN LA TABLA
          $a = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE id = {$id}")->row();
        if(!empty($a)){
          //  PONGO LOS DATOS DEL RECORD EN MEM
          $this->start_atom($a);
        }else{
          $this->id = null;
          $this->name = 'EMPTY';
          $this->type = 'EMPTY';
        }
    }
  }

  function expose_as_json(){
      $r=[];
      foreach ($this->pcles as $v) {
        // *** SI EL PCLE CONTIENE UN JSON OBJ LO DECODEAMOS SINO PASA EL VALOR TAL COMO ESTA
        $val = (strpos($v->label,'_json') > -1 )?json_decode($v->value, true):$v->value;
        $r[$v->label]=$val;
      }
      //*** RETORNA EL ARRAY COMO UN JSON OBJECT
    return json_encode($r);
  }


  private function verify_type($t){
    $t = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} WHERE name = '{$t}'")->row();

    if(empty($t)){
      return null;
    }else{
      return $t->id;
    }
  }

  //  SETTINGS DEL ATOM
  private function start_atom($a){
    $this->id = $a->id;
    $this->name = $a->name;
    $this->last_update = $a->last_update;
    $trf = $this->types_ref_name;
    $this->type_id = $a->$trf;
    $this->type = $this->Mdb->db->query("SELECT name FROM {$this->types_db_name} WHERE id = ".$this->type_id)->row()->name;
    $this->pcles = $this->get_all_pcles();
    // $this->struct = $this->init_struct();
  }


  // ACTUALIZA LOS PCLES Y RETORNA EL STRUCT
  private function init_struct(){
    // CURRENT STRUCT BY ID
    $x = $this->Mdb->db->query("SELECT id,label FROM {$this->struct_db_name} WHERE $this->types_ref_name = $this->type_id ");
    // var_dump($x->result_array());
    if($x->result_id->num_rows){
      // RECORRE STRUCT Y COMPARA CON PCLES
      // SI NO ESTA EL ID DEL STRUCT  AGREGA EL STRUCT EN PCLES
      foreach ($x->result() as $str) {
        $sid = $str->id;
        $f = array_filter($this->pcles,function($cp) use($sid){return $cp->struct_id == $sid;});
        if(empty($f)){
          // echo '<br>adding pcle...'.$sid;
          $p = $this->Mdb->db->insert($this->pcles_db_name,[
              $this->foreign_key => $this->id,
              $this->types_ref_name=>$this->type_id,
              'struct_id'=>$str->id,
              'label'=>$str->label,
            ]);
          // echo 'p'.$p;
        }else{
          // echo '<br>already in struct: '.$sid;
        }
      }
      $this->pcles = $this->get_all_pcles();
    }
  }

    //  OBTIENE LOS PCLES POR DEFAULT DE LA TABLA STRUCT
  private function fill_pcles_with_struct(){
    $this->struct = $this->Mdb->db->query("SELECT * FROM {$this->struct_db_name} WHERE $this->types_ref_name = ".$this->type_id)->result_array();
    // $c = array_map(function($i){$this->set_pcle(0,$i['label'],$i['value'],$i['title'],$i['vis_elem_type'],$i['vis_ord_num']);},$this->struct);
    //  STRUCT IMPLICIT MODE
    $c = array_map(function($i){
      $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          'atom_types_id'=>$this->type_id,
          'struct_id'=>$i['id'],
          'label'=>$i['label'],
        ]);
      },$this->struct);
    $this->pcles = $this->get_all_pcles();
  }


  function get_all_pcles(){
    // return $this->Mdb->db->query("SELECT p.id,p.atom_id,p.value,s.label,s.title,s.vis_elem_type,s.vis_ord_num,s.validates FROM {$this->pcles_db_name} p JOIN $this->struct_db_name s on s.id = p.struct_id WHERE {$this->foreign_key} = {$this->id} ORDER BY s.vis_ord_num ASC")->result();
    return $this->Mdb->db->query("SELECT
      p.id,
      p.atom_id,
      p.struct_id,
      p.value,
      s.label,
      s.title,
      vo.nombre as vis_elem_type,
      s.vis_ord_num,
      s.validates
      FROM {$this->pcles_db_name} p
      JOIN $this->struct_db_name s on s.id = p.struct_id
      JOIN $this->visual_objects_db_name vo on vo.id = s.vis_elem_type
      WHERE $this->foreign_key = $this->id ORDER BY s.vis_ord_num ASC")->result();
  }





  public function old__construct($id=0,$type=null,$name=null) {
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this->db_name = 'atoms';
    $this->types_db_name = 'atom_types';
    $this->types_ref_name = 'atom_types_id';
    $this->pcles_db_name = 'atoms_pcles';
    $this->foreign_key = 'atom_id';
    $this->pcles = [];
    //** ID CERO
    //  checkea el tipo y crea con
    if($id == 0){
      $this->name = $name;
      $this->type = $type;
      $q = "SELECT id FROM {$this->types_db_name} WHERE name = '{$type}' ";
      $ch = $this->Mdb->db->query($q)->row();
      if(empty($ch)){
        $this->type = null;
        $this->id = null;
        $this->name = null;
      }
      else{
        $this->type_id = $ch->id;
        $this->id_handler();
      }
    }
    else{
      // TRAE EL RECORD DE LA TABLA
      $id_ok = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE id = {$id}")->row();
      if(!empty($id_ok)){
        $this->id = $id_ok->id;
        $this->name = $id_ok->name;
        $this->type_id = $id_ok->atom_types_id;
        $this->type = $this->Mdb->db->query("SELECT name FROM {$this->types_db_name} WHERE id = ".$id_ok->atom_types_id)->row()->name;
        $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = '$id_ok->id'")->result();
      }else{
        $this->type = null;
        $this->id = null;
        $this->name = null;
      }
    }
  }

    //  CREA EL OBJ
  // ID = 0 BUSCA TYPE Y NAME ANTES DE CREAR EL RECORD
  // ID = -1 CREA EL RECORD SIN BUSCAR
  public function id_handler($id,$type,$name){
    if($id === 0){
      // OBTIENE EL TYPE ID, Y BUSCA POR TYPE + name EN LA TABLA ELEMENTS,
      $this->type_id = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} where name LIKE '{$type}'")->row()->id;
      $t = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE {$this->types_ref_name} = {$this->type_id} AND name = '{$this->name}'")->row();
      if(!empty($t)){
        // ESTA EN LA TABLA...  PONE EL OBJETO EN MEMORIA
        $this->start_atom($t);
      }else{
        // NO ESTA EN LA TABLA, CREA EL RECORD Y PONE EN MEMORIA
        $this->Mdb->db->insert($this->db_name,['name' => $this->name, $this->types_ref_name => $this->type_id]);
        $this->id = $this->Mdb->db->insert_id();
        $this->fill_pcles_with_struct();
      }
    }
    if($id == -1){
      // CREA EL RECORD EN LA TABLA Y PONE EN MEMORIA
      $this->name = $name;
      $this->type_id = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} where name LIKE '{$type}'")->row()->id;
      $this->Mdb->db->insert($this->db_name,['name' => $this->name, $this->types_ref_name => $this->type_id]);
      $this->id = $this->Mdb->db->insert_id();
      $this->fill_pcles_with_struct();
    }
  }


  public function old_id_handler(){
    if(empty($this->name)){
      // CREA EL RECORD EN LA TABLA ,
      // CREA LOS PCLES DE STRUCT Y  CARGA TODO EN MEMORIA
      // $this->Mdb->db->insert($this->db_name,['name' => 'temp_'.rand() , $this->types_ref_name => $this->type_id]);
      // $this->id = $this->Mdb->db->insert_id();
      // $this->set_struct();
      // $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
      $this->type = null;
      $this->id = null;
      $this->name = null;
    }else{
      // GETTING ATOM BY NAME
      $t = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE {$this->types_ref_name} = $this->type_id AND  name = '{$this->name}'")->row();
      if(!empty($t)){
        // ESTA EN LA TABLA...  CREA EL OBJETO EN MEMORIA
        $this->id = $t->id;
        $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
      }else{
        // CREA LOS PCLES DE STRUCT Y  CARGA TODO EN MEMORIA
        $this->Mdb->db->insert($this->db_name,['name' => $this->name , $this->types_ref_name => $this->type_id]);
        $this->id = $this->Mdb->db->insert_id();
        $this->set_struct();
        $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
        $pn = $this->get_pcle('nombre');
        if(!empty($pn)){
          $this->set_pcle($pn->id,'nombre',$this->name);
        }
      }
    }
  }


  public function get_props(){
    return ['id'=>$this->id,'name'=>$this->name,'type'=>$this->type,'pcles'=>$this->pcles];
  }

  public function get($prop){
    return $this->$prop;
  }

  public function get_type_name(){
    return $this->Mdb->db->query("SELECT name FROM $this->types_db_name  WHERE id = {$this->type_id} ")->row()->name;
  }

  public function set($prop,$value){
      $this->$prop = $value;
      $this->Mdb->db->where('id', $this->id);
      $this->Mdb->db->update($this->db_name, [$prop=>$value]);
    }

  public function kill()
  {
    if(!empty($this->id)){
      $this->Mdb->db->query("DELETE FROM {$this->pcles_db_name} where {$this->foreign_key} = {$this->id}");
      $this->Mdb->db->query("DELETE FROM {$this->db_name} where id = {$this->id}");
    }
  }

   // UPDATES SOLO EL VALUE POR EL PCLE ID
  public function pcle_updv($pcle_id,$value){
    $this->Mdb->db->where('id', $pcle_id);
    $this->Mdb->db->update($this->pcles_db_name, ['value'=>$value]);
    $this->pcles = $this->get_all_pcles();
  }

  // muestra datos del element
  function expose(){
    return $this->get_all_pcles();
  }


 // TRAE EL PCLE POR SU LABEL
  public function get_pcle($lbl=null){
    //  SI LABEL ESTA VACIO TRAE TODOS
    if(empty($lbl)){
      return $this->pcles;
    }else{
      // busco en el array cargado en __construc
      foreach ($this->pcles as $p) {
        if($p->label === $lbl){return $p;}
      }
      return (object) ['id'=>0,'value'=>''];
    }
  }

  //*** GUARDA EL PCLE
  public function set_pcle($pcle_id,$label,$value){
    if($pcle_id === 0){
      //*** GETTING STRUCT_ID FROM STRUCTS DB
        $struct_id = $this->Mdb->db->query("SELECT id FROM $this->struct_db_name WHERE $this->types_ref_name = {$this->type_id} AND label = '{$label}' ");
      // BUSCA EL label
      $s = $this->get_pcle($label);
      if(empty($s->id)){
        $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          'struct_id'=>($struct_id->result_id->num_rows > 0)?$struct_id->row()->id:0,
          'label'=>$label,
          'value'=>$value,
        ]);
        $this->pcles = $this->get_all_pcles();
        // fix del array por el pcle insertado sin struct_id
        $this->pcles[count($this->pcles)-1]->label = $label;
      }else{
        $this->pcle_updv($s->id,$value);
      }
    }
    else{
      $this->pcle_updv($pcle_id,$value);
    }
  }


  // ATOM => CUSTOMIZAR EL ATOM DESDE SU TABLA DE ESTRUCTURA
  //  DEPRECATE
  // public function set_struct(){
  //   $p= $this->Mdb->db->query("SELECT * FROM atoms_struct WHERE atom_types_id = {$this->type_id} ORDER BY vis_ord_num ASC ")->result();
  //   foreach ($p as $pcl) {
  //     $this->set_pcle(0,$pcl->label,$pcl->value,$pcl->title,$pcl->vis_elem_type,$pcl->vis_ord_num);
  //   }
  // }

  // TRAE LA ESTRUCTURA DEL OBJETO LA COMPRA CON LA ESTRUCTURA GENERAL, SI FALTA ALGO LO COMPLETA, CAMBIA VIS_ELEM_TYPE ID POR ELNOMBRE RELATIVO

  function get_struct(){
    $p=[];
    $r = $this->Mdb->db->query("SELECT * FROM atoms_struct WHERE atom_types_id = {$this->type_id} ORDER BY vis_ord_num ASC ")->result_array();
    foreach ($r as $k) {
      $x = $this->get_pcle($k['label']);
      if(!empty($x->id)){
        $x->vis_elem_type = $this->set_vis_elem_type_name($x);
        $p[] = $x;
      }else{
        $this->set_pcle(0,$k['label'],$k['value'],$k['title'],$k['vis_elem_type'],$k['vis_ord_num']);
        $w = $this->get_pcle($k['label']);
        $w->vis_elem_type = $this->set_vis_elem_type_name($w);
        $p[] = $w;
      }
    }
    return $p;
  }

  function set_vis_elem_type_name($x){
    $id = (!empty($x->vis_elem_type))?$x->vis_elem_type:1;
    $r = $this->Mdb->db->query("SELECT * FROM pcle_types WHERE id = {$id}")->row();
    if(!empty($r)){
       return $r->type;
    }else{
      return 'text';
    }
  }

  // GUARDA CADA PCLE  OLD
  /*
  public function set_pcle($pcle_id,$label,$value,$title='',$vet=1,$von=-1){
    if($pcle_id == 0){
      $t = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} AND label LIKE '{$label}' ")->row();
      if(!empty($t)){
        $this->Mdb->db->where('id', $t->id);
        $this->Mdb->db->update($this->pcles_db_name, ['label'=>$label,'value'=>$value,'title'=>$title,'vis_elem_type'=>$vet,'vis_ord_num'=>$von]);
      }else{
        $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          'label'=>$label,
          'value'=>$value,
          'title'=>$title,
          'vis_elem_type'=>$vet,
          'vis_ord_num'=>$von
        ]);
      }
    }
    else{
      $this->Mdb->db->where('id', $pcle_id);
      $this->Mdb->db->update($this->pcles_db_name, ['label'=>$label,'value'=>$value]);
    }
  }
  */

    // GUARDA TODOS LOS DATOS DE UN PCLE
  // public function set_pcle($pcle_id,$label,$value,$title=null,$vet=null,$vord=null){
  //   //**** NO SE SI ESTA CREADO EL PCLE, BUSCO EL LABEL
  //   if($pcle_id == 0){
  //     $t = $this->Mdb->db->query("SELECT id FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} AND label LIKE '{$label}'");
  //     // SI ENCUENTRA LBL UPDATES PLCE
  //     var_dump($t);
  //     exit;

  //     if(!empty($t)){
  //       $this->pcle_updt($pcle_id,$label,$value,$title);
  //     }else{
  //       // NO ENCONTRO EL PCLE LO CREA
  //       $this->pcle_new($label,$value,$title,$vet,$vord);
  //     }
  //   }
  //   //**** TENGO PCLE ID HAGO UPDATE
  //   else{
  //     $this->pcle_updt($pcle_id,$label,$value,$title);
  //   }
  //   $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
  // }



  // GUARDA CADA PCLE
  // DEPRECATE
  // public function set_pcle($pcle_id,$label,$value,$title=null,$vet=null,$vord=null){
  //   if($pcle_id == 0){
  //     $t = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = $this->id AND label LIKE '{$label}' ")->row();
  //     if(!empty($t)){
  //       $this->update_pcle($pcle_id,$label,$value,$title,$vet,$vord);
  //     }else{
  //       $p = $this->Mdb->db->insert($this->pcles_db_name,[
  //         $this->foreign_key => $this->id,
  //         $this->types_ref_name => $this->type_id,
  //         'label'=>$label,
  //         'value'=>$value,
  //         'title'=>($title == '')?$this->mk_title($label):$title,
  //         'vis_elem_type'=>$vet,
  //         'vis_ord_num'=>$vord
  //       ]);
  //     }
  //     $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
  //   }
  //   else{
  //     $this->update_pcle($pcle_id,$label,$value,$title,$vet,$vord);
  //   }
  //   $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
  // }

  // function update_pcle($pcle_id,$label=null,$value,$title=null,$vet=null,$vord=null){
  //   $p = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE id = {$pcle_id} ")->row();
  //     if(!empty($p)){
  //       $upd_lbl = (!empty($label) && $label != $p->label)?$label:$p->label;
  //       $upd_title = (!empty($title) && $title != $p->title)?$title:$p->title;
  //       $upd_vet = (!empty($vet) && $vet != $p->vis_elem_type)?$vet:$p->vis_elem_type;
  //       $upd_vord = (!empty($vord) && $vord != $p->vis_ord_num)?$vord:$p->vis_ord_num;
  //       $this->Mdb->db->where('id', $pcle_id);
  //       $this->Mdb->db->update($this->pcles_db_name, ['label'=>$upd_lbl,'value'=>$value,'title'=>$upd_title,'vis_elem_type'=>$upd_vet,'vis_ord_num'=>$upd_vord]);
  //     }


  // }


  // function pcle_updv($pcle_id,$value){
  //   $this->Mdb->db->where('id', $pcle_id);
  //     $this->Mdb->db->update($this->pcles_db_name, ['value'=>$value]);
  // }



  // function pcle_updt($pcle_id,$label,$value,$title){
  //   $this->Mdb->db->where('id', $pcle_id);
  //     $this->Mdb->db->update($this->pcles_db_name, [
  //       'label'=>$label,
  //       'value'=>$value,
  //       'title'=>(!empty($title))?$title:$this->mk_title($label)
  //     ]);
  // }

  // function pcle_new($label,$value,$title,$vet,$vord){
  //   $this->Mdb->db->insert(
  //     $this->pcles_db_name,
  //     [
  //       $this->foreign_key => $this->id,
  //         'label'=>$label,
  //         'value'=>$value,
  //         'title'=>(!empty($title))?$title:$this->mk_title($label),
  //         'vis_elem_type'=>(!empty($vet))?$vet:1,
  //         'vis_ord_num'=>(!empty($vord))?$vord:-1
  //     ]
  //   );
  // }

  // public function get_pcle($lbl=''){
  //   $r = (object) array('id' => null, 'label' => $lbl, 'value' => null);
  //   if($lbl === ''){
  //     return $this->pcles;
  //   }else{
  //     foreach ($this->pcles as $p) {
  //       if($p->label == $lbl){
  //         $r = $p;
  //       }
  //     }
  //     return $r;
  //   }
  // }



//   function kill_pcle_arr_item($pcle_id,$item){
//     $rdb = $this->Mdb->db->query("SELECT * FROM  {$this->pcles_db_name} WHERE id = {$pcle_id}")->row();

//     $varr = explode(',',$rdb->value);
//     if (($key = array_search($item, $varr)) !== false) {
//         unset($varr[$key]);
//     }
//     $r = implode(',',$varr);
//     $this->set_pcle($pcle_id,$rdb->label,$r);
//   }


//   //  HACE DEL LABEL UN TITULO
//   function mk_title($l){
//     $r = str_replace('_', ' ', $l);
//     return ucwords($r);
//   }


}
