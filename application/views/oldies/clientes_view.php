
<main class="container-fluid">
	<div class="bs-component">
		<div class="container-fluid" id='main_container'role="main" style="padding-top: 100px;"></div>
	</div>
</main>

	




<!-- Modal -->
<div class="modal fade " id="my_modal" tabindex="-1" role="dialog" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog" id='my_modal_container'>
    <div class="modal-content">
      <div class="modal-header" id='modal_header'>
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
	window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id,'permisos'=>$permisos,'selects'=>$selects,'locked'=>$locked)); ?>;
	

	TOP.buttons_low_perm = {
		screen :"\
			<div class=\"row\" id=\"bots\">\
				<div class=\"col\"></div>\
				<div class=\"col-lg-6 \">\
					<p></p>\
					<div class=\"jumbotron\">\
						<p class=\"lead\" id=\"botpanel\">\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'get_elements',action:'call'})\">Resumen de Cuenta</button>\
							</p>\
					</div>\
				</div>\
				<div class=\"col\"></div>\
			</div>"
		}
	TOP.buttons_high_perm = {
		screen :"\
			<div class=\"row\" id=\"bots\">\
				<div class=\"col\"></div>\
				<div class=\"col-lg-6 \">\
					<p></p>\
					<div class=\"jumbotron\">\
						<p class=\"lead\" id=\"botpanel\">\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'get_elements',action:'call'})\">Resumen de Cuenta</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'new_contrato_elem',sending:true,action:'call'})\">Venta de lote</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'atom_crude'})\">Alta de Cliente</button>\
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
			// ****** LOCALE LANG DE TABLAS
			TOP.DataTable_lang = {
			    "sProcessing":     "Procesando...",
			    "sLengthMenu":     "Mostrar _MENU_ registros",
			    "sZeroRecords":    "No se encontraron resultados",
			    "sEmptyTable":     "Ningún dato disponible en esta tabla",
			    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
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
			        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
			    }
			}
			if(TOP.permisos < 5 ){
				$("#main_container").html(TOP.buttons_high_perm.screen);
				// $('#botpanel').append("<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':''})\"></button>");
			}else{
				$("#main_container").html(TOP.buttons_low_perm.screen);
			}
			//******* LOCALE FORMATO FECHA DE TABLAS
			$.fn.dataTable.moment('DD/MM/YYYY');
			
			TOP.history = [];
			TOP.curr_ok_act = {};
			TOP.curr_close_act = {method:'light_back'};
			
		}
	});
// $(document.body).css('zoom','80%')

</script>
</body>
</html>
