
<main class="container-fluid"  role="main" style="padding-top: 100px;">
<div class="bs-component" id='main_container'>
	<!-- <div class="container-fluid"><div class="row"><div class="col" id="msgs"></div></div></div> -->
	<!-- <div class="container-fluid"  > -->
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
      <div class="modal-body d-flex justify-content-around" id="my_modal_body"></div>
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
	$( window ).load(function() {
		window.TOP = <?php echo json_encode(array('route'=>$route,'user_id'=>$user_id,'permisos'=>$permisos,'selects'=>$selects,'locked'=>$locked,'screen'=>$screen)); ?>;
		// console.log('top',TOP);
		if (TOP.locked){
			front_call({'method':'updating'});
		}
		else{
			$("#main_container").html(jb_views.create({title:'Configuracion',id:'cfg_jb_cont'}));	
			TOP.screen.map(function(s){$("#cfg_jb_cont").append(btn_views.create({call:s.call,tag:s.tag}))});
			TOP.history = [];
			TOP.curr_ok_act = {};
			TOP.curr_close_act = {};
			set_dataTable_lang();	
		}
	});
</script>
</body>
</html>
