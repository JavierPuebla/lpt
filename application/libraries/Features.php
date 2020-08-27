<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Features {
  protected $FTR;
  // We'll use a constructor, as you can't directly call a function
  // from a property definition.
  public function __construct(){
    // Assign the CodeIgniter super-object
    $this->FTR =& get_instance();
    $this->FTR->load->model('app_model');
    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    include (APPPATH . 'JP_classes/Atom.php');
  }


    //****** LIBRERIA DE  FEATRURES DE ATOMS
    //**** LA LIBRERIRA ESTA DISPONIBLE CUANDO SE DA DE ALTA EN MENU_SUBITEMS
    //************************************************

    // RECIBE EN PARR (post array) EL TIPO DE ATOMS A LISTAR
    //****** 07 agosto 2020
    //**** LISTADO EDITABLE
    //************************************************
    function listado($parr){
      if($parr['atom_type']){
        $atp = $parr['atom_type'];
        $atp_id = $this->FTR->app_model->get_obj("SELECT id FROM atom_types WHERE name = '{$atp}' ");
        $q = $this->make_query($atp_id->id);
        $pr = $this->FTR->app_model->get_arr($q);
        $st = $this->FTR->app_model->get_arr("SELECT st.id,
          st.atom_types_id,
          st.label,
          st.title,
          st.vis_ord_num,
          v.nombre as dom_types,
          st.validates
          FROM atoms_struct st
          JOIN dom_types v on v.id = dom_types
          WHERE atom_types_id = {$atp_id->id} ORDER BY st.vis_ord_num ASC");
        $struct = ($st)?$st:[];
        $r = [
          'title'=>'Listado de '. $atp,
          'data'=> $pr,
          'struct'=> $struct,
          'target'=>1
        ];
        return ['callback'=>'listado','param'=>$r];
      }

    }


    // *************************************************************************
    // ******* 9 agosto 2020
    // ******* ACTUALIZA EL PCLE POR EL ID USADO EN LIST
    // *************************************************************************
    function pcle_updv($parr){
//'{"atom_type":"Clientes","feature":"6","atom_id":"21","struct_id":"1","value":"FRANCISCOs"}'
      if(array_key_exists('atom_id',$parr) && array_key_exists('value',$parr) && array_key_exists('struct_id',$parr)){
        $x = new Atom($this->FTR->app_model,$parr['atom_id']);
        $x->pcle_updv($x->get_pcle_by_struct_id($parr['struct_id'])->id,$parr['value']);
        return ['callback'=>'noti','param'=>['type'=>'success','noti_title'=>' OK ','noti_msg' =>'Datos Guardados...']];
      }else{
        return ['callback'=>'noti','param'=>['type'=>'danger','noti_title'=>' Error! ','noti_msg' =>'Fallo la actualizacion de datos... ']];
      }
    }


    //****** 08 agosto 2020   ********************************************************
    //**** crea el query que piviotea la tabla pcles  en base al type id recibido edn param
    //***************************************************************************************
    function make_query($atp_id){
      $str = $this->FTR->app_model->get_arr("SELECT id,label FROM atoms_struct WHERE atom_types_id = {$atp_id}");
      $q = "SELECT atom_id as atom_id";
      foreach($str as $stri){
        $q .= ",MAX(CASE WHEN struct_id = {$stri['id']} THEN value END) AS '{$stri['label']}'";
      }
      $q .= "  FROM atoms_pcles WHERE atom_types_id = {$atp_id} GROUP BY atom_id";
      return $q;
    }


    // FEATURE ID  = 3
    //*** LOS SELECTS LLEVAN EL NOMBRE DE SU DATA SOURCE EN LABEL
    function alta($parr){
      if(array_key_exists('atom_type',$parr) && array_key_exists('feature',$parr) ){
        $atp = $parr['atom_type'];
        $atp_id = $this->FTR->app_model->get_obj("SELECT id FROM atom_types WHERE name = '{$atp}' ")->id;
        // OBTENGO LA ESTRUCTURA DEL ATOM
        $st = $this->FTR->app_model->get_arr("SELECT
          s.label,
          s.value,
          s.title,
          vo.name as dom_types,
          s.vis_ord_num,
          s.validates
          FROM `atoms_struct` s
          JOIN dom_types vo on vo.id = s.dom_types
          WHERE s.atom_types_id = {$atp_id}
          ORDER BY s.vis_ord_num ASC");
          // filtro dom selects
          $selects = array_filter($st,function($i) {return $i['dom_types'] == 'select';});
          if(is_array($selects)){
            $select_content=[];
            foreach($selects as $key => $slct){
              // OBTENER CONTENIDO
              $c = $this->FTR->app_model->get_arr("SELECT * FROM {$slct['label']} ");
              $st[$key]['content']=$c;

            }
          }
          return [
            'callback'=>'alta',
            'param'=>[
              'target'=>1,
              'title'=> 'ALTA DE '.$atp,
              'ok_function'=>'save_new_atom',
              'data'=> $st
            ]
          ];
      }else{
        return ['callback'=>'noti','param'=>['type'=>'danger','noti_title'=>' Error! ','noti_msg' =>'Fallo en alta de datos... ']];
      }

    }

    function save_new_struct($p){
      $d = [];
      foreach($p['data'] as $i){
        if($i['label'] == 'atom_types'){$i['label'] = 'atom_types_id';}
        $d[$i['label']]=$i['value'];
      }
      // $insert_id = $this->FTR->app_model->insert('atoms_struct',$d);
      // return ['callback'=>'noti','param'=>['type'=>'success','noti_title'=>' OK ','noti_msg' =>'Datos Guardados...'.$insert_id]];
    }


    function resumen_cta($par){
      return "Hola ...".$par. 'soy Resumen de Cuenta ';
    }

    function new_feature($par){
      return "Hola ...".$par. 'soy new_feature';
    }




}
