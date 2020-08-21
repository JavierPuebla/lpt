<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Historial extends Element {

	
	//  CREA EL PRIMER ITEM DEL HISTORIAL
	function start($e_id){
		$id=0;
		$type_id = 11;
		$date = new DateTime();
		$elem_id=$this->id;
		$ord_num=1;
		$ev = new Event($id,$type_id,$date->format('Y-m-d'),$elem_id,$ord_num);
		$ev->set_pcle(0,'contrato_id',$e_id);
		$ev->set_pcle(0,'state','NORMAL'); 
	}

	

	function get_event_last(){
	    $ev = $this->Mdb->db->query("SELECT e.id from events e WHERE e.elements_id = {$this->id} ORDER BY e.id DESC LIMIT 1")->row(); 
	    if(!empty($ev)){
	      return new Event($ev->id);
	    }else{
	      return false;  
	    }
	}
  	
	// historial update  
	// params (event_type_id,user_id,text_accion,detalle_text_or_id,state_code)
  	function update($usr_id,$act,$det,$st){
		// try {
			
			$tp_id = $this->Mdb->db->query("SELECT id FROM events_types WHERE nombre = '{$st}' ")->row()->id;
			$date = new DateTime();
			$ord_num = 1;
			$last_ord_num = $this->Mdb->db->query("SELECT ev.ord_num from events ev WHERE ev.elements_id = {$this->id} ORDER BY ev.id DESC LIMIT 1");
			if(!empty($last_ord_num->num_rows)){
				$ord_num = intval($last_ord_num->row()->ord_num)+1;
			}
			$ev = new Event(0,$tp_id,$date->format('Y-m-d'),$this->id,$ord_num);		
			$ev->set_pcle(0,'user_id',$usr_id); 
			$ev->set_pcle(0,'accion',$act); 
			$ev->set_pcle(0,'detalle',$det); 
			$ev->set_pcle(0,'state',$st); 
			
		// } catch (Exception $e) {
		// 	echo 'no se puede actualizar el historial ->'.$e;
		// }
	}




}