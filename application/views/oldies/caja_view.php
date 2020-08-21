
<main class="container-fluid" id='main_container' role="main" style="padding-top: 100px;">
	<!-- <div class="bs-component">
	<div class="container-fluid"><div class="row"><div class="col" id="msgs"></div></div></div>
	<div class="container-fluid"  >
	</div>	
 --></main>



<!-- Modal -->
<div class="modal fade " id="my_modal" tabindex="-1" role="dialog" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog" id='my_modal_container'>
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
				 <button type="button" class="btn btn-secondary" id="close_button" data-dismiss="modal" onClick="front_call(TOP.curr_close_act)" >Cerrar</button>
        <button type="button" class="btn btn-primary" id="ok_button" onClick="front_call(TOP.curr_ok_act)">OK</button>
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
	window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id,'selects'=>$selects)); ?>;
	TOP.main_buttons = {
		screen :"\
			<div class=\"row\" id=\"bots\">\
				<div class=\"col\"></div>\
				<div class=\"col-lg-6 \">\
					<p></p>\
					<div class=\"jumbotron\">\
						<p class=\"lead\">\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'registro_operacion','sending':true,'action':'call'})\">Registrar operación</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'pase_entre_cajas','sending':true,'action':'call'})\">Transferencia entre cajas</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'arqueo_cajaybancos'})\">Planilla Cajas y Bancos</button>\
						</p>\
					</div>\
				</div>\
				<div class=\"col\"></div>\
			</div>"
		}
	$( window ).load(function() {
		if (TOP.locked){
			front_call({'method':'updating'});
		}
		else{
			$.fn.dataTable.moment('DD/MM/YYYY');
			TOP.DataTable_lang = {
			    "sProcessing":     "Procesando...",
			    "sLengthMenu":     "Mostrar _MENU_ registros",
			    "sZeroRecords":    "No se encontraron resultados",
			    "sEmptyTable":     "Ningún dato disponible en esta tabla",
			    "sInfo":           "Registros del _START_ al _END_ de _TOTAL_ ",
			    "sInfoEmpty":      "Registros del 0 al 0 de 0 ",
			    "sInfoFiltered":   "(filtrado del total de _MAX_ registros)",
			    "sInfoPostFix":    "",
			    "sSearch":         "Buscar:",
			    "sUrl":            "",
			    "sInfoThousands":  ",",
			    "sLoadingRecords": "Cargando...",
			    "oPaginate": {
			        "sFirst":    "Primero",
			        "sLast":     "Último",
			        "sNext":     "Siguiente",
			        "sPrevious": "Anterior"
			    },
			    "oAria": {
			        "sSortAscending":  ": Ordenar columna Ascendente",
			        "sSortDescending": ": Ordenar columna Descendente"
			    }
			}

			$("#main_container").html(TOP.main_buttons.screen);
			TOP.history=[];
			TOP.curr_ok_act = {};
			TOP.curr_close_act = {};	
		}	
	});

</script>
</body>
</html>
