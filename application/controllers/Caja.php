<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Caja extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this -> load -> model('app_model');
    $this->Mdb =& get_instance();
    $this->Mdb->load->database();

    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->helper('download');
    $this->load->library('cmn_functs');

    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    // LIBRERIA PARA EXPORTAR EXCEL
    include (APPPATH . 'controllers/Excel_features.php');

    // JP CLASES
      include (APPPATH . 'JP_classes/Atom.php');
      include (APPPATH . 'JP_classes/Element.php');
      include (APPPATH . 'JP_classes/Event.php');
      include (APPPATH . 'JP_classes/Historial.php');

    $this->Atom_types_id = 17;
    //****  USER PRIVILEDGES
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)){
      $this->usr_obj = $this->app_model->get_obj("SELECT * FROM usuarios WHERE id = {$user['user_id']} ");
    } else {
      redirect('login', 'refresh');
    }
  }


  public function index() {
    // ****** DATA PARA CUSTOMIZAR LA CLASE
    $cls_name = 'caja';
    // ****** RUTA DE ACCESO DEL CONTROLLER
    $route = 'caja/';
    // ****** ******************

    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
        // ****** NAVBAR DATA ********
        $userActs = $this -> app_model -> get_activities($user['user_id']);
        $acts = explode(',',$userActs['elements_id']);
        // ****** END NAVBAR DATA ********

        // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********

        // OBTIENE DATA PARA LOS DROPDOWNS
      // $usr_obj->permisos_usuario
      $selects = [
        'cuentas'=>$this->cmn_functs->get_cuentas_dpdown_data($this->usr_obj->permisos_usuario),
        'cuentas_imputacion'=>$this->cmn_functs->get_imputaciones_all_dpdown_data(),
        'centro_costos'=>$this->cmn_functs->get_centros_de_costos_dpdown_data(),
        'proveedor'=>$this->cmn_functs->get_proveedores_dpdown_data(),
        'tipo_asiento'=>[['id'=>'INGRESOS','lbl'=>'INGRESOS'],['id'=>'EGRESOS','lbl'=>'EGRESOS']]
      ];
        // PREPARO LOS DATOS DEL VIEW
      $var=array(
          'locked'=>($user['user_id'] == 484)?false:false,
          'route'=>$route,
          'user_id'=>$user['user_id'],
          'selects'=>($this->usr_obj->permisos_usuario <= 4)?$selects:[],
          'permisos'=>$this->usr_obj->permisos_usuario,
          'screen'=>$this->get_screen($this->usr_obj),
          'screen_title'=> 'Operaciones de Caja'
          );
        // ****** LOAD VIEW ******
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['user_id'])['usr_usuario']));
        $this -> load -> view('screen_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }
    // ****** END INDEX  ******


    // *** CREA EL SCREEN EN BASE A LOS PERMISOS DEL USUARIO
  function get_screen($u){
    $btns = [
      ['call'=>['method'=>'registro_operacion','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Registrar operación'],
      ['call'=>['method'=>'pase_entre_cajas','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Transferencia entre cajas'],
      ['call'=>['method'=>'arqueo_cajaybancos','sending'=>false],'tag'=>'Planilla Cajas y Bancos'],
      ['call'=>['method'=>'movs_de_cajas','sending'=>false],'tag'=>'Reporte Movimientos de Caja'],
      ['call'=>['method'=>'pagos_online','sending'=>true],'tag'=>'Pagos Online'],
    ];
    if($u->permisos_usuario < 3){
      return $btns;
    }
    else{
      $r = [];
      if ($u->permisos_usuario == 4){
        $r[]=$btns[3];
      }
    }
    return $r;
  }

    //**** DEVUELVE UN LISTADO DE CADA CAJA PEDIDA
  function planilla_caja_old(){
    $p = $this->input->post();
    $c = $p['data']['caja'];
    // caja es un array de cuentas o cajas
    //  si hay mas de un item
    // loop con el result de cada caja
    $cajas = [];
    foreach ($c as $key => $c_id) {
      // $cajas[]=$c_id;
      $l = $this->get_listado_caja($c_id,$p['data']['fec_desde'],$p['data']['fec_hasta']);
      $cajas[] = ['caja_id'=>$c_id,'list'=>$l];
    }

    $r = [
      'method'=>'arqueo_cajaybancos',
      'action'=>'response',
      'data'=>$cajas
    ];
    $this->cmn_functs->resp('front_call',$r);
        // $this->cmn_functs->resp('check',$r);
  }

  //  NUEVA VERSION DE
  function planilla_caja(){
    $p = $this->input->post();
    $c = $p['data']['caja'];
    // caja es un array de cuentas o cajas
    //  si hay mas de un item
    // loop con el result de cada caja
    $cajas = [];
    foreach ($c as $key => $c_id) {
      // $cajas[]=$c_id;
      $l = $this->get_listado_caja_2($c_id,$p['data']['fec_desde'],$p['data']['fec_hasta']);
      $cajas[] = ['caja_id'=>$c_id,'list'=>$l];
    }

    $r = [
      'method'=>'arqueo_cajaybancos',
      'action'=>'response',
      'data'=>$cajas
    ];
    $this->cmn_functs->resp('front_call',$r);
        // $this->cmn_functs->resp('check',$r);
  }


    //**** LISTA CAJA POR ID
  function get_listado_caja($caja_id,$rfec_in,$rfec_out){
    $cnom_qry = $this->app_model->get_obj("SELECT * FROM contab_cuentas WHERE id = ".$caja_id);
    $cnom = (!empty($cnom_qry))?$cnom_qry->nombre:'';
    $fd = $this->cmn_functs->fixdate_ymd($rfec_in)." 00:00:00";
    $fh = $this->cmn_functs->fixdate_ymd($rfec_out)." 23:59:59";
    $li = $this->app_model->get_plcaja($caja_id,'INGRESOS',$fd,$fh);
    $le = $this->app_model->get_plcaja($caja_id,'EGRESOS',$fd,$fh);
    $saldo = $this->app_model->get_saldo_previo($caja_id,$fd);

    //****** CAMBIO EN CAJAS VINCULADAS *******
    //****** BUSCO EN CONTAB CUENTAS SI LA CAJA ACTUAL ESTA EN CUNETA VINCULADA
    // SI ENCUENTRA LA CUENTA ACTUAL , ESTA SE INCORPORA AL LISTADO

    if($cnom_qry->cuenta_vinculada != ''){
      $ctav = $this->app_model->get_obj("SELECT id FROM contab_cuentas WHERE nombre LIKE '{$cnom_qry->cuenta_vinculada}' ");
      if(!empty($ctav)){
        $ctav_li = $this->app_model->get_plcaja($ctav->id,'INGRESOS',$fd,$fh);
        $ctav_le = $this->app_model->get_plcaja($ctav->id,'EGRESOS',$fd,$fh);
        $ctav_saldo = $this->app_model->get_saldo_previo($ctav->id,$fd);

      }
    }

    if(!empty($ctav) && !empty($cnom_qry)){

      return [
          'ingresos'=>$li,
          'egresos'=>$le,
          'caja_nom'=>$cnom,
          'fec_desde'=>$rfec_in,
          'fec_hasta'=>$rfec_out,
          'saldo'=>$saldo,
          'ctav_nom'=>$cnom_qry->cuenta_vinculada,
          'ctav_li'=>$ctav_li,
          'ctav_le'=>$ctav_le,
          'ctav_saldo'=>$ctav_saldo
      ];
    }
    if(!empty($cnom_qry)){
      return [
          'ingresos'=>$li,
          'egresos'=>$le,
          'caja_nom'=>$cnom,
          'fec_desde'=>$rfec_in,
          'fec_hasta'=>$rfec_out,
          'saldo'=>$saldo
      ];
    }

    return false;
  }



  //  nuevo listado de caja
    //**** LISTA CAJA POR ID
  function get_listado_caja_2($caja_id,$rfec_in,$rfec_out){
    $cnom_qry = $this->app_model->get_obj("SELECT * FROM contab_cuentas WHERE id = ".$caja_id);
    $cnom = (!empty($cnom_qry))?$cnom_qry->nombre:'';
    $fd = $this->cmn_functs->fixdate_ymd($rfec_in)." 00:00:00";
    $fh = $this->cmn_functs->fixdate_ymd($rfec_out)." 23:59:59";
    $li = $this->app_model->get_plcaja($caja_id,'INGRESOS',$fd,$fh);
    $le = $this->app_model->get_plcaja($caja_id,'EGRESOS',$fd,$fh);
    $saldo = $this->app_model->get_saldo_previo($caja_id,$fd);

    //****** CAMBIO EN CAJAS VINCULADAS *******
    //****** BUSCO EN CONTAB CUENTAS SI LA CAJA ACTUAL ESTA EN CUNETA VINCULADA
    // SI ENCUENTRA LA CUENTA ACTUAL , ESTA SE INCORPORA AL LISTADO
    $ctas_vinculadas = [];
    $c = json_decode( $cnom_qry->agrupar_cnta_id, $assoc_array = false );
    if(!empty($c) && count($c) > 0){
      foreach ($c as $key => $v) {
        $ctav_li = $this->app_model->get_plcaja($v,'INGRESOS',$fd,$fh);
        $ctav_le = $this->app_model->get_plcaja($v,'EGRESOS',$fd,$fh);
        $ctav_saldo = $this->app_model->get_saldo_previo($v,$fd);
        $caja = $this->app_model->get_obj("SELECT nombre FROM contab_cuentas WHERE id = $v");
        if(is_object($caja)){
          $ctas_vinculadas[] = ['nombre'=>$caja->nombre,'ingresos'=>$ctav_li,'egresos'=>$ctav_le,'saldo'=>$ctav_saldo];
        }
      }
    }



    if(count($ctas_vinculadas)>0){
      return [
          'ingresos'=>$li,
          'egresos'=>$le,
          'caja_nom'=>$cnom,
          'fec_desde'=>$rfec_in,
          'fec_hasta'=>$rfec_out,
          'saldo'=>$saldo,
          'cuentas_vinculadas'=>$ctas_vinculadas
      ];
    }else{
      return [
          'ingresos'=>$li,
          'egresos'=>$le,
          'caja_nom'=>$cnom,
          'fec_desde'=>$rfec_in,
          'fec_hasta'=>$rfec_out,
          'saldo'=>$saldo
      ];
    }
  }


  function call_asiento(){
    $p = $this->input->post();
    // $id_caja = $p['data']['id_caja'];
    // SETEO EL ARRAY DATA
    // DEFAULT PCLES
    // $caja_pcles= $this->app_model->get_all_from('atoms_struct',"WHERE atom_types_id = {$this->Atom_types_id} ORDER BY ord_num_inform ASC ");
    //MANDO A PANTALLA LOS INPUTS
    // array con op_nro y op_id de contab_asientos

    // $op = $this->app_model->get_num_operac_caja($p['user_id']);

    $fec = date("d/m/Y");
    $selects = [
      'cuentas'=>$this->cmn_functs->get_cuentas_dpdown_data($this->usr_obj->permisos_usuario),
      'impt_cli'=>$this->cmn_functs->get_imputaciones_cli_dpdown_data(),
      'impt_prov'=>$this->cmn_functs->get_imputaciones_prov_dpdown_data(),
      'proveedores'=>$this->cmn_functs->get_proveedores_dpdown_data(),
      'clientes'=>$this->cmn_functs->get_clientes_dpdown_data(),
      'barrio'=>$this->cmn_functs->get_dpdown_data('BARRIO')
    ];
    echo json_encode(array(
      'callback'=>'mk_asiento_caja',
      'param'=>array(
        'fecha'=>$fec,
        // 'op_nro'=> $op['op_num'],
        // 'op_id'=> $op['op_id'],
        'selects'=>$selects
      )
    ));
  }

  function save_asiento(){
    // FIELDS PARA GUARDAR EN ASIENTOS CONTABLES
    $f = $this->input->post('fields');
    $ccd = $this->input->post('ccd');
    $res = $this->cmn_functs->mk_asiento_caja($f,$ccd);
    echo json_encode(array(
      'callback'=>'front_call',
      'param'=>array(
        'method'=>'registro_operacion',
        'action'=>'response',
        'result'=>$res,
      )
    ));
  }


  function call_pase_entre_cajas(){
    $p = $this->input->post();
    $fec = date("d/m/Y");
    $selects = ['cuentas'=>$this->cmn_functs->get_cuentas_dpdown_data($this->usr_obj->permisos_usuario)
                // 'impt_cli'=>$this->cmn_functs->get_imputaciones_cli_dpdown_data(),
                // 'impt_prov'=>$this->cmn_functs->get_imputaciones_prov_dpdown_data(),
                // 'proveedores'=>$this->cmn_functs->get_proveedores_dpdown_data(),
                // 'clientes'=>$this->cmn_functs->get_clientes_dpdown_data(),
                // 'barrio'=>$this->cmn_functs->get_dpdown_data('BARRIO')
                ];
    echo json_encode(array(
      'callback'=>'mk_pase_asiento_caja',
      'param'=>array(
        'fecha'=>$fec,
        'selects'=>$selects,
        )
    ));

  }

  function save_pase_entre_cajas(){
    // FIELDS PARA GUARDAR EN ASIENTOS CONTABLES
    $p = $this->input->post();
    if($this->app_model->insert_pase_entre_cajas($p['egreso'],$p['ingreso'])){
      $res = 'OK';
    }else{
      $res = 'FAIL';
    }
    $this->cmn_functs->resp('front_call',array('method'=>'pase_entre_cajas','action'=>'response','result'=>$res));
  }


  function update_op(){
    $p = $this->input->post('data');
    $x = [
      'cuentas_id'=>0,
      'cuenta_imputacion_id'=>0,
      'proveedor_id'=>0,
      'id'=>0
      ];

    if(intval($p['cuentas_id'])> 0){$x['cuentas_id'] = $p['cuentas_id'];}
    if(intval($p['cuenta_imputacion_id'])> 0){$x['cuenta_imputacion_id'] = $p['cuenta_imputacion_id'];}
    if(intval($p['proveedor_id'])> 0){$x['proveedor_id'] = $p['proveedor_id'];}
    if(intval($p['id'])> 0){$x['id'] = $p['id'];}

    if(intval($x['cuentas_id']) > 0 && intval($x['cuenta_imputacion_id']) > 0 && intval($x['id']) > 0){
      $op_result = $this->app_model->update('contab_asientos',$x,'id',$p['id']);
      $r = [
        'method'=>$p['resp_method'],
        'data'=>$p['resp_data'],
        'sending'=>true,
        'action'=>'refresh',
        'result'=>$op_result
      ];
      $this->cmn_functs->resp('front_call',$r);
    }else{
      echo 'Error-> update_op ';
      var_dump($p);
    }


  }

  function edit_op(){
    $p = $this->input->post('data');
    $res = $this->app_model->get_obj("
        SELECT
        a.id,
        a.operacion_nro  as 'Nro. de Operacion',
        DATE_FORMAT(a.fecha,'%d/%m/%Y') as Fecha,
        a.tipo_asiento as Tipo,
        cimp.id as 'Cuenta de Imputación',
        cnta.id as Caja,

        pl.value as 'Codigo Lote',
        CONCAT(IF(pcl_apl.value != '', pcl_apl.value,''), ' ',pcl.value )as 'Cliente',
        a.proveedor_id as Proveedor,
        a.monto as 'Monto $',
        a.nro_comprobante as 'Nro. de Comprobante',
        cpr.concepto as Concepto,
        cpr.id as cpr_id,
        atbr.name as 'Centro de Costos',
        ccd.percent as 'Centro Costos %',
        a.observaciones as 'Observaciones'

        FROM `contab_asientos` a
        LEFT OUTER JOIN contab_cuentas cnta on a.cuentas_id = cnta.id
        LEFT OUTER JOIN contab_cuenta_de_imputacion cimp on a.cuenta_imputacion_id = cimp.id
        LEFT OUTER join atoms_pcles ppr on a.proveedor_id = ppr.atom_id AND ppr.label = 'nombre'
        LEFT OUTER join atoms_pcles pcl on a.cliente_id = pcl.atom_id AND pcl.label = 'nombre'
        LEFT OUTER join atoms_pcles pcl_apl on a.cliente_id = pcl_apl.atom_id AND pcl_apl.label = 'apellido'
        LEFT OUTER join atoms_pcles pl on a.lote_id = pl.atom_id AND pl.label = 'name'
        LEFT OUTER JOIN comprobantes cpr on a.nro_comprobante = cpr.nro_comprobante AND a.operacion_nro = cpr.op_caja_nro
        LEFT OUTER JOIN contab_cc_distrib ccd on a.id = ccd.asiento_id
        LEFT OUTER JOIN atoms atbr on atbr.id = ccd.barrio_id
        WHERE a.id = {$p['op_id']}");
    echo json_encode(array(
      'callback'=>'front_call',
      'param'=>array(
        'route'=>'caja/',
        'method'=>'edit_op',
        'action'=>'response',
        'result'=>$res,
      )
    ));
  }

  function anular_op(){
    $p = $this->input->post();
    // LOG DE ACCIONES DE BORRADO
    $this->app_model->insert('user_action_log',['user_id'=> $p['user_id'],'method'=>'anular_op','action'=>'anula operacion','log'=>'marcado estado -1 en contab_asientos id:'.$p['data']['id']]);
    $this->app_model->update('contab_asientos',['estado'=> -1 ],'id',$p['data']['id']);
    //  OPERACION  en contab_asientos
    $op = $this->app_model->get_obj("SELECT * FROM contab_asientos WHERE id = {$p['data']['id']} ");
    // SETEAR NRO DE COMPROBANTE COMO ANULADO
    $this->app_model->update('comprobantes',['estado'=> -1 ],'nro_comprobante',$op->nro_comprobante);

    if( $op->tipo_asiento  == 'INGRESOS' && intval($op->nro_comprobante) > 0){
      $events_id = [];
      // OBTENER record del  COMPROBANTE
      $cpr = $this->app_model->get_obj("SELECT * FROM comprobantes WHERE nro_comprobante = {$op->nro_comprobante}");
      if(!empty($cpr)){
        // OBTENER EVENTS INVOLUCRADOS Y ANULAR
        $events_id = explode('|', $cpr->detalle_events_id);
      }
      if(count($events_id) > 0 && $events_id[0] != ''){
        // ACTUALIZAR CADA EVENT
        foreach ($events_id as $ev_id) {
          // 191 es pago de cuota
          if($op->cuenta_imputacion_id == 191){
            // actualiza el estado del event pago de cuota
            $ev = new Event($ev_id);
            $ev->set_pcle(0,'estado','ANULADO');
            // marca como borrados todos los comprobantes con numero mayor en el contrato
            $recibo_pago = intval($ev->get_pcle('recibo_nro')->value);
        		$c = $this->Mdb->db->query("UPDATE comprobantes
              SET estado = -1
              WHERE elements_id = {$ev->elements_id}
              AND nro_comprobante > $recibo_pago "
            );
          }
        }
      }
    }
    // ID 203 ES TRANSFERENCIA ENTRE CAJAS
    if($op->cuenta_imputacion_id == 203){
      if($op->tipo_asiento == 'EGRESOS'){
            //  OBTENER LA OPERACION DE ID SIGUIENTE EN CONTAB CUENTAS
        $x = intval($p['data']['id'])+1;
        $op_c = $this->app_model->get_obj("SELECT * FROM contab_asientos WHERE id = {$x} ");
            //  CHECKEAR LA CUENTA CONTRAPARTE
        if($op_c->cta_contraparte_id == $op->cuentas_id){
          $this->app_model->update('contab_asientos',['estado'=> -1 ],'id',$x);
        }
      }
      if($op->tipo_asiento == 'INGRESOS'){
            //  OBTENER LA OPERACION DE ID SIGUIENTE EN CONTAB CUENTAS
        $x = intval($p['data']['id'])-1;
        $op_c = $this->app_model->get_obj("SELECT * FROM contab_asientos WHERE id = {$x} ");
            //  CHECKEAR LA CUENTA CONTRAPARTE
        if($op_c->cta_contraparte_id == $op->cuentas_id){
          $this->app_model->update('contab_asientos',['estado'=> -1 ],'id',$x);
        }
      }
    }
    $this->cmn_functs->resp('front_call',$p['list_refresh']);
  }

  function movs_de_cajas(){
    $p = $this->input->post('data');
    $dt_in = $p['dt_in'];
    $dt_out = $p['dt_out'];
    $r = $this->app_model->get_arr("SELECT
      DATE_FORMAT(ca.fecha, '%d/%m/%Y') as fecha,
      FLOOR(ca.monto) as importe,
      cc.nombre as caja,
      (CASE
        WHEN p.name != 'null' THEN p.name
        -- WHEN p2.name != 'null' THEN CONCAT(atlt.name, ' ' ,p2.name)
        WHEN p2.name != 'null' THEN CONCAT(atlt.name, ' ' ,p2.name)
        WHEN cctr.nombre != 'null' THEN  cctr.nombre
      END ) as contraparte,

      ci.nombre as concepto,
      pdni.value as dni,
      ca.observaciones as detalle,
      GROUP_CONCAT(atbr.name SEPARATOR ',') as 'cdc_name',
      GROUP_CONCAT(ccd.percent SEPARATOR ',') as 'cdc_percent'
      FROM contab_asientos ca
      LEFT OUTER JOIN contab_cuentas cc on cc.id = ca.cuentas_id
      LEFT OUTER JOIN contab_cuenta_de_imputacion ci on ci.id = ca.cuenta_imputacion_id
      LEFT OUTER JOIN contab_cuentas cctr on cctr.id = ca.cta_contraparte_id
      LEFT OUTER JOIN atoms p on p.id = ca.proveedor_id
      LEFT OUTER JOIN atoms p2 on p2.id = ca.cliente_id
      LEFT OUTER JOIN atoms_pcles pdni on pdni.atom_id = ca.cliente_id AND pdni.label = 'dni'
      LEFT OUTER JOIN contab_cc_distrib ccd on ca.id = ccd.asiento_id
      LEFT OUTER JOIN atoms atbr on atbr.id = ccd.barrio_id
      LEFT OUTER JOIN atoms atlt on atlt.id = ca.lote_id
      WHERE estado = 1 AND tipo_asiento = '{$p['tipo']}'  AND ca.fecha >= STR_TO_DATE('{$dt_in} 00:00:00','%d/%m/%Y %H:%i:%s') AND ca.fecha <= STR_TO_DATE('{$dt_out} 23:59:59','%d/%m/%Y %H:%i:%s') GROUP BY ca.operacion_nro"
    );
    if(count($r) > 0){
      $recs = [];
      setlocale(LC_MONETARY, 'en_US');
      foreach ($r as $row) {
          $tr = [
            'Fecha'=> $row['fecha'],
            'Importe'=>$row['importe'],
            'Caja'=>$row['caja'],
            'Contraparte'=>$row['contraparte'],
            'DNI'=>$row['dni'],
            'Concepto'=>$row['concepto'],
            'Detalle'=>$row['detalle']
          ];
          $cdc_names = explode(',', $row['cdc_name']);
          $cdc_percents = explode(',', $row['cdc_percent']);
          $i = 0;
          foreach ($cdc_names as $key => $c) {
            $i ++;
            // SUMA LOS PORCENTAJES EN LAS COLUMNAS DE CENTRO DE COSTOS
            $tr[$c]=intval(intval($row['importe']) * intval($cdc_percents[$key])/100);
            // MUESTRA PORCENTAJES
            // $tr['Ctro. Ctos. '.$i]=$c.': '. money_format('%(#10n', intval(intval($row['importe']) * intval($cdc_percents[$key])/100));
          }
          $recs[] = $tr;
        }


      $response = [
          'method'=>'movs_de_cajas',
          'action'=>'response',
          'data'=>$recs,
          'tit'=>$p['tipo'].' DE CAJAS'
        ];
      $this->cmn_functs->resp('front_call',$response);
    }else{
      $response =[
            'tit'=>'Movimientos de Cajas ',
            'msg'=>'El rango de fechas seleccionadas no es valido',
            'type'=>'warning',
            'container'=>'modal',
            'win_close_method' => 'light_back'
          ];
          $this->cmn_functs->resp('myAlert',$response);
    }
  }

  function pagos_online(){
    $p = $this->cmn_functs->get_pagos_online();
    if(empty($p)){
      $response =[
            'tit'=>'Registro de pagos online ',
            'msg'=>'error el listado esta vacio',
            'type'=>'warning',
            'container'=>'modal',
            'win_close_method' => 'light_back'
          ];
      $this->cmn_functs->resp('myAlert',$response);
    }
    $response = [
        'method'=>'pagos_online',
        'action'=>'response',
        'data'=>$p,
        'tit'=>"Listado de pagos online"
      ];
    $this->cmn_functs->resp('front_call',$response);
  }


  function file_download(){
    $p = $this->input->get('id');
    $data = file_get_contents(base_url('uploads/'.$p));
      force_download($p,$data);
  }

}
