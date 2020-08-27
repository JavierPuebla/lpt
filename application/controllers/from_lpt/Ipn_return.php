<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ipn_return extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->Mdb =& get_instance();
        $this->Mdb->load->database();

        $this->load->library('cmn_functs');

        // Establecer la zona horaria predeterminada a usar.
        date_default_timezone_set('UTC');

        include (APPPATH . 'JP_classes/Atom.php');
        include (APPPATH . 'JP_classes/Element.php');
        include (APPPATH . 'JP_classes/Event.php');
    }


    function index(){
        $p = $this->input->post();
        $signat = $this->get_signature($p);
        // $this->Mdb->db->insert('lotes_error_log',[
        //       'lote' => '000',
        //       'error'=> json_encode($p)."CALC SIGNAT->".$signat.'REC SIGNAT->'.$p['signature'],
        //       'location'=>'ipn_return/index - post RAW '
        //     ]);
        if(isset($p) && $signat == $p['signature']){
            // $this->Mdb->db->insert('lotes_error_log',[
            //   'lote' => '----',
            //   'error'=> json_encode($p)."CALC SIGNAT->".$signat.'REC SIGNAT->'.$p['signature'],
            //   'location'=>"ipn_return->index->SIGNAT TRUE"
            // ]);
            $this->save_ipn($p);
        }else{
            $this->Mdb->db->insert('lotes_error_log',[
              'lote' => 'fallo signature from ipn',
              'error'=> json_encode($p)."CALC SIGNAT->".$signat.'REC SIGNAT->'.$p['signature'],
              'location'=>'ipn_return/index - post SIGNAT FALSE '
            ]);

        }
    }



    function save_ipn($r){
      $tq = $this->Mdb->db->query("SELECT * FROM contab_cobro_inmediato WHERE id = {$r['vads_trans_id']}");
        if(!$tq->result_id->num_rows){
            $this->Mdb->db->insert('lotes_error_log',[
              // 'lote' => (new Atom((new Element($elm_id))->get_pcle('prod_id')->value))->name,
              'error'=> 'transaction id '.$r['vads_trans_id'].'no encontrada ',
              'location'=>'save_ipn function'
            ]);
            exit();

        }else{
            $monto = number_format(floatval(substr($r['vads_effective_amount'],0,(strlen($r['vads_effective_amount'])-2))),2);
            $fecha_arg = ($this->cmn_functs->get_transaction_date($r['vads_trans_date']))->format('d/m/Y H:i:s');
            // UPDATES TRANSACTION
            $trns = $tq->row();
            $this->Mdb->db->where('id',$trns->id);
            $this->Mdb->db->update('contab_cobro_inmediato',
                [
                    'effective_amount'=>$monto,
                    'auth_number'=>$r['vads_auth_number'],
                    'auth_result'=>$r['vads_auth_result'],
                    'card_brand'=>$r['vads_card_brand'],
                    'trans_date'=>($this->cmn_functs->get_transaction_date($r['vads_trans_date']))->format('Y-m-d H:i:s'),
                    'card_number'=>$r['vads_card_number']

                ]);

            $cargos = json_decode( $trns->cargos, $assoc_array = true );

            // LA IMPUTACION DE LAS CUOTAS ESTA SUSPENDIDA POR QUE COBRO INMEDIATO MANDO UN PAGO QUE ESTABA RECHAZADO
            // SE HACE MANUALMENTE Y EL RECORD EN DB ES SOLO PARA CONTROL
            // $this->cmn_functs->inputar_cuotas($cargos,$trns->elem_id,$fec_pago,$trns->id,$r['vads_card_brand']);

            //********* envio de email
            $cli_nom = (new Atom((new Element($trns->elem_id))->get_pcle('cli_id')->value))->name;
            $lote_nom = (new Atom((new Element($trns->elem_id))->get_pcle('prod_id')->value))->name;
            $this->load->library('email');
            $this->email->to('llamarca@lotesparatodos.com.ar,jpuebla.ar@gmail.com');
            // $this->email->to('jpuebla.ar@gmail.com');
            $this->email->from('no-reply@lotesparatodos.com.ar', 'Lotes Para Todos - Acceso web de clientes');
            $this->email->subject('Pago realizado online');
            $this->email->message('El cliente '.$cli_nom .
          "\r\n ha realizado un pago online,  Monto: ".$monto.
          "\r\n Lote Numero: ".$lote_nom.
          "\r\n Fecha: ".$fecha_arg.
          "\r\n Medio de pago: ". $r["vads_card_brand"].
          "\r\n Numero de medio de pago: ". $r["vads_card_number"].
          "\r\n Numero de autorización: ".$r["vads_auth_number"].
          "\r\n Numero de transaccion: ".$r["vads_trans_id"]
          );

            $this->email->send();

        }
    }



    function get_signature($fields){
        ksort($fields); //sorting fields alphabetically
        $clv = "WdwpCPS16J6HwckK";
        $signature_content  = "";
        foreach ($fields as $nom => $val) {
            if(substr($nom,0,5) == 'vads_') {
                // Concatenation with  "+"
                $signature_content  .= $val."+";
            }
        }
        // Adding the certificate at the end
        $signature_content .= $clv ;
        $signature = base64_encode(hash_hmac('sha256',$signature_content, $clv, true));

        return $signature;
    }


    function test_save_ipn(){
         //  fake call data
            $fc = json_decode( "{
                \"vads_amount\":\"707000\",
                \"vads_auth_mode\":\"FULL\",
                \"vads_auth_number\":\"440368\",
                \"vads_auth_result\":\"00\",
                \"vads_capture_delay\":\"0\",
                \"vads_card_brand\":\"VISA_DEBIT\",
                \"vads_card_number\":\"497826XXXXXX0001\",
                \"vads_payment_certificate\":\"8f4a688d64d114ed69a6db9b7a5e880e00853509\",
                \"vads_ctx_mode\":\"TEST\",
                \"vads_currency\":\"032\",
                \"vads_effective_amount\":\"707000\",
                \"vads_effective_currency\":\"032\",
                \"vads_site_id\":\"86342911\",
                \"vads_trans_date\":\"20191127170214\",
                \"vads_trans_id\":\"900006\",
                \"vads_trans_uuid\":\"e9314ac66a2943d9bb4e58f760a5ac91\",
                \"vads_validation_mode\":\"0\",
                \"vads_version\":\"V2\",
                \"vads_warranty_result\":\"NO\",
                \"vads_payment_src\":\"EC\",
                \"vads_ext_trans_id\":\"A123456789123456789\",
                \"vads_sequence_number\":\"1\",
                \"vads_contract_used\":\"5930711802939\",
                \"vads_trans_status\":\"CAPTURED\",
                \"vads_expiry_month\":\"6\",
                \"vads_expiry_year\":\"2020\",
                \"vads_bank_code\":\"14889\",
                \"vads_bank_label\":\"Banque de Nouvelle Cal\u00e9donie\",
                \"vads_bank_product\":\"F\",
                \"vads_pays_ip\":\"AR\",
                \"vads_presentation_date\":\"20191127170233\",
                \"vads_effective_creation_date\":\"20191127170233\",
                \"vads_operation_type\":\"DEBIT\",
                \"vads_threeds_enrolled\":\"\",
                \"vads_threeds_cavv\":\"\",
                \"vads_threeds_eci\":\"\",
                \"vads_threeds_xid\":\"\",
                \"vads_threeds_cavvAlgorithm\":\"\",
                \"vads_threeds_status\":\"\",
                \"vads_threeds_sign_valid\":\"\",
                \"vads_threeds_error_code\":\"\",
                \"vads_threeds_exit_status\":\"\",
                \"vads_threeds_auth_type\":\"\",
                \"vads_result\":\"00\",
                \"vads_extra_result\":\"\",
                \"vads_card_country\":\"FR\",
                \"vads_language\":\"es\",
                \"vads_hash\":\"f5236304e6a2a07a76b96dae7a790a2882c3b90c03e2423d86cf6098b691f197\",
                \"vads_url_check_src\":\"PAY\",
                \"vads_action_mode\":\"INTERACTIVE\",
                \"vads_payment_config\":\"SINGLE\",
                \"vads_page_action\":\"PAYMENT\",
                \"signature\":\"I\/XC3j3fKtapODWdnZeh17JrCASmxKuFdtZMheEtWRc=\"
            }", $assoc_array = true );
            $this->save_ipn($fc);

    }




}
