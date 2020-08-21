<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Element {
  public $id;
  public $type_id;
  public $ev_types_id = 8 ;// esta seteado en 8 Future cuota por que de momento es el tipo general de evento
  public $db_name = 'elements';
  public $struct_db_name = 'elements_struct';
  public $ev_struct_db_name = 'events_struct';
  public $types_db_name = 'elements_types';
  public $types_ref_name = 'elements_types_id';
  public $ev_types_ref_name = 'events_types_id';
  public $pcles_db_name = 'elements_pcles';
  public $foreign_key = 'elements_id';
  public $visual_objects_db_name = 'visual_objects';
  public $owner_id;
  public $type;
  public $pcles = [];



  public function __construct($id=0,$type=null,$owner_id=null) {
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    if($id <= 0){
      $this->type_id = $this->verify_type($type);
      if(!empty($this->type_id)){
        // SI ESTA EN LA BASE DE DATOS LINKEA EL ID EXISTENTE SINO CREA UNO NUEVO
        $this->owner_id = $owner_id;
        $this->type = $type;
        $this->id_handler($id,$type,$owner_id);
      }else{
        $this->id = null;
        $this->owner_id = $owner_id;
        $this->type = 'WRONG_TYPE';
      }
    }else{
        // ID ES MAYOR QUE 0 TRAE EL RECORD QUE ESTA EN LA TABLA
          $e = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE id = {$id}")->row();
        if(!empty($e)){
          //  PONGO LOS DATOS DEL RECORD EN MEM
          $this->start_element($e);
        }else{
          $this->id = null;
          $this->owner_id = $owner_id;
          $this->type = 'EMPTY';
        }
    }
  }

  private function verify_type($t){
    $t = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} WHERE name = '{$t}'")->row();

    if(empty($t)){
      return null;
    }else{
      return $t->id;
    }
  }





    // **********************************************
    // *** Nueva version de get_all_pcles con vis_elem_type desde la tabla visual objects y control de validates
    // **********************
    // COMBINA LOS ID DE PCLES CON LOS ITEMS DE STRUCT
    function get_all_pcles(){
      return $this->Mdb->db->query("SELECT
        p.id,
        p.elements_id,
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

    // agregado para datos de print boleto
    function get_monto_ciclo1(){
      $tot_ctas = intval($this->get_pcle('cant_ctas')->value);
      $cant_ctas_ciclo_2 = intval($this->get_pcle('cant_ctas_ciclo_2')->value);
      $monto_cta_1 = intval($this->get_pcle('monto_cta_1')->value);
      $res = $monto_cta_1 * (($tot_ctas - $cant_ctas_ciclo_2)-1);
      return $res;
    }


  // OBTIENE EL PCLE DESDE LA DB
  function get_pcle_db($label){
    $q = $this->Mdb->db->query("SELECT value FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} AND label LIKE '{$label}'");
    if($q->result_id->num_rows){
      return $q->row()->value;
    }else{
      return null;
    }
  }

  //********* ANTES DEL UPDATE DE ELEMENTS_STRUCT **** get all pcles debe tomar los records desde db en esta forma luego va con el formato de join de struct y pcles
  // function get_all_pcles(){
  //   return $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} ")->result();
  // }

  //  SETTINGS DEL ELM
  private function start_element($e){
    $this->id = $e->id;
    $this->owner_id = $e->owner_id;
    $this->last_update = $e->last_update;
    $trf = $this->types_ref_name;
    $this->type_id = $e->$trf;
    $this->type = $this->Mdb->db->query("SELECT name FROM {$this->types_db_name} WHERE id = ".$this->type_id)->row()->name;
    $this->struct = $this->Mdb->db->query("SELECT * FROM {$this->struct_db_name} WHERE $this->types_ref_name = {$this->type_id}")->result();
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
              $this->types_ref_name =>$this->type_id,
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




  //  OBTIENE LOS PCLES POR DEFAULT DE LA TABLA DESTRUCT
  private function fill_pcles_with_struct(){
    $this->struct = $this->Mdb->db->query("SELECT * FROM {$this->struct_db_name} WHERE $this->types_ref_name = ".$this->type_id )->result_array();
    // $c = array_map(function($i){$this->set_pcle(0,$i['label'],$i['value'],$i['title'],$i['vis_elem_type'],$i['vis_ord_num']);},$this->struct);
    //  STRUCT IMPLICIT MODE
    $c = array_map(function($i){
      $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          $this->types_ref_name =>$this->type_id,
          'struct_id'=>$i['id'],
          'label'=>$i['label'],
        ]);
      },$this->struct);
    $this->pcles = $this->get_all_pcles();
  }

  // COLECTA LA ESTRUCTURA Y SUBDIVIDE EN PARES DE ID LABEL PARA SELECTS
  // PARA CONSTRUIR LA BARRA DE SELECCION DE FILTROS
  public function get_filters(){
    $str = $this->Mdb->db->query("SELECT id,label,title,filter_type FROM {$this->struct_db_name} WHERE reports = 1 AND $this->types_ref_name = {$this->type_id} ORDER BY vis_ord_num " )->result_array();
    $report_view = $this->Mdb->db->query("SELECT name FROM {$this->types_db_name} WHERE id = {$this->type_id}")->row()->name;
    $f_index =[];
    $c = 0;
    foreach ($str as $str_itm) {
      $fitms = $this->Mdb->db->query("SELECT DISTINCT(value),a.name FROM elements_pcles ep LEFT OUTER JOIN atoms a on a.id = ep.value WHERE struct_id = {$str_itm['id']} AND ep.value IS NOT NULL AND ep.value != -1 ");
      $x=[];
      if($fitms->result_id->num_rows){
        $x = $fitms->result_array();
      }
      $f_index[]=['filter_type'=>$str_itm['filter_type'],'label'=>$str_itm['label'],'title'=>$str_itm['title'],'count'=>count($x),'cnt'=>$x];
    }
    return $f_index;
  }



  //  CREA EL OBJ
  // ID = 0 BUSCA TYPE Y OWNER_ID ANTES DE CREAR EL RECORD
  // ID = -1 CREA EL RECORD SIN BUSCAR
  public function id_handler($id,$type,$owner_id){
    if($id === 0){
      // OBTIENE EL TYPE ID, Y BUSCA POR TYPE + OWNER_ID EN LA TABLA ELEMENTS,
      $this->type_id = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} where name LIKE '{$type}'")->row()->id;
      $t = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE {$this->types_ref_name} = {$this->type_id} AND owner_id = '{$this->owner_id}'")->row();
      if(!empty($t)){
        // ESTA EN LA TABLA...  PONE EL OBJETO EN MEMORIA
        $this->start_element($t);
      }else{
        // NO ESTA EN LA TABLA, CREA EL RECORD Y PONE EN MEMORIA
        $this->Mdb->db->insert($this->db_name,['owner_id' => $this->owner_id, $this->types_ref_name => $this->type_id]);
        $this->id = $this->Mdb->db->insert_id();
        $this->fill_pcles_with_struct();
      }
    }
    if($id == -1){
      // CREA EL RECORD EN LA TABLA Y PONE EN MEMORIA
      $this->owner_id = $owner_id;
      $this->type_id = $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} where name LIKE '{$type}'")->row()->id;
      $this->Mdb->db->insert($this->db_name,['owner_id' => $this->owner_id, $this->types_ref_name => $this->type_id]);
      $this->id = $this->Mdb->db->insert_id();
      $this->fill_pcles_with_struct();
    }
  }




  public function get_props(){
    $p = $this->get_all_pcles();
    $x = [];
    foreach ($p as $pcle) {
      if(strpos($pcle->label, '_id') > 0 && intval($pcle->value) > 0){
        $ap = new Atom($pcle->value);
        $x[] = [$pcle->label => $ap->get_props()];
      }
    }


    return ['id'=>$this->id,'owner_id'=>$this->owner_id,'owner_type'=>$this->get_owner_type(),'owner_name'=>$this->get_owner_name(),'owner_props'=>$this->get_owner_props(),'type'=>$this->type,'pcles'=>$this->get_all_pcles(),'extra_data'=>$x,'events'=>$this->get_events_all()];
  }

  public function get_active_props(){
    $p = $this->get_all_pcles();
    $x = [];$active_pcles=[];
    foreach ($p as $pcle) {
      if(strpos($pcle->label, '_id') > 0 && intval($pcle->value) > 0){
        $ap = new Atom($pcle->value);
        $x[] = [$pcle->label => $ap->get_props()];
      }
      if($pcle->vis_ord_num > 0  && $pcle->vis_ord_num < 99){
        $active_pcles[]=$pcle;
      }
    }
    return [
      'id'=>$this->id,
      'owner_id'=>$this->owner_id,
      'owner_type'=>$this->get_owner_type(),
      'owner_name'=>$this->get_owner_name(),
      'owner_props'=>$this->get_owner_props(),
      'type'=>$this->type,
      'pcles'=>$active_pcles,
      'extra_data'=>$x,
      'events'=>$this->get_events_all()];
  }


  public function get($prop){
    return $this->$prop;
  }


  public function set($prop,$value){
    $this->$prop = $value;
    $this->Mdb->db->where('id', $this->id);
    $this->Mdb->db->update($this->db_name, [$prop=>$value]);
  }




  // GUARDA CADA PCLE
  public function set_pcle_back($pcle_id,$label,$value,$title='',$vet='text',$vord=0){
    if($pcle_id == 0){
      $t = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = $this->id AND label LIKE '{$label}' ")->row();
      if(!empty($t)){
        $this->update_pcle($t->id,$label,$value,$title,$vet,$vord);
        // $this->pcle_updv($t->id,$value);

      }else{
        //*** GETTING STRUCT_ID FROM STRUCTS DB
        $struct_id = $this->Mdb->db->query("SELECT id FROM $struct_db_name WHERE $types_ref_name = {$this->type} AND label = $label ");

        //*** INSERTANDO EL VALUE EN DB
        $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          $this->types_ref_name => $this->type_id,
          'label'=>$label,
          'struct_id'=>($struct_id->result_id->num_rows > 0)?$struct_id->row()->value:0,
          'value'=>$value,
          'title'=>($title == '')?$this->mk_title($label):$title,
          'vis_elem_type'=>$vet,
          'vis_ord_num'=>$vord
        ]);
      }
      $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
    }
    else{
      $this->update_pcle($pcle_id,$label,$value,$title,$vet,$vord);
    }
    $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id}")->result();
  }

  // UPDATES PLCE SI EL NUEVO CONTENIDO ES DISTINTO DEL EXISTENTE
  // function update_pcle($pcle_id,$label=null,$value,$title='',$vet='text',$vord=0){
  //   $p = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE id = {$pcle_id} ")->row();
  //   if(!empty($p)){
  //     $upd_lbl = (!empty($label) && $label != $p->label)?$label:$p->label;
  //     $upd_title = (!empty($title) && $title != $p->title)?$title:$p->title;
  //     $upd_vet = (!empty($vet) && $vet != $p->vis_elem_type)?$vet:$p->vis_elem_type;
  //     $upd_vord = (!empty($vord) && $vord != $p->vis_ord_num)?$vord:$p->vis_ord_num;
  //     $this->Mdb->db->where('id', $pcle_id);
  //     $this->Mdb->db->update($this->pcles_db_name, ['label'=>$upd_lbl,'value'=>$value,'title'=>$upd_title,'vis_elem_type'=>$upd_vet,'vis_ord_num'=>$upd_vord]);
  //   }
  // }

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
        if($p->label === $lbl && !empty($p)){return $p;}
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
          'elements_types_id'=>$this->type_id,
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



  function kill(){
    $this->Mdb->db->query("DELETE FROM {$this->pcles_db_name} where {$this->foreign_key} = {$this->id}");
    $this->Mdb->db->query("DELETE FROM {$this->db_name} where id = {$this->id}");
  }

  function kill_event($ev_id){
    $a = $this->Mdb->db->query("DELETE FROM events_pcles WHERE events_id = ". $ev_id);
    $b = $this->Mdb->db->query("DELETE FROM events WHERE id = ". $ev_id);
  }

  //  cambia el id de los eventos marcados en $prev_event
  // usado para actualizaciones de contrato
  function change_event_type($ev_id=0,$prev_type=null,$new_type=null){
    if(empty($new_type) || empty($prev_type)){exit('Error: new_type no puede ser null en el llamado a la funcion -> change_ev_type ');}
    $chk_prev_type = $this->Mdb->db->query("SELECT id from events_types where id = {$prev_type} ")->row();
    $chk_new_type = $this->Mdb->db->query("SELECT id from events_types where id = {$new_type} ")->row();
    if(empty($chk_prev_type) || empty($chk_new_type)){exit('Error: new_type o prev_type no validos ');}
    if($ev_id == 0){
      $ar = $this->Mdb->db->query("SELECT * from events where elements_id = {$this->id} AND events_types_id = {$prev_type} ")->result_array();
      foreach ($ar as $v) {
        $ev = new Event($v['id']);
        $ev->set('events_types_id',$new_type);
      };
    }else{
      $ev = new Event($ev_id);
      $ev->set('events_types_id',$new_type) ;
    }
  }


  function kill_events_all(){
    $ar = $this->Mdb->db->query("SELECT * from events where elements_id = {$this->id}")->result_array();
    foreach ($ar as $v) {
      $this->Mdb->db->query("DELETE FROM events_pcles WHERE events_id = ". $v['id']);
      $this->Mdb->db->query("DELETE FROM events WHERE id = ". $v['id']);
    };
  }
    // VACIA UN PCLE QUE CONTIENE UN ARRAY
  function kill_pcle_arr_item($pcle_id,$item){
    $rdb = $this->Mdb->db->query("SELECT * FROM  {$this->pcles_db_name} WHERE id = {$pcle_id}")->row();

    $varr = explode(',',$rdb->value);
    if (($key = array_search($item, $varr)) !== false) {
      unset($varr[$key]);
    }
    $r = implode(',',$varr);
    $this->set_pcle($pcle_id,$rdb->label,$r);
  }

  //  HACE DEL LABEL UN TITULO
  function mk_title($l){
    $r = str_replace('_', ' ', $l);
    return ucfirst($r);
  }

//***   NEW RETORNA EL EVENTO PAGADO CON FECHA DE PAGO MAS ALTA
  function get_last_payment(){
      $q = "SELECT e.id as id , STR_TO_DATE(epd.value, '%d/%m/%Y') as dtd ,epr.value as rec_nro FROM `events` e
        JOIN events_pcles epst ON epst.events_id = e.id and epst.label = 'estado' AND epst.value LIKE 'p%'
        JOIN events_pcles epd ON epd.events_id = e.id AND epd.label = 'fec_pago'
        JOIN events_pcles epr ON epr.events_id = e.id AND epr.label = 'recibo_nro'
        WHERE e.events_types_id > 3 AND e.events_types_id < 8 AND e.elements_id = $this->id ORDER BY dtd DESC, id DESC limit 1";
    $lp = $this->Mdb->db->query($q);
    if($lp->result_id->num_rows){
      return new Event($lp->row()->id);
    }else{
      return null;
    }
  }

  //  *** RETORNA UN ARRAY CON LOS IDS DE LOS SERVICIOS DE LOS QUE ES OWNER O FALSE
  public function get_servicios(){
    $q = "SELECT e.id FROM `elements` e JOIN elements_types etp on etp.id = e.elements_types_id WHERE etp.name = 'SERVICIO' AND e.owner_id = ".$this->id;
    $srv = $this->Mdb->db->query($q)->result_array();
    if(!empty($srv)){
      return $srv;
    }else{
      return false;
    }
  }



  function get_all_ctas_servicios(){
    $s = $this->get_servicios();
    $res = [];
    if($s){
      foreach ($s as $srv) {
        $e = new Element($srv['id']);
        $srv_atom = new Atom($e->get_pcle('atom_id')->value);


        $res[]=['name'=>$srv_atom->name,'events'=>$e->get_events_cuota()];
      }
    }
    return $res;
  }

  // RETORNA EL NOMBRE DEL OWNER ID
  function get_owner_type(){
    $x = $this->Mdb->db->query("SELECT t.name FROM atoms a JOIN atom_types t on t.id = a.atom_types_id WHERE a.id = ".$this->owner_id)->row();
    $r = ($x)?$x->name:'-';
    return $r;
  }



  // RETORNA EL NOMBRE DEL OWNER ID
  function get_owner_name(){
    $x = $this->Mdb->db->query("SELECT * from atoms where id = ".$this->owner_id)->row();
    $r = ($x)?$x->name:'-';
    return $r;
  }

  // RETORNA LOS PROPERTIES DEL OWNER
  function get_owner_props(){
    $x = $this->Mdb->db->query("SELECT p.id,p.atom_id,p.value,s.label,s.title,s.vis_elem_type,s.vis_ord_num FROM atoms_pcles p LEFT OUTER JOIN atoms_struct s on s.id = p.struct_id WHERE atom_id = {$this->owner_id} AND s.vis_ord_num > 0 ORDER BY s.vis_ord_num ASC ")->result_array();
    $r = (count($x) > 0)?$x:[];
    return $r;
  }


    // RETORNA EL NOMBRE DEL PLAN DE FINANCIACION NO SIRVE DEPRECAR
  public function get_plan(){

    return (new Atom($this->get_pcle('financ_id')->value))->get_pcle('name')->value;

    // $f=$this->get_pcle('financ_id');
    // if(!empty($f)){
    //   $p = $this->Mdb->db->query("SELECT name from atoms where id = ".$f->value)->row();
    // }else{
    //   $p = false;
    // };
    // $r = ($p)?$p->name:'-';
    // return $r;
  }

  //****  DEPRECATED
  // public function get_plan_all_data(){
  //   $f=$this->get_pcle('financ_id')->value;
  //   if($f){
  //     $p = $this->Mdb->db->query("SELECT
  //       p.id as id,
  //       p.atom_id,
  //       pname.value as name,
  //       pcantctas.value as cant_ctas,
  //       pindac.value as indac,
  //       pfrec.value as frec_indac
  //       FROM `pcles` p
  //       LEFT OUTER join atoms_pcles pname ON pname.atom_id = p.atom_id AND pname.label = 'name'
  //       LEFT OUTER join atoms_pcles pcantctas ON pcantctas.atom_id = p.atom_id AND pcantctas.label = 'cant_ctas'
  //       LEFT OUTER join atoms_pcles pindac ON pindac.atom_id = p.atom_id AND pindac.label = 'indac'
  //       LEFT OUTER join atoms_pcles pfrec ON pfrec.atom_id = p.atom_id AND pfrec.label = 'frecuencia_indac'
  //       WHERE p.atom_id = {$f} GROUP BY p.atom_id ")->row();
  //   }else{
  //     $p = false;
  //   };
  //   $r = ($p)?$p:'-';
  //   return $r;
  // }



  public function get_fec_init(){
    $fq = $this->Mdb->db->query("SELECT date from events where elements_id = {$this->id}")->row();
    $r = (!empty($fq))?$fq->date:'undefined';
    return $r;
  }

  public function get_cli_name(){
    $cl_id = $this->get_pcle('cli_id');
    if(!empty($cl_id)){
      $cli= new Atom($cl_id->value);
      return $cli->name;
    }else{
      return false;
    }
  }

  //  DEVUELVE LAS CUOTAS A PAGAR VENCIDAS Y LA DEL MES EN CURSO O CERO
  public function get_cta_upc(){
    // tipos de cta UPCOMING
    // 1 futura | 1 vencida | varias futuras y varias vencidas | varias vencidas ninguna futura
    $v = [
        'e_id'=>$this->id,
        'total'=>0,
        'events'=>[]
    ];
    // OBTENGO EL MONTO DEL ULTIMO PAGO PARA TENER MONTO CUOTA CON EL VALUE ANTERIOR
    $lp_ev = $this->get_last_payment();
    if(!empty($lp_ev->id)){
        $v['total'] = intval($lp_ev->get_pcle('monto_pagado')->value);
    }
    //*** CUOTAS VENCIDAS A_PAGAR O CERO
    $vencidos = $this->get_events(4,'a_pagar');
    $lv = $this->get_events_last_vencido_a_pagar();

    if(!empty($lv)){
        $v['total'] = $lv['total'];
        $v['events'] = $vencidos['events'];
    }
    // SI HAY CUOTAS FUTURAS A PAGAR
    // ** $f LA CUOTA IMPAGA DEL MES PROXIMO
    $f = $this->get_events_first_future();
    if(!empty($f)){
      // FECHA DE LA CUOTA
      $fd = $this->fixdate_ymd($f['events']['fecha']);
      $x = new DateTime($fd);
      // USAR FAKE DATE 10 DE ABRIL PARA TESTEAR CUOTAS Y SERV
      // $fake_date = '2020-04-10';
      //  HOY
      $t = new DateTime(date('Y-m-d'));
      // SI CUOTA FUTURA A_PAGAR ES DEL MISMO MES Y AÑO
      // LA REEMPLAZA $V
      if($x->format('Y-m') === $t->format('Y-m')){
        $v['total'] = $f['total'];
        $v['events'] = array_merge($v['events'],[$f['events']]);
      }
    }
    // NO HAY CUOTA PAGADAS Y LA CUOTA INICIAL ESTA A_PAGAR
    if($v['total'] == 0 && empty($events)){
      $v['total'] = $this->get_pcle('monto_cta_1')->value;
    }
    //  AGREGA CUOTAS REFUERZO SI EXISTEN
    if(intval($this->get_pcle('frecuencia_ctas_refuerzo')->value) > 0){
        $cr = $this->get_event_refuerzo();
        if(!empty($cr)){
          $v['events'] = array_merge($v['events'],$cr['events']);
        }
    }

  return $v;
  }


  //  DEVUELVE LAS CUOTAS A PAGAR VENCIDAS Y LA DEL MES EN CURSO O CERO
  public function old_get_cta_upc(){
    // tipos de cta UPCOMING
    // 1 futura | 1 vencida | varias futuras y varias vencidas | varias vencidas ninguna futura
    $v = [
        'e_id'=>$this->id,
        'total'=>0,
        'events'=>[]
    ];
    // OBTENGO EL MONTO DEL ULTIMO PAGO PARA TENER MONTO CUOTA CON EL VALUE ANTERIOR
    $lp_ev = $this->get_last_payment();
    if(!empty($lp_ev)){
        $v['total'] = intval($lp_ev->get_pcle('monto_pagado')->value);
        // $v = [
        //     'total'=>
        // ];
    }

    //*** CUOTAS VENCIDAS A_PAGAR O CERO
    $vencidos = $this->get_events(4,'a_pagar');
    $lv = $this->get_events_last_vencido_a_pagar();
    if(!empty($vencidos)){
        $v['total'] = $lv['total'];
        $v['events'] = $vencidos['events'];
      // $v = [
      //   'total'=>$lv['total'],
      //   'events'=>$vencidos['events']
      // ];
    }
    // SI HAY CUOTAS FUTURAS A PAGAR
    // ** $f LA CUOTA IMPAGA DEL MES PROXIMO
    $f = $this->get_events_first_future();
    if(!empty($f)){
      // FECHA DE LA CUOTA
      $fd = $this->fixdate_ymd($f['events']['fecha']);
      $x = new DateTime($fd);
      // USAR FAKE DATE 10 DE ABRIL PARA TESTEAR CUOTAS Y SERV
      // $fake_date = '2020-04-10';
      //  HOY
      $t = new DateTime(date('Y-m-d'));
      // SI CUOTA FUTURA A_PAGAR ES DEL MISMO MES Y AÑO
      // LA REEMPLAZA $V
      if($x->format('Y-m') === $t->format('Y-m')){
        $v['total'] = $f['total'];
        $v['events'] = array_merge($v['events'],[$f['events']]);
      }
    }
    // $A ES CUOTA 0 ANTICIPO EN CREDITOS VIEJOS
    $a = $this->get_event_by_ord_num(0);
    var_dump($a); exit();

    // SI HAY ANTICIPO CTA CERO LO AGREGA AL ARRAY en $v
    if(is_object($a)){
      $estado = $a->get_pcle('estado')->id;
      if(!empty($estado)){
          if($a->get_pcle('estado')->value == 'a_pagar'){
          $v['total'] = intval($a->get_pcle('monto_cta')->value);
          $v['events'] = array_merge($v['events'],[$a->get_props()]);
        }
      }
    }
    //  CHECK DE CUOTAS REFUERZO
    if(intval($this->get_pcle('frecuencia_ctas_refuerzo')->value) > 0){
        $cr = $this->get_event_refuerzo();
        if(!empty($cr)){
          $v['events'] = array_merge($v['events'],$cr['events']);
        }
    }
  return $v;
  }




  //***  RECURSIVE FACTORIAL
  function factorial($n){
    if($n === 0){return 1;}
    return $n * $this->factorial($n-1);

  }


  // DIF DIAS PARA LOS INTERESES
  function dif_dias($date){
    $ddt = new DateTime($date);
    $today = new DateTime();
    $dif_date = $today->diff($ddt);
    return intval($dif_date->format('%a'));
  }


  public function date_time_diff($FromDate, $ToDate) {
    $FromDate = new DateTime($FromDate);
    $ToDate   = new DateTime($ToDate);
    $Interval = $FromDate->diff($ToDate);

    $Difference["Hours"] = $Interval->h;
    $Difference["Weeks"] = floor($Interval->d/7);
    $Difference["Days"] = $Interval->d % 7;
    $Difference["Months"] = $Interval->m;

    return $Difference;
  }


  function get_tot_pagado(){
    return (intval($this->get_events(4,'pagado')['total'])+intval($this->get_events(4,'p_ftrm')['total'])+intval($this->get_events(6,'pagado')['total']));
  }


    function get_ctas_pagas(){
      $q = "SELECT ev.id FROM `events` ev  JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value like 'p%' WHERE elements_id = {$this->id} ";
      $evs = $this->Mdb->db->query($q)->result_array();
      $tot_pagado = 0;
      $pcles = [];
      $p=[];
      if(!empty($evs)){
        foreach ($evs as $ev) {
          $e = new Event($ev['id']);
          $tot_pagado += intval($e->get_pcle('monto_pagado')->value);
          $p[]=$e->get_props();
        }
      }

      return  ['tot_pagado'=>$tot_pagado,'events'=>$p,'ev_count'=>count($evs)];
    }



  function get_cta_by_date($d){
    $ev = $this->Mdb->db->query("SELECT id FROM events WHERE events_types_id > 3 AND events_types_id < 9 AND elements_id = {$this->id} and date = '{$d}' ");
    if($ev->result_id->num_rows){
      return new Event($ev->row()->id);
    }else{
      return false;
    }
  }

  function get_event_by_pcle($type_id,$estado,$plbl,$pvalue){
    $r = $this->get_events($type_id,$estado);
    foreach($r['events'] as $ev){
      $x = new Event($ev['id']);
      $pcl = $x->get_pcle($plbl);
        // var_dump($pcl->value);
      if(!empty($pcl) && preg_match("~\b$pvalue\b~",$pcl->value)){
        return $x;
      }
    }
    return false;
  }

  function get_event_by_ord_num($n){
    $ord_num = strval(intval($n)).'.0';
    $ev = $this->Mdb->db->query("SELECT id FROM events WHERE events_types_id > 3 AND events_types_id < 9 AND elements_id = {$this->id} and ord_num = '{$ord_num}' ORDER BY id ASC");
    if($ev->result_id->num_rows){
      return new Event($ev->row()->id);
    }else{
      return false;
    }
  }

  function get_event_by_ord_num_ordered($n,$o){
    $ev = $this->Mdb->db->query("SELECT id FROM events WHERE events_types_id > 3 AND events_types_id < 9 AND elements_id = {$this->id} and ord_num = {$n} ORDER BY date {$o} LIMIT 1 ")->row();
    if(!empty($ev)) return new Event($ev->id);
  }



  function get_events_cuota(){
    $r = [];
    $lista = $this->Mdb->db->query("SELECT * from events ev where ev.elements_id = {$this->id} AND ev.events_types_id > 3 AND ev.events_types_id < 9 ORDER BY id ASC ")->result_array();
    foreach ($lista as $ev) {
      $ep = $this->Mdb->db->query('SELECT * from events_pcles where events_id = '.$ev['id'])->result_array();
     // if(!array_key_exists('recibo_nro', $ep)){
     //    $cev = new Event($ev['id']);
     //    $cev->set_pcle(0,'recibo_nro',0,);
     //    $cev->set_pcle(0,'interes_mora',0);
     //    $ep = $this->Mdb->db->query('SELECT * from events_pcles where events_id = '.$ev['id'])->result_array();
     //  }
      $r[]=['event'=>$ev,'pcles'=>$ep];
    }
    return $r;
  }



  function get_events_all(){

    $r = [];
    $lista = $this->Mdb->db->query("SELECT * from events where elements_id = {$this->id} and events_types_id >= 4 and events_types_id <= 8 ORDER BY id ASC ")->result_array();
    foreach ($lista as $ev) {
      $ep = $this->Mdb->db->query('SELECT * from events_pcles where events_id = '.$ev['id'])->result_array();
      $r[]=['event'=>$ev,'pcles'=>$ep];
    }
    return $r;
  }


  function get_events_id_by_state($st){
    $q = "SELECT e.id from events e
    JOIN events_pcles ep on ep.events_id = e.id AND ep.label = 'estado' AND ep.value LIKE '{$st}'
    WHERE e.elements_id = {$this->id} ORDER BY e.id ASC";
    return $this->Mdb->db->query($q)->result_array();
  }

  function get_events($type_id_in,$state='',$type_id_out=0){
    if($type_id_out == 0){$type_id_out = $type_id_in;}
    $events = [];
    $total = 0;
    $res=[];
      // $ofset = ($this->get_pcle('financ_id')->value == 2235)?36:0;
    $q = "SELECT ev.id as id FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE '{$state}'
      WHERE ev.elements_id = {$this->id} AND ev.events_types_id >= {$type_id_in} AND ev.events_types_id <= {$type_id_out} ORDER BY id, date ASC";
    $ev = $this->Mdb->db->query($q)->result_array();
      //******* EVENTS DEL TIPO PEDIDO
    foreach ($ev as $i){
      $events[] = new Event($i['id']);
    }
    foreach ($events as $evx) {
      $res[] = $evx->get_props();
      if(strpos($evx->get_pcle('estado')->value,'p') === 0){
        $total += intval($evx->get_pcle('monto_pagado')->value);
      }else{
        $total += intval($evx->get_pcle('monto_cta')->value);
      }
    }
    return ['total'=>$total,'events'=>$res];
  }

  //****** 3 agosto 2020
  //**** retorna el total de cuotas a pagar y el monto
  //************************************************
  function get_total_ctas_a_pagar(){
    $lpayment = 0;
    $cant_tot = 0;
    $monto_financ = 0;
    $costo_financ = 178;
    $lp_ev = (!empty($this->get_last_payment()->id))?$this->get_last_payment():null;
    if(!empty($lp_ev)){
      $lpayment = intval($lp_ev->get_pcle('monto_pagado')->value);
    }
    $vencidas = count($this->get_events(4,'a_pagar')['events']);
    $ftr = $this->get_events(8,'a_pagar');
    $futuras = count($ftr['events']);

    // NO HAY CUOTA PAGADAS Y LA CUOTA INICIAL ESTA A_PAGAR
    // PROYECTA EN LPAYMENT LA COTA PENDIENTE DE PAGO
    if(($vencidas+$futuras) > 0 && $lpayment == 0){
      $lpayment = intval($this->get_pcle('monto_cta_1')->value);
    }

    //*** DEFINE CUOTA UPCOMMING
    if($futuras > 0){
    $cta_upc = $this->get_events_first_future()['total'];
    }else{
      $cta_upc = $lpayment;
    }

    // CUOTAS A PAGAR
    $cant_tot = $vencidas+$futuras;

    //  SI ESTA EN CICLO 1 SUMA LAS CUOTAS DEL CICLO ACTUAL Y LAS DEL CICLO 2
    $ctas_ciclo2 = intval($this->get_pcle('cant_ctas_ciclo_2')->value);
    if(intval($this->get_pcle('current_ciclo')->value) == 1){
      if($ctas_ciclo2 > 0)
        $cant_tot += $ctas_ciclo2;
    }
    if($cant_tot > 1 && $ctas_ciclo2 > 0){
      $monto_financ = intval(($cta_upc * $cant_tot) *($costo_financ/$cant_tot)*$cant_tot/100);
    }else{
        $monto_financ = intval($cta_upc * $cant_tot);
    }

    return ['cant_ctas'=>$cant_tot,'monto_1_pago'=>($cta_upc * $cant_tot),'cta_upc'=>$cta_upc,'monto_financ'=>$monto_financ];
  }

  //****** 21 julio 2020
  //**** retorna total por servicios a pagar cant de cuotas pagadas
  //************************************************
  function get_srv_data(){
    $res = [
      'cant_ctas_pagadas'=>0,
      'cant_ctas_a_pagar'=>0,
      'monto_1_pago'=>0,
      'monto_financ'=>0
    ];
    $srv = $this->get_servicios();
    if(is_array($srv) && count($srv) >0 ){
      foreach($srv as $s){
        $sx = new Element($s['id']);
        $sx_apg = $sx->get_total_ctas_a_pagar();
        $res['cant_ctas_pagadas'] += intval($sx->get_ctas_pagas()['ev_count']);
        $res['cant_ctas_a_pagar'] += $sx_apg['cant_ctas'];
        $res['monto_1_pago'] += $sx_apg['monto_1_pago'];
        $res['monto_financ'] += $sx_apg['monto_financ'];
      }
    }
    return $res;
  }




  function get_deuda_bruta(){
    $total = 0;
    $q = "SELECT ev.id as id FROM events ev WHERE ev.elements_id = {$this->id} ORDER BY id, date ASC";
    $ev = $this->Mdb->db->query($q)->result_array();
      //******* EVENTS DEL elem LOTE
    foreach ($ev as $i){
      $e = new Event($i['id']);
      $state = $e->get_pcle('estado')->value;
      if($state == 'a_pagar'){
        $total += $e->get_pcle('monto_cta')->value;
      }
    }
    return $total;
  }

  function get_tot_a_pagar_lote(){
    $q = "SELECT SUM(evpp.value) as tot FROM events ev
          JOIN events_pcles evp on evp.events_id = ev.id and evp.struct_id = 3 AND evp.value = 'a_pagar'
          JOIN events_pcles evpp on evpp.events_id = evp.events_id AND evpp.struct_id = 1
          WHERE ev.elements_id = {$this->id} AND ev.events_types_id >= 4 AND ev.events_types_id <= 8";
    $t = $this->Mdb->db->query($q);
    if($t->result_id->num_rows){
      // var_dump($t->row()->tot);exit();
      return intval($t->row()->tot);
    }else{
      return 0;
    }
  }

  //*** RETORNA LA SUMA DE LAS CUOTAS CREADAS Y SI ESTA EN EL CICLO 1 CALCULA EL MONTO DEL CICLO 2 EN BASE AL VALOR DE LA UTIMA CUOTA DEL CICLO 1 MULTIPLICADO POR LA CANT DE CUOTAS DEL CICLO 2
  function get_saldo_a_financiar(){
    $q = "SELECT ev.id,ev.date,ev.ord_num, evpp.value  FROM events ev
          JOIN events_pcles evp on evp.events_id = ev.id and evp.struct_id = 3 AND evp.value = 'a_pagar'
          JOIN events_pcles evpp on evpp.events_id = evp.events_id AND evpp.struct_id = 1
          WHERE ev.elements_id = {$this->id} AND ev.events_types_id >= 4 AND ev.events_types_id <= 8 ORDER BY ev.date DESC";
    $t = $this->Mdb->db->query($q);
    if($t->result_id->num_rows){
      // SUMA TODOS LOS MONTO_CTA
      $tt1 = array_reduce($t->result_array(),function($z,$i){return $z += intval($i['value']);});
      $ciclo_2 = intval($this->get_pcle('cant_ctas_ciclo_2')->value);
      // ESTA EN CICLO 1 Y HAY CICLO 2 PENDIENTE
      if(intval($this->get_pcle('current_ciclo')->value) === 1 && $ciclo_2 > 0){
        $last_cta = intval($t->result_array()[0]['value']);
        $total = $tt1 + intval($last_cta * $ciclo_2);
      }else{
        // ASUMO QUE ESTA EN CICLO 1 Y NO HAY CICLO2 O ESTA EN CURR_CICLO  CICLO 2
        $total = $tt1;
      }
      $fecha  = $t->result_array()[0]['date'];
      $ev_id = $t->result_array()[0]['id'];
      $ord_num = $t->result_array()[0]['ord_num'];
      return ['total'=>$total,'fecha'=>$fecha,'ev_id'=>$ev_id,'ord_num'=>$ord_num];
    }
  }


  // devuelve el total de la deuda pactada pagada  y a pagar
  function get_deuda_total(){
    $total = 0;
    $q = "SELECT ev.id as id FROM events ev WHERE ev.elements_id = {$this->id} ORDER BY id, date ASC";
    $ev = $this->Mdb->db->query($q)->result_array();
      //******* EVENTS DEL elem LOTE
    foreach ($ev as $i){
      $e = new Event($i['id']);
      if($e->type_id == 4 || $e->type_id == 8){
        $total += $e->get_pcle('monto_cta')->value;
      }
    }
    // *** DEUDA DE SERVICIOS ****
    $srv = $this->get_servicios();
    if(is_array($srv) && count($srv)>0){
      foreach ($srv as $s) {
        $qsrv = "SELECT ev.id as id FROM events ev WHERE ev.elements_id = {$s['id']} ORDER BY id, date ASC";
        $ev_srv = $this->Mdb->db->query($qsrv)->result_array();
        foreach ($ev_srv as $evs) {
          $es = new Event($evs['id']);
          if($e->type_id == 4 || $e->type_id == 8){
            $total += $e->get_pcle('monto_cta')->value;
          }
        }
      }
    }
    return $total;
  }

  /*
//**** QUERY A EVENTS de lotes
    // levanta todo lo imputado antes del 20 de agosto (fecha de inicio de modalidad de pago 2)
    //  QUERY DE MONTO DE CUOTAS
    $q1 = "SELECT  SUM(evp3.value) as t1 FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'p%'
      JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = 'fec_pago' AND STR_TO_DATE(evp2.value, '%d/%m/%Y') < STR_TO_DATE('20/08/2019', '%d/%m/%Y')
      JOIN events_pcles evp3 on evp3.events_id = ev.id AND evp3.label = 'monto_pagado' JOIN events_pcles evp4 on evp4.events_id = ev.id AND evp.label = 'recibo_nro' AND evp4.value NOT LIKE '-1'
      JOIN events_pcles evp5 on evp5.events_id = ev.id AND evp.label = 'recibo_nro' AND evp5.value NOT LIKE ''
      JOIN events_pcles evp6 on evp6.events_id = ev.id AND evp.label = 'last_mondif' AND STR_TO_DATE(evp6.value, '%d/%m/%Y') <=  STR_TO_DATE('20/08/2019', '%d/%m/%Y')
      WHERE ev.elements_id = {$this->id} ";


  */

// AND evp.label = 'recibo_nro' AND evp.value > 0
  function get_pagos_fk($l){
    $q2 = "SELECT evp2.value as total FROM events ev
        LEFT OUTER JOIN events_pcles evp on evp.events_id = ev.id
        LEFT OUTER JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = '{$l}'
        LEFT OUTER JOIN events_pcles evp3 on evp3.events_id = ev.id AND evp3.label = 'fec_pago'
        WHERE ev.events_types_id  > 3 AND STR_TO_DATE(evp3.value, '%d/%m/%Y') < STR_TO_DATE('30/06/2019', '%d/%m/%Y') AND ev.elements_id = {$this->id} GROUP BY evp2.id";
    $t1 = $this->Mdb->db->query($q2);
    if($t1->result_id->num_rows){
      $res = 0;
      foreach ($t1->result_array() as $ev) {
        $res += intval($ev['total']);
      }
      return $res;
    }else{
      return 0;
    }
  }

  function get_pagos_fk_serv($l){
    $srvs_arr = $this->get_servicios();
    $res = 0;
    if(is_array($srvs_arr)){
      foreach ($srvs_arr as $srv) {
        $curr_srv = new Element($srv['id']);
        $res += $curr_srv->get_pagos_fk($l);
      }
    }
    return $res;
  }

  // ****************************************************
  //*** pagos en contab_asientos a partir del 20/08/2019
  function get_pagos_caja(){
      $caja = 0;
      // QUERY A CONTAB_ASIENTOS
      $l = $this->get_pcle('prod_id')->value;
      $cli = $this->get_pcle('cli_id')->value;
      if(!empty($l)){
        $ast = $this->Mdb->db->query("SELECT SUM(monto) as tot FROM contab_asientos WHERE lote_id = {$l} AND cliente_id = {$cli} AND tipo_asiento = 'INGRESOS' AND cuenta_imputacion_id = 191  AND estado = 1 AND fecha >= '2019-08-20' ");
        if($ast->result_id->num_rows){
          $caja = intval($ast->row()->tot);
        }
      }
    return $caja;
  }

  // ***************************************************************************************
  //***  TOTAL DE IMPUTACIONES DE CUOTAS devuelve un array con fecha monto y nro de recibo

  function get_imputaciones_ctas_2($l){
    $i = 0;
    $test = $this->Mdb->db->query("SELECT evp2.value as monto, evp3.value as fecha, evp4.value as nro_comprobante FROM events ev
        LEFT OUTER JOIN events_pcles evp on evp.events_id = ev.id
        LEFT OUTER JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = '{$l}'
        LEFT OUTER JOIN events_pcles evp3 on evp3.events_id = ev.id AND evp3.label = 'fec_pago'
        LEFT OUTER JOIN events_pcles evp4 on evp4.events_id = ev.id AND evp4.label = 'recibo_nro'
        WHERE ev.events_types_id  > 3 AND STR_TO_DATE(evp3.value, '%d/%m/%Y') >= STR_TO_DATE('20/08/2019', '%d/%m/%Y') AND ev.elements_id = {$this->id} group by evp2.id ");

    if($test->result_id->num_rows){
        $i=0;
        foreach ($test->result_array() as $r) {
          $i += intval($r['monto']);
        }
      }
    return $i;
  }


  function get_imputaciones_ctas($l){
      $r = 0;
      //**** QUERY MONTO CUOTA
      $q1 = "SELECT  SUM(evp2.value) as t1 FROM events ev
        JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'p%'
        JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = '{$l}'
        JOIN events_pcles evp3 on evp3.events_id = ev.id AND evp3.label = 'fec_pago'
        WHERE ev.events_types_id  > 3 AND STR_TO_DATE(evp3.value, '%d/%m/%Y') >= STR_TO_DATE('20/08/2019', '%d/%m/%Y') AND ev.elements_id = {$this->id}";
      $x = $this->Mdb->db->query($q1);
      if($x->result_id->num_rows){
        $r = $x->row()->t1;
      }
      return intval($r);
    }



  //********** TOTAL DE IMPUTACIONES DE CUOTAS
  function get_imputaciones_ctas_back ($l){
    //**** QUERY MONTO CUOTA
    $q1 = "SELECT  SUM(evp2.value) as t1 FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'p%'
      JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = '{$l}'
      JOIN events_pcles evp3 on evp3.events_id = ev.id AND evp3.label = 'fec_pago'
      WHERE ev.events_types_id  > 3 AND STR_TO_DATE(evp3.value, '%d/%m/%Y') >= STR_TO_DATE('20/08/2019', '%d/%m/%Y') AND ev.elements_id = {$this->id}";
    $t1 = $this->Mdb->db->query($q1)->row()->t1;

    return intval($t1);
  }

  function get_cant_ctas_imputadas(){
    $q1 = "SELECT  COUNT(evp.id) as t1 FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'p%'
      WHERE ev.elements_id = {$this->id} ";
    $c = $this->Mdb->db->query($q1);
    if($c->result_id->num_rows){
      return intval($c->row()->t1);
    }else{
      return 0 ;
    }






  }

    //***  NEW
  function get_cant_ctas_restantes(){
    $q1 = "SELECT  COUNT(evp.id) as t1 FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'a_pagar'
      WHERE ev.elements_id = {$this->id} AND ev.events_types_id = 8 ";
    $x = $this->Mdb->db->query($q1);
    if($x->result_id->num_rows){
      return intval($x->row()->t1);
    }else{
      return 0;
    }
  }


  function get_imputaciones_intereses(){
    //**** QUERY MONTO INTERESES
    $qi1 = "SELECT  SUM(evp2.value) as ti1 FROM events ev
      JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value LIKE 'p%'
      JOIN events_pcles evp2 on evp2.events_id = ev.id AND evp2.label = 'interes_mora'
      WHERE ev.elements_id = {$this->id} ";
    $ti1 = $this->Mdb->db->query($qi1)->row()->ti1;

    return intval($ti1);
  }



  function get_events_pftrm_fix($type_id){
    $events = [];
    $total = 0;
    $res=[];
    $q = "SELECT ev.id as id  FROM events ev JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' AND evp.value = 'p_ftrm'  WHERE ev.elements_id = {$this->id} AND ev.events_types_id = {$type_id} ";
    $ev = $this->Mdb->db->query($q)->result_array();
      //******* EVENTS DEL TIPO PEDIDO
    foreach ($ev as $i){
      $events[] = new Event($i['id']);
    }
    foreach ($events as $evx) {
      if($evx->get_pcle('interes_mora') && $evx->get_pcle('interes_mora')->value > 0){
          $res[] = $evx->get_props();
      }else{
            $pcles = [
            $evx->get_pcle('monto_cta'),
            $evx->get_pcle('fecha_vto'),
            $evx->get_pcle('estado'),
            $evx->get_pcle('nro_cta'),
            ($evx->get_pcle('monto_pagado'))?$evx->get_pcle('monto_pagado'):0,
            ($evx->get_pcle('fec_pago'))?$evx->get_pcle('fec_pago'):0,
            ($evx->get_pcle('recibo_nro'))?$evx->get_pcle('recibo_nro'):0
          ];
          $res[] = ['id'=>$evx->get('id'),'fecha'=>$evx->get('date'),'ord_num'=>$evx->get('ord_num'),'type'=>$evx->get('type'),'pcles'=>$pcles];
      }

      if(strpos($evx->get_pcle('estado')->value,'p') == 0){
        $total += intval($evx->get_pcle('monto_pagado')->value);
      }
    }
    return ['total'=>$total,'events'=>$res];
  }


  function get_events_pagado_fix($type_id){

    $events = [];
    $total = 0;
    $res=[];
      // $ofset = ($this->get_pcle('financ_id')->value == 2235)?36:0;
    $q = "SELECT ev.id as id  FROM events ev
    JOIN events_pcles evp on evp.events_id = ev.id AND evp.label = 'estado' and evp.value = 'pagado'
    WHERE ev.elements_id = {$this->id} AND ev.events_types_id = {$type_id} ORDER BY ev.ord_num ASC";
    $ev = $this->Mdb->db->query($q)->result_array();
      //******* EVENTS DEL TIPO PEDIDO
    foreach ($ev as $i){
      $events[] = new Event($i['id']);
    }
    foreach ($events as $evx) {
      // var_dump($evx);
      $pcles = [
        $evx->get_pcle('monto_cta'),
        $evx->get_pcle('fecha_vto'),
        $evx->get_pcle('estado'),
        $evx->get_pcle('nro_cta'),
        ($evx->get_pcle('monto_pagado'))?$evx->get_pcle('monto_pagado'):0,
        ($evx->get_pcle('fec_pago'))?$evx->get_pcle('fec_pago'):0,
        ($evx->get_pcle('recibo_nro'))?$evx->get_pcle('recibo_nro'):0
      ];
      $res[] = ['id'=>$evx->get('id'),'fecha'=>$evx->get('date'),'ord_num'=>$evx->get('ord_num'),'type'=>$evx->get('type'),'pcles'=>$pcles];
      if($evx->get_pcle('estado')->value == 'pagado'){
        $total += intval($evx->get_pcle('monto_pagado')->value);
      }
    }
    return ['total'=>$total,'events'=>$res];
  }



  function clean_a_pagar_events(){
    $evs = $this->Mdb->db->query("SELECT ev.id FROM events ev join events_pcles evp_st on evp_st.events_id = ev.id AND evp_st.label = 'estado' and evp_st.value = 'a_pagar' where ev.elements_id = {$this->id} order by ev.id asc")->result_array();
    foreach ($evs as $v) {
      $ev = new Event($v['id']);
      $ev->kill();
    }
  }

  function get_evid_by_stakpos($type_id,$state,$fstlst){
    $p = ($fstlst == 'first')?'ASC':'DESC';
    $r = [];
    $ev = $this->Mdb->db->query("SELECT e.id,e.date,e.events_types_id from events e LEFT OUTER JOIN events_pcles ep on e.id = ep.events_id where e.elements_id = {$this->id} AND e.events_types_id = {$type_id} and ep.label = 'estado' AND ep.value = '{$state}' ORDER BY e.id {$p} LIMIT 1")->row();
    if(!empty($ev)){
      return $ev->id;
    }else{
      return false;
    }
  }

  function get_event_refuerzo(){
    $ev_obj = $this->Mdb->db->query("SELECT e.id from events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.events_types_id = 8 AND e.elements_id = {$this->id}
      AND ep.label = 'estado' AND ep.value = 'a_pagar'
      AND e.ord_num LIKE '%.1'
      ORDER BY e.id ASC LIMIT 1");
    if($ev_obj->result_id->num_rows){
      $ev = new Event($ev_obj->row()->id);
      return ['total'=>$ev->get_pcle('monto_cta')->value,'events'=>$ev->get_props()];
    }else{
      return false;
    }

  }

  function get_events_first_future(){
    $ev_obj = $this->Mdb->db->query("SELECT e.id from events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.events_types_id = 8 AND e.elements_id = {$this->id} AND ep.label = 'estado' AND ep.value = 'a_pagar'
      ORDER BY e.id ASC LIMIT 1");
    if($ev_obj->result_id->num_rows){
      $ev = new Event($ev_obj->row()->id);
      return ['total'=>$ev->get_pcle('monto_cta')->value,'events'=>$ev->get_props()];
    }else{
      return false;
    }
  }

  function get_events_last_vencido_a_pagar(){
    $ev_obj = $this->Mdb->db->query("SELECT e.id from events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.events_types_id = 4 AND e.elements_id = {$this->id} AND ep.label = 'estado' AND ep.value = 'a_pagar'
      ORDER BY e.id DESC LIMIT 1");
    if($ev_obj->result_id->num_rows){
      $ev = new Event($ev_obj->row()->id);
      return ['total'=>$ev->get_pcle('monto_cta')->value,'events'=>$ev->get_props()];
    }else{
      return false;
    }
  }


  function get_first_future_event($state){
    $r = null;         ;
    $ev_obj = $this->Mdb->db->query("SELECT e.id from events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.events_types_id = 8 AND e.elements_id = {$this->id} AND ep.label = 'estado' AND ep.value = '{$state}'
      ORDER BY e.date ASC LIMIT 1")->row();
    if(!empty($ev_obj)){
      $ev = new Event($ev_obj->id);
      $ep = [
        'id'=> $ev->id,
        'monto_cta'=>$ev->get_pcle('monto_cta'),
        'fecha_vto'=>$ev->get_pcle('fecha_vto'),
        'nro_cta'=>$ev->get_pcle('nro_cta')
      ];
      $r=['id'=>$ev_obj->id,'fecha'=>$ev->date,'type'=>$ev->type_id,'ord_num'=>$ev->ord_num,'pcles'=>$ep];
    }
    return $r;

  }

  function get_first_event_id($type_id,$state){
    $r = [];
    $ev = $this->Mdb->db->query("SELECT e.id,e.date,e.events_types_id from events e LEFT OUTER JOIN events_pcles ep on e.id = ep.events_id where e.elements_id = {$this->id} AND e.events_types_id = {$type_id} and ep.label = 'estado' AND ep.value = '{$state}' ORDER BY e.id ASC LIMIT 1")->row();
    if(!empty($ev)){
      return $ev->id;
    }else{
      return false;
    }
  }

  function get_last_event_id($type_id,$state){
    $r = [];
    $ev = $this->Mdb->db->query("SELECT e.id,e.date,e.events_types_id from events e LEFT OUTER JOIN events_pcles ep on e.id = ep.events_id where e.elements_id = {$this->id} AND e.events_types_id = {$type_id} and ep.label = 'estado' AND ep.value = '{$state}' ORDER BY e.id DESC LIMIT 1")->row();
    if(!empty($ev)){
      return $ev->id;
    }else{
      return false;
    }
  }

  function get_first_event($state){
    $r = [];
    $ev = $this->Mdb->db->query("SELECT e.id,e.date,e.events_types_id from events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.elements_id = {$this->id} AND ep.label = 'estado' AND ep.value = '{$state}'
      ORDER BY e.date ASC LIMIT 1")->row();
    if(!empty($ev)){
      $e = new Event($ev->id);
      $ep = [
        'monto'=>$e->get_pcle('monto_cta'),
        'fec_vto'=>$e->get_pcle('fecha_vto'),
        'nro_cta'=>$e->get_pcle('nro_cta')
      ];
      $r=['fecha'=>$ev->date,'type'=>$ev->events_types_id,'pcles'=>$ep];
    }
    return $r;

  }

  function get_last_event($state){
    $r = [];
    $ev = $this->Mdb->db->query("SELECT e.id,e.date,e.events_types_id FROM events e
      LEFT OUTER JOIN events_pcles ep on ep.events_id = e.id
      WHERE e.elements_id = {$this->id} AND ep.label = 'estado' AND ep.value = '{$state}'
      ORDER BY e.date DESC LIMIT 1")->row();
    if(!empty($ev)){
      $e = new Event($ev->id);
      $ep = [
        'monto'=>$e->get_pcle('monto_cta'),
        'fec_vto'=>$e->get_pcle('fecha_vto'),
        'nro_cta'=>$e->get_pcle('nro_cta'),
        'fec_pago'=>$e->get_pcle('fec_pago'),
        'mto_pagado'=>$e->get_pcle('monto_pagado')
      ];
      $r=['fecha'=>$ev->date,'type'=>$ev->events_types_id,'pcles'=>$ep];
    }
    return $r;
  }

  function get_ahorro(){
    $ctas_adl = $this->get_events(6,'pagado');
    $mto_nominal = 0;
    $mto_pagado = 0;
    foreach ($ctas_adl['events'] as $c) {
      $cev = new Event($c['id']);
      $mto_nominal += intval($cev->get_pcle('monto_cta')->value);
      $mto_pagado += intval($cev->get_pcle('monto_pagado')->value);
    }
    return $mto_nominal - $mto_pagado;
  }

  //****** 21 julio 2020
  //**** calcula ahorro actual y ahorro acumulado
  //************************************************
  function get_ahorro_actual_y_acumulado(){
    $lp = $this->get_last_payment();
    $tp = 0;
    $actual = 0;
    $acumulado = 0;
    if(!empty($lp->id)){
      if($lp->type_id == 6){
        $mcta = intval($lp->get_pcle('monto_cta')->value);
        $mpago = intval($lp->get_pcle('monto_pagado')->value);
        $actual = $mcta - $mpago;
      }
    }
    return ['actual'=>$actual,'acumulado'=>$this->get_ahorro()];
  }

  // ***** arregla las fechas para MySQL
  function fixdate_ymd($dt){
    if(strpos($dt,'/') > 0)
      return substr($dt,strrpos($dt,'/')+1).'-'.substr($dt,strpos($dt,'/')+1,2).'-'.substr($dt,0,strpos($dt,'/'));
    return $dt;
  }
  // ***** arregla las fechas para pantalla
  function fixdate_dmY($dt){
    $d = explode('-',$dt);
    return $d[2].'/'.$d[1].'/'.$d[0];
  }


}
