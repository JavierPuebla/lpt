
// *** GENERIC TABLE 1 ***********
const gntbl_1 = {
	_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		this._scrn = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
		this._scrn += "<div class=\"col\"><legend>"+v.title+"</legend></div>";
		this._scrn += "</div>"; 
		this._scrn += "<table class=\"table table-hover\" id=\'"+v.tbl_id+"\'>";
		this._scrn +="<thead><tr>";
		const h = v.headings;
		

		for(var k in h){
			this._scrn +="<th>"+h[k]+"</th>";	  
		} 
		this._scrn +="</thead></tr>";
		// BODY AND ROWS ************
		this._scrn +="<tbody>";
		let i = v.items;
		let rows = '';
		for(let r in i ){
			let cols = '';
			for (let x in h){
				// ************* DEFAULT VALUE 
				let col_value  = i[r][x];
				// ***** VALUE DE BOTON ACCIONES
				if(x == 'id' && h[x] == 'Acciones'){
					let colv = ''; 
					for(let c in v.acciones){
					// console.log('acciones',v.acciones[c]);
						colv += "<span class=\"p-1\"><button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'"+v.acciones[c].method+"',sending:"+v.acciones[c].sending+",data:{id:"+i[r][x]+"}})><i class=\"icon ion-"+v.acciones[c].icon+"\"></i></button></span>";
					}
					col_value = colv;
				}
				cols +="<td>"+col_value+"</td>";
			}
			
			rows +="<tr>"+cols+"</tr>";		
		}  
		this._scrn += rows + "</tbody></table></div>";
	}
};

// DETALLE DE CUOTAS 
var table_detalle_ctas ={
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		
		obj.set(val);
		return obj; 	
	},
	set: function(val){
		this._data = val;
		if(this._data.hasOwnProperty('ctas')){
			// SI EL TIT DICE RESTANTE O EN MORA AGREGO LA CUENTA DE LIQUIDACION EN UN PAGO  
			if (val.title.match(/Restantes/)|| val.title.match(/Mora/)){
				this._data.temp_tmonto_cta = 0;
				for (var i = 0;  i < this._data['ctas'].length; i++){
					for (var x = 0; x < this._data['ctas'][i].pcles.length; x++) {
						if (this._data['ctas'][i].pcles[x].label == 'nro_recibo' || this._data['ctas'][i].pcles[x].label == 'fecha_pago' ){
							this._data['ctas'][i].pcles[x].vis_elem_type = -1;
						}
						if(this._data['ctas'][i].pcles[x].label == 'monto_cta'){
							if(i == 0 ){
								this._data.temp_tmonto_cta_act =  this._data['ctas'][i].pcles[x].value
							}
							this._data.temp_tmonto_cta += parseInt(this._data['ctas'][i].pcles[x].value);
						} 
					}
				}
				this._data.temp_liquidac_1pago = parseInt(this._data.temp_tmonto_cta_act)*i;	
				this._data.title = "&nbsp;&nbsp;"+this._data.title + "&nbsp;&nbsp;Total:&nbsp;&nbsp;" + this._data.temp_tmonto_cta.toLocaleString() +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total en 1 Pago : " + this._data.temp_liquidac_1pago.toLocaleString() 
			}
			this.title = this._data.title
			// this._data.title += "<div class='col-lg-4 align-items-right'>Total: "+this._data.temp_tmonto_cta+"</div>"
			
			
			// hides nro. de cuota
			// for (var i = 0;  i < this._data['ctas'].length; i++){
			// 		for (var x = 0; x < this._data['ctas'][i].pcles.length; x++) {
			// 			if (this._data['ctas'][i].pcles[x].label == 'nro_cta' ){;
			// 				// // console.log('closing',this._data[i].pcles[i])
			// 				this._data['ctas'][i].pcles[x].vis_elem_type = -1;
			// 			} 
			// 		}
			// 	}
			this._screen = "<table class=\"table table-hover\" id=\"tbl_det_ctas\">";
			// make table hedings *****
			// console.log('det cuotas',this._data);
			this._screen +="<thead><tr>";
			var t = this._data['ctas'][this._data['ctas'].length -1].pcles.map(function(i){if(i.vis_elem_type > -1){return "<th class=\"text-center\" scope=\'col\'>"+i.title+"</th>"}});
			this._screen += t.join('');
			this._screen +="</thead></tr><tbody>";
			
			//  get labels 
			const h = this._data['ctas'][this._data['ctas'].length -1].pcles.filter(i => i.vis_elem_type > -1);
			
			// make table rows
			var tr = '<tr>';
			const tblrows = this._data['ctas'].map(function(x){
				const r = h.map(function(hl){ 
					return x.pcles.find(function(pf){return pf.label == hl.label});
				});

				r.map(function(i){
					if(typeof i !== 'undefined' && i.hasOwnProperty('value')){
						const v = (i.label.indexOf('monto') > -1 ? parseInt(i.value).toLocaleString():i.value);
						tr += "<td class=\'text-center\'>"+v+"</td>";
							// console.log('tr',tr);		
						}
					})
				tr +="</tr>";
					// return r.map(function(x){if(x.hasOwnProperty('value')){return x['value']}});
					
				});
			
			 // console.log('rows',tr);		
					// h.map(function(p){x.pcles.find(function(f){return f.label == p.label;})});


			// for (var i = 0;  i < this._data['ctas'].length; i++) {
			// 	// var temp = this._data['ctas'][i].pcles.map((x)=>{ 
			// 	// 	if(x.vis_elem_type > -1){
			// 	// 		return "<td>"+this.fix_value_type.call(this,x)+"</td>";
			// 	// 	} 
			// 	// });
			// 	const temp = this._data['ctas'][i].pcles.map((x)=>{ 
			// 		const pcls = lbl.map((p)=>{
			// 			if(x.hasOwnProperty(p.label)){
			// 				return "<td>"+this.fix_value_type.call(this,x)+"</td>";
			// 			}	
			// 		});

			// 	});

			// 	this._screen += temp.join('');
			// 	console.log('temp',temp.join(''));
			// 	this._screen += "</tr>";
			// }
			this._screen += tr+"</tbody></table>";
		};
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	fix_value_type:function(v){
		if(v.hasOwnProperty('label')){
			if(v.label.indexOf('monto') > -1){
				return parseFloat(v.value).toLocaleString();
			}else if(v.label.indexOf('fec')> -1){
				return v.value	
				// return fx_date_to_dmy(v.value);
			}else {
				return v.value;
			}
		}else{
			return 'no_data';
		}
	},
}
// ************************

// TABLE cobranza futura
var table_reports ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
    // TOP.selected_ids = [];
	// TOP.current_selection_table = val;
	this._data = val;
	

	var h = val.headings;
	this._screen = "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	this._screen +="<thead><tr class=\"d-flex\">";

	this._screen +="<th scope=\'col\' class=\"col-3 align-middle text-center\">"+h.cli+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.pagado+"</th>";
	
	for (var i = 0; i < val.items.maxd; i++) {
		this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h['apg_'+i]+"</th>";	
	}
	this._screen +="</thead></tr><tbody>";
	if(val.items.hasOwnProperty('data')){
		for (var it = 0 ; it < val.items.data.length ; it ++){
			val.items.data[it].a_pagar.events.for
			this._screen +="<tr class=\"d-flex \" >\
			<td scope=\'col\'class=\"col-3 align-middle text-center\">"+val.items.data[it].cli+"</td>\
			<td scope=\'col\'class=\"col-1 align-middle text-center\">"+parseInt(val.items.data[it].pagado_cli).toLocaleString()+"</td>"
			val.items.data[it].a_pagar.events.forEach(e => this._screen +="<td scope=\'col\'class=\"col-1 align-middle text-center\">"+parseInt(e.monto).toLocaleString()+"</td>");
			this._screen +="</tr>";	

		}
	}
	this._screen += "</tr>";  
	this._screen +="</tbody></table></div>";
	this._screen +="<div class\"row d-flex\"><div class=\"col-4 d-flex\">Total Pagado a la fecha: "+parseInt(val.items.totgen).toLocaleString()+" </div>"

	},
	get_screen:function(){return this._screen},
}

// TABLE listado clientes cuota a pagar
var table_audit ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
    // TOP.selected_ids = [];
	// TOP.current_selection_table = val;
	this._data = val;
	
	// o.data.forEach( a => // console.log('cnt',a.cli))
	// o.data.forEach( a => // console.log('cnt',a.fst.pcles.monto))
	// val.items.data.forEach(l => // console.log(l.cli));	
	

	var h = val.headings;
	this._screen = "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	this._screen +="<thead><tr class=\"d-flex\">";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.num+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-3 align-middle text-center\">"+h.cli+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.fstf_vto+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.fst_monto+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-2 align-middle text-center\">"+h.fst_nro_cta+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.lst_vto+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.lst_monto+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-2 align-middle text-center\">"+h.lst_nro_cta+"</th>";
	

	this._screen +="</thead></tr><tbody>";
	

	if(val.items.hasOwnProperty('data')){
		
		for (var key in val.items.data) {
			// // console.log('da',val.items.data[key])
			var l = val.items.data[key];
			if (l.hasOwnProperty('tot_pagado')) {
				
				this._screen +="<tr class=\"d-flex \" >\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+l.num+"</td>\
				<td scope=\'col\'class=\"col-3 align-middle text-center\">"+(l.cli!=undefined?l.cli.substring(0,25):'-')+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+(l.fst[0]!=undefined?fx_date_to_dmy(l.fst[0].fecha):'-')+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+l.tot_pagado+"</td>\
				<td scope=\'col\'class=\"col-2 align-middle text-center\">Cancelado</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">-</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">-</td>\
				<td scope=\'col\'class=\"col-2 align-middle text-center\">-</td>\
				</tr>";
			}	
			else{
				this._screen +="<tr class=\"d-flex \" >\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+l.num+"</td>\
				<td scope=\'col\'class=\"col-3 align-middle text-center\">"+l.cli.substring(0,25)+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+(l.fst.hasOwnProperty('pcles')?l.fst.pcles.fec_vto.value:'-')+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+parseInt(l.fst.pcles.monto.value)+"</td>\
				<td scope=\'col\'class=\"col-2 align-middle text-center\">"+l.fst.pcles.nro_cta.value+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+(l.lst.hasOwnProperty('pcles')?l.lst.pcles.fec_vto.value:'-')+"</td>\
				<td scope=\'col\'class=\"col-1 align-middle text-center\">"+(l.lst.hasOwnProperty('pcles')?parseInt(l.lst.pcles.monto.value):'-')+"</td>\
				<td scope=\'col\'class=\"col-2 align-middle text-center\">"+(l.lst.hasOwnProperty('pcles')?l.lst.pcles.nro_cta.value:'-')+"</td>\
				</tr>";
			}
		}
	}
	this._screen += "</tr>";  
	this._screen +="</tbody></table></div>";
	this._screen += this.get_pagination();
	},
	get_screen:function(){return this._screen},
	get_pagination(){
		var cp = parseInt(this._data.items.page)
		var scr = "<div class=\"row d-flex justify-content-around align-items-center \"><div><ul class=\"pagination\">";
		for (var i = 1; i <= parseInt(this._data.items.paginas); i++) {
			scr += "<li class=\"page-item "+(i==cp?"active":'')+"\"><a class=\"page-link\" href=\"#\" onClick=front_call({method:'list_audit',page:"+i+",sending:true})>"+i+"</a></li>"
		}

		scr +="</ul></div></div>";
		return scr;
	}
};

// *****************  ATOMS TABLE ***********
var table_atoms ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
	  // // console.log('table_atom data',this._data);

	  this._screen = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
	  this._screen += this.get_newbot();
	  this._screen += "<div class=\"col\"><legend>Modificar Items del tipo "+val.type+"</legend></div>";
	  this._screen += "</div>"; 
	  this._screen += "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	  this._screen +="<thead><tr class=\"d-flex\">";
	  var h = Object.values(val.headings); 	
	//  // console.log('headins',); 
	//  HEADINGS ***********************
	for(var k in h){
		if(h[k] != 'Acciones'){
			this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">"+h[k]+"</th>";	  
		}
	} 
	this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">Acciones</th>";
	this._screen +="</thead></tr><tbody>";
	// BODY AND ROWS ************
	this._screen +="<tbody>";
	if(val.items.hasOwnProperty('rows')){
		var rows = val.items.rows;
		TOP.contab={};
		TOP.contab.rows = rows;
		for(var r in rows ){
			this._screen +="<tr class=\"d-flex \" >";
			if(rows[r].label != 'id'){
				this._screen +="<td scope=\'col\'class=\"col align-middle \">"+rows[r].name+"</td>";
			}
			this._screen += "<td scope=\'col\'class=\"col-2 align-middle text-center\">"+this.get_actions(rows[r].id)+"</td>";
			this._screen +=	"</tr>";
		}  
	}
	
	this._screen +="</tbody></table></div>";
	this._screen += this.get_pagination();
	},
	get_screen:function(){return this._screen},
	get_actions(id){
		var act="<div class=\"row d-flex justify-content-around align-items-center \">";
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_atom',data:{id:"+id+"},sending:true})><i class='icon align-bottom ion-md-open'></i></button></div>"
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'delete_atom',data:{id:"+id+",sending:'false'}})><i class='icon align-bottom ion-md-delete'></i></button></div>"
		act +="</div>";
		return act;
	},
	get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_atom',type:'"+this._data.type+"','sending':true})> Agregar Nuevo Item </button>\
		</div>";
		return act;
	},
	get_pagination(){
		var cp = parseInt(this._data.items.current_page)
		var scr = "<div class=\"row d-flex justify-content-around align-items-center \"><div><ul class=\"pagination\">";
		for (var i = 1; i <= parseInt(this._data.items.tot_pages); i++) {
			scr += "<li class=\"page-item "+(i==cp?"active":'')+"\"><a class=\"page-link\" href=\"#\" onClick=front_call({method:'refresh_atom',id:'"+this._data.items.id+"',page:"+i+",sending:true})>"+i+"</a></li>"
		}
		scr +="</ul></div></div>";
		return scr;
	}
};

var table_contab = {
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
	  // // console.log('data',this._data);
	  // o.data.forEach( a => // console.log('cnt',a.cli))
	  // o.data.forEach( a => // console.log('cnt',a.fst.pcles.monto))
	  // val.items.data.forEach(l => // console.log(l.cli));	

	  this._screen = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
	  this._screen += this.get_newbot();
	  this._screen += "<div class=\"col\"><legend>Cuentas contables</legend></div>";
	  this._screen += "</div>"; 
	  this._screen += "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	  this._screen +="<thead><tr class=\"d-flex\">";
	  var h = Object.values(val.headings); 	
	//  // console.log('headins',); 
	for(var k in h){
		// // console.log(k)
		if(h[k] != 'Id'){
			this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">"+h[k]+"</th>";	  
		}
	} 
	this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">Acciones</th>";
	this._screen +="</thead></tr><tbody>";

	if(val.items.hasOwnProperty('rows')){
		var rows = val.items.rows;
		TOP.contab={};
		TOP.contab.rows = rows;
		for(var r in rows ){
			this._screen +="<tr class=\"d-flex \" >";
			for (var line in rows[r]){
				if(rows[r][line].label != 'id'){
					this._screen +="<td scope=\'col\'class=\"col align-middle \">"+rows[r][line].value+"</td>";
				}
			}
			var acc_id = rows[r].find(function(i){return i.label == 'id'})
			this._screen += "<td scope=\'col\'class=\"col-2 align-middle text-center\">"+this.get_actions(acc_id.value)+"</td>";
			this._screen +=	"</tr>";
		}  
	}
	
	this._screen +="</tbody></table></div>";
	// this._screen += this.get_pagination();
	},
	get_screen:function(){return this._screen},
	get_actions(id){
		var act="<div class=\"row d-flex justify-content-around align-items-center \">";
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_contab',data:"+id+"})><i class='icon align-bottom ion-md-open'></i></button></div>"
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'delete_contab',data:"+id+"})><i class='icon align-bottom ion-md-delete'></i></button></div>"
		act +="</div>";
		return act;
	},
	get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_contab','sending':false})> Agregar Nuevo Item </button>\
		</div>";
		return act;
	},
	
}

// TABLE PLANILLA DE CAJAS
var table_plc ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		TOP.selected_ids = [];
		TOP.current_selection_table = val;
		this._data = val;
		if(val.items.length > 0 ){
			this._screen = "<table class=\"table table-hover\" id=\'"+val.table_id+"\'>";
			this._screen +="<thead><tr>";
			var t='';
			for(var key in val.headings) {
				t += "<th>"+val.headings[key]+"</th>";      		
			}
			this._screen += t;
			this._screen +="</thead></tr><tbody>";
	    // make table rows
	    var t2 = val.items.map(function(x,i){
	        // // console.log('listing',x )
	        var d ='';
	        for(var key in val.headings) {
	        	if(x.hasOwnProperty(key)) {
	        		var it = '';
	        		if(key == "monto"){
	        			it = parseFloat(x[key]).toLocaleString();
	        		}else if(x[key] != '' && x[key] != undefined && x[key] != null){
	        			it = x[key];
	        		}
	        		if(key.indexOf('date') > -1 || key.indexOf('fecha') > -1 || key.indexOf('fec') > -1){
	        			it = (x[key] != '' ? fx_date_to_dmy(x[key]):'')
	        		}
	        		if(key == 'id'){
	        			it = "<button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_op',sending:true,data:{op_id:"+x[key]+"}})><i class='icon align-bottom ion-md-open'></i></button>"
	        		}
	        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
	        			TOP.selected_ids.push(x[key]);
	        			it = "<div class=\"custom-control custom-checkbox\">\
	        			<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
	        			<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
	        		}
	        		if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
	        			if(val.extras.editables.find(function(e){return e == key})){
	        				let p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x['events_id']}
	        				it = editable.create(p);
	        			};
	        		}
	        		d += "<td>"+it+"</td>";	
	        	}
	        }
	        return "<tr class=\""+val.row_indicator+" \" >"+d+"</tr>";
	    });
	    this._screen += t2.join('');
	    this._screen += "</tr>";  
	    this._screen +="</tbody></table></div>";
	}else{
		this._screen = "Sin datos";
	}

	},
	get_screen:function(){return this._screen},
	}

	// *** REVISION TABLE ***********
	const table_revision = {
		_data:{},
		_screen:'',
		_height:500,
		get:function(val){
			return this._data[val];
		},
		create:function(val){
			var obj = Object.create(this);
			obj.set(val);
			return obj;
		},
		set: function(val){
			this._data = val;
			this._screen = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
			this._screen += this.get_newbot();
			this._screen += "<div class=\"col\"><legend>Lotes Reportados </legend></div>";
			this._screen += "</div>"; 
			this._screen += "<table class=\"table table-hover\" id=\'"+val.tbl_id+"\'>";
			this._screen +="<thead><tr>";

		//  // console.log('headins',); 
		//  HEADINGS ***********************
		var h = val.headings;
		for(var k in h){
			this._screen +="<th>"+h[k]+"</th>";	  
		} 
		this._screen +="</thead></tr>";
		// BODY AND ROWS ************
		this._screen +="<tbody>";
		var i = val.items;
		var rows = '';
		for(var r in i ){
			
			var cols = '';
			for (var x in h ){
				// console.log('item',r)
				// console.log(x);
				// ************* DEFAULT VALUE 
				var col_value  = i[r][x];
				// *** VALUE SI ES CAMPO EDITABLE DE ASIGNADO_A
				// if(x == 'asignado_a2'){
				// 	const p = {'value':i[r][x],'label':x,'method':val.extras.edit_call,'id':i[r]['rev_id']};
				// 	col_value = select_obj_by_name.create(p).get_screen();
				// }
				if(x =='estado'){
					col_value = this.get_estado(i[r][x],i[r]['rev_id']);	
				}

				// if(x == 'estado' && col_value == 'pendiente'){state = 'class=\"table-warning\"';}else{state = '';}

				cols +="<td>"+col_value+"</td>";
			}
			
			rows +="<tr>"+cols+"</tr>";		
		}  
		this._screen += rows + "</tbody></table></div>";
	},
	get_screen:function(){return this._screen},
	get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_revision','sending':false})> Nuevo Mensage </button>\
		</div>";
		return act;
	},
	get_estado : function(v,id){
		var type = '';
		var x = '';
		switch(v){
			case 'resuelto':
			type = 'success';
			x = 'Resuelto';
			break;
			case 'pendiente':
			type = 'warning';
			x = 'Pendiente';
			break;
			
		}
		// *** SELECTOR DE ESTADO
		var r = "\
		<div class=\"btn-group dropleft p-1\" role=\"group\" aria-label=\"Button group with nested dropdown\">\
		<button type=\"button\" id=\"btn_estado_"+id+"\" class=\"btn btn-sm btn-"+type+"\">"+x+"</button>\
		<div class=\"btn-group dropleft show\" role=\"group\">\
		<button id=\"btnGroupDrop_"+id+"\" type=\"button\" class=\"btn btn-sm btn-"+type+" dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\"></button>\
		<div class=\"dropdown-menu \" aria-labelledby=\"btnGroupDrop1\" x-placement=\"bottom-end\" style=\"position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform; z-index:10000;\">\
		<a class=\"dropdown-item\" onClick=front_call({'method':'revision_set_estado',sending:true,'state':'1','id':"+id+"})>Resuelto</a>\
		<a class=\"dropdown-item\" onClick=front_call({'method':'revision_set_estado',sending:true,'state':'0','id':"+id+"})>Pendiente</a>\
		</div>\
		</div>\
		</div>";
		
		return r;
	},
};


// RECIBE OBJ HEADINGS Y ARR ITEMS CON OBJS DEL MISMO LABEL 
var mk_simple_table ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		this._screen = "<table class=\"table table-hover "+val.size+"\">";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    // make table rows
    // // console.log('listing', val)
    var t2 = val.items.map(function(x){
        // // console.log('listing',x )
        var d ='';
        for(var key in val.headings) {

        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		if(!isNaN(parseFloat(x[key]))){
        			it = parseFloat(x[key]);
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fecha') > -1 || key.indexOf('fec') > -1){
        			it = (x[key] != '' ? fx_date_to_dmy(x[key]):'')
        		}
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES 
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					//// console.log('making select',x[key])
					// // console.log('Ts',TOP.selected)
					
					// if(TOP.selected.find(function(i){return i.events_id == x[key]}) != undefined){
						// // console.log(TOP.selected.find(function(i){return i.events_id == x[key]}.selected))
						// console.log('en table',TOP.selected)
						var ch = TOP.selected.find(function(i){return i.events_id == x[key]});
						// console.log('checking',x[key])
						// console.log('found ',ch)
					// }else{
					//	var ch = {'selected':false};
					// }
					it = "<div class=\"custom-control custom-checkbox\">\
					<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected() "+(ch!=undefined?"checked":"")+">\
					<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
				}
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x['events_id']}
						it = editable.create(p);
					};
				}
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr "+(x.termino!="EN_MORA" ? "class=\"table-success\"" : "class=\"table-warning\"")+">"+d+"</tr>";
	});
    this._screen += t2.join('');
    this._screen += "</tr>";  
    this._screen +="</tbody></table></div>";
	},
	get_screen:function(){return this._screen},
}


// NO ESTOY USANDO | RECORRE UN ARRAY HEADINGS Y LUEGO UN ARRAY CON FILAS Y COLUMNAS
var mk_pcles_table ={
	_data:{},
	_screen:'',
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		TOP.selected_ids = [];
		TOP.current_selection_table = val;
		this._data = val;
		this._screen = "<table class=\"table table-hover\">";
    // make table hedings *****
    this._screen +="<thead><tr>";
    var t='';
    for(var key in val.headings) {
    	if(val.headings.hasOwnProperty(key)) {
    		t += "<th scope=\'col\'>"+val.headings[key]+"</th>";
    	}
    }
    this._screen += t;
    this._screen +="</thead></tr><tbody>";
    // make table rows
    
    for(var line in val.items){
    	var d ='';
    	for(var col in val.items[line]){
    		var it = val.items[line][col].value;
    		var isdate = it.match(/\d+\-\d+\-\d+/);
    		if(isdate != null){
    			it = fx_date_to_dmy(it);
    		}else 
    		if(!isNaN(parseFloat(it))){
    			it = parseFloat(it).toLocaleString();
    		}


    		d += "<td class=\"align-middle\">"+it+"</td>";
    	}
    	this._screen += "<tr>"+d+"</tr>";
    }
    this._screen += "</tr>";  
    this._screen +="</tbody></table>";    

 //    var t2 = val.items.map(function(l){
 //        var d ='';
 //        var line = l.map(function(x){
 //        	// console.log('line',x.label,x.value )
 //            // for(var xk in val.headings) {
	// 	    	// // console.log('x',x[xk])
	// 	    	// if(x.hasOwnProperty(key)) {
	// 	        	var it = '';
	// 	        	if(!isNaN(parseFloat(x.value))){
	// 		          it = parseFloat(x.value).toLocaleString();
	// 		        }else if(x.value != '' || x.value != undefined){
	// 		        	it = x.value;
	// 		        }
	// 		        if(x.value.indexOf('date') > -1 || x.value.indexOf('fecha') > -1){

	// 		        	it = (it.length > 1 ? fx_date_to_dmy(x.value):'')
	// 		        }
	// 		        if(x.value == 'events_id' && val.extras.hasOwnProperty('select_id')){
	// 		        	TOP.selected_ids.push(x.value);
	// 		        	it = "<div class=\"custom-control custom-checkbox\">\
	// 							  <input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x.value+"\" value="+x.value+" onChange=update_selected("+x.value+",'"+val.extras.caller+"') checked=\"\">\
	//   							<label class=\"custom-control-label\" for=\"select_id_check_"+x.value+"\"></label></div>";
	// 		        }
	// 		      	d += "<td class=\"align-middle\">"+it+"</td>";
	// 			// }
	// 		// }
	// 		return d;
	// 	});
	// 	return "<tr>"+line+"</tr>";
	// });

 //      this._screen += t2.join('');
 //      this._screen += "</tr>";  
 //      this._screen +="</tbody></table>";
	},
	get_screen:function(){return this._screen},
	}

	// RECIBE UN OBJETO CON HEADING ARRAY / CONTENT ARRAY /
	// HACE UN TABLE EDITABLE  
	var mk_editable_table ={
		_data:{},
		_screen:'',
		create:function(val){
			var obj = Object.create(this);
			obj.set(val);
			return obj.get_screen();
		},
		set: function(val){
			this._data = val;
	    // if(this._data.length >0){
	    	this._screen = "<table class=\"table table-hover\">";
	      // make table hedings *****
	      this._screen +="<thead><tr>";
	      var t = this._data.headings.map(function(i){return "<th scope=\'col\'>"+i+"</th>"});
	      this._screen += t.join('');
	      this._screen +="</thead></tr><tbody>";
	      // make table rows
	      var t2 = this._data.items.map(function(x){
	      	x.type = 'text';
	      	var isdate = x.value.match(/\d+\/\d+\/\d+/);
	      	if(isdate != null){
	      		x.value = fx_date_to_dmy(x.value);
	      	}else if(!isNaN(x.value)){
	      		x.value = parseFloat(x.value).toLocaleString();
	      		x.type = 'number'; 
	      	}
	      	return "<tr><td>"+x.label+"</td><td id=\""+x.id+"\">"+editable.create(x)+"</td></tr>"
	      });
	      
	      this._screen += t2.join('');
	      this._screen += "</tr>";  
	      this._screen +="</tbody></table>";
	    // }
	},
	get_screen:function(){return this._screen},
}

var mk_table_gen1={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		console.log('items',TOP)
		console.log('val',val)
		// ************  HEADINGS   *************
		this._screen = "<table class=\"table table-hover\">";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    //     ************* ROWS ***************
    // <button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'kill_event',sending:true,data:{ev_id:"+x['events_id']+",elm_id:"+x['elements_id']+"}})><i class=\"icon align-bottom ion-md-delete\"></i></button>
   // // console.log('heading',val.headings)
   
   	var t2 = val.items.map(function(x){
   	var d ='';
        // console.log('h2',val.headings)
        for(var key in val.headings) {
        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		if(!isNaN(parseFloat(x[key]))){
        			it = parseFloat(x[key]);
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fec') > -1){
        			
        			it = (x[key] != '' ? x[key]:'-')
        		}
        		// *********** fix de editar contrato ******
        		if(key == 'event_type'){
        			var types = {'4':"Vencida","6":"Adelantada","8":"A Vencer"}
        			it = types[x[key]];
        		}
        		// *********** id para kill event  *************
        		// if(key == 'event_id'){
        		// 	it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'kill_event',sending:true,data:{ev_id:"+x[key]+",elm_id:"+ TOP.last_call_param.id +"}})><i class=\"icon-small align-bottom ion-md-flash\"></i></button><span>&nbsp;"+x[key]+"</span>"
        		// }

        		//************ ES UN SELECT CHECK BOX *****************
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES 
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<div class=\"custom-control custom-checkbox\">\
					<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
					<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
				}
				// ****************  ES UN CAMPO EDITABLE  ***************************
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var pcleid = key +'_pcle_id';
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x[pcleid],'parent_id':x['event_id']}
						it = editable.create(p);
					};
				}
				// **************** ES UN ID PARA ACTIVAR DETALLE  ********************
				// // console.log('edit1',key)
				// // console.log('edit2',val.headings[key])
				if(key == 'detalle_id'){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES 
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'detalle_recibo',sending:true,data:{rec_id:"+x[key]+"}})><i class=\"icon align-bottom ion-md-open\"></i></button>";
				}

				//*************  IMPRIMO EL TD  ******************************
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr>"+d+"</tr>";
	});
   	this._screen += t2.join('');
   	this._screen += "</tr>";  
   	this._screen +="</tbody></table></div>";
	},
	get_screen:function(){return this._screen},	
}




const table_detalle_movs = {
	create:function(v,id){
		return "<div class=\"row p-1\"><div class=\"col d-flex justify-content-between\"></div>\
		</div>\
		<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-center'>\
		<h5>Detalle Movimientos</h5>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\'>\
		<div class=\'col d-flex p-1 justify-content-center\' id=\"container_table_last_movs\">"+otbl.create(v,id)+"</div>\
		</div>\
		</div>"
	}
}

const new_modal = {
	create:function(o){
		$('#my_modal_container').addClass('modal-dialog-centered modal-'+o.wm);
		if(o.title != ''){
			$('#modal_header').addClass('d-flex')
			$('#my_modal_title').html(o.title)	
		}else{
			$('#modal_header').addClass('d-none')
		}
		$('#my_modal_body').html(o.content);
		
		if(o.okbutt){
			$('#ok_button').show();	
		}else{
			$('#ok_button').hide();
		}
		
		$('#close_button').show();
		
		$('#my_modal').on('shown.bs.modal', function() {
		    $('input:text:visible:first', this).focus()
		});
		
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
			$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-xl');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-lg2');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-med');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-sml');
		})
		$("#my_modal").modal('show');
	}
}

var mk_table_gen2={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		// ************  HEADINGS   *************
		this._screen = "<table class=\"table table-hover\" id=\'"+val.table_id+"\'>";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    //     ************* ROWS ***************
   // // console.log('heading',val.headings)
   // // console.log('items',val.items)
   var t2 = val.items.map(function(x){
   	var d ='';
        // // console.log('h2',val.headings)
        for(var key in val.headings) {
        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		var reg = /^\d+$/;

        		if(reg.exec(x[key])){
        			it = parseFloat(x[key]).toLocaleString();
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fec') > -1){
        			
        			it = (x[key] != '' ? x[key]:'-')
        		}
        		
        		//************ ES UN SELECT CHECK BOX *****************
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES 
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<div class=\"custom-control custom-checkbox\">\
					<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
					<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
				}
				// ****************  ES UN CAMPO EDITABLE  ***************************
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var pcleid = key +'_pcle_id';
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x[pcleid],'parent_id':x['event_id']}
						it = editable.create(p);
					};
				}
				// **************** ES UN ID PARA ACTIVAR acciones  ********************
				// // console.log('edit1',key)
				// // console.log('edit2',val.headings[key])
				if(key == 'elem_id'){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES 
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'get_elements',sending:true,kprevwin:true,caller:'"+val.caller+"',data:{elm_id:"+x[key]+"}})><i class=\"icon align-bottom ion-md-open\"></i></button>";
				}
				//*************  IMPRIMO EL TD  ******************************
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr>"+d+"</tr>";
	});
   this._screen += t2.join('');
   this._screen += "</tr>";  
   this._screen +="</tbody></table></div>";
	},
	get_screen:function(){return this._screen},	
}


// RECIBE UN PCLE Y LO VUELVE EDITABLE  (ON CHANGE LLAMA A SU CALLER PIDIENDO UPDATE)
var editable = {
	_data:{},
	_screen:{},
	create:function (val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._screen;  
	},
	set: function(v){
		this._data = v; 
		var parent_id = (v.hasOwnProperty('parent_id')?v.parent_id:0);
		v.type = 'text';
    // var isdate = v.value.match(/\d+\/\d+\/\d+/);
    // tengo que revisar el modo date
    // if(isdate != null){
    	// v.value = fx_date_to_dmy(v.value);
        // v.type = 'date';
    // } 
    if(!isNaN(v.value)){
    	v.value = parseFloat(v.value);
    	v.type = 'number'; 
    }
    var r = "\<div class=\"form-group form-inline \">";
    if (v.hasOwnProperty('title') && v.title != ''){
    	r += "<label class=\"col-form-label\" for=\"edi_"+v.id+"\">"+v.title+"</label>";
    }
    r+= "<input type=\""+v.type+"\" class=\"form-control\" id=\"edi_"+v.id+"\" ";
    r+= "value=\""+v.value+"\"  ";
    r+= (v.method == 'update_edi' && v.value == 0  ? "disabled=\"\"":"")
    r+= (v.method == 'update_edi'? "onChange=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_edi'? "onblur=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_rev_asignado'?"style=\'width: 7em;\'":''); 
    r+= "onChange=front_call({method:\""+v.method+"\",data:{id:\""+v.id+"\",label:\""+v.label+"\",elem_id:\'"+TOP.curr_elem_id+"\',val:this.value,parent_id:"+parent_id+"}})";
    r+= (v.type == 'number'?" min=0 max=999999 style=\"width: 9em;\"":'');
    r+= ">";
     // r+= (v.label.match(/_usd/))?"<div class=\"input-group-append\"><span class=\"input-group-text\">u$d</span></div>":"";
     r+= "</div>";
     this._screen = r;
 },
};



// VENTANA DE INPUTS EN JUMBOTRON CENTRADO CON TITULO Y BOT FINAL DE CALL TO ACTION 
var jb = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "<div class=\"row\" id=\"\">\
		<div class=\"col-sm-1\"></div>\
		<div class=\"col-sm-10\">\
		<div class=\"jumbotron jumbotron\">\
		<p class=\"lead\">"+v.title+"</p>\
		<div class=\"row\">"+v.content+"</div>\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2\"><div class=\"btn btn-primary\" onClick=\"front_call({method:'"+v.method+"',sending:true,action:'"+v.action+"'})\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</div>\
		<div class=\"col-sm-1\"></div>\
		</div>\
		";
	}
	
} 

var jb2 = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "<div class=\"jumbotron jumbotron p-4\">\
		<h4 class=\"text-center\">"+v.title+"</h4>\
		<div class=\"row\">"+v.content+"</div>\
		<hr class='my-4'>\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2 align-bottom\"><div class=\"btn btn-primary\" onClick=\"front_call({method:'"+v.method+"',sending:true,action:'"+v.action+"'})\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</div>";
	}
	
} 

// CREA UN LISTGROUP RECIBE:OBJECT CON: title,body,footer,call_param y call_text
var list_group = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "\
		<div class=\"list-group\">\
		<a href=\"#\" id=\"lg_title\"class=\"list-group-item list-group-item-action active\">"+v.title+"</a>\
		<a href=\"#\" id=\"lg_body\"class=\"list-group-item list-group-item-action\">"+v.item+"</a>\
		<a href=\"#\" id=\"lg_footer\"class=\"list-group-item list-group-item-action disabled\">\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2\"><div class=\"btn btn-primary\" onClick=\"front_call("+v.call_param+")\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</a>\
		</div>";
	}
} 

// CARD DETALLE DE MOVIMIENTOS
const det_movs = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value 
			}
		}
	},
	set: function(v){
		// let scr = "<div class='card bg-light '><div class='card-header d-flex justify-content-start'>";
		// scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		// scr +="<i class='align-bottom icon ion-md-arrow-back'></i></button><h5 class=\"pl-4\"> Detalle de Operación  </h5>\</div>";
		let scr = '<div class=\'card bg-light\'>';
		scr +="<div class=\'card-body d-flex flex-wrap justify-content-start \'><div class=\'p-2 m-2\'>";
		//*****  BOT REIMPRIMIR RECIBO
			scr += "<button type=\"button\" class=\"btn btn-primary\" onClick=\"print_elem('reprint_recibo')\"><i class=\"icon ion-md-print\"></i></button>";
		scr +="</div>";
		//call a print recibo
		// console.log('call a print recibo', v)
		// recibo_reimprimir.create(v);
		for(var key in v){
			// console.log('OP',key , v[key])
				if(key != 'id' && key != 'cpr_id' && v[key] != null){
					if(key.match(/Monto/)){v[key] = parseFloat(v[key]).toLocaleString()};
					
					scr += data_box_small.create({id:0,label:key,value: v[key]}).get_screen();	
				}
		}
		scr += "</div></div>";
		this._screen = scr;
	},
}


// CARD DE VER OPERACION DE CAJA
const op_caja = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value 
			}
		}
	},
	set: function(v){
		// let scr = "<div class='card bg-light '><div class='card-header d-flex justify-content-start'>";
		// scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		// scr +="<i class='align-bottom icon ion-md-arrow-back'></i></button><h5 class=\"pl-4\"> Detalle de Operación  </h5>\</div>";
		let scr = '<div class=\'card bg-light\'>';
		scr +="<div class=\'card-body d-flex flex-wrap justify-content-start \'><div class=\'p-2 m-2\'>";
		//*****  BOT ANULAR OPERACION 
		scr += "<button type=\"button\" class=\"btn btn-primary mr-1\" onClick=\"front_call({method:\'anular_op\',sending:false,data:{id:\'"+v.id+"\'}})\"><i class=\"icon ion-md-trash\"></i></button>";
		
		//*****  BOT REIMPRIMIR RECIBO
		if(v.cpr_id){
			scr += "<button type=\"button\" class=\"btn btn-primary\" onClick=\"print_elem('reprint_recibo')\"><i class=\"icon ion-md-print\"></i></button>";
		}
		scr +="</div>";
		//call a print recibo
		// console.log('call a print recibo', v)
		// recibo_reimprimir.create(v);
		for(var key in v){
			// console.log('OP',key , v[key])
				if(key != 'id' && key != 'cpr_id' && v[key] != null){
					if(key.match(/Monto/)){v[key] = parseFloat(v[key]).toLocaleString()};
					
					scr += data_box_small.create({id:0,label:key,value: v[key]}).get_screen();	
				}
		}
		scr += "</div></div>";
		this._screen = scr;
	},
}



// CARDS DE EDITAR CONTRATO
var elem_card = {
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){


		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value 
			}
		}
	},
	set: function(v){
		this._data = v;
		// // console.log('pcles',v.el_pcles);
		let scr = "<div class='card bg-light '><div class='card-header d-flex justify-content-start'>";
		scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		scr +="<i class='align-bottom icon ion-md-arrow-back'></i></button><h5 class=\"pl-4\"> Contrato </h5>\</div>";
		scr +="<div class=\'card-body d-flex justify-content-around \'>";
		for(var key in v){
			
			if(key != 'cuotas'  && v[key]['id'] != null){
				if(v[key]['label'] == 'saldo'){
					console.log('saldo edi',v)
					let x = {'value':v[key]['value'],'label':v[key]['label'],'method':'update_elem_pcle','id':v[key]['id'],'parent_id':v[key]['elements_id']}
						z = editable.create(x);
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value:z

					}).get_screen();
				}else{
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value: v[key]['value']

					}).get_screen();
				}
			}
		}
		scr += "</div></div>";
		this._screen = scr;
	},
}

//  CARD CUOTAS EN EDITAR CONTRATO 
var cuotas_card = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-start'>\
		<h5 class=\"pl-4\"> Editar Cuotas </h5>\
		</div>\
		<div class=\'card-body d-flex justify-content-around\' id=\"cuotas_card_body\">\
		<p>"+v.t1+"</br></p>\
		</div>\
		</div>";
	}
}

// CARD CUOTAS SERVICIOS EN EDITAR CONTRATO
var servicios_card = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class='card bg-light '>\
		<div class='card-header  m-2 d-flex justify-content-start'>\
		<h5 class=\"pl-4\">"+v.title+" </h5>\
		</div>\
		<div class=\'card-body d-flex justify-content-around\' id=\"servs_card_body_"+v.index+"\">\
		<p>"+v.t1+"</br></p>\
		</div>\
		</div>";
	}
}


//  UN PANEL VACIO
var panel = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class='card d-flex flex-fill bg-light'>\
		<div class='card-header  d-flex justify-content-start'>\
		<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">\
		<i class='align-bottom icon ion-md-arrow-back'></i>\
		</button>\
		<h5 class=\"pl-4\">"+v.title+"</h5>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\' id=pnl_\""+v.pnl_id+"_body\">"+v.content+"</div></div>";
	}
}
//  BOXES PARA EL PANEL
var data_box = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		n = "<div class=\'card border-dark mb-3 \' style=\"max-width: 22rem;\" id=\"data_box"+v.id+"\">";
		n+="<div class=\"card-header\" onClick=\'"+v.onclick+"\' >"+v.label+"</div>"
		n+="<div class=\"card-body text-center \"><legend>"+v.value+"</legend></div></div>";
		this._screen = n;	
	}	
}


var data_box_small = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		n = "<div class=\'card m-3 p-1\' id=\"data_box"+v.id+"\">";
		
		n +="<div class=\"card-header\">"+ v.label+"</div>";
		n +="<div class=\"card-body text-center\">";
		n += "<p class=\"card-text\" >"+v.value+"</p>";
		n +="</div></div>";
		// n+="<div class=\"card-body  \">"+v.value+"</div></div>";
		this._screen = n;	
	}	
}

// objeto TABLA
const otbl={
	create:function(v,id){
		let r = '';
		r += "<table class=\"table table-hover\" id=\'"+id+"\'>";
		r += "<thead><tr>";
		r += (Object.keys(v[0]).map(i=>{return "<th>"+i+"</th>"})).join('');
		r +="</thead></tr>";
		r +="<tbody>";
		r += v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{return "<td>"+row[c]+"</td>"}).join('')+"</tr>"}).join('');
		r += "</tbody></table></div>";
		return r
	}
}


// objeto contenedor y tabla 

const ocont_and_table = {
	create:function(t,v,id){
		return "<div class=\"row p-1\"><div class=\"col d-flex justify-content-between\"></div>\
		</div>\
		<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-center'>\
		<h5>"+t+"</h5>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\'>\
		<div class=\'col d-flex p-1 justify-content-center\' id=\"container_table_last_movs\">"+otbl.create(v,id)+"</div>\
		</div>\
		</div>"
	}
}


/* CARD FOOTER CON BOTON 
<div class='card-body d-flex justify-content-around ' id=\"cuotas_card_footer\">\
	<button type=\"button\" id=\"bot_\" class=\"btn btn-secondary\" onClick=front_call({method:'set_pago_cuotas'})>Action</button>\
</div>\
*/


// ** CARDS ESTADO DE CUENTA DEL CLIENTE
var cards={
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	set: function(val){
		TOP.data = val;
		// // console.log('TOP data',TOP.data)
		this._data = val;
		this._screen = this.get_card1() + this.get_card2();
	},
	get_fec_init : function(){
		var r = "\
		<div class=\"btn-group p-1\" role=\"group\" aria-label=\"Button group with nested dropdown\">\
		<button type=\"button\" id=\"btn_curr_state\" class=\"btn btn-success\">"+this._data.lote['fec_init']+"</button>\
		</div>";
		// return "<span class=\"badge badge-"+type+" badge-pill \">"+x+"</span></li></a>";
		return r;
	},
	get_curr_state : function(){
		var type = '';
		var x = '';
		switch(this._data.lote.curr_state){
			case 'normal':
			type = 'success';
			x = 'Revisado';
			break;
			case 'a_revisar':
			type = 'danger';
			x = 'A Revisar';
			break;
			case 'cancelado':
			type = 'success';
			x = 'Cancelado';
			break;
			case 'rescindido':
			type = 'warning';
			x = 'Rescindido';
			break;
		}
		// *** SELECTOR DE ESTADO
		var r = "\
		<div class=\"btn-group p-1\" role=\"group\" aria-label=\"Button group with nested dropdown\">\
		<button type=\"button\" id=\"btn_curr_state\" class=\"btn btn-"+type+"\">"+x+"</button>\
		<div class=\"btn-group show\" role=\"group\">\
		<button id=\"btnGroupDrop1\" type=\"button\" class=\"btn btn-"+type+" dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\"></button>\
		<div class=\"dropdown-menu \" aria-labelledby=\"btnGroupDrop1\" x-placement=\"bottom-start\" style=\"position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform; z-index:10000;\">\
		<a class=\"dropdown-item\" id=\'state_item_1\' href=\"#\" onClick=front_call({'method':'set_curr_state',sending:true,'state':'normal'})>Revisado</a>\
		<a class=\"dropdown-item\" id=\'state_item_2\' href=\"#\" onClick=front_call({'method':'set_curr_state',sending:true,'state':'a_revisar'})>A Revisar</a>\
		</div>\
		</div>\
		</div>";
		// return "<span class=\"badge badge-"+type+" badge-pill \">"+x+"</span></li></a>";
		return r;
	},
	get_header : function(){
		var r = this.get('lote','lote_nom')+"&nbsp";
		r += this.get('lote','cli_atom_name')+"&nbsp; - "+this.get_telefono();
		if(TOP.user_id == '484'){
			r += " -/ ID Contrato:"+this.get('lote','elements_id')+"-/ ID Lote:"+this.get('lote','owner_id');
		}
		return r;
	},
	get:function(card,prop){
		if(this._data.hasOwnProperty(card) && this._data[card].hasOwnProperty(prop)){
			return this._data[card][prop];	
		}
	},
	get_screen:function(){
		return this._screen
	},
	get_telefono : function(){
		return this.get_gpcle(this._data.lote,'cli_data','celular') +" / "+this.get_gpcle(this._data.lote,'cli_data','telefono');
	},
	get_plan : function (){
		return this._data.lote.financ;	
	},
	get_pcle : function (card,p,lbl){
		if(this._data.hasOwnProperty(card) && this._data[card].hasOwnProperty(p) && this._data[card][p].length > 0){
			var r = this._data[card][p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];	
			}
		}
	},
	get_gpcle : function (arr,p,lbl){
		
		if(arr && arr.hasOwnProperty(p) && arr[p].length > 0){
			var r = arr[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];	
			}
		}
	},
	get_title : function (c,p,t){
		if(this._data[c].hasOwnProperty(p) && this._data[c][p].length > 0){
			var r = this._data[c][p].filter(function(i){return i.label === t});
			if(r.length >0){
				return r[0]['title'];	
			}
			
		}
	},
	
	get_nro_cta : function(){
		if(this._data.lote['cta_upc'].length > 0 && this._data.lote['cta_upc'][0].hasOwnProperty('cuota') && this._data.lote['cta_upc'][0]['cuota'].hasOwnProperty('id')){
			var x = this._data.lote['cta_upc'][0]['pcles'].filter(function(i){return i.label == 'nro_cta'});
			return x[0].value;
		}else {
			return ' ';
		}
	},
	get_cta_upc : function(){
		return (this._data.lote.hasOwnProperty('cta_upc') && this._data.lote.cta_upc !== 0 ? parseInt(this._data.lote.cta_upc.pcles.monto.value).toLocaleString():0);
	},
	get_cant_ctas_restantes : function(){
		return this._data.lote.ctas_restantes.events.length;
	},
	get_cant_ctas_pagas : function(){
		return parseInt(this._data.lote.ctas_pagas.events.length) ;
	},
	get_cant_ctas_pftrm :  function(){
		return this._data.lote.ctas_pft.events.length;
	},
	get_cant_ctas_adelant:function(){
		return this._data.lote.ctas_adelantadas.events.length;
	},
	get_cant_ctas_mora : function(){
		return this._data.lote.ctas_mora.events.length;
	},
	get_tot_en_mora : function(){
		return parseInt(this._data.lote.ctas_mora.total);	
	},
	get_tot_restantes :function(){
		return parseInt(this._data.lote.ctas_restantes.total);	
	}, 
	get_totcant_ctas_pagas : function(){
		
		return parseInt(this.get_cant_ctas_pagas()) + parseInt(this.get_cant_ctas_adelant())
	},
	get_tot_pagado : function(){
		var r = 0;
		if(this._data.lote['ctas_pagas'].hasOwnProperty('tot_pagado') && this._data.lote['ctas_adelantadas'].hasOwnProperty('total')){
			r = parseInt(this._data.lote['ctas_pagas'].tot_pagado)+parseInt(this._data.lote['ctas_adelantadas'].total);
		}
		return (isNaN(r)? r = 0:r=r.toLocaleString());
	},
	get_tot_a_pagar : function(){
		var x = parseInt(this.get_tot_en_mora()) + parseInt(this.get_tot_restantes());
		return (isNaN(x)? r = 0:r=x.toLocaleString());
	},

	get_ctas_a_pagar : function(){
		return this.get_cant_ctas_mora()+ this.get_cant_ctas_restantes();
	},
	get_monto_cta_actual(c){
		if(c[0]){
			let r = c[0]['pcles'].filter(function(i){return i.label === 'monto_cta'});
			return parseInt(r[0]['value']).toLocaleString();
		}else{
			return 0;
		}
	},
	lote_card : function(){
		let r ="<ul class=\"list-group\">\
		<li class=\"list-group-item d-flex justify-content-around align-items-center active \">Plan :&nbsp;"+this.get_plan()+"</li>\
		<li class=\"list-group-item d-flex justify-content-around align-items-center active \" >Cuotas Pagas :&nbsp;"+this.get_totcant_ctas_pagas()+"  -- Total $: "+this.get_tot_pagado()+"</li>\
		<li class=\"list-group-item d-flex justify-content-around align-items-center active \">Cuotas A Pagar :&nbsp;"+this.get_ctas_a_pagar()+"&nbsp;cuotas  -- Total $:&nbsp; "+this.get_tot_a_pagar()+"</li>\
		<li class=\"list-group-item d-flex justify-content-around align-items-center active \" >Cuota Actual $ :&nbsp;"+this.get_monto_cta_actual(this._data.lote.ctas_restantes.events)+"</li>\
		"+(this._data.lote.sf.monto != 0?"<li class=\"list-group-item d-flex justify-content-around align-items-center active p-1\" >Saldo a Financiar $ :&nbsp"+parseInt(this._data.lote.sf.monto).toLocaleString()+"</li>\
			<li class=\"list-group-item d-flex justify-content-around align-items-center active \" >En Fecha:&nbsp"+this._data.lote.sf.fecha+"</li>":'')+"\
		<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas&nbsp;Pagadas\',elem:'lote',action:\'ctas_pagas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagas<span class=\"badge badge-success badge-pill\">"+this.get_cant_ctas_pagas()+"</span></li></a>\
		<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas&nbsp;Adelantadas\', elem:'lote',action:\'ctas_adelantadas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Adelantadas<span class=\"badge badge-success badge-pill\">"+this.get_cant_ctas_adelant()+"</span></li></a>\
		<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas&nbsp;Restantes\',elem:'lote',action:\'ctas_restantes\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Restantes<span class=\"badge badge-primary badge-pill\">"+this.get_cant_ctas_restantes()+"</span></li></a>\
		<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas&nbsp;Pagas Fuera de Termino\',elem:'lote',action:\'ctas_pft\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagadas Fuera de termino<span class=\"badge badge-warning badge-pill\">"+this.get_cant_ctas_pftrm()+"</span></li></a>\
		<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas&nbsp;en&nbsp;Mora\',elem:'lote',action:\'ctas_mora\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas en Mora<span class=\"badge badge-danger badge-pill\">"+this.get_cant_ctas_mora()+"</span></li></a>\
		"+(this.get('saldo')>0?"<a href=\'#\' ><li class=\"list-group-item d-flex justify-content-between align-items-center\">Saldo a favor<span class=\"badge badge-success badge-pill\">"+this.get('lote','saldo')+"</span></li></a>":"");+"\
		</ul><p></br></p>";
		return r;
	},
	//***************** CARD SERVICIOS ***********************
	get_deuda_servicios : function(){
		d=0;
		this._data.srv.forEach( s => d += parseInt(s.estado_deuda))
		return d;
	},
	get_tot_srvs : function(){
		p=0;
		this._data.srv.forEach( s => p += parseInt(s.precio))
		return p;	
	},
	get_servicios : function(){
		p='';
		// this._data.srv.forEach( s => p += "<a href=\'#\' onClick=\"front_call({method:\'detalle_servicio\',title:\'Detalle de "+s.servicio+"\',action:\'servicios\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">"+s.servicio+"<span>"+s.precio.toLocaleString()+"</span></li></a>\
		// ");
		// return p;
	},
	service_cards : function(){
		var r = '';
		// console.log('servicio',this._data.srv)
		for (var i = 0 ; i < this._data.srv.length ; i ++){
			// r +="<div class=\'col d-flex p-1 justify-content-center\'>";
			r +="<div id=\'srv_card_"+i+"\'class=\'col d-flex p-1 justify-content-center\'>";
			r +="<ul class=\"list-group\">";
			if(parseInt(this._data.srv[i].tot_pagado) === 0){
				r +="<li class=\"list-group-item d-flex justify-content-end align-items-right active p-0 \">";
				r +="<button type=\"button\" class=\"btn btn-sm btn-secondary\" onclick=\"front_call({method:'kill_service_elem',sending:false,elm_id:'"+this._data.srv[i].srvc_id+"'})\"><i class=\"icon align-bottom ion-md-close-circle-outline\"></i></button></li>";	
			}
			
			r += "<li class=\"list-group-item d-flex justify-content-around align-items-center active "+(parseInt(this._data.srv[i].tot_pagado) === 0?'p-0':'')+" \">"+this._data.srv[i].srvc_name+"</li>"; 	
			r += "<li class=\"list-group-item d-flex justify-content-around align-items-center active \">Tot. Pagado: "+this._data.srv[i].tot_pagado.toLocaleString()+"</li>";
			// console.log('cta servicio',this._data.srv[i])
			r += "<li class=\"list-group-item d-flex justify-content-around align-items-center active \" style=\"padding:5px;\">Cuotas a Pagar:"+this._data.srv[i].ctas_restantes.events.length+" -- Total $:"+parseInt(this._data.srv[i].ctas_restantes.total).toLocaleString()+"</li>";
			r += "<li class=\"list-group-item d-flex justify-content-around align-items-center active \" style=\"padding:5px;\">Fecha último pago: "+this._data.srv[i].fec_ultimo_pago.toLocaleString()+"</li>";
			
			
			
			r += "<li class=\"list-group-item d-flex justify-content-around align-items-center active \" style=\"padding:5px;\">Cuota Actual $:"+this.get_monto_cta_actual(this._data.srv[i].ctas_restantes.events)+"</li>";
			
			// var tot_a_pagar = parseInt(get_pcle(this._data.srv[i].cta_upc,'monto_cta'))+ parseInt(this._data.srv[i].ctas_mora.total);
			// r += "<li class=\"list-group-item d-flex justify-content-center align-items-left active\" >Total a Pagar $: "+tot_a_pagar.toLocaleString()+"</li>";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Pagas en fecha\',elem:'srv',e_index:"+i+",action:\'ctas_pagas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagas<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_pagas'].events.length+"</span></li></a>";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Adelantadas\',elem:'srv',e_index:"+i+",action:\'ctas_adelantadas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Adelantadas<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_adelantadas'].events.length+"</span></li></a>";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicio Restantes\',elem:'srv',e_index:"+i+",action:\'ctas_restantes\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Restantes<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_restantes'].events.length+"</span></li></a>"
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Pagas fuera de termino\',elem:'srv',e_index:"+i+",action:\'ctas_pft\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagas Fuera de Termino<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_pft'].events.length+"</span></li></a>";

			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Restantes\',elem:'srv',e_index:"+i+",action:\'ctas_mora\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas en mora<span class=\"badge badge-danger badge-pill\">"+this._data.srv[i]['ctas_mora'].events.length+"</span></li></a>"

			r += "</ul></div>";// *** FINAL SERVICIOS

		}
		return r;
	},
	//  *****************************************

	// ************** CARD 1 *****************
	get_card1 : function(){
		return "<div class=\"row p-1\"><div class=\"col d-flex justify-content-between\">"+this.get_curr_state()+" "+this.get_fec_init()+"\
		</div>\
		</div>\
		<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-between'>\
		<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class='icon align-bottom  ion-md-arrow-back'></i></button>\
		<h5>"+this.get_header()+"</h5>\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'atom_crude',action:'edit',sending:true,data:{atom_id:"+this._data.lote['cli_id']+",owner_id:"+this._data.lote['elements_id']+"}})><i class=\"icon align-bottom ion-md-open\"></i></button>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\'>\
		<div id=\'lote_card\' class=\'col d-flex p-1 justify-content-center\'>"+ this.lote_card() + "</div>\
		"+this.service_cards()+"\
		</div>\
		<div class='card-body d-flex justify-content-between '>\
		<button type=\"button\" id=\"bot_pago\" class=\"btn btn-secondary\" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>Ingresar Pago</button>\
		<button type=\"button\" id=\"bot_new_service\" class=\"btn btn-secondary\" onClick=front_call({method:'new_service_elem',action:'call',sending:true})>Nuevo Servicio</button>\
		</div>\
		</div>"
	},
	// **********************************************
	// **************  CARD 2  *****************
	
	get_card2 : function(){
		return "<div class=\"row p-1\"><div class=\"col d-flex justify-content-between\"></div>\
		</div>\
		<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-center'>\
		<h5>Últimos Movimientos</h5>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\'>\
		<div class=\'col d-flex p-1 justify-content-center\' id=\"container_table_last_movs\">"+otbl.create(this._data.last_mov,'table_last_movs')+"</div>\
		</div>\
		</div>"
	} ,
	// *********************************************
};

// ****** ****************** ***********  ***********
// ****** PAGO DE CUOTA
// ****** ****************** ***********  ***********
// c = ['tipo_pago'=>'Normal','fec_vto'=>$ct[0]['cuota']->date,'pcles'=>$ct[0]['pcles'],'tot_cta'=>intval($monto[key($monto)]['value'])];
var pgc = {
	_tipocuota:{'actual':1,'adelanto':2},
	_cant_adelanto:{'cant':1},
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	get_gpcle : function (arr,p,lbl){
		
		if(arr && arr.hasOwnProperty(p) && arr[p].length > 0){
			var r = arr[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];	
			}
		}
	},
	get_pcle : function (p,lbl){
		if(this._data.hasOwnProperty(p) && this._data[p].length > 0){
			var r = this._data[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];	
			}
		}
	},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.pago_cta.selects[name].length; i++) {
			var n = this._data.pago_cta.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	
	},
	set: function(v){
		console.log('en pgc',TOP.data);
		this._data = TOP.data;
		this._data.pago_cta = TOP.pago;
		this._data.container = 'main_container';
		this._screen = "<div class=\"jumbotron\" style=\"padding:25px;\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-2 col-md-1 \"><button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class=\'align-bottom icon ion-md-arrow-back\'></i></button></div>\
		<div class=\"col-xs-8 col-lg-9\">";
		this._screen += "<h5>"+this._data.lote.lote_nom+ " "+ this.get_gpcle(this._data.lote,'cli_data','nombre') + " "+ (this.get_gpcle(this._data.lote,'cli_data','apellido')== undefined?'':this.get_gpcle(this._data.lote,'cli_data','apellido'))+"<h5>";
		this._screen +="</div>\
		</div>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col\">\
		<legend class=\'align-baseline\'>Cuotas Lote</legend></div>\
		<div class=\"col text-right\">\
		<button type=\"button\" onClick=agregar_adls('lote') class=\"btn btn-primary \"><i class=\'icon ion-md-add-circle-outline\'></i></button>\
		</div></div>\
		<div class=\"col-xs-12\" id=\"ctas_table\"></div>\
		<hr>";
		if(this._data.srv.length > 0){
			this._screen +=	"<div class=\"row d-flex justify-content-between\">\
			<div class=\"col\">\
			<legend class=\'align-baseline\' id=\'tit_servicios\'>Cuotas Servicios</legend></div>\
			<div class=\"col text-right\">\
			<button type=\"button\" onClick=agregar_adls('servicios') class=\"btn btn-primary \"><i class=\'icon ion-md-add-circle-outline\'></i></button>\
			</div></div><div class=\"col-xs-12\" id=\"srvs_table\"></div>";	
		}
		let n = '';  
		n +="<div class=\"row d-flex justify-content-start\">";

		// **** BLOCK SELECTORES RECIBO / FECHA / CUENTA *****
		n +="<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4\">";
		// n +="<div class=\"form-group\" id=\"fg_rec_num\"><label for=\"rec_num\">Nro. de recibo</label><input type=\"number\" readonly=\"true\" class=\"form-control\" id=\"rec_num\" value="+this._data.pago_cta.rec_num+"></div>";
		n += date_obj.create({label:'fecha_pago',title:'Fecha de pago','extras':'no_col'}).get_screen();
		n +="<div class=\"row m-1\"></div>";
		n += select_obj.create({label:'cuentas',title:'Cuenta','extras':'no_col'}).get_screen();
		// n +="<div class=\"d-flex align-content-start flex-wrap p-1\"><div class=\"form-group\" id=\"fg_cuenta_pgc\"><label for=\"cuenta_pgc\">Cuenta</label><select class=\"form-control\" id=\"cuenta_pgc\"><option value = -1 >Selecciona la cuenta</option>";
		// n += this.fill_select('cuentas');
		n += "</div>";
		
		// **** BLOCK NUMEROS DE PAGO *********
		n +=  "<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4 p-2\"><big>";

		n+="<div class=\'row d-flex justify-content-end\'>";
		n+="<div class=\"form-group row form-inline p-1\">";
		n+="<label for=\"monto_ctas\" class=\"col-form-label text-right\">Total Cuotas $:</label>";
		n+="<div class=\"col \">";
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_ctas\" value=0>";
		n+="</div>";
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div></div>";
		n+="<div class=\'row d-flex justify-content-end\'>";
		n+="<div class=\"form-group row form-inline p-1\"><label for=\"monto_interes\" class=\"col-form-label text-right\">Total Intereses $:</label>"
		n+="<div class=\"col\"><input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_interes\" ></div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div></div>";


		n+=" <div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"monto_servicios \" class=\"col-form-label text-right\">Total Servicios $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_servicios\"value=0>"
		n+="</div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"monto_a_pagar \" class=\"col-form-label text-right\">Total Cargos $:</label>"
		n+="<div class=\"col \">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_a_pagar\">"
		n+="</div>";
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1 text_right\">"
		n+="<label for=\"saldo \" class=\"col-form-label text-right\">Saldo en cuenta $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"saldo\">"
		n+="</div>";
		n+="<div class=\"col-1 p-0\"></div>";
		n+="</div>";
		n+="</div>";

		n+=" <div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"estado_actual \" class=\"col-form-label text-right\">Estado Actual $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"estado_actual\"value=0>"
		n+="</div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>";

		// ****** BLOCK MONTO INGRESADO  *********

		n+="<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4 \"><big>";
		n+="<div class=\'row d-flex justify-content-center m-4\'>";
		n+="<div class=\"form-group row form-inline p-1\">";
		n+="<label for=\"monto_recibido\" class=\"col-form-label-lg text-right\">Total ingresado $:</label>";
		// n+="<div class=\"col \">";
		n+="<input type=\"number\" class=\"form-control-lg\" id=\"monto_recibido\" onChange=check_pgc_monto_regibido()>";
		// n+="</div>";
		n+="</div>";
		n+="</div>";

		n+="<div class=\'row d-flex justify-content-center m-3\'>";
		n+="<div class=\"form-group row form-inline p-1\">";
		// n+="<div class=\"col-3 d-flex align-self-end\">";
		n+="<div class=\"btn btn-primary\" id=\"bot_process_pago\" onClick=\"front_call({method:'procesar_pago_cuota',sending:false})\" href=\"#\" role=\"button\">Procesar Pago</div>";
		n+="</div>";
		n+="</div>";	
		n+="</div>";

		this._screen += n;						
							// </div>";
		// this._screen +=	"<div class=\'row d-flex justify-content-around\'>\
								// 		<label for=\"monto_pago \" class=\"col-form-label\">Total a pagar $:</label>\
								// 		<div class=\"col\">\
								// 	   		<input type=\"text\" readonly=\"\" class=\"form-control-plaintext\" id=\"monto_pago\">\
								// </div>\
							// </div></big>";
						// 	<div class=\"row d-flex justify-content-start\">\
						// 		<div class=\"col\" name=\'tot_a_pagar\'>\
						// 			<legend><div class=\"form-group row form-inline float-left\">\

						// 			    
						// 			</div></legend>\
						// 		</div>\

						// 		    <div class=\"col-3 d-flex align-self-end\">\
						// 				<div class=\"btn btn-primary\" id=\"bot_process_pago\" onClick=\"front_call({method:'procesar_pago_cuota'})\" href=\"#\" role=\"button\">Procesar Pago</div>\
						// 			</div>\
						// 		</div>\
						// 	</div>\
						// </div>\
						// <div class=\"row\" id=\"row_ctas_adl\"></div>\
						// <div class=\"row\">";
		// this._screen +="<div class=\"col-8 d-flex align-self-start\"></div></div>";
	}
};

// RECIBO DE PAGO DE CUOTA
var rec_pgc = {
	_print:{},
	create: function(){
		var o = Object.create(this);
		o.set();
		return o;	
	},
	get_print:function(){return this._print},
	get_Tpcle : function (arr,lbl){
		if(arr.length > 0){
			var r = arr.filter(function(i){return i.label === lbl});
			if(r.length > 0){
				return r[0]['value'];	
			}
		}
	},
	set: function (){
		this._print ="\
		<font size=\"+2\">\
		<div class=\"container-fluid\">\
		<div class=\"row\">\
		<div class=\"col\"><img src=\"aplication_images/logo_recibo.jpg\"></div>\
		<div class=\"col\"><p></p><h3>RECIBO NRO.: "+TOP.curr_recibo_nro+"</h3><h3>FECHA: "+moment().format('D/M/YYYY')+"</h3></div>\
		</div>\
		<hr>\
		<div class=\"row\">\
		<p></p>\
		\
		<div class=\"col\">\
		<p>Nombre: "+this.get_Tpcle(TOP.data.lote.cli_data,'nombre')+"</p>\
		<p>telefono: "+this.get_Tpcle(TOP.data.lote.cli_data,'telefono')+"</p>\
		<p>Forma de pago:"+$("#cuenta_pgc option:selected" ).text()+"</p>\
		</div>\
		<div class=\"col\">\
		<p>Domicilio: "+this.get_Tpcle(TOP.data.lote.cli_data,'domicilio')+", "+this.get_Tpcle(TOP.data.lote.cli_data,'localidad')+"</p>\
		<p>e-mail: -- </p>\
		</div>\
		</div>\
		<hr>\
		</br>\
		<div class=\"row\">\
		<div class=\"col\">\
		<p>Recibimos la suma de Pesos: "+numeroALetras(parseInt($('#monto_recibido').val()), {plural: 'PESOS ',singular: 'PESO',centPlural: 'CENTAVOS',centSingular: 'CENTAVO'})+"</p>\
		<p>En concepto de : "+TOP.concepto+"</p>\
		</div>\
		</div>\
		</br>\
		</br>\
		</br>\
		<div class=\"row\">\
		<div class=\"col\">\
		<p>firma: ______________________</p>\
		<p>Aclaración: __________________</p>\
		\
		</div>\
		<div class=\"col\">\
		<h2>Son $: "+parseInt($('#monto_recibido').val())+".-</h2>\
		\
		</div>\
		</div>\
		</div>\
		</font>"
	} 
};

// ****** ****************** ***********  ***********
// ****** END PAGO DE CUOTA
// ****** ****************** ***********  ***********





// ****** ****************** ***********  ***********
// RECIBO REIMPRESION 
// ****** ****************** ***********  ***********
const recibo_reimprimir = {
	_print:{},
	create: function(v){
		let o = Object.create(this);
		o.set(v);
		return o;	
	},
	get_print:function(){return this._print},
	get_Tpcle : function (arr,lbl){
		if(arr.length > 0){
			let r = arr.filter(function(i){return i.label === lbl});
			if(r.length > 0){
				return r[0]['value'];	
			}
		}
	},
	set: function (v){
		let tp = "<font size=\"+1\"><div class=\"container\"><div class=\"row\"><div class=\"col\"><img src=\"aplication_images/logo_recibo.jpg\"></div>";
		tp +="<div class=\"col\"><p></p><h3>RECIBO NRO.: "+v['Nro. de Comprobante']+"</h3><h3>FECHA: "+v['Fecha']+"</h3></div>"
		tp +="</div><hr><div class=\"row\"><p></p><div class=\"col\"><p>Nombre: "+v['Cliente']+"</p>";
		tp +="<p>Codigo Lote: "+v['Codigo Lote']+"</p>"
		tp += "<p>Forma de pago:"+v['Caja']+"</p>"
		tp += "</div></div>"
		// <div class=\"col\"><p>Domicilio: "+this.get_Tpcle(TOP.data.lote.cli_data,'domicilio')+", "+this.get_Tpcle(TOP.data.lote.cli_data,'localidad')+"</p>\
		// <p>e-mail: -- </p>\
		tp +="<hr></br><div class=\"row\"><div class=\"col\">";
		console.log('reimp',v);
		tp +="<p>Recibimos la suma de Pesos: "+numeroALetras(parseInt(v['Monto $'].replace(/,/g, '')), {plural: 'PESOS ',singular: 'PESO',centPlural: 'CENTAVOS',centSingular: 'CENTAVO'})+"</p>";
		
		tp += "<p>En concepto de : "+v['Concepto']+"</p></div></div></br></br></br><div class=\"row\"><div class=\"col\"><p>firma: ______________________</p><p>Aclaración: __________________</p></div><div class=\"col\"><h2>Son $: "+v['Monto $']+".-</h2>"
		tp+="</div></div>"
		tp +="<hr/><div class=\"row\"><div class=\"col\"><p>";
		tp +="* La fecha de vencimiento de cada cuota es el dia 25 de cada mes, luego generara intereses por mora."
		tp +="</p></div></div>";
		tp +="<div class=\"row\"><div class=\"col\"><p>";
		tp +="* Su codigo de Pago Facil para abonar en Pagos Pyme es de LPT + "+ v['Codigo Lote'];
		tp +="</p></div></div>";
		tp +="<div class=\"row\"><div class=\"col\"><p>";
		tp +="* No se reciben depositos bancarios, si transferencias y por la misma, debe enviar el comprobante a la casilla de mail administracion@lotesparatodos.com.ar o bien al whatsapp 11 3359-8458";
		tp +="</p></div></div>";
		tp +="</div></font>";

		this._print = tp;
	} 	
};




// ****** ****************** ***********  ***********
// ** CONFIRMATION WINDOW 
// ****** ****************** ***********  ***********
var conf={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this.title = v.title;
		this._data = v;
		this._screen = "\
		<div class=\"row\">\
		<div class=\"col-lg-12\">\
		<legend>"+v.msg+"</legend>\
		</div>\
		</div>";
	}

};



// ****** ****************** ***********  ***********
// ** ALERT  WINDOW 
// ****** ****************** ***********  ***********
var alert={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = "\
		<div class=\"alert alert-"+v.type+"\">\
		<h4>"+v.tit+"</h4><p>"+v.msg+"</p></div>";
	}
};




// ****** ****************** ***********  ***********
// ** INPUT WINDOW
// ** llamado por 
// ** Obtener resumen de cuenta y Editar Contrato de Lote
// ****** ****************** ***********  ***********
var get_element_input={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v; 
		this._screen = "\
		<div class=\"form-group\">\
		<label class=\"col-form-label\" for=\"lote\">Nombre de Cliente o Lote</label>\
		<input type=\"text\" class=\"form-control\" placeholder=\"Ingresa nombre del cliente o numero de lote \" id=\"lote\">\
		</div>"
	}
};
// *********************


//  PANTALLA PLANILLA DE CAJA
var planilla_caja = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this.tot_caja = (v.saldo + v.tt_ingr) - v.tt_egre
		var x = ''
		this._screen = 
		"<div class=\"row\"><div class=\'col\'><h5></h5></div></div>\
		<div class=\"jumbotron jumbotron-fluid\" style=\"padding-top:25px\">\
		<div class='container-fluid' role='container'>\
		<div class=\"row d-flex justify-content-start\">\
		<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class='align-middle icon ion-md-arrow-back'></i></button>\
		<div class=\"col-6 \"><h5>Planilla de caja: "+v.caja_nom+"</h5></div>\
		<div class=\"col\"><h5>Desde: "+v.fd+"</h5></div>\
		<div class=\"col\"><h5>Hasta: "+v.fh+"</h5></div>\
		</div>\
		<hr>"
		if(v.tt_ingr > 0){
			this._screen +="<div class=\"row\">\
			<div class=\"col\"><h4>Ingresos</h4></div>\
			</hr>\
			</div>\
			<div class=\"row\">\
			<div class=\"col\" id=\"table_ingresos\"></div>\
			</div>"
		}
		if(v.tt_egre > 0){
			this._screen += "<div class=\"row\">\
			</hr>\
			<div class=\"col\"><h4>Egresos</h4></div>\
			</hr>\
			</div>\
			<div class=\"row\">\
			</hr>\
			<div class=\"col\" id=\"table_egresos\"></div>\
			</hr>\
			</div>";
		}	
		this._screen += "</br>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Saldo Previo:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.saldo.toLocaleString()+"</h5></div>\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total Ingresos:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.tt_ingr.toLocaleString()+"</h5></div>\
		</div>\
		<div class=\"row d-flex justify-content-end\">\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total Egresos:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.tt_egre.toLocaleString()+"</h5></div>\
		</div>\
		<div class=\"row d-flex justify-content-end\">\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+this.tot_caja.toLocaleString()+"</h5></div>\
		</div>\
		</div>\
		</div>";
	}
}


// ** PANTALLA DE REGISTRO DE OPERACIONES
var reg_op = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	fill_select:function(name){
		// // console.log('setting select:',name)
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		
		this._data = v;
		this._screen = 
		"<div class=\"row\"><div class=\'col\'><h5></h5></div></div>\
		<div class=\"jumbotron\" style=\"padding-top:25px;\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-1\"><button type=\"button\" onClick=front_call({'method':'registro_operacion','sending':false,'action':'step_back'}) class=\"btn btn-primary\"><i class='align-bottom icon ion-md-arrow-back'></i></button>\
		</div>\
		<div class=\"col\">\
		<h4>Registro de Operaciones<h4>\
		</div>\
		<div class=\"col\">\
		<h5>Fecha: "+v.fecha+" </h5>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-12\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_tipo_asiento\">\
		<label for=\"tipo_asiento\">Tipo de Asiento</label>\
		<select class=\"form-control\" id=\"tipo_asiento\" onBlur=chk_tipo_asiento()>\
		<option value=\"\">Selecciona el tipo de asiento</option>\
		<option value=\"INGRESOS\">Ingreso</option>\
		<option value=\"EGRESOS\">Egreso</option>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group\" id=\"fg_cuenta\">\
		<label for=\"cuenta\">Cuenta</label>\
		<select class=\"form-control\" id=\"cuenta\"\"><option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_imputacion\">\
		<label for=\"imputacion\">Concepto</label>\
		<select class=\"form-control\" id=\"imputacion\"><option value=''>Selecciona la imputación</option>\
		"+this.fill_select('impt_prov')+"</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_contraparte\">\
		<label for=\"contraparte\">Proveedor</label>\
		<select class=\"form-control\" id=\"contraparte\">\
		<option value='-'>Seleccionar -</option>\
		"+0+"</select>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\" id=\"centro_costos_container\">\
		<div class=\"col-sm-5\">\
		<div class=\"form-group\" id=\"fg_cent_ctos_"+TOP.count_centro_costos_list+"\">\
		<label for=\"cent_ctos\">Centro de Costos</label>\
		<select class=\"form-control\" id=\"cent_ctos_"+TOP.count_centro_costos_list+"\" onChange=\"select_cctos_id(this.id)\" ><option value=''>Selecciona el Centro de Costos</option>\
		"+this.fill_select('barrio')+"</select>\
		</div>\
		</div>\
		<div class=\"form-group\" id=\"fg_percent_cctos_"+TOP.count_centro_costos_list+"\">\
		<label for=\"percent_barrio_"+TOP.count_centro_costos_list+"\">Distribucion Porcentaje </label>\
		<div class=\"input-group \">\
		<input type=\"number\" max=100 min=0 class=\"form-control\" id=\"percent_cctos_"+TOP.count_centro_costos_list+"\" aria-describedby=\"basic-addon\"\
		<div class=\"input-group-append\">\
		<span class=\"input-group-text\" id=\"basic-addon\">%</span>\
		</div>\
		</div>\
		<div class=\"col-sm-3\">\
		</br>\
		<div class=\"btn btn-secondary align-bottom\" onClick=add_cctos() href=\"#\" role=\"button\"><i class='icon align-bottom  ion-md-add-circle'></i></div>\
		<div class=\"btn btn-secondary align-bottom\" onClick=remove_cctos() href=\"#\" role=\"button\"><i class='icon align-bottom  ion-md-remove-circle'></i></div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-4\">\
		<div class=\"form-group\">\
		<label for=\"numero_comprobante \" class=\"col-form-label\">Nro. Comprobante</label>\
		<input type=\"text\" class=\"form-control\" id=\"numero_comprobante\">\
		</div>\
		</div>\
		<div class=\"col-sm-8\">\
		<div class=\"form-group\">\
		<label for=\"observaciones\" class=\"col-form-label\">Observaciones</label>\
		<input type=\"text\" class=\"form-control\" id=\"observaciones\">\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-sm-4 \">\
		<div class=\"form-group inline float-right\" id=\"fg_monto\">\
		<label for=\"monto\" class=\"col-form-label\"><legend>Monto $:</legend></label>\
		<input type=\"number\" class=\"form-control align-self-center\" id=\"monto\" >\
		</div>\
		</div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-primary\" id=\"bot_guardar\"onClick=\"front_call({method:'registro_operacion',sending:true,action:'save'})\"  href=\"#\" role=\"button\">Guardar\
		</div>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-8\" id=\"result_footer\" ></div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-secondary\" id=\"bot_volver\"onClick=\"front_call({method:'back'})\"  href=\"#\" role=\"button\">Volver\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>"
	}

}

// ** PANTALLA DE PASE ENTRE CAJAS
var pase_caja_screen = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	fill_select:function(name){
		// // console.log('setting select:',name)
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		
		this._data = v;
		this._screen = 
		"<div class=\"row\"><div class=\'col\'><h5></h5></div></div>\
		<div class=\"jumbotron\" style=\"padding-top:25px;\">\
		<div class=\"row d-flex justify-content-around\">\
		<div class=\"col-sm-1\"><button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class='align-bottom icon ion-md-arrow-back'></i></button>\
		</div>\
		<div class=\"col\">\
		<h4>Transferencia entre Cajas<h4>\
		</div>\
		<div class=\"col\">\
		<h5>Fecha: "+v.fecha+" </h5>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-12\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_cuenta_egreso\">\
		<label for=\"cuenta_egreso\">Cuenta Origen</label>\
		<select class=\"form-control\" id=\"cuenta_egreso\">\
		<option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group inline float-right\" id=\"fg_monto_egreso\">\
		<label for=\"monto_egreso\" >Monto salida :</label>\
		<input type=\"number\" min=1 class=\"form-control align-self-center\" id=\"monto_egreso\" >\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_cuenta_ingreso\">\
		<label for=\"cuenta_ingreso\">Cuenta Destino</label>\
		<select class=\"form-control\" id=\"cuenta_ingreso\">\
		<option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group inline float-right\" id=\"fg_monto_ingreso\">\
		<label for=\"monto_ingreso\">Monto Ingresado :</label>\
		<input type=\"number\" min=1 class=\"form-control align-self-center\" id=\"monto_ingreso\" >\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-4\">\
		<div class=\"form-group\">\
		<label for=\"numero_comprobante \" class=\"col-form-label\">Nro. Comprobante</label>\
		<input type=\"text\" class=\"form-control\" id=\"numero_comprobante\">\
		</div>\
		</div>\
		<div class=\"col-sm-8\">\
		<div class=\"form-group\">\
		<label for=\"observaciones\" class=\"col-form-label\">Observaciones</label>\
		<input type=\"text\" class=\"form-control\" id=\"observaciones\">\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-primary\" id=\"bot_guardar\"onClick=\"front_call({method:'pase_entre_cajas',sending:true,action:'save'})\"  href=\"#\" role=\"button\">Guardar\
		</div>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-8\" id=\"result_footer\" ></div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-secondary\" id=\"bot_volver\"onClick=\"front_call({method:'back'})\"  href=\"#\" role=\"button\">Volver\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>"
	}

}



// DEPRECATED???
// var barrios_box = {
// 	_data:{},
// 	_screen:{},
// 	create:function(val){
// 		var obj = Object.create(this);
// 		obj.set(val);
// 		return obj;     
// 	},
// 	fill_item:function(name){
// 		var x='';
// 		for (var i = 0; i < this._data.selects[name].length; i++) {
// 			var n = this._data.selects[name][i];
// 			x += "\
// 			<li class=\"list-group-item d-flex justify-content-between align-items-center\" style=\"padding-bottom:1px;\">\
// 			<div class=\"col-sm-6\"><span id=\""+n.id+"\">"+n.lbl+"</span></div>\
// 			<div class=\"col-sm-6\">\
// 			<div class=\"input-group mb-3\">\
// 			<input type=\"number\" max=100 min=0 class=\"form-control align-self-center\" id=\"percent_barrio_"+n.id+"\" aria-describedby=\"basic-addon\"\
// 			<div class=\"input-group-append\">\
// 			<span class=\"input-group-text\" id=\"basic-addon\">%</span>\
// 			</div>\
// 			</div>\
// 			</div>\
// 			</li>";   
// 		}
// 		return x;       

// 	},
// 	get:function(val){return this._data[val]},
// 	get_screen:function(){return this._screen},
// 	set: function(v){
// 		this._data = v;
// 		this._screen ="\
// 		<ul class=\"list-group\">\
// 		"+this.fill_item('barrio')+"\
// 		</ul>";
// 	}
// }



// SELECCIONAR ARCHIVO PARA SUBIR AL SERVIDOR
const dialog_upload = {
		_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		this._scrn =
	"<form id=\"upload_form\" method=\"post\" enctype=\"multipart/form-data\"><div class=\"form-group\"><input type=\"file\" class=\"form-control-file\" id=\"file_to_upload\" aria-describedby=\"fileHelp\"><small id=\"fileHelp\" class=\"form-text text-muted\">seleciona el archivo para adjuntar al lote y clickea el boton OK.</small></div></format>";
	}


}


// SELECT NUEVO PLAN DE FINANC EN CAMBIO DE CICLO
var dialog_new_plan = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < TOP.selects[name].length; i++) {
			var n = TOP.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	set: function(v){
		this._data = v;
		this._screen ="<div class=\"row d-flex justify-content-center\">";
		this._screen +="<div class=\"col d-flex \"><div class=\"form-group row\"><label class=\'col-form-label\'  for=\"lfp\">Última fecha de Pago:</label><input type=\"text\" id=\'lfp\' readonly=\"\" class=\"form-control-plaintext text-left\" value=\'"+v.last_fec_pago+"\'></div></div>";
		
		this._screen +="<div class=\"col  d-flex \"><div class=\"form-group row\"><label class=\'col-form-label\' for=\"lm\">Monto $:</label><input type=\"text\" id=\'lm\' readonly=\"\" class=\"form-control-plaintext text-left\" value=\'"+v.last_monto_pagado+"\'></div></div>";
		this._screen +="</div>"; 
		this._screen +="<hr/><div class=\"row d-flex justify-content-center\">\
		<div class=\"col\">\
		<div class=\"form-group\" id=\"fg_financ_plan_select\">\
		<label for=\"financ_plan_select\">Selecciona el Plan de Financiación</label>\
		<select class=\"form-control\" id=\"financ_plan_select\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('financiacion')+"</select>\
		</div>\
		</div>\
		</div>\
		";
		this._screen +="<hr/><div class=\"row d-flex justify-content-center\"><div class=\"col p-2\">";
		this._screen +=date_obj.create({label:'financ_plan_fec_prox_venc',title:'Proximo Vencimiento'}).get_screen();
		this._screen +="</div></div>";
	}
}


//  config select del atom
var dialog_atom = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	set: function(v){
		this._data = v;
		this._screen = 
		" <div class=\"row d-flex justify-content-center\">\
		<div class=\"col\">\
		<div class=\"form-group\" id=\"fg_atm_select\">\
		<label for=\"caja\">Obtener Listado de Items</label>\
		<select class=\"form-control\" id=\"atm_select\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('atoms')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

}
//  config select del atom
var dialog_revision = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		var c = "<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\"><label class=\"col-form-label\" for=\"lote\">Numero de Lote</label><input type=\"text\" class=\"form-control\" id=\"rev_lote\" value=\""+(this._data.lote != undefined ? this._data.lote : '')+"\"></div></div></div>";
		c +="<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\"><label class=\"col-form-label\" for=\"coment\">Mensage</label><input type=\"text\" class=\"form-control\" id=\"rev_coment\" placeholder=\""+(this._data.coment != undefined ? this._data.coment : '')+"\"></div></div></div>";
		
		c +="<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\">"+select_obj_by_name.create({label:'asignado_a',title:'Asignar A'}).get_screen()+"</div></div></div>";	
		
		this._screen = c;
	}
}

//  config select del tablas contab
var dialog_contab = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	set: function(v){
		this._data = v;
		this._screen = 
		" <div class=\"row d-flex justify-content-center\">\
		<div class=\"col\">\
		<div class=\"form-group\" id=\"fg_contab_select\">\
		<label for=\"caja\">Obtener Listado Items de caja</label>\
		<select class=\"form-control\" id=\"contab_select\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('contab')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

}

// *** arqueo DIALOG WINDOW
var dialog_arqueo= {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";	
		}
		return x;    	

	},
	set: function(v){
		this._data = v;
		this._screen = 
		" <div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-4\">"+date_obj.create({label:'fec_desde',title:'Desde Fecha'}).get_screen()+"</div>\
		<div class=\"col-sm-4\">"+date_obj.create({label:'fec_hasta',title:'Hasta Fecha'}).get_screen()+"</div>\
		<div class=\"col-sm-4\">\
		<div class=\"form-group\" id=\"fg_caja\">\
		<label for=\"caja\">Caja o Banco</label>\
		<select class=\"form-control\" id=\"caja\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('cuentas')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

};



// *** CRUDE DIALOG WINDOW
var dialog_crude= {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class=\"form-group width-100\">\
		<label class=\"col-form-label\" for=\"atom_name\">"+v.label+"</label>\
		<input type=\"text\" class=\"form-control\" placeholder=\""+v.placeholder+"\" id=\"atom_name\">\
		</div>\
		"
	}
};

// *******************
// *** CRUDE OBJECTS *
// *******************

var container_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen ="<div class=\"d-flex align-content-stretch flex-wrap \">";
		this._screen += v;
		this._screen +="</div>"; 
	}
};	

var text_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class=\"d-flex align-content-start flex-wrap p-1 \">\
		<div class=\"form-group width-100\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label:v.title)+"</label>\
		<input type=\"text\" class=\"form-control\" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" >\
		</div>\
		</div>"
	}
};	


// OBJ NUMBER
var number_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = 
		"<div class=\"d-flex align-content-start flex-wrap p-1 \">\
		<div class=\"form-group\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label "+(v.vis_ord_num == -1?'d-none':'')+"\" id=\'lbl_"+v.label+"\' for=\""+v.label+"\" style=\"text-transform:capitalize;\">"+(v.title == null?v.label:v.title)+"</label>\
		<input type=\"number\" class=\"form-control "+(v.vis_ord_num == -1?'d-none':'')+"\" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" onBlur=validate_field(\""+v.label+"\") onChange=validate_field(\""+v.label+"\")>\
		</div>\
		</div>";
	}
};

// OBJ DATE PICKER
var date_obj={
	_data:{},
	_screen:{},
	create:function(val){
		this._data = val;
		var obj = Object.create(this);
		obj.set();
		return obj; 	
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set: function(){
		var v = this._data;
		this._screen = 
		"<div class=\"d-flex align-content-start flex-wrap p-1\">\
		<div class=\"form-group\" id=\"fg_"+v.label+"\">\
		<label for=\""+v.label+"\">"+v.title+"</label>\
		<div class='input-group date' >\
		<input type='text' class=\"form-control\" id=\""+v.label+"\" placeholder=\"Selecciona una fecha\" />\
		<span class=\"input-group-append \">\
		<span class=\"input-group-text\"><span class=\"icon ion-md-calendar \"></span></span>\
		</span>\
		</div>\
		<script type=\"text/javascript\">$(function () { $('#"+v.label+"').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>\
		</div>\
		</div>";
	}
};	


const select_obj_by_name = {
	_sn:'',
	create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o; 	
	},
	get_screen:function(){return this._sn},
	set: function(v){
		let c = "<div class=\"d-flex align-content-start flex-wrap p-1 \"><div class=\"form-group\">";
		c += (v.hasOwnProperty('title')?"<label for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>":"");
		c += " <select class=\"form-control\" style=\'width: 7em;\' id=\""+v.label+"\" onChange=front_call({method:\'"+v.method+"\',data:{id:\'"+v.id+"\',value:this.value}})><option value=''>Selecciona</option>";
		const x = v.label;
		if(TOP.hasOwnProperty('selects')){
			if(TOP.selects[x] != undefined){
				for (let i = 0; i < TOP.selects[x].length; i++) {
					let sl = (TOP.selects[x][i].lbl == v.value)?"selected=\"selected\"":"";
					c += "<option "+sl+" > "+TOP.selects[x][i].lbl+"</option>";	
				}
			}	
		}
		c +="</select></div></div>";
		this._sn = c;
	}
}


// OBJ SELECTOR 
var select_obj = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen ="<div class=\"d-flex align-content-start flex-wrap p-1 \">\
		<div class=\"form-group\">\
		<label for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>\
		<select class=\"form-control\" id=\""+v.label+"\" onChange=check_select(\""+v.label+"\") ><option value='-1'>Selecciona</option>";
		var x = v.label;
		if(TOP.hasOwnProperty('selects')){
  	    		// console.log('selector_obj',TOP.selects)
  	    		if(TOP.selects[x] != undefined){
  	    			for (var i = 0; i < TOP.selects[x].length; i++) {
  	    				var sl = (TOP.selects[x][i].id == v.value)?"selected=\"selected\"":" ";
  	    				this._screen += "<option value="+TOP.selects[x][i].id+" "+sl+" > "+TOP.selects[x][i].lbl+"</option>";	
  	    			}
  	    		}	
  	    	}
  	    	this._screen +="</select></div></div>";
  	    }
  	}



// OBJ SELECTOR 
var checkbox_obj = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get_screen:function(){return this._screen},
	set: function(v){
		var x = "<div class=\"custom-control custom-checkbox\">";
		x += "<input type=\"checkbox\" class=\"custom-control-input\" id=\""+v.label+"\" "+(v.value == 'true'?'checked':'')+">";
		x += "<label class=\"custom-control-label\" for=\""+v.label+"\">"+v.title+"</label>";
		x += "</div>";
		this._screen = x;

	}
}


// ** CONTRATO
var dialog_contrato={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj; 	
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		var scr = "<div class=\"row\"><div class=\"col\" >";
		scr += date_obj.create({label:'date_contrato',title:'Fecha de inicio del contrato'}).get_screen();
		scr += "<hr>";
		scr += select_obj.create({label:'lt_disp',title:'Lote Numero'}).get_screen();	
		scr += select_obj.create({label:'clientes',title:'Comprador'}).get_screen();
		scr += select_obj.create({label:'financiacion',title:'Plan de Financiacion'}).get_screen();
		scr += "<div class=\"col d-none\" id=\"cnt_"+v.label+"\" >";
		scr += numebr_obj.create({label:'anticipo',title:'Anticipo $'}).get_screen();
		scr +="</div>"
		scr += "<hr>";
		scr += number_obj.create({label:'mto_cta_1',title:'Monto de cuota 1'}).get_screen();	
		scr += "</div></div>";	
		this._screen += scr;
	}

};

var dialog_new_atom = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		this._data = val;
		obj.set();
		return obj
	},
	get_screen:function(){return this._screen},
	set:function(){

	}
}

//  modl win create service
var dialog_new_service ={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		this._data = val;
		obj.set();
		return obj
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set:function(){
		console.log('dialog new service',this._data);
		var tx = '';//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();    
		tx += "<div class=\"row d-flex justify-content-start\">";
		
		this._data.struct.map(function(i){
			tx += "<div class=\"col-6 "+(i.label == 'anticipo' || i.label == 'cuentas'?'d-none':'')+" \" id=\"cnt_"+i.label+"\" >";
			tx += window[i.vis_elem_type+'_obj'].create(i).get_screen();
			tx +="</div>"
		});

		
		// tx += " \
		// 				<div class=\"form-group\" id=\"fg_srvc_select\">\
		// 	                <label for=\"srvc\">Servicio</label>\
		// 	                <select class=\"form-control\" id=\"srvc_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('srvs')+"</select>\
		// 	            </div>\
		// 	        </div>\
		// 		    <div class=\"col\">\
		// 		            <div class=\"form-group\" id=\"fg_srvc_monto\">\
		// 		                <label for=\"srvc_monto\">Monto</label>\
		// 		                <input type=\'number\' class=\"form-control\" id=\"srvc_monto\"\"><option value=''>Selecciona</option>\
		// 		                        "+this.fill_select('financ')+"</select>\
		// 		            </div>\
		// 		    </div>";

  //       tx +="<div class=\"row d-flex justify-content-center\">\
		// 			<div class=\"col\">\
		// 				<div class=\"form-group\" id=\"fg_srvc_select\">\
		// 	                <label for=\"srvc\">Servicio</label>\
		// 	                <select class=\"form-control\" id=\"srvc_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('srvs')+"</select>\
		// 	            </div>\
		// 	            <div class=\"form-group\" id=\"fg_financ_select\">\
		// 	                <label for=\"financ\">Financiacion</label>\
		// 	                <select class=\"form-control\" id=\"financ_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('financ')+"</select>\
		// 	            </div>\
		// 	        </div>\
		// 	     </div>";
		
		
		this._screen = tx;
	},
	
};



