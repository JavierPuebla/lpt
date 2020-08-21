

<div class="bs-component">
	<div class="container-fluid" id='main_container'>
	</div>
</div>
	<!-- Modal -->
<div class="modal fade" id="my_modal" tabindex="-1" role="dialog" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my_modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="my_modal_body"></div>
      <div class="modal-footer" >
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="ok_button">OK</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
	window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id)); ?>;
	
	TOP.main_buttons = {
		screen :"\
			<div class=\"row\" id=\"bots\">\
				<div class=\"col\"></div>\
				<div class=\"col-lg-6 \">\
					<p></p>\
					<div class=\"jumbotron\">\
						<p class=\"lead\">\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'get_lpr'})\">Administrar Lista de Precios</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\" onclick=\"front_call({'method':'create_lpr','data':{}})\">Crear Lista Nueva</button>\
							<button type=\"button\" class=\"btn btn-secondary btn-lg btn-block\">-----</button>\
						</p>\
					</div>\
				</div>\
				<div class=\"col\"></div>\
			</div>"
		}


	$( window ).load(function() {
		
		$("#main_container").html(TOP.main_buttons.screen);
	});
</script>
</body>
</html>
