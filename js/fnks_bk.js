
//*** UPDATE 25 junio 2020
function front_call	(obj){
	TOP.send = false;
	// **** CHECKEA SI LA LLAMADA ES OK Y SI ES PARA JS O PARA PHP
	router(obj);
	// console.log('salida del router',TOP);
	if(TOP.send){
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
	        message: 'Procesando...',
	        baseZ: 10000
    	});
 		obj.sending = false;
		TOP.send = false;
		return $.ajax({
		 	type : "POST",
		 	url : TOP.route+obj.method,
		 	data : obj,
		 	dataType : "text",
		 	success : function(r) {
		 		$.unblockUI();
		 		if(r == ''){
		 			$('#main_container').html('');
					location.reload(true);
				}
				else{
		 			if(IsJsonString(r))
					{
					 	//JSON IS OK
						const res = JSON.parse(r);
						TOP.info = res.info;
						TOP.last_call = res.callback;
						TOP.last_call_param = res.param;
						if(res.param != false){
							window[res.callback](res.param);
						}else{
							myAlert({tit:'busqueda de datos', msg:'No hay registros ',type:'warning',container:'#modal-footer-msgs',extra:'no_autohide'})
						}
					}else{
						myAlert({container:'modal',type:'danger',tit:'ERROR JSON',msg:r ,extra:''})
					}
				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
					$.unblockUI();
					myAlert({ container:'modal', type:'danger',tit:'DATOS NO VALIDOS', msg:xhr.responseText})
					console.log('Error:',xhr.responseText);

			}
		});
	}
	if(TOP.uploading){
		TOP.uploading = false;
		$.blockUI({ message: null, baseZ: 10000  });
		if($('#file_to_upload').prop('files')){
			var file_data = $('#file_to_upload').prop('files')[0];
			var form_data = new FormData();
			form_data.append('file', file_data);
			form_data.append('elm_id', TOP.data.lote.elements_id);
			form_data.append('lote_nom', TOP.data.lote.lote_nom);
			$.ajax({
				url: TOP.route+obj.method,
				dataType: 'text',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function (r) {
					$.unblockUI();
					console.log('res',r)
					const x = JSON.parse(r);
					if(x.param.response){
						TOP.data.uploaded_files[x.param.folder] = x.param.up_files
						console.log('top uploaded',TOP.data.uploaded_files);
						front_call({method:'refresh_uploaded_files',sending:false,folder: x.param.folder });
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
							message: x.param.msg ,
							baseZ: 10000,
							timeout:1500
						});
						$('#my_modal').modal('hide');
					}else{
						myAlert({container:'modal',type:'danger',msg:x.param.msg})
					}
					TOP.curr_ok_act = {method:'light_back'};
				},
				error: function (r) {
					console.log('res',r)
					$.unblockUI();
					const x = JSON.parse(r);
					myAlert({container:'modal',type:'danger',msg: x.param.msg })
					TOP.curr_ok_act = {method:'light_back'};
				}
			});
		}
	}
}

// ******************** 24 de junio 2020
// actualiza el contenido del dropdown
// y actualiza el pcle en la base
// params (row_id, new value, front call params method,pcle_id y parent atom o elem id)
// ******************
function dropDownUpdate(id,nv,x){
	$('#'+id).html(decodeURIComponent(nv));
	set_curr_edited(decodeURIComponent(nv));
	x.value = decodeURIComponent(nv);
	front_call(x);
}

// ******************** 16 de junio 2020
// *** checkea si el lote tiened cuotas en mora para
// aprobar la tabla de cuotas de get_card1 en clpsd_cards
// si estan en mora desahbilita el boton pagar online

// **** update 30 junio
function sinCtasEnMora(data){
	let res = true;
	// if(data.lote.ctas_mora.events.length >= 3){ res = false;}
	if(data.srv.length > 0){
		//let totalEnMora = 0;
		data.srv.forEach((item, i) => {
			if(item.ctas_mora.events.length >= 3){ res = item.ctas_mora.events.length+' servicios_en_mora';}
			// totalEnMora += item.ctas_mora.events.length
		});
		// if(totalEnMora >= 2){res = false;}
		// console.log('tt',totalEnMora);
	}
	if(data.lote.estado_contrato.value === 'BLOQUEADO'){res = false;}
	if(data.lote.estado_contrato.value === 'EN LEGALES'){res = false;}
	if(data.lote.estado_contrato.value === 'NORMAL'){res = true;}
	return res;
}


// FILTER STUFF
function cf1(lbl,cat,subcat) {
	let d = {lbl:lbl,cat:cat,sbc:subcat};
	console.log('incoming',cat);
	console.log('en cf1',TOP.curr_filters);
	// CATEGORIA ENTRANTE MATA CATEGORIA ACTUAL
	let z = TOP.curr_filters.findIndex(function(x){return x.cat == cat});
	if(z > -1){
		TOP.curr_filters.splice(z, 1);
	}
	// HANDLER DE CANTIDADES Y MONTOS INPUTS
	if(lbl.indexOf('cant_')> -1 || lbl.indexOf('monto_')> -1){
		d.cat = $('#'+lbl+'_range_in').val();
		d.sbc = $('#'+lbl+'_range_out').val();
	}
	else if (lbl.indexOf('fec_')> -1) {
		d.cat = $('#'+lbl+'_date_in').val();
		d.sbc = $('#'+lbl+'_date_out').val();
	}
	// NO DUPLICA SI YA ESTA EN LA LISTA
	let t = TOP.curr_filters.find(function(x){return x.cat == d.cat && x.sbc == d.sbc})
	if(!t){TOP.curr_filters.push(d)}
	update_filter_bar();
	front_call({method:'filter',sending:true,data:TOP.curr_filters})
}

// ACTUALIZA LOS TOASTS DE SELECCION DE FILTROS
function update_filter_bar() {
	let toast_cnt = "<div id=\'toast_cnt\' class=\'row d-flex flex-wrap justify-content-start\'></div>"
	let toasts = '';
	let cfl = [...TOP.filter_columns] ;
	TOP.curr_filters.map(function(v,i){
			let tts = (v.cat == v.sbc?v.cat:v.cat+' '+v.sbc);
			if(v.lbl.indexOf('cant_')> -1 || v.lbl.indexOf('monto_')> -1 || v.lbl.indexOf('fec_')> -1){
				let ci = cfl.findIndex(c=>{return c.label == v.lbl});
				tts = cfl[ci].title +' '+ (!v.cat?'0':v.cat)+' a '+(!v.sbc?' ':v.sbc);
			}
			toasts += f_toast.create(tts,i);
			cfl.splice(cfl.findIndex(c=>{return c.label == v.lbl}), 1);
	});
	$('#filter_column').html(toast_cnt + data_box_small.create({
		label:'filter',
		id:"dbx_filter",
		value:filter.create(cfl),collapsed:true}).get_screen()
	);
	$('#toast_cnt').append(toasts);
}


function ftoast_remove(ix) {
	TOP.curr_filters.splice(ix, 1);
	// toggle_vis(n,true)
	update_filter_bar();
	front_call({method:'filter',sending:true,data:TOP.curr_filters});
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
// CHEKEA SI CURRENT USER PUEDE GUARDAR DATOS DE LA EVENTANA EDIT_CALL
function get_auth_user(){
	if (TOP.user_id == 484 || TOP.user_id == 501){
		return true;
	}else{
		return false;
	}
}

// RETORNA TODAS LAS CUOTAS DE UN CONTRATO , CUOTAS DEL LOTE Y CUOTAS DE SERVICIOS SI HAY
// PARA EL EDITOR DE CONTRATO
function get_cuotas_for_edit(d){
	let c = [];
	d.events.map(function(e){
		let itm = {};
		if(e.hasOwnProperty('pcles')){
			let monto = e.pcles.find(function(p){return p.label == "monto_cta" });
			let fvto = e.pcles.find(function(p){return p.label == "fecha_vto" });
			let estado = e.pcles.find(function(p){return p.label == "estado" });
			let nro_cta = e.pcles.find(function(p){return p.label == "nro_cta" });
			let monto_pagado = e.pcles.find(function(p){return p.label == "monto_pagado" });
			let fec_pago = e.pcles.find(function(p){return p.label == "fec_pago"});
			let dias_mora = e.pcles.find(function(p){return p.label == "dias_mora"});
			let interes_mora = e.pcles.find(function(p){return p.label == "interes_mora"});
			itm = {
				'elem_id':e.event.elements_id,
				'ordnum':e.event.ord_num,
				'event_id':e.event.id,
				'event_type':e.event.events_types_id,
				'monto_cta':(monto !== undefined?monto.value:0),
				'fecha_vto':(fvto !== undefined?fvto.value:'-'),
				'estado':(estado !== undefined?estado.value:'-'),
				'estado_pcle_id':(estado !== undefined?estado.id:0),
				'nro_cta':(nro_cta !== undefined?nro_cta.value:0),
				'monto_pagado':(monto_pagado!=undefined?monto_pagado.value:0),
				'monto_pagado_pcle_id':(monto_pagado!= undefined?monto_pagado.id:0),
				'fec_pago':(fec_pago != undefined ? fec_pago.value : "-" ),
				'fec_pago_pcle_id':(fec_pago != undefined ? fec_pago.id : 0),
				'dias_mora':(dias_mora != undefined ? dias_mora.value : 0),
				'dias_mora_pcle_id':(dias_mora != undefined ? dias_mora.id : 0),
				'interes_mora':(interes_mora != undefined ? interes_mora.value : 0),
				'interes_mora_pcle_id':(interes_mora != undefined ? interes_mora.id : 0),
			}
		}
		c.push(itm);
	});
	let services = [];
	let s = d.servicios;
	for(let i = 0; i < s.length ; i++){
		let serv = [];
		s[i].events.map(function(e){
			let itm = {};
			if(e.hasOwnProperty('pcles')){
				let monto = e.pcles.find(function(p){return p.label == "monto_cta" });
				let fvto = e.pcles.find(function(p){return p.label == "fecha_vto" });
				let estado = e.pcles.find(function(p){return p.label == "estado" });
				let nro_cta = e.pcles.find(function(p){return p.label == "nro_cta" });
				let monto_pagado = e.pcles.find(function(p){return p.label == "monto_pagado" });
				let fec_pago = e.pcles.find(function(p){return p.label == "fec_pago"});

				itm = {
					'ordnum':e.event.ord_num,
					'event_id':e.event.id,
					'event_type':e.event.events_types_id,
					'monto_cta':(monto !== undefined?monto.value:0),
					'fecha_vto':(fvto !== undefined?fvto.value:'-'),
					'estado':(estado !== undefined?estado.value:'-'),
					'estado_pcle_id':(estado !== undefined?estado.id:0),
					'nro_cta':(nro_cta !== undefined?nro_cta.value:0),
					'monto_pagado':(monto_pagado!=undefined?monto_pagado.value:0),
					'monto_pagado_pcle_id':(monto_pagado!= undefined?monto_pagado.id:0),
					'fec_pago':(fec_pago != undefined ? fec_pago.value : "-" ),
					'fec_pago_pcle_id':(fec_pago != undefined ? fec_pago.value : 0)
				}
			}
			serv.push(itm);
		});
		services.push({name:s[i].name,itm_serv:serv});
	}
	return {c_lote:c,c_serv:services};
}

// FIX DE VISUAL ELEMENT TYPE VIEJO QUE PASA DE ID A TEXTO  DEPRECATE
function vet_check(v){
	if(!v){return 'text';}
	if(isNaN(v)){return v}
	if(parseInt(v) === -1){return 'text';}
	if(parseInt(v) <= 1){return 'text';}
	if(parseInt(v) === 2){return 'number';}
	if(parseInt(v) === 3){return 'select';}
	if(parseInt(v) === 4){return 'date';}
	if(parseInt(v) > 4 ){return 'text';}
}


// OBTIENEN EL NOMBRE DE LA CAJA O CUENTA DESDE EL ID DEL SELECT
function get_cuenta_name(id){
	if (TOP.selects.hasOwnProperty('cuentas')){
		let r = TOP.selects.cuentas.find(function(i){return i.id == id});
		console.log("en get cuenta name",r);
	}
}

//**** OBTIENE LOS VALORES DEL TITULO DEL LISTADO DETALLE CUOTAS
function get_valores_tit_detalle(varr){
	// console.log('varr',varr);
	ctas = varr.filter(i=>{return i.type === 'CUOTA';});
	adls = varr.filter(i=>{return i.type === 'CTA_ADELANTADA';});
	enfecha = ctas.filter(i=>{return get_pcle(i,'estado') === 'pagado';});
	ftrm = ctas.filter(i=>{return get_pcle(i,'estado') === 'p_ftrm';});
	ahr = 0
	if(adls.length >0){
		adlmc = adls.map(c=>{return parseInt(get_pcle(c,'monto_cta'))}).reduce(function(ant,act){return ant+act},0);
		adlmp = adls.map(c=>{return parseInt(get_pcle(c,'monto_pagado'))}).reduce(function(ant,act){return ant+act},0);
		ahr = adlmc-adlmp;
	}
	return {adl:adls.length, enfecha:enfecha.length,ftrm:ftrm.length,ahorro:ahr};
}

function set_curr_edited(v){
	TOP.curr_edition_val = v.innerHTML;
}

// VALIDA EL CAMPO EDITABLE DE TABLAS
function validate_td_update(id){
	let t = $('#'+id).html();
	// TIENE ENTER REEMPLAZA EL HTML HACE NEXT
	if(t.match(/<br>/g)){
		$('#'+id).html(t.replace(/<br>/g,''));
		$($('#'+id).next()).focus()
	}
}


function init_f_tbl(id,actions_col_index){
	let settings = {
		language: TOP.DataTable_lang,
		deferRender:    true,
		ordering: true,
		order: [[ 1, 'asc' ]],
		select:false,
		searching:false,
		paging: false,
	  scrollX: true,
	  scrollY: 500,
		scrollCollapse: true,
		fixedHeader: false,
		autoWidth: false,
		info: true,
		responsive: false,
		dom: 'Bfti',
		buttons: [
					{
						extend: 'excel',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'print',
						exportOptions: {
							columns: ':visible'
						}
					},
					'colvis'
				],
		columnDefs: [{
			targets: actions_col_index,
			orderable: false
		}]

	}
 	return $('#'+id).DataTable(settings);
}

// **** DATATABLES SIN FILTER OPTIONS PERO CON SEARCH
function init_table_2(tid,tbf){
	'use strict';
	let settings = {
		language:TOP.DataTable_lang,
		ordering: true,
		autoWidth: true,
		order: [[ 0, 'desc' ]],
		deferRender:true,
		scrollY:430,
		scrollX:true,
		scrollCollapse:true,
		scroller:true,
		fixedHeader:true,
		fixedColumns:true,
		select:false,
		responsive:false,
		dom:'ftiB',
		searching:true,
		buttons: [
		'copy', 'excel', 'print'
		]
	}
	//***  FUNCION DE LLAMADA CUANDO SE TERMINA DE IMPRIMIR LA TABLA. ES USADA PARA SUMAR  TOTALES DE COLUMNAS
	if(tbf.hasOwnProperty('drawCallback')){
		settings.drawCallback = tbf.drawCallback;
	}
	if(tbf.hasOwnProperty('columnDefs')){
		settings.columnDefs = tbf.columnDefs;
	}
	return $('#'+tid).DataTable(settings);
	// ESTAN INACTIVOS LOS FILTROS DE COLUMNAS *****
	// let columns = [];
	// let fexc = 0;
	// if(tbf.hasOwnProperty('filter_exc')){
	// 	fexc = tbf.filter_exc;
	// }
	// // SI FILTER EXCLUDE ES DISTINTO DE ALL PONE FILTROS A LAS COLUMNAS
	//
	// if(fexc == 'all'){
	// 	//*** FILTROS DE LAS COLUMNAS
	// 	for (let i = 0 ; i <= (tblColCount(tid)-1) - fexc; i++) {
	// 		columns.push({
	// 			column_number: i,
	// 			filter_type: "multi_select",
	// 			select_type: 'select2',
	// 			filter_default_label:"Seleccionar filtro",
	// 			// filter_default_label:"Filtrar",
	// 			filter_reset_button_text:"x",
	// 			// exclude:false,
	// 			// exclude_label: '',
	// 			select_type_options: {
	// 				width: '90%',
	// 				minimumResultsForSearch: -1 // remove search box
	// 			}
	// 		});
	// 	}
	// 	yadcf.init(DTable,columns);
	//
	// }
}

//**** DATA TABLES SETTING CON X SCROLL Y FILTROS
function init_table_3(tid,tbf){
	'use strict';
	let settings = {
		language: TOP.DataTable_lang,
		deferRender:    true,
		select:false,
		searching:false,
		paging: false,
	  scrollX: true,
	  scrollY: '100%',
		scrollCollapse: false,
		autoWidth: false,
		info: false,
		responsive: false,
		dom: 'ftiB',
		// "dom": '<"top">t<"bottom"iB><"clear">',
		buttons: [
			'copy', 'excel', 'print'
		],
		searching:true,
	}
	//***  FUNCION DE LLAMADA CUANDO SE TERMINA DE IMPRIMIR LA TABLA. ES USADA PARA SUMAR  TOTALES DE COLUMNAS
	if(tbf.hasOwnProperty('drawCallback')){
		settings.drawCallback = tbf.drawCallback;
	}
	if(tbf.hasOwnProperty('columnDefs')){
		settings.columnDefs = tbf.columnDefs;
	}
	let DTable = $('#'+tid).DataTable(settings);
	let columns = [];
	let fexc = 0;
	if(tbf.hasOwnProperty('filter_exc')){
		fexc = tbf.filter_exc;
	}
	// SI FILTER EXCLUDE ES DISTINTO DE ALL PONEFILTROS A LAS COLUMNAS
	if(fexc != 'all'){
		//*** FILTROS DE LAS COLUMNAS
		for (let i = 0 ; i <= (tblColCount(tid)-1) - fexc; i++) {
			columns.push({
				column_number: i,
				filter_type: "text",
				// select_type: 'select2',
				// filter_default_label:"Seleccionar filtro",
				filter_default_label:"Filtrar",
				filter_reset_button_text:false,
				exclude:true,
				exclude_label: '',
				// select_type_options: {
				// 	width: '95%',
				// 	// minimumResultsForSearch: -1 // remove search box
				// }
			});
		}
		yadcf.init(DTable,columns);
	}
	// return $('#'+tid).DataTable(settings); //*** RETORNA SETTINGS CUANDO ESTA DESACTIVADO YADCF
}


//********** SET UP DE DATATABLES con filters
function init_table(tid,tbf){
	'use strict';
	let settings = {
		language: TOP.DataTable_lang,
		// lengthMenu: [[10, 50,100, -1], [10, 50, 100,"Todos"]],
		deferRender:    true,
		scrollY:        430,
		scrollX:        false,
		scrollCollapse: true,
		scroller:       true,

		// fixedHeader: true,
		// fixedColumns: true,
		select:true,
		responsive: true,
		dom: 'tiB',
		// "dom": '<"top">t<"bottom"iB><"clear">',

		buttons: [
		'copy', 'excel', 'print','colvis'
		],

		// 	"info":false,
		searching:true,
		// 'lengthChange':false,
		// "paging": true,
	}
	//***  FUNCION DE LLAMADA CUANDO SE TERMINA DE IMPRIMIR LA TABLA. ES USADA PARA SUMAR  TOTALES DE COLUMNAS
	if(tbf.hasOwnProperty('drawCallback')){
		settings.drawCallback = tbf.drawCallback;
	}
	if(tbf.hasOwnProperty('columnDefs')){
		settings.columnDefs = tbf.columnDefs;
	}
	let DTable = $('#'+tid).DataTable(settings);
	let columns = [];
	let fexc = 0;
	if(tbf.hasOwnProperty('filter_exc')){
		fexc = tbf.filter_exc;
	}
	// SI FILTER EXCLUDE ES DISTINTO DE ALL PONEFILTROS A LAS COLUMNAS
	if(fexc != 'all'){
		//*** FILTROS DE LAS COLUMNAS
		for (let i = 0 ; i <= (tblColCount(tid)-1) - fexc; i++) {
			columns.push({
				column_number: i,
				filter_type: "text",
				// select_type: 'select2',
				// filter_default_label:"Seleccionar filtro",
				filter_default_label:"Filtrar",
				filter_reset_button_text:false,
				exclude:true,
				exclude_label: '',
				// select_type_options: {
				// 	width: '95%',
				// 	// minimumResultsForSearch: -1 // remove search box
				// }
			});
		}
		yadcf.init(DTable,columns);

	}

	//  falta  refrescar la suma de totales
	// $( TOP.current_tot_col_datatables_api.column(TOP.current_tot_col).footer() ).html("<th class='d-flex justify-content-end'>"+accounting.formatMoney(TOP.current_tot_col_datatables_api.column( TOP.current_tot_col, {} ).data().sum(), "", 0, ".", ",")+"</th>");


	// mto_col = detect_monto(tid);
	// if(mto_col){
	// 	$('#'+tid).DataTable({

	// 	 });
	// }

	//********************* old version
	// for (let i = 0 ; i < tblColCount(tid); i++) {
	// 	yadcf.init(DTable,[{
	// 		column_number: [0,1,2],
	// 		filter_type: "multi_select",
	// 		select_type: 'select2',
	// 		filter_default_label:"Seleccionar filtro",
	// 		filter_reset_button_text:false,
	// 		select_type_options: {
	// 			width: '85%',
	// 			// minimumResultsForSearch: -1 // remove search box
	// 		}
	// 	}]);
		// yadcf.initMultipleTables([DTable], [{
		// 	filter_container_id: 'multi-table-filter',
		// }]);
	// }

}

function init_table_editable(tid,tbf){
	'use strict';
	let settings = {
		language: TOP.DataTable_lang,
		// lengthMenu: [[10, 50,100, -1], [10, 50, 100,"Todos"]],
		deferRender:    true,
		scrollY:        '60vh',
		scrollCollapse: false,
		scrollX:        true,
		scroller:       true,

		select:false,
		responsive: false,
		dom: 'Bfti',
		// "dom": '<"top">t<"bottom"iB><"clear">',


				buttons: [
					{
						extend: 'copy',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'print',
						exportOptions: {
							columns: ':visible'
						}
					},
					'colvis'
				],
				// info:true,
		searching:true,
		// 'lengthChange':false,
		// "paging": true,
		aaSorting: [],
		columnDefs: [{
			targets: 0,
			orderable: false,
			// visible:false
		}]
	}
	//***  FUNCION DE LLAMADA CUANDO SE TERMINA DE IMPRIMIR LA TABLA. ES USADA PARA SUMAR  TOTALES DE COLUMNAS
	if(tbf.hasOwnProperty('drawCallback')){
		settings.drawCallback = tbf.drawCallback;
	}
	if(tbf.hasOwnProperty('columnDefs')){
		settings.columnDefs = tbf.columnDefs;
	}
	return $('#'+tid).DataTable(settings);
}



function tblColCount(id) {
    let colCount = 0;
    $("#"+id+" tr:nth-child(1) th").each(function () {
        if ($(this).attr('colspan') && $(this).html().indexOf('Acciones') == -1 && $(this).html().indexOf('Monto $') == -1) {
            // console.log('cc colspan ',$(this).html());
            colCount ++;
        }
    });
    return colCount;
}

function detect_tot_col(id,col) {
    let count = -1;
    $("#"+id+" tr:nth-child(1) th").each(function () {
        count ++
        if ($(this).attr('colspan') && $(this).html().indexOf(col) > -1) {
            return false
        }
    });
    return count ;
}


// ***************************************

function upd_checked(o){
	if(!TOP.checked_items.find(x => x == o)){
		TOP.checked_items.push(o);
	}else{
		const c = TOP.checked_items.findIndex(i => i == o);
		TOP.checked_items.splice(c,1);
	}
}



// REPORTE CR1
function mk_report_cr1(o){
	// console.log('report',o)
	var headings = {'cli':'Cliente','pagado':'Pagado'};
	var maxd = 0;
	for (var i = 0;i < o.data.length ; i++) {
		// console.log('data ',o.data[i].a_pagar.events.length)
		if (o.data[i].a_pagar.events.length > maxd){maxd = o.data[i].a_pagar.events.length}
		// headings ['apg_'+i]=o.data.a_pagar[i].fecha;
	}
	var crm = new Date();
	for (var i = 0; i < maxd; i++) {
		crm = new Date(new Date(crm).setMonth(crm.getMonth()+1))
		var nm = (crm.getMonth()+1)+'/'+crm.getFullYear();
		// console.log('new date', nm)
		headings ['apg_'+i] = nm;
	}
	// console.log('head',headings);
	o.maxd = maxd;
	var out = table_reports.create({'headings':headings,'items':o}).get_screen();
	$('#main_container').html(out);
}

//******** CONFIGURACION LISTA DE ATOMS *******
// **** PONE EN PANTALLA
function mk_atoms_list(o){
	TOP.atoms = o.rows;
	TOP.atom_type = o.type;
	// TOP.curr_page_in_atom_select = o.current_page;
	// TOP.tot_pages_in_atom_select = o.tot_pages;

	const headings= {'id':'Codigo ID','atom_types_id':'Tipo','name':'Nombre'}
	const out = table_atoms.create({'type':o.type,'headings':headings,'items':o}).get_screen();

	$('#my_modal').modal('hide');
	$('#main_container').html(out);

}

function mk_contab_list(o){

	TOP.contab = o.rows;
	TOP.current_selection_table = o.id;
	TOP.curr_page_in_select_table = o.current_page;

	var headings = {};
	for (var k=0 ;k<o.rows[0].length  ;k++ ) {
		v = o.rows[0][k];
		headings[v['label']]=v['title'];

	}

	var out = table_contab.create({'headings':headings,'items':o}).get_screen();
	o.content = out;
	o.method = 'back'
	$('#my_modal').modal('hide');
	$('#main_container').html(out);
	console.log('contab_tbl...')
	$(document).ready(function (){
		$('#contab_tbl').dataTable({
					"order": [[ 0, "asc" ]],
					 "info":false,
					 'searching':true,
					 'lengthChange':false,
					 "paging": true,
					language: TOP.DataTable_lang,
				});

	})


}

// function edit_atom(o){
// 	let cnt = '';
// 	console.log('editing',o)
// 	for (var i = 0; i < o.data.length; i++){
// 		var vt = ['text','text','number','select','date'];
// 		var v =0;
// 		if(o.data[i].vis_elem_type == null || o.data[i].vis_elem_type ==  '-1'){v = 0}else{ v = parseInt(o.data[i].vis_elem_type);}

// 		var c = window[vt[v]+'_obj'].create(o.data[i]);

// 		cnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
// 	}
// 	var cont = container_obj.create(cnt);
// 	cont.title = 'Modificar Item: '+ (o.atom_name == 'null'?'nuevo':o.atom_name);
// 	mk_modal(cont);
// 	TOP.curr_edit = o;
// 	TOP.curr_ok_act = {
// 		method:'save_atom',
// 		sending: true,
// 	};
// 	TOP.curr_close_act = {method:'cancel_edit_atom'};
// }


// function new_atom(o){
// 	var cnt = '';
// 	var x= {};
// 	if(o.atom_name.indexOf('Nuevo')> -1){
// 		o.label = 'nombre';
// 		o.value = '';
// 		o.title = o.title;
// 	}
// 	var c = window['text_obj'].create(o);
// 	// cnt +="<div class=\"d-flex align-content-center p-2\">"+c.get_screen()+"</div>";
// 	// var cont = container_obj.create(c.get_screen());

// 	mk_modal(c);
// 	TOP.curr_edit = o;
// 	TOP.curr_ok_act = {
// 		method:'add',
// 		sending: true,
// 	};
// 	TOP.curr_close_act = {method:'new_atom_back'};
// }

// //  PANTALLA DE AUDITORIA
// function mk_audit(o){
// 	console.log('audit',o);


// 	var headings = {'num':'Num','cli':'Cliente','fstf_vto':'Fecha Vto','fst_monto':'Monto','fst_nro_cta':'Cuota Numero','lst_vto':'Fecha Vto.','lst_monto':'Monto','lst_nro_cta':'Cuota Numero'}
// 	// 				'nro_recibo':'Recibo Nro.','monto_recibo':'Monto','fecha_pago':'Fecha Pago'};

// 	// var tbl_cnt ={'container':'main_container','headings':headings,'items':o,extras:{'select_id':false,'caller':'audit'}};
// 	var out = table_audit.create({'headings':headings,'items':o}).get_screen();

// 	// out += "<hr class=\"my-4\"><div class=row><div class=\"col-sm-1\"></div><p class=\"lead\">Documentos</p></div>"

// 	// var h_docs = {'fecha':'Fecha','monto':'Monto','nro_comprobante':'Nro.','tipo_comprobante':'Tipo'}
// 	// var c_docs = o.docs.map(function(d){
// 	// 	var r = {
// 	// 		'fecha':d.fecha,
// 	// 		'monto':d.monto,
// 	// 		'nro_comprobante':d.nro_comprobante,
// 	// 		'tipo_comprobante':d.tipo_comprobante
// 	// 	}
// 	// 	return r;
// 	// })
// 	// out += mk_simple_table.create({'headings':h_docs,'items':c_docs}).get_screen();
// 	o.content = out;
// 	o.method = 'back'
// 	// $('#my_modal').modal('hide');
// 	$('#main_container').html(out);
// }


// ***************************************************************************
// 13/01/2020
// on selec del tipo de contraparte recarga los selects
// de concepto y contraparte en registro de operacion de caja
// ***************************************************************************
function chk_tipo_contraparte(){
	// console.log('chk', TOP.asiento_caja.selects);
	const v = $('#contraparte_select').val();
	console.log('ctp',v)
	var t = (v == 'CLIENTE' ? 'clientes' : 'proveedores');
	var so = (v == 'CLIENTE' ? 'cliente' : 'proveedor');
	var concepto = (v == 'CLIENTE' ? 'impt_cli' : 'impt_prov');
	// SELECT CONTRAPARTE
	var c ="<label for=\"contraparte\">"+ t.charAt(0).toUpperCase() + t.substr(1) + "</label>\
	            <select class=\"form-control\" id=\"contraparte\">\
	            <option value=''>Selecciona un "+so+"  -</option>";

	var cl = TOP.asiento_caja.selects[t];
	for (var i = 0; i < cl.length; i++) {
		var n = cl[i];
		if(n.lbl != null){
			c += "<option value="+n.id+">"+n.lbl+"</option>";
	  	}
	}
	c += "</select>";
	$('#fg_contraparte').html(c);
	// ---------------
	// SELECT CONCEPTO
	var x ="<label for=\"imputacion\">Concepto</label>\
	            <select class=\"form-control\" id=\"imputacion\">\
	            <option value=''>Selecciona un concepto -</option>";
	var sl = TOP.asiento_caja.selects[concepto];
		for (var i = 0; i < sl.length; i++) {
			var n = sl[i];
			if(n.lbl != null){
				x += "<option value="+n.id+">"+n.lbl+"</option>";
		  	}
		}
		x += "</select>";
		$('#fg_imputacion').html(x);
}



// PASE ENTRE CAJAS
function mk_pase_asiento_caja(o){
	console.log('pase',o)
	TOP.pase_caja = o;
	var a = pase_caja_screen.create(o);
	$('#main_container').html(a.get_screen());
	$('#bot_volver').hide();
}


//  ASIENTOS DE CAJA
function mk_asiento_caja(o){
	// console.log('en asiento caja', TOP)
	// console.log(' obj en asiento caja', o)

	TOP.asiento_caja = o;
	var a = reg_op.create(o);

	$('#main_container').html(a.get_screen());
	$('#bot_volver').hide();
}
// CHECKEA EL TIPO DE ASIENTO ONBLUR DEL SELECT para cambiar el select de proveedor/cli
function chk_tipo_asiento(){
	// console.log('chk', TOP.asiento_caja.selects);
	const v = $('#tipo_asiento').val();
	// console.log('tp',v)
	var t = (v == 'INGRESOS' ? 'clientes' : 'proveedores');
	var so = (v == 'INGRESOS' ? 'cliente' : 'proveedor');
	var concepto = (v == 'INGRESOS' ? 'impt_cli' : 'impt_prov');
	// SLECT CONTRAPARTE
	var c ="<label for=\"contraparte\">"+ t.charAt(0).toUpperCase() + t.substr(1) + "</label>\
	            <select class=\"form-control\" id=\"contraparte\">\
	            <option value=''>Selecciona un "+so+"  -</option>";

	var cl = TOP.asiento_caja.selects[t];
	for (var i = 0; i < cl.length; i++) {
		var n = cl[i];
		if(n.lbl != null){
			c += "<option value="+n.id+">"+n.lbl+"</option>";
	  	}
	}
	c += "</select>";
	$('#fg_contraparte').html(c);
	// ---------------
	// SELECT CONCEPTO
	var x ="<label for=\"imputacion\">Concepto</label>\
	            <select class=\"form-control\" id=\"imputacion\">\
	            <option value=''>Selecciona un concepto -</option>";
	var sl = TOP.asiento_caja.selects[concepto];
		for (var i = 0; i < sl.length; i++) {
			var n = sl[i];
			if(n.lbl != null){
				x += "<option value="+n.id+">"+n.lbl+"</option>";
		  	}
		}
		x += "</select>";
		$('#fg_imputacion').html(x);
}

// LISTADO DE CAJA CUANDO ES PEDIDO DE UNA SOLA
function caja_unica(data){
	let c = hcaja1.create(data);
	c.print_button = true;
	c.print_option = 'print_lcaja'
	$('#main_container').html((panel.create(c)).get_screen());
	$('#printable_content').html(print_panel.create(c).get_screen());
	let header = {'fecha':'Fecha','nro_operac':'Nro. Op.','imputacion':'Concepto','contraparte':'Contraparte','nro_comprobante':'Nro. Comprobante.','monto':'Monto','id':'Ver'};
	let content = {'table_id':'','row_indicator':'','size':'table-sm','headings':header,'items':'',extras:{'select_id':false}};
	if(data.ingresos.length > 0){
		content.items = data.ingresos;
		content.table_id = "tbl_ingresos";
		content.row_indicator = "table-success";
		content.title = "Ingresos "+ data.caja_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}
	if(data.egresos.length > 0){
		content.items = data.egresos;
		content.table_id = "tbl_egresos";
		content.row_indicator = 'table-warning';
		content.title = "Egresos "+ data.caja_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}

	if(data.hasOwnProperty('ctav_nom') && data.ctav_li.length > 0){
		content.items = data.ctav_li;
		content.table_id = "tbl_ctav_li";
		content.row_indicator = 'table-success';
		content.title = "Ingresos "+ data.ctav_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}

	if(data.hasOwnProperty('ctav_nom') &&  data.ctav_le.length > 0){
		content.items = data.ctav_le;
		content.table_id = "tbl_ctav_le";
		content.row_indicator = 'table-warning';
		content.title = "Egresos "+ data.ctav_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}


	$('#printable_content').append("<hr/><h5>Confeccionado por:</h5><h5>Firma:</h5>");
	$('#tbl_ingresos').DataTable({language: TOP.DataTable_lang});
	$('#tbl_egresos').DataTable({language: TOP.DataTable_lang});
	$('#tbl_ctav_li').DataTable({language: TOP.DataTable_lang});
	$('#tbl_ctav_le').DataTable({language: TOP.DataTable_lang});


}

// LISTADO DE CAJA CUANDO ES PEDIDO DE UNA SOLA
//  VERSION 2 CON MULTIPLES CAJAS VINCULADAS
function caja_unica_2(data){
	let c = hcaja2.create(data);
	c.print_button = true;
	c.print_option = 'print_lcaja'
	$('#main_container').html((panel.create(c)).get_screen());
	$('#printable_content').html(print_panel.create(c).get_screen());
	let header = {'fecha':'Fecha','nro_operac':'Nro. Op.','imputacion':'Concepto','contraparte':'Contraparte','nro_comprobante':'Nro. Comprobante.','monto':'Monto','id':'Ver'};
	let content = {'table_id':'','row_indicator':'','size':'table-sm','headings':header,'items':'',extras:{'select_id':false}};
	if(data.ingresos.length > 0){
		content.items = data.ingresos;
		content.table_id = "tbl_ingresos";
		content.row_indicator = "table-success";
		content.title = "Ingresos "+ data.caja_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}
	if(data.egresos.length > 0){
		content.items = data.egresos;
		content.table_id = "tbl_egresos";
		content.row_indicator = 'table-warning';
		content.title = "Egresos "+ data.caja_nom;
		$('#main_container').append(table_plc.create(content).get_screen());
		$('#printable_content').append(print_table_plc.create(content).get_screen());
	}

	if(data.hasOwnProperty('cuentas_vinculadas')){
		for (var i = 0; i < data.cuentas_vinculadas.length; i++) {
			let cx = data.cuentas_vinculadas[i];
			if(cx.ingresos.length > 0){
				content.items = cx.ingresos;
				content.table_id = "tbl_cv_"+i+"_ingresos";
				content.row_indicator = 'table-success';
				content.title = "Ingresos "+ cx.nombre;
				$('#main_container').append(table_plc.create(content).get_screen());
				$('#printable_content').append(print_table_plc.create(content).get_screen());
			}

			if(cx.egresos > 0){
				content.items = cx.egresos;
				content.table_id = "tbl_cv_"+i+"_egresos";
				content.row_indicator = 'table-warning';
				content.title = "Egresos "+ cx.nombre;
				$('#main_container').append(table_plc.create(content).get_screen());
				$('#printable_content').append(print_table_plc.create(content).get_screen());
			}

		}
	}
	let tbl_settings = {
		"order": [[ 0, "desc" ]],
		"info":false,
		'searching':false,
		'lengthChange':false,
		// "paging": false,
		'language': TOP.DataTable_lang
	};
	$('#printable_content').append("<hr/><h5>Confeccionado por:</h5><h5>Firma:</h5>");
	$('#tbl_ingresos').DataTable(tbl_settings);
	$('#tbl_egresos').DataTable(tbl_settings);
	//*** INITIALIZE DE TABLAS SI HAY CUENTAS VINCULADAS
	if(data.hasOwnProperty('cuentas_vinculadas')){
		for (var t = 0; t < data.cuentas_vinculadas.length; t++){
			$("#tbl_cv_"+t+"_ingresos").DataTable(tbl_settings);
			$("#tbl_cv_"+t+"_egresos").DataTable(tbl_settings);
		}
	}


}


function caja_multiple(data){
	let header = {'caja_nom':'Cuenta / Caja','saldo_prev':'Saldo Previo','ingresos':'Ingresos','egresos':'Egresos','total':'Total'};
	let content = {'table_id':'tbl_cajas','row_indicator':'','size':'table-sm','headings':header,'items':data,extras:{'select_id':false}};
	content.title = "Detalle de Cajas"
	$('#main_container').append(table_plc.create(content).get_screen());

}

function get_otbl_heading(v){
	let x = 0;
	h = Object.keys(v[0]).length;
	// v ROWS DE LA TABLA
	for (let i = 1; i < v.length; i++) {
		// LOS KEYS SON LOS HEADINGS EN LA TABLA
		if(Object.keys(v[i]).length > h){
			x = i;
			h = Object.keys(v[x]).length;
		}
	}
	return x;
};


// RECIBE EL OBJETO  CRUDE Y CONSTUYE UNA VENTANA GENERICA  DE INPUTS
function mk_inputs(o){
	let cnt = '';
	let col = 3;
	// console.log('inputs', o)
	if(o.title == 'Asiento de caja'){col = 2}
	for (var i = 0; i < o.data.length; i++){
		// DIVIDO EN COLUMNAS
		if(i % col == 0){
			cnt += "</div><div class=\"row\">";
		};
		// CREO OBJETOS DE INPUT
		// EL ARRAY _vt  ELIGE EL TIPO DE ELEMENTO SEGUN ESTA en la columna vis_elem_type de pcles
		cnt += "<div class=\"col\">";
		var vt = ['text','text','number','select','date'];
		// console.log('d',vt[o.data[i].vis_elem_type])
		var c = window[vt[o.data[i].vis_elem_type]+'_obj'].create(o.data[i]);
		cnt += c.get_screen();
		cnt += "</div>";
	}
	// var p = {'method':o.method,'sending':true,'action':o.action}
	// var x = {title:o.title,content:cnt,footer:'',call_param:p,call_text:'Guardar'}
	o.content = cnt;
	o.call_text = 'Guardar';
	o.footer = '';
	$('#my_modal').modal('hide');
	$('#main_container').html(jb2.create(o).get_screen());
}


// RECIBE EL OBJETO  CRUDE Y CONSTUYE UNA VENTANA GENERICA  DE INPUTS
function mk_cli_inputs(o){
	var cnt = '';
	var col = 3;
	rc = 0
	// console.log('odata ',o)
	for (var i = 0; i < o.data.length; i++){
		// DIVIDO EN COLUMNAS
		if(rc % col == 0){
			cnt += "</div><div class=\"row\">";
		};
		if(o.data[i].label == 'nombre'){rc= 0;cnt += "</div><div class=\"row\"><legend> Datos del Cliente</legend>"}
		if(o.data[i].label == 'nombre_contacto'){rc= 0;cnt += "</div><div class=\"row\"><legend><hr class='my-4'> Datos de Contacto 1 </legend>"}
		if(o.data[i].label == 'nombre_segundo_contacto'){rc= 0;cnt += "</div><div class=\"row\"><legend><hr class='my-4'> Datos de Contacto 2 </legend>"}
		// CREO OBJETOS DE INPUT
		// EL ARRAY _vt  ELIGE EL TIPO DE ELEMENTO SEGUN ESTA en la columna vis_elem_type de pcles
		// console.log('odata in for :',o.data[i])
		cnt += "<div class=\"col\">";
		var vt = ['text','text','number','select','date'];
		// if(o.data[i].label != 'vendedor'){
			var vis_elem_type = (o.data[i].vis_elem_type > 2 ?o.data[i].vis_elem_type:1);
			var c = window[vt[vis_elem_type]+'_obj'].create(o.data[i]);
			cnt += c.get_screen();
			cnt += "</div>";
		// }
		rc ++;
	}
	cnt += "</div>";
	// var p = {'method':o.method,'sending':true,'action':o.action}
	// var x = {title:o.title,content:cnt,footer:'',call_param:p,call_text:'Guardar'}
	o.content = cnt;
	o.call_text = 'Guardar';

	// TOP.last_call_param.selects = o.footer;
	// var v = o.data.find(function(i){return i.label == 'vendedor'});
	// var f = window['select_obj'].create(v);
	// o.footer = f.get_screen();
	$('#my_modal').modal('hide');
	$('#main_container').html(jb2.create(o).get_screen());
}

// MODIFICA EL FORMATO DEL CONTENIDO DE VAL PARA EL TD DEL OBJETO TABLA SEGUN EL TEXTO EN KEY
//  retorna el contenido formateado
function td_format_cont(key,val){
	let r = "<td>"+val+"</td>";
	if(key.indexOf('Saldo')> -1 || key.indexOf('Total')> -1){
		r =  '<td class=\'text-right\' data-order=\''+val+'\'>'+accounting.formatMoney(parseFloat(val), "", 0, ".", ",")+"</td>" ;
	}
	if(key.indexOf('Valor')> -1 || key.indexOf('VALOR')> -1){
		r =  '<td class=\'text-center\'data-order=\''+val+'\'>'+accounting.formatMoney(parseFloat(val), "$", 0, ".", ",")+"</td>" ;
	}
	if(key.indexOf('AHORRO') > -1 ||key.indexOf('MONTO') > -1 || key.indexOf('Monto') > -1 || key.indexOf('Limite Cred.') > -1 || key.indexOf('tot_cta') > -1 || key.indexOf('Intereses') > -1){
		if(parseFloat(val) % 1 !== 0){
			r =  '<td class=\'text-center\' data-order=\''+val+'\'>'+accounting.formatMoney(parseFloat(val), "$", 0, ".", ",")+"</td>" ;
		}else{
			r =  '<td class=\'text-center\' data-order=\''+val+'\'>'+accounting.formatMoney(parseFloat(val), "$", 0, ".", ",")+"</td>" ;
		}
	}
	if(key.indexOf('Fecha') > -1 ){
		r =  '<td class=\'text-right\'>'+val+"</td>" ;
	}
	if(key.indexOf('Nro') > -1 ){
		r =  '<td class=\'text-center\'>'+val+"</td>" ;
	}
	if(key.indexOf('Cuenta') > -1 || key.indexOf('Cant') > -1  ){
		r =  '<td class=\'d-flex justify-content-center\'>'+val+"</td>" ;
	}
	if(key.indexOf('Cant') > -1 ){
		r =  '<td class=\'d-flex justify-content-center\'>'+val+"</td>" ;
	}
	if(key.indexOf('srv_name') > -1 ||key.indexOf('lote_name') > -1 ){
		r =  '<td width=\'110\' class=\'d-flex justify-content-left pl-3\'>'+val+"</td>" ;
	}
	if(key.indexOf('Acciones') > -1 ){
		r =  '<td class=\'acciones\'>'+val+"</td>" ;
	}
	return r;
}


// RECIBE DATA OBJECT EN O, FORMATEA EL VAL Y DEVEUELVE UN TD COMPLETO O UN EMPTY TD
function data_format_hook(id,o,val){
	let r = "<td>"+val+"</td>";
	if(o.label == 'prod_id'){
		r = "<td>"+(val!= undefined ?val.substring(0,8):'');
		r +="<a class=\'ml-3\' tabindex=\"0\" role=\"button\" onClick=front_call({method:\'get_elements\',sending:true,data:{elm_id:\'"+id+"\'}})	>";
		r +="<i class=\"material-icons m-0 p-0\">launch</i>";
		r +="</a></td>";
	}
	return r;
}



function call_popover(id){
		$('#td_icon_'+id).html('hourglass_empty');
		 $.ajax({
					url:"reportes/fetch_popover",
					method:"POST",
					async:true,
					data:{id:id},
					success:function(data){
						if(data == ''){data = 'Sin datos...'}
						$('#popover_'+id).popover({content:data});
						$('#popover_'+id).popover('show');
						$('#td_icon_'+id).html('people_outline');
					},
					error : function(xhr, ajaxOptions, thrownError) {
						$.unblockUI();
						$('#popover_'+id).popover({content:'NO HAY DATOS',placement:'top'});
						$('#popover_'+id).popover('show');
						$('#td_icon_'+id).html('people_outline');
					}
		 });

}


// RECIBE EL OBJETO A ENVIAR A PANTALLA
function mk_screen(o){
	$('#'+o.get('container')).html(o.get_screen());
}

//  *** LISTADO PLANILLA DE CAJA
function mk_planilla_caja(o){
	// console.log('pl',o);

	var ingre = o.res_id.ingresos;
	var egre = o.res_id.egresos;
	o.tt_ingr = 0;
	for(key in ingre){
		o.tt_ingr += parseFloat(ingre[key].monto);
	}
	o.tt_egre = 0;
	for(key2 in egre){
		o.tt_egre += parseFloat(egre[key2].monto);
	}
	o.caja_nom = o.res_id.caja_nom;
	o.saldo = parseFloat(o.res_id.saldo);
	o.fd =o.res_id.fec_desde;
	o.fh =o.res_id.fec_hasta;
	// PANTALLA DE LAPLANILLA
	var plc = planilla_caja.create(o);
	$('#main_container').html(plc.get_screen());

	// PREPARE TABLAS INGRESOS Y EGRESOS
	//SETEO DEL ARRAY Y MAKE TABLE
		// TOP.xc = c.slice(0);
		// console.log('pl',o)
		if(ingre.length > 0){
			var h1 = {'fecha':'Fecha','nro_operac':'Nro. Op.','imputacion':'Concepto','contraparte':'Contraparte','nro_comprobante':'Nro. Comp.','monto':'Monto','id':'Ver'};
			var c1 ={'table_id':'tbl_ingre','container':'table_ingresos','row_indicator':'table-success','size':'table-sm','headings':h1,'items':ingre , extras:{'select_id':false,'caller':'pl_caja'}};
			var tbl_ingresos = table_plc.create(c1);
			mk_screen(tbl_ingresos);
			$('#tbl_ingre').DataTable({
				language: TOP.DataTable_lang
			});

		}
		if(egre.length > 0){
			var h2 = {'fecha':'Fecha','nro_operac':'Nro. Op.','imputacion':'Concepto','contraxt_objparte':'Contraparte','nro_comprobante':'Nro. Comp.','monto':'Monto','id':'Ver'};
			var c2 ={'table_id':'tbl_egre','container':'table_egresos','row_indicator':'table-warning','size':'table-sm','headings':h2,'items':egre , extras:{'select_id':false,'caller':'pl_caja'}};
			var tbl_egresos = table_plc.create(c2);
			mk_screen(tbl_egresos);
			$('#tbl_egre').DataTable({
				language: TOP.DataTable_lang
			});
		}






	// $('#bot_volver').hide();

}



// *** HACE EL LISTADO DE LAS LISTAS DE PRECIOS
function mk_lpr(d){
	TOP.lpr_data = d;
	$('#my_modal').modal('hide');
	var tc = d['pcles'].filter(function(i){return i.label === 'cotiz_usd'});
	var m2 = d['pcles'].filter(function(i){return i.label === 'm2_usd'});
	var screen = "<div class=\"row\" id=\"screen_lpr\">\
				<div class=\"col\"></div>\
				<div class=\"col-lg-6 \">\
					<p></p>\
					<div class=\"jumbotron\">\
						<p class=\"lead\">\
							Lista de precios: "+d.atom_name+"\
						</p>\
						<div class ='row'><div class='col-md-6'>"+editable.create(tc[0])+"</div>\
						<div class='col-md-6'>"+editable.create(m2[0])+"</div>\
						</div>\
					</div>\
				</div>\
				<div class=\"col\"></div>\
			</div>"


	// ***** INDICE DE ANTICIPO ******
	var iant = d.pcles.filter(function(i){return i.label.match(/iant_\d+/)});
	var iant_headings = ['Cuotas','Indice de Anticipo'];
	var iant_cnt = iant.map(function(i){return f={'id':i.id,'label':i.label.substr(i.label.indexOf('_')+1),'value':i.value}});
	var tbl_iant ={'headings':iant_headings,'items':iant_cnt};
	var iant_screen = mk_editable_table.create(tbl_iant);


	// **** INDICE DE CUOTA MENSUAL ****
	var icm = d.pcles.filter(function(i){return i.label.match(/icm_\d+/)});
	var icm_headings = ['Cuotas','Indice Mensual'];
	var icm_cnt = icm.map(function(i){return f={'id':i.id,'label':i.label.substr(i.label.indexOf('_')+1),'value':i.value}});
	var tbl_icm ={'headings':icm_headings,'items':icm_cnt};
	var icm_screen = mk_editable_table.create(tbl_icm);

	screen +="<div class=\"row justify-content-center\">";
	screen += "<div class=\"jumbotron col-lg-4\">"+iant_screen+"</div>";

	screen += "<div class=\"jumbotron col-lg-4\">"+icm_screen+"</div>";
	screen += "</div>";
	$('#main_container').html(screen);
}

// RECIBE EL ARRAY DE PCLES Y ACTUALIZA SU VALOR DESDE LOS INPUTS FIELDS
function update_crude_data(d){
	return d.map(function(i){
		i.value = $('#'+i.label).val();
		return i;
	});
}


// ******* TABLAS DE CUOTAS LOTE Y SERVICIOS PARA RESUMEN DE CUENTA DEL CLIENTE WEB -> (obj rdcc) ******

function rdcc_setup_cuotas(elm){
	if(elm.cuotas.a_pagar.length >0){
		var headings = {'lote_name':'Descripcion','nro_cta':'Cta. Nro','fec_vto':'Fecha Vto.','termino':'Estado','dias_mora':'Dias en Mora','interes_mora':'Int. Mora','tot_cta':'Monto $'};
		var contenido ={'container':'ctas_lote_'+elm.elm_id,'headings':headings,'items':elm.cuotas.a_pagar,extras:{'select_id':false,'caller':'rdcc_ctas','editables':[],'edit_call':'update_edi'}};
		var tbl = mk_simple_table.create(contenido);
		mk_screen(tbl);
	}
	if(elm.cuotas.srv.length >0){
		var srvh = {'srv_name':'Descripcion','nro_cta':'Cta. Nro','fec_vto':'Fecha Vto.','termino':'Estado','dias_mora':'Dias en Mora','interes_mora':'Int. Mora','tot_cta':'Monto $'}
		var srvc ={'container':'ctas_servicios_'+elm.elm_id,'headings':srvh,'items':elm.cuotas.srv,extras:{'select_id':false,'caller':'rdcc_ctas','editables':[],'edit_call':'update_edi'}};
		var t = mk_simple_table.create(srvc);
		mk_screen(t);
	}

}


function get_total_amount(dt,lbl){
	return dt.map(function(t){return t[lbl]}).reduce(function(a, b){ return a + b; },0);
}

// ******* END TABLAS DE CUOTAS LOTE Y SERVICIOS PARA RESUMEN DE CUENTA DEL CLIENTE WEB -> (obj rdcc) ******

// ******** ACTUALIZA LAS CUOTAS SELECIONADAS
function ctas_sl(){
	TOP.pago.cta_events_id = [];
	TOP.pago.tot_ctas = 0;
	TOP.pago.intereses = 0;
	TOP.pago.monto_cta = [];
	TOP.pago.ctas_nro = [];
	TOP.pago.termino = [];
	TOP.pago.dias_mora = [];
	TOP.pago.interes_mora = [];


	s = TOP.selected_ids;
	t = TOP.current_selection_table;
	// RECORRE LOS ITEMS SELECCIONADOS
	for (var k=0; k< t.length;k++ ) {
		var f = s.find(function(e) {return e == t[k].events_id;});
		if(f){
			if(t[k].hasOwnProperty('nro_cta'))	{
				TOP.pago.monto_cta.push(t[k].tot_cta);
				TOP.pago.termino.push(t[k].termino);
				TOP.pago.dias_mora.push(parseInt(t[k].dias_mora));
				TOP.pago.interes_mora.push(parseInt(t[k].interes_mora));
				TOP.pago.ctas_nro.push(t[k].nro_cta);
				TOP.pago.tot_ctas += parseInt(t[k].tot_cta);
				TOP.pago.intereses += parseInt($('#edi_'+t[k].events_id).val());
				TOP.pago.cta_events_id.push(t[k]);
				$('#edi_'+t[k].events_id).val(parseInt(t[k].interes_mora));
				// ACTIVA O DESACTIVA EL CAMPO EDITABLE DE INTRESES SI EL CAMPO DIAS DEN MORA ES MAYOR A CERO
				if(parseInt(t[k].dias_mora) > 0){
					$('#edi_'+t[k].events_id).prop('disabled', false);
				}
			}
		}
	}
}

// ***** ACTUALIZA LOS SERVICIOS
function srv_sl(){
	TOP.pago.servicios = 0;
	TOP.pago.serv_id = [];
	s = TOP.selected_ids;
	t = TOP.current_selection_table;
	for (var k=0; k< t.length;k++ ) {
		var f = s.find(function(e) {return e == t[k].events_id});
		if(f){
			if(t[k].hasOwnProperty('srv')){
				TOP.pago.serv_id.push(t[k]);
				TOP.pago.servicios += parseInt(t[k].monto_cta);
			}
		}
	}
	// console.log('sid',TOP.pago.serv_id);
	// console.log('srvs',TOP.pago.servicios);
}
function res_check_recnum(r){
	//console.log('reschk', r);
	switch(r){
		case 'OK':
		TOP.recnum_ok_state = true;
		$('#fg_rec_num').removeClass("has-danger");
		$('#rec_num').removeClass('is-invalid');
		$('#fg_rec_num').addClass("has-success");
		$('#rec_num').addClass('is-valid');
		break;
		case 'FAILED':
		TOP.recnum_ok_state = false;
		$('#fg_rec_num').removeClass("has-success");
		$('#rec_num').removeClass('is-valid');
		$('#fg_rec_num').addClass("has-danger");
		$('#rec_num').addClass('is-invalid');

		myAlert({'container':'modal','type':'danger','tit':'Error!','msg':'El numero de recibo '+$('#rec_num').val()+' no es valido, ya existe. ','extra':'no_autohide'});
		break;
	}
}


function update_selected(){
	// console.log('id',i)
	// console.log('state',$('#select_id_check_'+i).prop('checked'))
	TOP.selected = [];
	TOP.cargos.map(function(i){
		var s = TOP.cargos.find(x=>x.events_id == i.events_id);
		s.selected = $('#select_id_check_'+i.events_id).prop('checked');
	});
	TOP.selected = TOP.cargos.filter(x=>x.selected);
	// console.log('seleted',TOP.selected)
	totales_pago();
	check_update_plan();
}

function set_top_pago(c){
	console.log('setting_top_pago',c);
	TOP.pago = {};
	TOP.pago.selects = (c.hasOwnProperty('selects') ? c.selects:[])
	TOP.pago.cuotas = (c.hasOwnProperty('cuotas')? c.cuotas:[]);
	TOP.pago.servicios = (c.hasOwnProperty('servicios')? c.servicios:[]);
	TOP.saldo_anterior = (c.hasOwnProperty('saldo_int')? c.saldo_int:0);
	TOP.pago.rec_num = (c.hasOwnProperty('rec_num') ? c.rec_num:0);
	TOP.pago.update_pending = (c.hasOwnProperty('update_pending') ? c.update_pending:0);


	TOP.cargos = TOP.pago.cuotas.concat(TOP.pago.servicios);
	TOP.selected = TOP.cargos.filter(i => i.selected);
}

function check_update_plan(){
	if(TOP.pago.update_pending == 'true'){
		$('#bot_imputar_pago').hide();
		$('#bot_update_plan').show();
	}else{
		$('#bot_imputar_pago').show();
		$('#bot_update_plan').hide();
	}
}


function totales_pago(){
	TOP.tot_monto_ctas = TOP.cargos.filter(i => i.tipo == 'cta_lote' && i.selected).map(function(t){return t.tot_cta}).reduce(function(a, b){ return a + b; },0);
	TOP.tot_monto_intrs = TOP.cargos.filter(i => i.termino == 'EN_MORA' && i.selected).map(function(t){return t.interes_mora}).reduce(function(a, b){ return a + b; },0);
	TOP.tot_monto_srvc = TOP.cargos.filter(i => i.tipo == 'cta_srvc' && i.selected).map(function(t){return t.tot_cta}).reduce(function(a, b){ return a + b; },0);

	TOP.tot_a_pagar = TOP.tot_monto_ctas+TOP.tot_monto_intrs+TOP.tot_monto_srvc;


	TOP.estado_actual = TOP.saldo_anterior - TOP.tot_a_pagar;


	// TOP.grand_tot = (TOP.tot_monto_subtot > 0 ?0:gtsl);

	$('#monto_ctas').val(accounting.formatMoney(parseInt(TOP.tot_monto_ctas), " ", 0, ".", ","));
	$('#monto_interes').val(accounting.formatMoney(parseInt(TOP.tot_monto_intrs), " ", 0, ".", ","));
	$('#monto_servicios').val(accounting.formatMoney(parseInt(TOP.tot_monto_srvc), " ", 0, ".", ","));
	$('#monto_a_pagar').val(accounting.formatMoney(parseInt(TOP.tot_a_pagar), " ", 0, ".", ","));
	$('#saldo').val(accounting.formatMoney(parseInt(TOP.saldo_anterior), " ", 0, ".", ","));

	$('#estado_actual').val(accounting.formatMoney(parseInt(TOP.estado_actual), " ", 0, ".", ","));

	console.log('setting pago buttons')
	// RESETEA PAGOS CONTAINER PORQUE CAMBIO EL MONTO TOTAL ENVIADO A LA PASARELA DE PAGOS
	$('#pagos_container').html(TOP.botones_de_pago);
	// *****
}

function set_pantalla_pago(){
	var mpgc = pgc.create();
	mk_screen(mpgc);
	listado_ctas();
	listado_srvc();
}

function listado_ctas(){
	if(TOP.pago.cuotas.length >0){
		var headings = {'events_id':'Chk','lote_name':'Descripción','nro_cta':'Cta. Nro','fec_vto':'Fecha Vto.','termino':'Estado','dias_mora':'Dias en Mora','interes_mora':'Int. Mora','tot_cta':'Monto $'};
		var contenido ={'container':'ctas_table','headings':headings,'items':TOP.pago.cuotas,extras:{'select_id':true,'caller':'pago_ctas','editables':['interes_mora',],'edit_call':'update_edi'}};
		var tbl = mk_simple_table.create(contenido);
		mk_screen(tbl);
	}
}

function listado_srvc(){
	// console.log('en listado serv',TOP.pago.servicios)
	if(TOP.pago.servicios.length > 0){
		$('#tit_servicios').html('Cuotas Servicios')
		var srvh = {'events_id':'Chk','srv_name':'Descripcion','nro_cta':'Cta. Nro','fec_vto':'Fecha Vto.','termino':'Estado','dias_mora':'Dias en Mora','interes_mora':'Int. Mora','tot_cta':'Monto $'}
		var srvc ={'container':'srvs_table','headings':srvh,'items':TOP.pago.servicios,extras:{'select_id':true,'caller':'servicios','editables':['interes_mora'],'edit_call':'update_edi'}};
		var t = mk_simple_table.create(srvc);
		mk_screen(t);
	}
}
// ACTUALIZA INTERESES
function upd_intrst(e){
	var i = TOP.cargos.find(x=>x.events_id == e.id);
	i.interes_mora = parseInt((isNaN($('#edi_'+e.id).val()) || $('#edi_'+e.id).val() == ''?0:$('#edi_'+e.id).val()));
	$('#edi_'+e.id).val(i.interes_mora);
	update_selected();
}

function set_adls(c){
	TOP.pago.adls = 0;
	TOP.pago.adl_mtc = 0;
	TOP.pago.srv_adls = 0;
	// console.log('adls',c.adls.disp)
	if(c.hasOwnProperty('adls') && c.adls.hasOwnProperty('disp') && c.adls.disp.length > 0){
		TOP.pago.adls = c.adls;
		TOP.pago.adl_mtc = c.adls.mt_cta;
		TOP.pago.adl_ids = TOP.pago.adls.disp.reverse();
		TOP.pago.adl_pcles = TOP.pago.adls.pcles.reverse();
	}
	if(c.hasOwnProperty('srv_register') && c.srv_register.length > 0 && c.hasOwnProperty('srv_adls') && c.srv_adls.length > 0){
		TOP.pago.srv_adls = c.srv_adls;
		TOP.pago.serv_id = [];
		TOP.pago.srv_adl_reg = [];
		c.srv_adls.map(function(i){
			TOP.pago.srv_adl_reg.push({
				mt_cta:i.adls.mt_cta,
				reg_id:i.srv_register,
				srv_name:i.srv_name,
				srv_imputacion_id:i.srv_imputacion_id,
				disp:(i.adls ?i.adls.disp.reverse(): 0),
				pcle:(i.adls ?i.adls.pcles.reverse():0)
			})
		});
	}

}

function agregar_adls(t){
	switch(t){
		case 'lote':
			var cta = TOP.pago.adl_ids.shift();
			var pcle = TOP.pago.adl_pcles.shift()
			var o = {
				'selected':true,
				'events_id':parseInt(cta.events_id),
				'fec_vto':cta.fec_vto,
				'nro_cta':pcle,
				'tipo':'cta_lote',
				'termino':'ADL',
				'dias_mora':0,
				'interes_mora':0,
				'tot_cta':parseInt(TOP.pago.adl_mtc)

			};
			// RESETEO PAGOS CONTAINER PARA EVITAR CLIKEOS EN REDIRECIONADOR DE PAGO CON MONTOS VIEJOS

			TOP.selected.push(o)
			TOP.pago.cuotas.push(o);
			listado_ctas();
		break;
		case 'servicios':
			console.log('adding srv',TOP.pago);
			if(!TOP.pago.hasOwnProperty('servicios')){
				TOP.pago.servicios = [];
			}
			TOP.pago.srv_adl_reg.map(function(x){
				if(x.disp.length > 0 && x.pcle.length > 0){
					var srv_cta =  x.disp.shift();
					var srv_pcle = x.pcle.shift();
					var s = {
						'selected':true,
						'events_id':parseInt(srv_cta.events_id),
						'fec_vto':srv_cta.fec_vto,
						'nro_cta':srv_pcle,
						'srv_name':x.srv_name,
						'srv_cta_imputacion_id':x.srv_imputacion_id,
						'tipo':'cta_srvc',
						'termino':'ADL',
						'dias_mora':0,
						'interes_mora':0,
						'tot_cta':parseInt(x.mt_cta)
					};
					TOP.selected.push(s)
					TOP.pago.servicios.push(s);
					// console.log('pushing serv',TOP.selected);
				}
			});

			listado_srvc();
		break;
	}
	TOP.cargos = TOP.pago.cuotas.concat(TOP.pago.servicios);
	update_selected();
}

//***  DEPRECATE ***
function is_pac(r,a){

	if((a-r)>0){
		return false;
	}else{
		return true
	}

}

function check_pgc_monto_regibido(){
	// console.log('checking monto',$('#monto_recibido').val())
	if($('#monto_recibido').val() < 0){
		$('#monto_recibido').val(0);
	}
}

function clean_top(){
	TOP.adl=0;
	TOP.adl_ids=[];
	TOP.adl_pcles=[];
	TOP.cards_data = {};
	TOP.curr_recibo_nro=0;
	TOP.current_selection_table=[];
	TOP.data={};
	TOP.pago={};
	TOP.selected_ids=[];
	TOP.xc=[];
}


// BOT IMPRIMIR RECIBO
function print_recibo_cta(r){

	TOP.curr_rec = r;
	// TOP.concepto = r.concepto;
	// TOP.monto_recibo = r.monto_recibido;

	var o = {
		'msg':"<div class=\"row\">\
					<div class=\"col\">\
						<div class=\"btn btn-primary btn-block\" onClick=print_elem('recibo')  href=\"#\" role=\"button\">Ver / Imprimir Recibo</div>\
					</div>\
				</div>",
		'type':'success',
		'tit':'',
		'container':'modal'
		// 'win_close_method':'back'
	}
	// o.after_action = 'back';
	TOP.curr_ok_act = {method:'get_elements',sending:true,data:{elm_id:r.elem_id}};
	TOP.curr_close_act = {method:'get_elements',sending:true,data:{elm_id:r.elem_id}};
	myAlert(o);
	return false
}

function print_pagares(r){

	TOP.curr_rec = r;
	// TOP.concepto = r.concepto;
	// TOP.monto_recibo = r.monto_recibido;

	var o = {
		'msg':"<div class=\"row\">\
					<div class=\"col\">\
						<div class=\"btn btn-primary btn-block\" onClick=print_elem('pagares')  href=\"#\" role=\"button\">Ver / Imprimir Recibo</div>\
					</div>\
				</div>",
		'type':'success',
		'tit':'',
		'container':'modal'
		// 'win_close_method':'back'
	}
	// o.after_action = 'back';
	TOP.curr_ok_act = {method:'get_elements',sending:true,data:{elm_id:r.elem_id}};
	TOP.curr_close_act = {method:'get_elements',sending:true,data:{elm_id:r.elem_id}};
	myAlert(o);
	return false
}



function curr_config_state_change(state){
	if(state == 'a_revisar'){
		$('#btn_curr_state').html('A Revisar');
		$('#btn_curr_state').removeClass('btn-success');
		$('#btn_curr_state').addClass('btn-danger');
		$('#btnGroupDrop1').removeClass('btn-success');
		$('#btnGroupDrop1').addClass('btn-danger');
	}
	if (parseInt(TOP.permisos) < 2 && state === 'normal'){
			$('#btn_curr_state').removeClass('btn-danger');
			$('#btn_curr_state').addClass('btn-success');
			$('#btnGroupDrop1').removeClass('btn-danger');
			$('#btnGroupDrop1').addClass('btn-success');
			$('#btn_curr_state').html('Revisado');
	}
	front_call({'method':'set_config_curr_state','state':state});

}


function curr_state_change(e){
	$('#my_modal').modal('hide');
	switch(e){
		case 'NORMAL':
			$('#btn_curr_state').removeClass('btn-danger');
			$('#btn_curr_state').addClass('btn-success');
			$('#btnGroupDrop1').removeClass('btn-danger');
			$('#btnGroupDrop1').addClass('btn-success');
			$('#btn_curr_state').html('Revisado');
		break;
		case 'EN_REVISION':
			$('#btn_curr_state').html('A Revisar');
			$('#btn_curr_state').removeClass('btn-success');
			$('#btn_curr_state').addClass('btn-danger');
			$('#btnGroupDrop1').removeClass('btn-success');
			$('#btnGroupDrop1').addClass('btn-danger');
		break;
		case 'REVISADO':
			$('#btn_curr_state').removeClass('btn-danger');
			$('#btn_curr_state').addClass('btn-success');
			$('#btnGroupDrop1').removeClass('btn-danger');
			$('#btnGroupDrop1').addClass('btn-success');
			$('#btn_curr_state').html('Revisado');
		break;
		case 'EN_RESCISION':
			$('#btn_curr_state').html('En Rescisión');
			$('#btn_curr_state').removeClass('btn-success');
			$('#btn_curr_state').addClass('btn-warning');
			$('#btnGroupDrop1').removeClass('btn-success');
			$('#btnGroupDrop1').addClass('btn-warning');
		break;
		case 'RESCINDIDO':
			$('#btn_curr_state').html('En Rescisión');
			$('#btn_curr_state').removeClass('btn-success');
			$('#btn_curr_state').addClass('btn-danger');
			$('#btnGroupDrop1').removeClass('btn-success');
			$('#btnGroupDrop1').addClass('btn-danger');
		break;
		case 'EN_LEGALES':
			$('#btn_curr_state').html('En Rescisión');
			$('#btn_curr_state').removeClass('btn-success');
			$('#btn_curr_state').addClass('btn-danger');
			$('#btnGroupDrop1').removeClass('btn-success');
			$('#btnGroupDrop1').addClass('btn-danger');
		break;
		case 'EN_ACTUALIZACION':
			$('#btn_curr_state').html('En Rescisión');
			$('#btn_curr_state').removeClass('btn-success');
			$('#btn_curr_state').addClass('btn-warning');
			$('#btnGroupDrop1').removeClass('btn-success');
			$('#btnGroupDrop1').addClass('btn-warning');
		break;
		case 'ACTUALIZADO':
			$('#btn_curr_state').removeClass('btn-danger');
			$('#btn_curr_state').addClass('btn-success');
			$('#btnGroupDrop1').removeClass('btn-danger');
			$('#btnGroupDrop1').addClass('btn-success');
			$('#btn_curr_state').html('Revisado');
		break;


	}

}

//  GENERA CARD DE CLIENTES

/*
data.sort(function(a,b) {
  a = a.split('/').reverse().join('');
  b = b.split('/').reverse().join('');
  return a > b ? 1 : a < b ? -1 : 0;

  // return a.localeCompare(b);         // <-- alternative

});

*/

// crea  el objeto res y lo manda a mk_modal
// function get_detalle(p){
// 	// console.log('get detalle',p);
// 	var res = table_detalle_ctas.create(p);
// 	mk_modal(res);

// }

function get_Tpcle(arr,lbl){
	if(arr.length > 0){
		var r = arr.filter(function(i){return i.label === lbl});
		if(r.length > 0){
			return r[0]['value'];
		}
	}
}


// ACTUALIZA LA PANTALLA SEGUN CASOS SELECCIONADOS
function check_select(id){

	switch (id){
		case 'financiacion':
			// console.log('financ',$('#'+id).children('option:selected').val())
			// console.log('financ anticipo?',($('#'+id).children('option:selected').html().indexOf('ANTICIPO +') > -1 ?'yep':'NOP'))
			// // BUSCA LAS DOS OPCIONES DE FINANCIACIONES DE SERVICIOS CON ANTICIPO + CTAS
			if($('#'+id).children('option:selected').html().indexOf('ANTICIPO +') > -1){
					$('#cnt_anticipo').removeClass('d-none');
					$('#cnt_anticipo').addClass('d-flex');
					// $('#cnt_anticipo').removeClass('d-none');

			}else{
					$('#cnt_anticipo').removeClass('d-flex');
					$('#cnt_anticipo').addClass('d-none');
			}
			if($('#'+id).children('option:selected').html().indexOf('Anticipo') > -1){
					$('#cnt_cant_ctas_post_posesion').removeClass('d-none');
					$('#cnt_cant_ctas_post_posesion').addClass('d-flex');
					// $('#cant_ctas_post_posesion').removeClass('d-none');

			}else{
					$('#cnt_cant_ctas_post_posesion').removeClass('d-flex');
					$('#cnt_cant_ctas_post_posesion').addClass('d-none');
			}
		break;
		case 'servicios':
			if($('#servicios option:selected').text().match(/(prestamo*)/i)){
				$('#cnt_cuentas').removeClass('d-none')
			}else{
				$('#cnt_cuentas').addClass('d-none')
			}

		break;
		case 'rev_fplan':
			front_call({method:'revision_update_select',sending:true,data:{financ_id:$('#rev_fplan').val()}});
		break;
		case 'cuentas':
			TOP.curr_cuentas_selected_id = $('#cuentas').val();
		break;
	}
}

// CAMBIA DE FORMATO DD/MM/YYYY A YYYY-MM-DD
function fx_date_to_ymd(val){
    var t = val.split('/')
    return new Date(t[2],parseInt(t[1])-1,t[0])
}

// CAMBIA DE FORMATO YYYY-MM-DD A DD/MM/YYYY
function fx_date_to_dmy (str_dt){
    var t = str_dt.split('-')
    return t[2]+'/'+t[1]+'/'+t[0];
}

//*****************************************************
//  CUANDO SE ESTA CREANDO UN CONTRATO PUEDE HABER
// VARIOS CLIENTES COTITULARES  DEL MISMO LOTE
// toma de top la propiedad extra_cotitular y separa con comas los ids de los titulares
function extra_cotitular_handler(){

}


// DEPRECATED
function add_owner(){
	if($('#cli_'+TOP.count_clientes_contrato).val() != ''){
		TOP.count_clientes_contrato ++;
		var t = "<div class=\"form-group\" id=\"owners_"+TOP.count_clientes_contrato+"\">\
		<label class=\"col-form-label\" for=\"cli_"+TOP.count_clientes_contrato+"\">Comprador "+TOP.count_clientes_contrato+"</label>\
	  	<input type=\"text\" class=\"form-control\" placeholder=\"Ingresa nombre del cliente\" id=\"cli_"+TOP.count_clientes_contrato+"\">\
	  	</div>";
		$('#owners_container').append(t);
		$('#cli_'+TOP.count_clientes_contrato).autocomplete({
					    source:  "clientes/autocomplete_clientes",
						minLength: 2,
						response: function( event, ui) {

						},
						select: function(event, ui)
				   		{
				      		var found = TOP.cli_id.find(function(element) {
							  return element ==  ui.item.id;
							});
				      		if(found){
				      			remove_owner();
				      		}else{
				      			TOP.cli_id.push(ui.item.id);
				      		}

				      	}
					});
		$('#cli_'+TOP.count_clientes_contrato).focus();

	}

}

function remove_owner(){
	if(TOP.count_clientes_contrato > 1){
		$('#owners_'+TOP.count_clientes_contrato).remove();
		TOP.count_clientes_contrato --;
		TOP.cli_id.splice(-1);
		$('#cli_'+TOP.count_clientes_contrato).focus();

	}

}


//  AGREGAR SELECTS EN CENTRO DE COSTOS

function add_cctos(){
	if($('#percent_cctos_'+TOP.count_centro_costos_list).val() != '' && $('#percent_cctos_'+TOP.count_centro_costos_list).val() > 0){
		TOP.count_centro_costos_list ++;
		var t ="<div class='row d-flex justify-content-between'>";
		t +="<div class='col d-flex'>";
		t += "<div class='form-group' id='fg_cent_ctos_"+TOP.count_centro_costos_list+"'><label for='cent_ctos'>Centro de Costos</label>";
		t += "<select class='form-control' id='cent_ctos_"+TOP.count_centro_costos_list+"' onChange='select_cctos_id(this.id)'><option value=''>Selecciona el Centro de Costos</option>"+glbl_fill_select(TOP.selects.centro_costos)+"</select>";
		t += "</div>";
		t += "</div>";
		t += "<div class='col d-flex'>";
		t += "<div class='form-group' id='fg_percent_cctos_"+TOP.count_centro_costos_list+"'><label for='percent_barrio_"+TOP.count_centro_costos_list+"'>Porcentaje de distribución</label>";
		t += "<input type='number' max=100 min=0 class='form-control' id='percent_cctos_"+TOP.count_centro_costos_list+"'>";
		t += "</div>";
		t += "</div>";
		t +="<div class='col d-flex'></div>";
		t += "</div>";
		$('#centro_costos_container').append(t);
		$('#cent_ctos_'+TOP.count_centro_costos_list).focus();
	}

}

function remove_cctos(){
	if(TOP.count_centro_costos_list > 1){
		$('#fg_cent_ctos_'+TOP.count_centro_costos_list).remove();
		$('#fg_percent_cctos_'+TOP.count_centro_costos_list).remove();
		TOP.count_centro_costos_list --;
		TOP.cctos_id.splice(-1);
		$('#cent_ctos_'+TOP.count_centro_costos_list).focus();

	}
}

function select_cctos_id(id){
	// if(TOP.count_centro_costos_list)
	var n = $('#'+id).val();
	var found = TOP.cctos_id.find(function(t) {
							  return t ==  n;
							});
	if(found){
		remove_cctos();
	}else{
		TOP.cctos_id.push(n);
	}
}

function glbl_fill_select(select_arr){
	var x='';
	for (var i = 0; i < select_arr.length; i++) {
	  	       		var n = select_arr[i];
	  	       		x += "<option value="+n.id+">"+n.lbl+"</option>";
	  	    	}
	  	return x;
	}


//*****************  GENERIC FNKS -> FUNCTIONS


// devuelve la fecha en el formato 31 de enero de 2200
function fec_frmt_1(d){
	let mes = ['err','Enero','Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'];
	return d.substr(0,d.indexOf('/')) + ' de '+ mes[parseInt(d.substr(d.indexOf('/')+1,d.lastIndexOf('/')))]+" de "+ d.substr(d.lastIndexOf('/')+1);
}

// obtiene el value de un pcle label
function get_pcle(parr,lbl){
	if(parr.hasOwnProperty('pcles')){
		var r = parr['pcles'].filter(function(i){return i.label === lbl});
		if(r.length >0){
			return r[0]['value'];
		}else{
			false
		}
	}
}

// obtiene el value de un pcle label 2da version (usada por imprimir pagares)
function get_any_pcle (arr,lbl){
	let res = arr.find(o=>{return o.label == lbl});
	return (res?res.value:'');
}

function ordenar_por_fecha(marr){
	// console.log(marr)
	t = marr.sort(function(a, b) {
	    a = parseInt(a.ord_num);
	    b = parseInt(b.ord_num);
	    return a<b ? -1 : a>b ? 1 : 0;
	});
	return t;
}


// NOT IMPLEMENTED YET
function handlerDelete(){
	if($("#checkDelete").prop('checked')){
		$('#btn_ok').removeClass('btn btn-primary');
		$('#btn_ok').addClass('btn btn-danger');
		$('#btn_ok').html('Eliminar item');
		TOP.deleterec = true;
		return false;
	}
	$('#btn_ok').removeClass('btn btn-danger');
	$('#btn_ok').addClass('btn btn-primary');
	$('#btn_ok').html('Guardar');
	TOP.deleterec = false;
	return false;
}

// {MSG:'EXISTEN MAS DE 2 CUOTAS EN MORA, DEBE CONSULTAR CON EL ADMINISTRADOR GRAL.',TYPE:'DANGER',CONTAINER:'MSGS'}
// O CONTIENE: O.MSG: EL MENSAGE, O.TYPE:  TIPO DE MENSAJE BOOTSTRAP (DANGER SUCCESS WARNING),
// O.CONTAINER :LUGAR DONDE APARECE , O.EXTRA: CONTROLA EL MODAL SI LO HAY
// myAlert({msg:'',type:'warning',container:'modal',extra:'no_autohide'});
function myAlert(o){
	var x = alert.create(o);
  	if(o.container == 'modal'){

		mk_modal(x);
		if(!TOP.curr_ok_act || TOP.curr_ok_act == ''){
			TOP.curr_ok_act = {method:'light_back'};
		}

	}else{
  		$(o.container).html(x.get_screen());
	}
    if(o.extra != 'no_autohide'){
		setTimeout(function(){
			$(o.container).html('');
			if(o.extra == 'hide_modal'){$('#my_modal').modal('hide')};
			if(o.extra == 'refresh'){}
		},3500);
	}
	if(o.hasOwnProperty('after_action')){
		setTimeout(function(){
			$('#main_container').html('');
			$('#my_modal').modal('hide');
			front_call(o.after_action);
		},1500);

	}
	if(o.hasOwnProperty('after_action_noclean')){
		setTimeout(function(){
			front_call(o.after_action_noclean);
		},1500);

	}
}


// NOT IMPLEMENTED  A REHACER
function getCurrentInputsValues(){
	let e1 = Object.getOwnPropertyNames(TOP.validateDefVals);
	let e2={};
	e1.map(function(l){
		if(l.indexOf('dpk_')> -1){
			e2[l] = $('#'+l).find("input").val();
		}else{
			e2[l] = $('#'+l).val();
		}
	});
	return e2;
}


function modal_setup(){
	$('#my_modal_container').removeClass('modal-dialog-centered modal-xl');
	$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
	$('#my_modal_container').removeClass('modal-dialog-centered modal-med');
	$('#my_modal_container').removeClass('modal-dialog-centered modal-sml');
	$('#myModalLabel').html('');
	$('#modal_content').html('');
	$('#modalFooterMsgtxt').html('');
	$('#modalFooterMsg').addClass('hidden');
	$('#print_button').addClass('hidden');
	$('#btn_ok').removeClass('btn btn-danger');
	$('#btn_ok').addClass('btn btn-primary');
	$('#btn_ok').html('Guardar');
	$('#my_modal').modal('hide');

}

function mk_modal(o){
	$('#my_modal_container').addClass((o.winmed?o.winmed:'modal-dialog-centered modal-lg'));
	$('#my_modal_body').html(o.get_screen());
	$('#my_modal_title').html((o.title ? o.title.toUpperCase() : ''));
	$('#print_button').hide();
	$('#delete_button').hide();
	if(o.hasOwnProperty('print_button') && o.print_button){
		$('#print_button').addClass('d-flex');
	}
	$('#close_button').show();
	$('#ok_button').show();
	if(o.hasOwnProperty('hide_ok_button')){
		$('#ok_button').hide();
	}
	if(o.hasOwnProperty('hide_back_button')){
			$('#close_button').hide();
	}
	if(o.hasOwnProperty('delete_button') && o.delete_button == true){
			$('#delete_button').addClass('d-flex');
	}

	$('#my_modal').on('shown.bs.modal', function() {
	    $('input:text:visible:first', this).focus()
	  });
	// $('#my_modal').on('hidden.bs.modal', function (e) {})

	// KEYBOARD FALSE PARA NO CERRAR CON KEYSTROKES
	$('#my_modal').modal({
		    backdrop: 'static',
		    keyboard: false
		});
	// CLEAR ALL ANTES DE CERRAR
	$('#my_modal').on('hidden.bs.modal', function (e) {
		$('#my_modal_title').html('');
		$('#my_modal_body').html('');
		$('#modal-footer-msgs').html('');
		$('#print_button').removeClass('d-flex');
		$('#print_button').addClass('d-none');
		$('#delete_button').removeClass('d-flex');
		$('#delete_button').addClass('d-none');
		$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
		$('#my_modal_container').removeClass('modal-dialog-centered modal-xl');
		$('#my_modal_container').removeClass('modal-dialog-centered modal-lg2');
		$('#my_modal_container').removeClass('modal-dialog-centered modal-med');
		$('#my_modal_container').removeClass('modal-dialog-centered modal-sml');
	})
	$("#my_modal").modal('show');
}



// TESTEA LO QUE DEVUELVE PHP EN VARS
function check(o){
	console.log('check Result', o)
	console.log('top',TOP)
	$('#my_modal').modal('hide');
}
function print_resumen_de_cta(){
	$('#printable_content').html((clpsd_cards.create(TOP.data)).get_print_vers());
	$('#printable_content').printThis();

}
function set_detalle_to_print(t,c){
		//*** CONTAINER
		let r ="<div class='container m-3 p-4'>";
		//*** HEADER
		r += "<div class=\'card bg-light mb-2 \'><div class='card-header d-flex justify-content-between'>";
		r += "<div class='col-9 justify-content-start'><div class='row'><h5> Nombre de Cliente: "+TOP.data.lote.cli_atom_name+"</div>"
		r += "<div class='row'>Numero de Lote: "+TOP.data.lote.lote_nom+"</div>"
		r += "<div class='row'>Plan de Financiación: "+TOP.data.lote.financ+"</div>";
		r += "<div class='row'>Fecha de Inicio del plan:  "+TOP.data.lote.fec_init + "</div>";
		r += t + "</div>";
		r +="<div class='col justify-content-end'><img src=\"/images/logo_LPT1.svg\"></div>"
		r += "</div>"
		//*** TABLA DE CUOTAS
		r += "<div class=\'card-body\'>";
		r += c;
		r += "<hr/>";
		r += "</div></div></div>";
		$('#printable_content').html(r);
}

function btns_row(o){
	let b ='';
	// DOWNLOAD BUTTON
	if(o.hasOwnProperty('download')){
		b += "<div class=\'col d-inline-flex p-1\'>"+o.download+"</div>";
	}
	// PRINT BUTTON
	if(o.hasOwnProperty('print')){
		b += "<div class=\'col d-inline-flex p-1\'>"+o.print+"</div>";
	}
	return "</div><hr/><div class=\"row d-inline-flex \">"+b+	"</div><hr/>"
}

function print_elem(e){
    switch(e){
		case 'recibo':
			let p = rec_pgc.create();
			let t = p.get_print();
			print_window(t);
		break;
		case 'reprint_recibo':
			let rp = recibo_reimprimir.create(TOP.curr_srv_resp);
			let rt = rp.get_print();
			print_window(rt);
		break;
		case 'boleto':
			console.log('data to print',e);
		break;
		case 'print_lcaja':
			print_win2('printable_content');
		break;
	}
}


function print_window(e){
    var mywindow = window.open('', 'PRINT', 'height=850,width=960');
    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"></head><body  onafterprint="self.close()">');
    mywindow.document.write("<div id=\"print_container\">"+e+"</div>");
    mywindow.document.write("<hr><div class=\"row\">\
    	<div class=\"col\"></div><div class=\"col\">\
		<div class=\"btn btn-secondary btn-block\" onClick=window.close()  href=\"#\" role=\"button\">Cancelar</div>\
		</div>\
		<div class=\"col\">\
			<div class=\"btn btn-primary btn-block\" onClick=print_div()  href=\"#\" role=\"button\">Imprimir recibo</div>\
		</div><div class=\"col\"></div>\
	</div>\
	<script>function print_div(){var printContents = document.getElementById(\"print_container\").innerHTML;var originalContents = document.body.innerHTML;document.body.innerHTML = printContents;window.print();document.body.innerHTML = originalContents}</script>");
    mywindow.document.write("<hr><div class=\"row\"></div>");
    mywindow.document.write('</body></html>');
}



function print_win2(e){

    var mywindow = window.open('', 'PRINT');
    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"></head><body  onafterprint="self.close()">');
    mywindow.document.write("<div class=\'printable_content\' id=\"print_container\">"+$('#'+e).html()+"</div>");
    mywindow.document.write("<hr><div class=\"row\">\
    	<div class=\"col\"></div><div class=\"col\">\
		<div class=\"btn btn-secondary btn-block\" onClick=window.close()  href=\"#\" role=\"button\">Cancelar</div>\
		</div>\
		<div class=\"col\">\
			<div class=\"btn btn-primary btn-block\" onClick=print_div()  href=\"#\" role=\"button\">Imprimir</div>\
		</div><div class=\"col\"></div>\
	</div>\
	<script>function print_div(){var printContents = document.getElementById(\"print_container\").innerHTML;var originalContents = document.body.innerHTML;document.body.innerHTML = printContents;window.print();document.body.innerHTML = originalContents}</script>");
    mywindow.document.write("<hr><div class=\"row\"></div>");
    mywindow.document.write('</body></html>');
}

// *************************************************************************
// *** 06/01/2010
// *** obtinene el index del array con mas key intems
// *** usado para obteber los headers de tablas enb repotable
// ************************************************************************
function get_obj_with_all_keys(v){
	let  x = 0 , c = 0;

	for (var i = 0; i < v.length; i++) {
		if (Object.keys(v[i]).length > c ){

			c = Object.keys(v[i]).length;
			x = i;
		}
	}
	// console.log('index OBJECT with all keys',x);
	return x;
}


function route_value_source(x){
	console.log('value source ',x);
	switch(x){
		case 'monto_total':
		return TOP.monto_total_contrato;
		break;
		case 'monto_cta_1':
		return TOP.monto_cta_1;
		break;

		default :
		return $("#"+ x ).val();
		break;
	}
}

function fix_tipo_lbl(lbl){
	if(lbl.indexOf('tipo') > -1){
		var nlbl = 'tipo';
	}
	else if(lbl.indexOf('categoria') > -1){
		var nlbl = 'categoria';
	}
	else{
		var nlbl = lbl;
	}
	// console.log('fixed',nlbl)
	return nlbl;
}

		//**************************************************
		//*** 02/01/20
		//*** COPIES TO CLIPBOARD
		//*** USADO EN RESUMEN DE CUENTA NRO DE PARTIDA
		//*************************************************

function copy_to_clipboard(){
	/* Get the text field */
	  var dtx = document.getElementById("nro_partida");
	  console.log(dtx);
	  /* Select the text field */
	  dtx.select();
	  dtx.setSelectionRange(0, 99999); /*For mobile devices*/

	  /* Copy the text inside the text field */
	  document.execCommand("copy");
	  myAlert({container:'modal',type:'light',tit:'',msg:'El numero de partida se copió al portapapeles' ,extra:'hide_modal'})
}

function set_dataTable_lang(){
	// TRADUCCION DATATABLE
	$.fn.dataTable.moment('DD/MM/YYYY');
	TOP.DataTable_lang = {
		"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "Registros del _START_ al _END_ de _TOTAL_ ",
		"sInfoEmpty":      "Registros del 0 al 0 de 0 ",
		"sInfoFiltered":   "(filtrado de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"sSelect_item": "Filas seleccionadas.",
		"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
		},
		"oAria": {
			"sSortAscending":  ": Ordenar columna Ascendente",
			"sSortDescending": ": Ordenar columna Descendente"
		},
		"buttons": {
                copy: 'Copiar',
                excel: 'Excel',
                print: 'Imprimir',
								colvis:'Columnas'
        },
        select: {
            rows: {
                _: " %d filas seleccionadas",
                0: "Clickea para seleccionar la fila",
                1: " 1 fila seleccionada"
            }
        }
	}
}

function set_autonumeric_def(){
	TOP.autonumeric_def = {
	    // allowDecimalPadding: false,
	    decimalPlaces: 0,
	    decimalPlacesRawValue: 0,
	    emptyInputBehavior: "zero",
	    maximumValue: "1000000000000",
	    minimumValue: "0"
	}
}



// ************************************
// *****  TABLES DE ESTADO DE CUENTA **
// ************************************
function set_estado_tables(){

	$('#tbl_ctas_lote').addClass('tbl_lote');
	$('#tbl_ctas_srv').addClass('tbl_srv');
	$('#tbl_ctas_prest').addClass('tbl_prest');
}


// ************************************
// *****  BARCHART ***************
// ************************************
function mk_barchart1(data,container){
  var margin = {top: 20, right: 20, bottom: 70, left: 90},
  width = 1400 - margin.left - margin.right,
  height = 600 - margin.top - margin.bottom;

  // Parse the date / time
  var parseDate = d3.time.format("%Y-%m").parse;

  var x = d3.scale.ordinal().rangeRoundBands([0, width], .05);

  var y = d3.scale.linear().range([height, 0]);

  var xAxis = d3.svg.axis()
      .scale(x)
      .orient("bottom")
      .tickFormat(d3.time.format("%Y-%m"));

  var yAxis = d3.svg.axis()
      .scale(y)
      .orient("left")
      .ticks(10);


  var svg = d3.select(container).append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");


      data.forEach(function(d) {
        // console.log(d)
        d.date = parseDate(d.date);
        d.value = +d.value;
    });

  x.domain(data.map(function(d) { return d.date; }));
  y.domain([0, d3.max(data, function(d) { return d.value; })]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .selectAll("text")
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", "-.55em")
      .attr("transform", "rotate(-90)" );

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
      // .call(parseInt(yAxis / 1000) + "K")
      // .call(d3.axisLeft(yAxis).ticks(5).tickFormat(function(d) { return parseInt(d / 1000) + "K"; }).tickSizeInner([-width]))
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Value ($)");

  svg.selectAll("bar")
      .data(data)
    .enter().append("rect")
      .style("fill", "steelblue")
      .attr("x", function(d) { return x(d.date); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.value); })
      .attr("height", function(d) { return height - y(d.value); })
}

function mk_barchart(dta,cont){
	const margin = { top: 50, right: 40, bottom: 70, left: 90 };
	const width = $(window).width() - margin.left - margin.right;
	const height = 650 - margin.top - margin.bottom;

	const xScale = d3.scaleBand()
	.range([0, width])
	.round(true)
  .paddingInner(0.1); // space between bars (it's a ratio)

  const yScale = d3.scaleLinear()
  .range([height, 0]);



  const xAxis = d3.axisBottom()
  .scale(xScale);

  const yAxis = d3.axisLeft()
  .scale(yScale)
  .ticks(10);

  const svg = d3.select(cont)
  .append('svg')
  .attr('width', width + margin.left + margin.right)
  .attr('height', height + margin.top + margin.bottom)
  .append('g')
  .attr('transform', `translate(${margin.left}, ${margin.right})`);

  const tooltip = d3.select(cont).append('div')
  .attr('class', 'tooltip')
  .style('opacity', 0);

  xScale
  .domain(dta.map(d => d.date));
  yScale
  .domain([0, d3.max(dta, d => d.value)]);

	// svg.append('g')
	//   .attr('class', 'x axis')
	//   .attr('transform', `translate(0, ${height})`)
	//   .call(xAxis)
	//   .selectAll("text")
	//   .style("text-anchor", "end")
	//   .attr("dx", "-.8em")
	//   .attr("dy", "-.55em")
	//   .attr("transform", "rotate(-90)" );


  svg.append('g')
  .attr('class', 'y axis')
  .call(yAxis)
  .append('text')
  .attr('transform', 'rotate(-90)')
  .attr('y', 6)
  .attr('dy', '.71em')
  .style('text-anchor', 'end')
  .text('Pesos Arg.');

  svg.selectAll('.bar').data(dta)
  .enter()
  .append('rect')
  .attr('class', 'bar')
  .attr('x', d => xScale(d.date))
  .attr('width', xScale.bandwidth())
  .attr('y', d => yScale(d.value))
  .attr('height', d => height - yScale(d.value))
  .on('mouseover', (d) => {
  	tooltip.transition().duration(500).style('opacity', 0.9);
  	tooltip.html(tooltip_data(d))
  	.style('left', `${d3.event.layerX + 5}px`)
  	.style('top', `${(d3.event.layerY - 70)}px`);
  })
  .on('mouseout', () => tooltip.transition().duration(500).style('opacity', 0))
  .on('click',(d)=>detalle_tooltip(d));

}

function tooltip_data(d){
		var mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
		// console.log('ttd',d)
		var dm = mes[parseInt(d.date.substr(d.date.indexOf('-')+1))-1];
		var dyear = d.date.substr(0,d.date.indexOf('-'));
		return '<h6>'+dm+', '+dyear+'</h6><h6>Tot Cobranza $: '+d.value.toLocaleString()+'</h6>';

}

function detalle_tooltip(d){
	var mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	var dm = mes[parseInt(d.date.substr(d.date.indexOf('-')+1))-1];
	var dyear = d.date.substr(0,d.date.indexOf('-'));
	var tit =  '<h6>'+dm+', '+dyear+'  &nbsp;&nbsp;  Cobranza $: '+d.value.toLocaleString()+'</h6>';


	var headings = {
		'name':'Cliente',
		'lote':'Lote',
		'barrio':'Barrio',
		'monto':'Monto A Cobrar',
		'ord_num':'Cuota Nro.'
	};
	var contenido = {
		'table_id':'tbl_repo_cob_fut',
		'container':'panel_table_container',
		'headings':headings,
		'items':TOP.tooltip_data[d.date],
		'caller':'cob_fut',
		extras: {
			'select_id':false
		}
	};

	var x = mk_table_gen2.create(contenido);
	x.title = tit;
	x.hide_ok_button = true;
	x.winmed = 'modal-dialog modal-dialog-centered modal-xl';
	mk_modal(x);
	$('#tbl_repo_cob_fut').DataTable({
		language: TOP.DataTable_lang
	});
	TOP.curr_close_act = { method: 'light_back' };
}


function graf_cfut_full(){

	console.log('mk-graf',TOP.graf_mes_a_mes_gen)
	$('#bc_container').html('');
	TOP.tooltip_data = TOP.tooltip_data_graf_full;
	mk_barchart(TOP.graf_mes_a_mes_gen,'#bc_container')
}

function graf_cfut_fn(){
	$('#bc_container').html('');
	TOP.tooltip_data = TOP.tooltip_data_graf_fn;
	mk_barchart(TOP.graf_mes_a_mes_fn,'#bc_container')
}


// **************************************
// **** funcion numeros a letras  *******
// **************************************
var numeroALetras = (function() {

    function Unidades(num){

        switch(num)
        {
            case 1: return 'UN';
            case 2: return 'DOS';
            case 3: return 'TRES';
            case 4: return 'CUATRO';
            case 5: return 'CINCO';
            case 6: return 'SEIS';
            case 7: return 'SIETE';
            case 8: return 'OCHO';
            case 9: return 'NUEVE';
        }

        return '';
    }//Unidades()

    function Decenas(num){

        let decena = Math.floor(num/10);
        let unidad = num - (decena * 10);

        switch(decena)
        {
            case 1:
                switch(unidad)
                {
                    case 0: return 'DIEZ';
                    case 1: return 'ONCE';
                    case 2: return 'DOCE';
                    case 3: return 'TRECE';
                    case 4: return 'CATORCE';
                    case 5: return 'QUINCE';
                    default: return 'DIECI' + Unidades(unidad);
                }
            case 2:
                switch(unidad)
                {
                    case 0: return 'VEINTE';
                    default: return 'VEINTI' + Unidades(unidad);
                }
            case 3: return DecenasY('TREINTA', unidad);
            case 4: return DecenasY('CUARENTA', unidad);
            case 5: return DecenasY('CINCUENTA', unidad);
            case 6: return DecenasY('SESENTA', unidad);
            case 7: return DecenasY('SETENTA', unidad);
            case 8: return DecenasY('OCHENTA', unidad);
            case 9: return DecenasY('NOVENTA', unidad);
            case 0: return Unidades(unidad);
        }
    }//Unidades()

    function DecenasY(strSin, numUnidades) {
        if (numUnidades > 0)
            return strSin + ' Y ' + Unidades(numUnidades)

        return strSin;
    }//DecenasY()

    function Centenas(num) {
        let centenas = Math.floor(num / 100);
        let decenas = num - (centenas * 100);

        switch(centenas)
        {
            case 1:
                if (decenas > 0)
                    return 'CIENTO ' + Decenas(decenas);
                return 'CIEN';
            case 2: return 'DOSCIENTOS ' + Decenas(decenas);
            case 3: return 'TRESCIENTOS ' + Decenas(decenas);
            case 4: return 'CUATROCIENTOS ' + Decenas(decenas);
            case 5: return 'QUINIENTOS ' + Decenas(decenas);
            case 6: return 'SEISCIENTOS ' + Decenas(decenas);
            case 7: return 'SETECIENTOS ' + Decenas(decenas);
            case 8: return 'OCHOCIENTOS ' + Decenas(decenas);
            case 9: return 'NOVECIENTOS ' + Decenas(decenas);
        }

        return Decenas(decenas);
    }//Centenas()

    function Seccion(num, divisor, strSingular, strPlural) {
        let cientos = Math.floor(num / divisor)
        let resto = num - (cientos * divisor)

        let letras = '';

        if (cientos > 0)
            if (cientos > 1)
                letras = Centenas(cientos) + ' ' + strPlural;
            else
                letras = strSingular;

        if (resto > 0)
            letras += '';

        return letras;
    }//Seccion()

    function Miles(num) {
        let divisor = 1000;
        let cientos = Math.floor(num / divisor)
        let resto = num - (cientos * divisor)

        let strMiles = Seccion(num, divisor, 'UN MIL', 'MIL');
        let strCentenas = Centenas(resto);

        if(strMiles == '')
            return strCentenas;

        return strMiles + ' ' + strCentenas;
    }//Miles()

    function Millones(num) {
        let divisor = 1000000;
        let cientos = Math.floor(num / divisor)
        let resto = num - (cientos * divisor)

        let strMillones = Seccion(num, divisor, 'UN MILLON ', 'MILLONES ');
        let strMiles = Miles(resto);

        if(strMillones == '')
            return strMiles;

        return strMillones + ' ' + strMiles;
    }//Millones()

    return function NumeroALetras(num, currency) {
        currency = currency || {};
        let data = {
            numero: num,
            enteros: Math.floor(num),
            centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
            letrasCentavos: '',
            letrasMonedaPlural: currency.plural || '',//'PESOS', 'Dólares', 'Bolívares', 'etcs'
            letrasMonedaSingular: currency.singular || '', //'PESO', 'Dólar', 'Bolivar', 'etc'
            letrasMonedaCentavoPlural: currency.centPlural || '',
            letrasMonedaCentavoSingular: currency.centSingular || ''
        };

        if (data.centavos > 0) {
            data.letrasCentavos = '' + (function () {
                    if (data.centavos == 1)
                        return Millones(data.centavos) + ' ' + data.letrasMonedaCentavoSingular;
                    else
                        return Millones(data.centavos) + ' ' + data.letrasMonedaCentavoPlural;
                })();
        };

        if(data.enteros == 0)
            return 'CERO ' + data.letrasMonedaPlural + ' ' + data.letrasCentavos;
        if (data.enteros == 1)
            return Millones(data.enteros) + ' ' + data.letrasMonedaSingular + ' ' + data.letrasCentavos;
        else
            return Millones(data.enteros) + ' ' + data.letrasMonedaPlural + ' ' + data.letrasCentavos;
    };

})();
// **************************************
// **** END funcion numeros a letras  ***
// **************************************
