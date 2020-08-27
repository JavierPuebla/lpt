<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Clientes extends CI_Controller {
    // ******   CONSTRUCTOR AND INDEX
    public function __construct() {
        parent::__construct();
        $this->Mdb =& get_instance();
        $this->Mdb->load->database();

        $this -> load -> model('app_model');
        $this->load->helper('array');
        $this->load->helper('form');
        $this->load->library('cmn_functs');
        // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $this->types_id = 1;
        $this->type_text = 'CLIENTE';
        $this->route = 'clientes/';

        include (APPPATH . 'JP_classes/Atom.php');
        include (APPPATH . 'JP_classes/Element.php');
        include (APPPATH . 'JP_classes/Event.php');

        //****  USER PRIVILEDGES
        $user = $this -> session -> userdata('logged_in');
        if (is_array($user)){
            if($user['user_permisos'] < 100){
                $this->usr_obj = $this->app_model->get_obj("SELECT permisos_usuario FROM usuarios WHERE id = {$user['user_id']} ");
            }else{
                $this->usr_obj = $this->app_model->get_obj("SELECT permisos_usuario FROM usuarios WHERE id = 509 ");
            }
        }else {
            redirect('login', 'refresh');
        }
    }

    public function index() {
        $cls_name = 'clientes';
        $user = $this -> session -> userdata('logged_in');
        if (is_array($user)) {
            // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW ***********
            // $financ_type = new Atom(0,'TIPO_DE_FINANCIACION','LOTE ANTICIPO Y CUOTAS CICLO 2');
            $selects = [
                'servicios'=>$this->cmn_functs->get_dpdown_data('SERVICIO'),
                // 'financ_ciclo2'=>$this->cmn_functs->get_dpdown_data_financ($financ_type->id),
                // 'financiacion'=>$this->cmn_functs->get_dpdown_data('FINANCIACION'),
                'proveedor'=>$this->cmn_functs->get_dpdown_data('PROVEEDOR'),
                'cuentas'=>$this->cmn_functs->get_cuentas_dpdown_data($this->usr_obj->permisos_usuario),
                'cli_id'=>$this->cmn_functs->get_dpdown_data('CLIENTE'),
                'barrio_id'=>$this->cmn_functs->get_dpdown_data('BARRIO'),
                'titular_id'=>$this->cmn_functs->get_dpdown_data('CLIENTE'),
                'cotitular_id'=> array_merge([['id'=>0,'lbl'=>'Sin Cotitular']],$this->cmn_functs->get_dpdown_data('CLIENTE')),
                'beneficiario_id'=> array_merge([['id'=>-1,'lbl'=>'Sin beneficiario']],$this->cmn_functs->get_dpdown_data('BENEFICIARIO')),
                'vendedor'=>$this->cmn_functs->get_dpdown_data('VENDEDOR'),
                'prod_id'=>$this->cmn_functs->get_dpdown_lt_disp(),
                'anticipo'=>[['id'=>-1,'lbl'=>'NO'],['id'=>1,'lbl'=>'SI']],
                'escaneado'=>[['id'=>-1,'lbl'=>'NO'],['id'=>1,'lbl'=>'SI']],
                'clausula_revision'=>[['id'=>'NO','lbl'=>'NO'],['id'=>'SI','lbl'=>'SI']],

                'aplica_revision'=>[['id'=>-1,'lbl'=>'NO'],['id'=>1,'lbl'=>'SI']],
                'frecuencia_ctas_refuerzo'=>[['id'=>-1,'lbl'=>'NO'],['id'=>3,'lbl'=>'3'],['id'=>6,'lbl'=>'6'],['id'=>9,'lbl'=>'9'],['id'=>12,'lbl'=>'12']],
                'financ_type'=>$this->cmn_functs->get_dpdown_financ_type(),
                'asignado_a'=>$this->cmn_functs->get_asigando_a_dpdown_data(),
                'rev_fplan'=>$this->cmn_functs->get_dpdown_data_rev_fplan(),
                'cant_ctas' => [['id'=>1,'lbl'=>'1'],['id'=>2,'lbl'=>'2'],['id'=>3,'lbl'=>'3'],['id'=>4,'lbl'=>'4'],['id'=>5,'lbl'=>'5'],['id'=>6,'lbl'=>'6'],['id'=>7,'lbl'=>'7'],['id'=>8,'lbl'=>'8'],['id'=>9,'lbl'=>'9'],['id'=>10,'lbl'=>'10'],['id'=>11,'lbl'=>'11'],['id'=>12,'lbl'=>'12'],['id'=>24,'lbl'=>'24'],['id'=>36,'lbl'=>'36'],['id'=>48,'lbl'=>'48'],['id'=>60,'lbl'=>'60'],['id'=>90,'lbl'=>'90'],['id'=>144,'lbl'=>'144'],['id'=>150,'lbl'=>'150'],['id'=>156,'lbl'=>'156'],['id'=>198,'lbl'=>'198'],['id'=>204,'lbl'=>'204']],
                'cant_ctas_ciclo_2' => [['id'=>0,'lbl'=>'NO'],['id'=>120,'lbl'=>'120'],['id'=>150,'lbl'=>'150']],
                'indac' => [['id'=>-1,'lbl'=>'NO'],['id'=>14,'lbl'=>'14%'],['id'=>16,'lbl'=>'16%'],['id'=>25,'lbl'=>'25%']],
                'frecuencia_indac' => [['id'=>-1,'lbl'=>'NO'],['id'=>6,'lbl'=>'Semestral'],['id'=>12,'lbl'=>'Anual']],
                'frecuencia_revision' => [['id'=>-1,'lbl'=>'NO'],['id'=>6,'lbl'=>'6 meses'],['id'=>12,'lbl'=>'12 meses'],['id'=>18,'lbl'=>'18 meses'],['id'=>24,'lbl'=>'24 meses']],
                //** update 2 julio 2020
                'estado_contrato'=>$this->cmn_functs->fill_select_by_atom_types_id(22)

            ];
            // PREPARO LOS DATOS DEL VIEW
            $var=array(
                'route'=>$this->route,
                'user_id'=>$user['user_id'],
                'permisos'=>$this->usr_obj->permisos_usuario,
                'selects'=>$selects,
                'locked'=>($user['user_id'] == 484)?false:false,
                'screen'=>$this->get_screen($user),
                'screen_title'=>'Clientes '
            );

            // ****** LOAD VIEWS ******
            $this -> load -> view('header-responsive');
            if($user['user_permisos'] < 100){
                // ****** NAVBAR DATA ********
                $userActs = $this -> app_model -> get_activities($user['user_id']);
                $acts = explode(',',$userActs['elements_id']);
                $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['user_id'])['usr_usuario']));
            }
            $this -> load -> view('screen_view', $var);
        } else {
            redirect('login', 'refresh');
        }
    }
    // ****** END INDEX  ******

    // *** CREA EL SCREEN EN BASE A LOS PERMISOS DEL USUARIO
    function get_screen($u){
        $btns = [
            ['call'=>['method'=>'get_elements','sending'=>false,'action'=>'call','data'=>0],'tag'=>'Resumen de Cuenta'],
            ['call'=>['method'=>'new_contrato_elem','sending'=>true,'action'=>'call','data'=>0],'tag'=>'Venta de lote'],
            ['call'=>['method'=>'call_new_atom','sending'=>true,'action'=>'call','type_text'=>'CLIENTE','data'=>0],'tag'=>'Alta de Cliente'],
        ];
        if(intval($u['user_permisos']) < 4 ){
            if($u['user_id'] == 501 || $u['user_id'] == 484){
                $btns[] = ['call'=>['method'=>'listado','sending'=>true,'action'=>'call','type_text'=>'CLIENTE','data'=>0],'tag'=>'Listado de Clientes'];
                $btns[] = ['call'=>['method'=>'get_saldos','sending'=>true,'action'=>'call','type_text'=>'CLIENTE','data'=>0],'tag'=>'Saldos en Cuenta'];
            }
            if($u['user_id'] == 501 || $u['user_id'] == 498 || $u['user_id'] == 499 || $u['user_id'] == 502){
                $btns[] = ['call'=>['method'=>'get_saldos','sending'=>true,'action'=>'call','type_text'=>'CLIENTE','data'=>0],'tag'=>'Saldos en Cuenta'];
            }
            return $btns;
        }
        else{
            $r = [$btns[0]];
        }
        return $r;
    }

    //****** 07 agosto 2020
    //**** LISTADO EDITABLE DE CLIENTES
    //************************************************
     function listado(){
      $d = [];
      $pr = $this->Mdb->db->query("SELECT atom_id as atom_id ,
          MAX(CASE WHEN label = 'id'  THEN value END) AS 'nombre',
          MAX(CASE WHEN label = 'nombre'  THEN value END) AS 'nombre',
          MAX(CASE WHEN label = 'apellido' THEN value END) AS 'apellido',
          MAX(CASE WHEN label = 'dni'  THEN value END) AS 'dni',
          MAX(CASE WHEN label = 'cuit_cuil'  THEN value END) AS 'cuit_cuil',
          MAX(CASE WHEN label = 'ocupacion'  THEN value END) AS 'ocupacion',
          MAX(CASE WHEN label = 'telefono'  THEN value END) AS 'telefono',
          MAX(CASE WHEN label = 'celular_difusion' THEN value END) AS 'celular_difusion',
          MAX(CASE WHEN label = 'celular'  THEN value END) AS 'celular',
          MAX(CASE WHEN label = 'domicilio'  THEN value END) AS 'domicilio',
          MAX(CASE WHEN label = 'localidad'  THEN value END) AS 'localidad',
          MAX(CASE WHEN label = 'codigo_postal'  THEN value END) AS 'codigo_postal',
          MAX(CASE WHEN label = 'email'  THEN value END) AS 'email',
          MAX(CASE WHEN label = 'nombre_contacto'  THEN value END) AS 'nombre_contacto',
          MAX(CASE WHEN label = 'apellido_contacto'  THEN value END) AS 'apellido_contacto',
          MAX(CASE WHEN label = 'domicilio_contacto'  THEN value END) AS 'domicilio_contacto',
          MAX(CASE WHEN label = 'localidad_contacto'  THEN value END) AS 'localidad_contacto',
          MAX(CASE WHEN label = 'cod_post_contacto'  THEN value END) AS 'cod_post_contacto',
          MAX(CASE WHEN label = 'tel_contacto' THEN value END) AS 'tel_contacto',
          MAX(CASE WHEN label = 'email_contacto' THEN value END) AS 'email_contacto',
          MAX(CASE WHEN label = 'parentesco_contacto' THEN value END) AS 'parentesco_contacto',
          MAX(CASE WHEN label = 'nombre_segundo_contacto' THEN value END) AS 'nombre_segundo_contacto',
          MAX(CASE WHEN label = 'apellido_segundo_contacto' THEN value END) AS 'apellido_segundo_contacto',
          MAX(CASE WHEN label = 'tel_segundo_contacto' THEN value END) AS 'tel_segundo_contacto',
          MAX(CASE WHEN label = 'email_segundo_contacto' THEN value END) AS 'email_segundo_contacto',
          MAX(CASE WHEN label = 'parentesco_segundo_contacto' THEN value END) AS 'parentesco_segundo_contacto'
          FROM atoms_pcles
          WHERE atom_types_id = 1 GROUP BY atom_id
      ");
      $st = $this->Mdb->db->query("SELECT st.id,
        st.label,
        st.title,
        st.vis_ord_num,
        v.nombre as vis_elem_type,
        st.validates
        FROM atoms_struct st
        JOIN visual_objects v on v.id = vis_elem_type
        WHERE atom_types_id = 1 ORDER BY st.vis_ord_num ASC");
      $struct = ($st->result_id->num_rows)?$st->result_array():[];
      if($pr->result_id->num_rows){
          $r = [
            'route'=>$this->route,
            'method'=>'listado',
            'action'=>'response',
            'title'=>' Listado de Clientes',
            'data'=> $pr->result_array(),
            'struct'=> $struct
          ];

      }else{
          exit('error en query');
      }
      $this->cmn_functs->resp('front_call',$r);
    }

    //****** 7 julio 2020 ****************************
    //**** listado de saldos en cuenta de cliente
    //************************************************
    function get_saldos(){
        $r =[];
        $q = "SELECT
                e.elements_id AS elem_id,
                (SELECT name FROM atoms WHERE id = (SELECT value FROM elements_pcles WHERE elements_id = e.elements_id AND label = 'prod_id') )  as atom_name
                FROM `elements_pcles` e
                WHERE e.elements_types_id = 1 AND e.label = 'cant_ctas_restantes' and e.value >0 group by e.elements_id";
        $c = 0;
        $e = $this->Mdb->db->query($q);
        if($e->result_id->num_rows){
            foreach($e->result_array() as $el){
                $l = '';$s='';$nc='';
                $q_saldo = "SELECT nro_comprobante,saldo from comprobantes WHERE elements_id = {$el['elem_id']} AND estado > 0  ORDER BY id DESC LIMIT 1 ";
                $cs = $this->Mdb->db->query($q_saldo);
                if($cs->result_id->num_rows){
                    $s = $cs->row()->saldo;
                    $nc = $cs->row()->nro_comprobante;
                }
                // 'elm_id'=>$el['elements_id'],
                $d[]=['Codigo Lote'=>$el['atom_name'],'Saldo'=>$s,'Nro. Comprobante'=>$nc];
            }
        }
        $r = [
            'route'=>$this->route,
            'method'=>'get_saldos',
            'action'=>'response',
            'title'=>' Saldos de Clientes',
            'editable'=>false,
            'nolabel'=>true,
            'data'=>$d
        ];
        $this->cmn_functs->resp('front_call',$r);
    }



    // *************************************************************************
    // ******* 05 marzo 2020
    // ******* ACTUALIZA EL PCLE POR EL ID USADO EN LIST
    // *************************************************************************
    function pcle_updv(){
        if(!$this -> session -> userdata('logged_in')){redirect('login', 'refresh');}
        $p = $this->input->post();
        $this->cmn_functs->atom_updv($this->route,$p);
    }

    // PAGO API
    function call_pago_api(){
        $p = $this->input->post('data');
        date_default_timezone_set('UTC');
        $monto = number_format ( floatval(abs(intval($p['monto']))) , 0 ,".",",");
        $monto_transac = abs(intval($p['monto']."00"));
        $date = date('YmdHis');

        $transaction_id = $this->cmn_functs->get_transaction_id($p['elem_id'],$p['cargos'],$monto_transac);
        $merchant_id = "86342911";
        $clv = "WdwpCPS16J6HwckK";

        $mensage_pago = "Clikear el boton \"Procesar Pago\"  para ser redirigido al sitio seguro donde podrá ingresar los datos de su medio de pago. Luego de verificado el pago se emitirá el recibo correspondiente.";


        // ENCODE SIGNATURE PARA ENVIAR AL SITIO DE COBRO
        $signature_content = "INTERACTIVE+{$monto_transac}+PRODUCTION+032+es+PAYMENT+SINGLE+{$merchant_id}+{$date}+{$transaction_id}+V2+{$clv}";
        $utf8_sig_cont = utf8_encode($signature_content);
        $signature = base64_encode(hash_hmac('sha256',$utf8_sig_cont, $clv, true));
        $res_data = "<div class='container'>
        <div class='row'><h3>Monto del Pago : \$ {$monto}</h3></div>
        <div class='row'><h3>{$mensage_pago}</h3></div>
        <form method='POST' action='https://secure.cobroinmediato.tech/vads-payment/'>
            <input type='hidden' name='vads_action_mode' value='INTERACTIVE' />
            <input type='hidden' name='vads_amount' value='{$monto_transac}' />
            <input type='hidden' name='vads_ctx_mode' value='PRODUCTION' />
            <input type='hidden' name='vads_currency' value='032' />
            <input type='hidden' name='vads_language' value='es' />
            <input type='hidden' name='vads_page_action' value='PAYMENT' />
            <input type='hidden' name='vads_payment_config' value='SINGLE' />
            <input type='hidden' name='vads_site_id' value='{$merchant_id}' />
            <input type='hidden' name='vads_trans_date' value='{$date}'  />
            <input type='hidden' name='vads_trans_id' value='{$transaction_id}' />
            <input type='hidden' name='vads_version' value='V2' />
            <input type='hidden' name='signature' value='$signature' />
            <input type='submit' class='btn btn-success' name='pagar' value='Procesar Pago'/>
            </form></div></div>";

            $r = [
                'method'=>'call_pago_api',
                'action'=>'response',
                'data'=>$res_data

            ];
            $this->cmn_functs->resp('front_call',$r);
    }

    // ****  ATOM CRUDE
    function add(){
        $p = $this->input->post();
        $this->app_model->update('atoms',array('name'=>$p['atom_name']),'id',$p['atom_id']);
        foreach ($p['data'] as $d) {
            $this->app_model->insert('atoms_pcles',array('atom_id'=>$p['atom_id'],'label'=>$d['label'],'value'=>$d['value'],'title'=>$d['title'],'vis_elem_type'=>$d['vis_elem_type']));
        }
        $r =[
            'tit'=>'REGISTRO DE NUEVO CLIENTE ',
            'msg'=>'Cliente creado correctamente',
            'type'=>'success',
            'container'=>'modal'
            // ,'after_action'=>['method'=>'back']
        ];
        echo json_encode(
            array(
                'callback'=>'myAlert',
                'param'=>$r
            )
        );
    }

    function upd(){
            $p = $this->input->post();
            $this->app_model->update('atoms',array('name'=>$p['atom_name']),'id',$p['atom_id']);
            foreach ($p['data'] as $d) {
                $this->app_model->update('atoms_pcles',array('value'=>$d['value']),'id',$d['id']);
            }
            if(!empty($p['owner_id_exists'])){
                $aft_act = ['method'=>'get_elements','sending'=>true,'data'=>['elm_id'=>$p['owner_id_exists']]];
            }else{
                $aft_act = ['method'=>'back'];
            }
            $r =[
                'tit'=>'Modificación de clientes',
                'msg'=>'Cliente actualizado correctamente',
                'type'=>'success',
                'container'=>'modal',
                'after_action'=>$aft_act
            ];
            echo json_encode(array
            (
                'callback'=>'myAlert',
                'param'=>$r
                )
            );
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
                    $pcle_id = $this->app_model->insert(
                        'atoms_pcles',
                        array(
                            'atom_id'=>$atm_id,
                            'atom_types_id'=>$this->Atom_types_id,
                            'label'=>$dp['label'],
                            'value'=>'',
                            'title'=>$dp['title'],
                            'vis_elem_type'=>$dp['vis_elem_type']
                        )
                    );
                    $res[] = $this->app_model->get_all_from('atoms_pcles',"WHERE id = ".$pcle_id)[0];
                }
            }
            return $res;
    }
    // DEBERIA DEPRECAR O REFACTORIZAR
    function atom_crude(){
            $p = $this->input->post();
            if($p['action'] == 'call'){
                // *****  TENGO UN NUMERO DE ID PARA PEDIR
                if(!empty($p['data']['atom_id'])){
                    $q="SELECT * FROM atoms WHERE id ={$p['data']['atom_id']}";
                }else if(!empty($p['data']['atom_name'])){
                    // *******  NO TENGO NRO ID BUSCO ATOM NAME
                    $q="SELECT * FROM atoms WHERE atom_types_id = {$this->Atom_types_id} AND name ='{$p['data']['atom_name']}'";
                }
                $chk = $this->app_model->get_obj($q);

                if(empty($chk)){
                    // ****** CREATE
                    $a = new Atom(0,"CLIENTE",$p['data']['atom_name']);
                    $res= $this->app_model->get_arr("SELECT * FROM atoms_struct WHERE atom_types_id = {$this->Atom_types_id} ORDER BY vis_ord_num ASC ");
                    $action = 'add';
                    $action_tit = 'Alta de Cliente';
                }else{
                    // ********** ES MODIF
                    $a = new Atom($chk->id);
                    // OBTENGO PCLES EN UN ARRAY PARA CHECKEAR Y AGREGAR LAS QUE FALTAN
                    $current_pcles_arr = $this->app_model->get_arr("SELECT * FROM pcles WHERE atom_id = {$a->id} ");
                    // DEFAULT PCLES
                    $default_pcles= $this->app_model->get_arr("SELECT * FROM atoms_struct WHERE atom_types_id = {$this->Atom_types_id} ORDER BY vis_ord_num ASC ");
                    $res=$this->complete_crude_pcles($a->id,$current_pcles_arr,$default_pcles);
                    $action_tit = 'Modificar Cliente: ';
                    $action = 'upd';
                }
                // MK_INPUTS TOMA EL CONTENIDO DE DATA Y LO LISTA EN UNA PANTALLA

                $r = [
                    'action'=>$action,
                    'title'=>$action_tit .'  '.$a->name,
                    'method'=>'atom_crude',
                    'atom_id'=>$a->id,
                    'data'=>$res
                ];

                $this->cmn_functs->resp('mk_cli_inputs',$r);
                // echo json_encode(
                //   array(
                //     'info'=>$this->input->post(),
                //     'callback'=>'',
                //     'param'=>array(
                // 'action'=>$action,
                // 'title'=>$action_tit .'  '.$a->name,
                // 'method'=>'atom_crude',
                // 'atom_id'=>$a->id,
                // 'data'=>$res,
                //       'footer'=>''
                //     )
                //   )
                // );
            }
    }
    function save_atom(){
            $p = $this->input->post();
            if(array_key_exists('atom_id', $p)){
                $t = new Atom($p['atom_id']);
                $t->set('name',$p['atom_name']);
                foreach ($p['data'] as $key => $val) {
                    if($p['action'] == 'upd'){
                        $t->set_pcle($val['id'],$val['label'],$val['value']);
                    }else{
                        $t->set_pcle(0,$val['label'],$val['value']);
                    }
                }
            }


            $r =[
                'tit'=>'Alta o Modificacion de Cliente ',
                'msg'=>'Guardado OK.',
                'type'=>'success',
                'container'=>'modal',
                'after_action'=> Array('method'=>'light_back')
            ];
            $this->cmn_functs->resp('myAlert',$r);
    }
    // *** *elements resumen  ELEMENTS DEL RESUMEN DE CUENTA
    function get_elements(){
        $p = $this->input->post('data');
        $time1 = date('H:i:s');
        // $this->fix_cli_titular();
        // DTA DEL LOTE Y LOS SERVICIOS ASOCIADOS
        $elm['lote'] = $this->get_lote($p['elm_id']);
        $time2 = date('H:i:s');
        $elm['srv'] = $this->get_srvs($p['elm_id']);
        $elm['last_mov'] = $this->get_last_movs($p['elm_id']);
        $elm['uploaded_files'] = ['lote_data_gen'=>$this->cmn_functs->get_uploaded_files($p['elm_id'],'lote_data_gen'),'web_cli'=>$this->cmn_functs->get_uploaded_files($p['elm_id'],'web_cli')] ;
        $elm['method']='get_elements';
        $elm['action']='response';
        $elm['route']= $this->route;

        if($elm['lote']){
            $elm['time1']= $time1;

            $elm['time2'] = $time2;
            $this->cmn_functs->resp('front_call',$elm);

        }else{
            // FALLO LA BUSQUEDA DEL NRO. DE LOTE
            $res =[
                'tit'=>'Estado de cuenta de clientes',
                'msg'=>'No se pudo acceder al resumen de cuenta ',
                'type'=>'warning',
                'container'=>'modal',
                'win_close_method' => 'back',
                'route'=> $this->route
            ];
            $this->cmn_functs->resp('myAlert',$res);
        }
    }

    // *******************************************
    // agrego datos para print boleto
    // *******************************************
    //***** NEW DATOS LOTE
    function old_get_lote($elm_id){
        $e = new Element($elm_id);
        $cl = new Atom ($e->get_pcle('titular_id')->value);
        $cotit = (!empty($e->get_pcle('cotitular_id')->value))?new Atom ($e->get_pcle('cotitular_id')->value):'';
        if($cotit != ''){
            $cotit_name = $cotit->get_pcle('apellido')->value . ' '.$cotit->get_pcle('nombre')->value ;
            $cotit_dni = $cotit->get_pcle('dni')->value;
            $cotit_domic = $cotit->get_pcle('domicilio')->value;
            $cotit_locali = $cotit->get_pcle('localidad')->value;
        }else{
            $cotit_name = '';
            $cotit_dni = '';
            $cotit_domic = '';
            $cotit_locali = '';
        }
        $l = new Atom($e->get_pcle('prod_id')->value);
        // $fplan = new Atom($e->get_pcle('financ_id')->value);
        $b = new Atom (0,'BARRIO',$l->get_pcle('emprendimiento')->value);
        // CHECKEA SI LA CUOTA UPCOMING FUE REFINANCIADA
        $this->update_vencimientos($e);

        // ACTUALIZA EL PLAN SI ESTA PENDIENTE
        if(intval($e->get_pcle('plan_update_pending')->value) > 0 && intval($e->get_pcle('cant_ctas_ciclo_2')->value) > 0&& $this->usr_obj->permisos_usuario < 3 ){
            $this->call_update_plan($elm_id);
        }

        // // EL ESTADO DEL CONTRATO RESCINDIDO ESTA PARA REVISAR **** DEVUELVE UN ARRAY CON STATE Y RSN_ID o false
        $h = $this->check_historial($l);
        // // DATOS DE CONTRATO RESCISION SI HUBIERA
        $rscn_data = ($h['state'] == 'RESCINDIDO')?$this->cmn_functs->get_rscn_data($h['rscn_obj']):-1;
        // EL CONTRATO
        $el = [
            'cli_id'=>$cl->id,
            'owner_id'=>$e->owner_id,
            'elements_id'=>$e->id,
            'cli_atom_name'=>$cl->get_pcle('apellido')->value . ' '.$cl->get_pcle('nombre')->value,
            'cli_data'=>$cl->get_pcle(),
            'lote_id'=>$l->id,
            'partida'=>($l->get_pcle('partida'))?$l->get_pcle('partida')->value:'',
            'propietario'=> ($l->get_pcle('propietario'))?$l->get_pcle('propietario')->value:'',
            'barrio_nom'=>$b->name,
            'barrio_id'=>$b->id,
            'lote_nom'=>$l->name,
            'observaciones'=>($e->get_pcle('observaciones')->id)?$e->get_pcle('observaciones')->value:'',
            'fec_init'=>$e->get_pcle('fec_ini')->value,
            'curr_state'=>$h['state'],
            'rscn_data'=>$rscn_data,
            'mto_reintegro'=>$this->cmn_functs->get_disponible_credito($e),
            'sf'=>$e->get_saldo_a_financiar(),
            // MUESTRO LA CUOTA UPC SIEMPRE QUE NO ESTE EN REFI
            'cta_upc'=>(!$this->cmn_functs->check_refi($e->id))?$e->get_cta_upc():[],
            'ctas_pagas'=>$e->get_events_pagado_fix(4),
            'ctas_adelantadas'=>$e->get_events(6,'pagado'),
            'ctas_restantes'=>$e->get_events(8,'a_pagar'),
            'ctas_mora'=>$e->get_events(4,'a_pagar'),
            'ctas_pft'=>$e->get_events_pftrm_fix(4,4),
            'ctas_ahorro'=>$e->get_ahorro(),
            'ctas_acciones'=>$this->get_acciones($e),
            'financ'=>$this->get_plan_name($e),
            'freq_rev'=>$this->check_freq_rev($e),
            // DATOS BOLETO ****
            'datos_boleto'=>[
                'tit_nomap'=> $cl->get_pcle('apellido')->value . ' '.$cl->get_pcle('nombre')->value,
                'tit_dni'=> $cl->get_pcle('dni')->value,
                'tit_domic'=>$cl->get_pcle('domicilio')->value,
                'tit_localidad'=>$cl->get_pcle('localidad')->value,
                'cotit_nomap'=>$cotit_name,
                'cotit_dni'=>$cotit_dni,
                'cotit_domic'=>$cotit_domic,
                'cotit_locali'=>$cotit_locali,
                'cod_lote'=>$l->name,
                'metros_2'=> $l->get_pcle('metros2')->value,
                'metros_frente'=> $l->get_pcle('frente')->value,
                'metros_fondo'=> $l->get_pcle('fondo')->value,
                'precio_total'=> intval($e->get_pcle('monto_cta_1')->value) * intval($e->get_pcle('cant_ctas')->value),
                'monto_ciclo1'=> $e->get_monto_ciclo1(),
                'expediente'=> $this->cmn_functs->get_expediente($e),
                'monto_ciclo2' =>  intval($e->get_pcle('monto_cta_1')->value) * intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'cant_cuotas_total' => intval($e->get_pcle('cant_ctas')->value),
                'primer_pago_ciclo1' => intval($e->get_pcle('monto_cta_1')->value),
                'saldo_ciclo1' => $e->get_monto_ciclo1(),
                'cant_ctas_ciclo1' => intval($e->get_pcle('cant_ctas')->value) - intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'cant_ctas_ciclo2' => intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'primerPagoDia' => 10,
                'primerPagoMes' => $this->cmn_functs->get_mes_txt(intval(substr($e->get_pcle('fec_ini')->value,3,2))+1),
                'primerPagoYear' => ($e->get_pcle('fec_ini')->value == '')?substr($e->get_pcle('fecha_boleto')->value,6,4):substr($e->get_pcle('fec_ini')->value,6,4),
                'fechaDia' => substr($e->get_pcle('fec_ini')->value,0,2),
                'fechaMes' => $this->cmn_functs->get_mes_txt(substr($e->get_pcle('fec_ini')->value,3,2)),
                'fechaYear' => substr($e->get_pcle('fec_ini')->value,6,4),
            ]
        ];
        return $el;
    }

    //******** UPDATED 02 julio 2020
    function get_lote($elm_id){
        $e = new Element($elm_id);
        $cl = new Atom ($e->get_pcle('titular_id')->value);
        $cotit = (!empty($e->get_pcle('cotitular_id')->value))?new Atom ($e->get_pcle('cotitular_id')->value):'';
        if($cotit != ''){
            $cotit_name = $cotit->get_pcle('apellido')->value . ' '.$cotit->get_pcle('nombre')->value ;
            $cotit_dni = $cotit->get_pcle('dni')->value;
            $cotit_email = $cotit->get_pcle('email')->value;
            $cotit_domic = $cotit->get_pcle('domicilio')->value;
            $cotit_locali = $cotit->get_pcle('localidad')->value;
        }else{
            $cotit_name = '';
            $cotit_dni = '';
            $cotit_domic = '';
            $cotit_locali = '';
            $cotit_email='';
        }
        $l = new Atom($e->get_pcle('prod_id')->value);
        // $fplan = new Atom($e->get_pcle('financ_id')->value);
        $b = new Atom (0,'BARRIO',$l->get_pcle('emprendimiento')->value);
        // CHECKEA SI LA CUOTA UPCOMING FUE REFINANCIADA
        $this->update_vencimientos($e);

        // ACTUALIZA EL PLAN SI ESTA PENDIENTE
        if(intval($e->get_pcle('plan_update_pending')->value) > 0 && intval($e->get_pcle('cant_ctas_ciclo_2')->value) > 0&& $this->usr_obj->permisos_usuario < 3 ){
            $this->call_update_plan($elm_id);
        }

        // // EL ESTADO DEL CONTRATO RESCINDIDO ESTA PARA REVISAR **** DEVUELVE UN ARRAY CON STATE Y RSN_ID o false
        // $h = $this->check_historial($l);
        // // DATOS DE CONTRATO RESCISION SI HUBIERA
        // $rscn_data = ($h['state'] == 'RESCINDIDO')?$this->cmn_functs->get_rscn_data($h['rscn_obj']):-1;
        //****** DATOS DE RESCISION DESACTIVADOS
        $rscn_data = -1;
        //** UPDATE 25 DE JUNIO 2020
        // VERIFICA EL ESTADO DEL CONTRATO Y SI NO ESTA LO CREA EN NORMAL POR DEFAULT
        if($e->get_pcle('estado_contrato')->id == 0){
            $e->set_pcle(0,'estado_contrato','NORMAL');
        }
        // EL CONTRATO
        $el = [
            'cli_id'=>$cl->id,
            'owner_id'=>$e->owner_id,
            'elements_id'=>$e->id,
            'cli_atom_name'=>$cl->get_pcle('apellido')->value . ' '.$cl->get_pcle('nombre')->value,
            'cli_data'=>$cl->get_pcle(),
            'lote_id'=>$l->id,
            'partida'=>($l->get_pcle('partida'))?$l->get_pcle('partida')->value:'',
            'propietario'=> ($l->get_pcle('propietario'))?$l->get_pcle('propietario')->value:'',
            'barrio_nom'=>$b->name,
            'barrio_id'=>$b->id,
            'lote_nom'=>$l->name,
            'observaciones'=>($e->get_pcle('observaciones'))?$e->get_pcle('observaciones')->value:'',
            'fec_init'=>$e->get_pcle('fec_ini')->value,
            'estado_contrato'=>[
                'label'=>'estado_contrato',
                'id'=>$e->get_pcle('estado_contrato')->id,
                'atom_id'=>$e->get_pcle('estado_contrato')->elements_id,
                'value'=>$e->get_pcle('estado_contrato')->value,
            ],
            'rscn_data'=>$rscn_data,
            'mto_reintegro'=>$this->cmn_functs->get_disponible_credito($e),
            'sf'=>$e->get_saldo_a_financiar(),
            // MUESTRO LA CUOTA UPC SIEMPRE QUE NO ESTE EN REFI
            'cta_upc'=>(!$this->cmn_functs->check_refi($e->id))?$e->get_cta_upc():[],
            'ctas_pagas'=>$e->get_events_pagado_fix(4),
            'ctas_adelantadas'=>$e->get_events(6,'pagado'),
            'ctas_restantes'=>$e->get_events(8,'a_pagar'),
            'ctas_mora'=>$e->get_events(4,'a_pagar'),
            'ctas_pft'=>$e->get_events_pftrm_fix(4,4),
            'ctas_ahorro'=>$e->get_ahorro(),
            'ctas_acciones'=>$this->get_acciones($e),
            'financ'=>$this->get_plan_name($e),
            'freq_rev'=>$this->check_freq_rev($e),
            // DATOS BOLETO ****
            'datos_boleto'=>[
                'tit_nomap'=> $cl->get_pcle('apellido')->value . ' '.$cl->get_pcle('nombre')->value,
                'tit_dni'=> $cl->get_pcle('dni')->value,
                'tit_domic'=>$cl->get_pcle('domicilio')->value,
                'tit_localidad'=>$cl->get_pcle('localidad')->value,
                'tit_email'=>$cl->get_pcle('email')->value,
                'cotit_nomap'=>$cotit_name,
                'cotit_dni'=>$cotit_dni,
                'cotit_domic'=>$cotit_domic,
                'cotit_locali'=>$cotit_locali,
                'cotit_email'=>$cotit_email,
                'cod_lote'=>$l->name,
                'metros_2'=> $l->get_pcle('metros2')->value,
                'metros_frente'=> $l->get_pcle('frente')->value,
                'metros_fondo'=> $l->get_pcle('fondo')->value,
                'precio_total'=> intval($e->get_pcle('monto_cta_1')->value) * intval($e->get_pcle('cant_ctas')->value),
                'monto_ciclo1'=> $e->get_monto_ciclo1(),
                'expediente'=> $this->cmn_functs->get_expediente($e),
                'monto_ciclo2' =>  intval($e->get_pcle('monto_cta_1')->value) * intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'cant_cuotas_total' => intval($e->get_pcle('cant_ctas')->value),
                'primer_pago_ciclo1' => intval($e->get_pcle('monto_cta_1')->value),
                'saldo_ciclo1' => $e->get_monto_ciclo1(),
                'cant_ctas_ciclo1' => intval($e->get_pcle('cant_ctas')->value) - intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'cant_ctas_ciclo2' => intval($e->get_pcle('cant_ctas_ciclo_2')->value),
                'primerPagoDia' => 10,
                'primerPagoMes' => $this->cmn_functs->get_mes_txt(intval(substr($e->get_pcle('fec_ini')->value,3,2))+1),
                'primerPagoYear' => ($e->get_pcle('fec_ini')->value == '')?substr($e->get_pcle('fecha_boleto')->value,6,4):substr($e->get_pcle('fec_ini')->value,6,4),
                'fechaDia' => substr($e->get_pcle('fec_ini')->value,0,2),
                'fechaMes' => $this->cmn_functs->get_mes_txt(substr($e->get_pcle('fec_ini')->value,3,2)),
                'fechaYear' => substr($e->get_pcle('fec_ini')->value,6,4),
                'gremio'=>'',
            ]
        ];
        return $el;
    }
    //***

        // checkea si debe suspender la impresion de pagares en fecha de revision.
        function check_freq_rev($e){
            // APLICA REVISION EN CICLO 1 ES TRUE Y ESTA EN CICLO 1
            if(intval($e->get_pcle('aplica_revision')->value) === 1 && intval($e->get_pcle('current_ciclo')->value) === 1 ){
                return $e->get_pcle('frecuencia_revision')->value;
            }
            if(intval($e->get_pcle('current_ciclo')->value) === 2){
                return $e->get_pcle('frecuencia_revision')->value;
            }
            return 0;
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

    // **** DATOS DE SERVICIOS
    function get_srvs($elm_id){
        $servs_arr=[];
        $s=[];
        // $srv_elmtype = $this->app_model->get_obj("SELECT id FROM elements_types WHERE name = 'SERVICIO' ");
        // **** COLECTA LOS SERVICIOS DEL CONTRATO CON ELM_ID
        $s = $this->app_model->get_arr("SELECT id FROM elements WHERE elements_types_id = 4 AND owner_id = {$elm_id} ");
        foreach ($s as $v) {
            $srv = new Element($v['id']);
            $this->update_vencimientos($srv);
            $srv_name = $this->cmn_functs->get_srv_name($srv);
            // $srv_atom_id = $srv->get_pcle('atom_id')->value;
            // $p = $srv->get_pcle();
            // $srv_atm = new Atom($srv_atom_id);
            // $tot_pagado = $srv->get_tot_pagado();
            // $lp = $srv->get_last_payment();
            $ctupc = $srv->get_cta_upc();
            $ctas_restantes = $srv->get_events(8,'a_pagar');
            $mto_1_pago = intval($ctupc) * intVal($ctas_restantes);
            $servs_arr[]=[
                'srvc_id'=>$srv->id,
                // 'upc2'=>$srv->upc2(),
                'cta_upc'=>$ctupc,
                'ctas_pagas'=>$srv->get_events_pagado_fix(4),
                'ctas_adelantadas'=>$srv->get_events(6,'pagado'),
                'ctas_restantes'=>$ctas_restantes,
                'ctas_mora'=>$srv->get_events(4,'a_pagar'),
                'ctas_pft'=>$srv->get_events_pftrm_fix(4,4),
                'ctas_ahorro'=>$srv->get_ahorro(),
                'financ_name'=>0,//$srv->get_plan(),
                'srvc_name'=>$srv_name,
                'fec_ini'=> $srv->get_pcle('fec_ini')->value,
                'tot_pagado'=> (!empty($tot_pagado))?$tot_pagado:0,
                'ctas_acciones'=> $this->get_acciones($srv),
                // 'fec_ultimo_pago'=> (!empty($lp))?$lp->get_pcle('fec_pago')->value:0,
                'sf'=>$srv->get_saldo_a_financiar(),
                // 'monto_en_1_pago'=>intval($ctupc) * count(($srv->get_events(8,'a_pagar')['events']))
                // 'cta_actual'=>($ctupc > 0)?$ctupc['pcles']->get_pcle('monto_cta')->value:0
            ];
        }
        return $servs_arr;
    }

    // ******************** 29/03/20 ***********
    // REFINANCIAR UNA CUOTA
    // CREA DOS SERVICIOS DE REFINANCIACION
    function mk_refi(){
        $p = $this->input->post('data');
        if(is_array($p)){
            $data_refi_lote =[];
            $data_refi_srvc = [];
            $a = $this->Mdb->db->query("SELECT id FROM atoms WHERE name LIKE 'Refinanciacion Lote' ");
            $b = $this->Mdb->db->query("SELECT id FROM atoms WHERE name LIKE 'Refinanciacion Servicios' ");
            // NOT REFINANCIACION DE LOTE O DE SERVIC EXITS
            if(!$a->result_id->num_rows && !$b->result_id->num_rows ){
                echo "Error  mk_refi clientes->Line 511 ";exit();
            }
            // DATOS PARA REFINANCIACION DEL LOTE
            $data_refi_lote = [
                'elm_id'=>$p['elm_id'],
                'fields'=>[
                    'atom_id'=>$a->row()->id,
                    'fec_ini'=>'01/04/2020',
                    'cant_ctas'=>3,
                    'cant_ctas_restantes'=>3,
                    'monto_total'=>intval($p['refi_cta_monto']),
                    'monto_cta_1'=>ceil(intval($p['refi_cta_monto'])/3)
                ]
            ];
            // SI HAY SERVICIOS ACTIVOS CON CUOTA UPCOMING
            if(array_key_exists('srv_events_id',$p) && $b->result_id->num_rows){
                $data_refi_srvc = [
                    'elm_id'=>$p['elm_id'],
                    'fields'=>[
                        'atom_id'=>$b->row()->id,
                        'fec_ini'=>'01/04/2020',
                        'cant_ctas'=>3,
                        'cant_ctas_restantes'=>3,
                        'monto_total'=> intval($p['refi_srv_monto']),
                        'monto_cta_1'=>intval(intval($p['refi_srv_monto'])/3)
                    ]
                ];
            }
            // SI HAY DATOS PARA GUARDAR CREA EL ITEM REFI Y GUARDA
            if(count($data_refi_lote) > 0){
                $refi_lote_id = $this->save_new_service($data_refi_lote);
                // SETEA EL EVENTO QUE ESTA SIENDO REFINANCIADO A VENCIDO 4 Y ESTADO REFINANCIADO

                $ev_rfdo = new Event($p['cta_event_id']);
                $ev_rfdo->set_pcle(0,'estado','refinanciado');
                $ev_rfdo->set('events_types_id',4);

                //  REFI ATOM GUARDA EL EVENT ID DE LA CUOTA REFINANCIADA
                $refi = new Atom(-1,"REFINANCIACION_ESPECIAL",$p['cta_event_id']);
                $refi->pcle_updv($refi->get_pcle('lote_atom_id')->id,$refi_lote_id);
                $refi->pcle_updv($refi->get_pcle('elem_id')->id,$p['elm_id']);
                $refi->pcle_updv($refi->get_pcle('lote_event_id')->id,$p['cta_event_id']);
                if(count($data_refi_srvc)> 0){
                    $refi_servicios_id = $this->save_new_service($data_refi_srvc);
                    //  REFI SRV_ATOM_ID GUARDA EL EVENT ID DE LA CUOTA REFINANCIADA DEL SERVICIO
                    $refi->pcle_updv($refi->get_pcle('srv_atom_id')->id,$refi_servicios_id);
                    $refi->pcle_updv($refi->get_pcle('srvs_events_id')->id,implode(',',$p['srv_events_id']));

                    foreach ($p['srv_events_id'] as $x) {
                        // SETEA EL EVENTO QUE ESTA SIENDO REFINANCIADO A VENCIDO 4 Y ESTADO REFINANCIADO
                        $ev_rfdo = new Event($x);
                        $ev_rfdo->set_pcle(0,'estado','refinanciado');
                        $ev_rfdo->set('events_types_id',4);
                    }
                }
                $res = [
                    'route'=>$this->route,
                    'method'=>'refinanciar',
                    'action'=>'save_response',
                ];
                $this->cmn_functs->resp('front_call',$res);
            }
        }
    }

    // guarda un servicio por sus pcles en $data
    function save_new_service($data){
        $srv = new Element(-1,"SERVICIO",$data['elm_id']);
        foreach ($data['fields'] as $key=>$val) {
            $srv->pcle_updv($srv->get_pcle($key)->id,$val);
        }
        // CREA CUOTAS EVENTS
        $this->create_cuotas_refinanc($srv);
        return $srv->id;
    }
    //*** END SAVE NEW SERVICE

    function create_cuotas_refinanc($elm){
        //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
        $nfi = '10'.substr($elm->get_pcle('fec_ini')->value, 2);
        $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
        //******
        $totm = intval($elm->get_pcle('monto_total')->value);
        $ctas = intval($elm->get_pcle('cant_ctas')->value);
        //*** MONTO DE CUOTA 1
        $monto_cta = intval($totm / $ctas);
        //*** GENERAR LAS CUOTAS SEGUN CANTIDAD TOTAL DE CUOTAS
        $tc = intval($elm->get_pcle('cant_ctas')->value);
        for ($i=1; $i <= $tc; $i++){
            // FECHA DE VENCIMIENTO
            $curr_fec_ven = $fv->format('d/m/Y');
            //*** OBTENGO NUMERO DE CTA ACTUAL TOTAL DE CUOTAS DE CADA CICLO Y CICLO ACTUAL
            $lyc = "Cuota ".$i.' de '.$ctas;
            // $tr[]=['cuota'=>$lyc,'ordnum'=>$i,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
            $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$i,$lyc);
            // INCREMENTO DEL MES DE FV
            $fv->modify('next month');
        }
        //***  FIN LOOP CREACION DE CUOTAS
    }

    //**** CHECKEO DEL HISTORIAL


    //**** !!!!! TIRANDO NORMAL POR DEFECTO!!!!
    function check_historial($l){
            $rscn_obj = 0;
            $stt = 'NORMAL';
            // $lote_hist_id = $l->get_pcle('hist_id')->value;
            // if(!empty($lote_hist_id)){
            //     $h = new Historial($l->get_pcle('hist_id')->value);
            //     $ev = $h->get_event_last();
            //     $stt = $ev->get_pcle('state')->value;
            //     if($stt == "RESCINDIDO"){
            //       if($ev->get_pcle('accion')->value === 'rescision_id'){
            //         $rscn_obj = new Atom($ev->get_pcle('detalle')->value);
            //       }
            //     }
            // }
            return ['state'=>$stt,'rscn_obj'=>$rscn_obj];
    }


    // UPLOAD DE LOTES DE ADMINISTRADORES
    public function lotes_file_upload(){
        $folder = 'lote_data_gen';
        $nom = $this->input->post('lote_nom');
        $elm_id = $this->input->post('elm_id');

        $upld_res = $this->cmn_functs->file_upload($nom,$elm_id,$folder);
        $this->cmn_functs->resp('',$upld_res);
    }


    // UPLOAD DE LOTES DE CLIENTES
    public function cli_file_upload(){
        $folder = 'web_cli';
        $nom = $this->input->post('lote_nom');
        $elm_id = $this->input->post('elm_id');
        $cli_id = (new Element($elm_id))->get_pcle('cli_id')->value;
        $cli_nom = (new Atom($cli_id))->name;
        $upld_res = $this->cmn_functs->file_upload($nom,$elm_id,$folder);



        //********* envio de email
        $this->load->library('email');
        $this->email->to('llamarca@lotesparatodos.com.ar');
        $this->email->from('no-reply@lotesparatodos.nuberio.com', 'Lotes Para Todos - Acceso web de clientes');
        $this->email->subject('Comprobante de pago o archivo subido al sevidor');
        $this->email->message('El cliente '.$cli_nom.' Lote Numero: '.$nom.' subió el archivo: '.end($upld_res['up_files']));

        $this->email->send();
        $this->cmn_functs->resp('',$upld_res);

    }
    // LISTADO DE ARCHIVOS SUBIDOS
    function get_uploaded_files($elm_id,$folder){
        $this->load->helper('directory');
        $e = new Element($elm_id);
        $l = new Atom($e->get_pcle('prod_id')->value);
        $partida = $l->get_pcle('partida')->value;
        $lnom = $l->name;
        $files_list = [];
        $d = directory_map($folder);
        if($d){
            foreach ($d as $v) {
                if(!is_array("/$lnom/") && preg_match("/$lnom/",$v) || substr($v,0,strpos($v,'.')) === $partida ){
                    $files_list[] = $v;
                }
            }
        }
        return $files_list;
    }


    //  **** MODELO DE SALIDA LISTADO
    // ***** LISTADO ULTIMOS MOVIMIENTOS
    function get_last_movs($elm_id){
        // ** OBTENGO DATOS
        $m = $this->app_model->get_arr("SELECT
            IF(DATE_FORMAT(c.fecha_comprobante,'%d/%m/%Y')!= '00/00/0000',
            DATE_FORMAT(c.fecha_comprobante,'%d/%m/%Y'),
            DATE_FORMAT(c.fecha,'%d/%m/%Y'))  as 'fecha_recibo',
            IF(a.observaciones != '',a.observaciones,  REPLACE(c.concepto,'pago cuota Nro. ','')) as 'concepto',
            (c.monto) as 'monto_operacion',
            c.nro_comprobante as 'recibo_nro',
            c.saldo as 'saldo'
            FROM `comprobantes` c
            LEFT OUTER JOIN contab_asientos a on a.operacion_nro = c.op_caja_nro
            WHERE c.estado > 0 AND c.elements_id = {$elm_id}
            AND c.nro_comprobante > 0 AND c.fecha > '2019-09-01'
            ORDER BY c.fecha DESC "
        );
        //  SI HAY DATOS
        //  ARMO EL LISTADO CON LOS LABELS PARA MOSTRAR COMO HEDADER ENCABEZADOS DE COLUMNAS
        if(empty($m)){
            return -1;
        }else{
            return array_map(function($v){
                return [
                    'Fecha'=>$v['fecha_recibo'],
                    'Concepto'=>$v['concepto'],
                    'Monto $'=>$v['monto_operacion'],
                    //  va el saldo actual
                    'Saldo $'=>$v['saldo'],
                    'Recibo Nro.'=>$v['recibo_nro'],
                    'Acciones'=>$this->cmn_functs->get_accion_icon('open_in_new','detalle_movs',$v['recibo_nro'],1).$this->cmn_functs->get_accion_icon('print','print_recibo',$v['recibo_nro'],1)
                ];
            },$m);
        }
    }

    //****** 20 Julio 2020
    //**** detalle de servicios cancelados
    //************************************************
    function detalle_servicios_cancelados(){
      $p = $this->input->post('data');
      $r = [
        'route'=>$this->route,
        'method'=>'detalle_servicios_cancelados',
        'sending'=>false,
        'action'=>'response',
        // 'container_id'=>$p['container_id'],
        'events'=> ''
      ];
      $q = "SELECT (SELECT value from events_pcles evp6 WHERE evp6.events_id = e.id AND evp6.struct_id = 6 limit 1) as 'Fecha de Pago',
      (SELECT value from events_pcles evp4 WHERE evp4.events_id = e.id AND evp4.struct_id = 4 limit 1) as 'Nro. de Cuota',
      (SELECT value from events_pcles evp5 WHERE evp5.events_id = e.id AND evp5.struct_id = 5 limit 1) as 'Monto Pagado',
      (SELECT value from events_pcles evp7 WHERE evp7.events_id = e.id AND evp7.label LIKE '%recibo%' limit 1) as 'Nro. Comprobante'
      FROM `events` e WHERE e.elements_id = {$p['id']} ";
      $ev = $this->Mdb->db->query($q);
      if(!empty($ev->result_id->num_rows)){
        $r['events'] = $ev->result_array();
      }
      $this->cmn_functs->resp('front_call',$r);
    }

    // ******** print de recibo OK ******
    function print_recibo(){
        $p = $this->input->post('data');
        $rec = $this->app_model->get_obj("SELECT * FROM comprobantes WHERE tipo_comprobante = 'RECIBO' AND nro_comprobante = {$p['id']}");
        $caja = $this->app_model->get_nombre_caja_by_nro_comprobante($p['id']);
        if(empty($rec)){
            $r =[
                'tit'=>'Impresion de Recibo ',
                'msg'=>'No se encuentra en Nro. de recibo '.$p['nro_comprobante'],
                'type'=>'danger',
                'container'=>'modal'
            ];
            $this->cmn_functs->resp('myAlert',$r);
            exit();
        }
        $rec_num = $rec->nro_comprobante;
        $concepto = $rec->concepto;
        $elm = new Element($rec->elements_id);
        $lote = (new Atom($elm->get_pcle('prod_id')->value))->name;
        $cli = new Atom($elm->get_pcle('cli_id')->value);
        $cli_name = $cli->name;
        $cli_dom = $cli->get_pcle('domicilio')->value.' '.$cli->get_pcle('localidad')->value;
        $fecha = $rec->fecha_comprobante;
        $det_ids = (!empty($rec->detalle_events_id))?explode('|', $rec->detalle_events_id):false;
        $detalle = (!empty($det_ids))?$this->cmn_functs->mk_detalle_recibo($det_ids):false;
        $monto = $rec->monto;
        $intereses = $rec->intereses_monto;
        $saldo = $rec->saldo;

        $res=[
            'route'=>$this->route,
            'method'=>'print_recibo',
            'fecha_pago'=>$rec->fecha_comprobante,
            'nom_lote' =>$lote,
            'nom_cli'=>$cli_name,
            'recibo_nro'=>$rec_num,
            'concepto'=>$concepto,
            'detalle'=>$detalle,
            'monto'=>intval($monto),
            'intereses'=>$intereses,
            'saldo'=>$saldo,
            'elem_id'=>$elm->id,
            'caja_name'=> $caja
        ];
        $this->cmn_functs->resp('front_call',$res);
    }


            //  *********** DATOS PARA LA VENTANA DE DETALLE DE ULTIMOS MOVIMIENTOS *******
            function detalle_movs(){
                $p = $this->input->post('data');
                // $events = $this->app_model->get_arr("SELECT events_id as id FROM events_pcles where label = 'recibo_nro' AND value = {$p['id']}");
                $events = $this->app_model->get_arr("SELECT ep.events_id as id, cc.nombre as caja , cp.concepto as concepto FROM events_pcles ep LEFT OUTER JOIN comprobantes cp on ep.value = cp.nro_comprobante LEFT OUTER JOIN contab_asientos a on a.operacion_nro = cp.op_caja_nro LEFT OUTER JOIN contab_cuentas cc on cc.id = a.cuentas_id where label = 'recibo_nro' AND value = {$p['id']}");
                if(empty($events)){
                    $r = -1;
                }else{

                    //  RETORNA LOS PLCES DEL EVENT CON VIS_ELEM_TYPE > 0
                    $r = array_map(function($ev){
                        $e = new Event($ev['id']);
                        if(empty($e)) return -1;
                        $res = [];
                        // $res['Caja']= $ev['caja'];
                        $res['Caja']=($ev['caja'] != null )?$ev['caja']:'Debitado de cuenta';
                        // $res['Concepto'] = $ev['concepto'];
                        $ep_arr = $e->get_pcle();
                        foreach ($ep_arr as $ep) {
                            if($ep->vis_elem_type > 0)
                            $res[$ep->title] = $ep->value;
                        }

                        return $res;
                    },$events);
                }

                // ****** RESPONSE
                $res=[
                    'route'=>$this->route,
                    'method'=>'detalle_movs',
                    'action' =>'response',
                    'events'=>$r
                ];
                echo json_encode(
                    array(
                        'callback'=>'front_call',
                        'param'=>$res
                    )
                );

            }

            function detalle_recibo(){
                $p = $this->input->post('data');
                $r = $this->app_model->get_arr("SELECT a.fecha ,cta.nombre as cuenta,ci.nombre as operacion,a.monto, evp_trm.value as termino FROM `contab_asientos` a LEFT OUTER JOIN events_pcles evp on evp.value = a.nro_comprobante LEFT OUTER JOIN events_pcles evp_fp on evp_fp.events_id = evp.events_id and evp_fp.label = 'fec_pago' LEFT OUTER JOIN events_pcles evp_trm on evp_trm.events_id = evp.events_id and evp_trm.label = 'termino_pago' LEFT OUTER JOIN contab_cuenta_de_imputacion ci on ci.id = a.cuenta_imputacion_id LEFT OUTER JOIN contab_cuentas cta on cta.id = a.cuentas_id WHERE nro_comprobante = {$p['rec_id']} GROUP BY operacion ");


                // ****** RESPONSE
                $res=[
                    'route'=>$this->route,
                    'method'=>'detalle_recibo',
                    'action' =>'response',
                    'data'=>$r
                ];
                echo json_encode(
                    array(
                        'callback'=>'front_call',
                        'param'=>$res
                    )
                );

            }

            /*
            datos del recibo:
            NUMERO DE LOTE + CLIENTE
            En concepto de:
            CUOTA NUMERO X DE 36/120/84 (EL QUE FUERE) + VTO + IMPORTE DE INTERES = VALOR QUE
            SE COBRO POR ESA CUOTA
            CUOTAS ADELANTADAS NUMERO + VTO
            PRESTAMO: CUOTA NUMERO X DE X + VTO
            */



            //************* update 30 de junio 2020
            //**** SELECTOR DE ESTADO GENERAL
            //*************************************
            function set_curr_state(){
                $p = $this->input->post();
                // actualizo el pcle del contrato
                $e = new Element($p['elem_id']);
                  $e->pcle_updv($e->get_pcle('estado_contrato')->id,$p['value']);
                $this->cmn_functs->resp('front_call',['method'=>'set_curr_state','route'=>$this->route,'response'=>true,'msg'=>'OK :)']);
            }

            function rescindir_contrato(){
                $p = $this->input->post('data');
                /* post
                elements_types_id: "1"
                elm_id: "4533"
                fecha: "25/06/2020"
                mto_reintegro: "122307"
                reintegro_nro_op: "0"
                rscn_nro_compr: "0"
                rscn_tipo_id: "2"
                */

                // GET ATOM DEL LOTE
                $e = new Element($p['elm_id']);
                $l = new Atom($e->get_pcle('prod_id')->value);


                // RESET LOTE A DISPONIBLE
                $l->pcle_updv($l->get_pcle('estado')->id,'DISPONIBLE');
                $l->pcle_updv($l->get_pcle('in_contrato_id')->id,0);

                //  CREATE ATOM RESCISION
                $r = new atom(0,'RESCISION','R_'.$l->get_pcle('name')->value);
                $r->pcle_updv($r->get_pcle('fecha')->value,$p['fecha']);
                $r->pcle_updv($r->get_pcle('mto_reintegro')->value,$p['mto_reintegro']);
                $r->pcle_updv($r->get_pcle('rscn_tipo_id')->value,$p['rscn_tipo_id']);

                // CONVIERTE EL ELEMENT CONTRATO EN ELEMENT RESCINDIDO
                // cambia owner id / element types
                $e->set('owner_id',$r->id);
                $e->pcle_updv($e->get_pcle('prod_id')->value,$r->id);
                $e->pcle_updv($e->get_pcle('estado_contrato')->value,'RESCINDIDO');
                $this->app_model->update('elements',['elements_types_id'=>5],'id',$p['elm_id']);


                //   RESPONDER AL FRONT
                $aft_act = ['method'=>'back'];
                $r =[
                    'tit'=>'Rescision de contrato ',
                    'msg'=>'El Contrato del lote '.$l->get_pcle('name')->value.' fue rescindido. '  ,
                    'type'=>'warning',
                    'container'=>'modal',
                    'after_action'=>$aft_act
                ];
                $this->cmn_functs->resp('myAlert',$r);


            }


            // CREA MENSAJE CUANDO EL CAMBIO DE ESTADO ES EN_REVISION
            function new_revision(){
                $p = $this->input->post();
                // get elem id desde el nombre de lote
                $a = new Atom(0,"LOTE",$p['lote']);
                $e = new Element(0,"CONTRATO",$a->id);
                $this->app_model->insert('revision',['element_id'=> $e->id,'user_id'=>$p['user_id'],'asignado_a'=>$p['asignado_a'],'coment'=>$p['coment']]);
                $reportados_id = $this->app_model->db->insert_id();
                $h = new Historial($a->get_pcle('hist_id')->value);
                $h->update($p['user_id'],'reportados_id',$reportados_id,'EN_REVISION');
                $this->cmn_functs->resp('curr_state_change','EN_REVISION');
                // echo json_encode(array('callback'=> 'curr_state_change','param'=>'a_revisar'));
            }



            // *** marca como resuelto  el reportado mas viejo por que no tengo id del registro en reportados
            // clean revision debe ir solo en reportados que tiene el id del problema.
            function clean_revision($e){
                $rec = $this->app_model->get_obj("SELECT id FROM revision WHERE element_id = {$e} AND solucionado = 0 ORDER BY id ASC LIMIT 1");
                if(!empty($rec)){
                    $this->app_model->update('revision',array('solucionado'=>1),'id',$rec->id);
                }
            }


            //  NOT TO DEPRECATE
            function save_pcle(){
                $p = $this->input->post('data');
                $e = new Element($p['elem_id']);
                $e->pcle_updv($e->get_pcle($p['pcle'])->id,$p['pcle_val']);
                // $e->set_pcle(0,$p['pcle'],$p['pcle_val']);
                $r = [
                    'route'=>$this->route,
                    'method'=>'save_pcle',
                    'sending'=>false,
                    'action'=>'response',
                    'container_id'=>$p['container_id'],
                    'result'=> $e->get_pcle($p['pcle'])->value
                ];
                $this->cmn_functs->resp('front_call',$r);
            }


            // **** END ELEMENTS DEL RESUMEN DE CUENTA

            // *** FUNCIONES DE VERSION 2 DE FINANCIACION

            //*** OK ****

            // COLECTA LOS EVENTOS A PAGAR Y LOS COVIERTE END VENCIDOS SI SUPERAN
            // LA FECHA DE VENCIMIENTO RESPECTO DE HOY
            //*** 22/03/20 ****  TO DO ****
            // suspender el vencimiento de cuotas de Abril
            //

            function update_vencimientos($e){
                // USAR FAKE DATE 10 DE ABRIL PARA TESTEAR CUOTAS Y SERV
                // $fake_date = '2020-04-10';
                // $dt_now = new DateTime($fake_date);
                $dt_now = new DateTime(date('Y-m-d'));
                $f = $e->get_events(8,'a_pagar');
                foreach ($f['events'] as $xv) {
                    $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
                    $dt_diff = $dt_xv->diff($dt_now);
                    if($dt_diff->invert == 0){
                        if($dt_diff->days >= 25){
                            $this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
                        }
                    }
                }
            }



            function update_vencimientos_old($e){
                // USAR FAKE DATE 10 DE ABRIL PARA TESTEAR CUOTAS Y SERV
                // $fake_date = '2020-04-10';
                $dt_now = new DateTime($fake_date);
                // $dt_now = new DateTime(date('Y-m-d'));
                $f = $e->get_events(8,'a_pagar');
                foreach ($f['events'] as $xv) {
                    $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
                    $dt_diff = $dt_xv->diff($dt_now);
                    if($dt_diff->invert == 0){
                        if($dt_diff->days >= 25){
                            $this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
                        }
                    }
                }
            }

            // *******************************
            // NEW checkeo de actualizaciones de contrato

            function check_update_pending($elm_id){
                $r = false;
                $e = new Element($elm_id);
                //** datos de revision
                $aprv = intval($e->get_pcle('aplica_revision')->value);
                $frec_rev =intval($e->get_pcle('frecuencia_revision')->value);
                $cclo = intval($e->get_pcle('current_ciclo')->value);
                $cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);
                $ctas_restantes = $e->get_cant_ctas_restantes();

                // echo 'curr cclo:'.$cclo;
                // echo '   frec rev: '.$frec_rev;
                // echo '    ctas ciclo 2 :'.$cclo2;
                // echo '   ctas restantes; ' .$ctas_restantes;
                // echo '  mod result : '. ($ctas_restantes % $frec_rev);
                // // ** CAMBIO DE PLAN ** HAY DOS CICLOS, CURR CICLO ES 1 Y CTAS RESTANTES ES 0
                if($cclo == 1 && $ctas_restantes == 0){$r = true;}
                // REVISON DE PLAN UNICO CICLO
                if($cclo == 1 && $cclo2 == 0 && $ctas_restantes % $frec_rev == 0){$r = true;}
                // REVISION  DE CICLO UNO CON APLICA REV
                if($cclo == 1 && $aprv == 1 && $ctas_restantes % $frec_rev == 0){$r = true;}
                // REVISION DE CICLO 2
                if($cclo == 2 && $ctas_restantes % $frec_rev == 0){$r = true;}
                $e->pcle_updv($e->get_pcle('plan_update_pending')->id,$r);
            }


            //*** DEPRECATE ****
            // function revision_plan($e){
            //   $pmt = $e->get_last_payment();
            //   if(!empty($pmt)){
            //     $lp = new Event($e->get_last_payment()->id);
            //     $x = $lp->get_pcle('nro_cta')->value;
            //     if(preg_match_all('!\d+!', $x, $m)){
            //       $nc = intval($m[0][0]);
            //       $aprv = intval($e->get_pcle('aplica_revision')->value);
            //       $cclo = intval($e->get_pcle('current_ciclo')->value);
            //       $frec_rev = intval($e->get_pcle('frecuencia_revision')->value);
            //       // APLICAr REVISION EN CICLO 1 cuando "Aplic_rev" esta seleccionado o si es ciclo 2
            //       if($cclo == 1 && $aprv == 1 && $nc % $frec_rev === 0){
            //         $res=[
            //           'method'=>'set_cambio_financ_plan',
            //           'action' =>'response',
            //           'elem_id'=>$e->id,
            //           'last_fec_pago'=>$lp->get_pcle('fec_pago')->value,
            //           'last_monto_pagado'=>$lp->get_pcle('monto_pagado')->value
            //         ];
            //         $this->cmn_functs->resp('front_call',$res);
            //         exit();
            //       }elseif($cclo == 2 && $nc % $frec_rev == 0){
            //         $res=[
            //           'method'=>'set_cambio_financ_plan',
            //           'action' =>'response',
            //           'elem_id'=>$e->id,
            //           'last_fec_pago'=>$lp->get_pcle('fec_pago')->value,
            //           'last_monto_pagado'=>$lp->get_pcle('monto_pagado')->value
            //         ];
            //         $this->cmn_functs->resp('front_call',$res);
            //         exit();
            //       }
            //     }
            //   }
            // }

            //*** DEPRECATE **** CHECKEA EL CAMBIO DE CILO DEL CONTRATO
            // function cambio_de_ciclo($e){
            //   $pmt = $e->get_last_payment();
            //   if(!empty($pmt)){
            //     $lp = new Event($pmt->id);
            //     $x = $lp->get_pcle('nro_cta')->value;
            //     if(preg_match_all('!\d+!', $x, $m)){
            //       if(intval($m[0][0]) === intval($m[0][1]) ){
            //         $e->set_pcle($e->get_pcle('current_ciclo')->id,'current_ciclo',2,'',-1);
            //         return true;
            //       }
            //     };
            //     return false;
            //   }
            // }

            // checkEA NUMERO DE CUOTAS
            function check_nro_de_cuota($x,$tc,$cclo2){
                $cclo = 1;
                if($cclo2 > 0 && $x <= ($tc-$cclo2)){
                    $tc = $tc-$cclo2;
                }elseif($cclo2 > 0 && $x > ($tc-$cclo2)){
                    $cclo = 2;
                    $x = $x - ($tc-$cclo2);
                    $tc = $cclo2;
                }
                return ['num'=>$x,'tot'=>$tc,'ciclo'=>$cclo];
            }

            //************************
            // NEW NOMBRE DEL PLAN
            function get_plan_name($e){
                $c = intval($e->get_pcle('cant_ctas')->value);
                $curr_cclo = intval($e->get_pcle('current_ciclo')->value);
                $cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);

                $user = $this -> session -> userdata('logged_in');


                if($user['user_id'] == 484 ){
                    // $indac = $this->cmn_functs->verif_indac($e->id);
                    $indac = intval($e->get_pcle('indac')->value);

                }else{
                    $indac = intval($e->get_pcle('indac')->value);
                }

                $intr = intval($e->get_pcle('interes')->value);

                if($curr_cclo == 1){
                    return ($c-$cclo2).' Ctas. Actualización '.$indac.' %';
                }
                if($curr_cclo == 2){
                    return $cclo2 . " Ctas. Actualización ".$indac. ' %';
                }
            }



            // *** END FUNCIONES DE VERSION 2 DE FINANCIACION


            // **** VERSION 2 DE GESTION DE PAGOS

            //  INGRESO DE PAGO
            // *********** HAY UN BUG ->
            /*************
            si falla algun paso intermedio en el guardado de las tablas
            la operacion queda a la mitad por que guarda en contab_asientos y en events, pero no en comprobantes, por ejemplo. tambien puede haber mas versiones del mismo fallo segun donde se de en la funcion el faltante de datos o var no reconocido.
            habria que hacer primero un checkeo de toda la data a guardar y una ves validado esto hacer el ingreso en las tablas del db en una transaccion cuestion que si falla alguna se revierte todo.
            ************ */

            function ingresar_pago($p=null){
                if(empty($p)){
                    $p = $this->input->post('data');
                }

                // ***** CHECK NRO DE RECIBO
                $rec_num = $this->cmn_functs->get_recnum();

                //  CODIGO DEL LOTE

                // **** SET CUENTA DE IMPUTACION
                $imp_id = $this->app_model->get_cuenta_de_imputacion_by_name('COBRO DE CUOTA');
                if(!$imp_id){
                    $r =[
                        'route'=>$this->route,
                        'method'=>'ingresar_pago',
                        'action'=>'response',
                        'result'=>'FAIL',
                        'tit'=>'Error',
                        'msg'=>'No se puede ingresar el pago, Error en la cuenta de imputacion',
                        'type'=>'danger',
                        'container'=>'#modal-footer-msgs',
                        'extra'=>'hide_modal',
                        // 'after_action'=>'light_back'
                    ];
                    $this->cmn_functs->resp('front_call',$r);
                    exit();
                }
                // **** FILL DE DATOS PARA EL ASIENTO
                $c = [
                    'nro_comprobante'=> $rec_num,
                    'cuentas_id' => $p['contab_cuenta'],
                    'cliente_id'=>$p['cliente_id'],
                    'lote_id'=>$p['lote_id'],
                    'operador_usuario_id'=>$p['user_id'],
                    'origen'=>'procesar_pagos',
                    'tipo_asiento'=>'INGRESOS',
                    'cuenta_imputacion_id' => $imp_id->id,
                    'monto'=> intval($p['monto_recibido']),
                    'observaciones'=>(array_key_exists('observaciones', $p))?$p['observaciones']:''
                ];

                $ccd[] = ['barrio_id'=> $p['barrio_id'],'percent'=> "100"];

                $op_nro = ($p['contab_cuenta'] > 0 )?$this->cmn_functs->mk_asiento_caja($c,$ccd):-1;

                // **** SET DEL EVENTO PAGO INGRESADO PARA EL COMPROBANTE DE PAGO
                $ev_p = new Event(0,3,$this->cmn_functs->fixdate_ymd($p['fec_pago']),$p['contrato_id']);
                $ev_p->set('ord_num',-1);

                $ev_p->set_pcle(0,'fecha_pago',$p['fec_pago'],'Fecha de Pago',1);
                $ev_p->set_pcle(0,'op_caja_nro',$op_nro,'Operac. Nro.',1);
                $ev_p->set_pcle(0,'monto',$c['monto'],'Monto',1);
                $ev_p->set_pcle(0,'recibo_nro',$c['nro_comprobante'],'Nro. Recibo',1);
                $ev_p->set_pcle(0,'estado','OK','Estado',99);

                // ***** INSERT DE DATOS TABLA COMPROBANTE
                //  update $table,$data,$ikey,$id
                $this->app_model->update(
                    'comprobantes',
                    [
                        'fecha_comprobante'=>$this->cmn_functs->fixdate_ymd($p['fec_pago']),
                        'tipo_comprobante'=>'RECIBO',
                        'monto'=>intval($p['monto_recibido']),
                        'intereses_monto'=>0,
                        'saldo'=>intval($p['saldo'])+intval($p['monto_recibido']),
                        'concepto'=>'PAGO INGRESADO',
                        'detalle_events_id'=>$ev_p->id,
                        'id_usuario'=>$p['user_id'],
                        'elements_id'=>$p['contrato_id'],
                        'op_caja_nro'=>$op_nro

                    ],
                    'nro_comprobante',
                    $rec_num
                );
                //  SI LA LLAMADA  VIENE DE ROUTER.JS no_response NO EXITE
                // SI PAGO LO ESOTY LLAMANDO DESDE ACTUALIZACION DE CONTRATO no_response ES TRUE
                if(!array_key_exists('no_response', $p)){
                    $r = [
                        'route'=>$this->route,
                        'method'=>'ingresar_pago',
                        'action'=>'response',
                        'result'=> 'OK',
                        'msg'=> 'Nro de Operación: '.$op_nro,
                        'tit'=>'Pago Ingresado Correctamente ',
                        'type'=>'success',
                        'container'=>'modal',
                        'extra'=>'no_autohide',
                        'last_mov'=> $this->get_last_movs($p['contrato_id'])
                    ];
                    $this->cmn_functs->resp('front_call',$r);
                }

            }

            //*** IMPUTACION DE CUOTAS
            function procesar_pago_cuota(){
                $p = $this->input->post('data');
                // ***** CHECK NRO DE RECIBO
                $rec_num = $this->cmn_functs->get_recnum();
                // SALIDA SI FALLA EL NUMERO DE RECIBO
                if(!$rec_num){
                    $r =[
                        'route'=>$this->route,
                        'tit'=>'Error',
                        'msg'=>'No se puede procesar el pago, falló la generación del Numero de Recibo',
                        'type'=>'danger',
                        'container'=>'modal',
                        'extra'=>'no_autohide'
                    ];
                    $this->cmn_functs->resp('myAlert',$r);
                    exit();
                }
                $concepto = '';
                // GENERO UN RECIBO REGISTRANDO LAS IMPUTACIONES EN COMPROBANTES
                // ARRAY PARA HACER LA TABLA DE DETALLES
                $det_evnts_id = [];
                // CUOTAS DE LOTES
                if(intval($p['tot_mto_ctas'])>0){
                    $p_lote = array_filter($p['selected'],function($i){return $i['tipo'] == 'cta_lote';});
                    $concepto .= $this->make_observac_pago($p_lote,'lote');//(count($p_lote)>1)?'Cuotas Lote, ':'Cuota Lote, ';
                    // $c['observaciones'] .= $this->make_observac_pago($p_lote,'lote');
                    $det_ev_ctas = $this->set_pago_ev($p_lote,$p,$rec_num);
                }
                // CUOTAS DE SERVICIOS
                if(intval($p['tot_mto_srvc'])>0){
                    $p_srvc = array_filter($p['selected'],function($i){return $i['tipo'] == 'cta_srvc';});
                    $concepto .= $this->make_observac_pago($p_srvc,'srvc');//(count($p_srvc)>1)?' Cuotas Servicios,':' Cuota Servicio, ';
                    // $c['observaciones'] .= $this->make_observac_pago($p_srvc,'srvc');
                    $det_ev_srvc =  $this->set_pago_ev($p_srvc,$p,$rec_num);
                }
                //*** MERGE ARRAY GUARDAR Y CONSTRUIR DETALLE
                $detalle =[];
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
                // CHECKEO REFINANCIACION_ESPECIAL
                if(!empty($detalle)){
                    foreach ($detalle as $d) {
                        $this->cmn_functs->check_close_refi($d);
                    }
                }


                // GUARDO EN COMPROBANTES
                $dtl = (count($detalle)>0)?implode('|', $detalle):0;
                //  update $table,$data,$ikey,$id
                $this->app_model->update(
                    'comprobantes',
                    [
                        'fecha_comprobante'=>$this->cmn_functs->fixdate_ymd($p['fec_pago']),
                        'tipo_comprobante'=>'RECIBO',
                        'monto'=>intval($p['total_imputado']),
                        'intereses_monto'=>intval($p['tot_mto_intrs']),
                        // ** saldo es el saldo anterior menos el monto imputado
                        'saldo'=>intval($p['saldo']),
                        'concepto'=>$concepto,
                        'detalle_events_id'=>$dtl,
                        'id_usuario'=>$p['user_id'],
                        'elements_id'=>$p['contrato_id'],
                        'op_caja_nro'=> -1
                    ],
                    'nro_comprobante',
                    $rec_num
                );
                $dtl_tbl = (count($detalle)>0)?$this->cmn_functs->mk_detalle_recibo($detalle):0;

                $nom_lote = new Atom($p['lote_id']);
                $nom_cli = new Atom($p['cliente_id']);
                $res=[
                    'route'=>$this->route,
                    'method'=>'print_recibo',
                    'after_action'=>json_encode(['method'=>'get_elements','sending'=>true,'data'=>['elm_id'=>$p['contrato_id']]]),
                    'fecha_pago'=>$this->cmn_functs->fixdate_ymd($p['fec_pago']),
                    'nom_lote' =>$nom_lote->name,
                    'nom_cli'=>$nom_cli->name,
                    'recibo_nro'=>$rec_num,
                    'concepto'=>$concepto,
                    'detalle'=>$dtl_tbl,
                    'monto'=>intval($p['total_imputado']),
                    'intereses'=>$p['tot_mto_intrs'],
                    'elem_id'=>$p['contrato_id'],
                    'caja_name'=> 'debitado de cuenta ',
                    // ** saldo es elsaldo anterior menos el monto imputado
                    'saldo'=>intval($p['saldo']),
                ];
                $this->cmn_functs->resp('front_call',$res);

            }

            //*** *set_pago  PREPARA DATOS PARA SCREEN DE IMPUTACION PAGO DE CUOTAS
            function set_pago_cuotas(){
                // TOMA DATOS DEL POST
                $p=$this->input->post();
                // LLAMAR A UPDATE_PLAN SE ESTA PENDIENTE DE REVISION
                $ctas = $this->cmn_functs->set_pago_de_cuotas($p['e_id']);
                // ****** RESPONSE
                $res=[
                    'route'=>$this->route,
                    'selects'=>['cuentas'=>$this->cmn_functs->get_cuentas_dpdown_data($this->usr_obj->permisos_usuario)],
                    'method'=>'set_pago_cuotas',
                    'action' =>'response',
                    'adls'=>(!empty($ctas['adls']))?$ctas['adls']:[],
                    'cuotas'=>(!empty($ctas['a_pagar']))?$ctas['a_pagar']:[],
                    'servicios'=>(!empty($ctas['srv']))?$ctas['srv']:[],
                    'srv_register'=>(!empty($ctas['srv_register']))?$ctas['srv_register']:[],
                    'srv_adls'=>(!empty($ctas['srv_adls']))?$ctas['srv_adls']:[],
                    'saldo_int'=> $ctas['saldo_int'],
                    'update_pending'=>$ctas['update_pending']
                ];
                $this->cmn_functs->resp('front_call',$res);
            }
            // **** END DE VERSION 2 DE GESTION DE PAGOS *********

            //****** VERSION 1 DE GESTION DE PAGOS ****
            function set_pac_ev($p,$c){

                $c['observaciones'] = 'Pago A Cuenta';
                $imp_id = $this->app_model->get_cuenta_de_imputacion_by_name('PAGO A CUENTA');
                $c['cuenta_imputacion_id'] = (!empty($imp_id)?$imp_id->id:0);
                $ccd[] = ['barrio_id'=> $p['barrio_id'],'percent'=> "100"];
                $op_nro=$this->cmn_functs->mk_asiento_caja($c,$ccd);
                // ACTUALIZA O INSERTA EL SALDO
                $el = new Element($p['contrato_id']);
                $saldo_prev = $el->get_pcle('saldo');
                if(!empty($saldo_prev)){
                    $new_saldo = intval($saldo_prev->value)+intval($c['monto']);
                    $el->set_pcle(0,'saldo',$new_saldo);
                }else{
                    $el->set_pcle(0,'saldo',$c['monto']);
                }
                //*** EVENTO DEL PAGO A CUENTA
                $ev_pac = new Event(0,3,$this->cmn_functs->fixdate_ymd($p['fec_pago']),$p['contrato_id']);
                $ev_pac->set_pcle(0,'fecha_pago',$p['fec_pago'],'Fecha de Pago',1);
                $ev_pac->set_pcle(0,'op_caja_nro',$op_nro,'Operac. Nro.',1);
                $ev_pac->set_pcle(0,'monto',$c['monto'],'Monto',1);
                $ev_pac->set_pcle(0,'recibo_nro',$c['nro_comprobante'],'Nro. Recibo',1);
                return ['ev_id'=>$ev_pac->id,'op_nro'=>$op_nro];
            }


            //***  ACTUALIZA LOS EVENTS CON LOS DATOS DEL PAGO LO USA VERSION 2 DE PAGO
            function set_pago_ev($evs_arr,$p,$rec_num){
                $evs_updated =[];
                //***** ACTUALIZO EVENTS
                foreach ($evs_arr as $pg) {
                    $ev = new Event($pg['events_id']);
                    // ACTUALIZA CUOTAS RESTANTES EN EL ELEMENT CONTRATO
                    $elm = new Element(intval($ev->get('elements_id')));
                    $ctasrest = $elm->get_pcle('cant_ctas_restantes');
                    $elm->pcle_updv($ctasrest->id,(intval($ctasrest->value)-1));
                    //*** CONTROLA EL NUMERO DE CUOTA Y PONE PLAN_UPDATE_PENDING EN TRUE SI ES EL NUEMRO DE REVISION DE PLAN
                    $this->handler_update_plan($elm);
                    // ********
                    // ***  SETEA EL ID DEL TERMINO DEL EVENTO CON 4 VENCIDO/PAGADO O 6 ADELANTADO
                    $t = $this->set_event_type_id($pg['termino'],$ev);
                    $ev->set_pcle(0,'estado', 'pagado');
                    $ev->set_pcle(0,'fec_pago',$p['fec_pago'],'Fecha de Pago',1);
                    $ev->set_pcle(0,'monto_pagado',$pg['tot_cta'],'Monto Pagado',1);
                    $ev->set_pcle(0,'recibo_nro',$rec_num,'Nro. Recibo',1);
                    if(intval($pg['interes_mora']) > 0){
                        $ev->set_pcle(0,'estado','p_ftrm');
                        $ev->set_pcle(0,'dias_mora',$pg['dias_mora'],'Dias Mora',1);
                        $ev->set_pcle(0,'interes_mora',$pg['interes_mora'],'Interes',1);
                    }
                    $evp = $ev->id;
                    $evs_updated[] = $ev->id;
                }
                return $evs_updated;
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
                        $r .= 'Debito Ctas. '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
                    }elseif(count($cpn) === 1){
                        $kin = array_shift($cpn);
                        if($kin['nro_cta'] === 'Anticipo'){
                            $r .= 'Debito Anticipo ';
                        }else{
                            $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                            $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                            $r .= 'Debito Cta. '.$cta_init.' de'.$tot_ctas;
                        }
                    }
                    if(count($cpadl) > 1){
                        $kin = array_shift($cpadl);
                        $kout = array_pop($cpadl);
                        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
                        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                        $r .= 'Debito Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
                    }elseif(count($cpadl) === 1){
                        $kin = array_shift($cpadl);
                        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                        $r .= 'Debito Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
                    }
                    break;
                    case 'srvc':
                    if(count($cpn) > 1){
                        $kin = array_shift($cpn);
                        $kout = array_pop($cpn);
                        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
                        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                        $r .= "Debito ".substr($kin['srv_name'], 0,20).' Cuotas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
                    }elseif(count($cpn) === 1){
                        $kin = array_shift($cpn);
                        if($kin['nro_cta'] === 'Anticipo'){
                            $r .= "Debito ".substr($kin['srv_name'], 0,20).' Anticipo';
                        }else{
                            $cta_init = ($kin['nro_cta'] == 'Anticipo')?$kin['nro_cta']:str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                            $tot_ctas = ($kin['nro_cta'] == 'Anticipo')?' ':substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                            $r .="Debito ".substr($kin['srv_name'], 0,20).' Cta. '.$cta_init.' de'.$tot_ctas;
                        }
                    }
                    if(count($cpadl) > 1){
                        $kin = array_shift($cpadl);
                        $kout = array_pop($cpadl);
                        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                        $cta_fin = str_replace('d', '', substr($kout['nro_cta'], strpos($kout['nro_cta'], ' '),4));
                        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                        $r .="Debito ".$kin['srv_name'].' Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
                        // $r .=" ".substr($kin['srv_name'], 0,20).'Debito Ctas. Adelantadas '.$cta_init.' a'.$cta_fin.' de'.$tot_ctas;
                    }elseif(count($cpadl) === 1){
                        $kin = array_shift($cpadl);
                        $cta_init = str_replace('d', '', substr($kin['nro_cta'], strpos($kin['nro_cta'], ' '),4));
                        $tot_ctas = substr($kin['nro_cta'], strpos($kin['nro_cta'], 'de')+2);
                        $r .= "Debito ".$kin['srv_name'].' Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
                        // $r .= " ".substr($kin['srv_name'], 0,20).'Debito Cta. Adelantada '.$cta_init.' de'.$tot_ctas;
                    }
                    break;
                }
                return $r;
            }

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


            // **** CREA CUOTAS VERSION 2 OK
            function create_cuotas_new_v2($elm){
                // $tr= []; ARRAY PARA TESTEOS
                //  CONTROLA CANTIDAD DE CUOTAS CREADAS Y LO GUARDA EN CUOTAS RESTANTES
                $ctas_restantes = 0;
                // CONTROLA CTAS REFUERZO SI HAY PARA DAR EL NUMERO DE CUOTA EN LA DESCRIPCION
                $ctas_refuerzo_creadas = 0;

                //***  GENERA CUOTA ANTICIPO (CUOTA CERO) el campo anticipo es el monto
                $ant = intval($elm->get_pcle('anticipo')->value);
                if($ant > 0){
                    $lyc = 'Cuota Anticipo';
                    // $tr[]=['cuota'=>$lyc,'ordnum'=>0,'fec_ven'=>$elm->get_pcle('fec_ini')->value,'monto'=>$ant,'elm'=>$elm->id];
                    $this->set_new_cuota($elm->id,8,$ant,date('d/m/Y'),0,$lyc);
                }
                //*****
                //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
                $fec_cta1 = $elm->get_pcle('fec_ini')->value;
                $nfi = '10'.substr($fec_cta1, 2);
                $f1 = $this->cmn_functs->fixdate_ymd($nfi);
                $fv = new DateTime($f1);
                //******

                $totm = intval($elm->get_pcle('monto_total')->value);
                $cta_1= intval($elm->get_pcle('monto_cta_1')->value);
                $interes = intval($elm->get_pcle('interes')->value);
                $ctas = intval($elm->get_pcle('cant_ctas')->value);
                //*** MONTO DE CUOTA 1
                $monto_cta = ($cta_1 <= 0)?intval($totm / $ctas) : intval($cta_1);


                // CONTROLA INTERES % SI ES MAYOR A CERO CALCULA EL INTERES MENSUAL PARA APLICARLO A LAS CUOTAS A CREAR QUE SON FIJAS
                if($interes > 0){
                    $monto_cta = intval(($monto_cta*($interes/100)+$monto_cta));
                    // $tr[]=['aplica_interes'=>$interes];
                }

                //*** CANTIDAD TOTAL GENERAL DE CUOTAS
                $tgc = intval($elm->get_pcle('cant_ctas')->value);

                $cclo2 = intval($elm->get_pcle('cant_ctas_ciclo_2')->value);

                if($cclo2 > $tgc){exit('error en la cantidad de cuotas');}
                // ajusta ciclo 1  en base a total cuotas menos la cant de cuotas en ciclo 2
                if($cclo2 > 0){
                    $tc = $tgc - $cclo2;
                }else{
                    $tc = $tgc;
                }

                for ($i=1; $i <= $tc; $i++){
                    // FECHA DE VENCIMIENTO
                    $curr_fec_ven = $fv->format('d/m/Y');
                    //*** OBTENGO NUMERO DE CTA ACTUAL TOTAL DE CUOTAS DE CADA CICLO Y CICLO ACTUAL
                    $nro_cta = $this->check_nro_de_cuota($i,$tgc,$cclo2);
                    $lyc = "Cuota ".$nro_cta['num'].' de '.$nro_cta['tot'];
                    $curr_cclo = $nro_cta['ciclo'];


                    // $tr[]=['cuota'=>$lyc,'ordnum'=>$i,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
                    $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$i,$lyc);
                    $ctas_restantes ++;

                    // APLICA CUOTA REFUERZO SI HAY
                    //*** EVALUA SI HAY CUOTAS REFUERZO Y LA APLICA SI EL MULTIPLO DEL MES ES EL CORRIENTE MES
                    $rfz = intval($elm->get_pcle('frecuencia_ctas_refuerzo')->value);
                    if($rfz > 0 && $i % $rfz === 0){
                        $ctas_refuerzo_creadas ++;
                        $lyc = 'Cuota Refuerzo '.$ctas_refuerzo_creadas;
                        $ord_num = floatval($i)+0.1;
                        $tr[]=['cuota'=>$lyc,'ordnum'=>$ord_num,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
                        $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$ord_num,$lyc);
                        $ctas_restantes ++;
                    }
                    //*************  ACCIONES SOBRE CADA CUOTA ****************
                    // INCREMENTA EL MONTO NOMINAL DE CUOTAS SI HAY INDAC
                    // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
                    $indac = intval($elm->get_pcle('indac')->value);
                    $frec_indac = intval($elm->get_pcle('frecuencia_indac')->value);
                    if($indac > 0 && $frec_indac > 0){
                        if($i > 1 && $i % $frec_indac === 0){
                            $monto_cta = round($monto_cta * $indac / 100 + $monto_cta);
                        }
                    }
                    // INCREMENTO DEL MES DE FV
                    $fv->modify('next month');
                }
                //***  FIN LOOP CREACION DE CUOTAS VERSION 2
                // setea el ciclo actual
                $elm->pcle_updv(0,'current_ciclo',1,'',-1);
                // GUARDA LA CANTIDAD DE CUOTAS RESTANTES EN EL CONTRATO
                $elm->set_pcle(0,'cant_ctas_restantes',$ctas_restantes,'',-1);
            }

            // **** NEW UPDATE CUOTAS PARA UPDATE de contrato param ($elem,$new_fec,$new_monto);
            function update_cuotas($elm_id,$new_fec,$new_monto){

                $elm = new Element($elm_id);
                $indac = intval($elm->get_pcle('indac')->value);
                $frec_indac = intval($elm->get_pcle('frecuencia_indac')->value);

                //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
                $nfi = '10'.substr($new_fec, 2);
                $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
                //******
                //*** CANTIDAD DE CUOTAS EN EL CONTRATO
                $cant_ctas = intval($elm->get_pcle('cant_ctas_restantes')->value);
                if($cant_ctas <= 0){$cant_ctas = 1;}
                //*** MONTO DE CUOTA 1
                $monto_cta = intval($new_monto);
                //*** MODIFICA TODAS LAS CUOTAS RESTANTES NO VENCIDAS
                $c = $elm->get_events(8,'a_pagar');
                foreach ($c['events'] as $event) {
                    $ev = new Event($event['id']);
                    //  UPDATE PROPERTY DATE
                    $ev->set('date',$fv->format('Y-m-d'));
                    // UPDATE PCLES  MONTO Y FECHA DE VENCIMIENTO
                    $ev->set_pcle($ev->get_pcle('fecha_vto')->id,'fecha_vto',$fv->format('d/m/Y'));
                    $ev->set_pcle($ev->get_pcle('monto_cta')->id,'monto_cta',$monto_cta);
                    // ESTOY EN MULTIPLO DE FREC_INDAC, APLICO EL AUMENTO A LA CUOTA
                    if($indac > 0 && $frec_indac > 0){
                        $i = intval($ev->get('ord_num'));
                        if($i > 1 && $i % $frec_indac === 0){
                            $monto_cta = round($monto_cta * $indac / 100 + $monto_cta);
                        }
                    }
                    // $i ++;
                    // INCREMENTO DEL MES DE FV
                    $fv->modify('next month');
                }
            }

            // **** NEW UPDATE CUOTAS PARA UPDATE de contrato param ($elem,$new_fec,$new_monto);
            function update_cuotas_servicios($elm_id,$new_fec,$new_monto,$new_cant_ctas_restantes){
                $elm = new Element($elm_id);
                //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
                $nfi = '10'.substr($new_fec, 2);
                $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
                //******
                //*** CUOTAS PAGADAS PREVIAMENTE
                $pmt = $elm->get_last_payment();
                $cpagas = intval($pmt->get('ord_num'));

                //*** Borrar todas las cuotas impagas vencidas
                $c = $elm->get_events_id_by_state('a_pagar');
                foreach ($c as $x) {
                    $ev=new Event($x['id']);
                    $ev->kill();
                }

                // OBTENGO EL NUMERO DE CUOTA DESDE EL CUAL VOY A ACTUALIZAR EL PLAN
                $initev = $cpagas+1;
                $finev = $new_cant_ctas_restantes;

                //*** MONTO DE CUOTA 1
                $monto_cta = intval($new_monto);


                //***** new cuotas
                $indac = intval($elm->get_pcle('indac')->value);
                $frec_indac = intval($elm->get_pcle('frecuencia_indac')->value);
                for ($i=$initev; $i <= $finev; $i++){
                    // FECHA DE VENCIMIENTO
                    $curr_fec_ven = $fv->format('d/m/Y');
                    //*** actualiza NUMERO DE CTA
                    $lyc = "Cuota ".$i.' de '.$new_cant_ctas_restantes;

                    $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$i,$lyc);
                    //*************  ACCIONES SOBRE CADA CUOTA ****************
                    // INCREMENTA EL MONTO NOMINAL DE CUOTAS SI HAY INDAC CUANDO ES MULTIPLO DE INDAC FREQ
                    if($indac > 0 && $frec_indac > 0){
                        if($i > 1 && $i % $frec_indac === 0){
                            $monto_cta = round($monto_cta * $indac / 100 + $monto_cta);
                        }
                    }
                    // INCREMENTO DEL MES DE FV
                    $fv->modify('next month');
                }
            }


            // **** CREA CUOTAS PARA EL CICLO 2 CUANDO HUBO CAMBIO DE PLAN
            function create_cuotas_de_ciclo2($elm_id,$new_fec_ini){
                $elm = new Element($elm_id);

                //*****
                //*** PONE A 10 DEL MES LA FECHA PARA TENER FECHA DE VENCIMIENTO
                $nfi = '10'.substr($new_fec_ini, 2);
                $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
                //******

                $totm = intval($elm->get_pcle('monto_total')->value);
                $monto_cta= intval($elm->get_pcle('monto_cta_1')->value);
                $ctas = intval($elm->get_pcle('cant_ctas_ciclo_2')->value);



                for ($i=1; $i <= $ctas; $i++){
                    // FECHA DE VENCIMIENTO
                    $curr_fec_ven = $fv->format('d/m/Y');
                    //*** OBTENGO NUMERO DE CTA ACTUAL TOTAL DE CUOTAS DE CADA CICLO Y CICLO ACTUAL
                    $nro_cta = $this->check_nro_de_cuota($i,$ctas,$ctas);
                    $lyc = "Cuota ".$nro_cta['num'].' de '.$nro_cta['tot'];
                    $curr_cclo = $nro_cta['ciclo'];


                    // $tr[]=['cuota'=>$lyc,'ordnum'=>$i,'fec_ven'=>$curr_fec_ven,'monto'=>$monto_cta,'elm'=>$elm->id];
                    $this->set_new_cuota($elm->id,8,$monto_cta,$curr_fec_ven,$i,$lyc);

                    //*************  ACCIONES SOBRE CADA CUOTA ****************
                    // INCREMENTA EL MONTO NOMINAL DE CUOTAS SI HAY INDAC
                    // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
                    $indac = intval($elm->get_pcle('indac')->value);
                    $frec_indac = intval($elm->get_pcle('frecuencia_indac')->value);
                    if($indac > 0 && $frec_indac > 0){
                        if($i > 1 && $i % $frec_indac === 0){
                            $monto_cta = round($monto_cta * $indac / 100 + $monto_cta);
                        }
                    }
                    // INCREMENTO DEL MES DE FV
                    $fv->modify('next month');
                }

                //***  FIN LOOP CREACION DE CUOTAS DEL CICLO 2
            }





            // **** DEPRECATED CONTRATO Y CUOTAS version 1
            function create_cuotas_new_version1($cant_ctas,$mto_cta,$fec_init,$indac,$frec_indac,$elm_id,$anticipo='false',$mto_anticipo=0){
                if($anticipo === true && $mto_anticipo > 0){
                    $this->set_new_cuota($elm_id,8,$mto_anticipo,$fec_init,0,'Anticipo');
                }
                $nfi = '10'.substr($fec_init, 2);
                $fv = new DateTime($this->cmn_functs->fixdate_ymd($nfi));
                for ($i=1; $i <= intval($cant_ctas); $i++){
                    $index_ctas = $i;
                    // FECHA DE MES ANTERIOR MAS UN MES
                    $curr_fec_ven = $fv->format('d/m/Y');
                    // LEYENDA DE NUMERO DE CUOTAS
                    $lyc = 'Cuota '.$i.' de '.intval($cant_ctas);

                    $this->set_new_cuota($elm_id,8,$mto_cta,$curr_fec_ven,$i,$lyc);

                    //*************  POST SAVE ACTIONS ****************
                    // INCREMENTA EL MONTO NOMINAL DE CUOTAS
                    // APLICACION DE INTERES SEMESTRAL

                    // ESTOY EN MULTIPLO DE $AP_INT, APLICO EL AUMENTO A LA CUOTA
                    if(intval($indac) > 0 && intval($frec_indac) > 0){
                        if($i > 1 && $i % $frec_indac == 0){
                            $mto_cta = round($mto_cta * $indac / 100 + $mto_cta);
                        }
                    }
                    // INCREMENTO DEL MES DE FV
                    $fv = $this->set_cta_next_date($fv,$elm_id);
                    // $fv->modify('next month');
                }
                return true;
            }

            //****** DATA STRUCT DE LA CUOTA
            function set_new_cuota($elm_id,$ev_type,$monto,$fec_vto,$ord_num,$nro_cta){
                $evnt = new Event(0,$ev_type,$fec_vto,$elm_id,$ord_num);
                $evnt->set_pcle(0,'monto_cta',$monto,'Monto Cuota',1);
                $evnt->set_pcle(0,'fecha_vto',$fec_vto,'Fecha Vto.',1);
                $evnt->set_pcle(0,'estado','a_pagar','',-1);
                $evnt->set_pcle(0,'nro_cta',$nro_cta,'Nro. Cuota',1);
                $evnt->set_pcle(0,'monto_pagado',0,'Monto Pagado',1);
                $evnt->set_pcle(0,'fec_pago','-','Fecha de Pago',1);

            }



            // TO DEPRECATE ***** LEYENDA NUMERO DE CUOTA
            function get_lyc($n,$t){
                if($t > 120){
                    $cc = 36;
                    $i=$n;
                    if($i > 36){
                        $i = $n-36;
                        $cc = 120;
                    }
                }else{
                    $i = $n;
                    $cc = $t;
                }
                return 'Cuota '.$i.' de '.$cc;
            }


            //  *** DEPRECATE
            public function OLD_upd_futr_past($e){
                //****  CHECKEO SI ESTA EN EL CAMBIO DE CICLO
                if($this->get_cambio_financ_plan($e)){
                    // $e->clean_a_pagar_events();
                    $ev_lpay = $e->get_last_payment();
                    if(!empty($ev_lpay)){
                        $res=[
                            'method'=>'set_cambio_financ_plan',
                            'action' =>'response',
                            'elem_id'=>$e->id,
                            'last_fec_pago'=>$ev_lpay->get_pcle('fec_pago')->value,
                            'last_monto_pagado'=>$ev_lpay->get_pcle('monto_pagado')->value
                        ];
                        $this->cmn_functs->resp('front_call',$res);
                    }
                    exit();
                }
                // ANTES DE IMPLEMENTAR CHECK_REV_PLAN HAY QUE REHACER REV_PLAN
                //  QUE DEBE CAMBIAR A REFINANCE PLAN
                // $rev_fp = $this->check_rev_fplan($e);
                // if(!empty($rev_fp)){
                //   $res=[
                //     'method'=>'set_revision_fplan',
                //     'action' =>'response',
                //     'data'=>$rev_fp
                //   ];
                //   $this->cmn_functs->resp('front_call',$res);
                //   exit();
                // }

                $dt_now = new DateTime(date('Y-m-d'));
                $f = $e->get_events(8,'a_pagar');
                foreach ($f['events'] as $xv) {
                    $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
                    $dt_diff = $dt_xv->diff($dt_now);
                    if($dt_diff->invert == 0){
                        if($dt_diff->days >= 25){
                            $this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
                        }
                    }
                }
            }


            // (OLD) OBTIENE TRUE / FALSE SI ESTA PAGA LA CUOTA 36 DEL CICLO ANTICIPO
            function get_cambio_financ_plan($e){
                $cp = $e->get_ctas_pagas();
                $plan = $e->get_plan();

                $ftype = (new Atom((new Atom($e->get_pcle('financ_id')->value))->get_pcle('financ_type')->value))->name;
                if(count($cp['events']) == 36 || count($cp['events']) == 48){
                    if(strstr($ftype,'CICLO 1') > -1){
                        return true;
                    }
                }
                else{
                    return false;
                }
            }


            // OLD  TEST SI ESTA EN LA CUOTA DE REVISION
            public static function check_rev_fplan($e){
                $last_pay = $e->get_last_payment();
                $pcl_lr = $e->get_pcle('last_rev_num');
                $lr_num = (!empty($pcl_lr))?$pcl_lr->value:0;
                $f = $e->get_pcle('financ_id');
                if(!empty($f) && !empty($last_pay)){
                    $fn = new Atom($f->value);
                    $fr = $fn->get_pcle('frecuencia_revision');

                    if(!empty($fr) && intval($fr->value) > 0){
                        if(intval($last_pay->ord_num) % intval($fr->value) == 0 && $lr_num < intval($last_pay->ord_num)){
                            $arr_s = Clientes::get_saldo_a_pagar($e);
                            $lpdt = $last_pay->get_pcle('fec_pago');
                            $lpmto = $last_pay->get_pcle('monto_pagado');
                            $dt_fecha_pago = (!empty($lpdt))?$lpdt->value:'';
                            $int_lpm = (!empty($lpmto))?intval($lpmto->value):0;
                            return [
                                'elem_id'=>$e->id,
                                'financ_id'=>$fn->id,
                                'last_pay_date'=>$dt_fecha_pago,
                                'last_pay_amount'=>$int_lpm,
                                'last_pay_ord_num'=>intval($last_pay->ord_num),
                                'atm_fnanc'=>$fn->get_props(),
                                'saldo_a_pagar'=>$arr_s['monto'],
                                'cant_ctas_a_pagar'=>$arr_s['ctas_rest']
                            ];
                        }
                        return false;
                    }
                    return false;
                }
                return false;
            }


            public static function get_saldo_a_pagar($e){
                // $fut_ev = $e->get_first_future_event('a_pagar');

                $ev_lp = $e->get_last_payment();
                $monto = intval($ev_lp->get_pcle('monto_pagado')->value);

                $fn_id = $e->get_pcle('financ_id')->value;
                $fn = new Atom($fn_id);
                $fn_cant_ctas = $fn->get_pcle('cant_ctas')->value;


                $ctas_rest = intval($fn_cant_ctas) - intval($ev_lp->ord_num);
                return ['ctas_rest'=>$ctas_rest,'monto'=>$ctas_rest*$monto];
            }





            // RESPONDE AL CAMBIO DE PLAN EN REV_SELECT
            function revision_update_select(){
                $p = $this->input->post('data');
                $a = new Atom($p['financ_id']);
                $res=[
                    'method'=>'revision_update_select',
                    'action' =>'response',
                    'data'=>$a->get_props()
                ];
                $this->cmn_functs->resp('front_call',$res);
            }


            //$cant_ctas,$mto_cta,$fec_init,$indac,$frec_indac,$elm_id,$anticipo='false',$mto_anticipo=0)
            // ACTUALIZA LA REVISION DE CONTRATO *** falta agregar el pcle last_rev_num a la estructura
            // FALTA REVISAR POR QUE TIENE QUE SER UNA REFINANCIACION DE PLAN ACCESIBLE EN CUNALQUIER MOMENTO
            function set_revision_fplan(){
                // $p = $this->input->post('data');
                // $e = new Element($p['elem_id']);
                // $e->set_pcle(0,'last_rev_num',$p['last_rev_num']);
                // $e->set_pcle(0,'financ_id',$p['new_plan_id']);
                // $e->clean_a_pagar_events();
                // $this->update_ctas_rev_fplan($e,$p);
                //  // **** SALIDA A FRONT
                // $aft_act = ['method'=>'get_elements','sending'=>true,'data'=>['elm_id'=>$e->id]];
                // $r =[
                //    'tit'=>'Revision de plan ',
                //    'msg'=>'Cliente actualizado correctamente',
                //    'type'=>'success',
                //    'container'=>'modal',
                //    'after_action'=>$aft_act
                //  ];
                //  echo json_encode(array
                //    (
                //      'callback'=>'myAlert',
                //      'param'=>$r
                //    )
                //  );
            }



            public function upd_futr_past_v1($e){
                //****  CHECKEO SI ESTA EN EL CAMBIO DE CICLO
                if($this->get_cambio_financ_plan($e)){
                    // $e->clean_a_pagar_events();
                    $ev_lpay = $e->get_last_payment();
                    if(!empty($ev_lpay)){
                        $res=[
                            'method'=>'set_cambio_financ_plan',
                            'action' =>'response',
                            'elem_id'=>$e->id,
                            'last_fec_pago'=>$ev_lpay->get_pcle('fec_pago')->value,
                            'last_monto_pagado'=>$ev_lpay->get_pcle('monto_pagado')->value
                        ];
                        $this->cmn_functs->resp('front_call',$res);
                    }
                    exit();
                }
                // ANTES DE IMPLEMENTAR CHECK_REV_PLAN HAY QUE REHACER REV_PLAN
                //  QUE DEBE CAMBIAR A REFINANCE PLAN
                // $rev_fp = $this->check_rev_fplan($e);
                // if(!empty($rev_fp)){
                //   $res=[
                //     'method'=>'set_revision_fplan',
                //     'action' =>'response',
                //     'data'=>$rev_fp
                //   ];
                //   $this->cmn_functs->resp('front_call',$res);
                //   exit();
                // }

                $dt_now = new DateTime(date('Y-m-d'));
                $f = $e->get_events(8,'a_pagar');
                foreach ($f['events'] as $xv) {
                    $dt_xv = new DateTime(substr($xv['fecha'],0,8).'01');
                    $dt_diff = $dt_xv->diff($dt_now);
                    if($dt_diff->invert == 0){
                        if($dt_diff->days >= 25){
                            $this->app_model->update('events',['events_types_id'=>4],'id',$xv['id']);
                        }
                    }
                }
            }
            // ****************************************



            // *************************************************************************
            // ******* 7 de octubre 2019
            // ******* PREPARA LA VENTANA DEl NUEVO ATOM CLIENTE
            // *************************************************************************

            function call_new_atom(){
                $p = $this->input->post('data');
                $st = $this->cmn_functs->call_atom_struct($this->type_text);
                if($st){
                    $this->cmn_functs->resp('front_call',[
                        'route'=>$this->route,
                        'method'=> 'call_new_atom',
                        'sending'=>false,
                        'action'=> 'call_response',
                        'data'=> ['type'=>$this->type_text,'title'=>$this->type_text,'pcles'=>$st]
                    ]);
                }else{
                    $res =[
                        'route'=>$this->route,
                        'tit'=>'Alta de Cliente',
                        'msg'=>'Error de conección ',
                        'type'=>'warning',
                        'container'=>'modal',
                        'win_close_method' => 'back'
                    ];
                    $this->cmn_functs->resp('myAlert',$res);
                }
            }

            // *************************************************************************
            // ******* 18 de octubre 2019
            // ******* PREPARA LA VENTANA DEl NUEVO ATOM CLIENTE
            // *************************************************************************

            function save_new_atom(){
                $p = $this->input->post('data');
                $atom_id = $this->cmn_functs->save_new_atom($p['type_text'],$p['fields']);
                if($atom_id){
                    $this->cmn_functs->resp('front_call',[
                        'route'=>$this->route,
                        'method'=> 'call_new_atom',
                        'sending'=>false,
                        'action'=> 'save_response',
                        'data'=> ['title'=>$p['type_text'],'atom_id'=>$atom_id]
                    ]);
                }else{
                    $res =[
                        'route'=>$this->route,
                        'tit'=>'ALTA DE CLIENTE',
                        'msg'=>'Error No se registro el nuevo Cliente',
                        'type'=>'warning',
                        'container'=>'modal',
                        'win_close_method' => 'back'
                    ];
                    $this->cmn_functs->resp('myAlert',$res);
                }

            }




            // *************************************************************************
            // ******* 4 de octubre 2019
            // ******* PREPARA LA VENTANA DEl ATOM / ELEM / EVENT A EDITAR
            // *************************************************************************

            function call_edit(){
                $p = $this->input->post('data');
                $type = $p['type'];
                $id = $p['id'];
                $r = $this->cmn_functs->call_edit($type,intval($id));
                $this->cmn_functs->resp('front_call',[
                    'route'=>$this->route,
                    'method'=> 'call_edit',
                    'sending'=>false,
                    'action'=> 'call_response',
                    'data'=> $r
                ]);
            }

            // *************************************************************************
            // ******* 4 de octubre 2019
            // *******  GUARDA LOS DATOS DEl ATOM EDITADO
            // *************************************************************************

            function save_edit(){
                $p = $this->input->post('data');
                $this->cmn_functs->save_edit('Atom',$p);
                $this->cmn_functs->resp('front_call',[
                    'route'=>$this->route,
                    'method'=> 'call_edit',
                    'sending'=>false,
                    'action'=> 'save_response',
                    'data'=> ['result'=>'OK']
                ]);
            }


            // *************************************************************************
            // ******* PREPARA LA VENTANA DE NUEVO CONTRATO O SERVICIO
            // *************************************************************************
            function call_new_elem(){
                $p = $this->input->post('data');
                $struct = $this->app_model->get_arr("SELECT label,value,title,vis_elem_type,vis_ord_num FROM `elements_struct` WHERE elements_types_id = {$p['elements_types_id']} AND vis_ord_num > 0 ORDER BY vis_ord_num ASC");
                if($struct){
                    switch ($p['elements_types_id']) {
                        case '4':
                        //  fix label atom_id to servicios
                        foreach ($struct as $key => $s) {
                            if($s['label'] == 'atom_id'){$struct[$key]['label'] = 'servicios';}
                            if($s['label'] == 'cant_ctas_ciclo_2'){$struct[$key]['vis_elem_type'] = '-1' ;}
                            if($s['label'] == 'anticipo'){$struct[$key]['anticipo'] = '-1' ;}
                            if($s['label'] == 'aplica_revision'){$struct[$key]['title'] = "Aplicar Revisión" ;}
                            $struct[$key]['vis_elem_type'] = $this->cmn_functs->get_vis_elem_name($s['vis_elem_type']);
                            if($s['vis_ord_num'] == 99){unset($struct[$key]);}
                        }
                        $m = 'new_service_elem';
                        break;
                        default:
                        $m = 'new_contrato_elem';
                        foreach ($struct as $key => $st) {
                            $struct[$key]['vis_elem_type'] = $this->cmn_functs->get_vis_elem_name($st['vis_elem_type']);
                            if($st['vis_ord_num'] == 99){unset($struct[$key]);}
                        }
                        break;
                    }
                    $this->cmn_functs->resp('front_call',[
                        'route'=>$this->route,
                        'method'=> $m,
                        'sending'=>false,
                        'action'=> 'call_response',
                        'data'=> $struct
                    ]);
                }
            }

            // *********************************************************
            //*** GUARDA EL NUEVO CONTRATO O SERVICIO  Y CREA LAS CUOTAS
            function save_new_elem(){
                $p = $this->input->post('data');
                switch ($p['elem_type']) {
                    case '4':
                    if(!array_key_exists('elem_id',$p)){exit('ERROR NO element id');}
                    $m = 'new_service_elem';
                    $srvc_atom_id = -1;
                    // ID DEL SERVICIO
                    foreach ($p['fields'] as $key => $s){
                        if($s['label'] == 'servicios'){
                            $srvc_atom_id = $s['value'];
                            // retorna la etiqueta servicios al nombre atom_id
                            $p['fields'][$key]['label'] = 'atom_id';
                        }
                        if($s['label'] == 'fec_ini'){
                            $fec_ini = $s['value'];
                        }
                    }

                    $srv_atm = new Atom($srvc_atom_id);
                    //  EL -1 DE NEW ELEMENT CREA UN NUEVO SERVICIO CON EL MISMO OWNER ID
                    $c = new Element(-1,'SERVICIO',$p['elem_id']);
                    $c->pcle_updv($c->get_pcle('atom_id')->id,$srv_atm->id);
                    // if(strpos($srv_atm->name,'Prestamo') > -1){
                    //
                    // }
                    // $c->pcle_updv($c->get_pcle('descripcion')->id,$srv_atm->name);
                    // $c->pcle_updv($c->get_pcle('estado')->id,'normal');
                    $c->pcle_updv($c->get_pcle('fec_ini')->id,$fec_ini);
                    // GUARDA LOS PCLES POR SUS LABELS
                    foreach ($p['fields'] as $pcle) {
                        if(array_key_exists('value', $pcle)){
                            $c->pcle_updv(intval($c->get_pcle($pcle['label'])->id),$pcle['value']);
                        }
                    }

                    break;
                    case '1':
                    $m = 'new_contrato_elem';
                    $lid = -1;
                    $clid = -1;
                    // ID DE LOTE
                    foreach ($p['fields'] as $v) {
                        if ($v['label'] == 'prod_id'){
                            $lid = $v['value'];
                        }
                        // fix de cli_id back compat
                        if ($v['label'] == 'titular_id'){
                            $clid = $v['value'];
                        }
                    }

                    if(!$lid){exit('ERROR EN lote id');}
                    // SET NUEVO ESTADO DEL LOTE
                    $l = new Atom($lid);
                    $l->pcle_updv($l->get_pcle('estado')->id,'ACTIVO');
                    // CREA EL CONTRATO
                    $c = new Element(0,'CONTRATO',$lid);
                    // GUARDA LOS PCLES POR SUS LABELS
                    foreach ($p['fields'] as $pcle) {
                        if(array_key_exists('value', $pcle)){
                            $c->pcle_updv(intval($c->get_pcle($pcle['label'])->id),$pcle['value']);
                        }
                    }
                    //**** FIX DE CLI_ID  BACK COMPAT ***
                    $c->pcle_updv($c->get_pcle('cli_id')->id,$clid);
                    $c->pcle_updv($c->get_pcle('titular_id')->id,$clid);
                    break;
                }// ** end of switch
                // SET CICLO 1 DEFAULT VALUE
                $c->pcle_updv(intval($c->get_pcle('current_ciclo')->id),1);
                // CREA CUOTAS EVENTS
                $this->create_cuotas_new_v2($c);
                // PONE UPDATE PENDING EN FALSE HASTA QUE SE EVALUE EL PENDING EN EL PAGO
                $c->pcle_updv($c->get_pcle('plan_update_pending')->id,'false');
                $d = ($p['elem_type'] == '4')?['result'=>'ok','elm_id'=>$p['elem_id']]:['result'=>'ok','elm_id'=>$c->id];
                // response
                $this->cmn_functs->resp('front_call',[
                    'route'=>$this->route,
                    'method'=> $m,
                    'action'=> 'save_response',
                    'data'=> $d
                ]);
            } //*** END SAVE NEW ELEMENT


            // *************************************************************************
            // ******* PREPARA LA VENTANA DE ACTUALIZACION DE CONTRATO
            // *************************************************************************
            function OLD_actualizar_contrato(){
                $p = $this->input->post('data');
                $e = new Element($p['elm_id']);
                $struct = $this->app_model->get_arr(
                    "SELECT st.label,p.value,st.title,vo.nombre as vis_elem_type,
                    st.vis_ord_num  FROM elements_pcles p
                    JOIN elements_struct st on st.id = p.struct_id
                    JOIN visual_objects vo on vo.id = st.vis_elem_type
                    WHERE elements_id = {$p['elm_id']}
                    AND st.vis_ord_num > 0
                    AND st.label != 'barrio_id'
                    ORDER BY st.vis_ord_num ");
                    //  FIXES ESPECIFICOS DE LA ACTUALIZACION DEL CONTRATO

                    foreach ($struct as $key => $v) {
                        // CARGA LOTE ID DEL LOTE OBTIENE NOMBRE DEL LOTE Y LO PONE COMO TEXT READONLY
                        if($v['label'] === 'prod_id'){
                            $struct[$key]['title'] = 'Lote';
                            $l = new Atom(intval($v['value']));
                            $struct[$key]['value'] = $l->name;
                            $struct[$key]['vis_elem_type'] = 'text_readonly';
                        }
                        //*** EL MONTO TOTAL DEL CONTRATO PASA A SER EL SALDO A FINANCIAR RESTANDO LO QUE YA ESTA PAGO
                        if($v['label'] === 'monto_total'){
                            $m = $e->get_saldo_a_financiar();
                            $struct[$key]['value'] = strval($m['total']);
                            $struct[$key]['title'] = 'Monto Total a Refinanciar';
                            $struct[$key]['vis_elem_type'] = 'text';
                        }
                        //  SI TITULAR_ID ESTA VACIO LO ACTUALIZA CON CLI_ID
                        if($v['label'] === 'titular_id' && empty($v['value'])){
                            $e->pcle_updv($v['id'],$e->get_pcle('cli_id')->value);
                            $struct[$key]['value'] = $e->get_pcle('cli_id')->value;
                        }
                    }
                    $this->cmn_functs->resp('front_call',[
                        'route'=>$this->route,
                        'method'=> 'actualizar_contrato',
                        'sending'=>false,
                        'action'=> 'call_response',
                        'data'=> $struct
                    ]);
                }

                // *************************************************************************
                // ******* GUARDA LA ACTUALIZACION DE CONTRATO
                // *************************************************************************
                function OLD_save_actualizar_contrato(){
                    $p = $this->input->post('data');
                    $e = new Element($p['elm_id']);
                    $fec_pago = date('d/m/Y');
                    //ACTUALIZA ELEMENT PARTICLES
                    foreach ($p['fields'] as $v) {
                        //*** EVITA CAMBIAR PROD_ID PORQUE VUELVE EL NUMERO DEL LOTE EN LUGAR DEL ID
                        if($v['label'] !== 'prod_id'){
                            $e->pcle_updv($e->get_pcle($v['label'])->id,$v['value']);
                        }
                        // OBTIENE FECHA DEL PAGO INICIAL DESDE EL ARRAY DE FIELDS
                        if($v['label'] == 'fec_ini'){
                            $fec_pago = $v['value'];
                        }
                    }

                    // OBTIENE LOS PAGOS ANTERIORES
                    $old_p = intval($e->get_tot_pagado());
                    $user = $this -> session -> userdata('logged_in');
                    if($old_p > 0){
                        // SALDO ANTERIOR
                        $ls = $this->Mdb->db->query("SELECT saldo FROM comprobantes WHERE elements_id = {$e->id} ORDER BY id DESC LIMIT 1");
                        $saldo = ($ls->result_id->num_rows)?intval($ls->row()->saldo):0;
                        $pago = [
                            'monto_recibido' => $old_p,
                            'fec_pago' => $fec_pago,
                            'contrato_id' => $p['elm_id'],
                            'cliente_id'=>$e->get_pcle('cli_id')->value,
                            'user_id'=> $user['user_id'],
                            'lote_id'=> $e->get_pcle('prod_id')->value,
                            'barrio_id'=>$p['barrio_id'],
                            'contab_cuenta'=>18, // caja id 18 es caja traspaso de saldos
                            'observaciones'=>'PAGO INGRESADO POR ACTUALIZACIÓN DE CONTRATO',
                            'saldo'=>$saldo,
                            'no_response'=>true

                        ];
                        $this->ingresar_pago($pago);
                        //CONVIERTE EN "ACTUALIZADO" LOS EVENTOS LOS EVENTOS DE CUOTA QUE YA ESTAN PAGADOS Y BORRA LOS NO PAGADOS
                        $e->change_event_type(0,4,21);
                        $e->change_event_type(0,6,21);
                    }
                    $e->clean_a_pagar_events();
                    // CREA LOS NUEVOS EVENTOS CUOTA
                    $this->create_cuotas_new_v2($e);
                    // PONE UPDATE PENDING EN FALSE HASTA QUE SE EVALUE ES PENDING EN EL PAGO
                    $e->pcle_updv($e->get_pcle('plan_update_pending')->value,'false');
                    // PONE CURRENT CICLO EN CILO 1
                    $e->pcle_updv($e->get_pcle('current_ciclo')->value,1);

                    // response
                    $this->cmn_functs->resp('front_call',[
                        'route'=>$this->route,
                        'method'=> 'actualizar_contrato',
                        'action'=> 'save_response',
                        'data'=> ['result'=>'ok','elm_id'=>$p['elm_id']]
                    ]);
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
                    // $this->app_model->insert('lotes_error_log',array('lote'=>$e->id,'error'=>json_encode($x)));
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
                // ******* UPDATE 25 junio 2020
                // ******* VENTANA DE ACTUALIZACION DE CONTRATO
                // *************************************************************************
                function actualizar_contrato(){
                    $p = $this->input->post('data');
                    $e = new Element($p['elm_id']);
                    $lote_id = intval($e->get_pcle('prod_id')->value);
                    $lote_obj = new Atom($lote_id);
                    // arma la estrucutra de datos a mostrar en pantalla
                    $struct = $this->app_model->get_arr(
                        "SELECT st.label,p.value,st.title,vo.nombre as vis_elem_type,
                        st.vis_ord_num  FROM elements_pcles p
                        JOIN elements_struct st on st.id = p.struct_id
                        JOIN visual_objects vo on vo.id = st.vis_elem_type
                        WHERE elements_id = {$p['elm_id']}
                        AND st.vis_ord_num > 0
                        AND st.label != 'barrio_id'
                        ORDER BY st.vis_ord_num ");
                        // RECORRO STRUCT RECOLECTANDO LOS DATOS PARA ENVIAR A PANTALLA
                        $dt = [];
                        foreach ($struct as $key => $v) {
                            // LABELS QUE VAN A PANTALLA
                            if(
                                $v['label'] === 'fec_ini' ||
                                $v['label'] === 'cant_ctas' ||
                                $v['label'] === 'monto_cta_1' ||
                                $v['label'] === 'current_ciclo' ||
                                $v['label'] === 'cant_ctas_ciclo_2' ||
                                $v['label'] === 'indac' ||
                                $v['label'] === 'frecuencia_indac' ||
                                $v['label'] === 'aplica_revision' ||
                                $v['label'] === 'clausula_revision' ||
                                $v['label'] === 'clausula_revision'
                            ){$dt[]=$struct[$key];}
                            //*** EL MONTO TOTAL DEL CONTRATO PASA A SER EL SALDO A FINANCIAR RESTANDO LO QUE YA ESTA PAGO
                            if($v['label'] === 'monto_total'){
                                $m = $e->get_saldo_a_financiar();
                                $struct[$key]['value'] = strval($m['total']);
                                $struct[$key]['title'] = 'Monto Total a Refinanciar';
                                $struct[$key]['vis_elem_type'] = 'text';
                                $dt[]=$struct[$key];
                            }
                            // //*** LA CANTIDAD DE CUOTAS RESTANTES TOTAL (no se si hace falta)
                            // if($v['label'] === 'cant_ctas_restantes'){
                            //   $m = $e->get_ctas_restantes_total();
                            //   $struct[$key]['value'] = strval($m);
                            //   $struct[$key]['title'] = 'Total de Cuotas Restantes';
                            //   $struct[$key]['vis_elem_type'] = 'text';
                            //   $dt[]=$struct[$key];
                            // }


                            //  FIX -> SI TITULAR_ID ESTA VACIO LO ACTUALIZA CON CLI_ID
                            if($v['label'] === 'titular_id' && empty($v['value'])){
                                $e->pcle_updv($v['id'],$e->get_pcle('cli_id')->value);
                                // $struct[$key]['value'] = $e->get_pcle('cli_id')->value;
                            }
                        }
                        $this->cmn_functs->resp('front_call',[
                            'route'=>$this->route,
                            'method'=> 'actualizar_contrato',
                            'title' => 'ACTUALIZACION DE CONTRATO DEL LOTE '.$lote_obj->name,
                            'sending'=>false,
                            'action'=> 'call_response',
                            'data'=> $dt
                        ]);
                    }

                    // *************************************************************************
                    // ******* GUARDA LA ACTUALIZACION DE CONTRATO
                    // *************************************************************************
                    function save_actualizar_contrato(){
                        $p = $this->input->post('data');
                        $e = new Element($p['elm_id']);
                        $fec_pago = date('d/m/Y');
                        //ACTUALIZA ELEMENT PARTICLES
                        foreach ($p['fields'] as $v) {
                            //*** EVITA CAMBIAR PROD_ID PORQUE VUELVE EL NUMERO DEL LOTE EN LUGAR DEL ID
                            if($v['label'] !== 'prod_id'){
                                $e->pcle_updv($e->get_pcle($v['label'])->id,$v['value']);
                            }
                            // OBTIENE FECHA DEL PAGO INICIAL DESDE EL ARRAY DE FIELDS
                            if($v['label'] == 'fec_ini'){
                                $fec_pago = $v['value'];
                            }
                        }
                        // OBTIENE LOS PAGOS ANTERIORES
                        $old_p = intval($e->get_tot_pagado());
                        $user = $this -> session -> userdata('logged_in');
                        // SI HAY SALDO ANTERIOR
                        if($old_p > 0){
                            $ls = $this->Mdb->db->query("SELECT saldo FROM comprobantes WHERE elements_id = {$e->id} ORDER BY id DESC LIMIT 1");
                            $saldo = ($ls->result_id->num_rows)?intval($ls->row()->saldo):0;
                            $pago = [
                                'monto_recibido' => $old_p,
                                'fec_pago' => $fec_pago,
                                'contrato_id' => $p['elm_id'],
                                'cliente_id'=>$e->get_pcle('cli_id')->value,
                                'user_id'=> $user['user_id'],
                                'lote_id'=> $e->get_pcle('prod_id')->value,
                                'barrio_id'=>$p['barrio_id'],
                                'contab_cuenta'=>18, // caja id 18 es caja traspaso de saldos
                                'observaciones'=>'PAGO INGRESADO POR ACTUALIZACIÓN DE CONTRATO',
                                'saldo'=>$saldo,
                                'no_response'=>true
                            ];
                            $this->ingresar_pago($pago);
                            //CONVIERTE EN "ACTUALIZADO" LOS EVENTOS LOS EVENTOS DE CUOTA QUE YA ESTAN PAGADOS
                            $e->change_event_type(0,4,21);
                            $e->change_event_type(0,6,21);
                        }
                        // BORRA LOS NO PAGADOS
                        $e->clean_a_pagar_events();
                        // CREA LOS NUEVOS EVENTOS CUOTA
                        $this->create_cuotas_new_v2($e);
                        // PONE UPDATE PENDING EN FALSE HASTA QUE SE EVALUE ES PENDING EN EL PAGO
                        $e->pcle_updv($e->get_pcle('plan_update_pending')->value,'false');
                        // PONE CURRENT CICLO EN CILO 1
                        $e->pcle_updv($e->get_pcle('current_ciclo')->value,1);

                        // response
                        $this->cmn_functs->resp('front_call',[
                            'route'=>$this->route,
                            'method'=> 'actualizar_contrato',
                            'action'=> 'save_response',
                            'data'=> ['result'=>'ok','elm_id'=>$p['elm_id']]
                        ]);
                    }

                    // *************************************************************************
                    // ******* NEW PREPARA LOS DATOS DE SELECCION EN LA VENTANA DE UPDATE PLAN
                    // *************************************************************************
                    function call_update_plan($e_id=0){
                        $p = []; // empty post array
                        if($e_id == 0){
                            $p = $this->input->post();
                            $e_id = $p['elm_id'];
                        }
                        $e = new Element(intval($e_id));
                        $lote_nom = (new Atom($e->get_pcle('prod_id')->value))->name;
                        // $r = array_key_exists('arbitrary_call', $p);
                        // $lbls = ['indac','frecuencia_indac','interes','frecuencia_ctas_refuerzo'];
                        $lbls = ['indac','frecuencia_indac','frecuencia_revision'];
                        $pmt = $e->get_last_payment();

                        //***  SI NOY PAYMENTS INGRESADOS last payment es  1
                        if(empty($pmt)){
                            // $aft_act = ['method'=>'light_back'];
                            // $res=[
                            //       'tit'=>'Actualización de plan ',
                            //       'msg'=>'No existen pagos registrados!',
                            //       'type'=>'secondary',
                            //       'container'=>'modal'
                            //     ];
                            // $this->cmn_functs->resp('myAlert',$res);
                            // exit();
                            $pmt = $e->get_event_by_ord_num(1);
                        }
                        $x = $pmt->get_pcle('nro_cta')->value;
                        //***  SI NUMERO DE CUOTA DE LAST PAYMENT CONTIENE NUMERO HACE UPDATE
                        if(preg_match_all('!\d+!', $x, $m)){
                            //** DE NRO DE CUOTA TEXT OBTENGO CUOTA NUMERO Y TOTAL DE CUOTAS
                            $nc = intval($m[0][0]); $tc = intval($m[0][1]);

                            //** datos de revision
                            $aprv = intval($e->get_pcle('aplica_revision')->value);
                            $frec_rev =intval($e->get_pcle('frecuencia_revision')->value);
                            $cclo = intval($e->get_pcle('current_ciclo')->value);
                            $cclo2 = intval($e->get_pcle('cant_ctas_ciclo_2')->value);
                            $ctas_restantes_div = (intval($e->get_pcle('cant_ctas_restantes')->value) > 0)?intval($e->get_pcle('cant_ctas_restantes')->value):$cclo2;

                            // DATOS PARA LA VENTANA DE CAMBIO DE PLAN
                            //  DATOS DEL CONTRATO
                            // SALDO A FINANCIAR CUANDO ES REVISION DE CONTRATO
                            // if(intval($e->get_pcle('cant_ctas_restantes')->value) > 0){
                            $saldo_un_pago = intval($e->get_events_first_future()['total']) *  intval($e->get_pcle('cant_ctas_restantes')->value);
                            // }
                            // SALDO MULTIPLICANDO LAS CUOTAS CUANDO ES CAMBIO DE CICLO
                            if(intval($e->get_pcle('cant_ctas_restantes')->value) == 0 && $cclo == 1 && $cclo2 > 0){
                                $saldo_un_pago = intval($pmt->get_pcle('monto_pagado')->value) * $cclo2 ;
                            }
                            // var_dump(intval($e->get_pcle('cant_ctas_restantes')->value));
                            // var_dump(intval($pmt->get_pcle('monto_pagado')->value));

                            $ctr_data=[
                                ['label'=>$pmt->get_pcle('fecha_vto')->label,'value'=>$pmt->get_pcle('fecha_vto')->value,'title'=>'Fecha último vencimiento','vis_elem_type'=>'text','readonly'=>true],
                                ['label'=>$pmt->get_pcle('monto_pagado')->label,'value'=>$pmt->get_pcle('monto_pagado')->value,'title'=>'Monto de cuota último pago','vis_elem_type'=>'text','readonly'=>true],
                                ['label'=>'last_nro_cta','value'=>$pmt->get_pcle('nro_cta')->value,'title'=>'Nro. Cuota último pago','vis_elem_type'=>'text','readonly'=>true],
                                ['label'=>'cant_ctas_imputadas','value'=>$e->get_cant_ctas_imputadas(),'title'=>'Total Ctas. Imputadas','vis_elem_type'=>'text','readonly'=>true],
                                // ['label'=>'cant_ctas_restantes','value'=>$e->get_pcle('cant_ctas_restantes')->value,'title'=>'Cuotas. Restantes','vis_elem_type'=>'text','readonly'=>true],

                                ['label'=>'saldo_a_financiar','value'=>$saldo_un_pago ,'title'=>'Saldo a Financiar','vis_elem_type'=>'text','readonly'=>($this->usr_obj->permisos_usuario >1)?true:false],

                                ['label'=>'monto_cta_1','value'=>intval($pmt->get_pcle('monto_pagado')->value) ,'title'=>'Monto Proxima Cuota','vis_elem_type'=>'text'],
                            ];
                            // 'readonly'=>($this->usr_obj->permisos_usuario >1)?true:false]
                            //FIELDS DEL CONTRATO
                            if(array_key_exists('arbitrary_call', $p)){
                                $ctr_fields = [['label'=>'cant_ctas_restantes','value'=>$ctas_restantes_div,'title'=>'Cuotas. Restantes','vis_elem_type'=>'text','readonly'=>true]
                            ];
                        }else{
                            $ctr_fields = [['label'=>'cant_ctas_restantes','value'=>$ctas_restantes_div,'title'=>'Cuotas. Restantes','vis_elem_type'=>'text','readonly'=>true]
                        ];
                    }
                    foreach ($lbls as $l) {
                        $ctr_fields[] = $e->get_pcle($l);
                    }
                    $res=[
                        'route'=>$this->route,
                        'method'=>'set_cambio_financ_plan',
                        'action' =>'response',
                        'elem_id'=>$e->id,
                        'ctr_data'=>$ctr_data,
                        'ctr_fields'=>$ctr_fields,
                        'archivos_lote'=>$this->get_uploaded_files($e->id,'./uploads/lote_data_gen/'),
                        'nombre'=>$lote_nom,
                    ];
                    $this->cmn_functs->resp('front_call',$res);
                    exit();
                }
            }
            // *************************************************************************
            // *** NEW ACTUALIZA LOS TERMINOS DE FINANCIACION EN EL CONTRATO
            // *************************************************************************
            function save_update_plan(){
                $p = $this->input->post('data');
                $elem = new Element($p['elem_id']);


                //  DOS TIPOS DE ELEM, LOTE O SERVICIO
                $t = $elem->get('type');
                $new_fec = $p['update_plan_fec_prox_venc'];
                $new_monto = intval($p['saldo_a_financiar']);
                $monto_cta_1 = intval($p['monto_cta_1']);
                $new_cant_ctas_restantes = intval($p['cant_ctas_restantes']);
                $curr_ctas_restantes = intval($elem->get_pcle('cant_ctas_restantes')->value);

                // CAMIO DE PLAN ES FALSE POR DEFECTO, DEFINE SI ES UNA ACTUALIZACION DE PLAN O UN CAMBIO DE CICLO
                $cambio_de_ciclo = false;
                //*** ACTUALIZA DATOS DEL CONTRATO
                //*** PARA CADA LABEL ENCONTRADO HACE UPDATE DEL PCLE
                // $u = ['cant_ctas_restantes','cant_ctas_ciclo_2','indac','frecuencia_indac','interes','frecuencia_ctas_refuerzo'];
                $u = ['indac','frecuencia_indac','cant_ctas_restantes','frecuencia_revision'];
                $x=[];
                foreach ($u as $uv) {
                    // var_dump($uv);
                    if(array_key_exists($uv, $p)){
                        $x[]=[$uv=>$p[$uv]];
                        $elem->pcle_updv($elem->get_pcle($uv)->id,$p[$uv]);
                    }
                }

                $elem->pcle_updv($elem->get_pcle('monto_total')->id,$new_monto);
                $elem->pcle_updv($elem->get_pcle('plan_update_pending')->id,-1);
                switch ($t) {
                    case 'CONTRATO':
                    $curr_ciclo = intval($elem->get_pcle('current_ciclo')->value);
                    $ctas_ciclo1 = intval($elem->get_pcle('cant_ctas')->value) - intval($elem->get_pcle('cant_ctas_ciclo_2')->value);
                    $ctas_ciclo2 = intval($elem->get_pcle('cant_ctas_ciclo_2')->value);
                    // ENTRA EN CICLO 2 DEBE CREAR LA CUOTAS NUEVAS EN BASE AL NUEVO MONTO
                    if($curr_ciclo == 1 && $curr_ctas_restantes == 0 && $ctas_ciclo2 > 0 ){
                        $elem->pcle_updv($elem->get_pcle('monto_cta_1')->id,$monto_cta_1);
                        // $elem->pcle_updv($elem->get_pcle('cant_ctas')->id,($ctas_ciclo1 + $ctas_ciclo2));
                        // $elem->pcle_updv($elem->get_pcle('cant_ctas_restantes')->id,$new_cant_ctas_restantes);
                        $elem->pcle_updv($elem->get_pcle('current_ciclo')->id,2);
                        // $elem->pcle_updv($elem->get_pcle('cant_ctas_ciclo_2')->id,$new_cant_ctas_restantes);
                        $cambio_de_ciclo = true;
                    }
                    if($cambio_de_ciclo){
                        // CREA LAS CUOTAS DEL CICLO 2
                        $this->create_cuotas_de_ciclo2($elem->id,$new_fec);
                    }else{
                        // ACTUALIZA LOS VALORES DE CUOTA Y LAS FECHAS DE VENCIMIENTO
                        $this->update_cuotas($elem->id,$new_fec,intval($monto_cta_1));
                    }
                    break;

                    case 'SERVICIO':
                    // ACTUALIZA LOS VALORES DE CUOTA Y LAS FECHAS DE VENCIMIENTO
                    $this->update_cuotas_servicios($elem->id,$new_fec,intval($monto_cta_1),$new_cant_ctas_restantes);
                    break;
                }
                // **** SALIDA A FRONT
                // $aft_act = ['method'=>'get_elements','sending'=>true,'data'=>['elm_id'=>$elem->id]];
                $aft_act = 'back';
                $r =[
                    'tit'=>'Actualización de plan ',
                    'msg'=>'Contrato actualizado!',
                    'type'=>'success',
                    'container'=>'modal',
                    'after_action'=>$aft_act,
                    'data'=>$x
                ];
                echo json_encode(array
                (
                    'callback'=>'myAlert',
                    'param'=>$r
                    )
                );
            }


            // *************************************************************************

            // *************************************************************************
            // ******* 10 marzo 2020
            // ******* Borra los atoms seleccionados en el array recibido
            // *************************************************************************
            function delete_selected(){
                $p = $this->input->post('data');

                foreach ($p as $id) {
                    $x = new Atom($id);
                    $x->kill();

                }
                $this->cmn_functs->resp('front_call',
                [
                    'method'=>'delete_selected',
                    'response'=>true,
                    'msg'=>'Borrado...'
                ]
            );
        }

        // *************************************************************************
        // ******* 20/mayo/2020
        // *******  CANCELA EL SERVICIO Y ACREDITA LOS PAGOS PREVIOS EN CUENTA DEL CONTRATO
        // *************************************************************************
        function cancelar_serv($e_id=0){
            $p = []; // empty post array
            $fec_pago = date('d/m/Y');
            if($e_id == 0){
                $p = $this->input->post();
                $e_id = $p['elm_id'];
            }
            $serv = new Element(intval($e_id));
            $nom = (new Atom($serv->get_pcle('atom_id')->value))->name;

            $contrato_owner = new Element($serv->get('owner_id'));
            $pmt = $serv->get_last_payment();
            // RESETEA PCLES
            $serv->pcle_updv($serv->get_pcle('estado')->value,'cancelado por falta de pago');
            $serv->pcle_updv($serv->get_pcle('cant_ctas_restantes')->value,0);

            //***  SI PAYMENTS INGRESADOS LOS MANDA AL SALDO
            if(!empty($pmt)){
                $user = $this -> session -> userdata('logged_in');
                // OBTIENE LOS PAGOS ANTERIORES
                $old_p = intval($serv->get_tot_pagado());
                // SALDO ANTERIOR
                $ls = $this->Mdb->db->query("SELECT saldo FROM comprobantes WHERE elements_id = {$contrato_owner->id} ORDER BY id DESC LIMIT 1");
                $saldo = ($ls->result_id->num_rows)?intval($ls->row()->saldo):0;
                $pago = [
                    'monto_recibido' => $old_p,
                    'fec_pago' => $fec_pago,
                    'contrato_id' => $contrato_owner->id,
                    'cliente_id'=>$contrato_owner->get_pcle('titular_id')->value,
                    'user_id'=> $user['user_id'],
                    'lote_id'=> $contrato_owner->get_pcle('prod_id')->value,
                    'barrio_id'=>$contrato_owner->get_pcle('barrio_id')->value,
                    'contab_cuenta'=>18, // caja id 18 es caja traspaso de saldos
                    'observaciones'=>'PAGO INGRESADO POR CANCELACION DE SERVICIO',
                    'saldo'=>$saldo,
                    'no_response'=>true
                ];

                // INGRESA EL PAGO
                $this->ingresar_pago($pago);
                //CONVIERTE EN "ACTUALIZADO" LOS EVENTOS LOS EVENTOS DE CUOTA QUE YA ESTAN PAGADOS Y BORRA LOS NO PAGADOS
                $serv->change_event_type(0,4,21);
                $serv->change_event_type(0,6,21);
                $serv->clean_a_pagar_events();
            }
            $res=[
                'route'=>$this->route,
                'method'=>'cancelar_serv',
                'action' =>'response_cancelado',
                'elem_id'=>$contrato_owner->id,
            ];
            $this->cmn_functs->resp('front_call',$res);
        }

        //**** DEPRECATED *****
        function save_new_service_elem(){
            $p = $this->input->post('data');
            $srv_atm = new Atom($p['srvc_id']);
            // **** CHECKEAR SI EL ELM YA TIENE ESTE SERVICIO
            // $chx = $this->app_model->get_obj("SELECT e.id FROM `elements` e LEFT OUTER JOIN elements_pcles ep on ep.elements_id = e.id AND label = 'atom_id' where elements_types_id = 4 AND owner_id = {$p['elm_id']} and ep.value = {$srv_atm->id} limit 1");
            // if(!empty($chx->id)){
            //   $r =[
            //     'tit'=>'Registro de Nuevo Servicio ',
            //     'msg'=>'No se pueden crear dos instancias del mismo servicio',
            //     'type'=>'warning',
            //     'container'=>'modal',
            //   ];
            // }
            $srv_elm = new Element(-1,'SERVICIO',$p['elm_id']);
            // ESTRUCTURA DE PCLES DEL SERVICIO
            $srv_elm->set_pcle(0,'atom_id',$srv_atm->id,'',-1);
            $srv_elm->set_pcle(0,'atom_name',$srv_atm->name,'',-1);
            $srv_elm->set_pcle(0,'estado','normal','',-1);
            $srv_elm->set_pcle(0,'fec_ini',$p['fec_ini']);

            $srv_elm->set_pcle(0,'estado_deuda',0);
            $srv_elm->set_pcle(0,'fec_ultimo_pago','-');


            // crea los pagos en base a financ id
            $f = new Atom($p['financ_id']);

            $srv_name = $srv_atm->name;
            $cant_ctas = intval($f->get_pcle('cant_ctas')->value);
            $intrst = intval($f->get_pcle('interes')->value);
            $indac = intval($f->get_pcle('indac')->value);
            $frec_indac = intval($f->get_pcle('frecuencia_indac')->value);;
            $m_anticipo = intval($p['monto_anticipo']);
            $anticipo = ($m_anticipo > 0)?true:false;
            $m = intval($p['monto']);

            // CALCULO DE CUOTAS DEL MUTUO
            if(strpos($srv_name, 'MUTUO') > -1){
                $cta_init = $m;
            }else{
                // CALCULO Y SETEO DE CUOTAS CONVENCIONALES
                $cant_ctas = ($cant_ctas === 0)?1:$cant_ctas;
                if($intrst === 0 && $indac === 0){
                    $cta_init = intval($m/$cant_ctas);
                }else{
                    $cta_init =  intval(($m*($intrst/100)+$m)/$cant_ctas);
                }
            }

            $ctas_created = $this->create_cuotas_new($cant_ctas,$cta_init,$p['fec_ini'],$indac,$frec_indac,$srv_elm->id,$anticipo,$m_anticipo);

            // if($ctas_created){
            // $this->mk_egreso_caja_por_prestamo($srv_elm->id)

            // }


            echo json_encode(
                array(
                    'callback'=>'front_call',
                    'param'=>array(
                        'route'=>$this->route,
                        'method'=> 'new_service_elem',
                        'action'=> 'save_response',
                        'sending'=>false,
                        'data'=> ['result'=>'ok','elm_id'=>$p['elm_id']]
                    )
                )
            );
        }

        function kill_elem(){
            $d = $this->input->post('elm_id');
            $e = new Element($d);
            if($e->type  === 'CONTRATO'){
                $lote = new Atom ($e->get_pcle('prod_id')->value);
                $lote->set_pcle(0,'estado','DISPONIBLE');
                $e->kill_events_all();
                $e->kill();
                $r =[
                    'method'=>'back',
                    'data'=>0
                ];
            }
            elseif($e->type === 'SERVICIO'){
                $owner_id = $e->owner_id;
                $e->kill_events_all();
                $e->kill();
                $r =[
                    'route'=>$this->route,
                    'method'=>'get_elements',
                    'sending'=>true,
                    'data'=>['elm_id'=>$owner_id]
                ];
            }
            else{
                $r =[
                    'method'=>'alert',
                    'data'=>['container'=>'#my_modal_body','type'=>'danger','tit'=>'Error!','msg'=>'Tipo de Contrato o Servicio no es valido','extra'=>'hide_modal']
                ];
            }
            $this->cmn_functs->resp('front_call',$r);
        }


        function mk_egreso_caja_por_prestamo(){
            // ****  Asiento de caja
            // **** egreso del prestamo
            // falta revisar
            $e = new Element($srv_elm->id);
            $lote_id = $e->get_pcle('prod_id')->value;
            $cli_id = $e->get_pcle('cli_id')->value;
            $l = new Atom($lote_id);
            $barrio = $l->get_pcle('emprendimiento')->value;
            $barrio_id = new Atom(0,'BARRIO',$barrio);

            $ccd[] = ['barrio_id'=> $barrio_id->id,'percent'=> "100"];
            $c = [
                'cuentas_id' => $p['cuentas'],
                'cliente_id'=>$cli_id,
                'lote_id'=>$lote_id,
                'operador_usuario_id'=>$p['user_id'],
                'origen'=>'save_new_service',
                'tipo_asiento'=>'EGRESOS',
                'cuenta_imputacion_id' => 58,
                'monto'=>0
            ];
            $op_nro=$this->cmn_functs->mk_asiento_caja($c,$ccd);
        }


        function get_financ_con_anticipo(){
            // get financ items para checkear financ con o Sin anticipo
            $ftid = $this->app_model->get_obj("SELECT id FROM atom_types WHERE name LIKE 'FINANCIACION' ");
            $fatm_id = $this->app_model->get_arr("SELECT id FROM atoms WHERE atom_types_id = {$ftid->id} ");
            $x_f = [];
            foreach ($fatm_id as $f) {
                $x = new Atom($f['id']);
                $q = $x->get_pcle('anticipo');
                if(is_object($q) &&  property_exists($q, 'value') && intval($q->value) === 1){
                    $x_f[]=$f['id'];
                }
            }
            return $x_f;
        }





        // **** AUTOCOMPLETES
        public function autocomplete_atom_name(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $atom_type_id = 1;
            $r = $this->app_model->atcp_atom_name($atom_type_id,$_GET['term']);
            echo json_encode($r);

        }

        public function autocomplete_empre(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_empre($_GET['term']);
            echo json_encode($r);

        }
        public function autocomplete_clientes(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_cli($_GET['term']);
            echo json_encode($r);

        }

        public function autocomplete_clientes_venta_lote(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_cli_venta_lote($_GET['term']);
            echo json_encode($r);

        }

        public function autocomplete_lotes(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_lotes($_GET['term']);
            echo json_encode($r);

        }

        public function autocomplete_lotes_disponibles(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_lotes_disponibles($_GET['term']);
            echo json_encode($r);

        }


        public function get_resumen(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r=[];
            $cl = $this->app_model->atcp_cli_by_nomap($_GET['term']);
            foreach ($cl as $c){
                $l = $this->app_model->get_lote_by_cli($c['id']);
                if(count($l)> 0){

                    $r[]=['label'=>$c['label'], 'name'=>$l[0]['name']  ,'id'=>$l[0]['id']];

                }

            }
            echo json_encode($r);
        }

        public function autocomplete_get_elements(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $r = $this->app_model->atcp_get_elements($_GET['term']);
            echo json_encode($r);
        }

        public function autocomplete_lotes_vendidos(){
            parse_str($_SERVER['QUERY_STRING'], $_GET);
            $lid = $this->app_model->atcp_lotes_vendidos($_GET['term']);
            $r=[];
            $l['label'] = '';
            foreach ($lid as $l) {
                $e = new Element(0,"CONTRATO",$l['id']);
                $cli = false;
                $tit = false;
                $id = $e->get_pcle('titular_id')->value;
                // if(!empty($id)){
                //     $cli =$id;
                // }
                //
                // else if(!empty($tit_id)){
                //     $tit = $e->get_pcle('titular_id')->value;
                // }
                //
                // if($cli == $tit ){
                //     $c = new Atom($tit);
                //     $l['label'] .= ($c->get_pcle('nombre'))?' - '.$c->get_pcle('nombre')->value:'';
                //     $l['label'] .= ($c->get_pcle('apellido'))?'  '.$c->get_pcle('apellido')->value:'';
                // }
                $r[]=['label'=>$l['label'],'id'=>$l['id']];
            }
            echo json_encode($r);

        }

        // public function autocomplete_financ(){
        //     parse_str($_SERVER['QUERY_STRING'], $_GET);
        //     $r = $this->app_model->atcp_financ($_GET['term']);
        //
        //     echo json_encode($r);
        //
        // }



    }
