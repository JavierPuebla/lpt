

function set_true(i){
	$('#fg_'+i).removeClass("is-invalid");
		$('#'+i).removeClass('is-invalid');
		$('#fg_'+i).addClass("is-valid");
		$('#'+i).addClass('is-valid');
		return true;
}

function set_false(i){

	$('#fg_'+i).removeClass("is-valid");
	$('#'+i).removeClass('is-valid');
	$('#fg_'+i).addClass("is-invalid");
	$('#'+i).addClass('is-invalid');
	TOP.curr_err_msg += 'El campo '+i+' tiene contenido no valido'
	return false;
}

//recibe el object label que es el id que puso obj create
function validate_not_empty_not_false(i){
	console.log('validating',i )
	if($('#'+i).val() !== '' && $('#'+i).val() != 0 && $('#'+i).val() != undefined){
		return set_true(i);
	}else{
		return set_false(i);
	}
}


//recibe el object label que es el id que puso obj create
function validate_field(i){
	console.log('validating',i ,($('#'+i).val() !== ''))
	if($('#'+i).val() !== ''){
		if (i.indexOf('dni') > -1 ) {
			return validate_num_minvalue(i,4000000);
		}else{
			return set_true(i);
		}
	}else{
		return set_false(i);
	}
}

function validate_negnum(i){
	console.log('validating num',i, ($('#'+i).val()> -1))
	if($('#'+i).val() !== '' && $('#'+i).val() > -1){
		return set_true(i);
	}else{
		return set_false(i);
	}
}


function validate_num_minvalue(i,minval){
	console.log('validating minimo num',i,$('#'+i).val(), minval)
	if(parseInt($('#'+i).val()) > minval){
		return set_true(i);
	}else{
		return set_false(i);
	}
}

//**** TO DEPRECATE
function validate_not_curr_month(i){
	let t = moment(new Date()).format("DD/MM/YYYY");
	if($('#'+i).val()!== '' && $('#'+i).val().substr(3,2) > t.substr(3,2) && $('#'+i).val().substr(6) >= t.substr(6)){
		return set_true(i);
	}else{
		return set_false(i);
	}
}
function validate_not_past_month(i){
	let t = new Date();
	curr_date = Date.parse($('#'+i).val().substr(3,2)+"/"+$('#'+i).val().substr(0,2)+"/"+$('#'+i).val().substr(6))
	if($('#'+i).val()!== '' && curr_date >= t){

		return set_true(i);
	}else{
		console.log('Validate not last month', 'false',curr_date)
		return set_false(i);
	}
}

function validate_not_past_month_limit_1(i){
	let t = new Date();
	dtt = (t.getMonth()+1)+"/"+t.getDate()+"/"+t.getFullYear();
	hoy = Date.parse(dtt);
	indate = $('#'+i).val().substr(3,2)+"/"+$('#'+i).val().substr(0,2)+"/"+$('#'+i).val().substr(6);
	curr_date = Date.parse(indate)
	limit_date = new Date().setMonth(t.getMonth()+1);

	if($('#'+i).val()!== '' && curr_date >= hoy && curr_date <= limit_date){
		return set_true(i);
	}else{
		return set_false(i);
	}
}

//**** update 18 de agosto
function validate_select(i){
	// console.log('validating select',i, ($('#'+i).val() !== '' && $('#'+i).val() !== -1))
	if($('#'+i).val() !== '' && $('#'+i).val() !== -1){
		return set_true(i);
	}else{
		return set_false(i);
	}
}


// recibe el valor formateado en i
function validate_fecha_ddmmYYY(lbl){
	let i = $('#'+lbl).val();
	let dia = parseInt(i.substr(0,2))
	let diaOk = (dia > 0 && dia <=31?true:false);
	let mes = parseInt(i.substr(3,2))
	let mesOk = (mes > 0 && mes <=12?true:false);
	let anio = parseInt(i.substr(6))
	let anioOk = (anio > 0 && anio <=12?true:false);
	if(dia && mes && anio){
		return set_true(lbl);
	}else{
		return set_false(lbl);
	}
}




function validate_combo(e,o){
	let r = true;
	switch(e){
		case 'new_contrato':
		if(parseInt($('#cant_ctas_ciclo_2').val()) > parseInt($('#cant_ctas').val())){
			set_false('cant_ctas_ciclo_2');
			set_false('cant_ctas');
			r = false;
		}
		// if(parseInt($('#cant_ctas_ciclo_2').val()) > 0 && parseInt($('#indac').val()) <= 0){
		// 	set_false('cant_ctas_ciclo_2');
		// 	set_false('indac');
		// 	r = false;
		// }
		// if(parseInt($('#cant_ctas_ciclo_2').val()) > 0 && parseInt($('#indac').val()) > 0 && parseInt($('#frecuencia_indac').val()) <= 0){
		// 	set_false('cant_ctas_ciclo_2');
		// 	set_false('indac');
		// 	set_false('frecuencia_indac');
		// 	r = false;
		// }
		if(parseInt($('#indac').val()) > 0 && parseInt($('#frecuencia_indac').val()) <= 0){
			set_false('frecuencia_indac');
			set_false('indac');
			r = false;
		}
		// if(parseInt($('#cant_ctas_ciclo_2').val()) == 120 &&  parseInt($('#cant_ctas').val()) != 156 ){
		// 	set_false('cant_ctas_ciclo_2');
		// 	set_false('cant_ctas');
		// 	r = false;
		// }
		// if(parseInt($('#cant_ctas_ciclo_2').val()) == 150 && parseInt($('#cant_ctas').val()) != 198){
		// 	set_false('cant_ctas_ciclo_2');
		// 	set_false('cant_ctas');
		// 	r = false;
		// }

		break;
	}
	return r;
}


function validate_click(e,o){
	switch(e){
		case 'pgc':
			var res = true;
			if(TOP.selected.length == 0 && $('#monto_recibido').val() == 0){
				TOP.curr_err_msg += "Debes seleccionar pagos o ingrersar un monto en $";
				var res = false;
			}
			return res;
		break;
		case 'ctr' :
			let nc_tst = o.map(function(i){
				if($('#'+i).prop('type') === 'text'){
					return validate_field(i)
				}else{

					return validate_negnum(i);
				}
			});
			return (nc_tst.indexOf(false) == -1 ? true : false);
		break;
		case 'update_plan' :
			if($('#indac').val() == 0 && validate_field('update_plan_fec_prox_venc')){
				return true;
			}
			let upf_tst = o.map(function(i){
				if(i === 'update_plan_fec_prox_venc'){
					return validate_not_past_month_limit_1(i);
				}
				if($('#'+i).prop('type') !== 'number'){
					return validate_field(i)
				}else{
					return validate_negnum(i);
				}
			});
			return (upf_tst.indexOf(false) == -1 ? true : false);
		break;

		case 'crude':
			// var t = o.map(function(i){
			// 	return validate_field(i.label)
			// });
			// if(t.indexOf(false) == -1){

			var x = (validate_field('nombre') && validate_field('apellido') && validate_field('dni') && validate_field('cuit'));
			// console.log('validate',x)
			return x;
			// if(){
			// 	return true
			// }else{
			// 	return false
			// }
		break;
		case 'reg_op':
			var t = o.map(function(i){
				return validate_field(i)
			});
			var t2 = o.map(function(i){
				if($('#'+i).prop('type') == 'number'){
					return validate_negnum(i)
				}
			});
			if(t.indexOf(false) == -1 && t2.indexOf(false) == -1 ){
				return true
			}else{
				return false
			}
		break;
		case 'pase_caja':
			not_epty_fields_res = (o.map(function(i){return validate_field(i)}).indexOf(false) == -1 ? true : false)
			not_negative_num_fields_res = (o.map(function(i){if($('#'+i).prop('type') == 'number'){return validate_negnum(i)}}).indexOf(false) == -1 ? true : false)
			not_same_account = (($('#'+o[0]).val() == $('#'+o[1]).val())?set_false([o[0],o[1]]):set_true([o[0],o[1]]));
			same_amount = (($('#'+o[2]).val() != $('#'+o[3]).val())?set_false([o[2],o[3]]):set_true([o[2],o[3]]));

			console.log('t:',$('#'+o[2]).val(),$('#'+o[3]).val())
			// console.log('res',not_same_account,not_same_amount);


			return (not_epty_fields_res && not_negative_num_fields_res && not_same_account && same_amount )

			// let t_cnta = false;
			// let t_mto = false;
			// var t =
			// var t2 = ;
			// if}
			// if(o[2] == o[3]){t_mto = set_false([o[2],o[3]]);}else{t_mto = set_true([o[2],o[3]])}
			// if(t_cnta && t_mto && t && t2){
			// 	return true
			// }else{
			// 	return false
			// }
		break;
		//**** LAST VERSION
		case 'new_contrato':
 			return (o.map(function(i){
					if($('#'+i).prop('type') === 'text'){
						return validate_field(i)
					}else{
						return validate_negnum(i);
					}
				}).indexOf(false) == -1 ? true : false);
		break;
		case 'new_service_elm':
			let ns_tst = o.map(function(i){
				if($('#'+i).prop('type') === 'text'){
					return validate_field(i)
				}else{
					return validate_negnum(i);
				}
			});
			return (ns_tst.indexOf(false) == -1 ? true : false);
		break;
		case 'save_edit':
					let sed_tst = o.map(function(i){
						console.log('validate',i);
						let elm_type = $('#'+i).prop('type');
						if( elm_type === 'text' ){
							return validate_not_empty_not_false(i)
						}else if(elm_type === 'number'){
							return validate_negnum(i);
						}
						else if(elm_type === 'date'){
							return validate_fecha(i);
						}
						else if (i.indexOf('dni') > -1 ) {
							return validate_num_minvalue(i,4000000);
						}
					});
			return (sed_tst.indexOf(false) == -1 ? true : false);
		break;

		case 'new_rev':
			if($('#asignado_a').val() != '' && $('#rev_coment').val() != ''){return true}else{return false};
		break;
		case 'rescision':
			return v_data(o)
		break;
	}

}

// update 30 junio 2020
// VALIDA UN BOJETO QUE PASA LOS VISUAL ELEMENTS
// EN EL FORMATO EN QUE LOS ENVIA EL CONTROLLER
// 0: {label: "fec_ini", value: "20/03/2018", title: "Fecha de Boleto", vis_elem_type: "date", vis_ord_num: "1"}
function validate_object(o){
	let rx = true;
	for (var i = 0; i < o.length; i++) {
		if(o[i]['vis_elem_type'] === 'text'){
			x = validate_field(o[i]['label']);
		}
		if(o[i]['vis_elem_type'] === 'date'){
			x = validate_fecha_ddmmYYY(o[i]['label']);
		}
		if(o[i]['vis_elem_type'] === 'select'){
			x = validate_select(o[i]['label']);
		}
		//****  SI ALGUNO DE LOS TESTEADOS DA FALSE RETORNA EL FALSE
		if(!x){rx = x}
	}
	return rx;
}

// RECORRE LOS ITEMS DE UN OBJ Y LOS VALIDA
function v_data(o){
	let tst = Object.keys(o).map(function(i){

		if($('#'+i).prop('type') === 'text'){
			return validate_field(i)
		}else{
			return validate_negnum(i);
		}
	});
	return (tst.indexOf(false) == -1 ? true : false);
}

function validate_cctos(itm_id){
    var err = false;

    // console.log('ccd',$('#percent_cctos_'+TOP.count_centro_costos_list).val())
    if(itm_id != '' && $("#"+itm_id).val() > 100 ){
    	err = true
    }else{
    	// CURRENT SUM CRS NO PUEDE SER MAYOR A 100
	    var crs = 0;
	    TOP.selected_ccd = [];
	    // ADEMAS DE VALIDAR, CONSTRUYO EL ARRAY PARA ENVIAR A GUARDAR
	    // EN CONTAB_CC_DISTRIB LA DISTRIBUCION DEL ASIENTO SEGUN CENTRO DE COSTOS

	    for(i = 1; i <= TOP.count_centro_costos_list;i++){
	        if($('#percent_cctos_'+i).val() != ''){
	            TOP.selected_ccd.push({
	            	'barrio_id':$('#cent_ctos_'+i).val(),
	            	'percent':$('#percent_cctos_'+i).val()
	        	});
	            crs = crs + parseInt($('#percent_cctos_'+i).val());
	        }
	    }
    };
    if(crs != 100){err =true};
    if(err){
    	myAlert({
    		tit:'Registro de Operaciones',
    		msg:'El total de la distribución del monto entre centros de costo, debe ser 100% Revisa los porcentajes de distribución',
    		type:'danger',
    		container:'modal',
    		win_close_method:'self'
    	});
    	return false;
    }else{
    	return true;
    }
}
