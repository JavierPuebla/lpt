<body>
<!-- <main class="container-fluid"> -->

	<!-- <div class="bs-component"> -->
		<div class="container-fluid" id='main_container' role="main" style="padding-top: 5px;"></div>
	<!-- </div> -->
<!-- </main> -->



<!-- Modal -->
<div class="modal fade " id="my_modal" tabindex="-1" role="dialog" aria-labelledby="my_modal" aria-hidden="true">
  <div class="modal-dialog" id='my_modal_container'>
    <div class="modal-content">
      <div class="modal-header">
        <div class="jp-modal-title" id="my_modal_title"></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="my_modal_body"></div>
      <div class="modal-footer" >
      	<div class="col-1 d-flex justify-content-start flex-wrap">
					<button type="button" class="btn-warning d-none mr-3 ml-3 " id="delete_button" onclick="front_call({method:'kill_modal_content',sending:false})"><i class="material-icons">delete</i></button>
					<button type="button" class="btn-normal d-none mr-3 ml-3 " id="print_button" onclick="$('#printable_content').printThis()"><i class="material-icons">print</i></button>

      	</div>

       	<div class="col d-flex justify-content-start flex-wrap" id='modal-footer-msgs'></div>
		<div class="col d-flex justify-content-end flex-wrap" id='modal-footer-butons'>
				<button type="button" class="btn btn-secondary " id="close_button" data-dismiss="modal" onClick="front_call(TOP.curr_close_act)" >Volver</button>
        <button type="button" class="btn btn-primary ml-1" id="ok_button" onClick="front_call(TOP.curr_ok_act)">Aceptar</button>
		</div>
	  </div>
    </div>
  </div>
</div>

 <!-- PRINTER  -->
<div class="panel panel-default" style="visibility:hidden" id="printable" >
	<div class="panel-body" id="printable_content">

	</div>
</div>



<noscript>JavaScript esta deshabilitado </noscript>
<script type="text/javascript">
	$( window ).load(function() {
		//** PUT MODAL TO TOP OF SCREEN
      function alignModal(){
          var modalDialog = $(this).find("#my_modal");
          /* Applying the top margin on modal dialog to align it vertically center */
          // console.log('modal position',Math.max(0, ($(window).height() - modalDialog.height())));
          modalDialog.css("margin-top", 0);
      }
      // Align modal when it is displayed
      $("#my_modal").on("shown.bs.modal", alignModal);

      // Align modal when user resize the window
      $(window).on("resize", function(){
          $(".modal:visible").each(alignModal);
      });
		//*** TOP VARS FROM SERVER
      window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id,'permisos'=>$permisos,'selects'=>$selects,'locked'=>$locked,'screen'=>$screen,'screen_title'=>$screen_title)); ?>;
  		// console.log('top',TOP);
  	// PANTALLA DE BOTONES DE SELECCION
    	if (TOP.locked){
  			front_call({'method':'updating'});
  		}
  		else{
  			$('#print_button_home').hide();$('#print_button').hide();
  			$("#main_container").html(jb_views.create({title:TOP.screen_title,id:'cfg_jb_cont'}));

  			TOP.screen.map(function(s){$("#cfg_jb_cont").append(btn_views.create({call:s.call,tag:s.tag}))});
  			TOP.history = [];
  			TOP.curr_ok_act = {};
  			TOP.curr_close_act = {};
  			set_dataTable_lang();
        set_autonumeric_def();
  		}
	  //*** FORCE NO STATUS BAR ON MOBILE
      setTimeout(function(){
        window.scrollTo(0, 1);
      }, 0);
  });
</script>
</body>
</html>
