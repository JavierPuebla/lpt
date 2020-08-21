
<main class="container" role="main" style="padding-top: 80px;">
	<div class="bs-component">
	<div class="container-fluid"><div class="row"><div class="col" id="msgs"></div></div></div>
	<div class="container" id='main_container' >
	</div>	
</main>

	




<!-- Modal -->
<div class="modal fade" id="my_modal" tabindex="-1" role="dialog" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="my_modal_title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="my_modal_body"></div>
      <div class="modal-footer" >
       	<div class="col" id='modal-footer-msgs'></div>
        <button type="button" class="btn btn-secondary" id="close_button" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="ok_button">OK</button>
      </div>
    </div>
  </div>
</div>

 <!-- PRINTER  -->
<div class="panel panel-default" style="visibility:hidden" id="printable" >
	<div class="panel-body" id="printable_content">
		
	</div>
</div>



<script type="text/javascript">
	// console.log(<?php //echo json_encode(array('route'=>$route,'user_id'=>$user_id)); ?>)
	window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id)); ?>;
	TOP.caja_test = {
		screen :"<div class=\"row\"><div class=\'col\'><h5> Registrar el Pago de cuotas </h5></div></div>\
          <div class=\"jumbotron\" style=\"padding-top:25px;\">\
            <div class=\"row d-flex justify-content-between\">\
            <div class=\"col-1\"><button type=\"button\" onClick=front_call({'method':'back_cards'}) class=\"btn btn-primary\"><i class='align-bottom icon-small ion-ios-arrow-back'></i></button></div>\
            <div class=\"col-8\">\
                <h5><h5>\
              </div>\
              <div class=\"col-3\">\
              \
              </div>\
            </div>\
              <div class=\"col\" id=\"ctas_table\"></div>\
            <hr>\
            <div class=\"row d-flex justify-content-between\">\
              <div class=\"col-sm-1\">\
              </div>\
              <div class=\"col-sm-3\" >\
                <div class=\"form-group\" id=\"fgrp_medio_de_pago\">\
                      <label for=\"select_medio_de_pago\">Medio</label>\
                    <select class=\"form-control\" id=\"select_medio_de_pago\"  placeholder=\"Selecciona medio \">\
                      <option value=\"Efectivo\">Efectivo</option>\
                    <option value=\"Deposito Bancario\">Deposito Bancario</option>\
                    </select>\
                </div>\
                <div class=\"custom-control custom-checkbox\">\
                    <input type=\"checkbox\" class=\"custom-control-input\" onChange=update_ctas_adl(0) id=\"check_ctas_adl\">\
                    <label class=\"custom-control-label\" for=\"check_ctas_adl\">Adelantar Cuotas</label>\
                  </div>\
                <div class=\"form-group\">\
                  <label for=\"cantidad_ctas \" style=\"display:none;\" id=\"lbl_cant_ctas_adl\">Cant.</label>\
                  <input type=\"number\" class=\"form-control\" id=\"cant_ctas_adl\" onChange=update_ctas_adl(this.value) min=\"0\" max=\"120\" value=\"0\">\
                </div>\
              </div>\
              <div class=\"col-sm-2\">\
              </div>\
              <div class=\"col\" >\
                <div class=\"form-group row form-inline float-right\">\
                      <label for=\"monto_interes \" class=\"col-form-label\">Total Intereses $:</label>\
                    <div class=\"col\">\
                      <input type=\"text\" readonly=\"\" class=\"form-control-plaintext\" id=\"monto_interes\" >\
                    </div>\
                    <label for=\"monto_ctas \" class=\"col-form-label\">Total Cuotas $:</label>\
                    <div class=\"col\">\
                      <input type=\"text\" readonly=\"\" class=\"form-control-plaintext\" id=\"monto_ctas\" value=0>\
                    </div>\
                </div>\
                <p></p>\
                <legend><div class=\"form-group row form-inline float-left\">\
                      <label for=\"monto_pago \" class=\"col-form-label\">Total a pagar $:</label>\
                    <div class=\"col\">\
                      <input type=\"text\" readonly=\"\" class=\"form-control-plaintext\" id=\"monto_pago\" >\
                    </div>\
                </div></legend>\
                  <p></p>\
                <div class=\"form-group row form-inline float-left \">\
                      <label for=\"monto_recibido \" class=\"col-form-label-lg\"><legend>Total ingresado $:</legend></label>\
                    <div class=\"col\">\
                      <legend><input type=\"number\" class=\"form-control-lg\" id=\"monto_recibido\" ></legend>\
                    </div>\
                  </div>\
              </div>\
            </div>\
            <div class=\"row\" id=\"row_ctas_adl\">\
              </div>\
            <div class=\"row\">\
              <div class=\"col\">\
                <div class=\"btn btn-primary float-right\" id=\"bot_process_pago\"onClick=\"front_call({method:'procesar_pago_cuota'})\"  href=\"#\" role=\"button\">Procesar Pago\
                </div>\
              </div>\
            </div>\
          </div>"
		}
	$( window ).load(function() {
		$("#main_container").html(TOP.caja_test.screen);
	});

</script>
</body>
</html>
