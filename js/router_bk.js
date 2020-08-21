function router(o){
	switch(o.method){

		case 'get_saldos':
		console.log('get saldos',o);
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				TOP.permisos = 10;


				TOP.route = o.route;
				history.add(o);
				$(document).attr("title", o.title);
				$('#navbar_msg').html(o.title);
				$('#main_container').html("<div class='container mt-5'>"+otbl.create(o.data,'tbl_saldos_cli')+"</div>");
				$(document).ready(function () {
					init_table_3('tbl_saldos_cli',{});
					//
					// init_table_3('tbl_saldos_cli',{
					// 	filter_exc : 1, // CANTIDAD DE COLUMNAS A EXCLUIR DEL FILTRADO SIRVE PARA EXCLUIR LA COLUMNA DE ACCIONES O DETALLE
					// 	drawCallback: function(){
					// 		//  SUMAR MONTO PAGADO
					// 		var api = this.api();
					// 		$( api.column(detect_tot_col(tbl_id,'Saldo')).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column(3, {} ).data().sum(), "", 0, ".", ",")+"</th>");
					// 	}
					// });
				});
			}
		}
		break;


		//*** GENERAL METHODS FROM MARZO 2020
		case 'listado':
				console.log('listado',o);
				if(o.sending){
					o.sending = false;
					TOP.send = true;
				}else{
					if(o.action == 'response'){
						// TOP.permisos = 10;
						TOP.route = o.route;
						history.add(o);
						$(document).attr("title", o.title);
						$('#navbar_msg').html(o.title);

						// let tt = otbl_editable.create(o.data,'tbl_'+o.method);
						// console.log('list',o);
						// edit_call es pcle_updv para todos esta en extras / otbl_editable
						// ************************************ ESTOY PASANDO PARAMETROS SEPARADOS DE FORMA INNECESARIA
					let heading = o.struct.map(x=>{return {'label':x.label,'title':x.title};});
					console.log('heading',heading);
					const tblId = "tbl_"+o.method;
					const tblData = {data:o.data,tblId:tblId,updateMethod:"pcle_updv"}
					const content = "<div class='mt-5'>"+otbl_editable.create(tblData)+"</div>";
					$('#main_container').html(content);
					$(document).ready(function () {
						TOP.curr_edition_val = '';
						TOP.checked_items = [];
						TOP.curr_edit_table = init_table_editable(tblId,{});
						// console.log('tbl',TOP.curr_edit_table);
					});
					}
				}
		break;

		case 'delete_selected':
		console.log(o);
		if(o.sending){
			// eliminar de la tabla en front
			o.data.map(x=>{
				TOP.curr_edit_table.row('#'+x).remove().draw('false');
			});
			TOP.curr_edition_val = '';
			TOP.checked_items = [];
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.response){
				$('#my_modal').modal('hide');
				// console.log('response',o);
			}else{
				if(TOP.checked_items.length > 0){
					TOP.curr_ok_act = {
						method:'delete_selected',
						sending:true,
						data:TOP.checked_items
					};
					c = alert.create({
						tit:"Eliminar Registros ",
						msg:"Confirma que desea eliminar los registros seleccionados?",
						type: 'danger'
					})
					mk_modal(c);
				}
			}
		}
		break;
		case 'pcle_updv':
		if(o.sending){
			o.sending = false;
			console.log('updating ',o);
			if($("#"+o.pcle_id).html() == ''){
				o.data = $("#"+o.pcle_id).val();
			}else{
				o.data = $("#"+o.pcle_id).html();
			}
			o.user_id = TOP.user_id
			if(o.data != TOP.curr_edition_val){
				console.log('sending ',o)
				TOP.send = true;
			}
		}
		if(o.response){
			TOP.send = false;
			console.log('response from server',o);
			$.blockUI({
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
				message: 'OK',
				baseZ: 10000,
				timeout:200
			});

			if(o.msg.indexOf('Error..') > -1 ){
				console.log('response',o.msg.indexOf('Error..'))
				myAlert({container:'modal',type:'danger',tit:'Error!',msg:'id de usuario no autorizado, los cambios no se registraron.',extra:''})
				TOP.send = false;
			}
		}
		break;
		case 'filter':
		if(o.sending){
			if(o.data.length == 0){
				TOP.send = false;
				front_call(TOP.history[TOP.history.length -1]);
				$(window).scrollTop(0);
			}else{

				TOP.history2 = [];
				TOP.send = true;
			}
		}else{
			if(o.action == 'response'){
				o.data.filter = TOP.filter_columns
				$('#filtered_tbl_container').html(f_tbl.create('f_tbl',o.data));
				$(document).ready(function () {
					TOP.curr_f_table = init_f_tbl('f_tbl');
					$(window).scrollTop(0);
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						$('#dbx_clp_area').collapse({
							toggle: true
						})
						$('#dbx_clp_area').collapse('hide');
					}
					TOP.history2.push($('#main_container').html());
				});
			}
		}
		break;
		// *******************************
		//  PROVEEDORES / OBRAS
		case 'alta_de_obra':
		if(o.sending){
			if(o.action === 'call'){
				o.method = 'alta_de_obra_call';
				o.data={elements_types_id:6}
				o.sending = false;
				TOP.send = true;
			}
			if(o.action === 'save'){
				o.method = 'alta_de_obra_save';
				//  FIELDS TO VALIDATE
				let ftv = (TOP.fields_contrato.filter(i =>{return i.validates !== '-1'})).map(x=>{return x.label});
				vf = validate_click('new_contrato',ftv);
				if(!vf){
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
					TOP.send = false;
					break;
				}
				o.data = {
					fields:TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					owner_id:TOP.fields_contrato.filter(n =>{return n.label === 'owner_id'})[0].value,
					elem_type:6
				};
				o.sending = false;
				console.log('sending',o.data);
				TOP.send = true;

			}
		}else{
			TOP.send = false;
			if(o.action == 'call_response'){
				// TOP.count_clientes_contrato = 1;
				// TOP.slctd_cli_id = 0;
				// TOP.slctd_lote_id = 0;
				// TOP.selects.financiacion = TOP.selects.financ_prod
				// TOP.financ_con_anticipo = o.financ_con_anticipo;
				TOP.fields_contrato = o.data;
				let x = dialog_new_contrato.create(o)
				console.log('o',TOP);
				x.title = 'ALTA DE OBRA';

				TOP.curr_close_act = {method:'back'}
				TOP.curr_ok_act = {
					method:'alta_de_obra',
					action:'save',
					sending:true
				}
				x.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(x);

				// Initializar AutoNumeric
				// const monto_total_contrato = new AutoNumeric('#monto_total', TOP.autonumeric_def);
				// $('#monto_total').change(function(){TOP.monto_total_contrato = monto_total_contrato.getNumericString()});
				// const monto_cta_1 = new AutoNumeric('#monto_cta_1', TOP.autonumeric_def);
				// $('#monto_cta_1').change(function(){TOP.monto_cta_1 = monto_cta_1.getNumericString()});
			}
			if(o.action == 'save_response'){
				console .log('response from save',o);
				// if(o.data.result == 'ok'){
				// 	console.log('save resp',o);
				// 	o.method = 'get_elements';
				// 	o.sending = true;
				// 	front_call(o);
				// }else{
				// 	myAlert({container:'modal',type:'danger',tit:'Error!',msg:'Fallo el alta del nuevo servicio',extra:''});
				// 	TOP.curr_ok_act.method = 'back';
				// 	TOP.curr_ok_act.sending = false;
				// }
			}

		}
		break;
		case 'gestion_de_obras':
		if(o.sending){
			if(o.action === 'call'){
				TOP.send = true;
			}
		}else{
			if(o.action === 'call_response'){
				history.add(o);
				console.log('gestion response',o);
				$(document).attr("title", o.title);
				$('#navbar_msg').html(o.title);
				TOP.route = o.route;
				TOP.filter_columns = o.data.filter;
				TOP.curr_filters = [];
				TOP.actions_col_index = 8 // index num de la columa de acciones si es -1 no pone col debe ser global para que lo acceda filter
				//*** SCREEN ELEMENTS
				let scrn = "<div class='mt-5'><div id=\'ft_and_tb_wrapper\' class='row mt-3'>";
				scrn += "<div id=\'filter_column\' class=\'col-12 col-sm-12 col-md-2 p-0\'>";
				scrn +="</div>";
				scrn += "<div id=\'filtered_tbl_container\' class=\'col-12 col-sm-12 col-md-10 p-1\'></div>";
				scrn +="<div></div>";
				let fbox = data_box_small.create({label:'Filtrar',id:"dbx_filter",value:filter.create(o.data.filter),collapsed:true}).get_screen();
				let toast = "<div id=\toast_cnt\' class=\'row col-12 d-flex flex-wrap justify-content-start\'></div>";
				$('#main_container').html(scrn);
				$('#filter_column').html(toast+fbox);
				$('#filtered_tbl_container').html(f_tbl.create('f_tbl',o.data));

				$(document).ready(function () {
					TOP.curr_f_table = init_f_tbl('f_tbl');
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						$('#dbx_clp_area').collapse({
							toggle: true
						})
						$('#dbx_clp_area').collapse('hide')
					}
				});
			}
		}
		break;
		case 'edit_obra_element':
		if(o.sending){
			if(o.action === 'call'){
				// ES LLAMADO DESDE GET ELEMENTS PRIMARIO
			}
			else if (o.action === 'save') {

			}
		}else{
			if(o.action == 'response'){
				console.log('edit obra element',o);
			}
		}

		break;
		// ********************************

		// **** ATOM CRUDE
		case 'atom_crude' :
		console.log('crude',o)
		if(o.sending){
			console.log('crude sending true',o)
			// ENVIANDO DATOS PARA CREAR NUEVO O MODIFICAR EXISTENTE
			if(o.action == 'add' || o.action == 'upd'){
				if(validate_click('crude',TOP.last_call_param.data)){
					TOP.data = update_crude_data(TOP.last_call_param.data);

					o.method = 'save_atom';
					o.data = TOP.data;
					if(TOP.hasOwnProperty('owner_id_exists')){
						o.owner_id_exists = TOP.owner_id_exists;
					}
					o.atom_name = get_Tpcle(TOP.data,'apellido')+" "+get_Tpcle(TOP.data,'nombre');
					o.atom_id = TOP.last_call_param.atom_id;
					console.log('sending OK',o)
					TOP.send = true;
				}else{
					myAlert({container:'#msgs',type:'danger',tit:'Error!',msg:'debe completar todos los campos ',extra:''})
					TOP.send = false;
				}
			}
			// ENVIADO NOMBRE PARA CHECKEAR SI EXISTE O CREAR NUEVO
			if(o.action == 'call'){
				if( !$('#atom_name').val()){
					myAlert({container:'#msgs',type:'danger',tit:'Error!',msg:'debe ingresar un nombre y  apellido valido ',extra:''})
					TOP.send = false;
				}else{
					o.data={
						atom_name:$('#atom_name').val()
					}
					TOP.send =  true;
				}
			}
			if(o.action == 'edit'){
				o.sending = false;
				// ******** GABO OWNER_ID_EXISTS PARA VOLVER DESDE EL GUARDADO DE LA EDICION DEL CLI
				TOP.owner_id_exists = o.data.owner_id;
				o.action = 'call'
				TOP.send = true;
				// console.log('crude to send',o)
			}
		}
		else{
			console.log('crude sending false',o)
			o.label = 'Clientes  Alta / Modificación  ';
			o.placeholder = 'Ingresar Nombre y Apellido ';
			var crude = dialog_crude.create(o);
			TOP.curr_ok_act = {
				method:'atom_crude',
				sending:true,
				action:'call',
			};
			TOP.curr_close_act = {method:'light_back'};
			mk_modal(crude);
			$('#atom_name').autocomplete({
				source:  TOP.route+"/autocomplete_atom_name",
				minLength: 2,
				response: function( event, ui) {
				},
				select: function(event, ui)
				{
					$('#atom_name').val(ui.item.value)
					$('#ok_button').focus();
				}
			});
			TOP.send = false;
		}
		break;
		case 'add' :
		if(o.sending){
			// console.log('adding', o)
		}else{
			// console.log('add_ method pero con sending en false... no envia al servidor')
		}
		break;
		case 'upd' :
		// console.log('updating', o)
		if(o.sending){

		}else{
			// console.log('upd_ method pero con sending en false... no envia al servidor')
		}
		break;
		// **** CLIENTES GET ELEMENTS RUTEA LAS LLAMADAS A CONTRATOS DE TODOS
		// CLIENTES, PROVEEDORES Y REPORTES HAY QUE REFACTORIZAR PARA
		// ATENDER LAS RESPUESTAS DE TODOS LOS CONTROLLERS
		case 'get_elements' :
		if(o.sending){
			if(o.hasOwnProperty('steps_back')){
				TOP.steps_back = o.steps_back;
			}
			TOP.curr_elem_id = (o.data.hasOwnProperty('elem_id')?o.data.elem_id:o.data.elm_id);
			o.data.elm_id = TOP.curr_elem_id;
			if(TOP.route == 'reportes/'){
				TOP.route = 'clientes/'
			}
			TOP.send = true;
		}else{
			if(o.action === 'response'){
				if(history.length > 0){
					o.back = back_button.create();
				}
				history.add(o);
				TOP.cards_data = o;
				TOP.route = o.route;
				// console.log('elem response',o);
				// $('#navbar_msg').html('Resumen de Cuenta: &nbsp;'+o.lote.barrio_nom+"&nbsp;"+o.lote.lote_nom);
				$('#my_modal').modal('hide');
				$('#main_container').html((clpsd_cards.create(o)).get_screen());
				// $('#printable_content').html((clpsd_cards.create(o)).get_print_vers());

				$(document).ready(function () {
					set_estado_tables();
					$('#table_last_movs').dataTable({
						language: TOP.DataTable_lang,
						responsive: false,
						searching: false,
						lengthChange: false,
						ordering:false,
						columnDefs: [
							{
								targets: 0,
								orderable: false,
							},
							{
								targets: 1,
								orderable: false,
							},
							{
								targets: 2,
								orderable: false,
							},
							{
								targets: 3,
								orderable: false,
							},
							{
								targets: 4,
								orderable: false,
							},{
								targets: 5,
								orderable: false,
							}
						]

					});
					$('#tbl_ctas').dataTable({
						"ordering": false,
						"searching": false,
						'paging': false,
						"pageLength": 100,
						"info": false,
						language: TOP.DataTable_lang,
						responsive: true
					});
					$('#table_cancs').dataTable({
						"ordering": false,
						"searching": false,
						'paging': false,
						"pageLength": 100,
						"info": false,
						language: TOP.DataTable_lang,
						responsive: true
					});
				});
				//  SETTINGS VENTANAS DE ARCHIVOS UPLOADED
				$('#lote_data_gen_tbl_uploaded_files').addClass('table-wrapper-scroll-y');
				$('#lote_data_gen_tbl_uploaded_files').addClass('file-upload-scrollbar');
				$('#lote_data_gen_data_box_panel_uploaded').addClass('p-1');
				$('#web_cli_tbl_uploaded_files').addClass('table-wrapper-scroll-y');
				$('#web_cli_tbl_uploaded_files').addClass('file-upload-scrollbar');
				$('#web_cli_data_box_panel_uploaded').addClass('p-1');

			}
			if(o.action === 'call'){
				var mdl = get_element_input.create(o)
				mdl.title = 'Obtener resumen de cuenta';
				mk_modal(mdl);
				$('#ok_button').hide();
				$('#lote').autocomplete({
					source:  "clientes/autocomplete_get_elements",
					minLength: 3,
					response: function( event, ui) {
					},
					select: function(event, ui)
					{
						front_call({method:'get_elements',sending:true,data:{elm_id:ui.item.id, elm_name:ui.item.label}})
					}
				});
				$('#lote').focus();
				TOP.send = false;
			}
		}
		break;
		case 'OLD_set_curr_state':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
			o.elem_id = TOP.data.lote.elements_id;
			o.lote_nom = TOP.data.lote.lote_nom;
			o.user_id = TOP.user_id;
			o.permisos = TOP.permisos;

			if(o.state == 'EN_RESCISION'){

				o.data.rscn_tipo_id = parseInt($('#rscn_tipo_id').val());
				if(validate_click('rescision',o.data)){
					console.log('valid RSCN',o);
					o.data.fecha = moment(new Date()).format("DD/MM/YYYY");
				}
				else{
					o.sending = true;
					TOP.send = false;
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'',msg:'Los campos marcados en rojo son requeridos'})

				}
			}
		}else{
			if(o.state == 'EN_RESCISION'){
				console.log('set_curr_state',TOP);
				TOP.selects.rscn_tipo_id = [{id:1,lbl:'carta documento'},{id:2,lbl:'formulario rescision'}];
				o.data = {};
				o.data.mto_reintegro = TOP.data.lote.mto_reintegro;
				o.data.reintegro_nro_op = 0;
				o.data.rscn_nro_compr = 0
				let cont=dialog_rscn.create(o);
				TOP.curr_elem_id = o.data.elem_id;

				cont.title = 'Rescindir Contrato de Lote';
				mk_modal(cont);
				TOP.curr_ok_act = {
					method:'set_curr_state',
					state : o.state,
					data:o.data,
					sending:true,
				}

			}

		}
		break;
		//*** UPDATE 25 JUNIO 2020
		case 'set_curr_state':
		if(o.sending){
			TOP.send = false;
			o.elem_id = TOP.data.lote.elements_id;
			o.lote_nom = TOP.data.lote.lote_nom;
			o.user_id = TOP.user_id;
			o.permisos = TOP.permisos;
			//**** OPCIONES DE CURR STATE
			if(o.value == 'ACTUALIZADO'){
				o.method = 'actualizar_contrato';
				o.action = 'call'
				o.data={elm_id:o.elem_id,elements_types_id:1}
				o.sending = false;
				TOP.send = true;
			}
			else if(o.value == 'RESCINDIDO') {
				// o.method = 'rescindir_contrato';
				// o.action = 'call'
				o.data={elm_id:o.elem_id,elements_types_id:1}
				o.sending = false;
				TOP.send = false;
				TOP.selects.rscn_tipo_id = [{id:1,lbl:'carta documento'},{id:2,lbl:'formulario rescision'}];
				o.data.mto_reintegro = TOP.data.lote.mto_reintegro;
				o.data.reintegro_nro_op = 0;
				o.data.rscn_nro_compr = 0
				let cont=dialog_rscn.create(o);
				TOP.curr_elem_id = o.data.elem_id;
				cont.title = 'Rescindir Contrato de Lote';
				mk_modal(cont);
				TOP.curr_ok_act = {
					method:'rescindir_contrato',
					data:o.data,
					sending:true,
				}
				TOP.curr_close_act = {method:'hist_home'};
			}else{
				// console.log('set_curr_state',o);
				// o.method = 'pcle_updv';
				// o.data = o.value;
				o.sending = false
				TOP.send = true;
			}
		}else{
			// *** RESPONSE FROM SERVER
			if(o.response){
				TOP.send = false;
				console.log('set curr state response from server',o);
				$.blockUI({
					css: {
						border: 'none',
						padding: '15px',
						backgroundColor: '#000',
						'-webkit-border-radius': '10px',
						'-moz-border-radius': '10px',
						opacity: .5,
						color: '#fff'
					},
					message: 'OK',
					baseZ: 10000,
					timeout:200
				});
				if(o.msg.indexOf('Error..') > -1 ){
					console.log('response',o.msg.indexOf('Error..'))
					myAlert({container:'modal',type:'danger',tit:'Error!',msg:'id de usuario no autorizado, los cambios no se registraron.',extra:''})
					TOP.send = false;
				}
			}
		}
		break;
		case 'rescindir_contrato':
		if(o.sending){
			o.data.rscn_tipo_id = parseInt($('#rscn_tipo_id').val());
			console.log('rescision',o.data);

			if(validate_click('rescision',{'mto_reintegro':o.data.mto_reintegro,'reintegro_nro_op':o.data.reintegro_nro_op,'rscn_nro_compr':o.data.rscn_nro_compr,'rscn_tipo_id':o.data.rscn_tipo_id})){
				console.log('valid RSCN',o);
				o.data.fecha = moment(new Date()).format("DD/MM/YYYY");
				o.sending = false;
				TOP.send = true;
			}
			else{
				o.sending = true;
				TOP.send = false;
				myAlert({container:'#modal-footer-msgs',type:'danger',tit:'',msg:'Los campos marcados en rojo son requeridos'})
			}

		}
		// RESPONSE FROM SERVER
		else{
			// if(o.action === 'response'){
			console.log('responsed rescision',o);
			// }
		}
		// console.log('contrato rescindido');
		break;
		//  *** INGRESAR UN PAGO EN CUENTA DEL CLIENTE
		case 'ingresar_pago':
		if(o.sending){
			// VALIDATE PAGO
			// if($('#monto_recibido').val() == ''){$('#monto_recibido').val(0)};
			TOP.curr_err_msg = '';
			if(
				validate_field('fecha_pago')
				&& validate_negnum('cuentas')
				&& validate_field('monto_recibido')
			){

				o.data = {
					'user_id':TOP.user_id,
					'lote_id':TOP.cards_data.lote.owner_id,
					'barrio_id':TOP.cards_data.lote.barrio_id,
					'cliente_id':TOP.cards_data.lote.cli_id,
					'contrato_id':TOP.cards_data.lote.elements_id,
					'monto_recibido':TOP.monto_recibido,
					'fec_pago':$('#fecha_pago').val(),
					'contab_cuenta':$("#cuentas").val(),
					'saldo':TOP.saldo_anterior,
				};
				o.sending = false;
				console.log('sending ingresar pago',o);
				TOP.send =  true;
			}else{
				if(TOP.curr_err_msg !== ''){var errmsg = TOP.curr_err_msg}else{var errmsg = 'Faltan datos o hay datos no validos.'}
				TOP.curr_close_act = {method:'light_back'};
				myAlert({tit:'Error!',msg:errmsg,type:'danger',container:'modal-footer-msgs'})
				TOP.send = false;
			}

		}else{
			var ip = dialog_ingresar_pago.create();
			TOP.curr_elem_id = o.elem_id;
			TOP.curr_ok_act = {
				method:'ingresar_pago',
				sending:true
			}
			ip.title = 'Ingresar Pago';
			mk_modal(ip);
			// Initializar AutoNumeric
			const monto = new AutoNumeric('#monto_recibido', TOP.autonumeric_def);
			$('#monto_recibido').change(function(){TOP.monto_recibido = monto.getNumericString()});
		}

		if(o.action == 'response'){
			myAlert(o);
			TOP.res_to_salida_gestiondepagos = false;
			TOP.history.pop();
			TOP.last_mov = o.last_mov;
			TOP.route = o.route;
			// REFRESCA EL LISTADO DE LAST MOVS
			for (var i = 0; i < TOP.history.length; i++) {
				TOP.history[i].last_mov = o.last_mov;
			}
			TOP.curr_ok_act = {method:'set_pago_cuotas',sending:true,action:'call',steps_back:true};
			$('#close_button').hide();
		}
		break;
		// *** PREPARA LA VENTANA DE GESTION DE PAGOS
		case 'set_pago_cuotas':
		if(o.sending && o.action == 'call'){
			o.e_id = TOP.cards_data.lote.elements_id
			o.srv = TOP.cards_data.srv.map(function(x){return x.srvc_id});
			o.sending = false;
			console.log('sending to set pago',o);
			TOP.send =  true;
		}
		if(o.action == 'response'){
			$('#my_modal').modal('hide');
			history.add(o);
			set_top_pago(o);
			set_adls(o);
			set_pantalla_pago();
			update_selected();
			console.log('TOP en set_pago response',TOP);
			TOP.route = o.route;
		}
		break;
		// IMPUTACION CUOTAS  VERSION 2
		case 'procesar_pago_cuota':
		if(o.sending){
			o.data = {
				'user_id':TOP.user_id,
				'lote_id':TOP.cards_data.lote.owner_id,
				'barrio_id':TOP.cards_data.lote.barrio_id,
				'cliente_id':TOP.cards_data.lote.cli_id,
				'contrato_id':TOP.cards_data.lote.elements_id,
				'total_imputado':TOP.tot_a_pagar,
				'tot_mto_ctas':TOP.tot_monto_ctas,
				'tot_mto_intrs':TOP.tot_monto_intrs,
				'tot_mto_srvc':TOP.tot_monto_srvc,
				'selected':TOP.selected,
				'fec_pago':$('#fecha_pago_imputacion').val(),
				'saldo':TOP.estado_actual
			}
			console.log('sending to pago',o)
			TOP.cuotas_imputadas = true;
			o.sending = false;
			TOP.send = true;
		}else{
			console.log('procesar ',TOP.estado_actual);
			// VALIDATES SELECTED CTAS
			if(TOP.tot_a_pagar == 0){TOP.curr_ok_act = {method:'light_back'};myAlert({'tit':'Error','msg':'Debe seleccionar las cuotas para imputar al pago ','type':'danger','container':'modal'});break;}
			// VALIDA QUE EL SALDO EN LA CUENTA ALCANCE PARA PAGAR LO SELECCIONADO
			if(TOP.estado_actual < -10){TOP.curr_ok_act = {method:'light_back'};myAlert({'tit':'Error','msg':'El saldo es insuficiente para imputarlo a las cuotas seleccionadas','type':'danger','container':'modal'});break;	}
			// VALIDA FECHA DE IMPUTACION DEL PAGO
			if($('#fecha_pago_imputacion').val() == ''){TOP.curr_ok_act = {method:'light_back'};myAlert({'tit':'Error','msg':'falta ingresar la fecha de imputacion del pago','type':'danger','container':'modal'});break;}
			var pp = dialog_imputacion_ctas.create();
			TOP.curr_ok_act = {
				method:'procesar_pago_cuota',
				sending:true
			}
			pp.title = 'Imputar pagos de cuotas';
			mk_modal(pp);
		}
		break;
		case 'call_pago_api':
		if(o.sending){
			if(TOP.cargos.length >0 ){
				TOP.send = true;
				TOP.sending = false;
				let cargos = TOP.cargos.filter(i=>{return i.selected});
				o.data = {monto:TOP.estado_actual,cargos:cargos,elem_id:TOP.data.lote.elements_id};
				console.log('process online',o);
			}
		}else{
			if(o.action == 'response'){
				TOP.route = o.route;
				$('#pagos_container').html(o.data);
			}
			console.log('procesar_pago_online sending false...');
		}
		break;
		// LISTADO DETALLE DE CUOTAS
		case 'detalle_ctas':
			// TOP DETALLE CUOTAS ARR CONTIENE TODAS LAS CUOTAS DE TODOS LOS TIPOS
			// o.det_arr_index ES LA CUOTA ACTUAL
			let tdata = new_det_ctas_data.create(TOP.detalle_ctas_arr[o.det_arr_index]);
			console.log('TOP det ctas',TOP.detalle_ctas_arr[o.det_arr_index]);
			tit_detalle ="<div class=\'col d-flex justify-content-start\'>Plan / Servicio: "+TOP.titulo_det_ctas_arr[o.det_arr_index]+"</div>";

			if(parseInt(o.det_arr_index)  == 0 || parseInt(o.det_arr_index) % 2 == 0){
				//** ES LISTADO DE CUOTAS PAGADAS
				let vt = get_valores_tit_detalle(TOP.detalle_ctas_arr[o.det_arr_index]);
				tit_detalle += "<div class=\'col d-flex justify-content-start\'>Pagos En ternimo: "+vt.enfecha+" , Fuera Termino: "+vt.ftrm+",  Adelantados: "+vt.adl+",  Ahorro : "+accounting.formatMoney(vt.ahorro, "$ ", 0, ".", ",")+ "</div>";
			}else{
				//** ES LISTADO DE CUOTAS A PAGAR
				tit_detalle += '<div class=\'col d-flex justify-content-start\'>Total en 1 Pago: ' + accounting.formatMoney(parseFloat(tdata['det'][0]['Monto Cuota'] * tdata['det'].length), "$ ", 0, ".", ",")+"</div>";
			}

			tbl = otbl.create(tdata.det,'det_ct');
			set_detalle_to_print(tit_detalle,tbl);
			const screen = {
				title:tit_detalle,
				cnt:tbl,
				get_screen : function(){return this.cnt },
				// winmed : 'modal-dialog-centered modal-xl',
				winmed : 'modal-xl',
				hide_ok_button : true,
				print_button: true
			}
			mk_modal(screen);
			TOP.send = false;
			TOP.curr_close_act = {method:'light_back'};
			$.fn.dataTable.moment('DD/MM/YYYY');
			$(document).ready(function (){


				$('#det_ct').dataTable({
					"order": [[ 4, "desc" ]],
					"info":false,
					'searching':false,
					'lengthChange':false,
					"paging": false,
					language: TOP.DataTable_lang,
				});
			})
		break;
		// LISTADO DETALLE DE SERVICIOS CANCELADOS
		case 'detalle_servicios_cancelados':
			if(o.sending){
				TOP.container_title = o.container_title
				o.sending = false;
				TOP.send = true;
			}else{
				if(o.action == 'response'){

					console.log('res de detalle cancelado', o);
					TOP.route = o.route;
					o.title = 'Detalle '+ decodeURI(TOP.container_title);
					new_modal.create({
						content:table_detalle_movs.create(o,'table_detalle_serv_cancelados'),
						title:'',
						wm:'xl',
						okbutt:false
					});
					TOP.send = false;
				}
			}
		break;
		case 'detalle_movs':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				console.log('res de detalle movs', o);
				TOP.route = o.route;
				o.title = 'Detalle  Movimientos';
				new_modal.create({
					content:table_detalle_movs.create(o,'table_detalle_movs'),
					title:'',
					wm:'xl',
					okbutt:false
				});
				TOP.send = false;
			}
		}
		break;
		case 'print_recibo':
			// console.log('print_recibo',o);
			if(o.sending){
				o.sending = false;
				TOP.send = true;
			}else{
				TOP.curr_rec = o;
				$('#printable_content').html('<strong>'+rec_pgc.create().get_print()+'<?strong>');
				if(o.hasOwnProperty('after_action')){
					$('#printable_content').printThis({afterPrint:front_call(JSON.parse(o.after_action))});
				}else{
					$('#printable_content').printThis();
				}
			}
		break;
		case 'new_contrato_elem' :
		if(o.sending){
			if(o.action === 'call'){
				o.method = 'call_new_elem';
				o.data={elements_types_id:1}
				o.sending = false;
				TOP.send = true;
			}
			if(o.action === 'save'){
				//  FIELDS TO VALIDATE
				let ftv = ["fec_ini", "prod_id", "titular_id", "vendedor","monto_cta_1","cant_ctas"];
				vf = validate_click('new_contrato',ftv);
				// vc = validate_combo('new_contrato');
				if(!vf){
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
					break;
				}
				o.method = 'save_new_elem'
				o.data = {
					fields:TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					elem_type:1
				};
				// if(TOP.hasOwnProperty('extra_cotitular_id')){
				// 	extra_cotitular_handler(o.data);
				// }
				o.sending = false;
				TOP.send = true;
			}
		}else{
			if(o.action === 'call_response'){
				// TOP.count_clientes_contrato = 1;
				// TOP.slctd_cli_id = 0;
				// TOP.slctd_lote_id = 0;
				// TOP.selects.financiacion = TOP.selects.financ_prod
				// TOP.financ_con_anticipo = o.financ_con_anticipo;
				TOP.fields_contrato = o.data;
				let x = dialog_new_contrato.create(o)
				console.log('o',TOP);
				x.title = 'NUEVO CONTRATO';

				TOP.curr_close_act = {method:'back'}
				TOP.curr_ok_act = {
					method:'new_contrato_elem',
					action:'save',
					sending:true
				}
				x.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(x);

				// Initializar AutoNumeric
				// const monto_total_contrato = new AutoNumeric('#monto_total', TOP.autonumeric_def);
				// $('#monto_total').change(function(){TOP.monto_total_contrato = monto_total_contrato.getNumericString()});
				const monto_cta_1 = new AutoNumeric('#monto_cta_1', TOP.autonumeric_def);
				$('#monto_cta_1').change(function(){TOP.monto_cta_1 = monto_cta_1.getNumericString()});
			}
			if(o.action == 'save_response'){
				console .log('response from save',o);
				if(o.data.result == 'ok'){
					console.log('save resp',o);
					o.method = 'get_elements';
					o.sending = true;
					front_call(o);
				}else{
					myAlert({container:'modal',type:'danger',tit:'Error!',msg:'Fallo el alta del nuevo servicio',extra:''});
					TOP.curr_ok_act.method = 'back';
					TOP.curr_ok_act.sending = false;
				}
			}
			TOP.send = false;
		}
		break;
		case 'new_cliente':
		if(o.sending){

		}else{
			if(o.action == 'call'){
				o.label = 'Clientes  Alta / Modificación  ';
				o.placeholder = 'Numero de DNI ';
				var crude = dialog_crude.create(o);
				TOP.curr_ok_act = {
					method:'new_cliente',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mk_modal(crude);
				$('#atom_name').autocomplete({
					source:  TOP.route+"/autocomplete_atom_name",
					minLength: 2,
					response: function( event, ui) {
					},
					select: function(event, ui)
					{
						$('#atom_name').val(ui.item.value)
						$('#ok_button').focus();
					}
				});
				TOP.send = false;
			}
		}

		break;
		// OBTUVO EL ID DEL PLAN QUE SE QUIERE ACTUALIZAR
		// SENDING TRUE GUARDA EL PLAN ACTUALIZADO
		// SENDING FALSE ACTION CALL LO PIDE EL SERVIDOR Y RESPONSE  ARMA EL MODAL WINDOW DE INGRESO DE DATOS
		case 'set_cambio_financ_plan':
		//
		if(o.sending){
			o.method = 'save_update_plan';
			// console.log('update_plan ',o);
			o.data = {
				elem_id:TOP.curr_elem_id,
				saldo_a_financiar: TOP.update_monto,
				// interes:$('#interes').val(),
				// frecuencia_ctas_refuerzo:$('#frecuencia_ctas_refuerzo').val(),
				cant_ctas_restantes:$('#cant_ctas_restantes').val(),
				// cant_ctas_ciclo_2:$('#cant_ctas_ciclo_2').val(),
				indac:$('#indac').val(),
				frecuencia_indac:$('#frecuencia_indac').val(),
				frecuencia_revision:$('#frecuencia_revision').val(),
				update_plan_fec_prox_venc:$('#update_plan_fec_prox_venc').val(),
				monto_cta_1:parseInt($('#monto_cta_1').val())

			}
			// const test=['cant_ctas_restantes','cant_ctas_ciclo_2','indac','frecuencia_indac','update_plan_fec_prox_venc'];
			// const test=['cant_ctas_restantes','indac','update_plan_fec_prox_venc'];
			const test=['cant_ctas_restantes','indac','monto_cta_1'];
			if(validate_click('update_plan',test)){
				console.log('sending update plan ',o);
				TOP.send = true;
			}else{
				o.sending = false;
				TOP.send = false;
				TOP.curr_ok_act = {
					method:'set_cambio_financ_plan',
					sending:true
				}
				myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
			}

		}else{
			//PIDE LOS DATOS AL SERVIDOR lo hace config_update_plan
			// if(o.action == 'call'){
			// 	o.method = 'call_update_plan'
			// 	o.data = {
			// 		elm_id:TOP.curr_elem_id
			// 	};
			// 	o.sending = false;
			// 	TOP.send = true;
			// }
			if(o.action == 'response'){
				// console.log('selects',TOP.selects)
				console.log('cambio financ plan',o)
				var cp = dialog_update_plan.create(o);
				TOP.route = o.route;
				TOP.curr_elem_id = o.elem_id;
				TOP.curr_ok_act = {
					method:'set_cambio_financ_plan',
					sending:true
				}
				cp.title = 'Actualizando Financiación de	'+o.nombre ;
				cp.winmed = 'modal-dialog-centered modal-xl';
				mk_modal(cp);
				// Initializar AutoNumeric
				TOP.update_monto = parseInt($('#saldo_a_financiar').val())
				const saf = new AutoNumeric('#saldo_a_financiar', TOP.autonumeric_def);
				$('#saldo_a_financiar').change(function(){TOP.update_monto = parseInt(saf.getNumericString())});

			}
		}
		break;
		case 'OLD_actualizar_contrato':
		if(o.sending){
			if(o.action === 'call'){
				o.method = 'actualizar_contrato';
				o.data={elm_id:TOP.data.lote.elements_id,elements_types_id:1}
				o.sending = false;
				TOP.send = true;
			}
			if(o.action === 'save'){
				// console.log('saving actualizacion de contrato',TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{return x.label}));
				//  FIELDS TO VALIDATE
				// let ftv = [ "titular_id", "cotitular_id", "vendedor", "monto_total","cant_ctas", "cant_ctas_ciclo_2", "indac", "frecuencia_indac", "interes", "anticipo"];
				// *** VALIDO EL ARRAY OF ELEMENTS LABELS
				// if(!validate_click('edit_contrato',ftv)){
				// 	myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
				// 	break;
				// }
				o.method = 'save_actualizar_contrato'

				o.data = {
					elm_id:TOP.data.lote.elements_id,
					barrio_id:TOP.cards_data.lote.barrio_id,
					fields:TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					elem_type:1
				};
				console.log('actualizacion de contrato ',o);
				o.sending = false;
				TOP.send = true;
			}
		}else{
			if(o.action === 'call_response'){
				TOP.route = o.route;
				TOP.fields_contrato = o.data;
				// console.log(' data recibida',o);
				TOP.monto_total_contrato = o.data.find(i=>{return i.label === 'monto_total'}).value;
				TOP.monto_cta_1 = 0;
				let x = dialog_new_contrato.create(o)
				console.log('monto contrato en TOP',TOP.monto_total_contrato);
				// console.log('actualizando contrato ',o);
				x.title = 'Actualizar Contrato';
				TOP.curr_close_act = {method:'back'}
				TOP.curr_ok_act = {
					method:'actualizar_contrato',
					action:'save',
					sending:true
				}
				x.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(x);
				// Initializar AutoNumeric
				console.log('setting autonum',$("#monto_total").val());
				const monto_total_contrato = new AutoNumeric("#monto_total", TOP.autonumeric_def);
				$("#monto_total").change(function(){TOP.monto_total_contrato = parseInt(monto_total_contrato.getNumericString())});
				const monto_cta_1 = new AutoNumeric("#monto_cta_1", TOP.autonumeric_def);
				$("#monto_cta_1").change(function(){TOP.monto_cta_1 = parseInt(monto_cta_1.getNumericString())});

			}
			if(o.action === 'save_response'){
				if(o.data.result == 'ok'){
					TOP.route = o.route;
					o.method = 'get_elements';
					o.sending = true;
					front_call(o);
				}else{
					myAlert({container:'modal',type:'danger',tit:'Error!',msg:'Fallo la actualizacion del contrato',extra:''});
					TOP.curr_ok_act.method = 'back';
					TOP.curr_ok_act.sending = false;
				}
			}
		}
		break;
		//*** update 2 de julio 2020
		case 'actualizar_contrato':
			if(o.sending){
				if(o.action === 'call'){
					o.method = 'actualizar_contrato';
					o.data={elm_id:TOP.data.lote.elements_id,elements_types_id:1}
					o.sending = false;
					TOP.send = true;
				}
				if(o.action === 'save'){
					console.log('saving actualizacion de contrato',TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{return x.label}));
					// PREPARING DATA
					o.data = {
						elm_id:TOP.data.lote.elements_id,
						barrio_id:TOP.cards_data.lote.barrio_id,
						fields:TOP.fields_contrato.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
						elem_type:1
					};
					//  FIELDS TO VALIDATE recorre el elem 'fields' en o.data y valida todos los contenidos edn ftv
 					// let ftv = [ "fec_ini","cant_ctas","monto_cta_1","current_ciclo", "cant_ctas_ciclo_2", "indac", "frecuencia_indac","aplica_revision","clausula_revision", "monto_total",];
					// *** VALIDO EL ARRAY OF ELEMENTS LABELS
					if(!validate_object(o.data.fields)){
						myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
						TOP.curr_ok_act = {
							method:'actualizar_contrato',
							action:'save',
							sending:true
						}
						TOP.send = false;

					}else{
						o.method = 'save_actualizar_contrato'
						console.log('SENDING actualizacion de contrato ',o);
						o.sending = false;
						TOP.send = true;
					}
				}
			}else{
				if(o.action === 'call_response'){
					TOP.route = o.route;
					TOP.fields_contrato = o.data;
					console.log(' data recibida',o);
					TOP.monto_total_contrato = o.data.find(i=>{return i.label === 'monto_total'}).value;
					TOP.monto_cta_1 = 0;

					let x = dialog_new_contrato.create(o)
					// console.log('monto contrato en TOP',TOP.monto_total_contrato);
					console.log('actualizando contrato ',o);
					x.title = o.title;
					TOP.curr_close_act = {method:"hist_home"};
					TOP.curr_ok_act = {
						method:'actualizar_contrato',
						action:'save',
						sending:true
					}
					x.winmed = 'modal-dialog-centered modal-xl'
					mk_modal(x);
					// Initializar AutoNumeric
					console.log('setting autonum',$("#monto_total").val());
					const monto_total_contrato = new AutoNumeric("#monto_total", TOP.autonumeric_def);
					$("#monto_total").change(function(){TOP.monto_total_contrato = parseInt(monto_total_contrato.getNumericString())});
					const monto_cta_1 = new AutoNumeric("#monto_cta_1", TOP.autonumeric_def);
					$("#monto_cta_1").change(function(){TOP.monto_cta_1 = parseInt(monto_cta_1.getNumericString())});

				}
				if(o.action === 'save_response'){
					if(o.data.result == 'ok'){
						TOP.route = o.route;
						o.method = 'get_elements';
						o.sending = true;
						front_call(o);
					}else{
						myAlert({container:'modal',type:'danger',tit:'Error!',msg:'Fallo la actualizacion del contrato',extra:''});
						TOP.curr_ok_act.method = 'back';
						TOP.curr_ok_act.sending = false;
					}
				}
			}
		break;

		case 'set_revision_fplan':
		if(o.sending){
			o.sending = false;
			o.data = {
				elem_id:TOP.curr_elem_id
				,new_plan_id:$('#rev_fplan').val()
				,new_monto_cta:$('#monto_cta_rest').val()
				,last_rev_num:TOP.ctas_pagadas
			}
			console.log('sending revision de plan ',o);
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				console.log('top',TOP)
				TOP.route = o.route;
				// TOP.selects = o.data.selects_rev_fplan;
				let cp = dialog_revision_fplan.create(o);
				TOP.curr_elem_id = o.data.elem_id;
				cp.title = 'Revision Condiciones de Financiacion';
				mk_modal(cp);
				TOP.curr_ok_act = {
					method:'set_revision_fplan',
					sending:true,
				}

				let cf_cant_ctas = get_pcle(o.data.atm_fnanc,'cant_ctas');

				let cf_indac = get_pcle(o.data.atm_fnanc,'indac');
				let last_pay_ord_num = o.data.last_pay_ord_num;
				let saldo = o.data.saldo_a_pagar;

				let ctas_rest = o.data.cant_ctas_a_pagar;
				let nc = parseInt(saldo) / ctas_rest;
				let new_monto = parseInt(nc * parseInt(cf_indac) / 100) + nc
				TOP.ctas_pagadas = last_pay_ord_num;
				TOP.rev_saldo_a_pagar = saldo;
				$('#cant_ctas_rest').val(ctas_rest);
				$('#monto_cta_rest').val(new_monto);



			}
		}
		break;

		case 'revision_update_select':
		if(o.sending){
			console.log('update_select',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				console.log('update_select',o)
				TOP.route = o.route;
				let cf_cant_ctas = get_pcle(o.data,'cant_ctas');
				let cf_indac = get_pcle(o.data,'indac');
				let last_pay_ord_num = TOP.ctas_pagadas;
				let cf_saldo = TOP.rev_saldo_a_pagar


				let cf_ctas_rest = parseInt(cf_cant_ctas) - parseInt(last_pay_ord_num);
				let nc = parseInt(cf_saldo) / cf_ctas_rest;
				let cf_new_monto = parseInt(nc + (nc * parseInt(cf_indac) / 100))
				$('#cant_ctas_rest').val(cf_ctas_rest);
				$('#monto_cta_rest').val(cf_new_monto);
			}
			TOP.send = false;
		}
		break;

		case 'new_service_elem':
		if(o.sending){
			if(o.action == 'call'){
				o.method = 'call_new_elem';
				o.data={elements_types_id:4}
				TOP.send = true;
			}
			if(o.action == 'save'){
				console.log('saving ',TOP.fields_servicios.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{return x.label}));
				//  FIELDS TO VALIDATE
				let ftv = ["fec_ini","servicios","monto_cta_1","cant_ctas"];
				// *** VALIDO EL ARRAY OF ELEMENTS LABELS
				// console.log('validating contrato',validate_click('new_contrato',ftv))
				// console.log('validating combo ',validate_combo('new_contrato'))
				vf = validate_click('new_contrato',ftv);
				vc = validate_combo('new_contrato');
				if(!vf || !vc){
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos incompletos o no validos',extra:''})
					break;
				}

				o.method = 'save_new_elem'
				o.data = {
					fields:TOP.fields_servicios.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					elem_type:4,
					elem_id:TOP.curr_elem_id
				};
				console.log('saving servicio ',o);
				o.sending = false;
				TOP.send = true;
			}
		}else{
			if(o.action == 'call_response'){
				console.log('response new serv ',o)
				TOP.fields_servicios = o.data;

				var cs = dialog_new_elem.create(o);
				TOP.curr_ok_act = {
					method:'new_service_elem',
					action:'save',
					sending:true
				}
				cs.title = 'Nuevo Servicio';
				cs.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(cs);
				// Initializar AutoNumeric
				// const monto_total_contrato = new AutoNumeric('#monto_total', TOP.autonumeric_def);
				// $('#monto_total').change(function(){TOP.monto_total_contrato = monto_total_contrato.getNumericString()});
				const monto_cta_1 = new AutoNumeric('#monto_cta_1', TOP.autonumeric_def);
				$('#monto_cta_1').change(function(){TOP.monto_cta_1 = monto_cta_1.getNumericString()});
			}
			if(o.action == 'save_response'){
				// console .log('response from save',o);
				if(o.data.result == 'ok'){
					o.method = 'get_elements';
					o.sending = true;
					front_call(o);
				}else{
					myAlert({container:'modal',type:'danger',tit:'Error!',msg:'Fallo el alta del nuevo servicio',extra:''});
					TOP.curr_ok_act.method = 'back';
					TOP.curr_ok_act.sending = false;
				}
			}
		}
		break;

		case 'refinanciar':
		if(o.sending){
			if(o.action == 'save'){
				o.method = 'mk_refi';
				o.data = {
					elm_id:TOP.data.lote.elements_id,
					cta_event_id:TOP.data.lote.cta_upc.events[0]['id'],
					srv_events_id:TOP.refi_srv_events_id,
					refi_cta_monto:$('#refi_cta_monto').val(),
					refi_srv_monto:$('#refi_srv_monto').val()

				}
				TOP.send = true;
				console.log('refi',o);
			}
		}else{
			if(o.action == 'call'){
				// muestra ventana emergente para poner monto con el monto de cta upc
				// y aceptar la refi
				// *** MODAL WINDOW TO SELECT FILE TO UPLOAD.
				let refi = dialog_refi.create(TOP.data);
				TOP.curr_ok_act ={
					method:'refinanciar',
					sending:true,
					action:'save'
				}
				refi.title = "Refinanciar Cuota Actual ";
				refi.winmed = 'modal-dialog-centered modal-xl';
				mk_modal(refi);
			}
			if(o.action == 'save_response'){
				$('#my_modal').modal('hide');
				front_call({method:'set_pago_cuotas',sending:true,action:'call',steps_back:true});
			}
		}


		break;

		case 'kill_elem':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			c = alert.create({
				tit:"Eliminar Contrato de Lote o Servicio",
				msg:"Esta accion no se puede deshacer. Confirma que desea eliminar?",
				type: 'danger'
			})
			mk_modal(c);
			TOP.curr_ok_act = {
				method:'kill_elem',
				sending:true,
				elm_id:o.data.id
			};
		}
		break;

		case 'print_pagares':
		if(o.sending){
			o.sending = false;
			TOP.send = false;
		}else{
			console.log('o',o);
			//  define si es servicio o prod lote
			if(TOP.data.lote.elements_id == o.data.id){
				console.log('found lote',TOP.data.lote);
				$('#printable_content').html(prepare_print_pagares.create(TOP.data.lote).get_screen());
				$('#printable_content').printThis();

			}else{
				let srv = TOP.data.srv.find(i=>{return i.srvc_id === o.data.id});
				console.log('found service', srv);
				$('#printable_content').html(prepare_print_pagares.create(srv).get_screen());
				$('#printable_content').printThis();
			}
		}
		break;
		case 'print_boleto':
		console.log('printing boleto',TOP.datos_boleto);
		$('#printable_content').html(boleto.create(TOP.cards_data.lote.datos_boleto));
		$('#printable_content').printThis({
			importStyle:true,
			importCSS: true,
		});
		break;



		case 'cli_file_upload':
		console.log('file upload',o)
		if(o.sending){
			o.sending = false;
			TOP.send = false;
			TOP.uploading = true;
		}else{

			// *** MODAL WINDOW TO SELECT FILE TO UPLOAD.
			let up = dialog_upload.create(o);
			TOP.curr_ok_act ={
				method:'cli_file_upload',
				sending:true,
			}
			up.title = "Subir Archivo ";
			up.winmed = 'modal-dialog-centered modal-xl';
			mk_modal(up);
		}
		break

		case 'refresh_uploaded_files':
		if(o.sending){
			o.sending = false;
		}else{
			uploaded_files_boxes.create(o.folder).refresh_uploaded_files(o.folder);
			$('#'+o.folder+'_tbl_uploaded_files').addClass('table-wrapper-scroll-y');
			$('#'+o.folder+'_tbl_uploaded_files').addClass('file-upload-scrollbar');
			$('#data_box_'+o.folder+'_panel_uploaded').addClass('p-1');
			//** CLEAN MODAL WINDOW
			setTimeout(function(){$('#my_modal').modal('hide');},1500);


		}

		break;

		// **** EDIT / CREATE  GENERICO
		case 'call_edit':
		if(o.sending){
			console.log('call edit',o,TOP.route)
			if(o.action === 'call'){
				o.method = 'call_edit';
				// o.data={elements_types_id:1}
				o.sending = false;
				TOP.send = true;
			}
			if(o.action == 'save'){
				// console.log('saving ',TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{return x.label}));
				//  FIELDS TO VALIDATE
				// let ftv = TOP.fields_editing.filter(i =>{return i.validates > 0 }).map(x=>{return x.label});
				let ftv = TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1' && i.validates}).map(x=>{return x.label+'_'+x.id})
				// *** VALIDO EL ARRAY OF ELEMENTS LABELS
				vf = validate_click('save_edit',ftv);

				let auth_user = get_auth_user()
				if(!vf || !auth_user){
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'datos o permisos de edicion no validos',extra:''})
					break;
				}
				o.method = 'save_edit'
				o.data = {
					fields:TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label+'_'+x.id);return x;}),
					// STREAM DE DATOS SIN ID EN EL LABEL
					// fields:TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					id:TOP.obj_editing.id
				};
				console.log('saving edit',o);
				o.sending = false;
				TOP.send = true;
			}

		}else{
			if(o.action == 'call_response'){
				console.log('',o)
				TOP.fields_editing = o.data.pcles;
				TOP.obj_editing = o.data;
				let x = edit_modal.create(o.data.pcles)
				x.title = o.data.name;
				TOP.curr_close_act = {method:'back'}
				TOP.curr_ok_act = {
					method:'call_edit',
					action:'save',
					sending:true
				}
				TOP.curr_close_act = {method:'light_back'};
				x.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(x);
			}
			if(o.action == 'save_response'){
				console .log('response from save',o);
				if(o.data.result === 'OK'){
					$.blockUI({
						css: {
							border: 'none',
							padding: '15px',
							backgroundColor: '#000',
							'-webkit-border-radius': '10px',
							'-moz-border-radius': '10px',
							opacity: .5,
							color: '#fff'
						},
						message: 'Guardado OK',
						baseZ: 10000,
						timeout:1500
					});
					$('#my_modal').modal('hide');
				}else{
					$('#my_modal').modal('hide');
					myAlert({container:'modal',type:'danger',tit:'Error de Conexión',msg:'',extra:''});
					TOP.curr_ok_act.method = 'back';
					TOP.curr_ok_act.sending = false;
				}
			}
		}
		break;
		case 'call_new_atom':
		if(o.sending){
			if(o.action === 'call'){
				o.method = 'call_new_atom';
				o.data={type_text:o.data}
				o.sending = false;
				TOP.send = true;
			}
			if(o.action === 'save'){
				// console.log('saving ',TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{return x.label}));
				//  FIELDS TO VALIDATE

				let ftv = TOP.fields_editing.filter(i =>{return i.validates > 0 }).map(x=>{return x.label});
				console.log('validating', ftv,TOP.fields_editing);

				// *** VALIDO EL ARRAY OF ELEMENTS LABELS
				let vf = validate_click('save_edit',ftv);
				// console.log('valid',vf);
				if(!vf){
					myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'Datos incompletos o no validos',extra:''})
					break;
				}
				o.method = 'save_new_atom'
				o.data = {
					fields:TOP.fields_editing.filter(i =>{return i.vis_elem_type !== '-1'}).map(x=>{x.value = route_value_source(x.label);return x;}),
					type_text:TOP.current_type
				};
				console.log('saving edit',o);

				o.sending = false;
				TOP.send = true;
			}
		}else{
			if(o.action == 'call_response'){
				TOP.fields_editing = o.data.pcles;
				TOP.current_type = o.data.type;
				TOP.route = o.route;
				let x = edit_modal.create(o.data.pcles)
				x.title = "NUEVO "+o.data.title;
				TOP.curr_close_act = {method:'back'}
				TOP.curr_ok_act = {
					method:'call_new_atom',
					action:'save',
					sending:true
				}
				TOP.curr_close_act = {method:'light_back'};
				x.winmed = 'modal-dialog-centered modal-xl'
				mk_modal(x);
			}
			if(o.action == 'save_response'){
				$.blockUI({
					css: {
						border: 'none',
						padding: '15px',
						backgroundColor: '#000',
						'-webkit-border-radius': '10px',
						'-moz-border-radius': '10px',
						opacity: .5,
						color: '#fff'
					},
					message: 'Guardado OK',
					baseZ: 10000,
					timeout:1500
				});
				$('#my_modal').modal('hide');

				console.log('response from save', o);
				// FORCE RELOAD DE CLIENTES
				window.location.reload()
			}
		}

		break;

		// **** CONFIG EDITAR / REVISAR CONTRATO. ******
		case 'edit_element':
		if(o.sending){
			if(TOP.user_id = 484){
				o.method = 'edit_element';
				TOP.curr_elem_id = o.data.elem_id;
				TOP.send = true;
			}else{
				o.method = 'edit_element';
				TOP.curr_elem_id = o.data.elem_id;
				TOP.send = true;
			}
		}else{
			if(o.action === 'call_response'){
				console.log('editing call response',o);
				TOP.curr_edit = o;
				TOP.data = o.data;
				TOP.send = false;
				TOP.user_id = o.user_id;
				TOP.permisos = o.permisos;
				TOP.curr_elem_id = o.data.id
				//**** CONTRATO
				let not_editable = [];
				if(parseInt(o.user_id) === 484 || parseInt(o.user_id) === 501){
					// TODOS LOS CAMPOS SON EDITABLES
					not_editable =[]; //o.data.pcles.map(function(x){return x.label});
				}else{
					// TODOS LOS CAMPOS SON READONLY
					not_editable = o.data.pcles.map(function(x){return x.label});
				}
					// ALGUNOS CAMPOS EDITABLES OTROS NO
					//not_editable = ['cli_id','titular_id','cotitular_id','tasa_reintegro_id','prod_id','monto_total','monto_cta_1','current_ciclo','cant_ctas','cant_ctas_ciclo_2','indac','interes','anticipo','cant_ctas_restantes','frecuencia_ctas_refuerzo','plan_update_pending'];
				let contrato = editable_set.create(o.data.pcles,{'readonly':not_editable}).get_screen();
				contrato = (collapsed_panel.create({title:'DATOS DEL CONTRATO',id:'ctr',content:contrato})).get_screen();
				ownr_tit = o.data.owner_type+" "+o.data.owner_name
				datos_owner = (collapsed_panel.create({title:ownr_tit,id:'prod',content:editable_set.create(o.data.owner_props,{}).get_screen()})).get_screen();

				//*** TITULAR
				const tt = o.data.extra_data.filter(x=>{return x.titular_id});
				if(tt){
					// console.log('tt',tt)
					const t_tit = " TITULAR : "+ tt[0].titular_id.name;
					if(parseInt(o.user_id) === 484 || parseInt(o.user_id) === 501){
						not_editable = [];
					}else{
						not_editable = tt[0].titular_id.pcles.map(t=>{return t.label});
					}
					datos_tt = (collapsed_panel.create({title:t_tit,id:'titular',content:editable_set.create(tt[0].titular_id.pcles,{'readonly':not_editable}).get_screen()})).get_screen();
				}
				//*** COTITULAR
				let datos_ctt = '';
				const ctt = o.data.extra_data.filter(x=>{return x.cotitular_id});
				if(ctt.length > 0){
					const ct_tit = " CO-TITULARES : "+ ctt[0].cotitular_id.name;
					if(parseInt(o.user_id) === 484 || parseInt(o.user_id) === 501){
						not_editable = [];
					}else{
						not_editable = ctt[0].cotitular_id.pcles.map(t=>{return t.label});
					}
					datos_ctt = (collapsed_panel.create({title:ct_tit,id:'cotitular',content:editable_set.create(ctt[0].cotitular_id.pcles,{'readonly':not_editable}).get_screen()})).get_screen();
				}

				//*** const ctas RETORNA UN OBJ CON C_LOTE Y C_SERV
				const ctas = get_cuotas_for_edit(o.data);
				// *** TABLA DE CUOTAS
				let headings = {
					// 'event_id':'Event',
					'nro_cta':'Cta. Nro.',
					'monto_cta':'Monto',
					'fecha_vto':'Fecha Vto',
					'estado':'Estado',
					'monto_pagado':'Monto Pagado',
					'fec_pago':"Fecha de pago",
					'dias_mora':"Dias de Mora",
					'interes_mora':"intereses"
				};
				let contenido = {
					'container':'cuotas_card_body',
					'headings':headings,
					'items':ctas.c_lote,
					// 'total':o.data.cuotas.tot_pagado,
					extras: {
						'select_id':false,
						'caller':'edit_element',
						'editables':['estado','fecha_vto','monto_pagado','fec_pago','dias_mora','interes_mora'],'edit_call':'update_event'
					}
				};
				let cuotas_tbl = mk_table_edit_contrato.create(contenido);
				cuotas_lote = collapsed_panel.create({title:'CUOTAS DEL LOTE',id:'cuotas_lote',content:cuotas_tbl.get_screen()}).get_screen();
				// *** TABLAS DE SERVICIOS
				let xcs = '';
				for(let i = 0; i < ctas.c_serv.length ;i++){
					let headings = {
						// 'event_id':'Event',
						'nro_cta':'Cta. Nro.',
						'monto_cta':'Monto',
						'fecha_vto':'Fecha Vto',
						'estado':'Estado',
						'monto_pagado':'Pagado',
						'fec_pago':"Fecha de pago"
					};
					// console.log('setting servs',services)
					let contenido = {
						'container':'servs_card_body_'+i,
						'headings':headings,
						'items':ctas.c_serv[i]['itm_serv'],
						// 'total':services[i]['itm_tot'],
						extras: {
							'select_id':false,
							'caller':'edit_element',
							'editables':['estado','fecha_vto','monto_pagado','fec_pago','dias_mora','interes_mora'],'edit_call':'update_event'
						}
					};
					let srv_tbl = mk_table_edit_contrato.create(contenido);
					xcs  += collapsed_panel.create({title:'CUOTAS DEL SERVICIO: '+ctas.c_serv[i].name,id:'cuotas_servicio_'+i,content:srv_tbl.get_screen()}).get_screen();
				}
				//**** COMPROBANTES
				let cprs ='';
				if(o.data.comprobantes){
					let cpr_tbl = comprobantes_tbl.create(o.data.comprobantes,'comprobantes');
					cprs = collapsed_panel.create({title:'COMPROBANTES',id:'container_comprobantes',content:cpr_tbl}).get_screen();
				}

				//*** VENTANAS DE UPLOADED FILES
				let upld = '';cnt = '';
				// BOXES DE UPLOADED FILES
				cnt = uploaded_files_boxes.create(['web_cli','lote_data_gen']).get_screen();
				//  BOT SUBIR ARCHIVO
				cnt += "<div class=\'col d-flex flex-wrap p-2 \'><div class=\'row\'><button type='button' class=\'btn btn-primary\' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button></div></div>";
				upld = collapsed_panel.create({title:'ARCHIVOS GUARDADOS',id:'files_uploaded',content:cnt}).get_screen();
				//*** PRINT TO PANTALLA
				$('#my_modal').modal('hide');
				const top_marg = "<div class='row mt-5'></div>";
				$('#main_container').html(top_marg+datos_owner+contrato+datos_tt+datos_ctt+cuotas_lote+xcs+cprs+upld);
			}



			if(o.action == 'get_elem_id'){
				var mdl = get_element_input.create(o)
				mdl.title = 'Editar Contrato ';
				mk_modal(mdl);
				$('#ok_button').hide();
				$('#lote').autocomplete({
					source:  "configuracion/autocomplete_edit_elem",
					minLength: 1,
					response: function( event, ui) {
					},
					select: function(event, ui)
					{
						front_call({method:'edit_element',sending:true,data:{type:'Element',id:ui.item.id}})
					}
				});
				$('#lote').focus();
				TOP.send = false;
			}
			//  END CUOTAS DEL CONTRATO
		}
		break;
		case 'set_config_curr_state':
		// console.log('data',TOP);
		o.elem_id = TOP.curr_edit.elm_id;
		o.lote_nom = TOP.curr_edit.lname;
		o.user_id = TOP.user_id;
		// console.log('data',o);
		TOP.send = true;
		break;
		case 'config_update_plan':
		if(o.sending){
			console.log('sending' , o)
			if(o.action == 'update_lote'){
				TOP.route = 'clientes/';
				o.method = 'call_update_plan';

			}
			if(o.action == 'update_srvc'){
				TOP.route = 'clientes/';
				o.method = 'call_update_service';
			}
			// o.data.arbitrary_call = true;
			o.sending = false;
			TOP.send= true;
		}
		else{
			TOP.send = false;
			//  RESPUESTA DATOS DEL PLAN SOLO ID DE ELEMENTS DE LOTE Y SERVICIOS
			if(o.action == 'response'){
				TOP.route = o.route;
				console.log('config response to update plan ',o)
				$('#my_modal').modal('hide');
				//** XCV
				// SI EL LOTE TIENE PRESTAMOS PERMITE SELECCIONAR ENTRE PRESTAMOS O EL LOTE
				$("#main_container").html(jb_views.create({title:"ACTUALIZAR PLAN",id:'cfg_jb_cont'}));

				o.data.map(function(s){$("#cfg_jb_cont").append(btn_views.create({call:s.call,tag:s.tag}))});
				// var cp = dialog_update_plan.create(o);
				// TOP.curr_elem_id = o.elem_id;
				// TOP.curr_ok_act = {
				// 	method:'set_cambio_financ_plan',
				// sending:true
				// }

				// cp.title = 'Revision de Plan de Financiación ';
				// cp.winmed = 'modal-dialog-centered modal-dialog-centered modal-xl';
				// // mk_modal(cp);
			}
			// MODAL PARA OBENER EL NUMERO DEL LOTE
			if(o.action == 'get_elem_id'){
				var mdl = get_element_input.create(o)
				mdl.title = 'Revisar Plan de Financiacion';
				mk_modal(mdl);
				$('#ok_button').hide();
				$('#lote').autocomplete({
					source:  "clientes/autocomplete_get_elements",
					minLength: 3,
					response: function( event, ui) {
					},
					select: function(event, ui)
					{
						front_call({method:'config_update_plan',sending:true,data:{elm_id:ui.item.id}})
					}
				});
				$('#lote').focus();
				TOP.send = false;
			}
		}
		break;
		case 'cancelar_serv':
		if(o.sending){
			console.log('sending' , o)
			if(o.action == 'confirma_cancelar_serv'){
				TOP.route = 'clientes/';
				o.method = 'cancelar_serv';
			}
			// if(o.action == 'update_srvc'){
			// 	TOP.route = 'clientes/';
			// 	o.method = 'call_update_service';
			// }
			// o.data.arbitrary_call = true;
			o.sending = false;
			TOP.send= true;
		}
		else{
			TOP.send = false;
			//  RESPUESTA DATOS DEL PLAN SOLO ID DE ELEMENTS DE LOTE Y SERVICIOS
			if(o.action == 'response'){
				TOP.route = o.route;
				console.log('config response to update plan ',o)
				$('#my_modal').modal('hide');
				//** XCV
				// SI EL LOTE TIENE PRESTAMOS PERMITE SELECCIONAR ENTRE PRESTAMOS O EL LOTE
				$("#main_container").html(jb_views.create({title:"<h3>CONFIRMAR CANCELACION DE SERVICIO</h3><h3>"+o.title+"</h3>",id:'cfg_jb_cont'}));
				o.data.map(function(s){$("#cfg_jb_cont").append(btn_views.create({call:s.call,tag:s.tag}))});
			}
			if(o.action == 'response_cancelado'){
				front_call({
					method:'get_elements',
					sending:true,
					data:{elm_id:o.elem_id}
				});
			}
			// MODAL PARA OBENER EL NUMERO DE contrato
			if(o.action == 'get_elem_id'){
				var mdl = get_element_input.create(o)
				mdl.title = 'Cancelar Servicio por falta de pago';
				mk_modal(mdl);
				$('#ok_button').hide();
				$('#lote').autocomplete({
					source:  "clientes/autocomplete_get_elements",
					minLength: 3,
					response: function( event, ui) {
					},
					select: function(event, ui)
					{
						front_call({method:'cancelar_serv',sending:true,data:{elm_id:ui.item.id}})
					}
				});
				$('#lote').focus();
				TOP.send = false;
			}
		}
		break;

		// **** CAJA
		case 'registro_operacion':
		if(o.sending){
			if(o.action == 'call' || o.action == undefined){
				TOP.count_centro_costos_list = 1;
				TOP.cctos_id= [];
				TOP.route = 'caja/';
				o.method = 'call_asiento';
				o.user_id = TOP.user_id;
				TOP.send = true;
			}
			if(o.action == 'save'){
				// VALIDAR CONTENIDO  DE VENTANA DE INPUT
				var f=['tipo_asiento','cuenta','imputacion','contraparte','monto','contraparte_select'];
				console.log('valid regop ', validate_click('reg_op',f));
				console.log('valid regop ', validate_cctos(''));
				if(validate_click('reg_op',f) && validate_cctos('')){
					TOP.route = 'caja/';
					o.method = 'save_asiento';
					if($('#contraparte_select').val() == 'CLIENTE'){
						var cl = $('#contraparte').val();
						var prv = '';
					}else{
						var cl = '';
						var prv = $('#contraparte').val();
					}
					o.fields = {
						'tipo_asiento':$('#tipo_asiento').val(),
						'cuentas_id':$('#cuenta').val(),
						'cuenta_imputacion_id':$('#imputacion').val(),
						'proveedor_id':prv,
						'cliente_id':cl,
						'nro_comprobante':$('#numero_comprobante').val(),
						'monto':$('#monto').val(),
						'observaciones':$('#observaciones').val(),
						'operador_usuario_id':TOP.user_id,
						'origen':'registro_operacion'
					}
					o.ccd = TOP.selected_ccd;
					o.op_id = TOP.asiento_caja.op_id,
					TOP.send = true;
				}else{
					myAlert({container:'#result_footer',type:'danger',tit:'Error! datos no validos',msg:'Revisa la operación y vuelve a intentar',extra:''})
					o.sendign = false;
					TOP.send = false;
				}
			}
		}else{
			TOP.send = false;
			if(o.action == 'response'){
				TOP.route = o.route;
				if(o.result > 0 ){
					$('#result_footer').html(alert.create({type:'success',tit:'Registro de operaciones' ,msg:"Operación registrada Nro: "+o.result}).get_screen());
					$('#bot_guardar').hide();
				}else{
					$('#result_footer').html(alert.create({type:'danger',tit:'Registro de operaciones',msg:"Error de base de datos.",extra:'no_autohide'}).get_screen());
					$('#bot_guardar').hide();
				}
				setTimeout(function(){
					$('#modal-footer-msgs').html('');
					front_call({'method':'registro_operacion','sending':true,'action':'call'})

				},3500);
				// $('#bot_volver').show();
				// $('#bot_volver').click(function(){});
			}
			// if(o.action == 'step_back'){
			// 	// console.log('st',TOP)
			// 	o.method = 'step_back'
			// 	o.op_id = [TOP.asiento_caja.op_id];
			// 	TOP.send = true;
			// }

		}
		break;
		case 'pase_entre_cajas':
		if(o.sending){
			if(o.action == 'call' || o.action == undefined){
				TOP.route = 'caja/';
				o.method = 'call_pase_entre_cajas';
				o.user_id = TOP.user_id;
				TOP.send = true;
			}
			if(o.action == 'save'){
				// VALIDAR CONTENIDO  DE VENTANA DE INPUT
				var f=['cuenta_egreso','cuenta_ingreso','monto_egreso','monto_ingreso'];
				if(validate_click('pase_caja',f)){
					var op_b = parseInt(TOP.pase_caja.op_nro)+1;
					TOP.route = 'caja/';
					o.method = 'save_pase_entre_cajas';
					o.egreso = {
						'tipo_asiento':"EGRESOS",
						'cuentas_id':$('#cuenta_egreso').val(),
						'cuenta_imputacion_id':203, // id de transferencia entre cajas
						'cta_contraparte_id': $('#cuenta_ingreso').val(),
						'monto':$('#monto_egreso').val(),
						'nro_comprobante':$('#numero_comprobante').val(),
						'observaciones':$('#observaciones').val(),
						'operador_usuario_id':TOP.user_id,
						'origen':'pase_entre_cajas'
					}
					o.ingreso = {
						'tipo_asiento':"INGRESOS",
						'cuentas_id':$('#cuenta_ingreso').val(),
						'cuenta_imputacion_id':203, // id de transferencia entre cajas
						'cta_contraparte_id': $('#cuenta_egreso').val(),
						'monto':$('#monto_ingreso').val(),
						'nro_comprobante':$('#numero_comprobante').val(),
						'observaciones':$('#observaciones').val(),
						'operador_usuario_id':TOP.user_id,
						'origen':'pase_entre_cajas'
					}
					// o.ccd = TOP.selected_ccd;
					// console.log('saving',o)
					TOP.send = true;
				}else{
					myAlert({container:'#result_footer',type:'danger',tit:'Error! datos no validos',msg:'Revisa la operación y vuelve a intentar',extra:''})
					o.sendign = false;
					TOP.send = false;
				}
			}

		}else{
			if(o.action == 'response'){
				TOP.route = o.route;
				if(o.result == 'OK'){
					$('#result_footer').html(alert.create({type:'success',tit:'Transferencia entre cajas' ,msg:"Operación guardada correctamente"}).get_screen());
					$('#bot_guardar').hide();

				}else{
					$('#result_footer').html(alert.create({type:'danger',tit:'Transferencia entre cajas',msg:"Error de base de datos."}).get_screen());
					$('#bot_guardar').hide();
				}
				setTimeout(function(){
					$('#modal-footer-msgs').html('');
					front_call({'method':'pase_entre_cajas','sending':true,'action':'call'})

				},3500);
				// $('#bot_volver').show();
				// $('#bot_volver').click(function(){});
			}
			TOP.send = false;
		}
		break;
		case 'arqueo_cajaybancos':
		if(o.sending){
			o.method = 'planilla_caja';
			// if(TOP.user_id == 484){
			// 	o.method = 'planilla_caja_test';
			// }

			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					caja:$('#caja').val(),
					fec_desde:$('#fec_desde').val(),
					fec_hasta:$('#fec_hasta').val()
				}
				TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			if(o.action == 'refresh'){
				// o.data = {
				// 	caja:$('#caja').val(),
				// 	fec_desde:$('#fec_desde').val(),
				// 	fec_hasta:$('#fec_hasta').val()
				// }
				TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			console.log('arq',o)

			TOP.send = true;
		}else{
			if(o.action == 'response'){
				console.log('response de listado de cajas',o)
				TOP.route = o.route;
				if(o.hasOwnProperty('data')){
					history.add(o);
					$('#my_modal').modal('hide');
					// LISTADO UNA CAJA MAIN Y CAJAS VINCULADAS
					if(o.data.length === 1){
						// if(TOP.user_id == 484){caja_unica_2(o.data[0]['list']);}
						// else{caja_unica(o.data[0]['list']);}
						caja_unica_2(o.data[0]['list']);
					}
					// LISTADO MULTIPLES CAJAS
					if(o.data.length > 1){
						let tot_saldo = 0;
						let tot_in = 0;
						let tot_out = 0;
						let cajas_rows = [];

						for (let i = 0; i < o.data.length; i++) {
							if(o.data[i]['list']){
								let ingre = 0;
								let egre = 0;

								// INGRESOS
								if(o.data[i]['list']['ingresos'].length > 0){
									if(o.data[i]['list']['ingresos'].length === 1){
										ingre = parseFloat(o.data[i]['list']['ingresos'][0].monto);
									}else{
										const tr = o.data[i]['list']['ingresos'].map(function(a){return parseFloat(a.monto)});
										ingre = tr.reduce(function(a,b){return a+b});
									}
								}
								// EGRESOS
								if(o.data[i]['list']['egresos'].length > 0){
									if(o.data[i]['list']['egresos'].length === 1){
										egre = parseFloat(o.data[i]['list']['egresos'][0].monto);
									}else{
										const x = o.data[i]['list']['egresos'].map(function(a){return parseFloat(a.monto)});
										egre = x.reduce(function(a,b){return a+b});
									}
								}
								cajas_rows.push({
									'caja_nom':o.data[i]['list']['caja_nom'],
									'saldo_prev':parseFloat(o.data[i]['list']['saldo']),
									'ingresos':ingre,
									'egresos':egre,
									'total':(parseFloat(o.data[i]['list']['saldo'])+ingre)-egre,

								});
								tot_in += ingre;
								tot_out += egre;
								tot_saldo += parseFloat(o.data[i]['list']['saldo']);
							}

						}

						let c = hcaja1.create({
							'caja_nom':'Multiple',
							'fec_desde':o.data[0]['list'].fec_desde,
							'fec_hasta':o.data[0]['list'].fec_hasta,
							'ingresos':[{'monto':tot_in}],
							'egresos':[{'monto':tot_out}],
							'saldo':tot_saldo
						});
						// c.print_button = true;
						// c.print_option = 'print_lcaja'
						var wrapper_caja_2 = "<div class=\'row mt-5\'></div>"+(panel.create(c)).get_screen();
						$('#main_container').html(wrapper_caja_2);
						// console.log('cajas',cajas_rows);
						caja_multiple(cajas_rows);
					}
				}
			}else{
				o.selects = TOP.selects;
				// // console.log('s',o.selects)
				let mdl = dialog_arqueo.create(o)
				TOP.curr_ok_act = {
					method:'arqueo_cajaybancos',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mdl.title = 'Planilla de Caja o Banco';
				mdl.winmed = 'modal-dialog-centered modal-xl';
				$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
				mk_modal(mdl);
			}
			TOP.send = false;
		}
		break;
		case 'edit_op':
		if(o.sending){
			console.log('edit op',o)
			TOP.route = 'caja/';
			o.sending = false;
			TOP.send = true;
		}else{
			switch(o.action){
				case 'response':
				console.log(TOP.history);
				TOP.route = o.route;
				TOP.curr_ok_act = {
					method:'update_op',
					sending:true,
					id:o.result.id,
					resp_method:TOP.history[0]['method'],
					resp_data:{
						caja:[TOP.history[0]['data'][0]['caja_id']],
						fec_desde:TOP.history[0]['data'][0]['list']['fec_desde'],
						fec_hasta:TOP.history[0]['data'][0]['list']['fec_hasta']
					}
				};

				TOP.curr_srv_resp = o.result
				let x = op_caja.create(o.result)
				x.title = "Detalle Operación de Caja";
				x.winmed = 'modal-dialog-centered modal-xl';
				$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
				mk_modal(x);

				break;
			}
		}
		break;
		case 'update_op':
		if(o.sending){
			TOP.route = 'caja/';
			o.sending = false;
			o.data = {
				cuentas_id:$('#cuentas').val(),
				cuenta_imputacion_id: $('#cuentas_imputacion').val(),
				proveedor_id:($('#proveedor').val()?$('#proveedor').val():0),
				id:TOP.curr_ok_act.id,
				resp_method:TOP.curr_ok_act.resp_method,
				resp_data:TOP.curr_ok_act.resp_data
				// resp_action:'refresh',
				// resp_sending:true
			}
			console.log('update op',o)
			TOP.send = true;
		}
		break;
		case'anular_op':
		if(o.sending){
			TOP.route = 'caja/';
			o.sending = false;
			o.user_id = TOP.user_id;
			console.log('sending anular',o)
			TOP.send = true;
		}else{
			TOP.curr_ok_act = {
				method:'anular_op',
				sending:true,
				data: o.data,
				list_refresh: TOP.list_refresh
			};
			c = alert.create({
				tit:"Anular Operación de Caja",
				msg:"Confirma que desea anular la presente operación?",
				type: 'danger'
			})
			$('#modal-footer-msgs').html(c.get_screen());
		}
		break;
		case 'movs_de_cajas':
		if(o.sending){
			TOP.route = 'caja/';
			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					dt_in:$('#fec_desde').val(),
					dt_out:$('#fec_hasta').val(),
					tipo:$('#tipo_asiento').val(),
				}
				// TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			// console.log('movs_de_cajas sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				//  RESPUESTA DE REPORT
				$('#my_modal').modal('hide');
				$('#navbar_msg').html(o.tit);
				$(document).attr("title", o.tit);

				var tbl_id =  'tbl_egr_caja';
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table(tbl_id,{
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tc = detect_tot_col(tbl_id,'Importe');
							if(tc){
								var api = this.api();
								TOP.current_tot_col_datatables_api = api;
								TOP.current_tot_col = tc;
							}
							$( api.column(tc).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							// SUMAR INTERESES
							// var ti = detect_tot_col(tbl_id,'Intereses');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
							// // sumar columna total
							// var ti = detect_tot_col(tbl_id,'Total');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
						}
					});
				});
			}else{
				let mdl = dialog_date_range.create(o)
				TOP.curr_ok_act = {
					method:'movs_de_cajas',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mdl.title = 'Reporte Movimientos de Cajas';
				mdl._screen += "<div class=\'row d-flex ml-3\'>"+select_obj.create({label:'tipo_asiento',value:'INGRESOS',title:"Tipo "}).get_screen()+"</div>";
				mk_modal(mdl);
			}
		}
		break;
		case 'pagos_online':
		TOP.route = 'caja/';
		if(o.sending){
			TOP.send = true;
		}else{
			if(o.action === 'response'){
				console.log('response from pagos online',o);
				$('#navbar_msg').html(o.tit);
				$(document).attr("title", o.tit);

				var tbl_id =  'tbl_pagos_online';
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table_2(tbl_id,{
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							// var tc = detect_tot_col(tbl_id,'Importe');
							// if(tc){
							// 	var api = this.api();
							// 	TOP.current_tot_col_datatables_api = api;
							// 	TOP.current_tot_col = tc;
							// }
							// $( api.column(tc).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							// SUMAR INTERESES
							// var ti = detect_tot_col(tbl_id,'Intereses');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
							// // sumar columna total
							// var ti = detect_tot_col(tbl_id,'Total');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
						}
					});
				});
			}
		}
		break;
		case 'rescindidos':
		if(o.sending){
			console.log('send rescindidos');
			TOP.route = 'reportes/';
			TOP.send = true;
		}else{
			if(o.action === 'response'){
				$('#navbar_msg').html(o.tit);
				$(document).attr("title", o.tit);
				var tbl_id =  'tbl_pagos_online';
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table_3(tbl_id,{});
				});
			}
		}
		break;
		case 'lr1':
		if(o.sending){
			console.log('send repo_1');
			TOP.route = 'reportes/';
			TOP.send = true;
		}else{
			if(o.action === 'response'){
				console.log('resp lr1',o);
				$('#navbar_msg').html(o.tit);
				$(document).attr("title", o.tit);
				var tbl_id =  'tbl_repo_1';
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table_3(tbl_id,{});
				});
			}
		}
		break;

		// **** REPORTS
		case 'repo_tool':
		TOP.route = 'reportes/'
		if(o.sending){
			if(o.action === 'call'){
				TOP.send = true;
			}
		}else{
			if(o.action === 'call_response'){
				// console.log('report tool response',o);
				// let tt = otbl_editable.create(o.data,'tbl_'+o.method);
				//	console.log('tt',tt);
				// let edit_call = (o.hasOwnProperty('edit_call')?o.edit_call:null)
				// let dtbl_id = 'tbl_'+o.method;
				history.add(o)
				$(document).attr("title", o.title);
				$('#navbar_msg').html(o.title);
				TOP.filter_columns = o.data.filter;
				TOP.curr_filters = [];
				TOP.actions_col_index = -1 // index num de la columa de acciones si es -1 no pone col
				//*** SCREEN ELEMENTS
				let scrn = "<div class='mt-5'><div id=\'ft_and_tb_wrapper\' class='row mt-3'>";
				scrn += "<div id=\'filter_column\' class=\'col-12 col-sm-12 col-md-2 p-0\'>";
				scrn +="</div>";
				scrn += "<div id=\'filtered_tbl_container\' class=\'col-12 col-sm-12 col-md-10 p-1\'></div>";
				scrn +="<div></div>";
				let fbox = data_box_small.create({label:'Filtrar',id:"dbx_filter",value:filter.create(o.data.filter),collapsed:true}).get_screen();
				let toast = "<div id=\toast_cnt\' class=\'row col-12 d-flex flex-wrap justify-content-start\'></div>";
				$('#main_container').html(scrn);
				$('#filter_column').html(toast+fbox);
				$('#filtered_tbl_container').html(f_tbl.create('f_tbl',o.data));
				$(document).ready(function () {
					TOP.curr_f_table = init_f_tbl('f_tbl');
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						$('#dbx_clp_area').collapse({
							toggle: true
						})
						$('#dbx_clp_area').collapse('hide')
					}
				});
			}
		}
		break;
		// ********************************


		case 'repo_en_mora':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				var data_boxes = '';
				data_boxes += data_box.create({id:'gttl',label:"Total $",value:parseInt(o.gttl).toLocaleString()}).get_screen()
				data_boxes += data_box.create({id:'impagas',label:"Cuotas Impagas:",value:parseInt(o.impagas).toLocaleString()}).get_screen()
				data_boxes += data_box.create({id:'tot_elmts',label:"Total Contratos:",value:parseInt(o.tot_elmts).toLocaleString()}).get_screen()
				data_boxes += data_box.create({id:'elmts_con_mora',label:"Contratos con mora:",value:parseInt(o.elmts_con_mora).toLocaleString()}).get_screen()
				var c = {
					title:'Lotes con cuotas en mora',
					content:data_boxes
				}
				var pnl = (panel.create(c)).get_screen();
				$('#main_container').html(pnl);
				var headings = {
					'cli_name':'Cliente',
					'total':'Monto Adeudado',
					'cant_events':'Cant. Cuotas',
					'ordnums':'Nro. de Cuota',
					'elem_id':'Detalle'
				};
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':o.drill_data,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				var tbl = mk_table_gen2.create(contenido);
				var tbltit = "<hr/><div class=\'row\'><h3 class=\'p-3\'>Contratos con cuotas en mora</h3></div>"
				$('#main_container').append(tbltit);
				$('#main_container').append(tbl.get_screen());
				$(document).ready(function (){
					init_table('tbl_'+o.method,{
						"order": [[ 4, "desc" ]],
						"lengthMenu": [[10, 50,100,200, -1], [10, 50, 100,200, "Todos"]],
						language: TOP.DataTable_lang
					});
				})
			}
		}
		break;
		case 'mora_3':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				// var data_boxes = '';
				// data_boxes += data_box.create({id:'gttl',label:"Total $",value:parseInt(o.gttl).toLocaleString()}).get_screen()
				// data_boxes += data_box.create({id:'impagas',label:"Cuotas Impagas:",value:parseInt(o.impagas).toLocaleString()}).get_screen()
				// // data_boxes += data_box.create({id:'tot_elmts',label:"Total Contratos:",value:parseInt(o.tot_elmts).toLocaleString()}).get_screen()
				// data_boxes += data_box.create({id:'elmts_con_mora',label:"Total Contratos: ",value:parseInt(o.elmts_con_mora).toLocaleString()}).get_screen()
				// var c = {
				// 	title:'Lotes con hasta 3 cuotas en mora ',
				// 	content:data_boxes
				// }
				// var pnl = (panel.create(c)).get_screen();
				// $('#main_container').html(pnl);
				const title = 'Contratos con hasta 3 cuotas en mora';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);


				var headings = {
					'cli_name':'Cliente',
					'total':'Monto Adeudado',
					'cant_events':'Cant. Cuotas',
					'ordnums':'Nro. de Cuota',
					'elem_id':'Acciones'
				};
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':o.drill_data,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				$('#main_container').html("<div class='container mt-5'>"+mk_table_gen2.create(contenido).get_screen()+"</div>");
				$(document).ready(function (){
					init_table('tbl_'+o.method,{
						filter_exc : 1, // CANTIDAD DE COLUMNAS A EXCLUIR DEL FILTRADO SIRVE PARA EXCLUIR LA COLUMNA DE ACCIONES O DETALLE
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tbl_id = 'tbl_'+o.method;
							var tc = detect_tot_col(tbl_id,'Monto Adeudado');
							var cant_ctas = detect_tot_col(tbl_id,'Cant. Cuotas');
							if(tc){
								var api = this.api();
								$(api.column(tc).footer()).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {} ).data().sum(), "", 0, ".", ",")+"</th>");
								$(api.column(cant_ctas).footer()).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( cant_ctas, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
						}
					});
				});
			}
		}
		break;
		case 'mora_mas_de_3':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				// var data_boxes = '';
				// data_boxes += data_box.create({id:'gttl',label:"Total $",value:parseInt(o.gttl).toLocaleString()}).get_screen()
				// data_boxes += data_box.create({id:'impagas',label:"Cuotas Impagas:",value:parseInt(o.impagas).toLocaleString()}).get_screen()
				// // data_boxes += data_box.create({id:'tot_elmts',label:"Total Contratos:",value:parseInt(o.tot_elmts).toLocaleString()}).get_screen()
				// data_boxes += data_box.create({id:'elmts_con_mora',label:"Total Contratos: ",value:parseInt(o.elmts_con_mora).toLocaleString()}).get_screen()
				// var c = {
				// 	title:'Contratos con mas de 3 cuotas en mora ',
				// 	content:data_boxes
				// }
				// var pnl = (panel.create(c)).get_screen();
				const title = 'Contratos con mas de 3 cuotas en mora';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);
				// $('#main_container').html(pnl);
				var headings = {
					'cli_name':'Cliente',
					'total':'Monto Adeudado',
					'cant_events':'Cant. Cuotas',
					'ordnums':'Nro. de Cuota',
					'elem_id':'Detalle'
				};
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':o.drill_data,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				$('#main_container').html("<div class='container mt-5'>"+mk_table_gen2.create(contenido).get_screen()+"</div>");
				$(document).ready(function (){
					init_table('tbl_'+o.method,{
						filter_exc : 1, // CANTIDAD DE COLUMNAS A EXCLUIR DEL FILTRADO SIRVE PARA EXCLUIR LA COLUMNA DE ACCIONES O DETALLE
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tbl_id = 'tbl_'+o.method;
							var tc = detect_tot_col(tbl_id,'Monto Adeudado');
							var cant_ctas = detect_tot_col(tbl_id,'Cant. Cuotas');
							if(tc){
								var api = this.api();
								$(api.column(tc).footer()).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {} ).data().sum(), "", 0, ".", ",")+"</th>");
								$(api.column(cant_ctas).footer()).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( cant_ctas, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
						}
					});
				});

			}
		}
		break;
		case 'cobranza_futura_2':
		if(o.sending){
			// o.sending = false;
			console.log('call cf2',o);
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				var data_boxes = '';
				data_boxes += data_box.create({id:'tot_gen',label:"Valor Financiado $",value:accounting.formatMoney(parseInt(o.tot_gen), "", 0, ".", ",")}).get_screen();
				// data_boxes += data_box.create({id:'fn_1pg',label:"Con Saldos a Financiar $&nbsp;&nbsp; <button type=\"button\" onClick=graf_cfut_fn() class=\"btn btn-primary\"><i class=\"icon ion-md-stats\"></i></button>",value:parseInt(o.ttfn_1pago).toLocaleString()}).get_screen();
				data_boxes += data_box.create({id:'tot_1p',label:"Valor Presente $",value:accounting.formatMoney(parseInt(o.tot_1pg), "", 0, ".", ",")}).get_screen();
				data_boxes += select_obj.create({label:'barrio',title:'Barrios'}).get_screen();
				data_boxes += '<div class=\'row\'><div class\'col d-flex\'><div class=\'img-fluid. max-width: 100%\'id=\'bc_container\'></div></div></div>';
				var c = {
					title:"Reporte Cobranza Futura  "+ (o.barrio ? o.barrio :'-') +"  Cant. Contratos : " + o.contratos.length,
					content: data_boxes
				}
				var pnl = (panel.create(c)).get_screen();
				$('#main_container').html(pnl);
				var headings = {
					'Codigo Lote':'Lote',
					'Barrio':'Barrio',
					'Valor Financiado':'Valor Financiado',
					'Valor Presente':'Valor Presente'
				};
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':o.contratos,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				var tbl = mk_table_gen2.create(contenido);
				$(document).attr("title", 'Cobranza Futura');
				// $('#navbar_msg').html('Cobranza Futura');
				// $('#main_container').append(tbltit);
				$('#main_container').append(tbl.get_screen());
				$(document).ready(function (){
					init_table('tbl_'+o.method,{
						filter_exc:'all',
						columnDefs:[
							{ "type": "num-fmt", "targets": 2},
							{ "type": "num-fmt", "targets": 3}
						],
						drawCallback: function(){
							//  SUMAR COLUMNAS DE MONTO
							var tbl_id = 'tbl_'+o.method;
							var api = this.api();
							$( api.column(2).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( 2, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							$( api.column(3).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( 3, {} ).data().sum(), "", 0, ".", ",")+"</th>");
						}
					});
				});
				// var md = [{date:'2010-01',value:1000},{date:'2010-02',value:5000}]
				// TOP.tooltip_data_graf_full = o.mmu;
				// TOP.tooltip_data_graf_fn = o.mmu_fn;
				// TOP.graf_mes_a_mes_gen = o.mes_a_mes;
				// TOP.graf_mes_a_mes_fn = o.mm_fn;
				$('#barrio').val(o.selection);
				$('#barrio').change(function(){
					front_call({'method':'cobranza_futura_2','sending':true,'action':'call',data:{barrio:$('#barrio').val(),elm_type:1}});
				})
			}
		}
		break;




		case 'cobranza_fut':
		if(o.sending){
			// o.sending = false;
			console.log('call cf',o);

			// if(!o.hasOwnProperty('data')){
			// 	o.data = {barrio:-1,elm_type:1};
			// }

			if(!o.data.hasOwnProperty('barrio')){
				o.data.barrio = ($('#barrio').val() === -1 ?$('#barrio').val():-1);
			}

			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);

				var data_boxes = '';
				data_boxes += data_box.create({id:'tot_gen',label:"Total General $ &nbsp;&nbsp;<button type=\"button\" onClick=graf_cfut_full() class=\"btn btn-primary\"><i class=\"icon align-bottom ion-md-stats\"></i></button>",value:parseInt(o.tot_gen).toLocaleString()}).get_screen();
				data_boxes += data_box.create({id:'fn_1pg',label:"Con Saldos a Financiar $&nbsp;&nbsp; <button type=\"button\" onClick=graf_cfut_fn() class=\"btn btn-primary\"><i class=\"icon ion-md-stats\"></i></button>",value:parseInt(o.ttfn_1pago).toLocaleString()}).get_screen();
				data_boxes += data_box.create({id:'tot_1p',label:"Valor Presente $",value:parseInt(o.tot_1pg).toLocaleString()}).get_screen();
				data_boxes += select_obj.create({label:'barrio',title:'Barrio'}).get_screen();

				data_boxes += '<div class=\'row\'><div class\'col d-flex\'><div class=\'img-fluid. max-width: 100%\'id=\'bc_container\'></div></div></div>';
				var c = {
					title:'Reporte Cobranza Futura  -  Cant. Contratos : '+o.contratos.length,
					content: data_boxes
				}
				var pnl = (panel.create(c)).get_screen();
				$('#main_container').html(pnl);
				var headings = {
					'cliente':'Cliente',
					'lote':'Lote',
					'barrio':'Barrio',
					'vt':'Monto A Cobrar',
					'vp':'Monto 1 Pago'
				};
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':o.contratos,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				var tbl = mk_table_gen2.create(contenido);
				$(document).attr("title", 'Cobranza Futura');
				// $('#navbar_msg').html('Cobranza Futura');
				// $('#main_container').append(tbltit);
				$('#main_container').append(tbl.get_screen());
				$(document).ready(function (){
					init_table('tbl_'+o.method,{
						filter_exc:'all',
						columnDefs:[
							{ "type": "num-fmt", "targets": 3},
							{ "type": "num-fmt", "targets": 4}
						],
						drawCallback: function(){
							//  SUMAR COLUMNAS DE MONTO
							var tbl_id = 'tbl_'+o.method;
							var api = this.api();
							$( api.column(3).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( 3, {} ).data().sum(), "", 0, ".", ",")+"</th>");
							$( api.column(4).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( 4, {} ).data().sum(), "", 0, ".", ",")+"</th>");
						}
					});
				});
				// var md = [{date:'2010-01',value:1000},{date:'2010-02',value:5000}]
				TOP.tooltip_data_graf_full = o.mmu;
				TOP.tooltip_data_graf_fn = o.mmu_fn;
				TOP.graf_mes_a_mes_gen = o.mes_a_mes;
				TOP.graf_mes_a_mes_fn = o.mm_fn;
				$('#barrio').val(o.selection);
				$('#barrio').change(function(){
					front_call({'method':'cobranza_fut','sending':true,'action':'call',data:{barrio:$('#barrio').val(),elm_type:1}});
				})


			}
		}
		break;
		case 'ctas_pagas_xmes_xcli':
		if(o.sending){
			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					fec_desde:$('#fec_desde').val(),
					fec_hasta:$('#fec_hasta').val()
				}
				// TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			console.log('cta pagas repo sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				$('#my_modal').modal('hide');
				history.add(o);
				let headings = ['Mes:     '];
				for (var i = 0; i < o.months.length; i++){
					headings.push(o.months[i]['tit_month']);
				}
				let row_cnt = [
					'ttl_activos',
					'ttl_cl_con_pagos',
					'percent_cl_con_pagos',
					'ttl_ctas_pagadas',
					'ctas_pagas_x_lt_activo',
					'ctas_pagas_x_lt_pago'
				];
				let row_tit = [
					'Total clientes activos',
					'Total clientes que abonaron',
					'Clientes que abonaron  ',
					'Cantidad de cuotas pagadas',
					'Ctas pagas por lote ',
					'Ctas pagas por pago ',
				];
				let filas = [];
				for (let r = 0; r < row_cnt.length; r++){
					x1 = [row_tit[r]];
					for (let h = 0; h < o.months.length; h++) {
						x1.push(o.months[h][row_cnt[r]])
					}
					filas.push(x1)
				}
				var contenido = {
					'table_id':'tbl_'+o.method,
					'container':'panel_table_container',
					'headings':headings,
					'items':filas,
					'caller':o.method,
					extras: {
						'select_id':false
					}
				};
				var tbl = tbl_farr.create(contenido);
				var tbltit = "<div class=\'row\'><h3 class=\'p-3\'>Cuotas pagas por mes</h3></div>"
				$('#navbar_msg').html('Cuotas pagas por mes');
				$('#main_container').html(tbltit);
				$('#main_container').append(tbl.get_screen());
				// $('#tbl_'+o.method).DataTable({
				// 	language: TOP.DataTable_lang
				// });
			}else{
				// o.selects = TOP.selects;
				// // console.log('s',o.selects)
				let mdl = dialog_date_range.create(o)
				TOP.curr_ok_act = {
					method:'ctas_pagas_xmes_xcli',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				// mdl.title = 'Reporte Cuotas Pagas Por Mes';
				// mdl.winmed = 'modal-dialog-centered modal-xl';
				// $('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
				mk_modal(mdl);
			}
		}
		break;

		case 'ctas_pagas_gen':
		if(o.sending){
			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					fec_desde:$('#fec_desde').val(),
					fec_hasta:$('#fec_hasta').val()
				}
				// TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			console.log('cta pagas gen sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				//  RESPUESTA DE REPORT
				$('#my_modal').modal('hide');
				const title = 'Cuotas Pagadas ';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);

				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table(tbl_id,{
						filter_exc : 3, // CANTIDAD DE COLUMNAS A EXCLUIR DEL FILTRADO SIRVE PARA EXCLUIR LA COLUMNA DE ACCIONES O DETALLE
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tc = detect_tot_col(tbl_id,'Monto');
							if(tc){
								var api = this.api();
								$( api.column(tc).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {page:'current'} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
							// SUMAR INTERESES
							var ti = detect_tot_col(tbl_id,'Intereses');
							if(ti){
								var api = this.api();
								$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
							// sumar columna total
							var ti = detect_tot_col(tbl_id,'Total');
							if(ti){
								var api = this.api();
								$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
						}
					});
				});
			}else{
				// o.selects = TOP.selects;
				// // console.log('s',o.selects)
				let mdl = dialog_date_range.create(o)
				TOP.curr_ok_act = {
					method:'ctas_pagas_gen',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mdl.title = 'Reporte Cuotas Lote Pagadas';
				// mdl.winmed = 'modal-dialog-centered modal-xl';
				// $('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
				mk_modal(mdl);
			}
		}
		break;
		//****** SERVICIOS PAGOS POR MES POR RANGO MESES
		case 'ctas_pagas_srv':
		if(o.sending){
			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					fec_desde:$('#fec_desde').val(),
					fec_hasta:$('#fec_hasta').val()
				}
			}
			console.log('cta pagas servicios sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				// **** TABLA RESULTADO
				$('#my_modal').modal('hide');
				$(document).attr("title", o.tit);
				$('#navbar_msg').html(o.tit);
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,'tbl_rep_srv_pgd')+"</div>");
				$(document).ready(function () {
					init_table('tbl_rep_srv_pgd',{
						filter_exc : 1, // CANTIDAD DE COLUMNAS A EXCLUIR DEL FILTRADO SIRVE PARA EXCLUIR LA COLUMNA DE ACCIONES O DETALLE
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var api = this.api();
							$( api.column(detect_tot_col(tbl_id,'Monto Cuota')).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column(3, {} ).data().sum(), "", 0, ".", ",")+"</th>");
						}
					});
				});
			}else{
				//******  DIALOG BOX CAPTURA PARAMS DEL REPORTE Y ENVIA EL PEDIDO (SENDING TRUE)
				let mdl = dialog_date_range.create(o)
				TOP.curr_ok_act = {
					method:'ctas_pagas_srv',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mdl.title = 'Reporte Servicios Pagados';
				mk_modal(mdl);
			}
		}
		break;

		case 'repo_ingresos_por_lote':
		if(o.sending){
			o.sending = false;
			if(o.action == 'call'){
				o.data = {
					fec_desde:$('#fec_desde').val(),
					fec_hasta:$('#fec_hasta').val()
				}
			}
			// console.log('cta pagas servicios sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				// **** TABLA RESULTADO
				$('#my_modal').modal('hide');

				const title = 'Ingresos Por Lote ';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);

				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,'tbl_rep_ingresos')+"</div>");
				$(document).ready(function () {
					init_table('tbl_rep_ingresos',{
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var api = this.api();
							$( api.column(2).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column(2, {} ).data().sum(), "", 0, ".", ",")+"</th>");
						}
					});
				});
			}else{
				//******  DIALOG BOX CAPTURA PARAMS DEL REPORTE Y ENVIA EL PEDIDO (SENDING TRUE)
				let mdl = dialog_date_range.create(o)
				TOP.curr_ok_act = {
					method:'repo_ingresos_por_lote',
					sending:true,
					action:'call',
				};
				TOP.curr_close_act = {method:'light_back'};
				mdl.title = 'Reporte Ingresos por lote';
				mk_modal(mdl);
			}
		}
		break;
		case 'stock_lotes':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				$('#my_modal').modal('hide');
				history.add(o);
				const tbltit = "<div class=\'row\'><h3 class=\'p-3\'>Stock Lotes a Vender</h3></div>";
				const table = otbl.create(o.data[1],'tbl_stock');
				const tot_amount = "<hr/><div class=\'row float-right \'><h5 class=\' p-3\'>"+Object.keys(o.data[0])+": "+accounting.formatMoney(o.data[0]['Total General'], "$ ", 2, ".", ",")+"</h5></div>"

				$('#main_container').html(tbltit+table+tot_amount);

				$('#navbar_msg').html('Stock Lotes a Vender');
				// $('#tbl_stock').DataTable({
				// 	language: TOP.DataTable_lang,
				// 	responsive: true
				// });

			}
		}
		break;
		// DEPRECATED
		case 'repo_g120':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				$('#navbar_msg').html('Reporte Garin 120 Cuotas');
				$('#main_container').html((otbl.create(o.data,'tbl_repo_g120')));
				$('#tbl_repo_g120').DataTable({
					language: TOP.DataTable_lang,
					responsive: true
				});
			}

		}
		break;
		case 'lotes_con_posesion' :
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				const title = 'Lotes con Posesion';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);

				$('#main_container').html("<div class='container mt-5'>"+otbl.create(o.data,'tbl_repo_lcp')+"</div>");
				$(document).ready(function () {
					init_table('tbl_repo_lcp',{});
				});
			}
		}
		break;
		case 'lotes_en_ciclo1' :
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				$(document).attr("title", o.tit);
				$('#navbar_msg').html(o.tit);
				$('#main_container').html("<div class='container mt-5'>"+otbl.create(o.data,'tbl_repo_lcp')+"</div>");
				$(document).ready(function () {
					init_table('tbl_repo_lcp',{});
				});
			}
		}
		break;

		case 'lotes_disponibles':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				history.add(o);
				const title = 'Lotes Disponibles';
				$(document).attr("title", title);
				$('#navbar_msg').html(title);
				$('#main_container').html("<div class='container mt-5'>"+otbl.create(o.data,'tbl_repo_disp')+"</div>");
				$(document).ready(function () {
					init_table('tbl_repo_disp',{});
				});
			}
		}

		break;
		case 'revision_plan':
		if(o.sending){
			o.sending = false;
			if(o.action == 'call'){
				o.data = {}
				// TOP.list_refresh = {method:'arqueo_cajaybancos',sending:true,data:o.data};
			}
			console.log('revision_plan sending... ',o)
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				//  RESPUESTA DE REPORT
				$('#my_modal').modal('hide');
				$(document).attr("title", o.tit);
				$('#navbar_msg').html(o.tit);
				let tbl_id =  'tbl_revision_plan';
				$('#main_container').html("<div class='container mt-5'>"+repotbl.create(o.data,tbl_id)+"</div>");
				$(document).ready(function (){
					init_table(tbl_id,{
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tc = detect_tot_col(tbl_id,'Monto');
							if(tc){
								var api = this.api();
								$( api.column(tc).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {page:'current'} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
							// SUMAR INTERESES
							// var ti = detect_tot_col(tbl_id,'Intereses');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
							// // sumar columna total
							// var ti = detect_tot_col(tbl_id,'Total');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
						}
					});
				});
			}
		}
		break;

		case 'print_repo':
		// let t = "<table class=\"table table-hover dataTable no-footer\" role=\"grid\" aria-describedby=\"tbl_"+o.data.id+"_info\">"+$('#tbl_'+o.data.id).html()+"</table>"
		$('#printable_content').html($('#main_container').html());
		$('#printable_content').printThis();
		break;



		// **** CONFIG CONTAB
		case 'new_contab':
		if(o.sending){
			o.sending = false;
			o.data={}
			cd = {};
			var valids = false;
			for(var i in TOP.curr_edit ){
				if(TOP.curr_edit[i].label != 'id'){
					if(validate_field(TOP.curr_edit[i].label)){
						valids = true;
					}
					cd[fix_tipo_lbl(TOP.curr_edit[i].label)] = $('#'+TOP.curr_edit[i].label).val();
				}
			}
			if(valids){
				TOP.current_selection_table = TOP.curr_edit_selection;
				o.table = TOP.curr_edit_selection;
				o.data = cd;
				// console.log('cd',cd)
				TOP.send = true;
			}else{
				TOP.send = false;
				myAlert({container:'#msgs',type:'danger',tit:'Error!',msg:'debe completar todos los campos ',extra:''});
			}
		}else{
			// CREO LOS CAMPOS EN OBJETO Y LOS PONGO EN UN CONTAINER DE PANTALLAS
			var ob = TOP.contab.rows[TOP.contab.rows.length-1];
			var cnt = '';
			// console.log('obj',ob);
			for (var i = 0; i < ob.length; i++){
				var vt = ['text','text','number','select','date'];
				var v =0;

				if(ob[i].vis_elem_type == null || ob[i].vis_elem_type ==  '-1'){v = 0}else{ v = parseInt(ob[i].vis_elem_type);}
				ob[i].value = '';
				if(ob[i].label != 'id'){
					var c = window[vt[v]+'_obj'].create(ob[i]);
					cnt += c.get_screen();
				}
			}
			var cont = container_obj.create(cnt);
			TOP.curr_ok_act = {
				method:'new_contab',
				sending:true,
			};
			TOP.curr_edit = ob;
			cont.title = 'Ingresar Nuevo Item'
			mk_modal(cont);
		}
		break;
		case 'list_contab':
		if(o.sending){
			o.sending = false;
			o.data= {
				id:$('#contab_select').val(),
				// 'page':1
			}
			// console.log('contab select',$('#contab_select').val())
			TOP.curr_edit_selection = $('#contab_select').val();
			TOP.send = true;
		}else{
			o.title = 'Listado Items Caja';
			o.selects = TOP.selects;
			// // console.log('s',o.selects)
			var mdl_atm = dialog_contab.create(o);
			mk_modal(mdl_atm);
			TOP.curr_ok_act = {
				method:'list_contab',
				sending:true,
				action:'call',
			}
		}
		break;
		case 'save_contab':
		if(o.sending){
			// console.log('sending',o);
			var cd= {};
			for(var i in o.obj ){
				if(o.obj[i].label == 'id'){
					cd[o.obj[i].label]= o.obj[i].value;
				}else{
					cd[fix_tipo_lbl(o.obj[i].label)] = $('#'+o.obj[i].label).val();
				}
			}
			TOP.current_selection_table = TOP.curr_edit_selection;
			o.table = TOP.curr_edit_selection;
			o.data = cd;
			// console.log('sending last',o);
			TOP.send = true;
		}

		break;
		case 'refresh_contab':
		o.method = 'list_contab';
		if(o.hasOwnProperty('id') && o.hasOwnProperty('page')){
			o.data= {
				id:o.id,
				'page':o.page
			};
		}else{
			o.data= {
				id:TOP.current_selection_table,
				'page':1
			}
		}
		TOP.send = true;
		break;
		case 'edit_contab':
		if(o.sending){
			o.sending = false;
			TOP.send = false;
		}else{
			// console.log('data',o);
			// console.log('top contab',TOP.contab.rows);
			let ob = {};
			let found_ob = {};
			// OBTENGO EL OBJETO A EDITAR
			for(let row in TOP.contab.rows ){

				ob = TOP.contab.rows[row].find(function(i){return i.value == o.data});
				for( let col in TOP.contab.rows[row]){
					// console.log('X:', TOP.contab.rows[row][col]);
					if(TOP.contab.rows[row][col].label == 'id' && TOP.contab.rows[row][col].value == o.data ){

						found_ob = TOP.contab.rows[row];
						break;
					}
				}
			}

			// CREO LOS CAMPOS EN OBJETO Y LOS PONGO EN UN CONTAINER DE PANTALLAS
			let cnt = '';
			for (let i2 = 0; i2 < found_ob.length; i2++){
				let vt = ['text','text','number','select','date'];
				let  v2 = 0;
				if(found_ob[i2].vis_elem_type == null || found_ob[i2].vis_elem_type ==  '-1'){v2 = 0}else{ v2 = parseInt(found_ob[i2].vis_elem_type);}
				if(found_ob[i2].label != 'id'){
					let c = window[vt[v2]+'_obj'].create(found_ob[i2]);
					cnt += c.get_screen();
				}
			}
			let cont = container_obj.create(cnt);
			TOP.curr_ok_act = {
				method:'save_contab',
				sending:true,
				obj:found_ob
			};
			cont.title = "Modificar Item"
			mk_modal(cont);
		}
		break;
		case 'delete_contab':
		if(o.sending){
			// console.log('deleting', ob);
			o.sending=false;
			o.table = TOP.curr_edit_selection;
			TOP.current_selection_table = TOP.curr_edit_selection;

			TOP.send = true;
		}else{
			TOP.curr_ok_act = {
				method:'delete_contab',
				sending:true,
				data:o.data
			};
			c = alert.create({
				tit:"Eliminar Item",
				msg:"Confirma que desea eliminar el item?",
				type: 'warning'
			})
			mk_modal(c);
		}
		// **** CONFIG ITEMS GENERALES
		case 'refresh_atoms':
		if(o.atom_type_id > 0){
			o.method = 'list_atoms';
			o.data={
				atp_id:o.atom_type_id,
			}
			TOP.send = true;
		}else{
			o.method = 'list_atoms';
			o.sending = false;
		}
		break;
		case 'cancel_edit_atom':
		// console.log('cancel',TOP.curr_edit)
		// console.log('atom_name cancel',TOP.curr_edit);
		o.sending = false;
		// if(TOP.curr_edit.atom_name == null){
		// 	o.method = 'kill_atom';
		// 	o.id = TOP.curr_edit.atom_id;
		// 	$('#my_modal').modal('hide')
		// 	TOP.send = true;
		// }else{
		//
		// 	$('#my_modal').modal('hide')
		// }
		TOP.send = false;
		break;
		case 'edit_atom':
		if(o.sending){
			o.sending = false;
			// console.log('sending edit',o)
			TOP.send = true;
		}else{
			if(o.action == 'call_response'){
				TOP.curr_edit = o;
				var mdl_contnt = ''
				o.data.map(function(itm,indx){
					if(o.type == 'LOTE' && itm.vis_elem_type == 'select'){
						var c = window[itm.vis_elem_type+'_obj_by_name'].create(itm);
						mdl_contnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
					}else{
						console.log('edit',itm);
						var c = window[itm.vis_elem_type+'_obj'].create(itm);
						mdl_contnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
					}
				});
				var cont = container_obj.create(mdl_contnt);
				cont.title = 'Modificar Item: '+ o.act_title ;
				mk_modal(cont);
				TOP.curr_edit = o;
				TOP.curr_ok_act = {
					method:'save_atom',
					sending: true,
				};
			}
		}
		break;
		case 'new_atom':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'call_response'){
				// console.log('selects',TOP.selects)
				TOP.curr_edit = o;
				var mdl_contnt = ''
				o.data.map(function(itm,indx){
					if(o.type == 'LOTE' && itm.vis_elem_type == 'select'){
						var c = window[itm.vis_elem_type+'_obj_by_name'].create(itm);
						mdl_contnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
					}else{
						var c = window[itm.vis_elem_type+'_obj'].create(itm);
						mdl_contnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
					}
				});
				var cont = container_obj.create(mdl_contnt);
				cont.title = 'Nuevo Elemento: '+ o.act_title ;
				mk_modal(cont);
				TOP.curr_edit = o;
				TOP.curr_ok_act = {
					method:'save_atom',
					sending: true,
				};

			}
		}
		break;
		case 'save_atom':
		// console.log('curr save  act',TOP)
		if(o.sending){
			o.sending = false;
			o.data={}
			cd = {};
			var valids = false;
			for(var i in TOP.curr_edit.data ){
				if(TOP.curr_edit.data[i].label != 'id'){
					if(TOP.curr_edit.data[i].label == 'nombre' || TOP.curr_edit.data[i].label == 'name' || TOP.curr_edit.data[i].label == 'lote' ){
						// cd[TOP.curr_edit.data[i].label]= $('#'+TOP.curr_edit.data[i].label).val();
						// console.log('to save',validate_field(TOP.curr_edit.data[i].label));
						// TOP.curr_edit.data[i].label == 'name' &&
						if(validate_field(TOP.curr_edit.data[0].label)){
							valids = true;
						}
						// **** HAGO DEL PCLE NOMBRE EL ATOM NAME
						TOP.curr_edit.atom_name = $('#'+TOP.curr_edit.data[i].label).val()

					}



					// ****** CARGO EN DATA LOS VALORES DE LOS INPUTS
					if(TOP.curr_edit.data[i].vis_elem_type == 'checkbox'){
						cd[TOP.curr_edit.data[i].label]= $('#'+TOP.curr_edit.data[i].label).prop('checked');
					}else{
						cd[TOP.curr_edit.data[i].label]= $('#'+TOP.curr_edit.data[i].label).val();
					}
				}
			}
			if(valids){
				// o.table = TOP.curr_edit_selection;
				// console.log('TOP',TOP.curr_edit)
				o.id = TOP.curr_edit.atom_id;
				o.type = TOP.curr_edit.act_title;
				o.atom_name = TOP.curr_edit.atom_name;
				o.data = cd;
				// console.log('sending',o)
				TOP.send = true;
			}else{
				TOP.send = false;
				myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'debe completar los campos requeridos',extra:''});
				TOP.curr_ok_act.sending = true
				// console.log('curr act',TOP.curr_ok_act)
			}
		}
		break;
		case 'kill_atom':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
			// console.log('killing on top',TOP);
			console.log('killing',o.data);
			// o.method = 'kill_atom';
			// o.id = TOP.curr_ok_act.data.id;
			o.atp_id = TOP.curr_atp_id;
			$('#my_modal').modal('hide');
		}else{
			myAlert({container:'modal',type:'danger',tit:'Eliminar registros ',msg:'Confirma que desea eliminar este registro? ',extra:''});
			TOP.curr_ok_act = {
				method:'kill_atom',
				data:o.data,
				sending:true,
			};
		}
		break;
		case 'list_atoms':
		if(o.sending){
			o.sending = false;
			if(!TOP.curr_atp_id){ TOP.curr_atp_id = $('#atoms').val();}
			o.data={
				atp_id:TOP.curr_atp_id,
			}
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				$('#my_modal').modal('hide');
				// history.add(o);
				$('#navbar_msg').html('Listado Elementos Tipo &nbsp; '+o.type);
				const bot_back = '';

				const bot_new_item = buton_primay.create({label:'Crear Nuevo Elemento',method:'call_new_atom',action:'call',data:o.type,sending:true}).get_screen();
				const tbl = otbl.create(o.data,'tbl_atoms');
				$('#main_container').html("<div class=\'mt-5\'>"+bot_new_item+tbl+"</div>");
				$('#tbl_atoms').DataTable({
					language: TOP.DataTable_lang,
					responsive: true
				});
			}else{
				//  *** NOT SENDING CREA MODAL CON SELECTOR DE ITEM
				o.title = 'Listado Items Generales';
				o.selects = TOP.selects;
				console.log('selects',o.selects)
				// var mdl_atm = dialog_atom.create(o);
				TOP.curr_ok_act ={
					method:'list_atoms',
					sending:true,
					action:'call',
				}
				TOP.curr_close_act = {method:'light_back'};
				let mdl = select_obj.create({label:'atoms',title:'Tipos de Elementos'});
				// mdl.winmed = 'modal-dialog modal-dialog-centered modal-sml';
				mdl.title = 'Listar Elementos del Sistema';
				mk_modal(mdl);
			}
		}
		break;
		case 'hist_list':
		console.log(o);
		break;
		// INACTIVO
		case 'saldo_cuentas':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action == 'response'){
				//  RESPUESTA DE REPORT
				$('#my_modal').modal('hide');
				// console.log(o)
				let br = btns_row(o);
				// $('#navbar_msg').html('');
				var tbltit = "<hr/><div class=\'row\'><h3 class=\'p-3\'>"+o.tit+"</h3></div>"
				var tbl_id =  'tbl_saldo_cuentas';
				$('#main_container').html(br + tbltit +repotbl.create(o.data,tbl_id));
				$(document).ready(function (){
					init_table(tbl_id,{
						language: TOP.DataTable_lang,
						lengthMenu: [[10, 50,100,200, -1], [10, 50, 100,200, "Todos"]],
						responsive: true,
						drawCallback: function(){
							//  SUMAR MONTO PAGADO
							var tc = false; //detect_tot_col(tbl_id,'Monto');
							if(tc){
								var api = this.api();
								$( api.column(tc).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( tc, {page:'current'} ).data().sum(), "", 0, ".", ",")+"</th>");
							}
							// SUMAR INTERESES
							// var ti = detect_tot_col(tbl_id,'Intereses');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
							// // sumar columna total
							// var ti = detect_tot_col(tbl_id,'Total');
							// if(ti){
							// 	var api = this.api();
							// 	$( api.column(ti).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(api.column( ti, {page:'current'} ).data().sum(), "$ ", 0, ".", ",")+"</th>");
							// }
						}
					});
				});
			}
		}
		break;

		// **** REVISION
		case 'list_revision':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			if(o.action === 'response'){
				history.add(o);
				$('#my_modal').modal('hide');
				var headings = {'fecha':'&nbsp;Fecha&nbsp;&nbsp;&nbsp;&nbsp;','usr':'Reportado por','asignado_a':'Asignado a','lote':'Lote','cli':'Cliente','coment':"Mensage",'hstate':'hstate','estado':'Estado'};
				$('#main_container').html(table_revision.create({'tbl_id':'tbl_'+o.method,'steps_back':true,'headings':headings,'items':o.rows}).get_screen());
				// editable de asignados: ,extras:{'select_id':false,'caller':'revision_list','editables':['asignado_a2'],'edit_call':'update_rev_asignado'}
				$.fn.dataTable.moment('DD/MM/YYYY');
				$('#tbl_'+o.method).DataTable({
					language: TOP.DataTable_lang,
					"pageLength": 100,
					"order": [[ 0, "desc" ]],
					"columnDefs": [
						{'targets':[6],'visible':false,'searchable':true},
						{'targets':[7],'visible':true,'searchable':false}

					]
				});
			}
		}
		break;
		case 'update_rev_asignado':
		TOP.route = 'configuracion/'
		TOP.send = true;
		break;
		case 'new_revision':
		if(o.sending){
			if(validate_click('new_rev')){
				o.sending = false;
				o.lote = $('#rev_lote').val()
				o.asignado_a = $('#asignado_a').val()
				o.coment = $('#rev_coment').val()
				o.user_id = TOP.user_id;
				console.log('new rev',o)
				TOP.send = true;
			}else{
				myAlert({container:'#modal-footer-msgs',type:'danger',tit:'Error!',msg:'debe completar todos los campos ',extra:''})
				TOP.send = false;
			}
		}else{
			var mdl_new_rev = dialog_revision.create(o);
			TOP.curr_ok_act ={
				method:'new_revision',
				sending:true
			}
			// TOP.curr_close_act ={
			// 	method:'set_curr_state',

			// }
			mdl_new_rev.title = "Reportar Problema"
			mk_modal(mdl_new_rev);

		}
		break;
		case 'list_elements':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}
		break;

		case 'revision_set_estado':
		if(o.sending){
			o.sending = false;
			TOP.route = 'configuracion/'
			TOP.send = true;

		}else{
			if(o.estado === '1'){
				$('#btn_estado_'+o.id).html('Resuelto');
				$('#btn_estado_'+o.id).removeClass('btn-warning');
				$('#btn_estado_'+o.id).addClass('btn-success');
				$('#btnGroupDrop_'+o.id).removeClass('btn-warning');
				$('#btnGroupDrop_'+o.id).addClass('btn-success');
			}else{
				$('#btn_estado_'+o.id).html('Pendiente');
				$('#btn_estado_'+o.id).removeClass('btn-success');
				$('#btn_estado_'+o.id).addClass('btn-warning');
				$('#btnGroupDrop_'+o.id).removeClass('btn-success');
				$('#btnGroupDrop_'+o.id).addClass('btn-warning');
			}

		}

		break;
		// **** MENSAGES
		case 'send_msg':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{

		}

		break;
		// **** LOTES
		case 'list_lotes':
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			// if(o.action == 'res_ok'){
			history.add(o);
			var headings = {'name':'Nombre ','emprendimiento':'Emprendimiento','estado':'Estado','id':'Acciones'};
			$('#main_container').html(gntbl_1.create({'title':'Listado de Lotes','tbl_id':'tbl_'+o.method,'steps_back':true,'headings':headings,'items':o.data,acciones:[{method:'lotes_file_upload',sending:false,icon:'ios-cloud-upload'},{method:'lotes_edit',sending:true,icon:'md-open'}]}).get_screen());
			// editable de asignados: ,extras:{'select_id':false,'caller':'revision_list','editables':['asignado_a2'],'edit_call':'update_rev_asignado'}
			$.fn.dataTable.moment('DD/MM/YYYY');
			$('#tbl_'+o.method).DataTable({
				language: TOP.DataTable_lang,
				"pageLength": 100,
				"order": [[ 0, "desc" ]],
			});

			// }
		}
		break;
		case 'lotes_file_upload':
		console.log('file upload',o)
		if(o.sending){
			o.sending = false;
			TOP.send = false;
			TOP.uploading = true;
		}else{

			// *** MODAL WINDOW TO SELECT FILE TO UPLOAD.
			let up = dialog_upload.create(o);
			TOP.curr_ok_act ={
				method:'lotes_file_upload',
				sending:true,
			}
			up.title = "Subir Archivo "
			mk_modal(up);
		}
		break
		case 'lotes_edit':
		console.log('lote edit',o.sending)
		if(o.sending){
			o.sending = false;
			TOP.send = true;
		}else{
			console.log('lote edit ----',o)
			if(o.action == 'show_edit_window'){
				let pd = {title:'Editar Lote',pnl_id:'lote',content:'contenido del panel'};
				$('#main_container').html(panel.create(pd).get_screen());
			}

		}
		break

		// **** WEB CLI
		case 'web_cli_get_resumen_de_cta':
		if(o.sending){
			o.method = 'get_elements';
			TOP.route = 'clientes/'
			TOP.send = true;
			o.sending = false;
		}else{

		}

		break;
		// **** GENERALES
		case 'call_resumen_contrato':
		if(o.sending){

		}else{

		}
		break;
		case 'save_pcle':
		if(o.sending){
			o.sending = false;
			o.data = {
				pcle:TOP.curr_edit.pcle_lbl,
				pcle_val:$('#'+TOP.curr_edit.pcle_lbl).val().replace(/[^\w\s!?]/g,''),
				elem_id:TOP.curr_edit.elm_id,
				container_id:TOP.curr_edit.container_id
			}
			TOP.send = true;
			console.log('save_Pcle',o);
		}else{
			if(o.action == 'response'){
				console.log('resp save',o);
				TOP.data.lote[TOP.curr_edit.pcle_lbl] = o.result;
				$('#'+TOP.curr_edit.container_id).html(o.result);
				$('#my_modal').modal('hide');
			}
		}
		break;
		case 'edit_dialog':
		if(o.sending){
			o.sending = false;
			TOP.send = false;
		}else{
			TOP.curr_edit = o;
			// TOP.curr_edit.container_id =

			console.log('editing',o);
			mk_modal(textarea_obj.create({label:o.pcle_lbl,value:o.curr_val}));
			TOP.curr_ok_act = {
				method:'save_pcle',
				sending: true,
			};
		}
		break;

		case 'embed_to_modal':
		if(o.sending){
			o.sending = false;
			TOP.send = false;
		}else{



			delete_permission = (parseInt(TOP.user_id) == 501 ? true : false);
			console.log('del permi',delete_permission);
			let r = "<div class='embed-responsive embed-responsive-1by1'><iframe class='embed-responsive-item' src="+o.src+"></iframe></div>"

			const screen = {
				title:o.title,
				cnt:r,
				get_screen : function(){return this.cnt },
				winmed : 'modal-dialog-centered modal-xl',
				hide_ok_button : true,
				delete_button: delete_permission
			}
			TOP.curr_close_act = 'light_back';
			TOP.curr_delete_target = o.src;
			mk_modal(screen);

		}
		break;
		case 'file_download':
		if(o.sending){
			o.sending = false;
			TOP.send = false;
			// console.log('download',TOP);
			location.href="https://lpt.nuberio.com/"+TOP.route+"file_download?id="+o.data.id;
		}else{

		}
		break;
		case 'light_back':
		$('#my_modal').modal('hide');
		break;
		case 'back':
		// if(TOP.permisos < 5 && TOP.history.length > 0 && TOP.history[TOP.history.length-1].method == 'set_pago_cuotas' && !TOP.cuotas_imputadas && !TOP.res_to_salida_gestiondepagos){
		// 	myAlert({container:'modal',type:'danger',tit:'Gestión de pagos',msg:'Salir sin imputar pagos?',extra:''});
		// 	TOP.res_to_salida_gestiondepagos = true;
		// 	TOP.curr_ok_act = {
		// 		method:'get_elements',
		// 		sending:true,
		// 		data:{elm_id:TOP.curr_elem_id}
		// 	};
		// 	break;
		// }
			history.back()
		break;
		case 'hist_home':
				history.home();
		break;
		case 'kill_modal_content':
		if(o.sending){
			// unlink
			// cuando cargo data con el nombre del delete target
			// reseteo TOP.curr_delete_target a vacio
			TOP.route = o.route;
			o.data = {'elm_id':TOP.data.lote.elements_id,'del_target':TOP.curr_delete_target};
			TOP.curr_delete_target = '';
			TOP.send = true;
			TOP.sending = false;
			console.log('Killing ->',TOP.curr_delete_target);

		}else{
			if(o.action == 'response'){
				if(TOP.route == 'configuracion/'){
					front_call({method:'edit_element',sending:true,data:{type:'Element',id:o.elm_id}})
				}else{
					front_call({method:'get_elements',sending:true,data:{elm_id:o.elm_id}})
				}
			}else{
				$('#delete_button').removeClass('d-flex');
				$('#delete_button').addClass('d-none');
				myAlert({container:'modal',type:'danger',tit:'',msg:"<p class=\"font-weight-bold text-danger text-uppercase\">Confirma que desea borrar el archivo: "+TOP.curr_delete_target.substring(TOP.curr_delete_target.lastIndexOf('/')+1)+" ? </p>",extra:'no_autohide'});
				TOP.curr_ok_act = {
					route:'configuracion/',
					method:'kill_modal_content',
					sending:true,
				};
			}
		}
		break;

		case 'update_rev_asignado':
		upd_intrst(o.data)
		TOP.send = false;
		break;
		case 'update_edi':
		upd_intrst(o.data)
		TOP.send = false;
		break;
		case 'pcle_updv_dup':
		if(o.sending){
			console.log('ooo',o);
			if($("#"+o.pcle_id).html() == undefined){
				o.data = $("#"+o.pcle_id).value();
			}else{
				o.data = $("#"+o.pcle_id).html();
			}

			console.log('sending ',o)
			o.sending = false;
			if(o.data != TOP.curr_edition_val){
				TOP.send = true;
			}

		}
		if(o.response){
			TOP.send = false;
			// console.log('response from server',o);
			$.blockUI({
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
				message: 'OK',
				baseZ: 10000,
				timeout:1000
			});
		}
		break;

		case 'pcle_updv_cnfg':
		if(o.sending){
			o.method = 'pcle_updv';
			// o.data = {
			// 	pcle_id:o.pcle_id,
			// 	prnt_id:o.prnt_id,
			// 	type:"Element",
			// 	val:$("#"+o.pcle_id).val(),
			// }
			o.data.val = $("#"+o.data.pcle_id).val(),
			console.log('sending ',o)
			o.sending = false;
			TOP.send = true;
		}
		if(o.response){
			console.log('response from server',o);
			$.blockUI({
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
				message: 'OK',
				baseZ: 10000,
				timeout:1000
			});
		}
		break;
		case 'pcle_updv_fec_ini':
		if(o.sending){
			o.data.val = $("#"+o.data.lid).val();
			console.log('sending ',o)
			o.sending = false;
			TOP.send = true;
		}else{
			TOP.curr_ok_act = {
				method:'pcle_updv_fec_ini',
				sending:true,
				data:o.data
			};
			TOP.curr_close_act = {
				method:'light-back',
				sending:false,

			};
			myAlert({tit:'Modifica la fecha de inicio del contrato!', msg:'Modificar la fecha de inicio del contrato altera las fechas de vencimiento de todas las cuotas. Desea continuar con la operacion? ',type:'danger',container:'modal',extra:'no_autohide'});
			if(o.response){
				TOP.curr_ok_act = {};
				$('#my_modal').modal('hide');
				front_call({method:'edit_element',sending:true,data:{type:o.type,id:o.id}});
			}
		}
		break;
		case 'update_elem_pcle':
		TOP.send = true;
		break;
		case 'update_comprobante':
		if(o.response){
			$.blockUI({
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
				message: 'OK.',
				baseZ: 10000,
				timeout:1000
			});
			TOP.send = false;
			console.log('response de update comprobante',o);
		}else{
			console.log('updating comprobante',o);
			TOP.send = true;
		}
		break;
		case 'update_event':
		if(o.response){
			$.blockUI({
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
				message: 'OK.',
				baseZ: 10000,
				timeout:1000
			});
			console.log('event update response',o);
			$("#"+o.scrn_elem_id).val(o.estado);

		}else{
			console.log('updating event',o)
			o.data.user_id = TOP.user_id;
			TOP.send = true;
		}
		break;
		case 'kill_event':
		o.data.elm_id = TOP.curr_edit.data.estado.elements_id
		TOP.send = true;
		break;
		case 'updating':
		TOP.curr_ok_act = {
			method:'back',
			sending:false,

		};
		TOP.curr_close_act = {
			method:'back',
			sending:false,

		};
		myAlert({container:'modal',type:'warning',tit:'Actualizando',msg:'El sistema esta en actualizacion...vuelvea intentar en dos minutos',extra:''});
		break;
		case 'alert':
		myAlert(o.data);
		break;
		// **** END
	}
}
