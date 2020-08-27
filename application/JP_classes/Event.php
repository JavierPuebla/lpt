<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event {
  
  public function __construct($id=0,$type_id='',$date='',$elements_id=0,$ord_num=0) {
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();
    $this->db_name = 'events';
    $this->types_db_name = 'events_types';
    $this->types_ref_name = 'events_types_id';
    $this->pcles_db_name = 'events_pcles';
    $this->foreign_key = 'events_id';
    
    if(!defined('MAX_DIAS_VENCIMIENTO')) define ('MAX_DIAS_VENCIMIENTO',25);
    
    // SETEO UN ID PARA EL EVENTO GUARDANDO FECHA Y TIPO EN LA BASE
    // CUANDO EL PARAM $ID NO ESTA SETEADO
    if($id == 0){
      $this->date = $this->fixdate($date);
      $this->ord_num = $ord_num;
      $this->elements_id = $elements_id;
      $this->elem_id = $elements_id;
      $this->type_id = $type_id;
      $this->type = $this->get_type_name($type_id);
      $this->id = $this->create_id();
      $this->pcles = [];
    }else{
      $e = $this->get_obj($id);
      if($e){
        $this->id = $e->id;
        $this->date = $e->date;
        $this->ord_num = $e->ord_num;
        $this->elem_id = $elements_id;
        $this->elements_id = $e->elements_id;
        $this->type_id = $e->events_types_id;
        $this->type = $this->get_type_name($e->events_types_id);
        $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = '$this->id'")->result();
      }else{
        return null;
      }
    }
  }
  
  
  
  public function create_id(){
    $this->Mdb->db->insert('events',['elements_id'=>$this->elements_id,'date'=>$this->date,$this->types_ref_name => $this->type_id,'ord_num'=>$this->ord_num]);
    return $this->Mdb->db->insert_id();
  }
  
  function get_obj($id){
    if(empty($id)){return null;}
    $x = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE id = {$id} ");
    if($x->result_id->num_rows){
      return $x->row();
    }else{
      return null;
    }
  }
  
  
  function get_type_name($t_id){
    if(!empty($t_id)){
      $n = $this->Mdb->db->query("SELECT nombre FROM {$this->types_db_name} WHERE id = ".$t_id);
      if($n->result_id->num_rows){
        return $n->row()->nombre;
      }
    }
    return '';
  }
  
  function get_type_id(){
    return $this->Mdb->db->query("SELECT id FROM {$this->types_db_name} WHERE time = '{$this->time}' AND nombre = '{$this->type}'")->row()->id;
  }
  
  
  function fixdate($dt){
    if(strpos($dt,'/') >0){
      return substr($dt,strrpos($dt,'/')+1).'-'.substr($dt,strpos($dt,'/')+1,2).'-'.substr($dt,0,strpos($dt,'/'));
    }else{
      return $dt;
    }
  }
  
  
  // GETTERS, SETTERS
  
  public function get_props(){
    return ['id'=>$this->id,'fecha'=>$this->date,'ord_num'=>$this->ord_num,'type'=>$this->type,'pcles'=>$this->pcles];
  }
  
  public function get($prop){
    return $this->$prop;
  }
  
  
  public function set($prop,$value){
    $this->$prop = $value;
    $this->Mdb->db->where('id', $this->id);
    $this->Mdb->db->update($this->db_name, [$prop=>$value]);
    // RELOAD
    $e = $this->Mdb->db->query("SELECT * FROM {$this->db_name} WHERE id = ". $this->id )->row();
    $this->id = $e->id;
    $this->date = $e->date;
    $this->ord_num = $e->ord_num;
    $this->elements_id = $e->elements_id;
    $this->type_id = $e->events_types_id;
    $this->type = $this->Mdb->db->query("SELECT nombre FROM $this->types_db_name WHERE id = ".$this->type_id)->row()->nombre;
    $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = '$this->id'")->result();
  }
  
  
  public function set_pago_type_and_date(DateTime $fp){
    //**** DIAS DE DIFERENCIA ENTRE FECHA DE PAGO Y FECHA DE VENCIMIENTO
    $fv = date("Y-m-d", strtotime(str_replace('/', '-', $this->get_pcle('fecha_vto')->value)));
    $dt_venc = new DateTime(substr($fv, 0,8).'01');
    $dif = $fp->diff($dt_venc);
    if($dif->invert){
      if($dif->days >= MAX_DIAS_VENCIMIENTO){
        // echo "fuera de termino";
        $this->set($this->types_ref_name,4);
        $pcl = $this->get_pcle('estado');
        $this->set_pcle($pcl->id,$pcl->label,'p_ftrm');
      }else {
        // echo 'en fecha';
        $this->set($this->types_ref_name,4);
        $pcl = $this->get_pcle('estado');
        $this->set_pcle($pcl->id,$pcl->label,'pagado');
      }
    }else{
      if($dif->days >= MAX_DIAS_VENCIMIENTO){
        // echo "adelantada";
        $this->set($this->types_ref_name,6);
        $pcl = $this->get_pcle('estado');
        $this->set_pcle($pcl->id,$pcl->label,'pagado');
      }else {
        // echo 'en fecha';
        $this->set($this->types_ref_name,4);
        $pcl = $this->get_pcle('estado');
        $this->set_pcle($pcl->id,$pcl->label,'pagado');
      }
    }
    $this->set_pcle(0,'fec_pago',$fp->format('d/m/Y'));
    
  }
  
  // UPDATES SOLO EL VALUE POR EL PCLE ID
  public function pcle_updv($pcle_id,$value){
    $this->Mdb->db->where('id', $pcle_id);
    $this->Mdb->db->update($this->pcles_db_name, ['value'=>$value]);
    $this->pcles = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} ")->result();
  }
  
  
  public function kill(){
    $this->Mdb->db->query("DELETE  FROM {$this->pcles_db_name} where {$this->foreign_key} = {$this->id}");
    $this->Mdb->db->query("DELETE  FROM {$this->db_name} where id = {$this->id}");
  }
  
  
  
  // GUARDA TODOS LOS DATOS DE UN PCLE
  public function set_pcle($pcle_id,$label,$value,$title=null,$vet=null,$vord=null){
    //**** NO SE SI ESTA CREADO EL PCLE BUSCO EL LABEL
    if($pcle_id == 0){
      $t = $this->Mdb->db->query("SELECT * FROM {$this->pcles_db_name} WHERE {$this->foreign_key} = {$this->id} AND label LIKE '{$label}' ")->row();
      // SI LO ENCUENTRA UPDATES
      if(!empty($t)){
        $d['label'] = $label;
        $d['value'] = $value;
        if($title !== null){$d['title']=$title;}
        if($vet !== null){$d['vis_elem_type']=$vet;}
        if($vord !== null){$d['vis_ord_num']=$vord;}
        $this->Mdb->db->where('id', $t->id);
        $this->Mdb->db->update($this->pcles_db_name,$d);
      }else{
        // NO ENCONTRO EL PCLE LO CREA
        $p = $this->Mdb->db->insert($this->pcles_db_name,[
          $this->foreign_key => $this->id,
          'label'=>$label,
          'value'=>$value,
          'title'=>($title!== null)?$title:$this->mk_title($label),
          'vis_elem_type'=>($vet !== null)?$vet:1,
          'vis_ord_num'=>($vord !== null)?$vord:-1
          ]);
        }
      }
      //**** TENGO PCLE ID HAGO UPDATE
      else{
        $this->Mdb->db->where('id', $pcle_id);
        $this->Mdb->db->update($this->pcles_db_name, [
          'label'=>$label,
          'value'=>$value
          ]);
        }
        // **** UPDATES PCLES PROP
        $this->pcles = $this->get_pcle();
      }
      
      
      // OBTIENE PLCE POR SU LABEL
      public function get_pcle($lbl=''){
        if($lbl === ''){
          return $this->pcles;
        }else{
          // busco en el array cargado en __construc
          foreach ($this->pcles as $p) {
            if($p->label === $lbl){return $p;}
          }
          return (object) ['id'=>0,'value'=>''];
        }
      }
      
      
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
      
      
    }
    