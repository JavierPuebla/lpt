<body>
<main class="container-fluid">
	<div class="bs-component">
		<div class="container-fluid" id='main_container'role="main" style="padding-top: 60px;"></div>
	</div>
</main>

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



<noscript>JavaScript esta deshabilitado </noscript>
<script type="text/javascript">
	$( window ).load(function() {
		window.TOP = <?php echo json_encode(array('elements'=>$elm)); ?>;
		console.log('top',TOP);
		if (TOP.locked){
			front_call({'method':'updating'});
		}
		else{
			let gtot_ctas = 0; gtot_intrs = 0;
			const tit ="<div class=\"row d-flex justify-content-around\"><div class=\"col d-flex p-4\"><a href=\'../login_cli\'><img src=\'../uploads/logo_LPT.png\'></div><div class=\"col d-flex p-2\"></a><h5 class=\"text-center\"> Resumen de cuenta de "+TOP.elements[0].user_data.user_apellido+" "+TOP.elements[0].user_data.user_nombre+"</h5></div></div>";
			$("#main_container").append(tit);
			for (let i = 0; i < TOP.elements.length; i++) {
				$("#main_container").append(rdcc.create(TOP.elements[i]));
				rdcc_setup_cuotas(TOP.elements[i]);	
				gtot_ctas += get_total_amount(TOP.elements[i].cuotas.a_pagar,'tot_cta');
				gtot_intrs += get_total_amount(TOP.elements[i].cuotas.a_pagar,'interes_mora');
				if(TOP.elements[i].cuotas.srv.length > 0){
					// for (let s = 0; s < TOP.elements[i].cuotas.srv.length; s++) {
						gtot_ctas += get_total_amount(TOP.elements[i].cuotas.srv,'tot_cta');
						gtot_intrs += get_total_amount(TOP.elements[i].cuotas.srv,'interes_mora');	
					// }
				}
				
			}
			const totales = "<div class=\'card bg-light mt-4 p-4\'><h5 class=\"text-center\">Monto Total A Pagar: &nbsp;"+ accounting.formatMoney(parseFloat(gtot_ctas), "$ ", 0, ".", ",")+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Intereses por Mora:&nbsp;"+accounting.formatMoney(parseFloat(gtot_intrs), "$ ", 0, ".", ",")+" </h5></div>"			
			$("#main_container").append(totales);

			
			

			// TOP.screen.map(function(s){$("#cfg_jb_cont").append(btn_views.create({call:s.call,tag:s.tag}))});
			// TOP.history = [];
			// TOP.curr_ok_act = {};
			// TOP.curr_close_act = {};
			// set_dataTable_lang();	
		}
	});
</script>
</body>
</html>
