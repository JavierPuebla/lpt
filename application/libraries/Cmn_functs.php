<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmn_functs {
  protected $CMF;
  // We'll use a constructor, as you can't directly call a function
  // from a property definition.
  public function __construct(){
    // Assign the CodeIgniter super-object
    $this->CMF =& get_instance();
    $this->CMF ->load -> model('app_model');
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();



    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');
  }



  // function create_servicio_corte_cesped($elm_id);

  function show($x){
    highlight_string("<?php\n\$data =\n" . var_export($x, true) . ";\n?>");
    exit();
  }

  // *************************************************************************
  // ******* 23 junio 2020
  // ******* retorna la data para completar un select
  // *************************************************************************
  function fill_select_by_atom_types_id($type_id){
    $r = [];
    $x = $this->Mdb->db->query("SELECT name FROM atoms WHERE atom_types_id = {$type_id} ORDER BY name ASC");
    // si encuentro event_id devuelvo el refi atom
    if($x->result_id->num_rows){
      foreach ($x->result_array() as $v) {
        $r[]=['label'=>$v['name'],'id'=>$v['name']];
      }
      return $r;
    }else{
      return false;
    }
  }


  // *************************************************************************
  // ******* 20 mayo 2020
  // *** recibe el element y retorna el expediente correspondiente o MORENO
  // *************************************************************************
  function get_expediente($e){
    $barrio = new Atom($e->get_pcle('barrio_id')->value);
    if($barrio->name == 'MORENO'){
      return 'MORENO';
    }else{
      $l = new Atom($e->owner_id);
      return substr($l->get_pcle('expediente')->value,0,strpos($l->get_pcle('expediente')->value,'/'));
    }
  }
  // recibe el numero del mes devuleve el mes en txto
  function get_mes_txt($n){
    $a = ['','Enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    if($n>12 || $n < 0){$n = 12;}
    return $a[intval($n)];
  }

  // *************************************************************************
  // ******* 20 mayo 2020
  // ******* retorna nombre del servicio con % y ctas
  // *************************************************************************
  function get_srv_name($elm){
    $idc = (!empty($elm->get_pcle('indac')->value) && $elm->get_pcle('indac')->value != '-1' )?$elm->get_pcle('indac')->value:'';
    if(!empty($idc)){$a_indac = $idc."% ";}else{$a_indac = '';}

    $cc = (!empty($elm->get_pcle('cant_ctas')->value))?$elm->get_pcle('cant_ctas')->value:'';
    if($cc == '1'){$a_cant_ctas = $cc.' Cta.';}else{$a_cant_ctas = $cc." Ctas.";}

    $a_srvc = new Atom($elm->get_pcle('atom_id')->value);
    $a_name = $a_srvc->name ;
    // en prestamos busca el pcle descripcion con el numero de prestamo
    if(strpos($a_name,'Prestamo') > -1 && !empty($elm->get_pcle('descripcion')->id)){
        $a_name = $elm->get_pcle('descripcion')->value;
    }
    return $a_name." ".$a_indac." ".$a_cant_ctas;
  }

  // *************************************************************************
  // ******* 27 de enero 2020
  // ******* ACTUALIZA EL PCLE POR EL ID USADO EN LIST
  // *************************************************************************
  function pcle_updv_old($user_id,$route,$type=0,$prnt_id=0,$id=0,$v=0){
    if($type == 0 || $prnt_id == 0 || $id == 0 || $v == 0){
      $this->resp('front_call',['method'=>'pcle_updv','response'=>true,'msg'=>'Error.. (:<  ']) ;
    }
    if(!empty($route) && $user_id < 500 && $prnt_id > -1 && $id > -1 ){
      $e = new $type($prnt_id);
      $e->pcle_updv($id,$v);
      $this->resp(
        'front_call',
        [
          'route'=>$route,
          'method'=>'pcle_updv',
          'response'=>true,
          'msg'=>'OK :)'
        ]
      );
    }else{
      $this->resp('front_call',['method'=>'pcle_updv','response'=>true,'msg'=>'Error.. (:<  ']) ;
    }
  }

  //****** 7 agosto 2020
  //**** ACTUALIZA EL ATOM DE ATOM_ID EN $P, USANDO PCLE_UPDV
  //************************************************
  function atom_updv($route,$p){
    if(!empty($p)){
      if(array_key_exists('parent_id',$p) && array_key_exists('label',$p) && array_key_exists('data',$p)){
        $d = $p['data'];
        $d = preg_replace('/&nbsp;+/', ' ', $d);
        $d = preg_replace('/[^A-Za-z0-9`\/ñÑ@!$.%\\()+-=]/',' ', $d);

        $e = new Atom($p['parent_id']);
        if(!empty($e->id)){
          $e->set('last_update',Date('Y-m-d H:i:s'));
          $e->pcle_updv($e->get_pcle($p['label'])->id,ltrim($d));
          $this->resp('front_call',['method'=>'pcle_updv','route'=>$route,'response'=>true,'msg'=>'OK :)']);
        }else{
          $this->resp('front_call',['method'=>'pcle_updv','route'=>$route,'response'=>true,'msg'=>'Error.. Atom '.$e->type]) ;
        }

      }
    }else{
      $this->resp('front_call',['method'=>'pcle_updv','route'=>$route,'response'=>true,'msg'=>'Error.. (:<  ']) ;
    }

  }

  // BUSCA EL EVENT_ID (CUOTA UPC) Y SI ENCUENTRA DEVUELVE EL ELM_ID (SERVICIO REFI ASOCIADO)
  function check_refi($event_id){
    $r = [];
    $refi = $this->Mdb->db->query("SELECT atom_id FROM atoms_pcles WHERE atom_types_id = 20 AND (struct_id = 107 AND value = {$event_id}) OR (struct_id = 108 AND value LIKE '%{$event_id}%')");
    // si encuentro event_id devuelvo el refi atom
    if($refi->result_id->num_rows){
      $r = new Atom($refi->row()->atom_id);
      return $r->get_pcle('elem_id')->value;
    }else{
      return false;
    }
  }

  // SI ENCUENTRA el event_id de refi y es ultima cuota cancela la cuota parent
  function check_close_refi($ev_id){
    // $EV ES EL  EVENTO DE REFI QUE ESTA SIENDO PAGADO
    $ev = new Event($ev_id);
    $r_elm_id = intval($ev->get('elem_id'));
    $refi_lote = $this->Mdb->db->query("SELECT atom_id FROM atoms_pcles WHERE atom_types_id = 20 AND struct_id = 111 AND value = {$r_elm_id} ");
    // $refi_srv = $this->Mdb->db->query("SELECT atom_id FROM atoms_pcles WHERE atom_types_id = 20 AND (struct_id = 112 AND value = {$refi_elm_id}) ");
    if($refi_lote->result_id->num_rows){
      $r = new Atom($refi_lote->row()->atom_id);
      // $R ES EL ATOM REFINANCIACION QUE TIENE LOS EVENTOS QUE ESTA REFIANCIANDO
      $refi_lote_elm = new Element($r->get_pcle('lote_atom_id')->value);
      // SI CUOTAS RESTANTES DEL REFI ES CERO CIERRO LA FINANC E IMPUTO LAS CUOTAS EN "EVENT_ID"
      if(($refi_lote_elm->get_pcle('cant_ctas_restantes')->value) <= 0){
        // OBTENGO DE REFI LOS PARENT EVENTS Y LOS MARCO COMO PAGADOS
        // EVENT DE LA CUOTA DEL LOTE
        $parent_event_lote = $r->get_pcle('lote_event_id')->value;
        if(!empty($parent_event_lote)){
          $pev_lote = new Event($parent_event_lote);
          $pev_lote->set('events_types_id',4);
          $pev_lote->set_pcle(0,'estado', 'pagado');
          $pev_lote->set_pcle(0,'fec_pago',$ev->get_pcle('fec_pago')->value,'Fecha de Pago',1);
          $pev_lote->set_pcle(0,'monto_pagado',$pev_lote->get_pcle('monto_cta')->value,'Monto Pagado',1);
          $pev_lote->set_pcle(0,'recibo_nro',$ev->get_pcle('recibo_nro')->value,'Nro. Recibo',1);
        }
        // MARCO LOS EVENTS DE REFI CON EVENT TYPE 3 PARA QUE NO LOS COMPUTE COMO PAGOS DE CUOTA (SON PAST PAGO A CUENTA)
        $n = intval($refi_lote_elm->id);
        $n2 = intval($r->get_pcle('srv_atom_id')->value);
        $refi_events = $this->Mdb->db->query("SELECT id FROM events WHERE elements_id = {$n} OR elements_id = {$n2} ");
        foreach ($refi_events->result() as $rev) {
          $x = new Event($rev->id);
          $x->set('events_types_id',3);
        }
        // EVENTS DE LOS SERVICIOS
        $pev_srvs = [];
        if(!empty($r->get_pcle('srvs_events_id')->value)){
          $pev_srvs = explode(',',$r->get_pcle('srvs_events_id')->value);
          foreach ($pev_srvs as $pev_i) {
            $pev_srv = new Event($pev_i);
            $pev_srv->set('events_types_id','4');
            $pev_srv->set_pcle(0,'estado', 'pagado');
            $pev_srv->set_pcle(0,'fec_pago',$ev->get_pcle('fec_pago')->value,'Fecha de Pago',1);
            $pev_srv->set_pcle(0,'monto_pagado',$pev_srv->get_pcle('monto_cta')->value,'Monto Pagado',1);
            $pev_srv->set_pcle(0,'recibo_nro',$ev->get_pcle('recibo_nro')->value,'Nro. Recibo',1);
          }
        }
      }
    }
  }

  // *************************************************************************
  // ******* 2 abril 2020
  // ******* retorna los lotes rescindidos
  // *************************************************************************
  /*LOTE+
  NOMBRE Y APELLIDO DE TITULARES+
  FECHA INICIO DE BOLETO+
  CANTIDAD DE CUOTAS PAGAS+
  FECHA DE ULTIMA CUOTA PAGA Y MONTO DE ESA CUOTA Y
  MONTO TOTAL PAGO DEL BOLETO.*/
  public function get_rescindidos(){
    $qry = $this->Mdb->db->query("SELECT * from rescindidos");
    if($qry->result_id->num_rows){
      $res =[];
      foreach ($qry->result() as $row){
        $elm = new Element($row->id);
        $lp= $elm ->get_last_payment();
        $cp = $elm->get_ctas_pagas();
        $res[]=[
          'Lote'=>$row->lote,
          'Titulares'=>$row->titulares,
          'Fecha de Inicio'=>$row->fecha_inicio,
          'Cantidad Cuota Pagas'=>count($cp['events']),
          'Fecha ultima Cuota Paga'=>(!empty($lp))?$lp->get_pcle('fec_pago')->value:'',
          'Monto '=>(!empty($lp))?$lp->get_pcle('monto_pagado')->value:'',
          'Total Pagado'=>$cp['tot_pagado']
        ];
      }
      return $res;
    }
  }

  // *************************************************************************
  // ******* 14 julio 2020
  // ******* retorna las operaciones de pago con tarjeta
  // *************************************************************************
  function get_pagos_online(){
      $res = [];
      $dt = $this->Mdb->db->query("SELECT
          ci.id,
          ci.elem_id,
          DATE_FORMAT(ci.date,'%d/%m/%Y') as fecha,
          al.name as lote,
          ci.cargos,
          ci.effective_amount,
          ci.auth_number,
          ci.card_brand,
          ci.card_number
          FROM `contab_cobro_inmediato` ci
          JOIN elements_pcles ep on ep.elements_id = ci.elem_id AND ep.label = 'prod_id'
          JOIN atoms al on  al.id = ep.value
          ORDER BY ci.id DESC "
      );
      if($dt->result_id->num_rows){
          foreach ($dt->result_array() as $row) {
              // $r = str_replace('[','',$row['cargos']);
              // $r = str_replace(']','',$r);
            if(intval($row['auth_number']) > 0){
                $r = json_decode($row['cargos'],TRUE);
                $cta_nro ="";
                $fec_ven ="";
                $cuota="";
                $interes="";
                if(is_array($r)){
                    foreach($r as $mr){
                        $cta_nro .= (!empty($mr['nro_cta']))?$mr['nro_cta'].'<br/>':'';
                        $fec_ven .= (!empty($mr['fec_vto']))?date('d/m/Y', strtotime($mr['fec_vto'])).'<br/>':'';
                        $cuota .= (!empty($mr['tot_cta']))?$mr['tot_cta'].'<br/>':'';
                        $interes .= (!empty($mr['interes_mora']))?$mr['interes_mora'].'<br/>':'';
                        // $tot_intereses += (!empty($mr['interes_mora']))?intval($mr['interes_mora']):0;
                        // $tot_pago += (!empty($mr['tot_cta']))?intval($mr['tot_cta']):0;
                    }
                }

                $res[] = [
                    'Fecha Pago'=> $row['fecha'],
                    'ID Transaccion' => $row['id'],
                    'Lote' => $row['lote'],
                    'Cuota Nro.'=>$cta_nro,
                    'Fecha Vto.'=>$fec_ven,
                    'Monto Cuota'=>$cuota,
                    "Intereses"=>$interes,
                    'Total Pagado' =>$row['effective_amount'],
                    'Autorización Nro.' =>$row['auth_number'],
                    'Tarjeta' =>$row['card_brand'],
                    'Tarjeta Nro.' =>$row['card_number'],
                ];
            }

          }
      }
      return $res;
  }





  // *************************************************************************
  // ******* 4 marzo 2020
  // ******* retorna las operaciones de pago con tarjeta
  // *************************************************************************
  function old_get_pagos_online(){
    $res = [];
    $dt = $this->Mdb->db->query("SELECT
      ci.id,
      ci.elem_id,
      DATE_FORMAT(ci.date,'%d/%m/%Y') as fecha,
      al.name as lote,
      ci.cargos,
      ci.effective_amount,
      ci.auth_number,
      ci.card_brand,
      ci.card_number
      FROM `contab_cobro_inmediato` ci
      JOIN elements_pcles ep on ep.elements_id = ci.elem_id AND ep.label = 'prod_id'
      JOIN atoms al on  al.id = ep.value
      ORDER BY ci.id DESC "
    );
    if($dt->result_id->num_rows){
      foreach ($dt->result_array() as $row) {



        $r = str_replace('[','',$row['cargos']);
        $r = str_replace(']','',$r);
        $r = json_decode($r,TRUE);
        // if(is_array($r)){
          // $ev = new Event($r['events_id'])
          $res[] = [
            'Fecha Pago'=> $row['fecha'],
            'ID Transaccion' => $row['id'],
            'Lote' => $row['lote'],
            'Cuota Nro.'=>(!empty($r) && key_exists('nro_cta',$r))?$r['nro_cta']:'',
            'Fecha Vto.'=>(!empty($r) && key_exists('fec_vto',$r))?date('d/m/Y', strtotime($r['fec_vto'])):'',
            'Monto Cuota'=>(!empty($r) && key_exists('tot_cta',$r))?$r['tot_cta']:'',
            "Intereses"=>(!empty($r) && key_exists('interes_mora',$r))?$r['interes_mora']:'',
            'Monto' =>$row['effective_amount'],
            'Autorización Nro.' =>$row['auth_number'],
            'Tarjeta' =>$row['card_brand'],
            'Tarjeta Nro.' =>$row['card_number'],
          ];
          // $res[]=json_decode($r);
        // }

      }
    }
    return $res;
  }

      /*
    [{
    "selected":"true",
    "events_id":"419780",
    "nro_cta":"Cuota 2 de 48",
    "fec_vto":"2020-04-10",
    "tipo":"cta_lote",
    "lote_name":"GA-115",
    "termino":"Normal",
    "tot_cta":"9992",
    "dias_mora":"0",
    "interes_mora":"0"
    },
    {"selected":"true",
    "events_id":"419823",
    "fec_vto":"2023-11-10",
    "nro_cta":"Cuota 45 de 48",
    "tipo":"cta_lote",
    "termino":"ADL",
    "dias_mora":"0",
    "interes_mora":"0",
    "tot_cta":"9992"
    }]

    */


  /*
  {"selected":"true","events_id":"412779","tipo":"cta_lote","lote_name":"TT-999","nro_cta":"Cuota 1 de 48","fec_vto":"2019-10-10","termino":"EN_MORA","tot_cta":"5000","dias_mora":"50","interes_mora":"500"},{"selected":"false","events_id":"412780","tipo":"cta_lote","lote_name":"TT-999","nro_cta":"Cuota 2 de 48","fec_vto":"2019-11-10","termino":"EN_MORA","tot_cta":"5000","dias_mora":"19","interes_mora":"190"}]

  */

  //*******************************************************
  // *** 13 marzo 2020
  // consulta la base y retorna el filtrado
  //*******************************************************
  public function get_ftrd_qry($f,$v){
    $q = $this->mk_qry($f,$v);
    $res = $this->Mdb->db->query($q);
    return $res->result_array();
  }


  //*******************************************************
  // *** 13 marzo 2020
  // CONSTRUYE UN QUERY  DE FILTRADO
  // RECIBE UN ARRAY CON EL PAR DE IDS  STRUCT_ID-ATOM_ID
  // O SOLO EL ID DE STRUCT O UN CUSTOM RANGE
  //*******************************************************
  public function mk_qry($i,$view){
    $sql=[];
    foreach ($i as $o) {
      // es range de cantidades o montos
      if(strpos($o['lbl'],'cant_') > -1 || strpos($o['lbl'],'monto_') > -1 ){
        $rng_in = (intval($o['cat']) >= 0)?intval($o['cat']):0;
        $rng_out = (intval($o['sbc']) >= intval($o['cat']))?intval($o['sbc']):99999999999;
        $sql[] = '('.$o['lbl'] .' >= '. $rng_in .' AND '.$o['lbl'].' <= '. $rng_out.')';
      }
      // es range de fechas
      else if(strpos($o['lbl'],'fec_') > -1){
        // valida cuando estan vacios los range in / out
        $rng_in = ($o['cat'] !== '')?$o['cat']:'01/01/1900';
        $rng_out = ($o['sbc'] !== '')?$o['sbc']:'01/01/2200';

        $sql[] = "(STR_TO_DATE(". $o['lbl'] .", '%d/%m/%Y') >= STR_TO_DATE('". $rng_in ."', '%d/%m/%Y')  AND  STR_TO_DATE(". $o['lbl'] .", '%d/%m/%Y') <=  STR_TO_DATE('". $rng_out ."', '%d/%m/%Y'))";
      }
      // solo categoria
      else if($o['cat'] === $o['sbc']){
        $sql[] = $o['lbl']. " IS NOT NULL";
      }
      // categoria y subcat
      else if ($o['cat'] !== $o['sbc']){
        $sql[] = $o['lbl']." = '".$o['sbc']."' " ;
      }
    }
    $query = "SELECT * FROM {$view}";
    if (!empty($sql)) {
      $query .= ' WHERE ' . implode(' AND ', $sql);
    }
    // var_dump($query);exit();
    return $query;
  }


  //**************************************************
  // *** 25 enero 2020
  // IMPORT DE ATOMS DESDE EXCEL CON UN STRUCT YA DEFINIDO EN LA BASE
  // DESCARTA LO QUE NO ESTA EN LA STRUCT PERO ACTUALIZA Y AGREGA
  //**************************************************
  function import_excel($archivo,$atom_type_name){
    $checked_atom_type = $this->Mdb->db->query("SELECT id FROM atom_types WHERE name LIKE '$atom_type_name'");
    if(!$checked_atom_type->result_id->num_rows){
      return 'Error atom type name no existe';
    }
    try {
      $t = $this->excel_to_arr($archivo);
    } catch (\Exception $e) {
      // var_dump($e);
      return  "Error :".$e->getMessage();
    }
    // KEYS IN STRUCT_DB SI NO ESTA EN DB LO IGNORA
    $x = $this->Mdb->db->query("SELECT label FROM `atoms_struct` WHERE atom_types_id = {$checked_atom_type->row()->id} ");
    if(!$x->result_id->num_rows){
      return 'Error colectando los labels de atoms_struct';
    }
    $keys_in_struct = $x->result_array();
    foreach ($t as $i => $row) {
      $pcles = [];
      foreach ($keys_in_struct as $rowkey => $rowval) {
        $k = $this->check_key($rowval['label'],$t[1]);
        if($k){
          $s1 = preg_replace('/[^A-Za-z0-9`\/ñÑáéíóúü@.%\-_]/', "_", $row[$k]);
          // validate row key:value
          if($i > 1 && $s1 != ' ' && strlen($s1) > 0 ){
            $pcles[$rowval['label']]=$s1;
          }
        }
      }
      if(!empty($pcles)){
        echo "<br/>setting Atom->".$atom_type_name.' name:'.reset($pcles);
        $x = new Atom(0,$atom_type_name,reset($pcles));
        foreach ($pcles as $pkey => $pval) {
          echo "<br/> pcle key".$pkey.' val:'.$pval;
          $x->pcle_updv($x->get_pcle($pkey)->id,$pval);
          // echo "<br/> atom id: ".$x->id." new val: ".$x->get_pcle($pkey)->value;
          // si no encuentra en atom crea uno, eso esta bien? al momento de importar from excel?
        }
      }
    }
    return 'OK';
  }


  function file_import($atom_type){
    // **** VALIDAR SI EXISTE EL DIRECTORIO O CREARLO
    if (!is_dir('./uploads/'.$atom_type)) {
      mkdir('./uploads/'.$atom_type, 0777, TRUE);
    }
    $config['upload_path']          = './uploads/'.$atom_type;
    $config['allowed_types']        = 'xls|xlsx';
    $config['max_size']             = 20480;
    $config['max_width']            = 1024;
    $config['max_height']           = 768;
    $config['overwrite']            = true;

    if (isset($_FILES['file']['name'])) {
      if (0 < $_FILES['file']['error']) {
        echo 'Error: No se puede cargar el archivo'. $_FILES['file']['error'];
      } else {
        $_FILES['file']['name'] = $this->sanitize_filename($_FILES['file']['name']);
        $this->CMF->load->library('upload', $config);
        if (!$this->CMF->upload->do_upload('file')) {
          echo $this->CMF->upload->display_errors();
        } else {
          return $atom_type.'/'.$_FILES['file']['name'];
        }
      }
    } else {
      echo 'Selecciona un archivo valido ';
    }
  }

  // ***************************
  // *** 25 enero 2020
  // *** TRAE DATOS DE UN EXCEL A UN ARRAY
  //  LA LIBRERIA ESTA DESACTUALIZADA HAY QUE TRAER NUEVA LIB
  // function excel_to_arr($arch){
  //   $inputFileName = 'uploads/'.$arch;
  //   // echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME);
  //   $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
  //   $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
  //   return $sheetData;
  // }

  // ***************************
  // *** 25 enero 2020
  // *** encuentra el array key de encabezado en un
  // *** array devuelto por excel_to_arr
  function check_key($key, $arr){
    foreach ($arr as $xlk => $x) {
      if ($x === $key) {
        return $xlk;
      }
    }
    return null;
  }





  //***************************************************************************
  // *** 13/01/2020
  // *** retorna el nombre de dom element del id de struct en param
  //***************************************************************************
  function get_vis_elem_name($id){
    $r = $this->Mdb->db->query("SELECT nombre FROM visual_objects WHERE id = {$id} ");
    if($r->result_id->num_rows){
      return $r->row()->nombre;
    }else{
      return 'text';
    }
  }

  //**************************************************
  //*** 21/12/2019
  //*** OBTIENE DATOS PARA UN SELECT ELEMENT
  //*** DESDE EL QUERY EN PARAM
  //*************************************************
  function mk_select($query,$label_field){
    $r = [];
    $x = $this->Mdb->db->query($query);
    if($x->result_id->num_rows){
      foreach ($x->result_array() as $v) {
        $r[]=['id'=>$v['id'],'lbl'=>$v[$label_field]];
      }
      return $r;
    }
    return false;
  }


  // *************************************************************************
  // *** 19/12/2019
  // *** UPLOAD DE ARCHIVOS
  // ************************************************************************

  public function file_upload($nom,$elm_id,$folder){
    $config['upload_path']          = './uploads/'.$folder."/";
    $config['allowed_types']        = 'jpg|png|pdf|doc|docx';
    $config['max_size']             = 10000;
    // $config['max_width']            = 15000;
    // $config['max_height']           = 25000;
    $config['remove_spaces']        = true;
    $config['overwrite']            = false;

    if (isset($_FILES['file']['name'])) {
      if (0 < $_FILES['file']['error']) {
        $m = 'Error en la carga del archivo'. $_FILES['file']['error'];
        return array('response'=>false,'msg' => $m);
      } else {
        if(!preg_match("/$nom/",$this->sanitize_filename($_FILES['file']['name']))){
          $_FILES['file']['name'] = $nom."_".$this->sanitize_filename($_FILES['file']['name']);
        }
        $this->CMF->load->library('upload', $config);
        if (!$this->CMF->upload->do_upload('file')) {
          return array('response'=>false,'msg'=>$this->CMF->upload->display_errors());
        } else {
          return array('response'=>true,'msg'=>'OK!','folder'=>$folder,'up_files'=>$this->get_uploaded_files($elm_id,$folder));
        }
      }
    } else {
      return array('response'=>false,'msg'=>'Selecciona un archivo valido');
    }

  }

  // *************************************************************************
  // *** 19/12/2019
  // *** obtiene una lista de los archivos subidos en la carpeta
  // ***
  // ************************************************************************
  function get_uploaded_files($elm_id,$folder){
    $this->CMF->load->helper('directory');
    $e = new Element($elm_id);
    $l = new Atom($e->get_pcle('prod_id')->value);
    $partida = $l->get_pcle('partida')->value;
    $lnom = $l->name;
    $files_list = [];
    $d = directory_map('./uploads/'.$folder."/");
    if($d){
      foreach ($d as $v) {
        if(!is_array("/$lnom/") && preg_match("/$lnom/",$v) || substr($v,0,strpos($v,'.')) === $partida ){
          $files_list[] = $v;
        }
      }
    }
    return $files_list;
  }



  // *************************************************************************
  // *** 28 de noviembre 2019
  // *** new imputacion de cuotas pagadas para ipn de pasarela de pagos
  // *** array $p
  // ************************************************************************
  function inputar_cuotas($p,$elm_id,$fec_pago,$trns_id,$medio_de_pago){
    // ***** CHECK NRO DE RECIBO
    $rec_num = $this->get_recnum();
    // SALIDA SI FALLA EL NUMERO DE RECIBO devuelve false
    if(!$rec_num){return false;}
    //*** NO HAY CUOTAS SELECIONADAS RETORNA FALSE
    if(count($p) == 0){return false;}

    // GENERO UN RECIBO REGISTRANDO LAS IMPUTACIONES EN COMPROBANTES
    // ARRAY PARA HACER LA TABLA DE DETALLES
    $det_evnts_id = [];
    $concepto = '';

    // REGISTRA PAGADAS LAS CUOTAS DE LOTES
    $p_lote = array_filter($p,function($i){return $i['tipo'] == 'cta_lote';});
    if(is_array($p_lote)){
      $tcta =  array_reduce($p_lote, function($a,$x){$a += intval($x['tot_cta']);return $a;});
      $tint =  array_reduce($p_lote, function($a,$x){$a += intval($x['interes_mora']);return $a;});
      $concepto .= $this->make_observac_pago($p_lote,'lote');
      $det_ev_ctas = $this->set_pago_ev($p_lote,$p,$rec_num,$fec_pago);
    }

    // REGISTRA PAGADAS CUOTAS DE SERVICIOS
    // if(intval($p['tot_mto_srvc'])>0){
    $p_srvc = array_filter($p,function($i){return $i['tipo'] == 'cta_srvc';});
    if(is_array($p_srvc)){
      $srv_tcta =  array_reduce($p_srvc, function($a,$x){$a += intval($x['tot_cta']);return $a;});
      $srv_tint =  array_reduce($p_srvc, function($a,$x){$a += intval($x['interes_mora']);return $a;});
      $concepto .= $this->make_observac_pago($p_srvc,'srvc');
      $det_ev_srvc =  $this->set_pago_ev($p_srvc,$p,$rec_num,$fec_pago);
    }
    //*** MERGE ARRAY GUARDAR Y CONSTRUIR DETALLE
    $detalle = [];
    if(!empty($det_ev_ctas)){
      if(!empty($det_ev_srvc)){
        $detalle = array_merge($det_evnts_id,$det_ev_ctas,$det_ev_srvc);
      }else{
        $detalle = array_merge($det_evnts_id,$det_ev_ctas);
      }
    }
    if(!empty($det_ev_srvc) && empty($det_ev_ctas)){
      $detalle = array_merge($det_evnts_id,$det_ev_srvc);
    }

    // **** FILL DE DATOS PARA EL ASIENTO DE CAJA
    $e = new Element($elm_id);
    $c = [
      'nro_comprobante'=> $rec_num,
      'cuentas_id' => 27,
      'cliente_id'=>$e->get_pcle('cli_id')->value,
      'lote_id'=>$e->get_pcle('prod_id')->value,
      'operador_usuario_id'=>$e->get_pcle('cli_id')->value,
      'origen'=>'pago_online',
      'tipo_asiento'=>'INGRESOS',
      'cuenta_imputacion_id' => 191,
      'monto'=> ($tcta + $srv_tcta + $tint + $srv_tint),
      'observaciones'=>'pago online Nro: '.$trns_id
    ];
    $empre = (new Atom($e->get_pcle('prod_id')->value))->get_pcle('emprendimiento')->value;
    $barrio_id = 0;
    $bx = $this->Mdb->db->query("SELECT id FROM atoms WHERE name  = '{$empre}'");
    if($bx->result_id->num_rows){$barrio_id = $bx->row()->id;}
    $ccd[] = ['barrio_id'=> $barrio_id,'percent'=> "100"];

    $op_nro = $this->mk_asiento_caja($c,$ccd);




    // GUARDO EN COMPROBANTES
    $dtl = (count($detalle)>0)?implode('|', $detalle):0;
    $this->Mdb->db->update(
      'comprobantes',
      [
        'fecha_comprobante'=>$fec_pago,
        'tipo_comprobante'=>'RECIBO',
        'monto'=>( $tcta + $srv_tcta ),
        'intereses_monto'=>( $tint + $srv_tint ),
        // ** saldo es elsaldo anterior menos el monto imputado
        // 'saldo'=>intval($p['saldo']),
        'concepto' => $concepto,
        'detalle_events_id' => $dtl,
        // 'id_usuario'=>$p['user_id'],
        'elements_id'=>$elm_id,
        'op_caja_nro'=> $op_nro
      ],
      'nro_comprobante',
      $rec_num
    );

  }

  //*** CONCEPTO DEL RECIBO Y OBSERVACIONES EN ASIENTO DE OPERACIONES
  function make_observac_pago($elms,$tipo){
    $cpn = array_filter($elms,function($i){return $i['termino'] !== 'ADL';});
    $cpadl = array_filter($elms,function($i){return $i['termino'] === 'ADL';});

    // $res = [$cpn,$cpadl];
    // echo json_encode(array('callback'=> 'check','param'=> $res));
    // exit;

    $r = '';
    switch ($tipo) {
      case 'lote':
      if(count($cpn) >1){
        $kin = array_shift($cpn);
        $kout = array_pop($cpn);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .= 'Lote Debito Ctas. '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
      }elseif(count($cpn) === 1){
        $kin = array_shift($cpn);
        if($kin['nro_cta'] === 'Anticipo'){
          $r .= 'Lote Debito Anticipo ';
        }else{
          $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
          $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
          $r .= 'Lote Debito Cta. '.$cta_init.' de'.$tot_ctas;
        }
      }
      if(count($cpadl) > 1){
        $kin = array_shift($cpadl);
        $kout = array_pop($cpadl);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .= 'Lote Debito Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
      }elseif(count($cpadl) === 1){
        $kin = array_shift($cpadl);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .= 'Lote Debito Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
      }
      break;
      case 'srvc':
      if(count($cpn) > 1){
        $kin = array_shift($cpn);
        $kout = array_pop($cpn);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .= " ".substr($kin['srv_name'], 0,20).' Debito Cuotas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
      }elseif(count($cpn) === 1){
        $kin = array_shift($cpn);
        if($kin['nro_cta'] === 'Anticipo'){
          $r .= " ".substr($kin['srv_name'], 0,20).' Debito Anticipo';
        }else{
          $cta_init = ($kin['nro_cta'] == 'Anticipo')?$kin['nro_cta']:str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
          $tot_ctas = ($kin['nro_cta'] == 'Anticipo')?' ':substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
          $r .=" ".substr($kin['srv_name'], 0,20).' Debito Cta. '.$cta_init.' de'.$tot_ctas;
        }
      }
      if(count($cpadl) > 1){
        $kin = array_shift($cpadl);
        $kout = array_pop($cpadl);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .=" ".$kin['srv_name'].' Debito Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
        // $r .=" ".substr($kin['srv_name'], 0,20).'Debito Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
      }elseif(count($cpadl) === 1){
        $kin = array_shift($cpadl);
        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
        $r .= " ".$kin['srv_name'].' Debito Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
        // $r .= " ".substr($kin['srv_name'], 0,20).'Debito Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
      }
      break;
    }
    return $r;
  }




  //***  ACTUALIZA LOS EVENTS CON LOS DATOS DEL PAGO LO USA VERSION 2 DE PAGO hay duplicado en clientes
  function set_pago_ev($evs_arr,$p,$rec_num,$fec_pago){
    $evs_updated =[];
    //***** ACTUALIZO EVENTS
    foreach ($evs_arr as $pg) {

      $ev = new Event($pg['events_id']);

      // ACTUALIZA CUOTAS RESTANTES EN EL ELEMENT CONTRATO
      $elm = new Element(intval($ev->get('elem_id')));
      $ctasrest = $elm->get_pcle('cant_ctas_restantes');
      $elm->pcle_updv($ctasrest->id,(intval($ctasrest->value)-1));
      //*** CONTROLA EL NUMERO DE CUOTA Y PONE PLAN_UPDATE_PENDING EN TRUE SI ES EL NUEMRO DE REVISION DE PLAN
      //falta rever update plan dentro de cmn functs
      $this->handler_update_plan($elm);
      // ********
      // ***  SETEA EL ID DEL TERMINO DEL EVENTO CON 4 VENCIDO/PAGADO O 6 ADELANTADO
      $t = $this->set_event_type_id($pg['termino'],$ev);
      $ev->set_pcle(0,'estado', 'pagado');
      $ev->set_pcle(0,'fec_pago',$fec_pago,'Fecha de Pago',1);
      $ev->set_pcle(0,'monto_pagado',$pg['tot_cta'],'Monto Pagado',1);
      $ev->set_pcle(0,'recibo_nro',$rec_num,'Nro. Recibo',1);
      if(intval($pg['interes_mora']) > 0){
        $ev->set_pcle(0,'estado','p_ftrm');
        $ev->set_pcle(0,'dias_mora',$pg['dias_mora'],'Dias Mora',1);
        $ev->set_pcle(0,'interes_mora',$pg['interes_mora'],'Interes',1);
      }
      $evs_updated[] = $ev->id;
    }
    return $evs_updated;
  }

  // *************************************************************************
  // ******* PONE UPDATE PLAN EN REVISION PARA LA PROXIMA CUOTA
  // *************************************************************************

  function handler_update_plan($e){
    $ctas_restantes = intval($e->get_pcle('cant_ctas_restantes')->value);
    $cclo = intval($e->get_pcle('current_ciclo')->value);
    $cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);
    $aprv = intval($e->get_pcle('aplica_revision')->value);
    $frec_rev = intval($e->get_pcle('frecuencia_revision')->value);
    $ctas_restantes_cclo1 = intval($e->get_pcle('cant_ctas_restantes')->value) - $cclo2;
    $pup = $e->get_pcle('plan_update_pending')->value;
    //****  CONTROL LOG
    // $x = [
    //   'cant_ctas_restantes'=>$ctas_restantes,
    //   'curr_ciclo'=>$cclo,
    //   'cclo2'=>$cclo2,
    //   'aplica rev'=>$aprv,
    //   'frec_rev'=>$frec_rev,
    //   'ctas_restantes_ciclo1'=>(intval($ctas_restantes_cclo1)>=0)?$ctas_restantes_cclo1:0,
    //   'curr_update_pending'=>$pup
    // ];
    // $this->Mdb->db->insert('lotes_error_log',array('lote'=>$e->id,'error'=>json_encode($x)));
    // ******

    //  CICLO ACTUAL ES 1 APLICAR REVISION ES TRUE CANTIDAD DECUOTAS EN CICLO 2  MAYOR A CERO EL NUMERO DE CUOTA ESTA EN LA FRECUENCIA DE REVISION
    if($cclo == 1 && $aprv == 1 && $cclo2 > 0 && $ctas_restantes % $frec_rev == 0 ){
      $e->pcle_updv($pup,'true');
    }
    //  CICLO ACTUAL ES 2  Y EL NUMERO DE CUOTA ESTA EN LA FRECUENCIA DE REVISION
    if($cclo == 2 && $ctas_restantes % $frec_rev == 0 ){
      $e->pcle_updv($pup,'true');
    }
    //  CICLO ACTUAL ES 1 Y CTAS RESTANTES DE CICLO 1 ES CERO Y HAY CICLO2
    if($cclo == 1 && $ctas_restantes_cclo1 == 0 && $cclo2 > 0 ){
      $e->pcle_updv($pup,'true');
    }
    // CICLO 1 APLICA REV Y CTA RESTANTES ES MULTIMPLO DE FREC_REV
    if($cclo == 1 && $aprv == 1 && $cclo2 <= 0 && $ctas_restantes % $frec_rev == 0 ){
      $e->pcle_updv($pup,'true');
    }
  }

  // *************************************************************************
  // ******* DEVUELVE EL ID DEL TIPO DE EVENTO EN BASE A  ADL / EN_MORA
  // *************************************************************************
  function set_event_type_id($t,$ev){
    switch ($t) {
      case 'EN_MORA':
      $ev->set('events_types_id','4');
      break;
      case 'ADL':
      $ev->set('events_types_id','6');
      break;
      default:
      $ev->set('events_types_id','4');
      break;
    }
  }


  // ************************************************************************************************
  // ******* DEVUELVE la fecha de pago en formato dd/mm/AAAA tomando fecha de transaccion desde ipn
  // ************************************************************************************************
  function get_transaction_date($d){
    $x = new DateTime(substr($d,0,4).'-'.substr($d,4,2).'-'.substr($d,6,2).' '.substr($d,8,2).':'.substr($d,10,2).':'.substr($d,12,2));
    if (is_a($x, 'DateTime')) {
      return $x;
    }else{
      return new DateTime();
    }

  }

  // *************************************************************************
  // *** 28 de noviembre 2019
  // *** creates a transaction para pagos online
  // *** args :  elem_id , $events (array con id de los eventos pagados y el monto de cada uno)
  // *** return integer con contab_cobro_inmediato id
  // ************************************************************************
  function get_transaction_id($elm_id,$events,$monto){
    // $monto_ctas = [];$monto_intereses = [];$events_id = [];
    // foreach ($events as $ev) {
    //   $monto_ctas[] = $ev['tot_cta'];
    //   $monto_intereses [] = $ev['interes_mora'];
    //   $events_id[]=$ev['events_id'];
    // }
    $this->Mdb->db->insert('contab_cobro_inmediato',[
      'elem_id' => $elm_id,
      'cargos'=> json_encode($events),
      // 'monto_cuotas'=>json_encode($monto_ctas),
      // 'monto_intereses'=>json_encode($monto_intereses),
      'amount'=>$monto,
    ]);
    $i = $this->Mdb->db->insert_id();
    return $i;
  }




  function update_vencimientos($e){
    $dt_now = new DateTime(date('Y-m-d'));
    $f = $e->get_events(8,'a_pagar');
    foreach ($f['events'] as $xv) {
      $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
      $dt_diff = $dt_xv->diff($dt_now);
      if($dt_diff->invert == 0){
        if($dt_diff->days >= 25){
          $this->Mdb->db->update('events',['events_types_id'=>4],'id',$xv['id']);
        }
      }
    }
  }

  //*****  COLUMNA DE BUTTONS DE ACCIONES EN LA TABLA DE RESUMEN DE CUENTA
  //  DEVUELVE EL HTML CON LA LLAMADA A ROUTER.JS Y EL ELEMENT_ID CORRESPONDIENTE A LA CUOTA

  function get_acciones($e){
    $r = '<div class=\'row d-flex\'><div class=\'col d-flex inline-flex justify-content-end\'>';
    $cp = $e->get_tot_pagado();

    if($e->type_id == 4 && $cp == 0){
      $r .= $this->cmn_functs->get_accion_icon('delete_outline','kill_elem',$e->id,0);
    }
    if($this->usr_obj->permisos_usuario < 3){
      if ($e->get_events(4,'a_pagar')['total'] > 0 || $e->get_events(8,'a_pagar')['total'] > 0){
        // HABILITA EL BOTON DE IMPRIMIR
        $r .= $this->cmn_functs->get_accion_icon('print','print_pagares',$e->id,0);
      }
    }
    $r .="</div></div>";
    return $r;
  }








  // TASA DE REINTEGRO
  function get_mto_rtg($elm){
    $tasa_rtg = intval($elm->get_pcle_db('tasa_reintegro_id'));
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
        return intval($cp['tot_pagado'] - ($cp['tot_pagado']*$r/100)) ;
      }
    }
    return 0;
  }


  // *************************************************************************
  // ******* 11/11/2019
  // OBTIENE EL monto disponible para credito personales
  // *************************************************************************
  function get_disponible_credito($e){
    $rtg =  $this->get_mto_rtg($e);
    $res = 0;
    $srv_elmtype = $this->CMF->app_model->get_obj("SELECT id FROM elements_types WHERE name = 'SERVICIO' ");
    // **** COLECTA LOS SERVICIOS DEL CONTRATO CON ELM_ID
    $s = $this->CMF->app_model->get_arr("SELECT id FROM elements WHERE elements_types_id = {$srv_elmtype->id} AND owner_id = {$e->id} ");
    foreach ($s as $v) {
      $srv = new Element($v['id']);
      $srv_atom_id = $srv->get_pcle('atom_id')->value;
      $srv_atm = new Atom($srv_atom_id);
      if(strpos($srv_atm->name, 'Prestamo') > -1 && $srv->get_cant_ctas_restantes() > 0){
        // monto de cuota 1 sirve para obtener el monto nominal del prestamo, si no esta paga es busca la primera a pagar
        $c = $srv->get_first_event('pagado');
        if(!empty($c)){
          $cta_1 = intval($c['pcles']['monto']->value);
          if($cta_1 <= 0){$cta_1 = intval($srv->get_first_event('a_pagar')['pcles']['monto']->value);}
          if($cta_1 > 0){
            // POR CADA 10K PRESTADO HAY 500 DE CUOTA
            $res += ($cta_1 / 500)*10000;
          }
        }
      }
    }
    return $rtg - $res;
  }




  // *************************************************************************
  // ******* 12 de noviembre 2019
  // OBTIENE EL INDICE DE ACTUALIZACION CALCULANDO LA DIF ENTRE LAS CUOTAS
  // *************************************************************************

  function verif_indac($e_id){
    $elm = new Element($e_id);
    $p = $elm->get_last_payment();
    if(empty($p->id)){
      $p = $elm->get_event_by_ord_num(1);
    }
    // $fr = intval($elm->get_pcle('frecuencia_indac')->value);
    // if(empty($p->id)){
    //   return 0;
    // }
    $mto_a = intval($p->get_pcle('monto_cta')->value);
    $ctas_restantes = intval($elm->get_pcle('cant_ctas_restantes')->value);
    $px = $p->id ;
    $mto_x = 0;
    $index = 0;
    while ($mto_x <= $mto_a && $index < $ctas_restantes) {
      $index ++;
      $px ++;
      $ev_x = new Event($px);
      if(property_exists($ev_x,'id')){
        $mto_x = intval($ev_x->get_pcle('monto_cta')->value);
      }
    }
    if($mto_x > 0){
      $res = number_format(floatval((($mto_x-$mto_a)/$mto_a)*100),2);
    }else{
      $res = 0;
    }
    return $res;

  }

  // *************************************************************************
  // ******* 18 de octubre 2019
  // ******* DEVUELVE ES STRUCT DE ATOM PEDIDO POR TYPE_TEXT
  // *************************************************************************

  function call_atom_struct($type_text){
    $st = '';
    $t = $this->Mdb->db->query("SELECT id FROM atom_types WHERE name = '{$type_text}' ");
    if($t->result_id->num_rows){
      $st = $this->Mdb->db->query("SELECT s.label,s.value,s.title,vo.nombre as vis_elem_type,s.vis_ord_num,s.validates FROM `atoms_struct` s JOIN visual_objects vo on vo.id = s.vis_elem_type WHERE s.atom_types_id = {$t->row()->id} ORDER BY s.vis_ord_num ASC")->result_array();
    }

    return $st;
  }


  // *************************************************************************
  // ******* 18 de octubre 2019
  // *************************************************************************

  function save_new_atom($type_text,$fields){
    $exists = $this->check_dni($fields);
    if($exists){
      $a = new Atom($exists);
    }else{
      $name = $this->make_atom_name($type_text,$fields);
      $a = new Atom(0,$type_text,$name);
    }
    foreach ($fields as $pcle) {
      $a->pcle_updv($a->get_pcle($pcle['label'])->id,$pcle['value']);
    }
    return $a->id;
  }



  function check_dni($f){
    $id = 0;
    foreach ($f as $i) {
      if($i['label'] == 'dni'){
        $t = $this->Mdb->db->query("SELECT atom_id FROM atoms_pcles WHERE label = 'dni' AND CONVERT(value,UNSIGNED INTEGER)  = CONVERT('{$i['value']}',UNSIGNED INTEGER)");
        if($t->result_id->num_rows){
          $id = $t->row()->atom_id;
        }
      }
    }
    return $id;
  }

  // *************************************************************************
  // ******* 18 de octubre 2019
  // ******* OBTIENE DE FIELDS LA DATA PARA EL NOMBRE DEL ATOM SEGUN EL TIPO
  // *************************************************************************
  function make_atom_name($type_text,$fields){
    if($type_text == 'CLIENTE' || $type_text == 'VENDEDOR' ){
      return $fields[1]['value'] .' '. $fields[0]['value'];
    }
    else{
      return $fields[0]['value'];
    }
  }

  // *************************************************************************
  // ******* 4 de octubre 2019
  // ******* PREPARA LA VENTANA DEl ATOM / ELEM / EVENT A EDITAR
  // *************************************************************************
  function call_edit($type,$id){
    $t = new $type($id);
    // echo json_encode($t->get_props());
    // exit();
    return $t->get_props();
  }

  // *************************************************************************
  // ******* 4 de octubre 2019
  // ******* GUARDA LOS DATOS DEl ATOM / ELEM / EVENT  EDITADO
  // *************************************************************************
  function save_edit($type,$d){
    // CHECK IF ARRAY HAS ALL NEEDED KEYS

    $a = new $type(intval($d['id']));
    // GUARDA LOS PCLES POR SUS LABELS
    foreach ($d['fields'] as $pcle) {
      if(array_key_exists('value',$pcle)){
        $a->pcle_updv(intval($pcle['id']),$pcle['value']);
      }
    }
    if($a->type_id == 1 || $a->type_id == 14){
      $a->set('name',$d['fields'][1]['value'].' '.$d['fields'][0]['value']);
    }else{
      $a->set('name',$d['fields'][0]['value']);
    }
  }


  //**** update 13 agosto 2020
  // *************************************************************************
  // ******* retorna las cuotas de lote a pagar vencidas y mes en curso
  // ******* si hay cuota refuerzo la muestra primero
  // *************************************************************************
  function get_lote_ctas_a_pagar($e){
    $cx=[];
    $lname = substr((new Atom($e->get_pcle('prod_id')->value))->name, 0,20);
    // VENCIDAS
    foreach ($e->get_events(4,'a_pagar')['events'] as $c) {
      if(!$this->check_refi($c['id'])){
        $cta = new Event($c['id']);
        $dm = ($this->dif_dias($cta->date) > 25)?$this->dif_dias($cta->date):0;
        $intrs = ($this->dif_dias($cta->date) > 25)?ceil(intval($cta->get_pcle('monto_cta')->value) * (0.002 * intval($this->dif_dias($cta->date)))):0;
        $cx[]=[
          'selected'=>true,
          'events_id'=>intval($cta->id),
          'tipo'=>'cta_lote',
          'lote_name'=>$lname,
          'nro_cta'=>$cta->get_pcle('nro_cta')->value,
          'fec_vto'=>$this->fixdate_ymd($cta->get_pcle('fecha_vto')->value),
          'termino'=>'EN_MORA',
          'tot_cta'=>intval($cta->get_pcle('monto_cta')->value),
          'dias_mora'=>$dm,
          'interes_mora'=> $intrs,
        ];
      }
    }
    // *** MES EN CURSO
    //  si es lote con xfinanc_abril
    // y no tiene ctas vencidas no levanta la cuota future
    $c=[];
    $testc = $e->get_events_first_future();
    if($this->validate_dt_cta($testc['events']['fecha'])){
      $c = $testc;
    }
    //  CHECK DE CUOTAS REFUERZO
    if(intval($e->get_pcle('frecuencia_ctas_refuerzo')->value) > 0){
      $x = $e->get_event_refuerzo();
      //  SI EXISTEN CUOTAS REFUERZO Y LA CUOTA REFUERZO TIENE VENCIMIENTO EN EL MES EN CURSO
      if($x !== false && $this->validate_dt_cta($x['events']['fecha']))
        if(!empty($c)){
          $c['events'] = array_merge($c['events'],$x['events']);
        }else{
          // SI $C ESTA VACIO AGREGA SOLO LA CUOTA REFUERZO
          $c = $x;
        }
    }
    if(!empty($c)){
      if(!$this->check_refi($c['events']['id'])){
        $cta = new Event($c['events']['id']);
        if(isset($cta->id)){
          $cx[] = [
            'selected'=>true,
            'events_id'=>$cta->id,
            'nro_cta'=>$cta->get_pcle('nro_cta')->value,
            'fec_vto'=>$this->fixdate_ymd($cta->get_pcle('fecha_vto')->value),
            'tipo'=>'cta_lote',
            'lote_name'=>$lname,
            'termino'=>'Normal',
            'tot_cta'=>intval($cta->get_pcle('monto_cta')->value),
            'dias_mora'=>0,
            'interes_mora'=>0
          ];
        }
      }
    }
    return $cx;
  }

  // *************************************************************************
  // ******* retorna las cuotas a pagar de servicios del lote, vencidas y mes en curso
  // *************************************************************************
  function get_lote_servs_a_pagar($e){
    // $d = date('d/m/Y');
    $srv_register = [];
    $srv_adls = [];
    $sr_ctas = [];
    // $refi_atom_id = $this->check_refi($e->id);
    // if($refi_atom_id){
    //   // **** COLECTA LOS SERVICIOS REFI DESDE EL ATOM REFINANC
    //   $rf = new Atom($refi_atom_id);
    //   $srv[] = ['id'=>$rf->get_pcle('refi_lote_id')->value];
    //   if(!empty($rf->get_pcle('refi_servicios_id')->value)){
    //     $srv[] = ['id'=>$rf->get_pcle('refi_servicios_id')->value];
    //   }
    // }
    // **** DETERMINA EL TIPO SERVICIO
    $srv_elmtype = $this->CMF->app_model->get_obj("SELECT id FROM elements_types WHERE name = 'SERVICIO' ");
    // **** COLECTA LOS SERVICIOS DEL CONTRATO CON ELM_ID
    $srv = $this->CMF->app_model->get_arr("SELECT id FROM elements WHERE elements_types_id = {$srv_elmtype->id} AND owner_id = {$e->id} ");
    //  elem tiene servicios
    if (!empty($srv)){
      //**  TO DO
      // busca primero xfinanc_abril cuota lote y lo carga
      // si encuentra service xfinanc_servicios
      // trae solo el servicio xfinanc sino trae normalmente los servicios.

      foreach ($srv as $s) {
        $service = new Element($s['id']);
        $atom = new Atom($service->get_pcle('atom_id')->value);
        $srv_cta_imputacion_id = $atom->get_pcle('cuenta_de_imputacion')->value;
        $srv_register[]=$service->id;
        // GET CTAS VENCIDAS IMPAGAS de SERVICIOS
        $cm = $service->get_events(4,'a_pagar');
        if(!empty($cm)){
          foreach ($cm['events'] as $c) {
            $srv_cta = new Event($c['id']);
            if(!$this->check_refi($c['id'])){
              // $sr_fec = $c->get_pcle('fecha_vto')->value;
              // $sr_fec = array_filter($c['pcles'],function($i){return $i->label == 'fecha_vto';});
              $sr_fec_vto = $this->fixdate_ymd($srv_cta->get_pcle('fecha_vto')->value);
              $sr_monto = $srv_cta->get_pcle('monto_cta')->value;
              // $sr_monto = array_filter($c['pcles'],function($i){return $i->label == 'monto_cta';});
              $sr_nro_cta = $srv_cta->get_pcle('nro_cta')->value;
              // $sr_nro_cta = array_filter($c['pcles'],function($i){return $i->label == 'nro_cta';});
              $sr_estado = $srv_cta->get_pcle('estado')->value;
              // $sr_estado = array_filter($c['pcles'],function($i){return $i->label == 'estado';});
              $s_dm = ($this->dif_dias($srv_cta->date) > 25)?$this->dif_dias($srv_cta->date):0;
              $s_intrs = ($this->dif_dias($srv_cta->date) > 25)?ceil(intval($srv_cta->get_pcle('monto_cta')->value) * (0.002 * intval($this->dif_dias($srv_cta->date)))):0;

              // ** LA CUOTA APAGAR
              $sr_ctas[] = [
                'selected'=>true,
                'events_id'=>intval($srv_cta->id),
                'nro_cta'=>$sr_nro_cta,
                'fec_vto'=>$this->fixdate_ymd($sr_fec_vto),
                'tipo'=>'cta_srvc',
                'termino'=>'EN_MORA',
                'tot_cta'=>intval($sr_monto),
                'dias_mora'=>$s_dm,
                'interes_mora'=>$s_intrs,
                'srv_name'=>substr($atom->name, 0,20),
                'srv_cta_imputacion_id'=>$srv_cta_imputacion_id
              ];
            }
          }
        }
        // GET CTA UPCOMING  servicios--
        $c = $service->get_first_future_event('a_pagar');
        if(!empty($c)){
          $ct = $c;
          if($this->validate_dt_cta($ct['fecha'])){
            if(!$this->check_refi($ct['pcles']['id'])){
              // ** LA CUOTA APAGAR
              $sr_ctas[] = [
                'selected'=>true,
                'events_id'=>$ct['pcles']['id'] ,
                'nro_cta'=>$ct['pcles']['nro_cta']->value,
                'fec_vto'=>$this->fixdate_ymd($ct['pcles']['fecha_vto']->value),
                'tipo'=>'cta_srvc',
                'termino'=>'Normal',
                'tot_cta'=>intval($ct['pcles']['monto_cta']->value),
                'dias_mora'=>0,
                'interes_mora'=>0,
                'srv_name'=>substr($atom->name, 0,20),
                'srv_cta_imputacion_id'=>$srv_cta_imputacion_id
              ];
            }
          }
        }
        //  GET CTAS ADL
        $srv_to_adl =$this->get_ctas_adl($service);
        if(!empty($srv_to_adl)){
          $ev_id = $srv_to_adl['disp'][0]['events_id'];
          if(!$this->check_refi(intval($ev_id)-1) && !$this->check_refi($ev_id)){
            $srv_adls[] = [
              'srv_register'=>$service->id,
              'srv_name'=>substr($atom->name, 0,20),
              'srv_imputacion_id'=>$srv_cta_imputacion_id,
              'adls'=> $srv_to_adl
            ];
          }
        }
      }
    }
    return ['reg'=>$srv_register,'adls'=>$srv_adls,'ctas'=>$sr_ctas];
  }

  // *************************************************************************
  // ******* PRESENTAR PAGO DE CUOTAS VERSION 2
  // *************************************************************************

  function set_pago_de_cuotas($elm_id){
    $ctas = 0;
    $e = new Element(intval($elm_id));
    // CUOTAS DE LOTE
    $ctas = $this->get_lote_ctas_a_pagar($e);


    // SERVICIOS
    $s = $this->get_lote_servs_a_pagar($e);
    // CUOTAS DE LOTE PARA ADELANTAR
    $ctas_restantes = $e->get_pcle('cant_ctas_restantes')->value;
    $ctas_adl = (intval($ctas_restantes)>1)?$this->get_ctas_adl($e):[];

    // GETS SALDO DESDE COMPROBANTES
    $saldo_int = $this->get_saldo_comprobantes($elm_id);
    // ****** RESPONSE
    $res=[
      'adls'=>$ctas_adl,
      'a_pagar'=>$ctas,
      'srv'=>$s['ctas'],
      'srv_register'=>$s['reg'],
      'srv_adls'=>$s['adls'],
      'saldo_int'=> $saldo_int,
      'update_pending'=>false//$e->get_pcle('plan_update_pending')->value
    ];
    return $res;
  }

  //  **** END SET PAGO DE CUOTAS VERSION 2

  //  DEVUELVE CUOTAS ADELANTADAS DISPONIBLES PARA EL ELEMENT
  function get_ctas_adl($el){

    //*******  CONDICION REQUERIDA
    // CUOTAS FUTURAS A PAGAR
    $ctas_disp = $el->get_events(8,'a_pagar');


    if(count($ctas_disp['events']) > 0){
      // OBTENGO EL MONTO A PAGAR DE LA PROXIMA CUOTA
      $last_pay_ev = $el->get_last_payment();
      if(!empty($last_pay_ev)){
        $lpdate = $this->fixdate_ymd($last_pay_ev->get_pcle('fec_pago')->value);
        if($this->dif_today($lpdate)->m == 0){
          $lp_monto =  $last_pay_ev->get_pcle('monto_pagado')->value;
        }else{
          $lp_monto = (new Event($ctas_disp['events'][0]['id']))->get_pcle('monto_cta')->value;
        }
      }
      else{
        $lp_monto = (new Event($ctas_disp['events'][0]['id']))->get_pcle('monto_cta')->value;
      }
    }
    if(count($ctas_disp['events'])> 0 && !empty($lp_monto)){
      // OBTENGO UN ARRAY DE CUOTAS DISPONIBLES PARA PAGAR EN FORMA ADELANTADA
      $ev = new Event($ctas_disp['events'][0]['id']);
      $min_id = $ctas_disp['events'][0]['id'];
      $max_id = end($ctas_disp['events'])['id'];
      if($min_id && $max_id){
        $cda = $this->CMF->app_model->get_arr("SELECT id as events_id, date as fec_vto FROM events WHERE id >= {$min_id} AND id <= {$max_id} ");
        if(count($cda)>0){
          $cd_pcles = [];
          foreach($cda as $cd){
            $x = new Event($cd['events_id']);
            $cd_pcles[]=$x->get_pcle('nro_cta')->value;
          };

          return ['disp'=>$cda,'mt_cta'=>$lp_monto,'pcles'=>$cd_pcles];
        }
      }
    }
    return [];
  }

  // DIF DIAS PARA LOS INTERESES
  function dif_dias($date){
    $ddt = new DateTime($date);
    $today = new DateTime();
    $dif_date = $today->diff($ddt);
    return intval($dif_date->format('%a'));
  }


  function validate_dt_cta($date){
    $ddt = new DateTime($date);
    // USAR FAKE DATE 10 DE ABRIL PARA TESTEAR CUOTAS Y SERV
    // $fake_date = '2020-05-10';
    // $today = new DateTime($fake_date);
    $today = new DateTime();
    $dif_date = $today->diff($ddt);
    if($ddt->format('m') <= $today->format('m') && $ddt->format('Y') == $today->format('Y')){
      return true;
    }else{
      return false;
    }
  }
  // **********************************************************************************
  // ******* END DE FUNCIONES PARA SETEAR PAGO DE CUOTAS EN CLIENTE.PHP Y WEDB_CLI.PHP
  // **********************************************************************************

  function get_saldo_comprobantes($e_id){
    $ls = $this->Mdb->db->query("SELECT saldo FROM comprobantes WHERE elements_id = {$e_id} AND estado = 1 ORDER BY id DESC LIMIT 1");
    $saldo = ($ls->result_id->num_rows)?intval($ls->row()->saldo):0;
    return $saldo;
  }

  // **********************************************************************************
  // ******* GET NEW SALDO ARMA LA DATA PARA CALCULAR EL SALDO EN EL ARMADO DE CUOTAS
  // ***** esta para descartar si funciona bien el saldo de comprobantes
  // **********************************************************************************

  function get_new_saldo($elm_id){
    $res = [];
    //***** LOTE ****
    $elm = new Element($elm_id);
    $lote_name = (new Atom($elm->get_pcle('prod_id')->value))->name;

    if(strpos($lote_name, 'R_') > -1){return false;}

    //*** DEUDA TOTAL BRUTA
    $deuda_bruta  = intval($elm->get_deuda_total());

    //*** PAGOS OBTENIDOS DE IMPUTACIONES PRE 20/08/19 (CON NRO DE RECIBO)
    // $pagos_viejos = intval($elm->get_pagos_fk('monto_pagado'));
    // $intereses_pagos_viejos = intval($elm->get_pagos_fk('interes_mora'));
    //** PAGOS SERVICIOS VIEJOS
    // $pagos_servicios_viejos = $elm->get_pagos_fk_serv('monto_pagado');
    // $interses_servicios_viejos = $elm->get_pagos_fk_serv('interes_mora');

    //*** ASIENTOS DE CAJA
    $pagos_caja = $elm->get_pagos_caja();


    // CARGO EL LAST SALDO DESDE PCLE
    $pagos_a_cuenta_old = 0;
    $ls = $this->Mdb->db->query("SELECT value FROM elements_pcles WHERE elements_id = {$elm_id} AND label = 'saldo'");
    if($ls->result_id->num_rows){
      $pagos_a_cuenta_old = intval($ls->row()->value);
    }

    $cta_upc = intval($elm->get_cta_upc()['total']);
    // $ctas_imputadas = intval($elm->get_imputaciones_ctas());
    // $intereses_cobrados = intval($elm->get_imputaciones_intereses());
    $intereses_cobrados = intval($elm->get_imputaciones_ctas('interes_mora'));
    $ctas_imputadas = intval($elm->get_imputaciones_ctas('monto_pagado'));

    //***** SERVICIOS ****
    $srv_arr = $elm->get_servicios();
    $srv_dttl = 0; $srv_pttl = 0; $srv_cta_upc = 0; $srv_ctas_imputadas = 0; $srv_intereses_cobrados = 0;

    if(is_array($srv_arr)){
      $srv_intereses_cobrados = 0;
      $srv_ctas_imputadas = 0;
      $srv_cta_upc = 0;
      foreach ($srv_arr as $srv) {
        $s = new Element($srv['id']);
        $srv_cta_upc += intval($s->get_cta_upc()['total']);
        $srv_intereses_cobrados += intval($s->get_imputaciones_ctas('interes_mora'));
        $srv_ctas_imputadas += intval($s->get_imputaciones_ctas('monto_pagado'));
      }
    }


    // $pagos = intval($pagos_viejos) + intval($pagos_caja) + intval($pagos_a_cuenta_old);
    $pagos =  $pagos_a_cuenta_old + (intval($pagos_caja) - ($intereses_cobrados + $srv_intereses_cobrados)) ;
    $imputado = $ctas_imputadas + $srv_ctas_imputadas ;

    // *** PATCH DE SALDOS
    // *** RESTA EL MONTO EN PATCH PARA COMPENSAR EL MONTO ERRONEO QUE ESTOY SUMANDO DOS VECES
    // *** POR SER UNA IMPUTACION HECHA DESPUES DE 20 DE AGOSTO CON FECHA PREVIA, COSA QUE DUPLICA EN EL INGRESO DEL PAGO A CONTAB ASIENTOS
    // *** Y  monto_pagado EN EVENTS_PCLES.
    $p = $this->Mdb->db->query("SELECT * FROM patch_saldos WHERE elm_id = {$elm_id} ");
    if($p->result_id->num_rows){
      if($p->row()->activo){$pagos -= $p->row()->monto;}
    }

    $ts = ($pagos - $imputado > 0 )?$pagos - $imputado:0;

    $res = [
      'lote'=> $lote_name,
      'deuda_bruta'=>$deuda_bruta,
      // 'pagos_viejos'=>$pagos_viejos,
      // 'interses_viejos'=>$intereses_pagos_viejos,
      // 'srv_pagos_viejos'=>$pagos_servicios_viejos,
      // 'srv_intereses_viejos'=>$interses_servicios_viejos,
      'pagos_caja'=>$pagos_caja,
      'pagos_a_cuenta'=>$pagos_a_cuenta_old,
      'lt_imputados'=>$ctas_imputadas,
      'lt_intereses'=>$intereses_cobrados,
      'srv_imputado'=>$srv_ctas_imputadas,
      '$srv_intereses_cobrados'=>$srv_intereses_cobrados,
      'intereses_cobrados' => $intereses_cobrados + $srv_intereses_cobrados,
      'deuda_exigible_sin_intrs' => $cta_upc + $srv_cta_upc,
      'pagos'=> $pagos,
      'imputado' => $imputado,
      'r_saldo_2'=> $pagos - $imputado,
      'r_saldo'=> $ts
    ];
    return $res;

  }
  // END GET NEW SALDO
  // *************************************************************


      // back
      /*



        // **********************************************************************************
        // ******* 13/11/2019
        // ******* AUTOCOMLETE GENERICO
        // **********************************************************************************
        public function autocomplete_gen($t){

          if(preg_match('/(?<!\d)\d{1,4}(?!\d)/', $t)){
            $q = "SELECT CONCAT(a.name,' ' ,a1.name) as label , ep.elements_id as id FROM `atoms` a JOIN elements_pcles ep on ep.label = 'prod_id' and VALUE = a.id JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id AND ep2.label = 'cli_id' JOIN atoms a1 on a1.id = ep2.value WHERE a.atom_types_id = 2 AND a.name LIKE '%{$t}%'";
          }else{
            // TERM ES CHAR BUSCO EN CLIENTES
            $q = "SELECT CONCAT(a1.name,' ' ,a.name) as label , ep.elements_id as id FROM `atoms` a JOIN elements_pcles ep on ep.label = 'cli_id' and VALUE = a.id JOIN elements_pcles ep2 on ep2.elements_id = ep.elements_id AND ep2.label = 'prod_id' JOIN atoms a1 on a1.id = ep2.value WHERE a.atom_types_id = 1 AND a.name LIKE '%{$t}%' ";
            }

            $x = $this->Mdb->db->query($q);
            if($x->result_id->num_rows){
            $r = $x->row_array;
            }
            return $r;
        }






      function get_new_saldo($elm_id){
      $res = [];
      //***** LOTE ****
      $elm = new Element($elm_id);
      $lote_name = (new Atom($elm->get_pcle('prod_id')->value))->name;

      if(strpos($lote_name, 'R_') > -1){return false;}

      //*** DEUDA TOTAL BRUTA
      $deuda_bruta  = intval($elm->get_deuda_total());

      //*** PAGOS OBTENIDOS DE IMPUTACIONES PRE 20/08/19 (CON NRO DE RECIBO)
      $pagos_viejos = intval($elm->get_pagos_fk('monto_pagado'));
      $intereses_pagos_viejos = intval($elm->get_pagos_fk('interes_mora'));
      //** PAGOS SERVICIOS VIEJOS
      $pagos_servicios_viejos = $elm->get_pagos_fk_serv('monto_pagado');
      $interses_servicios_viejos = $elm->get_pagos_fk_serv('interes_mora');

      //*** ASIENTOS DE CAJA
      $pagos_caja = $elm->get_pagos_caja();


      // CARGO EL LAST SALDO DESDE PCLE
      $pagos_a_cuenta_old = 0;
      $ls = $this->Mdb->db->query("SELECT value FROM elements_pcles WHERE elements_id = {$elm_id} AND label = 'saldo'");
      if($ls->result_id->num_rows){
      $pagos_a_cuenta_old = intval($ls->row()->value);
      }

      $cta_upc = intval($elm->get_cta_upc()['total']);
      // $ctas_imputadas = intval($elm->get_imputaciones_ctas());
      // $intereses_cobrados = intval($elm->get_imputaciones_intereses());
      $intereses_cobrados = intval($elm->get_imputaciones_ctas('interes_mora'));
      $ctas_imputadas = intval($elm->get_imputaciones_ctas('monto_pagado'));

      //***** SERVICIOS ****
      $srv_arr = $elm->get_servicios();
      $srv_dttl = 0; $srv_pttl = 0; $srv_cta_upc = 0; $srv_ctas_imputadas = 0; $srv_intereses_cobrados = 0;

      if(is_array($srv_arr)){
      $srv_intereses_cobrados = 0;
      $srv_ctas_imputadas = 0;
      $srv_cta_upc = 0;
      foreach ($srv_arr as $srv) {
      $s = new Element($srv['id']);
      $srv_cta_upc += intval($s->get_cta_upc()['total']);
      $srv_intereses_cobrados += intval($s->get_imputaciones_ctas_2('interes_mora'));
      $srv_ctas_imputadas += intval($s->get_imputaciones_ctas_2('monto_pagado'));
      }
      }


      // $pagos = intval($pagos_viejos) + intval($pagos_caja) + intval($pagos_a_cuenta_old);
      $pagos =  $pagos_a_cuenta_old + (intval($pagos_caja) - ($intereses_cobrados + $srv_intereses_cobrados)) ;
      $imputado = $ctas_imputadas + $srv_ctas_imputadas ;

      // *** PATCH DE SALDOS
      // *** RESTA EL MONTO EN PATCH PARA COMPENSAR EL MONTO ERRONEO QUE ESTOY SUMANDO DOS VECES
      // *** POR SER UNA IMPUTACION HECHA DESPUES DE 20 DE AGOSTO CON FECHA PREVIA, COSA QUE DUPLICA EN EL INGRESO DEL PAGO A CONTAB ASIENTOS
      // *** Y  monto_pagado EN EVENTS_PCLES.
      $p = $this->Mdb->db->query("SELECT * FROM patch_saldos WHERE elm_id = {$elm_id} ");
      if($p->result_id->num_rows){
      if($p->row()->activo){$pagos -= $p->row()->monto;}
      }

      $ts = ($pagos - $imputado > 0 )?$pagos - $imputado:0;

      $res = [
      'lote'=> $lote_name,
      'deuda_bruta'=>$deuda_bruta,
      'pagos_viejos'=>$pagos_viejos,
      'interses_viejos'=>$intereses_pagos_viejos,
      'srv_pagos_viejos'=>$pagos_servicios_viejos,
      'srv_intereses_viejos'=>$interses_servicios_viejos,
      'pagos_caja'=>$pagos_caja,
      'pagos_a_cuenta'=>$pagos_a_cuenta_old,
      'lt_imputados'=>$ctas_imputadas,
      'lt_intereses'=>$intereses_cobrados,
      'srv_imputado'=>$srv_ctas_imputadas,
      '$srv_intereses_cobrados'=>$srv_intereses_cobrados,
      'intereses_cobrados' => $intereses_cobrados + $srv_intereses_cobrados,
      'deuda_exigible_sin_intrs' => $cta_upc + $srv_cta_upc,
      'pagos'=> $pagos,
      'imputado' => $imputado,
      'r_saldo_2'=> $pagos - $imputado,
      'r_saldo'=> $ts
      ];
      return $res;

      }


      */


      // *************************
      // ****  FALTA TERMINAR ****
      // *************************
      // actualiza el eventType por fecha de pago y
      function updateEvType($ev,$fp_xl=null,$fv_xl=null){
      if(!empty($ev) && !empty($fp_xl) && !empty($fv_xl)){
      $estado = 'a_pagar';

      }

      $fvenc=substr($ev->date, 0,8).'01';
      $estado = $ev->get_pcle('estado')->value;

      // DIAS DE DIFERENCIA ENTRE fecha de pago y fecha de vencimiento
      $fpago=$this->fixdate_ymd($fp);
      $fv = $this->fixdate_ymd($fv);


      $dt_pago = new DateTime($fpago);
      $dt_venc = new DateTime($fvenc);
      if($estado != 'a_pagar'){
      $dt_int = $dt_pago->diff($dt_venc);
      if($dt_int->invert == 0){
      if($dt_int->days < 28 || $dt_int->days == 0){
      // echo 'DIA_DE_VENCIMIENTO';
      $trm = "NORMAL";
      $ev_type_id = 4;
      $estado = 'pagado';
    }
    if($dt_int->days >= 28)  {
      // echo 'ADL';
      $trm = "ADL";
      $ev_type_id = 6;
      $trmd = intval($dt_int->days);
      $estado = 'pagado';
    }
  }else{
    if($dt_int->m < 1){
      // echo 'NORMAL';
      $trm = "NORMAL";
      $ev_type_id = 4;
      $trmd = intval($dt_int->days);
      $estado = 'pagado';
    }else{
      // echo 'FUERA_TERMINO';
      $trm = 'FUERA_TERMINO';
      $ev_type_id = 4;
      $trmd = intval($dt_int->days);
      $estado = 'p_ftrm';
    }
  }
  }else{
    $dt_int = $dt_pago->diff($dt_venc);
    if($dt_int->invert == 0){
      if($dt_int->days < 28 || $dt_int->days == 0){
        echo 'DIA_DE_VENCIMIENTO';
        $trm = "";
        $ev_type_id = 4;
        $trmd = 0;
      }
      if($dt_int->days >= 1)  {
        // echo 'ADL';
        $trm = "";
        $ev_type_id = 8;
        $trmd = 0;
      }
    }else{
      if($dt_int->m < 1){
        // echo 'NORMAL';
        $trm = "";
        $ev_type_id = 4;
        $trmd = 0;
      }else{
        // echo 'FUERA_TERMINO';
        $trm = '';
        $ev_type_id = 4;
        $trmd = 0;

      }
    }

  }


  return ['ev_type_id'=>$ev_type_id,'estado'=> $estado];
  }

  function get_lotes_financ_normal($barrio){
    return $this->CMF->app_model->get_arr("SELECT
    l.name as cod_lote,
    f.name as financ_name
    FROM `elements_pcles` ep1
    LEFT OUTER JOIN elements_pcles ep2 on ep2.elements_id = ep1.elements_id AND ep2.label = 'financ_id'
    LEFT OUTER JOIN elements_pcles ep3 on ep3.elements_id = ep1.elements_id AND ep3.label = 'prod_id'
    LEFT outer join atoms f on f.id = ep2.value
    LEFT outer join atoms l on l.id = ep3.value
    where ep1.label = 'cant_ctas_post_posesion' AND ep1.value > 0 ");
  }

  function get_lotes_activos(){
    return $this->CMF->app_model->get_arr("
    SELECT
    a.id as id,
    a.name as name
    FROM `atoms` a
    join atoms_pcles p on p.atom_id = a.id AND p.label = 'estado'
    WHERE a.atom_types_id = 2 AND p.value = 'ACTIVO' OR p.value = 'CANJE' OR p.value = 'CEDIDO'");
  }

  function get_contratos_activos(){
    $lotes_act = $this->get_lotes_activos();
    $cntrs_act = [];
    foreach ($lotes_act as $la) {
      $elm = $this->CMF->app_model->get_obj("SELECT id FROM elements WHERE owner_id = {$la['id']}");
      $cntr = new Element($elm->id);
      // $lote = (new Atom($cntr->get_pcle('prod_id')->value))->name;
      // $cli = (new Atom($cntr->get_pcle('cli_id')->value))->name;
      // $fnn = (new Atom($cntr->get_pcle('financ_id')->value))->name;

      if(!empty($cntr->id)){
        $ftr_evs = $cntr->get_events('8','a_pagar');
        if(!empty($ftr_evs)){
          $cntrs_act[]=$cntr;
        }
      }

      // echo '<br >--> Atm_id: '.$la['id'].' name: '.$la['name'].' -->Nom Lote:'.$lote.'  -->Cli: '.$cli.' -->Financ: '.$fnn. '<--';

    }
    return $cntrs_act;
  }


  // ********************************************************************************
  // actualiza el estado de eventos segun la fecha de vencimiento y la fecha actual
  // ********************************************************************************
  function update_estado_de_eventos_a_pagar($e){
    $dt_now = new DateTime(date('Y-m-d'));
    $f = $e->get_events(8,'a_pagar');
    foreach ($f['events'] as $xv) {
      $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
      $dt_diff = $dt_xv->diff($dt_now);
      if($dt_diff->invert == 0){
        if($dt_diff->days >= 25){
          $this->CMF->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
        }
      }
    }
  }


  //  RECIBE FECHA DE PAGO , FECHA DE VENCIMIENTO Y ESTADO DE PAGO PARA DEFINIR TYPE ID
  function get_event_type_by_fecha_y_estado_de_pago($fp,$fv,$estado){
    if($fp == "-"){
      $h = new DateTime(date('Y-m-d'));
      $fp = $h->format('d/m/Y');
    }

    $new_estado = $this->get_estado_pago($fv,$fp);

    if($estado != 'a_pagar'){

      if($new_estado == 'pagado'){
        $ev_type_id = 4;
        $estado = 'pagado';

      }elseif($new_estado == 'adelantada'){
        $ev_type_id = 6;
        $estado = 'pagado';

      }elseif($new_estado == 'p_ftrm'){
        $ev_type_id = 4;
        $estado = 'p_ftrm';
      }
    }else{
      //  ESTADO ACTUAL A_PAGAR
      $today = new DateTime(date('Y-m-d'));
      $fv = new DateTime($this->fixdate_ymd($fv));
      if($today->diff($fv)->invert === 0 && $today->diff($fv)->days > 0 )  {
        $ev_type_id = 8;
      }else{
        $ev_type_id = 4;
      }
    }
    return ['ev_type_id'=>$ev_type_id,'estado'=> $estado];
  }


  // RECIBE FECHA DE VENCIMIENTO Y FECHA DE PAGO Y RETORNA TERMINOS DE PAGO
  function get_estado_pago($fv,$fp){
    $fv = $this->fixdate_ymd($fv);
    $fp = $this->fixdate_ymd($fp);
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

  //  INICIA HISTORIAL PARA LOS LOTES QUE ESTABAN SIN CURRENT STATE
  function hist_init($lt_obj){
    // TRAE LA VERSION ANTIGUA DE ESTADO DEL LOTE
    if($lt_obj->get_pcle('estado')->value == 'ACTIVO'){
      $e = new Element(0,'CONTRATO',$lt_obj->id);
      $e_prev_st = $e->get_pcle('curr_state')->value;
    }
    //  INIT DEL HISTORIAL
    $h=new Historial(0,'HISTORIAL',$lt_obj->id);
    $h->start($e->id);
    // GUARDA ID DE HISTORIAL EN LOS PCLES DE LOTE
    $lt_obj->set_pcle(0,'hist_id',$h->id);

    if($e_prev_st === 'normal'){
      // params (event_type_id,user_id,text_accion,detalle_text_or_id,state_code)
      // 14 = REVISADO
      $h->update(484,'hist_update','','REVISADO');
    }

    if($e_prev_st == 'a_revisar'){
      // params (event_type_id,user_id,text_accion,detalle_text_or_id,state_code)
      // 14 = REVISADO
      $rev = $this->CMF->app_model->get_obj("SELECT * FROM revision WHERE element_id = {$e->id} AND solucionado = 0 limit 1");

      $h->update(484,'revision_id',$rev->id,'EN_REVISION');
    }
    return $h;
  }

  //RECIBE EL ARRAY DE RESCISION Y LO FORMATEA PARA PANTALLA
  function get_rscn_data($v){
    if(empty($v)){
      return -1;
    }else{
      // echo '<pre>';
      // var_dump($ro);
      return [
        $v->get_pcle('nombre')->title => $v->get_pcle('nombre')->value,
        $v->get_pcle('fecha')->title => $v->get_pcle('fecha')->value,
        $v->get_pcle('mto_reintegro')->title =>$v->get_pcle('mto_reintegro')->value,
        'estado del Reintegro'=>'falta definir',
        $v->get_pcle('rscn_tipo_id')->title => $v->get_pcle('rscn_tipo_id')->value,
        $v->get_pcle('reintegro_nro_op')->title => $v->get_pcle('reintegro_nro_op')->value,
        'Acciones'=>$this->get_accion_icon('open-in-new','detalle_rscn',$v->id)
      ];

      //.$this->get_accion_icon('print','reprint_recibo',$v['recibo_nro']
      // exit;
      // return $v;
      //
      //
      //
      // 'Monto $'=>$v['monto_operacion'],
      // 'Recibo Nro.'=>$v['recibo_nro'],

      // 'Acciones'=>$this->get_accion_icon('open','detalle_movs',$v['recibo_nro']).$this->get_accion_icon('print','reprint_recibo',$v['recibo_nro'])

      // },$r);
    }
  }

  function get_accion_icon($icon,$method,$id,$sending=1){
    return "<button type=\"button\" class=\"btn-normal\" onClick=front_call({method:'".$method."',sending:".$sending.",data:{id:'".$id."'}})><i class='material-icons'>".$icon."</i></button>";
  }


  function mk_button_action($icon,$method,$action,$data,$sending=1){
    return "<button type=\'button\' class=\'btn-normal\' onClick=front_call({
      method:\'".$method."\',
      sending: ".$sending.",
      action:\'".$action."\',
      data:".json_encode($data)."
    })><i class='material-icons'>".$icon."</i></button>";
  }


  function get_adl_able_plan_id(){
    $t = $this->CMF->app_model->get_arr("SELECT a.id FROM `atoms` a join atoms_pcles p on p.atom_id = a.id and p.label = 'name' join atoms_pcles pin on pin.atom_id = a.id and pin.label = 'frecuencia_indac' join atoms_pcles pt on pt.atom_id = a.id and pt.label = 'financ_type' WHERE a.atom_types_id = 7 AND pin.value > 0 AND pt.value = 1");
    $r = [];
    foreach ($t as $v) {
      $r[]=$v['id'];
    }
    return $r;
  }

  function err_exit($msg,$err_data){
    $r =[
      'tit'=>'Error',
      'msg'=>$msg . $err_data,
      'type'=>'danger',
      'container'=>'#msgs'
    ];
    echo json_encode(array(
      'callback'=>'myAlert',
      'param'=>$r
    ));
    return false;
  }

  // ******* OBTIENE UNA ESTRUCTURA DE ATOM DESDE LA TABLA CUANDO SE VA A CREAR UN NUEVO ATOM
  function get_struct($name){
    $r = $this->CMF->app_model->get_obj("SELECT id FROM atom_types WHERE name = '{$name}'  ");
    if(!empty($r)){
      $str = $this->CMF->app_model->get_arr("SELECT * FROM atoms_struct WHERE atom_types_id = {$r->id} ORDER BY vis_ord_num ASC");
      // $v = [1=>'text',2=>'number',3=>'select',4=>'date_picker',5=>'timestamp',6=>'checkbox'];
      foreach ($str as $key =>$val) {
        $vn = $this->CMF->app_model->get_obj("SELECT * FROM pcle_types WHERE id = {$val['vis_elem_type']}");
        $str[$key]['vis_elem_type'] = (!empty($vn))?$vn->type:'text';
      }
      return $str;
    }
    return false;
  }

  function get_pcle_vis_type($pcle){
    $id = (!empty($pcle->vis_elem_type))?$pcle->vis_elem_type:1;
    $q = "SELECT * FROM pcle_types WHERE id = {$id}";
    $r = $this->CMF->app_model->get_obj($q);
    if(!empty($r)){
      return $r->type;
    }else{
      return 'text';
    }
  }


  // COMPLETA LOS CAMPOS EN CREATE UPDATE DE ATOMS
  function complete_crude_pcles($atm_id,$crp,$defp){
    foreach ($defp as $dp) {
      if($dp['label'] == 'codigo'){break;}
      // $tmpres=$dp;
      $cr_lbl_found = false;
      foreach ($crp as $crp_val) {
        if($crp_val['label'] == $dp['label']){
          $cr_lbl_found = true;
          break;
        }
      }
      if($cr_lbl_found){
        $res[]=$crp_val;
      }else{
        $pcle_id = $this->CMF->app_model->insert('pcles', array(
          'atom_id'=>$atm_id,
          'label'=>$dp['label'],
          'value'=>'',
          'title'=>$dp['title'],
          'vis_elem_type'=>$dp['vis_elem_type']
        ));
        $res[] = $this->CMF->app_model->get_all_from('pcles',"WHERE id = ".$pcle_id)[0];
      }
    }
    return $res;
  }

  public function get_arr($q='')
  {
    return $this->Mdb->db->query($q)->result_array();
  }

  public function get_obj($q='')
  {
    return $this->Mdb->db->query($q)->row();
  }

  public function resp($cbk,$d){
    echo json_encode(
      array(
        'callback'=>$cbk,
        'param'=>$d
      )
    );
  }

  function hist_update($l_id,$type){

    // $h = new Historial(0,'HISTORIAL',$l_id);
    // $id=0;
    // $type_id = $type; // viene del router con la seleccion del usuario

    // $state = // traer de la base de datos $this->CMF->app_model-> //'en revision' // lo define type_id
    // $action = 'resicion de contrato' // viene de imput field en pantalla
    // $detalle = 'observac detalles' // viene de imput field en pantalla
    // $date = new DateTime();
    // $elem_id=$h->id;
    // $ord_num = intval($h->get_event_last()->ord_num)+1;
    // $ev = new Event($id,$type_id,$date->format('Y-m-d'),$elem_id,$ord_num);
    // $ev->set_pcle(0,'user_id',$usr_id);
    // $ev->set_pcle(0,'accion',$action);
    // $ev->set_pcle(0,'detalle',$detalle);
    // $ev->set_pcle(0,'state',$state);

  }


  // function get_recnum($i){
  //   // OBTENGO UN NRO DE RECIBO
  //   $rn = $this->get_obj("SELECT nro_comprobante FROM comprobantes WHERE tipo_comprobante = 'RECIBO' ORDER BY id DESC LIMIT 1");
  //   $rec = intval($rn->nro_comprobante)+$i;
  //   if($this->check_recnum($rec) > 0){
  //     $r = $rec;
  //   }else{
  //     if($i > 50){
  //       return false;
  //     }
  //     $r = $this->get_recnum($i+1);
  //   }
  //   return $r;
  // }

  // exit('No se puede crear el numero de recibo, supera max_index.');
  // OBTENGO UN NRO DE RECIBO
  //*** NUEVO GET_RECNUM INSERTA EL RECORD PARA QUE NO PUEDAN SUPLICAR EL RECNUM
  function get_recnum(){
    $rn = $this->get_obj("SELECT nro_comprobante FROM comprobantes WHERE tipo_comprobante = 'RECIBO' ORDER BY id DESC LIMIT 1");
    $rec = intval($rn->nro_comprobante)+1;
    if($this->check_recnum($rec) > 0){
      $this->CMF->app_model->insert('comprobantes',['nro_comprobante'=>$rec]);
      return $rec;
    }else{
      return false;
    }
  }



  function check_recnum($rnum){
    $t = $this->get_obj("SELECT nro_comprobante from comprobantes where nro_comprobante = '{$rnum}'");
    if(empty($t)){
      return $rnum;
    }else{
      return 0;
    }
  }

  function  mk_asiento_caja($f_arr,$ccd){
    // $f_arr es los fields
    // $ccd centro de costos distrib para atribuir el in / out al centro correspondiente.
    $op_num = $this->CMF->app_model->get_num_operac_caja();
    if(!$op_num){return false;}
    $f_arr['operacion_nro'] = $op_num;
    $op_id = $this->CMF->app_model->insert('contab_asientos',$f_arr);
    //  TENGO INSERT ID PARA LINKEAR AL CENTRO DE COSTOS
    // INSERTO DISTIBUCION DEL ASIENTO POR BARRIO CENTRO DE COSTOS
    // $CCD ES UN ARRAY CON ID DEL BARRIO Y PORCENTAJE, Y $T ES EL ID DEL ASIENTO
    if(!$op_id){return false;}
    $this->cc_distrib_proces($ccd,$op_id);
    return $op_num;
  }


  //*** RECIBE UN ARRAY DE EVENTS_ID Y LOCN CONVIERTE EN UN ARRAY PARA HACER UNA TABLE EN JS
  function  mk_detalle_recibo($ev_id_arr){
    $r=[];
    foreach ($ev_id_arr as $k => $ev_id) {
      $ev = new Event($ev_id);
      // *** ES DEBITO DE  CUOTAS
      if(intval($ev->type_id) >= 1 && intval($ev->type_id) <= 6){
        $its = $ev->get_pcle('interes_mora');
        $r[] = [
          'Nombre'=>$this->get_item_pagado($ev->elements_id),
          'Numero de Cuota'=>$ev->get_pcle('nro_cta')->value,
          'Vencimiento'=>$ev->get_pcle('fecha_vto')->value,
          'Monto Pagado'=>$ev->get_pcle('monto_pagado')->value,
          'intereses'=>(!empty($its))?$its->value:0,
        ];

      }
      //  *** ES PAGO INGRESADO
      // if(intval($ev->type_id) === 3 ){
      //   var_dump($ev->get_props());exit();
      //   $r[] = [
      //     'Detalle '=>'PAGO INGRESADO ',
      //     'Numero de operacion '=>$ev->get_pcle('op_caja_nro')->value,
      //     'Monto '=>$ev->get_pcle('monto')->value,
      //   ];
      // }
    }
    return $r;
  }

  function get_item_pagado($elm_id){
    $e = new Element($elm_id);
    switch ($e->type) {
      case 'CONTRATO':
      $a_id = $e->get_pcle('prod_id')->value;
      break;
      case 'SERVICIO':
      $a_id = $e->get_pcle('atom_id')->value;
      break;
      default:
      $a_id = $e->get_pcle('prod_id')->value;
      break;

    }
    $atm = new Atom($a_id);
    return $atm->name;
  }


  function cc_distrib_proces($arr_brbox,$asi_id){
    foreach ($arr_brbox as $v) {
      $data = ['barrio_id'=>$v['barrio_id'],'percent'=>$v['percent'],'asiento_id'=>$asi_id];
      $ccd_id = $this->CMF->app_model->insert('contab_cc_distrib',$data);
    }
  }

  // MAKE IMPUTACIONES clientes DROPDOWN
  function get_imputaciones_dpdown_data($type='',$cat=''){
    $t = ($cat !='' && $type != '')?" WHERE tipo LIKE '{$type}' AND categoria LIKE '{$cat}'":"";
    $q = "SELECT * FROM `contab_cuenta_de_imputacion` {$t}   ORDER BY nombre ASC";
    $d = $this->CMF->app_model->get_arr($q);
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre']];
    }
    return $r;
  }

  function get_imputaciones_all_dpdown_data(){
    $q = "SELECT * FROM `contab_cuenta_de_imputacion` ORDER BY nombre ASC";
    $d = $this->CMF->app_model->get_arr($q);
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre']];
    }
    return $r;
  }


  // MAKE IMPUTACIONES clientes DROPDOWN
  function get_imputaciones_cli_dpdown_data(){
    $d = $this->CMF->app_model->get_arr("SELECT * FROM `contab_cuenta_de_imputacion` WHERE tipo LIKE 'AMBAS' OR tipo LIKE 'INGRESO%'  ORDER BY nombre ASC ");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre']];
    }
    return $r;
  }

  // MAKE IMPUTACIONES PROVEEDOR DROPDOWN
  function get_imputaciones_prov_dpdown_data(){
    $d = $this->CMF->app_model->get_arr("SELECT * FROM `contab_cuenta_de_imputacion` WHERE tipo LIKE 'AMBAS' OR tipo LIKE 'EGRESO%'  ORDER BY nombre ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre']];
    }
    return $r;
  }

  // MAKE CUENTAS DROPDOWN
  function get_cuentas_dpdown_data($user_prms=0){
    $d = $this->CMF->app_model->get_arr('SELECT * FROM contab_cuentas ORDER BY id,tipo ASC');
    //***  ID DE contab_cuentas  QUE PUEDEN VER USUARIOS CON PERMISOS_ID 2
    $cprms2 = [1,2,4,5,7,8,9,15,19,20,21,22,23,24,25,29];
    $r=[];
    foreach ($d as $v) {
      if($user_prms > 1){
        if(in_array($v['id'], $cprms2)){
          $r[]=['id'=>$v['id'],'lbl'=>$v['tipo']. ' - '.$v['nombre']];
        }
      }else{
        $r[]=['id'=>$v['id'],'lbl'=>$v['tipo']. ' - '.$v['nombre']];
      }
    }
    return $r;
  }

  function get_centros_de_costos_dpdown_data(){
    $d = $this->CMF->app_model->get_arr('SELECT * FROM atoms WHERE atom_types_id = 4 ORDER BY id ASC');
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }

  // MAKE PEOVEEDORES DROPDOWN
  function get_dpdown_financ_type(){
    $d = $this->CMF->app_model->get_arr('SELECT * FROM atoms WHERE atom_types_id = 9 ORDER BY name ASC');
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }



  function get_proveedores_dpdown_data(){
    $d = $this->CMF->app_model->get_arr('SELECT * FROM atoms WHERE atom_types_id = 6 ORDER BY name ASC');
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }

  // MAKE CLIENTES DROPDOWN
  function get_clientes_dpdown_data(){
    $d = $this->CMF->app_model->get_arr("SELECT id,name FROM atoms WHERE atom_types_id = 1 ORDER BY name ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }


  function get_asigando_a_dpdown_data(){
    // $d = $this->CMF->app_model->get_arr("SELECT id,nombre_usuario FROM usuarios ORDER BY id ASC");
    $d = ['Lorena','Alejandro','Sandra','Javier'];
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v,'lbl'=>$v];
    }
    return $r;
  }



  function get_usuarios_dpdwn_data(){
    $d = $this->CMF->app_model->get_arr("SELECT id,nombre_usuario FROM usuarios ORDER BY id ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre_usuario']];
    }
    return $r;
  }



  function get_servicios_dpdwn_data(){
    $d = $this->CMF->app_model->get_arr("SELECT id,nombre FROM contab_cuenta_de_imputacion WHERE tipo LIKE 'INGRESOS' ORDER BY nombre ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['nombre']];
    }
    return $r;
  }

  function get_dpdown_tipo_contab_cuentas(){
    $d = $this->CMF->app_model->get_arr('SELECT DISTINCT(tipo) FROM contab_cuentas');
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['tipo'],'lbl'=>$v['tipo']];
    }
    return $r;
  }

  function get_dpdown_categoria_contab_cuenta_imputacion(){
    $d = $this->CMF->app_model->get_arr('SELECT DISTINCT(categoria) FROM contab_cuenta_de_imputacion');
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['categoria'],'lbl'=>$v['categoria']];
    }
    return $r;
  }

  function get_dpdown_data_barrios(){
    // $d = $this ->CMF ->app_model -> get_dpdown_data($tbl,$fields,$modif);
    $atom_type = $this->CMF->app_model->get_obj_from('atom_types',"WHERE name LIKE 'BARRIO' ");
    $d = $this->CMF->app_model->get_arr("SELECT * FROM atoms WHERE atom_types_id = {$atom_type->id} AND name != 'UNICO' ORDER BY name ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }


  // ***** obtiene la data para el dropdown de la tabla asignada
  // $name busca en atomtypes_id
  // obtiene todos los atom_id de name
  function get_dpdown_data($name){
    // $d = $this ->CMF ->app_model -> get_dpdown_data($tbl,$fields,$modif);
    $atom_type = $this->CMF->app_model->get_obj_from('atom_types',"WHERE name LIKE '".$name."' ");
    $d = $this->CMF->app_model->get_arr("SELECT * FROM atoms WHERE atom_types_id = {$atom_type->id} ORDER BY name ASC");
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }

  function get_dpdown_data_financ($t){
    $atom_type = $this->CMF->app_model->get_obj_from('atom_types',"WHERE name LIKE 'FINANCIACION' ");
    $d = $this->CMF->app_model->get_arr("SELECT a.id as id, a.name as lbl,p.label, p.value FROM atoms a join atoms_pcles p on p.atom_id = a.id AND p.label = 'financ_type' and p.value = {$t} WHERE a.atom_types_id = {$atom_type->id} ORDER BY a.name ASC");
    return $d;

  }

  // function get_dpdown_data_financ_ciclo1($t){
  //   $atom_type = $this->CMF->app_model->get_obj_from('atom_types',"WHERE name LIKE 'FINANCIACION' ");
  //   $d = $this->CMF->app_model->get_arr("SELECT a.id as id, a.name as lbl,p.label, p.value FROM atoms a join atoms_pcles p on p.atom_id = a.id AND p.label = 'financ_type' and p.value = {$t} WHERE a.atom_types_id = {$atom_type->id} ORDER BY a.name ASC");
  //   return $d;

  // }


  function get_dpdown_data_rev_fplan(){
    $atom_type = $this->CMF->app_model->get_obj_from('atom_types',"WHERE name LIKE 'FINANCIACION' ");
    $d = $this->CMF->app_model->get_arr("SELECT a.id as id, a.name as lbl FROM atoms a join atoms_pcles p on p.atom_id = a.id AND label = 'frecuencia_revision' AND value = 24 WHERE a.atom_types_id = 7 ORDER BY a.name ASC");
    return $d;
  }

  function get_dpdown_lt_disp(){

    $q= "SELECT a.id,a.name FROM `atoms` a
    join atoms_pcles p on p.atom_id = a.id AND p.label = 'estado' AND p.value = 'DISPONIBLE'
    WHERE a.atom_types_id = 2 AND a.name NOT LIKE 'R_%' ORDER BY name ASC";
    $d = $this->CMF->app_model->get_arr($q);
    $r=[];
    foreach ($d as $v) {
      $r[]=['id'=>$v['id'],'lbl'=>$v['name']];
    }
    return $r;
  }

  //** QUERY DNI CLIENTE
  // "SELECT cap.value as apellido, cnm.value as nombre, c.value as dni,c.atom_id as user_id, e.elements_id,l.name as lote_name FROM atoms_pcles c
  //     LEFT OUTER join atoms_pcles cap ON cap.label = 'apellido'AND cap.atom_id = c.atom_id
  //     LEFT OUTER join atoms_pcles cnm ON cnm.label = 'nombre' AND cnm.atom_id = c.atom_id
  //     LEFT OUTER JOIN elements_pcles e on e.label = 'cli_id' AND e.value = c.atom_id
  //     LEFT OUTER JOIN elements_pcles et on et.label LIKE '%titular%'AND et.value = c.atom_id
  //     LEFT OUTER JOIN elements_pcles el on el.label = 'prod_id' AND el.elements_id = e.elements_id
  //     LEFT OUTER JOIN atoms l on l.id = el.value
  //     WHERE c.label = 'dni' AND c.value = {$user_dni} AND e.elements_id != 'NULL' LIMIT 1");


  // COMPARA HOY CON LA FECHA EN PARAM Y DEVUELVE OBJETO DATE
  function dif_today($date){
    $ddt = new DateTime(trim($date));
    $today = new DateTime();
    $dif_date = $today->diff($ddt);
    return $dif_date;
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

  function get_pcle_value_by_label($arr,$lbl){
    foreach ($arr as $i) {
      if($i['label'] == $lbl)
      return $i['value'];
    }
    return false;
  }

  function get_pcle_by_label($arr,$lbl){
    foreach ($arr as $i) {
      if($i['label'] == $lbl)
      return $i;
    }
  }

  function get_code_lote($cln){
    if(substr($cln, 0,1) == 'C'){
      $r = substr($cln, 1,strpos($cln, ' ',2));
    }else{
      $r = substr($cln, 0,strpos($cln, ' '));
    }
    return rtrim(ltrim($r));
  }

  function sanitize_filename($string) {
    //Lower case everything
    // $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    // $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
  }


}
