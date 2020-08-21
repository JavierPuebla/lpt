
<!-- Modal -->
<div class="modal fade" id="my_modal" tabindex="-1" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="my_modal_title"></h4>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body" id="my_modal_body"></div>
      <div class="modal-footer" >
        <div class="col" id='modal-footer-msgs'></div>
                 <button type="button" class="btn btn-secondary" id="close_button" data-dismiss="modal" onClick="front_call(TOP.curr_close_act)" >Cerrar</button>
        <button type="button" class="btn btn-primary" id="ok_button" onClick="front_call(TOP.curr_ok_act)">OK</button>
      </div>
    </div>
  </div>
</div>




<!-- 
<div class="bs-component">
	<div class="container-fluid" id='mainContainer'>
		<div class="row">
			<div class="form-group">
				<label for="in">input</label>
				<input type="text"  class="form-control-plaintext" id="in" value='00'>
			</div>	
		</div>

		<div class="row">
			<div class="col">
				<div class="btn btn-primary" onClick="front_call({method:'clean'})"  href="#" role="button">do</div>		
			</div>
		</div>
	</div>
</div>
<hr>
 --><!-- 
<div class=\"bs-component\">\
	<div class=\"container\">\
		<div class=\"row\">\
			<div class=\"col\"><img src=\"aplication_images/logo_recibo.jpg\"></div>\
			<div class=\"col\"><p></p><h5>RECIBO NRO.: 99999</h5><H5>FECHA: 10/10/2018</H5></div>\
		</div>\
		<hr>\
		<div class=\"row\">\
			<p></p>\
			\
			<div class=\"col\">\
				<p>Nombre: ldldldldllddldld</p>\
				<p>telefono: 999999999</p>\
				<p>Concepto de venta: ldldldldllddldld</p>\
				<p>Forma de pago: ldldldldllddldld</p>\
			</div>\
			<div class=\"col\">\
				<p>Domicilio: ldldldldllddldld</p>\
				<p>e-mail: 999999999</p>\
				<p>Unidad funcional Nro: ldldldldllddldld</p>\
				<p>Cuota Nro: 999 de: 120 </p>	\
			</div>\
		</div>\
		<div class=\"row\">\
			<div class=\"col\">\
				<p>Recibimos la suma de Pesos: ldldldldllddldld</p>\
				<p>En concepto de : fkkfkfkdlskdlskfsdlsfkdkflsdfksdlfksdlfk</p>\
				<p>Unidad funcional Nro: ldldldldllddldld</p>\
				\
			</div>\
		</div>\
		<div class=\"row\">\
			<div class=\"col\">\
				<p>firma: ______________________</p>\
				<p>Aclaracion: __________________</p>\
				\
			</div>\
			<div class=\"col\">\
				<h5>Son $: 99,999,999</h5>\
				\
			</div>\
		</div>\
	</div>\
</div>\

<div class="panel panel-default" style="visibility:hidden" id="printable" >
	<div class="panel-heading">
		<h3 class="panel-title" id="printTitle"></h3>
	</div>
	<div class="panel-body" id="printable_content"></div>
</div>
 -->

<script type="text/javascript">
	$( window ).load(function() {
		window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id)); ?>;
		// console.log(<?php //echo json_encode(array('atoms'=>$atoms,'pcles'=>$pcles,'events'=>$events,'user_id'=>$user_id)); ?>)

		console.log('date',moment().format('D/M/YYYY'))
        $('#my_modal').addClass('modal-xl');
        $("#my_modal").modal('show');
	});

function printDiv(nombreDiv) {
     var contenido = document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal = document.body.innerHTML;
     document.body.innerHTML = contenido;
     window.print();
     document.body.innerHTML = "<h3>Imprimiendo...</h3>";
     window.location.reload(true);
}




</script>
</body>
</html>
